<?php
/* ----------------------------------------------------------------------
   $Id: class_google_search.php,v 1.1 2007/06/07 16:06:31 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: web_search.php,v 1.1 2004/07/02 22:27:20 chaicka  
   ----------------------------------------------------------------------
   WebSearch

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2004 osCommerce
   ---------------------------------------------------------------------- 
   Google API Access for PHP
   - A PHP class library to access the Google API via SOAP using WSDL.
   ---------------------------------------------------------------------- 

   This source file is released under LGPL license, available through 
   the world wide web at, http://www.gnu.org/copyleft/lesser.html. This 
   library is distributed WITHOUT ANY WARRANTY. Please see the LGPL for 
   more details.

   This library requires the "NuSOAP - Web Services Toolkit for PHP", 
   available for free at, http://dietrich.ganx4.com/nusoap under the 
   LGPL license.You should have the file, nusoap.php somewhere on the 
   include path, or include it manually in the GoogleSearch.php source 
   file. 

   PLEASE READ THE README.TXT FILE, for more details on how to use this 
   library.

   COMPATIBILITY NOTE: At the moment, this library is experimental and 
   designed to work only with PHP4 and above. 
   ----------------------------------------------------------------------  */


  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  /**
   * GoogleSearch - provides access to Google search API via SOAP using WSDL.
   *
   * @package		WebSearch
   * @version		$Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/07 16:06:31 $
   */

  /**
   * GoogleSearch - provides access to Google search API via SOAP using WSDL.
   * @author Vijay Immanuel <immanuel_vijay@vsnl.net>
   * @access public
   */
   class GoogleSearch  {

	//declare variables

	/**
	* @var wsdlURL URL of the Google WSDL file.
	* @var nameSpace Namespace URN
	* @access private
	*/
	var $wsdlURL = "http://api.google.com/GoogleSearch.wsdl";
	var $nameSpace = "urn:GoogleSearch";

	/**
	* @var queryFunction Actual name of function to be called.
	* @var queryParams Parameters to be passed to the function.
	* @access private
	*/
	var $queryFunction = '';
	var $queryParams = array();
	
	/**
	* Google doSearch parameters.
	*
	* @var key Google licence key.
	* @var q Query phrase.
	* @var start Start index of result.
	* @var maxResults Maximum number results to be returned (not to exceed 10).
	* @var filter Toggles result filtering feature.
	* @var restrict Word/phrase to restrict searches to.
	* @var safeSearch Toogles Google "SafeSearch" feature.
	* @var lr Language restrictions to apply.
	* @var ie Input encoding (no longer supported - "UTF-8" is always used).
	* @var oe Output encoding (no longer supported - "UTF-8" is always used).
	* @access private
	*/
	var $key = '';
	var $q = '';
	var $start = 0;
	var $maxResults = 10;
	var $filter = false;
	var $restrict = '';
	var $safeSearch = false;
	var $lr = '';
	var $ie = '';	//deprecated
	var $oe = '';	//deprecated

	/**
	* Google doGetCachedPage parameter.
	*
	* @var url URL of the cached page to be returned.
	* @access private
	*/
	var $url = '';

	/**
	* Google doSpellingSuggestion parameter.
	*
	* @var phrase Word/phrase to be checked.
	* @access private
	*/
	var $phrase = '';

	/**
	* Variables that are returned.
	* @var searchResult Result returned - a GoogleSearchResult object.
	* @var error Error string in case of error.
	* @access private
	*/
	var $searchResult;
	var $error;
	
	/**
	* Constructor
	* @access public
	*/
	function GoogleSearch()
	{
		
	}

	/**
	* Retrieves a cached web page through Google.
	* @param url string [optional]
	* @return mixed
	* @access public
	*/
	function doGetCachedPage($url = '')
	{
		if($url == '')
		{
			$url = $this->url;
		}
		$this->url = $url;

		$this->queryFunction = "doGetCachedPage";
		$this->queryParams = array(
						'key' => $this->key, 
						'url' => $this->url 
					);

		$result = $this->call($this->queryFunction, $this->queryParams);

		if(!isset($result) || !$result)
		{
			return false;
		}

		return base64_decode($result);
	}

	/**
	* Invokes Google search.
	* @return mixed
	* @access public
	*/
	function doSearch()
	{
		$this->queryFunction = "doGoogleSearch";
		$this->queryParams = array(
						'key' => $this->key, 
						'q' => $this->q, 
						'start' => $this->start, 
						'maxResults' => $this->maxResults, 
						'filter' => $this->filter, 
						'restrict' => $this->restrict, 
						'safeSearch' => $this->safeSearch, 
						'lr' => $this->lr, 
						'ie' => $this->ie, 	//deprecated
						'oe' => $this->oe 	//deprecated
					);

		$result = $this->call($this->queryFunction, $this->queryParams);

		if(!isset($result) || !$result || !is_array($result))
		{
			return false;
		}

		$this->searchResult = new GoogleSearchResult($result);

		return $this->searchResult;
	}

	/**
	* Returns a spelling suggestion for a word/phrase from Google.
	* @param phrase string [optional]
	* @return string
	* @access public
	*/
	function doSpellingSuggestion($phrase = '')
	{
		if($phrase == '')
		{
			$phrase = $this->phrase;
		}
		$this->phrase = $phrase;

		$this->queryFunction = "doSpellingSuggestion";
		$this->queryParams = array(
						'key' => $this->key, 
						'phrase' => $this->phrase
					);

		$result = $this->call($this->queryFunction, $this->queryParams);

		if(!isset($result) || !$result)
		{
			return false;
		}

		return $result;
	}

	/**
	* Set URL for cached page Google search.
	* @param url string
	* @return mixed
	* @access public
	*/
	function setCachedPageURL($url)
	{
		$this->url = $url;
	}

	/**
	* Toggle "related queries" filter.
	* @param on boolean
	* @return none
	* @access public
	*/
	function setFilter($on)
	{
		$this->filter = $on;
	}

	/**
	* Set user authentication key (Provided by Google).
	* @param key string
	* @return none
	* @access public
	*/
	function setKey($key)
	{
		$this->key = $key;
	}

	/**
	* Set language restrictions for search.
	* @param lr string
	* @return none
	* @access public
	*/
	function setLanguageRestricts($lr)
	{
		$this->lr = $lr;
	}

	/**
	* Set maximum number of results to be returned.
	* @param maxResults int
	* @return none
	* @access public
	*/
	function setMaxResults($maxResults)
	{
		$this->maxResults = $maxResults;
	}

	/**
	* Set host to use as HTTP proxy.
	* @param host string
	* @return none
	* @access public
	*/
	function setProxyHost($host)
	{
		//yet to implement
	}

	/**
	* Set password for HTTP proxy.
	* @param password string
	* @return none
	* @access public
	*/
	function setProxyPassword($password)
	{
		//yet to implement
	}

	/**
	* Set port to use as HTTP proxy.
	* @param port int
	* @return none
	* @access public
	*/
	function setProxyPort($port)
	{
		//yet to implement
	}

	/**
	* Set username for HTTP proxy.
	* @param username string
	* @return none
	* @access public
	*/
	function setProxyUserName($username)
	{
		//yet to implement
	}

	/**
	* Set query string for Google search.
	* @param q string
	* @return none
	* @access public
	*/
	function setQueryString($q)
	{
		$this->q = $q;
	}

	/**
	* Set restrict for search.
	* @param restrict string
	* @return none
	* @access public
	*/
	function setRestrict($restrict)
	{
		$this->restrict = $restrict;
	}

	/**
	* Toggle Google SafeSearch feature.
	* @param safeSearch boolean
	* @return none
	* @access public
	*/
	function setSafeSearch($safeSearch)
	{
		$this->safeSearch = $safeSearch;
	}

	/**
	* Set URL for Google SOAP Search service.
	* @param wsdlURL string
	* @return none
	* @access public
	*/
	function setWSDLURL($wsdlURL)
	{
		$this->wsdlURL = $wsdlURL;
	}

	/**
	* Set phrase/word for Google spelling suggestion.
	* @param phrase string
	* @return none
	* @access public
	*/
	function setSpellingSuggestionPhrase($phrase)
	{
		$this->phrase = $phrase;
	}

	/**
	* Set index (zero-based) of first result to be returned.
	* @param start int
	* @return none
	* @access public
	*/
	function setStartResult($start)
	{
		$this->start = $start;
	}

	/**
	* Performs actual call to Google API.
	* @param query string params array
	* @return array boolean
	* @access private
	*/
	function call($query, $params)
	{
		$soapclient = new soapclient($this->wsdlURL, 'wsdl', $this->nameSpace);

		if($this->error = $soapclient->getError())
		{
			//comment out echo, if you want to handle errors yourself using getError().
			//echo "Error instantiating SOAP client: " . $this->error;
			return false;
		}


		$result = $soapclient->call($query, $params);

		if($this->error = $soapclient->getError())
		{
			//comment out echo, if you want to handle errors yourself using getError().
			//echo "Error executing query: " . $this->error;
			return false;
		}

		return $result;
	}

	/**
	* Returns error messages (if any).
	* @return string
	* @access public
	*/
	function getError()
	{
		if(!$this->error)
		{
			return false;
		}

		return $this->error;
	}

}


