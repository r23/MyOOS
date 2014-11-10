<?php
/* $Id: rdf.class.php,v 1.2 2009/01/17 17:35:46 r23 Exp $ */

//
// +----------------------------------------------------------------------+
// | rss Parser                                                           |
// | Copyright (c) 2001 Stefan Saasen                                     |
// +----------------------------------------------------------------------+
// | The contents of this file are subject to the Mozilla Public License  |
// | Version 1.1 (the "License"); you may not use this file except in     |
// | compliance with the License. You may obtain a copy of the License at |
// | http://www.mozilla.org/MPL/                                          |
// |                                                                      |
// | Software distributed under the License is distributed on an "AS IS"  |
// | basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See  |
// | the License for the specific language governing rights and           |
// | limitations under the License.                                       |
// +----------------------------------------------------------------------+
// |                                                                      |
// | Maintainer and initial developer:                                    |
// | Stefan Saasen <s@fase4.com>                                          |
// |                                                                      |
// | Proxy and authentication methods added by:                           |
// | Marco Kraus <marco.kraus@siemens.com>                                |
// +----------------------------------------------------------------------+
// | Ref:                                                                 |
// |   @link http://www.fase4.com/rdf/                   Latest release   |
// +----------------------------------------------------------------------+

/**
* Class RSS Parser
*
* This class offers methods to parse RSS Files
*
* @link      http://www.fase4.com/rdf/ Latest release of this class
* @package   rss
* @copyright Copyright (c) 2001 fase4.com. All rights reserved.
* @author    Stefan Saasen <s@fase4.com>
* @version   1.7 (Date: 2003/07/06 20:33:58) Revision: 1.40 
* @access    public
*/

class fase4_rdf {

    /**
    * If $_link_target is set a target='xxx' attribute in each <a /> and <form /> html tag will be added
    *
    * Possible values are "_blank", "_content", "_parent", "_self", "_top"
    *
    * @access private
    * @var    string
    */
    var $_link_target = "_blank";

    /**
    * vars for proxy settings - Prox Host
    *
    * @access private
    * @var      string
    */
    var $_phost = "";

    /**
    * vars for proxy settings - Prox Port
    *
    * @access private
    * @var      string
    */
    var $_pport = "";

    /**
    * vars for proxy settings - Prox Username
    *
    * @access private
    * @var      string
    */    
    var $_pname = "";

    /**
    * vars for proxy settings - Prox Password
    *
    * @access private
    * @var      string
    */
    var $_ppasswd = "";

    /**
    * just a flag for checking if proxy-support should be enabled
    * set default to false (will be enabled if set_proxy is called)
    *
    * @access   private
    * @see      set_proxy()
    * @var      bool
    */
    var $_use_proxy = false;

    /**
    * just a flag for checking if proxy-support with authentication
    * should be enabled
    * set default to false (will be enabled if set_proxy is called)
    *
    * @access   private
    * @see      set_proxy()
    * @var      boolean
    */
    var $_use_proxy_auth = false;

    /**
    * The time the Files will be cached (in seconds).
    *
    * @access private
    * @var    int
    */
    var $_refresh = 0;   // int

    /**
    * The Name of the cached File.
    *
    * @access private
    * @var    string
    */
    var $_cached_file = "";   // String

    /**
    * Indicates whether the cached or the remote file was used.
    *
    * @access private
    * @var    boolean
    */
    var $_use_cached_file = false;

    /**
    * (fast|normal) depends on _use_dynamic_display(). _use_dynamic_display( TRUE ) -> 'normal', otherwise 'fast'
    *
    * @access private
    * @var    string
    */
    var $_cache_type = "";

    /**
    * The Name of the Remote File.
    *
    * @access private
    * @var    string
    */
    var $_remote_file = "";

    /**
    * Path to the Cache Directory.
    *
    * @access private
    * @var    string
    */
    var $_cache_dir = "cache/";  // String

    /**
    * Indicates whether the Creating of the Cache Directory needs to be done or not.
    *
    * @access private
    * @var    boolean
    */
    var $_cache_dir_ok = false;

    /**
    * Type of the file to be parsed (RSS or RDF).
    *
    * The Type depends on the root node
    *
    * @access private
    * @var    string
    */
    var $_type = ""; // string (rss or rdf)

    /**
    * Array of Display Settings.
    *
    * Specific Parameters can be set to hidden. These are: 
    * image, channel and textinput. If set to "hidden" those elements won't be displayed.
    *
    * @access private
    * @var    array
    */
    var $_display_opt = array( 'build' => '', 'image' => '', 'channel' => '', 'textinput' => '', 'cache_update' => '');  // Array

    /**
    * Defines the width attribute in the table that holds the rdf/rss representation
    *
    * @access private
    * @var    int
    * @see    see_table_width()
    */
    var $_table_width = 400;

    /**
    * Indicates whether or not to use dynamic Display Settings
    *
    * @access private
    * @var    array
    */
    var $_use_dynamic_display = false;

