<?php

// HAWHAW: HTML and WML hybrid adapted webserver
// PHP class library
// Copyright (C) 2008 Norbert Huffschmid
// Last modified: 23. July 2008
//
// This library is free software; you can redistribute it and/or modify it under the
// terms of the GNU Library General Public License as published by the Free Software
// Foundation; either version 2 of the License, or (at your option) any later
// version.
//
// This library is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
// PARTICULAR PURPOSE. See the GNU Library General Public License for more details.
// http://www.gnu.org/copyleft/library.html
//
// If you modify this library, you have to make sure that the "powered by HAWHAW"
// copyright link below the display area is kept unchanged.
//
// For further information about this library and its license terms please visit:
// http://www.hawhaw.de/

###################################
#   BEGIN OF CONFIGURATION PART   #
###################################

# path to your TeraWurfl installation (if any)
# http://www.tera-wurfl.com/
# Tera-WURFL can be used OPTIONALLY for improved device detection
# recommended settings in tera_wurfl_config.php:
#   define("MATCH_TO_UA_PREFIX", false);
#   define("RETURN_GENERIC", true);
# un-comment and adapt the following define statement
#   in order to activate Tera-WURFL usage
//define("HAW_TERA_WURFL_PATH", "../TeraWurfl");

###################################
#    END OF CONFIGURATION PART    #
###################################

// miscellaneous constants
define("HAW_HAW_VERSION", "HAWHAW V5.21");
define("HAW_COPYRIGHT", "(C) Norbert Huffschmid");
if (defined("HAW_TERA_WURFL_PATH"))
  define("HAW_VERSION", HAW_HAW_VERSION . "/Tera-WURFL");
else
  define("HAW_VERSION", HAW_HAW_VERSION);

// constants for markup languages
define("HAW_HTML", 1);
define("HAW_WML",  2);
define("HAW_HDML", 3);
define("HAW_VXML", 4);

// constants for forced markup output
define("HAW_OUTPUT_AUTOMATIC", "automatic");
define("HAW_OUTPUT_BIGSCREEN", "bigscreen");
define("HAW_OUTPUT_WAP",       "wap");
define("HAW_OUTPUT_HDML",      "hdml");
define("HAW_OUTPUT_PDA",       "pda");
define("HAW_OUTPUT_IMODE",     "imode");
define("HAW_OUTPUT_MML",       "mml");
define("HAW_OUTPUT_VOICEXML",  "voicexml");
define("HAW_OUTPUT_XHTML",     "xhtml");
define("HAW_OUTPUT_LYNX",      "lynx");
define("HAW_OUTPUT_DOTMOBI",   "dotmobi");
// ----- proprietary output types (for debugging purposes only) ------
define("HAW_OUTPUT_UP",        "up");	// UP browser
define("HAW_OUTPUT_OWGUI",     "owgui");// Openwave GUI
define("HAW_OUTPUT_PVX",       "pvx");	// PublicVoiceXML

// constants for page elements
define("HAW_PLAINTEXT", 1);
define("HAW_IMAGE", 2);
define("HAW_TABLE", 3);
define("HAW_FORM", 4);
define("HAW_LINK", 5);
define("HAW_PHONE", 6);
define("HAW_LINKSET", 7);
define("HAW_INPUT", 8);
define("HAW_TEXTAREA", 9);
define("HAW_BANNER", 10);
define("HAW_SELECT", 11);
define("HAW_CHECKBOX", 12);
define("HAW_RADIO", 13);
define("HAW_HIDDEN", 14);
define("HAW_SUBMIT", 15);
define("HAW_RAW", 16);
define("HAW_RULE", 17);
define("HAW_VOICERECORDER", 18);
define("HAW_USERDEFINED", 19);   // synonym for HAW_PLUGIN (deprecated)
define("HAW_PLUGIN", 19);

// constants for page setup
define("HAW_ALIGN_LEFT", 1);
define("HAW_ALIGN_RIGHT", 2);
define("HAW_ALIGN_CENTER", 3);
define("HAW_NOTITLE", "");

// constants for text formatting
define("HAW_TEXTFORMAT_NORMAL", 0);
define("HAW_TEXTFORMAT_BOLD", 1);
define("HAW_TEXTFORMAT_UNDERLINE", 2);
define("HAW_TEXTFORMAT_ITALIC", 4);
define("HAW_TEXTFORMAT_BIG", 8);
define("HAW_TEXTFORMAT_SMALL", 16);
define("HAW_TEXTFORMAT_BOXED", 32);

// constants for form handling
define("HAW_METHOD_GET", 0);
define("HAW_METHOD_POST", 1);

// constants for input treatment
define("HAW_INPUT_TEXT", 0);
define("HAW_INPUT_PASSWORD", 1);

// constants for select treatment
define("HAW_NOTSELECTED", 0);
define("HAW_SELECTED", 1);
define("HAW_SELECT_POPUP", 0);
define("HAW_SELECT_SPIN", 1);

// constants for radio and checkbox treatment
define("HAW_NOTCHECKED", 0);
define("HAW_CHECKED", 1);

// constants for link treatment
define("HAW_NO_ACCESSKEY", -1);

// constants for HDML card types
define("HAW_HDML_DISPLAY", 0);
define("HAW_HDML_ENTRY", 1);
define("HAW_HDML_CHOICE", 2);
define("HAW_HDML_NODISPLAY", 3);

// constants for banners
define("HAW_TOP", 0);
define("HAW_BOTTOM", 1);
define("HAW_NOLINK", -1);

// constants for MML input modes
define("HAW_INPUT_HIRAGANA", 1);  // values correspond to according cHTML values!
define("HAW_INPUT_KATAKANA", 2);
define("HAW_INPUT_ALPHABET", 3);
define("HAW_INPUT_NUMERIC", 4);

// constants for VoiceXML
define("HAW_VOICE_PAUSE", 300);   // pause after text output
define("HAW_VOICE_ENUMERATE", 1); // activate enumeration

// constants for bigscreen default display
define("HAW_DISP_BGCOLOR",    "#00BB77"); // green HAWHAW display
define("HAW_DISP_LINKCOLOR",  "#004411"); // dark green
define("HAW_DISP_VLINKCOLOR", "#006633"); // lighter green
define("HAW_DISP_FACE",       "Arial,Times");

// constants for simulator handling
define("HAW_SIM_NONE",    0); // no simulator at all
define("HAW_SIM_CLASSIC", 1); // classic hawhaw simulator style
define("HAW_SIM_SKIN",    2); // skin-based (phone-) simulator


function HAW_specchar($input, $deck)
{
  // convert special characters

  $temp = htmlspecialchars($input); // translate &"<> to HTML entities
  $output = "";

  if ($deck->ml == HAW_WML)
    $temp = str_replace("$", "$$", $temp);     // escape $ character in WML
  elseif ($deck->ml == HAW_HDML)
    $temp = str_replace("$", "&dol;", $temp);  // escape $ character in HDML

  if (strstr($deck->charset,"iso-8859-1"))
  {
    // character set iso-8859-1 (trivial mapping 1:1)

    for ($i=0; $i<strlen($temp); $i++)
    {
      // do for each character of $temp

      if (ord(substr($temp, $i, 1)) > 127)
        // translate character into &#...; sequence
        $output .= "&#" . ord(substr($temp, $i, 1)) . ";";
      else
        // copy character unchanged
        $output .= substr($temp, $i, 1);
    }
  }
  else
  {
    // other character set than iso-8859-1

    if ($deck->unicodearray)
    {
      // array with mapping rules was prepared earlier

      if(!trim($temp))
        return $temp;

      while($temp!="")
      {
        // do for each character in string

        if (ord(substr($temp,0,1))>127)
        {
          $index = ord(substr($temp, 0, 1)); // ASCII value of first character from $temp

          if ($deck->unicodearray[$index])
          {
            // unicode stored for this one byte code
            // insert unicode and go 1 byte further

            $output .= "&#" . $deck->unicodearray[$index] . ";";
            $temp = substr($temp, 1, strlen($temp));
          }
          else
          {
            // check if there's a unibyte code stored for 1st two bytes

            $index = $index * 256 + ord(substr($temp, 1, 1));

            if ($deck->unicodearray[$index])
            {
              // unicode stored for this 2-byte code!
              // insert unicode and go 2 bytes further

              $output .= "&#" . $deck->unicodearray[$index] . ";";
              $temp = substr($temp, 2, strlen($temp));
            }
            else
            {
              // no mapping info for 1st 2 bytes available ==> leave it as it is

              $output .= substr($temp, 0, 1);
              $temp = substr($temp, 1, strlen($temp));
            }
          }
        }
        else
        {
          // character <= 127 ==> leave it as it is
          $output .= substr($temp, 0, 1);
          $temp = substr($temp, 1, strlen($temp));
        }
      }
    }

    else
      $output = $temp; // no mapping to unicode required
  }

  return($output);
}


function HAW_voice_audio($text, $audio_src, $pause, $deck)
{
  // print VoiceXML tags for audio output

  if ($audio_src)
    // play audio file if possible
    printf("<audio src=\"%s\">%s</audio>", $audio_src, $text);
  else
    // speak text only
    echo $text;

  if (($pause > 0) && $deck->supportsVXMLBreakTag)
    printf("<break time=\"%dms\"/>", $pause);
}


function HAW_voice_eventhandler($handler, $audio_array, $deck)
{
  // create VoiceXML event handler, e.g. "help", "nomatch", "noinput"

  if (count($audio_array) > 0)
  {
    while (list($key, $val) = each($audio_array))
    {
      if ($key > 0)
        $count = sprintf(" count=\"%d\"", $key+1);
      else
        $count = "";

      printf("<catch%s event=\"%s\">", $count, $handler);

      HAW_voice_audio(HAW_specchar($val["text"], $deck), $val["src"], 0, $deck);

      if ($val["url"])
        printf("<goto next=\"%s\"/>", $val["url"]);

      echo "</catch>\n"; 
    }
  }
}


function HAW_remove_invalid_gsl_chars($string)
{
  // remove all invalid GSL characters (according Nuance Grammar Developer's Guide)
  return(ereg_replace("[^0-9a-zA-Z@_'\.\-]", " ", $string));
}


function HAW_handle_forced_output(&$url, $hawoutput)
{
  // add hawoutput query parameter to url in order to force special HAWHAW output type

  if ($hawoutput)
  {
    // hawoutput parameter to be added

    if (strchr($url, '?'))
      $url .= "&hawoutput=" . $hawoutput;
    else
      $url .= "?hawoutput=" . $hawoutput;
  }
}






class HAW_hdmlcardset
{
  var $number_of_cards;
  var $card;            // array of cards
  var $title;
  var $final_action = "";    // action of last card
  var $defaults;        // default values of variables
  var $disable_cache;
  var $debug;
  var $charset;


  function HAW_hdmlcardset($title, $defaults, $disable_cache, $debug, $charset)
  {
    $this->title = $title;
    $this->defaults = $defaults;
    $this->disable_cache = $disable_cache;
    $this->debug = $debug;
    $this->charset = $charset;

    // initialize first card of cardset as DISPLAY card

    $this->card[0]["type"] = HAW_HDML_DISPLAY;

    $this->card[0]["options"] = " name=\"1\"";

    if ($title)
      $this->card[0]["options"] .= " title=\"$title\"";

    $this->number_of_cards = 1;
  }


  function add_display_content($display_content)
  {
    // enhance the display content of the current card with the received content

    // number_of_cards-1 is the index of the current card, i.e. the last card

    if ($this->card[$this->number_of_cards-1]["type"] == HAW_HDML_DISPLAY)
    {
      // current card is display card ==> continue with content

      if (isset($this->card[$this->number_of_cards-1]["display_content"]))
        $this->card[$this->number_of_cards-1]["display_content"] .= $display_content;
      else
        $this->card[$this->number_of_cards-1]["display_content"] = $display_content;
    }
    else
    {
      // current card is entry or choice card
      // ==> create new display card to display received content
      // ==> link current card to this new display card

      $this->card[$this->number_of_cards]["type"] = HAW_HDML_DISPLAY;

      $cardname = sprintf(" name=\"%d\"", $this->number_of_cards+1);
      $this->card[$this->number_of_cards]["options"] .= $cardname;

      if ($this->title)
        $this->card[$this->number_of_cards]["options"] .= " title=\"$this->title\"";

      $this->card[$this->number_of_cards]["display_content"] = $display_content;

      $action = sprintf("<action type=\"accept\" task=\"go\" dest=\"#%d\">\n",
                         $this->number_of_cards+1);
      $this->card[$this->number_of_cards-1]["action"] = $action;

      $this->number_of_cards++;
    }
  }


  function make_ui_card($options, $generic_content, $cardtype)
  {
    // make user interactive card (ENTRY or CHOICE card)

    if ($this->card[$this->number_of_cards-1]["type"] == HAW_HDML_DISPLAY)
    {
      // current card is display card

      // ==> make an entry/choice card out of it
      $this->card[$this->number_of_cards-1]["type"] = $cardtype;

      // append options to the already existing ones
      if (!isset($this->card[$this->number_of_cards-1]["options"]))
        $this->card[$this->number_of_cards-1]["options"] = "";

      $this->card[$this->number_of_cards-1]["options"] .= $options;

      // append received content to the already existing one
      if (!isset($this->card[$this->number_of_cards-1]["display_content"]))
        $this->card[$this->number_of_cards-1]["display_content"] = "";

      $this->card[$this->number_of_cards-1]["display_content"] .= $generic_content;
    }
    else
    {
      // current card is already entry or choice card
      // ==> create new entry/choice card
      // ==> link current card to this new entry/choice card

      $this->card[$this->number_of_cards]["type"] = $cardtype;

      $cardname = sprintf(" name=\"%d\"", $this->number_of_cards+1);

      if (!isset($this->card[$this->number_of_cards]["options"]))
        $this->card[$this->number_of_cards]["options"] = "";

      $this->card[$this->number_of_cards]["options"] .= $cardname;

      if ($this->title)
        $this->card[$this->number_of_cards]["options"] .= " title=\"$this->title\"";

      $this->card[$this->number_of_cards]["options"] .= $options;

      $this->card[$this->number_of_cards]["display_content"] = $generic_content;

      $action = sprintf("<action type=\"accept\" task=\"go\" dest=\"#%d\">\n",
                         $this->number_of_cards+1);
      $this->card[$this->number_of_cards-1]["action"] = $action;

      $this->number_of_cards++;
    }
  }


  function set_final_action($action)
  {
    $this->final_action = $action;
  }


  function create_hdmldeck()
  {
    if (!$this->debug)
    {
      $ct = sprintf("content-type: text/x-hdml;charset=%s", $this->charset);
      header($ct);
    }

    $ttl = ($this->disable_cache ? " TTL=\"0\"" : "");

    printf("<hdml version=\"3.0\" public=\"true\"%s>\n", $ttl);
    printf("<!-- Generated by %s %s -->\n", HAW_VERSION, HAW_COPYRIGHT);

    // create NODISPLAY card if it's necessary to initialize variables
    if ($this->defaults)
    {
      $vars = "";

      while (list($d_key, $d_val) = each($this->defaults))
        $vars .= sprintf("%s=%s&amp;", $d_val['name'], $d_val['value']);

      // strip terminating '&'
      $vars = substr($vars, 0, -5);

      echo "<nodisplay>\n";
      printf("<action type=\"accept\" task=\"go\" dest=\"#1\" vars=\"%s\">\n", $vars);
      echo "</nodisplay>\n";
    }

    // set action of last card
    $this->card[$this->number_of_cards-1]["action"] = $this->final_action;

    // create all cards of card set
    $i = 0;
    while ( $i < $this->number_of_cards )
    {
      if ($this->card[$i]["type"] == HAW_HDML_DISPLAY)
        $cardtype = "display";
      elseif ($this->card[$i]["type"] == HAW_HDML_ENTRY)
        $cardtype = "entry";
      elseif ($this->card[$i]["type"] == HAW_HDML_CHOICE)
        $cardtype = "choice";

      printf("<%s%s>\n", $cardtype, $this->card[$i]["options"]);
      printf("%s", $this->card[$i]["action"]);
      printf("%s", $this->card[$i]["display_content"]);
      printf("</%s>\n", $cardtype);

      $i++;
    }

    echo "</hdml>\n";
  }
};






/**
  This class is the top level class of all HAWHAW classes. Your page should consist
  of exactly one HAW_deck object. For WML browsers one deck with one card will be
  generated. For HDML browsers one deck including as much cards as necessary will
  generated. HTML browsers will receive a normal HTML page, PDA browsers will
  receive handheldfriendly HTML etc.
  <p>Do not overload HAW_deck objects! Remember that a lot of older WAP devices can not
  handle more than about 1400 byte of compiled data.
  <p><b>Examples:</b><p>
  $myPage = new HAW_deck();<br>
  $myPage = new HAW_deck("My WAP page");<br>
  $myPage = new HAW_deck("", HAW_ALIGN_CENTER);<br>
  $myPage = new HAW_deck("PDA edition", HAW_ALIGN_CENTER, HAW_OUTPUT_PDA);<br>
  ...<br>
  $myPage->set_bgcolor("blue");<br>
  ...<br>
  $myPage->add_text($myText);<br>
  ...<br>
  $myPage->create_page();
*/
class HAW_deck
{
  var $title;
  var $alignment;
  var $output;
  var $timeout;
  var $red_url;
  var $disable_cache = false;
  var $css = "";       // css file
  var $css_class = ""; // class of deck within css file
  var $ml;
  var $element = array();
  var $number_of_elements = 0;
  var $number_of_forms = 0;
  var $number_of_linksets = 0;
  var $number_of_links = 0;
  var $number_of_phones = 0;
  var $top_banners;
  var $number_of_top_banners = 0;
  var $bottom_banners;
  var $number_of_bottom_banners = 0;
  var $display_banners = false;
  var $link_brackets = true;
  var $waphome;
  var $hdmlcardset;

  // browser dependent properties
  var $desktopBrowser = false;  // non-mobile browser
  var $pureHTML;                // deprecated synonym for $desktopBrowser (keep for compatibilty reasons)
  var $PDAstyle = false;		// PDA browsers needs special HTML code
  var $iModestyle = false;		// cHTML too
  var $upbrowser = false;               // UP browser
  var $owgui_1_3 = false;               // Openwave GUI Extensions for WML 1.3
  var $MMLstyle = false;		// Mobile Markup Language
  var $lynx = false;			// Lynx text browser
  var $iPhoneAlike = false;		// iPhone, iPod Touch etc.
  var $xhtml = false;			// XHTML MP (mobile profile)
  var $gif_enabled = false;             // browser can not deal with GIF images
  var $submitViaLink = false;           // use link instead of <do>
  var $supportsVXMLBreakTag = true;     // false for PublicVoiceXML browser only
  var $MotorolaVoiceXML = false;        // for Motorola VoiceXML specifics
  var $css_enabled = false;             // support of cascading style sheets (HTML)
  var $xhtmlmp_preferred_mime_type = "application/vnd.wap.xhtml+xml"; // default mime type for XHTML Mobile Profile
  var $debug = false;                   // HAWHAW debugger off
  var $hawoutput = "";                  // no forced output

  // device simulator properies
  var $use_simulator = HAW_SIM_CLASSIC; // decide whether default simulator device is to be used
  var $skin = "";                       // contains css url, where skinning is defined

  // character set properties
  var $charset = "utf-8";          // default charset
  var $unicodemaptab;              // filename of cross mapping table to map country
                                   // specific character code into unicode
  var $unicodearray;               // array containing cross mapping table

  // language properties
  var $language = "";


  // display properties for HTML

  // page background properties
  var $bgcolor = "";
  var $background = "";

  // display (table) properties
  var $border = 8;
  var $disp_bgcolor = "";
  var $disp_background = "";
  var $width  = "200px";
  var $height = "200px";

  // text properties
  var $size = "";
  var $color = "";
  var $link_color = "";
  var $vlink_color = "";
  var $face = "";
  var $fontstyle_attrbs = "";

  // voice properties
  var $voice_links = "";
  var $voice_timeout = 0;
  var $voice_text = "";
  var $voice_audio_src = "";
  var $voice_jingle = "";
  var $voice_help = array();
  var $voice_noinput = array();
  var $voice_nomatch = array();
  var $voice_property = array();
  var $voice_navigator = array();

  /**
    Constructor
    @param title (optional, default: HAW_NOTITLE) <br>
       If a string is provided here, it will be displayed
       in the HTML title bar, respectively somewhere on the WAP display. Using a
       title you will normally have to spend one of your few lines on your WAP
       display. Consider that some WAP phones/SDK's and handheld devices don't
       display the title at all.
    @param alignment (optional, default: HAW_ALIGN_LEFT) <br>
       You can enter HAW_ALIGN_CENTER
       or HAW_ALIGN_RIGHT to modify the alignment of the whole page.
    @param output (optional, default: HAW_OUTPUT_AUTOMATIC) <br>
       You can override HAWHAW's automatic browser detection and force a certain
       output type.<br>
       Possible value are:<br>
       <ul>
       <li>HAW_OUTPUT_AUTOMATIC (default)</li>
       <li>HAW_OUTPUT_BIGSCREEN</li>
       <li>HAW_OUTPUT_WAP</li>
       <li>HAW_OUTPUT_HDML</li>
       <li>HAW_OUTPUT_PDA</li>
       <li>HAW_OUTPUT_IMODE</li>
       <li>HAW_OUTPUT_MML</li>
       <li>HAW_OUTPUT_VOICEXML</li>
       <li>HAW_OUTPUT_XHTML</li>
       <li>HAW_OUTPUT_LYNX</li>
       <li>HAW_OUTPUT_DOTMOBI</li>
       </ul><br>
       With this parameter you can offer dedicated links for PC's, WAP phones, PDA's,
       XHTML phones etc.
  */
  function HAW_deck($title=HAW_NOTITLE, $alignment=HAW_ALIGN_LEFT, $output=HAW_OUTPUT_AUTOMATIC)
  {
    // determine environment variables
    if (isset($_SERVER['HTTP_USER_AGENT']))
      $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    else
      $HTTP_USER_AGENT = "";

    if (isset($_SERVER['HTTP_ACCEPT']))
      $HTTP_ACCEPT = $_SERVER['HTTP_ACCEPT'];
    else
      $HTTP_ACCEPT = "";

    if (isset($_SERVER['HTTP_UA_OS']))
      $HTTP_UA_OS = $_SERVER['HTTP_UA_OS'];
    else
      $HTTP_UA_OS = "";

      if (isset($_SERVER['HTTP_HOST']))
      $HTTP_HOST = $_SERVER['HTTP_HOST'];
    else
      $HTTP_HOST = "";

    if (isset($_SERVER['SCRIPT_NAME']))
      $SCRIPT_NAME = $_SERVER['SCRIPT_NAME'];
    else
      $SCRIPT_NAME = "";

    $this->set_title($title);
    $this->set_alignment($alignment);

    $this->timeout = 0;
    $this->red_url = "";

    $this->waphome = "http://" . $HTTP_HOST . $SCRIPT_NAME;

    // query parameter "hawdebug" can be used to debug hawhaw output
    //     enter http://wap.yourdomain.com/yourscript.php?hawdebug=wap
    //     resp. other value defined above for HAW_OUTPUT_...
    // query parameter "hawoutput" can be used for overwrite output type
    //     enter http://wap.yourdomain.com/yourscript.php?hawoutput=wap
    global $HTTP_GET_VARS;
    $forced_output = "";

    if (isset ($HTTP_GET_VARS["hawdebug"]))
    {
      $forced_output = $HTTP_GET_VARS["hawdebug"];
      $this->debug = true;
    }
    elseif (isset ($HTTP_GET_VARS["hawoutput"]))
    {
      $forced_output = $HTTP_GET_VARS["hawoutput"];
      $this->hawoutput = $HTTP_GET_VARS["hawoutput"];
    }
    elseif ($output != HAW_OUTPUT_AUTOMATIC)
      $forced_output = $output;

    // manipulate HTTP environment variables in case of forced output
    if ($forced_output)
    {
      if ($forced_output == HAW_OUTPUT_BIGSCREEN)
      {
        $HTTP_ACCEPT = "";
        $HTTP_USER_AGENT = "MSIE";
      }
      elseif ($forced_output == HAW_OUTPUT_WAP)
      {
        $HTTP_ACCEPT = "text/vnd.wap.wml";
        $HTTP_USER_AGENT = "";
      }
      elseif ($forced_output == HAW_OUTPUT_HDML)
      {
        $HTTP_ACCEPT = "hdml;version=3.0";
        $HTTP_USER_AGENT = "";
      }
      elseif ($forced_output == HAW_OUTPUT_PDA)
      {
        $HTTP_ACCEPT = "";
        $HTTP_USER_AGENT = "avantgo";
      }
      elseif ($forced_output == HAW_OUTPUT_IMODE)
      {
        $HTTP_ACCEPT = "";
        $HTTP_USER_AGENT = "DoCoMo";
      }
      elseif ($forced_output == HAW_OUTPUT_MML)
      {
        $HTTP_ACCEPT = "";
        $HTTP_USER_AGENT = "J-";
      }
      elseif ($forced_output == HAW_OUTPUT_VOICEXML)
      {
        $HTTP_ACCEPT = "text/x-vxml";
        $HTTP_USER_AGENT = "Motorola VoxGateway/2.0";
      }
      elseif ($forced_output == HAW_OUTPUT_XHTML)
      {
        $HTTP_ACCEPT = "application/vnd.wap.xhtml+xml";
        $HTTP_USER_AGENT = "";
      }
      elseif ($forced_output == HAW_OUTPUT_LYNX)
      {
        $HTTP_ACCEPT = "";
        $HTTP_USER_AGENT = "Lynx";
      }
      elseif ($forced_output == HAW_OUTPUT_DOTMOBI)
      {
        $HTTP_ACCEPT = "";
        $HTTP_USER_AGENT = "someFictionalBrowser";
      }
      elseif ($forced_output == HAW_OUTPUT_UP)
      {
        $HTTP_ACCEPT = "text/vnd.wap.wml";
        $HTTP_USER_AGENT = "UP.Browser";
      }
      elseif ($forced_output == HAW_OUTPUT_OWGUI)
      {
        $HTTP_ACCEPT = "text/vnd.wap.wml";
        $HTTP_USER_AGENT = "UP/GUI...UP.Link ";
      }
      elseif ($forced_output == HAW_OUTPUT_PVX)
      {
        $HTTP_ACCEPT = "";
        $HTTP_USER_AGENT = "publicVoiceXML"; // user agent header of PublicVoiceXML browser
      }
    }

    $this->pureHTML = &$this->desktopBrowser; // synonyms for non-mobile browsers
    
    if (strstr(strtolower($HTTP_USER_AGENT), "lynx"))
      $this->lynx = true;
        
    if (strstr($HTTP_USER_AGENT, "(iPhone") ||
        strstr($HTTP_USER_AGENT, "(iPod") ||
        strstr($HTTP_USER_AGENT, "(Aspen Simulator"))
      $this->iPhoneAlike = true;    
    
    if (defined("HAW_TERA_WURFL_PATH") && !$forced_output)
    {
      $this->determine_capabilities_terawurfl();
    }
    else
    {
      $this->determine_capabilities_generic($HTTP_USER_AGENT, $HTTP_ACCEPT, $HTTP_UA_OS);
    }
  }


