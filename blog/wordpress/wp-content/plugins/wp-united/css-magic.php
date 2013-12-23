<?php
/** 
*
* WP-United "CSS Magic" template integrator
*
* @package WP-United
* @version $Id: 0.9.1.5  2012/12/28 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
*
*/

/**
 *	You will want to remove this line in your own projects.
*/
if ( !defined('IN_PHPBB') && !defined('ABSPATH') ) exit;

/**
 * This library attempts to magically fuse templates on the page
 * by modifying CSS specicifity on the fly
 * Inspired by cssParser class. you are welcome to use this class in your own projects, provided this licence is kept intact.
 * This class should be useful for a wide range of template integration projects.
 * @package CSS Magic
 * @author John Wells
 *
 * USAGE NOTES:
 * 
 * 
 * To read a css file:
 * $success = $cssMagic->parseFile('path_to_file');
 * 
 * Or read a css string:
 * $cssMagic->clear(); // you can read multiple strings and files. They are combined together. To start anew, use this.
 * $success = $cssMagic->parseString($cssString);
 * $success will return 1 or true on success, or 0 or false on failure.
 * 
 * Now, make all the CSS we have read in apply only to children of a particular DIV with ID = $id
 * $cssMagic->makeSpecificById($id);
 * 
 * Or, we could use $cssMagic->makeSpecificByClass($class) 
 * Or, both :-) $cssMagic->makeSpecificByIdThenClass($classAndId) 
 * 
 * Now get the modified CSS. The output is fairly nicely compressed too.
 * $fixedCSS = $cssMagic->getCSS();
 * 
 * Alternatively, send the output straight to the browser as a CSS file:
 * echo $cssMagic; 
 * 
 * When you're finished,
 * $cssMagic->clear();
 * 
 * Note: CSS Magic doesn't try to validate the CSS coming in. If the inbound CSS in invalid or garbage, 
 * you'll get garbage coming out -- perhaps even worse than before.
 * 
 * (c) John Wells, 2009-2013
 */
class CSS_Magic {
	private 	
		$css,
		$filename,
		$parsedFromFile,
		$nestedItems,
		$importedItems,
		$totalItems,
		$baseUrl,
		$basePath,
		$processImports;
	
	/**
	 * If you want to use this class as a sngleton, invoke via CSS_Magic::getInstance();
	 */
	public static function getInstance ($processImports = false, $baseURL = false, $basePath = false) {
		static $instance;
		if (!isset($instance)) {
			$instance = new CSS_Magic($processImports, $baseURL, $basePath);
        } 
        return $instance;
    }
	
	/**
	 * Class constructor
	 */
	public function __construct($processImports = false, $baseUrl = false, $basePath = false) {
		$this->clear();
		$this->filename = '';
		$this->parsedFromFile = false;
		$this->nestedItems = array();
		$this->importedItems = array();
		$this->totalItems = 0;
		$this->baseUrl = $baseUrl;
		$this->basePath = $basePath;
		$this->processImports = $processImports;
	}
	/**
	 * initialise or clear out internal representation
	 * @return void
	 */
	public function clear() {
		$this->css = array();
		$this->nestedItems = array();
		$this->importedItems = array();
	}
	
