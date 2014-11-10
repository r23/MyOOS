####################################################################
#                                                                  #
# pXw4Pa v1.0                                                      #
#                                                                  #
####################################################################

poor XML wrapper for PHP arrays


copyright (C) 2005/2006 yayo (Roberto Correzzola)


version:                    1.0
released:                   May 7, 2006
license:                    GNU GPL (General Public License) v.2
author:                     yayo (Roberto Correzzola)
email: (also read section
"future planning")          yayo.75@katamail.com
homepage:                   http://pxw4pa.sourceforge.net/index.php
SF page:                    http://sourceforge.projects.pxw4pa/


This software and its documentation are licensed under the terms
of the Creative Commons GNU General Public License v2.0
http://creativecommons.org/licenses/GPL/2.0/


####################################################################
# INDEX                                                            #
####################################################################

01. disclaimer.
02. license.
03. history.
04. what is pXw4Pa?
05. download link and requirement.
06. how to use it.
07. how to WRITE an XML file from a PHP array.
08. how to READ a PHP array from an XML file.
09. XML tags used.
10. demonstration.
11. options.
12. limitations, bugs, issues...
13. future planning.
14. about implementing this code somewhere...
15. donations(?).
16. acknowledgements.
17. author's other projects.

####################################################################
# DOCUMENTATION                                                    #
####################################################################

____________________________________________________________________
01. disclaimer.
¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
The author is not responsible for data loss, or any kind of trouble
that results from the use of this software.
USE IT AT YOUR OWN RISK!

____________________________________________________________________
02. license.
¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
pXw4Pa v1.0 (poor XML wrapper for PHP arrays)
Copyright (C) 2005/2006 yayo (Roberto Correzzola)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License as
published by the Free Software Foundation; either version 2 of the
License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.


---

To display an abstract of the license click here:
http://creativecommons.org/licenses/GPL/2.0/deed.en

From the same page you have access to the whole text of the license
(the full text is also attached to the pXw4Pa package).


____________________________________________________________________
03. history.
¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
Version 1.0
- both functions were totally rewritten: serialization is
  no longer used;
  read function now uses PHP XML parser, which displays useful info
  on badly formatted XML.
  compared to version 0.8, the functions are now around half the
  size and twice as fast.
- supported data types: string, integer, double (=floating point),
  boolean, and NULL
- added the attribute "type" to the XML structure
- some documentation changes
- some other minor changes

Version 0.8
- the XML code produced is now w3c valid: it contains a link to the
  Document Type Declaration file pXw4Pa.dtd (contained in the
  package files).
- added support for empty and space-filled tags (tags which
  contains spaces, tabs, CarriageReturn and LineFeed characters.)
- some documentation changes

Version 0.7
- added a demonstration/test for Write function
- Html documentation code validated upon W3C standard
  (CSS file too)
- some documentation changes

Version 0.6
- fixed a serious bug with the Write function (which lead to failed
  translation from serialize syntax on strings with a ";" char);
  the translation code now ensures a much more correct result.
- some documentation changes

Version 0.5
- script modified (added multiple language support)
- html documentation script rewritten (index added)
- added css file
- Italian documentation
- Support for XML comments added

Version 0.2
- Added some notes on documentation
- Fixed a bug which created trouble when the 1st subnode wasn't
  named

Version 0.1
- 1st release

____________________________________________________________________
04. what is pXw4Pa?
¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
"pXw4Pa" is a couple of simple functions written in PHP 4 which
can transfer an array of data from or to an XML file.
pXw4Pa can be used to store/swap data blocks of your programs to
an XML file simply and quickly, without making use of XML parsers
or other big stuff.

The XML (acronym for Extensible Markup Language) is a standard way
to store information in a simple text file, using a special syntax
and a set of labels. It is a restricted form of the SGML format,
the Standard Generalized Markup Language [ISO 8879].
You can find all the information you need about it on this page:

http://en.wikipedia.org/wiki/XML

---

Example of pXw4Pa use.
Imagine you're working on a PHP script and you need to manage
this data:

