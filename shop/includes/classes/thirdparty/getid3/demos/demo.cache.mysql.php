<?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <info@getid3.org>               //
//  available at http://getid3.sourceforge.net                 //
//            or http://www.getid3.org                         //
/////////////////////////////////////////////////////////////////
//                                                             //
// /demo/demo.cache.mysql.php - part of getID3()               //
// Sample script demonstrating the use of the DBM caching      //
// extension for getID3()                                      //
// See readme.txt for more details                             //
//                                                            ///
/////////////////////////////////////////////////////////////////

/* 
 * Define the IP address you want to accept requests from 
 * as a security measure. If blank we accept anyone promisciously!
 */
$ACCEPTIP = '127.0.0.1';

function err($s)
{
	die('**** '.$s.' ');
}

$remote = $_SERVER["REMOTE_ADDR"]; 
 

if (!empty($ACCEPTIP))
 if ($remote != '127.0.0.1' && $remote != $ACCEPTIP) 
 	err("Unauthorised client: '$remote'");
	



require_once('../getid3/getid3.php');
getid3_lib::IncludeDependency(GETID3_INCLUDEPATH.'extension.cache.mysql.php', __FILE__, true);

$getID3 = new getID3_cached_mysql('localhost', 'database', 'username', 'password');

$r = $getID3->analyze('/path/to/files/filename.mp3');

echo '<pre>';
var_dump($r);
echo '</pre>';

// uncomment to clear cache
//$getID3->clear_cache();

?>