	/**
	 * For parsed strings, it can be useful to set the filename, for substitutions
	 */
	public function set_filename($fileName) {
		$this->filename = $fileName;
	}
	/**
	 * Parses inbound CSS, storing it as an internal representation of keys and code
	 * @param string $str A valid CSS string
	 * @return int the number of CSS keys stored
	 */
	public function parseString($str, $clear = false) {
	
		if ($clear) $this->clear();
	
		$keys = '';
		
		$Instr = $str;
		// Remove comments
		$str = preg_replace("/\/\*(.*)?\*\//Usi", "", $str);
		$str = str_replace("\t", "", $str);
		$str = str_replace("}\\", '[TANTEK]', $str);
		// find nested stylesheets
		preg_match_all('/(\@[^\{]*\{)([^\{^\}]*(\{[^\@^\{^\}]*\}[^\{^\}]*)*?)\}/', $str, $nested);
		
		$nestIndex = sizeof($this->nestedItems);
		if(sizeof($nested[0]) && isset($nested[1]) && is_array($nested[1]) && sizeof($nested[1])) {
			foreach($nested[1] as $nestNum => $nestSel) {
				if(!empty($nestSel) && isset($nested[2]) && is_array($nested[2]) && isset($nested[2][$nestNum])) {
					// handle imported stylesheets separately
					if(stristr($nestSel, '@import') !== false) {
						continue;
					}

					$subSheet = new CSS_Magic($this->processImports, $this->baseUrl, $this->basePath);
					$subSheet->set_filename($this->filename);
					$this->totalItems = $this->totalItems + $subSheet->parseString($nested[2][$nestNum]);
				
					$this->nestedItems[$nestIndex] = array(
						'selector'	=> $nestSel,
						'content'	=> $subSheet
					);
					
					$str = str_replace($nested[0][$nestNum], '[WPU_NESTED] {' . $nestIndex . '}', $str);
					$nestIndex++;
				}
			}
		}
		
		// Other nested stylesheets:
		if($this->processImports) {
			preg_match_all('/\@import\s(url\()?[\'"]?([^\'^"^\)]*)[\'"]?\)?;/', $str, $imported);
			$importIndex = sizeof($this->importedItems);
			if(sizeof($imported[0]) && isset($imported[2]) && is_array($imported[2]) && sizeof($imported[2])) {
				foreach($imported[2] as $importNum => $importUrl) {
				
					$this->totalItems = $this->totalItems + 1;
					$subSheet = new CSS_Magic($this->processImports, $this->baseUrl, $this->basePath);
					$this->importedItems[$importIndex] = array(
						'obj'		=>	$subSheet,
						'orig'	=>	$imported[0][$importNum],
						'url'		=>	$imported[2][$importNum]
					);
					
					$str = str_replace($imported[0][$importNum], '[WPU_NESTED_IMPORT] {' . $importIndex . '}', $str);
					$importIndex++;
				}
			}
			// Now process the nested imports:
			$this->process_imports($subUrl, $subPath);
		}
		
		$parts = explode("}",$str);

		if(count($parts) > 0) {
			foreach($parts as $part) { 
				if(strpos($part, '{') !== FALSE) {
					list($keys,$cssCode) = explode('{', $part);
					// store full selector
					if(strlen($keys) > 0) {
						$keys = str_replace("\n", "", $keys);
						$keys = str_replace("\r", "", $keys);
						$keys = str_replace("\\", "", $keys);
						$this->addSelector($keys, trim($cssCode));
					}
				}
			}
		}
		
		// process nested stylesheets too
		$this->totalItems = $this->totalItems + count($this->css);
		return ($this->totalItems);
	}
	/**
	 * Opens and parses a CSS file
	 * @param string $filename The path and name of the file 
	 * @param bool $clear Set to true to clear out the internal representation and start again. Leave false to add to what we already have.
	 * @return void
	 */
	public function parseFile($filename,  $clear = false) {
		if ($clear) $this->clear();
		$this->filename = $filename;
		
		if(@file_exists($filename)) {
			$this->parsedFromFile = true;
			return $this->parseString(@file_get_contents($filename));
		} else {
			return false;
		}
	}
	
