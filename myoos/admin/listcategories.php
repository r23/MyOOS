<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: validcategories.php,v 0.01 2002/08/17 15:38:34 Richard Fielder
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   Copyright (c) 2002 Richard Fielder
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/main.php';

?>
<html>
<head>
<title>Valid Categories/Products List - Administration [OOS]</title>
<style type="text/css">
<!--
h4 {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: x-small; text-align: center}
p {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: xx-small}
th {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: xx-small}
td {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: xx-small}
-->
</style>
<head>
<body>
<table width="550" border="1" cellspacing="1" bordercolor="gray">
<tr>
<td colspan="4">
<h4>Valid Categories List</h4>
</td>
</tr>
<?php
   $coupon_get = $dbconn->Execute("SELECT restrict_to_categories FROM " . $oostable['coupons'] . " WHERE coupon_id='".$_GET['cid']."'");
   $get_result = $coupon_get->fields;
   echo "<tr><th>Category ID</th><th>Category Name</th></tr><tr>";
   $cat_ids = preg_split("/[,]/", $get_result['restrict_to_categories']);
for ($i = 0; $i < count($cat_ids); $i++) {
    $sql = "SELECT 
                 c.categories_id, c.categories_status, cd.categories_name
             FROM
                 " . $oostable['categories'] . " c, 
                 " . $oostable['categories_description'] . " cd 
             WHERE
                 c.categories_status = '1' AND
                 c.categories_id = cd.categories_id AND
                 cd.categories_languages_id = '" . intval($_SESSION['language_id']) . "' 
                 c.categories_id = '" . $cat_ids[$i] . "'";
    $result = $dbconn->Execute($sql);
    if ($row = $result->fields) {
        echo "<td>".$row["categories_id"]."</td>\n";
        echo "<td>".$row["categories_name"]."</td>\n";
        echo "</tr>\n";
    }
}
    echo "</table>\n";
?>
<br>
<table width="550" border="0" cellspacing="1">
<tr>
<td align=middle><input type="button" value="Close Window" onClick="window.close()"></td>
</tr></table>

<?php require 'includes/bottom.php'; ?>
<?php require 'includes/nice_exit.php'; ?>