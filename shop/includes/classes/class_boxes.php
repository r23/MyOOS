<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: boxes.php,v 1.31 2003/02/11 00:04:48 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  class tableBox {
    var $table_border = '0';
    var $table_width = '100%';
    var $table_cellspacing = '0';
    var $table_cellpadding = '2';
    var $table_parameters = '';
    var $table_row_parameters = '';
    var $table_data_parameters = '';

// class constructor
    function tableBox($contents, $direct_output = FALSE) {
      $tableBox_string = '<table border="' . $this->table_border . '" width="' . $this->table_width . '" cellspacing="' . $this->table_cellspacing . '" cellpadding="' . $this->table_cellpadding . '"';
      if (oos_is_not_null($this->table_parameters)) $tableBox_string .= ' ' . $this->table_parameters;
      $tableBox_string .= '>' . "\n";

      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        if (isset($contents[$i]['form']) && oos_is_not_null($contents[$i]['form'])) $tableBox_string .= $contents[$i]['form'] . "\n";
        $tableBox_string .= '  <tr';
        if (oos_is_not_null($this->table_row_parameters)) $tableBox_string .= ' ' . $this->table_row_parameters;
        if (isset($contents[$i]['params']) && oos_is_not_null($contents[$i]['params'])) $tableBox_string .= ' ' . $contents[$i]['params'];
        $tableBox_string .= '>' . "\n";

        if (is_array($contents[$i][0])) {
          for ($x=0, $n2=sizeof($contents[$i]); $x<$n2; $x++) {
            if (isset($contents[$i][$x]['text']) && oos_is_not_null($contents[$i][$x]['text'])) {
              $tableBox_string .= '    <td';
              if (isset($contents[$i][$x]['align']) && oos_is_not_null($contents[$i][$x]['align'])) $tableBox_string .= ' align="' . $contents[$i][$x]['align'] . '"';
              if (isset($contents[$i][$x]['params']) && oos_is_not_null($contents[$i][$x]['params'])) {
                $tableBox_string .= ' ' . $contents[$i][$x]['params'];
              } elseif (oos_is_not_null($this->table_data_parameters)) {
                $tableBox_string .= ' ' . $this->table_data_parameters;
              }
              $tableBox_string .= '>';
              if (isset($contents[$i][$x]['form']) && oos_is_not_null($contents[$i][$x]['form'])) $tableBox_string .= $contents[$i][$x]['form'];
              $tableBox_string .= $contents[$i][$x]['text'];
              if (isset($contents[$i][$x]['form']) && oos_is_not_null($contents[$i][$x]['form'])) $tableBox_string .= '</form>';
              $tableBox_string .= '</td>' . "\n";
            }
          }
        } else {
          $tableBox_string .= '    <td';
          if (isset($contents[$i]['align']) && oos_is_not_null($contents[$i]['align'])) $tableBox_string .= ' align="' . $contents[$i]['align'] . '"';
          if (isset($contents[$i]['params']) && oos_is_not_null($contents[$i]['params'])) {
            $tableBox_string .= ' ' . $contents[$i]['params'];
          } elseif (oos_is_not_null($this->table_data_parameters)) {
            $tableBox_string .= ' ' . $this->table_data_parameters;
          }
          $tableBox_string .= '>' . $contents[$i]['text'] . '</td>' . "\n";
        }

        $tableBox_string .= '  </tr>' . "\n";
        if (isset($contents[$i]['form']) && oos_is_not_null($contents[$i]['form'])) $tableBox_string .= '</form>' . "\n";
      }

      $tableBox_string .= '</table>' . "\n";

      if ($direct_output == TRUE) echo $tableBox_string;

      return $tableBox_string;
    }
  }

  class infoBox extends tableBox {
    function infoBox($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->infoBoxContents($contents));
      $this->table_cellpadding = '1';
      $this->table_parameters = 'class="infoBox"';
      $this->tableBox($info_box_contents, true);
    }

    function infoBoxContents($contents) {
      $this->table_cellpadding = '3';
      $this->table_parameters = 'class="infoBoxContents"';
      $info_box_contents = array();
      $info_box_contents[] = array(array('text' => ''));
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $info_box_contents[] = array(array('align' => $contents[$i]['align'],
                                           'form' => $contents[$i]['form'],
                                           'params' => 'class="boxText"',
                                           'text' => $contents[$i]['text']));
      }
      $info_box_contents[] = array(array('text' => ''));
      return $this->tableBox($info_box_contents);
    }
  }

  class infoBoxHeading extends tableBox {
    function infoBoxHeading($contents, $left_corner = TRUE, $right_corner = TRUE, $right_arrow = FALSE) {
      global $aLang;
      
      $this->table_cellpadding = '0';

      if ($left_corner == TRUE) {
        $left_corner = oos_image(OOS_IMAGES . 'infobox/corner_left.gif');
      } else {
        $left_corner = oos_image(OOS_IMAGES . 'infobox/corner_right_left.gif');
      }
      if ($right_arrow == TRUE) {
        $right_arrow = '<a href="' . $right_arrow . '">' . oos_image(OOS_IMAGES . 'infobox/arrow_right.gif', $aLang['icon_arrow_right']) . '</a>';
      } else {
        $right_arrow = '';
      }
      if ($right_corner == TRUE) {
        $right_corner = $right_arrow . oos_image(OOS_IMAGES . 'infobox/corner_right.gif');
      } else {
        $right_corner = $right_arrow;
      }

      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => 'height="14" class="infoBoxHeading"',
                                         'text' => $left_corner),
                                   array('params' => 'width="100%" height="14" class="infoBoxHeading"',
                                         'text' => $contents[0]['text']),
                                   array('params' => 'height="14" class="infoBoxHeading" nowrap',
                                         'text' => $right_corner));
      $this->tableBox($info_box_contents, true);
    }
  }


