<?php
/* ----------------------------------------------------------------------
   $Id: info_message.php 448 2013-06-27 22:50:49Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: header.php,v 1.39 2003/02/13 04:23:23 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

$aInfoMessage = array();

if ($messageStack->size('error') > 0) {
    $aInfoMessage = array_merge ($aInfoMessage, $messageStack->output('error') );
}

if (isset($_SESSION['success_new_password']) && ($_SESSION['success_new_password'] == true)) {
	$aInfoMessage[] = array('type' => 'success',
                            'text' => $aLang['text_forgotten_success']);
	$_SESSION['success_new_password'] = false;
}


for ($i = 0; $i < count($aInfoMessage); $i++) {
     switch ($aInfoMessage[$i]['type']) {
       case 'warning':
         $smarty->append('oos_info_warning', array('text' => $aInfoMessage[$i]['text']));
         break;

       case 'error':
         $smarty->append('oos_error_message', array('text' => $aInfoMessage[$i]['text']));
         break;

       case 'info':
       case 'success':
         $smarty->append('oos_info_message', array('text' => $aInfoMessage[$i]['text']));
         break;
     }
}

