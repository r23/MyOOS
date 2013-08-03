<?php
/* ----------------------------------------------------------------------
   $Id: oos_event_session.php 470 2013-07-08 12:16:25Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  class oos_event_session {

    var $name;
    var $description;
    var $uninstallable;
    var $depends;
    var $preceeds;
    var $author;
    var $version;
    var $requirements;


   /**
    *  class constructor
    */
    function oos_event_session() {

      $this->name          = PLUGIN_EVENT_SESSION_NAME;
      $this->description   = PLUGIN_EVENT_SESSION_DESC;
      $this->uninstallable = false;
      $this->author        = 'OOS Development Team';
      $this->version       = '1.0';
      $this->requirements  = array(
                               'oos'         => '1.5.0',
                               'smarty'      => '2.6.9',
                               'adodb'       => '4.62',
                               'php'         => '4.2.0'
      );
    }

    function create_plugin_instance() {
      global $request_type, $spider_flag, $spider_kill_sid;

      $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
      $spider_flag = false;
      $spider_kill_sid = 'false';

      // set the top level domains
      $http_domain = oos_server_get_top_level_domain(OOS_HTTP_SERVER);
      $https_domain = oos_server_get_top_level_domain(OOS_HTTPS_SERVER);
      $current_domain = (($request_type == 'NONSSL') ? $http_domain : $https_domain);

      // set the session cookie parameters
      if (function_exists('session_set_cookie_params')) {
        session_set_cookie_params(0, '/', (oos_is_not_null($current_domain) ? '.' . $current_domain : ''));
      } elseif (function_exists('ini_set')) {
        ini_set('session.cookie_lifetime', '0');
        ini_set('session.cookie_path', '/');
        ini_set('session.cookie_domain', (oos_is_not_null($current_domain) ? '.' . $current_domain : ''));
      }

      // set the session ID if it exists
      if (isset($_POST[oos_session_name()])) {
        oos_session_id($_POST[oos_session_name()]);
      } else if (isset($_GET[oos_session_name()])) {
        oos_session_id($_GET[oos_session_name()]);
      }


      if (empty($user_agent) === false) {
       $spider_agent = @parse_ini_file('includes/ini/spiders.ini');

        foreach ($spider_agent as $spider) {
          if (empty($spider) === false) {
            if (strpos($user_agent, trim($spider)) !== false) {
              $spider_kill_sid = 'true';
              $spider_flag = true;
              break;
            }
          }
        }
      }

      if ($spider_flag === false) {
         // set the session name and save path
         oos_session_name('OOSSID');

        // lets start our session
        oos_session_start();
      }

      if (!isset($_SESSION)) {
        $_SESSION = array();
      }

      // create the shopping cart
      if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = new shoppingCart;
      }

      // navigation history
      if (!isset($_SESSION['navigation'])) {
        $_SESSION['navigation'] = new oosNavigationHistory;
      }

	  // products history
      if (!isset($_SESSION['products_history'])) {
		$_SESSION['products_history'] = new oosProductsHistory;
      }

      if (!isset($_SESSION['member'])) {
        $_SESSION['member'] = new oosMember;
        $_SESSION['member']->default_member();
      }
	  
      if (!isset($_SESSION['error_cart_msg'])) {
        $_SESSION['error_cart_msg'] = '';
      }

      $aContents = oos_get_content();
      

      // verify the browser user agent
      $http_user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

      if (!isset($_SESSION['session_user_agent'])) {
        $_SESSION['session_user_agent'] = $http_user_agent;
      }

      if ($_SESSION['session_user_agent'] != $http_user_agent) {
        unset($_SESSION['customer_id']);
        unset($_SESSION['session_user_agent']);
        $_SESSION['cart']->reset();
        $_SESSION['member']->default_member();

        oos_redirect(oos_link($aContents['login'], '', 'SSL'));
      }

      // verify the IP address
      if (!isset($_SESSION['session_ip_address'])) {
        $_SESSION['session_ip_address'] = oos_server_get_remote();
      }

      if ($_SESSION['session_ip_address'] != oos_server_get_remote()) {
        unset($_SESSION['customer_id']);
        unset($_SESSION['session_ip_address']);
        $_SESSION['cart']->reset();
        $_SESSION['member']->default_member();

        oos_redirect(oos_link($aContents['login'], '', 'SSL'));
      }

      return true;
    }

    function install() {
      return false;
    }

    function remove() {
      return false;
    }

    function config_item() {
      return false;
    }
  }

