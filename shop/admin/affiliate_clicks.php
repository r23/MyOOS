<?php
/* ----------------------------------------------------------------------
   $Id: affiliate_clicks.php,v 1.1 2007/06/08 17:14:40 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_clicks.php,v 1.5 2003/02/12 21:15:13 harley_vb
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

  if ($_GET['acID'] > 0) {
    $sql = "SELECT ac.*, pd.products_name, a.affiliate_firstname, a.affiliate_lastname FROM " . $oostable['affiliate_clickthroughs'] . " ac LEFT JOIN " . $oostable['products'] . " p on (p.products_id = ac.affiliate_products_id) LEFT JOIN " . $oostable['products_description'] . " pd on (pd.products_id = p.products_id AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "') LEFT JOIN " . $oostable['affiliate_affiliate'] . " a  on (a.affiliate_id = ac.affiliate_id) WHERE a.affiliate_id = '" . $_GET['acID'] . "' ORDER BY ac.affiliate_clientdate desc";
  } else {
    $sql = "SELECT ac.*, pd.products_name, a.affiliate_firstname, a.affiliate_lastname FROM " . $oostable['affiliate_clickthroughs'] . " ac LEFT JOIN " . $oostable['products'] . " p on (p.products_id = ac.affiliate_products_id) LEFT JOIN " . $oostable['products_description'] . " pd on (pd.products_id = p.products_id AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "') LEFT JOIN " . $oostable['affiliate_affiliate'] . " a  on (a.affiliate_id = ac.affiliate_id) ORDER BY ac.affiliate_clientdate desc";
  }
  $affiliate_clickthroughs_raw = $sql;
  $affiliate_clickthroughs_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $affiliate_clickthroughs_raw, $affiliate_clickthroughs_numrows);

  $no_js_general = true;
  require 'includes/oos_header.php'; 
?>
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<?php require 'includes/oos_blocks.php'; ?>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
<?php 
  if ($_GET['acID'] > 0) {
?>
            <td class="pageHeading" align="right"><?php echo '<a href="' . oos_href_link_admin($aFilename['affiliate_statistics'], oos_get_all_get_params(array('action'))) . '">' . oos_image_swap_button('back','back_off.gif', IMAGE_BACK) . '</a>'; ?></td>
<?php
  } else {
?>
            <td class="pageHeading" align="right"><?php echo '<a href="' . oos_href_link_admin($aFilename['affiliate_summary'], '') . '">' . oos_image_swap_button('back','back_off.gif', IMAGE_BACK) . '</a>'; ?></td>
<?php
  }
?>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_AFFILIATE_USERNAME .'/<br />' . TABLE_HEADING_IPADDRESS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ENTRY_DATE .'/<br />' . TABLE_HEADING_REFERRAL_URL; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CLICKED_PRODUCT; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_BROWSER; ?></td>
              </tr>
<?php
  if ($affiliate_clickthroughs_numrows > 0) {
    $affiliate_clickthroughs_values = $dbconn->Execute($affiliate_clickthroughs_raw);
    $number_of_clickthroughs = '0';
    while ($affiliate_clickthroughs = $affiliate_clickthroughs_values->fields) {
      $number_of_clickthroughs++;

      if ( ($number_of_clickthroughs / 2) == floor($number_of_clickthroughs / 2) ) {
        echo '                  <tr class="productListing-even">';
      } else {
        echo '                  <tr class="productListing-odd">';
      }
?>
                <td class="dataTableContent"><?php echo $affiliate_clickthroughs['affiliate_firstname'] . " " . $affiliate_clickthroughs['affiliate_lastname']; ?></td>
                <td class="dataTableContent" align="center"><?php echo oos_date_short($affiliate_clickthroughs['affiliate_clientdate']); ?></td>
<?php
      if ($affiliate_clickthroughs['affiliate_products_id'] > 0) $link_to = '<a href="' . oos_catalog_link($oosModules['products'], $oosCatalogFilename['product_info'], 'products_id=' . $affiliate_clickthroughs['affiliate_products_id']) . '" target="_blank">' . $affiliate_clickthroughs['products_name'] . '</a>';
      else $link_to = "Startpage";
?>
                <td class="dataTableContent"><?php echo $link_to; ?></td>
                <td class="dataTableContent" align="center"><?php echo $affiliate_clickthroughs['affiliate_clientbrowser']; ?></td>
              </tr>
              <tr>
                <td class="dataTableContent"><?php echo $affiliate_clickthroughs['affiliate_clientip']; ?></td>
                <td class="dataTableContent" colspan="3"><?php  echo $affiliate_clickthroughs['affiliate_clientreferer']; ?></td>
              </tr>
              <tr>
                <td class="dataTableContent" colspan="4"><?php echo oos_draw_separator('pixel_black.gif', '100%', '1'); ?></td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $affiliate_clickthroughs_values->MoveNext();
    }

    // Close result set
    $affiliate_clickthroughs_values->Close();

  } else {
?>
              <tr class="productListing-odd">
                <td colspan="7" class="smallText"><?php echo TEXT_NO_CLICKS; ?></td>
              </tr>
<?php
  }
?>
              <tr>
                <td class="smallText" colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $affiliate_clickthroughs_split->display_count($affiliate_clickthroughs_numrows,  MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CLICKS); ?></td>
                    <td class="smallText" align="right"><?php echo $affiliate_clickthroughs_split->display_links($affiliate_clickthroughs_numrows,  MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], oos_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<?php require 'includes/oos_footer.php'; ?>
<br />
</body>
</html>
<?php require 'includes/oos_nice_exit.php';?>