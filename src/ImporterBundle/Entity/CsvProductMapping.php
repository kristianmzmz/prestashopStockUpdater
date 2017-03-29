<?php

namespace ImporterBundle\Entity;

class CsvProductMapping
{
    const CSV_COLUMN_MAPPING = array(
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
//        9999 => 'id_lang', //  Idioma
//        1 => 'active', // Activo (0/1)
//        1 => 'price_tex', // Precio sin IVA
//        1 => 'id_tax_rules_group', // ID regla de impuestos
//        1 => 'wholesale_price', // Precio al por mayor
//        1 => 'on_sale', // En rebaja (0/1)
//        1 => 'reduction_price', // Valor del descuento
//        1 => 'reduction_percent', // Porcentaje del descuento
//        1 => 'reduction_from', // Descuento desde (aaaa-mm-dd)
//        1 => 'reduction_to', // Descuento hasta (aaaa-mm-dd)
//        1 => 'supplier', // Proveedor
//        1 => 'manufacturer', // Fabricante
//        1 => 'ean13', // EAN13
//        1 => 'upc', // UPC
//        1 => 'ecotax', // Ecotasa
//        1 => 'minimal_quantity', // Cantidad mínima
//        1 => 'visibility', // Visible en
//        1 => 'additional_shipping_cost', // Coste adicional del envío
//        1 => 'unity', // Unidad para el precio unitario
//        1 => 'unit_price', // Precio unitario
//        1 => 'available_now', // Texto para cuando está disponible
//        1 => 'available_later', // Texto para cuando se permiten pedidos en espera
//        1 => 'available_for_order', // Disponible para pedidos (0 = No, 1 = Si)
//        1 => 'available_date', // Fecha de disponibilidad del producto
//        1 => 'date_add', // Fecha de creación del producto
//        1 => 'show_price', // Mostrar Precio (0 = No, 1 = Si)
//        1 => 'delete_existing_images', // Elimine las imágenes existentes (0 = no, 1 = si)
//        1 => 'online_only', // Solo disponible por Internet (0 = No, 1 = Si)
//        1 => 'condition', // Condición
//        1 => 'customizable', // Personalizable (0 = No, 1 = Yes)
//        1 => 'uploadable_files', // Se pueden subir ficheros (0 = No, 1 = Yes)
//        1 => 'text_fields', // Campos de textos (0 = No, 1 = Yes)
//        1 => 'out_of_stock', // Cuando no haya existencias
//        1 => 'shop', // ID / Nombre de la tienda
//        1 => 'advanced_stock_management', // Gestor avanzado de inventario
//        1 => 'depends_on_stock', // Dependiendo del stock
//        1 => 'warehouse', // Almacén
    );

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
        return self::CSV_COLUMN_MAPPING;
    }
}