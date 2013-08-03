<?php
/* ----------------------------------------------------------------------
   $Id: quickfind.php 409 2013-06-11 15:53:40Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: quickfind.php,v 1.10 2005/08/04 23:25:46 hpdl Exp $
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  $q = '';
  $name = '';
  $url = '';
  $limit = 10;
  $results = array();

  $q = addslashes(preg_replace("%[^0-9a-zA-Z ]%", "", $_GET['keywords']) );

  if (isset($q) && !empty($q)) {
    $productstable = $oostable['products'];
    $products_descriptiontable = $oostable['products_description'];
    $sql = 'SELECT pd.products_id, pd.products_name, p.products_model 
            FROM ' . $products_descriptiontable . ' pd 
            LEFT JOIN ' . $productstable . ' p 
            ON (p.products_id = pd.products_id) 
            WHERE (pd.products_name LIKE "%' . oos_db_input($q) . '%" OR 
                   p.products_model like "%' . oos_db_input($q) . '%") 
              AND  p.products_status = "3" 
              AND  pd.products_languages_id = "' . intval($nLanguageID) . '" 
            ORDER BY pd.products_name ASC
            LIMIT ' . $limit;
    $result = $dbconn->Execute($sql);

    if ($result->RecordCount() > 0) {
      while ($row = $result->fields) {

        if (isset($row['products_model']) && !empty($row['products_model'])) {
          $model = ' [' . $row['products_model'] . ']';
        } else {
          $model = '';
        }

        $name = $row['products_name'];
        $results[] = '<a href="' . oos_href_link($aContents['product_info'], 'products_id=' . $row['products_id']) . '">' .  $row['products_name'] . '</a>' . ((isset($row['products_model']) && !empty($row['products_model']))?' [' . $row['products_model'] . ']':'') . "\n";

        $result->MoveNext();
      }
    } else {
      $results [] = 'No Quick Find Results';
    }
    echo implode('<br />' . "\n", $results);
  } else {
    echo "Quick Find Results ...";
  }

  die; #for disable output $_GET, $_POST, $_COOKIE, $_SESSION arrays