    /**
    * <item> count
    *
    * @access private
    * @var    int
    */
    var $_item_count = 0;

    /**
    * No of max <item>s
    *
    * @access private
    * @var    boolean
    */
    var $_max_count = false;

    /**
    * Array containing the content of <channel />
    *
    * @access private
    * @var    array
    */
    var $_array_channel = array();

    /**
    * Array containing the content of each <item />
    *
    * @access private
    * @var    array
    */
    var $_array_item = array();

    /**
    * Array containing the content of <textinput />
    *
    * @access private
    * @var    array
    */
    var $_array_textinput = array();

    /**
    * Array containing the content of <image />
    *
    * @access private
    * @var    array
    */
    var $_array_image = array();

    /**
    * Array containing the Channel content. Just For internal XML Parser Purposes.
    *
    * @access private
    * @var    array
    */
    var $_citem = array();

    /**
    * Array containing the Channel Parser Depth. Just For internal XML Parser Purposes.
    *
    * @access private
    * @var    array
    */
    var $_cdepth = array();

    /**
    * Array containing the Channel tags. Just For internal XML Parser Purposes.
    *
    * @access private
    * @var    array
    */
    var $_ctags = array( "x" ); 

    /**
    * Array containing the Channel content. Just For internal XML Parser Purposes.
    *
    * @access private
    * @var    array
    */
    var $_item = array();   // Array

    /**
    * Array containing the Channel Parser Depth. Just For internal XML Parser Purposes.
    *
    * @access private
    * @var    array
    */
    var $_depth = array();  // Array

    /**
    * Array containing the tags. Just For internal XML Parser Purposes.
    *
    * @access private
    * @var    array
    */
    var $_tags = array( "x" );  // Array

    /**
    * Garbage collection: probability in percent
    *
    * @var      integer     0 => never
    * @access   public
    */
    var $gc_probability = 1;

    /**
    * HTML Output
    *
    * @var      string
    * @access   private
    */
    var $_output = "";

    /**
    * @var  string
    */
    var $_parse_mode = "";

    /**
    * Constructor of our Class
    *
    * This Method checks if the Cache Directory can be found. Otherwise it tries to creat the Cache Directory at the specified Path.
    * Additionally the Refresh Time is set to a default Value of 1200s (20 min).
    *
    * @access    public
    * @author    Stefan Saasen <s@fase4.com>
    * @see       _refresh
    */
    function fase4_rdf()
    {
        // default Value, to be overwritten in set_refresh()
        $this->_refresh = (time() - 1200);
        $this->_clear_cItems();
        $this->_clear_Items();
    }

    /**
    * This Method starts the parsing of the specified RDF File. The File can be a local or a remote File.
    *
    * @access    public
    * @author    Stefan Saasen <s@fase4.com>
    * @param     string $rdf    RDF File (Location)
    * @return    string Displays RDF Content ( using _display() )
    * @see       _remote_file, cache()
    */
    function parse_RDF( $rdf ) 
    { 
        unset($this->_array_item);
        $this->_remote_file = $rdf;
        echo "<!-- http://www.fase4.com/rdf/ -->\n";
        echo "<table width=\"".$this->_table_width."\">\n";
        echo decode($this->cache());
        echo "</table>\n";
        $this->_output = "";
        $this->_item_count = 0;
        return true;
    }

    /**
    * This Method is called when all parsing is finished to use the garbage collection
    *
    * @access    public
    * @author    Stefan Saasen <s@fase4.com>
    * @param     string $rdf    RDF File (Location)
    * @return    string Displays RDF Content ( using _display() )
    * @see        _remote_file, cache()
    */
    function finish() 
    { 
        flush();
        $this->_garbage_collection();
    }

    /**
    * With this method you can decide whether to use the normal cache and dynamic display Options or to use a static cache.
    *
    * In the first case the rdf/rss File will be stored locally, in the second case the html output of the specified source will be stored.
    * In this case you can not modify the display settings.
    * processing time: ( 1.4792) --> remote file
    * processing time: ( 0.0313) --> using 'normal cache' with display Modification turned on.
    * processing time: ( 0.0019) --> using 'fast cache'
    *
    * @access    public
    * @author    Stefan Saasen <s@fase4.com>
    * @param     string $rdf    RDF File (Location)
    * @return    string Displays RDF Content ( using _display() )
    * @see        _remote_file, cache()
    */
    function use_dynamic_display( $bool )
    {
        $this->_use_dynamic_display = $bool;
        return true;
    }