  /**
    Adds a HAW_text object to HAW_deck.
    @param text Some HAW_text object.
    @see HAW_text
  */
  function add_text($text)
  {
    if (!is_object($text))
      die("invalid argument in add_text()");

    $this->element[$this->number_of_elements] = $text;

    $this->number_of_elements++;
  }


  /**
    Adds a HAW_image object to HAW_deck.
    @param image Some HAW_image object.
    @see HAW_image
  */
  function add_image($image)
  {
    if (!is_object($image))
      die("invalid argument in add_image()");

    $this->element[$this->number_of_elements] = $image;

    $this->number_of_elements++;
  }


  /**
    Adds a HAW_table object to HAW_deck.
    @param table Some HAW_table object.
    @see HAW_table
  */
  function add_table($table)
  {
    if (!is_object($table))
      die("invalid argument in add_table()");

    $this->element[$this->number_of_elements] = $table;

    $this->number_of_elements++;
  }


  /**
    Adds a HAW_form object to HAW_deck.
    @param form Some HAW_form object.
    @see HAW_form
  */
  function add_form($form)
  {
    if (!is_object($form))
      die("invalid argument in add_form()");

    if ($this->number_of_forms > 0)
      die("only one form per deck allowed!");

    $this->element[$this->number_of_elements] = $form;

    $this->number_of_elements++;
    $this->number_of_forms++;
  }


  /**
    Adds a HAW_link object to HAW_deck.
    @param link Some HAW_link object.
    @see HAW_link
  */
  function add_link($link)
  {
    if (!is_object($link))
      die("invalid argument in add_link()");

    $this->element[$this->number_of_elements] = $link;

    $this->number_of_elements++;
    $this->number_of_links++;
  }


  /**
    Adds a HAW_phone object to HAW_deck.
    @param phone Some HAW_phone object.
    @see HAW_phone
  */
  function add_phone($phone)
  {
    if (!is_object($phone))
      die("invalid argument in add_phone()");

    $this->element[$this->number_of_elements] = $phone;

    $this->number_of_elements++;
    $this->number_of_phones++;
  }


  /**
    Adds a HAW_linkset object to HAW_deck.
    @param linkset Some HAW_linkset object.
    @see HAW_linkset
  */
  function add_linkset($linkset)
  {
    if (!is_object($linkset))
      die("invalid argument in add_linkset()");

    if ($this->number_of_linksets > 0)
      die("only one linkset per deck allowed!");

    $this->element[$this->number_of_elements] = $linkset;

    $this->number_of_elements++;
    $this->number_of_linksets++;
  }


  /**
    Adds a HAW_raw object to HAW_deck.
    @param raw Some HAW_raw object.
    @see HAW_raw
  */
  function add_raw($raw)
  {
    if (!is_object($raw))
      die("invalid argument in add_raw()");

    $this->element[$this->number_of_elements] = $raw;

    $this->number_of_elements++;
  }


  /**
    Adds some HAWHAW plugin to HAW_deck.<br>
    @param plugin Some user-defined plugin object.

    <pre>
    A HAWHAW plugin class definition should look like this:

      class HAW_plugin_foo // some class name of your choice
      {
        var $bar;   // some class variable declarations of your choice
      
        function HAW_plugin_foo(...)
        {
          # some constructor code (if required)
        }

        function set_bar($bar)
        {
          # as many user-defined functions as you need
        }

        # this member function is called by HAWHAW and MUST NOT be changed!
        function get_elementtype()
        {
          return HAW_PLUGIN;
        }
      
        function create($deck)
        {
          # this member function is called by HAWHAW and MUST be present!
          # it is in the programmers responsibility what kind of markup is created here!
          # you have access to all HAW_deck properties by evaluating $deck
        }
      };

      Usage: ...
             require_once("hawhaw.inc);
             require_once("HAW_plugin_foo.php");
             ...       
             $myClass = new HAW_plugin_foo("Cool output");
             $myClass->set_coolstuff($ringtone, $sms, $userlocation);
             $myDeck->add_plugin($myClass);
             ...
    </pre>
  */
  function add_plugin($plugin)
  {
    if (!is_object($plugin))
      die("invalid argument in add_plugin()");

    $this->element[$this->number_of_elements] = $plugin;

    $this->number_of_elements++;
  }


  /**
    Adds some user-defined HAWHAW object to HAW_deck.<br>
    @param udef Some user-defined object.
    @deprecated use HAW_deck::add_plugin() instead
  */
  function add_userdefined($udef)
  {
    $this->add_plugin($udef);
  }


  /**
    Adds a HAW_banner object to HAW_deck. <br>
    Note: Has no effect on WML/handheld pages.
    @param banner Some HAW_banner object.
    @param position (optional)<br>
      HAW_TOP: above HAW_deck<br>
      HAW_BOTTOM: beneath HAW_deck  (default)
    @see HAW_banner
  */
  function add_banner($banner, $position=HAW_BOTTOM)
  {
    if (!is_object($banner))
      die("invalid argument in add_banner()");

    if ($position == HAW_TOP)
    {
      $this->top_banners[$this->number_of_top_banners] = $banner;
      $this->number_of_top_banners++;
    }
    else
    {
      $this->bottom_banners[$this->number_of_bottom_banners] = $banner;
      $this->number_of_bottom_banners++;
    }
  }


  /**
    Adds a HAW_rule object to HAW_deck.
    @param rule Some HAW_rule object.
    @see HAW_rule
  */
  function add_rule($rule)
  {
    if (!is_object($rule))
      die("invalid argument in add_rule()");

    $this->element[$this->number_of_elements] = $rule;

    $this->number_of_elements++;
  }


  /**
    Adds a HAW_voicerecorder object to HAW_deck.
    @param voicerecorder Some HAW_voicerecorder object.
    @see HAW_voicerecorder
  */
  function add_voicerecorder($voicerecorder)
  {
    if (!is_object($voicerecorder))
      die("invalid argument in add_voicerecorder()");

    $this->element[$this->number_of_elements] = $voicerecorder;

    $this->number_of_elements++;
  }


  /**
    Redirects automatically after timeout to another URL. <br>
    Note: This feature can not be supported for HDML browsers, due to HDML's missing
    timer functionality. If you intend to serve HDML users, you should consider this
    by creating an additional link to <i>red_url</i>.
    @param timeout Some timeout value in seconds.
    @param red_url Some URL string.
  */
  function set_redirection($timeout, $red_url)
  {
    $this->timeout = $timeout;
    $this->red_url = $red_url;
  }


  /**
    Disables deck caching in the users client. <br>
    Note: Use this object function, if you intend to provide changing content under
    the same URL.
  */
  function disable_cache()
  {
    $this->disable_cache = true;
  }


  /**
    Enables mobile specific session support. <br>
    Note: Session handling for mobile devices is a little bit tricky. Cookies sometimes are
    not supported by devices/gateways and transient session ID's are to be considered as
    potential security issue. It is up to the programmer to decide which way to go by setting
    the according control flags in php.ini. <br>
    This function is helpful in case that trans_sid session handling applies: <br>
    As XML does not allow unescaped '&amp;' characters, the arg-separator has to be modified.
    And as WML/VoiceXML defines tags which are not defined in HTML, the PHP predefined
    url_rewriter tags have to be adapted. <br>
    Important: This function has be called before any session handling is done! Due to some
    bugs in previous PHP versions, it is highly recommended to use PHP version 4.1.1 or
    higher.
  */
  function enable_session()
  {
    // set session server options

    //ini_set('session.use_cookies', 0);         // do not use cookies
    //ini_set('session.use_trans_sid', 1);       // use transient sid support

    ini_set('arg_separator.output', '&amp;');  // '&' is not allowed in WML (XML)

    // url rewriter tags are dependent from markup language

    switch ($this->ml)
    {
      case HAW_HTML:
      {
        ini_set('url_rewriter.tags', 'a=href,form=option');
        break;
      }

      case HAW_WML:
      {
        ini_set('url_rewriter.tags', 'a=href,go=href,option=onpick');
        break;
      }

      case HAW_HDML:
      {
        ini_set('url_rewriter.tags', 'a=href');
        break;
      }

      case HAW_VXML:
      {
        ini_set('url_rewriter.tags', 'submit=next,link=next,goto=next');
        break;
      }
    }
  }


  /**
    Sets a given character set. <br>
    Note: For the iso-8859-1 character set all characters with value greater
    127 are automatically transformed into a "&amp;#dec;" sequence (unicode).
    For all other character sets a specific cross mapping table is
    required for the conversion into unicode. Usage of unicode cross
    mapping tables is optional for non-iso-8859-1 character sets.
    Mapping tables are available at:
    <a href="http://www.unicode.org/Public/MAPPINGS/" target="_blank">
    http://www.unicode.org/Public/MAPPINGS/</a>
    @param charset Character set used.<br>
           Default: utf-8 (until hawhaw.inc V5.19: iso-8859-1)
    @param unicodemaptab (optional)<br>
           Cross mapping table from country specific character coding to
           unicode (default: no mapping to unicode activated).<br>
           With this parameter you specify where HAWHAW will find the conversion table
           with the mapping rules. It is recommended to store the cross mapping table
           in the same directory where hawhaw.inc is located.<br>
           format: [PATH][Filename].[EXT] e.g. "../GB2312.TXT"
  */
  function set_charset($charset, $unicodemaptab="")
  {
    $this->charset = $charset;
    $this->unicodemaptab = $unicodemaptab;

    if ($unicodemaptab)
    {
      // cross mapping table is to be used

      if(!file_exists($unicodemaptab))
      {
        $errormsg =  "HAWHAW!<br>Cross mapping table \"$unicodemaptab\" not found!<br>";
        $errormsg .= "Please download your country-specific unicode cross mapping table from:<br>";
        $errormsg .= "<a href=http://www.unicode.org/Public/MAPPINGS/>http://www.unicode.org/Public/MAPPINGS/</a>";
        die("$errormsg");
      }

      // open cross mapping table file and create array with mapping info

      $filename = $unicodemaptab;
      $line=file($filename);

      while(list($key,$value)=each($line))
      {
        if (substr($value, 0, 2) == "0x") // skip comment lines
        {
          $pair = explode("	", $value); // tab separates code and unicode

          if ((strlen($pair[0]) == 6) && ($pair[0] < 0x8080))
            $this->unicodearray[hexdec($pair[0]) + 0x8080] = hexdec($pair[1]); // offset EUC
          else
            $this->unicodearray[hexdec($pair[0])] = hexdec($pair[1]);
        }
      }
    }
  }


  /**
    Sets a given language. <br>
    Note: Specifies the main language of the deck, as e.g. english, german,
    japanese or whatever. Language declaration is highly recommended in order
    to provide barrierfree content and as is required to validate a deck as
    Bobby AAA approved!
    @param language Language code according ISO 639, e.g. "en", "de", etc.
  */
  function set_language($language)
  {
    $this->language = $language;
  }


  /**
    Sets the window background color for a HTML (big-screen browser) page.
    Has no effect on WML/handheld pages.
    @param bgcolor See HTML specification for possible values (e.g. "#CCFFFF",
      "red", ...).
  */
  function set_bgcolor($bgcolor)
  {
    $this->bgcolor = $bgcolor;
  }


  /**
    Sets a css file for css-enabled browsers.
    @param css file with css (cascading style sheets) declarations.
  */
  function set_css($css)
  {
    $this->css = $css;
  }


  /**
    Sets a css class for this object in a (X)HTML page.
    @param css_class CSS class to be defined in the deck's central CSS file
  */
  function set_css_class($css_class)
  {
    $this->css_class = $css_class;
  }

  
  /**
    Sets a window wallpaper for a HTML (big-screen browser) page.
    Has no effect on WML/handheld pages.
    @param background e.g. "backgrnd.gif"
  */
  function set_background($background)
  {
    $this->background = $background;
  }


  /**
    Sets the thickness of the HTML display frame. Has no effect on WML/handheld pages.
    @param border Thickness is pixels (default: 8)
  */
  function set_border($border)
  {
    $this->border = $border;
  }


  /**
    Sets the display background color for a (X)HTML page. Has no effect on
    WML pages.
    @param disp_bgcolor See HTML specification for possible values (e.g. "#CCFFFF",
      "red", ...).
  */
  function set_disp_bgcolor($disp_bgcolor)
  {
    $this->disp_bgcolor = $disp_bgcolor;
  }


  /**
    Sets a display wallpaper for a (X)HTML page. Has no effect on WML
    pages.
    @param background e.g. "backgrnd.gif"
  */
  function set_disp_background($background)
  {
    $this->disp_background = $background;
  }


  /**
    Sets the display width for a HTML page. Has no effect on WML/handheld
    pages.
    @param width See HTML specification for possible values (e.g. "200px", "50%", ...).
  */
  function set_width($width)
  {
    if (is_int($width))
      $this->width = $width . "px"; // for compatibility reasons (until V5.10 HAWHAW interpreted int as px)
    else
      $this->width = $width;
  }


  /**
    Sets the display height for a HTML page. Has no effect on WML/handheld
    pages.
    @param height See HTML specification for possible values (e.g. "200px", "50%", ...).
  */
  function set_height($height)
  {
    if (is_int($height))
      $this->height = $height . "px";
    else
      $this->height = $height;
  }


  /**
    Sets the font size for all characters in a (X)HTML page. Has no effect on
    WML pages.
    @param size See HTML specification for possible values (e.g. "12pt", ...).
  */
  function set_size($size)
  {
    $this->size = $size;
  }


  /**
    Sets the color for all characters in a (X)HTML page. Has no effect on WML pages.
    Nevertheless, HAW_text objects can overwrite this value by defining their own color
    attributes.
    @param color See HTML specification for possible values (e.g. "#CCFFFF", "red",
       ...).
    @see HAW_text
  */
  function set_color($color)
  {
    $this->color = $color;
  }


  /**
    Sets the color of links in a (X)HTML page. Has no effect on
    WML pages.
    @param link_color See HTML specification for possible values (e.g. "#CCFFFF", "red",
       ...).
  */
  function set_link_color($link_color)
  {
    $this->link_color = $link_color;
  }


  /**
    Sets the color of visited links in a (X)HTML page. Has no effect on
    WML pages.
    @param vlink_color See HTML specification for possible values (e.g. "#CCFFFF", "red",
       ...).
  */
  function set_vlink_color($vlink_color)
  {
    $this->vlink_color = $vlink_color;
  }


  /**
    Sets the font for all characters in a (X)HTML page. Has no effect on
    WML pages.
    @param face See HTML specification for possible values (e.g. "Avalon",
       "Wide Latin").
  */
  function set_face($face)
  {
    $this->face = $face;
  }


  /**
    Sets the deck title (see also HAW_deck constructor).
    @param title deck title
  */
  function set_title($title)
  {
    $this->title = $title;
  }


  /**
    Sets the text alignment for the whole deck.
    @param alignment text alignment <br>
     HAW_ALIGN_LEFT (default)<br>
     HAW_ALIGN_CENTER<br>
     HAW_ALIGN_RIGHT
  */
  function set_alignment($alignment)
  {
    if ($alignment == HAW_ALIGN_CENTER)
      $this->alignment = "center";
    elseif ($alignment == HAW_ALIGN_RIGHT)
      $this->alignment = "right";
    else
      $this->alignment = "left";
  }


  /**
    Sets the URL of a website, a bigscreen-browsing user is invited to visit via WAP. <br>
    Note: On bigscreen browsers a small copyright link to the
    HAWHAW information page will be created automatically by HAWHAW. The information
    page invites the user to visit your site with a WAP phone.
    Therefore by default your hostname and your refering script will be part of this
    copyright link. You can modify this value, e.g. if your application leads the
    user across different pages, but you want to make visible the entry page only.
    @param waphome Some URL string.
  */
  function set_waphome($waphome)
  {
    $this->waphome = $waphome;
  }


  /**
    Activates or deactivated the device simulator on bigscreen browsers. <br>
    Note: If no sim parameter is provided, the HAWHAW default skin is used.
    HAWHAW output can be displayed in any kind of user-defined skin.
    See <a href="http://skin.hawhaw.de/skin.css" target="_blank">
    CSS file</a> of the HAWHAW default skin for more info.
    @param sim HAW_SIM_NONE | HAW_SIM_CLASSIC | URL of CSS file, where skin is defined (optional)
  */
  function use_simulator($sim="http://skin.hawhaw.de/skin.css")
  {
    if ($sim === HAW_SIM_NONE)
      $this->use_simulator = HAW_SIM_NONE;
    else if ($sim === HAW_SIM_CLASSIC)
      $this->use_simulator = HAW_SIM_CLASSIC;
    else if (is_string($sim))
    {
      $this->use_simulator = HAW_SIM_SKIN;
      $this->skin = $sim;
    }
    else
      die("invalid argument in use_simulator()");
  }
  

  /**
    Defines whether links are shown within square brackets or not.
    @param flag true (default) | false
  */
  function use_link_brackets($flag)
  {
    $this->link_brackets = $flag;
  }


  /**
    Sets deck-related text to be spoken by voice browsers. <br>
    @param text Some introducing text that will be spoken before any other dialog or
                text output is started.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
  */
  function set_voice_text($text, $audio_src="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_text()");

