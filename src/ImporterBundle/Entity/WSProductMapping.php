<?php

namespace ImporterBundle\Entity;

class WSProductMapping
{
    const WS_SIMPLE_FIELDS = array(
        'id_manufacturer' => 'getIdManufacturer',
        'id_category_default' => 'getIdCategoryDefault',
        'reference' => 'getReference',
        'supplier_reference' => 'getSupplierReference',
        'width' => 'getWidth',
        'height' => 'getHeight',
        'depth' => 'getDepth',
        'weight' => 'getWeight',
        'price' => 'getPrice',
        'active' => 'getActive',
        'ean13' => 'getWan13',
        'id_tax_rules_group' => 'getIdTaxRulesGroup',
        'unity' => 'getUnity',
        'id_supplier' => 'getIdSupplier',
    );

    const WS_COMPOSED_FIELDS = array(
        'categories' => 'getCategories',
        'tags' => 'getTags',
    );

    const WS_INTERNATIONALISED_FIELDS = array(
        'name' => 'getName',
        'description' => 'getDescription',
        'description_short' => 'getDescriptionShort',
        'link_rewrite' => 'getLinkRewrite',
        'meta_description' => 'getMetaDescription',
        'meta_keywords' => 'getMetaKeywords',
        'meta_title' => 'getMetaTitle',
    );

    const WS_EMPTY_FIELDS = array(
        'cache_default_attribute',
        'quantity_discount',
        'location',
        'upc',
        'cache_is_pack',
        'cache_has_attachments',
        'is_virtual',
        'on_sale',
        'online_only',
        'ecotax',
        'wholesale_price',
        'unit_price_ratio',
        'additional_shipping_cost',
        'customizable',
        'text_fields',
        'uploadable_files',
        'available_date',
        'condition',
        'date_add',
    );

    const WS_DEFAULT_FIELDS = array(
        'cache_default_attribute',
        'quantity_discount',
        'location',
        'upc',
        'cache_is_pack',
        'cache_has_attachments',
        'is_virtual',
        'on_sale',
        'online_only',
        'ecotax',
        'wholesale_price',
        'unit_price_ratio',
        'additional_shipping_cost',
        'customizable',
        'text_fields',
        'uploadable_files',
        'available_date',
        'condition',
        'date_add',
    );

    const WS_OTHER_FIELDS = array(
        'quantity' => 'getQuantity',
        'images' => 'getImages',
        'features' => 'getFeatures',
    );
}