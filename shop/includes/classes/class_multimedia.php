<?php
/* ----------------------------------------------------------------------
   $Id: class_multimedia.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

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