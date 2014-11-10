<?php
/* ----------------------------------------------------------------------
   $Id: links.php,v 1.1 2007/06/08 17:14:41 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: links.php,v 1.00 2003/10/02 
   ----------------------------------------------------------------------
   Links Manager

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';

// define our link functions
  require 'includes/functions/function_links.php';
  require 'includes/functions/function_validations.php';

  $links_statuses = array();
  $links_status_array = array();
  $links_status_result = $dbconn->Execute("SELECT links_status_id, links_status_name FROM " . $oostable['links_status'] . " WHERE links_status_languages_id = '" . intval($_SESSION['language_id']) . "'");

  while ($links_status = $links_status_result->fields) {
    $links_statuses[] = array('id' => $links_status['links_status_id'],
                              'text' => $links_status['links_status_name']);
    $links_status_array[$links_status['links_status_id']] = $links_status['links_status_name'];

     // Move that ADOdb pointer!
    $links_status_result->MoveNext();
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  $error = false;
  $processed = false;

  if (!empty($action)) {
    switch ($action) {
      case 'insert':
      case 'update':
        $links_id = oos_db_prepare_input($_GET['lID']);
        $links_status = oos_db_prepare_input($_POST['links_status']);

        if (strlen($links_title) < ENTRY_LINKS_TITLE_MIN_LENGTH) {
          $error = true;
          $entry_links_title_error = true;
        } else {
          $entry_links_title_error = false;
        }

        if (strlen($links_url) < ENTRY_LINKS_URL_MIN_LENGTH) {
          $error = true;
          $entry_links_url_error = true;
        } else {
          $entry_links_url_error = false;
        }

        if (strlen($links_description) < ENTRY_LINKS_DESCRIPTION_MIN_LENGTH) {
          $error = true;
          $entry_links_description_error = true;
        } else {
          $entry_links_description_error = false;
        }

        if (strlen($links_contact_name) < ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH) {
          $error = true;
          $entry_links_contact_name_error = true;
        } else {
          $entry_links_contact_name_error = false;
        }

        if (strlen($links_contact_email) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
          $error = true;
          $entry_links_contact_email_error = true;
        } else {
          $entry_links_contact_email_error = false;
        }

        if (!oos_validate_email($links_contact_email)) {
          $error = true;
          $entry_links_contact_email_check_error = true;
        } else {
          $entry_links_contact_email_check_error = false;
        }

        if (strlen($links_reciprocal_url) < ENTRY_LINKS_URL_MIN_LENGTH) {
          $error = true;
          $entry_links_reciprocal_url_error = true;
        } else {
          $entry_links_reciprocal_url_error = false;
        }

        if ($error == false) {
          if (oos_empty($links_image_url) || ($links_image_url == 'http://')) {
            $links_image_url = '';
          }

          $sql_data_array = array('links_url' => $links_url,
                                  'links_image_url' => $links_image_url,
                                  'links_contact_name' => $links_contact_name,
                                  'links_contact_email' => $links_contact_email,
                                  'links_reciprocal_url' => $links_reciprocal_url, 
                                  'links_status' => $links_status, 
                                  'links_rating' => $links_rating);

          if ($action == 'update') {
            $sql_data_array['links_last_modified'] = 'now()';
          } else if($action == 'insert') {
            $sql_data_array['links_date_added'] = 'now()';
          }

          if ($action == 'update') {
            oos_db_perform($oostable['links'], $sql_data_array, 'update', "links_id = '" . (int)$links_id . "'");
          } else if($action == 'insert') {
            oos_db_perform($oostable['links'], $sql_data_array);

            $links_id = $dbconn->Insert_ID();
          }

          $categories_result = $dbconn->Execute("SELECT link_categories_id FROM " . $oostable['link_categories_description'] . " WHERE link_categories_name = '" . $links_category . "' AND link_categories_languages_id = '" . intval($_SESSION['language_id']) . "'");

          $categories = $categories_result->fields;
          $link_categories_id = $categories['link_categories_id'];

          if ($action == 'update') {
            $dbconn->Execute("UPDATE " . $oostable['links_to_link_categories'] . " SET link_categories_id = '" . (int)$link_categories_id . "' WHERE links_id = '" . (int)$links_id . "'");
          } else if($action == 'insert') {
            $dbconn->Execute("INSERT INTO " . $oostable['links_to_link_categories'] . " ( links_id, link_categories_id) values ('" . (int)$links_id . "', '" . (int)$link_categories_id . "')");
          }

          $sql_data_array = array('links_title' => $links_title,
                                  'links_description' => $links_description);

          if ($action == 'update') {
            oos_db_perform($oostable['links_description'], $sql_data_array, 'update', "links_id = '" . (int)$links_id . "' AND links_languages_id = '" . intval($_SESSION['language_id']) . "'");
          } else if($action == 'insert') {
            $insert_sql_data = array('links_id' => $links_id,
                                     'links_languages_id' => $_SESSION['language_id']);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
            oos_db_perform($oostable['links_description'], $sql_data_array);
          }

          if (isset($_POST['links_notify']) && ($_POST['links_notify'] == 'on')) {
            $email = sprintf(EMAIL_TEXT_STATUS_UPDATE, $links_contact_name, $links_status_array[$links_status]) . "\n\n" . STORE_OWNER . "\n" . STORE_NAME;

            oos_mail($links_contact_name, $links_contact_email, EMAIL_TEXT_SUBJECT, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

          }

          oos_redirect_admin(oos_href_link_admin($aFilename['links'], oos_get_all_get_params(array('lID', 'action')) . 'lID=' . $links_id));
        } else if ($error == true) {
          $lInfo = new objectInfo($_POST);
          $processed = true;
        }
        break;

      case 'deleteconfirm':
        $links_id = oos_db_prepare_input($_GET['lID']);

        oos_remove_link($links_id);

        oos_redirect_admin(oos_href_link_admin($aFilename['links'], oos_get_all_get_params(array('lID', 'action'))));
        break;

      default:
        $links_result = $dbconn->Execute("SELECT l.links_id, ld.links_title, l.links_url, ld.links_description, l.links_contact_email, l.links_status, l.links_image_url, l.links_contact_name, l.links_reciprocal_url, l.links_status, l.links_rating FROM " . $oostable['links'] . " l left join " . $oostable['links_description'] . " ld ON ld.links_id = l.links_id WHERE ld.links_id = l.links_id and l.links_id = '" . (int)$_GET['lID'] . "' AND ld.links_languages_id = '" . intval($_SESSION['language_id']) . "'");
        $links = $links_result->fields;

        $categories_result = $dbconn->Execute("SELECT lcd.link_categories_name AS links_category FROM " . $oostable['links_to_link_categories'] . " l2lc LEFT JOIN " . $oostable['link_categories_description'] . " lcd ON lcd.link_categories_id = l2lc.link_categories_id WHERE l2lc.links_id = '" . (int)$_GET['lID'] . "' AND lcd.link_categories_languages_id = '" . intval($_SESSION['language_id']) . "'");
        $category = $categories_result->fields;

        $lInfo_array = array_merge($links, $category);
        $lInfo = new objectInfo($lInfo_array);
    }
  }
  $no_js_general = true;
  require 'includes/oos_header.php';

  if ($action == 'edit' || $action == 'update' || $action == 'new' || $action == 'insert') {
?>
<script language="javascript"><!--

function check_form() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";

  var links_title = document.links.links_title.value;
  var links_url = document.links.links_url.value;
  var links_category = document.links.links_category.value;
  var links_description = document.links.links_description.value;
  var links_image_url = document.links.links_image_url.value;
  var links_contact_name = document.links.links_contact_name.value;
  var links_contact_email = document.links.links_contact_email.value;
  var links_reciprocal_url = document.links.links_reciprocal_url.value;
  var links_rating = document.links.links_rating.value;

  if (links_title == "" || links_title.length < <?php echo ENTRY_LINKS_TITLE_MIN_LENGTH; ?>) {
    error_message = error_message + "* " + "<?php echo ENTRY_LINKS_TITLE_ERROR; ?>" + "\n";
    error = 1;
  }

  if (links_url == "" || links_url.length < <?php echo ENTRY_LINKS_URL_MIN_LENGTH; ?>) {
    error_message = error_message + "* " + "<?php echo ENTRY_LINKS_URL_ERROR; ?>" + "\n";
    error = 1;
  }

  if (links_description == "" || links_description.length < <?php echo ENTRY_LINKS_DESCRIPTION_MIN_LENGTH; ?>) {
    error_message = error_message + "* " + "<?php echo ENTRY_LINKS_DESCRIPTION_ERROR; ?>" + "\n";
    error = 1;
  }

  if (links_contact_name == "" || links_contact_name.length < <?php echo ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "* " + "<?php echo ENTRY_LINKS_CONTACT_NAME_ERROR; ?>" + "\n";
    error = 1;
  }

  if (links_contact_email == "" || links_contact_email.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_EMAIL_ADDRESS; ?>";
    error = 1;
  }

  if (links_reciprocal_url == "" || links_reciprocal_url.length < <?php echo ENTRY_LINKS_URL_MIN_LENGTH; ?>) {
    error_message = error_message + "* " + "<?php echo ENTRY_LINKS_RECIPROCAL_URL_ERROR; ?>" + "\n";
    error = 1;
  }

  if (links_rating == "") {
    error_message = error_message + "* " + "<?php echo ENTRY_LINKS_RATING_ERROR; ?>" + "\n";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
<?php
  }
?>
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<?php require 'includes/oos_blocks.php'; ?>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ($action == 'edit' || $action == 'update' || $action == 'new' || $action == 'insert') {
    if ($action == 'edit' || $action == 'update') {
      $form_action = 'update';
      $contact_name_default = '';
      $contact_email_default = '';
    } else {
      $form_action = 'insert';
      $contact_name_default = STORE_OWNER;
      $contact_email_default = STORE_OWNER_EMAIL_ADDRESS;
    }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo oos_draw_form('links', $aFilename['links'], oos_get_all_get_params(array('action')) . 'action=' . $form_action, 'post', 'onSubmit="return check_form();"'); ?>
        <td class="formAreaTitle"><?php echo CATEGORY_WEBSITE; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_LINKS_TITLE; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_links_title_error == true) {
      echo oos_draw_input_field('links_title', $lInfo->links_title, 'maxlength="64"') . '&nbsp;' . ENTRY_LINKS_TITLE_ERROR;
    } else {
      echo $lInfo->links_title . oos_draw_hidden_field('links_title');
    }
  } else {
    echo oos_draw_input_field('links_title', $lInfo->links_title, 'maxlength="64"', true);
  }
?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_LINKS_URL; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_links_url_error == true) {
      echo oos_draw_input_field('links_url', $lInfo->links_url, 'maxlength="255"') . '&nbsp;' . ENTRY_LINKS_URL_ERROR;
    } else {
      echo $lInfo->links_url . oos_draw_hidden_field('links_url');
    }
  } else {
    echo oos_draw_input_field('links_url', oos_is_not_null($lInfo->links_url) ? $lInfo->links_url : 'http://', 'maxlength="255"', true);
  }
?></td>
          </tr>
<?php
    $categories_array = array();
    $categories_result = $dbconn->Execute("SELECT link_categories_id, link_categories_name FROM " . $oostable['link_categories_description'] . " WHERE link_categories_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY link_categories_name");
    while ($categories_values = $categories_result->fields) {
      $categories_array[] = array('id' => $categories_values['link_categories_name'], 'text' => $categories_values['link_categories_name']);

      // Move that ADOdb pointer!
      $categories_result->MoveNext();
    }
?>
          <tr>
            <td class="main"><?php echo ENTRY_LINKS_CATEGORY; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    echo $lInfo->links_category . oos_draw_hidden_field('links_category');
  } else {
    echo oos_draw_pull_down_menu('links_category', $categories_array, $lInfo->links_category, '', true);
  }
?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_LINKS_DESCRIPTION; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_links_description_error == true) {
      echo oos_draw_textarea_field('links_description', 'hard', 40, 5, $lInfo->links_description) . '&nbsp;' . ENTRY_LINKS_DESCRIPTION_ERROR;
    } else {
      echo $lInfo->links_description . oos_draw_hidden_field('links_description');
    }
  } else {
    echo oos_draw_textarea_field('links_description', 'hard', 40, 5, $lInfo->links_description) . TEXT_FIELD_REQUIRED;
  }
?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_LINKS_IMAGE; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    echo $lInfo->links_image_url . oos_draw_hidden_field('links_image_url');
  } else {
    echo oos_draw_input_field('links_image_url', oos_is_not_null($lInfo->links_image_url) ? $lInfo->links_image_url : 'http://', 'maxlength="255"');
  }
?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_CONTACT; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_LINKS_CONTACT_NAME; ?></td>
            <td class="main">
<?php
    if ($error == true) {
      if ($entry_links_contact_name_error == true) {
        echo oos_draw_input_field('links_contact_name', $lInfo->links_contact_name, 'maxlength="64"', true) . '&nbsp;' . ENTRY_LINKS_CONTACT_NAME_ERROR;
      } else {
        echo $lInfo->links_contact_name . oos_draw_hidden_field('links_contact_name');
      }
    } else {
      echo oos_draw_input_field('links_contact_name', oos_is_not_null($lInfo->links_contact_name) ? $lInfo->links_contact_name : $contact_name_default, 'maxlength="64"', true);
    }
?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_links_contact_email_error == true) {
      echo oos_draw_input_field('links_contact_email', $lInfo->links_contact_email, 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR;
    } elseif ($entry_links_contact_email_check_error == true) {
      echo oos_draw_input_field('links_contact_email', $lInfo->links_contact_email, 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_CHECK_ERROR;
    } else {
      echo $lInfo->links_contact_email . oos_draw_hidden_field('links_contact_email');
    }
  } else {
    echo oos_draw_input_field('links_contact_email', oos_is_not_null($lInfo->links_contact_email) ? $lInfo->links_contact_email : $contact_email_default, 'maxlength="96"', true);
  }
?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_RECIPROCAL; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_LINKS_RECIPROCAL_URL; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_links_reciprocal_url_error == true) {
      echo oos_draw_input_field('links_reciprocal_url', $lInfo->links_reciprocal_url, 'maxlength="255"') . '&nbsp;' . ENTRY_LINKS_RECIPROCAL_URL_ERROR;
    } else {
      echo $lInfo->links_reciprocal_url . oos_draw_hidden_field('links_reciprocal_url');
    }
  } else {
    echo oos_draw_input_field('links_reciprocal_url', oos_is_not_null($lInfo->links_reciprocal_url) ? $lInfo->links_reciprocal_url : 'http://', 'maxlength="255"', true);
  }
?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_OPTIONS; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_LINKS_STATUS; ?></td>
            <td class="main">
<?php 
  $link_statuses = array();
  $links_status_array = array();
  $links_status_result = $dbconn->Execute("SELECT links_status_id, links_status_name FROM " . $oostable['links_status'] . " WHERE links_status_languages_id = '" . intval($_SESSION['language_id']) . "'");
  while ($links_status = $links_status_result->fields) {
    $link_statuses[] = array('id' => $links_status['links_status_id'],
                             'text' => $links_status['links_status_name']);
    $links_status_array[$links_status['links_status_id']] = $links_status['links_status_name'];

    // Move that ADOdb pointer!
    $links_status_result->MoveNext();
  }

  echo oos_draw_pull_down_menu('links_status', $link_statuses, $lInfo->links_status); 

  if ($action == 'edit' || $action == 'update') {
    echo '&nbsp;&nbsp;' . ENTRY_LINKS_NOTIFY_CONTACT;
    echo oos_draw_checkbox_field('links_notify');
  }
?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_LINKS_RATING; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_links_rating_error == true) {
      echo oos_draw_input_field('links_rating', $lInfo->links_rating, 'size ="2" maxlength="2"') . '&nbsp;' . ENTRY_LINKS_RATING_ERROR;
    } else {
      echo $lInfo->links_rating . oos_draw_hidden_field('links_rating');
    }
  } else {
    echo oos_draw_input_field('links_rating', oos_is_not_null($lInfo->links_rating) ? $lInfo->links_rating : '0', 'size ="2" maxlength="2"', true);
  }
?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo (($action == 'edit') ? oos_image_swap_submits('update','update_off.gif', IMAGE_UPDATE) : oos_image_swap_submits('insert','insert_off.gif', IMAGE_INSERT)) . ' <a href="' . oos_href_link_admin($aFilename['links'], oos_get_all_get_params(array('action'))) .'">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr></form>
<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><?php echo oos_draw_form('search', $aFilename['links'], '', 'get'); ?>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . oos_draw_input_field('search'); ?></td>
          </form></tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TITLE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_URL; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_CLICKS; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $search = '';
    if (isset($_GET['search']) && oos_is_not_null($_GET['search'])) {
      $keywords = oos_db_input(oos_db_prepare_input($_GET['search']));
      $search = " AND ld.links_title like '%" . $keywords . "%'";
    }
    $links_result_raw = "SELECT l.links_id, l.links_url, l.links_image_url, l.links_date_added, l.links_last_modified, l.links_status, l.links_clicked, ld.links_title, ld.links_description, l.links_contact_name, l.links_contact_email, l.links_reciprocal_url, l.links_status 
                         FROM " . $oostable['links'] . " l LEFT JOIN 
                              " . $oostable['links_description'] . " ld ON 
                              l.links_id = ld.links_id 
                         WHERE ld.links_languages_id = '" . intval($_SESSION['language_id']) . "'" . $search . " 
                         ORDER BY ld.links_title, l.links_url";
    $links_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $links_result_raw, $links_result_numrows);
    $links_result = $dbconn->Execute($links_result_raw);
    while ($links = $links_result->fields) {
      if ((!isset($_GET['lID']) || (isset($_GET['lID']) && ($_GET['lID'] == $links['links_id']))) && !isset($lInfo)) { 
        $categories_result = $dbconn->Execute("SELECT lcd.link_categories_name AS links_category FROM " . $oostable['links_to_link_categories'] . " l2lc LEFT JOIN " . $oostable['link_categories_description'] . " lcd ON lcd.link_categories_id = l2lc.link_categories_id WHERE l2lc.links_id = '" . (int)$links['links_id'] . "' AND lcd.link_categories_languages_id = '" . intval($_SESSION['language_id']) . "'");
        $category = $categories_result->fields;

        $lInfo_array = array_merge($links, $category);
        $lInfo = new objectInfo($lInfo_array);
      }

      if (isset($lInfo) && is_object($lInfo) && ($links['links_id'] == $lInfo->links_id)) {
        echo '          <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['links'], 'page=' . $_GET['page'] . '&lID=' . $lInfo->links_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['links'], 'page=' . $_GET['page'] . '&lID=' . $links['links_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo $links['links_title']; ?></td>
                <td class="dataTableContent"><?php echo $links['links_url']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $links['links_clicked']; ?></td>
                <td class="dataTableContent"><?php echo $links_status_array[$links['links_status']]; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($lInfo) && is_object($lInfo) && ($links['links_id'] == $lInfo->links_id)) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . oos_href_link_admin($aFilename['links'], oos_get_all_get_params(array('lID')) . 'lID=' . $links['links_id']) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $links_result->MoveNext();
    }
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $links_split->display_count($links_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_LINKS); ?></td>
                    <td class="smallText" align="right"><?php echo $links_split->display_links($links_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], oos_get_all_get_params(array('page', 'info', 'x', 'y', 'lID'))); ?></td>
                  </tr>
                  <tr>
<?php
    if (isset($_GET['search']) && oos_is_not_null($_GET['search'])) {
?>
                    <td align="right"><?php echo '<a href="' . oos_href_link_admin($aFilename['links']) . '">' . oos_image_swap_button('reset','reset_off.gif', IMAGE_RESET) . '</a>'; ?></td>
                    <td align="right"><?php echo '<a href="' . oos_href_link_admin($aFilename['links'], 'page=' . $_GET['page'] . '&action=new') . '">' . oos_image_swap_button('new_link','new_link_off.gif', IMAGE_NEW_LINK) . '</a>'; ?></td>
<?php
    } else {
?>
                    <td align="right" colspan="2"><?php echo '<a href="' . oos_href_link_admin($aFilename['links'], 'page=' . $_GET['page'] . '&action=new') . '">' . oos_image_swap_button('new_link','new_link_off.gif', IMAGE_NEW_LINK) . '</a>'; ?></td>
<?php
    }
?>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'confirm':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_LINK . '</b>');

      $contents = array('form' => oos_draw_form('links', $aFilename['links'], oos_get_all_get_params(array('lID', 'action')) . 'lID=' . $lInfo->links_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO . '<br /><br /><b>' . $lInfo->links_url . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('delete','delete_off.gif', IMAGE_DELETE) . ' <a href="' . oos_href_link_admin($aFilename['links'], oos_get_all_get_params(array('lID', 'action')) . 'lID=' . $lInfo->links_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'check':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_CHECK_LINK . '</b>');
      $url = $lInfo->links_reciprocal_url;

      if (file($url)) {
        $file = fopen($url,'r');
        $link_check_status = false;

        while (!feof($file)) {
          $page_line = trim(fgets($file, 4096));

          if (eregi(LINKS_CHECK_PHRASE, $page_line)) {
            $link_check_status = true;
            break;
          }
        }

        fclose($file);

        if ($link_check_status == true) {
          $link_check_status_text = TEXT_INFO_LINK_CHECK_FOUND;
        } else {
          $link_check_status_text = TEXT_INFO_LINK_CHECK_NOT_FOUND;
        }
      } else {
        $link_check_status_text = TEXT_INFO_LINK_CHECK_ERROR;
      }

      $contents[] = array('text' => TEXT_INFO_LINK_CHECK_RESULT . ' ' . $link_check_status_text);
      $contents[] = array('text' => '<br /><b>' . $lInfo->links_reciprocal_url . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br /><a href="' . oos_href_link_admin($aFilename['links'], oos_get_all_get_params(array('lID', 'action')) . 'lID=' . $lInfo->links_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    default:
      if (isset($lInfo) && is_object($lInfo)) {
        $heading[] = array('text' => '<b>' . $lInfo->links_title . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['links'], oos_get_all_get_params(array('lID', 'action')) . 'lID=' . $lInfo->links_id . '&action=edit') . '">' . oos_image_swap_button('edit','edit_off.gif', IMAGE_EDIT) . '</a> <a href="' . oos_href_link_admin($aFilename['links'], oos_get_all_get_params(array('lID', 'action')) . 'lID=' . $lInfo->links_id . '&action=confirm') . '">' . oos_image_swap_button('delete','delete_off.gif', IMAGE_DELETE) . '</a> <a href="' . oos_href_link_admin($aFilename['links'], oos_get_all_get_params(array('lID', 'action')) . 'lID=' . $lInfo->links_id . '&action=check') . '">' . oos_image_swap_button('check_link','check_link_off.gif', IMAGE_CHECK_LINK) . '</a> <a href="' . oos_href_link_admin($aFilename['links_contact'], 'link_partner=' . $lInfo->links_contact_email) . '">' . oos_image_swap_button('email','email_off.gif', IMAGE_EMAIL) . '</a>');

        $contents[] = array('text' => '<br />' . TEXT_INFO_LINK_STATUS . ' '  . $links_status_array[$lInfo->links_status]);
        $contents[] = array('text' => '<br />' . TEXT_INFO_LINK_CATEGORY . ' '  . $lInfo->links_category);
        $contents[] = array('text' => '<br />' . oos_href_link_admin_info_image($lInfo->links_image_url, $lInfo->links_title, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br />' . $lInfo->links_title);
        $contents[] = array('text' => '<br />' . TEXT_INFO_LINK_CONTACT_NAME . ' '  . $lInfo->links_contact_name);
        $contents[] = array('text' => '<br />' . TEXT_INFO_LINK_CONTACT_EMAIL . ' ' . $lInfo->links_contact_email);
        $contents[] = array('text' => '<br />' . TEXT_INFO_LINK_CLICK_COUNT . ' ' . $lInfo->links_clicked);
        $contents[] = array('text' => '<br />' . TEXT_INFO_LINK_DESCRIPTION . ' ' . $lInfo->links_description);
        $contents[] = array('text' => '<br />' . TEXT_DATE_LINK_CREATED . ' ' . oos_date_short($lInfo->links_date_added));

        if (oos_is_not_null($lInfo->links_last_modified)) {
          $contents[] = array('text' => '<br />' . TEXT_DATE_LINK_LAST_MODIFIED . ' ' . oos_date_short($lInfo->links_last_modified));
        }
      }
      break;
  }

  if ( (oos_is_not_null($heading)) && (oos_is_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<?php require 'includes/oos_footer.php'; ?>
<br />
</body>
</html>
<?php require 'includes/oos_nice_exit.php'; ?>