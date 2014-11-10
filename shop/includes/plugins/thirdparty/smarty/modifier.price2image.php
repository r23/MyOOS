<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     price2image
 * Version:  0.4
 * Date:     Nov 30, 2006
 * Install:  Drop into the plugin directory
 *
 * Examples: {$products_special_price|price2image:true}
 *           {$products_special_price|price2image}
 * Author:   r23 <info at r23 dot de>
 * -------------------------------------------------------------
 */

if (!defined('OOS_IMAGE_EXTENSION')) {
    define('OOS_IMAGE_EXTENSION', 'gif'); // gif or png
}


function smarty_modifier_price2image($string, $special = false)
{
    global $oEvent, $oCurrencies, $aLang;

    if ($_SESSION['member']->group['show_price'] != 1) {
      return $aLang['no_login_no_prices_display'];
    }

    if ($oEvent->installed_plugin('down_for_maintenance')) {
      return $aLang['down_for_maintenance_no_prices_display'];
    } else {

      $image = '';

      $currency_info = array();
      $currency_info = $oCurrencies->get_currencies_info($_SESSION['currency']);

      $symbol_left = $currency_info['symbol_left'];
      $symbol_right = $currency_info['symbol_right'];
      $decimal_point = $currency_info['decimal_point'];
      $code = oos_currency_exits($_SESSION['currency']);

      if ($special == true) {
        $color = 'r';
      } else {
        $color = 'g';
      }

      switch($code) {
        case 'EUR':
        case '&euro;':
           $image .= '<img src="' . OOS_IMAGES . 'price/bEUR'. $color . '.' . OOS_IMAGE_EXTENSION . '" alt="EUR">';
           break;

        case 'USD':
           $image .= '<img src="' . OOS_IMAGES . 'price/bUSD'. $color . '.' . OOS_IMAGE_EXTENSION . '" alt="USD">';
           break;

        case 'CAD':
           $image .= '<img src="' . OOS_IMAGES . 'price/bUSD'. $color . '.' . OOS_IMAGE_EXTENSION . '" alt="CAD">';
           break;

        case 'CHF':
        case 'SFr.':
           $image .= '<img src="' . OOS_IMAGES . 'price/bCHF'. $color . '.' . OOS_IMAGE_EXTENSION . '" alt="CHF">';
           break;

        default:
          if ( (isset($symbol_left)) && (!empty($symbol_left)) ) {
            $image .= '<img src="' . OOS_IMAGES . 'price/b' . $code . $color . '.' . OOS_IMAGE_EXTENSION . '" alt="' . $code . '">';
          }
          break;
      }


      $string = str_replace($symbol_left, '', $string);
      $string = str_replace($symbol_right, '', $string);
      $string = trim($string);

      list($left, $right) = explode($decimal_point, $string);

      for ($i=0; $i<strlen($left); $i++) {
        $image .= '<img src="' . OOS_IMAGES . 'price/b' . ord($left[$i]) . $color . '.' . OOS_IMAGE_EXTENSION . '" alt="' . $left[$i] . '">';
      }

      // $decimal_point
      $asc = ord($decimal_point);
      switch($asc) {
        case '44':
        case '46':
            $image .= '<img src="' . OOS_IMAGES . 'price/b' . $asc . $color . '.' . OOS_IMAGE_EXTENSION . '" alt=".">';
            break;

        default:
            $image .= '<img src="' . OOS_IMAGES . 'price/b44' . $color . '.' . OOS_IMAGE_EXTENSION . '" alt=",">';
            break;
      }

      for ($i=0; $i<strlen($right); $i++) {
        $image .= '<img src="' . OOS_IMAGES . 'price/s' . ord($right[$i]) . $color . '.' . OOS_IMAGE_EXTENSION . '" alt="' . $right[$i] . '">';
      }

      switch($code) {
        case 'EUR':
        case '&euro;':
           // $image .= '<img src="' . OOS_IMAGES . 'price/bEUR'. $color . '.' . OOS_IMAGE_EXTENSION . '" alt="EUR">';
           break;

        case 'USD':
           // $image .= '<img src="' . OOS_IMAGES . 'price/bUSD'. $color . '.' . OOS_IMAGE_EXTENSION . '" alt="USD">';
           break;

        case 'CAD':
           // $image .= '<img src="' . OOS_IMAGES . 'price/bUSD'. $color . '.' . OOS_IMAGE_EXTENSION . '" alt="CAD">';
           break;


        case 'CHF':
        case 'SFr.':
           // $image .= '<img src="' . OOS_IMAGES . 'price/bCHF'. $color . '.' . OOS_IMAGE_EXTENSION . '" alt="CHF">';
           break;


        default:
           if ( (isset($symbol_right)) && (!empty($symbol_right)) ) {
             $image .= '<img src="' . OOS_IMAGES . 'price/b' . $code . $color . '.' . OOS_IMAGE_EXTENSION . '" alt="' . $code . '">';
           }
           break;
       }
    }

    return  $image;

}
?>