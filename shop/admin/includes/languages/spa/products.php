<?php
/* ----------------------------------------------------------------------
   $Id: products.php,v 1.3 2007/06/13 17:02:38 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: categories.php,v 1.19 2002/08/17 09:43:33 project3000
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ---------------------------------------------------------------------- */


define('TEXT_NEW_PRODUCT', 'Nuevo Producto en &quot;%s&quot;');
define('TEXT_PRODUCTS', 'Productos:');
define('TEXT_PRODUCTS_PRICE_INFO', 'Precio:');
define('TEXT_PRODUCTS_TAX_CLASS', 'Tipo Impuesto:');
define('TEXT_PRODUCTS_AVERAGE_RATING', 'Evaluacion Media:');
define('TEXT_PRODUCTS_QUANTITY_INFO', 'Cantidad:');
define('TEXT_DATE_ADDED', 'Añadido el:');
define('TEXT_DATE_AVAILABLE', 'Fecha Disponibilidad:');
define('TEXT_LAST_MODIFIED', 'Modificado el:');
define('TEXT_IMAGE_NONEXISTENT', 'NO EXISTE IMAGEN');
define('TEXT_PRODUCT_MORE_INFORMATION', 'Si quiere mas informacion, visite la <a href="http://%s" target="blank"><u>pagina</u></a> de este producto.');
define('TEXT_PRODUCT_DATE_ADDED', 'Este producto fue añadido el %s.');
define('TEXT_PRODUCT_DATE_AVAILABLE', 'Este producto estara disponible el %s.');

define('TEXT_TAX_INFO', ' ex VAT:');
define('TEXT_PRODUCTS_LIST_PRICE', 'List:');
define('TEXT_PRODUCTS_DISCOUNT_ALLOWED', 'Max Discount Allowed:');

define('TEXT_PRODUCTS_BASE_PRICE', 'Base Price ');
define('TEXT_PRODUCTS_BASE_UNIT', 'Base Unit:');
define('TEXT_PRODUCTS_BASE_PRICE_FACTOR', 'Factor to calculate Base Price:');
define('TEXT_PRODUCTS_BASE_QUANTITY', 'Base Quantity:');
define('TEXT_PRODUCTS_PRODUCT_QUANTITY', 'Product Quantity:');
define('TEXT_PRODUCTS_DECIMAL_QUANTITY', 'Decimal Quantity');
define('TEXT_PRODUCTS_UNIT', 'Product Unit');

define('TEXT_PRODUCTS_IMAGE_REMOVE', '<b>Remove</b> this Image from this Product?');
define('TEXT_PRODUCTS_IMAGE_DELETE', '<b>Delete</b> this Image from the Server?');
define('TEXT_PRODUCTS_ZOOMIFY', 'Zoomify');

define('TEXT_PRODUCTS_STATUS', 'Estado de los Productos:');
define('TEXT_CATEGORIES', 'Categorias:');
define('TEXT_PRODUCTS_DATE_AVAILABLE', 'Fecha Disponibilidad:');
define('TEXT_PRODUCT_NOT_AVAILABLE', 'Agotado');
define('TEXT_PRODUCTS_MANUFACTURER', 'Fabricante del producto:');
define('TEXT_PRODUCTS_NAME', 'Nombre del Producto:');
define('TEXT_PRODUCTS_DESCRIPTION', 'Descripcion del producto:');
define('TEXT_EDIT_CATEGORIES_DESCRIPTION_META', 'Descripción de la categoría para la ETIQUETA de la descripción(máximo 250 letras)');
define('TEXT_EDIT_CATEGORIES_KEYWORDS_META', 'Categoría de las palabras de búsqueda para la ETIQUETA de la palabraclave (de las referencias máximo cerca commaseparately -. 250 letras)');
define('TEXT_PRODUCTS_QUANTITY', 'Cantidad:');
define('TEXT_PRODUCTS_REORDER_LEVEL', 'Products Reorder Level:');
define('TEXT_PRODUCTS_MODEL', 'Modelo:');
define('TEXT_PRODUCTS_EAN', 'EAN :');
define('TEXT_PRODUCTS_IMAGE', 'Imagen:');
define('TEXT_PRODUCTS_URL', 'URL del Producto:');
define('TEXT_PRODUCTS_URL_WITHOUT_HTTP', '<small>(sin http://)</small>');
define('TEXT_PRODUCTS_PRICE', 'Precio:');
define('TEXT_PRODUCTS_WEIGHT', 'Peso:');
define('TEXT_PRODUCTS_SORT_ORDER', 'Sort Order:');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Error: Can not link products in the same category.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Error: No se puede escribir en el directorio de imagenes del catalogo: ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Error: No existe el directorio de imagenes del Catalogo: ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);
?>
