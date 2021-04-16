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

include_once ('./inc/header.php');
include_once ('./inc/runtime.php');
include_once ('./language/'.$config['language'].'/lang_main.php');
include ('./inc/template.php');

$action=(isset($_GET['action'])) ? $_GET['action'] : 'status';

if ($action=='phpinfo')
{
	// output phpinfo
	echo '<p align="center"><a href="main.php">&lt;&lt; Home</a></p>';
	phpinfo();
	echo '<p align="center"><a href="main.php">&lt;&lt; Home</a></p>';
	exit();
}

if (isset($_POST['htaccess'])||$action=='schutz') include ('./inc/home/protection_create.php');
if ($action=='edithtaccess') include ('./inc/home/protection_edit.php');
if ($action=='deletehtaccess') include ('./inc/home/protection_delete.php');

// Output headnavi
$tpl=new MODTemplate();
$tpl->set_filenames(array(
	'show' => 'tpl/home/headnavi.tpl'));
$tpl->assign_vars(array(
	'HEADER' => MODHeader(),
	'HEADLINE' => headline($lang['L_HOME'])));
$tpl->pparse('show');

mod_mysqli_connect();
if ($action=='status') include ('./inc/home/home.php');
elseif ($action=='db') include ('./inc/home/databases.php');
elseif ($action=='sys') include ('./inc/home/system.php');
elseif ($action=='vars') include ('./inc/home/mysql_variables.php');

echo MODFooter();

