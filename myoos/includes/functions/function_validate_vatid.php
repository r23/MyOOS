<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
  ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

  /**
   * VALID VAT NUMBER
   *
   * @package VATChecker
   * @license GPL <http://www.gnu.org/licenses/gpl.html>
   * @link    http://www.oos-shop.de
   */

  /**
   * ensure this file is being included by a parent file
   */
  defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');


 /**
  * Send request to VIES site and retrieve results
  *
  * @access public
  * @param  string
  * @return mixed
  */
function load_data($url)
{
    $url = parse_url((string) $url);

    if (!in_array($url['scheme'], ['','http'])) {
        return false;
    }

    $fp = fsockopen($url['host'], ($url['port'] > 0 ? $url['port'] : 80), $errno, $errstr, 2);
    if (!$fp) {
        return false;
    } else {
        fputs($fp, "GET ".$url['path']. (isset($url['query']) ? '?'.$url['query'] : '')." HTTP/1.0\r\n");
        fputs($fp, "Host: ".$url['host']."\r\n");
        fputs($fp, "Connection: close\r\n\r\n");

        $data = '';
        stream_set_blocking($fp, false);
        stream_set_timeout($fp, 4);
        $status = socket_get_status($fp);
        while (!feof($fp) && !$status['timed_out']) {
            $data .= fgets($fp, 1000);
            $status = socket_get_status($fp);
        }

        if ($status['timed_out']) {
            return false;
        }
        fclose($fp);

        return $data;
    }
}


 /**
  * Send & request to VIES site and interprets results
  *
  * @access public
  * @param  string
  * @return boolean
  */
function oos_validate_is_vatid($sVatno)
{
    $sVatno = trim((string) $sVatno);
    $sVatno = strtoupper($sVatno);

    $aRemove = [' ', '-', '/', '.', ':', ',', ';', '#'];
	
	$n = is_countable($aRemove) ? count($aRemove) : 0;
    for ($i=0, $n; $i<$n; $i++) {
        $sVatno = str_replace($aRemove[$i], '', $sVatno);
    }

    $sViesMS = substr($sVatno, 0, 2);
    $sVatno = substr($sVatno, 2);

    $urlVies = 'http://ec.europa.eu/taxation_customs/vies/cgi-bin/viesquer/?VAT='. $sVatno . '&MS=' . $sViesMS . '&Lang=EN';

    $DataHTML = load_data($urlVies);
    if (!$DataHTML) {
        return false;
    }

    $ViesOk = 'YES, VALID VAT NUMBER';
    $ViesEr = 'NO, INVALID VAT NUMBER';

    $DataHTML = '#' . strtoupper((string) $DataHTML);

    return ((strPos($DataHTML, $ViesOk) > 0) ? true : false);
}
