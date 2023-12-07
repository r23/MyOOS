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
define('TEXT_VIDEO_FILE', 'Video Dateiformat:');

define('TEXT_VIDEO_MP4_FILE', 'Video .mp4 Dateiformat:');
define('TEXT_VIDEO_OGV_FILE', 'Video .ogv Dateiformat:');
define('TEXT_VIDEO_WEBM_FILE', 'Video .webm Dateiformat:');
define('TEXT_POSTER_WEBP_FILE', 'Poster im WebP Format (optional):');

define('TEXT_VIDEO_TITLE', 'Überschrift, Video-Titel:');
define('TEXT_VIDEO_DESCRIPTION', 'Videobeschreibung:');
define('TEXT_VIDEO_PRELOAD', 'Mit dem Herunterladen der Videodaten beginnen:');
define('TEXT_VIDEO_PRELOAD_HELP', 'Schlägt dem Browser vor, ob das Herunterladen der Videodaten beginnen soll, sobald das &lt;video&gt;-Element geladen ist. Unterstützte Werte sind: <ul><li><b>auto</b>: Mit dem Laden des Videos sofort beginnen (wenn der Browser dies unterstützt).</li> <li><b>metadata</b>: Laden Sie nur die Metadaten des Videos, die Informationen wie die Dauer und die Abmessungen des Videos enthalten.</li><li><b>none</b>: Es werden keine Daten vorgeladen. Der Browser wartet, bis der Benutzer auf "Play" drückt, um mit dem Herunterladen zu beginnen.</li></ul>');

define('TEXT_VIDEO_UPLAOD_TITLE', 'Neue 16:9 Videodatei hochladen.');
define('TEXT_VIDEO_BROWSER_UPLOADER', 'Sie verwenden den im Browser integrierten Datei-Uploader.');
define('TEXT_VIDEO_MAX_UPLOAD', 'Maximale Dateigröße für Uploads: <strong>%s</strong>.');
define('TEXT_VIDEO_UPLAOD_PATIENCE', 'Bitte haben Sie etwas Geduld. Beim Uplaod wird ein Bild von dem Video erstellt und das Video wird automatisch in folgende Formate konvertiert: .mp4 .ogv und .webm.');
define('TEXT_VIDEO_UPLAOD_HELP', 'Für die Videobearbeitung wird FFmpeg auf dem Server benötigt.');

define('TEXT_VIDEO_REMOVE', 'Video löschen');
define('TEXT_UPLOAD_VIDEO', 'Upload Video');

define('ERROR_NO_VIDEO_FILE', 'Die Datei, die Sie hochladen möchten, hat kein gültiges Dateiforamt. Bitte versuchen Sie es erneut.');
define('TEXT_SUCCESSFULLY_UPLOADED_VIDEO', 'Ihr Video wurde hochgeladen.');
define('TEXT_SUCCESSFULLY_UPLOADED_VIDEO_MP4', 'Ihre MP4 Video-Datei wurde hochgeladen.');
define('TEXT_SUCCESSFULLY_UPLOADED_VIDEO_WEBM', 'Ihre WEBM Video-Datei wurde hochgeladen.');
define('TEXT_SUCCESSFULLY_UPLOADED_VIDEO_OGV', 'Ihre OGV Video-Datei wurde hochgeladen.');
define('TEXT_SUCCESSFULLY_UPLOADED_POSTER', 'Ihr Poster im WebP Format wurde hochgeladen.');

define('ERROR_PROBLEM_WITH_VIDEO_FILE', 'Es gab ein Problem mit dem Upload. Bitte versuchen Sie es erneut.');

define('TEXT_POSTER_HELP', 'Die Größe des Posters hängt von der Auflösung und dem Seitenverhältnis Ihres Videos ab. Wenn Sie ein 16:9-Video haben und die Option Vollbild verwenden, können Sie die folgenden Auflösungen für Ihr Poster verwenden: <br> 4320p (8k): 7680x4320 Pixel<br> 2160p (4K): 3840x2160 Pixel<br> 1080p (Full HD): 1920x1080 Pixel<br> 720p (HD): 1280x720 Pixel<br> 480p (SD): 854x480 Pixel<br>');
