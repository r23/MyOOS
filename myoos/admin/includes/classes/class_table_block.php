<?php
/**
   ----------------------------------------------------------------------
   $Id: class_table_block.php,v 1.1 2007/06/08 14:58:10 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: table_block.php,v 1.2 2002/11/22 18:45:46 dgw_
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

class tableBlock
{
    public $table_border = '0';
    public $table_width = '100%';
    public $table_cellspacing = '0';
    public $table_cellpadding = '2';
    public $table_parameters = '';
    public $table_row_parameters = '';
    public $table_data_parameters = '';

    public function __construct()
    {
    }

    public function tableThead($contents)
    {
        $sTableBox = '';

        $form_set = false;

        for ($i = 0, $n = is_countable($contents) ? count($contents) : 0; $i < $n; $i++) {
            $sTableBox .= '  <tr';
            if (oos_is_not_null($this->table_row_parameters)) {
                $sTableBox .= ' ' . $this->table_row_parameters;
            }
            if (isset($contents[$i]['params']) && oos_is_not_null($contents[$i]['params'])) {
                $sTableBox .= ' ' . $contents[$i]['params'];
            }
            $sTableBox .= '>' . "\n";

            $sTableBox .= '    <th';
            if (isset($contents[$i]['align']) && oos_is_not_null($contents[$i]['align'])) {
                $sTableBox .= ' align="' . $contents[$i]['align'] . '"';
            }
            if (isset($contents[$i]['params']) && oos_is_not_null($contents[$i]['params'])) {
                $sTableBox .= ' ' . $contents[$i]['params'];
            } elseif (oos_is_not_null($this->table_data_parameters)) {
                $sTableBox .= ' ' . $this->table_data_parameters;
            }
            $sTableBox .= '>' . $contents[$i]['text'] . '</th>' . "\n";


            $sTableBox .= '  </tr>' . "\n";
        }


        return $sTableBox;
    }


    public function tableBlock($contents)
    {
        $sTableBox = '';

        $form_set = false;
        if (isset($contents['form'])) {
            $sTableBox .= $contents['form'] . "\n";
            $form_set = true;
            array_shift($contents);
        }


        for ($i = 0, $n = is_countable($contents) ? count($contents) : 0; $i < $n; $i++) {
            $sTableBox .= '  <tr';
            if (oos_is_not_null($this->table_row_parameters)) {
                $sTableBox .= ' ' . $this->table_row_parameters;
            }
            if (isset($contents[$i]['params']) && oos_is_not_null($contents[$i]['params'])) {
                $sTableBox .= ' ' . $contents[$i]['params'];
            }
            $sTableBox .= '>' . "\n";

            if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
                for ($x = 0, $y = is_countable($contents[$i]) ? count($contents[$i]) : 0; $x < $y; $x++) {
                    if (isset($contents[$i][$x]['text']) && oos_is_not_null($contents[$i][$x]['text'])) {
                        $sTableBox .= '    <td';
                        if (isset($contents[$i][$x]['align']) && oos_is_not_null($contents[$i][$x]['align'])) {
                            $sTableBox .= ' align="' . $contents[$i][$x]['align'] . '"';
                        }
                        if (isset($contents[$i][$x]['params']) && oos_is_not_null($contents[$i][$x]['params'])) {
                            $sTableBox .= ' ' . $contents[$i][$x]['params'];
                        } elseif (oos_is_not_null($this->table_data_parameters)) {
                            $sTableBox .= ' ' . $this->table_data_parameters;
                        }
                        $sTableBox .= '>';
                        if (isset($contents[$i][$x]['form']) && oos_is_not_null($contents[$i][$x]['form'])) {
                            $sTableBox .= $contents[$i][$x]['form'];
                        }
                        $sTableBox .= $contents[$i][$x]['text'];
                        if (isset($contents[$i][$x]['form']) && oos_is_not_null($contents[$i][$x]['form'])) {
                            $sTableBox .= '</form>';
                        }
                        $sTableBox .= '</td>' . "\n";
                    }
                }
            } else {
                $sTableBox .= '    <td';
                if (isset($contents[$i]['align']) && oos_is_not_null($contents[$i]['align'])) {
                    $sTableBox .= ' align="' . $contents[$i]['align'] . '"';
                }
                if (isset($contents[$i]['params']) && oos_is_not_null($contents[$i]['params'])) {
                    $sTableBox .= ' ' . $contents[$i]['params'];
                } elseif (oos_is_not_null($this->table_data_parameters)) {
                    $sTableBox .= ' ' . $this->table_data_parameters;
                }
                $sTableBox .= '>' . $contents[$i]['text'] . '</td>' . "\n";
            }

            $sTableBox .= '  </tr>' . "\n";
        }

        if ($form_set == true) {
            $sTableBox .= '</form>' . "\n";
        }

        return $sTableBox;
    }
}
