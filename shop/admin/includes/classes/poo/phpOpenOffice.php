<?php

// +----------------------------------------------------------------------+
// | phpOpenOffice - Solution for modifying OpenOffice documents with PHP |
// | v0.1b - 18/12/2003                                                   |
// |                                                                      |
// | This software is published under BSD licence.                        |
// | http://www.opensource.org/licenses/bsd-license.php                   |
// +----------------------------------------------------------------------+
// | Written by Bjoern Kahle, Hamburg 2003 (phpopenoffice at pinasoft.de) |
// | http://www.pinasoft.de/projects/phpOpenOffice/                       |
// +----------------------------------------------------------------------+

// Where is phpOpenOffice going to store the documents temporarly
if (!defined('POO_TMP_PATH')) {
  define('POO_TMP_PATH', '/tmp/');
}


// Where are the OpenOffice templates
if (!defined('POO_TEMPLATE_PATH')) {
  define('POO_TEMPLATE_PATH', "");
}

// PhpConcept Library - Zip Module 2.0
// http://www.phpconcept.net
if (!defined('PCLZIP_INCLUDE_PATH')) {
  define('PCLZIP_INCLUDE_PATH',"./pclzip/");
}
define( 'PCLZIP_TEMPORARY_DIR', POO_TMP_PATH );
require PCLZIP_INCLUDE_PATH . 'pclzip.lib.php';


// Use zlib from PHPMyAdmin for writing zipped files,
// because documents created with PclZip cannot be opened with OpenOffice
// Needs to be fixed in later version.
require_once('includes/classes/class_zip.php');

// How are variables defined within the document
if (!defined('POO_VAR_PREFIX')) {
  define('POO_VAR_PREFIX', '%');
}

if (!defined('POO_VAR_SUFFIX')) {
  define('POO_VAR_SUFFIX', '%');
}


// Callback function for pclzip
$archiveFiles = array();
function ooPreAdd($p_event, &$p_header)
{
	global $archiveFiles;
	if($p_header['folder'] == 0)
		$archiveFiles[] = $p_header["stored_filename"];
	return 0;
}


class phpOpenOffice
{
	var $tmpDirName = "";
	var $parserFiles = "";
	var $parsedDocuments = "";
	var $mimetypeFile = "";
	var $mimetype = "";
	var $zipFile = "";


	// Load document from filesystem
	function loadDocument($filename)
	{
		if(!file_exists($filename))
		{
			$this->handleError("File not found: ".$filename, E_USER_ERROR);
		}
		else
		{
			$this->zipFile = $filename;
		}


		// Find a random folder name for PCLZIP_OPT_ADD_PATH
		$this->tmpDirName = $this->getRandomString(16);
		$this->mimetypeFile = POO_TMP_PATH."/".$this->tmpDirName."/mimetype";
		$this->parserFiles = array();
		$this->parserFiles["content.xml"] = POO_TMP_PATH."/".$this->tmpDirName."/content.xml";
		$this->parserFiles["styles.xml"] = POO_TMP_PATH."/".$this->tmpDirName."/styles.xml";


		// Open archive and extract content.xml
		$archive = new PclZip($filename);
		$list = $archive->extract(PCLZIP_OPT_PATH, POO_TMP_PATH, PCLZIP_OPT_ADD_PATH, $this->tmpDirName);
	}


	// Put variables into extracted content file
	function parse($variables)
	{
		// Has file been extracted ?
		if($this->tmpDirName == "")
		{
			$this->handleError("No document loaded. Use loadDocument function first.", E_USER_ERROR);
		}


		// Is dir still there
		if(!is_dir(POO_TMP_PATH."/".$this->tmpDirName))
		{
			$this->handleError("Directory not found: ".$this->tmpDirName, E_USER_ERROR);
		}


		// Is argument valid ?
		if(!is_array($variables))
		{
			$this->handleError("First parameter need to been an array.", E_USER_ERROR);
		}


		// Read mimetype
		$fp = fopen($this->mimetypeFile, "r");
		$this->mimetype = fread($fp, filesize($this->mimetypeFile));
		fclose($fp);


		// Open files and start parsing
		$parsedDocuments = array();
		foreach (array_keys($this->parserFiles) as $file)
		{
			$fp = fopen($this->parserFiles[$file], "r");
			$this->parsedDocuments[$file] = fread($fp, filesize($this->parserFiles[$file]));
			fclose($fp);

			foreach(array_keys($variables) as $key)
			{
				$value = $this->xmlencode( $variables[$key] );
				$this->parsedDocuments[$file] = str_replace(POO_VAR_PREFIX.$key.POO_VAR_SUFFIX, $value, $this->parsedDocuments[$file]);
			}
		}
	}


	// encode string xml compatible
	function xmlencode($param)
	{
		$xml = $param;

		$xml = str_replace("&", "&amp;", $xml);
		$xml = str_replace(">", "&gt;", $xml);
		$xml = str_replace("<", "&lt;", $xml);
		$xml = str_replace("'", "&apos;", $xml);
		$xml = str_replace("\"", "&quot;", $xml);

		$xml = utf8_encode($xml);
		return $xml;
	}

