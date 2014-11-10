<?php
/* ----------------------------------------------------------------------
   $Id: banner_statistics.php,v 1.1 2007/06/08 17:14:40 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: banner_statistics.php,v 1.4 2002/11/22 14:45:45 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';

  $banner_extension = oos_banner_image_extension();

// check if the graphs directory exists
  $dir_ok = false;
  if ( (function_exists('imagecreate')) && ($banner_extension) ) {
    if (is_dir(OOS_IMAGES . 'graphs')) {
      if (is_writeable(OOS_IMAGES . 'graphs')) {
        $dir_ok = true;
      } else {
        $messageStack->add(ERROR_GRAPHS_DIRECTORY_NOT_WRITEABLE, 'error');
      }
    } else {
      $messageStack->add(ERROR_GRAPHS_DIRECTORY_DOES_NOT_EXIST, 'error');
    }
  }

  $banner_result = $dbconn->Execute("SELECT banners_title FROM " . $oostable['banners'] . " WHERE banners_id = '" . $_GET['bID'] . "'");
  $banner = $banner_result->fields;

  $years_array = array();
  $years_result = $dbconn->Execute("SELECT distinct year(banners_history_date) as banner_year FROM " . $oostable['banners_history'] . " WHERE banners_id = '" . $_GET['bID'] . "'");
  while ($years = $years_result->fields) {
    $years_array[] = array('id' => $years['banner_year'],
                           'text' => $years['banner_year']);

    // Move that ADOdb pointer!
    $years_result->MoveNext();
  }

  // Close result set
  $years_result->Close();

  $months_array = array();
  for ($i=1; $i<13; $i++) {
    $months_array[] = array('id' => $i,
                            'text' => strftime('%B', mktime(0,0,0,$i)));
  }

  $type_array = array(array('id' => 'daily',
                            'text' => STATISTICS_TYPE_DAILY),
                      array('id' => 'monthly',
                            'text' => STATISTICS_TYPE_MONTHLY),
                      array('id' => 'yearly',
                            'text' => STATISTICS_TYPE_YEARLY));
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><?php echo oos_draw_form('year', $aFilename['banner_statistics'], '', 'get'); ?>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', '1', HEADING_IMAGE_HEIGHT); ?></td>
            <td class="main" align="right"><?php echo TITLE_TYPE . ' ' . oos_draw_pull_down_menu('type', $type_array, (($_GET['type']) ? $_GET['type'] : 'daily'), 'onChange="this.form.submit();"'); ?><noosript><input type="submit" value="GO"></noosript><br />
<?php
  switch ($_GET['type']) {
    case 'yearly': break;
    case 'monthly':
      echo TITLE_YEAR . ' ' . oos_draw_pull_down_menu('year', $years_array, (($_GET['year']) ? $_GET['year'] : date('Y')), 'onChange="this.form.submit();"') . '<noosript><input type="submit" value="GO"></noosript>';
      break;

    default:
    case 'daily':
      echo TITLE_MONTH . ' ' . oos_draw_pull_down_menu('month', $months_array, (($_GET['month']) ? $_GET['month'] : date('n')), 'onChange="this.form.submit();"') . '<noosript><input type="submit" value="GO"></noosript><br />' . TITLE_YEAR . ' ' . oos_draw_pull_down_menu('year', $years_array, (($_GET['year']) ? $_GET['year'] : date('Y')), 'onChange="this.form.submit();"') . '<noosript><input type="submit" value="GO"></noosript>';
      break;
  }
?>
            </td>
          <?php echo oos_draw_hidden_field('page', $_GET['page']) . oos_draw_hidden_field('bID', $_GET['bID']); ?></form></tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="center">
<?php
  if ( (function_exists('imagecreate')) && ($dir_ok) && ($banner_extension) ) {
    $banner_id = $_GET['bID'];
    switch ($_GET['type']) {
      case 'yearly':
        include 'includes/graphs/banner_yearly.php';
        echo oos_image(OOS_IMAGES . 'graphs/banner_yearly-' . $banner_id . '.' . $banner_extension);
        break;

      case 'monthly':
        include 'includes/graphs/banner_monthly.php';
        echo oos_image(OOS_IMAGES . 'graphs/banner_monthly-' . $banner_id . '.' . $banner_extension);
        break;

      default:
      case 'daily':
        include 'includes/graphs/banner_daily.php';
        echo oos_image(OOS_IMAGES . 'graphs/banner_daily-' . $banner_id . '.' . $banner_extension);
        break;
    }
?>
          <table border="0" width="600" cellspacing="0" cellpadding="2">
            <tr class="dataTableHeadingRow">
             <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_SOURCE; ?></td>
             <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_VIEWS; ?></td>
             <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_CLICKS; ?></td>
           </tr>
<?php
    for ($i = 0, $n = count($stats); $i < $n; $i++) {
      echo '            <tr class="dataTableRow">' . "\n" .
           '              <td class="dataTableContent">' . $stats[$i][0] . '</td>' . "\n" .
           '              <td class="dataTableContent" align="right">' . number_format($stats[$i][1]) . '</td>' . "\n" .
           '              <td class="dataTableContent" align="right">' . number_format($stats[$i][2]) . '</td>' . "\n" .
           '            </tr>' . "\n";
    }
?>
          </table>
<?php
  } else {
    include 'includes/functions/function_graphs.php';
    switch ($_GET['type']) {
      case 'yearly':
        echo oosBannerGraphYearly($_GET['bID']);
        break;

      case 'monthly':
        echo oosBannerGraphMonthly($_GET['bID']);
        break;

      default:
      case 'daily':
        echo oosBannerGraphDaily($_GET['bID']);
        break;
    }
  }
?>
        </td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main" align="right"><?php echo '<a href="' . oos_href_link_admin($aFilename['banner_manager'], 'page=' . $_GET['page'] . '&bID=' . $_GET['bID']) . '">' . oos_image_swap_button('back','back_off.gif', IMAGE_BACK) . '</a>'; ?></td>
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
<?php require 'includes/oos_nice_exit.php'; ?>