    /**
    * This Method avtually parses the XML data.
    *
    * @access    private
    * @author    Stefan Saasen <s@fase4.com>
    * @param     string $data    RDF File XML Data
    * @see       _clear_Items()
    */
    function _parse_xRDF( $data )
    {
        $this->_clear_Items(); 
        $xml_parser = xml_parser_create(); 
        xml_set_object($xml_parser,$this);
        xml_parser_set_option($xml_parser,XML_OPTION_CASE_FOLDING,0);
        xml_set_element_handler($xml_parser, "_startElement", "_endElement"); 
        xml_set_character_data_handler($xml_parser, "_parseData");    
        if (!xml_parse($xml_parser, trim($data))) {
                $this->_throw_exception(sprintf("XML error: %s at line %d", 
                xml_error_string(xml_get_error_code($xml_parser)), 
                xml_get_current_line_number($xml_parser))."<br /><br />Exception in function parse_RDF()."); 
            }
        xml_parser_free($xml_parser); 
    }


    /**
    * This Methods allows you to set the Refresh Time
    *
    * @access    public
    * @author    Stefan Saasen <s@fase4.com>
    * @param     int $seconds time files will be cached (in seconds). 
    * @return    boolean
    * @see       _refresh
    */
    function set_refresh( $seconds )
    {
        $this->_refresh = (time() - $seconds);
        return true;
    }

    /**
    * This Methods allows you to set the No. of <item>s to display
    *
    * @access    public
    * @param     int $int No of max <item>s
    * @author    Stefan Saasen <s@fase4.com>
    * @return    boolean
    * @see       _max_count, _endElement()
    */
    function set_max_item( $int )
    {
        $this->_max_count = $int;
        return true;
    }

    /**
    * This Methods allows you to set the Cache Directory
    *
    * @access    public
    * @author    Stefan Saasen <s@fase4.com>
    * @param     string $dir Path to Directory.
    * @return    boolean
    * @see       _cache_dir
    */
    function set_CacheDir( $dir ) 
    {
        if(substr($dir, -1) != "/") {
            $dir = $dir."/";
        }
        $this->_cache_dir = $dir;
    }

    /**
    * This Method displays Error Messages and terminates the Execution of the Script
    *
    * @access    private
    * @param     string $msg Message to display on failure
    * @author    Stefan Saasen <s@fase4.com>
    */
    function _throw_exception( $msg )
    {
        echo "<div style=\"font-family: verdana, helvetica, arial, sans-serif;font-size:11px; color: #6699cc;margin-top:10px;margin-bottom:10px;\" align=\"center\">fase4 RDF Error: ".$msg."</div>";
        return true;
    }

    /**
    * This Method clears the Array containig the Items.
    *
    * @access    private
    * @author    Stefan Saasen <s@fase4.com>
    * @see       _item
    */
    function _clear_Items( ) { 
        $this->_item = array(
            'title'=>"", 
            'link'=>"", 
            'description'=>"",
            'url'=>"",
            'language'=>"",
            'pubDate'=>"",
            'lastBuildDate'=>"",
            'width'=>'',
            'height'=>''
        );
    }
    /**
    * This Method clears the Array containig the Channel Items.
    *
    * @access    private
    * @author    Stefan Saasen <s@fase4.com>
    * @see       _item
    */
    function _clear_cItems( ) { 
        $this->_citem = array(
            'title'=>"", 
            'link'=>"", 
            'description'=>"",
            'url'=>"",
            'language'=>"",
            'copyright'=>"",
            'managingEditor'=>"",
            'webMaster'=>"",
            'pubDate'=>"",
            'lastBuildDate'=>"",
            'category'=>"",
            'generator'=>"",
            'docs'=>"",
            'cloud'=>"",
            'ttl'=>"",
            'image'=>"",
            'textinput'=>"",
            'skipHours'=>"",
            'skipDays' =>""
        );
    }

    /**
    * XML Parser Start Element Handler
    *
    * @access    private
    * @author    Stefan Saasen <s@fase4.com>
    * @param     mixed  $parser a reference to the XML parser calling the handler. 
    * @param     string $name contains the name of the element for which this handler is called.
    * @param     string $attrs contains an associative array with the element's attributes (if any). 
    * @see       _get_ChannelData(), _clear_Items(), _type, _parse_mode, _depth, _tags, _cdepth, _ctags
    */
    function _startElement($parser, $name, $attrs) {
        // We have to determine, which type of xml data we have to parse
        if($name == "rss") {
            $this->_type = "rss";
        } elseif($name == "rdf:RDF" OR $name == "rdf") {
            $this->_type = "rdf";
        }


        if ( $name == "channel" AND $this->_type != "rdf" ) {
            $this->_parse_mode = "channel";
        } elseif ( ($name=="item")
                    ||($name=="image")
                    ||($name=="textinput")
                    ||(($name=="channel") && ($this->_type != "rss")) ) {
            if($this->_parse_mode=="channel") {
                $this->_get_ChannelData( $parser );
            }
            $this->_parse_mode = "all";
        }

        if( !isset( $this->_depth[$parser] ) ) {
            $this->_depth[$parser] = 0;
        }
        $this->_depth[$parser]++; 
        array_push($this->_tags, $name); 
        
        if( !isset( $this->_cdepth[$parser] ) ) {
            $this->_cdepth[$parser] = 0;
        }
        $this->_cdepth[$parser]++; 
        array_push($this->_ctags, $name);
    }   // END _startElement()