    $this->voice_text = $text;
    $this->voice_audio_src = $audio_src;
  }


  /**
    Play jingle before link &lt;label&gt;s are spoken. <br>
    Note: Voice users can not distinguish between plain text and link text. Playing
    a short jingle before each link will make voice navigation easier.
    @param url Some wav-file.
  */
  function set_voice_jingle($url)
  {
    if (!is_string($url))
      die("invalid argument in set_voice_jingle()");

    $this->voice_jingle = $url;
  }


  /**
    Sets help text for voice browsers. <br>
    @param text Some helpful information concerning this deck.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
    @param url Some other voice deck to go to (optional).
  */
  function set_voice_help($text, $audio_src="", $url="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_help()");

    $arr["text"] = $text;
    $arr["src"]  = $audio_src;
    $arr["url"]  = $url;

    $this->voice_help[] = $arr;
  }


  /**
    Sets noinput text for voice browsers. <br>
    @param text Some text to inform the user that no input has been received.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
    @param url Some other voice deck to go to (optional).
  */
  function set_voice_noinput($text, $audio_src="", $url="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_noinput()");

    $arr["text"] = $text;
    $arr["src"]  = $audio_src;
    $arr["url"]  = $url;

    $this->voice_noinput[] = $arr;
  }


  /**
    Sets nomatch text for voice browsers. <br>
    @param text Some text to complain that user input was not recognized.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
    @param url Some other voice deck to go to (optional).
  */
  function set_voice_nomatch($text, $audio_src="", $url="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_nomatch()");

    $arr["text"] = $text;
    $arr["src"]  = $audio_src;
    $arr["url"]  = $url;

    $this->voice_nomatch[] = $arr;
  }


  /**
    Sets a name/value property pair for VoiceXML decks. <br>
    Attention: This function should be used by experienced VoiceXML developers
    only! name/value combinations which are not compliant to the VoiceXML
    standard or are not supported by the voice platform, will throw an
    semantic error and terminate voice output. Please refer to the
    <a href="http://www.w3.org/TR/voicexml20/#dml6.3" target="_blank">
    W3C VoiceXML Recommendation (chapter 6.3)</a>
    for detailled info about valid name/value combinations.
    @param name Name of property (e.g. "bargein", "timeout", etc.)
    @param value Value of property (e.g. "false", "10s", etc.)
  */
  function set_voice_property($name, $value)
  {
    $arr["name"]  = $name;
    $arr["value"] = $value;

    $this->voice_property[] = $arr;
  }


  /**
    Defines a DTMF/voice grammar to repeat or skip voice output. <br>
    This function enables voice users to repeat or skip voice output which
    has been created by means of HAW_text objects.
    For both <i>repeat</i> and <i>forward</i> at least one trigger criteria (DTMF or voice)
    has to be defined. 
    HAW_text objects where user navigation should apply have to call <i>set_voice_navigation()</i>.
    @param repeat_dtmf DTMF key that triggers the repetition of the current output (e.g. "1", "2", "", etc.)
    @param repeat_voice What the user has to say to repeat the current output (e.g. "repeat", "")
    @param forward_dtmf DTMF key that terminates the current output (e.g. "0", "*", "", etc.)
    @param forward_voice What the user has to say to continue with the next output (e.g. "forward", "")
    @see HAW_text
  */
  function set_voice_navigator($repeat_dtmf, $repeat_voice, $forward_dtmf, $forward_voice)
  {
    $repeat_dtmf  = (string) $repeat_dtmf;
    $forward_dtmf = (string) $forward_dtmf;
 
    if (!ereg("^[0-9#\*]$", $repeat_dtmf) && !$repeat_voice)
      die("at least one repeat indication required in set_voice_navigator()");

    if (!ereg("^[0-9#\*]$", $forward_dtmf) && !$forward_voice)
      die("at least one forward indication required in set_voice_navigator()");

    $this->voice_navigator["repeat_dtmf"]   = $repeat_dtmf;
    $this->voice_navigator["repeat_voice"]  = $repeat_voice;
    $this->voice_navigator["forward_dtmf"]  = $forward_dtmf;
    $this->voice_navigator["forward_voice"] = $forward_voice;
  }


  /**
    Smooth contiguous text sections. <br>
    Note: This function affects VoiceXML output only.<br>
    Voice decks often are assembled by some HAW_text objects. Each HAW_text
    object creates a VoiceXML &lt;block&gt; element. On some voice browsers this
    results in an interruptive speech synthesis. This function coalesces directly
    neighbored HAW_text objects to one single HAW_text object and thus results in
    a smoothed voice perception.
  */
  function smooth_voice()
  {
    if ($this->ml == HAW_VXML)
    {
      // this function make sense for VoiceXML output only

      $i = 0;

      while (isset($this->element[$i]))
      {
        $current_element = $this->element[$i];

        if (isset($this->element[$i+1]))
        {
          $next_element = $this->element[$i+1];

          if (($current_element->get_elementtype() == HAW_PLAINTEXT) &&
              ($current_element->get_br() == 0) &&
              ($next_element->get_elementtype() == HAW_PLAINTEXT) &&
              ($next_element->get_br() == 0) &&
              ($current_element->get_attrib() == $next_element->get_attrib()))
          {
            // create single HAW_text object containing both text sections
            $merged_text = new HAW_text($current_element->get_text() . " " . $next_element->get_text(),
                                        $current_element->get_attrib());

            // adopt line breaks from next element
            $merged_text->set_br($next_element->get_br());

            // replace old two objects with new object
            array_splice($this->element, $i, 2, array($merged_text));
            $this->number_of_elements--;

            $i--; // continue with merged element
          }
        }

        $i++;
      }
    }
  }


  /**
    Get markup language evaluated for the detected browser. <br>
    @returns
    - HAW_HTML (HTML)<br>
    - HAW_WML (WML)<br>
    - HAW_HDML (HDML)<br>
    - HAW_VXML (VoiceXML)<br><br>
    Example:<br>
    ...<br>
    $myDeck = new HAW_deck();<br>
    if ($myDeck->get_markup_language() == HAW_VXML) {<br>
      # some VoiceXML stuff<br>
    }<br>
    else {<br>
      # some visual stuff<br>
    } 
  */
  function get_markup_language()
  {
    return $this->ml;
  }
  
  
  /**
    Creates the page in the according markup language. Depending on the clients
    browser type, HTML (pure HTML, handheldfriendly HTML, XHTML, i-mode cHTML, MML),
    WML, HDML or VoiceXML is thrown out.
  */
  function create_page()
  {
    global $haw_license_holder;
    global $haw_license_domain;
    global $haw_signature;
    global $haw_sig_text;
    global $haw_sig_link;

    // add hawoutput query parameter to redirection url, if required
    HAW_handle_forced_output($this->red_url, $this->hawoutput);

    if ($this->debug)
      header("content-type: text/plain");

    if ($this->disable_cache)
      header("cache-control: no-cache");

    if ($this->ml == HAW_HTML)
    {
      // create HTML page header

      if (!$this->debug)
      {
        if ($this->xhtml)
          $ct = "content-type: " . $this->xhtmlmp_preferred_mime_type;
        else
          $ct = sprintf("content-type: text/html;charset=%s", $this->charset);

        header($ct);
      }

      if ($this->xhtml)
      {
        // advice transcoding proxies (http://dev.mobi/node/611)
        header("cache-control: no-transform");
        header("vary: User-Agent, Accept");

        printf("<?xml version=\"1.0\" encoding=\"%s\"?>\n", $this->charset);

        if ($this->lynx)
          echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\" >\n";
        else
          echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\" \"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\" >\n";
      }
      elseif ($this->css_enabled)
        echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n";

      if ($this->language)
      {
        if ($this->xhtml)
          $language = sprintf(" xml:lang=\"%s\"", $this->language);
        else
          $language = sprintf(" lang=\"%s\"", $this->language);
      }
      else
        $language = "";

      if ($this->xhtml)
        // W3C mobileOK requires namespace attribute
        $namespace = " xmlns=\"http://www.w3.org/1999/xhtml\"";
      else
        $namespace = "";
        
      printf("<html%s%s>\n", $language, $namespace);
      echo "<head>\n";

      // validation issue: HTML does not allow XHTML-stylish "/>" within <head> part
      // HTML has set option SHORTTAG=YES in SGML declaration
      // ==> "/" closes the tag and ">" will be treated as text
      // <body> allows text, but <head> does not! 
      if ($this->xhtml)
        $endtag = "/>";
      else
        $endtag = ">";

      if (!$this->iModestyle && !$this->MMLstyle)
      {
        // cHTML and MML don't support meta tags

        if ($haw_license_domain)
          $license = " - registered for $haw_license_domain";
        else
          $license = "";

        printf("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=%s\" %s\n",
                $this->charset, $endtag);
        printf("<meta name=\"GENERATOR\" content=\"%s (PHP) %s%s\" %s\n",
               HAW_VERSION, HAW_COPYRIGHT, $license, $endtag);

        if ($this->timeout > 0)
        {
          printf("<meta http-equiv=\"refresh\" content=\"%d; URL=%s\" %s\n",
                  $this->timeout, HAW_specchar($this->red_url, $this), $endtag);
        }

        if ($this->disable_cache)
        {
          echo "<meta http-equiv=\"Cache-Control\" content=\"must-revalidate\" $endtag\n";
          echo "<meta http-equiv=\"Cache-Control\" content=\"no-cache\" $endtag\n";
          echo "<meta http-equiv=\"Cache-Control\" content=\"max-age=0\" $endtag\n";
          echo "<meta http-equiv=\"Expires\" content=\"0\" $endtag\n";
        }
      }

      if ($this->PDAstyle)
      {
        echo "<meta name=\"HandheldFriendly\" content=\"True\" $endtag\n";
      }

      if ($this->css_enabled && $this->css)
      {
        printf("<link href=\"%s\" type=\"text/css\" rel=\"stylesheet\" />\n", $this->css);
      }

      // init style properties
      $bgcolor = "";
      $background = "";
      $disp_background = "";
      $size = "";
      $color = "";
      $link_color = "";
      $vlink_color = "";
      $face = "";
      $bodystyle = "text-align: center; ";
      $css_style = "";

      if ($this->desktopBrowser || $this->PDAstyle || $this->xhtml)
      {
        if ($this->desktopBrowser)
        {
          // big-screen browser
 
          if ($this->bgcolor)
          {
            // set background color (=window background color) 
            $bgcolor = " bgcolor=\"" . $this->bgcolor . "\"";
            $bodystyle .= sprintf("background-color:%s; ", $this->bgcolor);
          }

          if ($this->background)
          {
            // set wallpaper (=window wallpaper)
            $background = " background=\"" . $this->background . "\"";
            $bodystyle .= sprintf("background-image:url(%s); ", $this->background);
          }

          if (!$this->css) // settings in css-file have priority
          {
            // set display defaults, if not assigned by yet
            if (!$this->disp_bgcolor)
              $this->disp_bgcolor = HAW_DISP_BGCOLOR;
            if (!$this->link_color)
              $this->link_color = HAW_DISP_LINKCOLOR;
            if (!$this->vlink_color)
              $this->vlink_color = HAW_DISP_VLINKCOLOR;
            if (!$this->face)
              $this->face = HAW_DISP_FACE;
          }
        }
        else
        {
          // XHTML or PDA

          if ($this->disp_bgcolor)
          {
            // set background color of mobile device
            $bgcolor = " bgcolor=\"" . $this->disp_bgcolor . "\"";
            $bodystyle .= sprintf("background-color:%s; ", $this->disp_bgcolor);
          }

          if ($this->disp_background)
          {
            // set wallpaper of mobile device
            $background = " background=\"" . $this->disp_background . "\"";
            $bodystyle .= sprintf("background-image:url(%s); ", $this->disp_background);
          }
        }

        if ($this->size)
        {
          // set the font size for all characters in a HTML created page
          $size = " size=\"" . $this->size . "\"";
          $bodystyle .= sprintf("font-size:%s; ", $this->size);
        }

        if ($this->color)
        {
          // set the color for all characters in a HTML created page
          $color = " color=\"" . $this->color . "\"";
          $bodystyle .= sprintf("color:%s; ", $this->color);
        }

        if ($this->link_color)
        {
          // set the color of links in a HTML created page
          $link_color = " link=\"" . $this->link_color . "\"";
          $css_style .= sprintf("a:link { color:%s; }\n", $this->link_color);
        }

        if ($this->vlink_color)
        {
          // set the color of visited links in a HTML created page
          $vlink_color = " vlink=\"" . $this->vlink_color . "\"";
          $css_style .= sprintf("a:visited { color:%s; }\n", $this->vlink_color);
        }

        if ($this->face)
        {
          // set the font for all characters in a HTML created page
          $face = " face=\"" . $this->face . "\"";
          $bodystyle .= sprintf("font-family:%s; ", $this->face);
        }

        $this->fontstyle_attrbs = $size . $color . $face;
      }

      printf("<title>%s</title>\n", HAW_specchar($this->title, $this));

      if ($this->desktopBrowser && ($this->use_simulator == HAW_SIM_SKIN) && $this->css_enabled)
        // use HAWHAW default- or user-defined skin
        printf("<link rel=\"stylesheet\" type=\"text/css\" href=\"%s\" $endtag\n", $this->skin);

      if ($this->css_enabled)
        printf("<style type=\"text/css\">\n<!--\nbody { %s}\n%s-->\n</style>\n", $bodystyle, $css_style);

      echo "</head>\n";

      if ($this->css_enabled)
        echo "<body>\n";
      else
        printf("<body%s%s%s%s>\n", $bgcolor, $background, $link_color, $vlink_color);

      if ($this->display_banners)
      {
        if ($this->number_of_top_banners > 0)
        {
          echo "<center>\n";

            for ($i=0; $i<$this->number_of_top_banners; $i++)
            {
              // display banners at the top of the HTML page
              $banner = $this->top_banners[$i];
              $banner->create();
            }

          echo "</center>\n";
        }
      }

      if ($this->desktopBrowser)
      {
        if ($this->css_enabled && ($this->use_simulator == HAW_SIM_SKIN))
        {
          // use skin design
          echo "<div id=\"skin\">\n";

          if ($this->css_class)
            $class_param = " class=\"" . $this->css_class . "\"";
          else
            $class_param = "";
          
          printf("<div id=\"display\"%s>\n", $class_param);
        }
        else if ($this->use_simulator == HAW_SIM_CLASSIC)
        {
          // use classic HAWHAW design
          printf("<div id=\"classic\" style=\"background-color: %s; background-image: url(%s); border: %dpx solid #aaa; padding: 8px; width: %s; height: %s; margin: 0px auto; overflow: auto;\">\n",
                 $this->disp_bgcolor, $this->disp_background, $this->border, $this->width, $this->height);
        }
      }

      if (!$this->css_enabled && $this->fontstyle_attrbs)
      {
        // write style attributes, if any
        printf("<font%s>\n", $this->fontstyle_attrbs);
      }
    }
    else
    {
      // determine default values for WML, HDML and VXML form elements

      while (list($e_key, $e_val) = each($this->element))
      {
        if ($e_val->get_elementtype() == HAW_FORM)
        {
          // one (and only one!) form exists

          $form = $e_val;
          $defaults = $form->get_defaults();
        }
      }

      if ($this->ml == HAW_WML)
      {
        // create WML page header
        if (!$this->debug)
        {
          $ct = sprintf("content-type: text/vnd.wap.wml");
          header($ct);
        }

        if ($this->disable_cache)
        {
          // not all WAP clients interprete meta directives!
          // disable caching by sending HTTP content-location header with unique value

          if (isset($_SERVER['REQUEST_URI']))
            $request_uri = $_SERVER['REQUEST_URI'];
          else
            $request_uri = "";

          if (strchr($request_uri, "?"))
            // request URI already contains parameter(s)
            $header= sprintf("content-location: %s&hawcid=%s", $request_uri, date("U"));
          else
            // no parameters in URI
            $header= sprintf("content-location: %s?hawcid=%s", $request_uri, date("U"));

          header($header);
        }

        echo "<?xml version=\"1.0\"?" . ">\n";

        if ($this->owgui_1_3)
          echo "<!DOCTYPE wml PUBLIC \"-//OPENWAVE.COM//DTD WML 1.3//EN\" \"http://www.openwave.COM/dtd/wml13.dtd\" >\n";
        else
          echo "<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/DTD/wml_1.1.xml\">\n";

        printf("<!-- Generated by %s %s -->\n", HAW_VERSION, HAW_COPYRIGHT);

        if ($this->language)
          $language = sprintf(" xml:lang=\"%s\"", $this->language);
        else
          $language = "";

        printf("<wml%s>\n", $language);

        if ($this->disable_cache)
        {
          echo "<head>\n";
          echo "<meta http-equiv=\"Cache-Control\" content=\"must-revalidate\" forua=\"true\"/>\n";
          echo "<meta http-equiv=\"Cache-Control\" content=\"no-cache\" forua=\"true\"/>\n";
          echo "<meta http-equiv=\"Cache-Control\" content=\"max-age=0\" forua=\"true\"/>\n";
          echo "<meta http-equiv=\"Expires\" content=\"0\" forua=\"true\"/>\n";
          echo "<meta http-equiv=\"Pragma\" content=\"no-cache\" forua=\"true\"/>\n";
          echo "</head>\n";
        }

        if ($this->title)
          $title = " title=\"" . HAW_specchar($this->title,$this) . "\"";
        else
          $title = "";

        printf("<card%s>\n", $title);

        if (isset ($defaults) && $defaults)
        {
          // default values exist

          // set variables each time the card is enter in forward direction ...

          echo "<onevent type=\"onenterforward\">\n";
          echo "<refresh>\n";

          // initialize all WML variables with their default values
          while (list($d_key, $d_val) = each($defaults))
            printf("<setvar name=\"%s\" value=\"%s\"/>\n",
                   $d_val['name'], HAW_specchar($d_val['value'], $this));

          reset($defaults);

          echo "</refresh>\n";
          echo "</onevent>\n";

          // ... and backward direction

          echo "<onevent type=\"onenterbackward\">\n";
          echo "<refresh>\n";

          while (list($d_key, $d_val) = each($defaults))
            printf("<setvar name=\"%s\" value=\"%s\"/>\n", $d_val['name'], $d_val['value']);

          echo "</refresh>\n";
          echo "</onevent>\n";
        }

        // set redirection timeout
        if ($this->timeout > 0)
        {
           echo "<onevent type=\"ontimer\">\n";
           printf("<go href=\"%s\"/>\n", HAW_specchar($this->red_url, $this));
           echo "</onevent>\n";
           printf("<timer value=\"%d\"/>\n", $this->timeout*10);
        }

        // define <back> softkey
        echo "<do type=\"prev\" label=\"Back\">\n";
        echo "<prev/>\n";
        echo "</do>\n";
      }
      elseif ($this->ml == HAW_HDML)
      {
        // create HDML card set structure

        if (!isset($defaults))
          $defaults = array();

        $this->hdmlcardset = new HAW_hdmlcardset(HAW_specchar($this->title, $this),
                                                 $defaults, $this->disable_cache,
                                                 $this->debug, $this->charset);
      }
      elseif ($this->ml == HAW_VXML)
      {
        // create VXML page header
        if (!$this->debug)
        {
          $ct = sprintf("content-type: application/vxml+xml");
          header($ct);
        }

        printf("<?xml version=\"1.0\" encoding=\"%s\"?>\n", $this->charset);

        printf("<!-- Generated by %s %s -->\n", HAW_VERSION, HAW_COPYRIGHT);

        // language declaration breaks Voxeo Motorola voice browser!!!
        if ($this->language && !$this->MotorolaVoiceXML)
          $language = sprintf(" xml:lang=\"%s\"", $this->language);
        else
          $language = "";

        printf("<vxml%s version=\"2.0\">\n", $language);

        if ($this->disable_cache)
          echo "<meta http-equiv=\"Expires\" content=\"0\"/>\n";

        // define voice deck properties
        while (list($key, $val) = each($this->voice_property))
          printf("<property name=\"%s\" value=\"%s\"/>\n", $val["name"], $val["value"]);

        // Voxeo-specific TTS settings
        if ($this->MotorolaVoiceXML)
        {
          if ($this->language == "fr")
            echo "<property name=\"nuance.core.tts.ResourceName\" value=\"fr-FR.default\"/>\n";
          if ($this->language == "es")
            echo "<property name=\"nuance.core.tts.ResourceName\" value=\"es-MX.default\"/>\n";
        }

        echo "<form>\n";

        if (count($this->voice_navigator) > 0)
        {
          // allow user navigation in dedicated prompts

          echo "<var name=\"nav_counter\"/>\n";

          if (strlen($this->voice_navigator["repeat_dtmf"]) > 0)
            $dtmf = sprintf(" dtmf=\"%s\"", $this->voice_navigator["repeat_dtmf"]);
          else
            $dtmf = "";

          if ($this->voice_navigator["repeat_voice"])
            $voice_grammar = sprintf("<grammar>[%s]</grammar>", $this->voice_navigator["repeat_voice"]);
          else
            $voice_grammar = "";

          printf("<link%s event=\"repeat\">%s</link>\n", $dtmf, $voice_grammar); // define repeat link

          if (strlen($this->voice_navigator["forward_dtmf"]) > 0)
            $dtmf = sprintf(" dtmf=\"%s\"", $this->voice_navigator["forward_dtmf"]);
          else
            $dtmf = "";

          if ($this->voice_navigator["forward_voice"])
            $voice_grammar = sprintf("<grammar>[%s]</grammar>", $this->voice_navigator["forward_voice"]);
          else
            $voice_grammar = "";

          printf("<link%s event=\"forward\">%s</link>\n", $dtmf, $voice_grammar); // define forward link

          // define catch handler for links
          echo "<catch event=\"repeat\"><goto expritem=\"'block' + nav_counter\"/></catch>\n";
          echo "<catch event=\"forward\"><goto expritem=\"'block' + nav_counter + 'end'\"/></catch>\n";
        }

        if ($this->voice_text || $this->voice_audio_src)
        {
          // create introducing audio output for VoiceXML deck

          echo "<block><prompt>";

          HAW_voice_audio(HAW_specchar($this->voice_text, $this),
                        $this->voice_audio_src, HAW_VOICE_PAUSE, $this);

          echo "</prompt></block>\n";
        }
      }
    }

    // text-align aligns left, centered or right
    $divstyle = sprintf("text-align:%s;", $this->alignment);

    // position:relative is needed to work-around MSIE's Peekaboo bug ...
    if (isset($_SERVER['HTTP_USER_AGENT']) && strstr($_SERVER['HTTP_USER_AGENT'], " MSIE "))
      $divstyle .= " position:relative;";

    if ($this->ml == HAW_HTML)
    {
      if ($this->css_enabled)
      {
        if ($this->css_class)
        {
          $class = $this->css_class;

          if (isset($_SERVER['HTTP_USER_AGENT']) && strstr($_SERVER['HTTP_USER_AGENT'], " MSIE "))
            $class .= " peekaboo"; // CSS hook for peekaboo handling
        }
        else
        {
          if (isset($_SERVER['HTTP_USER_AGENT']) && strstr($_SERVER['HTTP_USER_AGENT'], " MSIE "))
            $class = "peekaboo";
          else
            $class = "";
        }
        
        if ($class)
          $class_param = " class=\"" . $class . "\"";
        else
          $class_param = "";
          
        printf("<div id=\"canvas\" style=\"%s\"%s>\n", $divstyle, $class_param);
        echo "<span id=\"hawcsshook1\"></span>\n"; // universal CSS hook for toolbars etc.
      }
      else
        printf("<div id=\"canvas\" align=\"%s\">\n", $this->alignment);
    }
    elseif ($this->ml == HAW_WML)
      printf("<p align=\"%s\">\n", $this->alignment);

    $i = 0;
    while (isset($this->element[$i]))
    {
      $page_element = $this->element[$i];
      switch ($page_element->get_elementtype())
      {
        case HAW_PLAINTEXT:
        case HAW_IMAGE:
        case HAW_TABLE:
        case HAW_FORM:
        case HAW_LINK:
        case HAW_PHONE:
        case HAW_LINKSET:
        case HAW_RAW:
        case HAW_PLUGIN:
        case HAW_RULE:
        case HAW_VOICERECORDER:
        {
          $element = $this->element[$i];
          $element->create($this);

          break;
        }
      }

      $i++;
    }

    if ($this->ml == HAW_HTML)
    {
      // create HTML page end

      //  ATTENTION!
      //
      //  DO NOT REMOVE THE COPYRIGHT LINK WITHOUT PERMISSION!
      //  IF YOU DO SO, YOU ARE VIOLATING THE LICENSE TERMS
      //  OF THIS SOFTWARE! YOU HAVE TO PAY NOTHING FOR THIS
      //  SOFTWARE, SO PLEASE BE SO FAIR TO ACCEPT THE RULES.
      //  IF YOU DON'T, YOUR WEBSITE WILL AT LEAST BE LISTED IN
      //  THE HAWHAW HALL OF SHAME!
      //  PLEASE REFER TO THE LIBRARY HEADER AND THE HAWHAW
      //  HOMEPAGE FOR MORE INFORMATION.

      echo "</div>\n";

      if (!$this->css_enabled && $this->fontstyle_attrbs)
        echo "</font>\n";

      if ($this->desktopBrowser)
      {
        if ($this->css_enabled && ($this->use_simulator == HAW_SIM_SKIN))
        {
          // terminate display divs
          echo "</div>\n";
          echo "</div>\n";
        }
        
        if ($this->use_simulator == HAW_SIM_CLASSIC)
          echo "</div>\n"; // terminate classic div

        if (!$this->lynx)
        {
          $signature = "";
          $default_text = "Powered by HAWHAW (C)";
          if ($haw_license_holder && $haw_signature)
          {
            if ($haw_signature == 1)
              $signature = "<small>" . $default_text . "</small>\n";
            else
              if ($haw_sig_text)
                if ($haw_sig_link)
                  $signature = sprintf("<a href=\"%s\" target=\"_blank\"><small>%s</small></a>\n", $haw_sig_link, $haw_sig_text);
                else
                  $signature = sprintf("<small>%s</small>\n", $haw_sig_text);
          }
          else
            $signature = sprintf("<a href=\"http://info.hawhaw.de/index.htm?host=%s\" target=\"_blank\"><small>%s</small></a>\n",
                                 $this->waphome, $default_text);

          if ($signature && ($this->use_simulator == HAW_SIM_NONE))
            printf("<br /><span style=\"font-size:8pt;\">%s</span>\n", $signature);
          else
            echo $signature;
        }
      }

      if ($this->display_banners)
      {
        if ($this->number_of_bottom_banners > 0)
        {
          echo "<center>\n";

            for ($i=0; $i<$this->number_of_bottom_banners; $i++)
            {
              // display banners at the bottom of the HTML page
              $banner = $this->bottom_banners[$i];
              $banner->create();
            }

          echo "</center>\n";
        }
      }

      echo "</body>\n";
      echo "</html>\n";
    }
    elseif ($this->ml == HAW_WML)
    {
      // create WML page end
      echo "</p>\n";
      echo "</card>\n";
      echo "</wml>\n";
    }
    elseif ($this->ml == HAW_HDML)
    {
      // create HDML page from hdml card set structure
      $cardset = $this->hdmlcardset;
      $cardset->create_hdmldeck();
    }
    elseif ($this->ml == HAW_VXML)
    {
      // create VoiceXML page end

      // set redirection timeout
      if ($this->red_url && ($this->timeout == 0))
      {
        // special handling for timeout value 0 in VoiceXML:
        // go immediately to next page after all text has been spoken
        // should be used for pure voice apps only!!!
        printf("<block><goto next=\"%s\" /></block>\n", HAW_specchar($this->red_url, $this));
      }
      elseif ($this->timeout > 0)
      {
        // redirect after <timout> to another URL
        // (define dummy grammar in case that no other grammar is active)
        printf("<field><grammar>[(hawhaw really rocks)]</grammar><prompt timeout=\"%ds\"/><noinput><goto next=\"%s\"/></noinput><nomatch /></field>\n",
               $this->timeout, HAW_specchar($this->red_url, $this));
      }
      elseif($this->voice_timeout > 0)
      {
        // there is at least one voice link active
        // wait longest link timeout value until disconnect is forced

        printf("<field><prompt timeout=\"%ds\"/>", $this->voice_timeout);

        // test for user-defined noinput event handler
        if (!isset($this->voice_noinput) || (count($this->voice_noinput) == 0))
          echo "<noinput><exit/></noinput>"; // terminate in case of no input

        echo "</field>\n";
      }

      echo "</form>\n";

      if ($this->voice_links)
        echo $this->voice_links;

      // create event handlers
      HAW_voice_eventhandler("help",    $this->voice_help,    $this);
      HAW_voice_eventhandler("noinput", $this->voice_noinput, $this);
      HAW_voice_eventhandler("nomatch", $this->voice_nomatch, $this);

      echo "</vxml>\n";
    }
  }
  
  function determine_capabilities_generic($HTTP_USER_AGENT, $HTTP_ACCEPT, $HTTP_UA_OS)
  {
    // make this only once for performance reasons
    $ua_low  = strtolower($HTTP_USER_AGENT);
    $acc_low = strtolower($HTTP_ACCEPT);
    
    if (strstr($ua_low, "docomo") ||
        strstr($ua_low, "portalmmm") ||
        strstr($ua_low, "o2imode") ||
        strstr($ua_low, "opera ") ||
        strstr($ua_low, "reqwirelessweb") ||
        strstr($acc_low, "vnd.wap.xhtml"))
    {
      $this->ml = HAW_HTML;   // create HTML (even when device accepts text/vnd.wap.wml too!)
    }
    elseif (strstr($acc_low, "text/vnd.wap.wml"))
    {
      $this->ml = HAW_WML;  // create WML

      if (strstr($HTTP_USER_AGENT, "UP/") ||
          strstr($HTTP_USER_AGENT, "UP.B"))
        $this->upbrowser = true; // UP browser

      if ((strstr($HTTP_USER_AGENT, "UP/") ||
           strstr($HTTP_USER_AGENT, "UP.B")) &&
           strstr($HTTP_USER_AGENT, "GUI") &&
           strstr($HTTP_USER_AGENT, "UP.Link")) // Non-UP.Link gateways sometimes have problems!
        $this->owgui_1_3 = true; // device accepts Openwave GUI extensions for WML 1.3
    }
    elseif (strstr($acc_low, "hdml;version=3.")) // HDML 3.0 and 3.1
      $this->ml = HAW_HDML; // create HDML
    elseif (strstr($acc_low, "vxml") ||               // VoiceXML signalled in accept header
            strstr($acc_low, "voicexml") ||           // alternative accept header
            strstr($HTTP_USER_AGENT, "OpenVXI") ||    // Speechworks OpenVXI
            strstr($ua_low, "publicvoicexml") || // PublicVoiceXML voice browser
            strstr($ua_low, "hastenix") ||       // hawhaw asterisk adapter
            strstr($HTTP_USER_AGENT, "Tellme"))  // Tellme studio voice browser
    {
      $this->ml = HAW_VXML; // create VoiceXML

      // PublicVoiceXML browser cannot handle <break> elements!!!
      if (strstr($ua_low, "publicvoicexml"))
      {
        $this->supportsVXMLBreakTag = false;
      }

      // detect Motorola VoiceXML platform
      if (strstr($ua_low, "motorola"))
      {
        $this->MotorolaVoiceXML = true;
      }
    }
    else
    {
      if (strstr($HTTP_USER_AGENT, "Mozilla") ||
          strstr($HTTP_USER_AGENT, "MSIE") ||
          strstr($ua_low, "lynx") ||
          strstr($ua_low, "avantgo") ||
          strstr($ua_low, "pendragonweb") ||
          strstr($ua_low, "blackberry") ||
          strstr($ua_low, "j-"))
        $this->ml = HAW_HTML;   // HTML-based browser
      else
      {
      	if (!$HTTP_ACCEPT ||
      	     (strcmp(substr($_SERVER['HTTP_HOST'], -5), '.mobi') == 0))
      	{
          // let's be dotmobi compliant ...
          $this->ml = HAW_HTML;
          $this->xhtml = true;
          $this->gif_enabled = true;
          $this->css_enabled = true;
      	}
      	else
          $this->ml = HAW_WML;    // try it with WML
      }
    }

    // guess some non-mobile browsers
    if (strstr($HTTP_USER_AGENT, "MSIE") ||      // can be WIN CE!
        strstr($HTTP_USER_AGENT, "Firefox") ||
        strstr($HTTP_USER_AGENT, "Macintosh") || // Safari desktop browser
        strstr($HTTP_USER_AGENT, "Iceweasel") ||
        strstr($HTTP_USER_AGENT, "Konqueror") ||
        strstr($HTTP_USER_AGENT, "Opera"))       // can be Opera Mini!
    {
      $this->desktopBrowser = true;
      $this->css_enabled = true;
      $this->display_banners = true;
    }
    
    if (strstr($acc_low, "vnd.wap.xhtml") ||
        strstr($ua_low, "opera mini") ||
        strstr($ua_low, "google wireless transcoder") ||
        (strstr($ua_low, "symbianos") && strstr($acc_low, "xhtml+xml")) ||
        ($this->iPhoneAlike == true) ||
        strstr($HTTP_USER_AGENT, "(Linux; U; Android") ||
        strstr($HTTP_USER_AGENT, "Kindle/"))
    {
      // XHTML device detected
      $this->xhtml = true;
      $this->gif_enabled = true;
      $this->css_enabled = true;
      $this->desktopBrowser = false;
      $this->display_banners = false;
    }
    elseif ( strstr($ua_low, "avantgo") ||
             strstr($ua_low, "reqwirelessweb") ||
             strstr($ua_low, "pendragonweb") ||
             strstr($ua_low, "palmos") ||
             strstr($ua_low, "palmsource") ||
             strstr($ua_low, "/palm") ||
             strstr($ua_low, "windows ce") ||
             strstr($ua_low, "minimo") ||
             strstr($ua_low, "wm5 pie") ||
             strstr($ua_low, "wm6 pie") ||
             strstr($ua_low, "blackberry") ||
             strstr($ua_low, "nitro)") ||
             strstr(strtolower($HTTP_UA_OS), "windows ce") ||
             strstr($HTTP_USER_AGENT, "Mozilla/4.7"))
    {
      // PDA browser detected  (or poor old Netscape 4.7x which messes with CSS)
      $this->PDAstyle = true;
      $this->css_enabled = false;
      $this->desktopBrowser = false;
      $this->display_banners = false;
    }
    elseif (strstr($ua_low, "docomo") ||
            strstr($ua_low, "portalmmm") ||
            strstr($ua_low, "o2imode"))
    {
      // i-mode browser detected
      $this->iModestyle = true;
      $this->desktopBrowser = false;
      $this->display_banners = false;
    }
    elseif (strstr($ua_low, "j-"))
    {
      // MML browser detected
      $this->MMLstyle = true;
      $this->desktopBrowser = false;
      $this->display_banners = false;
    }
    elseif ($this->lynx == true)
    {
      // lynx text browser
      $this->css_enabled = true; // enabled in the meaning of silently discarding it ...
      $this->desktopBrowser = true;
      $this->xhtmlmp_preferred_mime_type = "text/html";
    }

    // determine if browser is able to display GIF images
    if (($this->ml == HAW_HTML) ||
        (strstr($acc_low, "image/gif")) ||
        (strstr($ua_low, "t68")))
      $this->gif_enabled = true; // browsers can display GIF

    // determine how forms are to be transmitted 
    if (strstr($ua_low, "ericsson") ||
        (strstr($ua_low, "nokia")))
      $this->submitViaLink = true; // with some WAP devices it's quite difficult to submit WML forms

    // set alternative mime-type for some XHTML Mobile Profile devices 
    if ($this->iPhoneAlike == true)
      $this->xhtmlmp_preferred_mime_type = "text/html";
  }
  
  function determine_capabilities_terawurfl()
  {
    require_once(dirname(__FILE__) . '/' . HAW_TERA_WURFL_PATH . '/tera_wurfl_config.php');
    require_once(WURFL_CLASS_FILE);

    $wurflObj = new tera_wurfl();

    // get the capabilites from the user agent and take a look at the
    // HTTP-ACCEPT headers in case the user agent is not found
    $identified = $wurflObj->GetDeviceCapabilitiesFromAgent($_SERVER['HTTP_USER_AGENT'], true);
    
    if ($identified)
    {
      $cap = $wurflObj->capabilities;

      if ($cap['product_info']['is_wireless_device'])
      {
        // wireless device    	

        $this->desktopBrowser = false;
        $this->display_banners = false;
      
        if ($cap['markup']['xhtml_support_level'] >= 1)
        {
          // XHTML-capable device
          $this->ml = HAW_HTML;
          $this->xhtml = true;
          $this->css_enabled = true;

          if (isset($cap['xhtml_ui']['xhtmlmp_preferred_mime_type']))
            $this->xhtmlmp_preferred_mime_type = $cap['xhtml_ui']['xhtmlmp_preferred_mime_type'];
        }
        else if ($cap['markup']['html_wi_imode_html_1'] ||
                 $cap['markup']['html_wi_imode_html_2'] ||
                 $cap['markup']['html_wi_imode_html_3'] ||
                 $cap['markup']['html_wi_imode_html_4'] ||
                 $cap['markup']['html_wi_imode_html_5'])
        {
          // i-Mode device
          $this->ml = HAW_HTML;
          $this->iModestyle = true;
        }
        else if ($cap['markup']['html_web_3_2'] ||
                 $cap['markup']['html_web_4_0'])
        {
          // HTML-prefering device
          $this->ml = HAW_HTML;
          $this->PDAstyle = true;
        }
        else if ($cap['markup']['wml_1_1'] ||
                 $cap['markup']['wml_1_2'] ||
                 $cap['markup']['wml_1_3'])
        {
          // WML device
          $this->ml = HAW_WML;

          if ($cap['wml_ui']['opwv_wml_extensions_support'])
            $this->owgui_1_3 = true; // device accepts Openwave GUI extensions for WML 1.3
        }
      }
      else
      {
        // no wireless device

        if ($cap['markup']['voicexml'])
        {
          $this->ml = HAW_VXML;
        }
        else
        {
          // treat it as normal web browser
          $this->ml = HAW_HTML;
          $this->css_enabled = true;
        }
      }
    
      if ($cap['image_format']['gif'])
        $this->gif_enabled = true;
    }
    else
    {
      // Tera-WURFL was not able to identify device
      $this->desktopBrowser = false;
      $this->display_banners = false;
      $this->ml = HAW_HTML;
      $this->xhtml = true;
      $this->css_enabled = true;
      $this->gif_enabled = true;
    }
  }
};






