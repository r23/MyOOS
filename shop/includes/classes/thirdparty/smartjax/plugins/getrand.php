<?php

class getrand 
{
	var $request;

	function getrand($request_string)
	{
		$this->request = $request_string;
	}
	/**
	 * method to return the contents of xmlhttprequest action
	 *
	 * @return  mixed
     *          depending on the format chosen in smartjax:
     *          TEXT => return a string of text
     *          XML => return a valid XML document
     *          JSON (default) => return a string of text OR PHP associative array
	 */
	function return_response()
	{
        $_resp['rand'] = rand(1,1000);
        $_resp['rand2'] = rand(1,1000);
        $_resp['rand3'] = rand(1,1000);
		return $_resp;
	}
	
	/**
	 * method to test if xmlhttprequest is authorized
	 *
	 * @return  boolean true/false
	 */
	function is_authorized()
	{
		return true;
	}
}
?>
