<?php
/* ----------------------------------------------------------------------
   $Id: categories.php,v 1.3 2007/06/13 16:39:11 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
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

define('HEADING_TITLE', 'Categorie / Prodotti');
define('HEADING_TITLE_SEARCH', 'Cerca:');
define('HEADING_TITLE_GOTO', 'Vai a:');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_CATEGORIES_PRODUCTS', 'Categorie / Prodotti');
define('TABLE_HEADING_ACTION', 'Azione');
define('TABLE_HEADING_STATUS', 'Stato');

define('TABLE_HEADING_MANUFACTURERS', 'Marche');
define('TABLE_HEADING_PRODUCT_SORT', 'Ordinamento');

define('TEXT_NEW_PRODUCT', 'Nuovo prodotto in &quot;%s&quot;');
define('TEXT_CATEGORIES', 'Categorie:');
define('TEXT_SUBCATEGORIES', 'Sottocategorie:');
define('TEXT_PRODUCTS', 'Prodotti:');
define('TEXT_PRODUCTS_PRICE_INFO', 'Prezzo:');
define('TEXT_PRODUCTS_TAX_CLASS', 'Tipo di tassa:');
define('TEXT_PRODUCTS_AVERAGE_RATING', 'Valore medio:');
define('TEXT_PRODUCTS_QUANTITY_INFO', 'Quantità:');
define('TEXT_DATE_ADDED', 'Data inserimento:');
define('TEXT_DATE_AVAILABLE', 'Data disponibilità:');
define('TEXT_LAST_MODIFIED', 'Ultima modifica:');
define('TEXT_IMAGE_NONEXISTENT', 'L\'IMMAGINE NON ESISTE');
define('TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS', 'Inserire una nuova categoria o un nuovo prodotto in <br>&nbsp;<br><b>%s</b>');
define('TEXT_PRODUCT_MORE_INFORMATION', 'Per ulteriori informazioni, visitare il <a href="http://%s" target="blank"><u>sito</u></a> di questo prodotto.');
define('TEXT_PRODUCT_DATE_ADDED', 'Questo prodotto è stato aggiunto al catalogo il %s.');
define('TEXT_PRODUCT_DATE_AVAILABLE', 'Questo prodotto sarà disponibile il%s.');

define('TEXT_INFO_PERCENTAGE', 'Percentuale:');
define('TEXT_INFO_EXPIRES_DATE', 'Scade il:');

define('TEXT_EDIT_INTRO', 'Eseguire i cambiamenti necessari');
define('TEXT_EDIT_CATEGORIES_ID', 'ID categoria:');
define('TEXT_EDIT_CATEGORIES_NAME', 'Nome categoria:');
define('TEXT_EDIT_CATEGORIES_HEADING_TITLE', 'Titolo Categoria:');
define('TEXT_EDIT_CATEGORIES_DESCRIPTION', 'Descrizione Categoria:');
define('TEXT_EDIT_CATEGORIES_DESCRIPTION_META', 'Descrizione Categoria per TAG Description (max. 250 lettere)');
define('TEXT_EDIT_CATEGORIES_KEYWORDS_META', 'Categoria, parole per il TAG Keyword (references by commaseparately - max. 250 lettere)');
define('TEXT_EDIT_CATEGORIES_IMAGE', 'Immagine per la categoria:');
define('TEXT_EDIT_SORT_ORDER', 'Categoria num.:');
define('TEXT_TAX_INFO', ' ex IVA:');
define('TEXT_PRODUCTS_LIST_PRICE', 'Lista:');
define('TEXT_PRODUCTS_DISCOUNT_ALLOWED', 'Massimo Sconto Permesso:');

define('TEXT_INFO_COPY_TO_INTRO', 'Scegliere la nuova categoria in cui copiare questo prodotto');
define('TEXT_INFO_CURRENT_CATEGORIES', 'Categorie correnti:');

define('TEXT_INFO_HEADING_NEW_CATEGORY', 'Nuova Categoria');
define('TEXT_INFO_HEADING_EDIT_CATEGORY', 'Modifica Categoria');
define('TEXT_INFO_HEADING_DELETE_CATEGORY', 'Cancella Categoria');
define('TEXT_INFO_HEADING_MOVE_CATEGORY', 'Sposta Categoria');
define('TEXT_INFO_HEADING_DELETE_PRODUCT', 'Cancella Prodotto');
define('TEXT_INFO_HEADING_MOVE_PRODUCT', 'Sposta Prodotto');
define('TEXT_INFO_HEADING_COPY_TO', 'Copia in');

define('TEXT_DELETE_CATEGORY_INTRO', 'Sicuro di voler eliminare questa categoria?');
define('TEXT_DELETE_PRODUCT_INTRO', 'Sicuro di voler eliminare permanentemente questo prodotto?');

define('TEXT_DELETE_WARNING_CHILDS', '<b>ATTENZIONE:</b> Ci sono %s (sotto-)categorie collegate a questa categoria!');
define('TEXT_DELETE_WARNING_PRODUCTS', '<b>ATTENZIONE:</b> Ci sono %s prodotti collegati a questa categoria!');

define('TEXT_MOVE_PRODUCTS_INTRO', 'Seleziona la categoria in cui vuoi spostare il file <b>%s</b>');
define('TEXT_MOVE_CATEGORIES_INTRO', 'Seleziona la categoria in cui vuoi spostare il file <b>%s</b>');
define('TEXT_MOVE', 'Spostare <b>%s</b> in:');

define('TEXT_NEW_CATEGORY_INTRO', 'Inserire le seguenti informazioni per la nuova categoria');
define('TEXT_CATEGORIES_NAME', 'Nome categoria:');
define('TEXT_CATEGORIES_IMAGE', 'Immagine per la categoria:');
define('TEXT_SORT_ORDER', 'Categoria num.:');

define('TEXT_PRODUCTS_STATUS', 'Stato prodotto:');
define('TEXT_PRODUCTS_DATE_AVAILABLE', 'Data disponibilità:');
define('TEXT_PRODUCT_AVAILABLE', 'Disponibile');
define('TEXT_PRODUCT_NOT_AVAILABLE', 'Esaurito');
define('TEXT_PRODUCTS_MANUFACTURER', 'Produttore:');
define('TEXT_PRODUCTS_NAME', 'Nome prodotto:');
define('TEXT_PRODUCTS_DESCRIPTION', 'Descrizione prodotto:');
define('TEXT_PRODUCTS_DESCRIPTION_META', 'Description of article for Description TAG (max. 250 letters)');
define('TEXT_PRODUCTS_KEYWORDS_META', 'Articolo, parole per il TAG Keyword (references by commaseparately - max. 250 lettere)');
define('TEXT_PRODUCTS_QUANTITY', 'Quantità prodotto:');
define('TEXT_PRODUCTS_REORDER_LEVEL', 'Livello Restock Prodotti:');
define('TEXT_PRODUCTS_MODEL', 'Modello prodotto:');
define('TEXT_PRODUCTS_IMAGE', 'Immagine prodotto:');
define('TEXT_PRODUCTS_URL', 'URL prodotto:');
define('TEXT_PRODUCTS_URL_WITHOUT_HTTP', '<small>(senza http://)</small>');define('TEXT_PRODUCTS_PRICE', 'Products Price:');
define('TEXT_PRODUCTS_WEIGHT', 'Grandezza prodotto:');
define('TEXT_PRODUCTS_STATUS', 'Stato Prodotto:');


define('EMPTY_CATEGORY', 'Categoria vuota');

define('TEXT_HOW_TO_COPY', 'Metodo di Copia:');
define('TEXT_COPY_AS_LINK', 'Copia come Link');
define('TEXT_COPY_AS_DUPLICATE', 'Copia come duplicato');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Errore: Non puoi linkare i prodotti nella stessa Categoria.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Errore: La Directory del Catalogo delle Immagini non è scrivibile: ' . DIR_FS_CATALOG_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Errore: La Directory del Catalogo delle Immagini non esiste: ' . DIR_FS_CATALOG_IMAGES);

define('TEXT_ADD_SLAVE_PRODUCT','Inserisci l\' ID PRodotto per aggiungerlo come figlio:');
define('IMAGE_SLAVE','Prodotti Figli');
define('TEXT_CURRENT_SLAVE_PRODUCTS','<b>Prodotti Figli Attuali:</b>');
define('IMAGE_DELETE_SLAVE','Cancella prodotto figlio');
?>
