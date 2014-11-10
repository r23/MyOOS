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

function SMARTJAX(server_url)
{
    // user editable vars
	this.async = true;					// syncronous mode or async
	this.method = "POST";				// POST/GET/HEAD
	this.send_headers = new Array();    // array of optional headers
	this.format = "JSON";				// JSON/XML/TEXT
    this.user = "";                     // http auth username
    this.pass = "";                     // http auth password
    this.show_errors = true;            // show alert messages on error
    // internal vars
	this.server_url = server_url; 		// server url for xmlhttprequest
	this.req = null;					// the xmlhttp request variable
	this.callBackFunc = "";			    // the callback function
    this.query_vars = "";               // extra query vars
	this.readyStateFunc = this.responseHandler;
}

/**
* Method to create an XMLHTTP object
*@access private
*/
SMARTJAX.prototype.initXMLHTTP = function()
{
    // reset req object
    this.req = null;
	if (window.XMLHttpRequest) {
	    // moz XMLHTTPRequest
		this.req = new XMLHttpRequest();
	}
	else if (window.ActiveXObject){
	    // IE/Windows ActiveX
		this.req = new ActiveXObject("Microsoft.XMLHTTP");
    }
	return this.req;
}

/**
 * make SmartJax xmlhttprequest
 *@access public
 *@param string url encoded query string
 *@param func callBackFunc function
 *@param string form id
 */
SMARTJAX.prototype.call = function(smartjaxAction, userCallBackFunc)
{
	this.fullUrl = '';
	
	// get XMLHTTPRequest Object
	if(!this.initXMLHTTP())
        return false;
	
	this.callBackFunc = userCallBackFunc;
	
	// set response handler, if none is set, use our default one
	this.req.onreadystatechange = this.readyStateFunc;

    // init query vars
    var qstring = 'smartjax=1&smartjax_action='+smartjaxAction;
    
    // add extra query vars
    if(this.query_vars) {
        qstring = qstring + this.query_vars;
    }
    
	// check for JSON encoding
	if(this.format != 'JSON') {
		qstring += '&smartjax_json=0';
	}

	// if get is used, append the query variables to the url string 
	this.full_url = (this.method == "POST") ? this.server_url : this.server_url + '?' + qstring;
	
	// open connection
	this.req.open(this.method, this.full_url, this.async, this.user, this.pass);
	
	// set any optional headers
	if(this.send_headers)
	{
		for(var sh in this.send_headers)
		{
			if(sh != '') {
				this.req.setRequestHeader(sh, this.send_headers[sh]);
			}
		}
	}
    
	// send request
    if(this.method == "POST") {
		this.req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		this.req.send(qstring);
	} else {
		this.req.send(null);
	}
	return true;
}

/**
* parse server response
*/
SMARTJAX.prototype.responseHandler = function()
{
	// 4 == "complete"
    if (sjObj.req.readyState == 4) {
        // 200 == "OK"
        if (sjObj.req.status == 200) {
            if(sjObj.req.responseText.substr(0,15) == 'smartjax_error:') {
                if(sjObj.show_errors) {
                    alert(sjObj.req.responseText);
                }
            } else {
			    if(sjObj.format == "JSON") {
				    try {
	        	        var myObject = eval( '(' + sjObj.req.responseText + ')' );
				        // send json to callBackFunc func
	        	        sjObj.callBackFunc(myObject);
				    } catch(e) {
                        if(sjObj.show_errors) {
				            alert('smartjax: unable to parse xmlhttprequest data');
                        }
				        sjObj.callBackFunc(false);
				    }
			    } else if(sjObj.format == "XML") {
				    // send xml to callBackFunc func
				    sjObj.callBackFunc(sjObj.req.responseXML);
			    } else {
                    // send text to callBackFunc func
				    sjObj.callBackFunc(sjObj.req.responseText);
			    }
            }
        } else {
            if(sjObj.show_errors) {
                alert('smartjax: xmlhttprequest error '+sjObj.req.status+' "'+sjObj.req.statusText+'"');
            }
        }
		// reset to defaults
		sjObj.resetDefaults();
    }
}

/**
* return object to default state
*/
SMARTJAX.prototype.resetDefaults = function()
{
	this.method = "POST";
	this.format = "JSON";
	this.callBackFunc = "";
    this.query_vars = "";
}

/**
 * Process form variables into a single query string
 *@param string the form id
 *@return string the query string
 */

SMARTJAX.prototype.loadForm = function(formid)
{
    var formobj = document.getElementById(formid);
    
    if(null == formobj) {
        if(this.show_errors) {
            alert('smartjax: unable to find form id "'+formid+'"');
        }
        return '';
    }
    
    var fields = new Array();
	for (var x = 0; x < formobj.elements.length; x++) {
       switch(formobj.elements[x].type) {
            case 'select-one':
                fields.push(encodeURIComponent(formobj.elements[x].name)+'='+encodeURIComponent(formobj.elements[x].options[formobj.elements[x].selectedIndex].value));
                break;
            case 'select-multiple':
			    for(var y=0; y < formobj.elements[x].options.length; y++) {
                    if(formobj.elements[x].options[y].selected) {
                       if(formobj.elements[x].options[y].value != '') {
                         fields.push(encodeURIComponent(formobj.elements[x].name)+'='+encodeURIComponent(formobj.elements[x].options[y].value));
                       } else {
                         fields.push(encodeURIComponent(formobj.elements[x].name)+'='+encodeURIComponent(formobj.elements[x].options[y].text));                    
                       }                         
                    }
				}
                break;
            case 'radio':
            case 'checkbox':
                if(formobj.elements[x].checked) {
                    fields.push(encodeURIComponent(formobj.elements[x].name)+'='+encodeURIComponent(formobj.elements[x].value));                
                }
                break;
            default:
                // text, password, textarea, etc
                fields.push(encodeURIComponent(formobj.elements[x].name)+'='+encodeURIComponent(formobj.elements[x].value));
                break;
       }
    }
    this.query_vars += '&' + fields.join('&');
}

SMARTJAX.prototype.addVars = function(query_string)
{
    this.query_vars += '&' + query_string;
}
