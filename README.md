Description
===========
Given a CSV with the proper columns, it will update or create a product inside Prestashop using the Webservice API:

The definition of the fields can be found here: 
***\ImporterBundle\Entity\CsvOnlyStockMapping***

***\ImporterBundle\Entity\CsvProductMapping***


Requirements
===========

- Composer
- PHP >5.6

_Only for full product version_
- Local MySql database 

Installation
============
- Composer install 
- Fill the requested parameters 

_Only for full product version_
- Create database schema --> ***php doctrine:schema:create***

Commands:
========
- Update Stocks only --> ***php app/console stock:updater***
- Updates Full product --> ***php app/console importer:import_products***


Notes 
=====
- If you have problems with the authorization add this to your .htaccess
 
```
<IfModule mod_rewrite.c>
RewriteEngine on
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
</IfModule>
```
