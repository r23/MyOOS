<?php
/* ----------------------------------------------------------------------
   $Id: products.php,v 1.4 2007/06/21 15:34:11 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: categories.php,v 1.24 2002/08/17 09:43:33 project3000
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

define('TEXT_NEW_PRODUCT', 'Nuovo Prodotto in &quot;%s&quot;');
define('TEXT_PRODUCTS', 'Prodotti:');
define('TEXT_PRODUCTS_PRICE_INFO', 'Prezzo:');
define('TEXT_PRODUCTS_TAX_CLASS', 'Tassa:');
define('TEXT_PRODUCTS_AVERAGE_RATING', 'Media Voti:');
define('TEXT_PRODUCTS_QUANTITY_INFO', 'Quantità:');
define('TEXT_DATE_ADDED', 'Data di aggiunta:');
define('TEXT_DATE_AVAILABLE', 'Data di disponibilità:');
define('TEXT_LAST_MODIFIED', 'Ultima modifica:');
define('TEXT_IMAGE_NONEXISTENT', 'L\'IMMAGINE NON ESISTE');
define('TEXT_PRODUCT_MORE_INFORMATION', 'Per maggiori informazioni visita il <a href="http://%s" target="blank"><u>Sito Web</u></a>.');
define('TEXT_PRODUCT_DATE_ADDED', 'Questo prodotto è stato aggiunto al nostro catalogo il %s.');
define('TEXT_PRODUCT_DATE_AVAILABLE', 'Questo prodotto sarà disponibile il %s.');

define('TEXT_TAX_INFO', ' ex IVA:');
define('TEXT_PRODUCTS_LIST_PRICE', 'Lista:');
define('TEXT_PRODUCTS_DISCOUNT_ALLOWED', 'Massimo sconto permesso:');

define('TEXT_PRODUCTS_BASE_PRICE', 'Prezzo Base ');
define('TEXT_PRODUCTS_BASE_UNIT', 'Quantità Base:');
define('TEXT_PRODUCTS_BASE_PRICE_FACTOR', 'Fattore per calcolare il prezzo di base:');
define('TEXT_PRODUCTS_BASE_QUANTITY', 'Quantità Base:');
define('TEXT_PRODUCTS_PRODUCT_QUANTITY', 'Quantità Prodotto:');
define('TEXT_PRODUCTS_DECIMAL_QUANTITY', 'Quantità Decimale');
define('TEXT_PRODUCTS_UNIT', 'Pacco');


define('TEXT_PRODUCTS_IMAGE_REMOVE', '<b>Rimuovere</b> questa immagine dal Prodotto?');
define('TEXT_PRODUCTS_IMAGE_DELETE', '<b>Cancellare</b> questa immagine dal Server?');
define('TEXT_PRODUCTS_ZOOMIFY', 'Zoomify');

define('TEXT_PRODUCTS_STATUS', 'Stato Prodotto:');
define('TEXT_CATEGORIES', 'Categorie:');
define('TEXT_PRODUCTS_DATE_AVAILABLE', 'Data di disponibilità:');
define('TEXT_PRODUCT_NOT_AVAILABLE', 'non disponibile');
define('TEXT_PRODUCTS_MANUFACTURER', 'Produttore:');
define('TEXT_PRODUCTS_NAME', 'Nome Prodotto:');
define('TEXT_PRODUCTS_DESCRIPTION', 'Descrizione Prodotto:');
define('TEXT_PRODUCTS_DESCRIPTION_META', 'Descrizione(tag decription per motori ricerca max. 250 lettere)');
define('TEXT_PRODUCTS_KEYWORDS_META', 'Parole chiave(tag keywords per motori ricerca, separate da una virgola max 250 lettere)');
define('TEXT_EDIT_CATEGORIES_DESCRIPTION_META', 'Category description for Description TAG (max. 250 letters)');
define('TEXT_EDIT_CATEGORIES_KEYWORDS_META', 'Category of search words for Keyword TAG (references by commaseparately - max. 250 letters)');
define('TEXT_PRODUCTS_QUANTITY', 'Quantità Prodotto:');
define('TEXT_PRODUCTS_REORDER_LEVEL', 'Livello di Restock Prodotto:');
define('TEXT_PRODUCTS_MODEL', 'Modello Prodotto:');
define('TEXT_PRODUCTS_EAN', 'EAN :');
define('TEXT_PRODUCTS_IMAGE', 'Immagine Prodotto:');
define('TEXT_PRODUCTS_URL', 'URL Prodotto:');
define('TEXT_PRODUCTS_URL_WITHOUT_HTTP', '<small>(senza http://)</small>');
define('TEXT_PRODUCTS_PRICE', 'Prezzo Prodotto:');
define('TEXT_PRODUCTS_WEIGHT', 'Peso Prodotto:');
define('TEXT_PRODUCTS_SORT_ORDER', 'Ordine:');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Errore:  Non posso collegare prodotti nella stessa categoria.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Errore: La cartella immagini del catalogo non è scrivibile: ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Errore: La cartella immagini del catalogo non esiste: ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);

?>
