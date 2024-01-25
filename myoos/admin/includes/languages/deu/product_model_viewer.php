<?php
/**
   ----------------------------------------------------------------------
   $Id: products.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
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


define('HEADING_TITLE', 'Augmented Reality');

define('TEXT_NEW_PRODUCT', 'Augmented Reality &quot;%s&quot;');
define('TEXT_PRODUCTS', 'Artikel');
define('TEXT_MODELS_MODEL', '3D-Modell');
define('TEXT_IMAGE_NONEXISTENT', 'BILD EXISTIERT NICHT');
define('TEXT_MODELS_GLB', '3D-Modell .glb Format');
define('TEXT_MODELS_USDZ', '3D-Modell .usdz Format optional für Apple');

define('TEXT_MODELS_TITLE', 'Alternativtext für ALT-Attribut:');
define('TEXT_MODELS_DESCRIPTION', '3D-Modellbeschreibung:');
define('TEXT_MODELS_BACKGROUND_COLOR', 'Hintergrundfarbe:');
define('TEXT_MODELS_OBJECT_SCALING', 'Skalierungsverhalten:');
define('TEXT_MODELS_OBJECT_SCALING_HELP', 'Steuert das Skalierungsverhalten im AR-Modus im Scene Viewer. Setzen Sie auf "fixed", um die Skalierung des Modells zu deaktivieren, wodurch das Modell immer auf 100 % Maßstab gesetzt wird. Auf "auto" gesetzt, was eine Größenänderung des Modells ermöglicht.');
define('TEXT_MODELS_OBJECT_ROTATION', 'Objekt-Rotation:');

define('TEXT_MODELS_HDR', 'Panorama Hintergrundbild');
define('TEXT_MODELS_HDR_NONE', 'Kein Hintergrundbild verwenden');

define('TEXT_MODEL_REMOVE', '3D-Modell löschen');
define('TEXT_MODELS_EXTENSIONS', 'glTF-Formt auswählen');

define('TEXT_UPLOAD_MODELS', 'Upload 3D-Modell');

define('ERROR_NO_GLB_FILE', 'Die Datei, die Sie hochladen möchten, ist keine .glb-Datei. Bitte versuchen Sie es erneut.');
define('TEXT_SUCCESSFULLY_UPLOADED_GLB', 'Ihre .glb-Datei wurde hochgeladen.');
define('ERROR_PROBLEM_WITH_GLB_FILE', 'Es gab ein Problem mit dem Upload. Bitte versuchen Sie es erneut.');

define('ERROR_NO_USDZ_FILE', 'Die Datei, die Sie hochladen möchten, ist keine .usdz-Datei. Bitte versuchen Sie es erneut.');
define('TEXT_SUCCESSFULLY_UPLOADED_USDZ', 'Ihre usdz-Datei wurde hochgeladen.');
define('ERROR_PROBLEM_WITH_USDZ_FILE', 'Es gab ein Problem mit dem Upload der usdz-Datei. Bitte versuchen Sie es erneut.');
