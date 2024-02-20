<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: category_tree.php,v 1.2, 2004/10/26 20:07:09 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2001 - 2004 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

class oosCategoryTree
{
    public $root_category_id = 0;
    public $max_level = 0;
    public $data = [];
    public $root_start_string = '';
    public $root_end_string = '';
    public $parent_start_string = '';
    public $parent_end_string = '';
    public $parent_group_start_string = '<ul>';
    public $parent_group_end_string = '</ul>';
    public $child_start_string = '<li>';
    public $child_end_string = '</li>';
    public $breadcrumb_separator = '_';
    public $breadcrumb_usage = true;
    public $spacer_string = '';
    public $spacer_multiplier = 1;
    public $follow_cpath = false;
    public $cpath_array = [];
    public $cpath_start_string = '';
    public $cpath_end_string = '';
    public $show_category_product_count = false;
    public $category_product_count_start_string = '&nbsp;(';
    public $category_product_count_end_string = ')';

    public function __construct()
    {
        if (SHOW_COUNTS == 'true') {
            $this->show_category_product_count = true;
        }

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        $nLanguageID = intval($_SESSION['language_id'] ?? DEFAULT_LANGUAGE_ID);

        $categoriestable = $oostable['categories'];
        $categories_descriptionstable = $oostable['categories_description'];
        $sql = "SELECT c.categories_id, cd.categories_name, c.parent_id, c.categories_status
              FROM $categoriestable c,
                   $categories_descriptionstable cd
              WHERE c.categories_status = '2'
                AND c.categories_id = cd.categories_id
                AND cd.categories_languages_id = '" .  intval($nLanguageID) . "'
              ORDER BY c.parent_id, c.sort_order, cd.categories_name";
        if (USE_CACHE == 'true') {
            $categories_result = $dbconn->CacheExecute(3600, $sql);
        } else {
            $categories_result = $dbconn->Execute($sql);
        }
        $this->data = [];

        while ($categories = $categories_result->fields) {
            $this->data[$categories['parent_id']][$categories['categories_id']] = ['name' => $categories['categories_name'], 'count' => 0];

            // Move that ADOdb pointer!
            $categories_result->MoveNext();
        }

        if ($this->show_category_product_count === true) {
            $this->calculateCategoryProductCount();
        }
    }

    public function setData(&$data_array)
    {
        if (is_array($data_array)) {
            $this->data = [];

            $n = is_countable($data_array) ? count($data_array) : 0;
            for ($i = 0, $n; $i < $n; $i++) {
                $this->data[$data_array[$i]['parent_id']][$data_array[$i]['categories_id']] = ['name' => $data_array[$i]['categories_name'], 'count' => $data_array[$i]['categories_count']];
            }
        }
    }

    public function buildBranch($parent_id, $level = 0)
    {
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

                $result .= str_repeat((string) $this->spacer_string, $this->spacer_multiplier * $level);

                $result .= $sLink;

                if ($this->follow_cpath === true) {
                    if (in_array($category_id, $this->cpath_array)) {
                        $result .= $this->cpath_start_string . $category['name'] . $this->cpath_end_string;
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

                if (isset($this->data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level + 1))) {
                    if ($this->follow_cpath === true) {
                        if (in_array($category_id, $this->cpath_array)) {
                            $result .= $this->buildBranch($category_id, $level + 1);
                        }
                    } else {
                        $result .= $this->buildBranch($category_id, $level + 1);
                    }
                }
            }
        }

        $result .= $this->parent_group_end_string;

