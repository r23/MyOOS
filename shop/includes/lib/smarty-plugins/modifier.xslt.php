<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     xslt
 * Purpose:  Translate the variable using specified XSL file.
 * Arguments:
 * 	xslfile - xslt filename to use
 * 
 * Example:  {$xmldoc|xslt:"/path/to/xslt.xsl"}
 * -------------------------------------------------------------
 */
function smarty_modifier_xslt($xml, $xslfile = '')
{
	$xh = xslt_create();
    $arguments = array(
         '/_xml' => $xml
    );
    return xslt_process($xh, 'arg:/_xml', $xslfile, NULL, $arguments);
	xslt_free($xh);
}
?>