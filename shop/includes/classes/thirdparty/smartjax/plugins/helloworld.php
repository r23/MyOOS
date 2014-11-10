<?php
class helloworld 
{
	var $request;

	function helloworld($request_string)
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
		return "Hello World! time is" . strftime('%H:%M:%S %Z');
	}
	
	function is_authorized()
	{
		return true;
	}
}
?>
