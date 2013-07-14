<?php
/* ----------------------------------------------------------------------
   $Id: function.html_href_link.php 216 2013-04-02 08:24:45Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: html_output.php,v 1.49 2003/02/11 01:31:02 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {html_href_link} function plugin
 *
 * Type:     function
 * Name:     html_href_link
 * @Version:  $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/08 13:34:16 $
 * -------------------------------------------------------------
 */

function smarty_function_html_href_link($params, &$smarty)
{
    global $oEvent, $spider_kill_sid;

    require_once(SMARTY_PLUGINS_DIR . 'shared.escape_special_chars.php');

    $content = '';
    $parameters = '';
    $connection = 'NONSSL';
    $add_session_id = 'true';
    $search_engine_safe = 'true';

    foreach($params as $_key => $_val) {
      switch($_key) {

        case 'content':	
          if(!is_array($_val)) {
            $$_key = smarty_function_escape_special_chars($_val);
          } else {
            throw new SmartyException ("html_href_link: Unable to determine the page link!", E_USER_NOTICE);
          }
          break;

        case 'oos_get':
        case 'addentry_id': 
        case 'connection':
        case 'add_session_id':
        case 'search_engine_safe':
            $$_key = (string)$_val;
            break;

        case 'anchor':
            $anchor = smarty_function_escape_special_chars($_val);
            break;

        default:
          if(!is_array($_val)) {
            $parameters .= $_key.'='.smarty_function_escape_special_chars($_val).'&amp;';
          } else {
            throw new SmartyException ("html_href_link: parameters '$_key' cannot be an array", E_USER_NOTICE);
          }
          break;
       }
    }

    if (empty($content)) {
      throw new SmartyException ("html_href_link: Unable to determine the page link!", E_USER_NOTICE);
    }

    if (isset($addentry_id)) {
      $addentry_id = $addentry_id + 2;
      $parameters .= 'entry_id='.$addentry_id.'&amp;';
    }
    if (isset($oos_get)) {
      $parameters .= $oos_get;
    }

    $content = trim($content);

    if ($connection == 'NONSSL') {
      $sLink = OOS_HTTP_SERVER . OOS_SHOP;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL == 'true') {
        $sLink = OOS_HTTPS_SERVER . OOS_SHOP;
      } else {
        $sLink = OOS_HTTP_SERVER . OOS_SHOP;
      }
    } else {
      throw new SmartyException ("html_href_link: Unable to determine the page link!", E_USER_NOTICE);
    }

    if (isset($parameters)) {
      $sLink .= 'index.php?content=' . $content . '&amp;' . oos_output_string($parameters);
    } else {
      $sLink .= 'index.php?content=' . $content;
	}
    $separator = '&amp;';

    while ( (substr($sLink, -5) == '&amp;') || (substr($sLink, -1) == '?') ) {
      if (substr($sLink, -1) == '?') {
        $sLink = substr($sLink, 0, -1);
      } else {
        $sLink = substr($sLink, 0, -5);
      }
    }


// Add the session ID when moving from HTTP and HTTPS servers or when SID is defined
    if ( (ENABLE_SSL == 'true' ) && ($connection == 'SSL') && ($add_session_id == 'true') ) {
      $_sid = oos_session_name() . '=' . oos_session_id();
    } elseif ( ($add_session_id == 'true') && (oos_is_not_null(SID)) ) {
      $_sid = SID;
    }

    if ( $spider_kill_sid == 'true') $_sid = NULL;


    if ( ($search_engine_safe == 'true') &&  $oEvent->installed_plugin('sefu') ) {
      $sLink = str_replace(array('?', '&amp;', '='), '/', $sLink);

      $separator = '?';

      $pos = strpos ($sLink, 'action');
      if ($pos === false) {
        $url_rewrite = new url_rewrite;
        $sLink = $url_rewrite->transform_uri($sLink);
      }
    }

	if (isset($anchor)) {
      $sLink .= '#' . $anchor;
    }
	
	
    if (isset($_sid)) {
      $sLink .= $separator . oos_output_string($_sid);
    }

    return $sLink;
  }

