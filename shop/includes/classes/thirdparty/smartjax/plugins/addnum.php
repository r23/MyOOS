<?php

class addnum
{
	var $request;

	function addnum($request_string)
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
		return (int)$this->request['num1'] + (int)$this->request['num2'];
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
