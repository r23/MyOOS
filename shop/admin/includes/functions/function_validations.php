<?php
/* ----------------------------------------------------------------------
   $Id: function_validations.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: validations.php,v 1.11 2003/02/11 01:31:02 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/**
 * validations
 *
 * @package validations
 * @copyright (C) 2006 by the MyOOS Development Team.
 * @license GPL <http://www.gnu.org/licenses/gpl.html>
 * @link http://www.oos-shop.de/
 */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

/**
 * Valid e-Mail - Addresses
 *
 * This function is converted from a JavaScript written by
 * Sandeep V. Tamhankar (stamhankar@hotmail.com). The original JavaScript
 * is available at http://javascript.internet.com
 *
 * @param $sEmail
 * @return boolean
 */
function oos_validate_is_email($sEmail) {
 
    if (!is_string($sEmail)) return false;
 
    $bValidAddress = true;

    $mail_pat = '^(.+)@(.+)$';
    $valid_chars = "[^] \(\)<>@,;:\.\\\"\[]";
    $atom = "$valid_chars+";
    $quoted_user='(\"[^\"]*\")';
    $word = "($atom|$quoted_user)";
    $user_pat = "^$word(\.$word)*$";
    $ip_domain_pat='^\[([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\]$';
    $domain_pat = "^$atom(\.$atom)*$";


    if (preg_match($mail_pat, $sEmail, $components)) {
        $user = $components[1];
        $domain = $components[2];
        // validate user
        if (preg_match($user_pat, $user)) {
            // validate domain
            if (preg_match($ip_domain_pat, $domain, $ip_components)) {
                // this is an IP address
            	  for ($i=1;$i<=4;$i++) {
      	            if ($ip_components[$i] > 255) {
      	                $bValidAddress = false;
                	      break;
      	            }
                }
            } else {
                // Domain is a name, not an IP
                if (preg_match($domain_pat, $domain)) {
                    /* domain name seems valid, but now make sure that it ends in a valid TLD or ccTLD
                     and that there's a hostname preceding the domain or country. */
                    $domain_components = explode(".", $domain);
                    // Make sure there's a host name preceding the domain.
                    if (count($domain_components) < 2) {
                        $bValidAddress = false;
                    } else {
                        $top_level_domain = strtolower($domain_components[count($domain_components)-1]);
                        // Allow all 2-letter TLDs (ccTLDs)
                        if (preg_match('/^[a-z][a-z]$/i', $top_level_domain) != 1) {
                            $sTld = get_all_top_level_domains();
                            if (preg_match("/".$sTld."/i", $top_level_domain) == 0) {
                                $bValidAddress = false;
                            }
                        }
                    }
                } else {
      	            $bValidAddress = false;
            	  }
          	}
        } else {
            $bValidAddress = false;
        }
    } else {
        $bValidAddress = false;
    }
    if ($bValidAddress && ENTRY_EMAIL_ADDRESS_CHECK == '1') {
        if (!checkdnsrr($domain, "MX") && !checkdnsrr($domain, "A")) {
          $bValidAddress = false;
        }
    }
    return $bValidAddress;
}


/**
 * test if a value is a valid URL
 *
 * @param string $sUrl the value being tested
 */
function oos_validate_is_url($sUrl) {
   if (strlen($sUrl) == 0) {
     return false;
   }

   return preg_match('!^http(s)?://[\w-]+\.[\w-]+(\S+)?$!i', $sUrl);
}


/**
 * A list of all TLDs that result in two part
 * domain names.
 *
 * @return string
 * @access public
 * @static
 *
 * @TODO Pipe separated list.
 */
function get_all_top_level_domains() {
   return '^com$|^edu$|^net$|^org$|^gov$|^mil$|^int$|^biz$|^info$|^name$|^pro$|^aero$|^coop$|^museum$';
}