/**
* GoogleSearchResult - encapsulates complete results returned by doGoogleSearch.
* @author Vijay Immanuel <immanuel_vijay@vsnl.net>
* @access public
*/
class GoogleSearchResult
{

	//declare variables

	/**
	* @var result Result array returned by doSearch.
	* @access private
	*/
	var $result = array();

	/**
	* Individual elements of result array returned by doSearch.
	* @var documentFiltering Document filtering on/off.
	* @var searchComments 
	* @var estimatedTotalResultsCount
	* @var estimateIsExact
	* @var resultElements Array of actual result elements.
	* @var searchQuery
	* @var startIndex
	* @var endIndex
	* @var searchTips
	* @var directoryCategories ODP directory category array.
	* @var searchTime Time taken for search (in seconds).
	* @access private
	*/
	var $documentFiltering;
	var $searchComments;
	var $estimatedTotalResultsCount;
	var $estimateIsExact;
	var $resultElements;
	var $searchQuery;
	var $startIndex;
	var $endIndex;
	var $searchTips;
	var $directoryCategories;
	var $searchTime;

	/**
	* Returned variables.
	* @var resultElement Array of GoogleSearchResultElement objects.
	* @var dirCategory ODP directory category - a GoogleSearchDirectoryCategory object.
	* @var private
	*/
	var $resultElement;
	var $dirCategory;


