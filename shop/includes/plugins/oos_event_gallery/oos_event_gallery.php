<?php
/* ----------------------------------------------------------------------
   $Id: oos_event_gallery.php,v 1.1 2007/06/08 15:02:12 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: buildgallery.php v1.8
   ----------------------------------------------------------------------

   by:
   Mario - mariohm@fibertel.com.ar
   Mike Peck - www.mikecpeck.com 
   Mike Johnson - mike@solanosystems.com
   Christian Machmeier - www.redsplash.de 
   Airtight - www.airtightinteractive.com

   DESCRIPTION
   -----------------------
   This script automatically generates the XML document and thumbnails for SimpleViewer 
   www.airtightinteractive.com/simpleviewer/

   TO USE
   -----------------------
   Instructions are at: www.airtightinteractive.com/simpleviewer/auto_server_instruct.html

   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  define('OOS_GALLERY_PATH', OOS_ABSOLUTE_PATH . 'gallery/');

  class oos_event_gallery {

    var $name;
    var $description;
    var $uninstallable;
    var $depends;
    var $preceeds;
    var $author;
    var $version;
    var $requirements;


   /**
    *  class constructor
    */
    function oos_event_gallery() {

      $this->name          = PLUGIN_EVENT_GALLERY_NAME;
      $this->description   = PLUGIN_EVENT_GALLERY_DESC;
      $this->uninstallable = true;
      $this->author        = 'OOS Development Team';
      $this->version       = '2.0';
      $this->requirements  = array(
                               'oos'         => '1.7.0',
                               'smarty'      => '2.6.14',
                               'adodb'       => '4.90',
                               'php'         => '4.2.0'
      );
    }

    function create_plugin_instance() {

      return true;
    }

    function install() {

      // SET GALLERY OPTIONS HERE
      // -----------------------
      // Set Gallery options by editing this text:

      $options .= '<simpleviewerGallery maxImageWidth="380" maxImageHeight="380" textColor="0x000000" frameColor="0x000000" frameWidth="5" stagePadding="5" thumbnailColumns="2" thumbnailRows="2" navPosition="right" title="OOS [Gallery]" enableRightClickOpen="false" backgroundImagePath="images/gallery.jpg" imagePath="gallery/images/" thumbPath="gallery/thumbs/">';

      // Set showDownloadLinks to true if you want to show a 'Download Image' link as the caption to each image.
      $showDownloadLinks = false;

      // set useCopyResized to true if thumbnails are not being created. 
      // This can be due to the imagecopyresampled function being disabled on some servers
      $useCopyResized = false;

      // Set sortImagesByDate to true to sort by date. Otherwise files are sorted by filename.
      $sortImagesByDate = true;

      // Set sortInReverseOrder to true to sort images in reverse order.
      $sortInReverseOrder = true;

      // END OF OPTIONS
      // -----------------------

      $tgdInfo    = getGDversion();
      if ($tgdInfo == 0){
        // print "Note: The GD imaging library was not found on this Server. Thumbnails will not be created. Please contact your web server administrator.<br><br>";
        $error_gdlib = "Note: The GD imaging library was not found on this Server. Thumbnails will not be created. Please contact your web server administrator.";
        $messageStack->add($error_gdlib, 'error');
      }

      if ($tgdInfo < 2){
        // print "Note: The GD imaging library is version ".$tgdInfo." on this server. Thumbnails will be reduced quality. Please contact your web server administrator to upgrade GD version to 2.0.1 or later.<br><br>";
        $error_gdlib = "Note: The GD imaging library is version ".$tgdInfo." on this server. Thumbnails will be reduced quality. Please contact your web server administrator to upgrade GD version to 2.0.1 or later.";
        $messageStack->add($error_gdlib, 'error');
      }

/*
     if ($sortImagesByDate){
       print "Sorting images by date.<br>";
     } else {
       print "Sorting images by filename.<br>";
     }

     if ($sortInReverseOrder){
       print "Sorting images in reverse order.<br><br>";
     } else {
       print "Sorting images in forward order.<br><br>";
     }
*/


      //loop thru images
      $xml = '<?xml version="1.0" encoding="UTF-8" ?>'.$options;
      $folder = opendir(OOS_GALLERY_PATH ."images");
      while($file = readdir($folder)) {
        if ($file == '.' || $file == '..' || $file == 'CVS') continue;

        if ($sortImagesByDate){
          $files[$file] = filemtime(OOS_GALLERY_PATH . "images/$file");
        } else {
          $files[$file] = $file;
        }
      }

      // now sort by date modified
      if ($sortInReverseOrder){
        arsort($files);
      } else {
        asort($files);
      }


      foreach($files as $key => $value) {
        $xml .= '
        <image>';
        $xml .= '<filename>'.$key.'</filename>';

        //add auto captions: 'Image X'
        if ($showDownloadLinks){		
          $xml .= '<caption><![CDATA[<A href="images/'.$key.'" target="_blank"><U>Open image in new window</U></A>]]></caption>';
        }
        $xml .= '</image>';

        // print "- Created Image Entry for: $key<br>";

        if (!file_exists(OOS_GALLERY_PATH. "/thumbs/".$key)){
          if (createThumb($key)){
           // print "- Created Thumbnail for: $key<br>";
          }
        }
      }

      closedir($folder);

      $xml .= '</simpleviewerGallery>';

      $file = OOS_ABSOLUTE_PATH . 'gallery.xml';
      if (!$file_handle = fopen($file,"w")) {
        // print "<br>Cannot open XML document: $file<br>";
      } elseif (!fwrite($file_handle, $xml)) { 
        // print "<br>Cannot write to XML document: $file<br>";
      } else {
        // print "<br>Successfully created XML document: $file<br>";
      }
      fclose($file_handle);

      return true;
    }

    function remove() {
      return false;
    }

    function config_item() {
      return false;
    }
  }


