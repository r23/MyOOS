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

if (!defined('MOD_VERSION')) die('No direct access.');
include ('./language/'.$config['language'].'/lang_sql.php');

include ('./inc/home/apr1_md5/apr1_md5.php');
use WhiteHat101\Crypt\APR1_MD5;

$dba=$hta_dir=$Overwrite=$msg='';
$error=array();
$is_htaccess=(file_exists('./.htaccess'));
if ($is_htaccess)
{
	$Overwrite='<p class="error">'.$lang['L_HTACCESS8'].'</p>';
	$htaccess_exist=file('.htaccess'); // read .htaccess
}

$step=(isset($_POST['step'])) ? intval($_POST['step']) : 0;
$type=1; // default encryption type set to MD5(APR)
if (strtoupper(substr(MOD_OS,0,3))=='WIN') $type=2; // we are on a Win-System; pre-select encryption type
if (isset($_POST['type'])) $type=intval($_POST['type']);
$username=(isset($_POST['username'])) ? $_POST['username'] : '';
$userpass1=(isset($_POST['userpass1'])) ? $_POST['userpass1'] : '';
$userpass2=(isset($_POST['userpass2'])) ? $_POST['userpass2'] : '';

header('Pragma: no-cache');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: -1");
header('Content-Type: text/html; charset=UTF-8');
$tpl=new MODTemplate();
$tpl->set_filenames(array(
	'show' => './tpl/home/protection_create.tpl'));
$tpl->assign_vars(array(
	'THEME' => $config['theme'],
	'HEADLINE' => headline($lang['L_HTACC_CREATE'])));

if (isset($_POST['username']))
{
	// Form submitted
	if ($username=='') $error[]=$lang['L_HTACC_NO_USERNAME'];
	if (($userpass1!=$userpass2)||($userpass1=='')) $error[]=$lang['L_PASSWORDS_UNEQUAL'];

	if (sizeof($error)==0)
	{
		$realm = 'MyOOS-Dumper';
		$htaccess =
			"<IfModule mod_rewrite.c>\n" .
			"  RewriteEngine off\n" .
			"</IfModule>\n" .
			"AuthName \"" . $realm . "\"\n" .
			"AuthType Basic\n" .
			"AuthUserFile \"" . $config['paths']['root'].".htpasswd\"\n" .
			"Require valid-user";
		switch ($type)
		{
			// CRYPT
			case 0:
				$userpass = crypt($userpass1, 'rl');
				break;
			// MD5(APR)
			case 1:
				$userpass = APR1_MD5::hash($userpass1);
				break;
			// PLAIN TEXT
			case 2:
				$userpass = $userpass1;
				break;
			// SHA1
			case 3:
				$userpass = '{SHA}' . base64_encode(sha1($userpass1, true));
				break;
			// BCRYPT
			case 4:
				$userpass = password_hash($userpass1, PASSWORD_BCRYPT);
				break;
		}
		$htpasswd=$username.':'.$userpass;
		@chmod($config['paths']['root'],0777);

		// save .htpasswd
		if ($file_htpasswd=@fopen('.htpasswd','w'))
		{
			$saved=fputs($file_htpasswd,$htpasswd);
			fclose($file_htpasswd);
		}
		else
			$saved=false;

		// save .htaccess
		if (false!==$saved)
		{
			$file_htaccess=@fopen('.htaccess','w');
			if ($file_htaccess)
			{
				$saved=fputs($file_htaccess,$htaccess);
				fclose($file_htaccess);
			}
			else
				$saved=false;
		}

		if (false!==$saved)
		{
		    $msg = '<span class="success">' . $lang['L_HTACC_CREATED'] . '</span>';
			$tpl->assign_block_vars('CREATE_SUCCESS', array(
				'HTACCESS' => htmlspecialchars($htaccess),
				'HTPASSWD' => htmlspecialchars($htpasswd),
			));
			@chmod($config['paths']['root'], 0755);
		}
		else
		{
			$tpl->assign_block_vars('CREATE_ERROR', array(
				'HTACCESS' => htmlspecialchars($htaccess),
				'HTPASSWD' => htmlspecialchars($htpasswd),
			));
		}
	}
}

if (sizeof($error)>0||!isset($_POST['username']))
{
	$tpl->assign_vars(array(
		'PASSWORDS_UNEQUAL' => my_addslashes($lang['L_PASSWORDS_UNEQUAL']),
		'HTACC_CONFIRM_CREATE' => my_addslashes($lang['L_HTACC_CONFIRM_CREATE']),
	));

	$tpl->assign_block_vars('INPUT',array(
		'USERNAME' => htmlspecialchars($username),
		'USERPASS1' => htmlspecialchars($userpass1),
		'USERPASS2' => htmlspecialchars($userpass2),
		'TYPE0_CHECKED' => $type==0 ? ' checked="checked"' : '',
		'TYPE1_CHECKED' => $type==1 ? ' checked="checked"' : '',
		'TYPE2_CHECKED' => $type==2 ? ' checked="checked"' : '',
		'TYPE3_CHECKED' => $type==3 ? ' checked="checked"' : '',
		'TYPE4_CHECKED' => $type==4 ? ' checked="checked"' : '',
	));
}

if (sizeof($error)>0) $msg='<span class="error">'.implode('<br>',$error).'</span>';
if ($msg>'') $tpl->assign_block_vars('MSG',array(
	'TEXT' => $msg));

$tpl->pparse('show');

echo MODFooter();
ob_end_flush();
die();
