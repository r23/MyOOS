<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */


/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

class plugin_event
{
    public $aEventPlugins;
    public $aPlugins;

    public function __construct()
    {
        $this->aEventPlugins = explode(';', (string) MODULE_PLUGIN_EVENT_INSTALLED);
    }

    public function getInstance()
    {
        $this->aPlugins = [];

        foreach ($this->aEventPlugins as $event) {
            $this->load_plugin($event);
        }
    }


    public function load_plugin($sInstance, $sPluginPath = '')
    {
        $sName = 'oos_event_' . $sInstance;

        if (!class_exists($sName)) {
            if (empty($sPluginPath)) {
                if (empty($sPluginPath)) {
                    $sPluginPath = $sName;
                }
            }


            $sPluginPath = oos_var_prep_for_os($sPluginPath);
            $sName = oos_var_prep_for_os($sName);

            if (file_exists('includes/plugins/' . $sPluginPath . '/' . $sName . '.php')) {
                include_once 'includes/plugins/' . $sPluginPath . '/' . $sName . '.php';
            }

            if (isset($_SESSION['language']) &&  file_exists('includes/plugins/' . $sPluginPath . '/lang/' . oos_var_prep_for_os($_SESSION['language']) . '.php')) {
                include_once 'includes/plugins/' . $sPluginPath . '/lang/' . oos_var_prep_for_os($_SESSION['language']) . '.php';
            } elseif (file_exists('includes/plugins/' . $sPluginPath . '/lang/' . DEFAULT_LANGUAGE . '.php')) {
                include_once 'includes/plugins/' . $sPluginPath . '/lang/' . DEFAULT_LANGUAGE . '.php';
            }

            if (!class_exists($sName)) {
                return false;
            }
        }

        if (@call_user_func($sName .'::create_plugin_instance')) {
            $this->aPlugins[] = $sName;
        }

        return true;
    }


    public function introspect()
    {
        $this->aPlugins = [];

        foreach ($this->aEventPlugins as $event) {
            $this->get_intro($event);
        }
    }


    public function get_intro($event)
    {
        @call_user_func(['oos_event_' . $event, 'intro']);
    }


    public function installed_plugin($event)
    {
        return in_array($event, $this->aEventPlugins);
    }
}
