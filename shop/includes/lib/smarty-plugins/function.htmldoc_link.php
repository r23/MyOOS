<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {htmldoc_link} function plugin
 *
 * Type:     function
 * Name:     htmldoc_link
 * @Version:  $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/08 13:34:16 $
 * -------------------------------------------------------------
 */

function smarty_function_htmldoc_link($params, &$smarty)
{

    global $oEvent, $spider_agent, $spider_ip, $spider_checked_for_spider, $spider_kill_sid;

        require_once(SMARTY_PLUGINS_DIR . 'shared.escape_special_chars.php');

    $modul = '';
    $file = '';
    $parameters = '';
    $connection = 'NONSSL';
    $add_session_id = 'true'; 
    $search_engine_safe = 'true';

    foreach($params as $_key => $_val) {
      switch($_key) {
        case 'modul':
          if(!is_array($_val)) {
            $$_key = smarty_function_escape_special_chars($_val);
          } else {
            $smarty->trigger_error("htmldoc_link: Unable to determine the page link!", E_USER_NOTICE);
          }
          break;

        case 'file':
          if(!is_array($_val)) {
            $$_key = smarty_function_escape_special_chars($_val);
          } else {
            $smarty->trigger_error("htmldoc_link: Unable to determine the page link!", E_USER_NOTICE);
          }
          break;

        case 'oos_get':
        case 'connection':
        case 'add_session_id':
            $$_key = (string)$_val;
            break;


        default:
          if(!is_array($_val)) {
            $parameters .= $_key.'='.smarty_function_escape_special_chars($_val).'&amp;';
          } else {
            $smarty->trigger_error("htmldoc_link: parameters '$_key' cannot be an array", E_USER_NOTICE);
          }
          break;
       }
    }


    if (empty($modul)) {
      $smarty->trigger_error("htmldoc_link: Unable to determine the page link!", E_USER_NOTICE);
    }

    if (empty($file)) {
      $smarty->trigger_error("htmldoc_link: Unable to determine the page link!", E_USER_NOTICE);
    }


    if (isset($oos_get)) {
      $parameters .= $oos_get;
    }

    $file = trim($file);

    if ($connection == 'NONSSL') {
      $doc_link = OOS_HTTP_SERVER . OOS_SHOP;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL == 'true') {
        $doc_link = OOS_HTTPS_SERVER . OOS_SHOP;
      } else {
        $doc_link = OOS_HTTP_SERVER . OOS_SHOP;
      }
    } else {
      $smarty->trigger_error("htmldoc_link: Unable to determine the page link!", E_USER_NOTICE);
    }

    $doc_link .= 'pdf.php?url=';

    if (isset($parameters)) {
      $link = OOS_HTTP_SERVER . OOS_SHOP . 'index.php?mp=' . $modul . '&amp;file=' . $file . '&amp;option=print&amp;pdf=true&' . oos_output_string($parameters);
    } else {
      $link = OOS_HTTP_SERVER . OOS_SHOP . 'index.php?mp=' . $modul . '&amp;file=' . $file . '&amp;option=print&amp;pdf=true';
    }

    $separator = '&amp;';

    while ( (substr($link, -5) == '&amp;') || (substr($link, -1) == '?') ) {
      if (substr($link, -1) == '?') {
        $link = substr($link, 0, -1);
      } else {
        $link = substr($link, 0, -5);
      }
    }

    if ( $spider_kill_sid == 'true') $_sid = NULL;


    if (isset($_sid)) {
      $link .= $separator . oos_output_string($_sid);
    }

    return $doc_link .  urlencode($link);
  }

?>