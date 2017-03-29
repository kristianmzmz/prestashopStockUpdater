<?php

namespace ImporterBundle\Entity;

class CsvOnlyStockMapping extends AbstractCsvMapping
{
    /** @var array */
    protected $csvColumnMapping = array(
        0 => 'id_product', //  Id
        1 => 'quantity', // Cantidad
    );
}