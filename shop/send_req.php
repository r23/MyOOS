<?php
/* ----------------------------------------------------------------------
   $Id: send_req.php,v 1.1 2007/06/13 17:33:39 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------

   Thanks to:
     Marcin 'nosferathoo' Puchalski
     Johan Sijbesma
     James Edgington
     Stephen Wald

   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


  $google = "www.google.com";
  $lang = $_GET['lang'];
  $path = "/tbproxy/spell?lang=$lang";
  $data = file_get_contents('php://input');
  $store = "";
  $fp = fsockopen("ssl://$google", 443, $errno, $errstr, 30);

  if ($fp) {
    $out = "POST $path HTTP/1.1\r\n";
    $out .= "Host: $google\r\n";
    $out .= "Content-Length: " . strlen($data) . "\r\n";
    $out .= "Content-type: application/x-www-form-urlencoded\r\n";
    $out .= "Connection: Close\r\n\r\n";
    $out .= $data;
    fwrite($fp, $out);
    while (!feof($fp)) {
      $store .= fgets($fp, 128);
    }
    fclose($fp);
  }
  print $store;
?>