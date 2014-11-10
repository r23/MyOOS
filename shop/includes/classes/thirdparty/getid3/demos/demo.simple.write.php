<?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <info@getid3.org>               //
//  available at http://getid3.sourceforge.net                 //
//            or http://www.getid3.org                         //
/////////////////////////////////////////////////////////////////
//                                                             //
// /demo/demo.simple.write.php - part of getID3()              //
// Sample script showing basic syntax for writing tags         //
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
	

$TaggingFormat = 'UTF-8';

require_once('../getid3/getid3.php');
// Initialize getID3 engine
$getID3 = new getID3;
$getID3->encoding = $TaggingFormat;

require_once('../getid3/write.php');
// Initialize getID3 tag-writing module
$tagwriter = new getid3_writetags;
//$tagwriter->filename       = '/path/to/file.mp3';
$tagwriter->filename       = 'd:/file.mp3';
$tagwriter->tagformats     = array('id3v1', 'id3v2.3');

// set various options (optional)
$tagwriter->overwrite_tags = true;
$tagwriter->tag_encoding   = $TaggingFormat;
$tagwriter->remove_other_tags = true;

// populate data array
$TagData['title'][]   = 'My Song';
$TagData['artist'][]  = 'The Artist';
$TagData['album'][]   = 'Greatest Hits';
$TagData['year'][]    = '2004';
$TagData['genre'][]   = 'Rock';
$TagData['comment'][] = 'excellent!';
$TagData['track'][]   = '04/16';

$tagwriter->tag_data = $TagData;

// write tags
if ($tagwriter->WriteTags()) {
	echo 'Successfully wrote tags<br>';
	if (!empty($tagwriter->warnings)) {
		echo 'There were some warnings:<br>'.implode('<br><br>', $tagwriter->warnings);
	}
} else {
	echo 'Failed to write tags!<br>'.implode('<br><br>', $tagwriter->errors);
}

?>