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
require_once MYOOS_INCLUDE_PATH . '/includes/lib/iban/php-iban.php');

$aBTValidationResultSet = array ();

class SepaAccountCheck {

/* Folgende Returncodes werden übergeben:                                                 */
/* 1 ->   Es wurde kein Kontoinhaber angegeben.                                           */
/* 4 ->   Kein Land zur Validierung der IBAN übergeben                                    */
/* 5 ->   Diese BIC in lokaler DB nicht gefunden.                                       */
/* 7 ->   IBAN Validierung fehlerhaft                                                     */
/* 8 ->   Es wurde keine SWIFT uebergeben.                                                */
/* 9 ->   Es wurde keine IBAN uebergeben.                                                 */
/* 10 ->  Die Länge der IBAN ist falsch                                                   */


	/* -------- Funktion zum externen Aufruf der Pruefung ---------- */
	function CheckAccount($dd_owner, $dd_iban, $dd_bic, $country_id) {
		// Erst einmal RICHTIG.
		$Result = 0;
		// Keinen Kontoinhaber uebergeben.
		if ($dd_owner == '' || strlen($dd_owner ) < 5) { return 1;}
		// Keine BIC uebergeben.
		if ($dd_bic == '') { return 8; }
		// Kein Land zur Kontovalidierung angegeben
		$iso_code_data = $this->GetCountryInformation($country_id);
		if ($iso_code_data['countries_iso_code_2'] == '' || $country_id == 0 ){
			return 4;
		} else {
			$this->aBTValidationResultSet['V_BANK_COUNTRY_CODE'] =  $iso_code_data['countries_iso_code_2'];
		}
		// Keine IBAN uebergeben.
		if ($dd_iban == '') { return 9; }
		// Länge der IBAN prüfen
		if ( strlen( iban_to_machine_format($dd_iban)) != iban_country_get_bban_length($iso_code_data['countries_iso_code_2']) + 4 ) return 10;
    
		// BIC in lokaler DB suchen  -- im Fehlerfall return 5
		$Result = $this->UseLocalService($dd_iban, $dd_bic);
    
		if(!verify_iban($dd_iban)) {return 7; }
    
		return $Result;
	}  /* End of CheckAccount */

	// ---------- Nutzung des lokalen Service ---------------------------
	function UseLocalService($dd_iban, $dd_bic) {
		// Datenbankabfrage der Bankleitzahl
		$adata = $this->GetAccountInformation($dd_bic);
		// Rueckgabearray erzeugen.
		$this->aBTValidationResultSet = array ();
		// Die Bankleitzahl wurde nicht gefunden.
		if ($adata == false) {
			$Result = 5; // BLZ nicht gefunden;
			$this->aBTValidationResultSet['V_BANK_BIC']      = '';
			$this->aBTValidationResultSet['V_BANK_NAME']        = '';
			$this->aBTValidationResultSet['V_BANK_ADDRESSE']       = '';
			$this->aBTValidationResultSet['V_BANK_ORT']             = '';
			// Notendige Rueckgabewerte initialisieren und Pruefziffer berechnen.
		} else {
			$Result = 0;
			$this->aBTValidationResultSet['V_BANK_BIC']       = $adata['bic'];
			$this->aBTValidationResultSet['V_BANK_NAME']        = $adata['name'];
			$this->aBTValidationResultSet['V_BANK_ADDRESSE']       = $adata['addresse'];
			$this->aBTValidationResultSet['V_BANK_ORT']             = $adata['ort'];
		}
		return $Result;
	}
  
  
	// ----------  Diese -Function gibt die Bankinformationen aus der Datenbank zurück ---------------*/
	function GetAccountInformation($bic) {
		$bank_query = tep_db_query("SELECT * from " . TABLE_SEPABT_SEPA . " WHERE bic = '" . $bic . "'");
		if (tep_db_num_rows($bank_query)){
			$adata = tep_db_fetch_array ($bank_query);
		} else {
			$adata = false;
		}
		return $adata;
	}

	// ----------  Diese -Function gibt das Länderkennzeichen zurück ---------------*/
	function GetCountryInformation($country_id) {
		$iso_code_query = tep_db_query("SELECT countries_iso_code_2 FROM " . TABLE_COUNTRIES . " where countries_id = '" . (int)$country_id . "'");
		if (tep_db_num_rows($iso_code_query)){
			$iso_code_data = tep_db_fetch_array ($iso_code_query);
		} else {
			$iso_code_data = false;
		}
		return $iso_code_data;
  }

	// -----------  Diese Funktion ueberprueft, ob das Lastschriftmodul installiert ist. -------------*/
	function IsGBTInstalled () {
		if (ereg ( 'sepabanktransfer.php', MODULE_PAYMENT_INSTALLED) ) {
			$is_installed = true;
		} else {
			$is_installed = false;
		}
		return $is_installed;
	}
}  

