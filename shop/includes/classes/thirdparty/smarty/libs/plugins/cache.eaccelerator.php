<?php
  /**
   * Smarty Cache Handler<br>
   * utilizing eaccelerator extension (http://eaccelerator.net/HomeUk)<br>
   * 
   * @package    Smarty
   * @subpackage plugins
   */
   
  /**
   * Helper function for smarty_cache_eaccelerator()
   * Clears a whole hierarchy of cache entries.
   * 
   * @access  private
   * @param   array  $hierarchy      hierarchical array of cache ids
   * @return  void
   * 
   * @see     smarty_cache_eaccelerator()
   */
  function _eaccelerator_clear_cache(&$hierarchy) {
    foreach ($hierarchy as $key => $value) {
      if (is_array($value)) {
        _eaccelerator_clear_cache($value);
      }
      else {
        eaccelerator_lock($value);
        eaccelerator_rm($value);
        eaccelerator_unlock($value);
      }
    }
  }
  
  /**
   * Helper function for smarty_cache_eaccelerator()
   * Checks whether a cached content has been expired by reading the content's header.
   * 
   * @access  private
   * @param   string    $cache_content      the cached content
   * @return  boolean                       TRUE if cache has been expired, FALSE otherwise
   * 
   * @see     smarty_cache_eaccelerator()
   */
  function _eaccelerator_hasexpired(&$cache_content) {
    $split      = explode("\n", $cache_content, 2);
    $attributes = unserialize($split[0]);
    
    if ($attributes['expires'] > 0 && time() > $attributes['expires'])
      return true;
    else
      return false;
  }
  
  /**
   * Smarty Cache Handler<br>
   * utilizing eAccelerator extension (http://eaccelerator.net/HomeUk)<br>
   * 
   * Name:     smarty_cache_eaccelerator<br>
   * Type:     Cache Handler<br>
   * Purpose:  Replacement for the file based cache handling of Smarty. smarty_cache_eaccelerator() is
   *           using Turck eaccelerator extension to minimize disk usage.
   * File:     cache.eaccelerator.php<br>
   * Date:     Dec 2, 2003<br>
   * 
   * Usage Example<br>
   * <pre>
   * $smarty = new Smarty;
   * $smarty->cache_handler_func = 'smarty_cache_eaccelerator';
   * $smarty->caching = true;
   * $smarty->display('index.tpl');
   * </pre>
   * 
   * @author   André Rabold
   * @version  RC-1
   * 
   * @param    string   $action         Cache operation to perform ( read | write | clear )
   * @param    mixed    $smarty         Reference to an instance of Smarty
   * @param    string   $cache_content  Reference to cached contents
   * @param    string   $tpl_file       Template file name
   * @param    string   $cache_id       Cache identifier
   * @param    string   $compile_id     Compile identifier
   * @param    integer  $exp_time       Expiration time
   * @return   boolean                  TRUE on success, FALSE otherwise
   * 
   * @link     http://eaccelerator.net/HomeUk
   *           (eaccelerator homepage)
   * @link     http://smarty.php.net/manual/en/section.template.cache.handler.func.php
   *           (Smarty online manual)
   */
  function smarty_cache_eaccelerator($action, &$smarty, &$cache_content, $tpl_file=null, $cache_id=null, $compile_id=null, $exp_time=null)
  {
    if(!function_exists("eaccelerator")) {
      $smarty->trigger_error("cache_handler: PHP Extension \"eaccelerator\" (http://eaccelerator.net/HomeUk) not installed.");
      return false;
    }
    
    // Create unique cache id:
    // We are using smarty's internal functions here to be as compatible as possible.
    $_auto_id    = $smarty->_get_auto_id($cache_id, $compile_id);
    $_cache_file = substr($smarty->_get_auto_filename(".", $tpl_file, $_auto_id),2);
    $eaccelerator_id  = "smarty_eaccelerator|".$_cache_file;
    
    // The index contains all stored cache ids in a hierarchy and can be iterated later
    $eaccelerator_index_id = "smarty_eaccelerator_index";
    
    switch ($action) {
    
      case 'read':
        // read cache from shared memory
        $cache_content = eaccelerator_get($eaccelerator_id);
        if (!is_null($cache_content) && _eaccelerator_hasexpired($cache_content)) {
          // Cache has been expired so we clear it now by calling ourself with another parameter :)
          $cache_content = null;
          smarty_cache_eaccelerator('clear', $smarty, $cache_content, $tpl_file, $cache_id, $compile_id);
        }
        
        $return = true;
        break;
        
      case 'write':
        // save cache to shared memory
        $current_time = time();
        if (is_null($exp_time) || $exp_time < $current_time)
          $ttl = 0;
        else
          $ttl = $exp_time - time();
        
        // First run garbage collection
        eaccelerator_gc();
        
        // Put content into cache
        eaccelerator_lock($eaccelerator_id);
        eaccelerator_put($eaccelerator_id, $cache_content, $ttl);
        
        // Create an index association
        eaccelerator_lock($eaccelerator_index_id);
        $eaccelerator_index = eaccelerator_get($eaccelerator_index_id);
        if (!is_array($eaccelerator_index))
          $eaccelerator_index = array();
        $indexes = explode(DIRECTORY_SEPARATOR, $_cache_file);
        $_pointer =& $eaccelerator_index;
        foreach ($indexes as $index) {
          if (!isset($_pointer[$index]))
            $_pointer[$index] = array();
          $_pointer =& $_pointer[$index];
        }
        $_pointer = $eaccelerator_id;
        eaccelerator_put($eaccelerator_index_id, $eaccelerator_index, 0);
        eaccelerator_unlock($eaccelerator_index_id);

        eaccelerator_unlock($eaccelerator_id);
        break;
        
      case 'clear':
        // clear cache info
        eaccelerator_lock($eaccelerator_index_id);
        $eaccelerator_index = eaccelerator_get($eaccelerator_index_id);
        if (is_array($eaccelerator_index)) {
          if (empty($cache_id) && empty($compile_id) && empty($tpl_file)) {
            // clear all cache
            eaccelerator_lock($eaccelerator_id);
            _eaccelerator_clear_cache($eaccelerator_index);
            eaccelerator_unlock($eaccelerator_id);
            $eaccelerator_index = array();
          }
          else {
            // clear single file or cache group
            $indexes = explode(DIRECTORY_SEPARATOR, $_cache_file);
            if (is_null($tpl_file))
              array_pop($indexes);
            
            $_pointer =& $eaccelerator_index;
            $_failed = false;
            foreach ($indexes as $index) {
              if (!isset($_pointer[$index])) {
                $_failed = true;
                break;
              }
              $_pointer =& $_pointer[$index];
            }
            
            if (!$_failed) {
              if (is_array($_pointer)) {
                // Clear cache group
                _eaccelerator_clear_cache($_pointer);
              }
              else {
                // Clear single file
                eaccelerator_lock($_pointer);
                eaccelerator_rm($_pointer);
                eaccelerator_unlock($_pointer);
              }
              $_pointer = null;
            }
          }
        }
        eaccelerator_put($eaccelerator_index_id, $eaccelerator_index, 0);
        eaccelerator_unlock($eaccelerator_index_id);
        
        $return = true;
        break;
        
      default:
        // error, unknown action
        $smarty->trigger_error("cache_handler: unknown action \"$action\"");
        $return = false;
        break;
    }
    return $return;
  }
?>