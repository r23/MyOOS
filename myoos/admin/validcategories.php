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
<td colspan="4">
<h4><?php echo TEXT_VALID_CATEGORIES_LIST; ?></h4>
</td>
</tr>
<?php
    echo "<tr><th>" . TEXT_VALID_CATEGORIES_ID . "</th><th>" . TEXT_VALID_CATEGORIES_NAME . "</th></tr>";
$categoriestable = $oostable['categories'];
$categories_descriptiontable = $oostable['categories_description'];
$sql = "SELECT c.categories_id, c.categories_status, cd.categories_name
            FROM $categoriestable c,
                 $categories_descriptiontable cd
            WHERE c.categories_status = 2
              AND c.categories_id = cd.categories_id
              AND cd.categories_languages_id = '" . intval($_SESSION['language_id']) . "'
            ORDER BY categories_id";
$result = $dbconn->Execute($sql);
while ($row = $result->fields) {
    echo '<tr>' . "\n";
    echo '<td>' . $row['categories_id']. '</td>' . "\n";
    echo '<td>' . $row['categories_name']. '</td>' . "\n";
    echo '</tr>' . "\n";

    // Move that ADOdb pointer!
    $result->MoveNext();
}
?>
</table>
<br>
<table width="550" border="0" cellspacing="1">
<tr>
<td align=middle><input type="button" value="Close Window" onClick="window.close()"></td>
</tr></table>

<?php require 'includes/bottom.php'; ?>
<?php require 'includes/nice_exit.php'; ?>