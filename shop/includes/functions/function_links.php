<?php
/* ----------------------------------------------------------------------
   $Id: function_links.php,v 1.1 2007/06/12 16:49:27 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: links.php,v 1.00 2003/10/03
   ----------------------------------------------------------------------
   Links Manager

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

 /**
  * Links Manager
  *
  * @link http://www.oos-shop.de/
  * @package Links Manager
  * @version $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/12 16:49:27 $
  */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

 /**
  * Construct a path to the link
  *
  * @param $nLinksId
  * @return string
  */
  function oos_get_links_path($nLinksId) {

    $lPath = '';

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $linkstable = $oostable['links'];
    $links_to_link_categoriestable = $oostable['links_to_link_categories'];
    $category_sql = "SELECT l2c.link_categories_id
                     FROM $linkstable l,
                          $links_to_link_categoriestable l2c
                     WHERE l.links_id = '" . intval($nLinksId) . "'
                       AND l.links_id = l2c.links_id";
    $category = $dbconn->SelectLimit($category_sql, 1);
    if ($category->RecordCount() >= 1) {
      $lPath = $category->fields['link_categories_id'];
    }

    return $lPath;
  }


 /**
  * The HTML image wrapper function
  *
  * @param $src
  * @param $alt
  * @param $width
  * @param $height
  * @param $parameters
  * @return string
  */
  function oos_href_links_image($src, $alt = '', $width = '', $height = '', $parameters = '') {
    if ( (empty($src) || ($src == OOS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {
      return false;
    }

    // alt is added to the img tag even if it is null to prevent browsers from outputting
    // the image filename as default
    $image = '<img src="' . oos_output_string($src) . '" border="0" alt="' . oos_output_string($alt) . '"';

    if (oos_is_not_null($alt)) {
      $image .= ' title=" ' . oos_output_string($alt) . ' "';
    }

    if ( (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) {
      if ($image_size = @getimagesize($src)) {
        if (empty($width) && oos_is_not_null($height)) {
          $ratio = $height / $image_size[1];
          $width = $image_size[0] * $ratio;
        } elseif (oos_is_not_null($width) && empty($height)) {
          $ratio = $width / $image_size[0];
          $height = $image_size[1] * $ratio;
        } elseif (empty($width) && empty($height)) {
          $width = $image_size[0];
          $height = $image_size[1];
        }
      } elseif (IMAGE_REQUIRED == 'false') {
        return false;
      }
    }

    // VJ begin maintain image proportion
    $calculate_image_proportion = 'true';

    if( ($calculate_image_proportion == 'true') && (!empty($width) && !empty($height))) {
      if ($image_size = @getimagesize($src)) {
        $image_width = $image_size[0];
        $image_height = $image_size[1];

        if (($image_width != 1) && ($image_height != 1)) {
          $whfactor = $image_width/$image_height;
          $hwfactor = $image_height/$image_width;

          if ( !($image_width > $width) && !($image_height > $height)) {
            $width = $image_width;
            $height = $image_height;
          } elseif ( ($image_width > $width) && !($image_height > $height)) {
            $height = $width * $hwfactor;
          } elseif ( !($image_width > $width) && ($image_height > $height)) {
            $width = $height * $whfactor;
          } elseif ( ($image_width > $width) && ($image_height > $height)) {
            if ($image_width > $image_height) {
              $height = $width * $hwfactor;
            } else {
              $width = $height * $whfactor;
            }
          }
        }
      }
    }
    //VJ end maintain image proportion

    if (oos_is_not_null($width) && oos_is_not_null($height)) {
      $image .= ' width="' . oos_output_string($width) . '" height="' . oos_output_string($height) . '"';
    }

    if (oos_is_not_null($parameters)) $image .= ' ' . $parameters;

    $image .= '>';

    return $image;
  }


 /**
  * Return the links url, based on whether click count is turned on/off
  *
  * @param $nIdentifier
  * @return string
  */
  function oos_get_links_url($nIdentifier) {
    global $spider_agent, $spider_ip, $spider_flag;

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $aFilename = oos_get_filename();
    $aModules = oos_get_modules();

    $linkstable = $oostable['links'];
    $links_sql = "SELECT links_id, links_url
                  FROM $linkstable
                  WHERE links_id = '" . intval($nIdentifier) . "'";
    $links = $dbconn->GetRow($links_sql);

    $sLinks = oos_href_link($aModules['main'], $aFilename['redirect'], 'action=links&amp;goto=' . $link['links_id']);

    return $sLinks;
  }


 /**
  * Update the links click statistics
  *
  * @param $nLinksId
  */
  function oos_update_links_click_count($nLinksId) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $linkstable = $oostable['links'];
    $dbconn->Execute("UPDATE $linkstable
                      SET links_clicked = links_clicked + 1
                      WHERE links_id = '" . intval($nLinksId) . "'");
  }

?>
