<?php defined( 'ABSPATH' ) or die( 'forbidden' );

// WP phpBB w3all - custom config.php phpBB configuration file

// note: you can COMMENT ( add chars // ) on the very last line of this file to force wp_w3all deactivation

// note: vars have been renamed (respect to default phpBB config.php file) to avoid conflicts with external plugins that may have vars named as phpBB
// and that instantiate db calls after integration plugin code execution

// Open with a text editor your phpBB root config.php file
// Change the following values and setup as they are (like on) into your phpBB root config.php file


$w3all_dbhost = 'required value here';
$w3all_dbport = ''; // maybe required
$w3all_dbname = 'required value here';
$w3all_dbuser = 'required value here';
$w3all_dbpasswd = 'required value here';
$w3all_table_prefix = 'required value here';

$w3all_dbms = ''; // not required
$w3all_phpbb_adm_relative_path = ''; // not required
$w3all_acm_type = ''; // not required

@define('WP_W3ALL_MANUAL_CONFIG', true);
// NOTE
// you can comment the following line, and force plugin deactivation
@define('PHPBB_INSTALLED', true);
