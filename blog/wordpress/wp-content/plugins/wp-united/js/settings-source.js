/** 
*
* @package WP-United
* @version $Id: 0.9.1.5  2012/12/28 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
* 
* JavaScript for the WP-United settings panels
*/
var $wpu = jQuery.noConflict();
(function($wpu) {
    $wpu.QueryString = (function(a) {
        if (a == "") return {};
        var b = {};
        for (var i = 0; i < a.length; ++i)
        {
            var p=a[i].split('=');
            if (p.length != 2) continue;
            b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
        }
        return b;
    })(window.location.search.substr(1).split('&'))
})(jQuery);


/**
 * Creates a file tree for the user to select the phpBB location
 */
function createFileTree() {
	$wpu('#phpbbpath').fileTree({ 
		root: '/',
		script: ajaxurl,
		multiFolder: false,
		loadMessage: fileTreeLdgText
	}, function(file) { 
		var parts = file.split('/');
		if ((parts.length) > 1) {
			file = parts.pop();
		}
		if(file=='config.php') {
			var pth = parts.join('/') + '/'; 
			$wpu('#phpbbpathshow').html(pth).css('color', 'green');
			$wpu('#wpupathfield').val(pth);
			$wpu('#phpbbpathgroup').hide('fade');
			$wpu('#txtchangepath').show();
			$wpu('#txtselpath').hide();
			$wpu('#wpucancelchange').hide();
			$wpu('#phpbbpathchooser').show('fade');
			$wpu('#wpusetup-submit').show();
			window.scrollTo(0,0);
		}
	});
	
	$wpu('#wpubackupentry').bind('keyup', function() {
		wpu_update_backuppath(true);
	});
	$wpu('#phpbbdocroot').bind('keyup', function() {
		wpu_update_backuppath(true);
	});	
}



// Resize text boxes dynamically
function resize_text_field($field) {
	var measure = $wpu('#wpu-measure');
	measure.text($field.val());
	var w = measure.width() + 16;
	if(w < 25) w = 25;
	$field.css('width', w + 'px');
}

