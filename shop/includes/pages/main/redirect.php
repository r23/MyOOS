<?php
/* ----------------------------------------------------------------------
   $Id: redirect.php,v 1.1 2007/06/07 16:50:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: redirect.php,v 1.9 2003/02/13 04:23:23 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  switch ($_GET['action']) {
    case 'banner': $bannerstable = $oostable['banners'];
                   $sql = "SELECT banners_url
                           FROM $bannerstable
                           WHERE banners_id = '" . oos_db_input($_GET['goto']) . "'";
                   $banner_result = $dbconn->Execute($sql);
                   if ($banner_result->RecordCount()) {
                     $banner = $banner_result->fields;
                     oos_update_banner_click_count($_GET['goto']);
                     oos_redirect($banner['banners_url']);
                   } else {
                     oos_redirect(oos_href_link($aModules['main'], $aFilename['main']));
                   }
                   break;

    case 'url':    if (isset($_GET['goto'])) {
                     oos_redirect('http://' . $_GET['goto']);
                   } else {
                     oos_redirect(oos_href_link($aModules['main'], $aFilename['main']));
                   }
                   break;

    case 'links':  require 'includes/functions/function_links.php';
                   $linkstable = $oostable['links'];
                   $links_sql = "SELECT links_url
                                 FROM $linkstable
                                 WHERE links_id = '" . intval($_GET['goto']) . "'";
                   $links_result = $dbconn->Execute($links_sql);
                   if ($links_result->RecordCount()) {
                     $link = $links_result->fields;
                     oos_update_links_click_count($_GET['goto']);
                     oos_redirect($link['links_url']);
                   } else {
                     oos_redirect(oos_href_link($aModules['main'], $aFilename['main']));
                   }
                   break;

    case 'manufacturer' : if (isset($_GET['manufacturers_id'])) {
                            $manufacturers_id = intval($_GET['manufacturers_id']);
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
                                oos_redirect(oos_href_link($aModules['main'], $aFilename['main']));
                              } else {
                                $manufacturer = $manufacturer_result->fields;
                                $manufacturers_infotable = $oostable['manufacturers_info'];
                                $dbconn->Execute("UPDATE $manufacturers_infotable SET url_clicked = url_clicked+1, date_last_click = now() WHERE manufacturers_id = '" . intval($manufacturers_id) . "' AND manufacturers_languages_id = '" . $manufacturer['manufacturers_languages_id'] . "'");
                              }
                            } else {
                              // url exists in selected language
                              $manufacturer = $manufacturer_result->fields;
                              $manufacturers_infotable = $oostable['manufacturers_info'];
                              $dbconn->Execute("UPDATE $manufacturers_infotable SET url_clicked = url_clicked+1, date_last_click = now() WHERE manufacturers_id = '" . intval($manufacturers_id) . "' AND manufacturers_languages_id = '" .  intval($nLanguageID) . "'");
                            }

                            oos_redirect($manufacturer['manufacturers_url']);
                          } else {
                            oos_redirect(oos_href_link($aModules['main'], $aFilename['main']));
                          }
                          break;

    default:       oos_redirect(oos_href_link($aModules['main'], $aFilename['main']));
                   break;
  }

?>
