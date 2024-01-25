<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: breadcrumb.php,v 1.3 2003/02/11 00:04:50 hpdl
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


class breadcrumb
{
    /**
     * @var array    Array of individual (linked) html strings created from crumbs
     */
    private array $links = [];


    /**
     * Create the breadcrumb
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * reset
     */
    private function reset()
    {
        $this->links = [];
    }

    /**
     * Add Link
     */
    public function add($title, $url = '', $icon = '')
    {
        $this->links[] = ['title' => $title, 'url' => $url, 'icon' => $icon];
    }


    /**
     * Create a breadcrumb element string
     *
     * @return string
     */
    public function trail()
    {
        $link_output = '';

        $n = sizeof($this->links);

        if ($n > 1) {
            $link_output .= '<ol class="breadcrumb">';

            for ($i = 0, $n; $i < $n; $i++) {
                $link_output .= '<li>';


                $link_output .= ($i == 0) ? '<span vocab="https://schema.org/" typeof="BreadcrumbList">' : '<span property="itemListElement" typeof="ListItem">';

                if (isset($this->links[$i]['url']) && (is_string($this->links[$i]['url']) && $this->links[$i]['url'] !== '')) {
                    $link_output .= '<a property="item" typeof="WebPage"  title="' . $this->links[$i]['title'] . '" href="' . $this->links[$i]['url'] . '">';
                }


                if (isset($this->links[$i]['icon']) && !empty($this->links[$i]['icon'])) {
                    $link_output .= '<i class="fa fa-' . $this->links[$i]['icon'] . '" aria-hidden="true"></i>';
                }
                $link_output .= '<span property="name">'. $this->links[$i]['title'] .'</span>';


                if (isset($this->links[$i]['url']) && (is_string($this->links[$i]['url']) && $this->links[$i]['url'] !== '')) {
                    $link_output .= '</a>';
                }

                // for php 7.2
                $nCount = $i + 1;
                $link_output .= '<meta property="position" content="' . $nCount . '"></span>';

                $link_output .= '</span>';
                $link_output .= '</li>';
            }

            $link_output .= '</ol>';
        }

        return $link_output;
    }
}
