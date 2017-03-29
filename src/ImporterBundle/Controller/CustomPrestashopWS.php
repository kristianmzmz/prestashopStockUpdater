<?php

namespace ImporterBundle\Controller;

use ImporterBundle\Entity\Product;
use ImporterBundle\Entity\Stock;
use PrestaShopWebservice;
use PrestaShopWebserviceException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Output\OutputInterface;

class CustomPrestashopWS extends PrestaShopWebservice
{
    const CREATE_ACTION = 'create';
    const UPDATE_ACTION = 'update';
    const ERROR_ACTION = 'error';

    const PRODUCT = 1;

    const INTERNATIONALISED_FIELDS = [
        'name' => 'getName',
        'description' => 'getDescription',
        'description_short' => 'getDescriptionShort',
        'link_rewrite' => 'getLinkRewrite',
        'meta_title' => 'getMetaTitle',
        'meta_description' => 'getMetaDescription',
        'meta_keywords' => 'getMetaKeywords',
    ];

    /**
     * @param int                  $productId
     * @param OutputInterface|null $output
     *
     * @return string
     */
    public function getActionType($productId, $output)
    {
        try {
            // Check if this product is in prestashop to create or update
            $this->get(array("resource" => "products", "id" => $productId));

            // If the call works, it will reach this point.
            // It means we found the product, we can now update it.
            return self::UPDATE_ACTION;

        } catch (PrestaShopWebserviceException $e) {
            // Here we are dealing with errors
            $trace = $e->getTrace();

            // If the product is not found, we'll create it
            if ($trace[0]['args'][0] == 404) {
                return self::CREATE_ACTION;
            } else {
                // Other kind of errors
                if ($output instanceof OutputInterface) {
                    if ($trace[0]['args'][0] == 401) {
                        $output->write('Parametros incorrectos, imposible autenticarse.', true);
                    } else {
                        $output->write('Error desconocido: ' . $e->getMessage(), true);
                    }
                }
            }
        }

        return self::ERROR_ACTION;
    }

    /**
     * Creates a product in the prestashop site.
     *
     * @param Product $product
     *
     * @return Product|bool
     */
    public function createProduct(Product $product)
    {
        // If there's no data...
        if (!$wsProduct = $this->convertProduct($product)) {
            return false;
        }

        // Update the product!
        $result = $this->add(
            array(
                "resource" => "products",
                "postXml" => $wsProduct->asXML(),
            )
        );

        if ($result) {
            $product->setRealProductId(
                (int)$result->children()->children()->id
            );

            // Now we can create the images, and update the product
            if ($defaultImageId = $this->uploadImages($product)) {
                $product->setDefaultImageId($defaultImageId);
                $this->updateProduct($product);
            }

            return $product;
        }

        return false;
    }

    /**
     * @param Product $product
     *
     * @return bool|Product
     */
    public function updateProduct(Product $product)
    {
        // If there's no data...
        if (!$wsProduct = $this->convertProduct($product, true)) {
            return false;
        }

        // Update the product!
        $result = $this->edit(
            array(
                "resource" => "products",
                "id" => $product->getIdProduct(),
                "putXml" => $wsProduct->asXML(),
            )
        );

        if ($result) {
            $product->setRealProductId(
                (int)$result->children()->children()->id
            );

            return $product;
        }

        return false;
    }


    /**
     * @param Stock $productStock
     *
     * @return bool|Stock
     */
    public function updateStock(Stock $productStock)
    {
        // If there's no data...
        return $this->getStockAvailableAndUpdate($productStock);
    }

