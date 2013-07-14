<?php
/* ----------------------------------------------------------------------
   $Id: class_image2swf.php 437 2013-06-22 15:33:30Z r23 $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
   Based on:

   Image2swf
   Autor: Michael Plies

   Ming can only convert non-progressiv jpg to swf
   with this class you can generate (depending on your gd-version)
   swf from jpg,gif,png

   I. You will need the Ming libarie on php
      -> http://www.opaque.net/ming/

   II. You will need the gd libarie on php
       depending on this you can use this class
       with jpg/gif/png

   !!!!attention !!!!
    this is my very first class (ever), so i would
    be very glad for comments and sugesstions
    maybe for better coding classes in php or something else ;-)
     -> kpa@see2b.de , thanks!
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

   class Image_base_funktions{

     function Cr_jpg($image){
       switch($this->image_array[2]) {
         case 1:
           if (function_exists('imagecreatefromgif')) {
             $image_handle = imagecreatefromgif($image);
           }
           break;

         case 3:
           if (function_exists('imagecreatefrompng')) {
             $image_handle = imagecreatefrompng($image);
           }
           break;

       }
       return $image_handle;
     }
   }

   class Image2swf extends Image_base_funktions{
     var $image_array;

     function Main($image,$swf_name){
       $filename = strtolower($image);
       $extension = explode("[/\\.]", $filename);
       $n = count($extension)-1;
       $type = $extension[$n];
       if ($type == 'jpg' || $type == 'jpeg') {
         $this->Make_swf($image,$swf_name);
       } else {
         $this->image_array = @GetImageSize($image);
         $image_handle = $this->Cr_jpg($image);
         $temp_image_name = OOS_ABSOLUTE_PATH . OOS_IMAGES . $swf_name . '.jpg';
         imagejpeg ($image_handle, $temp_image_name, 100);
         ImageDestroy($image_handle);
         $this->Make_swf($temp_image_name,$swf_name);
       }
       return true;
     }

     function Make_swf($image,$swf_name){

       $s = new SWFShape();

       $b = new SWFBitmap(fopen($image, "rb"));
       $f = $s->addFill($b);

       $s->setRightFill($f);

       $s->drawLine($this->image_array[0], 0);
       $s->drawLine(0, $this->image_array[1]);
       $s->drawLine(-$this->image_array[0], 0);
       $s->drawLine(0, -$this->image_array[1]);

       if (OOS_SWF_MOVIECLIP == 'true') {
         $p = new SWFSprite();
         //add our bitmap shape to this movieclip
         $p->add($s);
         $p->nextFrame();
       }

       $m = new SWFMovie();
       $m->setDimension($this->image_array[0], $this->image_array[1]);
       if (OOS_SWF_MOVIECLIP == 'true') {
         $m->add($p);
       } else {
         $m->add($s);
       }

       $m->save(OOS_ABSOLUTE_PATH_SWF . $swf_name . '.swf');

     }
   }

?>