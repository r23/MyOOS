<?php
/* ----------------------------------------------------------------------
   $Id: block_change_template.php,v 1.1 2007/06/07 11:55:41 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: mod_templatechooser.php,v 1.14 2004/08/26 21:29:11 rcastley
   ----------------------------------------------------------------------
   Mambo is Free Software

   2000 - 2004 Miro International Pty Ltd
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  $sLocaleDir = $oSmarty->template_dir;
  $aSkins = array();

  if (is_dir($sLocaleDir)) {
    if ($dh = opendir($sLocaleDir)) {
      while (($file = readdir($dh)) !== false) {
        if ($file == '.' || $file == '..' || $file == 'CVS' || $file == 'default' || filetype($sLocaleDir . $file) == 'file' ) continue;
        if (filetype(realpath($sLocaleDir . $file)) == 'dir') {
          $aSkins[] = $file;
        }
      }
      closedir($dh);
    }
  }

  sort($aSkins);

  $oSmarty->assign(
      array(
          'skins' => $aSkins,
          'block_heading_change_template' => $block_heading
     )
  );

?>
