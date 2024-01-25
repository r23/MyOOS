<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: navigation_history.php,v 1.5 2003/02/12 21:07:45 hpdl
   ----------------------------------------------------------------------
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

/**
 * Class Navigation History
 */
class navigationHistory
{
    public $path;
    public $snapshot;

    /**
     * Constructor of our Class
     */
    public function __construct()
    {
        $this->reset();
    }

    public function reset()
    {
        $this->path = [];
        $this->snapshot = [];
    }


    public function set_snapshot($page = '')
    {
        global $sContent;

        if (is_array($page)) {
            $this->snapshot = ['content' => $page['content'], 'get' => $page['get']];
        } else {
            $get_all = '';
            if (isset($_GET)) {
                $get_all = oos_get_all_get_parameters();
                $get_all = oos_remove_trailing($get_all);
            }
            $this->snapshot = ['content' => $sContent, 'get' => $get_all];
        }
    }


    public function clear_snapshot()
    {
        $this->snapshot = [];
    }

    public function set_path_as_snapshot($history = 0)
    {
        $pos = ((is_countable($this->path) ? count($this->path) : 0) - 1 - $history);
        $this->snapshot = ['content' => $this->path[$pos]['content'], 'get' => $this->path[$pos]['get']];
    }


    public function debug()
    {
        $n = is_countable($this->path) ? count($this->path) : 0;
        for ($i = 0, $n; $i < $n; $i++) {
            echo $this->path[$i]['content'] . '&' . $this->path[$i]['get'] . '<br />';
            echo '<br />';
        }

        echo '<br /><br />';
        if ((is_countable($this->snapshot) ? count($this->snapshot) : 0) > 0) {
            echo $this->snapshot['content'] . '&' . $this->snapshot['get'] . '<br />';
        }
    }
}