/**
  This class defines a form with various possible input elements. The input elements
  have to be defined as separate objects and are linked to the form with a special
  "add" function. One HAW_deck object can contain only one HAW_form object.
  <p><b>Examples:</b><p>
  $myPage = new HAW_deck(...);<br>
  ...<br>
  $myForm = new HAW_form("/mynextpage.php");<br>
  $myText = new HAW_text(...);<br>
  $myForm->add_text($myText);<br>
  $myInput = new HAW_input(...);<br>
  $myForm->add_input($myInput);<br>
  $mySubmit = new HAW_submit(...);<br>
  $myForm->add_submit($mySubmit);<br>
  ...<br>
  $myPage->add_form($myForm);<br>
  ...<br>
  $myPage->create_page();
  @see HAW_text
  @see HAW_image
  @see HAW_table
  @see HAW_input
  @see HAW_textarea
  @see HAW_select
  @see HAW_radio
  @see HAW_checkbox
  @see HAW_hidden
  @see HAW_submit
  @see HAW_rule
*/
class HAW_form
{
  var $url;
  var $method;
  var $element;
  var $number_of_elements = 0;
  var $number_of_submitobjects = 0;
  var $voice_text = "";
  var $voice_audio_src = "";

  /**
    Constructor
    @param url Address where the user input is sent to.<br>
    @param method (optional)<br>
      HAW_METHOD_GET  (default)<br>
      HAW_METHOD_POST
  */
  function HAW_form($url, $method=HAW_METHOD_GET)
  {
    if (!is_int($method) || (($method != HAW_METHOD_GET) && ($method != HAW_METHOD_POST)))
      die("invalid method in HAW_form: " . $method . " (possible values: HAW_METHOD_GET or HAW_METHOD_POST)");

    $this->url = $url;
    $this->method = $method;
  }


  /**
    Adds a HAW_text object to HAW_form.
    @param text Some HAW_text object.
    @see HAW_text
  */
  function add_text($text)
  {
    if (!is_object($text))
      die("invalid argument in add_text()");

    $this->element[$this->number_of_elements] = $text;

    $this->number_of_elements++;
  }


  /**
    Adds a HAW_image object to HAW_form.
    @param image Some HAW_image object.
    @see HAW_image
  */
  function add_image($image)
  {
    if (!is_object($image))
      die("invalid argument in add_image()");

    $this->element[$this->number_of_elements] = $image;

    $this->number_of_elements++;
  }


  /**
    Adds a HAW_table object to HAW_form.
    @param table Some HAW_table object.
    @see HAW_table
  */
  function add_table($table)
  {
    if (!is_object($table))
      die("invalid argument in add_table()");

    $this->element[$this->number_of_elements] = $table;

    $this->number_of_elements++;
  }


  /**
    Adds a HAW_input object to HAW_form.
    @param input Some HAW_input object.
    @see HAW_input
  */
  function add_input($input)
  {
    if (!is_object($input))
      die("invalid argument in add_input()");

    $this->element[$this->number_of_elements] = $input;

    $this->number_of_elements++;
  }


  /**
    Adds a HAW_textarea object to HAW_form.
    @param textarea Some HAW_textarea object.
    @see HAW_textarea
  */
  function add_textarea($textarea)
  {
    if (!is_object($textarea))
      die("invalid argument in add_textarea()");

    $this->element[$this->number_of_elements] = $textarea;

    $this->number_of_elements++;
  }


  /**
    Adds a HAW_select object to HAW_form.
    @param select Some HAW_select object.
    @see HAW_select
  */
  function add_select($select)
  {
    if (!is_object($select))
      die("invalid argument in add_select()");

    $this->element[$this->number_of_elements] = $select;

    $this->number_of_elements++;
  }


  /**
    Adds a HAW_radio object to HAW_form.
    @param radio Some HAW_radio object.
    @see HAW_radio
  */
  function add_radio($radio)
  {
    if (!is_object($radio))
      die("invalid argument in add_radio()");

    $this->element[$this->number_of_elements] = $radio;

    $this->number_of_elements++;
  }


  /**
    Adds a HAW_checkbox object to HAW_form.
    @param checkbox Some HAW_checkbox object.
    @see HAW_checkbox
  */
  function add_checkbox($checkbox)
  {
    if (!is_object($checkbox))
      die("invalid argument in add_checkbox()");

    $this->element[$this->number_of_elements] = $checkbox;

    $this->number_of_elements++;
  }


  /**
    Adds a HAW_hidden object to HAW_form.
    @param hidden Some HAW_hidden object.
    @see HAW_hidden
  */
  function add_hidden($hidden)
  {
    if (!is_object($hidden))
      die("invalid argument in add_hidden()");

    $this->element[$this->number_of_elements] = $hidden;

    $this->number_of_elements++;
  }


  /**
    Adds a HAW_submit object to HAW_form.
    @param submit Some HAW_submit object.
    @see HAW_submit
  */
  function add_submit($submit)
  {
    if (!is_object($submit))
      die("invalid argument in add_submit()");

    if ($this->number_of_submitobjects > 0)
      die("only one HAW_submit object per form allowed!");

    $this->element[$this->number_of_elements] = $submit;

    $this->number_of_elements++;
    $this->number_of_submitobjects++;
  }


  /**
    Adds a HAW_raw object to HAW_form.
    @param raw_markup_object Some HAW_raw object.
    @see HAW_raw
  */
  function add_raw($raw)
  {
    if (!is_object($raw))
      die("invalid argument in add_raw()");

    $this->element[$this->number_of_elements] = $raw;

    $this->number_of_elements++;
  }


  /**
    Adds some HAWHAW plugin object to HAW_form
    @param plugin Some user-defined plugin object.
    @see HAW_deck::add_plugin()
  */
  function add_plugin($plugin)
  {
    if (!is_object($plugin))
      die("invalid argument in add_plugin()");

    $this->element[$this->number_of_elements] = $plugin;

    $this->number_of_elements++;
  }


  /**
    Adds some user-defined HAWHAW object to HAW_form
    @param udef Some user-defined object.
    @deprecated use HAW_form::add_plugin() instead
  */
  function add_userdefined($udef)
  {
    $this->add_plugin($udef);
  }


  /**
    Adds a HAW_rule object to HAW_form.
    @param rule Some HAW_rule object.
    @see HAW_rule
  */
  function add_rule($rule)
  {
    if (!is_object($rule))
      die("invalid argument in add_rule()");

    $this->element[$this->number_of_elements] = $rule;

    $this->number_of_elements++;
  }


  /**
    Sets form-related text to be spoken by voice browsers. <br>
    @param text Some introducing text that will be spoken before any dialog
                is started.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
  */
  function set_voice_text($text, $audio_src="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_text()");

    $this->voice_text = $text;
    $this->voice_audio_src = $audio_src;
  }


  function get_defaults()
  {
    $defaults = array();

    $i = 0;
    while (list($key, $val) = each($this->element))
    {
      switch ($val->get_elementtype())
      {
        case HAW_CHECKBOX:
        {
          $checkbox = $val;

          if ($checkbox->is_checked())
          {
            $defaults[$i]["name"]  = $checkbox->get_name();
            $defaults[$i]["value"] = $checkbox->get_value();
            $i++;
          }

          break;
        }

        case HAW_INPUT:
        case HAW_TEXTAREA:
        case HAW_SELECT:
        case HAW_RADIO:
        case HAW_HIDDEN:
        {
          $element = $val;

          $defaults[$i]["name"]  = $element->get_name();
          $defaults[$i]["value"] = $element->get_value();
          $i++;

          break;
        }
      }
    }

    return $defaults;
  }

  function get_elementtype()
  {
    return HAW_FORM;
  }

  function create(&$deck)
  {
    // add hawoutput query parameter to form, if required
    if ($deck->hawoutput)
    {
      $hidden_hawoutput = new HAW_hidden("hawoutput", $deck->hawoutput);
      $this->add_hidden($hidden_hawoutput);
    }

    // determine all elements that have to be submitted

    $i = 0;
    $varnames = null;
    reset($this->element); //set the array pointer to first element
    while (list($key, $val) = each($this->element))
    {
      switch ($val->get_elementtype())
      {
        case HAW_INPUT:
        case HAW_TEXTAREA:
        case HAW_SELECT:
        case HAW_CHECKBOX:
        case HAW_RADIO:
        case HAW_HIDDEN:
        {
          $element = $val;
          $varnames[$i] = $element->get_name();
          $i++;
        }
      }
    }

    if ($deck->ml == HAW_HTML)
    {
      // start tag of HTML form
      if ($this->method == HAW_METHOD_POST)
        $method = " method=\"post\"";
      else
        $method = " method=\"get\"";

      printf("<form action=\"%s\"%s>\n", $this->url, $method);

      if ($deck->xhtml)
        echo "<div id=\"hawform\">\n"; // needed for validation
    }
      // not necessary in WML, HDML and VoiceXML!

    if ($deck->ml == HAW_VXML)
    {
      if ($this->voice_text || $this->voice_audio_src)
      {
        // create introducing audio output for VoiceXML form
    
        echo "<block><prompt>";
  
        HAW_voice_audio(HAW_specchar($this->voice_text, $deck),
                        $this->voice_audio_src, HAW_VOICE_PAUSE, $deck);

        echo "</prompt></block>\n";
      }
    }

    $i = 0;
    while (isset($this->element[$i]))
    {
      $form_element = $this->element[$i];
      switch ($form_element->get_elementtype())
      {
        case HAW_PLAINTEXT:
        case HAW_IMAGE:
        case HAW_TABLE:
        case HAW_INPUT:
        case HAW_TEXTAREA:
        case HAW_SELECT:
        case HAW_RADIO:
        case HAW_CHECKBOX:
        case HAW_HIDDEN:
        case HAW_RAW:
        case HAW_RULE:
        case HAW_PLUGIN:
        {
          $form_element->create($deck);
          break;
        }

        case HAW_SUBMIT:
        {
          $submit = $this->element[$i];
          $submit->create($deck, $varnames, $this->url, $this->method);
          break;
        }

      }

      $i++;
    }

    if ($deck->ml == HAW_HTML)
    {
      if ($deck->xhtml)
        echo "</div>\n";

      // terminate HTML form
      echo "</form>\n";
    }
  }
};






/**
  This class allows to insert plain text into a HAW_deck, HAW_form or HAW_table object.
  <p><b>Examples:</b><p>
  $myText1 = new HAW_text("Hello WAP!");<br>
  $myText2 = new HAW_text("Welcome to HAWHAW", HAW_TEXTFORMAT_BOLD);<br>
  $myText3 = new HAW_text("Good Morning", HAW_TEXTFORMAT_BOLD | HAW_TEXTFORMAT_BIG);<br>
  <br>
  $myText3->set_br(2);<br>
  @see HAW_deck
  @see HAW_form
  @see HAW_row
*/
class HAW_text
{
  var $text;
  var $attrib;
  var $color;
  var $boxcolor;
  var $css_class;
  var $br;
  var $voice_text;
  var $voice_audio_src;
  var $voice_navigation;

  /**
    Constructor
    @param text Some string you want to display
    @param attrib (optional)<br>
      HAW_TEXTFORMAT_NORMAL  (default)<br>
      HAW_TEXTFORMAT_BOLD<br>
      HAW_TEXTFORMAT_UNDERLINE<br>
      HAW_TEXTFORMAT_ITALIC<br>
      HAW_TEXTFORMAT_BIG<br>
      HAW_TEXTFORMAT_SMALL<br>
      HAW_TEXTFORMAT_BOXED (text in a colored box for headings etc.)
  */
  function HAW_text($text, $attrib=HAW_TEXTFORMAT_NORMAL)
  {
    $this->text = $text;
    $this->attrib = $attrib;
    $this->color = "";
    $this->boxcolor = "";
    $this->css_class = "";
    $this->br = 1; // default: 1 line break after text
    $this->voice_text = $text;
    $this->voice_audio_src = "";
    $this->voice_navigation = false;
  }

  /**
    Sets the color of this object in a (X)HTML page.
    @param color See HTML specification for possible values (e.g. "#CCFFFF", "red",
       ...).
    @param boxcolor (optional)<br>
       box background for attribute HAW_TEXTFORMAT_BOXED
  */
  function set_color($color, $boxcolor="")
  {
    $this->color = $color;
    $this->boxcolor = $boxcolor;
  }

  /**
    Sets a css class for this object in a (X)HTML page.
    @param css_class CSS class to be defined in the deck's central CSS file
  */
  function set_css_class($css_class)
  {
    $this->css_class = $css_class;
  }

  /**
    Sets the number of line breaks (CRLF) after text. (default: 1)
    @param br Some number of line breaks.
  */
  function set_br($br)
  {
    if (!is_int($br) || ($br < 0))
      die("invalid argument in set_br()");

    $this->br = $br;
  }

  /**
    Sets text to be spoken by voice browsers. <br>
    @param text Some alternative text that replaces original &lt;text&gt;.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
  */
  function set_voice_text($text, $audio_src="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_text()");