    /**
     * Converts the Schema product from prestashop, to a new one with the Database data
     *
     * @param Product $product
     * @param bool    $update
     *
     * @return \SimpleXMLElement
     */
    public function convertProduct(Product $product, $update = false)
    {
        $apiLanguagesUrl = $this->url . '/api/languages/';

        $productSchema = $this->get(array('url' => $this->url . '/api/products?schema=blank'));

        $resources = $productSchema->children()->children();

        unset($resources->id);
        unset($resources->position_in_category);
        unset($resources->id_shop_default);
        unset($resources->date_add);
        unset($resources->date_upd);
        unset($resources->associations->combinations);
        unset($resources->associations->product_options_values);
        unset($resources->associations->product_features);
        unset($resources->associations->stock_availables->stock_available->id_product_attribute);

        if ($update) {
            // Get the current product, to store the current data.
            $productUpdate = $this->get(array('resource' => 'products', 'id' => $product->getIdProduct()));

            $productResources = $productUpdate->children()->children();
            $resources->id = $product->getIdProduct();
            if (!isset($resources->id_default_image) || $resources->id_default_image == '') {
                $resources->id_default_image = $product->getDefaultImageId();
            } else {
                $resources->id_default_image = $productResources->id_default_image;

            }
        } else {
            $resources->id_default_image = 1;
            $resources->advanced_stock_management = 0;
            $resources->available_for_order = 1;
            $resources->id_default_combination = 0;
            $resources->indexed = 0;
            $resources->new = 0;
            $resources->show_price = 1;
            $resources->visibility = 'both';
        }

        $resources->price = $product->getPrice();
        $resources->id_manufacturer = $product->getIdManufacturer();
        $resources->id_supplier = $product->getIdSupplier();
        $resources->id_category_default = $product->getIdCategoryDefault();
        $resources->id_tax_rules_group = $product->getIdTaxRulesGroup();
        $resources->reference = $product->getReference();
        $resources->supplier_reference = $product->getSupplierReference();
        $resources->width = $product->getWidth();
        $resources->height = $product->getHeight();
        $resources->depth = $product->getDepth();
        $resources->weight = $product->getWeight();
        $resources->ean13 = $product->getEan13();
        $resources->active = $product->isActive();

        if (!empty($product->getCategories())) {
            $categories = explode(',', $product->getCategories());

            foreach ($categories as $category) {
                $resources->associations->categories->addChild('category')->addChild('id', $category);
            }
        }

        if (!empty($product->getTags())) {
            $tags = explode(',', $product->getTags());

            foreach ($tags as $tag) {
                $resources->associations->tags->addChild('tags')->addChild('id', $tag);
            }
        }

        foreach (self::INTERNATIONALISED_FIELDS as $translatedField => $translatedMethod) {
            $node = dom_import_simplexml($resources->$translatedField->language[0][0]);
            $no = $node->ownerDocument;
            $node->appendChild($no->createCDATASection($product->$translatedMethod()));
            $resources->$translatedField->language[0][0] = $product->$translatedMethod();
            $resources->$translatedField->language[0][0]['id'] = $product->getIdLang();
            $resources->$translatedField->language[0][0]['xlink:href'] = $apiLanguagesUrl . $product->getIdLang();
        }

        return $productSchema;
    }

    /**
     * @param Stock $productStock
     *
     * @return bool
     */
    private function getStockAvailableAndUpdate(Stock $productStock){
        $productUpdate = $this->get(array('resource' => 'products', 'id' => $productStock->getIdProduct()));
        $productResources = $productUpdate->children()->children();
        foreach ($productResources->associations->stock_availables->stock_available as $item) {
            if (!$this->setProductQuantity($productStock, $item->id, $item->id_product_attribute)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Converts the Schema product from prestashop, to a new one with the Database data
     *
     * @param Stock $productStock
     *
     * @return bool|Stock
     */
    private function setProductQuantity(Stock $productStock, $stockId, $attributeId)
    {
        // Get the current product, to store the current data.
        $stockSchema = $this->get(array('url' => $this->url . '/api/stock_availables?schema=blank'));
        $resources = $stockSchema->children()->children();
        $resources->id = $stockId;
        $resources->id_product  = $productStock->getIdProduct();
        $resources->quantity = $productStock->getQuantity();
        $resources->id_shop = 1;
        $resources->out_of_stock=1;
        $resources->depends_on_stock = 0;
        $resources->id_product_attribute=$attributeId;

        // Update the product!
        $result = $this->edit(
            array(
                "resource" => "stock_availables",
                "id" => $stockId,
                "putXml" => $stockSchema->asXML(),
            )
        );

        return $result !== false;
    }

    /**
     * If default image is true, will return the Image Id, fi not, true or false.
     *
     * @param Product $product
     *
     * @return bool|string
     */
    private function uploadImages(Product $product)
    {
        $productImages = explode(',', $product->getImages());
        $defaultImageId = null;
        $productId = !empty($product->getRealProductId()) ? $product->getRealProductId() : $product->getIdProduct();
        foreach ($productImages as $productImage) {
            $result = $this->uploadImage($productId, $productImage);
            if (is_null($defaultImageId) && $result) {
                /** @var \SimpleXMLElement $defaultImage */
                $defaultImage = $result->children()->children();
                $defaultImageId = (string)$defaultImage->id;
            }
        }

        return !is_null($defaultImageId) ? $defaultImageId : false;
    }

    /**
     * @param int    $productId
     * @param string $imageUrl
     *
     * @return mixed
     */
    private function uploadImage($productId, $imageUrl = '')
    {
        if (!file_exists($imageUrl)) {
            return false;
        }

        $image = curl_file_create($imageUrl);
        $request = self::executeRequest(
            $this->url . "/api/images/products/" . $productId,
            array(
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('image' => $image),
            )
        );

        self::checkStatusCode($request['status_code']);

        return self::parseXML($request['response']);
    }
}