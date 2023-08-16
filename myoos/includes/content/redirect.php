<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: redirect.php,v 1.9 2003/02/13 04:23:23 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

switch ($_GET['action']) {

case 'url':
    if (isset($_GET['goto']) && oos_is_not_null($_GET['goto'])) {
        $sgoto = filter_string_polyfill(filter_input(INPUT_GET, 'goto'));

        if (empty($sgoto) || !is_string($sgoto)) {
            oos_redirect(oos_href_link($aContents['403']));
        }

        $products_descriptiontable = $oostable['products_description'];
        $check_sql = "SELECT products_url FROM $products_descriptiontable WHERE products_url = '" . oos_db_input($sgoto) . "'";
        $check_result = $dbconn->Execute($check_sql);

        if ($check_result->RecordCount() >= 1) {
            oos_redirect('https://' . $sgoto);
        }
    }
    break;


case 'manufacturer':
    if (isset($_GET['manufacturers_id']) && is_numeric($_GET['manufacturers_id'])) {
        $manufacturers_id = filter_input(INPUT_GET, 'manufacturers_id', FILTER_VALIDATE_INT);

        $manufacturers_infotable = $oostable['manufacturers_info'];
        $sql = "SELECT manufacturers_url
					FROM $manufacturers_infotable
                    WHERE manufacturers_id = '" . intval($manufacturers_id) . "'
                    AND manufacturers_languages_id = '" .  intval($nLanguageID) . "'";
        $manufacturer_result = $dbconn->Execute($sql);

        if (!$manufacturer_result->RecordCount()) {
            // no url exists for the selected language, lets use the default language then
            $manufacturers_infotable = $oostable['manufacturers_info'];
            $languagestable = $oostable['languages'];
            $sql = "SELECT mi.manufacturers_languages_id, mi.manufacturers_url 
							FROM $manufacturers_infotable mi,
								$languagestable l
							WHERE mi.manufacturers_id = '" . intval($manufacturers_id) . "' 
							AND mi.manufacturers_languages_id = l.iso_639_2 
                            AND l.iso_639_2 = '" . DEFAULT_LANGUAGE . "'";
            $manufacturer_result = $dbconn->Execute($sql);
            if (!$manufacturer_result->RecordCount()) {
                // no url exists, return to the site
                oos_redirect(oos_href_link($aContents['home']));
            } else {
                $manufacturer = $manufacturer_result->fields;
                $manufacturers_infotable = $oostable['manufacturers_info'];
                $dbconn->Execute("UPDATE $manufacturers_infotable SET url_clicked = url_clicked+1, date_last_click = now() WHERE manufacturers_id = '" . intval($manufacturers_id) . "' AND manufacturers_languages_id = '" . intval($manufacturer['manufacturers_languages_id']) . "'");
            }
        } else {
            // url exists in selected language
            $manufacturer = $manufacturer_result->fields;
            $manufacturers_infotable = $oostable['manufacturers_info'];
            $dbconn->Execute("UPDATE $manufacturers_infotable SET url_clicked = url_clicked+1, date_last_click = now() WHERE manufacturers_id = '" . intval($manufacturers_id) . "' AND manufacturers_languages_id = '" .  intval($nLanguageID) . "'");
        }

        oos_redirect($manufacturer['manufacturers_url']);
    }
    break;

}

oos_redirect(oos_href_link($aContents['home']));