    /**
    * Retrives the Channel Data in <rss> File
    *
    * @access    private
    * @author    Stefan Saasen <s@fase4.com>
    * @param     mixed  $parser a reference to the XML parser calling the handler. 
    * @see       _output, _display_opt, _citem
    */
    function _get_ChannelData( $parser )
    {
                if( empty($this->_display_opt["channel"]) OR
                    $this->_display_opt["channel"] != "hidden") {
                $this->_output .= "<tr><td>\n";
                $this->_output .= '<table border="0" width="100%" class="fase4_rdf_meta" cellspacing="5" cellpadding="2">'."\n";
                $this->_output .= "<tr><td class=\"fase4_rdf\"><div class=\"fase4_rdf_title\">".htmlspecialchars($this->_citem["title"])."</div></td></tr>\n"; 
                $this->_output .= "<tr><td class=\"fase4_rdf\">".strip_tags($this->_citem["description"], "<a>, <img>")."</td></tr>\n";
                $this->_output .= "<tr><td>&nbsp;</td></tr>\n";
                $this->_output .= "<tr><td class=\"fase4_rdf\">\n";
                if(isset($this->_display_opt["build"]) && $this->_display_opt["build"] != "hidden") {
                    if($this->_citem["lastBuildDate"]){$this->_output .= "build: ". $this->_citem["lastBuildDate"]."<br />";}
                } 
                if(isset($this->_display_opt["cache_update"]) && $this->_display_opt["cache_update"] != "hidden" && ( $_update = $this->get_cache_update_time()) ) {
                $this->_output .= "cache update: ".$_update."<br />\n";
                }
                $this->_output .= "<a href=\"".$this->_citem["link"]."\" ";
                if(isset($this->_link_target)) { $this->_output .= "target=\"".$this->_link_target."\" "; }
                $this->_output .= ">".$this->_cut_string($this->_citem["link"])."</a>";
                $this->_output .= "</td></tr>\n";
                $this->_output .= "</table></td></tr>";
                }
                    $this->_array_channel = array(  "title"=>$this->_citem["title"], 
                                                    "link"=>$this->_citem["link"],
                                                    "description"=>$this->_citem["description"],
                                                    "lastBuildDate"=>$this->_citem["lastBuildDate"]);
    }
    
