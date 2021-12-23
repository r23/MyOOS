<?php
/* ----------------------------------------------------------------------

   MyOOS [Dumper]
   http://www.oos-shop.de/

   Copyright (c) 2021 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   MySqlDumper
   http://www.mysqldumper.de

   Copyright (C)2004-2011 Daniel Schlichtholz (admin@mysqldumper.de)
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', true);

if (!@ob_start("ob_gzhandler")) @ob_start();

include ( './inc/header.php' );
include ( MOD_PATH.'language/'.$config['language'].'/lang.php' );
include ( MOD_PATH.'language/'.$config['language'].'/lang_help.php' );
echo MODHeader(0);
echo headline($lang['L_CREDITS']);
readfile ( MOD_PATH.'language/'.$config['language'].'/help.html' );
echo MODFooter();
ob_end_flush();