	/**
	* Constructor
	* @param result array
	* @access public
	*/
	function GoogleSearchResult($result)
	{
		$this->result = $result;
	}

	/**
	* Returns array of directory catagories for search result.
	* @return array
	* @access public
	*/
	function getDirectoryCategories()
	{
		$result = $this->result['directoryCategories'];

		if(!isset($result) || !is_array($result))
		{
			return false;
		}

		foreach($result as $index => $element)
		{
			$this->dirCategory[$index] = new GoogleSearchDirectoryCategory($element);
		}

		return $this->dirCategory;
	}

	/**
	* Returns true only if filtering was successfully performed on search results.
	* @return boolean
	* @access public
	*/
	function getDocumentFiltering()
	{
		return $this->result['documentFiltering'];
	}

	/**
	* Returns index (1-based) of last search result in result elements.
	* @return int
	* @access public
	*/
	function getEndIndex()
	{
		return $this->result['endIndex'];
	}

	/**
	* Returns estimated total number of results returned for search query.
	* @return int
	* @access public
	*/
	function getEstimatedTotalResultsCount()
	{
		return $this->result['estimatedTotalResultsCount'];
	}

	/**
	* Returns true only if estimated number of results is exact.
	* @return boolean
	* @access public
	*/
	function getEstimateIsExact()
	{
		return $this->result['estimateIsExact'];
	}

	/**
	* Returns an array of result elements.
	* @return array
	* @access public
	*/
	function getResultElements()
	{
		$result = $this->result['resultElements'];

		if(!isset($result) || !is_array($result))
		{
			return false;
		}

		foreach($result as $index => $element)
		{
			$this->resultElement[$index] = new GoogleSearchResultElement($element);
		}

		return $this->resultElement;
	}

