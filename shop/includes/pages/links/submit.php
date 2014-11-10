<?php
/* ----------------------------------------------------------------------
   $Id: submit.php,v 1.1 2007/06/07 16:47:09 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: links_submit.php,v 1.00 2003/10/03 
   ----------------------------------------------------------------------
   Links Manager

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  require 'includes/languages/' . $sLanguage . '/links_submit.php';

  $process = false;

  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    $process = true;

    $error = 'false';


    if (strlen($links_title) < ENTRY_LINKS_TITLE_MIN_LENGTH) {
      $error= 'true';
      $links_title_error = 'true';
    }

    if (strlen($links_url) < ENTRY_LINKS_URL_MIN_LENGTH) {
      $error= 'true';
      $links_url_error = 'true';
    }

    if (strlen($links_description) < ENTRY_LINKS_DESCRIPTION_MIN_LENGTH) {
      $error= 'true';
      $links_description_error = 'true';
    }

    if (strlen($links_contact_name) < ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH) {
      $error= 'true';
      $links_contact_name_error = 'true';
    }

    if (strlen($links_contact_email) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
      $error= 'true';
      $links_contact_email_error= 'true';
    } elseif (oos_validate_is_email($links_contact_email) == false) {
      $error= 'true';
      $links_contact_email_error= 'true';
    }

    if (strlen($links_reciprocal_url) < ENTRY_LINKS_URL_MIN_LENGTH) {
      $error= 'true';
      $links_reciprocal_url_error= 'true';
    }

    if ($error == 'false') {
      if($links_image == 'http://') {
        $links_image = '';
      }

      // default values
      $links_date_added = 'now()';
      $links_status = '1'; // Pending approval
      $links_rating = '0'; 

      $sql_data_array = array('links_url' => $links_url,
                              'links_image_url' => $links_image,
                              'links_contact_name' => $links_contact_name,
                              'links_contact_email' => $links_contact_email,
                              'links_reciprocal_url' => $links_reciprocal_url, 
                              'links_date_added' => $links_date_added, 
                              'links_status' => $links_status, 
                              'links_rating' => $links_rating);

      oos_db_perform($oostable['links'], $sql_data_array);

      $links_id = $dbconn->Insert_ID();

      $categories_result = $dbconn->Execute("SELECT link_categories_id FROM " . $oostable['link_categories_description'] . " WHERE link_categories_name = '" . intval($links_category) . "' AND link_categories_languages_id = '" .  intval($nLanguageID) . "'");
      $link_categories_id = $categories_result->fields['link_categories_id'];

      $dbconn->Execute("INSERT INTO " . $oostable['links_to_link_categories'] . " (links_id, link_categories_id) values ('" . intval($links_id) . "', '" . intval($link_categories_id) . "')");

      $sql_data_array = array('links_id' => $links_id,
                              'links_languages_id' => $sLanguage,
                              'links_title' => $links_title,
                              'links_description' => $links_description);

      oos_db_perform($oostable['links_description'], $sql_data_array);


// build the message content
      $name = $links_contact_name;

      $email_text = sprintf($aLang['email_greet_none'], $links_contact_name);
      $email_text .= $aLang['email_welcome'] . $aLang['email_text'] . $aLang['email_contact'] . $aLang['email_warning'];

      oos_mail($name, $links_contact_email, $aLang['email_subject'], $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

      oos_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $aLang['email_owner_subject'], $aLang['email_owner_text'], $name, $links_contact_email);

      oos_redirect(oos_href_link($aModules['links'], $aFilename['links_submit_success'], '', 'SSL'));
    }
  }

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aModules['links'], $aFilename['links']));

  if (isset($_GET['lPath'])) {
    $current_categories_id = oos_var_prep_for_os($_GET['lPath']);

    $default_category = '';
    $current_categories_result = $dbconn->Execute("SELECT link_categories_name FROM " . $oostable['link_categories_description'] . " WHERE link_categories_id ='" . intval($current_categories_id) . "' AND link_categories_languages_id = '" .  intval($nLanguageID) . "'");
    if ($categories = $current_categories_result->fields) {
      $default_category = $categories['link_categories_name'];
      $oBreadcrumb->add($default_category, oos_href_link($aModules['links'], $aFilename['links'] . '?lPath=' . intval($lPath)));
    }
  }

  //link category drop-down list
  $categories_array = array();
  $categories_result = $dbconn->Execute("SELECT lcd.link_categories_id, lcd.link_categories_name FROM " . $oostable['link_categories_description'] . " lcd WHERE lcd.link_categories_languages_id = '" .  intval($nLanguageID) . "' ORDER BY lcd.link_categories_name");
  while ($categories_values = $categories_result->fields) {
    $categories_array[] = array('id' => $categories_values['link_categories_name'],
                                'text' => $categories_values['link_categories_name']);
    $categories_result->MoveNext();
  }
  $links_category_pull_down_menu = oos_draw_pull_down_menu('links_category', $categories_array, $default_category);


  $oBreadcrumb->add($aLang['navbar_title_2']);

  ob_start();
  require 'js/links_form_check.js.php';
  $javascript = ob_get_contents();
  ob_end_clean();

  $aOption['template_main'] = $sTheme . '/modules/links_submit.html';
  $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';

  $nPageType = OOS_PAGE_TYPE_MAINPAGE;

  require 'includes/oos_system.php';
  if (!isset($option)) {
    require 'includes/info_message.php';
    require 'includes/oos_blocks.php';
    require 'includes/oos_counter.php';
  }

  // assign Smarty variables;
  $oSmarty->assign(
      array(
          'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'account.gif',

          'error'                      => $error,
          'links_title_error'          => $links_title_error,
          'links_url_error'            => $links_url_error,
          'links_description_error'    => $links_description_error,
          'links_image_error'          => $links_image_error,
          'links_contact_name_error'   => $links_contact_name_error,
          'links_contact_email_error'  => $links_contact_email_error,
          'links_reciprocal_url_error' => $links_reciprocal_url_error,

          'links_title'                => $links_title,
          'links_url'                  => $links_url,
          'links_category_pull_down_menu' => $links_category_pull_down_menu,
          'links_description'          => $links_description,
          'links_image'                => $links_image,
          'links_contact_name'         => $links_contact_name,
          'links_contact_email'        => $links_contact_email,
          'links_reciprocal_url'       => $links_reciprocal_url
      )
  );

  // JavaScript
  $oSmarty->assign('oos_js', $javascript);

  $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
  $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';

?>
