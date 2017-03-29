Requirements
===========

- Composer
- Local MySql database
- PHP >5.6

Installation
============
- Composer install 
- Fill the requested parameters 
- Create database schema --> ***php app/console stock:updater***

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
