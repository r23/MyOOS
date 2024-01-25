<?php
/**
   ----------------------------------------------------------------------
   $Id: function.html_href_link.php,v 1.1 2007/06/08 13:34:16 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: html_output.php,v 1.49 2003/02/11 01:31:02 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage plugins
 */


/**
 * Smarty {html_href_link} function plugin
 *
 * Type:     function
 * Name:     html_href_link
 *
 * @Version: $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/08 13:34:16 $
 * -------------------------------------------------------------
 */

function smarty_function_html_href_link($params, &$smarty)
{
    global $session, $oEvent, $spider_kill_sid, $debug;

    include_once SMARTY_PLUGINS_DIR . 'shared.escape_special_chars.php';


    $content = '';
    $parameters = '';
    $add_session_id = true;
    $search_engine_safe = 'true';

    foreach ($params as $_key => $_val) {
        switch ($_key) {

            case 'content':
                if (!is_array($_val)) {
                    ${$_key} = smarty_function_escape_special_chars($_val);
                } else {
                    throw new SmartyException("html_href_link: Unable to determine the page link!", E_USER_NOTICE);
                }
                break;

            case 'oos_get':
            case 'addentry_id':
            case 'add_session_id':
            case 'search_engine_safe':
                ${$_key} = (string)$_val;
                break;

            case 'anchor':
                $anchor = smarty_function_escape_special_chars($_val);
                break;

            default:
                if (!is_array($_val)) {
                    $parameters .= $_key.'='.smarty_function_escape_special_chars($_val).'&amp;';
                } else {
                    throw new SmartyException("html_href_link: parameters '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;
        }
    }

    if (empty($content) && ($debug == 1)) {
        throw new SmartyException("html_href_link: Unable to determine the page link!", E_USER_NOTICE);
    }

    if (isset($addentry_id)) {
        $addentry_id = $addentry_id + 2;
        $parameters .= 'entry_id='.$addentry_id.'&amp;';
    }
    if (isset($oos_get)) {
        $parameters .= $oos_get;
    }

    $content = trim((string) $content);

    $link = OOS_HTTPS_SERVER . OOS_SHOP;

    if (isset($parameters)) {
        $link .= 'index.php?content=' . $content . '&amp;' . oos_output_string($parameters);
    } else {
        $link .= 'index.php?content=' . $content;
    }

    $separator = '&amp;';

    while ((str_ends_with($link, '&amp;')) || (str_ends_with($link, '?'))) {
        if (str_ends_with($link, '?')) {
            $link = substr($link, 0, -1);
        } else {
            $link = substr($link, 0, -5);
        }
    }

    if (isset($anchor)) {
        $link .= '#' . $anchor;
    }


    // Add the session ID when moving from HTTP and HTTPS servers or when SID is defined
    if (isset($_SESSION)) {
        // Add the session ID when moving from HTTP and HTTPS servers or when SID is defined

        if ($add_session_id == true) {
            $_sid = $session->getName() . '=' . $session->getId();
        }

        if ($spider_kill_sid == 'true') {
            $_sid = null;
        }
    }



    if (($search_engine_safe == 'true') &&  $oEvent->installed_plugin('sefu')) {
        $link = str_replace(['?', '&amp;', '='], '/', $link);

        $separator = '?';

        $pos = strpos($link, 'action');
        if ($pos === false) {
            $url_rewrite = new url_rewrite();
            $link = $url_rewrite->transform_uri($link);
        }
    }

    if (isset($_sid)) {
        $link .= $separator . oos_output_string($_sid);
    }


    return $link;
}
