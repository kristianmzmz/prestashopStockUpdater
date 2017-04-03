<?php

namespace ImporterBundle\Controller;

use ImporterBundle\Entity\AbstractCsvMapping;
use ImporterBundle\Entity\Stock;
use League\Csv\Reader;

class CsvStockProvider
{
    /** @var string|null */
    private $csvPath;

    /** @var Reader|null */
    private $csvReader;

    /** @var array */
    private $csvMapping;

    /**
     * CsvProductProvider constructor.
     *
     * @param AbstractCsvMapping $csvMapping
     */
    public function __construct(AbstractCsvMapping $csvMapping) {
        $this->csvPath = $csvMapping->getCsvPath();
        $this->csvMapping = $csvMapping->getMapping();

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
}