$pc =(
    hardware =(
        1 = "monitor"
        2 = "motherboard+CPU"
        3 = "hard disk"
        4 = "keyboard"
        5 = "mouse"
        6 = "modem"
    )
    software =(
        1 = "operative system"
        2 = "drivers"
        3 = "applications"
    )
)

The array $pc contains both hardware and software subarrays, and
each one of these contains a list of other string type data values.
Using the pxw4pa write function you can save this array into a
text file with .xml extension.
The result will be something like this:


<?xml version="1.0"?>
<pXw4Pa version="1.0">
<group>
    <group name="hardware">
      <entry name="1" type="string">monitor</entry>
      <entry name="2" type="string">motherboard+CPU</entry>
      <entry name="3" type="string">hard disk</entry>
      <entry name="4" type="string">keyboard</entry>
      <entry name="5" type="string">mouse</entry>
      <entry name="6" type="string">modem</entry>
    </group>
    <group name="software">
      <entry name="1" type="string">operative system</entry>
      <entry name="2" type="string">drivers</entry>
      <entry name="3" type="string">applications</entry>
    </group>
  </group>
</pXw4Pa>

(supposing that the name of the parent array "pc" is used as the
filename: "pc.xml")

This lets you easily move your data almost everywhere, share it
with other programmers, or use it in other software, since the XML
format is a standard and it's widely used.

____________________________________________________________________
05. download link and requirement.
¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
First of all, you must download the zip archive containing the 2
functions, of course. : )
You can download it from this address:

http://prdownloads.sourceforge.net/pxw4pa/pXw4Pa_1.0.zip?download

On this page you will be asked to choose a mirror (one of the
SourceForge archives) for the download. When you've chosen a mirror
wait a moment: the download will start automatically.

---

Also remember that to use pXw4Pa you need at least the version
4.0.0 of PHP.

____________________________________________________________________
06. how to use it.
¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯

The file "pXw4Pa.php" contains the 2 functions.
You can copy the function you need to your PHP file, or copy the
file to your project folder and link it to your project,
adding the line

     require 'pXw4Pa.php';

to the beginning of your code.

____________________________________________________________________
07. how to WRITE an XML file from a PHP array.
¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
To write an array to an XML file call the WRITE function
in this way:

     pXw4Pa_write($array,'filename');

replacing the "$array" text with the name of the array you want
to process, and "filename" with the name of XML file you're
going to write.

####################################################################
# WARNING!                                                         #
# Any existing XML file will be overwritten                        #
# without asking you to confirm!                                   #
####################################################################

If you omit the filename, the code will use the default name
"pXw4Pa_ouput.xml".

If something goes wrong an error message will help you to find
the problem.

