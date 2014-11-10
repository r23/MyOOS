<?php
/* ----------------------------------------------------------------------
   $Id: oos_main.php,v 1.3 2008/07/06 23:58:17 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2008 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: application_top.php,v 1.264 2003/02/17 16:37:52 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being require d by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

// debug
  $debug = 'false';

// start the timer for the page parse time log
  define('PAGE_PARSE_START_TIME', microtime());

// for debug set the level of error reporting
  error_reporting(E_ALL & ~E_NOTICE);
//  error_reporting(0);

// disable use_trans_sid as oos_href_link() does this manually
  if (function_exists('ini_set')) {
    ini_set('arg_separator.output', '&amp;');
  }

// Set the local configuration parameters - mainly for developers
  if (file_exists('includes/local/configure.php')) include('includes/local/configure.php');


// require  server parameters
  require 'includes/configure.php';
  require 'includes/oos_define.php';

// Version information
  require 'includes/oos_version.php';

// Load server utilities
  require 'includes/functions/function_server.php';


// redirect to the installation module if DB_SERVER is empty
  if (strlen(OOS_DB_TYPE) < 1) {
    if (is_dir('install')) {
      header('Location: install/step.php');
      exit;
    }
  }

// set the type of request (secure or not)
  $request_type = 'NONSSL';
  if (ENABLE_SSL == 'true') {
    if (strtolower(oos_server_get_var('HTTPS')) == 'on'
      || (oos_server_get_var('HTTPS') == '1')
      || oos_server_has_var('SSL_PROTOCOL')) {
        $request_type = 'SSL';
    }
  }

// require  the list of project filenames
  require 'includes/oos_modules.php';
  require 'includes/oos_filename.php';


// require  the list of project database tables
  require 'includes/oos_tables.php';

// define general functions used application-wide
  require 'includes/functions/function_global.php';
  require 'includes/functions/function_kernel.php';
  require 'includes/functions/function_input.php';
  require 'includes/functions/function_output.php';
  require 'includes/functions/function_encoded.php';

// require  the password crypto functions
  require 'includes/functions/function_password.php';

// require  validation functions (right now only email address)
  require 'includes/functions/function_validations.php';

  if (isset($_POST)) {
    foreach ($_POST as $key=>$value) {
      $$key = oos_prepare_input($value);
    }
  }


// initialize the logger class
  require 'includes/classes/class_logger.php';

  require 'includes/classes/class_member.php';
  require 'includes/classes/class_products_history.php';
  require 'includes/classes/class_shopping_cart.php';
  require 'includes/classes/class_navigation_history.php';

// require  the mail classes
  require 'includes/classes/thirdparty/phpmailer/class.phpmailer.php';

  require 'includes/functions/function_session.php';


// require  the database functions
  $adodb_logsqltable = $oostable['adodb_logsql'];
  if (!defined('ADODB_LOGSQL_TABLE')) {
    define('ADODB_LOGSQL_TABLE', $adodb_logsqltable);
  }
  require 'includes/classes/thirdparty/adodb/adodb-errorhandler.inc.php';
  require 'includes/classes/thirdparty/adodb/adodb.inc.php';
  require 'includes/functions/function_db.php';

// make a connection to the database... now 
  if (!oosDBInit()) {
    die('Unable to connect to database server!');
  }

  $dbconn =& oosDBGetConn();
  oosDB_importTables($oostable);


// set the application parameters
  $configurationtable = $oostable['configuration'];
  $configuration_query = "SELECT configuration_key AS cfg_key, configuration_value AS cfg_value
                          FROM $configurationtable";

  if (USE_DB_CACHE == 'true') {
    $configuration_result = $dbconn->CacheExecute(3600, $configuration_query);
  } else {
    $configuration_result = $dbconn->Execute($configuration_query);
  }

  while ($configuration = $configuration_result->fields) {
    define($configuration['cfg_key'], $configuration['cfg_value']);
    // Move that ADOdb pointer!
    $configuration_result->MoveNext();
  }
  // Close result set
  $configuration_result->Close();

//for debugging purposes 
//  require 'includes/oos_debug.php';


  require 'includes/classes/class_plugin_event.php';
  $oEvent = new plugin_event;
  $oEvent->getInstance();

// set the language
  $nLanguageID = isset($_SESSION['language_id']) ? $_SESSION['language_id']+0 : 1;

// determine the page directory
  if (isset($_GET['mp'])) {
    $sMp = oos_var_prep_for_os($_GET['mp']);
  } elseif (isset($_POST['mp'])) {
    $sMp = oos_var_prep_for_os($_POST['mp']);
  }
  if (isset($_GET['file'])) {
    $sFile = oos_var_prep_for_os($_GET['file']);
  } elseif (isset($_POST['file'])) {
    $sFile = oos_var_prep_for_os($_POST['file']);
  }

  if ( (empty($sMp)) || (empty($sFile)) ) {
    $sMp = $aModules['main'];
    $sFile = $aFilename['main'];
  }

  $sFile = str_replace('.html', '', $sFile);

// Cross-Site Scripting attack defense
   oos_secure_input();

// PrintPage
  if (isset($_GET['option'])) {
    $option = oos_var_prep_for_os($_GET['option']);
  }


// products history
  if (!isset($_SESSION['products_history'])) {
    $_SESSION['products_history'] = new oosProductsHistory;
  }

// initialize the message stack for output messages
  require 'includes/classes/class_message_stack.php';
  $oMessage = new messageStack;

// templates selection
  if (!isset($_SESSION['theme']) || isset($_GET['template'])) {
    if (isset($_GET['template']) && oos_template_exits($_GET['template'])) {
      $_SESSION['theme'] = oos_var_prep_for_os($_GET['template']);
    } else {
      $_SESSION['theme'] = STORE_TEMPLATES;
    }
  }
  $sTheme = oos_var_prep_for_os($_SESSION['theme']);

  // PAngV
  if ($_SESSION['member']->group['show_price'] == 1) {
    if ($_SESSION['member']->group['show_price_tax'] == 1) {
      $sPAngV = $aLang['text_taxt_incl'];
    } else {
      $sPAngV = $aLang['text_taxt_add'];
    }

    if (isset($_SESSION['customers_vat_id_status']) && ($_SESSION['customers_vat_id_status'] == 1)) {
      $sPAngV = $aLang['tax_info_excl'];
    }

    $sPAngV .= ', <br />';
    $sPAngV .= sprintf($aLang['text_shipping'], oos_href_link($aModules['info'], $aFilename['information'], 'information_id=1'));
  }


  $today = date("Y-m-d H:i:s");

// Shopping cart actions
  if ( isset($_GET['action']) || isset($_POST['action']) ) {
    require 'includes/oos_cart_actions.php';
  }

// split-page-results
  require 'includes/classes/class_split_page_results.php';

// infobox
  require 'includes/classes/class_boxes.php';

  require 'includes/functions/function_coupon.php';


  $products_unitstable = $oostable['products_units'];
  $query = "SELECT products_units_id, products_unit_name
            FROM $products_unitstable
            WHERE languages_id = '" . intval($nLanguageID) . "'";
  $products_units = $dbconn->GetAssoc($query);


  $aOption = array();

?>