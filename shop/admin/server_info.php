<?php
/* ----------------------------------------------------------------------
   $Id: server_info.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: server_info.php,v 1.3 2002/03/16 01:36:56 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';


 /**
  * Retreive server information
  *
  * @return array
  */
  function oosGetSystemInformation() {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $db_host = $dbconn->host;
    $db_database = $dbconn->database;
    $phpv = phpversion();


    $db_result = $dbconn->ServerInfo($oostable['countries']);

    list($system, $host, $kernel) = preg_split('/[\s,]+/', @exec('uname -a'), 5);

    return array('date' => oos_datetime_short(date('Y-m-d H:i:s')),
                 'system' => $_ENV["OS"],
                 'kernel' => $kernel,
                 'host' => $host,
                 'ip' => gethostbyname($host),
                 'uptime' => @exec('uptime'),
                 'HTTP_SERVER' => $_SERVER['SERVER_SOFTWARE'],
                 'php' => $phpv,
                 'zend' => (function_exists('zend_version') ? zend_version() : ''),
                 'db_server' => $db_host,
                 'db_ip' => gethostbyname(OOS_DB_SERVER),
                 'db_version' => OOS_DB_TYPE . $db_result['description'], 
                 'db_database' => $db_database);
  }


  $system = oosGetSystemInformation();
  
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
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" cellspacing="0" cellpadding="3">
              <tr>
                <td class="smallText"><b><?php echo TITLE_SERVER_HOST; ?></b></td>
                <td class="smallText"><?php echo $system['host'] . ' (' . $system['ip'] . ')'; ?></td>
                <td class="smallText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo TITLE_DATABASE_HOST; ?></b></td>
                <td class="smallText"><?php echo $system['db_server'] . ' (' . $system['db_ip'] . ')'; ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TITLE_SERVER_OS; ?></b></td>
                <td class="smallText"><?php echo $system['system'] . ' ' . $system['kernel']; ?></td>
                <td class="smallText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>DB Server</b></td>
                <td class="smallText"><?php echo $system['db_version']; ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TITLE_SERVER_DATE; ?></b></td>
                <td class="smallText"><?php echo $system['date']; ?></td>
                <td class="smallText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo TITLE_DATABASE_DATE; ?></b></td>
                <td class="smallText"><?php echo $system['db_date']; ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TITLE_SERVER_UP_TIME; ?></b></td>
                <td colspan="3" class="smallText"><?php echo $system['uptime']; ?></td>
              </tr>
              <tr>
                <td colspan="4"><?php echo oos_draw_separator('trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TITLE_SYSTEM; ?></b></td>
                <td colspan="3" class="smallText"><?php echo php_uname(); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TITLE_HTTP_SERVER; ?></b></td>
                <td colspan="3" class="smallText"><?php echo $system['HTTP_SERVER']; ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TITLE_PHP_VERSION; ?></b></td>
                <td colspan="3" class="smallText"><?php echo $system['php'] . ' (' . TITLE_ZEND_VERSION . ' ' . $system['zend'] . ')&nbsp;&raquo;&nbsp;&raquo;&nbsp;<a href="' . oos_href_link_admin($aFilename['php_info'], '', 'NONSSL') . '" target="_blank">' . TITLE_PHP_INFORMATION . '</a>'; ?></td>
             </tr>
             <tr>
                <td class="smallText"><b>ADODB Version</b></td>
                <td colspan="3" class="smallText"><?php echo $ADODB_vers; ?></td>
             </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
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