Note: the write function exchange <, >, " and & chars with the
equivalent HTML entities (&#60;, &#62;, &#34; and &#38;) before
to write the file, to avoid to create an invalid XML.

____________________________________________________________________
08. how to READ a PHP array from an XML file.
¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
To read an array from an XML file call the READ function
in this way:

     $array=pXw4Pa_read('filename');

replacing the "$array" text with the name of the array where you
want to store the content of the XML file, and "filename" with the
name of XML file you're going to read.

If something goes wrong an error message will help you to find
the problem.

---

If you're going to write an XML file by hand, keep in mind
these things:

1. The whole content of your XML-array must be closed inside a
   "<group></group>" node without a name (=>attribute "name").
   This is because the read function searches for the XML file
   content as a single big group (as it should be).
   The name of that group will be the name of the array that must
   be assigned within the PHP code. This means that the content
   of your XML-array will start with the sequence

     <pXw4Pa version="#.#"><group>

   and will be closed by the sequence

     </group></pXwaPa>.

2. Using the "less then", "greater then" and "double quote"
   characters (<, > and ") inside a string, could lead to an
   invalid XML file, since these characters are the same used to
   define the tags of the XML syntax.
   
   If you need to have valid XML files you must change these
   characters to something else, like the HTML entities.
   
   To get the HTML entity of a character, write this sequence
   without spaces inbetween: ampersand+hash+[ASCII code of the
   required char]+semicolon.

   Example.
   Since the ASCII value of the "less then" char is 60, its HTML
   entity is:

     &#60;

   Also the ampersand (&) can lead to problems if you use it alone.
   Use the same rule here too: write it as &#38;
   (also &amp; will work)

____________________________________________________________________
09. XML tags used.
¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
All the XML data managed by the two functions is delimited by
these tags.
(Read also the notes in the previous section about these tags.)

     <pXw4Pa version="1.0">
     </pXw4Pa>

---

The GROUP tags are used to define the subarrays.
These tags use the attribute NAME to store the name (or index)
of the subarray.

     <group name="...">
     </group>

---

The ENTRY tags are used to define the single values.
These tags use the attribute NAME to store the name (or index)
of the value, and the attribute TYPE to define the type of data
of the value (valid types are "string", "integer", "double",
"boolean" and "NULL").

     <entry name="..." type="...">
     </entry>

---

The one exception is entries with a NULL value, which have no
closing tag:

     <entry name="..." type="NULL"/>

---

some more info:

1st. if you are going to write the XML by hand and you don't
     care about the index values, try to omit the NAME attribute:
     the read function will assign to each value/array the 1st
     numerical index available.

2nd. Undefined type of data (entries without TYPE attribute)
     are set to the string type by default.
     (If you're going to write an XML file by hand, you can omit
     the TYPE attribute for this kind of data.)

3rd. Write function always write both NAME and TYPE attributes.

____________________________________________________________________
10. demonstration.
¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
Please refer to the interactive documentation (pXw4Pa.doc.php)
for the demonstrations.

____________________________________________________________________
11. options.
¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯

"?v" (both functions)
"?v+" (for READ function V1.0 only)

   You can add the "?v" debug option ("v" means verbose) to the
   2 functions to display result of the processing sequence.

   example:

     $array=pXw4Pa_read('filename','?v');
     pXw4Pa_write($array,'filename','?v');

   Version 1.0 of the read function also lets you use the "?v+"
   option to display the types of data for each entry, along with
   the names and values.

   example:

     $array=pXw4Pa_read('filename','?v+');

---

"?dtd" (for WRITE function v1.0 only)

   This option lets you link the XML file written to the pXw4Pa.dtd
   file (DTD = Document Type Declaration).
   This file contains all the info about the labels and grammar
   used on a XML file written with pXw4Pa.
   This is required when you need to make valid XML.
   Remember to copy the DTD file to the same folder as the
   XML file.

   example:

     pXw4Pa_write($array,'filename','?dtd');

   This option doesn't allow you to pass a specified DTD filename
   value directly to the function, since usually the default
   filename is ok. If you really want to change this value read
   the last part of this section.

---

"?css=cssfilename"
OR "?xsl=xslfilename"
(both for WRITE function v1.0 only)

   These options let you attach a CSS (Cascade Style Sheet) or an
   XSL (eXtensible Stylesheet Language) file to the XML, which
   can be useful to display the content of the XML/array with
   a browser.
   Replace "(css/xsl)filename" with the filename of the CSS/XSL
   file you're going to use (along with URL or fullpath,
   if required).
   Remember to use the correct path of the CSS/XSL file.
   If you use only the filename, without URL or full path,
   the file must reside in the same folder as the XML.

   example:

     pXw4Pa_write($array,'filename','?css=cssfilename');
     pXw4Pa_write($array,'filename','?xsl=xslfilename');

   NOTE: A CSS file lets you display only the values of an XML
   files. To display also indexes and data types use XSL,
   instead.

   Here you can see some examples of XML, formatted using the
   CSS and XSL files provided with the pXw4Pa package.

   testcss.xml
   test1.xml
   test2.xml
   test3.xml
   test4.xml

   Note: The XSL files that you find in the pXw4Pa package don't
   display indexes which are not defined in the XML files.
   Each one of them is represented by a question mark or
   the undefined word.

   ---

   DTD, CSS and XSL options use global variables.
   This can be useful if you always use the same value(s):
   rather than adding the required option each time you call
   the write function, you can set up the corresponding global
   variable once, at the beginning of your code.
   These are the three global variables used:

   $_pXw4Pa["dtdfilename"]
   $_pXw4Pa["css"]
   $_pXw4Pa["xsl"]

   Note that the "dtdfilename" variable contain the value
   "pXw4Pa.dtd" by default.

   To use this feature you must assign to the correct variable
   the URL/path/filename of the required file.

   example:

     $_pXw4Pa["xsl"]="my_project/project.xsl";

____________________________________________________________________
12. limitations, bugs, issues...
¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
- It seems that the read function doesn't recognize the
  &nbsp; HTML entity.
  The problem can be temporarily solved by using the HTML
  entity:

     &#160;

- speed is now improved, but pXw4Pa may be not the best choice
  for real time management of large information archives.

---

If you've found a bug not listed here you can contact me to my
email address so we can try to fix it (for more info about how
to contact me, please read the next section)

____________________________________________________________________
13. future planning.
¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
No guarantees.

If someone wants to help this little project to grow, without
starting another one by his(/her)self, contact me at my email
address (at the top of this page).

Write only in Italian or clear English, please, and remember to put
the "[pXw4Pa v#.#]" text on the subject of your mail with the
correct version number of the code, to help me to find your mail
within the spam.

____________________________________________________________________
14. about implementing this code somewhere...
¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
...it would be nice to read the project name within the credits.
; )
And sending me an e-mail about it, would be nice too (my address
is on the top of this page; before to write me read also the note
on the previous section).
Thanks!!