// WebMakers.com Added: Allows a footer that matches the header to be added
  class infoBoxFooter extends tableBox {
    function infoBoxFooter($contents, $left_corner = TRUE, $right_corner = TRUE, $right_arrow = FALSE) {
      $this->table_cellpadding = '0';

      if ($left_corner) {
        $left_corner = oos_image(OOS_IMAGES . 'infobox/corner_left.gif');
      } else {
        $left_corner = oos_image(OOS_IMAGES . 'infobox/corner_right_left.gif');
      }
      if ($right_arrow) {
        $right_arrow = '<a href="' . $right_arrow . '">' . oos_image(OOS_IMAGES . 'infobox/arrow_right.gif', ICON_ARROW_RIGHT) . '</a>';
      } else {
        $right_arrow = '';
      }
      if ($right_corner) {
        $right_corner = $right_arrow . oos_image(OOS_IMAGES . 'infobox/corner_right.gif');
      } else {
        $right_corner = $right_arrow;
      }

      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => 'height="14" class="infoBoxHeading"', 'text' => $left_corner),
                                   array('params' => 'width="100%" height="14" class="infoBoxHeading"', 'text' => $contents[0]['text']),
                                   array('params' => 'height="14" class="infoBoxHeading"', 'text' => $right_corner));
      $this->tableBox($info_box_contents, true);
    }
  }

  class contentBox extends tableBox {
    function contentBox($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->contentBoxContents($contents));
      $this->table_cellpadding = '1';
      $this->table_parameters = 'class="infoBox"';
      $this->tableBox($info_box_contents, true);
    }

    function contentBoxContents($contents) {
      $this->table_cellpadding = '4';
      $this->table_parameters = 'class="infoBoxContents"';
      return $this->tableBox($contents);
    }
  }

  class contentBoxHeading extends tableBox {
    function contentBoxHeading($contents) {
      $this->table_width = '100%';
      $this->table_cellpadding = '0';

      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => 'height="14" class="infoBoxHeading"',
                                         'text' => oos_image(OOS_IMAGES . 'infobox/corner_left.gif')),
                                   array('params' => 'height="14" class="infoBoxHeading" width="100%"',
                                         'text' => $contents[0]['text']),
                                   array('params' => 'height="14" class="infoBoxHeading"',
                                         'text' => oos_image(OOS_IMAGES . 'infobox/corner_right_left.gif')));

      $this->tableBox($info_box_contents, true);
    }
  }

  class errorBox extends tableBox {
    function errorBox($contents) {
      $this->table_data_parameters = 'class="errorBox"';
      $this->tableBox($contents, true);
    }
  }

