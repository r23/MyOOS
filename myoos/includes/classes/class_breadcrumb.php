<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2019 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: breadcrumb.php,v 1.3 2003/02/11 00:04:50 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );


class breadcrumb {

	/**
	 * @var array	Array of individual (linked) html strings created from crumbs
	 */
	private $links = array();


	/**
	 * Create the breadcrumb
	 */
	public function __construct() {
		$this->reset();
	}
	
	/**
	 * reset
	 */
    private function reset() {
		$this->links = array();
    }

	/**
	 * Add Link
	 */	
	public function add($title, $url = '', $icon = '') {
		$this->links[] = array('title' => $title, 'url' => $url, 'icon' => $icon );
    }
	

	/**
	 * Create a breadcrumb element string
	 *
	 * @return	string
	 */
    public function trail() {

		$link_output = '';
		
		$n = sizeof($this->links);
		for ($i=0, $n; $i<$n; $i++) {
			$link_output .= '<li typeof="v:Breadcrumb">';
			
			if ( isset( $this->links[$i]['url'] ) && ( is_string( $this->links[$i]['url'] ) && $this->links[$i]['url'] !== '' ) ) {
				$link_output .= '<a title="' . $this->links[$i]['title'] . '" href="' . $this->links[$i]['url'] . '"  rel="v:url" property="v:title">';
			} else {
				$link_output .= '<span property="v:title">';
			}

			if (isset($this->links[$i]['icon']) && !empty($this->links[$i]['icon']))	{
				$link_output .= '<i class="fa fa-' . $this->links[$i]['icon'] . '" aria-hidden="true"></i>';
			} 

			$link_output .= $this->links[$i]['title'];
		
			
			if (isset($this->links[$i]['url']) && ( is_string( $this->links[$i]['url'] ) && $this->links[$i]['url'] !== '' ) ) {
				$link_output .= '</a>';
			} else {
				$link_output .= '</span>';
			}

			$link_output .= '</li>';
		}
				
		return $link_output;
    }  
} 
