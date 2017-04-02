<?php

namespace ImporterBundle\Controller;

use ImporterBundle\Entity\AbstractCsvMapping;
use ImporterBundle\Entity\Product;
use ImporterBundle\Entity\ProductRepository;
use ImporterBundle\Entity\Stock;
use ImporterBundle\Entity\StockRepository;
use League\Csv\Reader;

class CsvProductProvider
{
    /** @var string|null */
    private $csvPath;

    /** @var Reader|null */
    private $csvReader;

    /** @var array */
    private $csvMapping;

    /** @var ProductRepository */
    private $productRepository;

    /** @var string */
    private $localPathImages;

    /** @var StockRepository */
    private $stockRepository;

    /**
     * CsvProductProvider constructor.
     *
     * @param ProductRepository  $productRepository
     * @param StockRepository    $stockRepository
     * @param AbstractCsvMapping $csvMapping
     * @param string             $localPathImages
     */
    public function __construct(
        ProductRepository $productRepository,
        StockRepository $stockRepository,
        AbstractCsvMapping $csvMapping,
        $localPathImages
    ) {
        $this->csvPath = $csvMapping->getCsvPath();
        $this->csvMapping = $csvMapping->getMapping();
        $this->productRepository = $productRepository;
        $this->stockRepository = $stockRepository;
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
     * @param array $csvRow
     *
     * @return Stock
     */
    public function processCSVStockRow($csvRow)
    {
        return $this->createStockFormRowData($csvRow);
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

    /**
     * Returns the Product object with the data set from the CSV row
     *
     * @param $csvRowData
     *
     * @return Stock
     */
    private function createStockFormRowData($csvRowData)
    {
        $stock = new Stock();
        foreach ($this->csvMapping as $columnKey => $columnName) {
            $methodName = Utils::getSetterName($columnName);

            $value = Utils::cleanValue($csvRowData[$columnKey]);

            $stock->$methodName($value);
        }

        return $stock;
    }

    /**
     * Returns the Product object with the data set from the CSV row
     *
     * @param $csvRowData
     *
     * @return Product
     */
    private function createProductFormRowData($csvRowData)
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
}