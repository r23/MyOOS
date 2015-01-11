<?php
/* ----------------------------------------------------------------------
   $Id: stats_referer.php,v 1.1 2007/06/08 17:14:42 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2015 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/main.php';

  if (isset($_GET['action']) && ($_GET['action'] == 'reset')) {
    $referertable = $oostable['referer'];
    $dbconn->Execute("DELETE FROM $referertable");
    oos_redirect_admin(oos_href_link_admin($aContents['stats_referer'], 'reset=1'));
  }
  if (isset($_GET['reset']) && ($_GET['reset'] == '1')) {
    $messageStack->add(TEXT_HTTP_REFERERS_RESET, 'success');
  }

  $no_js_general = true;
  require 'includes/header.php'; 
?>
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<?php require 'includes/blocks.php'; ?>
        </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NUMBER; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FREQUENCY; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_URL; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PER_CENT; ?>&nbsp;</td>
              </tr>
<?php
  if (isset($_GET['page']) && ($_GET['page'] > 1)) $rows = $_GET['page'] * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;

  $referertable = $oostable['referer'];
  $result = $dbconn->Execute("SELECT SUM(frequency) AS total FROM $referertable");
  $totalfreq = $result->fields['total'];

  $referertable = $oostable['referer'];
  $referer_sql_raw = "SELECT referer_id, url, frequency
                      FROM $referertable
                      WHERE url != 'Bookmark'
                      ORDER BY frequency
                      DESC";
  $referer_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $referer_sql_raw, $referer_result_numrows);
  $referer_result = $dbconn->Execute($referer_sql_raw);
  while ($referer = $referer_result->fields) {
    $rows++;

    $url = $referer['url'];
    $urls = str_replace('&', ' &', $url);
    $urls = str_replace('/', '/ ', $urls);

    $url = urlencode($url);
    $url = htmlspecialchars(utf8_encode($url));

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
?>
              <tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'" onclick="document.location.href='http://www.google.com/url?sa=D&q=<?php echo $url; ?>'">
                <td class="dataTableContent"><?php echo $rows; ?>.</td>
                <td class="dataTableContent"><?php echo $referer['frequency']; ?></td>
                <td class="dataTableContent"><?php echo $urls; ?></td>
               <td class="dataTableContent" align="center"><?php echo round(($referer['frequency'] / $totalfreq * 100), 2) ?>%&nbsp;</td>
              </tr>
<?php
    // Move that ADOdb pointer!
    $referer_result->MoveNext();
  }

  // Close result set
  $referer_result->Close();
?>
            </table></td>
          </tr>
          <tr>
            <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $referer_split->display_count($referer_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_HTTP_REFERERS); ?></td>
                <td class="smallText" align="right"><?php echo $referer_split->display_links($referer_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>

           <tr>
            <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo '<a href="' . oos_href_link_admin($aContents['stats_referer'],"action=reset") . '">' . oos_image_swap_button('reset','reset_off.gif', IMAGE_RESET) . '</a>'; ?></td>
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


<?php require 'includes/bottom.php'; ?>
<?php require 'includes/nice_exit.php'; ?>