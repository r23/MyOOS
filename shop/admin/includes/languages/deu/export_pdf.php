<?php
/* ----------------------------------------------------------------------
   $Id: export_pdf.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   PDF Catalogs v.2.0.1 for osCommerce v.2.2 MS2

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ---------------------------------------------------------------------- */
/******************************************************************************/
/* PDF Catalogs v.1.55 for osCommerce v.2.2 MS2                               */
/*                                                                            */
/* by Antonios THROUVALAS (antonios@throuvalas.net), April 2004               */
/* by Nicolas Hilly (n.hilly@laposte.net), August 2004                        */
/*                                                                            */
/* Based on PDF Catalogs v.1.4 by gurvan.riou@laposte.net                     */
/*                                                                            */
/* Uses FPDF (http://www.fpdf.org), Version 1.52, by Olivier PLATHEY          */
/*                                                                            */
/* Credit goes also to:                                                       */
/* - Yamasoft (http://www.yamasoft.com/php-gif.zip) for their GIF class,      */
/* - Jerome FENAL (jerome.fenal@logicacmg.com) for introducing GIF Support    */
/*   in the FPDF Class,                                                       */
/* - The osC forums members (forums.oscommerce.com)!                          */
/*                                                                            */
/* Please donate to the osCommerce Core Team!                                 */
/* Freeware, You may use, modify and redistribute this software as you wish!  */
/******************************************************************************/
  
define('BOX_CATALOG_PDF_CATALOGUE', 'PDF Katalog(e) erstellen');
define('HEADING_TITLE', 'PDF Katalog(e) erstellen');
define('PDF_PRE_GENERATED', ' Um die PDF Generation Erstellung zu starten, kann einige Minuten dauern, klicken Sie hier: ');
define('PDF_GENERATED', 'Anzahl erstellter PDF Katalog(e): ');
define('PDF_INDEX_HEADER', '    Artikelnamme                                           Kategorie   Seite');
define('PDF_TXT_MODEL', 'Artikelnummer :  ');
define('PDF_TXT_MANUFACTURER', 'Hersteller :  ');
?>
