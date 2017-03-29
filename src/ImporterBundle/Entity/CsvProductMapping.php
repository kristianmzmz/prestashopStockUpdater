<?php

namespace ImporterBundle\Entity;

class CsvProductMapping extends AbstractCsvMapping
{
    /** @var array */
    protected $csvColumnMapping = array(
        0 => 'id_product', //  Id
        3 => 'name', // Nombre
        22 => 'description', // Descripción
        21 => 'description_short', // Descripción corta
        27 => 'link_rewrite', // Enlace reescrit
        26 => 'meta_description', // Meta descripción
        25 => 'meta_keywords', // Meta palabras clave
        24 => 'meta_title', // Meta-título
        4 => 'id_category_default', // Categorías id
        5 => 'categories', // Categorías (x,y,z...)
        9 => 'reference', // Referencia nº
        8 => 'supplier_reference', // N° de referencia proveedor
        6 => 'price', // Precios con IVA
        10 => 'width', // Ancho
        11 => 'height', // Alto
        12 => 'depth', // Profundidad
        13 => 'weight', // Peso
        14 => 'quantity', // Cantidad
        23 => 'tags', // Etiquetas (x,y,z...)
        28 => 'images', // URL's de las imágenes (x,y,z...)
        20 => 'features', // Característica (Nombre:Valor:Posición:Personalizado)
        29 => 'updated', // Updated
    );
}