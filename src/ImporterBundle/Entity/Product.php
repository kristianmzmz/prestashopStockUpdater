<?php

namespace ImporterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 */
class Product
{
    /** @var integer */
    private $realProductId;

    /** @var integer */
    private $idProduct;

    /** @var integer */
    private $idLang = '1';

    /** @var string */
    private $name;

    /** @var string */
    private $description;

    /** @var string */
    private $descriptionShort;

    /** @var string */
    private $linkRewrite;

    /** @var string */
    private $metaDescription;

    /** @var string */
    private $metaKeywords;

    /** @var string */
    private $metaTitle;

    /** @var integer */
    private $idCategoryDefault;

    /** @var string */
    private $categories;

    /** @var string */
    private $reference;

    /** @var string */
    private $supplierReference;

    /** @var string */
    private $price = '0.000000';

    /** @var integer */
    private $idSupplier;

    /** @var integer */
    private $idManufacturer;

    /** @var integer */
    private $idShopDefault = '1';

    /** @var integer */
    private $idTaxRulesGroup = '1';

    /** @var string */
    private $ean13;

    /** @var integer */
    private $quantity = '0';

    /** @var string */
    private $unity;

    /** @var string */
    private $width = '0.000000';

    /** @var string */
    private $height = '0.000000';

    /** @var string */
    private $depth = '0.000000';

    /** @var string */
    private $weight = '0.000000';

    /** @var boolean */
    private $active = '1';

    /** @var boolean */
    private $updated = '1';

    /** @var string */
    private $tags;

    /** @var string */
    private $images;

    /** @var string */
    private $features;

    /** @var string */
    private $defaultImageId;

    /**
     * @return string
     */
    public function getDefaultImageId()
    {
        return $this->defaultImageId;
    }

    /**
     * @param string $defaultImageId
     */
    public function setDefaultImageId($defaultImageId)
    {
        $this->defaultImageId = $defaultImageId;
    }

    /**
     * @return int
     */
    public function getIdProduct()
    {
        return $this->idProduct;
    }

    /**
     * @param int $idProduct
     *
     * @return $this
     */
    public function setIdProduct($idProduct)
    {
        $this->idProduct = $idProduct;

        return $this;
    }

    /**
     * @return int
     */
    public function getIdLang()
    {
        return $this->idLang;
    }

    /**
     * @param int $idLang
     *
     * @return $this
     */
    public function setIdLang($idLang)
    {
        $this->idLang = $idLang;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescriptionShort()
    {
        return $this->descriptionShort;
    }

    /**
     * @param string $descriptionShort
     *
     * @return $this
     */
    public function setDescriptionShort($descriptionShort)
    {
        $this->descriptionShort = $descriptionShort;

        return $this;
    }

    /**
     * @return string
     */
    public function getLinkRewrite()
    {
        return $this->linkRewrite;
    }

    /**
     * @param string $linkRewrite
     *
     * @return $this
     */
    public function setLinkRewrite($linkRewrite)
    {
        $this->linkRewrite = $linkRewrite;

        return $this;
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaDescription
     *
     * @return $this
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * @param string $metaKeywords
     *
     * @return $this
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * @param string $metaTitle
     *
     * @return $this
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * @return int
     */
    public function getIdCategoryDefault()
    {
        return $this->idCategoryDefault;
    }

    /**
     * @param int $idCategoryDefault
     *
     * @return $this
     */
    public function setIdCategoryDefault($idCategoryDefault)
    {
        $this->idCategoryDefault = $idCategoryDefault;

        return $this;
    }

    /**
     * @return string
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param string $categories
     *
     * @return $this
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     *
     * @return $this
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return string
     */
    public function getSupplierReference()
    {
        return $this->supplierReference;
    }

    /**
     * @param string $supplierReference
     *
     * @return $this
     */
    public function setSupplierReference($supplierReference)
    {
        $this->supplierReference = $supplierReference;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $price
     *
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return int
     */
    public function getIdSupplier()
    {
        return $this->idSupplier;
    }

    /**
     * @param int $idSupplier
     *
     * @return $this
     */
    public function setIdSupplier($idSupplier)
    {
        $this->idSupplier = $idSupplier;

        return $this;
    }

    /**
     * @return int
     */
    public function getIdManufacturer()
    {
        return $this->idManufacturer;
    }

    /**
     * @param int $idManufacturer
     *
     * @return $this
     */
    public function setIdManufacturer($idManufacturer)
    {
        $this->idManufacturer = $idManufacturer;

        return $this;
    }

    /**
     * @return int
     */
    public function getIdShopDefault()
    {
        return $this->idShopDefault;
    }

    /**
     * @param int $idShopDefault
     *
     * @return $this
     */
    public function setIdShopDefault($idShopDefault)
    {
        $this->idShopDefault = $idShopDefault;

        return $this;
    }

    /**
     * @return int
     */
    public function getIdTaxRulesGroup()
    {
        return $this->idTaxRulesGroup;
    }

    /**
     * @param int $idTaxRulesGroup
     *
     * @return $this
     */
    public function setIdTaxRulesGroup($idTaxRulesGroup)
    {
        $this->idTaxRulesGroup = $idTaxRulesGroup;

        return $this;
    }

    /**
     * @return string
     */
    public function getEan13()
    {
        return $this->ean13;
    }

    /**
     * @param string $ean13
     *
     * @return $this
     */
    public function setEan13($ean13)
    {
        $this->ean13 = $ean13;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     *
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return string
     */
    public function getUnity()
    {
        return $this->unity;
    }

    /**
     * @param string $unity
     *
     * @return $this
     */
    public function setUnity($unity)
    {
        $this->unity = $unity;

        return $this;
    }

    /**
     * @return string
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param string $width
     *
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param string $height
     *
     * @return $this
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return string
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @param string $depth
     *
     * @return $this
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param string $weight
     *
     * @return $this
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     *
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isUpdated()
    {
        return $this->updated;
    }

    /**
     * @param boolean $updated
     *
     * @return $this
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param string $tags
     *
     * @return $this
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return string
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param string $images
     *
     * @return $this
     */
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * @return string
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * @param string $features
     *
     * @return $this
     */
    public function setFeatures($features)
    {
        $this->features = $features;

        return $this;
    }

    /**
     * @return int
     */
    public function getRealProductId()
    {
        return $this->realProductId;
    }

    /**
     * @param int $realProductId
     */
    public function setRealProductId($realProductId)
    {
        $this->realProductId = $realProductId;
    }
}

