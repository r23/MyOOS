<?php
/* ----------------------------------------------------------------------
   $Id: confirmation.php,v 1.3 2009/01/17 00:34:07 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2009 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: checkout_confirmation.php,v 1.6.2.1 2003/05/03 23:41:23 wilt
   orig: checkout_confirmation.php,v 1.135 2003/02/14 20:28:46 dgw_
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  require 'includes/languages/' . $sLanguage . '/checkout_confirmation.php';
  require 'includes/functions/function_address.php';

// if the customer is not logged on, redirect them to the login page
  if (!isset($_SESSION['customer_id'])) {
    $_SESSION['navigation']->set_snapshot(array('mode' => 'SSL', 'modules' => $aModules['checkout'], 'file' =>$aFilename['checkout_payment']));
    oos_redirect(oos_href_link($aModules['user'], $aFilename['login'], '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($_SESSION['cart']->count_contents() < 1) {
    oos_redirect(oos_href_link($aModules['main'], $aFilename['main_shopping_cart']));
  }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($_SESSION['cart']->cartID) && isset($_SESSION['cartID'])) {
    if ($_SESSION['cart']->cartID != $_SESSION['cartID']) {
      oos_redirect(oos_href_link($aModules['checkout'], $aFilename['checkout_shipping'], '', 'SSL'));
    }
  }

  if (isset($_POST['payment'])) $_SESSION['payment'] = oos_db_prepare_input($_POST['payment']);

  $sCheckBanktransfer = '0';
  if (isset($_POST['recheckok'])) {

  } else {
    if ( (isset($_POST['payment'])) && ($_POST['payment'] ==  'banktransfer') ) {
      $sCheckBanktransfer = '1';
	}
  }
  if ( (isset($_GET['banktransfer'])) && ($_GET['banktransfer'] ==  'true') ) {
      $sCheckBanktransfer = '1';
  }

  $nMandat = 0;
  $nSepabt = 0;
  if (isset($_POST['sepa'])) {
	$nMandat = 1;
  } else {
    if ( (isset($_POST['payment'])) && ($_POST['payment'] ==  'sepabanktransfer') ) {
      $nSepabt = 1;
	}
  }
  if ( (isset($_GET['sepabanktransfer'])) && ($_GET['sepabanktransfer'] ==  'true') ) {
      $nSepabt = 1;
  }

  if ( (isset($_POST['comments'])) && (empty($_POST['comments'])) ) {
    $_SESSION['comments'] = '';
  } else if (oos_is_not_null($_POST['comments'])) {
    $_SESSION['comments'] = oos_db_prepare_input($_POST['comments']);
  }

  if (isset($_POST['campaign_id']) && is_numeric($_POST['campaign_id'])) {
    $_SESSION['campaigns_id'] = intval($_POST['campaign_id']);
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!isset($_SESSION['shipping'])) {
    oos_redirect(oos_href_link($aModules['checkout'], $aFilename['checkout_shipping'], '', 'SSL'));
  }

// if conditions are not accepted, redirect the customer to the payment method selection page
if (($sCheckBanktransfer == '0') or ($nSepabt == 0)) {
  if ( (DISPLAY_CONDITIONS_ON_CHECKOUT == 'true') && (empty($_POST['gv_redeem_code'])) ) {
    if ($_POST['conditions'] == false) {
      $_SESSION['navigation']->remove_current_page();
      $_SESSION['navigation']->remove_last_page();

      oos_redirect(oos_href_link($aModules['checkout'], $aFilename['checkout_payment'], 'error_message=' . urlencode(decode($aLang['error_conditions_not_accepted'])), 'SSL', true, false));
    }
  }
} 

if ( (isset($_POST['payment'])) && ($_POST['payment'] ==  'sepabanktransfer') ) {
	if (isset($_POST['mandatsreferenz_id']) && is_numeric($_POST['mandatsreferenz_id'])) {
		if ($_POST['mandate_status'] == 'on') {
            $sepamandattable = $oostable['sepamandatsreferenz'];
            $dbconn->Execute("UPDATE $sepamandattable SET pdf_date_send = '" . oos_db_input(date('Ymd')) . "'
                          WHERE mandatsreferenz_id = '" . intval($_POST['mandatsreferenz_id']) .  "' 
						  AND customers_id = '" . intval($_SESSION['customer_id']) .  "'");	
			$nMandat = 2;
		} else {
			$bMandatError = true; 	
			$error = 'Bitte best&auml;tigen Sie die Mandatserteilung';
		}
	}
}


// load the selected payment module
  require 'includes/classes/class_payment.php';

  if ($credit_covers) $_SESSION['payment'] = '';

  $payment_modules = new payment($_SESSION['payment']);
  require 'includes/classes/class_order_total.php';

  require 'includes/classes/class_order.php';
  $oOrder = new order;

  if ( (isset($_SESSION['shipping'])) && ($_SESSION['shipping']['id'] == 'free_free')) {
    if ( ($oOrder->info['total'] - $oOrder->info['shipping_cost']) < MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER ) {
      oos_redirect(oos_href_link($aModules['checkout'], $aFilename['checkout_shipping'], '', 'SSL'));
    }
  }


  $payment_modules->update_status();
  $order_total_modules = new order_total;
  $order_total_modules->collect_posts();


  if (isset($_SESSION['cot_gv'])) {
    $credit_covers = $order_total_modules->pre_confirmation_check();
  }


  if ( (is_array($payment_modules->modules)) && (count($payment_modules->modules) > 1) && (!is_object($$_SESSION['payment'])) && (!$credit_covers) ) {
    oos_redirect(oos_href_link($aModules['checkout'], $aFilename['checkout_payment'], 'error_message=' . urlencode(decode($aLang['error_no_payment_module_selected'])), 'SSL'));
  }

if (($sCheckBanktransfer == '0') and ($nSepabt == 0)) {
  if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
  }
} 

/*
elseif ($nSepabt == 0) {
  if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
  }
}
*/

