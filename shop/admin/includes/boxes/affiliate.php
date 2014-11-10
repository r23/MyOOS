<?php
/* ----------------------------------------------------------------------
   $Id: affiliate.php,v 1.1 2007/06/08 14:03:09 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate.php,v 1.2 2003/02/12 00:15:01 harley_vb  
   ----------------------------------------------------------------------
   OSC-Affiliate

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

?>
<!-- affiliates //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_AFFILIATE,
                     'link'  => oos_href_link_admin(basename($_SERVER['PHP_SELF']), oos_get_all_get_params(array('selected_box')) . 'selected_box=affiliate'));

  if ($_SESSION['selected_box'] == 'affiliate' || $menu_dhtml == true) {
    $contents[] = array('text'  => oos_admin_files_boxes('affiliate_summary', BOX_AFFILIATE_SUMMARY) .
                                   oos_admin_files_boxes('affiliate', BOX_AFFILIATE) .
                                   oos_admin_files_boxes('affiliate_payment', BOX_AFFILIATE_PAYMENT) .
                                   oos_admin_files_boxes('affiliate_sales', BOX_AFFILIATE_SALES) .
                                   oos_admin_files_boxes('affiliate_clicks', BOX_AFFILIATE_CLICKS) .
                                   oos_admin_files_boxes('affiliate_banners_manager', BOX_AFFILIATE_BANNERS) .
                                   oos_admin_files_boxes('affiliate_contact', BOX_AFFILIATE_CONTACT));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- affiliates_eof //-->
