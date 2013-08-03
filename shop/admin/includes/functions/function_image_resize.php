<?php
/* ----------------------------------------------------------------------
   $Id: function_image_resize.php 442 2013-06-27 00:04:01Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   image_resize.php,v 1.3 2003/03/28 16:57:40 hook
   Copyright (c) 2003 IN-Solution.de Henri Schmidhuber

   based on

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  if (!defined('OOS_SMALL_IMAGE_WIDTH')) {
    define('OOS_SMALL_IMAGE_WIDTH', '110');
  }

  if (!defined('OOS_SMALL_IMAGE_HEIGHT')) {
    define('OOS_SMALL_IMAGE_HEIGHT', '110');
  }

function oos_copy_uploaded_file($filename, $target) {
  if (substr($target, -1) != '/') $target .= '/';
  if (OOS_RANDOM_PICTURE_NAME == 'true') {
    $picture_tempname = oos_get_random_picture_name(26);
    $picture_tempname .= '.';
    $picture_tempname .= oos_get_extension($filename['name']);
  } else {
    $picture_tempname = $filename['name'];
  }
  $picture_name = oos_str_strip_all($picture_tempname);
  // Big Image
  // path for big image:
  $target_big = $target . OOS_POPUP_IMAGES;
  // If !path make it, if you have problems remove the line
  // if (!is_dir($target_big)) mkdir($target_big,0777);

  // Resize Big image
  $target_big .= $picture_name;
  if (OOS_WATERMARK == 'true') {
    oos_watermark ($filename['tmp_name'], $target_big, OOS_WATERMARK_QUALITY);
  } else {
    if (OOS_BIGIMAGE_WIDTH || OOS_BIGIMAGE_HEIGHT ) {
      oos_resize_image($filename['tmp_name'], $target_big, OOS_BIGIMAGE_WIDTH, OOS_BIGIMAGE_HEIGHT, OOS_BIGIMAGE_WAY_OF_RESIZE);
    } else {
      copy($filename['tmp_name'], $target_big);
    }
  }

  $target_small = $target . $picture_name;
  oos_resize_image($filename['tmp_name'], $target_small, OOS_SMALL_IMAGE_WIDTH, OOS_SMALL_IMAGE_HEIGHT, OOS_SMALLIMAGE_WAY_OF_RESIZE);

  return $picture_name;
}


function oos_str_strip_all ($str) {
  $str =& trim($str);
  $str =& strtolower($str);
  // Strip non-alpha & non-numeric except ._-:
  // 12 abc def/ghi\jkl'&%$mno\n343dd -> 12abcdyY8-._efghijkl343ddi
  // Use to get usefull Filenames for pictures
  return preg_replace ("/[^[:alnum:]._-]/", "", $str);
}


function oos_get_random_picture_name($length = 24) {
  $sStr = "";

  for ($index = 1; $index <= $length; $index++) {
    // Pick random number between 1 and 62
    $randomNumber = rand(1, 62);
    // Select random character based on mapping.
    if ($randomNumber < 11) {
      $sStr .= Chr($randomNumber + 48 - 1); // [ 1,10] => [0,9]
    } else if ($randomNumber < 37) {
      $sStr .= Chr($randomNumber + 65 - 10); // [11,36] => [A,Z]
    } else {
      $sStr .= Chr($randomNumber + 97 - 36); // [37,62] => [a,z]
    }
  }
    return $sStr;
}



function oos_resize_image($pic,$image_new,$new_width,$new_height,$fixed=0) {
   // resize Picture if possible
   // $fixed:
   // 0: propotional resize; new_width or new_height are max Size
   // 1: new_width, new_height are new sizes; Image is proportional resized into new Image.  OOS_IMAGE_BGCOLOUR are the backgroundcolors
   // 2: new_width, new_height are new sizes; Thumbnail, Pic is resized and part of it is copied to new imaget
   $dst_img = '';
   $imageInfo = GetImageSize($pic);
   $width = $imageInfo[0];
   $height = $imageInfo[1];

   if (($new_width && $width>=$new_width)  || ( $new_height && $height>=$new_height)  ) {

     // check if php with gd-lib-support is installed
     if (function_exists('imagecreatefromjpeg')) {

       switch ($imageInfo[2]) {
         case 1:
           if (function_exists('imagecreatefromgif')) {
             $src_img = imagecreatefromgif($pic);
           }
           break;

         case 2:
           if (function_exists('imagecreatefromjpeg')) {
             $src_img = imagecreatefromjpeg($pic);
           }
           break;

         case 3:
           if (function_exists('imagecreatefrompng')) {
             $src_img = imagecreatefrompng($pic);
           }
           break;

       }

       if ($src_img) {

         switch ($fixed) {
           case 0:
             // proportionaler resize; width oder height ist die maximale Größe
             $x = $new_width/$width;
             $y = $new_height/$height;
             if (($y>0 && $y<$x) || $x==0) $x=$y;
             $width_big = $width*$x;
             $height_big = $height*$x;

			 $dst_img = imagecreatetruecolor($width_big,$height_big);
             imagecopyresampled($dst_img,$src_img,0,0,0,0,$width_big,$height_big,imagesx($src_img),imagesy($src_img));

             break;

           case 1:
             // Bild wird proportional verkleinert in das neue Bild kopiert
             if ($new_width > 0)  $x = $new_width / $width;
             if ($new_height > 0) $y = $new_height / $height;

             if (($y > 0 && $y < $x) || $x == 0) $x = $y;
             $width_big = $width * $x;
             $height_big = $height * $x;

             if ($new_width > 0 && $new_width > $width_big)  {
               $dst_width = $new_width;
             } else {
               $dst_width = $width_big;
             }
             if ($new_height > 0 && $new_height > $height_big) {
               $dst_height = $new_height;
             } else {
               $dst_height = $height_big;
             }
             // copy new picture into center of $dst_img
             if ($dst_width > $width_big) {
               $dstX = ($dst_width - $width_big)/2;
             } else {
               $dstX = 0;
             }
             if ($dst_height > $height_big) {
               $dstY = ($dst_height - $height_big)/2;
             } else {
              $dstY = 0;
             }
               $dst_img = imagecreatetruecolor($dst_width,$dst_height);
               $colorallocate = ImageColorAllocate ($dst_img, OOS_IMAGE_BGCOLOUR_R, OOS_IMAGE_BGCOLOUR_G, OOS_IMAGE_BGCOLOUR_B);
               imagefilledrectangle($dst_img,0,0,$dst_width,$dst_height,$colorallocate);
               imagecopyresampled($dst_img,$src_img,$dstX,$dstY,0,0,$width_big,$height_big,imagesx($src_img),imagesy($src_img));
             break;

           case 2:
             // Thumbnail, Bild wird verkleinert und ein Ausschnitt wird ins neue kopiert
             if ($new_width > 0)  $x = $new_width / $width;
             if ($new_height > 0) $y = $new_height / $height;
             if (($x > 0 && $y > $x) || $x==0) $x = $y;
             $width_big = $width * $x;
             $height_big = $height * $x;
             // Bild verkleinern
               $dst_img = imagecreatetruecolor($new_width,$new_height);
               $tmp_img = imagecreatetruecolor($width_big,$height_big);
               imagecopyresampled($tmp_img,$src_img,0,0,0,0,$width_big,$height_big,imagesx($src_img),imagesy($src_img));
               imagecopy($dst_img,$tmp_img,0,0,0,0,$new_width,$new_height);

               if (IMGLENS == 'true') {
                 $trans = imagecolorallocatealpha($dst_img, 255, 255, 255, 25);
                 imagefilledrectangle($dst_img, 0, $new_height-9, $new_width, $new_height, $trans);

                 $magnifier = imagecreatefromstring(gzuncompress(base64_decode("eJzrDPBz5+WS4mJgYOD19HAJAtLcIMzBBiRXrilXA1IsxU6eIRxAUMOR0gHkcxZ4RBYD1QiBMOOlu3V/gIISJa4RJc5FqYklmfl5CiGZuakMBoZ6hkZ6RgYGJs77ex2BalRBaoLz00rKE4tSGXwTk4vyc1NTMhMV3DKLUsvzi7KLFXwjFEAa2svWnGdgYPTydHEMqZhTOsE++1CAyNHzm2NZjgau+dAmXlAwoatQmOld3t/NPxlLMvY7sovPzXHf7re05BPzjpQTMkZTPjm1HlHkv6clYWK43Zt16rcDjdZ/3j2cd7qD4/HHH3GaprFrw0QZDHicORXl2JsPsveVTDz//L3N+WpxJ5Hff+10Tjdd2/Vi17vea79Om5w9zzyne9GLnWGrN8atby/ayXPOsu2w4quvVtxNCVVz5nAf3nDpZckBCedpqSc28WTOWnT7rZNXZSlPvFybie9EFc6y3bIMCn3JAoJ+kyyfn9qWq+LZ9Las26Jv482cDRE6Ci0B6gVbo2oj9KabzD8vyMK4ZMqMs2kSvW4chz88SXNzmeGjtj1QZK9M3HHL8L7HITX3t19//VVY8CYDg9Kvy2vDXu+6mGGxNOiltMPsjn/t9eJr0ja/FOdi5TyQ9Lz3fOqstOr99/dnro2vZ1jy76D/vYivPsBoYPB09XNZ55TQBAAJjs5s</body>")));
                 imagealphablending( $dst_img, true);
                 imagecopy( $dst_img, $magnifier, $new_width-15, $new_height-14, 0, 0, 11, 11);
                 imagedestroy($magnifier);
               }

             break;

           }
           // Copy Picture
           $fh = fopen($image_new,'w');
           fclose($fh);
           if ($imageInfo[2]==1) imagegif($dst_img,$image_new);
           if ($imageInfo[2]==2) imagejpeg($dst_img,$image_new,OOS_WATERMARK_QUALITY);
           if ($imageInfo[2]==3) imagepng($dst_img,$image_new);
           return true;
         }
       }
     }
   // pic couldn't be resized, so copy original
  copy ($pic,$image_new);
  return false;
}

function oos_alpha_image($pic, $image_new, $new_width, $new_height) {
   $dst_img = '';
   $imageInfo = GetImageSize($pic);
   $width = $imageInfo[0];
   $height = $imageInfo[1];

   if (($new_width && $width>=$new_width)  || ( $new_height && $height>=$new_height)  ) {

     // check if php with gd-lib-support is installed
     if (function_exists('imagecreatefromjpeg')) {

       switch ($imageInfo[2]) {
         case 1:
           if (function_exists('imagecreatefromgif')) {
             $src_img = imagecreatefromgif($pic);
           }
           break;

         case 2:
           if (function_exists('imagecreatefromjpeg')) {
             $src_img = imagecreatefromjpeg($pic);
           }
           break;

         case 3:
           if (function_exists('imagecreatefrompng')) {
             $src_img = imagecreatefrompng($pic);
           }
           break;

       }

       if ($src_img) {

         // Thumbnail, Bild wird verkleinert und ein Ausschnitt wird ins neue kopiert
         if ($new_width > 0)  $x = $new_width / $width;
         if ($new_height > 0) $y = $new_height / $height;
         if (($x > 0 && $y > $x) || $x==0) $x = $y;
         $width_big = $width * $x;
         $height_big = $height * $x;


           $dst_img = imagecreatetruecolor($new_width,$new_height);
           $tmp_img = imagecreatetruecolor($width_big,$height_big);

           imagecopyresampled($tmp_img,$src_img,0,0,0,0,$width_big,$height_big,imagesx($src_img),imagesy($src_img));
           imagecopy($dst_img,$tmp_img,0,0,0,0,$new_width,$new_height);

           $trans = imagecolorallocatealpha($dst_img, 255, 255, 255, 60);
           imagefilledrectangle( $dst_img, 0, 0, $new_width, $new_height, $trans);


         // Copy Picture
         $fh = fopen($image_new,'w');
         fclose($fh);

         if ($imageInfo[2]==1) imagegif($dst_img,$image_new);
         if ($imageInfo[2]==2) imagejpeg($dst_img,$image_new,OOS_WATERMARK_QUALITY);
         if ($imageInfo[2]==3) imagepng($dst_img,$image_new);

         return true;
      }
    }
  }

  return false;
}






function oos_watermark($pic, $image_new, $quality = '100') {
   $dst_img = '';
   $imageInfo = GetImageSize($pic);
   $width = $imageInfo[0];
   $height = $imageInfo[1];
   $logoinfo = getimagesize(OOS_WATERMARK_LOGO);
   $logowidth = $logoinfo[0];
   $logoheight = $logoinfo[1];
   if (function_exists('imagecreatefromjpeg')) {   // check if php with gd-lib-support is installed
     if ($imageInfo[2]==1) {
       if (function_exists('imagecreatefromgif')) {
         $src_img = imagecreatefromgif($pic);
       }
     }
     if ($imageInfo[2]==2) {
       if (function_exists('imagecreatefromjpeg')) {
         $src_img = imagecreatefromjpeg($pic);
       }
     }
     if ($imageInfo[2]==3) {
       if (function_exists('imagecreatefrompng')) {
         $src_img = imagecreatefrompng($pic);
       }
     }

     if ($src_img) {
       if (OOS_BIGIMAGE_WIDTH || OOS_BIGIMAGE_HEIGHT) {
         // proportionaler resize; width oder height ist die maximale Größe
         $x = OOS_BIGIMAGE_WIDTH/$width;
         $y = OOS_BIGIMAGE_HEIGHT/$height;
         if (($y>0 && $y<$x) || $x==0) $x=$y;
         $width_big = $width*$x;
         $height_big = $height*$x;
         $width = $width_big;
         $height= $height_big;
         $dst_img = imagecreatetruecolor($width_big, $height_big);
         imagecopyresampled($dst_img, $src_img, 0, 0, 0 , 0 , $width_big, $height_big, imagesx($src_img), imagesy($src_img));
       } else {
         $dst_img = $src_img;
       }
       $hori = $width - $logowidth;
       $vert = $height - $logoheight;
       $horizmargin =  round($hori / 2);
       $vertmargin =  round($vert / 2);
       ImageAlphaBlending($dst_img, true);
       $logoImage = ImageCreateFromPNG(OOS_WATERMARK_LOGO);
       $logoW = ImageSX($logoImage);
       $logoH = ImageSY($logoImage);
       ImageCopy($dst_img, $logoImage, $horizmargin, $vertmargin, 0, 0, $logoW, $logoH);


       // Copy Picture
       $fh = fopen($image_new,'w');
       fclose($fh);
       if ($imageInfo[2]==1) imagegif($dst_img, $image_new);
       if ($imageInfo[2]==2) imagejpeg($dst_img, $image_new, $quality);
       if ($imageInfo[2]==3 )imagepng($dst_img, $image_new);
       return true;
     }
   }
   // pic couldn't be resized, so copy original
   copy ($pic,$image_new);
   return false;
 }

