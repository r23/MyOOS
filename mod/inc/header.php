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

$mod_path=realpath(dirname(__FILE__) . '/../') . '/';
if (!defined('MOD_PATH')) define('MOD_PATH',$mod_path);
session_name('MyOOSDumperID');
session_start();
if (!isset($download))
{
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0",false);
	header("Pragma: no-cache");
}
include ( MOD_PATH . 'inc/functions.php' );
include ( MOD_PATH . 'inc/mysqli.php' );
if (!defined('MOD_VERSION')) die('No direct access.');
if (!file_exists($config['files']['parameter'])) $error=TestWorkDir();
read_config($config['config_file']);
include ( MOD_PATH . 'language/lang_list.php' );
if (!isset($databases['db_selected_index'])) $databases['db_selected_index']=0;
SelectDB($databases['db_selected_index']);
$config['theme'] = isset($config['theme']) ? $config['theme'] : 'mod';
$config['files']['iconpath']='./css/' . $config['theme'] . '/icons/';
if (isset($error)) echo $error;
