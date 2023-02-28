<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   Newsletter Module
   P&G developmment

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   Copyright (c) 2000,2001 The Exchange Project
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

if (!defined('OOS_VALID_MOD')) {
    die('Direct Access to this location is not allowed.');
}

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/newsletter.php';

// require  the password crypto functions
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_password.php';

if (isset($_GET['subscribe']) && ($_GET['subscribe'] == 'confirm')) {
    $sU = oos_db_prepare_input($_GET['u']);
    $sID = oos_db_prepare_input($_GET['id']);
    $sE = oos_db_prepare_input($_GET['e']);

    if (empty($sU) || !is_string($sU)) {
        oos_redirect(oos_href_link($aContents['403']));
    }
    if (empty($sID) || !is_string($sID)) {
        oos_redirect(oos_href_link($aContents['403']));
    }
    if (empty($sE) || !is_string($sE)) {
        oos_redirect(oos_href_link($aContents['403']));
    }

    $sSha1 = sha1($sID);
    if ($sSha1 != $sU) {
        oos_redirect(oos_href_link($aContents['403']));
    }

    $pos = strpos($sID, "f00d");
    if ($pos === false) {
        oos_redirect(oos_href_link($aContents['403']));
    } else {
        $sID = substr($sID, 4, -4);
    }

    $newsletter_recipients = $oostable['newsletter_recipients'];
    $sql = "UPDATE $newsletter_recipients
               SET date_added = now(),
				  status = '1'
			WHERE recipients_id = '" . intval($sID) . "'
			AND mail_key = '" . oos_db_input($sE) . "'";
    $dbconn->Execute($sql);

    $newsletter_recipients_history = $oostable['newsletter_recipients_history'];
    $dbconn->Execute(
        "INSERT INTO $newsletter_recipients_history 
					(recipients_id,
					new_value,
					date_added) VALUES ('" . intval($sID) . "',
									  '1',
                                      now())"
    );
    oos_redirect(oos_href_link($aContents['newsletter'], 'subscribe=success'));
}

$oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['newsletter']));
$sCanonical = oos_href_link($aContents['newsletter'], '', false, true);

$aTemplate['page'] = $sTheme . '/page/newsletter.html';

$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;
$nPageType = OOS_PAGE_TYPE_SERVICE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

// assign Smarty variables;
$smarty->assign(
    array(
        'breadcrumb'     => $oBreadcrumb->trail(),
        'heading_title' => $aLang['navbar_title'],
        'canonical'        => $sCanonical
    )
);

// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
