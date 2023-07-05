<?php
/**
   ----------------------------------------------------------------------
   $Id: products.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: categories.php,v 1.22 2002/08/17 09:43:33 project3000
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */


define('HEADING_TITLE', 'Produkt Video');

define('TEXT_NEW_PRODUCT', 'Produkt Video über &quot;%s&quot;');
define('TEXT_PRODUCTS', 'Artikel');
define('TEXT_VIDEO_SOURCE', 'Video');
define('TEXT_IMAGE_NONEXISTENT', 'BILD EXISTIERT NICHT');
define('TEXT_VIDEO_FILE', 'Video im .avi Dateiformat:');

define('TEXT_VIDEO_TITLE', 'Überschrift, Video-Titel:');
define('TEXT_VIDEO_DESCRIPTION', 'Videobeschreibung:');
define('TEXT_VIDEO_PRELOAD', 'Mit dem Herunterladen der Videodaten beginnen:');
define('TEXT_VIDEO_PRELOAD_HELP', 'Schlägt dem Browser vor, ob das Herunterladen der Videodaten beginnen soll, sobald das &lt;video&gt;-Element geladen ist. Unterstützte Werte sind: <ul><li><b>auto</b>: Mit dem Laden des Videos sofort beginnen (wenn der Browser dies unterstützt).</li> <li><b>metadata</b>: Laden Sie nur die Metadaten des Videos, die Informationen wie die Dauer und die Abmessungen des Videos enthalten.</li><li><b>none</b>: Es werden keine Daten vorgeladen. Der Browser wartet, bis der Benutzer auf "Play" drückt, um mit dem Herunterladen zu beginnen.</li></ul>');

define('TEXT_VIDEO_UPLAOD_TITLE', 'Neue .avi Videodatei hochladen.');
define('TEXT_VIDEO_BROWSER_UPLOADER', 'Sie verwenden den im Browser integrierten Datei-Uploader.');
define('TEXT_VIDEO_MAX_UPLOAD', 'Maximale Dateigröße für Uploads: <strong>%s</strong>.');
define('TEXT_VIDEO_UPLAOD_PATIENCE', 'Bitte haben Sie etwas Geduld. Beim Uplaod wird ein Bild von dem Video erstellt und das Video wird automatisch in folgende Formate konvertiert: .mp4 .ogv und .webm.');
define('TEXT_VIDEO_UPLAOD_HELP', 'Für die Videobearbeitung wird FFmpeg auf dem Server benötigt.');

define('TEXT_VIDEO_REMOVE', 'Video löschen');
define('TEXT_UPLOAD_VIDEO', 'Upload Video');

define('ERROR_NO_VIDEO_FILE', 'Die Datei, die Sie hochladen möchten, ist keine .mpg-Datei. Bitte versuchen Sie es erneut.');
define('TEXT_SUCCESSFULLY_UPLOADED_VIDEO', 'Ihr Video wurde hochgeladen.');
define('ERROR_PROBLEM_WITH_VIDEO_FILE', 'Es gab ein Problem mit dem Upload. Bitte versuchen Sie es erneut.');
