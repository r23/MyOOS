<?php
/* ----------------------------------------------------------------------
   $Id: class_language.php 296 2013-04-13 14:48:55Z r23 $

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
    var $languages, $catalog_languages, $browser_languages, $language;

    function language($lng = '') {
      $this->languages = array('ar' => 'ar([-_][[:alpha:]]{2})?|arabic',
                               'bg' => 'bg|bulgarian',
                               'br' => 'pt[-_]br|brazilian portuguese',
                               'ca' => 'ca|catalan',
                               'cs' => 'cs|czech',
                               'da' => 'da|danish',
                               'de' => 'de([-_][[:alpha:]]{2})?|german',
                               'el' => 'el|greek',
                               'en' => 'en([-_][[:alpha:]]{2})?|english',
                               'es' => 'es([-_][[:alpha:]]{2})?|spanish',
                               'et' => 'et|estonian',
                               'fi' => 'fi|finnish',
                               'fr' => 'fr([-_][[:alpha:]]{2})?|french',
                               'gl' => 'gl|galician',
                               'he' => 'he|hebrew',
                               'hu' => 'hu|hungarian',
                               'id' => 'id|indonesian',
                               'it' => 'it|italian',
                               'ja' => 'ja|japanese',
                               'ko' => 'ko|korean',
                               'ka' => 'ka|georgian',
                               'lt' => 'lt|lithuanian',
                               'lv' => 'lv|latvian',
                               'nl' => 'nl([-_][[:alpha:]]{2})?|dutch',
                               'no' => 'no|norwegian',
                               'pl' => 'pl|polish',
                               'pt' => 'pt([-_][[:alpha:]]{2})?|portuguese',
                               'ro' => 'ro|romanian',
                               'ru' => 'ru|russian',
                               'sk' => 'sk|slovak',
                               'sr' => 'sr|serbian',
                               'sv' => 'sv|swedish',
                               'th' => 'th|thai',
                               'tr' => 'tr|turkish',
                               'uk' => 'uk|ukrainian',
                               'tw' => 'zh[-_]tw|chinese traditional',
                               'zh' => 'zh|chinese simplified');

      $this->catalog_languages = array();
      $languages_query = tep_db_query("select languages_id, name, code, image, directory from " . TABLE_LANGUAGES . " order by sort_order");
      while ($languages = tep_db_fetch_array($languages_query)) {
        $this->catalog_languages[$languages['code']] = array('id' => $languages['languages_id'],
                                                             'name' => $languages['name'],
                                                             'image' => $languages['image'],
                                                             'directory' => $languages['directory']);
      }

      $this->browser_languages = '';
      $this->language = '';

      $this->set_language($lng);
    }

    function set_language($language) {
      if ( (tep_not_null($language)) && (isset($this->catalog_languages[$language])) ) {
        $this->language = $this->catalog_languages[$language];
      } else {
        $this->language = $this->catalog_languages[DEFAULT_LANGUAGE];
      }
    }

    function get_browser_language() {
      $this->browser_languages = explode(',', getenv('HTTP_ACCEPT_LANGUAGE'));

      for ($i=0, $n=sizeof($this->browser_languages); $i<$n; $i++) {
        reset($this->languages);
        while (list($key, $value) = each($this->languages)) {
          if (preg_match('/^(' . $value . ')(;q=[0-9]\\.[0-9])?$/i', $this->browser_languages[$i]) && isset($this->catalog_languages[$key])) {
            $this->language = $this->catalog_languages[$key];
            break 2;
          }
        }
      }
    }
  }

  
--
  class language {
    var $languages, $catalog_languages, $browser_languages, $language;

    function language($lng = '') {
      $this->languages = array('ar' => array('ar([-_][[:alpha:]]{2})?|arabic', 'arabic', 'ar'),
                               'bg-win1251' => array('bg|bulgarian', 'bulgarian-win1251', 'bg'),
                               'bg-koi8r' => array('bg|bulgarian', 'bulgarian-koi8', 'bg'),
                               'ca' => array('ca|catalan', 'catala', 'ca'),
                               'cs-iso' => array('cs|czech', 'czech-iso', 'cs'),
                               'cs-win1250' => array('cs|czech', 'czech-win1250', 'cs'),
                               'da' => array('da|danish', 'danish', 'da'),
                               'de' => array('de([-_][[:alpha:]]{2})?|german', 'german', 'de'),
                               'el' => array('el|greek',  'greek', 'el'),
                               'en' => array('en([-_][[:alpha:]]{2})?|english', 'english', 'en'),
                               'es' => array('es([-_][[:alpha:]]{2})?|spanish', 'spanish', 'es'),
                               'et' => array('et|estonian', 'estonian', 'et'),
                               'fi' => array('fi|finnish', 'finnish', 'fi'),
                               'fr' => array('fr([-_][[:alpha:]]{2})?|french', 'french', 'fr'),
                               'gl' => array('gl|galician', 'galician', 'gl'),
                               'he' => array('he|hebrew', 'hebrew', 'he'),
                               'hu' => array('hu|hungarian', 'hungarian', 'hu'),
                               'id' => array('id|indonesian', 'indonesian', 'id'),
                               'it' => array('it|italian', 'italian', 'it'),
                               'ja-euc' => array('ja|japanese', 'japanese-euc', 'ja'),
                               'ja-sjis' => array('ja|japanese', 'japanese-sjis', 'ja'),
                               'ko' => array('ko|korean', 'korean', 'ko'),
                               'ka' => array('ka|georgian', 'georgian', 'ka'),
                               'lt' => array('lt|lithuanian', 'lithuanian', 'lt'),
                               'lv' => array('lv|latvian', 'latvian', 'lv'),
                               'nl' => array('nl([-_][[:alpha:]]{2})?|dutch', 'dutch', 'nl'),
                               'no' => array('no|norwegian', 'norwegian', 'no'),
                               'pl' => array('pl|polish', 'polish', 'pl'),
                               'pt-br' => array('pt[-_]br|brazilian portuguese', 'brazilian_portuguese', 'pt-BR'),
                               'pt' => array('pt([-_][[:alpha:]]{2})?|portuguese', 'portuguese', 'pt'),
                               'ro' => array('ro|romanian', 'romanian', 'ro'),
                               'ru-koi8r' => array('ru|russian', 'russian-koi8', 'ru'),
                               'ru-win1251' => array('ru|russian', 'russian-win1251', 'ru'),
                               'sk' => array('sk|slovak', 'slovak-iso', 'sk'),
                               'sk-win1250' => array('sk|slovak', 'slovak-win1250', 'sk'),
                               'sr-win1250' => array('sr|serbian', 'serbian-win1250', 'sr'),
                               'sv' => array('sv|swedish', 'swedish', 'sv'),
                               'th' => array('th|thai', 'thai', 'th'),
                               'tr' => array('tr|turkish', 'turkish', 'tr'),
                               'uk-win1251' => array('uk|ukrainian', 'ukrainian-win1251', 'uk'),
                               'zh-tw' => array('zh[-_]tw|chinese traditional', 'chinese_big5', 'zh-TW'),
                               'zh' => array('zh|chinese simplified', 'chinese_gb', 'zh'));

      $this->catalog_languages = array();
      $languages_query = xtc_db_query("SELECT * FROM " . TABLE_LANGUAGES . " WHERE status = '1' ORDER BY sort_order");
      while ($languages = xtc_db_fetch_array($languages_query)) {
        $this->catalog_languages[$languages['code']] = array('id' => $languages['languages_id'],
                                                             'name' => $languages['name'],
                                                             'image' => $languages['image'],
                                                             'status' => $languages['status'],
                                                             'code' => $languages['code'],
                                                             'language_charset' => $languages['language_charset'],
                                                             'directory' => $languages['directory']);
      }

      $this->browser_languages = '';
      $this->language = '';

      if ( (!empty($lng)) && (isset($this->catalog_languages[$lng])) ) {
        $this->language = $this->catalog_languages[$lng];
        //BOF - DokuMan - 2011-01-21 - Fix language detection error
        //} else {
        //  $this->language = $this->catalog_languages[DEFAULT_LANGUAGE];
        //}
        } elseif(isset($this->catalog_languages[DEFAULT_LANGUAGE])) {
          $this->language = $this->catalog_languages[DEFAULT_LANGUAGE];
        } else {
          $this->language = $this->catalog_languages[key($this->catalog_languages)];
        //EOF - DokuMan - 2011-01-21 - Fix language detection error
      }
    }

    function get_browser_language() {
      $this->browser_languages = explode(',', (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '')); //DokuMan - 2010-08-04 - use $_SERVER here for better windows environment compatiblity

      //BOF - DokuMan - 2011-12-19 - precount for performance
      //for ($i=0, $n=sizeof($this->browser_languages); $i<$n; $i++) {
      $n=sizeof($this->browser_languages);
      for ($i=0; $i<$n; $i++) {
      //EOF - DokuMan - 2011-12-19 - precount for performance
        reset($this->languages);
        foreach($this->languages as $key => $value) { //Dokuman - 2011-07-26 - Change while with foreach for performance
          if (preg_match('/^(' . $value[0] . ')(;q=[0-9]\\.[0-9])?$/i', $this->browser_languages[$i]) && isset($this->catalog_languages[$key])) { // Hetfield - 2009-08-19 - replaced deprecated function eregi with preg_match to be ready for PHP >= 5.3
            $this->language = $this->catalog_languages[$key];
            break 2;
          }
        }
      }
    }
  }