	/**
	 * Attempt to process @imported stylesheets
	 * If an absolute URL is provided, attempt to substitute in $subPath for $subURL.
	 * Modifies the internal representation. 
	 * @param string $subURL a URL to substitute out. Optional.
	 * @param string $subPath a path to substitute in. Optional.
	 * @return void
	 */
	private function process_imports() {
	
		$basePath = $this->add_trailing_slash(dirname(realpath($this->filename)));
		$path = '';
		
		foreach($this->importedItems as $importIndex => $importItem) {
			if(
				(stristr($importItem['url'], 'http://') !== false) ||
				(stristr($importItem['url'], 'https://') !== false)
			) {
				// full URL:
				if(empty($this->baseUrl)) {
					continue;
				}
				
				$path = str_replace($this->baseUrl, $this->basePath, $importItem['url']);
				
				if(
					(stristr($importItem['url'], 'http://') !== false) ||
					(stristr($importItem['url'], 'https://') !== false)
				) {
					continue;
				}
				
			} elseif(substr($importItem['url'], 0, 1) === '/') {
				// absolute URL:
				$path = $this->get_doc_root() . $importItem['url'];
			} else {
				// relative URL:
				
				$path = $basePath . $importItem['url'];
				
			}
			$path = @realpath($path);

			// only process imported stylesheets if we haven't done so already, to avoid infinite recursion
			if(!$this->file_already_processed($path)) {
				if(!empty($path) && $importItem['obj']->parseFile($path)) {
					$importItem['obj']->process_imports($subUrl, $subPath);
				}
			}
		}
	}
	
	
	/**
	 * Determine if this file has already been processed, to avoid infinite recursion into stylesheets that import
	 * each other. For example, if stylesheet A imports stylesheet B, which imports stylesheet A, a black hole opens
	 * and the entire earth gets sucked in. Or something.
	 */
	public function file_already_processed($path) {
	
		foreach($this->importedItems as $importIndex => $importItem) {
			if($importItem['obj']->file_already_processed($path)) {
				return true;
			}
		}
		
		if($this->parsedFromFile && ($this->filename == $path)) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Add selector (private) -- adds a selector to the internal representation
	 * @return void
	 */
	private function addSelector($keys, $cssCode) {
		$keys = trim($keys);
		$cssCode = trim($cssCode);
		
		while(array_key_exists($keys, $this->css)) {
			$keys = "__ " . $keys;
		}
		
			$this->css[$keys] = $cssCode;

	}
	/**
	 * Makes the CSS more specific by applying an outer ID
	 * @param string $id The DOM ID to use
	 * @param bool $removeBody Whether the body tag should be ignored
	 * @return void
	 */
	public function makeSpecificById($id, $removeBody = false) {
		$this->_makeSpecific("#{$id}", $removeBody);
	}
	/**
	 * Makes the CSS more specific by applying an outer class name
	 * @param string $class The document class to use
	 * @param bool $removeBody Whether the body tag should be ignored
	 * @return void
	 */
	public function makeSpecificByClass($class, $removeBody = false) {
		$this->_makeSpecific(".{$class}", $removeBody);
	}
	/**
	 * Makes the CSS more specific by applying an outer ID and class
	 * @param string $classAndIdThe string to prepend
	 * @param bool $removeBody Whether the body tag should be ignored
	 * @return void
	 */
	public function makeSpecificByIdThenClass($classAndId, $removeBody = false) {
		$this->_makeSpecific("#{$classAndId} .{$classAndId}", $removeBody);
	}
	/**
	 * Applies a prefix (e.g. "wpu") to specific IDs
	 * @param string prefix the prefix to apply
	 * @param bool $IDs an array of IDs to modify
	 * @return void
	 */
	public function renameIds($prefix, $IDs) {
		$fixed = array();
		$searchStrings = array();
		$replStrings = array();
		if(sizeof($IDs)) {
			foreach($IDs as $ID) {
				foreach(array(' ', '{', '.', '#', ':') as $suffix) {
					$searchStrings[] = "#{$ID}{$suffix}";
					$replStrings[] = "#{$prefix}{$ID}{$suffix}";
				}
			}
			foreach($this->css as $keyString => $cssCode) {
				$fixed[str_replace($searchStrings, $replStrings, $keyString)] = $cssCode;
			}
			$this->css = $fixed;
		}
		unset($fixed);
	}
	/**
	 * Applies a prefix (e.g. "wpu") to specific classes
	 * @param string prefix the prefix to apply
	 * @param bool $classess an array ofclasses to modify
	 * @return void
	 */	
	public function renameClasses($prefix, $classes) {
		$fixed = array();
		$searchStrings = array();
		$replStrings = array();
		if(sizeof($classes)) {
			foreach($classes as $class) {
				foreach(array(' ', '{', '.', '#', ':') as $suffix) {
					$searchStrings[] = '#' . $class . $suffix;
					$replStrings[] = "#{$prefix}{$class}";
				}
			}
			foreach($this->css as $keyString => $cssCode) {
				$fixed[str_replace($searchStrings, $replStrings, $keyString)] = $cssCode;
			}
			$this->css = $fixed;
		}		
		unset($fixed);
	}	
	
	/**
	 * Makes all stored CSS specific to a particular parent ID or class
	 * @param string prefix the prefix to apply
	 * @param bool  $removeBody: set to true to ignore body keys
	 * @access private but marked as public as we can have recursive (nested) CSS Magics.
	 * @return void
	 */
	public function _makeSpecific($prefix, $removeBody = false) {
		$fixed = array();
		// things that could be delimiting a "body" selector at the beginning of our string.
		$seps = array(' ', '>', '<', '.', '#', ':', '+', '*', '[', ']', '?');
		$index = 0;
		foreach($this->css as $keyString => $cssCode) {
			$keyString = str_replace('__ ', '', $keyString);
			$index++;
			$fixedKeys = array();
			if($keyString ==  '[WPU_NESTED]') {
				$fixedKeys = array('[WPU_NESTED]');
				$this->nestedItems[(int)$cssCode]['content']->_makeSpecific($prefix, $removeBody);
			} else if($keyString == '[WPU_NESTED_IMPORT]') {
				// TODO: process nested import
				$fixedKeys = array('[WPU_NESTED_IMPORT]');
				$this->importedItems[(int)$cssCode]['obj']->_makeSpecific($prefix, $removeBody);
			} else {
				$keys = explode(',', $keyString);
				foreach($keys as $key) {
					$fixedKey = trim($key);
					$foundBody = false;
					// remove references to 'body'
					//$keyElements = preg_split('/[\s<>\.#\:\+\*\[\]\?]/');
					foreach($seps as $sep) {
						$keyElements = explode($sep, $fixedKey);
						$bodyPos = array_search("body", $keyElements);
						if($bodyPos !== false) {
							$keyElements[$bodyPos] = $prefix;
							if(!$removeBody) {
								if(sizeof($keyElements) > 1) { 
									$fixedKey = implode($sep, $keyElements);
								} else {
									$fixedKey = $keyElements[$bodyPos]; 
								}
							
							} 
							$foundBody = true;
						}
					}
					// add prefix selector before each selector
					if(!$foundBody) {
						if(($fixedKey[0] != "@") && (strlen(trim($fixedKey)))) {
							if(strpos($fixedKey, '* html') !== false) { // ie hack
								$fixedKey = str_replace('* html', '* html ' . $prefix . ' ', $fixedKey);
							} elseif(strpos($fixedKey, '*+ html') !== false) { // ie7 hack
								$fixedKey = str_replace('*+ html', '*+ html ' . $prefix . ' ', $fixedKey);
							} elseif($fixedKey == 'html') {
								$fixedKey = $prefix;
							} else {
								$fixedKey = "{$prefix} " . $fixedKey;
							}
							
						}
					
					}
					if(!empty($fixedKey)) {
						$fixedKeys[] = $fixedKey;
					}
				}
				
			} 
			
			// recreate the fixed key
			if(sizeof($fixedKeys)) {
				$fixedKeyString = implode(', ', $fixedKeys);
			
				while(array_key_exists($fixedKeyString, $fixed)) {
					$fixedKeyString = "__ " . $fixedKeyString;
				}	
				$fixed[$fixedKeyString] = $cssCode;

			}
		}

		// done
		$this->css = $fixed;
		unset($fixed);

	}
	
	/**
	 * Removes common elements from CSS selectors
	 * For example, this can be used to undo CSS magic additions
	 * @param string $txt the stuff to wipe out
	 * @return void
	 */
	public function removeCommonKeyEl($txt) {
		$newCSS = array();
		foreach($this->css as $keyString => $cssCode) {
			$newKey = trim(str_replace($txt, '', $keyString));
			if(!empty($newKey)) {
				$newCSS[$newKey] = $cssCode;
			}
		}
		$this->css = $newCSS;
		unset($newCSS);
		
		foreach($this->nestedItems as $index => $nestedItem) {
			$nestedItem['content']->removeCommonKeyEl($txt);
		}
		foreach($this->importedItems as $index => $importedItem) {
			$importedItem['obj']->removeCommonKeyEl($txt);
		}
	}
	
	/**
	 * Returns all key classes and IDs
	 * @param array $ignores: An array of items which, if any are found in the CSS key, will cause this key to be ignored
	 * @return array an array with all classes and IDs
	 */
	public function getKeyClassesAndIDs($ignores = '') {
		$classes = array();
		$ids = array();
		if(!is_array($ignores)) {
			$ignores = array();
		}
		
		foreach($this->css as $keyString => $cssCode) {
			
			foreach($ignores as $ignore) {
				if(strstr($keyString, $ignore) !== false) {
					continue 2;
				}
			}
			$keyString = str_replace('__ ', '', $keyString);
			
			preg_match_all('/\..[^\s^#^>^<^\.^,^:]*/', $keyString, $cls);
			preg_match_all('/#.[^\s^#^>^<^\.^,^:]*/', $keyString, $id);
			
			if(sizeof($cls[0])) {
				$classes = array_merge($classes, $cls[0]);
			}
			if(sizeof($id[0])) {
				$ids = array_merge($ids, $id[0]);
			}			
		}
		
		
		foreach($this->nestedItems as $index => $nestedItem) {
			$nestedEls = $nestedItem['content']->getKeyClassesAndIDs($ignores);
			if(sizeof($nestedEls['classes'])) {
				$classes = array_merge($classes, $nestedEls['classes']);
			}
			if(sizeof($nestedEls['ids'])) {
				$ids = array_merge($ids, $nestedEls['ids']);
			}
		}
		
		foreach($this->importedItems as $index => $importedItem) {
			$importedEls = $importedItem['obj']->getKeyClassesAndIDs($ignores);
			if(sizeof($importedEls['classes'])) {
				$classes = array_merge($classes, $importedEls['classes']);
			}
			if(sizeof($importedEls['ids'])) {
				$ids = array_merge($ids, $importedEls['ids']);
			}
		}		
		
		if(sizeof($classes)) {
			$classes = array_unique($classes);
		}
		if(sizeof($ids)) {
			$ids = array_unique($ids);
		}		
		return array('ids' => $ids, 'classes' => $classes);
	}
	
	
	/**
	 * Searchs through all keys and and makes modifications
	 * @param array $finds key elements to find
	 * @param array $replacements Matching replacements for key elements
	 * @return void
	 */
	public function modifyKeys($finds, $replacements) {
		$theFinds = array();
		$theRepl = array();
		// First prepare the find/replace strings
		foreach($finds as $findString) {
			$theFinds[] = '/' . str_replace('.', '\.', $findString) . '([\s#\.<>:]|$)/';
		}
		foreach($replacements as $replString) {
			$theRepl[] = $replString . '\\1';
		}

		$keys = array_keys($this->css);
		$values = array_values($this->css);
		
		$keys = preg_replace($theFinds, $theRepl, $keys);
		$this->css = array_combine($keys, $values);
		
		foreach($this->nestedItems as $index => $nestedItem) {
			$nestedItem['content']->modifyKeys($finds, $replacements);
		}
		foreach($this->importedItems as $index => $importedItem) {
			$importedItem['obj']->modifyKeys($finds, $replacements);
		}
		
	}
	
	/**
	 * Cleans up relative URLs in stylesheets so that they still work even through style-fixer
	 * @param string $filePath the path to the current file
	 * @param string $css a string containing valid CSS to be modified
	 */
	public function fix_urls() {
		global $phpbb_root_path, $wpUnited, $phpbbForum;
		
		$alreadyProcessed = array();
		
		$filePath = (empty($this->filename)) ? $this->basePath : dirname($this->filename);
		
		$relPath = $this->compute_path_difference($filePath);
		
		$urlToCssFile = str_replace($this->basePath, $this->baseUrl, $filePath);
	
		if($urlToCssFile) {
			$urlToCssFile = explode('/', str_replace('\\', '/', $urlToCssFile));
		}
		
		$newCSS = array();
		
		foreach($this->css as $keyString => $cssCode) {
			
			$urls = 0;
			$cssResult = $cssCode;
			
			preg_match_all('/url\(.*?\)/', $cssCode, $urls);
			if(is_array($urls[0])) {
				foreach($urls[0] as $url) {	
					
					$replace = false;
					
					if((stristr($url, "http:") === false)  && (stristr($url, "https:") === false) && (substr($url, 0, 1) != '/')) {
						$out = str_replace(array('url', '(', ')', "'", '"', ' '), '', $url);
						if ($out != '/') {
							$replace = true;
						}
					}
				
					if ($replace) {
						
						// only process URLs we haven't processed before in this session
						if(isset($alreadyProcessed[$url])) {
							$out = $alreadyProcessed[$url];
						} else {
						
						
							// We try to sub in the absolute URL for the file path. If that fails then we use the computed relative path difference.
							if($urlToCssFile) {
								$urlParts = explode('/', $out);
								$canModify = true;
								
								$result = $urlToCssFile;
								foreach($urlParts as $part) {
									if (($part == '.') || ($part == '')) {
										continue;
									} else if ($part == '..') {
										if(!sizeof($result)) {
											$canModify = false;
											break;
										}
										array_pop($result);
									} else {
										$result[] = $part;
									}
								}
								if($canModify) {
									$out = implode('/', $result);
								}	
							}
							
							if((stristr($out, "http:") === false)  && (stristr($url, "https:") === false)) {
								$out = $relPath.$out;
							}
							$out = str_replace(array('//', ':/'), array('/', '://'), $out);		
							
							
							$alreadyProcessed[$url] = $out;
						}
						
						$cssResult = str_replace($url, "url('{$out}')", $cssResult);
					}
				}
			}
			
			$newCSS[$keyString] = $cssResult;
			
		}
		
		$this->css = $newCSS;
		
		foreach($this->nestedItems as $index => $nestedItem) {
			$nestedItem['content']->fix_urls();
		}
		foreach($this->importedItems as $index => $importedItem) {
			$importedItem['obj']->fix_urls();
		}
		
	}
	
	private function compute_path_difference($filePath) {
		
		$absFileLoc = clean_path(realpath($filePath));

		if(is_dir($absFileLoc)) {
			$absFileLoc = $this->add_trailing_slash($absFileLoc);
		}

		$currLoc = @realpath($this->add_trailing_slash(getcwd()));
		
		if(is_dir($absCurrLoc)) {
			$absCurrLoc = $this->add_trailing_slash($absCurrLoc);
		}
		
		// A fix for the WP-United build environment symlinks
		$absCurrLoc = str_replace('wpu-buildenv/sources/wp-united/root/wp-united/', 'wpu-buildenv/sources/phpbb/wp-united/', $absCurrLoc);
		
		$pathSep = (stristr( PHP_OS, "WIN")) ? "\\": "/";

		$absFileLoc = explode($pathSep, $absFileLoc);
		$absCurrLoc = explode($pathSep, $absCurrLoc);
		array_pop($absFileLoc);

		while($absCurrLoc[0]==$absFileLoc[0]) { 
			array_shift($absCurrLoc);
			array_shift($absFileLoc);
		}
		$pathsBack = array(".");
		for($i=0;$i<(sizeof($absCurrLoc)-1);$i++) {
			$pathsBack[] = "..";
		}
		$relPath = $this->add_trailing_slash(implode("/", $pathsBack)) . $this->add_trailing_slash(implode("/", $absFileLoc));
		$relPath = str_replace('//', '/', $relPath);
		return $relPath;
	}

	/*
	 * returns all our stored, fixed (hopefully!) CSS
	 * @return string fixed CSS
	 */
	public function getCSS() {
		$response = '';
		foreach($this->css as $keyString => $cssCode) {
			$keyString = str_replace('__ ', '', $keyString);
			$cssCode = str_replace('[TANTEK]', "}\\", $cssCode);
			if($keyString == '[WPU_NESTED]') {
				$response .= $this->nestedItems[(int)$cssCode]['selector'];
				$response .= $this->nestedItems[(int)$cssCode]['content']->getCSS() . "}\n\n";
			} elseif($keyString == '[WPU_NESTED_IMPORT]') {
				$r = $this->importedItems[(int)$cssCode]['obj']->getCSS();
				$response .= (empty($r)) ? $this->importedItems[(int)$cssCode]['orig'] : $r ;
				$response .= "\n\n";
			} else {
				$response .= $keyString . '{' . $cssCode . "}\n";
			}
		}
		return $response;
	}
	/**
	 * Sends CSS directly to browser as text/css
	 * @return void
	 */
	public function sendCSS() {
		header("Content-type: text/css");
		echo $this->getCSS();
	
	}

	/**
	 * You can do echo $cssMagic and voila: A stylesheet is in the intertubes.
	 * @return CSS string
	 */
	public function __toString() {
		header("Content-type: text/css");
		$this->getCSS();
	}
	
	/**
	 * Get the document root
	 */
	private function get_doc_root() {
		$docRoot =  (isset($_SERVER['DOCUMENT_ROOT'])) ? $_SERVER['DOCUMENT_ROOT'] : substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF']) );
		$docRoot = @realpath($docRoot); 
		$docRoot = str_replace( '\\', '/', $docRoot);
		$docRoot = ($docRoot[strlen($docRoot)-1] == '/') ? $docRoot : $docRoot . '/';
		return $docRoot;
	}
	
	/**
	 * Adds a traling slash to a string if one is not already present.
	 * @param string $path
	 * @return string modified path
	 */
	private function add_trailing_slash($path) {
		return ( $path[strlen($path)-1] == "/" ) ? $path : $path . "/";
	}
}

// The end.
