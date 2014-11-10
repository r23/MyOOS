<?php
/* ----------------------------------------------------------------------
   $Id: function_banner.php,v 1.1 2007/06/12 16:49:27 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: banner.php,v 1.10 2003/02/11 01:31:01 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

 /**
  * Banner
  *
  * @link http://www.oos-shop.de/
  * @package Banner
  * @version $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/12 16:49:27 $
  */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

 /**
  * Sets the status of a banner
  *
  * @param $banners_id
  * @param $banners_id
  * @return string
  */
  function oos_set_banner_status($banners_id, $status) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    if ($status == '1') {
      $bannerstable = $oostable['banners'];
      return $dbconn->Execute("UPDATE $bannerstable
                               SET status = '1',
                                   date_status_change = now(),
                                   date_scheduled = NULL
                               WHERE banners_id = '" . intval($banners_id) . "'");
    } elseif ($status == '0') {
      $bannerstable = $oostable['banners'];
      return $dbconn->Execute("UPDATE $bannerstable
                               SET status = '0',
                                   date_status_change = now()
                              WHERE banners_id = '" . intval($banners_id) . "'");
    } else {
      return false;
    }
  }


 /**
  * Auto activate banners
  */
  function oos_activate_banners() {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $bannerstable = $oostable['banners'];
    $banners_result = $dbconn->Execute("SELECT banners_id, date_scheduled FROM $bannerstable WHERE date_scheduled != NULL");

    if ($banners_result->RecordCount() > 0) {
      while ($banners = $banners_result->fields) {
        if (date('Y-m-d H:i:s') >= $banners['date_scheduled']) {
          oos_set_banner_status($banners['banners_id'], '1');
        }

        // Move that ADOdb pointer!
        $banners_result->MoveNext();
      }

      // Close result set
      $banners_result->Close();
    }
  }


 /**
  * Auto expire banners
  */
  function oos_expire_banners() {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $bannerstable = $oostable['banners'];
    $banners_historytable = $oostable['banners_history'];
    $banners_result = $dbconn->Execute("SELECT b.banners_id, b.expires_date, b.expires_impressions, sum(bh.banners_shown) as banners_shown FROM $bannerstable b, $banners_historytable bh WHERE b.status = '1' AND b.banners_id = bh.banners_id GROUP BY b.banners_id, b.expires_date, b.expires_impressions");

    if ($banners_result->RecordCount() > 0) {
      while ($banners = $banners_result->fields) {
        if (oos_is_not_null($banners['expires_date'])) {
          if (date('Y-m-d H:i:s') >= $banners['expires_date']) {
            oos_set_banner_status($banners['banners_id'], '0');
          }
        } elseif (oos_is_not_null($banners['expires_impressions'])) {
          if ($banners['banners_shown'] >= $banners['expires_impressions']) {
            oos_set_banner_status($banners['banners_id'], '0');
          }
        }

        // Move that ADOdb pointer!
        $banners_result->MoveNext();
      }

      // Close result set
      $banners_result->Close();
    }
  }


 /**
  * Display a banner from the specified group or banner id ($identifier)
  *
  * @param $action
  * @param $identifier
  * @return string
  */
  function oos_display_banner($action, $identifier) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $aFilename = oos_get_filename();
    $aModules = oos_get_modules();

    if ($action == 'dynamic') {
      $bannerstable = $oostable['banners'];
      $banners = $dbconn->Execute("SELECT COUNT(*) AS total FROM $bannerstable WHERE status = '1' AND banners_group = '" . oos_db_input($identifier) . "'");
      if ($banners->fields['total'] > 0) {
        $bannerstable = $oostable['banners'];
        $banner = oos_random_select("SELECT banners_id, banners_title, banners_image, banners_html_text FROM $bannerstable WHERE status = '1' AND banners_group = '" . oos_db_input($identifier) . "'");
      } else {
        trigger_error("oos_display_banner(' . $action . ', ' . $identifier . ') -> No banners with group \'' . $identifier . '\' found!", E_USER_ERROR);
        return false;
      }
    } elseif ($action == 'static') {
      if (is_array($identifier)) {
        $banner = $identifier;
      } else {
        $bannerstable = $oostable['banners'];
        $banner_result = $dbconn->Execute("SELECT banners_id, banners_title, banners_image, banners_html_text FROM $bannerstable WHERE status = '1' AND banners_id = '" . oos_db_input($identifier) . "'");
        if ($banner_result->RecordCount() > 0) {
          $banner = $banner_result->fields;
        } else {
          trigger_error("oos_display_banner(' . $action . ', ' . $identifier . ') -> Banner with ID \'' . $identifier . '\' not found, or status inactive", E_USER_ERROR);
          return false;
        }
      }
    } else {
      trigger_error("oos_display_banner(' . $action . ', ' . $identifier . ') -> Unknown $action parameter value - it must be either \'dynamic\' or \'static\'", E_USER_ERROR);
      return false;
    }

    if (oos_is_not_null($banner['banners_html_text'])) {
      $banner_string = $banner['banners_html_text'];
    } else {
      $banner_string = '<a href="' . oos_href_link($aModules['main'], $aFilename['redirect'], 'action=banner&amp;goto=' . $banner['banners_id']) . '" target="_blank">' . oos_image(OOS_IMAGES . $banner['banners_image'], $banner['banners_title']) . '</a>';
    }

    oos_update_banner_display_count($banner['banners_id']);

    return $banner_string;
  }


 /**
  * Check to see if a banner exists
  *
  * @param $action
  * @param $identifier
  */
  function oos_banner_exists($action, $identifier) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    if ($action == 'dynamic') {
      $bannerstable = $oostable['banners'];
      return oos_random_select("SELECT banners_id, banners_title, banners_image, banners_html_text FROM $bannerstable WHERE status = '1' AND banners_group = '" . oos_db_input($identifier) . "'");
    } elseif ($action == 'static') {
      $bannerstable = $oostable['banners'];
      $banner_result = $dbconn->Execute("SELECT banners_id, banners_title, banners_image, banners_html_text FROM $bannerstable WHERE status = '1' AND banners_id = '" . oos_db_input($identifier) . "'");
      return $banner_result-fields;
    } else {
      return false;
    }
  }


 /**
  * Update the banner display statistics
  *
  * @param $banner_id
  */
  function oos_update_banner_display_count($banner_id) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $banners_historytable = $oostable['banners_history'];
    $banner_check = $dbconn->Execute("SELECT COUNT(*) AS total FROM $banners_historytable WHERE banners_id = '" . intval($banner_id) . "' AND date_format(banners_history_date, '%Y%m%d') = date_format(now(), '%Y%m%d')");
    if ($banner_check->fields['total'] > 0) {
      $banners_historytable = $oostable['banners_history'];
      $dbconn->Execute("UPDATE $banners_historytable
                  SET banners_shown = banners_shown + 1
                  WHERE banners_id = '" . intval($banner_id) . "' AND
                  date_format(banners_history_date, '%Y%m%d') = date_format(now(), '%Y%m%d')");
    } else {
      $banners_historytable = $oostable['banners_history'];
      $dbconn->Execute("INSERT INTO $banners_historytable
                  (banners_id,
                   banners_shown,
                   banners_history_date) VALUES ('" . intval($banner_id) . "',
                                                 1,
                                                 now())");
    }
  }


 /**
  * Update the banner click statistics
  *
  * @param $banner_id
  */
  function oos_update_banner_click_count($banner_id) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $banners_historytable = $oostable['banners_history'];
    $dbconn->Execute("UPDATE $banners_historytable
                  SET banners_clicked = banners_clicked + 1 
                  WHERE banners_id = '" . intval($banner_id) . "' 
                    AND date_format(banners_history_date, '%Y%m%d') = date_format(now(), '%Y%m%d')");
  }

?>