<?php
/* ----------------------------------------------------------------------
   $Id: pdf.php,v 1.1 2007/06/13 17:33:39 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

 /**
  * HTMLDoc is available from: 
  * http://www.easysw.com/htmldoc and needs installing on the 
  * server for better HTML to PDF conversion
  *
  * @package htmldoc
  * @copyright (C) 2006 by the OOS Development Team.
  * @license GPL <http://www.gnu.org/licenses/gpl.html>
  * @link http://www.oos-shop.de/
  */

  define('OOS_VALID_MOD', 'yes');

// Set the local configuration parameters - mainly for developers
  if (file_exists('includes/local/configure.php')) {
    include 'includes/local/configure.php';
  }
  include 'includes/configure.php';


 /**
  *  Convert the named file/URL to PDF.
  */
  function topdf($filename,  $options = "") {
    # Write the content type to the client...
    header("Content-Type: application/pdf");
    header("Content-Disposition: inline; filename=\"oos-to.pdf\"");
    flush();

    # Run HTMLDOC to provide the PDF file to the user...
    # Use the --no-localfiles option for enhanced security!
    # Use ulimit to limit the maximum amount of memory used by each instance!
    passthru("ulimit -m 16384; htmldoc --no-localfiles --no-compression -t pdf14 --quiet "
            ."--jpeg --webpage $options '$filename'");
  }


 /**
  * See if the URL contains bad characters...
  */
  function bad_url($url) {

    if (empty ($url)) {
      return (1);
    }

    // See if the URL starts with http: or https:
    if (strncmp($url, "http://", 7) != 0 &&
	strncmp($url, "https://", 8) != 0) {
        return 1;
    }

    // Check for bad characters in the URL.
    $len = strlen($url);
    for ($i = 0; $i < $len; $i ++) {
        if (!strchr("~_*()/:%?+-&@;=,$.", $url[$i]) &&
	    !ctype_alnum($url[$i])) {
	    return 1;
	}
    }

    return 0;
}



 /**
  * MAIN ENTRY - Pass the trailing path info in to HTMLDOC
  */
  $url = OOS_HTTP_SERVER . OOS_SHOP . 'index.php?' . $_SERVER['QUERY_STRING'];


   if (bad_url($url)) {
     print("<HTML><HEAD><TITLE>Bad URL</TITLE></HEAD>\n"
        ."<BODY><H1>Bad URL</H1>\n");

     // Show an error message...
     print("<p><b>Error!</b></p>\n"
          ."<p>... is not a valid URL. Unable to process!</p>\n");

     print("</BODY></HTML>\n");
   } else {

     topdf($url, "--header t.D --footer ./. --size letter --left 0.5in");
   }


?>