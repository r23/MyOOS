<?php
/* ----------------------------------------------------------------------
   $Id: AnswerPageTemplate.php,v 1.1 2007/06/08 17:14:43 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/


   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:


   osCommerce Payment-Modul TeleCash Click&Pay easy
   Version 0.8 vom 23.03.2004

   (c) 2004: Dieter H�auf
   ----------------------------------------------------------------------
   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  error_reporting(E_ALL & ~E_NOTICE);

  define('OOS_VALID_MOD', 'yes');
  define('SPIDER_USE_KILLER', 'false'); 

  include '../includes/configure.php';
  include '../includes/oos_define.php';
  include '../includes/oos_tables.php';

// include the list of project filenames
  include '../includes/oos_modules.php');
  include '../includes/oos_filename.php');

  require '../includes/functions/function_global.php';
  require '../includes/functions/function_kernel.php';
  require '../includes/functions/function_server.php';
  require '../includes/functions/function_output.php';

  if (isset($_GET)) {
    foreach ($_GET as $key=>$value) {
      $$key = oos_prepare_input($value);
    }
  }

  if($tcphMerchantID != 'demo') die('ERROR'); // Tragen Sie hier Ihre TeleCash-MerchantID ein.


// include the database functions
  require('../includes/classes/adodb/adodb-errorhandler.inc.php');
  require('../includes/classes/adodb/adodb.inc.php');
  require('../includes/functions/function_db.php');

// make a connection to the database... now
  if (!oosDBInit()) {
    die('Unable to connect to database server!');
  }

  $db =& oosDBGetConn();

// set the application parameters
  $configuration_sql = "SELECT configuration_key AS cfg_key, configuration_value AS cfg_value
                        FROM " . $oostable['configuration'];
  if (USE_DB_CACHE == 'true') {
    $configuration_result = $db->CacheExecute(3600, $configuration_sql);
  } else {
    $configuration_result = $db->Execute($configuration_sql);
  }
  while ($configuration = $configuration_result->fields) {
    define($configuration['cfg_key'], $configuration['cfg_value']);
    $configuration_result->MoveNext();
  }

  if ( (!isset($_SESSION['language']))
    || (!preg_match('/^[a-z]{2,4}$/', $_SESSION['language'])) )  {
    $_SESSION['language'] = DEFAULT_LANGUAGE;
  }
  $aLanguage = oos_var_prep_for_os($_SESSION['language']);
  require_once('../includes/languages/' . $aLanguage . '.php');

  if (ENABLE_SSL == 'true') {
    $link = OOS_HTTPS_SERVER . OOS_SHOP;
  } else {
    $link = OOS_HTTP_SERVER . OOS_SHOP;
  }

  if ($tcphResultCode == 'OK') {
    $link .= 'index.php?mp=' . $aModules['checkout'] . '&file=' . $aFilename['checkout_process'];
  } else {
    $link .= 'index.php?mp=' . $aModules['checkout'] . '&file=' . $aFilename['checkout_payment'] . '&tcphResultCode=' . $tcphResultCode;
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo STORE_NAME; ?></title>
<meta http-equiv="Refresh" content="0; URL=<?php echo $link; ?>">
?>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->

   <br><br><br>";
   <table border="0" align="center" width="100%">
     <tr>
        <td width="20%" align="center"><a href="<?php  echo $link; ?>">Zur&uuml;ck zum Shop</a></td>
     </tr>
   </table>
</body>
</html>
