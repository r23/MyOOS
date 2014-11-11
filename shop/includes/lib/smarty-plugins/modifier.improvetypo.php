<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.improvetypo.php
 * Purpose:  see shared.improvetypo.php
 *
 * Author:   Christoph Erdmann <smarty@cerdmann.com>
 * Internet: http://www.cerdmann.com
 * -------------------------------------------------------------
 */
 
require_once $smarty->_get_plugin_filepath('shared','improvetypo');

function smarty_modifier_improvetypo($content,$diff = false)
	{
	return smarty_improvetypo($content,$diff);
	}
	
?>
