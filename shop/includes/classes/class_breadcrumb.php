<?php
/* ----------------------------------------------------------------------
   $Id: class_breadcrumb.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
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

class breadcrumb
{
    var $_trail;

    function breadcrumb()
	{
		$this->reset();
    }

    function reset()
	{
		$this->_trail = array();
    }

    function add($title, $link = '', $icon = '')
	{
		$this->_trail[] = array('title' => $title, 'link' => $link, 'icon' => $icon );
    }

    function trail($separator = ' - ')
	{
		$trail_string = '<ol class="breadcrumb pull-right hidden-xs">';

		$n = sizeof($this->_trail);
		for ($i=0, $n; $i<$n; $i++)
		{
			if (isset($this->_trail[$i]['link']) && !empty($this->_trail[$i]['link'])) {
				$trail_string .= '<li><a title="' . $this->_trail[$i]['title'] . '" href="' . $this->_trail[$i]['link'] . '">';
			} else {
				$trail_string .= '<li class="active">';
			}
			
			
			if (isset($this->_trail[$i]['icon']) && !empty($this->_trail[$i]['icon']))	{
				$trail_string .= '<i class="fa fa-' . $this->_trail[$i]['icon'] . '"></i>';
			} else {
				$trail_string .= $this->_trail[$i]['title'];
			}
			
			if (isset($this->_trail[$i]['link']) && !empty($this->_trail[$i]['link'])) {
				$trail_string .= '</a>';
			} 
			
			if (($i+1) < $n) $trail_string .= $separator;
			
			$trail_string .= '</li>';
		}

		$trail_string .= '</ol>';
		return $trail_string;
    }

}

  
