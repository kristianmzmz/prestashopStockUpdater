<?php

namespace ImporterBundle\Entity;

abstract class AbstractCsvMapping
{
    /** @var array */
    protected $csvColumnMapping = [];

    /** @var string */
    protected $csvPath;

    /**
     * CsvProductMapping constructor.
     *
     * @param string $csvPath
     */
    public function __construct($csvPath)
    {
        $this->csvPath = $csvPath;
    }

    /**
     * @return string
     */
    public function getCsvPath()
    {
        return $this->csvPath;
    }

    /**
     * @return array
     */
    public function getMapping()
    {
        return $this->csvColumnMapping;
    }
}