    $this->voice_text = $text;
    $this->voice_audio_src = $audio_src;
  }

  /**
    Turns on the voice navigation capability for this object. <br>
    Note: See <i>set_voice_navigator(...)</i> in class HAW_deck.
    @see HAW_deck
  */
  function set_voice_navigation()
  {
    $this->voice_navigation = true;
  }

  function get_text()
  {
    return $this->text;
  }

  function get_color()
  {
    return $this->color;
  }

  function get_br()
  {
    return $this->br;
  }

  function get_attrib()
  {
    return $this->attrib;
  }

  function get_elementtype()
  {
    return HAW_PLAINTEXT;
  }

  function create(&$deck)
  {
    if ($deck->ml == HAW_HDML)
    {
      // HDML

      if ($deck->alignment != "left")
        $deck->hdmlcardset->add_display_content("<" . $deck->alignment . ">\n");

      // print text
      if ($this->text)
      {
        $content = sprintf("%s\n", HAW_specchar($this->text, $deck));
        $deck->hdmlcardset->add_display_content($content);
      }

      // create required amount of carriage return's
      $br = "";
      for ($i=0; $i < $this->br; $i++)
        $br .= "<br>\n";

      $deck->hdmlcardset->add_display_content($br);
    }
    elseif(($deck->ml == HAW_HTML) || ($deck->ml == HAW_WML))
    {
      // HTML or WML

      if (($deck->ml == HAW_HTML) && $deck->css_enabled && $this->css_class)
      {
        printf("<span class=\"%s\">\n", $this->css_class);
      }
      
      if (($this->attrib & HAW_TEXTFORMAT_BOXED) && ($deck->ml == HAW_HTML))
      {
        // determine text and background color, if not already assigned

        if (!$this->color)
          $this->color = $deck->disp_bgcolor;
        if (!$this->color)
          $this->color = "#FFFFFF"; // default: white text

        if (!$this->boxcolor)
          $this->boxcolor = $deck->color;
        if (!$this->boxcolor)
          $this->boxcolor = "#000000"; // default: on black background

        if ($deck->css_enabled)
          printf("<div id=\"hawtextbox\" style=\"background-color:%s; margin:0px;\">\n",
                 $this->boxcolor);
        else
        {
          printf("<table border=\"0\" bgcolor=\"%s\" width=\"100%%\"><tr><td><font%s>\n",
                 $this->boxcolor, $deck->fontstyle_attrbs);
  
          // align text in (table-)box
          printf("<div id=\"hawtextbox\" align=\"%s\">\n", $deck->alignment);
        }

        // decrement line breaks because div/table already causes 1 br
        if ($this->br >= 1)
          $this->br--;
      }
      
      if ($this->attrib & HAW_TEXTFORMAT_BOLD)
        echo "<b>\n";

      if ($this->attrib & HAW_TEXTFORMAT_UNDERLINE)
      {
        if ($deck->css_enabled)
          echo "<span style=\"text-decoration:underline;\">\n";
        else
          echo "<u>\n";
      }

      if ($this->attrib & HAW_TEXTFORMAT_ITALIC)
        echo "<i>\n";

      if ($this->attrib & HAW_TEXTFORMAT_BIG)
        echo "<big>\n";

      if ($this->attrib & HAW_TEXTFORMAT_SMALL)
        echo "<small>\n";

      if (($deck->ml == HAW_HTML) && $this->color)
      {
        if ($deck->css_enabled)
          printf("<span style=\"color:%s;\">\n", $this->color);
        else
          printf("<font color=\"%s\">", $this->color);
      }

      // print text
      if (isset($this->text))
        printf("%s\n", HAW_specchar($this->text, $deck));

      if (($deck->ml == HAW_HTML) && $this->color)
      {
        if ($deck->css_enabled)
          echo "</span>";
        else
          echo "</font>";
      }

      if ($this->attrib & HAW_TEXTFORMAT_SMALL)
        echo "</small>\n";

      if ($this->attrib & HAW_TEXTFORMAT_BIG)
        echo "</big>\n";

      if ($this->attrib & HAW_TEXTFORMAT_ITALIC)
        echo "</i>\n";

      if ($this->attrib & HAW_TEXTFORMAT_UNDERLINE)
      {
        if ($deck->css_enabled)
          echo "</span>\n";
        else
          echo "</u>\n";
      }

      if ($this->attrib & HAW_TEXTFORMAT_BOLD)
        echo "</b>\n";

      if (($this->attrib & HAW_TEXTFORMAT_BOXED) && ($deck->ml == HAW_HTML))
      {
        if ($deck->css_enabled)
          echo "</div>\n";
        else
          echo "</div></font></td></tr></table>\n";
      }

      // create required amount of carriage return's
      for ($i=0; $i < $this->br; $i++)
        echo "<br />\n";

      if (($deck->ml == HAW_HTML) && $deck->css_enabled && $this->css_class)
        echo "</span>\n";
    }
    elseif($deck->ml == HAW_VXML)
    {
      // VoiceXML

      if ($this->voice_navigation)
      {
        // enable navigation (repeat/forward)
        static $block_counter = 0;
        printf("<block name=\"block%d\"><assign name=\"nav_counter\" expr=\"%d\"/><prompt>",
               $block_counter, $block_counter);        
      }
      else
        echo "<block><prompt>";
  
      $pause = $this->br * HAW_VOICE_PAUSE; // short pause for each break

      // remove leading commas, dots etc. which may appear after link objects
      HAW_voice_audio(ereg_replace("^[\?!,;.]", " ", HAW_specchar($this->voice_text, $deck)),
                      $this->voice_audio_src, $pause, $deck);
  
      echo "</prompt></block>\n";

      if ($this->voice_navigation)
      {
        // create artificial field to control VoiceXML sequencing
        printf("<field name=\"dummy%d\">\n", $block_counter);
        echo "<property name=\"timeout\" value=\"0.5s\"/>\n";
        echo "<grammar>[(hawhaw really rocks)]</grammar>\n";
        printf("<noinput><assign name=\"dummy%d\" expr=\"true\"/></noinput>\n", $block_counter);
        echo "</field>\n";

        // create block end where forward will go to
        printf("<block name=\"block%dend\"/>\n", $block_counter++);
      }
    }
  }
};






/**
  This class allows to insert bitmap images into a HAW_deck, HAW_form or HAW_table
  object.
  <p><b>Examples:</b><p>
  $myImage1 = new HAW_image("my_image.wbmp", "my_image.gif", ":-)");<br>
  $myImage2 = new HAW_image("my_image.wbmp", "my_image.gif", ":-)", "my_image.bmp");<br>
  $myImage2->set_br(1);<br>
  @see HAW_deck
  @see HAW_form
  @see HAW_row
*/
class HAW_image
{
  var $src_wbmp;
  var $src_html;
  var $alt;
  var $src_bmp;
  var $br;
  var $localsrc;
  var $chtml_icon;
  var $mml_icon;
  var $html_width;
  var $html_height;
  var $voice_text;
  var $voice_audio_src;

  /**
    Constructor
    @param src_wbmp Your bitmap in WAP-conform .wbmp format.
    @param src_html Your bitmap in .gif, .jpg or any other HTML compatible format.
    @param alt Alternative text for your bitmap. Will be displayed if the client can
       display none of your graphic formats.
    @param src_bmp (optional)<br>your bitmap in monochrome .bmp format. If the
       browser signals in the HTTP request header, that he's only able to display
       image/bmp and not image/vnd.wap.wbmp (e.g. the UPSim 3.2 did so), this image
       will be sent.
  */
  function HAW_image($src_wbmp, $src_html, $alt, $src_bmp="")
  {
    $this->src_wbmp = $src_wbmp;
    $this->src_html = $src_html;
    $this->alt      = $alt;
    $this->src_bmp  = $src_bmp;
    $this->br = 0;		// default: no line break after image
    $this->localsrc = "";	// no localsrc attribute
    $this->chtml_icon = 0;	// no cHTML icon
    $this->mml_icon = 0;	// no MML icon
    $this->html_width = ""; // not specified
    $this->html_height = "";// not specified
    $this->voice_text = "";
    $this->voice_audio_src = "";
  }

  /**
    Sets the number of line breaks (CRLF) after the image. (default: 0)
    @param br Some number of line breaks.
  */
  function set_br($br)
  {
    if (!is_int($br) || ($br < 0))
      die("invalid argument in set_br()");

    $this->br = $br;
  }

  /**
    Use localsrc attribute on WAP/HDML devices. <br>
    Using built-in icons, mobile devices don't have to download
    bitmap images. If the device can not render the specified icon,
    it will download the according bitmap as specified in the HAW_image
    constructor.
    @param icon Device-internal representation of the image, e.g. "heart".
  */
  function use_localsrc($icon)
  {
    if (!is_string($icon))
      die("invalid argument in use_localsrc()");

    $this->localsrc = $icon;
  }

  /**
    Use cHTML icon instead of HTML bitmap on i-mode devices. <br>
    Using built-in icons, mobile devices don't have to download
    bitmap images. Has no effect on non-i-mode devices.
    @param icon cHTML icon code, e.g. 63889 for the "heart" icon.
  */
  function use_chtml_icon($icon)
  {
    if (!is_int($icon) || ($icon < 1))
      die("invalid argument in use_chtml_icon()");

    $this->chtml_icon = $icon;
  }

  /**
    Use MML icon instead of HTML bitmap on MML devices. <br>
    Using built-in icons, mobile devices don't have to download
    bitmap images. Has no effect on non-MML devices.
    @param icon MML icon code, e.g. "GB" for the "heart" icon.
  */
  function use_mml_icon($icon)
  {
    if (!is_string($icon) || (strlen($icon) != 2))
      die("invalid argument in use_mml_icon()");

    $this->mml_icon = $icon;
  }

  /**
    Specifies the width of the HTML image.
    @param width image width in pixels.
  */
  function set_html_width($width)
  {
    $this->html_width = $width;
  }

  /**
    Specifies the height of the HTML image.
    @param height image height in pixels.
  */
  function set_html_height($height)
  {
    $this->html_height = $height;
  }

  /**
    Sets text to be spoken by voice browsers. <br>
    @param text Some text that represents the image for voice users.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
  */
  function set_voice_text($text, $audio_src="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_text()");

    $this->voice_text = $text;
    $this->voice_audio_src = $audio_src;
  }

  function get_elementtype()
  {
    return HAW_IMAGE;
  }

  function create(&$deck)
  {
    if (isset($_SERVER['HTTP_ACCEPT']))
      $HTTP_ACCEPT = $_SERVER['HTTP_ACCEPT'];
    else
      $HTTP_ACCEPT = "";

    if ($deck->ml == HAW_HDML)
    {
      // HDML

      if ($deck->alignment != "left")
        $deck->hdmlcardset->add_display_content("<" . $deck->alignment . ">\n");

      if ($this->localsrc)
        $icon = sprintf(" icon=\"%s\"", $this->localsrc);
      else
        $icon = "";

      $content = sprintf("<img src=\"%s\" alt=\"%s\"%s>\n",
                         $this->src_bmp,
                         HAW_specchar($this->alt, $deck), $icon);

      $deck->hdmlcardset->add_display_content($content);

      // create required amount of carriage return's
      $br = "";
      for ($i=0; $i < $this->br; $i++)
        $br .= "<br>\n";

      $deck->hdmlcardset->add_display_content($br);
    }
    elseif (($deck->ml == HAW_HTML) || ($deck->ml == HAW_WML))
    {
      // HTML or WML

      $size = "";
      if ($this->html_width)
        $size .= sprintf(" width=\"%d\"", $this->html_width);
      if ($this->html_height)
        $size .= sprintf(" height=\"%d\"", $this->html_height);
      
      if ($deck->ml == HAW_HTML)
      {
        // HTML

        if ($deck->iModestyle && $this->chtml_icon)
        {
          // cHTML icon available ==> use this icon instead of bitmap
          printf("&#%d;", $this->chtml_icon);
        }
        elseif ($deck->MMLstyle && $this->mml_icon)
        {
          // MML icon available ==> use this icon instead of bitmap
          echo CHR(27) . "$" . $this->mml_icon . CHR(15);
        }
        else
        {
          // use HTML bitmap
          
          if ($deck->css_enabled)
            $style = " style=\"vertical-align:middle; border-style:none\"";
          else
            $style = " align=\"middle\" border=\"0\"";

          printf("<img src=\"%s\" alt=\"%s\"%s%s />",
                 $this->src_html, HAW_specchar($this->alt, $deck), $size, $style);
        }

        // evaluate HTML break instruction
        if ($deck->MMLstyle)
          $br_command = "<br>\n"; // MML has problems with clear attribute
        elseif ($deck->xhtml)
          $br_command = "<br style=\"clear:both;\" />\n"; // XHTML does not know clear attribute
        else
          $br_command = "<br clear=\"all\" />\n";
      }
      else
      {
        // WML

        if ($this->localsrc)
          $localsrc = sprintf(" localsrc=\"%s\"", $this->localsrc);
        else
          $localsrc = "";

        if ($deck->gif_enabled && (substr(strtolower($this->src_html), -4) == ".gif"))
          // user agent is able to display the provided GIF image
          printf("<img src=\"%s\" alt=\"%s\"%s%s/>\n", $this->src_html,
                  HAW_specchar($this->alt, $deck), $localsrc, $size);

        elseif (strstr(strtolower($HTTP_ACCEPT), "image/vnd.wap.wbmp"))
          // user agent is able to display .wbmp image
          printf("<img src=\"%s\" alt=\"%s\"%s/>\n", $this->src_wbmp,
                  HAW_specchar($this->alt, $deck), $localsrc);

        elseif (strstr(strtolower($HTTP_ACCEPT), "image/bmp") && $this->src_bmp)
          // user agent is able to display .bmp and .bmp image is available
          printf("<img src=\"%s\" alt=\"%s\"%s/>\n", $this->src_bmp,
                  HAW_specchar($this->alt, $deck), $localsrc);

        else
          // hope that the user agent makes the best of it!
          printf("<img src=\"%s\" alt=\"%s\"%s/>\n", $this->src_wbmp,
                  HAW_specchar($this->alt, $deck), $localsrc);

        // break instruction in WML
        $br_command = "<br/>\n";
      }

      // create required amount of carriage return's
      for ($i=0; $i < $this->br; $i++)
        echo $br_command;
    }
    elseif ($deck->ml == HAW_VXML)
    {
      // VoiceXML

      if ($this->voice_text || $this->voice_audio_src)
      {
        // create image-related audio output for VoiceXML images

        echo "<block><prompt>";

        HAW_voice_audio(HAW_specchar($this->voice_text, $deck),
                        $this->voice_audio_src, HAW_VOICE_PAUSE, $deck);
  
        echo "</prompt></block>\n";
      }
    }
  }
};






/**
  This class allows to insert tables into a HAW_deck or HAW_form object.
  <p>Note: Not all WAP clients are able to display tables properly! HDML is not
  supporting tables at all. For HDML users the table's content will be generated
  column-by-column, respectively row-by-row, where each table cell will result in
  one separate line on the display.
  <p><b>Examples:</b><p>
  ...<br>
  $myTable = new HAW_table();<br>
  $row1 = new HAW_row();<br>
  $row1->add_column($image1);<br>
  $row1->add_column($text1);<br>
  $myTable->add_row($row1);<br>
  $row2 = new HAW_row();<br>
  $row2->add_column($image2);<br>
  $row2->add_column($text2);<br>
  $myTable->add_row($row2);<br>
  $myDeck->add_table($myTable);<br>
  ...
  @see HAW_deck
  @see HAW_form
  @see HAW_row
*/
class HAW_table
{
  var $row;
  var $number_of_rows;
  var $voice_text;
  var $voice_audio_src;

  /**
    Constructor
  */
  function HAW_table()
  {
    $this->number_of_rows = 0;
    $this->voice_text = "";
    $this->voice_audio_src = "";
  }


  /**
    Adds a HAW_row object to HAW_table.
    @param row Some HAW_row object.
  */
  function add_row($row)
  {
    if (!is_object($row))
      die("invalid argument in add_row()");

    $this->row[$this->number_of_rows] = $row;

    $this->number_of_rows++;
  }

  /**
    Sets text to be spoken by voice browsers. <br>
    Note: Tables should be avoided whenever voice users are targeted.
    Anyway, the HAWHAW API allows to assign voice output to HAW_table objects in order
    to say some introducing words before the table content is spoken.
    @param text Some helpful text.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
  */
  function set_voice_text($text, $audio_src="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_text()");

    $this->voice_text = $text;
    $this->voice_audio_src = $audio_src;
  }

  function get_elementtype()
  {
    return HAW_TABLE;
  }

  function create(&$deck)
  {
    // HDML does not support tables ==> skip all table tags for HDML

    if ($deck->ml == HAW_HTML)
    {
      // HTML
      if ($deck->xhtml && !$deck->css)
        // border attribute is not allowed in XHTML
        echo "<table style=\"border-width:1px; border-style:solid;\">\n";
      else
        printf("<table border=\"1\" align=\"%s\">\n", $deck->alignment);
    }
    elseif ($deck->ml == HAW_WML)
    {
      // WML

      // evaluate maximum number of columns in table

      $max_columns = 0;
      for ($i = 0; $i < $this->number_of_rows; $i++)
      {
        $row = $this->row[$i];
        $columns = $row->get_number_of_columns();

        if ($columns > $max_columns)
          $max_columns = $columns;
      }

      printf("<table columns=\"%d\">\n", $max_columns);
    }
    elseif ($deck->ml == HAW_VXML)
    {
      if ($this->voice_text || $this->voice_audio_src)
      {
        // create introducing audio output for VoiceXML table
  
        echo "<block><prompt>";
  
        HAW_voice_audio(HAW_specchar($this->voice_text, $deck),
                        $this->voice_audio_src, HAW_VOICE_PAUSE, $deck);
      
        echo "</prompt></block>\n";
      }
    }

    for ($i = 0; $i < $this->number_of_rows; $i++)
    {
      $row = $this->row[$i];
      $row->create($deck);
    }

    //terminate table
    if ($deck->ml == HAW_HTML)
    {
      // make new line in HTML
      if ($deck->MMLstyle)
        echo "</table><br>\n"; // MML has problems with clear attribute
      elseif ($deck->xhtml)
        echo "</table><br style=\"clear:both;\" />\n"; // XHTML does not know clear attribute
      else
        echo "</table><br clear=\"all\" />\n";
    }
    elseif ($deck->ml == HAW_WML)
    {
      // make new line in WML
      echo "</table><br/>\n";
    }
  }
};






/**
  This class defines the rows, a HAW_table object consists of.
  <p><b>Examples:</b><p>
  ...<br>
  $image1 = new HAW_image("my_image.wbmp", "my_image.gif", ":-)");<br>
  $text1 = new HAW_text("my text");<br>
  $row1 = new HAW_row();<br>
  $row1->add_column($image1);<br>
  $row1->add_column();<br>
  $row1->add_column($text1);<br>
  ...
  @see HAW_table
  @see HAW_text
  @see HAW_image
  @see HAW_link
*/
class HAW_row
{
  var $column;
  var $number_of_columns;

  /**
    Constructor
  */
  function HAW_row()
  {
    $this->number_of_columns = 0;
  }


  /**
    Adds a cell element to a HAW_row object.
    @param cell_element (optional)<br>Can be a HAW_text object, a HAW_image object,
      a HAW_link object, an array containing some of these three objects,
      or the NULL pointer (default).
      The latter results in an empty cell element.
  */
  function add_column($cell_element=NULL)
  {
    if (is_object($cell_element))
    {
      if (($cell_element->get_elementtype() != HAW_PLAINTEXT) &&
          ($cell_element->get_elementtype() != HAW_IMAGE) &&
          ($cell_element->get_elementtype() != HAW_LINK))
        die("invalid argument in add_column()");
    }
    elseif (is_array($cell_element))
    {
      foreach ($cell_element as $an_element)
      {
        if (($an_element->get_elementtype() != HAW_PLAINTEXT) &&
            ($an_element->get_elementtype() != HAW_IMAGE) &&
            ($an_element->get_elementtype() != HAW_LINK))
          die("invalid argument in add_column() array");
      }
    }

    $this->column[$this->number_of_columns] = $cell_element;
    $this->number_of_columns++;
  }

  function get_number_of_columns()
  {
     return $this->number_of_columns;
  }

  function create(&$deck)
  {
    // HDML and VXML do not support tables ==> skip all table tags for HDML and VXML

    if (($deck->ml != HAW_HDML) && ($deck->ml != HAW_VXML))
      echo "<tr>\n";  // start of row

    for ($i = 0; $i < $this->number_of_columns; $i++)
    {
      if (($deck->ml != HAW_HDML) && ($deck->ml != HAW_VXML))
        echo "<td>\n";  // start of column

      // call create function for each cellelement that is a HAWHAW object
      $column = $this->column[$i];
      if (is_object($column))
        $column->create($deck);
      elseif (is_array($column))
        foreach ($column as $an_element)
          $an_element->create($deck);

      if (($deck->ml != HAW_HDML) && ($deck->ml != HAW_VXML))
        echo "</td>\n";  // end of column
    }

    if (($deck->ml != HAW_HDML) && ($deck->ml != HAW_VXML))
      echo "</tr>\n";  // end of row
  }
};






/**
  This class provides a text input field in a HAW_form object.
  <p><b>Examples:</b><p>
  $myInput1 = new HAW_input("cid", "", "Customer ID");<br>
  <br>
  $myInput2 = new HAW_input("cid", "", "Customer ID", "*N");<br>
  $myInput2->set_size(6);<br>
  $myInput2->set_maxlength(6);<br>
  <br>
  $myInput3 = new HAW_input("pw", "", "Password", "*N");<br>
  $myInput3->set_size(8);<br>
  $myInput3->set_maxlength(8);<br>
  $myInput3->set_type(HAW_INPUT_PASSWORD);<br>
  @see HAW_form
*/
class HAW_input
{
  var $name;
  var $value;
  var $label;
  var $size;
  var $maxlength;
  var $type;
  var $format;
  var $mode;
  var $br;
  var $voice_text;
  var $voice_audio_src;
  var $voice_type;
  var $voice_grammar;
  var $voice_help;
  var $voice_noinput;
  var $voice_nomatch;

  /**
    Constructor
    @param name Variable in which the input is sent to the destination URL.
    @param value Initial value (string!) that will be presented in the input field.
    @param label Describes your input field on the visitors's screen/display.
    @param format (optional, default: "*M")<br>
       Input format code according to the WAP standard.
       Allows the WAP user client e.g. to input only digits and no characters.
  */
  function HAW_input($name, $value, $label, $format="*M")
  {
    $this->name = $name;
    $this->value = $value;
    $this->label = $label;
    $this->format = $format;
    $this->type = HAW_INPUT_TEXT;
    $this->mode = HAW_INPUT_ALPHABET;
    $this->br = 1;
    $this->voice_text = $label;
    $this->voice_audio_src = "";
    $this->voice_type = "digits";
    $this->voice_grammar = "";
    $this->voice_help = array();
    $this->voice_noinput = array();
    $this->voice_nomatch = array();
  }

  /**
    Set size of the input field. <br>
    Note: Will be ignored in case of HDML/VoiceXML output.
    @param size Number of characters fitting into the input field.
  */
  function set_size($size)
  {
    $this->size = $size;
  }

  /**
    Set maximum of allowed characters in the input field. <br>
    Note: Will be ignored in case of HDML output.
    @param maxlength Maximum number of characters the user can enter.
  */
  function set_maxlength($maxlength)
  {
    $this->maxlength = $maxlength;
  }

  /**
    Set input type
    @param type Allowed values: HAW_INPUT_TEXT (default) or HAW_INPUT_PASSWORD.
  */
  function set_type($type)
  {
    $this->type = $type;
  }

  /**
    Set input mode/istyle for japanese MML/i-mode devices
    @param mode input mode<br>
      HAW_INPUT_ALPHABET  (default)<br>
      HAW_INPUT_KATAKANA<br>
      HAW_INPUT_HIRAGANA<br>
      HAW_INPUT_NUMERIC
  */
  function set_mode($mode)
  {
    $this->mode = $mode;

    // input mode can be mapped into a format string, used from WML and HDML
    // ==> set format string
    //     (if a format string was provided during object initiation, this value
    //      will be overwritten)

    switch ($mode)
    {
      case HAW_INPUT_HIRAGANA: { $this->format = "*M"; break; }
      case HAW_INPUT_KATAKANA: { $this->format = "*M"; break; }
      case HAW_INPUT_ALPHABET: { $this->format = "*m"; break; }
      case HAW_INPUT_NUMERIC:  { $this->format = "*N"; break; }
    }
  }

  /**
    Sets the number of line breaks (CRLF) after input field (default: 1). <br>
    Note: Has no effect in WML/HDML.
    @param br Some number of line breaks.
  */
  function set_br($br)
  {
    if (!is_int($br) || ($br < 0))
      die("invalid argument in set_br()");

    $this->br = $br;
  }

  /**
    Sets text to be spoken by voice browsers. <br>
    @param text Some alternative text that replaces &lt;label&gt;.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
  */
  function set_voice_text($text, $audio_src="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_text()");

