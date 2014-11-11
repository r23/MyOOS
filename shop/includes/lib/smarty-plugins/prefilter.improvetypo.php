<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     prefilter.improvetypo.php
 * Purpose:  see shared.improvetypo.php
 *
 * Author:   Christoph Erdmann <smarty@cerdmann.com>
 * Internet: http://www.cerdmann.com
 * -------------------------------------------------------------
 */
function smarty_prefilter_improvetypo($content, &$smarty)
	{
	require_once $smarty->_get_plugin_filepath('shared','improvetypo');
	return smarty_improvetypo($content);
	}
	
?>
