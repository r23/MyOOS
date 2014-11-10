<?php
/* ----------------------------------------------------------------------
   $Id: oos_version.php,v 1.4 2008/08/15 16:28:30 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

 /**
  * This file is subject to change for each release
  *
  * @package    kernel
  * @access     public
  *
  * @author     r23, <info@r23.de>
  * @copyright  (C) r23, {@link http://www.oos-shop.de/}
  * @license    http://www.gnu.org/licenses/gpl.html
  * @version    $Revision: 1.4 $
  */


 /**
  * Naming, version & release date
  */
  define('OOS_NAME', 'OOS [OSIS Online Shop]');

 /**
  * Additional software subname string
  */
  define('OOS_SUBNAME', 'The OOS Project - Community Made Shopping!');

 /**
  * Major software version
  */
  define('OOS_VERSION_MAJOR', '1');

 /**
  * Minor software version
  */
  define('OOS_VERSION_MINOR', '7');

 /**
  * Micro software version
  */
  define('OOS_VERSION_MICRO', '9');

 /**
  * Software version patch
  */
  define('OOS_VERSION_PATCH', '');

 /**
  * Software release version
  */
  define('OOS_RELEASE_NAME', '');

 /**
  * Software build (full) date
  */
  define('OOS_VERSION_DATE', 'August 15, 2008');



 // --- Do not change from here -----------------------------------------

 /**
  * Software build (unix timestamp) date
  */
  @define('OOS_VERSION_STAMP', strtotime(OOS_VERSION_DATE));

 /**
  * Complete software version string
  */
  define('OOS_VERSION', OOS_VERSION_MAJOR . '.'
                             . OOS_VERSION_MINOR . '.'
                             . OOS_VERSION_MICRO
                             . OOS_VERSION_PATCH);

 /**
  * Complete software name string
  */
  define('OOS_FULL_NAME', OOS_NAME . ' ' . OOS_VERSION);

 /**
  * The URL of the home of this software
  */
  define('OOS_HOME', 'http://www.oos-shop.de/');

?>
