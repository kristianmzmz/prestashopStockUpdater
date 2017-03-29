Requirements
===========

- Composer
- Local MySql database
- PHP >5.6

Installation
============
- Composer install 
- Fill the parameters

Commands:
========
- Launch in command line --> ***php app/console importer:import_products***


Notes 
=====
- If you have problems with the authorization add this to your .htaccess
 
```
<IfModule mod_rewrite.c>
RewriteEngine on
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
</IfModule>
```