    /**
    * XML Parser End Element Handler
    *
    * @access    private
    * @author    Stefan Saasen <s@fase4.com>
    * @param     mixed  $parser a reference to the XML parser calling the handler. 
    * @param     string $name contains the name of the element for which this handler is called.
    * @see       _clear_Items(), _type, _parse_mode, _depth, _tags, _cdepth, _ctags, _item, _output, _display_opt
    */
    function _endElement($parser, $name) { 
        array_pop($this->_tags); 
        $this->_depth[$parser]--;
        array_pop($this->_ctags); 
        $this->_cdepth[$parser]--;
        switch ($name) { 
            case "item":
                if(empty($this->_max_count) OR $this->_item_count < $this->_max_count) {
                    if($this->_item["title"] != $this->_item["description"] 
                                            AND $this->_item["description"]) {
                        $this->_output .= "<tr><td class=\"fase4_rdf\">".strip_tags($this->_item["description"], "<a>, <img>, <br>")."</td></tr>\n";
                        $this->_output .= "<tr><td class=\"fase4_rdf\"><a href=\"".$this->_item["link"]."\" ";
                        if(isset($this->_link_target)) { $this->_output .= "target=\"".$this->_link_target."\" "; }
                        $this->_output .= ">".strip_tags($this->_item["title"], "<a>, <img>")."</a></td></tr>\n";
                        // we just display the <hr> if there is a description
                        $this->_output .= "<tr><td><hr noshade=\"noshade\" size=\"1\" /></td></tr>\n";
                    } else {
                        $this->_output .= "<tr><td class=\"fase4_rdf\">\n";
                        $this->_output .= "<a href=\"".$this->_item["link"]."\" ";
                        if(isset($this->_link_target)) { $this->_output .= "target=\"".$this->_link_target."\" "; }
                        $this->_output .= ">".$this->_item["title"]."</a></td></tr>\n";
                    }
                        $this->_array_item[] = array(   "title"=>$this->_item["title"], 
                                                        "link"=>$this->_item["link"],
                                                        "description"=>$this->_item["description"]);
                        ++$this->_item_count;
                }
                    $this->_clear_Items(); 
            break; 
            case "image": 
                if(isset($this->_display_opt["image"]) && ($this->_display_opt["image"] != "hidden") && $this->_item["url"]) {            
                    $this->_output .= "<tr><td class=\"fase4_rdf\">\n";
                    $this->_output .= "<a href=\"".$this->_item["link"]."\" ";
                    if(isset($this->_link_target)) { $this->_output .= "target=\"".$this->_link_target."\" "; }
                    $this->_output .= "><img src=\"".$this->_item["url"]."\"";
                if(isset($this->_item["width"]) && isset($this->_item["height"])) {
                    $this->_output .= " width=\"".$this->_item["width"]."\" height=\"".$this->_item["height"]."\"";
                }
                $this->_output .= " alt=\"".$this->_item["title"]."\" border=\"0\" /></a></td></tr>\n"; 

                    $this->_array_image[] = array(  "url"=>$this->_item["url"], 
                                                    "link"=>$this->_item["link"],
                                                    "width"=>$this->_item["width"],
                                                    "height"=>$this->_item["height"]);
                    $this->_clear_Items(); 
                } elseif( isset($this->_display_opt["image"] ) && ($this->_display_opt["image"] == "hidden") ) {
                    $this->_clear_Items();
                }

            break; 
            case "channel": 
                if(isset($this->_display_opt["channel"]) AND $this->_display_opt["channel"] != "hidden" AND $this->_item["title"] != "") {   
                    $this->_output .= "<tr><td>\n";
                    $this->_output .= '<table border="0" width="100%" class="fase4_rdf_meta" cellspacing="5" cellpadding="2">'."\n";
                    $this->_output .= "<tr><td class=\"fase4_rdf\"><div class=\"fase4_rdf_title\">".htmlspecialchars($this->_item["title"])."</div></td></tr>\n"; 
                    $this->_output .= "<tr><td class=\"fase4_rdf\">".strip_tags($this->_item["description"], "<a>, <img>")."</td></tr>\n";
                    $this->_output .= "<tr><td>&nbsp;</td></tr>\n";
                    $this->_output .= "<tr><td class=\"fase4_rdf\">\n";
                if($this->_display_opt["build"] != "hidden") {
                    if($this->_item["lastBuildDate"]){$this->_output .= "build: ". $this->_item["lastBuildDate"]."<br />";}
                }
                if($this->_display_opt["cache_update"] != "hidden" && ( $_update = $this->get_cache_update_time()) ) {
                    $this->_output .= "cache update: ".$_update."<br />\n";
                }
                $this->_output .= "<a href=\"".$this->_item["link"]."\" ";
                if(isset($this->_link_target)) { $this->_output .= "target=\"".$this->_link_target."\" "; }
                $this->_output .= ">".$this->_cut_string($this->_item["link"])."</a>\n";
                $this->_output .= "</td></tr>\n";
                $this->_output .= "</table></td></tr>\n";
                }
                    $this->_array_channel = array(  "title"=>$this->_item["title"], 
                                                    "link"=>$this->_item["link"],
                                                    "description"=>$this->_item["description"],
                                                    "lastBuildDate"=>$this->_item["lastBuildDate"]);
                    $this->_clear_Items();
                    $this->_clear_cItems();
            break; 
            case "textinput":
                if(isset($this->_display_opt["textinput"]) && ($this->_display_opt["textinput"] != "hidden") && $this->_item["name"] && $this->_item["link"]) {
                    $this->_output .= "<tr><td class=\"fase4_rdf\">\n";
                    $this->_output .= "<form action=\"".$this->_item["link"]."\" ";
                    if(isset($this->_link_target)) { $this->_output .= "target=\"".$this->_link_target."\" "; }
                    $this->_output .= "method=\"get\">\n";
                    $this->_output .= "<div class=\"fase4_rdf_title\">".$this->_item["title"]."</div>";
                    $this->_output .= strip_tags($this->_item["description"], "<a>, <img>")."<br><br>\n";
                    $this->_output .= "<input class=\"fase4_rdf_input\" type=\"text\" name=\"".$this->_item["name"]."\">&nbsp;\n";
                    $this->_output .= "<input class=\"fase4_rdf_input\" type=\"submit\" value=\"go\">";
                    $this->_output .= "</form>\n";
                    $this->_output .= "</td></tr>\n";
                    $this->_array_textinput = array(    "title"=>$this->_item["title"],
                                                        "name"=>$this->_item["name"],
                                                        "link"=>$this->_item["link"],
                                                        "description"=>$this->_item["description"]);
                    $this->_clear_Items();
                } elseif( isset($this->_display_opt["textinput"]) && ($this->_display_opt["textinput"] == "hidden") ) {
                    $this->_clear_Items();
                }

                break;
        } 
    } 

    /**
    * This Method returns the data from the <channel /> paragraph.
    *
    * @access    public
    * @author    Stefan Saasen <s@fase4.com>
    * @return    array
    * @see       _array_channel
    */
    function get_array_channel( )
    {
        return $this->_array_channel;
    }

    /**
    * This Method returns the data from each <item /> paragraph.
    *
    * @access    public
    * @author    Stefan Saasen <s@fase4.com>
    * @return    array
    * @see        _array_item
    */
    function get_array_item( )
    {
        return $this->_array_item;
    }

