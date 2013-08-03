<?php
/* ----------------------------------------------------------------------
   $Id: class_category_tree.php 439 2013-06-24 22:47:03Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: category_tree.php,v 1.2, 2004/10/26 20:07:09 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2001 - 2004 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  class oosCategoryTree {
    var $root_category_id = 0,
        $max_level = 0,
        $data = array(),
        $root_start_string = '',
        $root_end_string = '',
        $parent_start_string = '',
        $parent_end_string = '',
        $parent_group_start_string = '<ul>',
        $parent_group_end_string = '</ul>',
        $child_start_string = '<li>',
        $child_end_string = '</li>',
        $breadcrumb_separator = '_',
        $breadcrumb_usage = true,
        $spacer_string = '',
        $spacer_multiplier = 1,
        $follow_category = false,
        $category_array = array(),
        $category_start_string = '',
        $category_end_string = '',
        $show_category_product_count = false,
        $show_image_folder = false,
        $category_product_count_start_string = '&nbsp;(',
        $category_product_count_end_string = ')';

    function oosCategoryTree() {

      if (SHOW_COUNTS == 'true') {
        $this->show_category_product_count = true;
      }

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $nLanguageID = isset($_SESSION['language_id']) ? $_SESSION['language_id']+0 : DEFAULT_CUSTOMERS_STATUS_ID;

      $categoriestable = $oostable['categories'];
      $categories_descriptionstable = $oostable['categories_description'];
      $sql = "SELECT c.categories_id, cd.categories_name, c.parent_id, c.categories_status
              FROM $categoriestable c,
                   $categories_descriptionstable cd
              WHERE c.categories_status = '1'
                AND c.categories_id = cd.categories_id
                AND cd.categories_languages_id = '" .  intval($nLanguageID) . "'
              ORDER BY c.parent_id, c.sort_order, cd.categories_name";
      if (USE_DB_CACHE == 'true') {
        $categories_result = $dbconn->CacheExecute(3600, $sql);
      } else {
        $categories_result = $dbconn->Execute($sql);
      }
      $this->data = array(); 

      while ($categories = $categories_result->fields) {
        $this->data[$categories['parent_id']][$categories['categories_id']] = array('name' => $categories['categories_name'], 'count' => 0);

        // Move that ADOdb pointer!
        $categories_result->MoveNext();
      }

      if ($this->show_category_product_count === true) {
        $this->calculateCategoryProductCount();
      }
    }

    function setData(&$data_array) {
      if (is_array($data_array)) {
        $this->data = array();

        for ($i=0, $n=count($data_array); $i<$n; $i++) {
          $this->data[$data_array[$i]['parent_id']][$data_array[$i]['categories_id']] = array('name' => $data_array[$i]['categories_name'], 'count' => $data_array[$i]['categories_count']);
        }
      }
    }

    function buildBranch($parent_id, $level = 0) {
      $result = $this->parent_group_start_string;

      $aContents = oos_get_content();


      if (isset($this->data[$parent_id])) {
        foreach ($this->data[$parent_id] as $category_id => $category) {
          if ($this->breadcrumb_usage == true) {
            $category_link = $this->buildBreadcrumb($category_id);
          } else {
            $category_link = $category_id;
          }

          $sLink = '<a href="' . oos_href_link($aContents['shop'], 'category=' . $category_link) . '">';


          $result .= $this->child_start_string;

          if (isset($this->data[$category_id])) {
            $result .= $this->parent_start_string;
          }

          if ($level == 0) {
            $result .= $this->root_start_string;
          }

          $result .= str_repeat($this->spacer_string, $this->spacer_multiplier * $level);

          if ($this->show_image_folder === true) {
            $result .= $sLink;
            if (in_array($category_id, $this->category_array)) {
              $result .= oos_image_folder('current_folder.png', $category['name']);
            } else {
              $result .= oos_image_folder('folder.png', $category['name']);
            }
            $result .= '</a>&nbsp;';
          }

          $result .= $sLink;

          if ($this->follow_category === true) {
            if (in_array($category_id, $this->category_array)) {
              $result .= $this->category_start_string . $category['name'] . $this->category_end_string;
            } else {
              $result .= $category['name'];
            }
          } else {
            $result .= $category['name'];
          }
          $result .= '</a>';

          if ($this->show_category_product_count === true) {
            $result .= $this->category_product_count_start_string . $category['count'] . $this->category_product_count_end_string;
          }

          if ($level == 0) {
            $result .= $this->root_end_string;
          }

          if (isset($this->data[$category_id])) {
            $result .= $this->parent_end_string;
          }

          $result .= $this->child_end_string;

          if (isset($this->data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1))) {
            if ($this->follow_category === true) {
              if (in_array($category_id, $this->category_array)) {
                $result .= $this->buildBranch($category_id, $level+1);
              }
            } else {
              $result .= $this->buildBranch($category_id, $level+1);
            }
          }
        }
      }

      $result .= $this->parent_group_end_string;

      return $result;
    }


    function buildBranchArray($parent_id, $level = 0, $result = '') {
      if (empty($result)) {
        $result = array();
      }

      if (isset($this->data[$parent_id])) {
        foreach ($this->data[$parent_id] as $category_id => $category) {
          if ($this->breadcrumb_usage == true) {
            $category_link = $this->buildBreadcrumb($category_id);
          } else {
            $category_link = $category_id;
          }

          $result[] = array('id' => $category_link,
                            'title' => str_repeat($this->spacer_string, $this->spacer_multiplier * $level) . $category['name']);

          if (isset($this->data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1))) {
            if ($this->follow_category === true) {
              if (in_array($category_id, $this->category_array)) {
                $result = $this->buildBranchArray($category_id, $level+1, $result);
              }
            } else {
              $result = $this->buildBranchArray($category_id, $level+1, $result);
            }
          }
        }
      }

      return $result;
    }


    function buildBreadcrumb($category_id, $level = 0) {
      $breadcrumb = '';

      foreach ($this->data as $parent => $categories) {
        foreach ($categories as $id => $info) {
          if ($id == $category_id) {
            if ($level < 1) {
              $breadcrumb = $id;
            } else {
              $breadcrumb = $id . $this->breadcrumb_separator . $breadcrumb;
            }

            if ($parent != $this->root_category_id) {
              $breadcrumb = $this->buildBreadcrumb($parent, $level+1) . $breadcrumb;
            }
          }
        }
      }

      return $breadcrumb;
    }

    function buildTree() {
      return $this->buildBranch($this->root_category_id);
    }

    function getTree($parent_id = '') {
      return $this->buildBranchArray((empty($parent_id) ? $this->root_category_id : $parent_id));
    }

    function calculateCategoryProductCount() {
      foreach ($this->data as $parent => $categories) {
        foreach ($categories as $id => $info) {
          $this->data[$parent][$id]['count'] = $this->countCategoryProducts($id);

          $parent_category = $parent;
          while ($parent_category != $this->root_category_id) {
            foreach ($this->data as $parent_parent => $parent_categories) {
              foreach ($parent_categories as $parent_category_id => $parent_category_info) {
                if ($parent_category_id == $parent_category) {
                  $this->data[$parent_parent][$parent_category_id]['count'] += $this->data[$parent][$id]['count'];

                  $parent_category = $parent_parent;
                  break 2;
                }
              }
            }
          }
        }
      }
    }

    function countCategoryProducts($category_id) {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $productstable = $oostable['products'];
      $products_to_categoriestable = $oostable['products_to_categories'];
      $sql = "SELECT COUNT(*) AS total
              FROM $productstable p,
                   $products_to_categoriestable p2c
              WHERE p2c.categories_id = '" . intval($category_id) . "'
                AND p2c.products_id = p.products_id
                AND p.products_status >= 1";
      $count_result = $dbconn->Execute($sql);
      $count = $count_result->fields['total'];

      return $count;
    }

    function setRootCategoryID($root_category_id) {
      $this->root_category_id = $root_category_id;
    }

    function setMaximumLevel($max_level) {
      $this->max_level = $max_level;
    }

    function setRootString($root_start_string, $root_end_string) {
      $this->root_start_string = $root_start_string;
      $this->root_end_string = $root_end_string;
    }

    function setParentString($parent_start_string, $parent_end_string) {
      $this->parent_start_string = $parent_start_string;
      $this->parent_end_string = $parent_end_string;
    }

    function setParentGroupString($parent_group_start_string, $parent_group_end_string) {
      $this->parent_group_start_string = $parent_group_start_string;
      $this->parent_group_end_string = $parent_group_end_string;
    }

    function setChildString($child_start_string, $child_end_string) {
      $this->child_start_string = $child_start_string;
      $this->child_end_string = $child_end_string;
    }

    function setBreadcrumbSeparator($breadcrumb_separator) {
      $this->breadcrumb_separator = $breadcrumb_separator;
    }

    function setBreadcrumbUsage($breadcrumb_usage) {
      if ($breadcrumb_usage === true) {
        $this->breadcrumb_usage = true;
      } else {
        $this->breadcrumb_usage = false;
      }
    }

    function setSpacerString($spacer_string, $spacer_multiplier = 2) {
      $this->spacer_string = $spacer_string;
      $this->spacer_multiplier = $spacer_multiplier;
    }

    function setCategoryPath($category, $category_start_string = '', $category_end_string = '') {
      $this->follow_category = true;
      $this->category_array = explode($this->breadcrumb_separator, $category);
      $this->category_start_string = $category_start_string;
      $this->category_end_string = $category_end_string;
    }

    function setFollowCategoryPath($follow_category) {
      if ($follow_category === true) {
        $this->follow_category = true;
      } else {
        $this->follow_category = false;
      }
    }

    function setCategoryPathString($category_start_string, $category_end_string) {
      $this->category_start_string = $category_start_string;
      $this->category_end_string = $category_end_string;
    }

    function setShowCategoryProductCount($show_category_product_count) {
      if ($show_category_product_count === true) {
        $this->show_category_product_count = true;
      } else {
        $this->show_category_product_count = false;
      }
    }

    function setShowImageFolder($show_image_folder) {
      if ($show_image_folder === true) {
        $this->show_image_folder = true;
      } else {
        $this->show_image_folder = false;
      }
    }

    function setCategoryProductCountString($category_product_count_start_string, $category_product_count_end_string) {
      $this->category_product_count_start_string = $category_product_count_start_string;
      $this->category_product_count_end_string = $category_product_count_end_string;
    }
  }

