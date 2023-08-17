<?php
/**
   ----------------------------------------------------------------------
   $Id: oos_event_breadcrumb.php,v 1.1 2007/06/07 17:29:24 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2004 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

  /**
   * ensure this file is being included by a parent file
   */
  defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

class oos_event_breadcrumb
{
    public $name = PLUGIN_EVENT_BREADCRUMB_NAME;
    public $description = PLUGIN_EVENT_BREADCRUMB_DESC;
    public $uninstallable = false;
    public $depends;
    public $preceeds;
    public $author = 'MyOOS Development Team';
    public $version = '2.0';
    public $requirements = array(
                         'oos'         => '1.7.0',
                         'smarty'      => '2.6.9',
                         'adodb'       => '4.62',
                         'php'         => '5.9.0'
    );


    /**
     *  class constructor
     */
    public function __construct()
    {
    }

    public static function create_plugin_instance()
    {
        global $oBreadcrumb, $session, $aLang, $sCanonical, $aCategoryPath;

        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $aContents = oos_get_content();

        // include the breadcrumb class and start the breadcrumb trail
        include_once MYOOS_INCLUDE_PATH . '/includes/classes/class_breadcrumb.php';
        $oBreadcrumb = new breadcrumb();


        $oBreadcrumb->add($aLang['header_title_top'], oos_href_link($aContents['home']));
        $nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;

        // add category names or the manufacturer name to the breadcrumb trail
        if (isset($aCategoryPath) && (count($aCategoryPath) > 0)) {
            $nLanguageID = isset($_SESSION['language_id']) ? intval($_SESSION['language_id']) : DEFAULT_LANGUAGE_ID;

            $n = count($aCategoryPath);
            for ($i = 0, $n; $i < $n; $i++) {
                $categories_descriptiontable = $oostable['categories_description'];
                $categories_sql = "SELECT categories_name
                             FROM $categories_descriptiontable
                             WHERE categories_id = '" . intval($aCategoryPath[$i]) . "'
                             AND categories_languages_id = '" .  intval($nLanguageID) . "'";
                $categories = $dbconn->Execute($categories_sql);
                if ($categories->RecordCount() > 0) {
                    $oBreadcrumb->add($categories->fields['categories_name'], oos_href_link($aContents['shop'], 'category=' . implode('_', array_slice($aCategoryPath, 0, ($i+1)))));
                    $sCanonical = oos_href_link($aContents['shop'], 'category=' . implode('_', array_slice($aCategoryPath, 0, ($i+1))) . '&amp;page=' . $nPage, false, true);
                } else {
                    break;
                }
            }
        } elseif (isset($_GET['manufacturers_id']) && is_numeric($_GET['manufacturers_id'])) {
            $manufacturers_id = filter_input(INPUT_GET, 'manufacturers_id', FILTER_VALIDATE_INT);
            $manufacturerstable = $oostable['manufacturers'];
            $manufacturers_sql = "SELECT manufacturers_name
                              FROM $manufacturerstable
                              WHERE manufacturers_id = '" . intval($manufacturers_id) . "'";
            $manufacturers = $dbconn->Execute($manufacturers_sql);

            if ($manufacturers->RecordCount() > 0) {
                $oBreadcrumb->add($manufacturers->fields['manufacturers_name'], oos_href_link($aContents['shop'], 'manufacturers_id=' . $manufacturers_id));
                $sCanonical = oos_href_link($aContents['shop'], 'manufacturers_id=' . $manufacturers_id . '&amp;page=' . $nPage, false, true);
            }
        }

        return true;
    }

    public function install()
    {
        return false;
    }

    public function remove()
    {
        return false;
    }

    public function config_item()
    {
        return false;
    }
}