function wpu_update_backuppath(changeColor) {
	var $docRoot = $wpu('#phpbbdocroot');
	var $bckEntry = $wpu('#wpubackupentry');
	var pth = $docRoot.val() + $bckEntry.val();
	resize_text_field($docRoot);
	resize_text_field($bckEntry);
	pth = pth.replace(/\\/g, '/').replace(/\/\//g,'/');
	$wpu('#wpupathfield').val(pth);
	var $p = $wpu('#phpbbpathshow').html(pth);
	if(changeColor) {
		$p.css('color', 'orange');
	}
}

// Triggered on filetree load, so we can intercept if nothing useful is returned.
var wpuUsingBackupEntry=false;
var wpuForceBackupEntry=false;
function wpu_filetree_trigger(data) {
	
	if(data.length < 50) {
		// FileTree isn't showing any useful data, abandon it and fall back to textbox entry
		wpuForceBackupEntry = true;
		wpuSwitchEntryType();
	} else {
		$wpu('#phpbbpath').show();
		$wpu('#wpubackupgroup').hide();	
	}
}

function wpuSwitchEntryType() {
	
	if(!wpuUsingBackupEntry) {
		// switch to manual text entry
		wpuUsingBackupEntry = true;
		$wpu('#phpbbpath').hide();
		$wpu('#wpubackupgroup').show();
		$wpu('#wpuentrytype').text(autoText);
		wpu_update_backuppath(!wpuForceBackupEntry);
		$wpu('#wpusetup-submit').show();
	} else {
		if(!wpuForceBackupEntry) {
			// switch to filechooser
			wpuUsingBackupEntry = false;
			$wpu('#phpbbpath').show();
			$wpu('#wpubackupgroup').hide();
			$wpu('#wpuentrytype').text(manualText);
			$wpu('#wpusetup-submit').hide();
		}
	}
	
	return false;
}

/**
 * Initialises the settings page
 */
function setupSettingsPage() {
	$wpu('#wputabs').tabs({
		select: function(event, ui) {                   
			window.location.hash = ui.tab.hash;
		}
    });
	$wpu('#phpbbpathchange').button();	
	$wpu('#wputpladvancedstgs').button();	
	$wpu('.wpuwhatis').button();	
	
	var selTab = $wpu.QueryString['tab']; 
	if(selTab != undefined) {
		 $wpu('#wputabs').tabs('select', '#' + selTab); 
	}

}

/**
 * Sets the form fields up when a valid phpBB path is chosen in the filetree
 */
function setPath(type) {
	if(type=='setup') {
		$wpu('#phpbbpathgroup').hide();
		$wpu('#phpbbpathchooser').button();
		$wpu('#phpbbpathchooser').show();
		$wpu('#txtchangepath').show();
		$wpu('#txtselpath').hide();
	}
	$wpu("#phpbbpathshow").html(phpbbPath).css('color', 'green');
	$wpu("#wpupathfield").val(phpbbPath);
}

/**
 * Sets up the buttons for the help / what is this menu
 */
function setupHelpButtons() {
	$wpu('.wpuwhatis').click(function() {
		$wpu('#wpu-desc').text($wpu(this).attr('title'));
		$wpu("#wpu-dialog").dialog({
			modal: true,
			title: 'WP-United Help',
			buttons: {
				Close: function() {
					$wpu(this).dialog('close');
				}
			}
		});
		return false;
	});	
}

/**
 * Sets the settings form dynamic elements to their initial states
 */
function settingsFormSetup() {
	if($wpu('#wpuxpost').is(':checked')) {
		$wpu('#wpusettingsxpostxtra').show();
		if($wpu('#wpuxpostcomments').is(':checked')) {
			$wpu('#wpusettingsxpostcomments').show();
		}
	}
	if($wpu('#wpuloginint').is(':checked')) $wpu('#wpusettingsxpost').show();
	if($wpu('#wputplint').is(':checked')) {
		$wpu('#wpusettingstpl').show();
		if($wpu('#wputplrev').is(':checked')) {
			$wpu('#wputemplate-w-in-p-opts').hide();
		} else {
			$wpu('#wputemplate-p-in-w-opts').hide();
		}
	}
	
	$wpu('input[name=rad_tpl]').change(function() {
		$wpu('#wputemplate-p-in-w-opts').toggle();
		$wpu('#wputemplate-w-in-p-opts').toggle();
	});

	$wpu('#wpuloginint').change(function() {
		$wpu('#wpusettingsxpost').toggle("slide", "slow");
	});
	$wpu('#wpuxpost').change(function() {
		$wpu('#wpusettingsxpostxtra').toggle("slide", "slow");
	});
	$wpu('#wpuxpostcomments').change(function() {
		$wpu('#wpusettingsxpostcomments').toggle("slide", "slow");
	});
	
	setCSSMLevel(cssmVal);
	
	$wpu('#wputplint').change(function() { 
			$wpu('#wpusettingstpl').toggle("slide", "slow");
			var slVal = ($wpu(this).val()) ? 2 : 0;						
			setCSSMLevel(slVal);
			$wpu("#wpucssmlvl").slider("value", slVal);
	});	

	$wpu("#wpucssmlvl").slider({
		value: cssmVal,
		min: 0,
		max: 2,
		step: 1,
		change: function(event, ui) {
			setCSSMLevel(ui.value);
		}
	});	
	
}


/**
 * Re-displays the file tree when the user wants to change the phpBB path
 */
function wpuChangePath() {
	$wpu('#phpbbpathgroup').show('fade');
	$wpu('#phpbbpathchooser').hide('fade');
	$wpu('#txtchangepath').hide();
	$wpu('#txtselpath').show();
	$wpu('#wpucancelchange').show();
	$wpu('#wpucancelchange').button();
	if(!wpuUsingBackupEntry) {
		$wpu('#wpusetup-submit').hide();
	} else {
		$wpu('#wpusetup-submit').show();
	}
	return false;
}

/**
 * Resets the fields and filetree when the user cancels changing the phpBB path
 */
function wpuCancelChange() {
	$wpu('#phpbbpathgroup').hide('fade');
	$wpu('#phpbbpathchooser').show('fade');
	$wpu('#txtchangepath').show();
	$wpu('#txtselpath').hide();
	$wpu('#wpucancelchange').hide();
	$wpu('#wpusetup-submit').hide();			
	return false;
}


/**
 * Sets the CSS Magic / Template Vodoo options when the slider is moved
 */
function setCSSMLevel(level) {
	var lvl, desc;
	if(level == 0) {
		lvl = statusCSSMDisabled;
		desc = descCSSMDisabled;
	} else if(level == 1) {
		lvl = statusCSSMMed;
		desc = descCSSMMed;
	} else if(level == 2) {
		lvl = statusCSSMFull;
		desc = descCSSMFull;							
	} 
	$wpu('#wpucssmlvlfield').val(level);
	$wpu('#cssmlvltitle').html(lvl);
	$wpu('#cssmlvldesc').html(desc);
	try {
		$wpu("#cssmdesc").effect('highlight');
	} catch(e) {}
}
		
/**
 * Shows advanced template setings
 */	
function tplAdv() {
	$wpu('#wpusettingstpladv').toggle('fade');
	$wpu('#wutpladvshow').toggle()
	$wpu('#wutpladvhide').toggle();
	return false;
}

/**
 * Prevents the user from typing alphanumeric characters in the padding fields
 */
function check_padding(evt) {
	var theEvent = evt || window.event;
	var key = theEvent.keyCode || theEvent.which;
	var keyS = String.fromCharCode( key );
	var regex = /[0-9]/;
	if( !regex.test(keyS) && (key!=8) && (key!=46) ) {
		theEvent.returnValue = false;
		if (theEvent.preventDefault) theEvent.preventDefault();
	}
}

function default_padding() {
	$wpu('#wpupadtop').val('6');
	$wpu('#wpupadright').val('12');
	$wpu('#wpupadbtm').val('6');
	$wpu('#wpupadleft').val('12');
	return false;
}




/**
 * Sends the settings to phPBB
 */
function wpu_transmit(type, formID, urlToRefresh) {
	$wpu('#wpustatus').hide();
	window.scrollTo(0,0);
	$wpu('#wputransmit').dialog({
		modal: true,
		title: 'Connecting...',
		width: 360,
		height: 160,
		draggable: false,
		disabled: true,
		closeOnEscape: false,
		resizable: false,
		show: 'puff'
	});
	$wpu('.ui-dialog-titlebar').hide();
	var formData;
	
	// update the backup entry method if needed
	if((type=='wp-united-setup') && wpuUsingBackupEntry) {
		wpu_update_backuppath(true);
	}
	
	
	wpu_setup_errhandler();
	
	formData = $wpu('#' + formID).serialize() +'&action=wpu_settings_transmit&type=' + type + '&_ajax_nonce=' + transmitNonce;
	$wpu.post(ajaxurl, formData, function(response) { 
		response = $wpu.trim(response);
		var responseMsg;
		if(response.length >= 2) responseMsg = response.substr(0, 2);
		if(responseMsg == 'OK') {
			// the settings were applied
			window.location = 'admin.php?page=' + type + '&msg=success' + '&tab=' + window.location.hash.replace('#', '');
			return;
		}
		wpu_process_error(response);
	});
	return false;
}

/**
 * Listen for ajax errors
 */
 var wpu_handling_error = false;
function wpu_setup_errhandler() {
	$wpu(document).ajaxError(function(e, xhr, settings, exception) {
		
		if(!wpu_handling_error) {
			wpu_handling_error = true;
			if(exception == undefined) {
				var exception = 'Server ' + xhr.status + ' error. Please check your server logs for more information.';
			}
			var resp = '<br />There was no page output.<br />';
			if(typeof xhr.responseText !== 'undefined') {
				
				// extract any head, etc from the page.
				var mResp = xhr.responseText.split(/<body/i);
				if(mResp.length) { 
					resp = '<div ' + mResp[1];
					mResp = resp.split(/<\/body>/i);
				}
				resp = (mResp.length) ? mResp[0] + '</div>' : resp;
			
			
				resp = '<br />The page output was:<br /><div>' + resp + '</div>';
			}
			wpu_process_error(errMsg = 'WP-United caught an error: ' + settings.url + ' returned: ' + exception + resp);
		}
	});
	
}

/**
 * Processes various types of errors received during the ajax call
 * Messges prefixed with [ERROR] are handled errors
 * Other types are PHP errors, or server responses with unexpected content
 * Finally we also process non-300 rsponses from jQuery's ajaxError
 */
function wpu_process_error(transmitMessage) { 
	// there was an uncatchable error, send a disable request
	if  (transmitMessage.indexOf('[ERROR]') == -1) { 
		var disable = '&wpudisable=1&action=wpu_disable&_ajax_nonce=' + disableNonce;
		if(transmitMessage == '') {
			transmitMessage = blankPageMsg;
		}
		// prevent recursive ajax error:
		$wpu(document).ajaxError(function() {
			// TODO: if server 500 error or disable, try direct delete method
			send_back_msg('admin.php?page=wp-united-setup&msg=fail', transmitMessage);
		}); 
		$wpu.post(ajaxurl, disable, function(response) {
			// the connection has been disabled, redirect
			send_back_msg('admin.php?page=wp-united-setup&msg=fail', transmitMessage);
		});
	} else {
		// we caught the error, redirect to setup page
		transmitMessage = transmitMessage.replace(/\[ERROR\]/g, '');
		send_back_msg('admin.php?page=wp-united-setup&msg=fail', transmitMessage);
	}
}

// We have to send messages back by POST as URI vars are too long
function send_back_msg(uri, msg) {

	// escape any html in error messages
	$wpu('<div id="escapetext"> </div>').appendTo('body');

	$wpu('<form action="' + uri + '" method="post"><input type="hidden" name="msgerr" value="' + Base64.encode($wpu('#escapetext').text(msg).html()) + '"></input></form>').appendTo('body').submit();
}

/**
 * Sanitizes returned html so we can send it back as a request var
 */
function makeMsgSafe(msg) {
	msg = Base64.encode(msg)
	msg = msg.replace(/\+/ig, '%2B');
	msg = msg.replace(/\=/ig, '%3D');
	msg = msg.replace(/\//ig, '%2F');
	return escape(msg);
}

/**
 * The user wants to disable WP-United
 */
function wpu_manual_disable(type) {
	$wpu("#wputransmit").dialog({
		modal: true,
		title: connectingText,
		width: 360,
		height: 160,
		draggable: false,
		disabled: true,
		closeOnEscape: false,
		resizable: false,
		show: 'puff'
	});
	$wpu('.ui-dialog-titlebar').hide();
	var disable = 'wpudisableman=1&action=wpu_disableman&_ajax_nonce=' + disableNonce;
	$wpu.post(ajaxurl, disable, function(response) {
		// the connection has been disabled, redirect
		window.location = 'admin.php?page='+type;
	});
	
	return false;
	
}

/**
 **********************************************************
 * User mapper 
 **********************************************************
 */

var leftSide, rightSide;
var wpuMapActions = new Array();
var wpuTypedMatches = new Array();
var wpuSuggCache;
var panelOpen = false;
var panelHidden = false;


/**
 * Initialises the user mapper page
 */
function setupUserMapperPage() {

	$wpu('.wpuprocess').button({
		icons: {
			primary: 'ui-icon-transferthick-e-w'
		}
	});
	$wpu('.wpuclear').button({
		icons: {
			primary: 'ui-icon-cancel'
		}
	});		
	setupAcpPopups();
	
	/**
	 * Delegates actions via a single event listener (to improve performance)
	 * Uses native JS rather than jQuery for the most part in order to keep event actions
	 * as speedy as possible.
	 */
	document.getElementById('wpumapscreen').onclick = function(event) {
		var el = event.target || event.srcElement;
		var elType = el.nodeName.toLowerCase();
		
		if(elType == 'a') {
			
			if(el.className.indexOf('wpuprofilelink') > -1) {
				$wpu.colorbox({
					href: el.href,
					width: '88%', 
					height: '92%', 
					title: (mapProfileTitle == undefined) ? '' : mapProfileTitle,
					iframe: true
				});	
			}
			return false;
			
		}
		
		// now deal with buttons
			
		if((elType != 'span') || (el.className.indexOf('ui-button') == -1)) {
			return false;
		}
		el = el.parentNode;
		if(el.className.indexOf('ui-button-disabled') > -1) {
			return false;
		}

		if( (el.id == undefined) || (el.id == '') ) {
				
			if(el.className.indexOf('wpumapactionedit') > -1) {
				$wpu.colorbox({
					href: el.href,
					width: '88%', 
					height: '92%', 
					title: (mapEditTitle == undefined) ? '' : mapEditTitle,
					iframe: true,
					onClosed: function() {
						wpuShowMapper(false);
					}
				});
				return false;
			}
			
			return false;
		}
		
		// only remaining possibility is a map action button
		wpuProcessMapActionButton(el.id);
		
		return false;
		
	};	
	
	// bind top form changes
	$wpu('#wpumapdisp select').bind('change', function() {
		if(!generatingMapper) {
			wpuShowMapper(true);
		}
	});
	$wpu('#wpumapsearchbox').bind('keyup', function() {
		if(!generatingMapper) {
			var newState = $wpu(this).val();
			if(newState != mapTxtInputState) {
				mapTxtInputState = newState;
				wpuShowMapper(true);
			}
		}
	});
	
	wpuShowMapper(true);
}



var wpuEndPoint;
var wpuNeverEndPoint;
function wpuSetupPermsMapper() {
	
	$wpu('#wputabs').tabs({
		select: function(event, ui) {                   
			window.location.hash = ui.tab.hash;
		},
		show: function(event, ui) {
			jsPlumb.repaintEverything();
		}
    });
	
	jsPlumb.importDefaults({
		DragOptions : { cursor: 'pointer', zIndex:2000 },
		PaintStyle : { strokeStyle:'#666' },
		EndpointStyle : { width:20, height:16, strokeStyle:'#666' },
		Container : $wpu('#wpuplumbcanvas')
	});	
	
	wpuEndPoint = {
		endpoint:['Dot', { radius:15 }],
		paintStyle:{ fillStyle:'#000061' },
		scope:'wpuplumb',
		connectorStyle:{ strokeStyle:'#000061', lineWidth:6 },
		connector: ['Bezier', { curviness:63 } ],
		maxConnections:10,
	};
	wpuNeverEndPoint = {
		endpoint:['Rectangle', { width:15, height: 15 }],
		paintStyle:{ fillStyle:'#dd0000' },
		scope:'wpuplumbnever',
		connectorStyle:{ strokeStyle:'#dd0000', lineWidth:6 },
		connector: ['Bezier', { curviness:63 } ],
		maxConnections:10
	};	

	initPlumbing();	
	
}


function wpuApplyPerms() {
	
	var connections = jsPlumb.getConnections('wpuplumb');
	var nevers = jsPlumb.getConnections('wpuplumbnever');
	var results = [];
	for(var i=0;i<connections.length;i++) {
		results.push(connections[i].sourceId.split(/-/g)[1] + '=' + connections[i].targetId.split(/-/g)[1]);
	}
	var resultsNever = [];
	for(var i=0;i<nevers.length;i++) {
		resultsNever.push(nevers[i].sourceId.split(/-/g)[1] + '=' + nevers[i].targetId.split(/-/g)[1]);
	}	
	
	window.scrollTo(0,0);
	$wpu('#wpu-reload').dialog({
		modal: true,
		title: wpuConnectingText,
		width: 360,
		height: 160,
		draggable: false,
		disabled: true,
		closeOnEscape: false,
		resizable: false,
		show: 'puff'
	});
	$wpu('.ui-dialog-titlebar').hide();
	$wpu('#wpu-desc').html('<strong>' + wpuProcessingText + '</strong><br />' + wpuWaitText);
	
	//TODO: setup error handler here
	
	$wpu.post('admin.php?page=wpu-user-mapper', 'wpusetperms=' + makeMsgSafe(results.join(',')) + '&wpusetnevers=' + makeMsgSafe(resultsNever.join(',')) + '&_ajax_nonce=' + firstMapActionNonce, function(response) { 
		response = $wpu.trim(response);
		var responseMsg;
		if(response.length >= 2) responseMsg = response.substr(0, 2);
		if(responseMsg =='OK') {
			// the settings were applied
		}
				
		$wpu('#wpu-reload').dialog('destroy');
		window.location.reload();
		
	});
	return false;
	;
	
	return false;
}

function wpuClearPerms() {
	window.scrollTo(0,0);
	$wpu('#wpu-desc').html('<strong>' + wpuClearingText + '</strong><br />Please wait...');
	$wpu("#wpu-reload").dialog({
		modal: true,
		title: 'Resetting...',
		width: 360,
		height: 160,
		draggable: false,
		disabled: true,
		closeOnEscape: false,
		resizable: false
	});
	$wpu('.ui-dialog-titlebar').hide();
	window.location.reload(1);
}



/**
 * Sends the filter fields to the back-end, processes the returned user mapper html, and
 * sets up all contained buttons/fields/etc.
 */
var selContainsCurrUser = false;
var generatingMapper = false;
var mapTxtInputState = '';

function wpuShowMapper(repaginate) {
	
	if(generatingMapper) {
		return;
	}
	
	generatingMapper = true;
	mapTxtInputState = $wpu('#wpumapsearchbox').val();
	if(repaginate == true) {
		$wpu('#wpufirstitem').val(0);
	}
	
	$wpu('#wpumapscreen').html('<div class="wpuloading"><p>' + wpuLoading + '</p><img src="' + imgLdg + '" /></div>');
	var formData = $wpu('#wpumapdisp').serialize() + '&wpumapload=1&_ajax_nonce=' + mapNonce;
	
	// set up ajax error handler
	$wpu(document).ajaxError(function(e, xhr, settings, exception) {
		if(exception == undefined) {
			var exception = 'Server ' + xhr.status + ' error. Please check your server logs for more information.';
		}
		$wpu('#wpumapscreen').html(errMsg = settings.url + ' returned: ' + exception);
	});

	$wpu.post('admin.php?page=wpu-user-mapper', formData, function(response, status, xhr) {

		// Set up the page when a user mapper response has been received
		if($wpu('#wpumapside').val() == 'phpbb') {
			leftSide = phpbbText;
			rightSide = wpText;
		} else {
			leftSide = wpText;
			rightSide = phpbbText; 
		}
		var pag = $wpu(response).find('pagination').text();
		var bulk = $wpu(response).find('bulk').text();
		$wpu('#wpumappaginate1').html(pag);
		$wpu('#wpumappaginate2').html(bulk + pag);
		// wrap content in an additional div to speed DOM insertion
		var content = Base64.decode($wpu(response).find('mapcontent').text());
		$wpu('#wpuoffscreen').html(content);

		
		var sMB = setTimeout('setupMapButtons()', 200);
		var sMV = setTimeout('makeMapVisible()', 1000);

		wpuMapClearAll();
		wpuSuggCache = {};
		wpuTypedMatches = new Array();
		
		// set up autocompletes
		$wpu('#wpumaptable input.wpuusrtyped').each(function() {
			$wpu(this).autocomplete({
				minLength: 2,
				source: function(request, response) {
					var findIn = ($wpu('#wpumapside').val() == 'phpbb')  ? 'wp' : 'phpbb';
					if ( request.term in wpuSuggCache ) {
						response(wpuSuggCache[request.term]);
						return;
					}
					$wpu.ajax({
						url: 'admin.php?page=wpu-user-mapper',
						dataType: 'json',
						data: 'term=' + request.term + '&_ajax_nonce=' + autofillNonce + '&pkg=' + findIn,
						success: function(recv) {
							wpuSuggCache[request.term] = recv;
							response(recv);
						}
					});
				},
				select: function(event, ui) {
					var buttonID = $wpu(this).attr('id').replace('wpumapsearch', 'wpumapfrom');
					var userID = $wpu(this).attr('id').split(/-/ig)[1];
					var userName = $wpu('#wpuuser' + userID + ' .wpuprofilelink').text();
					
					if(ui.item.statuscode == 1) {
						
						$wpu(this).val(ui.item.label);
						
						var details = {
							'username': userName,
							'touserid': ui.item.value,
							'tousername': ui.item.label,
							'toemail': ui.item.desc
						}
						wpuTypedMatches[userID] = details;
						
						$wpu('#wpuavatartyped' + userID).html(ui.item.avatar);
						
						$wpu('#' + buttonID).bind('click', function() {
							return wpuMapIntegrateTyped(this);
						});
						$wpu('#' + buttonID).button('enable');
					} else {
						$wpu('#' + buttonID).unbind('click');
						$wpu('#' + buttonID).button('disable');
						$wpu('#wpuavatartyped' + userID).html('');
					}
					return false;
				},
				focus: function(event, ui) {
					if(ui.item.statuscode == 1) {
						$wpu(this).val(ui.item.label);
					}
					return false;
				}
			})
			.data('autocomplete')._renderItem = function(ul, item) {
				var statusColor = (item.statuscode == 0) ? 'red' : 'green';
				return $wpu('<li></li>')
					.data('item.autocomplete', item )
					.append( '<a><small><strong>' + item.label + '</strong><br />' + item.desc + '<br /><em style="color: ' + statusColor + '">' + item.status + '</em></small></a>')
					.appendTo( ul );
			};
		});
		
		currAction = 0;
		
		generatingMapper = false;
		if($wpu('#wpumapsearchbox').val() != mapTxtInputState) {
			clearTimeout(sMB);
			clearTimeout(sMV);
			wpuShowMapper(true);
		}
		
	});
	

}

function makeMapVisible() {
	$wpu('#wpumapscreen').html('');
	$wpu('#wpumapscreen').append($wpu('#wpumaptable'));
	
}


function wpuProcessMapActionButton(btnID) {

		var actionDetails = btnID.split(/-/g);
		
		if(actionDetails.length < 2) {
			return false;
		}
		
		var intUsrID, intUsrName;
		var mapAction = actionDetails[1];
		var pkg = actionDetails[2];
		var altPkg = (pkg == 'wp') ? 'phpbb' : 'wp';
		var usrID = actionDetails[3];
		
		var usrName = $wpu('#wpu' + pkg + 'login' + usrID).text();
		
		switch(mapAction) {
			case 'del':
				return wpuMapDel(usrID, pkg, usrName);
				break;
			
			case 'delboth':
				intUsrID = actionDetails[4];
				intUsrName = $wpu('#wpu' + altPkg + 'login' + intUsrID).text();
				return wpuMapDelBoth(usrID, intUsrID, usrName, intUsrName);
				break;
				
			case 'create':
				return wpuMapCreate(usrID, altPkg, usrName);
				break;
				
			case 'break':
				intUsrID = actionDetails[4];
				intUsrName = $wpu('#wpu' + altPkg + 'login' + intUsrID).text();
				return wpuMapBreak(usrID, intUsrID, usrName, intUsrName);
				break;
				
			case 'sync':
				intUsrID = actionDetails[4];
				intUsrName = $wpu('#wpu' + altPkg + 'login' + intUsrID).text();
				return wpuMapSync(usrID, intUsrID, usrName, intUsrName);
				break;
		}
		
		return false;	
	
}




/**
 * Progressively enhances links into buttons
 */

function setupMapButtons() {
	$wpu('#wpumaptable a.wpumapactionsync').button({ 
		icons: {primary:'ui-icon-refresh'},
		text: false
	});
	$wpu('#wpumaptable a.wpumapactionbrk').button({ 
		icons: {primary:'ui-icon-scissors'},
		text: false
	});
	$wpu('#wpumaptable a.wpumapactioncreate').button({ 
		icons: {primary: 'ui-icon-plusthick'},
		text: false
	});		
	$wpu('#wpumaptable a.wpumapactiondel').button({ 
		icons: {primary:'ui-icon-trash'},
		text: false
	});
	$wpu('#wpumaptable  a.wpumapactionlnk').button({ 
		icons: {primary:'ui-icon-link'},
		text: false
	});
	$wpu('#wpumaptable a.wpumapactionlnktyped').button({ 
		icons: {primary:'ui-icon-link'},
		text: false,
		disabled: true
	});
	$wpu('#wpumaptable a.wpumapactionedit').button({ 
		icons: {primary:'ui-icon-gear'},
		text: false
	});	

	//$wpu('.wpubuttonset').buttonset();
	
}

/**
 * Process a bulk action
 */
function wpuMapBulkActions() {
	var bulkType = $wpu('#wpuquicksel').val();
	
	switch(bulkType) {
		
		case 'del':
			$wpu('#wpumaptable .wpuintegnot a.wpumapactiondel').each(function() {
				if(!$wpu(this).button('widget').hasClass('ui-button-disabled')) {
					wpuProcessMapActionButton($wpu(this).attr('id'));
				}
			});
		break;
		
		
		case 'create':
			$wpu('#wpumaptable .wpuintegnot a.wpumapactioncreate').each(function() {
				if(!$wpu(this).button('widget').hasClass('ui-button-disabled')) {
					wpuProcessMapActionButton($wpu(this).attr('id'));
				}
			});		
		break;
		
		case 'break':
			$wpu('#wpumaptable .wpuintegok a.wpumapactionbrk').each(function() {
				if(!$wpu(this).button('widget').hasClass('ui-button-disabled')) {
					wpuProcessMapActionButton($wpu(this).attr('id'));
				}
			});		
		break;
		
		case 'sync':
			$wpu('#wpumaptable .wpuintegok a.wpumapactionsync').each(function() {
				if(!$wpu(this).button('widget').hasClass('ui-button-disabled')) {
					wpuProcessMapActionButton($wpu(this).attr('id'));
				}
			});	
		break;
	}
	
	
	
	return false;
	
}



/**
 * Sets up popup "Colourboxes" for phpBB ACP administration from the permissions tab
 */
function setupAcpPopups() {
	$wpu('#wpumapscreen a.wpuacppopup, #wpumaptab-perms a.wpuacppopup').colorbox({
		width: '88%', 
		height: '92%', 
		title: (acpPopupTitle == undefined) ? '' : acpPopupTitle,
		iframe: true,
		onClosed: function() {
			window.scrollTo(0,0);
			$wpu('#wpu-desc').html('<strong>' + wpuReloading + '</strong><br />Please wait...');
			$wpu("#wpu-reload").dialog({
				modal: true,
				title: wpuReloading,
				width: 360,
				height: 160,
				draggable: false,
				disabled: true,
				closeOnEscape: false,
				resizable: false
			});
			$wpu('.ui-dialog-titlebar').hide();
			window.location.reload(1);
		}
	});
}


/**
 * Displays the actions panel whenever an action is added
 */
function showPanel() {
	if(!panelOpen) {
		$wpu('#wpumapcontainer').splitter({
			type: 'v',
			sizeRight: 225
		});
		$wpu('#wpumapscreen').css('overflow-y', 'auto');
		$wpu('#wpumappanel').show('slide', {
			direction: 'right'
		});
		$wpu('#wpumappanel h3').prepend('<span class="ui-icon ui-icon-triangle-1-e"></span>');
		$wpu('#wpumappanel h3 .ui-icon').click(function() {
			togglePanel($wpu(this));
		});
		
		panelOpen = true;
	}
	panelHidden = true;
	togglePanel($wpu('#wpumappanel h3 .ui-icon'));
}

/**
* This doesn't really close the panel as the splitter doesn't have a destroy method
* instead we just set its width to zero
*/
function closePanel() {
	if(panelOpen) {
		$wpu("#wpumapcontainer").trigger("resize", [ $wpu("#wpumapcontainer").width() ]);
		$wpu("#wpumapcontainer .vsplitbar").css('display', 'none');
		panelHidden = true;
	}
}
/**
 * Toggles the actions panel between fully open and "almost hidden"
 */
function togglePanel(el) {
	if(!panelHidden) {
		el
			.removeClass('ui-icon-triangle-1-e')
			.addClass('ui-icon-triangle-1-w');
		 $wpu("#wpumapcontainer").trigger("resize", [ $wpu("#wpumapcontainer").width() - 20 ]);
		panelHidden = true;
	} else {
		el
			.removeClass('ui-icon-triangle-1-w')
			.addClass('ui-icon-triangle-1-e')
			$wpu("#wpumapcontainer .vsplitbar").css('display', 'block');
		$wpu("#wpumapcontainer").trigger("resize", [ $wpu("#wpumapcontainer").width() - 225 ]);
		panelHidden = false;
	}
}


/**
 * Converts an autocompleted user selection to the "integrate to this user" action
 */
function wpuMapIntegrateTyped(el) {
	if($wpu(el).button("widget").hasClass('ui-state-disabled')) {
		return false;
	}
		
	var userID = $wpu(el).attr('id').split(/-/ig)[1];
	
	if(userID in wpuTypedMatches) {
		return wpuMapIntegrate(el, userID, wpuTypedMatches[userID].touserid, wpuTypedMatches[userID].username, wpuTypedMatches[userID].tousername, '', wpuTypedMatches[userID].toemail);
	}
	return false;
}

/**
 * Generates an "integrate to this user" action.
 * Called directly if a suggestion is chosen, or called via wpuMapIntegrateTypes
 * if they used the autocomplete
 */
function wpuMapIntegrate(el, userID, toUserID, userName, toUserName, userEmail, toUserEmail) {
	if($wpu(el).button("widget").hasClass('ui-state-disabled')) {
		return false;
	}
	showPanel();
	var actionType = actionIntegrate;
	var actionDets = actionIntegrateDets.replace('%1$s', leftSide)
		.replace ('%2$s','<em>' + userName + '</em>')
		.replace('%3$s', rightSide)
		.replace ('%4$s', '<em>' + toUserName + '</em>');
	var actionsIndex= wpuMapActions.length;
	var markup = '<li id="wpumapaction' + actionsIndex + '"><strong>' + actionType + '</strong> ' + actionDets + '</li>';
	
	
	var pckg = $wpu('#wpumapside').val();
	if( ((pckg == 'wp') && ((userID == currWpUser) || (toUserID == currPhpbbUser))) ||
		 ((pckg == 'phpbb') && ((userID == currPhpbbUser) || (toUserID == currWpUser))) ) {
			 selContainsCurrUser = true;
	}
	
	wpuMapActions.push({
			'type': 'integrate',
			'userid': userID,
			'intuserid': toUserID,
			'desc': actionType + ' ' + actionDets,
			'package': pckg
		});
		$wpu('#wpupanelactionlist').append(markup);

		$wpu('#wpuuser' + userID).find('a.ui-button:not(.wpumapactionedit)').button('disable');	
		
		if($wpu(el).attr('id').indexOf('wpumapfrom') > -1) {
			$wpu('#' + $wpu(el).attr('id').replace('wpumapfrom', 'wpumapsearch')).attr('disabled', 'disabled');
			$wpu(el).unbind('click');
		}
	
	 return false;
}


/**
 * Generates a "Sync user profiles" action
 */
function wpuMapSync(userID, intUserID, userName, intUserName) {

	showPanel();
	var actionType = actionSync;
	var actionDets = actionSyncDets.replace('%1$s', '<em>' + userName + '</em>')
			.replace('%2$s', '<em>' + intUserName + '</em>');
	var actionsIndex= wpuMapActions.length;
	var markup = '<li id="wpumapaction' + actionsIndex + '"><strong>' + actionType + '</strong> ' + actionDets + '</li>';

	var pckg = $wpu('#wpumapside').val();
	if( ((pckg == 'wp') && ((userID == currWpUser) || (intUserID == currPhpbbUser))) ||
		 ((pckg == 'phpbb') && ((userID == currPhpbbUser) || (intUserID == currWpUser))) ) {
			 selContainsCurrUser = true;
	}	

	wpuMapActions.push({
		'type': 'sync',
		'userid': userID,
		'intuserid': intUserID,
		'desc': actionType + ' ' + actionDets,
		'package': pckg
	});	
	$wpu('#wpupanelactionlist').append(markup);

	$wpu('#wpuuser' + userID).find('a.ui-button:not(.wpumapactionedit)').button('disable');
			
	return false;
}	
	
/**
 * Generates a "break integration" action
 */
function wpuMapBreak(userID, intUserID, userName, intUserName) {

	showPanel();
	var actionType = actionBreak;
	var actionDets = actionBreakDets.replace('%1$s', '<em>' + userName + '</em>')
			.replace('%2$s', '<em>' + intUserName + '</em>');
	var actionsIndex= wpuMapActions.length;
	var markup = '<li id="wpumapaction' + actionsIndex + '"><strong>' + actionType + '</strong> ' + actionDets + '</li>';

	var pckg = $wpu('#wpumapside').val();
	if( ((pckg == 'wp') && ((userID == currWpUser) || (intUserID == currPhpbbUser))) ||
		 ((pckg == 'phpbb') && ((userID == currPhpbbUser) || (intUserID == currWpUser))) ) {
			 selContainsCurrUser = true;
	}

	
	wpuMapActions.push({
		'type': 'break',
		'userid': userID,
		'intuserid': intUserID,
		'desc': actionType + ' ' + actionDets,
		'package': pckg
	});
	$wpu('#wpupanelactionlist').append(markup);

	$wpu('#wpuuser' + userID).find('a.ui-button:not(.wpumapactionedit)').button('disable');
			
	return false;
}

/**
 * Generates a "delete from both sides" action
 */
function wpuMapDelBoth(userID, intUserID, userName, intUserName) {
	
	showPanel();
	var actionType = actionDelBoth;
	var actionDets = actionDelBothDets
		.replace('%1$s', '<em>' + userName + '</em>')
		.replace ('%2$s', leftSide)
		.replace('%3$s', '<em>' + intUserName + '</em>')
		.replace ('%4$s', rightSide);
	var actionsIndex= wpuMapActions.length;
	var markup = '<li id="wpumapaction' + actionsIndex + '"><strong>' + actionType + '</strong> ' + actionDets + '</li>';


	var pckg = $wpu('#wpumapside').val();
	if( ((pckg == 'wp') && ((userID == currWpUser) || (intUserID == currPhpbbUser))) ||
		 ((pckg == 'phpbb') && ((userID == currPhpbbUser) || (intUserID == currWpUser))) ) {
			 selContainsCurrUser = true;
	}
	
	
	
	wpuMapActions.push({
		'type': 'delboth',
		'userid': userID,
		'intuserid': intUserID,
		'desc': actionType + ' ' + actionDets,
		'package': pckg
	});
	$wpu('#wpupanelactionlist').append(markup);
	$wpu('#wpuuser' + userID).find('a.ui-button:not(.wpumapactionedit)').button('disable');
	
	return false;
}

/**
 * Generates a "delete user" action
 */
function wpuMapDel(userID, pckg, userName) {
	
	var txtPackage = (pckg == 'phpbb') ? phpbbText : wpText;
	showPanel();
	var actionType = actionDel;
	var actionDets = actionDelDets
		.replace('%1$s', '<em>' + userName + '</em>')
		.replace ('%2$s', txtPackage);
	var actionsIndex= wpuMapActions.length;
	var markup = '<li id="wpumapaction' + actionsIndex + '"><strong>' + actionType + '</strong> ' + actionDets + '</li>';

	if( ((pckg == 'wp') && (userID == currWpUser)) || ((pckg == 'phpbb') && (userID == currPhpbbUser)) ) {
			 selContainsCurrUser = true;
	}


	wpuMapActions.push({
		'type': 'del',
		'userid': userID,
		'desc': actionType + ' ' + actionDets,
		'package': pckg
	});
	$wpu('#wpupanelactionlist').append(markup);
	
	// disable delboth links and clicked delete link, leave the other one
	var altPckg =  (pckg == 'phpbb') ? 'wp' : 'phpbb';
	$wpu('#wpuuser' + userID).find('a.ui-button:not(.wpumapactionedit)').button('disable');
	$wpu('#wpuuser' + userID).find('div.wpu' + altPckg + 'user a.wpumapactiondel').button('enable');
	
	$wpu('#wpuavatartyped' + userID).html('');
	$wpu('#wpumapsearch-' + userID).attr('disabled', 'disabled');
	
	return false;
}


/**
 * Generates a "Create user" action
 */
function wpuMapCreate(userID, altPckg, userName) {
	
	var txtAltPackage = (altPckg == 'phpbb') ? phpbbText : wpText;
	showPanel();
	var actionType = actionCreate;
	var actionDets = actionCreateDets
		.replace('%1$s', '<em>' + userName + '</em>')
		.replace ('%2$s', txtAltPackage);
	var actionsIndex= wpuMapActions.length;
	var markup = '<li id="wpumapaction' + actionsIndex + '"><strong>' + actionType + '</strong> ' + actionDets + '</li>';
	
	
	if( ((altPckg == 'wp') && (userID == currPhpbbUser)) ||
		 ((altPckg == 'phpbb') && (userID == currWpUser)) ) {
			 selContainsCurrUser = true;
	}
	
	
	wpuMapActions.push({
		'type': 'createin',
		'userid': userID,
		'desc': actionType + ' ' + actionDets,
		'package': altPckg
	});
	$wpu('#wpupanelactionlist').append(markup);
	
	$wpu('#wpuuser' + userID).find('a.ui-button:not(.wpumapactionedit)').button('disable');
	$wpu('#wpuavatartyped' + userID).html('');
	$wpu('#wpumapsearch-' + userID).attr('disabled', 'disabled');
	
	return false;
}	


/**
 * Clears all actions from the actions panel and closes it, and resets all button states
 */
function wpuMapClearAll() {
	wpuMapActions = new Array();
	$wpu('#wpupanelactionlist').html('');
	closePanel();
	$wpu('#wpumapscreen').find(
		'a.wpumapactionbrk, ' + 
		'a.wpumapactiondel, ' +
		'a.wpumapactionlnk, ' +
		'a.wpumapactioncreate, ' +
		'a.wpumapactionsync'
	).button('enable');
	$wpu('#wpumapscreen a.wpumapactionlnktyped').button('disable');
	$wpu('#wpumapscreen a.wpuusrtyped').val('');
	$wpu('#wpumapscreen input.wpuusrtyped').removeAttr('disabled');
	$wpu('#wpumapscreen div.wpuavatartyped').html('');
	return false;
}

/**
* Handle user mapper change page request
*/
function wpuMapPaginate(el) {
	var numStart = (el.href.indexOf('start=') > -1) ? el.href.split('start=')[1] : 0
	$wpu('#wpufirstitem').val(numStart);
	wpuShowMapper(false);
	return false;
}

/**
 * Process the user mapper acitons
 */
var numActions;
var currAction = 0;
function wpuProcess() {
	window.scrollTo(0,0);
	$wpu('#wpu-reload').dialog({
		modal: true,
		title: 'Applying actions...',
		width: 360,
		height: 220,
		draggable: false,
		disabled: true,
		closeOnEscape: false,
		resizable: false,
		show: 'puff',
		buttons: {
			'Cancel remaining actions': function() {
				wpuProcessFinished();
			}
		}
	});
	$wpu('#wpuldgimg').show();
	numActions = wpuMapActions.length;
	
	wpuNextAction(firstMapActionNonce);
	
	return false;
}

/**
 * Get the next mapper action in the queue
 */
function wpuNextAction(nonce) {
	el = $wpu('#wpupanelactionlist li:first');
	if(el.length) {
		wpuProcessNext(el,nonce);
	} else {
		wpuProcessFinished();
	}
}

/**
 * Process the next mapper action in the queue
 */	
function wpuProcessNext(el, nonce) {
	var mapAction, actionData, postString;
	
	var currDesc = '';
	var nextMapActionNonce = 0;
	
	currAction++;
	mapAction = parseInt(el.attr('id').replace('wpumapaction', ''));
	$wpu(el).remove();
		
	currDesc = wpuMapActions[mapAction]['desc'];
	$wpu('#wpu-desc').html('<strong>Processing action ' + (currAction + 1) + ' of ' + (numActions + 1) + '</strong><br />' + currDesc);
	
	$wpu(document).ajaxError(function(e, xhr, settings, exception) {
		if(exception == undefined) {
			var exception = 'Server ' + xhr.status + ' error. Please check your server logs for more information.';
		}
		$wpu('#wpu-desc').html(errMsg = 'An error occurred. The remaining actions have not been processed. Error: ' + exception);
	});
		
	// fashion POST data from wpuMapActions
	actionData = new Array();
	for(actionKey in wpuMapActions[mapAction]) {
		if(actionKey != 'desc') {
			actionData.push(actionKey + '=' + wpuMapActions[mapAction][actionKey]);
		}
	}
	postString = actionData.join('&');
	postString += '&wpumapaction=1&_ajax_nonce=' + nonce;
	
	$wpu.post('admin.php?page=wpu-user-mapper', postString, function(response) {
		var actionStatus = $wpu(response).find('status').text();
		var actionDetails = $wpu(response).find('details').text();
		var nextNonce = $wpu(response).find('nonce').text();
		actionStatus = $wpu.trim(actionStatus);
		var actionStatusMsg
		if(actionStatus.length >= 2)actionStatusMsg = actionStatus.substr(0, 2);
		if(actionStatusMsg=='OK') {
			wpuNextAction(nextNonce);
			
		} else {
			// handle error
			$wpu('#wpu-reload').dialog('destroy');
			$wpu('#wpu-desc').html(errMsg = 'An error occurred on the server. The remaining actions have not been processed. Error: ' + actionDetails);
			$wpu('#wpu-reload').dialog({
				modal: true,
				title: 'Error',
				width: 360,
				height: 220,
				draggable: false,
				resizable: false,
				show: 'puff',
				buttons: {
					'OK': function() {
						wpuProcessFinished();
					}
				}
			});
			$wpu('#wpuldgimg').hide();
		}
			
	});				

	return false;
}


/**
 * Finish processing mapping actions
 * Reload the page if the current user was affected
 */		
function wpuProcessFinished() {
	$wpu('#wpu-reload').dialog('destroy');
	if(selContainsCurrUser) {
		window.location.reload();
	} else {
		wpuShowMapper(false);
	}
}



/**
 *	Deferred script loading -- called twice, once on jquery document.ready, and once by timeout. 
 *  This prevents other plugin's scripts that die on the ready event from killing ours
 */
var wpuHasInited = false;
function wpu_hardened_init() {
	if(!wpuHasInited) {
		wpuHasInited = true;
		wpu_hardened_init_tail();
	}
}
	
/**
 * Base64 encode/decode for passing messages
 */
var Base64 = {
	_keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
	encode : function (input) {
		var output = "";
		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		var i = 0;

		input = Base64._utf8_encode(input);
		while (i < input.length) {
			chr1 = input.charCodeAt(i++);
			chr2 = input.charCodeAt(i++);
			chr3 = input.charCodeAt(i++);

			enc1 = chr1 >> 2;
			enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
			enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
			enc4 = chr3 & 63;

			if (isNaN(chr2)) {
				enc3 = enc4 = 64;
			} else if (isNaN(chr3)) {
				enc4 = 64;
			}

			output = output +
			this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
			this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

		}

		return output;
	},
	decode : function (input) {
		var output = "";
		var chr1, chr2, chr3;
		var enc1, enc2, enc3, enc4;
		var i = 0;

		input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

		while (i < input.length) {

			enc1 = this._keyStr.indexOf(input.charAt(i++));
			enc2 = this._keyStr.indexOf(input.charAt(i++));
			enc3 = this._keyStr.indexOf(input.charAt(i++));
			enc4 = this._keyStr.indexOf(input.charAt(i++));

			chr1 = (enc1 << 2) | (enc2 >> 4);
			chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
			chr3 = ((enc3 & 3) << 6) | enc4;

			output = output + String.fromCharCode(chr1);

			if (enc3 != 64) {
				output = output + String.fromCharCode(chr2);
			}
			if (enc4 != 64) {
				output = output + String.fromCharCode(chr3);
			}

		}

		output = Base64._utf8_decode(output);

		return output;

	},
	_utf8_encode : function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";

		for (var n = 0; n < string.length; n++) {

			var c = string.charCodeAt(n);

			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}
		}
		return utftext;
	},

	// private method for UTF-8 decoding
	_utf8_decode : function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;
		while ( i < utftext.length ) {
			c = utftext.charCodeAt(i);
			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}
		}
		return string;
	}
}
