<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   Id: pnAPI.php,v 1.41 2003/07/12 21:44:40 markwest Exp
   ----------------------------------------------------------------------
   PostNuke Content Management System
   Copyright (C) 2001 by the Post-Nuke Development Team.
   http://www.postnuke.com/
   ----------------------------------------------------------------------
   LICENSE

   This program is free software; you can redistribute it and/or
   modify it under the terms of the GNU General Public License (GPL)
   as published by the Free Software Foundation; either version 2
   of the License, or (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   To read the license please visit http://www.gnu.org/copyleft/gpl.html
   ----------------------------------------------------------------------
   Original Author of file: Jim McDonald
   Purpose of file: The PostNuke API
   ----------------------------------------------------------------------

 /**
 * security
 *
 * @link    http://www.postnuke.com/
 * @package security
 * @version $Revision: 1.2 $ - changed by $Author: r23 $ on $Date: 2008/08/15 16:28:30 $
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

/**
 * Protects better diverse attempts of Cross-Site Scripting
 * attacks, thanks to webmedic, Timax, larsneo.
 *
 * Lets validate the current php version and set globals
 * accordingly.
 * Do not change this value unless you know what you are
 * doing you have been warned!
 */
function oos_secure_input()
{
    $aContents = oos_get_content();

    // Cross-Site Scripting attack defense - Sent by larsneo
    // some syntax checking against injected javascript
    // extended by Neo

    /**
     * Lets now sanitize the GET vars
     */
    if (count($_GET) > 0) {
        foreach ($_GET as $secvalue) {
            if (!is_array($secvalue)) {
                if ((preg_match("/<[^>]*script*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/.*[[:space:]](or|and)[[:space:]].*(=|like).*/i", (string) $secvalue))
                    || (preg_match("/<[^>]*object*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*iframe*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*applet*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*meta*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*style*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*form*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*window.*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*alert*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*img*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*document.*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*cookie*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/\"/i", (string) $secvalue))
                ) {
                    oos_redirect(oos_href_link($aContents['home']));
                }
            }
        }
    }


    /**
     * Lets now sanitize the POST vars
     */
    if (count($_POST) > 0) {
        foreach ($_POST as $secvalue) {
            if (!is_array($secvalue)) {
                if ((preg_match("/<[^>]*script*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*object*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*iframe*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*applet*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*window.*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*alert*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*document.*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*cookie*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*meta*\"?[^>]*>/i", (string) $secvalue))
                ) {
                    oos_redirect(oos_href_link($aContents['home']));
                }
            }
        }
    }


    /**
     * Lets now sanitize the COOKIE vars
     */
    if (count($_COOKIE) > 0) {
        foreach ($_COOKIE as $secvalue) {
            if (!is_array($secvalue)) {
                if ((preg_match("/<[^>]*script*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/.*[[:space:]](or|and)[[:space:]].*(=|like).*/i", (string) $secvalue))
                    || (preg_match("/<[^>]*object*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*iframe*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*applet*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*meta*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*style*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*form*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*window.*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*alert*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*document.*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*cookie*\"?[^>]*>/i", (string) $secvalue))
                    || (preg_match("/<[^>]*img*\"?[^>]*>/i", (string) $secvalue))
                ) {
                    oos_redirect(oos_href_link($aContents['home']));
                }
            }
        }
    }
}