	/**
	* Returns string intended for display to user.
	* @return string
	* @access public
	*/
	function getSearchComments()
	{
		return $this->result['searchComments'];
	}

	/**
	* Returns query string actually used. 
	* @return string
	* @access public
	*/
	function getSearchQuery()
	{
		return $this->result['searchQuery'];
	}

	/**
	* Returns total server time to process the query (in seconds).
	* @return double
	* @access public
	*/
	function getSearchTime()
	{
		return $this->result['searchTime'];
	}

	/**
	* Returns instructive suggestions for using Google.
	* @return string
	* @access public
	*/
	function getSearchTips()
	{
		return $this->result['searchTips'];
	}

	/**
	* Returns index (1-based) of first search result in result elements.
	* @return int
	* @access public
	*/
	function getStartIndex()
	{
		return $this->result['startIndex'];
	}

	/**
	* Set Directory Category array.
	* @param cats mixed
	* @access private
	*/
	function setDirectoryCategories($cats)
	{
		$this->result['directoryCategories'] = $cats;
	}

	/**
	* Set document filtering.
	* @param fi boolean
	* @access private
	*/
	function setDocumentFiltering($fi)
	{
		$this->result['documentFiltering'] = $fi;
	}

	/**
	* Set end index of result in result elements.
	* @param en int
	* @access private
	*/
	function setEndIndex($en)
	{
		$this->result['endIndex'] = $en;
	}

	/**
	* Set estimated total results.
	* @param m int
	* @access private
	*/
	function setEstimatedTotalResultsCount($m)
	{
		$this->result['estimatedTotalResultsCount'] = $m;
	}

	/**
	* Toggle if estimate is exact.
	* @param xt boolean
	* @access private
	*/
	function setEstimateIsExact($xt)
	{
		$this->result['estimateIsExact'] = $xt;
	}

	/**
	* Set result elements.
	* @param rs mixed
	* @access private
	*/
	function setResultElements($rs)
	{
		$this->result['resultElements'] = $rs;
	}

	/**
	* Set search comments.
	* @param ct string
	* @access private
	*/
	function setSearchComments($ct)
	{
		$this->result['searchComments'] = $ct;
	}

	/**
	* Set search query.
	* @param q string
	* @access private
	*/
	function setSearchQuery($q)
	{
		$this->result['searchQuery'] = $q;
	}

	/**
	* Set search time.
	* @param tm double
	* @access private
	*/
	function setSearchTime($tm)
	{
		$this->result['searchTime'] = $tm;
	}

	/**
	* Set search tips.
	* @param tt string
	* @access private
	*/
	function setSearchTips($tt)
	{
		$this->result['searchTips'] = $tt;
	}

	/**
	* Set start index of result elements.
	* @param sn int
	* @access private
	*/
	function setStartIndex($sn)
	{
		$this->result['startIndex'] = $sn;
	}

}


/**
* GoogleSearchResultElement - encapsulates search result component of a search result.
* @author Vijay Immanuel <immanuel_vijay@vsnl.net>
* @access public
*/
class GoogleSearchResultElement
{

	//declare variables

	/**
	* @var resultElement Array of result elements.
	* @var dirCategory A GoogleSearchDirectoryCategory object.
	* @access private
	*/
	var $resultElement = array();
	var $dirCategory;


	/**
	* Constructor.
	* @param re array
	* @access public
	*/
	function GoogleSearchResultElement($re)
	{
		$this->resultElement = $re;
	}

	/**
	* Returns size (in KB) of cached version.
	* @return string
	* @access public
	*/
	function getCachedSize()
	{
		return $this->resultElement['cachedSize'];
	}

	/**
	* Returns directory category of result element.
	* @return object
	* @access public
	*/
	function getDirectoryCategory()
	{
		$result = $this->resultElement['directoryCategory'];

		if(!isset($result) || !is_array($result))
		{
			return false;
		}

		$this->dirCategory = new GoogleSearchDirectoryCategory($result);

		return $this->dirCategory;
	}

