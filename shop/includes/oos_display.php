<?php
/* ----------------------------------------------------------------------
   $Id: oos_display.php,v 1.1 2007/06/07 16:06:31 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  
 
  
  if (isset($option) && ($option == 'print')) {
    $smarty->display('default/print.html');
  } else {


// display the template
    $smarty->display($sTheme.'/theme.html');
  }
