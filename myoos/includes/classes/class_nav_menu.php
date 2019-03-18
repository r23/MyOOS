<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2019 by the MyOOS Development Team.
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

class nav_menu {
	var $root_category_id = 0,
        $max_level = 0,
        $data = array(),
        $root_start_string = '',
        $root_end_string = '',
        $parent_start_string = '',
        $parent_end_string = '',
        $parent_group_start_string = '<li class="main-nav-item main-nav-expanded">',
        $parent_group_end_string = '</li>',
        $child_start_string = '',
        $child_end_string = '',
        $spacer_string = '',
        $spacer_multiplier = 1,
        $follow_cpath = TRUE,
        $cpath_array = array(),
        $cpath_start_string = '',
        $cpath_end_string = '';

	public function __construct() {

		// Get database information
		$dbconn =& oosDBGetConn();
		$oostable =& oosDBGetTables();

		$nLanguageID = isset($_SESSION['language_id']) ? intval( $_SESSION['language_id'] ) : DEFAULT_LANGUAGE_ID;

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
		$this->data = array(); 

		while ($categories = $categories_result->fields) {
			$this->data[$categories['parent_id']][$categories['categories_id']] = array('name' => $categories['categories_name'], 'count' => 0);

			// Move that ADOdb pointer!
			$categories_result->MoveNext();
		}
    }

    public function buildBranch($parent_id, $level = 0) {
		$result = $this->parent_group_start_string;

		$aContents = oos_get_content();

		if (isset($this->data[$parent_id])) {
			foreach ($this->data[$parent_id] as $category_id => $category) {
				$sLink = '<a href="' . oos_href_link($aContents['shop'], 'category=' . $category_id) . '" title="' . $category['name'] . '">';

				$result .= $this->child_start_string;

				if (isset($this->data[$category_id])) {
					$result .= $this->parent_start_string;
				}

				if ( $level === 0 ) {
					$result .= $this->root_start_string;
				}

				$result .= str_repeat($this->spacer_string, $this->spacer_multiplier * $level);

				$result .= $sLink;
				
				if ( $level === 0 ) {
					// todo add color
					$result .= '<i class="fa fa-circle-o-notch" aria-hidden="true"></i>';
				}

				if ($this->follow_cpath === TRUE) {
					if (in_array($category_id, $this->cpath_array)) {
						$result .= $this->cpath_start_string . $category['name'] . $this->cpath_end_string;
					} else {
						$result .= $category['name'];
					}
				} else {
					$result .= $category['name'];
				}
				$result .= '</a>';


				if ( $level === 0 ) {
					$result .= $this->root_end_string;
				}

				if (isset($this->data[$category_id])) {
					$result .= $this->parent_end_string;
				}

				$result .= $this->child_end_string;

				if (isset($this->data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1))) {
					if ($this->follow_cpath === TRUE) {
						if (in_array($category_id, $this->cpath_array)) {
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


	public function buildBranchArray($parent_id, $level = 0, $result = '') {
		if (empty($result)) {
			$result = array();
		}

		if (isset($this->data[$parent_id])) {
			foreach ($this->data[$parent_id] as $category_id => $category) {
				$category_link = $category_id;

				$result[] = array('id' => $category_link,
									'title' => str_repeat($this->spacer_string, $this->spacer_multiplier * $level) . $category['name']);

				if (isset($this->data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1))) {
					if ($this->follow_cpath === TRUE) {
						if (in_array($category_id, $this->cpath_array)) {
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


    public function build() {
		return $this->buildBranch($this->root_category_id);
    }

    public function getTree($parent_id = '') {
		return $this->buildBranchArray((empty($parent_id) ? $this->root_category_id : $parent_id));
    }


    public function setRootCategoryID($root_category_id) {
		$this->root_category_id = $root_category_id;
    }

    public function setMaximumLevel($max_level) {
		$this->max_level = $max_level;
    }

    public function setRootString($root_start_string, $root_end_string) {
		$this->root_start_string = $root_start_string;
		$this->root_end_string = $root_end_string;
    }

    public function setParentString($parent_start_string, $parent_end_string) {
		$this->parent_start_string = $parent_start_string;
		$this->parent_end_string = $parent_end_string;
    }

    public function setParentGroupString($parent_group_start_string, $parent_group_end_string) {
      $this->parent_group_start_string = $parent_group_start_string;
      $this->parent_group_end_string = $parent_group_end_string;
    }

    public function setChildString($child_start_string, $child_end_string) {
		$this->child_start_string = $child_start_string;
		$this->child_end_string = $child_end_string;
    }

    public function setSpacerString($spacer_string, $spacer_multiplier = 2) {
		$this->spacer_string = $spacer_string;
		$this->spacer_multiplier = $spacer_multiplier;
    }

    public function setCategoryPath($cpath, $cpath_start_string = '', $cpath_end_string = '') {
		$this->follow_cpath = TRUE;
		$this->cpath_start_string = $cpath_start_string;
		$this->cpath_end_string = $cpath_end_string;
    }

    public function setFollowCategoryPath($follow_cpath) {
		if ($follow_cpath === TRUE) {
			$this->follow_cpath = TRUE;
		} else {
			$this->follow_cpath = FALSE;
		}
    }

	public function setCategoryPathString($cpath_start_string, $cpath_end_string) {
		$this->cpath_start_string = $cpath_start_string;
		$this->cpath_end_string = $cpath_end_string;
    }

	public function setShowCategoryProductCount($show_category_product_count) {
		if ($show_category_product_count === TRUE) {
			$this->show_category_product_count = TRUE;
		} else {
			$this->show_category_product_count = FALSE;
		}
    }

	public function setCategoryProductCountString($category_product_count_start_string, $category_product_count_end_string) {
		$this->category_product_count_start_string = $category_product_count_start_string;
		$this->category_product_count_end_string = $category_product_count_end_string;
	}
}

