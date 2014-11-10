<?php

/**
 * Project:     SmartyMenu: CSS Menus for the Smarty Template Engine
 * File:        SmartyMenu.class.php
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @link http://www.phpinsider.com/php/code/SmartyPaginate/
 * @copyright 2001-2004-2005 New Digital Group, Inc. of Lincoln, Inc.
 * @author Monte Ohrt <monte at newdigitalgroup dot com>
 * @package SmartyMenu
 * @version 1.1
 */


class SmartyMenu {

    /**
     * Class Constructor
     */
    function SmartyMenu() { }

    /**
     * save the menu in the PHP session
     *
     * @param string $name the menu session name
     * @param string $menu the menu variable
     */
    function saveMenu($name, $menu) {
        $_SESSION['SMARTYMENU'][$name] = $menu;
    }

    /**
     * load the menu from the PHP session
     *
     * @param string $name the menu session name
     */
    function loadMenu($name) {
        return isset($_SESSION['SMARTYMENU'][$name]) ? $_SESSION['SMARTYMENU'][$name] : false;
    }

    /**
     * reset the menu in the PHP session
     *
     * @param string $name the menu session name
     */
    function resetMenu($name) {
        unset($_SESSION['SMARTYMENU'][$name]);
    }

    /**
     * initialize the menu
     *
     * @param string $menu the menu variable
     */
    function initMenu(&$menu) {
        $menu = array();
    }

    /**
     * initialize the menu
     *
     * @param array $menu the menu array
     * @param array $item the item array
     */
    function addMenuItem(&$menu, $item) {
        $menu[] = $item;
    }

    /**
     * initialize the item
     *
     * @param string $item the item variable
     */
    function initItem(&$item) {
        $item = array();
    }

    /**
     * set the item text
     *
     * @param string $text the item text
     */
    function setItemText(&$item, $text) {
        $item['text'] = $text;
    }

    /**
     * set the item href link
     *
     * @param string $link the link text
     */
    function setItemLink(&$item, $link) {
        $item['link'] = $link;
    }

    /**
     * set the item CSS class
     *
     * @param string $class the class text
     */
    function setItemClass(&$item, $class) {
        $item['class'] = $class;
    }

    /**
     * set the item submenu
     *
     * @param array $item the item array
     * @param array $menu the submenu array
     */
    function setItemSubmenu(&$item, $submenu) {
        $item['submenu'] = $submenu;
    }

}

?>