    /**
    * This Method returns the data from <textinput />.
    *
    * @access    public
    * @author    Stefan Saasen <s@fase4.com>
    * @return    array
    * @see       _array_textinput
    */
    function get_array_textinput( )
    {
        return $this->_array_textinput;
    }

    /**
    * This Method returns the data from <image />.
    *
    * @access    public
    * @author    Stefan Saasen <s@fase4.com>
    * @return    array
    * @see       _array_image
    */
    function get_array_image( )
    {
        return $this->_array_image;
    }

    /**
    * XML Parser Data Handler
    *
    * @access    private
    * @author    Stefan Saasen <s@fase4.com>
    * @param     mixed  $parser a reference to the XML parser calling the handler. 
    * @param     string $text contains the character data as a string. 
    * @see       _parse_mode, _item, _tags, _depth, _citem, _ctags, _cdepth
    */
    function _parseData($parser, $text) 
    { 
        // $text =  utf8_decode($text);
        $clean = preg_replace("/\s/", "", $text); 
        if ($clean) { 
            $text = preg_replace("/^\s+/", "", $text); 
                if($this->_parse_mode == "all") {
                        if ( isset($this->_item[$this->_tags[$this->_depth[$parser]]]) && 
                            $this->_item[$this->_tags[$this->_depth[$parser]]] ) { 
                           $this->_item[$this->_tags[$this->_depth[$parser]]] .= $text; 
                        } else { 
                           $this->_item[$this->_tags[$this->_depth[$parser]]] = $text;  
                        }
                } elseif (isset($this->_parse_mode) && $this->_parse_mode == "channel") {
                        if ( isset($this->_citem[$this->_ctags[$this->_cdepth[$parser]]]) ) { 
                           $this->_citem[$this->_ctags[$this->_cdepth[$parser]]] .= $text; 
                        } else { 
                           $this->_citem[$this->_ctags[$this->_cdepth[$parser]]] = $text;  
                        }
                }
        } 
    } 

    /**
    * This Method allows you to choose if specific Parameters are displayed or not. These are: 
    * image, channel, textinput, build and cache_update. If set to "hidden" those elements won't be displayed.
    *
    * @access    public
    * @author    Stefan Saasen <s@fase4.com>
    * @param     array  $options
    * @see       _display_opt
    */
    function set_Options( $options = "" )
    {
        if(is_array( $options )) {
            $this->_display_opt = $options;
            return true;
        } else {
            unset($this->_display_opt);
            return false;
        }
    }

    /**
    * This Method allows you to define the width of the table that holds the representation of the rdf/rss file.
    *
    * @access    public
    * @author    Stefan Saasen <s@fase4.com>
    * @param     int  $width  attribute width in tag <table>
    * @see       _table_width
    */
    function set_table_width( $width = 400 )
    {
        $this->_table_width = $width;
        return true;
    }

    /**
    * This Method returns an assocative Array with available Options.
    *
    * The Keys are the Name of the Options to be set. 
    * The Values are  short Description of available Options.
    *
    * @access    public
    * @author    Stefan Saasen <s@fase4.com>
    * @return    array  $options
    * @see       _display_opt
    */
    function get_Options()
    {
        $options = array(   "image"=>"If 'image' is set to \"hidden\" no image provided by the RDF Publisher will be displayed.",
                            "channel"=>"If 'channel' is set to \"hidden\" the Channel Meta Data (i.e the Title and the short description regarding the RDF Publisher will not be displayed",
                            "textinput"=>"If set to \"hidden\" no Input Form will be displayed",
                            "build"=>"If set to \"hidden\" the Build Date (if provided) of the RDF File will not be displayed",
                            "cache_update"=>"If set to \"hidden\" the Update Date/Time of the cached Rdf File will not be displayed");
        return $options;
    }

    /**
    * This Method returns the Content of the RDF File in one string. The String actually holds the whole XML Document.
    *
    * @access    public
    * @author    Stefan Saasen <s@fase4.com>
    * @param     string $rdf    RDF File (Location)
    * @return    string XML Presentation of parsed RDF File
    * @see       _cached_file, _remote_file, _cache_dir, _refresh, _update_cache()
    */
    function cache()
    {
        // checks if the cache directory already exists
        // if not, the cache directory will be created
        if(!$this->_cache_dir_ok) {
            $this->_create_cache_dir();
        }
        if($this->_use_dynamic_display == true) {
            $this->_cached_file = md5("dynamic".$this->_remote_file);
            $this->_cache_type = "normal";
        } else {
            $this->_cached_file = md5($this->_remote_file);
            $this->_cache_type = "fast";            
        }

        $_cache_f = $this->_cache_dir.$this->_cached_file;

        if ( (!file_exists($_cache_f)) || (filemtime($_cache_f) < $this->_refresh) || (filesize($_cache_f) == 0)) {
        // We have to parse the remote file
        $this->_use_cached_file = false;
            // --> we want to provide proper Information for Use in 
            // get_cache_update_time()
            clearstatcache();
            if($this->_use_dynamic_display == true) {
                $_rdf = @implode(" ", $this->_rdf_data()); // -> proxy
                if(!$_rdf) {
                    $this->_throw_exception( $this->_remote_file." is not available" );
                }
                $this->_parse_xRDF( $_rdf );
                $this->_update_cache( $_rdf );
                $data = $this->_output;
            } else {
                $_rdf = @implode(" ", $this->_rdf_data()); // -> proxy
                if(!$_rdf) {
                    $this->_throw_exception( $this->_remote_file." is not available" );
                }
                $this->_parse_xRDF( $_rdf );
                $this->_update_cache( $this->_output );
                $data = $this->_output;
            }
        } else {
        // we can use the cached file
        $this->_use_cached_file = true;        
            if($this->_use_dynamic_display == true) {
                $this->_parse_xRDF( implode(" ", file($_cache_f)) );
                $data = $this->_output;
            } else {        
                $data = @implode(" ", file($_cache_f));
            }
        }
        return trim($data);
    }   // END cache()