        return $result;
    }


    public function buildBranchArray($parent_id, $level = 0, $result = '')
    {
        if (empty($result)) {
            $result = [];
        }

        if (isset($this->data[$parent_id])) {
            foreach ($this->data[$parent_id] as $category_id => $category) {
                if ($this->breadcrumb_usage == true) {
                    $category_link = $this->buildBreadcrumb($category_id);
                } else {
                    $category_link = $category_id;
                }

                $result[] = ['id' => $category_link, 'title' => str_repeat((string) $this->spacer_string, $this->spacer_multiplier * $level) . $category['name']];

                if (isset($this->data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level + 1))) {
                    if ($this->follow_cpath === true) {
                        if (in_array($category_id, $this->cpath_array)) {
                            $result = $this->buildBranchArray($category_id, $level + 1, $result);
                        }
                    } else {
                        $result = $this->buildBranchArray($category_id, $level + 1, $result);
                    }
                }
            }
        }

        return $result;
    }


    public function buildBreadcrumb($category_id, $level = 0)
    {
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
                        $breadcrumb = $this->buildBreadcrumb($parent, $level + 1) . $breadcrumb;
                    }
                }
            }
        }

        return $breadcrumb;
    }

    public function buildTree()
    {
        return $this->buildBranch($this->root_category_id);
    }

    public function getTree($parent_id = '')
    {
        return $this->buildBranchArray((empty($parent_id) ? $this->root_category_id : $parent_id));
    }

    public function calculateCategoryProductCount()
    {
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

    public function countCategoryProducts($category_id)
    {

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        $productstable = $oostable['products'];
        $products_to_categoriestable = $oostable['products_to_categories'];
        $sql = "SELECT COUNT(*) AS total
              FROM $productstable p,
                   $products_to_categoriestable p2c
              WHERE p2c.categories_id = '" . intval($category_id) . "'
                AND p2c.products_id = p.products_id
                AND p.products_setting >= 1";
        $count_result = $dbconn->Execute($sql);
        $count = $count_result->fields['total'];

        return $count;
    }

    public function setRootCategoryID($root_category_id)
    {
        $this->root_category_id = $root_category_id;
    }

    public function setMaximumLevel($max_level)
    {
        $this->max_level = $max_level;
    }

    public function setRootString($root_start_string, $root_end_string)
    {
        $this->root_start_string = $root_start_string;
        $this->root_end_string = $root_end_string;
    }

    public function setParentString($parent_start_string, $parent_end_string)
    {
        $this->parent_start_string = $parent_start_string;
        $this->parent_end_string = $parent_end_string;
    }

    public function setParentGroupString($parent_group_start_string, $parent_group_end_string)
    {
        $this->parent_group_start_string = $parent_group_start_string;
        $this->parent_group_end_string = $parent_group_end_string;
    }

    public function setChildString($child_start_string, $child_end_string)
    {
        $this->child_start_string = $child_start_string;
        $this->child_end_string = $child_end_string;
    }

    public function setBreadcrumbSeparator($breadcrumb_separator)
    {
        $this->breadcrumb_separator = $breadcrumb_separator;
    }

    public function setBreadcrumbUsage($breadcrumb_usage)
    {
        if ($breadcrumb_usage === true) {
            $this->breadcrumb_usage = true;
        } else {
            $this->breadcrumb_usage = false;
        }
    }

    public function setSpacerString($spacer_string, $spacer_multiplier = 2)
    {
        $this->spacer_string = $spacer_string;
        $this->spacer_multiplier = $spacer_multiplier;
    }

    public function setCategoryPath($cpath, $cpath_start_string = '', $cpath_end_string = '')
    {
        $this->follow_cpath = true;
        $this->cpath_array = explode($this->breadcrumb_separator, (string) $cpath);
        $this->cpath_start_string = $cpath_start_string;
        $this->cpath_end_string = $cpath_end_string;
    }

    public function setFollowCategoryPath($follow_cpath)
    {
        if ($follow_cpath === true) {
            $this->follow_cpath = true;
        } else {
            $this->follow_cpath = false;
        }
    }

    public function setCategoryPathString($cpath_start_string, $cpath_end_string)
    {
        $this->cpath_start_string = $cpath_start_string;
        $this->cpath_end_string = $cpath_end_string;
    }

    public function setShowCategoryProductCount($show_category_product_count)
    {
        if ($show_category_product_count === true) {
            $this->show_category_product_count = true;
        } else {
            $this->show_category_product_count = false;
        }
    }

    public function setCategoryProductCountString($category_product_count_start_string, $category_product_count_end_string)
    {
        $this->category_product_count_start_string = $category_product_count_start_string;
        $this->category_product_count_end_string = $category_product_count_end_string;
    }
}
