<?php
/* ----------------------------------------------------------------------
   $Id: categories_discounts_price.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   Written by Linda McGrath osCOMMERCE@WebMakers.com
   http://www.thewebmakerscorner.com
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );


if ( !($pInfo->products_discount1_qty == 0 and $pInfo->products_discount2_qty == 0 and $pInfo->products_discount3_qty == 0 and $pInfo->products_discount4_qty == 0 )) {

  $the_special=oos_get_products_special_price($_GET['pID']);

  $q0=$pInfo->products_quantity_order_min;
  $q1=$pInfo->products_discount1_qty;
  $q2=$pInfo->products_discount2_qty;
  $q3=$pInfo->products_discount3_qty;
  $q4=$pInfo->products_discount4_qty;

  $col_cnt=1;
  if ( $pInfo->products_discount1 > 0 ) {
    $col_cnt= $col_cnt+1;
  }
  if ( $pInfo->products_discount2 > 0 ) {
    $col_cnt= $col_cnt+1;
  }
  if ( $pInfo->products_discount3 > 0 ) {
    $col_cnt= $col_cnt+1;
  }
  if ( $pInfo->products_discount4 > 0 ) {
    $col_cnt= $col_cnt+1;
  }
?>

  <tr>
    <td colspan="2" class="main" align="right">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="main" align="right">
      <table width="<?php echo 50*$col_cnt; ?>" border="1" cellpadding="2" cellspacing="2" align="right">
        <tr>
          <td>
            <table width="100%" border="0" cellpadding="2" cellspacing="2" align="center">
<?php
if ( $q1 < $q0 ) {
?>
              <tr>
                <td colspan="<?php echo $col_cnt; ?>" class="DiscountPriceTitle" align="center">WARNING: Quanties Minimum &gt;<br /> Price Break 1</td>
              </tr>
              <tr>
                <td colspan="<?php echo $col_cnt; ?>" class="DiscountPriceTitle" align="center">&nbsp;</td>
              </tr>

<?php
}
?>
              <tr>
                <td colspan="<?php echo $col_cnt; ?>" class="DiscountPriceTitle" align="center"><?php echo TEXT_DISCOUNTS_TITLE; ?></td>
              </tr>
              <tr>
<?php
  echo '      <td class="DiscountPriceQty" align="center">';
  echo (($q1-1) > $q0 ? $q0 . '-' . ($q1-1) : $q0);
  echo '      </td>';

  if ( $q1 > 0 ) {
    echo '<td class="DiscountPriceQty" align="center">';
    echo ($q2 > 0 ? (($q2-1) > $q1 ? $q1 . '-' . ($q2-1) : $q1) : $q1 . '+');
    echo '</td>';
  }

  if ( $q2 > 0 ) {
    echo '<td class="DiscountPriceQty" align="center">';
    echo ($q3 > 0 ? (($q3-1) > $q2 ? $q2 . '-' . ($q3-1) : $q2) : $q2 . '+');
    echo '</td>';
  }

  if ( $q3 > 0 ) {
    echo '<td class="DiscountPriceQty" align="center">';
    echo ($q4 > 0 ? (($q4-1) > $q3 ? $q3 . '-' . ($q4-1) : $q3) : $q3 . '+');
    echo '</td>';
  }

  if ( $q4 > 0 ) {
    echo '<td class="DiscountPriceQty" align="center">';
    echo ($q4 > 0 ? $q4 . '+' : '');
    echo '</td>';
  }
?>
              </tr>

              <tr>
<?php
  echo '<td class="DiscountPrice" align="center">';
  echo ( ($the_special==0) ? $currencies->format($pInfo->products_price) : $currencies->format($the_special) );
  echo '</td>';
 
  if ( $q1 > 0 ) {
    $oosDiscount1=$pInfo->products_discount1; 
    if (OOS_PRICE_IS_BRUTTO == 'true') {
      $oosDiscount1 = ($oosDiscount1*($tax[tax_rate]+100)/100);	
    }
    $oosDiscount1 = round($oosDiscount1,TAX_DECIMAL_PLACES);
    echo '<td class="DiscountPrice" align="center">';
    echo $currencies->format($oosDiscount1);
    echo '</td>';
  }

  if ( $q2 > 0 ) {
    $oosDiscount2=$pInfo->products_discount2; 
    if (OOS_PRICE_IS_BRUTTO == 'true') {
      $oosDiscount2 = ($oosDiscount2*($tax[tax_rate]+100)/100);	
    }
    $oosDiscount2 = round($oosDiscount2,TAX_DECIMAL_PLACES);
    echo '<td class="DiscountPrice" align="center">';
    echo $currencies->format($oosDiscount2);
    echo '</td>';
  }

  if ( $q3 > 0 ) {
    $oosDiscount3=$pInfo->products_discount3; 
    if (OOS_PRICE_IS_BRUTTO == 'true') {
      $oosDiscount3 = ($oosDiscount3*($tax[tax_rate]+100)/100);	
    }
    $oosDiscount3 = round($oosDiscount3,TAX_DECIMAL_PLACES);
    echo '<td class="DiscountPrice" align="center">';
    echo $currencies->format($oosDiscount3);
    echo '</td>';
  }

  if ( $q4 > 0 ) {
    $oosDiscount4=$pInfo->products_discount4; 
    if (OOS_PRICE_IS_BRUTTO == 'true') {
      $oosDiscount4 = ($oosDiscount4*($tax[tax_rate]+100)/100);	
    }
    $oosDiscount4 = round($oosDiscount4,TAX_DECIMAL_PLACES);
    echo '<td class="DiscountPrice" align="center">';
    echo $currencies->format($oosDiscount4);
    echo '</td>';
  }
?>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
<?php
}
?>