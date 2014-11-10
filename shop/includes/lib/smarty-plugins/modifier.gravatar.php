<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty gravatar plugin
 *
 * Type:     modifier<br>
 * Name:     gravatar<br>
 * Author:   Matt Schinckel<br>
 *           mailto:matt@schinckel.net<br>
 *           aim:mschinckel<br>
 *           http://schinckel.net<br>
 * Purpose:  convert email address to gravatar
 * @param string
 * @return string
 */
function smarty_modifier_gravatar($email, $default=false, $size=false, $rating=false, $border=false)
{

    $gravurl = "<img src='http://www.gravatar.com/avatar.php?gravatar_id=".md5($email);
    if ($default)
    {
        $gravurl .= "&amp;default=".urlencode($default);
    }
    if ($size)
    {
        $gravurl .= "&amp;size=".$size;
    }
    if ($rating)
    {
        $gravurl .= "&amp;rating=".$rating;
    }
    if ($border)
    {
        $gravurl .= "&amp;border=".$border;
    }
    return $gravurl. "' alt='Gravatar Image' />";
}

?>