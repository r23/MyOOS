<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: validproducts.php,v 0.01 2002/08/17 15:38:34 Richard Fielder
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

?><!DOCTYPE html>
<html lang="<?php echo $_SESSION['iso_639_1']; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.1, maximum-scale=5.0, user-scalable=yes">
<title>Valid Categories/Products List - Administration [MyOOS]</title>
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
<td colspan="3">
<h4><?php echo TEXT_VALID_PRODUCTS_LIST; ?></h4>
</td>
</tr>
<?php
    echo "<tr><th>". TEXT_VALID_PRODUCTS_ID . "</th><th>" . TEXT_VALID_PRODUCTS_NAME . "</th><th>" . TEXT_VALID_PRODUCTS_MODEL . "</th></tr>";
    $productstable = $oostable['products'];
    $products_descriptiontable = $oostable['products_description'];
    $sql = "SELECT p.products_id, p.products_model, p.products_status, pd.products_name
            FROM $productstable p,
                 $products_descriptiontable pd
            WHERE p.products_status >= '1'
              AND pd.products_id = p.products_id
              AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "'
            ORDER BY pd.products_name";
    $result = $dbconn->Execute($sql);
while ($row = $result->fields) {
    echo '<tr>' . "\n";
    echo '<td>' . $row['products_id'] . '</td>' . "\n";
    echo '<td>' . $row['products_name'] . '</td>' . "\n";
    echo '<td>' . $row['products_model'] . '</td>' . "\n";
    echo '</tr>' . "\n";

    // Move that ADOdb pointer!
    $result->MoveNext();
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