<?php
/* ----------------------------------------------------------------------
   $Id: pdf_config.php,v 1.1 2007/06/08 15:20:14 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   PDF Catalogs v.2.0.1 for osCommerce v.2.2 MS2

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------

   PDF Catalogs v.2.0.1 for osCommerce v.2.2 MS2

   by Infobroker (info@cooleshops.de), April 2006
   by Antonios THROUVALAS (antonios@throuvalas.net), April 2004
   by Mitch O`Brian (mitchobrian.de), juli2004 2004
   by Nicolas Hilly (n.hilly@laposte.net), August 2004
   by Christophe Buchi (chris@butch.ch), September 2004
   by Ryan Kononoff (ryankononoff@shaw.ca), October 2004

   Based on PDF Catalogs v.1.4 by gurvan.riou@laposte.net

   Uses FPDF (http://www.fpdf.org), Version 1.52, by Olivier PLATHEY
    modified by Infobroker

   Credit goes also to:
   - Yamasoft (http://www.yamasoft.com/php-gif.zip) for their GIF class,
   - Jerome FENAL (jerome.fenal@logicacmg.com) for introducing GIF Support
     in the FPDF Class,
   - The osC forums members (forums.oscommerce.com)!

   Please donate to the osCommerce Core Team!
   Freeware, You may use, modify and redistribute this software as you wish!  */
 ---------------------------------------------------------------------- */

// Directory where the generated PDF files will be stored!
// If you mofify the name of this directory, please modify accordingly the 
//catalog/pdf_catalogue_info.php file!!
// Don't forget to change the permissions of this directory to 755!
define('DIR_WS_PDF_CATALOGS', 'catalogues/');
// Directory where the Font files are stored
define('FPDF_FONTPATH', 'font/');
// Filename to use as a base for the name of the generated PDF files.
// If you mofify the name of this file, please modify it accordingly in
// the catalog/pdf_catalogue.php file, around line .
define('PDF_FILENAME', 'catalog');
// the catalog/pdf_catalogue_info.php file, around line .
define('PDF_FILENAME_CATEGORIES', 'categories');
// Orientation of the pages (default A4) P = Portrait, L = Landscape
define('PDF_ORIENTATION', 'P');
// Red, Green, Blue Components (Values between 0 and 255).
// Eg. Use 255,255,255 for white and 0,0,0 for black!
define('BACKGROUND_COLOR', '255,255,255');
// The logo of your shop, to be displayed instead of the PDF_TITLE. Must be in
// the catalog/images directory! Will be resized to have a height of 30 pt.
define('PDF_LOGO', '');
// Same title for all pages, will be used if the PDF_LOGO is not defined!
define('PDF_TITLE', 'Katalog PDF');
// Specify the catalog header date format
define('PDF_DATE_FORMAT', '%m/%Y');
//Base Currency
define('CURRENCY', 'Ђ');

//Put the currency to the left or to the right of the price 'R' or 'L'  
define('CURRENCY_RIGHT_OR_LEFT', 'R');
//How many digits after dot for the price
define('DIGITS_AFTER_DOT',2);
//Size of the char for the directories tree 
define('DIRECTORIES_TREE_FONT_SIZE', 10);

//Put the VAT if you want
define('VAT', '1');
//Top page titles color
define('HEIGHT_TITLES_CELL_COLOR', '255,153,0');
// Categories (separator) center page titles color (if activated)
define('CENTER_TITLES_CELL_COLOR', '255,153,0');
//Show or not the new products (0 = no, 1 = yes)
define('SHOW_NEW_PRODUCTS', 0);
//New products title (same for all languages) 
define('NEW_TITLE', 'NEW');
//New products color title
define('NEW_CELL_COLOR', '248,98,98');

//Width max in mm  
define('MAX_IMAGE_WIDTH',50);

//Height max in mm   
define('MAX_IMAGE_HEIGHT',50);

//pix to mm factor   
define('PDF_TO_MM_FACTOR', 0.3526);
//Show links to your Oscommerce products (0 = no, 1 = yes) 
define('SHOW_PRODUCTS_LINKS', 1);
//Show products image or not if not no links (0 = no, 1 = yes)
define('SHOW_IMAGES', 1);



//Show categories tree or not (0 = no, 1 = yes)
define('SHOW_TREE', 1);
//Show introduction or not (0 = no, 1 = yes)
define('SHOW_INTRODUCTION', 0);
//Show the empty categories or not (0 = no, 1 = yes)
define('SHOW_EMPTY_CATEGORIES', 0);
//Show table index of products (0 = no, 1 = yes)
define('SHOW_INDEX', 1);
//Add a field aside the products name in the index (0 = nothing, 1 = manufacturer, 2 = model, 3 = date added)
define('INDEX_EXTRA_FIELD',3);
//Define a default image for products without photo, must be inside DIR_FS_CATALOG.DIR_WS_IMAGES
define('DEFAULT_IMAGE', 'no_picture.jpg');

// insert a separator page between categories (0 = no, 1 = yes)
define('CATEGORIES_PAGE_SEPARATOR', 0);
// String appearing between categories and subcategories
define('CATEGORIES_SEPARATOR', ' -/- ');
// String appearing between names, subcategories and pages in the index
define('INDEX_SEPARATOR', '');
// Blank lines before the products description cells
define('PRODUCTS_SEPARATOR', 20);
//Size of the border surronding the images of the products (0 = no, 0.2, 1 recommanded)
define('SIZE_BORDER_IMAGE', 0);
// Border around products description (0 = no, 1 = yes)
define('PRODUCTS_BORDER', 1);

// Resize images, so the pdf is smaller but it needs time!
// (set max_execution_time in php.ini to your value)
// (0 = no, 1 = yes)
// Set it to 0 if your are using GIF files, otherwise the script will generate
// an error! I am still working on that, any help is appreciated! :-)
// Addition - set to 0 even if using JPEGS, else you'll get another error! (Vger)
define('RESIZE_IMAGES', 0);

//Show products name or not (0 = no, 1 = yes)
define('SHOW_NAME', 1);
//Background color of the name cell
define('NAME_COLOR', '177,200,205');
//Show products name or not (0 = no, 1 = yes)
define('SHOW_MODEL', 1);
//Show products description or not (0 = no, 1 = yes)
define('SHOW_DESCRIPTION', 1);
//Show products manufacturer or not (0 = no, 1 = yes)
define('SHOW_MANUFACTURER', 1);
//Show products prices or not (0 = no, 1 = yes)
define('SHOW_PRICES', 1);
//Specials prices FONT color 
define('SPECIALS_PRICE_COLOR', '248,98,98');
//Show products date added or not (0 = no, 1 = yes) 
define('SHOW_DATE_ADDED', 0);
//Show products tax class id or not (0 = no, 1 = yes)
define('SHOW_TAX_CLASS_ID', 0);
?>