    $this->voice_text = $text;
    $this->voice_audio_src = $audio_src;
  }

  /**
    Sets the type of the input field in voice decks (default: "digits"). <br>
    Note: Support of builtin grammar types is platform specific.
    The W3C VoiceXML Version 2.0 recommendation defines these grammar types:
    <ul>
    <li>boolean</li>
    <li>date</li>
    <li>digits (default)</li>
    <li>currency</li>
    <li>number</li>
    <li>phone</li>
    <li>time</li>
    </ul>
    @param type String with grammar type.
  */
  function set_voice_type($type)
  {
    if (!is_string($type))
      die("invalid argument in set_voice_type()");

    $this->voice_type = $type;
  }

  /**
    Defines an external grammar for an input field in voice decks. <br>
    Attention: This function should be used by experienced VoiceXML developers
    only! Please refer to the W3C VoiceXML Recommendation for detailled info
    about grammar definitions. 
    @param src URL specifying the location of the grammar, e.g. "http://www.foo.com/myinput.grxml".
    @param type Media type of the grammar, e.g. "application/srgs+xml (optional)".
  */
  function set_voice_grammar($src, $type="")
  {
    if (!is_string($src))
      die("invalid argument in set_voice_grammar()");

    $this->voice_grammar["src"]  = $src;
    $this->voice_grammar["type"] = $type;
    
    $this->voice_type = ""; // external grammar overrides builtin grammar
  }

  /**
    Sets help text for voice browsers. <br>
    @param text Some helpful information concerning this input.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
    @param url Some other voice deck to go to (optional).
  */
  function set_voice_help($text, $audio_src="", $url="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_help()");

    $arr["text"] = $text;
    $arr["src"]  = $audio_src;
    $arr["url"]  = $url;

    $this->voice_help[] = $arr;
  }

  /**
    Sets noinput text for voice browsers. <br>
    @param text Some text to inform the user that no input has been received.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
    @param url Some other voice deck to go to (optional).
  */
  function set_voice_noinput($text, $audio_src="", $url="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_noinput()");

    $arr["text"] = $text;
    $arr["src"]  = $audio_src;
    $arr["url"]  = $url;

    $this->voice_noinput[] = $arr;
  }

  /**
    Sets nomatch text for voice browsers. <br>
    @param text Some text to complain that user input was not recognized.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
    @param url Some other voice deck to go to (optional).
  */
  function set_voice_nomatch($text, $audio_src="", $url="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_nomatch()");

    $arr["text"] = $text;
    $arr["src"]  = $audio_src;
    $arr["url"]  = $url;

    $this->voice_nomatch[] = $arr;
  }

  function get_name()
  {
    return $this->name;
  }

  function get_value()
  {
    return $this->value;
  }

  function get_label()
  {
    return $this->label;
  }

  function get_size()
  {
    return $this->size;
  }

  function get_maxlength()
  {
    return $this->maxlength;
  }

  function get_type()
  {
    return $this->type;
  }

  function get_mode()
  {
    return $this->mode;
  }

  function get_format()
  {
    return $this->format;
  }

  function get_elementtype()
  {
    return HAW_INPUT;
  }

  function create(&$deck)
  {
    $class_param = "";

    if ($this->type == HAW_INPUT_PASSWORD)
    {
      $type = "type=\"password\"";
      
      if ($deck->css_enabled)
        $class_param = " class=\"hawinputpassword\"";
    }
    else
    {
      $type = "type=\"text\"";
      
      if ($deck->css_enabled)
        $class_param = " class=\"hawinputtext\"";
    }
      
    if ($this->size)
      $size = sprintf("size=\"%d\"", $this->size);
    else
      $size = "";

    if ($this->maxlength)
      $maxlength = sprintf("maxlength=\"%d\"", $this->maxlength);
    else
      $maxlength = "";

    if ($deck->ml == HAW_HTML)
    {
      if ($deck->iModestyle)
        $mode = sprintf(" istyle=\"%d\"", $this->mode);
      else
        $mode = "";

      if ($deck->MMLstyle)
      {
        switch ($this->mode)
        {
          case HAW_INPUT_ALPHABET: { $mode = " mode=\"alphabet\""; break; }
          case HAW_INPUT_KATAKANA: { $mode = " mode=\"katakana\""; break; }
          case HAW_INPUT_HIRAGANA: { $mode = " mode=\"hiragana\""; break; }
          case HAW_INPUT_NUMERIC:  { $mode = " mode=\"numeric\"";  break; }
          default:                 { $mode = " mode=\"alphabet\""; break; }
        }
      }

      // create HTML input
      if ($deck->xhtml || $deck->desktopBrowser)
      {
        printf("<label for=\"%s\">%s</label>\n",
                $this->name, HAW_specchar($this->label, $deck));
        printf("<input %s name=\"%s\" id=\"%s\" value=\"%s\" %s %s%s%s /> ",
                $type, $this->name,
                $this->name, $this->value, $size, $maxlength, $mode, $class_param);
      }
      else
        printf("%s <input %s name=\"%s\" value=\"%s\" %s %s%s%s /> ",
                HAW_specchar($this->label, $deck), $type,
                $this->name, $this->value, $size, $maxlength, $mode, $class_param);

      for ($i=0; $i < $this->br; $i++)
        echo "<br />\n";
    }
    elseif ($deck->ml == HAW_WML)
    {
      // create WML input
      printf("%s<input emptyok=\"true\" format=\"%s\" %s name=\"%s\" value=\"%s\" %s %s/>\n",
              HAW_specchar($this->label, $deck), $this->format,
              $type, $this->name, $this->value, $size, $maxlength);
    }
    elseif ($deck->ml == HAW_HDML)
    {
      // create HDML input

      $options  = " format=\"$this->format\"";
      $options .= " key=\"$this->name\"";

      if ($this->type == HAW_INPUT_PASSWORD)
        $options .= " NOECHO=\"true\"";

      if ($deck->alignment != "left")
        $display_content = "<" . $deck->alignment . ">\n";

      $display_content .= HAW_specchar($this->label, $deck);
      $display_content .= "\n";

      // make user interactive entry card
      $deck->hdmlcardset->make_ui_card($options, $display_content, HAW_HDML_ENTRY);
    }
    elseif ($deck->ml == HAW_VXML)
    {
      // create VoiceXML input

      if ($this->voice_type)
      {
        if (($this->voice_type == "digits") && $this->maxlength)
          $type = sprintf(" type=\"digits?maxlength=%d\"", $this->maxlength);
        else
          $type = sprintf(" type=\"%s\"", $this->voice_type);
      }
      else
        $type = "";

      printf("<field%s name=\"%s\">\n", $type, $this->name);

      if ($this->voice_grammar)
      {
        // external grammar has been defined

        if ($this->voice_grammar["type"])
          $grammar_type = sprintf(" type=\"%s\"", $this->voice_grammar["type"]);
        else
          $grammar_type = ""; // let the interpreter context determine the grammar type

        printf("<grammar src=\"%s\"%s/>\n",
                $this->voice_grammar["src"], $grammar_type);
      }

      if ($this->voice_text || $this->voice_audio_src)
      {
        echo "<prompt>";

        HAW_voice_audio(HAW_specchar($this->voice_text, $deck),
                        $this->voice_audio_src, 0, $deck);

        echo "</prompt>\n";
      }

      // create event handlers
      HAW_voice_eventhandler("help",    $this->voice_help,    $deck);
      HAW_voice_eventhandler("noinput", $this->voice_noinput, $deck);
      HAW_voice_eventhandler("nomatch", $this->voice_nomatch, $deck);
  
      echo "</field>\n";
    }
  }
};






/**
  This class provides a input textarea in a HAW_form object.<br>
  Note: Creates no output for VoiceXML. Voice applications can use class
  HAW_voicerecorder instead.
  <p><b>Examples:</b><p>
  $myArea1 = new HAW_textarea("fb", "", "Feedback");<br>
  <br>
  $myArea2 = new HAW_textarea("msg", "Enter message here ...", "Message", 40 , 5);<br>
  $myArea2->set_br(2);<br>
  @see HAW_form
  @see HAW_voicerecorder
*/
class HAW_textarea
{
  var $name;
  var $value;
  var $label;
  var $rows;
  var $cols;
  var $mode;
  var $br;

  /**
    Constructor
    @param name Variable in which the input is sent to the destination URL.
    @param value Initial value (string!) that will be presented in the textarea.
    @param label Describes your textarea on the visitor's screen/display.
    @param rows Rows (optional, default: 3)
    @param cols Columns (optional, default: 16)
  */
  function HAW_textarea($name, $value, $label, $rows=3, $cols=16)
  {
    $this->name   = $name;
    $this->value  = $value;
    $this->label  = $label;
    $this->rows   = $rows;
    $this->cols   = $cols;
    $this->mode   = HAW_INPUT_ALPHABET;
    $this->br     = 1;
  }


  /**
    Set input mode/istyle for japanese MML/i-mode devices
    @param mode input mode<br>
      HAW_INPUT_ALPHABET  (default)<br>
      HAW_INPUT_KATAKANA<br>
      HAW_INPUT_HIRAGANA<br>
      HAW_INPUT_NUMERIC
  */
  function set_mode($mode)
  {
    $this->mode = $mode;
  }

  /**
    Sets the number of line breaks (CRLF) after textarea. (default: 1)<br>
    Note: Has no effect in WML/HDML.
    @param br Some number of line breaks.
  */
  function set_br($br)
  {
    if (!is_int($br) || ($br < 0))
      die("invalid argument in set_br()");

    $this->br = $br;
  }

  function get_name()
  {
    return $this->name;
  }

  function get_value()
  {
    return $this->value;
  }

  function get_label()
  {
    return $this->label;
  }

  function get_mode()
  {
    return $this->mode;
  }

  function get_elementtype()
  {
    return HAW_TEXTAREA;
  }

  function create(&$deck)
  {
    if ($deck->ml == HAW_HTML)
    {
      if ($deck->iModestyle)
        $mode = sprintf(" istyle=\"%d\"", $this->mode);
      else
        $mode = "";

      if ($deck->MMLstyle)
      {
        switch ($this->mode)
        {
          case HAW_INPUT_ALPHABET: { $mode = " mode=\"alphabet\""; break; }
          case HAW_INPUT_KATAKANA: { $mode = " mode=\"katakana\""; break; }
          case HAW_INPUT_HIRAGANA: { $mode = " mode=\"hiragana\""; break; }
          case HAW_INPUT_NUMERIC:  { $mode = " mode=\"numeric\"";  break; }
          default:                 { $mode = " mode=\"alphabet\""; break; }
        }
      }

      if (!$deck->iModestyle && !$deck->MMLstyle)
        $wrap = sprintf(" wrap=\"virtual\"");
      else
        $wrap = "";

      // create HTML input
      printf("%s<br /><textarea name=\"%s\" rows=\"%s\" cols=\"%s\"%s%s>%s</textarea> ",
              HAW_specchar($this->label, $deck), $this->name, $this->rows,
              $this->cols, $mode, $wrap, $this->value);

      for ($i=0; $i < $this->br; $i++)
        echo "<br />\n";
    }
    elseif ($deck->ml == HAW_WML)
    {
      // create WML input
      printf("%s<input emptyok=\"true\" name=\"%s\" value=\"%s\"/>\n",
              HAW_specchar($this->label, $deck),
              $this->name, $this->value);
    }
    elseif ($deck->ml == HAW_HDML)
    {
      // create HDML input

      $options = " key=\"$this->name\"";

      if ($deck->alignment != "left")
        $display_content = "<" . $deck->alignment . ">\n";

      $display_content .= HAW_specchar($this->label, $deck);
      $display_content .= "\n";

      // make user interactive entry card
      $deck->hdmlcardset->make_ui_card($options, $display_content, HAW_HDML_ENTRY);
    }
    elseif ($deck->ml == HAW_VXML)
    {
      // no output for VoiceXML possible
    }
  }
};






/**
  This class provides a select element in a HAW_form object.
  It allows to create optimized WML for WAP devices which
  are capable to interprete the Openwave GUI extensions for WML 1.3. All other
  WAP devices receive WML 1.1 compatible markup code, which is quite similar to
  the markup code created by the HAW_radio class.
  <p><b>Examples:</b><p>
  $mySelect = new HAW_select("color");<br>
  $mySelect->add_option("Blue", "b");<br>
  $mySelect->add_option("Red", "r", HAW_SELECTED);<br>
  $mySelect->add_option("Yellow", "y");
  @see HAW_form
*/
class HAW_select
{
  var $name;
  var $type;
  var $value;
  var $options;
  var $number_of_options;
  var $voice_text;
  var $voice_audio_src;
  var $voice_help;
  var $voice_noinput;
  var $voice_nomatch;


  /**
    Constructor
    @param name Variable in which the information about the selected option is sent to
       the destination URL.
    @param type (optional)<br>
      Type of select area:<br>
      HAW_SELECT_POPUP: popup the whole selection list  (default)<br>
      HAW_SELECT_SPIN: rotate options on a WAP device screen<br>
  */
  function HAW_select($name, $type=HAW_SELECT_POPUP)
  {
    $this->name = $name;
    $this->type = $type;
    $this->value = "";
    $this->number_of_options = 0;
    $this->voice_text = HAW_VOICE_ENUMERATE;
    $this->voice_audio_src = "";
    $this->voice_help = array();
    $this->voice_noinput = array();
    $this->voice_nomatch = array();
  }

  /**
    Adds one option to a HAW_select object.
    @param label Describes the option on the visitor's screen/display.
    @param value Value (string!) sent in the "name" variable, if this option is selected.
    @param is_selected (optional)<br>Allowed values are HAW_SELECTED or HAW_NOTSELECTED
       (default).<br>Note: Setting to "selected" will overwrite previous "selected"
       options of this HAW_select object.
  */
  function add_option($label, $value, $is_selected=HAW_NOTSELECTED)
  {
    if (!is_string($label) || !is_string($value))
      die("invalid argument in add_option()");

    $this->options[$this->number_of_options]["label"] = $label;
    $this->options[$this->number_of_options]["value"] = $value;
    $this->options[$this->number_of_options]["is_selected"] = $is_selected;
    
    if ((strlen($this->value) == 0) || ($is_selected == HAW_SELECTED))
      $this->value = $value;

    $this->number_of_options++;
  }

  /**
    Sets text to be spoken by voice browsers. <br>
    @param text Some alternative text that replaces the enumeration of select options.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
  */
  function set_voice_text($text, $audio_src="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_text()");

    $this->voice_text = $text;
    $this->voice_audio_src = $audio_src;
  }

  /**
    Sets help text for voice browsers. <br>
    @param text Some helpful information concerning this select element.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
    @param url Some other voice deck to go to (optional).
  */
  function set_voice_help($text, $audio_src="", $url="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_help()");

    $arr["text"] = $text;
    $arr["src"]  = $audio_src;
    $arr["url"]  = $url;

    $this->voice_help[] = $arr;
  }

  /**
    Sets noinput text for voice browsers. <br>
    @param text Some text to inform the user that no input has been received.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
    @param url Some other voice deck to go to (optional).
  */
  function set_voice_noinput($text, $audio_src="", $url="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_noinput()");

    $arr["text"] = $text;
    $arr["src"]  = $audio_src;
    $arr["url"]  = $url;

    $this->voice_noinput[] = $arr;
  }

  /**
    Sets nomatch text for voice browsers. <br>
    @param text Some text to complain that user input was not recognized.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
    @param url Some other voice deck to go to (optional).
  */
  function set_voice_nomatch($text, $audio_src="", $url="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_nomatch()");

    $arr["text"] = $text;
    $arr["src"]  = $audio_src;
    $arr["url"]  = $url;

    $this->voice_nomatch[] = $arr;
  }

  function get_name()
  {
    return $this->name;
  }

  function get_value()
  {
    return $this->value;
  }

  function get_elementtype()
  {
    return HAW_SELECT;
  }

  function create(&$deck)
  {
    if ($deck->ml == HAW_HTML)
    {
      // create HTML select

      if ($deck->xhtml || $deck->desktopBrowser)
      {
        // dummy label for select element (needed for Bobby approval)
        // object to be enhanced in future versions ...
        printf("<label for=\"%s\">%s</label>\n", $this->name, " ");
        printf("<select name=\"%s\" id=\"%s\" size=\"1\">\n", $this->name, $this->name);
      }
      else
        printf("<select name=\"%s\" size=\"1\">\n", $this->name);

      for ($i=0; $i < $this->number_of_options; $i++)
      {
        if ($this->options[$i]["is_selected"] == HAW_SELECTED)
          $state = " selected=\"selected\"";
        else
          $state = "";

        echo "<option" . $state . " value=\"" . $this->options[$i]["value"] . "\">"
                       . HAW_specchar($this->options[$i]["label"], $deck) . "</option>\n";
      }

      echo "</select>\n";
    }
    elseif ($deck->ml == HAW_WML)
    {
      // create WML select

      if ($deck->owgui_1_3)
      {
        // Openwave GUI extensions for WML 1.3

        switch ($this->type)
        {
          case HAW_SELECT_POPUP: { $type_option = "type=\"popup\""; break; }
          case HAW_SELECT_SPIN:  { $type_option = "type=\"spin\"";  break; }
          default:               { $type_option = "type=\"popup\""; break; }
        }

        echo "<select " . $type_option . " name=\"" . $this->name . "\">\n";
      }
      else
        // conventional WML (similar as for HAW_radio)
        echo "<select name=\"" . $this->name . "\">\n";

      for ($i=0; $i < $this->number_of_options; $i++)
      {
        echo "<option value=\"" . $this->options[$i]["value"] . "\">"
             . HAW_specchar($this->options[$i]["label"], $deck) . "</option>\n";
      }

      echo "</select>\n";
    }
    elseif ($deck->ml == HAW_HDML)
    {
      // create HDML select (similar as for HAW_radio)

      $options = " key=\"$this->name\"";
      $ce_area = "";

      while (list($key, $val) = each($this->options))
      {
        // create one <ce> statement for each option
        $ce_area .= sprintf("<ce value=\"%s\">%s\n",
                            $val["value"],
                            HAW_specchar($val["label"], $deck));
      }

      // make user interactive choice card
      $deck->hdmlcardset->make_ui_card($options, $ce_area, HAW_HDML_CHOICE);
    }
    elseif ($deck->ml == HAW_VXML)
    {
      // create VoiceXML select

      printf("<field name=\"%s\">\n", $this->name);

      if ($this->voice_text || $this->voice_audio_src)
      {
        if ($this->voice_text != HAW_VOICE_ENUMERATE)
        {
          echo "<prompt>";

          HAW_voice_audio(HAW_specchar($this->voice_text, $deck),
                          $this->voice_audio_src, 0, $deck);

          echo "</prompt>\n";
        }
      }

      while (list($key, $val) = each($this->options))
      {
        if ($key < 9)
          $dtmf = sprintf(" dtmf=\"%d\"", $key+1);
        else
          $dtmf = "";

        if ($deck->supportsVXMLBreakTag)
          $breakTag = sprintf("<break time=\"%dms\"/>", HAW_VOICE_PAUSE);
        else
          $breakTag = "";

        printf("<prompt>%s%s</prompt>\n", HAW_specchar($val["label"], $deck), $breakTag);

        printf("<option%s value=\"%s\">%s</option>\n", $dtmf, $val["value"],
               ereg_replace("[\?!]"," ",strtolower(HAW_specchar($val["label"], $deck))));
      }

      // create event handlers
      HAW_voice_eventhandler("help",    $this->voice_help,    $deck);
      HAW_voice_eventhandler("noinput", $this->voice_noinput, $deck);
      HAW_voice_eventhandler("nomatch", $this->voice_nomatch, $deck);
  
      echo "</field>\n";
    }
  }
};






/**
  This class provides a radio button element in a HAW_form object.
  <p><b>Examples:</b><p>
  $myRadio = new HAW_radio("country");<br>
  $myRadio->add_button("Finland", "F");<br>
  $myRadio->add_button("Germany", "G", HAW_CHECKED);<br>
  $myRadio->add_button("Sweden", "S");
  @see HAW_form
*/
class HAW_radio
{
  var $name;
  var $value;
  var $buttons;
  var $number_of_buttons;
  var $voice_text;
  var $voice_audio_src;
  var $voice_help;
  var $voice_noinput;
  var $voice_nomatch;

  /**
    Constructor
    @param name Variable in which the information about the pressed button is sent to
       the destination URL.
  */
  function HAW_radio($name)
  {
    $this->name  = $name;
    $this->value = "";
    $this->number_of_buttons = 0;
    $this->voice_text = HAW_VOICE_ENUMERATE;
    $this->voice_audio_src = "";
    $this->voice_help = array();
    $this->voice_noinput = array();
    $this->voice_nomatch = array();
  }

  /**
    Adds one radio button to a HAW_radio object.
    @param label Describes the radiobutton on the visitor's screen/display.
    @param value Value (string!) sent in the "name" variable, if this button is selected.
    @param is_checked (optional)<br>Allowed values are HAW_CHECKED or HAW_NOTCHECKED
       (default).<br>Note: Setting to "checked" will overwrite previous "checked"
       radiobuttons of this HAW_radio object.
  */
  function add_button($label, $value, $is_checked=HAW_NOTCHECKED)
  {
    if (!is_string($label) || !is_string($value))
      die("invalid argument in add_button()");

    $this->buttons[$this->number_of_buttons]["label"] = $label;
    $this->buttons[$this->number_of_buttons]["value"] = $value;
    $this->buttons[$this->number_of_buttons]["is_checked"] = $is_checked;
    
    if ((strlen($this->value) == 0) || ($is_checked == HAW_CHECKED))
      $this->value = $value;

    $this->number_of_buttons++;
  }

  /**
    Sets text to be spoken by voice browsers. <br>
    @param text Some alternative text that replaces the enumeration of buttons.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
  */
  function set_voice_text($text, $audio_src="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_text()");

    $this->voice_text = $text;
    $this->voice_audio_src = $audio_src;
  }

  /**
    Sets help text for voice browsers. <br>
    @param text Some helpful information concerning this radio button element.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
    @param url Some other voice deck to go to (optional).
  */
  function set_voice_help($text, $audio_src="", $url="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_help()");

    $arr["text"] = $text;
    $arr["src"]  = $audio_src;
    $arr["url"]  = $url;

    $this->voice_help[] = $arr;
  }

  /**
    Sets noinput text for voice browsers. <br>
    @param text Some text to inform the user that no input has been received.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
    @param url Some other voice deck to go to (optional).
  */
  function set_voice_noinput($text, $audio_src="", $url="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_noinput()");

    $arr["text"] = $text;
    $arr["src"]  = $audio_src;
    $arr["url"]  = $url;

    $this->voice_noinput[] = $arr;
  }

  /**
    Sets nomatch text for voice browsers. <br>
    @param text Some text to complain that user input was not recognized.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
    @param url Some other voice deck to go to (optional).
  */
  function set_voice_nomatch($text, $audio_src="", $url="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_nomatch()");

    $arr["text"] = $text;
    $arr["src"]  = $audio_src;
    $arr["url"]  = $url;

    $this->voice_nomatch[] = $arr;
  }

  function get_name()
  {
    return $this->name;
  }

  function get_value()
  {
    return $this->value;
  }

  function get_elementtype()
  {
    return HAW_RADIO;
  }

  function create(&$deck)
  {
    if ($deck->ml == HAW_HTML)
    {
      // create HTML radio

      while (list($key, $val) = each($this->buttons))
      {
        if ($val["is_checked"] == HAW_CHECKED)
          $state = "checked=\"checked\"";
        else
          $state = "";

        if ($deck->xhtml || $deck->desktopBrowser)
        {
          printf("<input type=\"radio\" name=\"%s\" id=\"%s\" %s value=\"%s\" />",
                  $this->name, $this->name . $key, $state, $val["value"]);

          printf(" <label for=\"%s\">%s</label><br />\n",
                  $this->name . $key, HAW_specchar($val["label"], $deck));
        }
        else
          printf("<input type=\"radio\" name=\"%s\" %s value=\"%s\" /> %s<br />\n",
                  $this->name, $state, $val["value"],
                  HAW_specchar($val["label"], $deck));
      }
    }
    elseif ($deck->ml == HAW_WML)
    {
      // create WML radio

      if ($deck->owgui_1_3)
        // Openwave GUI extensions for WML 1.3
        printf("<select type=\"radio\" name=\"%s\">\n", $this->name);
      else
        // conventional WML (similar as for HAW_select)
        printf("<select name=\"%s\">\n", $this->name);

      while (list($key, $val) = each($this->buttons))
      {
        printf("<option value=\"%s\">%s</option>\n",
                $val["value"], HAW_specchar($val["label"], $deck));
      }

      echo "</select>\n";
    }
    elseif ($deck->ml == HAW_HDML)
    {
      // create HDML radio (similar as for HAW_select)

      $options = " key=\"$this->name\"";
      $ce_area = "";

      while (list($key, $val) = each($this->buttons))
      {
        // create one <ce> statement for each button
        $ce_area .= sprintf("<ce value=\"%s\">%s\n",
                            $val["value"],
                            HAW_specchar($val["label"], $deck));
      }

      // make user interactive choice card
      $deck->hdmlcardset->make_ui_card($options, $ce_area, HAW_HDML_CHOICE);
    }
    elseif ($deck->ml == HAW_VXML)
    {
      // create VoiceXML radio

      printf("<field name=\"%s\">\n", $this->name);

      if ($this->voice_text || $this->voice_audio_src)
      {
        if ($this->voice_text != HAW_VOICE_ENUMERATE)
        {
          echo "<prompt>";

          HAW_voice_audio(HAW_specchar($this->voice_text, $deck),
                          $this->voice_audio_src, 0, $deck);

          echo "</prompt>\n";
        }
      }

      while (list($key, $val) = each($this->buttons))
      {
        if ($key < 9)
          $dtmf = sprintf(" dtmf=\"%d\"", $key+1);
        else
          $dtmf = "";

        if ($deck->supportsVXMLBreakTag)
          $breakTag = sprintf("<break time=\"%dms\"/>", HAW_VOICE_PAUSE);
        else
          $breakTag = "";

        printf("<prompt>%s%s</prompt>\n", HAW_specchar($val["label"], $deck), $breakTag);

        printf("<option%s value=\"%s\">%s</option>\n", $dtmf, $val["value"],
                ereg_replace("[\?!]"," ",strtolower(HAW_specchar($val["label"], $deck))));
      }

      // create event handlers
      HAW_voice_eventhandler("help",    $this->voice_help,    $deck);
      HAW_voice_eventhandler("noinput", $this->voice_noinput, $deck);
      HAW_voice_eventhandler("nomatch", $this->voice_nomatch, $deck);
  
      echo "</field>\n";
    }
  }
};