    /**
    * This Methods creates the Cache Directory if the specified Directory does not exist.
    *
    * @access    private
    * @author    Stefan Saasen <s@fase4.com>
    * @param     string $dir Path to Directory.
    * @return    boolean
    * @see       _cache_dir, _cache_dir_ok
    */
    function _create_cache_dir()
    {
        $path = "";
        if(!@is_dir($this->_cache_dir)) {
            $arr = explode("/", $this->_cache_dir);
            $c = count($arr);
            if($arr[0]=="") {
                $path = "/";
            }
            for($i = 0;$i<$c;$i++)
            {
                if($arr[$i]!="") {
                    $path .= $arr[$i]."/";
                    if(!@is_dir($path)) {
                       if(!@mkdir($path, 0777)) { 
                            $this->_throw_exception("failed to create directory:<b>".$this->_cache_dir."</b>.<br /><br />Exception on Line: ".__LINE__);
                        return false;
                        }
                    }
                }
            }
            $this->_cache_dir_ok = true;
            return true;
        } else {
            $this->_cache_dir_ok = true;
            return true;    
        }
    }   // END _create_cache_dir()

    /**
    * This Method updates the cached RDF Files and synchronises them with their remote Counterparts.
    *
    * @access    private
    * @author    Stefan Saasen <s@fase4.com>
    * @param     string $rdf    RDF File (Location)
    * @see       _cache_dir, _cached_file, _throw_exception()
    */
    function _update_cache( $content = "" )
    {
             $_local = @fopen( $this->_cache_dir.$this->_cached_file, "w" );
             if(!$_local) { 
                $this->_throw_exception( "Cannot open ".$this->_cache_dir.$this->_cached_file."<br /><br />Exception at Line: ".__LINE__ ); 
                return false;
             }
             if(!fwrite( $_local, $content)) { 
                $this->_throw_exception( "Cannot write to: ".$this->_cached_file."<br /><br />Exception at Line: ".__LINE__ ); 
                return false;
             }             
             fclose( $_local ); 
             return true;
    }   // END _update_cache()

    /**
    * This Method returns the Date/Time of last Cache Update of the actually parsed RDF File.
    *
    * @access    public
    * @author    Stefan Saasen <s@fase4.com>
    * @return    string Date/Time of last Update
    * @see        _cache_dir, _cached_file
    */
    function get_cache_update_time()
    {
            return (file_exists($this->_cache_dir.$this->_cached_file))?date("d.m.Y H:i:s", filemtime($this->_cache_dir.$this->_cached_file)):"Cachemiss";
    }   // END get_cache_update_time()

    /**
    * This Method returns the Type of Cache that was used ('normal' or 'fast')
    *
    * @access    public
    * @author    Stefan Saasen <s@fase4.com>
    * @param     string $rdf    RDF File (Location)
    * @return    string Displays RDF Content ( using _display() )
    * @see       _remote_file, cache()
    */
    function get_CacheType()
    {
        return $this->_cache_type;
    }

    /**
    * Returns true if cached file was used, otherwise false
    *
    * @access    public
    * @author    Stefan Saasen <s@fase4.com>
    * @return    array  $options
    * @see        _use_cached_file
    */
    function is_cachedFile()
    {
        return $this->_use_cached_file;
    }

    /**
    * This Method deletes all the cached Files.
    *
    * Please keep in mind to use this method just as a 'manual garbage collection'
    * You should cache the rss/rdf files locally to avoid unnecessary traffic. 
    * (Both for your visitors and the Publisher)
    *
    * @access    public
    * @author    Stefan Saasen <s@fase4.com>
    * @see       _cache_dir
    */
    function clear_cache()
    {
        $dir = dir($this->_cache_dir);
        while($file=$dir->read()) {
            if($file!="." && $file!="..")  {
                if(!@unlink($dir->path.$file)) {
                    $this->_throw_exception( 
                    "Unable to unlink ".$dir->path.$file
                    ."<br /><br />Exception at Line: ".__LINE__ ); 
                    return false;
                }
            }
        }
        $dir->close();
        return true;
    }   // END clear_cache()	

