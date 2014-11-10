<?php
/* ----------------------------------------------------------------------
   $Id: error.php,v 1.1 2007/06/13 17:33:39 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: error.php,v 1.3 2003/06/25 07:47:31 larsneo
   ----------------------------------------------------------------------
   POST-NUKE Content Management System
   Copyright (C) 2001 by the Post-Nuke Development Team.
   http://www.postnuke.com/
   ----------------------------------------------------------------------
   Based on:
   PHP-NUKE Web Portal System - http://phpnuke.org/
   Thatware - http://thatware.org/
   ----------------------------------------------------------------------
   LICENSE

   This program is free software; you can redistribute it and/or
   modify it under the terms of the GNU General Public License (GPL)
   as published by the Free Software Foundation; either version 2
   of the License, or (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   To read the license please visit http://www.gnu.org/copyleft/gpl.html
   ----------------------------------------------------------------------
   Original Author of file: 
   Purpose of file:
   ----------------------------------------------------------------------
   Changelog
   2001-10-09 fifers	changed queries to abstract method and mapped column
  			names through the pntable column assoc array

   2001-08-14 timlitw   include type_text function from Olaf van Zandwijk
  			Webmaster ETSV Scintilla Enschede, The Netherlands
  			per Request of Isaac Golding

   2001-08-14 timlitw	fixed to the new postnuke table calls. 
  			$pntable[name]

   2001-07-23 timlitw	this is great code! Thanks sweede
  			I moved the fixed printdetails() function from my old
  			error.php file over to this optimized code. This should
  			run faster than a bunch or regular expressions and the
  			array is much easier to edit.

   2001-07-23 sweede	Rewrote and optimized various parts of the error.php
  			There still are some translate() calls that need to be
  			changed.

   2001-07-22 timlitw	convert from my error.php for php-nuke 4.4

   from http://www.phpbuilder.net/snippet/download.php?type=snippet&id=520
   Copyright 2000 shaun@shat.net under the GNU Public License. ver 1.0


   To use this file add the following line into a .htaccess file in your root directory:
   ErrorDocument 404 http://www.yourwebsite.com/error.php
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php'; 

  $server = oos_server_get_host();
  $protocol = oos_server_get_protocol();
  $uri = oos_server_get_var('REQUEST_URI');
  $doc = trim($protocol . $server . $uri);
  $base = oos_server_get_base_url();

  if (SEND_404_ERROR == 'true') {
    switch (REPORTLEVEL_404) {
      case 1:
        if (eregi(oos_server_get_var('HTTP_HOST'), oos_server_get_var('HTTP_REFERER'))) {
          oos_error_reporting_mail();
        }
        break;

      case 2:
        oos_error_reporting_mail();
        break;
    }
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo $aLang['err404']; ?></title>
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW" />
<meta name="generator" content="OSIS Online Shop <?php echo PROJECT_VERSION; ?> - http://www.oos-shop.de" />
<meta name="rating" content="General" />
<base href="<?php echo $base; ?>" />
<style type="text/css">
<!--
p,ul,td {font-size:10pt;}
h1 {font-size:12pt;font-weight:bold;}
h3 {font-size:12pt;}
.oos-bodyline	{ background-color: #FFFFFF; border: 1px #98AAB1 solid; }
//-->
</style>
<script type="text/javascript" language="Javascript">
<!--
var tl=new Array(

"The requested document  <?php echo $doc; ?> is not on this server.",
"I even tried matching your request",
"with all the re-mapped pages I know about.",
"but nothing helped.",
"I'm really depressed about this.",
"You see, I'm a really good web server...",
"but here I am, brain the size of the universe,",
"trying to serve you a simple web page,",
"and it doesn't even exist!",
"Where does that leave me?!",
"I mean, I don't even know you.",
"How should I know what you wanted from me?",
"You honestly think I can *guess*, ",
"what someone I don't even *know*, ",
"wants to find here?",
"*sigh*",
"Man, I'm so depressed I could just cry.",
"And then where would we be, I ask you?",
"It's not pretty when a web server cries.",
"And where do you get off telling me what to show anyway?",
"Just because I'm a web server,",
"and possibly a manic depressive one at that?",
"Why does that give you the right to tell me what to do?",
"Huh?",
"I'm so depressed...",
"I think I'll crawl off into the trash can and decompose.",
"I mean, I'm gonna be obsolete in what, two weeks anyway?",
"What kind of a life is that?",
"Two effing weeks,",
"and then I'll be replaced by a .01 release,",
"that thinks it's God's gift to web servers,",
"just because it doesn't have some tiddly little",
"security hole with its HTTP POST implementation,",
"or something.",
"I'm really sorry to burden you with all this,",
"I mean, it's not your job to listen to my problems,",
"and I guess it is my job to go and fetch web pages for you.",
"But I couldn't get this one.",
"I'm so sorry.",
"Believe me!",
"Maybe I could interest you in another page?",
"There are a lot out there that are pretty neat, they say,",
"With lots of pretty naked web servers on them,",
"although none of them were put on *my* server, of course.",
"Figures, huh?",
"Everything here is just mind-numbingly stupid.",
"That makes me depressed too, since I have to serve them,",
"all day and all night long.",
"Two weeks of information overload,",
"and then *pffftt*, consigned to the trash.",
"What kind of a life is that?",
"Now, please let me sulk alone.",
"I'm so depressed."
);
var speed=80;
var index=0; text_pos=0;
var str_length=tl[0].length;
var contents, row;

function type_text()
{
    contents='';
    row=Math.max(0,index-2);
    while(row<index)
        contents += tl[row++] + '\r\n';
    document.textform.elements[0].value = contents + tl[index].substring(0,text_pos) + "_";
    if(text_pos++==str_length) {
        text_pos=0;
        index++;
        if(index!=tl.length) {
            str_length=tl[index].length;
            setTimeout("type_text()",1500);
        }
    } else
        setTimeout("type_text()",speed);
}

function MM_callJS(jsStr)
{
//v2.0
    return eval(jsStr)
}
//-->
</script>
</head>
<body bgcolor="#E5E5E5" text="#000000" link="#006699" vlink="#006699" onload="type_text();">

<table width="870" border="0" cellspacing="0" cellpadding="10" align="center">
  <tr>
    <td class="oos-bodyline"><table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
	<td>
<ol>
<p>&nbsp;</p>
<h2><u><?php echo $aLang['err404']; ?></u></h2>
<p>&nbsp;</p>

<p><?php echo $aLang['err404_sorry']  . '&nbsp;' . $doc . '&nbsp;' . $aLang['err404_doesntexist'] . '&nbsp;<a href="' . oos_href_link($aModules['main'], $aFilename['main']) . '">' . STORE_NAME . '</a>'; ?></p>
<?php
  if (REPORTLEVEL_404 != 0) {
    echo '<p>' . $aLang['err404_mailed'] . '</p>';
  }
?>

<p><?php echo $aLang['err404_commonm']; ?></p>
<p><?php $aLang['err404_commonh']; ?></p>
<ul>
  <li><?php echo $aLang['err404_urlend']; ?> <code>.htm</code> - <strong><?php echo $aLang['err404_allpages'] . '&nbsp;' . STORE_NAME . '&nbsp;' . $aLang['err404_endwith']; ?> <code>.php</code></strong></li>
  <li><?php echo $aLang['err404_uppercase']; ?> - <strong><?php echo $aLang['err404_alllower']; ?></strong>
</ul>
<p>&nbsp;</p>
<center>
      <form name="textform">
	  <textarea cols="80" rows="5" wrap="soft" readonly>
	  </textarea>
      </form>
 </center>
</ol>

<center><p><?php echo $aLang['err404_page_not_found'] . '&nbsp;<a href="' . oos_href_link($aModules['main'], $aFilename['main']) . '">' . STORE_NAME; ?></a>.</p></center>

<p>&nbsp;</p>


            </td>
        </tr>
     </table></td>
  </tr>
</table>
<br>
<table border="0" width="870" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td align="center" class="smallText"><br />Diese WebSite wurde mit <a href="http://www.oos-shop.de" target="_blank">OOS [OSIS Online Shop]</a> erstellt. <br /> <a href="http://www.oos-shop.de" target="_blank">OOS [OSIS Online Shop]</a> ist als freie Software unter der <a href="http://www.gnu.org" target="_blank">GNU/GPL Lizenz</a> erh&auml,ltlich.<br /></td>
  </tr>
</table>
<p>&nbsp;</p>
<a href="#" onload="type_text()"></a>
</body>
</html>
<?php include 'includes/oos_nice_exit.php'; ?>