/**
  This class provides a single checkbox element in a HAW_form object.
  <p><b>Examples:</b><p>
  $myCheckbox = new HAW_checkbox("agmt", "yes", "I agree");<br>
  $myCheckbox = new HAW_checkbox("agmt", "yes", "I agree", HAW_NOTCHECKED);<br>
  $myCheckbox = new HAW_checkbox("agmt", "yes", "I agree", HAW_CHECKED);<br>
  <br>
  Note: The first and the second example are identical.
  @see HAW_form
*/
class HAW_checkbox
{
  var $name;
  var $value;
  var $state;
  var $voice_text;
  var $voice_audio_src;
  var $voice_help;
  var $voice_noinput;
  var $voice_nomatch;

  /**
    Constructor
    @param name Variable in which "value" is sent to the destination URL, in case that
       the box is checked.<br>
       Note: Some browsers send empty name parameters like
       <i>&apos;...&amp;name=&amp;...&apos;</i>
       for non-selected checkbox elements. Therefore for the receiving script it is not
       sufficient to check the presence of the name parameter only! The value must be
       checked too!
    @param value See name.
    @param label Describes the checkbox on the visitor's screen/display.
    @param state (optional)<br>Allowed values are HAW_CHECKED or HAW_NOTCHECKED
       (default).
  */
  function HAW_checkbox($name, $value, $label, $state=HAW_NOTCHECKED)
  {
    $this->name  = $name;
    $this->value = $value;
    $this->label = $label;
    $this->state = $state;
    $this->voice_text = $label;
    $this->voice_audio_src = "";
    $this->voice_help = array();
    $this->voice_noinput = array();
    $this->voice_nomatch = array();
  }

  /**
    Sets text to be spoken by voice browsers. <br>
    @param text Some alternative text that replaces &lt;label&gt;.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
  */
  function set_voice_text($text, $audio_src="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_text()");

    $this->voice_text = $text;
    $this->voice_audio_src = $audio_src;
  }

  /**
    Sets help text for voice browsers. <br>
    @param text Some helpful information concerning this checkbox.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
    @param url Some other voice deck to go to (optional).
  */
  function set_voice_help($text, $audio_src="", $url="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_help()");

    $arr["text"] = $text;
    $arr["src"]  = $audio_src;
    $arr["url"]  = $url;

    $this->voice_help[] = $arr;
  }

  /**
    Sets noinput text for voice browsers. <br>
    @param text Some text to inform the user that no input has been received.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
    @param url Some other voice deck to go to (optional).
  */
  function set_voice_noinput($text, $audio_src="", $url="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_noinput()");

    $arr["text"] = $text;
    $arr["src"]  = $audio_src;
    $arr["url"]  = $url;

    $this->voice_noinput[] = $arr;
  }

  /**
    Sets nomatch text for voice browsers. <br>
    @param text Some text to complain that user input (typically "yes" or "no") was not recognized.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
    @param url Some other voice deck to go to (optional).
  */
  function set_voice_nomatch($text, $audio_src="", $url="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_nomatch()");

    $arr["text"] = $text;
    $arr["src"]  = $audio_src;
    $arr["url"]  = $url;

    $this->voice_nomatch[] = $arr;
  }

  function is_checked()
  {
    return $this->state;
  }

  function get_name()
  {
    return $this->name;
  }

  function get_value()
  {
    return $this->value;
  }

  function get_label()
  {
    return $this->label;
  }

  function get_elementtype()
  {
    return HAW_CHECKBOX;
  }

  function create(&$deck)
  {
    if ($deck->ml == HAW_HTML)
    {
      // create HTML checkbox

      $state = ($this->is_checked() ? "checked=\"checked\"" : "");

      if ($deck->xhtml || $deck->desktopBrowser)
      {
        printf("<input type=\"checkbox\" name=\"%s\" id=\"%s\" %s value=\"%s\" />",
                $this->name, $this->name, $state, $this->value);

        printf(" <label for=\"%s\">%s</label><br />\n",
                $this->name, HAW_specchar($this->label, $deck));
      }
      else
        printf("<input type=\"checkbox\" name=\"%s\" %s value=\"%s\" /> %s<br />\n",
                $this->name, $state, $this->value,
                HAW_specchar($this->label, $deck));
    }
    elseif ($deck->ml == HAW_WML)
    {
      // create WML checkbox
      printf("<select name=\"%s\" multiple=\"true\">\n", $this->name);
      printf("<option value=\"%s\">%s</option>\n",
             $this->value, HAW_specchar($this->label, $deck));
      printf("</select>\n");
    }
    elseif ($deck->ml == HAW_HDML)
    {
      // create HDML checkbox
      // HDML does not support the multiple option feature!
      // ==> trick: simulate checkbox by creating radio buttons [x] and [ ]

      $options = " key=\"$this->name\"";

      // create label above the radio buttons
      $cb = sprintf("%s\n", HAW_specchar($this->label, $deck));

      // create "checked" radio button
      $cb .= sprintf("<ce value=\"%s\">[x]\n", $this->value);

      // create "not checked" radio button
      $cb .= "<ce value=\"\">[ ]\n";

      // make user interactive choice card
      $deck->hdmlcardset->make_ui_card($options, $cb, HAW_HDML_CHOICE);
    }
    elseif ($deck->ml == HAW_VXML)
    {
      // create VoiceXML checkbox (field with boolean grammar type)

      printf("<field type=\"boolean\" name=\"%s\">\n", $this->name);

      if ($this->voice_text || $this->voice_audio_src)
      {
        echo "<prompt>";

        HAW_voice_audio(HAW_specchar($this->voice_text, $deck),
                        $this->voice_audio_src, 0, $deck);

        echo "</prompt>\n";
      }

      printf("<filled><if cond=\"%s\"><assign name=\"%s\" expr=\"'%s'\"/><else/><assign name=\"%s\" expr=\"''\"/></if></filled>\n",
              $this->name, $this->name, $this->value, $this->name);

      // create event handlers
      HAW_voice_eventhandler("help",    $this->voice_help,    $deck);
      HAW_voice_eventhandler("noinput", $this->voice_noinput, $deck);
      HAW_voice_eventhandler("nomatch", $this->voice_nomatch, $deck);

      echo "</field>\n";
    }
  }
};






/**
  This class provides a "hidden" element in a HAW_form object.
  <p><b>Examples:</b><p>
  $myHiddenElement = new HAW_hidden("internal_reference", "08154711");
  @see HAW_form
*/
class HAW_hidden
{
  var $name;
  var $value;

  /**
    Constructor
    @param name Variable in which "value" sent to the destination URL.
    @param value See name.
  */
  function HAW_hidden($name, $value)
  {
    $this->name = $name;
    $this->value = $value;
  }

  function get_name()
  {
    return $this->name;
  }

  function get_value()
  {
    return $this->value;
  }

  function get_elementtype()
  {
    return HAW_HIDDEN;
  }

  function create(&$deck)
  {
    if ($deck->ml == HAW_HTML)
    {
      // create hidden HTML field

      printf("<input type=\"hidden\" name=\"%s\" value=\"%s\" />\n",
              $this->name, HAW_specchar($this->value, $deck));
    }
    elseif ($deck->ml == HAW_VXML)
    {
      // create hidden field in VoiceXML

      printf("<field name=\"%s\" expr=\"'%s'\"/>\n",
              $this->name, HAW_specchar($this->value, $deck));
    }

    // not necessary in WML and HDML!
  }
};






/**
  This class provides a submit button in a HAW_form object.
  One HAW_form object can contain only one HAW_submit object.
  <p><b>Examples:</b><p>
  $mySubmit = new HAW_submit("Submit");<br>
  $mySubmit = new HAW_submit("Submit", "user_pressed");
  @see HAW_form
*/
class HAW_submit
{
  var $label;
  var $name;
  
  /**
    Constructor
    @param label What's written on the button.
    @param name (optional)<br>
       Variable in which "label" is sent to the destination URL.
  */
  function HAW_submit($label, $name="")
  {
    $this->label = $label;
    $this->name = $name;
  }

  function get_name()
  {
    return $this->name;
  }

  function get_label()
  {
    return $this->label;
  }

  function get_elementtype()
  {
    return HAW_SUBMIT;
  }

  function create(&$deck, $varnames, $url, $method)
  {
    if ($deck->ml == HAW_HTML)
    {
      // create submit button in HTML

      $name = ($this->name ? "name=\"" . "$this->name" ."\"" : "");

      printf("<input type=\"submit\" %s value=\"%s\" id=\"hawinputsubmit\" /><br />\n", $name,
             HAW_specchar($this->label, $deck));

    }
    elseif (($deck->ml == HAW_WML) || ($deck->ml == HAW_HDML))
    {
      // determine querystring and postfields for WML and postdata for HDML

      $query_string = "";
      $post_fields = "";
      $post_data = "";

      if ($varnames) // safety check for "empty" forms
      {
        while (list($key, $val) = each($varnames))
        {
          $query_string .= $val . "=$(" . $val . ")&amp;";
          $post_fields .= sprintf("<postfield name=\"%s\" value=\"$(%s)\"/>\n", $val, $val);
          $post_data .= sprintf("%s=$(%s)&", $val, $val);
        }
      }

      if ($this->name != "")
      {
        $query_string .= $this->name . "=" . urlencode($this->label);
        $post_fields .= sprintf("<postfield name=\"%s\" value=\"%s\"/>\n",
                                 $this->name, HAW_specchar($this->label, $deck));
        $post_data .= sprintf("%s=%s", $this->name, HAW_specchar($this->label, $deck));
      }

      if (substr($query_string, -5) == "&amp;")
      {
        $query_string = substr($query_string, 0, strlen($query_string)-5);
        $post_data = substr($post_data, 0, strlen($post_data)-1);
      }

      // replace '&' character in URL with '&amp;'
      $url = ereg_replace("&", "&amp;", $url);

      if (strchr($url,"?"))
        $sep = "&amp;";
      else
        $sep = "?";

      if ($deck->ml == HAW_WML)
      {
        if ($deck->submitViaLink && ($method == HAW_METHOD_GET))
        {
          // special handling for devices which have problems with <do> construct

          printf("<anchor>%s\n", HAW_specchar($this->label, $deck));
          printf("<go href=\"%s%s%s\"/>\n", $url, $sep, $query_string);
          echo "</anchor><br/>\n";
        }
        else
        {
          // normal WML style

          if ($deck->owgui_1_3)
            // create <do type="button"> sequence for Openwave GUI extensions WML 1.3
            printf("<do type=\"button\" label=\"%s\">\n",
                    HAW_specchar($this->label, $deck));
          else
            // create <do type="accept"> sequence in normal WML
            printf("<do type=\"accept\" label=\"%s\">\n",
                    HAW_specchar($this->label, $deck));

          if ($method == HAW_METHOD_GET)
            printf("<go href=\"%s%s%s\">\n", $url, $sep, $query_string);
          else
            printf("<go href=\"%s\" method=\"post\">\n%s", $url, $post_fields);

          echo "</go>\n";
          echo "</do>\n";
        }
      }
      elseif ($deck->ml == HAW_HDML)
      {
        // store info for final card in HDML card set

        if ($method == HAW_METHOD_GET)
          $action = sprintf("<action type=\"accept\" label=\"%s\" task=\"go\" dest=\"%s%s%s\">\n",
                             HAW_specchar($this->label, $deck),
                             $url, $sep, $query_string);
        else
          $action = sprintf("<action type=\"accept\" label=\"%s\" task=\"go\" dest=\"%s\" method=\"post\" postdata=\"%s\">\n",
                             HAW_specchar($this->label, $deck),
                             $url, $post_data);

        $deck->hdmlcardset->set_final_action($action);
      }
    }
    elseif ($deck->ml == HAW_VXML)
    {
      // determine filled tag for VoiceXML

      $namelist = " namelist=\""; // init namelist attribute

      if ($varnames) // safety check for "empty" forms
      {
        // created namelist attribute with form fields

        while (list($key, $val) = each($varnames))
          $namelist .= $val . " ";
      }

      if ($this->name != "")
      {
        // create special field for submit info
        printf("<field name=\"%s\" expr=\"'%s'\"/>\n",
                $this->name, HAW_specchar($this->label, $deck));

        // add submit field to namelist
        $namelist .= $this->name . " ";
      }

      // remove last blank character in namelist
      if (substr($namelist, -1) == " ")
        $namelist = substr($namelist, 0, strlen($namelist)-1);

      // complete namelist attribute
      $namelist .= "\"";

      // replace '&' character in URL with '&amp;'
      $url = ereg_replace("&", "&amp;", $url);

      // consider send method
      if ($method == HAW_METHOD_POST)
        $sendmethod = " method=\"post\"";
      else
        $sendmethod = " method=\"get\"";

      printf("<filled><submit next=\"%s\"%s%s/></filled>\n",
             $url, $namelist, $sendmethod);
    }
  }
};






/**
  This class provides a link in a HAW_deck, HAW_linkset or HAW_table object.
  <p><b>Examples:</b><p>
  $myPage = new HAW_deck(...);<br>
  ...<br>
  $myLink = new HAW_link("Continue","/mynextpage.php");<br>
  $myPage->add_link($myLink);
  @see HAW_deck
  @see HAW_linkset
  @see HAW_table
*/
class HAW_link
{
  var $label;
  var $url;
  var $title;
  var $accesskey; // "1", "2", ... "0", "*", "#"
  var $css_class;
  var $image;
  var $br;
  var $voice_text;
  var $voice_audio_src;
  var $voice_timeout;
  var $voice_input;

  /**
    Constructor
    @param label Describes the link on the visitor's screen/display.
    @param url Next destination address.
    @param title (optional)<br>If a string is provided here, it will be displayed
       by means of the title option on bigscreen browsers, respectively somewhere
       on the WAP/XHTML display. In order to work well with a broad range of user agents,
       keep your title under 6 characters.
  */
  function HAW_link($label, $url, $title="")
  {
    $this->label = $label;
    $this->url = $url;
    $this->title = $title;
    $this->accesskey = HAW_NO_ACCESSKEY; // no accesskey assigned
                                         // can be assigned later e.g. from HAW_linkset object if required
    $this->css_class = "";
    $this->image = false;
    $this->br = 1; // default: 1 line break after text
    $this->voice_text = $label;
    $this->voice_audio_src = "";
    $this->voice_timeout = 10; // voice decks with text and links only terminate after 10 sec
    $this->voice_input = $label;
  }

  /**
    Adds an image that is to be displayed on the left side of the link.
    @param image HAW_image object that contains the image/icon
    @see HAW_image
  */
  function add_image($image)
  {
    $this->image = $image;
  }

  /**
    Sets a css class for this object in a (X)HTML page.
    @param css_class CSS class to be defined in the deck's central CSS file
  */
  function set_css_class($css_class)
  {
    $this->css_class = $css_class;
  }

  /**
    Sets the number of line breaks (CRLF) after link. (default: 1)
    @param br Some number of line breaks.
  */
  function set_br($br)
  {
    if (!is_int($br) || ($br < 0))
      die("invalid argument in set_br()");

    $this->br = $br;
  }

  /**
    Sets link text to be spoken by voice browsers. <br>
    @param text Some alternative text that replaces &lt;label&gt;.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
  */
  function set_voice_text($text, $audio_src="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_text()");

    $this->voice_text = $text;
    $this->voice_audio_src = $audio_src;
  }

  /**
    Sets the link timeout for voice browsers (default: 10 sec). <br>
    Note: Voice decks with text only will force a disconnect immediately after the
    complete text has been spoken.
    Voice decks with text and links will wait some time for user voice input and will
    initiate the disconnect too, if no user input is received.
    For each link it is possible to specify an individual timeout value, i.e. a voice
    deck does not disconnect before the longest timeout of all active links expires.
    @param timeout Timeout in seconds.
  */
  function set_voice_timeout($timeout)
  {
    if (!is_int($timeout) || ($timeout < 1))
      die("invalid argument in set_voice_timeout()");

    $this->voice_timeout = $timeout;
  }

  /**
    Sets a DTMF key as link trigger for voice browsers.
    @param key "1", "2", ..., "9", "0", "*", "#"
  */
  function set_voice_dtmf($key)
  {
    if (!ereg("[0-9#\*]", $key))
      die("invalid argument in set_voice_dtmf()");

    $this->set_accesskey($key);
  }

  /**
    Sets a phrase the voice browser is reacting on instead of &lt;label&gt;.
    @param phrase some text the user has to speak in order to trigger the link<br />
       Pass an empty string, if voice triggering should be disabled. In this case you
       should use set_voice_dtmf to correlate the link with a given DTMF event. Otherwise
       the link could not be triggered at all.
  */
  function set_voice_input($phrase)
  {
    $this->voice_input = $phrase;
  }

  function get_url()
  {
    return $this->url;
  }

  function get_label()
  {
    return $this->label;
  }

  function get_title()
  {
    return $this->title;
  }

  function get_accesskey()
  {
    return $this->accesskey;
  }

  function get_elementtype()
  {
    return HAW_LINK;
  }

  function set_accesskey($accesskey)
  {
    $this->accesskey = $accesskey;
  }

  function create(&$deck)
  {
    // add hawoutput query parameter to url, if required
    HAW_handle_forced_output($this->url, $deck->hawoutput);

    if ($this->url)
    {
      // inhibit "empty" links

      if ($deck->ml == HAW_HTML)
      {
        // create link in HTML

        $title_option = "";

        if ($this->title && ($deck->xhtml || $deck->lynx  || $deck->desktopBrowser))
          $title_option = sprintf(" title=\"%s\"", HAW_specchar($this->title, $deck));

        $accesskey_option = "";

        if (($this->accesskey != HAW_NO_ACCESSKEY) &&
            ($deck->iModestyle || $deck->xhtml))
          $accesskey_option = sprintf(" accesskey=\"%s\"", $this->accesskey);

        if ($deck->MMLstyle && ($this->accesskey != HAW_NO_ACCESSKEY))
          $accesskey_option = sprintf(" directkey=\"%s\"", $this->accesskey);

        // create required amount of carriage return's
        $br = "";
        for ($i=0; $i < $this->br; $i++)
          $br .= "<br />";

        if ($deck->css_enabled && $this->css_class)
        {
          printf("<span class=\"%s\">\n", $this->css_class);
        }

        if ($deck->link_brackets == true)
          echo "[";
          
        printf("<a href=\"%s\"%s%s>",
               HAW_specchar($this->url, $deck), $title_option, $accesskey_option);

        if (is_object($this->image))
          // image available for this link, let it create itself
          $this->image->create($deck);

        printf("%s</a>", HAW_specchar($this->label, $deck));
        
        if ($deck->link_brackets == true)
          echo "]";
        
        printf("%s\n", $br);
        
        if ($deck->css_enabled && $this->css_class)
        {
          echo "</span>\n";
        }
      }

      elseif ($deck->ml == HAW_WML)
      {
        // create link in WML

        if ($this->title)
          $title_option = sprintf(" title=\"%s\"",
                                   HAW_specchar($this->title, $deck));
        else
          $title_option = "";

        // create required amount of carriage return's
        $br = "";
        for ($i=0; $i < $this->br; $i++)
          $br .= "<br/>";

        printf("<a%s href=\"%s\">", $title_option, HAW_specchar($this->url, $deck));

        if (is_object($this->image))
          // image available for this link, let it create itself
          $this->image->create($deck);

        printf("%s</a>%s\n", HAW_specchar($this->label, $deck), $br);
      }

      elseif ($deck->ml == HAW_HDML)
      {
        // create link in HDML

        if ($this->title)
          $title_option = sprintf(" label=\"%s\"",
                                   HAW_specchar($this->title, $deck));
        else
          $title_option = "";

        if ($this->accesskey != HAW_NO_ACCESSKEY)
          $accesskey_option = sprintf(" accesskey=\"%s\"", $this->accesskey);
        else
          $accesskey_option = "";

        // create required amount of carriage return's
        $br = "";
        for ($i=0; $i < $this->br; $i++)
          $br .= "<br>";
          
        if (is_object($this->image) && (strlen($this->label) == 0))
          // workaround for (non-displayable) images and non-existing labels
          $this->label = $this->image->alt;

        $content = sprintf("<a task=\"go\" dest=\"%s\"%s%s>%s</a>%s\n",
                            HAW_specchar($this->url, $deck),
                            $title_option, $accesskey_option,
                            HAW_specchar($this->label, $deck), $br);

        $deck->hdmlcardset->add_display_content($content);
      }

      elseif ($deck->ml == HAW_VXML)
      {
        if ($this->accesskey != HAW_NO_ACCESSKEY)
          $dtmf = sprintf(" dtmf=\"%s\"", $this->accesskey);
        else
          $dtmf = "";

        if ($this->voice_input == "")
           $grammar = "";
        else
        {
          // GSL allows only lower case words
          $label = strtolower($this->voice_input);

          // remove all invalid GSL characters (according Nuance Grammar Developer's Guide)
          $label = HAW_remove_invalid_gsl_chars($label);

          // make it VoiceXML compliant
          $label = HAW_specchar($label, $deck);

          $grammar = sprintf("<grammar>[(%s)]</grammar>", $label);
        }

        if ($dtmf || $grammar)
        {
          // prepare tag for VoiceXML link (will be written at form end)
          $deck->voice_links .= sprintf("<link next=\"%s\"%s>$grammar</link>\n",
                                         HAW_specchar($this->url, $deck), $dtmf, $grammar);
        }

        if ($this->voice_text || $this->voice_audio_src)
        {
          // create audio output for VoiceXML link

          echo "<block><prompt>";

          if ($deck->voice_jingle)
          {
            // play jingle before link label is spoken
            printf("<audio src=\"%s\"></audio>", $deck->voice_jingle);
          }

          if ($this->br > 0)
            $pause = $this->br * HAW_VOICE_PAUSE; // short pause for each break
          else
            $pause = HAW_VOICE_PAUSE; // at least a short break to make the link phrase detectable

          HAW_voice_audio(HAW_specchar($this->voice_text, $deck),
                          $this->voice_audio_src, $pause, $deck);

          echo "</prompt></block>\n";
        }

        // update deck timeout
        if ($deck->voice_timeout < $this->voice_timeout)
          $deck->voice_timeout = $this->voice_timeout;
      }
    }
  }
};






/**
  This class provides a phone number in a HAW_deck object. If supported by their
  mobile device, users can establish an outgoing call to the specified number.
  <p><b>Examples:</b><p>
  $myPage = new HAW_deck(...);<br>
  ...<br>
  $myPhone = new HAW_phone("123-45678", "CALL");<br>
  $myPage->add_phone($myPhone);
  @see HAW_deck
*/
class HAW_phone
{
  var $label;
  var $destination;
  var $isPOTS; // Plain Old Telephony System
  var $title;
  var $accesskey; // "1", "2", ... "0", "*", "#"
  var $image;
  var $css_class;
  var $br;
  var $voice_text;
  var $voice_audio_src;
  var $voice_timeout;
  var $voice_input;

  /**
    Constructor
    @param destination Phone number for outgoing call.
    @param title (optional)<br>If a string is provided here, the call button on a
       WAP/HDML/XHTML device will be entitled. In order to work well with a broad range
       of user agents, keep your title under 6 characters.
  */
  function HAW_phone($destination, $title="")
  {
    $this->label = $destination;
    
    if (strstr($destination, ":")) {
      // destination with protocol part, e.g. sip:alice@atlanta.com 	
      $this->destination = $destination;
      $this->isPOTS = false;
    }
    else {
      // plain old telephone number - remove blanks, hyphens etc.
      $this->destination = ereg_replace("[^+0-9]", "", $destination);
      $this->isPOTS = true;
    }
    
    $this->title = $title;
    $this->accesskey = HAW_NO_ACCESSKEY;
    $this->image = false;
    $this->css_class = "";
    $this->br = 1; // default: 1 line break
    $this->voice_text = $destination;
    $this->voice_audio_src = "";
    $this->voice_timeout = 10;
    $this->voice_input = "";
  }

