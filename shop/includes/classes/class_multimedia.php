<?php
/* ----------------------------------------------------------------------
   $Id: class_multimedia.php,v 1.1 2007/06/07 16:06:31 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  require_once 'thirdparty/getid3/getid3/getid3.php';

  /**
   * Template engine
   *
   * @package  Smarty
   */
   class MM extends getID3  {

    /**
     * Constructor
     */
     function MM() {

       $this->getID3();

     }
   }

?>