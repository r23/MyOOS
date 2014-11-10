<?php
	include('phpOpenOffice.php');

	$vars = array();
	$vars["NAME"] = "Tux";
	$vars["MOBILE"] = "123-4567890";
	$vars["URL"] = "http://www.pinasoft.de/projects/phpopenoffice/";

	$doc = new phpOpenOffice();
	$doc->loadDocument("demo.sxw");
	$doc->parse($vars);
	$doc->download("demodatei");
	$doc->clean();
?>
