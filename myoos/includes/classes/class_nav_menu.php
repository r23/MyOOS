<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
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

class nav_menu
{
    public $root_category_id = 0;
    public $max_level = 0;
    public $count = 0;
    public $count_col = 0;
    public $submenu = 0;
    public $data = [];
    public $root_start_string = '<li class="main-nav-item main-nav-expanded">';
    public $root_end_string = '</li>';
    public $parent_start_string = '<li>';
    public $parent_end_string = '</li>';
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
    public $banner_image = '';
    public $banner_link = '';
    public $banner_name = '';


    public function __construct()
    {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $nLanguageID = isset($_SESSION['language_id']) ? intval($_SESSION['language_id']) : DEFAULT_LANGUAGE_ID;

        $categoriestable = $oostable['categories'];
        $categories_descriptionstable = $oostable['categories_description'];
        $sql = "SELECT c.categories_id, cd.categories_name, c.parent_id, c.categories_banner, c.color, c.menu_type, c.categories_status
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
            $this->data[$categories['parent_id']][$categories['categories_id']] = array('name' => $categories['categories_name'],
                                                                                        'banner' => $categories['categories_banner'],
                                                                                        'color' => $categories['color'],
                                                                                        'menu_type' => $categories['menu_type'],
                                                                                        'count' => 0);


            // Move that ADOdb pointer!
            $categories_result->MoveNext();
        }
    }

    public function buildBranch($parent_id, $level = 0, $submenu = 0)
    {
        $aContents = oos_get_content();

        if (isset($this->data[$parent_id])) {
            foreach ($this->data[$parent_id] as $category_id => $category) {
                $this->count++;

                if ($this->breadcrumb_usage == true) {
                    $category_link = $this->buildBreadcrumb($category_id);
                } else {
                    $category_link = $category_id;
                }

                $sLink = '<a href="' . oos_href_link($aContents['shop'], 'category=' . $category_link) . '" title="' . $category['name'] . '">';

                if ($category['banner'] != '') {
                    $this->banner_image = OOS_IMAGES . 'banners/large/' . $category['banner'];
                    $this->banner_link = oos_href_link($aContents['shop'], 'category=' . $category_link);
                    $this->banner_name = $category['name'];
                }



                switch ($level) {
                case 0:
                    $result .= $this->root_start_string;
                    break;

                case 1:
                    if ($submenu == 0) {
                        $submenu++;
                        $this->count = 0;
                        $this->submenu = 1;
                        $this->count_col++;

                        $result .= '<div class="main-nav-submenu">
											<div class="row"><div class="col-md-3"><ul class="list-unstyled"><li>';
                    } else {
                        $this->count+2;
                        $result .=  '<ul class="list-unstyled"><li>';
                    }
                    break;

                case 2:
                    $result .= $this->parent_start_string . "\n";
                    break;
                }


                $result .= $sLink;

                if ($level == 0) {
                    $result .= '<i class="fa fa-circle-o-notch ' . $category['color'] . '" aria-hidden="true"></i>';
                }

                switch ($category['menu_type']) {
                case 'NEW':
                    $result .= '<span class="badge badge-danger float-right">NEW</span>';
                    break;

                case 'PROMO':
                    $result .= '<span class="badge badge-success float-right">PROMO</span>';
                    break;
                }


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

                if ($level == 1) {
                    $result .= '</li>';
                }

                if ($level == 2) {
                    if ($this->count > 8) {
                        $this->count = 0;
                        $this->count_col++;

                        $result .= '</li></ul></div><div class="col-md-3">' . "\n";
                    }
                }

                if (isset($this->data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1))) {
                    if ($this->follow_cpath === true) {
                        if (in_array($category_id, $this->cpath_array)) {
                            $result .= $this->buildBranch($category_id, $level+1);
                        }
                    } else {
                        $result .= $this->buildBranch($category_id, $level+1, $submenu);
                    }
                }

                switch ($level) {
                case 0:
                    if ($this->submenu > 0) {
                        if (($this->banner_image != '') && ($this->count_col <= 3)) {
                            if ($this->count_col == 1) {
                                $result .= '</div><div class="col-md-9 text-right hidden-sm-down">';
                            } elseif ($this->count_col == 2) {
                                $result .= '</div><div class="col-md-6 text-right hidden-sm-down">';
                            } elseif ($this->count_col == 3) {
                                $result .= '</div><div class="col-md-6 text-right hidden-sm-down">';
                            }
                            $result .= '<a class="mt-15 block" href="'. $this->banner_link . '">
												<img class="img-fluid" src="' . $this->banner_image . '" alt="' . $this->banner_name .'">
											</a>';
                        }

                        $result .=  '</div></div></div>'  . "\n";
                    }
                    $this->submenu = 0;

                    $result .= $this->root_end_string;
                    break;

                case 1:
                    if ($this->count > 0) {
                        $result .=     '</ul>';
                    }
                    break;

                case 2:
                    if ($this->count > 0) {
                        $result .=  $this->parent_end_string;
                    }
                    break;
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
                        $breadcrumb = $this->buildBreadcrumb($parent, $level+1) . $breadcrumb;
                    }
                }
            }
        }

        return $breadcrumb;
    }

    public function build()
    {
        return $this->buildBranch($this->root_category_id);
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

    public function setCategoryPath($cpath, $cpath_start_string = '', $cpath_end_string = '')
    {
        $this->follow_cpath = true;
        $this->cpath_array = explode($this->breadcrumb_separator, $cpath);
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

    public function setCategoryProductCountString($category_product_count_start_string, $category_product_count_end_string)
    {
        $this->category_product_count_start_string = $category_product_count_start_string;
        $this->category_product_count_end_string = $category_product_count_end_string;
    }
}
