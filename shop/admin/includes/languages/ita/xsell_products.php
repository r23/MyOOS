<?php
/* ----------------------------------------------------------------------
   $Id: xsell_products.php,v 1.3 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File:  xsell_products.php, v1  2002/09/11
   ----------------------------------------------------------------------
   Cross-Sell

   Contribution based on:

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

define('CROSS_SELL_SUCCESS', 'Articoli Vendita Incrociata Aggiornati con Successo per il prodotto di vendita incrociata #'.$_GET['add_related_product_ID']);
define('SORT_CROSS_SELL_SUCCESS', 'Ordinamento Aggiornato con Successo per il prodotto di vendita incrociata #'.$_GET['add_related_product_ID']);
define('HEADING_TITLE', 'Amministrazione Vendita Incrociata (X-Sell)');
define('TABLE_HEADING_PRODUCT_ID', 'Id Prodotto');
define('TABLE_HEADING_PRODUCT_MODEL', 'Modello Prodotto');
define('TABLE_HEADING_PRODUCT_NAME', 'Nome Prodotto');
define('TABLE_HEADING_CURRENT_SELLS', 'Vendite incrociate attive');
define('TABLE_HEADING_UPDATE_SELLS', 'Aggiorna vendita incrociata');
define('TABLE_HEADING_PRODUCT_IMAGE', 'Immagine Prodotto');
define('TABLE_HEADING_PRODUCT_PRICE', 'Prezzo Prodotto');
define('TABLE_HEADING_CROSS_SELL_THIS', 'Incrociare questo?');
define('TEXT_EDIT_SELLS', 'Modifica');
define('TEXT_SORT', 'Dai priorità');
define('TEXT_SETTING_SELLS', 'Definisci Vendita-Incrociata per');
define('TEXT_PRODUCT_ID', 'Id Prodotto');
define('TEXT_MODEL', 'Modello');
define('TABLE_HEADING_PRODUCT_SORT', 'Ordinamento');
define('TEXT_IMAGE_NONEXISTENT', 'Niente Immagine');
define('TEXT_CROSS_SELL', 'Vendita-Incrociata');

?>
