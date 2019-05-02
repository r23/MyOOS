<?php
/* ----------------------------------------------------------------------

   MyOOS [Dumper]
   http://www.oos-shop.de/

   Copyright (c) 2016 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   MySqlDumper
   http://www.mysqldumper.de

   Copyright (C)2004-2011 Daniel Schlichtholz (admin@mysqldumper.de)
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


error_reporting(E_ALL);


if (function_exists("date_default_timezone_set")) date_default_timezone_set(@date_default_timezone_get());
//Konstanten
if (!defined('MSD_VERSION')) define('MSD_VERSION','4.2.2-dev');
if (!defined('MSD_OS')) define('MSD_OS',PHP_OS);
if (!defined('MSD_OS_EXT')) define('MSD_OS_EXT',@php_uname());
if (!defined('config') || !is_array($config)) $config=array();
if (!defined('databases') || !is_array($databases)) $databases=array();

//Pfade und Files
$config['paths']['root']=Realpfad('./');
$config['paths']['work']='work/';
$config['paths']['backup']=$config['paths']['work'] . 'backup/';
$config['paths']['log']=$config['paths']['work'] . 'log/';
$config['paths']['config']=$config['paths']['work'] . 'config/';
$config['paths']['perlexec']='msd_cron/';

if (isset($_SESSION['config_file']))
{
	$config['config_file']=$_SESSION['config_file'];
	$config['cron_configurationfile']=$_SESSION['config_file'];
}
else
{
	$config['config_file']='myoosdumper';
	$_SESSION['config_file']='myoosdumper';
	$config['cron_configurationfile']='myoosdumper.conf.php';
}
$config['files']['log']=$config['paths']['log'] . 'mysqldump.log';
$config['files']['perllog']=$config['paths']['log'] . 'mysqldump_perl.log';
$config['files']['perllogcomplete']=$config['paths']['log'] . 'mysqldump_perl.complete.log';
$config['files']['parameter']=$config['paths']['config'] . $config['config_file'] . '.php';

// inti MySQL-Setting-Vars
$config['mysql_standard_character_set']='';
$config['mysql_possible_character_sets']=array();

//Ini-Parameter
$config['max_execution_time']=get_cfg_var('max_execution_time');
$config['max_execution_time']=( $config['max_execution_time'] <= 0 ) ? 30 : $config['max_execution_time'];
if ($config['max_execution_time'] > 30) $config['max_execution_time']=30;
$config['upload_max_filesize']=get_cfg_var('upload_max_filesize');
$config['safe_mode']=get_cfg_var('safe_mode');
$config['magic_quotes_gpc']=get_magic_quotes_gpc();
$config['disabled']=get_cfg_var('disable_functions');
$config['phpextensions']=implode(', ',get_loaded_extensions());
$m=trim(str_replace('M','',ini_get('memory_limit')));
// fallback if ini_get doesn't work
if (intval($m) == 0) $m=trim(str_replace('M','',get_cfg_var('memory_limit')));
$config['php_ram']=$m;

//Ist zlib moeglich?
$p1=explode(', ',$config['phpextensions']);
$p2=explode(',',str_replace(' ','',$config['disabled']));

//Buggy PHP-Version ?
$p3=explode('.',PHP_VERSION);
$buggy=( $p3[0] == 4 && $p3[1] == 3 && $p3[2] < 3 );
$config['zlib']=( !$buggy ) && ( in_array('zlib',$p1) && ( !in_array('gzopen',$p2) || !in_array('gzwrite',$p2) || !in_array('gzgets',$p2) || !in_array('gzseek',$p2) || !in_array('gztell',$p2) ) );

//Tuning-Ecke
$config['tuning_add']=1.1;
$config['tuning_sub']=0.9;
$config['time_buffer']=0.75; //max_zeit=$config['max_execution_time']*$config['time_buffer']
$config['perlspeed']=10000; //Anzahl der Datensaetze, die in einem Rutsch gelesen werden
$config['ignore_enable_keys'] = 0;

//Bausteine
$config['homepage']='http://foren.myoos.de/viewforum.php?f=40';

$nl="\n";
$mysql_commentstring='--';

//config-Variablen, die nicht gesichert werden sollen
$config_dontsave=Array(

					'homepage',
					'max_execution_time',
					'safe_mode',
					'magic_quotes_gpc',
					'disabled',
					'phpextensions',
					'php_ram',
					'zlib',
					'tuning_add',
					'tuning_sub',
					'time_buffer',
					'perlspeed',
					'cron_configurationfile',
					'dbconnection',
					'version',
					'mysql_possible_character_sets',
					'mysql_standard_character_set',
					'config_file',
					'upload_max_filesize',
					'mysql_can_change_encoding',
					'cron_samedb',
					'paths',
					'files'
);

$dontBackupDatabases = array('mysql', 'information_schema');

// Automatisches entfernen von Slashes und Leerzeichen vorn und hinten abschneiden
if (1==get_magic_quotes_gpc())
{
	$_POST=stripslashes_deep($_POST);
	$_GET=stripslashes_deep($_GET);
}
$_POST=trim_deep($_POST);
$_GET=trim_deep($_GET);

function v($t)
{
	echo '<br>';
	if (is_array($t) || is_object($t))
	{
		echo '<pre>';
		print_r($t);
		echo '</pre>';
	}
	else
		echo $t;
}

function getServerProtocol()
{
	return ( isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ) ? 'https://' : 'http://';
}

