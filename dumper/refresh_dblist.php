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


// Konfigurationsdateien, die aktualisiert werden sollen
// configurations to update
// mehrere Dateien so angeben | enter more than one configurationsfile like this
// $configurationfiles=array('mysqldumper','db2');
/////////////////////////////////////////////////////////////////////////
$configurationfiles=array(
						'myoosdumper'
);


define('OOS_VALID_MOD', true);

define('APPLICATION_PATH',realpath(dirname(__FILE__)));
chdir(APPLICATION_PATH);
include_once ( APPLICATION_PATH . '/inc/functions.php' );
$config['language']='en';
$config['theme']="msd";
$config['files']['iconpath']='css/' . $config['theme'] . '/icons/';

foreach ($configurationfiles as $conf)
{
	$config['config_file']=$conf;
	include ( $config['paths']['config'] . $conf . '.php' );
	GetLanguageArray();
	SetDefault();
}
