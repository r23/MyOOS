<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     oos_back_link
 * Version:  0.1
 * Date:     August 05, 2005
 * Install:  Drop into the plugin directory
 *
 * Author:   r23 <info at r23 dot de>
 * -------------------------------------------------------------
 */


function smarty_function_oos_back_link($params, &$smarty)
{

  $aModules = oos_get_modules();
  $aFilename =  oos_get_filename();

  if (count($_SESSION['navigation']->path)-2 > 0) {
    $back = count($_SESSION['navigation']->path)-2;
    $link = oos_href_link($_SESSION['navigation']->path[$back]['modules'], $_SESSION['navigation']->path[$back]['file'], $_SESSION['navigation']->path[$back]['get'].'&amp;history_back=true', $_SESSION['navigation']->path[$back]['mode']);
  } else {
    if (isset($_SERVER['HTTP_REFERER']) && strstr(HTTP_SERVER, $_SERVER['HTTP_REFERER'])) {
      $link = $_SERVER['HTTP_REFERER'];
    } else {
      $link = oos_href_link($aModules['main'], $aFilename['main']);
    }
  }

  while ( (substr($link, -5) == '&amp;') || (substr($link, -1) == '?') ) {
    if (substr($link, -1) == '?') {
      $link = substr($link, 0, -1);
    } else {
      $link = substr($link, 0, -5);
    }
  }

  return $link;

}
?>