// -----------------------
// buildgallery.php v1.8
// ----------------------
//
// by:
// Mario - mariohm@fibertel.com.ar
// Mike Peck - www.mikecpeck.com 
// Mike Johnson - mike@solanosystems.com
// Christian Machmeier - www.redsplash.de 
// Airtight - www.airtightinteractive.com
//
// DESCRIPTION
// -----------------------
// This script automatically generates the XML document and thumbnails for SimpleViewer 
// www.airtightinteractive.com/simpleviewer/
//
// TO USE
// -----------------------
// Instructions are at: www.airtightinteractive.com/simpleviewer/auto_server_instruct.html
//
//



// }}}
// {{{ createThumb()

/**
* Create a squared thumbnail from an existing image.
* 
* @param	string		$file		Location and name where the file is stored .
* @return	boolean
* @access	public
* @author	Christian Machmeier
*/
function createThumb($fileName)
{
	
	// Get information about the installed GD.
	$gdVersion = getGDversion();
	
	if ($gdVersion == false) {
		return false;
	}
	
	$file = OOS_GALLERY_PATH . 'images/'.$fileName;
	$fileDest = OOS_GALLERY_PATH . 'thumbs/'.$fileName;
	
	// Get the image dimensions.
	$dimensions = @getimagesize($file);
	$width		= $dimensions[0];
	$height		= $dimensions[1];	
	
	$outputX  = 65;
	$outputY  = 65;
	$quality  = 85;
	
	// The image is of vertical shape.
	if ($width < $height) {
		$deltaX   = 0;
		$deltaY   = ($height - $width) / 2;
		$portionX = $width;
		$portionY = $width;
		
	// The image is of horizontal shape.
	} else if ($width > $height) {
		$deltaX   = ($width - $height) / 2;
		$deltaY   = 0;
		$portionX = $height;
		$portionY = $height;
		
	// The image is of squared shape.
	} else {
		$deltaX   = 0;
		$deltaY   = 0;
		$portionX = $width;
		$portionY = $height;
	}
	
	$imageSrc  = @imagecreatefromjpeg($file);
	
	// The thumbnail creation with GD1.x functions does the job.
	if ($gdVersion < 2 || $useCopyResized) {
		
		// Create an empty thumbnail image.
		$imageDest = @imagecreate($outputX, $outputY);
		
		// Try to create the thumbnail from the source image.
		if (@imagecopyresized($imageDest, $imageSrc, 0, 0, $deltaX, $deltaY, $outputX, $outputY, $portionX, $portionY)) {
			
			// save the thumbnail image into a file.
			@imagejpeg($imageDest, $fileDest, $quality);
			
			// Delete both image resources.
			@imagedestroy($imageSrc);
			@imagedestroy($imageDest);
			
			return true;
			
		}

	} else {
		// The recommended approach is the usage of the GD2.x functions.
		
		// Create an empty thumbnail image.
		$imageDest = @imagecreatetruecolor($outputX, $outputY);
		
		// Try to create the thumbnail from the source image.
		if (@imagecopyresampled($imageDest, $imageSrc, 0, 0, $deltaX, $deltaY, $outputX, $outputY, $portionX, $portionY)) {
			
			// save the thumbnail image into a file.
			@imagejpeg($imageDest, $fileDest, $quality);
			
			// Delete both image resources.
			@imagedestroy($imageSrc);
			@imagedestroy($imageDest);
			
			return true;
		}
	}
	
	return false;
}

function getGDversion() {
   static $gd_version_number = null;
   if ($gd_version_number === null) {
       // Use output buffering to get results from phpinfo()
       // without disturbing the page we're in.  Output
       // buffering is "stackable" so we don't even have to
       // worry about previous or encompassing buffering.
       ob_start();
       phpinfo(8);
       $module_info = ob_get_contents();
       ob_end_clean();
       if (preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i",
               $module_info,$matches)) {
           $gd_version_number = $matches[1];
       } else {
           $gd_version_number = 0;
       }
   }
   return $gd_version_number;
}


?>
