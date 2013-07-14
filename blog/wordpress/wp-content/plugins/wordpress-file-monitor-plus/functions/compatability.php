<?php
/*  Copyright 2012  Scott Cariss  (email : scott@l3rady.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Bring fnmatch compatability to non unix OS
if ( ! function_exists( 'fnmatch' ) )
{
    define( 'FNM_PATHNAME', 1 );
    define( 'FNM_NOESCAPE', 2 );
    define( 'FNM_PERIOD', 4 );
    define( 'FNM_CASEFOLD', 16 );

    function fnmatch( $pattern, $string, $flags = 0 )
    {
        return pcre_fnmatch( $pattern, $string, $flags );
    }
}

function pcre_fnmatch( $pattern, $string, $flags = 0 )
{
    $modifiers = null;
    $transforms = array(
        '\*'    => '.*',
        '\?'    => '.',
        '\[\!'    => '[^',
        '\['    => '[',
        '\]'    => ']',
        '\.'    => '\.',
        '\\'    => '\\\\'
    );

    // Forward slash in string must be in pattern:
    if ( $flags & FNM_PATHNAME )
        $transforms['\*'] = '[^/]*';

    // Back slash should not be escaped:
    if ( $flags & FNM_NOESCAPE )
        unset($transforms['\\']);

    // Perform case insensitive match:
    if ( $flags & FNM_CASEFOLD )
        $modifiers .= 'i';

    // Period at start must be the same as pattern:
    if ( $flags & FNM_PERIOD )
    {
        if ( strpos( $string, '.' ) === 0 && strpos( $pattern, '.' ) !== 0 )
            return false;
    }


    $pattern = '#^'
        . strtr( preg_quote( $pattern, '#' ), $transforms )
        . '$#'
        . $modifiers;

    return (boolean) preg_match( $pattern, $string );
}