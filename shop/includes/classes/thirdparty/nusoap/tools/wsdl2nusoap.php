<?php
/*
$Id: wsdl2nusoap.php,v 1.1 2009/01/11 13:59:17 r23 Exp $

This tool is part of NuSOAP - Web Services Toolkit for PHP

Copyright (c) 2002 NuSphere Corporation

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

The NuSOAP project home is:
http://sourceforge.net/projects/nusoap/

The primary support for NuSOAP is the Help forum on the project home page.

If you have any questions or comments, please email:

Dietrich Ayala
dietrich@ganx4.com
http://dietrich.ganx4.com/nusoap

NuSphere Corporation
http://www.nusphere.com
*/
$dirname = dirname($_SERVER['PATH_TRANSLATED']);
$basename = basename($_SERVER['PATH_TRANSLATED']);

//require_once('c:/nusoap/lib/nusoap.php');

// optionally load just the required classes

// base
require_once($dirname . '/../lib/class.nusoap_base.php');

// transport classes
require_once($dirname . '/../lib/class.soap_transport_http.php');

// optional add-on classes
require_once($dirname . '/../lib/class.xmlschema.php');
require_once($dirname . '/../lib/class.wsdl.php');

class wsdl2nusoap_options {
	var $username = '';				// Username for HTTP authentication
	var $password = '';				// Password for HTTP authentication
	var $authtype = '';				// Type of HTTP authentication
	var $certRequest = array();		// Certificate for HTTP SSL authentication

    var $proxyhost = '';			// Hostname or IP address for proxy
    var $proxyport = '';			// IP port for proxy
	var $proxyusername = '';		// Username for proxy authentication
	var $proxypassword = '';		// Password for proxy authentication

	var $timeout = 0;				// HTTP connection timeout
	var $response_timeout = 30;		// HTTP response timeout

	var $curl_options = array();	// User-specified cURL options
	var $use_curl = false;			// whether to always try to use cURL

	var $portName = '';				// port name to use in WSDL
	var $bindingType = '';			// WSDL operation binding type
	
	function wsdl2nusoap_options() {
	}
}

class wsdl2nusoap extends nusoap_base {
	var $options;
	var $wsdl;
	var $wsdlFile;

	function wsdl2nusoap($wsdl, $options = null) {
		parent::nusoap_base();

		$this->debug("Enter wsdl2nusoap ctor");

		if (is_object($wsdl) && (get_class($wsdl) == 'wsdl')) {
			$this->wsdl = $wsdl;
			$this->wsdlFile = $wsdl->wsdl;
			$this->debug('existing wsdl instance created from ' . $this->wsdlFile);
		} elseif (is_string($wsdl)) {
			$this->wsdl = null;
			$this->wsdlFile = $wsdl;
			$this->debug('will use lazy evaluation of wsdl from ' . $this->wsdlFile);
		} else {
			$this->debug('$wsdl parameter is not a wsdl instance or string');
			$this->setError('$wsdl parameter is not a wsdl instance or string');
		}

		if (is_null($options)) {
			$this->options = new wsdl2nusoap_options();
		} else {
			$this->options = $options;
		}

		$this->debug("Leave wsdl2nusoap ctor");
	}

	/**
	* check WSDL passed as an instance or pulled from an endpoint
	*
	* @access   private
	*/
	function checkWSDL() {
		$this->appendDebug($this->wsdl->getDebug());
		$this->wsdl->clearDebug();
		$this->debug('Enter checkWSDL');
		// catch errors
		if ($errstr = $this->wsdl->getError()) {
			$this->appendDebug($this->wsdl->getDebug());
			$this->wsdl->clearDebug();
			$this->debug('got wsdl error: '.$errstr);
			$this->setError('wsdl error: '.$errstr);
		} elseif ($this->operations = $this->wsdl->getOperations($this->options->portName, 'soap')) {
			$this->appendDebug($this->wsdl->getDebug());
			$this->wsdl->clearDebug();
			$this->bindingType = 'soap';
			$this->debug('got '.count($this->operations).' operations from wsdl '.$this->wsdlFile.' for binding type '.$this->bindingType);
		} elseif ($this->operations = $this->wsdl->getOperations($this->options->portName, 'soap12')) {
			$this->appendDebug($this->wsdl->getDebug());
			$this->wsdl->clearDebug();
			$this->bindingType = 'soap12';
			$this->debug('got '.count($this->operations).' operations from wsdl '.$this->wsdlFile.' for binding type '.$this->bindingType);
			$this->debug('**************** WARNING: SOAP 1.2 BINDING *****************');
		} else {
			$this->appendDebug($this->wsdl->getDebug());
			$this->wsdl->clearDebug();
			$this->debug('getOperations returned false');
			$this->setError('no operations defined in the WSDL document!');
		}
		$this->debug('Leave checkWSDL');
	}