---

You can also use one of the antipixel logos (the files are inside
the pXw4Pa package) if you want to add a link to this page
to your website.

There are five versions of the logo:

blue (pxw4pa_logo1.png)
orange (pxw4pa_logo2.png)
pink (pxw4pa_logo3.png)
green (pxw4pa_logo3.png)
and grey (pxw4pa_logo3.png)

choose the one you like (or which fits into your website style),
copy it to your site folder, and insert it in your HTML
using this code:


<a href="http://pxw4pa.sourceforge.net/index.html">
<IMG src="pXw4Pa_logo1.png" width="80" height="15"
     alt="pXw4Pa site logo" style="border-width: 0px;">
</A>

____________________________________________________________________
15. donations(?).
¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
In case someone will finds this script useful and wants to offer
me a beer or some money to help me pay for petrol,
this would be very much appreciated.

Sadly the situation is a bit complicated.
At the present time I have no job, and no money,
so no bank account (and no credit card).
A line of text on the SourceForge hosting Terms of Service
forbids soliciting donations in other ways than the one available,
which is paypal.
But to use paypal, if I recall correctly,
I need to have a credit card.
So... OFFICIALLY no donations.

Officially, I said..
...if you're reading this text it means that you're doing this from
your PC, since the text file version of the documentation
is not online. :P
And, since your PC is not part of the SourceForge hosting service,
here I can solicite a bit!.. ;)

You can still contact me via email, if you want.
You can always send a bank note to my home address.

____________________________________________________________________
16. acknowledgements.
¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
Many thanks to the author of Stickies Tom Revell, for correcting
my poor English within this documentation file!
(Don't blame him if you find some errors in the text:
probably it's only because I have added something after his last
correction to keep the page up to date).

Tom's webpage:
http://www.zhornsoftware.co.uk/

---

Thanks also to Megagun, Raptorjedi and the community of AnywhereBB:
http://anywherebb.com/

mirror:
http://www.0x44.com/

---

Many thanks to Mark Dickens for his help in testing release 1.0.

Mark's project page:
http://adodblite.sourceforge.net/index.php


Mark also provided some patches to fix bugs in v0.8.
You can find those patches here:
https://sourceforge.net/tracker/index.php?func=detail
&aid=1355603&group_id=143311&atid=755119

---

Thanks also to Francesco from the PHPItalia mailing list
http://www.domeus.it/groups/index.jsp;jsessionid=
FDF92B0F41459CDD49802F590B83FA9D;dom01?gid=236020

who provide me some info about the references behaviour in PHP4
and 5.

____________________________________________________________________
17. author's other projects.
¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
Don't forget to take a look at my 2nd project:

TRASHY
(Tags Resized and Alternative Syntax for Html made by Yayo)

http://trashy.sourceforge.net/index.php


####################################################################
# end of file.                                                     #
#                                                                  #
# May 6, 2006                                                      #
# yayo                                                             #
####################################################################