<?php
/* ----------------------------------------------------------------------
   $Id: redirect.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  switch ($_GET['action']) {

    case 'url':    if (isset($_GET['goto'])) {
                     oos_redirect('http://' . $_GET['goto']);
                   } else {
                     oos_redirect(oos_href_link($aContents['main']));
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
                                oos_redirect(oos_href_link($aContents['main']));
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
                            oos_redirect(oos_href_link($aContents['main']));
                          }
                          break;

    default:       oos_redirect(oos_href_link($aContents['main']));
                   break;
  }

