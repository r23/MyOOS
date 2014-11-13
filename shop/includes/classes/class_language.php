<?php
/* ----------------------------------------------------------------------
   $Id: class_language.php,v 1.2 2008/07/22 13:16:53 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   browser language detection logic
   Copyright phpMyAdmin (select_lang.lib.php3 v1.24 04/19/2002)
   Copyright Stephane Garin <sgarin@sgarin.com> (detect_language.php v0.1 04/02/2002)

   File: language.php,v 1.6 2003/06/28 16:53:09 dgw_
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );


class language {
    var $languages;
    var $_languages = array();

    function language() {

        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $languagestable = $oostable['languages'];
        $languages_sql = "SELECT languages_id, name, iso_639_2, iso_639_1
                          FROM $languagestable
                          WHERE status = '1'
                          ORDER BY sort_order";
        if (USE_DB_CACHE == 'true')
		{
			$languages_result = $dbconn->CacheExecute(3600*24, $languages_sql);
        }
		else
		{
			$languages_result = $dbconn->Execute($languages_sql);
        }

        while ($languages = $languages_result->fields)
		{
			$this->_languages[$languages['iso_639_2']] = array('id' => $languages['languages_id'],
																'name' => $languages['name'],
																'iso_639_2' => $languages['iso_639_2'],
																'iso_639_1' => $languages['iso_639_1']);
			// Move that ADOdb pointer!
			$languages_result->MoveNext();
        }
    }


    function set_language($sLang = '') {

      if ( (oos_is_not_null($sLang)) && ($this->exists($sLang) === TRUE)) {
        $this->language = $this->get($sLang);
      } else {
        $this->language = $this->get(DEFAULT_LANGUAGE);
      }

       if (isset($_SESSION['customer_id'])) {
         $dbconn =& oosDBGetConn();
         $oostable =& oosDBGetTables();

         $sLanguage = oos_var_prep_for_os($this->language['iso_639_2']);
         $customerstable = $oostable['customers'];
         $query = "UPDATE $customerstable SET customers_language =? WHERE customers_id =?";
         $result = $dbconn->Execute($query, array($sLanguage, (int)$_SESSION['customer_id']));
       }

    }


    function get_browser_language() {

      $http_accept_language = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

      $browser_languages = array(
         'af' => 'af|afrikaans',
         'ar' => 'ar([-_][[:alpha:]]{2})?|arabic',
         'az' => 'az|azerbaijani',
         'bg' => 'bg|bulgarian',
         'br' => 'pt[-_]br|brazilian portuguese',
         'bs' => 'bs|bosnian',
         'ca' => 'ca|catalan',
         'cs' => 'cs|czech',
         'da' => 'da|danish',
         'deu' => 'de([-_][[:alpha:]]{2})?|german',
         'el' => 'el|greek',
         'eng' => 'en([-_][[:alpha:]]{2})?|english',
         'spa' => 'es([-_][[:alpha:]]{2})?|spanish',
         'et' => 'et|estonian',
         'fi' => 'fi|finnish',
         'fra' => 'fr([-_][[:alpha:]]{2})?|french',
         'gl' => 'gl|galician',
         'hu' => 'hu|hungarian',
         'ita' => 'it|italian',
         'ka' => 'ka|georgian',
         'lt' => 'lt|lithuanian',
         'nl' => 'nl([-_][[:alpha:]]{2})?|dutch',
         'no' => 'no|norwegian',
         'pol' => 'pl|polish',
         'pt' => 'pt([-_][[:alpha:]]{2})?|portuguese',
         'ro' => 'ro|romanian',
         'rus' => 'ru|russian',
         'sk' => 'sk|slovak',
         'sr' => 'sr|serbian',
         'sv' => 'sv|swedish',
         'tr' => 'tr|turkish',
         'uk' => 'uk|ukrainian',
         'zh' => 'zh|chinese simplified');

      foreach ($http_accept_language as $browser_language) {
        foreach ($browser_languages as $key => $value) {
          if (preg_match('/^(' . $value . ')(;q=[0-9]\\.[0-9])?$/', $browser_language) && $this->exists($key)) {
            $this->set_language($key);
            return TRUE;
          }
        }
      }

      $this->set_language(DEFAULT_LANGUAGE);
    }


    function get($sLang) {
      return $this->_languages[$sLang];
    }

    function getAll() {
      return $this->_languages;
    }

    function exists($sLang) {
      return array_key_exists($sLang, $this->_languages);
    }

    function getID() {
      return $this->language['id'];
    }

    function getName() {
      return $this->language['name'];
    }

    function getCode() {
      return $this->language['iso_639_2'];
    }
}