// load the selected shipping module
  require 'includes/classes/class_shipping.php';
  $shipping_modules = new shipping($_SESSION['shipping']);


// Stock Check
  $any_out_of_stock = false;
  if (STOCK_CHECK == 'true') {
    for ($i=0, $n=count($oOrder->products); $i<$n; $i++) {
      if (oos_check_stock($oOrder->products[$i]['id'], $oOrder->products[$i]['qty'])) {
        $any_out_of_stock = true;
      }
    }
    // Out of Stock
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
      oos_redirect(oos_href_link($aModules['main'], $aFilename['main_shopping_cart']));
    }
  }


  // links breadcrumb
  $oBreadcrumb->add(decode($aLang['navbar_title_1']), oos_href_link($aModules['checkout'], $aFilename['checkout_shipping'], '', 'SSL'));
  $oBreadcrumb->add(decode($aLang['navbar_title_2']));

  $aOption['template_main'] = $sTheme . '/modules/checkout_confirmation.html';
  $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';

  $nPageType = OOS_PAGE_TYPE_CHECKOUT;

  require 'includes/oos_system.php';
  if (!isset($option)) {
    require 'includes/info_message.php';
    require 'includes/oos_blocks.php';
    require 'includes/oos_counter.php';
  }

  
if ($nMandat == 1) {
		$sepabanktransfer_city = 'Ilshofen';
		$sepabanktransfer_owner = oos_prepare_input($_POST['sepabanktransfer_owner']);
		$sepabanktransfer_street_address = oos_prepare_input($_POST['sepabanktransfer_street_address']);
		$sepabanktransfer_postcode = oos_prepare_input($_POST['sepabanktransfer_postcode']);
		$sepabanktransfer_city = oos_prepare_input($_POST['sepabanktransfer_city']);
		$sepabanktransfer_state = oos_prepare_input($_POST['sepabanktransfer_state']);
		$sepabanktransfer_email_address = oos_prepare_input($_POST['sepabanktransfer_email_address']);
		$sepabanktransfer_name = oos_prepare_input($_POST['sepabanktransfer_name']);
		$sepabanktransfer_iban = oos_prepare_input($_POST['sepabanktransfer_iban']);
		$sepabanktransfer_bic = oos_prepare_input($_POST['sepabanktransfer_bic']);


		$sepabanktransfertable = $oostable['sepabanktransfer'];
		$sepa_sql = "SELECT sepabanktransfer_id, sepabanktransfer_owner, sepabanktransfer_street_address, sepabanktransfer_postcode,
						    sepabanktransfer_city, sepabanktransfer_state, sepabanktransfer_email_address,
						   sepabanktransfer_name, sepabanktransfer_iban, sepabanktransfer_bic, sepabanktransfer_status
				    FROM $sepabanktransfertable
				WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'";
		$sepa_result = $dbconn->Execute($sepa_sql);

		if (!$sepa_result->RecordCount()) {
		
            $sepabanktransfertable = $oostable['sepabanktransfer'];
            $dbconn->Execute("INSERT INTO $sepabanktransfertable
                       (customers_id,
                        sepabanktransfer_owner,
                        sepabanktransfer_street_address,
                        sepabanktransfer_postcode,
						sepabanktransfer_city,
						sepabanktransfer_state,
						sepabanktransfer_email_address,
						sepabanktransfer_name,
						sepabanktransfer_iban,
						sepabanktransfer_bic ) VALUES ('" . intval($_SESSION['customer_id']) . "',
                                                             '" . oos_db_input($sepabanktransfer_owner) . "',
                                                             '" . oos_db_input($sepabanktransfer_street_address) . "',
                                                             '" . oos_db_input($sepabanktransfer_postcode) . "',
                                                             '" . oos_db_input($sepabanktransfer_city) . "',
                                                             '" . oos_db_input($sepabanktransfer_state) . "',
                                                             '" . oos_db_input($sepabanktransfer_email_address) . "',
                                                             '" . oos_db_input($sepabanktransfer_name) . "',
                                                             '" . oos_db_input($sepabanktransfer_iban) . "',
                                                             '" . oos_db_input($sepabanktransfer_bic) . "')");

			$sepabanktransfer_id = $dbconn->Insert_ID();
		} else {
			$sepa = $sepa_result->fields;
			$sepabanktransfer_id = $sepa['sepabanktransfer_id'];
            $sepabanktransfertable = $oostable['sepabanktransfer'];
            $dbconn->Execute("UPDATE $sepabanktransfertable SET sepabanktransfer_owner = '" . oos_db_input($sepabanktransfer_owner) . "',
						    sepabanktransfer_street_address = '" . oos_db_input($sepabanktransfer_street_address) . "',
						    sepabanktransfer_postcode = '" . oos_db_input($sepabanktransfer_postcode) . "',
							sepabanktransfer_city = '" . oos_db_input($sepabanktransfer_city) . "',
							sepabanktransfer_state = '" . oos_db_input($sepabanktransfer_state) . "',
							sepabanktransfer_email_address = '" . oos_db_input($sepabanktransfer_email_address) . "',
							sepabanktransfer_name = '" . oos_db_input($sepabanktransfer_name) . "',
							sepabanktransfer_iban = '" . oos_db_input($sepabanktransfer_iban) . "',
							sepabanktransfer_bic = '" . oos_db_input($sepabanktransfer_bic) . "'
                          WHERE customers_id = '" . intval($_SESSION['customer_id']) .  "'");		
		} 


		$sepamandattable = $oostable['sepamandatsreferenz'];
        $dbconn->Execute("INSERT INTO $sepamandattable
                       (customers_id,
                        sepabanktransfer_id,
                        customers_email_address) VALUES ('" . intval($_SESSION['customer_id']) . "',
                                                       '" . oos_db_input($sepabanktransfer_id) . "',
                                                       '" . oos_db_input($sepabanktransfer_email_address) . "')");													   
		$mandatsreferenz_id = $dbconn->Insert_ID();	
		
	      $process_button_string = oos_draw_hidden_field('mandatsreferenz_id', $mandatsreferenz_id) .
								oos_draw_hidden_field('sepabanktransfer_owner', $sepabanktransfer_owner) .
                               oos_draw_hidden_field('sepabanktransfer_street_address', $sepabanktransfer_street_address).
                               oos_draw_hidden_field('sepabanktransfer_postcode', $sepabanktransfer_postcode) .
                               oos_draw_hidden_field('sepabanktransfer_city', $sepabanktransfer_city) .
                               oos_draw_hidden_field('sepabanktransfer_state', $sepabanktransfer_state) .
                               oos_draw_hidden_field('sepabanktransfer_email_address', $sepabanktransfer_email_address) .
                               oos_draw_hidden_field('sepabanktransfer_name', $sepabanktransfer_name) .
                               oos_draw_hidden_field('sepabanktransfer_iban', $sepabanktransfer_iban) .
                               oos_draw_hidden_field('sepabanktransfer_bic', $sepabanktransfer_bic);
	$sDate = oos_date_short(date("Y-m-d\ H:i:s"));
	$sMandat = sprintf ($aLang['module_payment_sepabt_text_mandat'], MODULE_PAYMENT_SEPABT_PAYEE, MODULE_PAYMENT_SEPABT_CREDITORID, $mandatsreferenz_id);

	if (isset($bMandatError) and ($bMandatError == true))  {
		$oSmarty->assign(
			array(
				'oos_payment_error' => 'true',
				'error' => $error
			)
		);
	} else {	
	

		include 'includes/lib/tcpdf/config/lang/ger.php';
		include 'includes/lib/tcpdf/tcpdf.php';

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


		require_once 'includes/lib/htmlpurifier/library/HTMLPurifier.auto.php';

		$config = HTMLPurifier_Config::createDefault();
		# $config->set('HTML.Allowed', 'p[style],span[style],b,strong,em,a[href],i,img[src],br,ul,li,dl,dt,div,i,ul,li,ol,blockquote,br,h1,h2,h3,h4,h5,h6,code,pre,sub,sup,del,div');
		// 'div,p,b,strong,em,a[href],i,ul,li,ol,blockquote,br,h1,h2,h3,h4,h5,h6,code,pre,sub,sup,del');
		#  $config->set('URI.Base', 'http://www.example.com');
		# $config->set('URI.MakeAbsolute', true);
		$config->set('AutoFormat.AutoParagraph', true);
		$config->set('Core.Encoding', 'UTF-8');
		$purifier = new HTMLPurifier($config);

		#$document = $purifier->purify($sMandat);

		# $document = $sMandat; 
		$mandat_file = 'mandat-' . $mandatsreferenz_id . '-' . date('YmdHis');

		// set document information
		$pdf->SetCreator('Michaelas Shop-Ecke) ');
		$pdf->SetAuthor('Michaelas Shop-Ecke');
		$sTitle = 'SEPA-Lastschriftmandat';


		$pdf->SetTitle($sTitle);

		// Count width of logo for better presentation
		$logodata = getimagesize(K_PATH_IMAGES.PDF_HEADER_LOGO);
		$logowidth = (int) ((14 * $logodata[0]) / $logodata[1]);

		//$pdf->SetSubject('TCPDF Tutorial');
		//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, $logowidth, 'Michaelas Shop-Ecke', 'Online-Shop für Kaffeevollautomaten, Zubehör und Ersatzteile');
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);	
	
	
	
		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		//set some language-dependent strings
		$pdf->setLanguageArray($l);
		// ---------------------------------------------------------
		// set default font subsetting mode
		$pdf->setFontSubsetting(true);
		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.
		// set font
		$pdf->SetFont('dejavusans', '', 10);

		// add a page
		$pdf->AddPage();
		// Set some content to print
		$html = '<h1>' . $sTitle . '</h1>';

		$html .= '<p></p>';
		$html .= $sMandat;   


		$html .= '<p></p>';
		$html .= '<table border="0" cellpadding="2" cellspacing="2">	
 <tr>
	<td>Name des Zahlungspflichtigen:</td>
	<td>' . html_entity_decode(utf8_encode($sepabanktransfer_owner), ENT_QUOTES) . '</td>
 </tr>
 <tr>
	<td>Stra&szlig;e und Hausnummer:</td>
	<td>' . html_entity_decode(utf8_encode($sepabanktransfer_street_address), ENT_QUOTES) . '</td>	
	</tr>
 <tr>
  <td>Postleitzahl /Ort:</td>
  <td>' . html_entity_decode($sepabanktransfer_postcode, ENT_QUOTES) . ' ' . html_entity_decode(utf8_encode($sepabanktransfer_city), ENT_QUOTES) . '</td>
	</tr>
 <tr>
  <td>Land:</td>
  <td>' . html_entity_decode(utf8_encode($sepabanktransfer_state), ENT_QUOTES) . '</td>
	</tr>
 <tr>
  <td>E-Mail:</td>
  <td>' . html_entity_decode($sepabanktransfer_email_address, ENT_QUOTES) . '</td>
	</tr>
 <tr>
  <td>Swift BIC:</td>
   <td>' . html_entity_decode($sepabanktransfer_bic, ENT_QUOTES) . '</td>
	</tr>
 <tr>
  <td>Bankkontonummer - IBAN:</td>
   <td>' . html_entity_decode($sepabanktransfer_iban, ENT_QUOTES) . '</td>
 </tr>
</table>';
		$html .= '<p></p><p> Ilshofen den,' . $sDate . ' ' . html_entity_decode(utf8_encode($sepabanktransfer_owner), ENT_QUOTES);

		$document = $purifier->purify($html);

		$pdf->writeHTML($document, true, false, true, false, ''); 
		
		// reset pointer to the last page
		$pdf->lastPage();

 
		// ---------------------------------------------------------
		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output(OOS_TEMP_PATH . '/tcpdf/' . $mandat_file . '.pdf', 'F');
		$_SESSION['mandat_file'] = $mandat_file;
	}						   
  // assign Smarty variables;
  $oSmarty->assign(
      array(
          'oos_breadcrumb' => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'confirmation.gif',
		  'sCheckBanktransfer' => $sCheckBanktransfer,
		  'nSepabt'           =>  $nSepabt,
		  'nMandat'         => $nMandat,
		  'mandat' 			=> $sMandat,
		  'mandatdate'		=> $sDate,
		  'process_button_string' => $process_button_string
      )
  );

    $oSmarty->assign(
      array(
		  'sepabanktransfer_owner' => $sepabanktransfer_owner,
		  'sepabanktransfer_street_address' => $sepabanktransfer_street_address,
		  'sepabanktransfer_postcode' => $sepabanktransfer_postcode,
		  'sepabanktransfer_city' => $sepabanktransfer_city,
		  'sepabanktransfer_state' => $sepabanktransfer_state,
		  'sepabanktransfer_email_address' => $sepabanktransfer_email_address,
		  'sepabanktransfer_name' => $sepabanktransfer_name,
		  'sepabanktransfer_iban' => $sepabanktransfer_iban,
		  'sepabanktransfer_bic' => $sepabanktransfer_bic
		  )
  );


} elseif ($nSepabt == 1) {

	$sepabanktransfertable = $oostable['sepabanktransfer'];
	$sepa_sql = "SELECT sepabanktransfer_owner, sepabanktransfer_street_address, sepabanktransfer_postcode,
						sepabanktransfer_city, sepabanktransfer_state, sepabanktransfer_email_address,
						sepabanktransfer_name, sepabanktransfer_iban, sepabanktransfer_bic, sepabanktransfer_status
				FROM $sepabanktransfertable
				WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'";
	$sepa_result = $dbconn->Execute($sepa_sql);

  if (!$sepa_result->RecordCount()) {
	$customerstable = $oostable['customers'];
    $sql = "SELECT customers_firstname, customers_lastname, customers_email_address
            FROM $customerstable
            WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'";
    $customer = $dbconn->Execute($sql);
    $customer_values = $customer->fields;
    $customers_name = $customer_values['customers_firstname'] . ' ' . $customer_values['customers_lastname'];

	$sepabanktransfer_owner = $customers_name;
	$sepabanktransfer_street_address = '';
	$sepabanktransfer_postcode = '';
	$sepabanktransfer_city = '';
	$sepabanktransfer_state = 'Deutschland';
	$sepabanktransfer_email_address = $customer_values['customers_email_address'];
	$sepabanktransfer_name = '';
	$sepabanktransfer_iban = '';
	$sepabanktransfer_bic  = '';

  } else {
	$sepa_info = $sepa_result->fields;

	$sepabanktransfer_owner = $sepa_info['sepabanktransfer_owner'];
	$sepabanktransfer_street_address = $sepa_info['sepabanktransfer_street_address'];
	$sepabanktransfer_postcode = $sepa_info['sepabanktransfer_postcode'];
	$sepabanktransfer_city = $sepa_info['sepabanktransfer_city'];
	$sepabanktransfer_state = $sepa_info['sepabanktransfer_state'];
	$sepabanktransfer_email_address = $sepa_info['sepabanktransfer_email_address'];
	$sepabanktransfer_name = $sepa_info['sepabanktransfer_name'];
	$sepabanktransfer_iban = $sepa_info['sepabanktransfer_iban'];
	$sepabanktransfer_bic  = $sepa_info['sepabanktransfer_bic'];
  }

   if (isset($_GET['payment_error']) && is_object(${$_GET['payment_error']}) && ($error = ${$_GET['payment_error']}->get_error())) {
		$sepabanktransfer_owner = oos_prepare_input($_GET['owner']);
		$sepabanktransfer_street_address = oos_prepare_input($_GET['street_address']);
		$sepabanktransfer_postcode = oos_prepare_input($_GET['postcode']);
		$sepabanktransfer_city = oos_prepare_input($_GET['city']);
		$sepabanktransfer_state = oos_prepare_input($_GET['state']);
		$sepabanktransfer_email_address = oos_prepare_input($_GET['email_address']);
		$sepabanktransfer_name = oos_prepare_input($_GET['name']);
		$sepabanktransfer_iban = oos_prepare_input($_GET['iban']);
		$sepabanktransfer_bic = oos_prepare_input($_GET['bic']);

    $oSmarty->assign(
        array(
            'oos_payment_error' => 'true',
            'error' => $error
        )
    );
  }



    $oSmarty->assign(
      array(
		  'sepabanktransfer_owner' => $sepabanktransfer_owner,
		  'sepabanktransfer_street_address' => $sepabanktransfer_street_address,
		  'sepabanktransfer_postcode' => $sepabanktransfer_postcode,
		  'sepabanktransfer_city' => $sepabanktransfer_city,
		  'sepabanktransfer_state' => $sepabanktransfer_state,
		  'sepabanktransfer_email_address' => $sepabanktransfer_email_address,
		  'sepabanktransfer_name' => $sepabanktransfer_name,
		  'sepabanktransfer_iban' => $sepabanktransfer_iban,
		  'sepabanktransfer_bic' => $sepabanktransfer_bic
		  )
  );


  // assign Smarty variables;
  $oSmarty->assign(
      array(
          'oos_breadcrumb' => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'confirmation.gif',
		  'sCheckBanktransfer' => $sCheckBanktransfer,
		  'nSepabt'           =>  $nSepabt,
		  'nMandat'         => $nMandat
      )
  );

  if (MODULE_ORDER_TOTAL_INSTALLED) {
    $order_total_modules->process();
    $order_total_output = $order_total_modules->output();
    $oSmarty->assign('order_total_output', $order_total_output);
  }

  if (is_array($payment_modules->modules)) {
    if ($confirmation = $payment_modules->confirmation()) {
      $oSmarty->assign('confirmation', $confirmation);
    }
  }

  if (isset($$_SESSION['payment']->form_action_url)) {
    $form_action_url = $$_SESSION['payment']->form_action_url;
  } else {
    $form_action_url = oos_href_link($aModules['checkout'], $aFilename['checkout_process'], '', 'SSL');
  }
  $oSmarty->assign('form_action_url', $form_action_url);

  if (is_array($payment_modules->modules)) {
    $payment_modules_process_button =  $payment_modules->process_button();
  }

  $oSmarty->assign('payment_modules_process_button', $payment_modules_process_button);
  $oSmarty->assign('order', $oOrder);
} elseif ($sCheckBanktransfer == '0') {

  // assign Smarty variables;
  $oSmarty->assign(
      array(
          'oos_breadcrumb' => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'confirmation.gif',
		  'sCheckBanktransfer' => $sCheckBanktransfer,
		  'nSepabt'           =>  $nSepabt,
		  'nMandat'         => $nMandat
      )
  );

  if (MODULE_ORDER_TOTAL_INSTALLED) {
    $order_total_modules->process();
    $order_total_output = $order_total_modules->output();
    $oSmarty->assign('order_total_output', $order_total_output);
  }

  if (is_array($payment_modules->modules)) {
    if ($confirmation = $payment_modules->confirmation()) {
      $oSmarty->assign('confirmation', $confirmation);
    }
  }

  if (isset($$_SESSION['payment']->form_action_url)) {
    $form_action_url = $$_SESSION['payment']->form_action_url;
  } else {
    $form_action_url = oos_href_link($aModules['checkout'], $aFilename['checkout_process'], '', 'SSL');
  }
  $oSmarty->assign('form_action_url', $form_action_url);

  if (is_array($payment_modules->modules)) {
    $payment_modules_process_button =  $payment_modules->process_button();
  }

  $oSmarty->assign('payment_modules_process_button', $payment_modules_process_button);
  $oSmarty->assign('order', $oOrder);

  } else {


  $oSmarty->assign(
      array(
          'oos_breadcrumb' => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'confirmation.gif',
		  'sCheckBanktransfer' => $sCheckBanktransfer,
		  'nSepabt'           =>  $nSepabt,
		  'nMandat'         => $nMandat
		  )
  );


  if (isset($_GET['payment_error']) && is_object(${$_GET['payment_error']}) && ($error = ${$_GET['payment_error']}->get_error())) {
    $oSmarty->assign(
        array(
            'oos_payment_error' => 'true',
            'error' => $error
        )
    );
  }


  $banktransfer_owner = $oOrder->billing['firstname'] . ' ' . $oOrder->billing['lastname'];

      $js = '<script type="text/javascript" language="JavaScript">' . "\n" .
             '<!-- ' . "\n" .
             'function check_form() {' . "\n" .
             '  var error = 0;' . "\n" .
             '  var error_message = "Notwendige Angaben fehlen!\nBitte richtig ausfüllen.\n\n"' . "\n";



     $js .= '  var banktransfer_blz = document.checkout_payment.banktransfer_blz.value;' . "\n" .
            '  var banktransfer_number = document.checkout_payment.banktransfer_number.value;' . "\n" .
            '  var banktransfer_owner = document.checkout_payment.banktransfer_owner.value;' . "\n";

      if (MODULE_PAYMENT_BANKTRANSFER_FAX_CONFIRMATION == 'true'){
        $js .= '  var banktransfer_fax = document.checkout_payment.banktransfer_fax.checked;' . "\n" .
               '  if (banktransfer_fax == false) {' . "\n";
      }

      $js .= '    if (banktransfer_owner == "") {' . "\n" .
             '       error_message = error_message + "' . $aLang['js_bank_owner'] . '";' . "\n" .
             '       error = 1;' . "\n" .
             '     }' . "\n" .
             '     if (banktransfer_blz == "") {' . "\n" .
             '       error_message = error_message + "' . $aLang['js_bank_blz'] . '";' . "\n" .
             '       error = 1;' . "\n" .
             '     }' . "\n" .
             '     if (banktransfer_number == "") {' . "\n" .
             '       error_message = error_message + "' . $aLang['js_bank_number'] . '";' . "\n" .
             '       error = 1;' . "\n" .
             '    }' . "\n";

      if (MODULE_PAYMENT_BANKTRANSFER_FAX_CONFIRMATION == 'true'){
        $js .= '  }' . "\n" ;
      }

      $js .= '' . "\n" .
             'if (error == 1 && submitter != 1) {' . "\n" .
             '   alert(error_message);' . "\n" .
             '   return false;' . "\n" .
             '} else {' . "\n" .
             '   return true;' . "\n" .
             '}' . "\n" .
             '}' . "\n" .
             '//--></script>' . "\n";

  // JavaScript
  $oSmarty->assign('oos_js', $js);
  // assign Smarty variables;
  $oSmarty->assign(
      array(
          'oos_breadcrumb' => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'confirmation.gif',
		  'sCheckBanktransfer' => $sCheckBanktransfer,
		  'banktransfer_owner' => $banktransfer_owner,
		  'nSepabt'           =>  $nSepabt,
		  'nMandat'         => $nMandat
		  )
  );
}



  $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
  $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';

