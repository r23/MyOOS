<?php
/* ----------------------------------------------------------------------
   $Id: index.php,v 1.1 2007/06/08 17:14:41 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: index.php,v 1.17 2003/02/14 12:57:29 dgw_
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';

  $cat = array(array('title' => BOX_HEADING_MY_ACCOUNT,
                     'access' => true,
                     'image' => 'my_account.gif',
                     'href' => oos_href_link_admin($aFilename['admin_account']),
                     'children' => array(array('title' => BOX_MY_ACCOUNT, 'link' => oos_href_link_admin($aFilename['admin_account']),
                                               'access' => true),
                                         array('title' => BOX_MY_ACCOUNT_LOGOFF, 'link' => oos_href_link_admin($aFilename['logoff']),
                                               'access' => true))),
               array('title' => BOX_HEADING_ADMINISTRATOR,
                     'access' => oos_admin_check_boxes('administrator.php'),
                     'image' => 'administrator.gif',
                     'href' => oos_href_link_admin(oos_selected_file('administrator.php'), 'selected_box=administrator'),
                     'children' => array(array('title' => BOX_ADMINISTRATOR_MEMBER, 'link' => oos_href_link_admin($aFilename['admin_members'], 'selected_box=administrator'),
                                               'access' => oos_admin_check_boxes('admin_members', 'sub_boxes')),
                                         array('title' => BOX_ADMINISTRATOR_BOXES, 'link' => oos_href_link_admin($aFilename['admin_files'], 'selected_box=administrator'),
                                               'access' => oos_admin_check_boxes('admin_files', 'sub_boxes')))),
               array('title' => BOX_HEADING_CONFIGURATION,
                     'access' => oos_admin_check_boxes('configuration.php'),
                     'image' => 'configuration.gif',
                     'href' => oos_href_link_admin($aFilename['configuration'], 'selected_box=configuration&gID=1'),
                     'children' => array(array('title' => BOX_CONFIGURATION_MYSTORE, 'link' => oos_href_link_admin($aFilename['configuration'], 'selected_box=configuration&gID=1'),
                                               'access' => oos_admin_check_boxes('configuration', 'sub_boxes')),
                                         array('title' => BOX_CONFIGURATION_LOGGING, 'link' => oos_href_link_admin($aFilename['configuration'], 'selected_box=configuration&gID=10'),
                                               'access' => oos_admin_check_boxes('configuration', 'sub_boxes')),
                                         array('title' => BOX_CONFIGURATION_CACHE, 'link' => oos_href_link_admin($aFilename['configuration'], 'selected_box=configuration&gID=11'),
                                               'access' => oos_admin_check_boxes('configuration', 'sub_boxes')))),
               array('title' => BOX_HEADING_MODULES,
                     'access' => oos_admin_check_boxes('modules.php'),
                     'image' => 'modules.gif',
                     'href' => oos_href_link_admin(oos_selected_file('modules.php'), 'selected_box=modules&set=payment'),
                     'children' => array(array('title' => BOX_MODULES_PAYMENT, 'link' => oos_href_link_admin($aFilename['modules'], 'selected_box=modules&set=payment'),
                                               'access' => oos_admin_check_boxes('modules', 'sub_boxes')),
                                         array('title' => BOX_MODULES_SHIPPING, 'link' => oos_href_link_admin($aFilename['modules'], 'selected_box=modules&set=shipping'),
                                               'access' => oos_admin_check_boxes('modules', 'sub_boxes')))),
               array('title' => BOX_HEADING_CATALOG,
                     'access' => oos_admin_check_boxes('catalog.php'),
                     'image' => 'catalog.gif',
                     'href' => oos_href_link_admin(oos_selected_file('catalog.php'), 'selected_box=catalog'),
                     'children' => array(array('title' => CATALOG_CONTENTS, 'link' => oos_href_link_admin($aFilename['categories'], 'selected_box=catalog'),
                                               'access' => oos_admin_check_boxes('categories', 'sub_boxes')),
                                         array('title' => BOX_CATALOG_MANUFACTURERS, 'link' => oos_href_link_admin($aFilename['manufacturers'], 'selected_box=catalog'),
                                               'access' => oos_admin_check_boxes('manufacturers', 'sub_boxes')))),
               array('title' => BOX_HEADING_LOCATION_AND_TAXES,
                     'access' => oos_admin_check_boxes('taxes.php'),
                     'image' => 'location.gif',
                     'href' => oos_href_link_admin(oos_selected_file('taxes.php'), 'selected_box=taxes'),
                     'children' => array(array('title' => BOX_TAXES_COUNTRIES, 'link' => oos_href_link_admin($aFilename['countries'], 'selected_box=taxes'),
                                               'access' => oos_admin_check_boxes('countries', 'sub_boxes')),
                                         array('title' => BOX_TAXES_GEO_ZONES, 'link' => oos_href_link_admin($aFilename['geo_zones'], 'selected_box=taxes'),
                                               'access' => oos_admin_check_boxes('geo_zones', 'sub_boxes')))),
               array('title' => BOX_HEADING_CUSTOMERS,
                     'access' => oos_admin_check_boxes('customers.php'),
                     'image' => 'customers.gif',
                     'href' => oos_href_link_admin(oos_selected_file('customers.php'), 'selected_box=customers'),
                     'children' => array(array('title' => BOX_CUSTOMERS_CUSTOMERS, 'link' => oos_href_link_admin($aFilename['customers'], 'selected_box=customers'),
                                               'access' => oos_admin_check_boxes('customers', 'sub_boxes')),
                                         array('title' => BOX_CUSTOMERS_ORDERS, 'link' => oos_href_link_admin($aFilename['orders'], 'selected_box=customers'),
                                               'access' => oos_admin_check_boxes('orders', 'sub_boxes')))),
               array('title' => BOX_HEADING_LOCALIZATION,
                     'access' => oos_admin_check_boxes('localization.php'),
                     'image' => 'localization.gif',
                     'href' => oos_href_link_admin(oos_selected_file('localization.php'), 'selected_box=localization'),
                     'children' => array(array('title' => BOX_LOCALIZATION_CURRENCIES, 'link' => oos_href_link_admin($aFilename['currencies'], 'selected_box=localization'),
                                               'access' => oos_admin_check_boxes('currencies', 'sub_boxes')),
                                         array('title' => BOX_LOCALIZATION_LANGUAGES, 'link' => oos_href_link_admin($aFilename['languages'], 'selected_box=localization'),
                                               'access' => oos_admin_check_boxes('languages', 'sub_boxes')))),
               array('title' => BOX_HEADING_REPORTS,
                     'access' => oos_admin_check_boxes('reports.php'),
                     'image' => 'reports.gif',
                     'href' => oos_href_link_admin($aFilename['stats_products_purchased'], 'selected_box=reports'),
                     'children' => array(array('title' => REPORTS_PRODUCTS, 'link' => oos_href_link_admin($aFilename['stats_products_purchased'], 'selected_box=reports'),
                                               'access' => oos_admin_check_boxes('stats_products_purchased', 'sub_boxes')),
                                         array('title' => REPORTS_ORDERS, 'link' => oos_href_link_admin($aFilename['stats_customers'], 'selected_box=reports'),              
                                               'access' => oos_admin_check_boxes('stats_customers', 'sub_boxes')))),

);

  $languages = oos_get_languages();
  $languages_array = array();
  $languages_selected = DEFAULT_LANGUAGE;
  for ($i = 0, $n = count($languages); $i < $n; $i++) {
    $languages_array[] = array('id' => $languages[$i]['iso_639_2'],
                               'text' => $languages[$i]['name']);
    if ($languages[$i]['iso_639_2'] == $_SESSION['language']) {
      $languages_selected = $languages[$i]['iso_639_2'];
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?> - Administration [OOS]</title>
<style type="text/css"><!--
a { color:#080381; text-decoration:none; }
a:hover { color:#aabbdd; text-decoration:underline; }
a.text:link, a.text:visited { color: #000000; text-decoration: none; }
a:text:hover { color: #000000; text-decoration: underline; }
a.main:link, a.main:visited { color: #ffffff; text-decoration: none; }
A.main:hover { color: #ffffff; text-decoration: underline; }
a.sub:link, a.sub:visited { color: #dddddd; text-decoration: none; }
A.sub:hover { color: #dddddd; text-decoration: underline; }
.heading { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 20px; font-weight: bold; line-height: 1.5; color: #D3DBFF; }
.main { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 17px; font-weight: bold; line-height: 1.5; color: #ffffff; }
.main_false { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 17px; font-weight: bold; line-height: 1.5; color: #dddddd; }
.sub { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; line-height: 1.5; color: #ffffff; }
.sub_false { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; line-height: 1.5; color: #C1C1C1; }
.text { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: bold; line-height: 1.5; color: #000000; }
.menuBoxHeading { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #ffffff; font-weight: bold; background-color: #7187bb; border-color: #7187bb; border-style: solid; border-width: 1px; }
.infoBox { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color: #080381; background-color: #f2f4ff; border-color: #7187bb; border-style: solid; border-width: 1px; }
.smallText { font-family: Verdana, Arial, sans-serif; font-size: 10px; }
.messageBox { font-family: Verdana, Arial, sans-serif; font-size: 10px; }
.messageStackError, .messageStackWarning { font-family: Verdana, Arial, sans-serif; font-size: 10px; background-color: #ffb3b5; }
.messageStackSuccess { font-family: Verdana, Arial, sans-serif; font-size: 10px; background-color: #99ff00; }
//--></style>

<!--[if lt IE 7]>
<script defer type="text/javascript" src="includes.giffix.js"></script>
<![endif]-->
  <script type="text/javascript" src="includes/imgswap.js"></script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<?php
  if ($messageStack->size > 0) {
    echo $messageStack->output();
  }
?>
<table border="0" width="600" height="100%" cellspacing="0" cellpadding="0" align="center" valign="middle">
  <tr>
    <td><table border="0" width="600" height="440" cellspacing="0" cellpadding="1" align="center" valign="middle">
      <tr bgcolor="#000000">
        <td><table border="0" width="600" height="440" cellspacing="0" cellpadding="0">
          <tr bgcolor="#ffffff" height="50">
            <td height="50"></td>
            <td align="right" class="text" nowrap><?php echo '<a href="' . oos_href_link_admin($aFilename['default']) . '">' . HEADER_TITLE_ADMINISTRATION . '</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="' . oos_catalog_link($oosModules['main'], $oosCatalogFilename['default']) . '">' . HEADER_TITLE_ONLINE_CATALOG . '</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="http://www.oos-shop.de/" target="_blank">' . HEADER_TITLE_SUPPORT_SITE . '</a>'; ?>&nbsp;&nbsp;</td>
          </tr>
          <tr bgcolor="#8C8E8F">
            <td colspan="2"><table border="0" width="460" height="390" cellspacing="0" cellpadding="2">
              <tr>
                <td width="140" valign="top"><table border="0" width="140" height="390" cellspacing="0" cellpadding="2">
                  <tr>
                    <td valign="center">
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('params' => 'class="menuBoxHeading"',
                     'text'  => 'OSIS online Shop');

  $contents[] = array('params' => 'class="infoBox"',
                      'text'  => '<a href="http://www.oos-shop.de/" target="_blank">' . BOX_ENTRY_HAMPEAGE . '</a><br />' .
                                 '<a href="http://developer.berlios.de/mail/?group_id=814" target="_blank">' . BOX_ENTRY_MAILING_LISTS . '</a><br />' .
                                 '<a href="http://www.oos-shop.de/modules.php?name=FAQ" target="_blank">' . BOX_ENTRY_FAQ . '</a><br />');

  $box = new box;
  echo $box->menuBox($heading, $contents);

  echo '<br />';

  $orders_contents = '';
  $orders_status_result = $dbconn->Execute("SELECT orders_status_name, orders_status_id FROM " . $oostable['orders_status'] . " WHERE orders_languages_id = '" . intval($_SESSION['language_id']) . "'");
  while ($orders_status = $orders_status_result->fields) {
    $orders_pending_result = $dbconn->Execute("SELECT COUNT(*) AS count FROM " . $oostable['orders'] . " WHERE orders_status = '" . $orders_status['orders_status_id'] . "'");
    $orders_pending = $orders_pending_result->fields;
    if (oos_admin_check_boxes($aFilename['orders'], 'sub_boxes') == true) { 
      $orders_contents .= '<a href="' . oos_href_link_admin($aFilename['orders'], 'selected_box=customers&status=' . $orders_status['orders_status_id']) . '">' . $orders_status['orders_status_name'] . '</a>: ' . $orders_pending['count'] . '<br />';
    } else {
      $orders_contents .= '' . $orders_status['orders_status_name'] . ': ' . $orders_pending['count'] . '<br />';
    }

    // Move that ADOdb pointer!
    $orders_status_result->MoveNext();
  }
  $orders_contents = substr($orders_contents, 0, -4);

  $heading = array();
  $contents = array();

  $heading[] = array('params' => 'class="menuBoxHeading"',
                     'text'  => BOX_TITLE_ORDERS);

  $contents[] = array('params' => 'class="infoBox"',
                      'text'  => $orders_contents);

  $box = new box;
  echo $box->menuBox($heading, $contents);

  echo '<br />';

  $customers_result = $dbconn->Execute("SELECT COUNT(*) AS count FROM " . $oostable['customers']);
  $customers = $customers_result->fields;
  $products_result = $dbconn->Execute("SELECT COUNT(*) AS count FROM " . $oostable['products'] . " WHERE products_status >= '1'");
  $products = $products_result->fields;
  $reviews_result = $dbconn->Execute("SELECT COUNT(*) AS count FROM " . $oostable['reviews']);
  $reviews = $reviews_result->fields;

  $heading = array();
  $contents = array();

  $heading[] = array('params' => 'class="menuBoxHeading"',
                     'text'  => BOX_TITLE_STATISTICS);

  $contents[] = array('params' => 'class="infoBox"',
                      'text'  => BOX_ENTRY_CUSTOMERS . ' ' . $customers['count'] . '<br />' .
                                 BOX_ENTRY_PRODUCTS . ' ' . $products['count'] . '<br />' .
                                 BOX_ENTRY_REVIEWS . ' ' . $reviews['count']);

  $box = new box;
  echo $box->menuBox($heading, $contents);

  echo '<br />';

  $contents = array();

  if ( (oos_server_get_var('HTTPS') == 'on') || (oos_server_get_var('HTTPS') == '1') ) {
    $size = ((oos_server_get_var('SSL_CIPHER_ALGKEYSIZE')) ? oos_server_get_var('SSL_CIPHER_ALGKEYSIZE') . '-bit' : '<i>' . BOX_CONNECTION_UNKNOWN . '</i>');
    $contents[] = array('params' => 'class="infoBox"',
                        'text' => oos_image(OOS_IMAGES . 'icons/locked.gif', ICON_LOCKED, '', '', 'align="right"') . sprintf(BOX_CONNECTION_PROTECTED, $size));
  } else {
    $contents[] = array('params' => 'class="infoBox"',
                        'text' => oos_image(OOS_IMAGES . 'icons/unlocked.gif', ICON_UNLOCKED, '', '', 'align="right"') . BOX_CONNECTION_UNPROTECTED);
  }

  $box = new box;
  echo $box->tableBlock($contents);
?>
                    </td>
                  </tr>
                </table></td>
                <td width="460" valign="center"><table border="0" width="460" height="375" cellspacing="1" cellpadding="1">
                  <tr>
                    <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr><?php echo oos_draw_form('languages', 'index.php', '', 'get'); ?>
                        <td class="heading"><?php echo HEADING_TITLE; ?></td>
                        <td align="right"><?php echo oos_draw_pull_down_menu('language', $languages_array, $languages_selected, 'onChange="this.form.submit();"'); ?></td>
                      </form></tr>
                    </table></td>
                  </tr>
<?php
  $col = 2;
  $counter = 0;
  for ($i = 0, $n = count($cat); $i < $n; $i++) {
    if ($cat[$i]['access'] == true) {
    $counter++;
    if ($counter < $col) {
      echo '                  <tr>' . "\n";
    }

    echo '                    <td><table border="0" cellspacing="0" cellpadding="2">' . "\n" .
         '                      <tr>' . "\n" .
         '                        <td><a href="' . $cat[$i]['href'] . '">' . oos_image(OOS_IMAGES . 'categories/' . $cat[$i]['image'], $cat[$i]['title'], '50', '50') . '</a></td>' . "\n" .
         '                        <td><table border="0" cellspacing="0" cellpadding="1">' . "\n" .
         '                          <tr>' . "\n" .
         '                            <td class="main"><a href="' . $cat[$i]['href'] . '" class="main">' . $cat[$i]['title'] . '</a></td>' . "\n" .
         '                          </tr>' . "\n" .
         '                          <tr>' . "\n" .
         '                            <td class="sub_false">';

    $children = '';
    for ($j = 0, $k = count($cat[$i]['children']); $j < $k; $j++) {
      if ($cat[$i]['children'][$j]['access'] == true) {
      $children .= '<a href="' . $cat[$i]['children'][$j]['link'] . '" class="sub">' . $cat[$i]['children'][$j]['title'] . '</a>, ';
      } else {
        $children .= '' . $cat[$i]['children'][$j]['title'] . ', ';
      }
    }
    echo substr($children, 0, -2);

    echo '</td> ' . "\n" .
         '                          </tr>' . "\n" .
         '                        </table></td>' . "\n" .
         '                      </tr>' . "\n" .
         '                    </table></td>' . "\n";

    if ($counter >= $col) {
      echo '                  </tr>' . "\n";
      $counter = 0;
    }
    } elseif ($cat[$i]['access'] == false) {
    $counter++;
    if ($counter < $col) {
      echo '                  <tr>' . "\n";
    }

    echo '                    <td><table border="0" cellspacing="0" cellpadding="2">' . "\n" .
         '                      <tr>' . "\n" .
         '                        <td>' . oos_image(OOS_IMAGES . 'categories/' . $cat[$i]['image'], $cat[$i]['title'], '50', '50') . '</td>' . "\n" .
         '                        <td><table border="0" cellspacing="0" cellpadding="1">' . "\n" .
         '                          <tr>' . "\n" .
         '                            <td class="main_false">' . $cat[$i]['title'] . '</td>' . "\n" .
         '                          </tr>' . "\n" .
         '                          <tr>' . "\n" .
         '                            <td class="sub_false">';

    $children = '';
    for ($j = 0, $k = count($cat[$i]['children']); $j < $k; $j++) {
      $children .= '' . $cat[$i]['children'][$j]['title'] . ', ';
    }
    echo substr($children, 0, -2);

    echo '</td> ' . "\n" .
         '                          </tr>' . "\n" .
         '                        </table></td>' . "\n" .
         '                      </tr>' . "\n" .
         '                    </table></td>' . "\n";

    if ($counter >= $col) {
      echo '                  </tr>' . "\n";
      $counter = 0;
    }
    }
  }
?>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php require 'includes/oos_footer.php'; ?></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php require 'includes/oos_nice_exit.php'; ?>