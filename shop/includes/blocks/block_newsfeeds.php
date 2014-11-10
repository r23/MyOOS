<?php
/* ----------------------------------------------------------------------
   $Id: block_newsfeeds.php,v 1.1 2007/06/07 11:55:41 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  $newsfeeds_block = 'false';

  $newsfeed_managertable = $oostable['newsfeed_manager'];
  $random_select = "SELECT newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name,
                           newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles,
                           newsfeed_manager_refresh, newsfeed_manager_status
                    FROM $newsfeed_managertable
                    WHERE newsfeed_manager_status = '1'
                      AND newsfeed_manager_languages_id = '" .  intval($nLanguageID) . "'";
  if (isset($newsfeed_categories_id) && ($newsfeed_categories_id > 0)) {
    $random_select .= " AND newsfeed_categories_id = '" . $newsfeed_categories_id . "'";
  }
  $random_select .= " ORDER BY newsfeed_manager_id DESC";
  $random_newsfeed = oos_random_select($random_select, MAX_RANDOM_SELECT_NEWSFEED);

  if ($random_newsfeed) {
    $newsfeeds_block = 'true';
    $newsfeeds = '<b>' . $random_newsfeed['newsfeed_manager_name'] . '</b><br />' .
                 strftime(DATE_TIME_FORMAT) . '<br />' ;

    require_once 'includes/classes/class_rdf.php';
    $rdf = new oosRDF();

    $rdf->use_dynamic_display(true);
    $rdf->set_Options(array('channel' => 'hidden',
                            'build' => 'hidden',
                            'cache_update' => 'hidden',
                            'textinput' => 'hidden',
                            'image' => ''));
    $rdf->set_max_item($random_newsfeed['newsfeed_manager_numarticles']);
    $rdf->set_refresh($random_newsfeed['newsfeed_manager_refresh']);

    ob_start();
    $rdf->parse_RDF($random_newsfeed['newsfeed_manager_link']);
    $newsfeeds .= ob_get_contents();
    ob_end_clean();
    $rdf->finish();

    $oSmarty->assign(
        array(
            'block_heading_newsfeeds' => $block_heading,
            'newsfeeds' => $newsfeeds
        )
    );
 }
 $oSmarty->assign('newsfeeds_block', $newsfeeds_block);

?>
