<?php
/* ----------------------------------------------------------------------
   $Id: affiliate_affiliates.php,v 1.1 2007/06/08 17:14:40 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_affiliates.php,v 1.10 2003/02/13 17:44:59 harley_vb 
   ----------------------------------------------------------------------
   OSC-Affiliate

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';

  require 'includes/classes/class_currencies.php';
  $currencies = new currencies();

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'update':
        $affiliate_id = oos_db_prepare_input($_GET['acID']);
        if ($affiliate_zone_id > 0) $affiliate_state = '';
        // If someone uses , instead of .
        $affiliate_commission_percent = str_replace (',' , '.' , $affiliate_commission_percent);

        $sql_data_array = array('affiliate_firstname' => $affiliate_firstname,
                                'affiliate_lastname' => $affiliate_lastname,
                                'affiliate_email_address' => $affiliate_email_address,
                                'affiliate_payment_check' => $affiliate_payment_check,
                                'affiliate_payment_paypal' => $affiliate_payment_paypal,
                                'affiliate_payment_bank_name' => $affiliate_payment_bank_name,
                                'affiliate_payment_bank_branch_number' => $affiliate_payment_bank_branch_number,
                                'affiliate_payment_bank_swift_code' => $affiliate_payment_bank_swift_code,
                                'affiliate_payment_bank_account_name' => $affiliate_payment_bank_account_name,
                                'affiliate_payment_bank_account_number' => $affiliate_payment_bank_account_number,
                                'affiliate_street_address' => $affiliate_street_address,
                                'affiliate_postcode' => $affiliate_postcode,
                                'affiliate_city' => $affiliate_city,
                                'affiliate_country_id' => $affiliate_country_id,
                                'affiliate_telephone' => $affiliate_telephone,
                                'affiliate_fax' => $affiliate_fax,
                                'affiliate_homepage' => $affiliate_homepage,
                                'affiliate_commission_percent' => $affiliate_commission_percent,
                                'affiliate_agb' => '1');

        if (ACCOUNT_DOB == 'true') $sql_data_array['affiliate_dob'] = oos_date_raw($affiliate_dob);
        if (ACCOUNT_GENDER == 'true') $sql_data_array['affiliate_gender'] = $affiliate_gender;
        if (ACCOUNT_COMPANY == 'true') {
          $sql_data_array['affiliate_company'] = $affiliate_company;
          $sql_data_array['affiliate_company_taxid'] =  $affiliate_company_taxid;
        }
        if (ACCOUNT_SUBURB == 'true') $sql_data_array['affiliate_suburb'] = $affiliate_suburb;
        if (ACCOUNT_STATE == 'true') {
          $sql_data_array['affiliate_state'] = $affiliate_state;
          $sql_data_array['affiliate_zone_id'] = $affiliate_zone_id;
        }

        $sql_data_array['affiliate_date_account_last_modified'] = 'now()';

        oos_db_perform($oostable['affiliate_affiliate'], $sql_data_array, 'update', "affiliate_id = '" . oos_db_input($affiliate_id) . "'");

        oos_redirect_admin(oos_href_link_admin($aFilename['affiliate'], oos_get_all_get_params(array('acID', 'action')) . 'acID=' . $affiliate_id));
        break;

      case 'deleteconfirm':
        $affiliate_id = oos_db_prepare_input($_GET['acID']);

        $dbconn->Execute("DELETE FROM " . $oostable['affiliate_affiliate'] . " WHERE affiliate_id = '" . oos_db_input($affiliate_id) . "'");

        oos_redirect_admin(oos_href_link_admin($aFilename['affiliate'], oos_get_all_get_params(array('acID', 'action')))); 
        break;
    }
  }
  require 'includes/oos_header.php';

  if ($action == 'edit') {
?>
<script language="javascript"><!--
function resetStateText(theForm) {
  theForm.affiliate_state.value = '';
  if (theForm.affiliate_zone_id.options.length > 1) {
    theForm.affiliate_state.value = '<?php echo JS_STATE_SELECT; ?>';
  }
}

function resetZoneSelected(theForm) {
  if (theForm.affiliate_state.value != '') {
    theForm.affiliate_zone_id.selectedIndex = '0';
    if (theForm.affiliate_zone_id.options.length > 1) {
      theForm.affiliate_state.value = '<?php echo JS_STATE_SELECT; ?>';
    }
  }
}

function update_zone(theForm) {
  var NumState = theForm.affiliate_zone_id.options.length;
  var SelectedCountry = '';

  while(NumState > 0) {
    NumState--;
    theForm.affiliate_zone_id.options[NumState] = null;
  }

  SelectedCountry = theForm.affiliate_country_id.options[theForm.affiliate_country_id.selectedIndex].value;

<?php echo oos_is_zone_list('SelectedCountry', 'theForm', 'affiliate_zone_id'); ?>

  resetStateText(theForm);
}

function check_form() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";

  var affiliate_firstname = document.affiliate.affiliate_firstname.value;
  var affiliate_lastname = document.affiliate.affiliate_lastname.value;
<?php if (ACCOUNT_COMPANY == 'true') echo 'var affiliate_company = document.affiliate.affiliate_company.value;' . "\n"; ?>
  var affiliate_email_address = document.affiliate.affiliate_email_address.value;  
  var affiliate_street_address = document.affiliate.affiliate_street_address.value;
  var affiliate_postcode = document.affiliate.affiliate_postcode.value;
  var affiliate_city = document.affiliate.affiliate_city.value;
  var affiliate_telephone = document.affiliate.affiliate_telephone.value;

<?php if (ACCOUNT_GENDER == 'true') { ?>
  if (document.affiliate.affiliate_gender[0].checked || document.affiliate.affiliate_gender[1].checked) {
  } else {
    error_message = error_message + "<?php echo JS_GENDER; ?>";
    error = 1;
  }
<?php } ?>

  if (affiliate_firstname = "" || affiliate_firstname.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_FIRST_NAME; ?>";
    error = 1;
  }

  if (affiliate_lastname = "" || affiliate_lastname.length < <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_LAST_NAME; ?>";
    error = 1;
  }

  if (affiliate_email_address = "" || affiliate_email_address.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_EMAIL_ADDRESS; ?>";
    error = 1;
  }

  if (affiliate_street_address = "" || affiliate_street_address.length < <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_ADDRESS; ?>";
    error = 1;
  }

  if (affiliate_postcode = "" || affiliate_postcode.length < <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_POST_CODE; ?>";
    error = 1;
  }

  if (affiliate_city = "" || affiliate_city.length < <?php echo ENTRY_CITY_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_CITY; ?>";
    error = 1;
  }

<?php if (ACCOUNT_STATE == 'true') { ?>
  if (document.affiliate.affiliate_zone_id.options.length <= 1) {
    if (document.affiliate.affiliate_state.value == "" || document.affiliate.affiliate_state.length < 4 ) {
       error_message = error_message + "<?php echo JS_STATE; ?>";
       error = 1;
    }
  } else {
    document.affiliate.affiliate_state.value = '';
    if (document.affiliate.affiliate_zone_id.selectedIndex == 0) {
       error_message = error_message + "<?php echo JS_ZONE; ?>";
       error = 1;
    }
  }
<?php } ?>

  if (document.affiliate.affiliate_country_id.value == 0) {
    error_message = error_message + "<?php echo JS_COUNTRY; ?>";
    error = 1;
  }

  if (affiliate_telephone = "" || affiliate_telephone.length < <?php echo ENTRY_TELEPHONE_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_TELEPHONE; ?>";
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
  if ($action == 'edit') {
    $query = "SELECT * FROM " . $oostable['affiliate_affiliate'] . " WHERE affiliate_id = '" . $_GET['acID'] . "'";
    $affiliate = $dbconn->GetRow($query);
    $aInfo = new objectInfo($affiliate);
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
      <tr><?php echo oos_draw_form('affiliate', $aFilename['affiliate'], oos_get_all_get_params(array('action')) . 'action=update', 'post', 'onSubmit="return check_form();"'); ?>
        <td class="formAreaTitle"><?php echo CATEGORY_PERSONAL; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
<?php
    if (ACCOUNT_GENDER == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_GENDER; ?></td>
            <td class="main"><?php echo oos_draw_radio_field('affiliate_gender', 'm', false, $aInfo->affiliate_gender) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . oos_draw_radio_field('affiliate_gender', 'f', false, $aInfo->affiliate_gender) . '&nbsp;&nbsp;' . FEMALE; ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_firstname', $aInfo->affiliate_firstname, 'maxlength="32"', true); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_lastname', $aInfo->affiliate_lastname, 'maxlength="32"', true); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_email_address', $aInfo->affiliate_email_address, 'maxlength="96"', true); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
<?php
   if (AFFILATE_INDIVIDUAL_PERCENTAGE == 'true') {
?>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_COMMISSION; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_AFFILIATE_COMMISSION; ?></td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_commission_percent', $aInfo->affiliate_commission_percent, 'maxlength="5"'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
<?php
    }
?>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_COMPANY; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_COMPANY; ?></td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_company', $aInfo->affiliate_company, 'maxlength="32"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_AFFILIATE_COMPANY_TAXID; ?></td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_company_taxid', $aInfo->affiliate_company_taxid, 'maxlength="64"'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_PAYMENT_DETAILS; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
<?php
  if (AFFILIATE_USE_CHECK == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_AFFILIATE_PAYMENT_CHECK; ?></td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_payment_check', $aInfo->affiliate_payment_check, 'maxlength="100"'); ?></td>
          </tr>
<?php
  }
  if (AFFILIATE_USE_PAYPAL == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_AFFILIATE_PAYMENT_PAYPAL; ?></td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_payment_paypal', $aInfo->affiliate_payment_paypal, 'maxlength="64"'); ?></td>
          </tr>
<?php
  }
  if (AFFILIATE_USE_BANK == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_AFFILIATE_PAYMENT_BANK_NAME; ?></td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_payment_bank_name', $aInfo->affiliate_payment_bank_name, 'maxlength="64"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_AFFILIATE_PAYMENT_BANK_BRANCH_NUMBER; ?></td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_payment_bank_branch_number', $aInfo->affiliate_payment_bank_branch_number, 'maxlength="64"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_AFFILIATE_PAYMENT_BANK_SWIFT_CODE; ?></td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_payment_bank_swift_code', $aInfo->affiliate_payment_bank_swift_code, 'maxlength="64"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NAME; ?></td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_payment_bank_account_name', $aInfo->affiliate_payment_bank_account_name, 'maxlength="64"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NUMBER; ?></td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_payment_bank_account_number', $aInfo->affiliate_payment_bank_account_number, 'maxlength="64"'); ?></td>
          </tr>
<?php
  }
?>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_ADDRESS; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_STREET_ADDRESS; ?></td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_street_address', $aInfo->affiliate_street_address, 'maxlength="64"', true); ?></td>
          </tr>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_SUBURB; ?></td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_suburb', $aInfo->affiliate_suburb, 'maxlength="64"', false); ?></td>
          </tr>
<?php
  }
?>
          <tr>
            <td class="main"><?php echo ENTRY_CITY; ?></td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_city', $aInfo->affiliate_city, 'maxlength="32"', true); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_POST_CODE; ?></td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_postcode', $aInfo->affiliate_postcode, 'maxlength="8"', true); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
            <td class="main"><?php echo oos_draw_pull_down_menu('affiliate_country_id', oos_get_countries(), $aInfo->affiliate_country_id, 'onChange="update_zone(this.form);"'); ?></td>
          </tr>
<?php
    if (ACCOUNT_STATE == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_STATE; ?></td>
            <td class="main"><?php echo oos_draw_pull_down_menu('affiliate_zone_id', oos_prepare_country_zones_pull_down($aInfo->affiliate_country_id), $aInfo->affiliate_zone_id, 'onChange="resetStateText(this.form);"'); ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;</td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_state', $aInfo->affiliate_state, 'maxlength="32" onChange="resetZoneSelected(this.form);"'); ?></td>
          </tr>
<?php
    }
?>
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
            <td class="main"><?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_telephone', $aInfo->affiliate_telephone, 'maxlength="32"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_FAX_NUMBER; ?></td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_fax', $aInfo->affiliate_fax, 'maxlength="32"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_AFFILIATE_HOMEPAGE; ?></td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_homepage', $aInfo->affiliate_homepage, 'maxlength="64"', true); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
       <tr>
        <td align="right" class="main"><?php echo oos_image_swap_submits('update','update_off.gif', IMAGE_UPDATE) . ' <a href="' . oos_href_link_admin($aFilename['affiliate'], oos_get_all_get_params(array('action'))) .'">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>';?></td>
      </tr></form>
<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><?php echo oos_draw_form('search', $aFilename['affiliate'], '', 'get'); ?>
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
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_AFFILIATE_ID; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LASTNAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FIRSTNAME; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_COMMISSION; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_USERHOMEPAGE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $search = '';
    if (isset($_GET['search']) && oos_is_not_null($_GET['search'])) {
      $keywords = oos_db_input(oos_db_prepare_input($_GET['search']));
      $search = " WHERE affiliate_id like '" . $keywords . "' or affiliate_firstname like '" . $keywords . "' or affiliate_lastname like '" . $keywords . "' or affiliate_email_address like '" . $keywords . "'";
    }
    $affiliate_result_raw = "SELECT * FROM " . $oostable['affiliate_affiliate'] . $search . " ORDER BY affiliate_lastname";
    $affiliate_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, 
    $affiliate_result_raw, $affiliate_result_numrows);
    $affiliate_result = $dbconn->Execute($affiliate_result_raw);
    while ($affiliate = $affiliate_result->fields) {
      $info_query = "SELECT affiliate_commission_percent, affiliate_date_account_created as date_account_created, affiliate_date_account_last_modified as date_account_last_modified, affiliate_date_of_last_logon as date_last_logon, affiliate_number_of_logons as number_of_logons FROM " . $oostable['affiliate_affiliate'] . " WHERE affiliate_id = '" . $affiliate['affiliate_id'] . "'";
      $info = $dbconn->GetRow($info_query);

      if ((!isset($_GET['acID']) || (isset($_GET['acID']) && ($_GET['acID'] == $affiliate['affiliate_id']))) && !isset($aInfo)) {
        $country_query = "SELECT countries_name FROM " . $oostable['countries'] . " WHERE countries_id = '" . $affiliate['affiliate_country_id'] . "'";
        $country = $dbconn->GetRow($country_query);

        $affiliate_info = array_merge($country, $info);

        $aInfo_array = array_merge($affiliate, $affiliate_info);
        $aInfo = new objectInfo($aInfo_array);
      }

      if (isset($aInfo) && is_object($aInfo) && ($affiliate['affiliate_id'] == $aInfo->affiliate_id) ) {
        echo '          <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['affiliate'], oos_get_all_get_params(array('acID', 'action')) . 'acID=' . $aInfo->affiliate_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['affiliate'], oos_get_all_get_params(array('acID')) . 'acID=' . $affiliate['affiliate_id']) . '\'">' . "\n";
      }
      if (substr($affiliate['affiliate_homepage'],0,7) != "http://") $affiliate['affiliate_homepage']="http://".$affiliate['affiliate_homepage'];
?>
                <td class="dataTableContent"><?php echo $affiliate['affiliate_id']; ?></td>        
                <td class="dataTableContent"><?php echo $affiliate['affiliate_lastname']; ?></td>
                <td class="dataTableContent"><?php echo $affiliate['affiliate_firstname']; ?></td>
                <td class="dataTableContent" align="right"><?php if($affiliate['affiliate_commission_percent'] > AFFILIATE_PERCENT) echo $affiliate['affiliate_commission_percent']; else echo  AFFILIATE_PERCENT; ?> %</td>
                <td class="dataTableContent"><?php echo '<a href="' . oos_href_link_admin($aFilename['affiliate'], oos_get_all_get_params(array('acID', 'action')) . 'acID=' . $affiliate['affiliate_id'] . '&action=edit') . '">' . oos_image(OOS_IMAGES . 'icons/preview.gif', ICON_PREVIEW) . '</a>'; echo '<a href="' . $affiliate['affiliate_homepage'] . '" target="_blank">' . $affiliate['affiliate_homepage'] . '</a>'; ?></td>
                <td class="dataTableContent" align="right"><?php echo '<a href="' . oos_href_link_admin($aFilename['affiliate_statistics'], oos_get_all_get_params(array('acID')) . 'acID=' . $affiliate['affiliate_id']) . '">' . oos_image(OOS_IMAGES . 'icons/statistics.gif', ICON_STATISTICS) . '</a>&nbsp;'; if (isset($aInfo) && is_object($aInfo) && ($affiliate['affiliate_id'] == $aInfo->affiliate_id) ) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . oos_href_link_admin($aFilename['affiliate'], oos_get_all_get_params(array('acID')) . 'acID=' . $affiliate['affiliate_id']) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $affiliate_result->MoveNext();
    }

    // Close result set
    $affiliate_result->Close();
?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $affiliate_split->display_count($affiliate_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_AFFILIATES); ?></td>
                    <td class="smallText" align="right"><?php echo $affiliate_split->display_links($affiliate_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], oos_get_all_get_params(array('page', 'info', 'x', 'y', 'acID'))); ?></td>
                  </tr>
<?php
    if (oos_is_not_null($_GET['search'])) {
?>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . oos_href_link_admin($aFilename['affiliate']) . '">' . oos_image_swap_button('reset','reset_off.gif', IMAGE_RESET) . '</a>'; ?></td>
                  </tr>
<?php
    }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'confirm':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CUSTOMER . '</b>');

      $contents = array('form' => oos_draw_form('affiliate', $aFilename['affiliate'], oos_get_all_get_params(array('acID', 'action')) . 'acID=' . $aInfo->affiliate_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO . '<br /><br /><b>' . $aInfo->affiliate_firstname . ' ' . $aInfo->affiliate_lastname . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('delete','delete_off.gif', IMAGE_DELETE) . ' <a href="' . oos_href_link_admin($aFilename['affiliate'], oos_get_all_get_params(array('acID', 'action')) . 'acID=' . $aInfo->affiliate_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    default:
      if (isset($aInfo) && is_object($aInfo)) {
        $heading[] = array('text' => '<b>' . $aInfo->affiliate_firstname . ' ' . $aInfo->affiliate_lastname . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['affiliate'], oos_get_all_get_params(array('acID', 'action')) . 'acID=' . $aInfo->affiliate_id . '&action=edit') . '">' . oos_image_swap_button('edit','edit_off.gif', IMAGE_EDIT) . '</a> <a href="' . oos_href_link_admin($aFilename['affiliate'], oos_get_all_get_params(array('acID', 'action')) . 'acID=' . $aInfo->affiliate_id . '&action=confirm') . '">' . oos_image_swap_button('delete','delete_off.gif', IMAGE_DELETE) . '</a> <a href="' . oos_href_link_admin($aFilename['affiliate_contact'], 'selected_box=affiliate&affiliate=' . $aInfo->affiliate_email_address) . '">' . oos_image_swap_button('email','email_off.gif', IMAGE_EMAIL) . '</a>');

        $affiliate_sales_raw = "SELECT COUNT(*) AS count, sum(affiliate_value) as total, sum(affiliate_payment) as payment FROM " . $oostable['affiliate_sales'] . " a left join " . $oostable['orders'] . " o on (a.affiliate_orders_id=o.orders_id) WHERE o.orders_status >= " . AFFILIATE_PAYMENT_ORDER_MIN_STATUS . " and  affiliate_id = '" . $aInfo->affiliate_id . "'";
        $affiliate_sales_values = $dbconn->Execute($affiliate_sales_raw);
        $affiliate_sales = $affiliate_sales_values->fields;

        $contents[] = array('text' => '<br />' . TEXT_DATE_ACCOUNT_CREATED . ' ' . oos_date_short($aInfo->date_account_created));
        $contents[] = array('text' => '' . TEXT_DATE_ACCOUNT_LAST_MODIFIED . ' ' . oos_date_short($aInfo->date_account_last_modified));
        $contents[] = array('text' => '' . TEXT_INFO_DATE_LAST_LOGON . ' '  . oos_date_short($aInfo->date_last_logon));
        $contents[] = array('text' => '' . TEXT_INFO_NUMBER_OF_LOGONS . ' ' . $aInfo->number_of_logons);
        $contents[] = array('text' => '' . TEXT_INFO_COMMISSION . ' ' . $aInfo->affiliate_commission_percent . ' %');
        $contents[] = array('text' => '' . TEXT_INFO_COUNTRY . ' ' . $aInfo->countries_name);
        $contents[] = array('text' => '' . TEXT_INFO_NUMBER_OF_SALES . ' ' . $affiliate_sales['count'],'');
        $contents[] = array('text' => '' . TEXT_INFO_SALES_TOTAL . ' ' . $currencies->display_price($affiliate_sales['total'],''));
        $contents[] = array('text' => '' . TEXT_INFO_AFFILIATE_TOTAL . ' ' . $currencies->display_price($affiliate_sales['payment'],''));
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