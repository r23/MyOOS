<?php

/**
 *
 * SmartJax xmlhttprequest library
 *
 * some code adopted (with permission) from My-BIC implementation by Jim Plush.
 *
 * JSON library by Michal Migurski, Matt Knapp, Brett Stimmerman
 *
 * Author: Monte Ohrt <monte at ohrt dot com>
 * Version: 1.0
 * Date: March 8th, 2006
 * Copyright: 2006 New Digital Group, Inc. All Rights Reserved.
 * License: LGPL GNU Lesser General Public License
 *
 **/

// directory containing smartjax libs
if(!defined('SMARTJAX_ROOT'))
  define('SMARTJAX_ROOT', dirname(__FILE__) . '/');
// directory containing smartjax plugins
if(!defined('SMARTJAX_PLUGINS'))
  define('SMARTJAX_PLUGINS', SMARTJAX_ROOT . 'plugins/');

if(empty($_REQUEST['smartjax_action'])) {
    $_message = "missing smartjax_action request var";
    echo "smartjax_error: $_message";
    exit();
}

// setup filename for action class (remove bad chars)
$_class = preg_replace('!\W!','',basename($_REQUEST['smartjax_action']));
$_class_file = $_class . '.php';

if(is_file(SMARTJAX_PLUGINS . $_class_file)) {
	include(SMARTJAX_PLUGINS . $_class_file);
	
	$xmlhttp_response = new $_class($_REQUEST);
	if($xmlhttp_response->is_authorized()) {
		$response = $xmlhttp_response->return_response();
	} else {
		// failed authorization
        $_message = "authorization failed";
        echo "smartjax_error: $_message";
        exit();
	}
	
	// if json = 0, then text/xml is assumed
	if(isset($_REQUEST['smartjax_json']) && $_REQUEST['smartjax_json'] == '0') {
		echo $response;
	} else {
		require(SMARTJAX_ROOT.'jsonlib.php');
		$JSON = new Services_JSON();
		echo $JSON->encode($response);
	}
} else {
	// no action found
    $_message = "unable to locate file: [SMARTJAX_PLUGINS]/$_class_file";
    echo "smartjax_error: $_message";
    exit();
}
		
	
?>
