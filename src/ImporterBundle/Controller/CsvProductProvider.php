<?php

namespace ImporterBundle\Controller;

use ImporterBundle\Entity\CsvProductMapping;
use ImporterBundle\Entity\Product;
use ImporterBundle\Entity\ProductRepository;
use League\Csv\Reader;

class CsvProductProvider
{
    /** @var string|null */
    private $csvPath;

    /** @var Reader|null */
    private $csvReader;

    /** @var CsvProductMapping */
    private $csvMapping;

    /** @var ProductRepository */
    private $productRepository;

    /** @var string */
    private $localPathImages;

    /**
     * CsvProductProvider constructor.
     *
     * @param ProductRepository $productRepository
     * @param CsvProductMapping $csvMapping
     * @param string            $localPathImages
     */
    public function __construct(ProductRepository $productRepository, CsvProductMapping $csvMapping, $localPathImages)
    {
        $this->csvPath = $csvMapping->getCsvPath();
        $this->csvMapping = $csvMapping->getMapping();
        $this->productRepository = $productRepository;
        $this->localPathImages = $localPathImages;

        if (is_null($this->csvPath)) {
            $this->csvReader = null;
        } else {
            $this->csvReader = Reader::createFromPath($this->csvPath);
            $this->csvReader->setDelimiter(';');
            $this->csvReader->setInputEncoding('ISO-8859-1');
            $this->csvReader->setOffset(1); //because we don't want to insert the header
        }
    }

    /**
     * @return array
     */
    public function getCSVRows()
    {
        if (is_null($this->csvReader)) {
            return [];
        }

        return $this->csvReader->fetchAll();
    }

    /**
     * Returns the Product object with the data set from the CSV row
     *
     * @param $csvRowData
     *
     * @return Product
     */
    public function createProductFormRowData($csvRowData)
    {
        $product = new Product();
        foreach ($this->csvMapping as $columnKey => $columnName) {
            $methodName = Utils::getSetterName($columnName);

            $value = Utils::cleanValue($csvRowData[$columnKey]);

            if ($methodName == 'setUpdated') {
                $value = empty($value) ? true : $value == "0";
            }

            if ($methodName == 'setImages') {
                $value = Utils::addUploadsPathToImage($value, $this->localPathImages);
            }

            if ($methodName == 'setLinkRewrite') {
                $value = Utils::cleanUrl($value);
            }

            $product->$methodName($value);
        }

        return $product;
    }

    /**
     * @param array $csvRow
     *
     * @return bool
     */
    public function processCSVRow($csvRow)
    {
        $product = $this->createProductFormRowData($csvRow);

        return $this->productRepository->createOrUpdateProduct($product);
    }
}