	// Save parsed document
	function savefile($filename)
	{
		global $archiveFiles;


		// Has file been extracted ?
		if($this->tmpDirName == "")
		{
			$this->handleError("No document loaded. Use loadDocument function first.", E_USER_ERROR);
		}


		// Is dir still there
		if(!is_dir(POO_TMP_PATH."/".$this->tmpDirName))
		{
			$this->handleError("Directory not found: ".$this->tmpDirName, E_USER_ERROR);
		}


		// Overwrite parsed documents
		foreach (array_keys($this->parserFiles) as $file)
		{
			$fp = fopen($this->parserFiles[$file], "w+");
			fputs($fp, $this->parsedDocuments[$file]);
			fclose($fp);
		}


		// Create new (zip-)file - Add all files and subdirectories from temporary directory
		$archive = new PclZip($filename);
		$v_list = $archive->create(POO_TMP_PATH."/".$this->tmpDirName, PCLZIP_OPT_REMOVE_PATH, POO_TMP_PATH."/".$this->tmpDirName."/", PCLZIP_CB_PRE_ADD, "ooPreAdd");


		// zip.lib dirty hack
		$zip = new zipfile();


		// Add specials files without compression
		for($i = 0; $i < count($archiveFiles); $i++)
		{
			$file = $archiveFiles[$i];

			/*if( $file == "mimetype" || $file == "meta.xml" || substr( $file, 0, 9) == "Pictures/" )
			{
				$v_list = $archive->add(POO_TMP_PATH."/".$this->tmpDirName."/".$file, PCLZIP_OPT_REMOVE_PATH, POO_TMP_PATH."/".$this->tmpDirName."/", PCLZIP_OPT_NO_COMPRESSION);
			}
			else
			{
				$v_list = $archive->add(POO_TMP_PATH."/".$this->tmpDirName."/".$file, PCLZIP_OPT_REMOVE_PATH, POO_TMP_PATH."/".$this->tmpDirName."/");
			}
			*/


			// zip.lib dirty hack
			$fp = fopen(POO_TMP_PATH."/".$this->tmpDirName."/".$file, "r");
			$content = fread($fp, filesize(POO_TMP_PATH."/".$this->tmpDirName."/".$file));
			fclose($fp);
			$zip->addFile($content, $file);
		}


		// Finally write file to disk => zip.lib dirty hack
		$fp = fopen($filename, "w+");
		fputs($fp, $zip->file());
		fclose($fp);
	}



	function download($filename)
	{
		// Build filename and save file temporarly to harddisk
		if($filename == "") $filename = $this->getRandomString(16);
		$info = pathinfo($this->zipFile);
		$fullfile = $filename.".".$info["extension"];
		$downloadFile = POO_TMP_PATH."/".$fullfile;
		$this->savefile($downloadFile);


		// Read temp file
		$fp = fopen($downloadFile, "r");
		$content = fread($fp, filesize($downloadFile));
		fclose($fp);


		// Build HTTP header and send file
		header("Expires: ".date("D, d M Y H:i:s", time() - 24 * 60 * 60)." GMT");	// expires in the past
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");			// Last modified, right now
		header("Cache-Control: no-cache, must-revalidate");				// Prevent caching, HTTP/1.1
		header("Pragma: no-cache");
		header("Content-Type: ".$this->mimetype);
		header('Content-Length: '.filesize($downloadFile));
		header('Content-Transfer-Encoding: binary');


		// (Browser specific)
		$browser= $_SERVER["HTTP_USER_AGENT"];
		if( preg_match('/MSIE 5.5/', $browser) || preg_match('/MSIE 6.0/', $browser) )
		{
			header('Content-Disposition: filename="'.$fullfile.'"');
		}
		else
		{
			header('Content-Disposition: attachment; filename="'.$fullfile.'"');
		}


		// Data
		echo $content;

		
		// Delete temp file
		@unlink($downloadFile);
	}


	// Cleans up filesystem after job is done
	function clean()
	{
		if($this->tmpDirName == "")
			return;
		$tmpPath = POO_TMP_PATH."/".$this->tmpDirName;
		$this->deldir($tmpPath);
	}


	// Returns random string..easy, eh ?
	function getRandomString($length)
	{
		srand(date("s"));
		$possible_charactors = "abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$string = "";
		while(strlen($string)<$length)
		{
			$string .= substr($possible_charactors, rand()%(strlen($possible_charactors)), 1);
		}
		return($string);
	}


	// Default error handler
	function handleError($errorMessage, $errorType = E_USER_WARNING)
	{
		$prefix = 'phpOpenOffice ' . (($errorType == E_USER_ERROR) ? 'Error' : 'Warning') . ': ';
		echo $prefix . $errorMessage;

		if($errorType == E_USER_ERROR) die;
    	}


	// Borrowed from marcelognunez at hotmail dot com
	function deldir($dir)
	{
		$current_dir = opendir($dir);
		while($entryname = readdir($current_dir))
		{
			if(is_dir("$dir/$entryname") and ($entryname != "." and $entryname!=".."))
			{
				$this->deldir("${dir}/${entryname}");
			}
			elseif($entryname != "." and $entryname!="..")
			{
				@unlink("${dir}/${entryname}");
		}
		}
		closedir($current_dir);
		@rmdir(${dir});
	}

}

?>