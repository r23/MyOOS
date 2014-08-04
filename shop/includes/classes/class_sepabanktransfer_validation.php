<?php
/* ----------------------------------------------------------------------
   $Id: class_banktransfer_validation.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   Sepabanktransfer(Lastschrft)

   Erstellt    19.10.2010    0.9

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2007 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

// Laden der Pruefroutinenmethoden.
require_once OOS_ABSOLUTE_PATH . '/includes/lib/iban/php-iban.php';


class SepaAccountCheck {

/* Folgende Returncodes werden übergeben:                                                 */
/* 1 ->   Es wurde kein Kontoinhaber angegeben.                                           */
/* 4 ->   Kein Land zur Validierung der IBAN übergeben                                    */
/* 5 ->   Diese BIC in lokaler DB nicht gefunden.                                       */
/* 7 ->   IBAN Validierung fehlerhaft                                                     */
/* 8 ->   Es wurde keine BIC uebergeben.                                                */
/* 9 ->   Es wurde keine IBAN uebergeben.                                                 */
/* 10 ->  Die Länge der IBAN ist falsch                                                   */


	/* -------- Funktion zum externen Aufruf der Pruefung ---------- */
	function CheckAccount($owner, $iban, $bic, $country_id) {
		// Erst einmal RICHTIG.
		$Result = 0;
		
		// Keinen Kontoinhaber uebergeben.
		if ($owner == '' || strlen($owner ) < 5) { return 1;}

		// Keine BIC uebergeben.
		if ($bic == '') { return 8; }
		
		// Kein Land zur Kontovalidierung angegeben
		
		// Keine IBAN uebergeben.
		if ($iban == '') { return 9; }
		
		// Länge der IBAN prüfen
		// if ( strlen( iban_to_machine_format($iban)) != iban_country_get_bban_length($iso_code_data['countries_iso_code_2']) + 4 ) return 10;
    
		// BIC in lokaler DB suchen  -- im Fehlerfall return 5
		// $Result = $this->UseLocalService($iban, $bic);
    
		if(!verify_iban($iban)) {return 7; }
    
		return $Result;
	}  /* End of CheckAccount */

}  