    /**
    * Cuts the String $string after $str_len and adds "... "
    *
    * @access   private
    * @param    string  $string String to be shortened
    * @param    int     $str_len length of the returned String (overall length including "... ")
    * @return   string  Cut String
    */
    function _cut_string( $string, $str_len = "30" )
    {
        if(strlen(trim($string))>$str_len) {
        $string = substr( trim($string) , 0, $str_len - 4);
        $string .= " ...";
        }
        return $string;
    }   // END _cut_string()

    /**
    * this Method implements simple Garbage Collection
    *
    * @access    private
    * @author    Stefan Saasen <s@fase4.com>
    * @see       _cache_dir, gc_probability, gc_maxlifetime
    */
    function _garbage_collection()
    {
        srand((double) microtime() * 1000000);
        if (rand(1, 100) <= $this->gc_probability) {
            $dir = dir($this->_cache_dir);
            while($file=$dir->read()) {
                if($file!="." AND $file!=".." AND filemtime($dir->path.$file) <= time() - $this->_refresh )  {
                @unlink($dir->path.$file);
                }
        }
        $dir->close();
        }   // END if
    }

    /* ==== Proxy/Auth methods ==== */

   /**
    * this method sets a proxy server
    *
    * @access    public
    * @param     string $phost Proxy Host
    * @param     string $pport Prox Port
    * @author    Marco Kraus <marco.kraus@siemens.com>
    */
    function set_proxy($phost, $pport)
    {
     $this->_use_proxy = true;

     if ($phost != "")
        $this->_phost = $phost;

     if ($pport != "")
        $this->_pport = $pport;
    }

    /**
    * this method sets a proxy server authentification
    *
    * @access    public
    * @param     string $pname Username
    * @param     string $ppaswd Password
    * @author    Marco Kraus <marco.kraus@siemens.com>
    */
    function set_proxy_auth( $pname, $ppasswd )
    {
     $this->_use_proxy_auth = true;

     if ($pname != "")
        $this->_pname = $pname;

     if ($ppasswd != "")
        $this->_ppasswd = $ppasswd;
    }


   /**
    * gets _remote_file into an array
    *
    * needed, cause if you use a proxy, you have to open 
    * a raw-tcp-socket to get the data
    *
    * @access    private
    * @author    Marco Kraus <Marco.Kraus@siemens.com>
    * @return array
    * @see _use_proxy, cache()
    */
    function _rdf_data()
    {
      if ( $this->_use_proxy == true )
      {
       // we need a raw socket here to connect to proxy
       $fp = fsockopen($this->_phost,$this->_pport);

       if (!$fp) {
           $this->_throw_exception( $this->_remote_file." is not available with proxy" );
       } else {
        if ( $this->_use_proxy_auth == true ) {
           fputs($fp, "GET ".$this->_remote_file." HTTP/1.0\r\n\r\n");
           } else {
           fputs($fp, "GET ".$this->_remote_file." HTTP/1.0\r\nProxy-Authorization: Basic ".base64_encode("$this->_pname:$this->_ppasswd") ."\r\n\r\n");
           }
        }


       for ( $i = 0; !feof ($fp) ; $i++)
       {
          $usable_data[$i] = "";
          $usable_data[$i] = fgets($fp,4096);

        // PARSE HEADER ---- first line has to be <?xml, second rdf or rss, and third is blank

        // strstr did not fit (ask Rasmus why), so we compare each character   
            if ( ($usable_data[$i][0] == "<" ) &&
               ($usable_data[$i][1] == "?" ) &&
               ($usable_data[$i][2] == "x" ) &&
               ($usable_data[$i][3] == "m" ) &&
               ($usable_data[$i][4] == "l" ) ) {
                    $usable_data[0] = $usable_data[$i]; // save current field
                      $i = 1; // just reset array to start
              }

        // there seems to be proxystuff after the <?xml....we delete this
            if ( (
               ($usable_data[$i][0] == "<" ) &&
               ($usable_data[$i][1] == "r" ) &&
               ($usable_data[$i][2] == "d" ) &&
               ($usable_data[$i][3] == "f" ) &&
               ($usable_data[$i][4] == ":" ) 
               )
               ||
               (
               ($usable_data[$i][0] == "<" ) &&
               ($usable_data[$i][1] == "r" ) &&
               ($usable_data[$i][2] == "s" ) &&
               ($usable_data[$i][3] == "s" )
               ) 
            ) {

                $usable_data[1] = $usable_data[$i]; // save current field
                $usable_data[2] = "\n";
                $i = 2; // just reset array to start
          }
       }

       fclose($fp);
       return $usable_data;
     } else {
        return (file($this->_remote_file));
     }
   }    // END _rdf_data()
}   // END class
?>