  /**
    Adds an image that is to be displayed on the left side of the phone link.
    @param image HAW_image object that contains the image/icon
    @see HAW_image
  */
  function add_image($image)
  {
    $this->image = $image;
  }

  /**
    Sets a css class for this object in a (X)HTML page.
    @param css_class CSS class to be defined in the deck's central CSS file
  */
  function set_css_class($css_class)
  {
    $this->css_class = $css_class;
  }

  /**
    Sets the number of line breaks (CRLF) after phone link. (default: 1)
    @param br Some number of line breaks.
  */
  function set_br($br)
  {
    if (!is_int($br) || ($br < 0))
      die("invalid argument in set_br()");

    $this->br = $br;
  }

  /**
    Sets text to be spoken by voice browsers. <br>
    @param text Some alternative text that replaces &lt;phone_number&gt;.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
  */
  function set_voice_text($text, $audio_src="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_text()");

    $this->voice_text = $text;
    $this->voice_audio_src = $audio_src;
  }

  /**
    Sets the timeout for voice browsers (default: 10 sec). <br>
    @see HAW_link
    @param timeout Timeout in seconds.
  */
  function set_voice_timeout($timeout)
  {
    if (!is_int($timeout) || ($timeout < 1))
      die("invalid argument in set_voice_timeout()");

    $this->voice_timeout = $timeout;
  }

  /**
    Sets a DTMF key as call trigger for voice browsers.
    @param key "1", "2", ..., "9", "0", "*", "#"
  */
  function set_voice_dtmf($key)
  {
    if (!ereg("[0-9#\*]", $key))
      die("invalid argument in set_voice_dtmf()");

    $this->accesskey = $key;
  }

  /**
    Sets a phrase the voice browser is reacting on.
    @param phrase some text the user has to speak in order to trigger an outgoing
       call<br />
       Pass an empty string, if voice triggering should be disabled. In this case you
       should use set_voice_dtmf to correlate the call with a given DTMF event. Otherwise
       the outgoing call could not be triggered at all.
  */
  function set_voice_input($phrase)
  {
    $this->voice_input = $phrase;
  }

  function get_elementtype()
  {
    return HAW_PHONE;
  }

  function create(&$deck)
  {
    if ($deck->ml == HAW_HTML)
    {
      if ($this->title)
        $title_option = sprintf(" title=\"%s\"", HAW_specchar($this->title, $deck));
      else
        $title_option = "";

	  // create required amount of carriage return's
      $br = "";
      for ($i=0; $i < $this->br; $i++)
        $br .= "<br />";

      if ($deck->iModestyle) {
        if ($this->isPOTS)
          $dest = "phoneto:" . $this->destination;
        else
          $dest = $this->destination;
      }
      elseif ($deck->MMLstyle || $deck->iPhoneAlike) {
        if ($this->isPOTS)
          $dest = "tel:" . $this->destination;
        else
          $dest = $this->destination;
      }
      elseif ($deck->desktopBrowser) {
        if ($this->isPOTS)
          $dest = "callto:" . $this->destination;
        else
          $dest = $this->destination;
      }
      else {
        // create wtai: link for all other HTML browsers
        // (they will at least throw an error message if a click on it does not work)
        if ($this->isPOTS)
          $dest = "wtai://wp/mc;" . ereg_replace("[+]", "%2B", $this->destination);
        else
          $dest = $this->destination;
      }

      if ($deck->css_enabled && $this->css_class)
      {
        printf("<span class=\"%s\">\n", $this->css_class);
      }

      if ($deck->link_brackets == true)
        echo "[";
          
      printf("<a href=\"%s\"%s>", $dest, $title_option);

      if (is_object($this->image))
        // image available for this link, let it create itself
        $this->image->create($deck);

      if ($deck->css_enabled)
        printf("<span class=\"hawphonelabel\">%s</span></a>", HAW_specchar($this->label, $deck));
      else
        printf("%s</a>", HAW_specchar($this->label, $deck));
      
      if ($deck->link_brackets == true)
        echo "]";
        
      printf("%s\n", $br);

      if ($deck->css_enabled && $this->css_class)
      {
        echo "</span>\n";
      }
    }
    elseif ($deck->ml == HAW_WML)
    {
      // create phone number in WML

      if ($this->title)
        $title_option = sprintf(" title=\"%s\"", HAW_specchar($this->title, $deck));
      else
        $title_option = "";

      // create required amount of carriage return's
      $br = "";
      for ($i=0; $i < $this->br; $i++)
        $br .= "<br/>";

      if ($this->isPOTS)
        $dest = "wtai://wp/mc;" . ereg_replace("[+]", "%2B", $this->destination);
      else
        $dest = $this->destination;

      printf("<a href=\"%s\"%s>", $dest, $title_option);

      if (is_object($this->image))
        // image available for this link, let it create itself
        $this->image->create($deck);

      printf("%s</a>%s\n", HAW_specchar($this->label, $deck), $br);
    }
    elseif ($deck->ml == HAW_HDML)
    {
      // create phone number in HDML

      if ($this->title)
        $title_option = sprintf(" label=\"%s\"", HAW_specchar($this->title, $deck));
      else
        $title_option = "";

      // create required amount of carriage return's
      $br = "";
      for ($i=0; $i < $this->br; $i++)
        $br .= "<br>";

      $content = sprintf("<a task=\"call\" number=\"%s\"%s>%s</a>%s\n",
                          $this->destination, $title_option,
                          HAW_specchar($this->label, $deck), $br);

      $deck->hdmlcardset->add_display_content($content);
    }
    elseif ($deck->ml == HAW_VXML)
    {
      // handle phone number in VXML
      
      if ($this->accesskey != HAW_NO_ACCESSKEY)
        $dtmf = sprintf(" dtmf=\"%s\"", $this->accesskey);
      else
        $dtmf = "";

      if ($this->voice_input == "")
         $grammar = "";
      else
      {
        // GSL allows only lower case words
        $label = strtolower($this->voice_input);

        // remove all invalid GSL characters (according Nuance Grammar Developer's Guide)
        $label = HAW_remove_invalid_gsl_chars($label);

        // make it VoiceXML compliant
        $label = HAW_specchar($label, $deck);

        $grammar = sprintf("<grammar>[(%s)]</grammar>", $label);
      }

      if ($dtmf || $grammar)
      {
        // create transfer link

        if ($this->isPOTS)
          $dest = "tel:" . $this->destination;
        else
          $dest = $this->destination;

        // prepare tag for VoiceXML link (will be written at form end)
        static $formid_counter = 1;
        $deck->voice_links .= sprintf("<link next=\"#transfer%d\"%s>$grammar</link>\n",
                                      $formid_counter, $dtmf, $grammar);
        $deck->voice_links .= sprintf("<form id=\"transfer%d\">\n", $formid_counter++);
        $deck->voice_links .= sprintf("<transfer dest=\"%s\" bridge=\"false\" />\n", $dest);
        $deck->voice_links .= "</form>\n";
      }
      else
        $this->voice_timeout = 0; // no timeout required for non-triggerable transfer

      if ($this->voice_text || $this->voice_audio_src)
      {
        // create audio output for VoiceXML link

        echo "<block><prompt>";

        if ($deck->voice_jingle)
        {
          // play jingle in advance
          printf("<audio src=\"%s\"></audio>", $deck->voice_jingle);
        }

        if (!$this->voice_audio_src &&
             (strcmp($this->voice_text, $this->label) == 0) &&
             ($this->isPOTS))
        {
          // plain old telephone number and no output modification
          // read digits more user-friendly ...
          // do not rely on VoiceXML elements like <say-as> !!!
          // plus digits, long numbers etc. make the whole thing finally fail ...

          if ($deck->supportsVXMLBreakTag)
            $break = "<break time=\"500ms\"/>"; // make significant pause between each digit
          else
            $break = "";

          for ($i=0; $i<strlen($this->destination); $i++)
            echo substr($this->destination, $i, 1) . " " . $break;
        }
        else
        {
          HAW_voice_audio(HAW_specchar($this->voice_text, $deck),
                          $this->voice_audio_src, HAW_VOICE_PAUSE, $deck);
        }

        echo "</prompt></block>\n";
      }

      // update deck timeout
      if ($deck->voice_timeout < $this->voice_timeout)
        $deck->voice_timeout = $this->voice_timeout;
    }
  }
};






/**
  This class defines a set of links. It should be preferably used for all kinds of menus.
  The links have to be defined as separate HAW_link objects and are attached to the
  linkset with a special "add_link" function.
  For WAP devices browser-dependent WML code will be created. On all UP-browser-based
  WAP devices linksets allow easier navigation through WML decks by using the "onpick"
  WML option and therefore are improving the "usability" of an application. Instead of
  painfully navigating through the links "sports->football->results->today" the mobile
  user e.g. can press "2431" on the keypad to enter his favorite deck. For all other
  WAP devices normal &lt;a&gt; tags are created. One HAW_deck object can contain only
  one linkset object.
  <p><b>Examples:</b><p>
  $myPage = new HAW_deck(...);<br>
  ...<br>
  $myLinkset = new HAW_linkset();<br>
  $myLink1 = new HAW_link("Phonebook", "/wap/phonebook.php");<br>
  $myLinkset->add_link($myLink1);<br>
  $myLink2 = new HAW_link("DateBook", "/wap/datebook.php");<br>
  $myLinkset->add_link($myLink2);<br>
  ...<br>
  $myPage->add_linkset($myLinkset);<br>
  ...<br>
  $myPage->create_page();
  @see HAW_link
  @see HAW_deck
*/
class HAW_linkset
{
  var $element;
  var $number_of_elements;
  var $voice_text;
  var $voice_audio_src;
  var $voice_help;
  var $voice_noinput;
  var $voice_nomatch;


  /**
    Constructor
  */
  function HAW_linkset()
  {
    $this->number_of_elements = 0;
    $this->voice_text = HAW_VOICE_ENUMERATE;
    $this->voice_audio_src = "";
  }


  /**
    Adds a HAW_link object to HAW_linkset
    @param link Some HAW_link object.
    @see HAW_link
  */
  function add_link(&$link)
  {
    if (!is_object($link))
      die("invalid argument in add_link()");

    if ($this->number_of_elements < 12)
    {
      // number of possible access keys not exhausted

      $accesskey = $this->number_of_elements + 1;   // start with key 1

      if ($accesskey == 12)
        $accesskey = "#";
      elseif ($accesskey == 11)
        $accesskey = "*";
      elseif ($accesskey == 10)
        $accesskey = "0";

      $link->set_accesskey($accesskey);
    }

    $this->element[$this->number_of_elements] = $link;

    $this->number_of_elements++;
  }

  /**
    Sets text to be spoken by voice browsers. <br>
    @param text Some alternative text that precedes the enumeration of link &lt;label&gt;s.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
  */
  function set_voice_text($text, $audio_src="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_text()");

    $this->voice_text = $text;
    $this->voice_audio_src = $audio_src;
  }

  function get_elementtype()
  {
    return HAW_LINKSET;
  }

  function create(&$deck)
  {
    if ($this->number_of_elements > 0)
    {
      if ($deck->ml == HAW_HTML)
      {
        // create linkset in HTML

        if ($deck->css_enabled)
        {
          // create links inside a frame

          echo "<div id=\"hawlinkset\">\n";
          
          if ($deck->lynx)
          {
            // create link list to avoid whitespace between links
            echo "<ul>\n";
            while (list($key, $val) = each($this->element))
            {
              echo "<li>\n";
              $val->create($deck); // create one list element for each link
              echo "</li>\n";
            }
            echo "</ul>\n";
          }
          else
          {
            while (list($key, $val) = each($this->element))
              $val->create($deck);
          }

          echo "</div>\n";
        }
        else
          // create normal links for the small devices
          while (list($key, $val) = each($this->element))
            $val->create($deck);
      }

      elseif ($deck->ml == HAW_WML)
      {
        // create linkset in WML

        if ($deck->upbrowser &&
           ($deck->number_of_forms == 0) &&
           ($deck->number_of_links == 0) &&
           ($deck->number_of_phones == 0))
        {
          echo "<select>\n";

          while (list($key, $val) = each($this->element))
          {
            if ($val->get_title())
              $title = " title=\"" . HAW_specchar($val->get_title(), $deck) . "\"";

            printf("<option onpick=\"%s\"%s>%s</option>\n",
                   HAW_specchar($val->get_url(), $deck), $title,
                   HAW_specchar($val->get_label(), $deck));
          }

          echo "</select>\n";
        }
        else
          // create normal WML links
          while (list($key, $val) = each($this->element))
            $val->create($deck);
      }

      elseif ($deck->ml == HAW_HDML)
      {
        // create linkset in HDML

        while (list($key, $val) = each($this->element))
          $val->create($deck);
      }

      elseif ($deck->ml == HAW_VXML)
      {
        // create linkset for VoiceXML

        if ($this->voice_text || $this->voice_audio_src)
        {
          if ($this->voice_text != HAW_VOICE_ENUMERATE)
          {
            echo "<block><prompt>";

            HAW_voice_audio(HAW_specchar($this->voice_text, $deck),
                            $this->voice_audio_src, 0, $deck);

            echo "</prompt></block>\n";
          }
        }

        while (list($key, $val) = each($this->element))
          $val->create($deck);

      }
    }
  }
};






/**
  This class allows direct insertion of markup code - Handle with care!
  <p><b>Example:</b><p>
  <pre>
  $myPage = new HAW_deck(...);
  $pageContent = new HAW_text("Here comes some content ...");
  $myPage->add_text($pageContent);
  $myHtmlCode = "<p>Here comes an adblock for html devices ...</p>";
  $myAdblock = new HAW_raw(HAW_HTML, $myHtmlCode);
  $myPage->add_raw($myAdblock);
  $myPage->create_page();
  </pre>
  @see HAW_deck
  @see HAW_form
*/
class HAW_raw
{
  var $ml;
  var $code;

  /**
    Constructor
    @param ml Markup language (HAW_HTML, HAW_WML, HAW_HDML, HAW_VXML).
    @param code Some markup code to be inserted for the selected markup language
  */
  function HAW_raw($ml,$code)
  {
    $this->ml = $ml;
    $this->code = $code;
  }

  function get_elementtype()
  {
    return HAW_RAW;
  }

  function create($deck)
  {
    if ($deck->ml == $this->ml)
      echo($this->code);
  }
};






/**
  This class provides a banner in a HAW_deck object (HTML only).
  <p><b>Examples:</b><p>
  $myPage = new HAW_deck(...);<br>
  ...<br>
  $myBanner1 = new HAW_banner("http://wwww.adpartner1.org/images/adp1.gif", "http://www.adpartner1.org/", "Welcome at adpartner1!");<br>
  $myPage->add_banner($myBanner1);<br>
  ...<br>
  $myBanner2 = new HAW_banner("http://wwww.adpartner2.org/images/adp2.gif", HAW_NOLINK, "Buy products of adpartner2!");<br>
  $myBanner2->set_size(300,50);<br>
  $myBanner2->set_br(0);<br>
  $myPage->add_banner($myBanner2,HAW_TOP);
  @see HAW_deck
*/
class HAW_banner
{
  var $image;
  var $url;
  var $alt;
  var $width = -1;
  var $height = -1;
  var $br = 1;

  /**
    Constructor
    @param image Your ad-partners banner in .gif, .jpg or any other HTML compatible format.
    @param url Link to your ad-partner (or HAW_NOLINK if no link available)
    @param alt Alternative text for the banner
  */
  function HAW_banner($image, $url, $alt)
  {
    $this->image = $image;
    $this->url = $url;
    $this->alt = $alt;
  }

  /**
    Sets explicitely the size of a banner<br>
    Note: Use of this function is not mandatory but will accelerate page set-up
    @param width Width of banner in pixels.
    @param height Height of banner in pixels.
  */
  function set_size($width, $height)
  {
    if (!is_int($width) || ($width < 1) || !is_int($height) || ($height < 1))
      die("invalid argument in set_size()");

    $this->width = $width;
    $this->height = $height;
  }

  /**
    Sets the number of line breaks (CRLF) after banner. (default: 1)
    @param br Some number of line breaks.
  */
  function set_br($br)
  {
    if (!is_int($br) || ($br < 0))
      die("invalid argument in set_br()");

    $this->br = $br;
  }


  function create()
  {
    if ($this->url != HAW_NOLINK)
      // banner links to url
      echo "<a href=\"$this->url\" target=\"_blank\">";

    if (($this->width>0) && ($this->height>0))
      // prepare this variable only if size info available
      $size = sprintf(" width=\"%d\" height=\"%d\"", $this->width, $this->height);
    else
      $size = "";

    printf("<img src=\"%s\" alt=\"%s\"%s hspace=\"10\" vspace=\"10\" border=\"0\" />",
           $this->image, $this->alt, $size);

    if ($this->url != HAW_NOLINK)
      // close <a> tag
      echo "</a>\n";

    for ($i=0; $i<$this->br; $i++)
      // create required number of line breaks
      echo "<br />\n";
  }
};






/**
  This class will cause a (hawrizontal) rule to be drawn across the screen.
  You can use it to separate text paragraphs in HAW_deck or HAW_form objects.
  <p><b>Examples:</b><p>
  $myDefaultRule = new HAW_rule();<br>
  $mySpecialRule = new HAW_rule("60%", 4);<br>
  ...<br>
  $myPage->add_rule($myDefaultRule)<br>;
  ...<br>
  $myPage->add_rule($mySpecialRule);
  @see HAW_deck
  @see HAW_form
*/
class HAW_rule
{
  var $width;
  var $size;


  /**
    Constructor
    @param width (optional)<br>Percentage of screen width or absolute value in
           number of pixels (e.g. "50%", 100).
    @param size (optional)<br>Height of the line to be drawn in pixels.
  */
  function HAW_rule($width="", $size="")
  {
    $this->width = $width;
    $this->size = $size;
  }


  function get_elementtype()
  {
    return HAW_RULE;
  }


  function create(&$deck)
  {
    $width_option = ($this->width ? " width=\"" . $this->width . "\"" : "");
    $size_option  = ($this->size  ? " size=\"" . $this->size . "\""   : "");

    if ($deck->ml == HAW_HTML)
      // draw horizontal row in HTML
      echo "<hr" . $width_option . $size_option . " />\n";

    elseif ($deck->ml == HAW_WML)
    {
      if ($deck->owgui_1_3)
        // WAP device accepts Openwave GUI extensions for WML 1.3
        echo "<hr" . $width_option . $size_option . "/>\n";
      else
        // WAP device does not understand <hr> tags
        // ==> draw some number of hyphens to create a rule
        echo "----------<br/>\n";
    }
    elseif ($deck->ml == HAW_HDML)
    {
      // HDML devices doesn't understand <hr>
      // ==> draw some number of hyphens to create a rule

      if ($deck->alignment != "left")
        $deck->hdmlcardset->add_display_content("<" . $deck->alignment . ">\n");

      $deck->hdmlcardset->add_display_content("----------\n<br>\n");
    }
    elseif ($deck->ml == HAW_VXML)
    {
      // make a longer speech pause
      if ($deck->supportsVXMLBreakTag)
        printf("<block><prompt><break time=\"%dms\"/></prompt></block>\n",
                3 * HAW_VOICE_PAUSE);
    }
  }
};






/**
  This class provides a voice recorder in a HAW_deck object.<br><br>
  Voice recording is feature for voice browsers only (VoiceXML). 
  The recorded voice input will be sent encrypted as multipart/form-data
  to some other url, which normally will be another PHP/HAWHAW script. Here you can
  store the received data as .wav file on your server, play it to the user, or do whatever
  you want. Saving of the received voice data is similar to normal PHP file upload handling.
  <br><br>
  Voice recording is a very powerful feature which offers many oportunities to
  create high-sophisticated voice applications.<br><br>
  <p><b>Examples:</b><p>
  $myRecorder = new HAW_voicerecorder("http://www.foo.com/script.php", "Please speak after the tone");<br>
  <br>
  $myRecorder = new HAW_voicerecorder("http://www.foo.com/script.php", "You have 2 minutes from now");<br>
  $myRecorder->make_beep(false);<br>
  $myRecorder->set_maxtime(120);<br><br>
  // ... and in http://www.foo.com/script.php we store the received wav file like this:<br>
  move_uploaded_file($_FILES['haw_recording']['tmp_name'], "/voice/message.wav");
  @see HAW_deck
*/
class HAW_voicerecorder
{
  var $url;
  var $label;
  var $beep;
  var $maxtime;
  var $finalsilence;
  var $type;
  var $voice_text;
  var $voice_audio_src;
  var $voice_noinput;

  /**
    Constructor
    @param url Address where the recorded file is sent to.
    @param label Some introducing words before the recording starts.
  */
  function HAW_voicerecorder($url, $label)
  {
    $this->url             = $url;
    $this->label           = $label;
    $this->beep            = true;
    $this->maxtime         = "";
    $this->finalsilence    = "";
    $this->type            = "";
    $this->voice_text      = $label;
    $this->voice_audio_src = "";
    $this->voice_noinput   = array();
  }

  /**
    Sets text to be spoken by voice browsers.
    @param text Some alternative text that replaces &lt;label&gt;.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
  */
  function set_voice_text($text, $audio_src="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_text()");

    $this->voice_text = $text;
    $this->voice_audio_src = $audio_src;
  }

  /**
    Sets noinput text for voice browsers. <br>
    @param text Some text to inform the user that no input has been received.
    @param audio_src Some audio file (e.g. *.wav file) to play (optional).
    @param url Some other voice deck to go to (optional).
  */
  function set_voice_noinput($text, $audio_src="", $url="")
  {
    if (!is_string($text))
      die("invalid argument in set_voice_noinput()");

    $arr["text"] = $text;
    $arr["src"]  = $audio_src;
    $arr["url"]  = $url;

    $this->voice_noinput[] = $arr;
  }

  /**
    Activates/deactivates beep before recording starts.
    @param beep_indicator: true (default) or false.
  */
  function make_beep($beep_indicator)
  {
    if ($beep_indicator == false)
      $this->beep = false;
  }

  /**
    Sets maximum duration of recording.
    @param maxtime Duration of record in seconds
  */
  function set_maxtime($maxtime)
  {
    $this->maxtime = $maxtime;
  }

  /**
    Sets interval of silence that indicates end of speech.
    @param finalsilence Silence duration (in seconds)
  */
  function set_finalsilence($finalsilence)
  {
    $this->finalsilence = $finalsilence;
  }

  /**
    Sets media format of recording.
    @param type e.g. "audio/x-wav"
  */
  function set_type($type)
  {
    $this->type = $type;
  }

  function get_elementtype()
  {
    return HAW_VOICERECORDER;
  }

  function create(&$deck)
  {
    if ($deck->ml == HAW_VXML)
    {
      // VoiceXML (no output for non-voice browsers)

      if ($this->maxtime)
        $maxtime = sprintf(" maxtime=\"%ds\"", $this->maxtime);
      else
        $maxtime = "";
  
      if ($this->finalsilence)
        $finalsilence = sprintf(" finalsilence=\"%ds\"", $this->finalsilence);
      else
        $finalsilence = "";
  
      if ($this->type)
        $type = sprintf(" type=\"%s\"", $this->type);
      else
        $type = "";
  
      if ($this->beep)
        $beep = "true";
      else
        $beep = "false";
  
      printf("<record name=\"haw_recording\" beep=\"%s\"%s%s%s>\n",
             $beep, $maxtime, $finalsilence, $type);
  
      if ($this->voice_text || $this->voice_audio_src)
      {
        echo "<prompt>";
  
        HAW_voice_audio(HAW_specchar($this->voice_text, $deck),
                        $this->voice_audio_src, 0, $deck);
  
        echo "</prompt>\n";
      }

      // replace '&' character in URL with '&amp;'
      $this->url = ereg_replace("&", "&amp;", $this->url);

      printf("<filled><submit next=\"%s\" enctype=\"multipart/form-data\" method=\"post\" namelist=\"haw_recording\"/></filled>\n",
              $this->url);

      // create event handler for noinput
      HAW_voice_eventhandler("noinput", $this->voice_noinput, $deck);
    
      // create catch handler for disconnect
      printf("<catch event=\"connection.disconnect.hangup\"><submit next=\"%s\" enctype=\"multipart/form-data\" method=\"post\" namelist=\"haw_recording\"/></catch>\n",
              $this->url);

      echo "</record>\n";
    }
  }
};

?>