<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Writed by Setec Astronomy - setec@freemail.it
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /**
   *  This simple class allows to extract the keywords contained in the 
   *  HTTP Referrer header provided by the web browser when a client comes 
   *  to your site from a search engine query.
   *
   * @package WebSearch
   * @version $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/07 16:06:31 $
   */

  class referrer {
    var $ini_file;
    var $ini_array;
    var $global_fields;

    function referrer() {
      $this->reset();
    }


    function _safe_set (&$var_true, $var_false = "") {
      if (!isset ($var_true)) {
        $var_true = $var_false;
      }
      return $var_true;
    }


    function getKeywords()  {
      $url = oos_server_get_var('HTTP_REFERER');

      if (empty ($url) || $this->ini_array === FALSE) {
        return FALSE;
      }

      $parse_url = parse_url ($url);
      $this->_safe_set ($parse_url["host"], "");
      $parse_url["host"] = strtolower ($parse_url["host"]);
      $this->_safe_set ($parse_url["query"], "");
      parse_str ($parse_url["query"], $parse_query);

      if (!empty ($parse_url["host"])) {
        $founded = FALSE;

        foreach ($this->ini_array as $engine) {
          $this->_safe_set ($engine["Host"], "");
          $this->_safe_set ($engine["QueryField"], "");
          $engine["Host"] = strtolower ($engine["Host"]);

          $host_pos = strpos($parse_url["host"], $engine["Host"]);

          if ($host_pos !== FALSE) {
            $founded = TRUE;
            if (isset ($parse_query[$engine["QueryField"]])) {
              return urldecode ($parse_query[$engine["QueryField"]]);
            }
          }
        }

        if (!$founded) {
          foreach ($this->global_fields as $field) {
            if (isset ($parse_query[$field])) {
              return urldecode ($parse_query[$field]);
            }
          }
        }
      }
    }



    function reset() {
      $this->ini_file = 'includes/ini/engines.ini';
      $this->ini_array = @parse_ini_file ($this->ini_file, true);

      if ($this->ini_array === FALSE) {
        trigger_error("referrer object creation failed", E_USER_NOTICE);
      }
      $this->global_fields = array ("q", "p", "query", "qwederr", "qs");
    }

  }