	/**
	 * instantiate wsdl object and parse wsdl file
	 *
	 * @access	public
	 */
	function loadWSDL() {
		$this->debug('Enter loadWSDL');
		if (is_null($this->wsdl)) {
			$this->debug('instantiating wsdl class with doc: ' . $this->wsdlFile);
			$this->wsdl =& new wsdl('', $this->options->proxyhost, $this->options->proxyport, $this->options->proxyusername, $this->options->proxypassword, $this->options->timeout, $this->options->response_timeout, $this->options->curl_options, $this->options->use_curl);
			$this->wsdl->setCredentials($this->options->username, $this->options->password, $this->options->authtype, $this->options->certRequest);
			$this->wsdl->fetchWSDL($this->wsdlFile);
			$this->appendDebug($this->wsdl->getDebug());
			$this->wsdl->clearDebug();
		}
		$this->checkWSDL();
		$this->debug('Leave checkWSDL');
	}

	function getCode() {
		$this->debug("Enter getCode");
		$this->loadWSDL();
		if ($this->getError()) {
			echo $this->getError();
		} else {
			echo "<?php\n";
			echo "/*\n";
			echo " *\tgenerated from " . $this->wsdlFile . " at " . $this->getmicrotime() . "\n";
			echo " */\n";
			echo "require_once('c:/nusoap/lib/nusoap.php');\n";
			$this->getComplexTypes();
			$this->getOperations();
			echo "?>\n";
		}
		$this->debug("Leave getCode");
	}

	function getComplexTypes() {
		$this->debug("Enter getComplexTypes");
		foreach ($this->wsdl->schemas as $ns => $list) {
			$this->debug("Process namespace $ns");
			foreach ($list as $xs) {
				foreach ($xs->complexTypes as $ctname => $ct) {
					$this->debug("Process complexType $ctname");
					$names = array();
					echo "\n";
					echo "/*\n";
					echo " *\t$ns:$ctname\n";
					echo " */\n";
					echo "class " . $ct['name'] . " {\n";
					echo "\t// elements\n";
					if (isset($ct['elements'])) {
						$this->debug("Process elements for complexType $ctname");
						foreach ($ct['elements'] as $elname => $el) {
							$this->debug("Process element $elname for complexType $ctname");
							$names[] = $el['name'];
							echo "\tvar \$" . $el['name'] . "; // " . $el['type'] . "\n";
						}
					}
					echo "\n";
					echo "\t// attributes\n";
					if (isset($ct['attributes'])) {
						$this->debug("Process attributes for complexType $ctname");
						foreach ($ct['attributes'] as $atname => $at) {
							$this->debug("Process attribute $atname for complexType $ctname");
							$names[] = $at['name'];
							echo "\tvar " . $at['name'] . "; // " . $at['type'] . "\n";
						}
					}
					echo "\n";
					echo "\t// ctor that initializes members from an associative array of values\n";
					echo "\tfunction " . $ct['name'] . "(\$values) {\n";
					echo "\t\tif (isset(\$values) && is_array(\$values)) {\n";
					foreach ($names as $name) {
						echo "\t\t\tif (isset(\$values['$name'])) \$this->$name = \$values['$name'];\n";
					}
					echo "\t\t}\n";
					echo "\t}\n";
					echo "}\n";
				}
			}
		}
		$this->debug("Leave getComplexTypes");
	}

