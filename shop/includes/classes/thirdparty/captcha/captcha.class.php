<?php
/* ----------------------------------------------------------------------
   $Id: captcha.class.php,v 1.1 2007/06/07 17:37:48 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   Projectname:   CAPTCHA class
   Version:       1.1
   Author:        Pascal Rehfeldt <Pascal@Pascal-Rehfeldt.com>
   Last modified: 15. March 2004
   Copyright (C): 2003, 2004 Pascal Rehfeldt, all rights reserved
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


  class captcha {

    var $sCaptcha;

    var $chars;
    var $max_char;
    var $min_char;
    var $image_type;

    function captcha ($type = 'png', $letter = '') {

      $this->image_type = $type;
      $this->max_char = 5;
      $this->min_char = 3;

      if ($letter == '') {

        $this->random_string();

      } else {

        $this->max_char = strlen($letter);
        $this->sCaptcha = $letter;

      }
      $this->SendHeader();
      $this->MakeCaptcha();
    }

    // serendipity_event_spamblock
    function random_string() {
        $this->chars = array(2, 3, 4, 7, 9); // 1, 5, 6 and 8 may look like characters.
        $this->chars = array_merge($this->chars, array('A','B','C','D','E','F','H','J','K','L','M','N','P','Q','R','T','U','V','W','X','Y','Z')); // I, O, S may look like numbers

        $strings   = array_rand($this->chars, mt_rand($this->max_char, $this->min_char));
        $this->sCaptcha    = '';
        foreach($strings AS $idx => $charidx) {
          $this->sCaptcha .= $this->chars[$charidx];
        }

        return $strings;
    }

    function SendHeader () {

      switch ($this->image_type) {

        case 'jpeg': header('Content-type: image/jpeg'); break;
        case 'png':  header('Content-type: image/png');  break;
        default:     header('Content-type: image/png');  break;

      }
    }


    function MakeCaptcha () {

      $imagelength = 120;
      $imageheight = 40;

      // BEGIN Code copied from  the Serendipity S9Y project.
      // serendipity_event_spamblock

      $fontfiles = array('Vera.ttf', 'VeraSe.ttf', 'chumbly.ttf', '36daysago.ttf');

      $strings  = $this->random_string();
      $fontname = $fontfiles[array_rand($fontfiles)];
      $font     = OOS_TEMP_PATH . 'font/' . $fontname;


      if (!file_exists($font)) {
        trigger_error('Captchas disabled on your server. You need GDLib and freetype libraries compiled to PHP, and need the .TTF files residing in your directory.', E_USER_ERROR);
        return 0;
      }

      $image       = imagecreate($imagelength, $imageheight);
      $bgcolor     = imagecolorallocate($image, 222, 222, 222);

      $pos_x  = 5;
      foreach($strings AS $idx => $charidx) {
        $color = imagecolorallocate($image, mt_rand(50, 235), mt_rand(50, 235), mt_rand(50,235));
        $size  = mt_rand(15, 21);
        $angle = mt_rand(-20, 20);
        $pos_y = ceil($imageheight - (mt_rand($size/3, $size/2)));

        imagettftext(
          $image,
          $size,
          $angle,
          $pos_x,
          $pos_y,
          $color,
          $font,
          $this->chars[$charidx]);

        $pos_x = $pos_x + $size + 2;
      }
      // end serendipity_event_spamblock

      switch ($this->image_type) {

        case 'jpeg': imagejpeg($image, '', 95); break;
        case 'png':  imagepng($image);  break;
        default:     imagepng($image);  break;

      }
      imagedestroy($image);

    }

    function getCaptcha () {
      return $this->sCaptcha;
    }

  }

?>