	/**
	* Returns title appearing in directory, if contained in ODP directory.
	* @return string
	* @access public
	*/
	function getDirectoryTitle()
	{
		return $this->resultElement['directoryTitle'];
	}

	/**
	* Returns host name, if multiple results come from a single host.
	* @return string
	* @access public
	*/
	function getHostName()
	{
		return $this->resultElement['hostName'];
	}

	/**
	* Returns true only if "related:" special query term is supported for this URL.
	* @return boolean
	* @access public
	*/
	function getRelatedInformationPresent()
	{
		return $this->resultElement['relatedInformationPresent'];
	}

	/**
	* Returns a snippet showing the query in context on the URL, where it appears.
	* @return string
	* @access public
	*/
	function getSnippet()
	{
		return $this->resultElement['snippet'];
	}

	/**
	* Returns ODP summary, if contained in ODP directory.
	* @return string
	* @access public
	*/
	function getSummary()
	{
		return $this->resultElement['summary'];
	}

	/**
	* Returns title of search result, formatted as HTML.
	* @return string
	* @access public
	*/
	function getTitle()
	{
		return $this->resultElement['title'];
	}

	/**
	* Returns absolute URL of search result.
	* @return string
	* @access public
	*/
	function getURL()
	{
		return $this->resultElement['URL'];
	}

	/**
	* Set cached size.
	* @param sz string
	* @access private
	*/
	function setCachedSize($sz)
	{
		$this->resultElement['cachedSize'] = $sz;
	}

	/**
	* Set directory category.
	* @param cat array
	* @access private
	*/
	function setDirectoryCategory($cat)
	{
		$this->resultElement['directoryCategory'] = $cat;
	}

	/**
	* Set directory title.
	* @param dt string
	* @access private
	*/
	function setDirectoryTitle($dt)
	{
		$this->resultElement['directoryTitle'] = $dt;
	}

	/**
	* Set host name.
	* @param hn string
	* @access private
	*/
	function setHostName($hn)
	{
		$this->resultElement['hostName'] = $hn;
	}

	/**
	* Set whether related information is present.
	* @param rt boolean
	* @access private
	*/
	function setRelatedInformationPresent($rt)
	{
		$this->resultElement['relatedInformationPresent'] = $rt;
	}

	/**
	* Set snippet.
	* @param s string
	* @access private
	*/
	function setSnippet($s)
	{
		$this->resultElement['snippet'] = $s;
	}

	/**
	* Set summary.
	* @param ds string
	* @access private
	*/
	function setSummary($ds)
	{
		$this->resultElement['summary'] = $ds;
	}

	/**
	* Set title.
	* @param t string
	* @access private
	*/
	function setTitle($t)
	{
		$this->resultElement['title'] = $t;
	}

	/**
	* Set URL.
	* @param u string
	* @access private
	*/
	function setURL($u)
	{
		$this->resultElement['URL'] = $u;
	}

}


/**
* GoogleSearchDirectoryCategory - encapsulates the directory category portion of search result.
* @author Vijay Immanuel <immanuel_vijay@vsnl.net>
* @access public
*/
class GoogleSearchDirectoryCategory
{

	//declare variables

	/**
	* @var dirCategory Array containing directory category info.
	* @access private
	*/
	var $dirCategory = array();

	/**
	* Constructor.
	* @param re array
	* @access public
	*/
	function GoogleSearchDirectoryCategory($re)
	{
		$this->dirCategory = $re;
	}

	/**
	* Returns ODP directory name for current ODP category
	* @return string
	* @access public
	*/
	function getFullViewableName()
	{
		return $this->dirCategory['fullViewableName'];
	}

	/**
	* Returns encoding scheme of directory information.
	* @return string
	* @access public
	*/
	function getSpecialEncoding()
	{
		return $this->dirCategory['specialEncoding'];
	}

	/**
	* Set ODP directory name.
	* @param fvn string
	* @access private
	*/
	function setFullViewableName($fvn)
	{
		$this->dirCategory['fullViewableName'] = $fvn;
	}

	/**
	* Set encoding scheme of directory information.
	* @param se string
	* @access private
	*/
	function setSpecialEncoding($se)
	{
		$this->dirCategory['specialEncoding'] = $se;
	}

}
?>