	/**
	* 
	*
	* @access   private
	*/
	function getOperations() {
		$this->debug("Enter getOperations");
		foreach ($this->wsdl->ports as $port => $portData) {
			$this->debug("Process port $port");
			$location = $portData['location'];
			echo "\n";
			echo "/*\n";
			echo " *\tport: $port\n";
			echo " *\tlocation: $location\n";
			echo " *\tbinding: " . $portData['binding'] . "\n";
			if (isset($this->wsdl->bindings[$portData['binding']])) {
				$this->debug("Process binding " . $portData['binding']);
				$binding = $this->wsdl->bindings[$portData['binding']];
//				foreach ($binding as $k => $v) {
//					echo "$k";
//					if (!is_array($v)) echo "=$v";
//					echo "\n";
//				}
				if (isset($binding['bindingType'])) {
					$bindingType = $binding['bindingType'];
				} else {
					$bindingType = "(default)";
				}
				if (isset($binding['style'])) {
					$style = $binding['style'];
				} else {
					$style = "(default)";
				}
				if (isset($binding['transport'])) {
					$transport = $binding['transport'];
				} else {
					$transport = "(default)";
				}
				echo " *\tbindingType: $bindingType\n";
				echo " *\tstyle: $style\n";
				echo " *\ttransport: $transport\n";
				echo " */\n";

				echo "class $port extends nusoap_client {\n";
				echo "\t// constructor\n";
				echo "\tfunction $port(\$endpoint = '$this->wsdlFile', \$wsdl = 'wsdl', \$proxyhost = false, \$proxyport = false, \$proxyusername = false, \$proxypassword = false, \$timeout = 0, \$response_timeout = 30, \$portName = '$port') {\n";
				echo "\t\tparent::nusoap_client(\$endpoint, \$wsdl, \$proxyhost, \$proxyport, \$proxyusername, \$proxypassword, \$timeout, \$response_timeout, \$portName);\n";
				echo "\t}\n";
				if (isset($binding['operations'])) {
					$this->debug("Process operations for binding " . $portData['binding']);
					foreach ($binding['operations'] as $opname => $op) {
						$this->debug("Process operation $opname");
//						foreach ($op as $k => $v) {
//							echo "$k";
//							if (!is_array($v)) echo "=$v";
//							echo "\n";
//						}
						if (isset($op['soapAction'])) {
							$soapAction = $op['soapAction'];
						} else {
							$soapAction = "";
						}

						$input = $op['input'];
//						foreach ($input as $k => $v) {
//							echo "$k";
//							if (!is_array($v)) echo "=$v";
//							echo "\n";
//						}
						if (isset($input['encodingStyle'])) {
							$encodingStyle = $input['encodingStyle'];
						} else {
							$encodingStyle = "";
						}
						if (isset($input['message'])) {
							$message = $input['message'];
						} else {
							$message = "";
						}
						if (isset($input['namespace'])) {
							$namespace = $input['namespace'];
						} else {
							$namespace = "";
						}
						if (isset($input['use'])) {
							$use = $input['use'];
						} else {
							$use = "(default)";
						}
						if (sizeof($input['parts']) > 0) {
							$paramStr = '';
							$paramArrayStr = '';
							$paramCommentStr = '';
							foreach ($input['parts'] as $name => $type) {
								$this->debug("Process input part $name for operation $opname");
								$paramStr .= "\$$name, ";
								$paramArrayStr .= "'$name' => \$$name, ";
								$paramCommentStr .= "\t *\t\t\t$type \$$name\n";
							}
							$paramStr = substr($paramStr, 0, strlen($paramStr)-2);
							$paramArrayStr = substr($paramArrayStr, 0, strlen($paramArrayStr)-2);
						} else {
							$paramStr = '';
							$paramArrayStr = '';
							$paramCommentStr = "\t *\t\t\tvoid\n";
						}
						echo "\n";
						echo "\t/*\n";
						echo "\t *\toperation: $opname\n";
						echo "\t *\tinput...\n";
						echo "\t *\t\tencodingStyle: $encodingStyle\n";
						echo "\t *\t\tnamespace: $namespace\n";
						echo "\t *\t\tuse: $use\n";
						echo "\t *\t\tmessage: $message\n";
						echo "\t *\t\tparts...\n";
						echo $paramCommentStr;
						echo "\t */\n";
						echo "\tfunction " . str_replace('.', '__', $opname) . "($paramStr) {\n";
						echo "\t\t\$params = array($paramArrayStr);\n";
						echo "\t\treturn \$this->call('$opname', \$params, '$namespace', '$soapAction', false, null, '$style', '$use');\n";
						echo "\t}\n";
					}
				}
				echo "}\n";
			} else {
				$this->debug("Binding " . $portData['binding'] . " not found");
				echo " */\n";
			}
		}
		$this->debug("Leave getOperations");
	}
}

ereg('\$Revisio' . 'n: ([^ ]+)', '$Revision: 1.1 $', $rev);
fwrite(STDERR, "$basename revision $rev[1]\n");

if (count($argv) == 2) {
	$worker = new wsdl2nusoap($argv[1]);
	$worker->getCode();
	//fwrite(STDERR, $worker->getDebug());
} else {
	fwrite(STDERR, "usage: $basename wsdl-url-or-file\n");
	fwrite(STDERR, "       The PHP is written to standard output\n");
}
?>
