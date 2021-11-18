/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ 4184:
/***/ (function(module, exports) {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
  Copyright (c) 2018 Jed Watson.
  Licensed under the MIT License (MIT), see
  http://jedwatson.github.io/classnames
*/
/* global define */

(function () {
	'use strict';

	var hasOwn = {}.hasOwnProperty;

	function classNames() {
		var classes = [];

		for (var i = 0; i < arguments.length; i++) {
			var arg = arguments[i];
			if (!arg) continue;

			var argType = typeof arg;

			if (argType === 'string' || argType === 'number') {
				classes.push(arg);
			} else if (Array.isArray(arg)) {
				if (arg.length) {
					var inner = classNames.apply(null, arg);
					if (inner) {
						classes.push(inner);
					}
				}
			} else if (argType === 'object') {
				if (arg.toString === Object.prototype.toString) {
					for (var key in arg) {
						if (hasOwn.call(arg, key) && arg[key]) {
							classes.push(key);
						}
					}
				} else {
					classes.push(arg.toString());
				}
			}
		}

		return classes.join(' ');
	}

	if ( true && module.exports) {
		classNames.default = classNames;
		module.exports = classNames;
	} else if (true) {
		// register as 'classnames', consistent with npm package name
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function () {
			return classNames;
		}).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {}
}());


/***/ }),

/***/ 3729:
/***/ (function(module, exports) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;//download.js v4.2, by dandavis; 2008-2016. [MIT] see http://danml.com/download.html for tests/usage
// v1 landed a FF+Chrome compat way of downloading strings to local un-named files, upgraded to use a hidden frame and optional mime
// v2 added named files via a[download], msSaveBlob, IE (10+) support, and window.URL support for larger+faster saves than dataURLs
// v3 added dataURL and Blob Input, bind-toggle arity, and legacy dataURL fallback was improved with force-download mime and base64 support. 3.1 improved safari handling.
// v4 adds AMD/UMD, commonJS, and plain browser support
// v4.1 adds url download capability via solo URL argument (same domain/CORS only)
// v4.2 adds semantic variable names, long (over 2MB) dataURL support, and hidden by default temp anchors
// https://github.com/rndme/download

(function (root, factory) {
	if (true) {
		// AMD. Register as an anonymous module.
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {}
}(this, function () {

	return function download(data, strFileName, strMimeType) {

		var self = window, // this script is only for browsers anyway...
			defaultMime = "application/octet-stream", // this default mime also triggers iframe downloads
			mimeType = strMimeType || defaultMime,
			payload = data,
			url = !strFileName && !strMimeType && payload,
			anchor = document.createElement("a"),
			toString = function(a){return String(a);},
			myBlob = (self.Blob || self.MozBlob || self.WebKitBlob || toString),
			fileName = strFileName || "download",
			blob,
			reader;
			myBlob= myBlob.call ? myBlob.bind(self) : Blob ;
	  
		if(String(this)==="true"){ //reverse arguments, allowing download.bind(true, "text/xml", "export.xml") to act as a callback
			payload=[payload, mimeType];
			mimeType=payload[0];
			payload=payload[1];
		}


		if(url && url.length< 2048){ // if no filename and no mime, assume a url was passed as the only argument
			fileName = url.split("/").pop().split("?")[0];
			anchor.href = url; // assign href prop to temp anchor
		  	if(anchor.href.indexOf(url) !== -1){ // if the browser determines that it's a potentially valid url path:
        		var ajax=new XMLHttpRequest();
        		ajax.open( "GET", url, true);
        		ajax.responseType = 'blob';
        		ajax.onload= function(e){ 
				  download(e.target.response, fileName, defaultMime);
				};
        		setTimeout(function(){ ajax.send();}, 0); // allows setting custom ajax headers using the return:
			    return ajax;
			} // end if valid url?
		} // end if url?


		//go ahead and download dataURLs right away
		if(/^data:([\w+-]+\/[\w+.-]+)?[,;]/.test(payload)){
		
			if(payload.length > (1024*1024*1.999) && myBlob !== toString ){
				payload=dataUrlToBlob(payload);
				mimeType=payload.type || defaultMime;
			}else{			
				return navigator.msSaveBlob ?  // IE10 can't do a[download], only Blobs:
					navigator.msSaveBlob(dataUrlToBlob(payload), fileName) :
					saver(payload) ; // everyone else can save dataURLs un-processed
			}
			
		}else{//not data url, is it a string with special needs?
			if(/([\x80-\xff])/.test(payload)){			  
				var i=0, tempUiArr= new Uint8Array(payload.length), mx=tempUiArr.length;
				for(i;i<mx;++i) tempUiArr[i]= payload.charCodeAt(i);
			 	payload=new myBlob([tempUiArr], {type: mimeType});
			}		  
		}
		blob = payload instanceof myBlob ?
			payload :
			new myBlob([payload], {type: mimeType}) ;


		function dataUrlToBlob(strUrl) {
			var parts= strUrl.split(/[:;,]/),
			type= parts[1],
			decoder= parts[2] == "base64" ? atob : decodeURIComponent,
			binData= decoder( parts.pop() ),
			mx= binData.length,
			i= 0,
			uiArr= new Uint8Array(mx);

			for(i;i<mx;++i) uiArr[i]= binData.charCodeAt(i);

			return new myBlob([uiArr], {type: type});
		 }

		function saver(url, winMode){

			if ('download' in anchor) { //html5 A[download]
				anchor.href = url;
				anchor.setAttribute("download", fileName);
				anchor.className = "download-js-link";
				anchor.innerHTML = "downloading...";
				anchor.style.display = "none";
				document.body.appendChild(anchor);
				setTimeout(function() {
					anchor.click();
					document.body.removeChild(anchor);
					if(winMode===true){setTimeout(function(){ self.URL.revokeObjectURL(anchor.href);}, 250 );}
				}, 66);
				return true;
			}

			// handle non-a[download] safari as best we can:
			if(/(Version)\/(\d+)\.(\d+)(?:\.(\d+))?.*Safari\//.test(navigator.userAgent)) {
				if(/^data:/.test(url))	url="data:"+url.replace(/^data:([\w\/\-\+]+)/, defaultMime);
				if(!window.open(url)){ // popup blocked, offer direct download:
					if(confirm("Displaying New Document\n\nUse Save As... to download, then click back to return to this page.")){ location.href=url; }
				}
				return true;
			}

			//do iframe dataURL download (old ch+FF):
			var f = document.createElement("iframe");
			document.body.appendChild(f);

			if(!winMode && /^data:/.test(url)){ // force a mime that will download:
				url="data:"+url.replace(/^data:([\w\/\-\+]+)/, defaultMime);
			}
			f.src=url;
			setTimeout(function(){ document.body.removeChild(f); }, 333);

		}//end saver




		if (navigator.msSaveBlob) { // IE10+ : (has Blob, but not a[download] or URL)
			return navigator.msSaveBlob(blob, fileName);
		}

		if(self.URL){ // simple fast and modern way using Blob and URL:
			saver(self.URL.createObjectURL(blob), true);
		}else{
			// handle non-Blob()+non-URL browsers:
			if(typeof blob === "string" || blob.constructor===toString ){
				try{
					return saver( "data:" +  mimeType   + ";base64,"  +  self.btoa(blob)  );
				}catch(y){
					return saver( "data:" +  mimeType   + "," + encodeURIComponent(blob)  );
				}
			}

			// Blob but not URL support:
			reader=new FileReader();
			reader.onload=function(e){
				saver(this.result);
			};
			reader.readAsDataURL(blob);
		}
		return true;
	}; /* end download() */
}));


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
!function() {
"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXPORTS
__webpack_require__.d(__webpack_exports__, {
  "PluginMoreMenuItem": function() { return /* reexport */ plugin_more_menu_item; },
  "PluginSidebar": function() { return /* reexport */ PluginSidebarEditSite; },
  "PluginSidebarMoreMenuItem": function() { return /* reexport */ PluginSidebarMoreMenuItem; },
  "__experimentalMainDashboardButton": function() { return /* reexport */ main_dashboard_button; },
  "__experimentalNavigationToggle": function() { return /* reexport */ navigation_toggle; },
  "initialize": function() { return /* binding */ initialize; },
  "reinitializeEditor": function() { return /* binding */ reinitializeEditor; }
});

// NAMESPACE OBJECT: ./packages/interface/build-module/store/actions.js
var actions_namespaceObject = {};
__webpack_require__.r(actions_namespaceObject);
__webpack_require__.d(actions_namespaceObject, {
  "disableComplementaryArea": function() { return disableComplementaryArea; },
  "enableComplementaryArea": function() { return enableComplementaryArea; },
  "pinItem": function() { return pinItem; },
  "setFeatureDefaults": function() { return setFeatureDefaults; },
  "setFeatureValue": function() { return setFeatureValue; },
  "toggleFeature": function() { return toggleFeature; },
  "unpinItem": function() { return unpinItem; }
});

// NAMESPACE OBJECT: ./packages/interface/build-module/store/selectors.js
var selectors_namespaceObject = {};
__webpack_require__.r(selectors_namespaceObject);
__webpack_require__.d(selectors_namespaceObject, {
  "getActiveComplementaryArea": function() { return getActiveComplementaryArea; },
  "isFeatureActive": function() { return isFeatureActive; },
  "isItemPinned": function() { return isItemPinned; }
});

// NAMESPACE OBJECT: ./packages/edit-site/build-module/store/actions.js
var store_actions_namespaceObject = {};
__webpack_require__.r(store_actions_namespaceObject);
__webpack_require__.d(store_actions_namespaceObject, {
  "__experimentalSetPreviewDeviceType": function() { return __experimentalSetPreviewDeviceType; },
  "addTemplate": function() { return addTemplate; },
  "closeGeneralSidebar": function() { return closeGeneralSidebar; },
  "goBack": function() { return goBack; },
  "openGeneralSidebar": function() { return openGeneralSidebar; },
  "openNavigationPanelToMenu": function() { return openNavigationPanelToMenu; },
  "pushTemplatePart": function() { return pushTemplatePart; },
  "removeTemplate": function() { return removeTemplate; },
  "revertTemplate": function() { return revertTemplate; },
  "setHomeTemplateId": function() { return setHomeTemplateId; },
  "setIsInserterOpened": function() { return setIsInserterOpened; },
  "setIsListViewOpened": function() { return setIsListViewOpened; },
  "setIsNavigationPanelOpened": function() { return setIsNavigationPanelOpened; },
  "setNavigationPanelActiveMenu": function() { return setNavigationPanelActiveMenu; },
  "setPage": function() { return setPage; },
  "setTemplate": function() { return setTemplate; },
  "setTemplatePart": function() { return setTemplatePart; },
  "showHomepage": function() { return showHomepage; },
  "toggleFeature": function() { return actions_toggleFeature; },
  "updateSettings": function() { return updateSettings; }
});

// NAMESPACE OBJECT: ./packages/edit-site/build-module/store/selectors.js
var store_selectors_namespaceObject = {};
__webpack_require__.r(store_selectors_namespaceObject);
__webpack_require__.d(store_selectors_namespaceObject, {
  "__experimentalGetInsertionPoint": function() { return __experimentalGetInsertionPoint; },
  "__experimentalGetPreviewDeviceType": function() { return __experimentalGetPreviewDeviceType; },
  "getCanUserCreateMedia": function() { return getCanUserCreateMedia; },
  "getCurrentTemplateNavigationPanelSubMenu": function() { return getCurrentTemplateNavigationPanelSubMenu; },
  "getCurrentTemplateTemplateParts": function() { return getCurrentTemplateTemplateParts; },
  "getEditedPostId": function() { return getEditedPostId; },
  "getEditedPostType": function() { return getEditedPostType; },
  "getHomeTemplateId": function() { return getHomeTemplateId; },
  "getNavigationPanelActiveMenu": function() { return getNavigationPanelActiveMenu; },
  "getPage": function() { return getPage; },
  "getPreviousEditedPostId": function() { return getPreviousEditedPostId; },
  "getPreviousEditedPostType": function() { return getPreviousEditedPostType; },
  "getSettings": function() { return getSettings; },
  "isFeatureActive": function() { return selectors_isFeatureActive; },
  "isInserterOpened": function() { return isInserterOpened; },
  "isListViewOpened": function() { return isListViewOpened; },
  "isNavigationOpened": function() { return isNavigationOpened; }
});

;// CONCATENATED MODULE: external ["wp","element"]
var external_wp_element_namespaceObject = window["wp"]["element"];
;// CONCATENATED MODULE: external ["wp","blocks"]
var external_wp_blocks_namespaceObject = window["wp"]["blocks"];
;// CONCATENATED MODULE: external ["wp","blockLibrary"]
var external_wp_blockLibrary_namespaceObject = window["wp"]["blockLibrary"];
;// CONCATENATED MODULE: external ["wp","data"]
var external_wp_data_namespaceObject = window["wp"]["data"];
;// CONCATENATED MODULE: external ["wp","coreData"]
var external_wp_coreData_namespaceObject = window["wp"]["coreData"];
// EXTERNAL MODULE: ./node_modules/downloadjs/download.js
var download = __webpack_require__(3729);
var download_default = /*#__PURE__*/__webpack_require__.n(download);
;// CONCATENATED MODULE: external ["wp","components"]
var external_wp_components_namespaceObject = window["wp"]["components"];
;// CONCATENATED MODULE: external ["wp","i18n"]
var external_wp_i18n_namespaceObject = window["wp"]["i18n"];
;// CONCATENATED MODULE: external ["wp","plugins"]
var external_wp_plugins_namespaceObject = window["wp"]["plugins"];
;// CONCATENATED MODULE: external ["wp","apiFetch"]
var external_wp_apiFetch_namespaceObject = window["wp"]["apiFetch"];
var external_wp_apiFetch_default = /*#__PURE__*/__webpack_require__.n(external_wp_apiFetch_namespaceObject);
;// CONCATENATED MODULE: external ["wp","primitives"]
var external_wp_primitives_namespaceObject = window["wp"]["primitives"];
;// CONCATENATED MODULE: ./packages/icons/build-module/library/download.js


/**
 * WordPress dependencies
 */

const download_download = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M18 11.3l-1-1.1-4 4V3h-1.5v11.3L7 10.2l-1 1.1 6.2 5.8 5.8-5.8zm.5 3.7v3.5h-13V15H4v5h16v-5h-1.5z"
}));
/* harmony default export */ var library_download = (download_download);
//# sourceMappingURL=download.js.map
;// CONCATENATED MODULE: external "lodash"
var external_lodash_namespaceObject = window["lodash"];
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/header/tools-more-menu-group/index.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */



const {
  Fill: ToolsMoreMenuGroup,
  Slot
} = (0,external_wp_components_namespaceObject.createSlotFill)('EditSiteToolsMoreMenuGroup');

ToolsMoreMenuGroup.Slot = ({
  fillProps
}) => (0,external_wp_element_namespaceObject.createElement)(Slot, {
  fillProps: fillProps
}, fills => !(0,external_lodash_namespaceObject.isEmpty)(fills) && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuGroup, {
  label: (0,external_wp_i18n_namespaceObject.__)('Tools')
}, fills));

/* harmony default export */ var tools_more_menu_group = (ToolsMoreMenuGroup);
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/plugins/index.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */






/**
 * Internal dependencies
 */


(0,external_wp_plugins_namespaceObject.registerPlugin)('edit-site', {
  render() {
    return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(tools_more_menu_group, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuItem, {
      role: "menuitem",
      icon: library_download,
      onClick: () => external_wp_apiFetch_default()({
        path: '/__experimental/edit-site/v1/export',
        parse: false
      }).then(res => res.blob()).then(blob => download_default()(blob, 'edit-site-export.zip', 'application/zip')),
      info: (0,external_wp_i18n_namespaceObject.__)('Download your templates and template parts.')
    }, (0,external_wp_i18n_namespaceObject.__)('Export'))));
  }

});
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: external ["wp","hooks"]
var external_wp_hooks_namespaceObject = window["wp"]["hooks"];
;// CONCATENATED MODULE: external ["wp","mediaUtils"]
var external_wp_mediaUtils_namespaceObject = window["wp"]["mediaUtils"];
;// CONCATENATED MODULE: ./packages/edit-site/build-module/hooks/components.js
/**
 * WordPress dependencies
 */


(0,external_wp_hooks_namespaceObject.addFilter)('editor.MediaUpload', 'core/edit-site/components/media-upload', () => external_wp_mediaUtils_namespaceObject.MediaUpload);
//# sourceMappingURL=components.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/hooks/index.js
/**
 * Internal dependencies
 */

//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: external ["wp","dataControls"]
var external_wp_dataControls_namespaceObject = window["wp"]["dataControls"];
;// CONCATENATED MODULE: ./packages/edit-site/build-module/store/defaults.js
const PREFERENCES_DEFAULTS = {
  features: {}
};
//# sourceMappingURL=defaults.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/store/constants.js
/**
 * WordPress dependencies
 */

/**
 * The identifier for the data store.
 *
 * @type {string}
 */

const STORE_NAME = 'core/edit-site';
const TEMPLATE_PART_AREA_HEADER = 'header';
const TEMPLATE_PART_AREA_FOOTER = 'footer';
const TEMPLATE_PART_AREA_SIDEBAR = 'sidebar';
const TEMPLATE_PART_AREA_GENERAL = 'uncategorized';
const TEMPLATE_PART_AREA_TO_NAME = {
  [TEMPLATE_PART_AREA_HEADER]: (0,external_wp_i18n_namespaceObject.__)('Header'),
  [TEMPLATE_PART_AREA_FOOTER]: (0,external_wp_i18n_namespaceObject.__)('Footer'),
  [TEMPLATE_PART_AREA_SIDEBAR]: (0,external_wp_i18n_namespaceObject.__)('Sidebar'),
  [TEMPLATE_PART_AREA_GENERAL]: (0,external_wp_i18n_namespaceObject.__)('General')
};
//# sourceMappingURL=constants.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/constants.js
/**
 * WordPress dependencies
 */

/**
 * Internal dependencies
 */


const TEMPLATES_PRIMARY = ['index', 'singular', 'archive', 'single', 'page', 'home', '404', 'search'];
const TEMPLATES_SECONDARY = ['author', 'category', 'taxonomy', 'date', 'tag', 'attachment', 'single-post', 'front-page'];
const TEMPLATES_TOP_LEVEL = [...TEMPLATES_PRIMARY, ...TEMPLATES_SECONDARY];
const TEMPLATES_GENERAL = ['page-home'];
const TEMPLATES_POSTS_PREFIXES = ['post-', 'author-', 'single-post-', 'tag-'];
const TEMPLATES_PAGES_PREFIXES = ['page-'];
const TEMPLATES_NEW_OPTIONS = ['front-page', 'single-post', 'page', 'archive', 'search', '404', 'index'];
const TEMPLATE_OVERRIDES = {
  singular: ['single', 'page'],
  index: ['archive', '404', 'search', 'singular', 'home'],
  home: ['front-page']
};
const MENU_ROOT = 'root';
const MENU_CONTENT_CATEGORIES = 'content-categories';
const MENU_CONTENT_PAGES = 'content-pages';
const MENU_CONTENT_POSTS = 'content-posts';
const MENU_TEMPLATE_PARTS = 'template-parts';
const MENU_TEMPLATES = 'templates';
const MENU_TEMPLATES_GENERAL = 'templates-general';
const MENU_TEMPLATES_PAGES = 'templates-pages';
const MENU_TEMPLATES_POSTS = 'templates-posts';
const MENU_TEMPLATES_UNUSED = 'templates-unused';
const SEARCH_DEBOUNCE_IN_MS = 75;
const MENU_TEMPLATE_PARTS_HEADERS = 'template-parts-headers';
const MENU_TEMPLATE_PARTS_FOOTERS = 'template-parts-footers';
const MENU_TEMPLATE_PARTS_SIDEBARS = 'template-parts-sidebars';
const MENU_TEMPLATE_PARTS_GENERAL = 'template-parts-general';
const TEMPLATE_PARTS_SUB_MENUS = [{
  area: TEMPLATE_PART_AREA_HEADER,
  menu: MENU_TEMPLATE_PARTS_HEADERS,
  title: (0,external_wp_i18n_namespaceObject.__)('headers')
}, {
  area: TEMPLATE_PART_AREA_FOOTER,
  menu: MENU_TEMPLATE_PARTS_FOOTERS,
  title: (0,external_wp_i18n_namespaceObject.__)('footers')
}, {
  area: TEMPLATE_PART_AREA_SIDEBAR,
  menu: MENU_TEMPLATE_PARTS_SIDEBARS,
  title: (0,external_wp_i18n_namespaceObject.__)('sidebars')
}, {
  area: TEMPLATE_PART_AREA_GENERAL,
  menu: MENU_TEMPLATE_PARTS_GENERAL,
  title: (0,external_wp_i18n_namespaceObject.__)('general')
}];
//# sourceMappingURL=constants.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/store/reducer.js
/**
 * WordPress dependencies
 */

/**
 * Internal dependencies
 */



/**
 * Reducer returning the user preferences.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 * @return {Object} Updated state.
 */

const preferences = (0,external_wp_data_namespaceObject.combineReducers)({
  features(state = PREFERENCES_DEFAULTS.features, action) {
    switch (action.type) {
      case 'TOGGLE_FEATURE':
        {
          return { ...state,
            [action.feature]: !state[action.feature]
          };
        }

      default:
        return state;
    }
  }

});
/**
 * Reducer returning the editing canvas device type.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */

function deviceType(state = 'Desktop', action) {
  switch (action.type) {
    case 'SET_PREVIEW_DEVICE_TYPE':
      return action.deviceType;
  }

  return state;
}
/**
 * Reducer returning the settings.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */

function settings(state = {}, action) {
  switch (action.type) {
    case 'UPDATE_SETTINGS':
      return { ...state,
        ...action.settings
      };
  }

  return state;
}
/**
 * Reducer keeping track of the currently edited Post Type,
 * Post Id and the context provided to fill the content of the block editor.
 *
 * @param {Array}  state  Current state history.
 * @param {Object} action Dispatched action.
 *
 * @return {Array} Updated state.
 */

function editedPost(state = [], action) {
  switch (action.type) {
    case 'SET_TEMPLATE':
    case 'SET_PAGE':
      return [{
        type: 'wp_template',
        id: action.templateId,
        page: action.page
      }];

    case 'SET_TEMPLATE_PART':
      return [{
        type: 'wp_template_part',
        id: action.templatePartId
      }];

    case 'PUSH_TEMPLATE_PART':
      return [...state, {
        type: 'wp_template_part',
        id: action.templatePartId
      }];

    case 'GO_BACK':
      return state.slice(0, -1);
  }

  return state;
}
/**
 * Reducer for information about the site's homepage.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */

function homeTemplateId(state, action) {
  switch (action.type) {
    case 'SET_HOME_TEMPLATE':
      return action.homeTemplateId;
  }

  return state;
}
/**
 * Reducer for information about the navigation panel, such as its active menu
 * and whether it should be opened or closed.
 *
 * Note: this reducer interacts with the inserter and list view panels reducers
 * to make sure that only one of the three panels is open at the same time.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 */

function navigationPanel(state = {
  menu: MENU_ROOT,
  isOpen: false
}, action) {
  switch (action.type) {
    case 'SET_NAVIGATION_PANEL_ACTIVE_MENU':
      return { ...state,
        menu: action.menu
      };

    case 'OPEN_NAVIGATION_PANEL_TO_MENU':
      return { ...state,
        isOpen: true,
        menu: action.menu
      };

    case 'SET_IS_NAVIGATION_PANEL_OPENED':
      return { ...state,
        menu: !action.isOpen ? MENU_ROOT : state.menu,
        // Set menu to root when closing panel.
        isOpen: action.isOpen
      };

    case 'SET_IS_LIST_VIEW_OPENED':
      return { ...state,
        menu: state.isOpen && action.isOpen ? MENU_ROOT : state.menu,
        // Set menu to root when closing panel.
        isOpen: action.isOpen ? false : state.isOpen
      };

    case 'SET_IS_INSERTER_OPENED':
      return { ...state,
        menu: state.isOpen && action.value ? MENU_ROOT : state.menu,
        // Set menu to root when closing panel.
        isOpen: action.value ? false : state.isOpen
      };
  }

  return state;
}
/**
 * Reducer to set the block inserter panel open or closed.
 *
 * Note: this reducer interacts with the navigation and list view panels reducers
 * to make sure that only one of the three panels is open at the same time.
 *
 * @param {boolean|Object} state  Current state.
 * @param {Object}         action Dispatched action.
 */

function blockInserterPanel(state = false, action) {
  switch (action.type) {
    case 'OPEN_NAVIGATION_PANEL_TO_MENU':
      return false;

    case 'SET_IS_NAVIGATION_PANEL_OPENED':
    case 'SET_IS_LIST_VIEW_OPENED':
      return action.isOpen ? false : state;

    case 'SET_IS_INSERTER_OPENED':
      return action.value;
  }

  return state;
}
/**
 * Reducer to set the list view panel open or closed.
 *
 * Note: this reducer interacts with the navigation and inserter panels reducers
 * to make sure that only one of the three panels is open at the same time.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 */

function listViewPanel(state = false, action) {
  switch (action.type) {
    case 'OPEN_NAVIGATION_PANEL_TO_MENU':
      return false;

    case 'SET_IS_NAVIGATION_PANEL_OPENED':
      return action.isOpen ? false : state;

    case 'SET_IS_INSERTER_OPENED':
      return action.value ? false : state;

    case 'SET_IS_LIST_VIEW_OPENED':
      return action.isOpen;
  }

  return state;
}
/* harmony default export */ var reducer = ((0,external_wp_data_namespaceObject.combineReducers)({
  preferences,
  deviceType,
  settings,
  editedPost,
  homeTemplateId,
  navigationPanel,
  blockInserterPanel,
  listViewPanel
}));
//# sourceMappingURL=reducer.js.map
;// CONCATENATED MODULE: external ["wp","url"]
var external_wp_url_namespaceObject = window["wp"]["url"];
;// CONCATENATED MODULE: external ["wp","notices"]
var external_wp_notices_namespaceObject = window["wp"]["notices"];
;// CONCATENATED MODULE: ./node_modules/@babel/runtime/helpers/esm/extends.js
function _extends() {
  _extends = Object.assign || function (target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i];

      for (var key in source) {
        if (Object.prototype.hasOwnProperty.call(source, key)) {
          target[key] = source[key];
        }
      }
    }

    return target;
  };

  return _extends.apply(this, arguments);
}
// EXTERNAL MODULE: ./node_modules/classnames/index.js
var classnames = __webpack_require__(4184);
var classnames_default = /*#__PURE__*/__webpack_require__.n(classnames);
;// CONCATENATED MODULE: ./packages/icons/build-module/library/check.js


/**
 * WordPress dependencies
 */

const check = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M16.7 7.1l-6.3 8.5-3.3-2.5-.9 1.2 4.5 3.4L17.9 8z"
}));
/* harmony default export */ var library_check = (check);
//# sourceMappingURL=check.js.map
;// CONCATENATED MODULE: ./packages/icons/build-module/library/star-filled.js


/**
 * WordPress dependencies
 */

const starFilled = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M11.776 4.454a.25.25 0 01.448 0l2.069 4.192a.25.25 0 00.188.137l4.626.672a.25.25 0 01.139.426l-3.348 3.263a.25.25 0 00-.072.222l.79 4.607a.25.25 0 01-.362.263l-4.138-2.175a.25.25 0 00-.232 0l-4.138 2.175a.25.25 0 01-.363-.263l.79-4.607a.25.25 0 00-.071-.222L4.754 9.881a.25.25 0 01.139-.426l4.626-.672a.25.25 0 00.188-.137l2.069-4.192z"
}));
/* harmony default export */ var star_filled = (starFilled);
//# sourceMappingURL=star-filled.js.map
;// CONCATENATED MODULE: ./packages/icons/build-module/library/star-empty.js


/**
 * WordPress dependencies
 */

const starEmpty = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  fillRule: "evenodd",
  d: "M9.706 8.646a.25.25 0 01-.188.137l-4.626.672a.25.25 0 00-.139.427l3.348 3.262a.25.25 0 01.072.222l-.79 4.607a.25.25 0 00.362.264l4.138-2.176a.25.25 0 01.233 0l4.137 2.175a.25.25 0 00.363-.263l-.79-4.607a.25.25 0 01.072-.222l3.347-3.262a.25.25 0 00-.139-.427l-4.626-.672a.25.25 0 01-.188-.137l-2.069-4.192a.25.25 0 00-.448 0L9.706 8.646zM12 7.39l-.948 1.921a1.75 1.75 0 01-1.317.957l-2.12.308 1.534 1.495c.412.402.6.982.503 1.55l-.362 2.11 1.896-.997a1.75 1.75 0 011.629 0l1.895.997-.362-2.11a1.75 1.75 0 01.504-1.55l1.533-1.495-2.12-.308a1.75 1.75 0 01-1.317-.957L12 7.39z",
  clipRule: "evenodd"
}));
/* harmony default export */ var star_empty = (starEmpty);
//# sourceMappingURL=star-empty.js.map
;// CONCATENATED MODULE: external ["wp","viewport"]
var external_wp_viewport_namespaceObject = window["wp"]["viewport"];
;// CONCATENATED MODULE: ./packages/icons/build-module/library/close-small.js


/**
 * WordPress dependencies
 */

const closeSmall = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M12 13.06l3.712 3.713 1.061-1.06L13.061 12l3.712-3.712-1.06-1.06L12 10.938 8.288 7.227l-1.061 1.06L10.939 12l-3.712 3.712 1.06 1.061L12 13.061z"
}));
/* harmony default export */ var close_small = (closeSmall);
//# sourceMappingURL=close-small.js.map
;// CONCATENATED MODULE: ./packages/interface/build-module/store/reducer.js
/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */


/**
 * Reducer to keep tract of the active area per scope.
 *
 * @param {boolean} state           Previous state.
 * @param {Object}  action          Action object.
 * @param {string}  action.type     Action type.
 * @param {string}  action.itemType Type of item.
 * @param {string}  action.scope    Item scope.
 * @param {string}  action.item     Item name.
 *
 * @return {Object} Updated state.
 */

function singleEnableItems(state = {}, {
  type,
  itemType,
  scope,
  item
}) {
  if (type !== 'SET_SINGLE_ENABLE_ITEM' || !itemType || !scope) {
    return state;
  }

  return { ...state,
    [itemType]: { ...state[itemType],
      [scope]: item || null
    }
  };
}
/**
 * Reducer keeping track of the "pinned" items per scope.
 *
 * @param {boolean} state           Previous state.
 * @param {Object}  action          Action object.
 * @param {string}  action.type     Action type.
 * @param {string}  action.itemType Type of item.
 * @param {string}  action.scope    Item scope.
 * @param {string}  action.item     Item name.
 * @param {boolean} action.isEnable Whether the item is pinned.
 *
 * @return {Object} Updated state.
 */

function multipleEnableItems(state = {}, {
  type,
  itemType,
  scope,
  item,
  isEnable
}) {
  if (type !== 'SET_MULTIPLE_ENABLE_ITEM' || !itemType || !scope || !item || (0,external_lodash_namespaceObject.get)(state, [itemType, scope, item]) === isEnable) {
    return state;
  }

  const currentTypeState = state[itemType] || {};
  const currentScopeState = currentTypeState[scope] || {};
  return { ...state,
    [itemType]: { ...currentTypeState,
      [scope]: { ...currentScopeState,
        [item]: isEnable || false
      }
    }
  };
}
/**
 * Reducer returning the defaults for user preferences.
 *
 * This is kept intentionally separate from the preferences
 * themselves so that defaults are not persisted.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */

const preferenceDefaults = (0,external_wp_data_namespaceObject.combineReducers)({
  features(state = {}, action) {
    if (action.type === 'SET_FEATURE_DEFAULTS') {
      const {
        scope,
        defaults
      } = action;
      return { ...state,
        [scope]: { ...state[scope],
          ...defaults
        }
      };
    }

    return state;
  }

});
/**
 * Reducer returning the user preferences.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */

const reducer_preferences = (0,external_wp_data_namespaceObject.combineReducers)({
  features(state = {}, action) {
    if (action.type === 'SET_FEATURE_VALUE') {
      const {
        scope,
        featureName,
        value
      } = action;
      return { ...state,
        [scope]: { ...state[scope],
          [featureName]: value
        }
      };
    }

    return state;
  }

});
const enableItems = (0,external_wp_data_namespaceObject.combineReducers)({
  singleEnableItems,
  multipleEnableItems
});
/* harmony default export */ var store_reducer = ((0,external_wp_data_namespaceObject.combineReducers)({
  enableItems,
  preferenceDefaults,
  preferences: reducer_preferences
}));
//# sourceMappingURL=reducer.js.map
;// CONCATENATED MODULE: ./packages/interface/build-module/store/actions.js
/**
 * Returns an action object used in signalling that an active area should be changed.
 *
 * @param {string} itemType Type of item.
 * @param {string} scope    Item scope.
 * @param {string} item     Item identifier.
 *
 * @return {Object} Action object.
 */
function setSingleEnableItem(itemType, scope, item) {
  return {
    type: 'SET_SINGLE_ENABLE_ITEM',
    itemType,
    scope,
    item
  };
}
/**
 * Returns an action object used in signalling that a complementary item should be enabled.
 *
 * @param {string} scope Complementary area scope.
 * @param {string} area  Area identifier.
 *
 * @return {Object} Action object.
 */


function enableComplementaryArea(scope, area) {
  return setSingleEnableItem('complementaryArea', scope, area);
}
/**
 * Returns an action object used in signalling that the complementary area of a given scope should be disabled.
 *
 * @param {string} scope Complementary area scope.
 *
 * @return {Object} Action object.
 */

function disableComplementaryArea(scope) {
  return setSingleEnableItem('complementaryArea', scope, undefined);
}
/**
 * Returns an action object to make an area enabled/disabled.
 *
 * @param {string}  itemType Type of item.
 * @param {string}  scope    Item scope.
 * @param {string}  item     Item identifier.
 * @param {boolean} isEnable Boolean indicating if an area should be pinned or not.
 *
 * @return {Object} Action object.
 */

function setMultipleEnableItem(itemType, scope, item, isEnable) {
  return {
    type: 'SET_MULTIPLE_ENABLE_ITEM',
    itemType,
    scope,
    item,
    isEnable
  };
}
/**
 * Returns an action object used in signalling that an item should be pinned.
 *
 * @param {string} scope  Item scope.
 * @param {string} itemId Item identifier.
 *
 * @return {Object} Action object.
 */


function pinItem(scope, itemId) {
  return setMultipleEnableItem('pinnedItems', scope, itemId, true);
}
/**
 * Returns an action object used in signalling that an item should be unpinned.
 *
 * @param {string} scope  Item scope.
 * @param {string} itemId Item identifier.
 *
 * @return {Object} Action object.
 */

function unpinItem(scope, itemId) {
  return setMultipleEnableItem('pinnedItems', scope, itemId, false);
}
/**
 * Returns an action object used in signalling that a feature should be toggled.
 *
 * @param {string} scope       The feature scope (e.g. core/edit-post).
 * @param {string} featureName The feature name.
 */

function toggleFeature(scope, featureName) {
  return function ({
    select,
    dispatch
  }) {
    const currentValue = select.isFeatureActive(scope, featureName);
    dispatch.setFeatureValue(scope, featureName, !currentValue);
  };
}
/**
 * Returns an action object used in signalling that a feature should be set to
 * a true or false value
 *
 * @param {string}  scope       The feature scope (e.g. core/edit-post).
 * @param {string}  featureName The feature name.
 * @param {boolean} value       The value to set.
 *
 * @return {Object} Action object.
 */

function setFeatureValue(scope, featureName, value) {
  return {
    type: 'SET_FEATURE_VALUE',
    scope,
    featureName,
    value: !!value
  };
}
/**
 * Returns an action object used in signalling that defaults should be set for features.
 *
 * @param {string}                  scope    The feature scope (e.g. core/edit-post).
 * @param {Object<string, boolean>} defaults A key/value map of feature names to values.
 *
 * @return {Object} Action object.
 */

function setFeatureDefaults(scope, defaults) {
  return {
    type: 'SET_FEATURE_DEFAULTS',
    scope,
    defaults
  };
}
//# sourceMappingURL=actions.js.map
;// CONCATENATED MODULE: ./packages/interface/build-module/store/selectors.js
/**
 * External dependencies
 */

/**
 * Returns the item that is enabled in a given scope.
 *
 * @param {Object} state    Global application state.
 * @param {string} itemType Type of item.
 * @param {string} scope    Item scope.
 *
 * @return {?string|null} The item that is enabled in the passed scope and type.
 */

function getSingleEnableItem(state, itemType, scope) {
  return (0,external_lodash_namespaceObject.get)(state.enableItems.singleEnableItems, [itemType, scope]);
}
/**
 * Returns the complementary area that is active in a given scope.
 *
 * @param {Object} state Global application state.
 * @param {string} scope Item scope.
 *
 * @return {string} The complementary area that is active in the given scope.
 */


function getActiveComplementaryArea(state, scope) {
  return getSingleEnableItem(state, 'complementaryArea', scope);
}
/**
 * Returns a boolean indicating if an item is enabled or not in a given scope.
 *
 * @param {Object} state    Global application state.
 * @param {string} itemType Type of item.
 * @param {string} scope    Scope.
 * @param {string} item     Item to check.
 *
 * @return {boolean|undefined} True if the item is enabled, false otherwise if the item is explicitly disabled, and undefined if there is no information for that item.
 */

function isMultipleEnabledItemEnabled(state, itemType, scope, item) {
  return (0,external_lodash_namespaceObject.get)(state.enableItems.multipleEnableItems, [itemType, scope, item]);
}
/**
 * Returns a boolean indicating if an item is pinned or not.
 *
 * @param {Object} state Global application state.
 * @param {string} scope Scope.
 * @param {string} item  Item to check.
 *
 * @return {boolean} True if the item is pinned and false otherwise.
 */


function isItemPinned(state, scope, item) {
  return isMultipleEnabledItemEnabled(state, 'pinnedItems', scope, item) !== false;
}
/**
 * Returns a boolean indicating whether a feature is active for a particular
 * scope.
 *
 * @param {Object} state       The store state.
 * @param {string} scope       The scope of the feature (e.g. core/edit-post).
 * @param {string} featureName The name of the feature.
 *
 * @return {boolean} Is the feature enabled?
 */

function isFeatureActive(state, scope, featureName) {
  var _state$preferences$fe, _state$preferenceDefa;

  const featureValue = (_state$preferences$fe = state.preferences.features[scope]) === null || _state$preferences$fe === void 0 ? void 0 : _state$preferences$fe[featureName];
  const defaultedFeatureValue = featureValue !== undefined ? featureValue : (_state$preferenceDefa = state.preferenceDefaults.features[scope]) === null || _state$preferenceDefa === void 0 ? void 0 : _state$preferenceDefa[featureName];
  return !!defaultedFeatureValue;
}
//# sourceMappingURL=selectors.js.map
;// CONCATENATED MODULE: ./packages/interface/build-module/store/constants.js
/**
 * The identifier for the data store.
 *
 * @type {string}
 */
const constants_STORE_NAME = 'core/interface';
//# sourceMappingURL=constants.js.map
;// CONCATENATED MODULE: ./packages/interface/build-module/store/index.js
/**
 * WordPress dependencies
 */

/**
 * Internal dependencies
 */





/**
 * Store definition for the interface namespace.
 *
 * @see https://github.com/WordPress/gutenberg/blob/HEAD/packages/data/README.md#createReduxStore
 *
 * @type {Object}
 */

const store = (0,external_wp_data_namespaceObject.createReduxStore)(constants_STORE_NAME, {
  reducer: store_reducer,
  actions: actions_namespaceObject,
  selectors: selectors_namespaceObject,
  persist: ['enableItems', 'preferences'],
  __experimentalUseThunks: true
}); // Once we build a more generic persistence plugin that works across types of stores
// we'd be able to replace this with a register call.

(0,external_wp_data_namespaceObject.registerStore)(constants_STORE_NAME, {
  reducer: store_reducer,
  actions: actions_namespaceObject,
  selectors: selectors_namespaceObject,
  persist: ['enableItems', 'preferences'],
  __experimentalUseThunks: true
});
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/interface/build-module/components/complementary-area-context/index.js
/**
 * WordPress dependencies
 */

/* harmony default export */ var complementary_area_context = ((0,external_wp_plugins_namespaceObject.withPluginContext)((context, ownProps) => {
  return {
    icon: ownProps.icon || context.icon,
    identifier: ownProps.identifier || `${context.name}/${ownProps.name}`
  };
}));
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/interface/build-module/components/complementary-area-toggle/index.js



/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */




function ComplementaryAreaToggle({
  as = external_wp_components_namespaceObject.Button,
  scope,
  identifier,
  icon,
  selectedIcon,
  ...props
}) {
  const ComponentToUse = as;
  const isSelected = (0,external_wp_data_namespaceObject.useSelect)(select => select(store).getActiveComplementaryArea(scope) === identifier, [identifier]);
  const {
    enableComplementaryArea,
    disableComplementaryArea
  } = (0,external_wp_data_namespaceObject.useDispatch)(store);
  return (0,external_wp_element_namespaceObject.createElement)(ComponentToUse, _extends({
    icon: selectedIcon && isSelected ? selectedIcon : icon,
    onClick: () => {
      if (isSelected) {
        disableComplementaryArea(scope);
      } else {
        enableComplementaryArea(scope, identifier);
      }
    }
  }, (0,external_lodash_namespaceObject.omit)(props, ['name'])));
}

/* harmony default export */ var complementary_area_toggle = (complementary_area_context(ComplementaryAreaToggle));
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/interface/build-module/components/complementary-area-header/index.js



/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */



const ComplementaryAreaHeader = ({
  smallScreenTitle,
  children,
  className,
  toggleButtonProps
}) => {
  const toggleButton = (0,external_wp_element_namespaceObject.createElement)(complementary_area_toggle, _extends({
    icon: close_small
  }, toggleButtonProps));
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "components-panel__header interface-complementary-area-header__small"
  }, smallScreenTitle && (0,external_wp_element_namespaceObject.createElement)("span", {
    className: "interface-complementary-area-header__small-title"
  }, smallScreenTitle), toggleButton), (0,external_wp_element_namespaceObject.createElement)("div", {
    className: classnames_default()('components-panel__header', 'interface-complementary-area-header', className),
    tabIndex: -1
  }, children, toggleButton));
};

/* harmony default export */ var complementary_area_header = (ComplementaryAreaHeader);
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/interface/build-module/components/action-item/index.js



/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */




function ActionItemSlot({
  name,
  as: Component = external_wp_components_namespaceObject.ButtonGroup,
  fillProps = {},
  bubblesVirtually,
  ...props
}) {
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Slot, {
    name: name,
    bubblesVirtually: bubblesVirtually,
    fillProps: fillProps
  }, fills => {
    if ((0,external_lodash_namespaceObject.isEmpty)(external_wp_element_namespaceObject.Children.toArray(fills))) {
      return null;
    } // Special handling exists for backward compatibility.
    // It ensures that menu items created by plugin authors aren't
    // duplicated with automatically injected menu items coming
    // from pinnable plugin sidebars.
    // @see https://github.com/WordPress/gutenberg/issues/14457


    const initializedByPlugins = [];
    external_wp_element_namespaceObject.Children.forEach(fills, ({
      props: {
        __unstableExplicitMenuItem,
        __unstableTarget
      }
    }) => {
      if (__unstableTarget && __unstableExplicitMenuItem) {
        initializedByPlugins.push(__unstableTarget);
      }
    });
    const children = external_wp_element_namespaceObject.Children.map(fills, child => {
      if (!child.props.__unstableExplicitMenuItem && initializedByPlugins.includes(child.props.__unstableTarget)) {
        return null;
      }

      return child;
    });
    return (0,external_wp_element_namespaceObject.createElement)(Component, props, children);
  });
}

function ActionItem({
  name,
  as: Component = external_wp_components_namespaceObject.Button,
  onClick,
  ...props
}) {
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Fill, {
    name: name
  }, ({
    onClick: fpOnClick
  }) => {
    return (0,external_wp_element_namespaceObject.createElement)(Component, _extends({
      onClick: onClick || fpOnClick ? (...args) => {
        (onClick || external_lodash_namespaceObject.noop)(...args);
        (fpOnClick || external_lodash_namespaceObject.noop)(...args);
      } : undefined
    }, props));
  });
}

ActionItem.Slot = ActionItemSlot;
/* harmony default export */ var action_item = (ActionItem);
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/interface/build-module/components/complementary-area-more-menu-item/index.js



/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */




const PluginsMenuItem = props => // Menu item is marked with unstable prop for backward compatibility.
// They are removed so they don't leak to DOM elements.
// @see https://github.com/WordPress/gutenberg/issues/14457
(0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuItem, (0,external_lodash_namespaceObject.omit)(props, ['__unstableExplicitMenuItem', '__unstableTarget']));

function ComplementaryAreaMoreMenuItem({
  scope,
  target,
  __unstableExplicitMenuItem,
  ...props
}) {
  return (0,external_wp_element_namespaceObject.createElement)(complementary_area_toggle, _extends({
    as: toggleProps => {
      return (0,external_wp_element_namespaceObject.createElement)(action_item, _extends({
        __unstableExplicitMenuItem: __unstableExplicitMenuItem,
        __unstableTarget: `${scope}/${target}`,
        as: PluginsMenuItem,
        name: `${scope}/plugin-more-menu`
      }, toggleProps));
    },
    role: "menuitemcheckbox",
    selectedIcon: library_check,
    name: target,
    scope: scope
  }, props));
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/interface/build-module/components/pinned-items/index.js



/**
 * External dependencies
 */


/**
 * WordPress dependencies
 */



function PinnedItems({
  scope,
  ...props
}) {
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Fill, _extends({
    name: `PinnedItems/${scope}`
  }, props));
}

function PinnedItemsSlot({
  scope,
  className,
  ...props
}) {
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Slot, _extends({
    name: `PinnedItems/${scope}`
  }, props), fills => !(0,external_lodash_namespaceObject.isEmpty)(fills) && (0,external_wp_element_namespaceObject.createElement)("div", {
    className: classnames_default()(className, 'interface-pinned-items')
  }, fills));
}

PinnedItems.Slot = PinnedItemsSlot;
/* harmony default export */ var pinned_items = (PinnedItems);
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/interface/build-module/components/complementary-area/index.js



/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */







/**
 * Internal dependencies
 */








function ComplementaryAreaSlot({
  scope,
  ...props
}) {
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Slot, _extends({
    name: `ComplementaryArea/${scope}`
  }, props));
}

function ComplementaryAreaFill({
  scope,
  children,
  className
}) {
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Fill, {
    name: `ComplementaryArea/${scope}`
  }, (0,external_wp_element_namespaceObject.createElement)("div", {
    className: className
  }, children));
}

function useAdjustComplementaryListener(scope, identifier, activeArea, isActive, isSmall) {
  const previousIsSmall = (0,external_wp_element_namespaceObject.useRef)(false);
  const shouldOpenWhenNotSmall = (0,external_wp_element_namespaceObject.useRef)(false);
  const {
    enableComplementaryArea,
    disableComplementaryArea
  } = (0,external_wp_data_namespaceObject.useDispatch)(store);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    // If the complementary area is active and the editor is switching from a big to a small window size.
    if (isActive && isSmall && !previousIsSmall.current) {
      // Disable the complementary area.
      disableComplementaryArea(scope); // Flag the complementary area to be reopened when the window size goes from small to big.

      shouldOpenWhenNotSmall.current = true;
    } else if ( // If there is a flag indicating the complementary area should be enabled when we go from small to big window size
    // and we are going from a small to big window size.
    shouldOpenWhenNotSmall.current && !isSmall && previousIsSmall.current) {
      // Remove the flag indicating the complementary area should be enabled.
      shouldOpenWhenNotSmall.current = false; // Enable the complementary area.

      enableComplementaryArea(scope, identifier);
    } else if ( // If the flag is indicating the current complementary should be reopened but another complementary area becomes active,
    // remove the flag.
    shouldOpenWhenNotSmall.current && activeArea && activeArea !== identifier) {
      shouldOpenWhenNotSmall.current = false;
    }

    if (isSmall !== previousIsSmall.current) {
      previousIsSmall.current = isSmall;
    }
  }, [isActive, isSmall, scope, identifier, activeArea]);
}

function ComplementaryArea({
  children,
  className,
  closeLabel = (0,external_wp_i18n_namespaceObject.__)('Close plugin'),
  identifier,
  header,
  headerClassName,
  icon,
  isPinnable = true,
  panelClassName,
  scope,
  name,
  smallScreenTitle,
  title,
  toggleShortcut,
  isActiveByDefault,
  showIconLabels = false
}) {
  const {
    isActive,
    isPinned,
    activeArea,
    isSmall,
    isLarge
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      getActiveComplementaryArea,
      isItemPinned
    } = select(store);

    const _activeArea = getActiveComplementaryArea(scope);

    return {
      isActive: _activeArea === identifier,
      isPinned: isItemPinned(scope, identifier),
      activeArea: _activeArea,
      isSmall: select(external_wp_viewport_namespaceObject.store).isViewportMatch('< medium'),
      isLarge: select(external_wp_viewport_namespaceObject.store).isViewportMatch('large')
    };
  }, [identifier, scope]);
  useAdjustComplementaryListener(scope, identifier, activeArea, isActive, isSmall);
  const {
    enableComplementaryArea,
    disableComplementaryArea,
    pinItem,
    unpinItem
  } = (0,external_wp_data_namespaceObject.useDispatch)(store);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    if (isActiveByDefault && activeArea === undefined && !isSmall) {
      enableComplementaryArea(scope, identifier);
    }
  }, [activeArea, isActiveByDefault, scope, identifier, isSmall]);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, isPinnable && (0,external_wp_element_namespaceObject.createElement)(pinned_items, {
    scope: scope
  }, isPinned && (0,external_wp_element_namespaceObject.createElement)(complementary_area_toggle, {
    scope: scope,
    identifier: identifier,
    isPressed: isActive && (!showIconLabels || isLarge),
    "aria-expanded": isActive,
    label: title,
    icon: showIconLabels ? library_check : icon,
    showTooltip: !showIconLabels,
    variant: showIconLabels ? 'tertiary' : undefined
  })), name && isPinnable && (0,external_wp_element_namespaceObject.createElement)(ComplementaryAreaMoreMenuItem, {
    target: name,
    scope: scope,
    icon: icon
  }, title), isActive && (0,external_wp_element_namespaceObject.createElement)(ComplementaryAreaFill, {
    className: classnames_default()('interface-complementary-area', className),
    scope: scope
  }, (0,external_wp_element_namespaceObject.createElement)(complementary_area_header, {
    className: headerClassName,
    closeLabel: closeLabel,
    onClose: () => disableComplementaryArea(scope),
    smallScreenTitle: smallScreenTitle,
    toggleButtonProps: {
      label: closeLabel,
      shortcut: toggleShortcut,
      scope,
      identifier
    }
  }, header || (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)("strong", null, title), isPinnable && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    className: "interface-complementary-area__pin-unpin-item",
    icon: isPinned ? star_filled : star_empty,
    label: isPinned ? (0,external_wp_i18n_namespaceObject.__)('Unpin from toolbar') : (0,external_wp_i18n_namespaceObject.__)('Pin to toolbar'),
    onClick: () => (isPinned ? unpinItem : pinItem)(scope, identifier),
    isPressed: isPinned,
    "aria-expanded": isPinned
  }))), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Panel, {
    className: panelClassName
  }, children)));
}

const ComplementaryAreaWrapped = complementary_area_context(ComplementaryArea);
ComplementaryAreaWrapped.Slot = ComplementaryAreaSlot;
/* harmony default export */ var complementary_area = (ComplementaryAreaWrapped);
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/interface/build-module/components/fullscreen-mode/index.js
/**
 * WordPress dependencies
 */


const FullscreenMode = ({
  isActive
}) => {
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    let isSticky = false; // `is-fullscreen-mode` is set in PHP as a body class by Gutenberg, and this causes
    // `sticky-menu` to be applied by WordPress and prevents the admin menu being scrolled
    // even if `is-fullscreen-mode` is then removed. Let's remove `sticky-menu` here as
    // a consequence of the FullscreenMode setup

    if (document.body.classList.contains('sticky-menu')) {
      isSticky = true;
      document.body.classList.remove('sticky-menu');
    }

    return () => {
      if (isSticky) {
        document.body.classList.add('sticky-menu');
      }
    };
  }, []);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    if (isActive) {
      document.body.classList.add('is-fullscreen-mode');
    } else {
      document.body.classList.remove('is-fullscreen-mode');
    }

    return () => {
      if (isActive) {
        document.body.classList.remove('is-fullscreen-mode');
      }
    };
  }, [isActive]);
  return null;
};

/* harmony default export */ var fullscreen_mode = (FullscreenMode);
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: external ["wp","compose"]
var external_wp_compose_namespaceObject = window["wp"]["compose"];
;// CONCATENATED MODULE: ./packages/interface/build-module/components/interface-skeleton/index.js



/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */

/**
 * WordPress dependencies
 */






function useHTMLClass(className) {
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    const element = document && document.querySelector(`html:not(.${className})`);

    if (!element) {
      return;
    }

    element.classList.toggle(className);
    return () => {
      element.classList.toggle(className);
    };
  }, [className]);
}

function InterfaceSkeleton({
  footer,
  header,
  sidebar,
  secondarySidebar,
  notices,
  content,
  drawer,
  actions,
  labels,
  className,
  shortcuts
}, ref) {
  const navigateRegionsProps = (0,external_wp_components_namespaceObject.__unstableUseNavigateRegions)(shortcuts);
  useHTMLClass('interface-interface-skeleton__html-container');
  const defaultLabels = {
    /* translators: accessibility text for the nav bar landmark region. */
    drawer: (0,external_wp_i18n_namespaceObject.__)('Drawer'),

    /* translators: accessibility text for the top bar landmark region. */
    header: (0,external_wp_i18n_namespaceObject.__)('Header'),

    /* translators: accessibility text for the content landmark region. */
    body: (0,external_wp_i18n_namespaceObject.__)('Content'),

    /* translators: accessibility text for the secondary sidebar landmark region. */
    secondarySidebar: (0,external_wp_i18n_namespaceObject.__)('Block Library'),

    /* translators: accessibility text for the settings landmark region. */
    sidebar: (0,external_wp_i18n_namespaceObject.__)('Settings'),

    /* translators: accessibility text for the publish landmark region. */
    actions: (0,external_wp_i18n_namespaceObject.__)('Publish'),

    /* translators: accessibility text for the footer landmark region. */
    footer: (0,external_wp_i18n_namespaceObject.__)('Footer')
  };
  const mergedLabels = { ...defaultLabels,
    ...labels
  };
  return (0,external_wp_element_namespaceObject.createElement)("div", _extends({}, navigateRegionsProps, {
    ref: (0,external_wp_compose_namespaceObject.useMergeRefs)([ref, navigateRegionsProps.ref]),
    className: classnames_default()(className, 'interface-interface-skeleton', navigateRegionsProps.className, !!footer && 'has-footer')
  }), !!drawer && (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "interface-interface-skeleton__drawer",
    role: "region",
    "aria-label": mergedLabels.drawer
  }, drawer), (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "interface-interface-skeleton__editor"
  }, !!header && (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "interface-interface-skeleton__header",
    role: "region",
    "aria-label": mergedLabels.header,
    tabIndex: "-1"
  }, header), (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "interface-interface-skeleton__body"
  }, !!secondarySidebar && (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "interface-interface-skeleton__secondary-sidebar",
    role: "region",
    "aria-label": mergedLabels.secondarySidebar,
    tabIndex: "-1"
  }, secondarySidebar), !!notices && (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "interface-interface-skeleton__notices"
  }, notices), (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "interface-interface-skeleton__content",
    role: "region",
    "aria-label": mergedLabels.body,
    tabIndex: "-1"
  }, content), !!sidebar && (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "interface-interface-skeleton__sidebar",
    role: "region",
    "aria-label": mergedLabels.sidebar,
    tabIndex: "-1"
  }, sidebar), !!actions && (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "interface-interface-skeleton__actions",
    role: "region",
    "aria-label": mergedLabels.actions,
    tabIndex: "-1"
  }, actions))), !!footer && (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "interface-interface-skeleton__footer",
    role: "region",
    "aria-label": mergedLabels.footer,
    tabIndex: "-1"
  }, footer));
}

/* harmony default export */ var interface_skeleton = ((0,external_wp_element_namespaceObject.forwardRef)(InterfaceSkeleton));
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/interface/build-module/components/index.js








//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/interface/build-module/index.js


//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/utils/is-template-revertable.js
/**
 * Check if a template is revertable to its original theme-provided template file.
 *
 * @param {Object} template The template entity to check.
 * @return {boolean} Whether the template is revertable.
 */
function isTemplateRevertable(template) {
  if (!template) {
    return false;
  }
  /* eslint-disable camelcase */


  return (template === null || template === void 0 ? void 0 : template.source) === 'custom' && (template === null || template === void 0 ? void 0 : template.has_theme_file);
  /* eslint-enable camelcase */
}
//# sourceMappingURL=is-template-revertable.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/store/actions.js
/**
 * WordPress dependencies
 */








/**
 * Internal dependencies
 */



/**
 * Returns an action object used to toggle a feature flag.
 *
 * @param {string} feature Feature name.
 *
 * @return {Object} Action object.
 */

function actions_toggleFeature(feature) {
  return {
    type: 'TOGGLE_FEATURE',
    feature
  };
}
/**
 * Returns an action object used to toggle the width of the editing canvas.
 *
 * @param {string} deviceType
 *
 * @return {Object} Action object.
 */

function __experimentalSetPreviewDeviceType(deviceType) {
  return {
    type: 'SET_PREVIEW_DEVICE_TYPE',
    deviceType
  };
}
/**
 * Returns an action object used to set a template.
 *
 * @param {number} templateId   The template ID.
 * @param {string} templateSlug The template slug.
 * @return {Object} Action object.
 */

function* setTemplate(templateId, templateSlug) {
  const pageContext = {
    templateSlug
  };

  if (!templateSlug) {
    const template = yield external_wp_data_namespaceObject.controls.resolveSelect(external_wp_coreData_namespaceObject.store, 'getEntityRecord', 'postType', 'wp_template', templateId);
    pageContext.templateSlug = template === null || template === void 0 ? void 0 : template.slug;
  }

  return {
    type: 'SET_TEMPLATE',
    templateId,
    page: {
      context: pageContext
    }
  };
}
/**
 * Adds a new template, and sets it as the current template.
 *
 * @param {Object} template The template.
 *
 * @return {Object} Action object used to set the current template.
 */

function* addTemplate(template) {
  const newTemplate = yield external_wp_data_namespaceObject.controls.dispatch(external_wp_coreData_namespaceObject.store, 'saveEntityRecord', 'postType', 'wp_template', template);

  if (template.content) {
    yield external_wp_data_namespaceObject.controls.dispatch(external_wp_coreData_namespaceObject.store, 'editEntityRecord', 'postType', 'wp_template', newTemplate.id, {
      blocks: (0,external_wp_blocks_namespaceObject.parse)(template.content)
    }, {
      undoIgnore: true
    });
  }

  return {
    type: 'SET_TEMPLATE',
    templateId: newTemplate.id,
    page: {
      context: {
        templateSlug: newTemplate.slug
      }
    }
  };
}
/**
 * Removes a template, and updates the current page and template.
 *
 * @param {number} templateId The template ID.
 */

function* removeTemplate(templateId) {
  yield (0,external_wp_dataControls_namespaceObject.apiFetch)({
    path: `/wp/v2/templates/${templateId}`,
    method: 'DELETE'
  });
  const page = yield external_wp_data_namespaceObject.controls.select(STORE_NAME, 'getPage');
  yield external_wp_data_namespaceObject.controls.dispatch(STORE_NAME, 'setPage', page);
}
/**
 * Returns an action object used to set a template part.
 *
 * @param {string} templatePartId The template part ID.
 *
 * @return {Object} Action object.
 */

function setTemplatePart(templatePartId) {
  return {
    type: 'SET_TEMPLATE_PART',
    templatePartId
  };
}
/**
 * Returns an action object used to push a template part to navigation history.
 *
 * @param {string} templatePartId The template part ID.
 *
 * @return {Object} Action object.
 */

function pushTemplatePart(templatePartId) {
  return {
    type: 'PUSH_TEMPLATE_PART',
    templatePartId
  };
}
/**
 * Updates the homeTemplateId state with the templateId of the page resolved
 * from the given path.
 *
 * @param {number} homeTemplateId The template ID for the homepage.
 */

function setHomeTemplateId(homeTemplateId) {
  return {
    type: 'SET_HOME_TEMPLATE',
    homeTemplateId
  };
}
/**
 * Resolves the template for a page and displays both. If no path is given, attempts
 * to use the postId to generate a path like `?p=${ postId }`.
 *
 * @param {Object} page         The page object.
 * @param {string} page.type    The page type.
 * @param {string} page.slug    The page slug.
 * @param {string} page.path    The page path.
 * @param {Object} page.context The page context.
 *
 * @return {number} The resolved template ID for the page route.
 */

function* setPage(page) {
  var _page$context;

  if (!page.path && (_page$context = page.context) !== null && _page$context !== void 0 && _page$context.postId) {
    const entity = yield external_wp_data_namespaceObject.controls.resolveSelect(external_wp_coreData_namespaceObject.store, 'getEntityRecord', 'postType', page.context.postType || 'post', page.context.postId);
    page.path = (0,external_wp_url_namespaceObject.getPathAndQueryString)(entity.link);
  }

  const {
    id: templateId,
    slug: templateSlug
  } = yield external_wp_data_namespaceObject.controls.resolveSelect(external_wp_coreData_namespaceObject.store, '__experimentalGetTemplateForLink', page.path);
  yield {
    type: 'SET_PAGE',
    page: !templateSlug ? page : { ...page,
      context: { ...page.context,
        templateSlug
      }
    },
    templateId
  };
  return templateId;
}
/**
 * Go back to the current editing page.
 */

function goBack() {
  return {
    type: 'GO_BACK'
  };
}
/**
 * Displays the site homepage for editing in the editor.
 */

function* showHomepage() {
  const {
    show_on_front: showOnFront,
    page_on_front: frontpageId
  } = yield external_wp_data_namespaceObject.controls.resolveSelect(external_wp_coreData_namespaceObject.store, 'getEntityRecord', 'root', 'site');
  const {
    siteUrl
  } = yield external_wp_data_namespaceObject.controls.select(STORE_NAME, 'getSettings');
  const page = {
    path: siteUrl,
    context: showOnFront === 'page' ? {
      postType: 'page',
      postId: frontpageId
    } : {}
  };
  const homeTemplate = yield* setPage(page);
  yield setHomeTemplateId(homeTemplate);
}
/**
 * Returns an action object used to set the active navigation panel menu.
 *
 * @param {string} menu Menu prop of active menu.
 *
 * @return {Object} Action object.
 */

function setNavigationPanelActiveMenu(menu) {
  return {
    type: 'SET_NAVIGATION_PANEL_ACTIVE_MENU',
    menu
  };
}
/**
 * Opens the navigation panel and sets its active menu at the same time.
 *
 * @param {string} menu Identifies the menu to open.
 */

function openNavigationPanelToMenu(menu) {
  return {
    type: 'OPEN_NAVIGATION_PANEL_TO_MENU',
    menu
  };
}
/**
 * Sets whether the navigation panel should be open.
 *
 * @param {boolean} isOpen If true, opens the nav panel. If false, closes it. It
 *                         does not toggle the state, but sets it directly.
 */

function setIsNavigationPanelOpened(isOpen) {
  return {
    type: 'SET_IS_NAVIGATION_PANEL_OPENED',
    isOpen
  };
}
/**
 * Returns an action object used to open/close the inserter.
 *
 * @param {boolean|Object} value                Whether the inserter should be
 *                                              opened (true) or closed (false).
 *                                              To specify an insertion point,
 *                                              use an object.
 * @param {string}         value.rootClientId   The root client ID to insert at.
 * @param {number}         value.insertionIndex The index to insert at.
 *
 * @return {Object} Action object.
 */

function setIsInserterOpened(value) {
  return {
    type: 'SET_IS_INSERTER_OPENED',
    value
  };
}
/**
 * Returns an action object used to update the settings.
 *
 * @param {Object} settings New settings.
 *
 * @return {Object} Action object.
 */

function updateSettings(settings) {
  return {
    type: 'UPDATE_SETTINGS',
    settings
  };
}
/**
 * Sets whether the list view panel should be open.
 *
 * @param {boolean} isOpen If true, opens the list view. If false, closes it.
 *                         It does not toggle the state, but sets it directly.
 */

function setIsListViewOpened(isOpen) {
  return {
    type: 'SET_IS_LIST_VIEW_OPENED',
    isOpen
  };
}
/**
 * Reverts a template to its original theme-provided file.
 *
 * @param {Object} template The template to revert.
 */

function* revertTemplate(template) {
  if (!isTemplateRevertable(template)) {
    yield external_wp_data_namespaceObject.controls.dispatch(external_wp_notices_namespaceObject.store, 'createErrorNotice', (0,external_wp_i18n_namespaceObject.__)('This template is not revertable.'), {
      type: 'snackbar'
    });
    return;
  }

  try {
    var _fileTemplate$content;

    const templateEntity = yield external_wp_data_namespaceObject.controls.select(external_wp_coreData_namespaceObject.store, 'getEntity', 'postType', template.type);

    if (!templateEntity) {
      yield external_wp_data_namespaceObject.controls.dispatch(external_wp_notices_namespaceObject.store, 'createErrorNotice', (0,external_wp_i18n_namespaceObject.__)('The editor has encountered an unexpected error. Please reload.'), {
        type: 'snackbar'
      });
      return;
    }

    const fileTemplatePath = (0,external_wp_url_namespaceObject.addQueryArgs)(`${templateEntity.baseURL}/${template.id}`, {
      context: 'edit',
      source: 'theme'
    });
    const fileTemplate = yield (0,external_wp_dataControls_namespaceObject.apiFetch)({
      path: fileTemplatePath
    });

    if (!fileTemplate) {
      yield external_wp_data_namespaceObject.controls.dispatch(external_wp_notices_namespaceObject.store, 'createErrorNotice', (0,external_wp_i18n_namespaceObject.__)('The editor has encountered an unexpected error. Please reload.'), {
        type: 'snackbar'
      });
      return;
    }

    const serializeBlocks = ({
      blocks: blocksForSerialization = []
    }) => (0,external_wp_blocks_namespaceObject.__unstableSerializeAndClean)(blocksForSerialization);

    const edited = yield external_wp_data_namespaceObject.controls.select(external_wp_coreData_namespaceObject.store, 'getEditedEntityRecord', 'postType', template.type, template.id); // We are fixing up the undo level here to make sure we can undo
    // the revert in the header toolbar correctly.

    yield external_wp_data_namespaceObject.controls.dispatch(external_wp_coreData_namespaceObject.store, 'editEntityRecord', 'postType', template.type, template.id, {
      content: serializeBlocks,
      // required to make the `undo` behave correctly
      blocks: edited.blocks,
      // required to revert the blocks in the editor
      source: 'custom' // required to avoid turning the editor into a dirty state

    }, {
      undoIgnore: true // required to merge this edit with the last undo level

    });
    const blocks = (0,external_wp_blocks_namespaceObject.parse)(fileTemplate === null || fileTemplate === void 0 ? void 0 : (_fileTemplate$content = fileTemplate.content) === null || _fileTemplate$content === void 0 ? void 0 : _fileTemplate$content.raw);
    yield external_wp_data_namespaceObject.controls.dispatch(external_wp_coreData_namespaceObject.store, 'editEntityRecord', 'postType', template.type, fileTemplate.id, {
      content: serializeBlocks,
      blocks,
      source: 'theme'
    });

    const undoRevert = async () => {
      await (0,external_wp_data_namespaceObject.dispatch)(external_wp_coreData_namespaceObject.store).editEntityRecord('postType', template.type, edited.id, {
        content: serializeBlocks,
        blocks: edited.blocks,
        source: 'custom'
      });
    };

    yield external_wp_data_namespaceObject.controls.dispatch(external_wp_notices_namespaceObject.store, 'createSuccessNotice', (0,external_wp_i18n_namespaceObject.__)('Template reverted.'), {
      type: 'snackbar',
      actions: [{
        label: (0,external_wp_i18n_namespaceObject.__)('Undo'),
        onClick: undoRevert
      }]
    });
  } catch (error) {
    const errorMessage = error.message && error.code !== 'unknown_error' ? error.message : (0,external_wp_i18n_namespaceObject.__)('Template revert failed. Please reload.');
    yield external_wp_data_namespaceObject.controls.dispatch(external_wp_notices_namespaceObject.store, 'createErrorNotice', errorMessage, {
      type: 'snackbar'
    });
  }
}
/**
 * Returns an action object used in signalling that the user opened an editor sidebar.
 *
 * @param {?string} name Sidebar name to be opened.
 *
 * @yield {Object} Action object.
 */

function* openGeneralSidebar(name) {
  yield external_wp_data_namespaceObject.controls.dispatch(store, 'enableComplementaryArea', STORE_NAME, name);
}
/**
 * Returns an action object signalling that the user closed the sidebar.
 *
 * @yield {Object} Action object.
 */

function* closeGeneralSidebar() {
  yield external_wp_data_namespaceObject.controls.dispatch(store, 'disableComplementaryArea', STORE_NAME);
}
//# sourceMappingURL=actions.js.map
;// CONCATENATED MODULE: ./node_modules/rememo/es/rememo.js


var LEAF_KEY, hasWeakMap;

/**
 * Arbitrary value used as key for referencing cache object in WeakMap tree.
 *
 * @type {Object}
 */
LEAF_KEY = {};

/**
 * Whether environment supports WeakMap.
 *
 * @type {boolean}
 */
hasWeakMap = typeof WeakMap !== 'undefined';

/**
 * Returns the first argument as the sole entry in an array.
 *
 * @param {*} value Value to return.
 *
 * @return {Array} Value returned as entry in array.
 */
function arrayOf( value ) {
	return [ value ];
}

/**
 * Returns true if the value passed is object-like, or false otherwise. A value
 * is object-like if it can support property assignment, e.g. object or array.
 *
 * @param {*} value Value to test.
 *
 * @return {boolean} Whether value is object-like.
 */
function isObjectLike( value ) {
	return !! value && 'object' === typeof value;
}

/**
 * Creates and returns a new cache object.
 *
 * @return {Object} Cache object.
 */
function createCache() {
	var cache = {
		clear: function() {
			cache.head = null;
		},
	};

	return cache;
}

/**
 * Returns true if entries within the two arrays are strictly equal by
 * reference from a starting index.
 *
 * @param {Array}  a         First array.
 * @param {Array}  b         Second array.
 * @param {number} fromIndex Index from which to start comparison.
 *
 * @return {boolean} Whether arrays are shallowly equal.
 */
function isShallowEqual( a, b, fromIndex ) {
	var i;

	if ( a.length !== b.length ) {
		return false;
	}

	for ( i = fromIndex; i < a.length; i++ ) {
		if ( a[ i ] !== b[ i ] ) {
			return false;
		}
	}

	return true;
}

/**
 * Returns a memoized selector function. The getDependants function argument is
 * called before the memoized selector and is expected to return an immutable
 * reference or array of references on which the selector depends for computing
 * its own return value. The memoize cache is preserved only as long as those
 * dependant references remain the same. If getDependants returns a different
 * reference(s), the cache is cleared and the selector value regenerated.
 *
 * @param {Function} selector      Selector function.
 * @param {Function} getDependants Dependant getter returning an immutable
 *                                 reference or array of reference used in
 *                                 cache bust consideration.
 *
 * @return {Function} Memoized selector.
 */
/* harmony default export */ function rememo(selector, getDependants ) {
	var rootCache, getCache;

	// Use object source as dependant if getter not provided
	if ( ! getDependants ) {
		getDependants = arrayOf;
	}

	/**
	 * Returns the root cache. If WeakMap is supported, this is assigned to the
	 * root WeakMap cache set, otherwise it is a shared instance of the default
	 * cache object.
	 *
	 * @return {(WeakMap|Object)} Root cache object.
	 */
	function getRootCache() {
		return rootCache;
	}

	/**
	 * Returns the cache for a given dependants array. When possible, a WeakMap
	 * will be used to create a unique cache for each set of dependants. This
	 * is feasible due to the nature of WeakMap in allowing garbage collection
	 * to occur on entries where the key object is no longer referenced. Since
	 * WeakMap requires the key to be an object, this is only possible when the
	 * dependant is object-like. The root cache is created as a hierarchy where
	 * each top-level key is the first entry in a dependants set, the value a
	 * WeakMap where each key is the next dependant, and so on. This continues
	 * so long as the dependants are object-like. If no dependants are object-
	 * like, then the cache is shared across all invocations.
	 *
	 * @see isObjectLike
	 *
	 * @param {Array} dependants Selector dependants.
	 *
	 * @return {Object} Cache object.
	 */
	function getWeakMapCache( dependants ) {
		var caches = rootCache,
			isUniqueByDependants = true,
			i, dependant, map, cache;

		for ( i = 0; i < dependants.length; i++ ) {
			dependant = dependants[ i ];

			// Can only compose WeakMap from object-like key.
			if ( ! isObjectLike( dependant ) ) {
				isUniqueByDependants = false;
				break;
			}

			// Does current segment of cache already have a WeakMap?
			if ( caches.has( dependant ) ) {
				// Traverse into nested WeakMap.
				caches = caches.get( dependant );
			} else {
				// Create, set, and traverse into a new one.
				map = new WeakMap();
				caches.set( dependant, map );
				caches = map;
			}
		}

		// We use an arbitrary (but consistent) object as key for the last item
		// in the WeakMap to serve as our running cache.
		if ( ! caches.has( LEAF_KEY ) ) {
			cache = createCache();
			cache.isUniqueByDependants = isUniqueByDependants;
			caches.set( LEAF_KEY, cache );
		}

		return caches.get( LEAF_KEY );
	}

	// Assign cache handler by availability of WeakMap
	getCache = hasWeakMap ? getWeakMapCache : getRootCache;

	/**
	 * Resets root memoization cache.
	 */
	function clear() {
		rootCache = hasWeakMap ? new WeakMap() : createCache();
	}

	// eslint-disable-next-line jsdoc/check-param-names
	/**
	 * The augmented selector call, considering first whether dependants have
	 * changed before passing it to underlying memoize function.
	 *
	 * @param {Object} source    Source object for derivation.
	 * @param {...*}   extraArgs Additional arguments to pass to selector.
	 *
	 * @return {*} Selector result.
	 */
	function callSelector( /* source, ...extraArgs */ ) {
		var len = arguments.length,
			cache, node, i, args, dependants;

		// Create copy of arguments (avoid leaking deoptimization).
		args = new Array( len );
		for ( i = 0; i < len; i++ ) {
			args[ i ] = arguments[ i ];
		}

		dependants = getDependants.apply( null, args );
		cache = getCache( dependants );

		// If not guaranteed uniqueness by dependants (primitive type or lack
		// of WeakMap support), shallow compare against last dependants and, if
		// references have changed, destroy cache to recalculate result.
		if ( ! cache.isUniqueByDependants ) {
			if ( cache.lastDependants && ! isShallowEqual( dependants, cache.lastDependants, 0 ) ) {
				cache.clear();
			}

			cache.lastDependants = dependants;
		}

		node = cache.head;
		while ( node ) {
			// Check whether node arguments match arguments
			if ( ! isShallowEqual( node.args, args, 1 ) ) {
				node = node.next;
				continue;
			}

			// At this point we can assume we've found a match

			// Surface matched node to head if not already
			if ( node !== cache.head ) {
				// Adjust siblings to point to each other.
				node.prev.next = node.next;
				if ( node.next ) {
					node.next.prev = node.prev;
				}

				node.next = cache.head;
				node.prev = null;
				cache.head.prev = node;
				cache.head = node;
			}

			// Return immediately
			return node.val;
		}

		// No cached value found. Continue to insertion phase:

		node = {
			// Generate the result from original function
			val: selector.apply( null, args ),
		};

		// Avoid including the source object in the cache.
		args[ 0 ] = null;
		node.args = args;

		// Don't need to check whether node is already head, since it would
		// have been returned above already if it was

		// Shift existing head down list
		if ( cache.head ) {
			cache.head.prev = node;
			node.next = cache.head;
		}

		cache.head = node;

		return node.val;
	}

	callSelector.getDependants = getDependants;
	callSelector.clear = clear;
	clear();

	return callSelector;
}

;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/template-hierarchy.js
/**
 * External dependencies
 */

/**
 * Internal dependencies
 */


function isTemplateSuperseded(slug, existingSlugs, showOnFront) {
  if (!TEMPLATE_OVERRIDES[slug]) {
    return false;
  } // `home` template is unused if it is superseded by `front-page`
  // or "show on front" is set to show a page rather than blog posts.


  if (slug === 'home' && showOnFront !== 'posts') {
    return true;
  }

  return TEMPLATE_OVERRIDES[slug].every(overrideSlug => existingSlugs.includes(overrideSlug) || isTemplateSuperseded(overrideSlug, existingSlugs, showOnFront));
}
function getTemplateLocation(slug) {
  const isTopLevelTemplate = TEMPLATES_TOP_LEVEL.includes(slug);

  if (isTopLevelTemplate) {
    return MENU_TEMPLATES;
  }

  const isGeneralTemplate = TEMPLATES_GENERAL.includes(slug);

  if (isGeneralTemplate) {
    return MENU_TEMPLATES_GENERAL;
  }

  const isPostsTemplate = TEMPLATES_POSTS_PREFIXES.some(prefix => slug.startsWith(prefix));

  if (isPostsTemplate) {
    return MENU_TEMPLATES_POSTS;
  }

  const isPagesTemplate = TEMPLATES_PAGES_PREFIXES.some(prefix => slug.startsWith(prefix));

  if (isPagesTemplate) {
    return MENU_TEMPLATES_PAGES;
  }

  return MENU_TEMPLATES_GENERAL;
}
function getUnusedTemplates(templates, showOnFront) {
  const templateSlugs = (0,external_lodash_namespaceObject.map)(templates, 'slug');
  const supersededTemplates = templates.filter(({
    slug
  }) => isTemplateSuperseded(slug, templateSlugs, showOnFront));
  return supersededTemplates;
}
function getTemplatesLocationMap(templates) {
  return templates.reduce((obj, template) => {
    obj[template.slug] = getTemplateLocation(template.slug);
    return obj;
  }, {});
}
//# sourceMappingURL=template-hierarchy.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/store/selectors.js
/**
 * External dependencies
 */


/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */



/**
 * @typedef {'template'|'template_type'} TemplateType Template type.
 */

/**
 * Returns whether the given feature is enabled or not.
 *
 * @param {Object} state   Global application state.
 * @param {string} feature Feature slug.
 *
 * @return {boolean} Is active.
 */

function selectors_isFeatureActive(state, feature) {
  return (0,external_lodash_namespaceObject.get)(state.preferences.features, [feature], false);
}
/**
 * Returns the current editing canvas device type.
 *
 * @param {Object} state Global application state.
 *
 * @return {string} Device type.
 */

function __experimentalGetPreviewDeviceType(state) {
  return state.deviceType;
}
/**
 * Returns whether the current user can create media or not.
 *
 * @param {Object} state Global application state.
 *
 * @return {Object} Whether the current user can create media or not.
 */

const getCanUserCreateMedia = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => () => select(external_wp_coreData_namespaceObject.store).canUser('create', 'media'));
/**
 * Returns the settings, taking into account active features and permissions.
 *
 * @param {Object}   state             Global application state.
 * @param {Function} setIsInserterOpen Setter for the open state of the global inserter.
 *
 * @return {Object} Settings.
 */

const getSettings = rememo((state, setIsInserterOpen) => {
  const settings = { ...state.settings,
    outlineMode: true,
    focusMode: selectors_isFeatureActive(state, 'focusMode'),
    hasFixedToolbar: selectors_isFeatureActive(state, 'fixedToolbar'),
    __experimentalSetIsInserterOpened: setIsInserterOpen
  };
  const canUserCreateMedia = getCanUserCreateMedia(state);

  if (!canUserCreateMedia) {
    return settings;
  }

  settings.mediaUpload = ({
    onError,
    ...rest
  }) => {
    (0,external_wp_mediaUtils_namespaceObject.uploadMedia)({
      wpAllowedMimeTypes: state.settings.allowedMimeTypes,
      onError: ({
        message
      }) => onError(message),
      ...rest
    });
  };

  return settings;
}, state => [getCanUserCreateMedia(state), state.settings, selectors_isFeatureActive(state, 'focusMode'), selectors_isFeatureActive(state, 'fixedToolbar')]);
/**
 * Returns the current home template ID.
 *
 * @param {Object} state Global application state.
 *
 * @return {number?} Home template ID.
 */

function getHomeTemplateId(state) {
  return state.homeTemplateId;
}

function getCurrentEditedPost(state) {
  return state.editedPost[state.editedPost.length - 1] || {};
}

function getPreviousEditedPost(state) {
  return state.editedPost[state.editedPost.length - 2] || {};
}
/**
 * Returns the current edited post type (wp_template or wp_template_part).
 *
 * @param {Object} state Global application state.
 *
 * @return {TemplateType?} Template type.
 */


function getEditedPostType(state) {
  return getCurrentEditedPost(state).type;
}
/**
 * Returns the ID of the currently edited template or template part.
 *
 * @param {Object} state Global application state.
 *
 * @return {string?} Post ID.
 */

function getEditedPostId(state) {
  return getCurrentEditedPost(state).id;
}
/**
 * Returns the previous edited post type (wp_template or wp_template_part).
 *
 * @param {Object} state Global application state.
 *
 * @return {TemplateType?} Template type.
 */

function getPreviousEditedPostType(state) {
  return getPreviousEditedPost(state).type;
}
/**
 * Returns the ID of the previous edited template or template part.
 *
 * @param {Object} state Global application state.
 *
 * @return {string?} Post ID.
 */

function getPreviousEditedPostId(state) {
  return getPreviousEditedPost(state).id;
}
/**
 * Returns the current page object.
 *
 * @param {Object} state Global application state.
 *
 * @return {Object} Page.
 */

function getPage(state) {
  return getCurrentEditedPost(state).page;
}
/**
 * Returns the active menu in the navigation panel.
 *
 * @param {Object} state Global application state.
 *
 * @return {string} Active menu.
 */

function getNavigationPanelActiveMenu(state) {
  return state.navigationPanel.menu;
}
/**
 * Returns the current template or template part's corresponding
 * navigation panel's sub menu, to be used with `openNavigationPanelToMenu`.
 *
 * @param {Object} state Global application state.
 *
 * @return {string} The current template or template part's sub menu.
 */

const getCurrentTemplateNavigationPanelSubMenu = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => state => {
  const templateType = getEditedPostType(state);
  const templateId = getEditedPostId(state);
  const template = templateId ? select(external_wp_coreData_namespaceObject.store).getEntityRecord('postType', templateType, templateId) : null;

  if (!template) {
    return MENU_ROOT;
  }

  if ('wp_template_part' === templateType) {
    var _TEMPLATE_PARTS_SUB_M;

    return ((_TEMPLATE_PARTS_SUB_M = TEMPLATE_PARTS_SUB_MENUS.find(submenu => submenu.area === (template === null || template === void 0 ? void 0 : template.area))) === null || _TEMPLATE_PARTS_SUB_M === void 0 ? void 0 : _TEMPLATE_PARTS_SUB_M.menu) || MENU_TEMPLATE_PARTS;
  }

  const templates = select(external_wp_coreData_namespaceObject.store).getEntityRecords('postType', 'wp_template');
  const showOnFront = select(external_wp_coreData_namespaceObject.store).getEditedEntityRecord('root', 'site').show_on_front;

  if (isTemplateSuperseded(template.slug, (0,external_lodash_namespaceObject.map)(templates, 'slug'), showOnFront)) {
    return MENU_TEMPLATES_UNUSED;
  }

  return getTemplateLocation(template.slug);
});
/**
 * Returns the current opened/closed state of the navigation panel.
 *
 * @param {Object} state Global application state.
 *
 * @return {boolean} True if the navigation panel should be open; false if closed.
 */

function isNavigationOpened(state) {
  return state.navigationPanel.isOpen;
}
/**
 * Returns the current opened/closed state of the inserter panel.
 *
 * @param {Object} state Global application state.
 *
 * @return {boolean} True if the inserter panel should be open; false if closed.
 */

function isInserterOpened(state) {
  return !!state.blockInserterPanel;
}
/**
 * Get the insertion point for the inserter.
 *
 * @param {Object} state Global application state.
 *
 * @return {Object} The root client ID, index to insert at and starting filter value.
 */

function __experimentalGetInsertionPoint(state) {
  const {
    rootClientId,
    insertionIndex,
    filterValue
  } = state.blockInserterPanel;
  return {
    rootClientId,
    insertionIndex,
    filterValue
  };
}
/**
 * Returns the current opened/closed state of the list view panel.
 *
 * @param {Object} state Global application state.
 *
 * @return {boolean} True if the list view panel should be open; false if closed.
 */

function isListViewOpened(state) {
  return state.listViewPanel;
}
/**
 * Returns the template parts and their blocks for the current edited template.
 *
 * @param {Object} state Global application state.
 * @return {Array} Template parts and their blocks in an array.
 */

const getCurrentTemplateTemplateParts = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => state => {
  var _template$blocks;

  const templateType = getEditedPostType(state);
  const templateId = getEditedPostId(state);
  const template = select(external_wp_coreData_namespaceObject.store).getEditedEntityRecord('postType', templateType, templateId);
  const templateParts = select(external_wp_coreData_namespaceObject.store).getEntityRecords('postType', 'wp_template_part');
  const templatePartsById = (0,external_lodash_namespaceObject.keyBy)(templateParts, templatePart => templatePart.id);
  return ((_template$blocks = template.blocks) !== null && _template$blocks !== void 0 ? _template$blocks : []).filter(block => (0,external_wp_blocks_namespaceObject.isTemplatePart)(block)).map(block => {
    const {
      attributes: {
        theme,
        slug
      }
    } = block;
    const templatePartId = `${theme}//${slug}`;
    const templatePart = templatePartsById[templatePartId];
    return {
      templatePart,
      block
    };
  }).filter(({
    templatePart
  }) => !!templatePart);
});
//# sourceMappingURL=selectors.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/store/index.js
/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */





const storeConfig = {
  reducer: reducer,
  actions: store_actions_namespaceObject,
  selectors: store_selectors_namespaceObject,
  controls: external_wp_dataControls_namespaceObject.controls,
  persist: ['preferences']
};
const store_store = (0,external_wp_data_namespaceObject.createReduxStore)(STORE_NAME, storeConfig); // Once we build a more generic persistence plugin that works across types of stores
// we'd be able to replace this with a register call.

(0,external_wp_data_namespaceObject.registerStore)(STORE_NAME, storeConfig);
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: external ["wp","blockEditor"]
var external_wp_blockEditor_namespaceObject = window["wp"]["blockEditor"];
;// CONCATENATED MODULE: external ["wp","editor"]
var external_wp_editor_namespaceObject = window["wp"]["editor"];
;// CONCATENATED MODULE: external ["wp","keyboardShortcuts"]
var external_wp_keyboardShortcuts_namespaceObject = window["wp"]["keyboardShortcuts"];
;// CONCATENATED MODULE: ./packages/icons/build-module/library/plus.js


/**
 * WordPress dependencies
 */

const plus = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M18 11.2h-5.2V6h-1.6v5.2H6v1.6h5.2V18h1.6v-5.2H18z"
}));
/* harmony default export */ var library_plus = (plus);
//# sourceMappingURL=plus.js.map
;// CONCATENATED MODULE: ./packages/icons/build-module/library/list-view.js


/**
 * WordPress dependencies
 */

const listView = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  viewBox: "0 0 24 24",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M13.8 5.2H3v1.5h10.8V5.2zm-3.6 12v1.5H21v-1.5H10.2zm7.2-6H6.6v1.5h10.8v-1.5z"
}));
/* harmony default export */ var list_view = (listView);
//# sourceMappingURL=list-view.js.map
;// CONCATENATED MODULE: ./packages/icons/build-module/library/wordpress.js


/**
 * WordPress dependencies
 */

const wordpress = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "-2 -2 24 24"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M20 10c0-5.51-4.49-10-10-10C4.48 0 0 4.49 0 10c0 5.52 4.48 10 10 10 5.51 0 10-4.48 10-10zM7.78 15.37L4.37 6.22c.55-.02 1.17-.08 1.17-.08.5-.06.44-1.13-.06-1.11 0 0-1.45.11-2.37.11-.18 0-.37 0-.58-.01C4.12 2.69 6.87 1.11 10 1.11c2.33 0 4.45.87 6.05 2.34-.68-.11-1.65.39-1.65 1.58 0 .74.45 1.36.9 2.1.35.61.55 1.36.55 2.46 0 1.49-1.4 5-1.4 5l-3.03-8.37c.54-.02.82-.17.82-.17.5-.05.44-1.25-.06-1.22 0 0-1.44.12-2.38.12-.87 0-2.33-.12-2.33-.12-.5-.03-.56 1.2-.06 1.22l.92.08 1.26 3.41zM17.41 10c.24-.64.74-1.87.43-4.25.7 1.29 1.05 2.71 1.05 4.25 0 3.29-1.73 6.24-4.4 7.78.97-2.59 1.94-5.2 2.92-7.78zM6.1 18.09C3.12 16.65 1.11 13.53 1.11 10c0-1.3.23-2.48.72-3.59C3.25 10.3 4.67 14.2 6.1 18.09zm4.03-6.63l2.58 6.98c-.86.29-1.76.45-2.71.45-.79 0-1.57-.11-2.29-.33.81-2.38 1.62-4.74 2.42-7.1z"
}));
/* harmony default export */ var library_wordpress = (wordpress);
//# sourceMappingURL=wordpress.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/header/navigation-link/index.js


/**
 * WordPress dependencies
 */







function NavigationLink({
  icon
}) {
  const {
    isRequestingSiteIcon,
    siteIconUrl
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      getEntityRecord,
      isResolving
    } = select(external_wp_coreData_namespaceObject.store);
    const siteData = getEntityRecord('root', '__unstableBase', undefined) || {};
    return {
      isRequestingSiteIcon: isResolving('core', 'getEntityRecord', ['root', '__unstableBase', undefined]),
      siteIconUrl: siteData.site_icon_url
    };
  }, []);
  const disableMotion = (0,external_wp_compose_namespaceObject.useReducedMotion)();
  let buttonIcon = (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Icon, {
    size: "36px",
    icon: library_wordpress
  });
  const effect = {
    expand: {
      scale: 1.7,
      borderRadius: 0,
      transition: {
        type: 'tween',
        duration: '0.2'
      }
    }
  };

  if (siteIconUrl) {
    buttonIcon = (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__unstableMotion.img, {
      variants: !disableMotion && effect,
      alt: (0,external_wp_i18n_namespaceObject.__)('Site Icon'),
      className: "edit-site-navigation-link__site-icon",
      src: siteIconUrl
    });
  } else if (isRequestingSiteIcon) {
    buttonIcon = null;
  } else if (icon) {
    buttonIcon = (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Icon, {
      size: "36px",
      icon: icon
    });
  }

  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__unstableMotion.div, {
    className: "edit-site-navigation-link",
    whileHover: "expand"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    className: "edit-site-navigation-link__button has-icon",
    label: (0,external_wp_i18n_namespaceObject.__)('Dashboard'),
    href: "index.php"
  }, buttonIcon));
}

/* harmony default export */ var navigation_link = (NavigationLink);
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/icons/build-module/library/more-vertical.js


/**
 * WordPress dependencies
 */

const moreVertical = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M13 19h-2v-2h2v2zm0-6h-2v-2h2v2zm0-6h-2V5h2v2z"
}));
/* harmony default export */ var more_vertical = (moreVertical);
//# sourceMappingURL=more-vertical.js.map
;// CONCATENATED MODULE: external ["wp","a11y"]
var external_wp_a11y_namespaceObject = window["wp"]["a11y"];
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/header/feature-toggle/index.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */






/**
 * Internal dependencies
 */


function FeatureToggle({
  feature,
  label,
  info,
  messageActivated,
  messageDeactivated
}) {
  const speakMessage = () => {
    if (isActive) {
      (0,external_wp_a11y_namespaceObject.speak)(messageDeactivated || (0,external_wp_i18n_namespaceObject.__)('Feature deactivated'));
    } else {
      (0,external_wp_a11y_namespaceObject.speak)(messageActivated || (0,external_wp_i18n_namespaceObject.__)('Feature activated'));
    }
  };

  const isActive = (0,external_wp_data_namespaceObject.useSelect)(select => {
    return select(store_store).isFeatureActive(feature);
  }, []);
  const {
    toggleFeature
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuItem, {
    icon: isActive && library_check,
    isSelected: isActive,
    onClick: (0,external_lodash_namespaceObject.flow)(toggleFeature.bind(null, feature), speakMessage),
    role: "menuitemcheckbox",
    info: info
  }, label);
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/header/more-menu/index.js


/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */



const POPOVER_PROPS = {
  className: 'edit-site-more-menu__content',
  position: 'bottom left'
};
const TOGGLE_PROPS = {
  tooltipPosition: 'bottom'
};

const MoreMenu = () => (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.DropdownMenu, {
  className: "edit-site-more-menu",
  icon: more_vertical,
  label: (0,external_wp_i18n_namespaceObject.__)('More tools & options'),
  popoverProps: POPOVER_PROPS,
  toggleProps: TOGGLE_PROPS
}, ({
  onClose
}) => (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuGroup, {
  label: (0,external_wp_i18n_namespaceObject._x)('View', 'noun')
}, (0,external_wp_element_namespaceObject.createElement)(FeatureToggle, {
  feature: "fixedToolbar",
  label: (0,external_wp_i18n_namespaceObject.__)('Top toolbar'),
  info: (0,external_wp_i18n_namespaceObject.__)('Access all block and document tools in a single place'),
  messageActivated: (0,external_wp_i18n_namespaceObject.__)('Top toolbar activated'),
  messageDeactivated: (0,external_wp_i18n_namespaceObject.__)('Top toolbar deactivated')
}), (0,external_wp_element_namespaceObject.createElement)(FeatureToggle, {
  feature: "focusMode",
  label: (0,external_wp_i18n_namespaceObject.__)('Spotlight mode'),
  info: (0,external_wp_i18n_namespaceObject.__)('Focus on one block at a time'),
  messageActivated: (0,external_wp_i18n_namespaceObject.__)('Spotlight mode activated'),
  messageDeactivated: (0,external_wp_i18n_namespaceObject.__)('Spotlight mode deactivated')
}), (0,external_wp_element_namespaceObject.createElement)(action_item.Slot, {
  name: "core/edit-site/plugin-more-menu",
  label: (0,external_wp_i18n_namespaceObject.__)('Plugins'),
  as: external_wp_components_namespaceObject.MenuGroup,
  fillProps: {
    onClick: onClose
  }
})), (0,external_wp_element_namespaceObject.createElement)(tools_more_menu_group.Slot, {
  fillProps: {
    onClose
  }
})));

/* harmony default export */ var more_menu = (MoreMenu);
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/save-button/index.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */





function SaveButton({
  openEntitiesSavedStates,
  isEntitiesSavedStatesOpen
}) {
  const {
    isDirty,
    isSaving
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      __experimentalGetDirtyEntityRecords,
      isSavingEntityRecord
    } = select(external_wp_coreData_namespaceObject.store);

    const dirtyEntityRecords = __experimentalGetDirtyEntityRecords();

    return {
      isDirty: dirtyEntityRecords.length > 0,
      isSaving: (0,external_lodash_namespaceObject.some)(dirtyEntityRecords, record => isSavingEntityRecord(record.kind, record.name, record.key))
    };
  }, []);
  const disabled = !isDirty || isSaving;
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    variant: "primary",
    className: "edit-site-save-button__button",
    "aria-disabled": disabled,
    "aria-expanded": isEntitiesSavedStatesOpen,
    disabled: disabled,
    isBusy: isSaving,
    onClick: disabled ? undefined : openEntitiesSavedStates
  }, (0,external_wp_i18n_namespaceObject.__)('Save')));
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/icons/build-module/library/undo.js


/**
 * WordPress dependencies
 */

const undo = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M18.3 11.7c-.6-.6-1.4-.9-2.3-.9H6.7l2.9-3.3-1.1-1-4.5 5L8.5 16l1-1-2.7-2.7H16c.5 0 .9.2 1.3.5 1 1 1 3.4 1 4.5v.3h1.5v-.2c0-1.5 0-4.3-1.5-5.7z"
}));
/* harmony default export */ var library_undo = (undo);
//# sourceMappingURL=undo.js.map
;// CONCATENATED MODULE: ./packages/icons/build-module/library/redo.js


/**
 * WordPress dependencies
 */

const redo = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M15.6 6.5l-1.1 1 2.9 3.3H8c-.9 0-1.7.3-2.3.9-1.4 1.5-1.4 4.2-1.4 5.6v.2h1.5v-.3c0-1.1 0-3.5 1-4.5.3-.3.7-.5 1.3-.5h9.2L14.5 15l1.1 1.1 4.6-4.6-4.6-5z"
}));
/* harmony default export */ var library_redo = (redo);
//# sourceMappingURL=redo.js.map
;// CONCATENATED MODULE: external ["wp","keycodes"]
var external_wp_keycodes_namespaceObject = window["wp"]["keycodes"];
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/header/undo-redo/undo.js


/**
 * WordPress dependencies
 */






function UndoButton() {
  const hasUndo = (0,external_wp_data_namespaceObject.useSelect)(select => select(external_wp_coreData_namespaceObject.store).hasUndo(), []);
  const {
    undo
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_coreData_namespaceObject.store);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    icon: !(0,external_wp_i18n_namespaceObject.isRTL)() ? library_undo : library_redo,
    label: (0,external_wp_i18n_namespaceObject.__)('Undo'),
    shortcut: external_wp_keycodes_namespaceObject.displayShortcut.primary('z') // If there are no undo levels we don't want to actually disable this
    // button, because it will remove focus for keyboard users.
    // See: https://github.com/WordPress/gutenberg/issues/3486
    ,
    "aria-disabled": !hasUndo,
    onClick: hasUndo ? undo : undefined
  });
}
//# sourceMappingURL=undo.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/header/undo-redo/redo.js


/**
 * WordPress dependencies
 */






function RedoButton() {
  const hasRedo = (0,external_wp_data_namespaceObject.useSelect)(select => select(external_wp_coreData_namespaceObject.store).hasRedo(), []);
  const {
    redo
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_coreData_namespaceObject.store);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    icon: !(0,external_wp_i18n_namespaceObject.isRTL)() ? library_redo : library_undo,
    label: (0,external_wp_i18n_namespaceObject.__)('Redo'),
    shortcut: external_wp_keycodes_namespaceObject.displayShortcut.primaryShift('z') // If there are no undo levels we don't want to actually disable this
    // button, because it will remove focus for keyboard users.
    // See: https://github.com/WordPress/gutenberg/issues/3486
    ,
    "aria-disabled": !hasRedo,
    onClick: hasRedo ? redo : undefined
  });
}
//# sourceMappingURL=redo.js.map
;// CONCATENATED MODULE: ./packages/icons/build-module/library/chevron-down.js


/**
 * WordPress dependencies
 */

const chevronDown = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  viewBox: "0 0 24 24",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"
}));
/* harmony default export */ var chevron_down = (chevronDown);
//# sourceMappingURL=chevron-down.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/header/document-actions/index.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */









function getBlockDisplayText(block) {
  if (block) {
    const blockType = (0,external_wp_blocks_namespaceObject.getBlockType)(block.name);
    return blockType ? (0,external_wp_blocks_namespaceObject.__experimentalGetBlockLabel)(blockType, block.attributes) : null;
  }

  return null;
}

function useSecondaryText() {
  const {
    getBlock
  } = (0,external_wp_data_namespaceObject.useSelect)(external_wp_blockEditor_namespaceObject.store);
  const activeEntityBlockId = (0,external_wp_data_namespaceObject.useSelect)(select => select(external_wp_blockEditor_namespaceObject.store).__experimentalGetActiveBlockIdByBlockNames(['core/template-part']), []);

  if (activeEntityBlockId) {
    return {
      label: getBlockDisplayText(getBlock(activeEntityBlockId)),
      isActive: true
    };
  }

  return {};
}
/**
 * @param {Object}   props             Props for the DocumentActions component.
 * @param {string}   props.entityTitle The title to display.
 * @param {string}   props.entityLabel A label to use for entity-related options.
 *                                     E.g. "template" would be used for "edit
 *                                     template" and "show template details".
 * @param {boolean}  props.isLoaded    Whether the data is available.
 * @param {Function} props.children    React component to use for the
 *                                     information dropdown area. Should be a
 *                                     function which accepts dropdown props.
 */


function DocumentActions({
  entityTitle,
  entityLabel,
  isLoaded,
  children: dropdownContent
}) {
  const {
    label
  } = useSecondaryText(); // The title ref is passed to the popover as the anchorRef so that the
  // dropdown is centered over the whole title area rather than just one
  // part of it.

  const titleRef = (0,external_wp_element_namespaceObject.useRef)(); // Return a simple loading indicator until we have information to show.

  if (!isLoaded) {
    return (0,external_wp_element_namespaceObject.createElement)("div", {
      className: "edit-site-document-actions"
    }, (0,external_wp_i18n_namespaceObject.__)('Loading…'));
  } // Return feedback that the template does not seem to exist.


  if (!entityTitle) {
    return (0,external_wp_element_namespaceObject.createElement)("div", {
      className: "edit-site-document-actions"
    }, (0,external_wp_i18n_namespaceObject.__)('Template not found'));
  }

  return (0,external_wp_element_namespaceObject.createElement)("div", {
    className: classnames_default()('edit-site-document-actions', {
      'has-secondary-label': !!label
    })
  }, (0,external_wp_element_namespaceObject.createElement)("div", {
    ref: titleRef,
    className: "edit-site-document-actions__title-wrapper"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalText, {
    size: "body",
    className: "edit-site-document-actions__title-prefix"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.VisuallyHidden, {
    as: "span"
  }, (0,external_wp_i18n_namespaceObject.sprintf)(
  /* translators: %s: the entity being edited, like "template"*/
  (0,external_wp_i18n_namespaceObject.__)('Editing %s:'), entityLabel))), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalText, {
    size: "body",
    className: "edit-site-document-actions__title",
    as: "h1"
  }, entityTitle), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalText, {
    size: "body",
    className: "edit-site-document-actions__secondary-item"
  }, label !== null && label !== void 0 ? label : ''), dropdownContent && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Dropdown, {
    popoverProps: {
      anchorRef: titleRef.current
    },
    position: "bottom center",
    renderToggle: ({
      isOpen,
      onToggle
    }) => (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
      className: "edit-site-document-actions__get-info",
      icon: chevron_down,
      "aria-expanded": isOpen,
      "aria-haspopup": "true",
      onClick: onToggle,
      label: (0,external_wp_i18n_namespaceObject.sprintf)(
      /* translators: %s: the entity to see details about, like "template"*/
      (0,external_wp_i18n_namespaceObject.__)('Show %s details'), entityLabel)
    }),
    contentClassName: "edit-site-document-actions__info-dropdown",
    renderContent: dropdownContent
  })));
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/template-details/template-areas.js


/**
 * WordPress dependencies
 */






/**
 * Internal dependencies
 */





function TemplatePartItemMore({
  onClose,
  templatePart,
  closeTemplateDetailsDropdown
}) {
  var _templatePart$title;

  const {
    pushTemplatePart,
    revertTemplate
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);

  function editTemplatePart() {
    pushTemplatePart(templatePart.id);
    onClose();
    closeTemplateDetailsDropdown();
  }

  function clearCustomizations() {
    revertTemplate(templatePart);
    onClose();
    closeTemplateDetailsDropdown();
  }

  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuGroup, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuItem, {
    onClick: editTemplatePart
  }, (0,external_wp_i18n_namespaceObject.sprintf)(
  /* translators: %s: template part title */
  (0,external_wp_i18n_namespaceObject.__)('Edit %s'), (_templatePart$title = templatePart.title) === null || _templatePart$title === void 0 ? void 0 : _templatePart$title.rendered))), isTemplateRevertable(templatePart) && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuGroup, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuItem, {
    info: (0,external_wp_i18n_namespaceObject.__)('Restore template to theme default'),
    onClick: clearCustomizations
  }, (0,external_wp_i18n_namespaceObject.__)('Clear customizations'))));
}

function TemplatePartItem({
  templatePart,
  clientId,
  closeTemplateDetailsDropdown
}) {
  const {
    selectBlock,
    toggleBlockHighlight
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_blockEditor_namespaceObject.store);

  const highlightBlock = () => toggleBlockHighlight(clientId, true);

  const cancelHighlightBlock = () => toggleBlockHighlight(clientId, false);

  return (0,external_wp_element_namespaceObject.createElement)("div", {
    role: "menuitem",
    className: "edit-site-template-details__template-areas-item"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuItem, {
    role: "button",
    icon: (0,external_wp_editor_namespaceObject.getTemplatePartIcon)(templatePart.area),
    iconPosition: "left",
    onClick: () => {
      selectBlock(clientId);
    },
    onMouseOver: highlightBlock,
    onMouseLeave: cancelHighlightBlock,
    onFocus: highlightBlock,
    onBlur: cancelHighlightBlock
  }, TEMPLATE_PART_AREA_TO_NAME[templatePart.area]), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.DropdownMenu, {
    icon: more_vertical,
    label: (0,external_wp_i18n_namespaceObject.__)('More options'),
    className: "edit-site-template-details__template-areas-item-more"
  }, ({
    onClose
  }) => (0,external_wp_element_namespaceObject.createElement)(TemplatePartItemMore, {
    onClose: onClose,
    templatePart: templatePart,
    closeTemplateDetailsDropdown: closeTemplateDetailsDropdown
  })));
}

function TemplateAreas({
  closeTemplateDetailsDropdown
}) {
  const templateParts = (0,external_wp_data_namespaceObject.useSelect)(select => select(store_store).getCurrentTemplateTemplateParts(), []);

  if (!templateParts.length) {
    return null;
  }

  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuGroup, {
    label: (0,external_wp_i18n_namespaceObject.__)('Areas'),
    className: "edit-site-template-details__group edit-site-template-details__template-areas"
  }, templateParts.map(({
    templatePart,
    block
  }) => (0,external_wp_element_namespaceObject.createElement)(TemplatePartItem, {
    key: templatePart.slug,
    clientId: block.clientId,
    templatePart: templatePart,
    closeTemplateDetailsDropdown: closeTemplateDetailsDropdown
  })));
}
//# sourceMappingURL=template-areas.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/template-details/index.js



/**
 * WordPress dependencies
 */






/**
 * Internal dependencies
 */





function TemplateDetails({
  template,
  onClose
}) {
  const {
    title,
    description
  } = (0,external_wp_data_namespaceObject.useSelect)(select => select(external_wp_editor_namespaceObject.store).__experimentalGetTemplateInfo(template), []);
  const newMenuSidebar = (0,external_wp_data_namespaceObject.useSelect)(select => select(store_store).getSettings().__experimentalNewMenuSidebar);
  const {
    openNavigationPanelToMenu,
    revertTemplate
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);
  const templateSubMenu = (0,external_wp_element_namespaceObject.useMemo)(() => {
    if ((template === null || template === void 0 ? void 0 : template.type) === 'wp_template') {
      return {
        title: (0,external_wp_i18n_namespaceObject.__)('templates'),
        menu: MENU_TEMPLATES
      };
    }

    return TEMPLATE_PARTS_SUB_MENUS.find(({
      area
    }) => area === (template === null || template === void 0 ? void 0 : template.area));
  }, [template]);

  if (!template) {
    return null;
  }

  const showTemplateInSidebar = () => {
    onClose();
    openNavigationPanelToMenu(templateSubMenu.menu);
  };

  const revert = () => {
    revertTemplate(template);
    onClose();
  };

  return (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-site-template-details"
  }, (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-site-template-details__group"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalHeading, {
    level: 4,
    weight: 600,
    className: "edit-site-template-details__title"
  }, title), description && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalText, {
    size: "body",
    className: "edit-site-template-details__description",
    as: "p"
  }, description)), (0,external_wp_element_namespaceObject.createElement)(TemplateAreas, {
    closeTemplateDetailsDropdown: onClose
  }), isTemplateRevertable(template) && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuGroup, {
    className: "edit-site-template-details__group edit-site-template-details__revert"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuItem, {
    className: "edit-site-template-details__revert-button",
    info: (0,external_wp_i18n_namespaceObject.__)('Restore template to theme default'),
    onClick: revert
  }, (0,external_wp_i18n_namespaceObject.__)('Clear customizations'))), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, _extends({
    className: "edit-site-template-details__show-all-button"
  }, newMenuSidebar ? {
    href: (0,external_wp_url_namespaceObject.addQueryArgs)('edit.php', {
      // TODO: We should update this to filter by template part's areas as well.
      post_type: template.type
    })
  } : {
    onClick: showTemplateInSidebar,
    'aria-label': (0,external_wp_i18n_namespaceObject.sprintf)(
    /* translators: %1$s: the template part's area name ("Headers", "Sidebars") or "templates". */
    (0,external_wp_i18n_namespaceObject.__)('Browse all %1$s. This will open the %1$s menu in the navigation side panel.'), templateSubMenu.title)
  }), (0,external_wp_i18n_namespaceObject.sprintf)(
  /* translators: the template part's area name ("Headers", "Sidebars") or "templates". */
  (0,external_wp_i18n_namespaceObject.__)('Browse all %s'), templateSubMenu.title)));
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/main-dashboard-button/index.js


/**
 * WordPress dependencies
 */

const slotName = '__experimentalMainDashboardButton';
const {
  Fill,
  Slot: MainDashboardButtonSlot
} = (0,external_wp_components_namespaceObject.createSlotFill)(slotName);
const MainDashboardButton = Fill;

const main_dashboard_button_Slot = ({
  children
}) => {
  const slot = (0,external_wp_components_namespaceObject.__experimentalUseSlot)(slotName);
  const hasFills = Boolean(slot.fills && slot.fills.length);

  if (!hasFills) {
    return children;
  }

  return (0,external_wp_element_namespaceObject.createElement)(MainDashboardButtonSlot, {
    bubblesVirtually: true
  });
};

MainDashboardButton.Slot = main_dashboard_button_Slot;
/* harmony default export */ var main_dashboard_button = (MainDashboardButton);
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/header/index.js


/**
 * WordPress dependencies
 */











/**
 * Internal dependencies
 */











const preventDefault = event => {
  event.preventDefault();
};

function Header({
  openEntitiesSavedStates,
  isEntitiesSavedStatesOpen
}) {
  const inserterButton = (0,external_wp_element_namespaceObject.useRef)();
  const {
    deviceType,
    entityTitle,
    template,
    templateType,
    isInserterOpen,
    isListViewOpen,
    listViewShortcut,
    isLoaded,
    newMenuSidebar
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      __experimentalGetPreviewDeviceType,
      getEditedPostType,
      getEditedPostId,
      isInserterOpened,
      isListViewOpened,
      getSettings
    } = select(store_store);
    const {
      getEditedEntityRecord
    } = select(external_wp_coreData_namespaceObject.store);
    const {
      __experimentalGetTemplateInfo: getTemplateInfo
    } = select(external_wp_editor_namespaceObject.store);
    const {
      getShortcutRepresentation
    } = select(external_wp_keyboardShortcuts_namespaceObject.store);
    const postType = getEditedPostType();
    const postId = getEditedPostId();
    const record = getEditedEntityRecord('postType', postType, postId);

    const _isLoaded = !!postId;

    return {
      deviceType: __experimentalGetPreviewDeviceType(),
      entityTitle: getTemplateInfo(record).title,
      isLoaded: _isLoaded,
      template: record,
      templateType: postType,
      isInserterOpen: isInserterOpened(),
      isListViewOpen: isListViewOpened(),
      listViewShortcut: getShortcutRepresentation('core/edit-site/toggle-list-view'),
      newMenuSidebar: getSettings().__experimentalNewMenuSidebar
    };
  }, []);
  const {
    __experimentalSetPreviewDeviceType: setPreviewDeviceType,
    setIsInserterOpened,
    setIsListViewOpened
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);
  const isLargeViewport = (0,external_wp_compose_namespaceObject.useViewportMatch)('medium');
  const openInserter = (0,external_wp_element_namespaceObject.useCallback)(() => {
    if (isInserterOpen) {
      // Focusing the inserter button closes the inserter popover
      inserterButton.current.focus();
    } else {
      setIsInserterOpened(true);
    }
  }, [isInserterOpen, setIsInserterOpened]);
  const toggleListView = (0,external_wp_element_namespaceObject.useCallback)(() => setIsListViewOpened(!isListViewOpen), [setIsListViewOpened, isListViewOpen]);
  const isFocusMode = templateType === 'wp_template_part';
  return (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-site-header"
  }, (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-site-header_start"
  }, newMenuSidebar && (0,external_wp_element_namespaceObject.createElement)(main_dashboard_button.Slot, null, (0,external_wp_element_namespaceObject.createElement)(navigation_link, null)), (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-site-header__toolbar"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    ref: inserterButton,
    variant: "primary",
    isPressed: isInserterOpen,
    className: "edit-site-header-toolbar__inserter-toggle",
    onMouseDown: preventDefault,
    onClick: openInserter,
    icon: library_plus,
    label: (0,external_wp_i18n_namespaceObject._x)('Toggle block inserter', 'Generic label for block inserter button')
  }), isLargeViewport && (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.ToolSelector, null), (0,external_wp_element_namespaceObject.createElement)(UndoButton, null), (0,external_wp_element_namespaceObject.createElement)(RedoButton, null), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    className: "edit-site-header-toolbar__list-view-toggle",
    icon: list_view,
    isPressed: isListViewOpen
    /* translators: button label text should, if possible, be under 16 characters. */
    ,
    label: (0,external_wp_i18n_namespaceObject.__)('List View'),
    onClick: toggleListView,
    shortcut: listViewShortcut
  })))), (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-site-header_center"
  }, (0,external_wp_element_namespaceObject.createElement)(DocumentActions, {
    entityTitle: entityTitle,
    entityLabel: templateType === 'wp_template_part' ? 'template part' : 'template',
    isLoaded: isLoaded
  }, ({
    onClose
  }) => (0,external_wp_element_namespaceObject.createElement)(TemplateDetails, {
    template: template,
    onClose: onClose
  }))), (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-site-header_end"
  }, (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-site-header__actions"
  }, !isFocusMode && (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.__experimentalPreviewOptions, {
    deviceType: deviceType,
    setDeviceType: setPreviewDeviceType
  }), (0,external_wp_element_namespaceObject.createElement)(SaveButton, {
    openEntitiesSavedStates: openEntitiesSavedStates,
    isEntitiesSavedStatesOpen: isEntitiesSavedStatesOpen
  }), (0,external_wp_element_namespaceObject.createElement)(pinned_items.Slot, {
    scope: "core/edit-site"
  }), (0,external_wp_element_namespaceObject.createElement)(more_menu, null))));
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/icons/build-module/library/cog.js


/**
 * WordPress dependencies
 */

const cog = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  fillRule: "evenodd",
  d: "M10.289 4.836A1 1 0 0111.275 4h1.306a1 1 0 01.987.836l.244 1.466c.787.26 1.503.679 2.108 1.218l1.393-.522a1 1 0 011.216.437l.653 1.13a1 1 0 01-.23 1.273l-1.148.944a6.025 6.025 0 010 2.435l1.149.946a1 1 0 01.23 1.272l-.653 1.13a1 1 0 01-1.216.437l-1.394-.522c-.605.54-1.32.958-2.108 1.218l-.244 1.466a1 1 0 01-.987.836h-1.306a1 1 0 01-.986-.836l-.244-1.466a5.995 5.995 0 01-2.108-1.218l-1.394.522a1 1 0 01-1.217-.436l-.653-1.131a1 1 0 01.23-1.272l1.149-.946a6.026 6.026 0 010-2.435l-1.148-.944a1 1 0 01-.23-1.272l.653-1.131a1 1 0 011.217-.437l1.393.522a5.994 5.994 0 012.108-1.218l.244-1.466zM14.929 12a3 3 0 11-6 0 3 3 0 016 0z",
  clipRule: "evenodd"
}));
/* harmony default export */ var library_cog = (cog);
//# sourceMappingURL=cog.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/sidebar/default-sidebar.js


/**
 * WordPress dependencies
 */

function DefaultSidebar({
  className,
  identifier,
  title,
  icon,
  children,
  closeLabel,
  header,
  headerClassName
}) {
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(complementary_area, {
    className: className,
    scope: "core/edit-site",
    identifier: identifier,
    title: title,
    icon: icon,
    closeLabel: closeLabel,
    header: header,
    headerClassName: headerClassName
  }, children), (0,external_wp_element_namespaceObject.createElement)(ComplementaryAreaMoreMenuItem, {
    scope: "core/edit-site",
    identifier: identifier,
    icon: icon
  }, title));
}
//# sourceMappingURL=default-sidebar.js.map
;// CONCATENATED MODULE: ./packages/icons/build-module/library/styles.js


/**
 * WordPress dependencies
 */

const styles = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  viewBox: "0 0 24 24",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M12 4c-4.4 0-8 3.6-8 8v.1c0 4.1 3.2 7.5 7.2 7.9h.8c4.4 0 8-3.6 8-8s-3.6-8-8-8zm0 15V5c3.9 0 7 3.1 7 7s-3.1 7-7 7z"
}));
/* harmony default export */ var library_styles = (styles);
//# sourceMappingURL=styles.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/utils.js
/**
 * External dependencies
 */

/* Supporting data */

const ROOT_BLOCK_NAME = 'root';
const ROOT_BLOCK_SELECTOR = 'body';
const ROOT_BLOCK_SUPPORTS = (/* unused pure expression or super */ null && (['background', 'backgroundColor', 'color', 'linkColor', 'fontFamily', 'fontSize', 'fontStyle', 'fontWeight', 'lineHeight', 'textDecoration', 'textTransform', 'padding']));
const PRESET_METADATA = [{
  path: ['color', 'palette'],
  valueKey: 'color',
  cssVarInfix: 'color',
  classes: [{
    classSuffix: 'color',
    propertyName: 'color'
  }, {
    classSuffix: 'background-color',
    propertyName: 'background-color'
  }, {
    classSuffix: 'border-color',
    propertyName: 'border-color'
  }]
}, {
  path: ['color', 'gradients'],
  valueKey: 'gradient',
  cssVarInfix: 'gradient',
  classes: [{
    classSuffix: 'gradient-background',
    propertyName: 'background'
  }]
}, {
  path: ['typography', 'fontSizes'],
  valueKey: 'size',
  cssVarInfix: 'font-size',
  classes: [{
    classSuffix: 'font-size',
    propertyName: 'font-size'
  }]
}, {
  path: ['typography', 'fontFamilies'],
  valueKey: 'fontFamily',
  cssVarInfix: 'font-family',
  classes: [{
    classSuffix: 'font-family',
    propertyName: 'font-family'
  }]
}];
const STYLE_PATH_TO_CSS_VAR_INFIX = {
  'color.background': 'color',
  'color.text': 'color',
  'elements.link.color.text': 'color',
  'color.gradient': 'gradient',
  'typography.fontSize': 'font-size',
  'typography.fontFamily': 'font-family'
};

function findInPresetsBy(features, blockName, presetPath, presetProperty, presetValueValue) {
  // Block presets take priority above root level presets.
  const orderedPresetsByOrigin = [(0,external_lodash_namespaceObject.get)(features, ['blocks', blockName, ...presetPath]), (0,external_lodash_namespaceObject.get)(features, presetPath)];

  for (const presetByOrigin of orderedPresetsByOrigin) {
    if (presetByOrigin) {
      // Preset origins ordered by priority.
      const origins = ['user', 'theme', 'core'];

      for (const origin of origins) {
        const presets = presetByOrigin[origin];

        if (presets) {
          const presetObject = (0,external_lodash_namespaceObject.find)(presets, preset => preset[presetProperty] === presetValueValue);

          if (presetObject) {
            if (presetProperty === 'slug') {
              return presetObject;
            } // if there is a highest priority preset with the same slug but different value the preset we found was overwritten and should be ignored.


            const highestPresetObjectWithSameSlug = findInPresetsBy(features, blockName, presetPath, 'slug', presetObject.slug);

            if (highestPresetObjectWithSameSlug[presetProperty] === presetObject[presetProperty]) {
              return presetObject;
            }

            return undefined;
          }
        }
      }
    }
  }
}

function getPresetVariableFromValue(features, blockName, variableStylePath, presetPropertyValue) {
  if (!presetPropertyValue) {
    return presetPropertyValue;
  }

  const cssVarInfix = STYLE_PATH_TO_CSS_VAR_INFIX[variableStylePath];
  const metadata = (0,external_lodash_namespaceObject.find)(PRESET_METADATA, ['cssVarInfix', cssVarInfix]);

  if (!metadata) {
    // The property doesn't have preset data
    // so the value should be returned as it is.
    return presetPropertyValue;
  }

  const {
    valueKey,
    path
  } = metadata;
  const presetObject = findInPresetsBy(features, blockName, path, valueKey, presetPropertyValue);

  if (!presetObject) {
    // Value wasn't found in the presets,
    // so it must be a custom value.
    return presetPropertyValue;
  }

  return `var:preset|${cssVarInfix}|${presetObject.slug}`;
}

function getValueFromPresetVariable(features, blockName, variable, [presetType, slug]) {
  const metadata = (0,external_lodash_namespaceObject.find)(PRESET_METADATA, ['cssVarInfix', presetType]);

  if (!metadata) {
    return variable;
  }

  const presetObject = findInPresetsBy(features, blockName, metadata.path, 'slug', slug);

  if (presetObject) {
    const {
      valueKey
    } = metadata;
    const result = presetObject[valueKey];
    return getValueFromVariable(features, blockName, result);
  }

  return variable;
}

function getValueFromCustomVariable(features, blockName, variable, path) {
  var _get;

  const result = (_get = (0,external_lodash_namespaceObject.get)(features, ['blocks', blockName, 'custom', ...path])) !== null && _get !== void 0 ? _get : (0,external_lodash_namespaceObject.get)(features, ['custom', ...path]);

  if (!result) {
    return variable;
  } // A variable may reference another variable so we need recursion until we find the value.


  return getValueFromVariable(features, blockName, result);
}

function getValueFromVariable(features, blockName, variable) {
  if (!variable || !(0,external_lodash_namespaceObject.isString)(variable)) {
    return variable;
  }

  const USER_VALUE_PREFIX = 'var:';
  const THEME_VALUE_PREFIX = 'var(--wp--';
  const THEME_VALUE_SUFFIX = ')';
  let parsedVar;

  if (variable.startsWith(USER_VALUE_PREFIX)) {
    parsedVar = variable.slice(USER_VALUE_PREFIX.length).split('|');
  } else if (variable.startsWith(THEME_VALUE_PREFIX) && variable.endsWith(THEME_VALUE_SUFFIX)) {
    parsedVar = variable.slice(THEME_VALUE_PREFIX.length, -THEME_VALUE_SUFFIX.length).split('--');
  } else {
    // We don't know how to parse the value: either is raw of uses complex CSS such as `calc(1px * var(--wp--variable) )`
    return variable;
  }

  const [type, ...path] = parsedVar;

  if (type === 'preset') {
    return getValueFromPresetVariable(features, blockName, variable, path);
  }

  if (type === 'custom') {
    return getValueFromCustomVariable(features, blockName, variable, path);
  }

  return variable;
}
//# sourceMappingURL=utils.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/context.js
/**
 * WordPress dependencies
 */

const DEFAULT_GLOBAL_STYLES_CONTEXT = {
  user: {},
  base: {},
  merged: {},
  setUserConfig: () => {}
};
const GlobalStylesContext = (0,external_wp_element_namespaceObject.createContext)(DEFAULT_GLOBAL_STYLES_CONTEXT);
//# sourceMappingURL=context.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/hooks.js
/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */



const EMPTY_CONFIG = {
  isGlobalStylesUserThemeJSON: true,
  version: 1
};
const useGlobalStylesReset = () => {
  const {
    user: config,
    setUserConfig
  } = (0,external_wp_element_namespaceObject.useContext)(GlobalStylesContext);
  const canReset = !!config && !(0,external_lodash_namespaceObject.isEqual)(config, EMPTY_CONFIG);
  return [canReset, (0,external_wp_element_namespaceObject.useCallback)(() => setUserConfig(() => EMPTY_CONFIG), [setUserConfig])];
};
function useSetting(path, blockName, source = 'all') {
  var _getSettingValueForCo;

  const {
    merged: mergedConfig,
    base: baseConfig,
    user: userConfig,
    setUserConfig
  } = (0,external_wp_element_namespaceObject.useContext)(GlobalStylesContext);
  const fullPath = !blockName ? `settings.${path}` : `settings.blocks.${blockName}.${path}`;

  const setSetting = newValue => {
    setUserConfig(currentConfig => {
      const newUserConfig = (0,external_lodash_namespaceObject.cloneDeep)(currentConfig);
      const pathToSet = external_wp_blocks_namespaceObject.__EXPERIMENTAL_PATHS_WITH_MERGE[path] ? fullPath + '.user' : fullPath;
      (0,external_lodash_namespaceObject.set)(newUserConfig, pathToSet, newValue);
      return newUserConfig;
    });
  };

  const getSettingValueForContext = name => {
    const currentPath = !name ? `settings.${path}` : `settings.blocks.${name}.${path}`;

    const getSettingValue = configToUse => {
      const result = (0,external_lodash_namespaceObject.get)(configToUse, currentPath);

      if (external_wp_blocks_namespaceObject.__EXPERIMENTAL_PATHS_WITH_MERGE[path]) {
        var _ref, _result$user;

        return (_ref = (_result$user = result === null || result === void 0 ? void 0 : result.user) !== null && _result$user !== void 0 ? _result$user : result === null || result === void 0 ? void 0 : result.theme) !== null && _ref !== void 0 ? _ref : result === null || result === void 0 ? void 0 : result.core;
      }

      return result;
    };

    let result;

    switch (source) {
      case 'all':
        result = getSettingValue(mergedConfig);
        break;

      case 'user':
        result = getSettingValue(userConfig);
        break;

      case 'base':
        result = getSettingValue(baseConfig);
        break;

      default:
        throw 'Unsupported source';
    }

    return result;
  }; // Unlike styles settings get inherited from top level settings.


  const resultWithFallback = (_getSettingValueForCo = getSettingValueForContext(blockName)) !== null && _getSettingValueForCo !== void 0 ? _getSettingValueForCo : getSettingValueForContext();
  return [resultWithFallback, setSetting];
}
function useStyle(path, blockName, source = 'all') {
  var _get;

  const {
    merged: mergedConfig,
    base: baseConfig,
    user: userConfig,
    setUserConfig
  } = (0,external_wp_element_namespaceObject.useContext)(GlobalStylesContext);
  const finalPath = !blockName ? `styles.${path}` : `styles.blocks.${blockName}.${path}`;

  const setStyle = newValue => {
    setUserConfig(currentConfig => {
      const newUserConfig = (0,external_lodash_namespaceObject.cloneDeep)(currentConfig);
      (0,external_lodash_namespaceObject.set)(newUserConfig, finalPath, getPresetVariableFromValue(mergedConfig.settings, blockName, path, newValue));
      return newUserConfig;
    });
  };

  let result;

  switch (source) {
    case 'all':
      result = getValueFromVariable(mergedConfig.settings, blockName, (_get = (0,external_lodash_namespaceObject.get)(userConfig, finalPath)) !== null && _get !== void 0 ? _get : (0,external_lodash_namespaceObject.get)(baseConfig, finalPath));
      break;

    case 'user':
      result = getValueFromVariable(mergedConfig.settings, blockName, (0,external_lodash_namespaceObject.get)(userConfig, finalPath));
      break;

    case 'base':
      result = getValueFromVariable(baseConfig.settings, blockName, (0,external_lodash_namespaceObject.get)(baseConfig, finalPath));
      break;

    default:
      throw 'Unsupported source';
  }

  return [result, setStyle];
}
const hooks_ROOT_BLOCK_SUPPORTS = ['background', 'backgroundColor', 'color', 'linkColor', 'fontFamily', 'fontSize', 'fontStyle', 'fontWeight', 'lineHeight', 'textDecoration', 'textTransform', 'padding'];
function getSupportedGlobalStylesPanels(name) {
  if (!name) {
    return hooks_ROOT_BLOCK_SUPPORTS;
  }

  const blockType = (0,external_wp_blocks_namespaceObject.getBlockType)(name);

  if (!blockType) {
    return [];
  }

  const supportKeys = [];
  Object.keys(external_wp_blocks_namespaceObject.__EXPERIMENTAL_STYLE_PROPERTY).forEach(styleName => {
    if (!external_wp_blocks_namespaceObject.__EXPERIMENTAL_STYLE_PROPERTY[styleName].support) {
      return;
    } // Opting out means that, for certain support keys like background color,
    // blocks have to explicitly set the support value false. If the key is
    // unset, we still enable it.


    if (external_wp_blocks_namespaceObject.__EXPERIMENTAL_STYLE_PROPERTY[styleName].requiresOptOut) {
      if ((0,external_lodash_namespaceObject.has)(blockType.supports, external_wp_blocks_namespaceObject.__EXPERIMENTAL_STYLE_PROPERTY[styleName].support[0]) && (0,external_lodash_namespaceObject.get)(blockType.supports, external_wp_blocks_namespaceObject.__EXPERIMENTAL_STYLE_PROPERTY[styleName].support) !== false) {
        return supportKeys.push(styleName);
      }
    }

    if ((0,external_lodash_namespaceObject.get)(blockType.supports, external_wp_blocks_namespaceObject.__EXPERIMENTAL_STYLE_PROPERTY[styleName].support, false)) {
      return supportKeys.push(styleName);
    }
  });
  return supportKeys;
}
function useColorsPerOrigin(name) {
  const [userColors] = useSetting('color.palette.user', name);
  const [themeColors] = useSetting('color.palette.theme', name);
  const [coreColors] = useSetting('color.palette.core', name);
  return (0,external_wp_element_namespaceObject.useMemo)(() => {
    const result = [];

    if (coreColors && coreColors.length) {
      result.push({
        name: (0,external_wp_i18n_namespaceObject.__)('Core'),
        colors: coreColors
      });
    }

    if (themeColors && themeColors.length) {
      result.push({
        name: (0,external_wp_i18n_namespaceObject.__)('Theme'),
        colors: themeColors
      });
    }

    if (userColors && userColors.length) {
      result.push({
        name: (0,external_wp_i18n_namespaceObject.__)('User'),
        colors: userColors
      });
    }

    return result;
  }, [userColors, themeColors, coreColors]);
}
function useGradientsPerOrigin(name) {
  const [userGradients] = useSetting('color.gradients.user', name);
  const [themeGradients] = useSetting('color.gradients.theme', name);
  const [coreGradients] = useSetting('color.gradients.core', name);
  return (0,external_wp_element_namespaceObject.useMemo)(() => {
    const result = [];

    if (coreGradients && coreGradients.length) {
      result.push({
        name: (0,external_wp_i18n_namespaceObject.__)('Core'),
        gradients: coreGradients
      });
    }

    if (themeGradients && themeGradients.length) {
      result.push({
        name: (0,external_wp_i18n_namespaceObject.__)('Theme'),
        gradients: themeGradients
      });
    }

    if (userGradients && userGradients.length) {
      result.push({
        name: (0,external_wp_i18n_namespaceObject.__)('User'),
        gradients: userGradients
      });
    }

    return result;
  }, [userGradients, themeGradients, coreGradients]);
}
//# sourceMappingURL=hooks.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/preview.js


/**
 * WordPress dependencies
 */

/**
 * Internal dependencies
 */



const StylesPreview = () => {
  const [fontFamily = 'serif'] = useStyle('typography.fontFamily');
  const [textColor = 'black'] = useStyle('color.text');
  const [linkColor = 'blue'] = useStyle('elements.link.color.text');
  const [backgroundColor = 'white'] = useStyle('color.background');
  const [gradientValue] = useStyle('color.gradient');
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Card, {
    className: "edit-site-global-styles-preview",
    style: {
      background: gradientValue !== null && gradientValue !== void 0 ? gradientValue : backgroundColor
    }
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalHStack, {
    spacing: 5
  }, (0,external_wp_element_namespaceObject.createElement)("div", {
    style: {
      fontFamily,
      fontSize: '80px',
      color: textColor
    }
  }, "Aa"), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalVStack, {
    spacing: 2
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.ColorIndicator, {
    colorValue: textColor
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.ColorIndicator, {
    colorValue: linkColor
  }))));
};

/* harmony default export */ var preview = (StylesPreview);
//# sourceMappingURL=preview.js.map
;// CONCATENATED MODULE: ./packages/icons/build-module/icon/index.js
/**
 * WordPress dependencies
 */

/** @typedef {{icon: JSX.Element, size?: number} & import('@wordpress/primitives').SVGProps} IconProps */

/**
 * Return an SVG icon.
 *
 * @param {IconProps} props icon is the SVG component to render
 *                          size is a number specifiying the icon size in pixels
 *                          Other props will be passed to wrapped SVG component
 *
 * @return {JSX.Element}  Icon component
 */

function Icon({
  icon,
  size = 24,
  ...props
}) {
  return (0,external_wp_element_namespaceObject.cloneElement)(icon, {
    width: size,
    height: size,
    ...props
  });
}

/* harmony default export */ var build_module_icon = (Icon);
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/navigation-button.js



/**
 * WordPress dependencies
 */



function NavigationButton({
  path,
  icon,
  children,
  isBack = false,
  ...props
}) {
  const navigator = (0,external_wp_components_namespaceObject.__experimentalUseNavigator)();
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalItem, _extends({
    onClick: () => navigator.push(path, {
      isBack
    })
  }, props), icon && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalHStack, {
    justify: "flex-start"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.FlexItem, null, (0,external_wp_element_namespaceObject.createElement)(build_module_icon, {
    icon: icon,
    size: 24
  })), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.FlexItem, null, children)), !icon && children);
}

/* harmony default export */ var navigation_button = (NavigationButton);
//# sourceMappingURL=navigation-button.js.map
;// CONCATENATED MODULE: ./packages/icons/build-module/library/typography.js


/**
 * WordPress dependencies
 */

const typography = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M6.9 7L3 17.8h1.7l1-2.8h4.1l1 2.8h1.7L8.6 7H6.9zm-.7 6.6l1.5-4.3 1.5 4.3h-3zM21.6 17c-.1.1-.2.2-.3.2-.1.1-.2.1-.4.1s-.3-.1-.4-.2c-.1-.1-.1-.3-.1-.6V12c0-.5 0-1-.1-1.4-.1-.4-.3-.7-.5-1-.2-.2-.5-.4-.9-.5-.4 0-.8-.1-1.3-.1s-1 .1-1.4.2c-.4.1-.7.3-1 .4-.2.2-.4.3-.6.5-.1.2-.2.4-.2.7 0 .3.1.5.2.8.2.2.4.3.8.3.3 0 .6-.1.8-.3.2-.2.3-.4.3-.7 0-.3-.1-.5-.2-.7-.2-.2-.4-.3-.6-.4.2-.2.4-.3.7-.4.3-.1.6-.1.8-.1.3 0 .6 0 .8.1.2.1.4.3.5.5.1.2.2.5.2.9v1.1c0 .3-.1.5-.3.6-.2.2-.5.3-.9.4-.3.1-.7.3-1.1.4-.4.1-.8.3-1.1.5-.3.2-.6.4-.8.7-.2.3-.3.7-.3 1.2 0 .6.2 1.1.5 1.4.3.4.9.5 1.6.5.5 0 1-.1 1.4-.3.4-.2.8-.6 1.1-1.1 0 .4.1.7.3 1 .2.3.6.4 1.2.4.4 0 .7-.1.9-.2.2-.1.5-.3.7-.4h-.3zm-3-.9c-.2.4-.5.7-.8.8-.3.2-.6.2-.8.2-.4 0-.6-.1-.9-.3-.2-.2-.3-.6-.3-1.1 0-.5.1-.9.3-1.2s.5-.5.8-.7c.3-.2.7-.3 1-.5.3-.1.6-.3.7-.6v3.4z"
}));
/* harmony default export */ var library_typography = (typography);
//# sourceMappingURL=typography.js.map
;// CONCATENATED MODULE: ./packages/icons/build-module/library/color.js


/**
 * WordPress dependencies
 */

const color = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  viewBox: "0 0 24 24",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M17.2 10.9c-.5-1-1.2-2.1-2.1-3.2-.6-.9-1.3-1.7-2.1-2.6L12 4l-1 1.1c-.6.9-1.3 1.7-2 2.6-.8 1.2-1.5 2.3-2 3.2-.6 1.2-1 2.2-1 3 0 3.4 2.7 6.1 6.1 6.1s6.1-2.7 6.1-6.1c0-.8-.3-1.8-1-3zm-5.1 7.6c-2.5 0-4.6-2.1-4.6-4.6 0-.3.1-1 .8-2.3.5-.9 1.1-1.9 2-3.1.7-.9 1.3-1.7 1.8-2.3.7.8 1.3 1.6 1.8 2.3.8 1.1 1.5 2.2 2 3.1.7 1.3.8 2 .8 2.3 0 2.5-2.1 4.6-4.6 4.6z"
}));
/* harmony default export */ var library_color = (color);
//# sourceMappingURL=color.js.map
;// CONCATENATED MODULE: ./packages/icons/build-module/library/layout.js


/**
 * WordPress dependencies
 */

const layout = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M18 5.5H6a.5.5 0 00-.5.5v3h13V6a.5.5 0 00-.5-.5zm.5 5H10v8h8a.5.5 0 00.5-.5v-7.5zm-10 0h-3V18a.5.5 0 00.5.5h2.5v-8zM6 4h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z"
}));
/* harmony default export */ var library_layout = (layout);
//# sourceMappingURL=layout.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/border-panel.js


/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */


const MIN_BORDER_WIDTH = 0; // Defining empty array here instead of inline avoids unnecessary re-renders of
// color control.

const EMPTY_ARRAY = [];
function useHasBorderPanel(name) {
  const controls = [useHasBorderColorControl(name), useHasBorderRadiusControl(name), useHasBorderStyleControl(name), useHasBorderWidthControl(name)];
  return controls.some(Boolean);
}

function useHasBorderColorControl(name) {
  const supports = getSupportedGlobalStylesPanels(name);
  return useSetting('border.color', name)[0] && supports.includes('borderColor');
}

function useHasBorderRadiusControl(name) {
  const supports = getSupportedGlobalStylesPanels(name);
  return useSetting('border.radius', name)[0] && supports.includes('borderRadius');
}

function useHasBorderStyleControl(name) {
  const supports = getSupportedGlobalStylesPanels(name);
  return useSetting('border.style', name)[0] && supports.includes('borderStyle');
}

function useHasBorderWidthControl(name) {
  const supports = getSupportedGlobalStylesPanels(name);
  return useSetting('border.width', name)[0] && supports.includes('borderWidth');
}

function BorderPanel({
  name
}) {
  const units = (0,external_wp_components_namespaceObject.__experimentalUseCustomUnits)({
    availableUnits: useSetting('spacing.units')[0] || ['px', 'em', 'rem']
  }); // Border width.

  const hasBorderWidth = useHasBorderWidthControl(name);
  const [borderWidthValue, setBorderWidth] = useStyle('border.width', name); // Border style.

  const hasBorderStyle = useHasBorderStyleControl(name);
  const [borderStyle, setBorderStyle] = useStyle('border.style', name); // Border color.

  const [colors = EMPTY_ARRAY] = useSetting('color.palette');
  const disableCustomColors = !useSetting('color.custom')[0];
  const disableCustomGradients = !useSetting('color.customGradient')[0];
  const hasBorderColor = useHasBorderColorControl(name);
  const [borderColor, setBorderColor] = useStyle('border.color', name); // Border radius.

  const hasBorderRadius = useHasBorderRadiusControl(name);
  const [borderRadiusValues, setBorderRadius] = useStyle('border.radius', name);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.PanelBody, {
    title: (0,external_wp_i18n_namespaceObject.__)('Border'),
    initialOpen: true
  }, (hasBorderWidth || hasBorderStyle) && (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-site-global-styles-sidebar__border-controls-row"
  }, hasBorderWidth && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalUnitControl, {
    value: borderWidthValue,
    label: (0,external_wp_i18n_namespaceObject.__)('Width'),
    min: MIN_BORDER_WIDTH,
    onChange: value => {
      setBorderWidth(value || undefined);
    },
    units: units
  }), hasBorderStyle && (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.__experimentalBorderStyleControl, {
    value: borderStyle,
    onChange: setBorderStyle
  })), hasBorderColor && (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.__experimentalColorGradientControl, {
    label: (0,external_wp_i18n_namespaceObject.__)('Color'),
    colorValue: borderColor,
    colors: colors,
    gradients: undefined,
    disableCustomColors: disableCustomColors,
    disableCustomGradients: disableCustomGradients,
    onColorChange: setBorderColor
  }), hasBorderRadius && (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.__experimentalBorderRadiusControl, {
    values: borderRadiusValues,
    onChange: setBorderRadius
  }));
}
//# sourceMappingURL=border-panel.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/color-utils.js
/**
 * Internal dependencies
 */

function useHasColorPanel(name) {
  const supports = getSupportedGlobalStylesPanels(name);
  return supports.includes('color') || supports.includes('backgroundColor') || supports.includes('background') || supports.includes('linkColor');
}
//# sourceMappingURL=color-utils.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/dimensions-panel.js


/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */


const AXIAL_SIDES = ['horizontal', 'vertical'];
function useHasDimensionsPanel(name) {
  const hasPadding = useHasPadding(name);
  const hasMargin = useHasMargin(name);
  const hasGap = useHasGap(name);
  return hasPadding || hasMargin || hasGap;
}

function useHasPadding(name) {
  const supports = getSupportedGlobalStylesPanels(name);
  const [settings] = useSetting('spacing.padding', name);
  return settings && supports.includes('padding');
}

function useHasMargin(name) {
  const supports = getSupportedGlobalStylesPanels(name);
  const [settings] = useSetting('spacing.margin', name);
  return settings && supports.includes('margin');
}

function useHasGap(name) {
  const supports = getSupportedGlobalStylesPanels(name);
  const [settings] = useSetting('spacing.blockGap', name);
  return settings && supports.includes('--wp--style--block-gap');
}

function filterValuesBySides(values, sides) {
  if (!sides) {
    // If no custom side configuration all sides are opted into by default.
    return values;
  } // Only include sides opted into within filtered values.


  const filteredValues = {};
  sides.forEach(side => {
    if (side === 'vertical') {
      filteredValues.top = values.top;
      filteredValues.bottom = values.bottom;
    }

    if (side === 'horizontal') {
      filteredValues.left = values.left;
      filteredValues.right = values.right;
    }

    filteredValues[side] = values[side];
  });
  return filteredValues;
}

function splitStyleValue(value) {
  // Check for shorthand value ( a string value ).
  if (value && typeof value === 'string') {
    // Convert to value for individual sides for BoxControl.
    return {
      top: value,
      right: value,
      bottom: value,
      left: value
    };
  }

  return value;
}

function DimensionsPanel({
  name
}) {
  const showPaddingControl = useHasPadding(name);
  const showMarginControl = useHasMargin(name);
  const showGapControl = useHasGap(name);
  const units = (0,external_wp_components_namespaceObject.__experimentalUseCustomUnits)({
    availableUnits: useSetting('spacing.units', name)[0] || ['%', 'px', 'em', 'rem', 'vw']
  });
  const [rawPadding, setRawPadding] = useStyle('spacing.padding', name);
  const paddingValues = splitStyleValue(rawPadding);
  const paddingSides = (0,external_wp_blockEditor_namespaceObject.__experimentalUseCustomSides)(name, 'padding');
  const isAxialPadding = paddingSides && paddingSides.some(side => AXIAL_SIDES.includes(side));

  const setPaddingValues = newPaddingValues => {
    const padding = filterValuesBySides(newPaddingValues, paddingSides);
    setRawPadding(padding);
  };

  const resetPaddingValue = () => setPaddingValues({});

  const hasPaddingValue = () => !!paddingValues && Object.keys(paddingValues).length;

  const [rawMargin, setRawMargin] = useStyle('spacing.margin', name);
  const marginValues = splitStyleValue(rawMargin);
  const marginSides = (0,external_wp_blockEditor_namespaceObject.__experimentalUseCustomSides)(name, 'margin');
  const isAxialMargin = marginSides && marginSides.some(side => AXIAL_SIDES.includes(side));

  const setMarginValues = newMarginValues => {
    const margin = filterValuesBySides(newMarginValues, marginSides);
    setRawMargin(margin);
  };

  const resetMarginValue = () => setMarginValues({});

  const hasMarginValue = () => !!marginValues && Object.keys(marginValues).length;

  const [gapValue, setGapValue] = useStyle('spacing.blockGap', name);

  const resetGapValue = () => setGapValue(undefined);

  const hasGapValue = () => !!gapValue;

  const resetAll = () => {
    resetPaddingValue();
    resetMarginValue();
    resetGapValue();
  };

  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalToolsPanel, {
    label: (0,external_wp_i18n_namespaceObject.__)('Dimensions'),
    resetAll: resetAll
  }, showPaddingControl && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalToolsPanelItem, {
    hasValue: hasPaddingValue,
    label: (0,external_wp_i18n_namespaceObject.__)('Padding'),
    onDeselect: resetPaddingValue,
    isShownByDefault: true
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalBoxControl, {
    values: paddingValues,
    onChange: setPaddingValues,
    label: (0,external_wp_i18n_namespaceObject.__)('Padding'),
    sides: paddingSides,
    units: units,
    allowReset: false,
    splitOnAxis: isAxialPadding
  })), showMarginControl && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalToolsPanelItem, {
    hasValue: hasMarginValue,
    label: (0,external_wp_i18n_namespaceObject.__)('Margin'),
    onDeselect: resetMarginValue,
    isShownByDefault: true
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalBoxControl, {
    values: marginValues,
    onChange: setMarginValues,
    label: (0,external_wp_i18n_namespaceObject.__)('Margin'),
    sides: marginSides,
    units: units,
    allowReset: false,
    splitOnAxis: isAxialMargin
  })), showGapControl && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalToolsPanelItem, {
    hasValue: hasGapValue,
    label: (0,external_wp_i18n_namespaceObject.__)('Block spacing'),
    onDeselect: resetGapValue,
    isShownByDefault: true
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalUnitControl, {
    label: (0,external_wp_i18n_namespaceObject.__)('Block spacing'),
    __unstableInputWidth: "80px",
    min: 0,
    onChange: setGapValue,
    units: units,
    value: gapValue
  })));
}
//# sourceMappingURL=dimensions-panel.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/typography-panel.js


/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */


function useHasTypographyPanel(name) {
  const hasLineHeight = useHasLineHeightControl(name);
  const hasFontAppearance = useHasAppearanceControl(name);
  const hasLetterSpacing = useHasLetterSpacingControl(name);
  const supports = getSupportedGlobalStylesPanels(name);
  return hasLineHeight || hasFontAppearance || hasLetterSpacing || supports.includes('fontSize');
}

function useHasLineHeightControl(name) {
  const supports = getSupportedGlobalStylesPanels(name);
  return useSetting('typography.lineHeight', name)[0] && supports.includes('lineHeight');
}

function useHasAppearanceControl(name) {
  const supports = getSupportedGlobalStylesPanels(name);
  const hasFontStyles = useSetting('typography.fontStyle', name)[0] && supports.includes('fontStyle');
  const hasFontWeights = useSetting('typography.fontWeight', name)[0] && supports.includes('fontWeight');
  return hasFontStyles || hasFontWeights;
}

function useHasLetterSpacingControl(name) {
  const supports = getSupportedGlobalStylesPanels(name);
  return useSetting('typography.letterSpacing', name)[0] && supports.includes('letterSpacing');
}

function TypographyPanel({
  name
}) {
  const supports = getSupportedGlobalStylesPanels(name);
  const [fontSizes] = useSetting('typography.fontSizes', name);
  const disableCustomFontSizes = !useSetting('typography.customFontSize', name)[0];
  const [fontFamilies] = useSetting('typography.fontFamilies', name);
  const hasFontStyles = useSetting('typography.fontStyle', name)[0] && supports.includes('fontStyle');
  const hasFontWeights = useSetting('typography.fontWeight', name)[0] && supports.includes('fontWeight');
  const hasLineHeightEnabled = useHasLineHeightControl(name);
  const hasAppearanceControl = useHasAppearanceControl(name);
  const hasLetterSpacingControl = useHasLetterSpacingControl(name);
  const [fontFamily, setFontFamily] = useStyle('typography.fontFamily', name);
  const [fontSize, setFontSize] = useStyle('typography.fontSize', name);
  const [fontStyle, setFontStyle] = useStyle('typography.fontStyle', name);
  const [fontWeight, setFontWeight] = useStyle('typography.fontWeight', name);
  const [lineHeight, setLineHeight] = useStyle('typography.lineHeight', name);
  const [letterSpacing, setLetterSpacing] = useStyle('typography.letterSpacing', name);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.PanelBody, {
    className: "edit-site-typography-panel",
    initialOpen: true
  }, supports.includes('fontFamily') && (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.__experimentalFontFamilyControl, {
    fontFamilies: fontFamilies,
    value: fontFamily,
    onChange: setFontFamily
  }), supports.includes('fontSize') && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.FontSizePicker, {
    value: fontSize,
    onChange: setFontSize,
    fontSizes: fontSizes,
    disableCustomFontSizes: disableCustomFontSizes
  }), hasLineHeightEnabled && (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.LineHeightControl, {
    value: lineHeight,
    onChange: setLineHeight
  }), hasAppearanceControl && (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.__experimentalFontAppearanceControl, {
    value: {
      fontStyle,
      fontWeight
    },
    onChange: ({
      fontStyle: newFontStyle,
      fontWeight: newFontWeight
    }) => {
      setFontStyle(newFontStyle);
      setFontWeight(newFontWeight);
    },
    hasFontStyles: hasFontStyles,
    hasFontWeights: hasFontWeights
  }), hasLetterSpacingControl && (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.__experimentalLetterSpacingControl, {
    value: letterSpacing,
    onChange: setLetterSpacing
  }));
}
//# sourceMappingURL=typography-panel.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/context-menu.js


/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */







function ContextMenu({
  name,
  parentMenu = ''
}) {
  const hasTypographyPanel = useHasTypographyPanel(name);
  const hasColorPanel = useHasColorPanel(name);
  const hasBorderPanel = useHasBorderPanel(name);
  const hasDimensionsPanel = useHasDimensionsPanel(name);
  const hasLayoutPanel = hasBorderPanel || hasDimensionsPanel;
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalItemGroup, null, hasTypographyPanel && (0,external_wp_element_namespaceObject.createElement)(navigation_button, {
    icon: library_typography,
    path: parentMenu + '/typography'
  }, (0,external_wp_i18n_namespaceObject.__)('Typography')), hasColorPanel && (0,external_wp_element_namespaceObject.createElement)(navigation_button, {
    icon: library_color,
    path: parentMenu + '/colors'
  }, (0,external_wp_i18n_namespaceObject.__)('Colors')), hasLayoutPanel && (0,external_wp_element_namespaceObject.createElement)(navigation_button, {
    icon: library_layout,
    path: parentMenu + '/layout'
  }, (0,external_wp_i18n_namespaceObject.__)('Layout')));
}

/* harmony default export */ var context_menu = (ContextMenu);
//# sourceMappingURL=context-menu.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/screen-root.js


/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */





function ScreenRoot() {
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Card, {
    size: "small"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.CardBody, null, (0,external_wp_element_namespaceObject.createElement)(preview, null)), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.CardBody, null, (0,external_wp_element_namespaceObject.createElement)(context_menu, null)), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.CardDivider, null), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.CardBody, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalItemGroup, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalItem, null, (0,external_wp_element_namespaceObject.createElement)("p", null, (0,external_wp_i18n_namespaceObject.__)('Customize the appearance of specific blocks for the whole site.'))), (0,external_wp_element_namespaceObject.createElement)(navigation_button, {
    path: "/blocks"
  }, (0,external_wp_i18n_namespaceObject.__)('Blocks')))));
}

/* harmony default export */ var screen_root = (ScreenRoot);
//# sourceMappingURL=screen-root.js.map
;// CONCATENATED MODULE: ./packages/icons/build-module/library/chevron-right.js


/**
 * WordPress dependencies
 */

const chevronRight = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M10.6 6L9.4 7l4.6 5-4.6 5 1.2 1 5.4-6z"
}));
/* harmony default export */ var chevron_right = (chevronRight);
//# sourceMappingURL=chevron-right.js.map
;// CONCATENATED MODULE: ./packages/icons/build-module/library/chevron-left.js


/**
 * WordPress dependencies
 */

const chevronLeft = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M14.6 7l-1.2-1L8 12l5.4 6 1.2-1-4.6-5z"
}));
/* harmony default export */ var chevron_left = (chevronLeft);
//# sourceMappingURL=chevron-left.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/header.js


/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */



function ScreenHeader({
  back,
  title,
  description
}) {
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalVStack, {
    spacing: 2
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalHStack, {
    spacing: 2
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalView, null, (0,external_wp_element_namespaceObject.createElement)(navigation_button, {
    path: back,
    icon: (0,external_wp_element_namespaceObject.createElement)(build_module_icon, {
      icon: (0,external_wp_i18n_namespaceObject.isRTL)() ? chevron_right : chevron_left,
      variant: "muted"
    }),
    size: "small",
    isBack: true,
    "aria-label": (0,external_wp_i18n_namespaceObject.__)('Navigate to the previous view')
  })), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalSpacer, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalHeading, {
    level: 5
  }, title))), description && (0,external_wp_element_namespaceObject.createElement)("p", {
    className: "edit-site-global-styles-header__description"
  }, description));
}

/* harmony default export */ var header = (ScreenHeader);
//# sourceMappingURL=header.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/screen-block-list.js


/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */








function BlockMenuItem({
  block
}) {
  const hasTypographyPanel = useHasTypographyPanel(block.name);
  const hasColorPanel = useHasColorPanel(block.name);
  const hasBorderPanel = useHasBorderPanel(block.name);
  const hasDimensionsPanel = useHasDimensionsPanel(block.name);
  const hasLayoutPanel = hasBorderPanel || hasDimensionsPanel;
  const hasBlockMenuItem = hasTypographyPanel || hasColorPanel || hasLayoutPanel;

  if (!hasBlockMenuItem) {
    return null;
  }

  return (0,external_wp_element_namespaceObject.createElement)(navigation_button, {
    path: '/blocks/' + block.name
  }, block.title);
}

function ScreenBlockList() {
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(header, {
    back: "/",
    title: (0,external_wp_i18n_namespaceObject.__)('Blocks'),
    description: (0,external_wp_i18n_namespaceObject.__)('Customize the appearance of specific blocks and for the whole site.')
  }), (0,external_wp_blocks_namespaceObject.getBlockTypes)().map(block => (0,external_wp_element_namespaceObject.createElement)(BlockMenuItem, {
    block: block,
    key: 'menu-itemblock-' + block.name
  })));
}

/* harmony default export */ var screen_block_list = (ScreenBlockList);
//# sourceMappingURL=screen-block-list.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/screen-block.js


/**
 * WordPress dependencies
 */

/**
 * Internal dependencies
 */




function ScreenBlock({
  name
}) {
  const blockType = (0,external_wp_blocks_namespaceObject.getBlockType)(name);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(header, {
    back: "/blocks",
    title: blockType.title
  }), (0,external_wp_element_namespaceObject.createElement)(context_menu, {
    parentMenu: '/blocks/' + name,
    name: name
  }));
}

/* harmony default export */ var screen_block = (ScreenBlock);
//# sourceMappingURL=screen-block.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/screen-typography.js


/**
 * WordPress dependencies
 */

/**
 * Internal dependencies
 */




function ScreenTypography({
  name
}) {
  const parentMenu = name === undefined ? '' : '/blocks/' + name;
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(header, {
    back: parentMenu ? parentMenu : '/',
    title: (0,external_wp_i18n_namespaceObject.__)('Typography'),
    description: (0,external_wp_i18n_namespaceObject.__)('Manage the fonts used on the website and the default aspect of different global elements.')
  }), (0,external_wp_element_namespaceObject.createElement)(TypographyPanel, {
    name: name
  }));
}

/* harmony default export */ var screen_typography = (ScreenTypography);
//# sourceMappingURL=screen-typography.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/subtitle.js


/**
 * WordPress dependencies
 */


function Subtitle({
  children
}) {
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalHeading, {
    className: "edit-site-global-styles-subtitle",
    level: 2
  }, children);
}

/* harmony default export */ var subtitle = (Subtitle);
//# sourceMappingURL=subtitle.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/palette.js


/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */




const EMPTY_COLORS = [];

function Palette({
  name
}) {
  const [colorsSetting] = useSetting('color.palette.user', name);
  const colors = colorsSetting || EMPTY_COLORS;
  const screenPath = !name ? '/colors/palette' : '/blocks/' + name + '/colors/palette';
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalVStack, {
    spacing: 3
  }, (0,external_wp_element_namespaceObject.createElement)(subtitle, null, (0,external_wp_i18n_namespaceObject.__)('Palette')), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalItemGroup, {
    isBordered: true,
    isSeparated: true
  }, (0,external_wp_element_namespaceObject.createElement)(navigation_button, {
    path: screenPath
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalHStack, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.FlexBlock, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalZStack, {
    isLayered: false,
    offset: -8
  }, colors.slice(0, 5).map(({
    color
  }) => (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.ColorIndicator, {
    key: color,
    colorValue: color
  })))), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.FlexItem, null, (0,external_wp_i18n_namespaceObject.sprintf)( // Translators: %d: Number of palette colors.
  (0,external_wp_i18n_namespaceObject._n)('%d color', '%d colors', colors.length), colors.length))))));
}

/* harmony default export */ var palette = (Palette);
//# sourceMappingURL=palette.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/screen-colors.js


/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */







function BackgroundColorItem({
  name,
  parentMenu
}) {
  const supports = getSupportedGlobalStylesPanels(name);
  const hasSupport = supports.includes('backgroundColor') || supports.includes('background');
  const [backgroundColor] = useStyle('color.background', name);
  const [gradientValue] = useStyle('color.gradient', name);

  if (!hasSupport) {
    return null;
  }

  return (0,external_wp_element_namespaceObject.createElement)(navigation_button, {
    path: parentMenu + '/colors/background'
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalHStack, {
    justify: "flex-start"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.FlexItem, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.ColorIndicator, {
    colorValue: gradientValue !== null && gradientValue !== void 0 ? gradientValue : backgroundColor
  })), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.FlexItem, null, (0,external_wp_i18n_namespaceObject.__)('Background'))));
}

function TextColorItem({
  name,
  parentMenu
}) {
  const supports = getSupportedGlobalStylesPanels(name);
  const hasSupport = supports.includes('color');
  const [color] = useStyle('color.text', name);

  if (!hasSupport) {
    return null;
  }

  return (0,external_wp_element_namespaceObject.createElement)(navigation_button, {
    path: parentMenu + '/colors/text'
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalHStack, {
    justify: "flex-start"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.FlexItem, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.ColorIndicator, {
    colorValue: color
  })), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.FlexItem, null, (0,external_wp_i18n_namespaceObject.__)('Text'))));
}

function LinkColorItem({
  name,
  parentMenu
}) {
  const supports = getSupportedGlobalStylesPanels(name);
  const hasSupport = supports.includes('linkColor');
  const [color] = useStyle('elements.link.color.text', name);

  if (!hasSupport) {
    return null;
  }

  return (0,external_wp_element_namespaceObject.createElement)(navigation_button, {
    path: parentMenu + '/colors/link'
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalHStack, {
    justify: "flex-start"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.FlexItem, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.ColorIndicator, {
    colorValue: color
  })), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.FlexItem, null, (0,external_wp_i18n_namespaceObject.__)('Links'))));
}

function ScreenColors({
  name
}) {
  const parentMenu = name === undefined ? '' : '/blocks/' + name;
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(header, {
    back: parentMenu ? parentMenu : '/',
    title: (0,external_wp_i18n_namespaceObject.__)('Colors'),
    description: (0,external_wp_i18n_namespaceObject.__)('Manage palettes and the default color of different global elements on the website.')
  }), (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-site-global-styles-screen-colors"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalVStack, {
    spacing: 10
  }, (0,external_wp_element_namespaceObject.createElement)(palette, {
    name: name
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalVStack, {
    spacing: 3
  }, (0,external_wp_element_namespaceObject.createElement)(subtitle, null, (0,external_wp_i18n_namespaceObject.__)('Elements')), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalItemGroup, {
    isBordered: true,
    isSeparated: true
  }, (0,external_wp_element_namespaceObject.createElement)(BackgroundColorItem, {
    name: name,
    parentMenu: parentMenu
  }), (0,external_wp_element_namespaceObject.createElement)(TextColorItem, {
    name: name,
    parentMenu: parentMenu
  }), (0,external_wp_element_namespaceObject.createElement)(LinkColorItem, {
    name: name,
    parentMenu: parentMenu
  }))))));
}

/* harmony default export */ var screen_colors = (ScreenColors);
//# sourceMappingURL=screen-colors.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/color-palette-panel.js


/**
 * WordPress dependencies
 */

/**
 * Internal dependencies
 */


function ColorPalettePanel({
  name
}) {
  const [userColors, setColors] = useSetting('color.palette', name, 'user');
  return (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-site-global-styles-color-palette-panel"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalColorEdit, {
    colors: userColors,
    onChange: setColors
  }));
}
//# sourceMappingURL=color-palette-panel.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/screen-color-palette.js


/**
 * WordPress dependencies
 */

/**
 * Internal dependencies
 */




function ScreenColorPalette({
  name
}) {
  const parentMenu = name === undefined ? '' : '/blocks/' + name;
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(header, {
    back: parentMenu + '/colors',
    title: (0,external_wp_i18n_namespaceObject.__)('Color Palette'),
    description: (0,external_wp_i18n_namespaceObject.__)('Color palettes are used to provide default color options for blocks and various design tools. Here you can edit the colors with their labels.')
  }), (0,external_wp_element_namespaceObject.createElement)(ColorPalettePanel, {
    name: name
  }));
}

/* harmony default export */ var screen_color_palette = (ScreenColorPalette);
//# sourceMappingURL=screen-color-palette.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/screen-background-color.js


/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */




function ScreenBackgroundColor({
  name
}) {
  const parentMenu = name === undefined ? '' : '/blocks/' + name;
  const supports = getSupportedGlobalStylesPanels(name);
  const [solids] = useSetting('color.palette', name);
  const [gradients] = useSetting('color.gradients', name);
  const [areCustomSolidsEnabled] = useSetting('color.custom', name);
  const [areCustomGradientsEnabled] = useSetting('color.customGradient', name);
  const colorsPerOrigin = useColorsPerOrigin(name);
  const gradientsPerOrigin = useGradientsPerOrigin(name);
  const [isBackgroundEnabled] = useSetting('color.background', name);
  const hasBackgroundColor = supports.includes('backgroundColor') && isBackgroundEnabled && (solids.length > 0 || areCustomSolidsEnabled);
  const hasGradientColor = supports.includes('background') && (gradients.length > 0 || areCustomGradientsEnabled);
  const [backgroundColor, setBackgroundColor] = useStyle('color.background', name);
  const [userBackgroundColor] = useStyle('color.background', name, 'user');
  const [gradient, setGradient] = useStyle('color.gradient', name);
  const [userGradient] = useStyle('color.gradient', name, 'user');

  if (!hasBackgroundColor && !hasGradientColor) {
    return null;
  }

  const settings = [];
  let backgroundSettings = {};

  if (hasBackgroundColor) {
    backgroundSettings = {
      colorValue: backgroundColor,
      onColorChange: setBackgroundColor
    };

    if (backgroundColor) {
      backgroundSettings.clearable = backgroundColor === userBackgroundColor;
    }
  }

  let gradientSettings = {};

  if (hasGradientColor) {
    gradientSettings = {
      gradientValue: gradient,
      onGradientChange: setGradient
    };

    if (gradient) {
      gradientSettings.clearable = gradient === userGradient;
    }
  }

  settings.push({ ...backgroundSettings,
    ...gradientSettings,
    label: (0,external_wp_i18n_namespaceObject.__)('Background color')
  });
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(header, {
    back: parentMenu + '/colors',
    title: (0,external_wp_i18n_namespaceObject.__)('Background'),
    description: (0,external_wp_i18n_namespaceObject.__)('Set a background color or gradient for the whole website.')
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.__experimentalPanelColorGradientSettings, {
    title: (0,external_wp_i18n_namespaceObject.__)('Color'),
    settings: settings,
    colors: colorsPerOrigin,
    gradients: gradientsPerOrigin,
    disableCustomColors: !areCustomSolidsEnabled,
    disableCustomGradients: !areCustomGradientsEnabled,
    __experimentalHasMultipleOrigins: true,
    showTitle: false
  }));
}

/* harmony default export */ var screen_background_color = (ScreenBackgroundColor);
//# sourceMappingURL=screen-background-color.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/screen-text-color.js


/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */




function ScreenTextColor({
  name
}) {
  const parentMenu = name === undefined ? '' : '/blocks/' + name;
  const supports = getSupportedGlobalStylesPanels(name);
  const [solids] = useSetting('color.palette', name);
  const [areCustomSolidsEnabled] = useSetting('color.custom', name);
  const [isTextEnabled] = useSetting('color.text', name);
  const colorsPerOrigin = useColorsPerOrigin(name);
  const hasTextColor = supports.includes('color') && isTextEnabled && (solids.length > 0 || areCustomSolidsEnabled);
  const [color, setColor] = useStyle('color.text', name);
  const [userColor] = useStyle('color.text', name, 'user');

  if (!hasTextColor) {
    return null;
  }

  const settings = [{
    colorValue: color,
    onColorChange: setColor,
    label: (0,external_wp_i18n_namespaceObject.__)('Text color'),
    clearable: color === userColor
  }];
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(header, {
    back: parentMenu + '/colors',
    title: (0,external_wp_i18n_namespaceObject.__)('Text'),
    description: (0,external_wp_i18n_namespaceObject.__)('Set the default color used for text across the site.')
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.__experimentalPanelColorGradientSettings, {
    title: (0,external_wp_i18n_namespaceObject.__)('Color'),
    settings: settings,
    colors: colorsPerOrigin,
    disableCustomColors: !areCustomSolidsEnabled,
    __experimentalHasMultipleOrigins: true,
    showTitle: false
  }));
}

/* harmony default export */ var screen_text_color = (ScreenTextColor);
//# sourceMappingURL=screen-text-color.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/screen-link-color.js


/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */




function ScreenLinkColor({
  name
}) {
  const parentMenu = name === undefined ? '' : '/blocks/' + name;
  const supports = getSupportedGlobalStylesPanels(name);
  const [solids] = useSetting('color.palette', name);
  const [areCustomSolidsEnabled] = useSetting('color.custom', name);
  const colorsPerOrigin = useColorsPerOrigin(name);
  const [isLinkEnabled] = useSetting('color.link', name);
  const hasLinkColor = supports.includes('linkColor') && isLinkEnabled && (solids.length > 0 || areCustomSolidsEnabled);
  const [linkColor, setLinkColor] = useStyle('elements.link.color.text', name);
  const [userLinkColor] = useStyle('elements.link.color.text', name, 'user');

  if (!hasLinkColor) {
    return null;
  }

  const settings = [{
    colorValue: linkColor,
    onColorChange: setLinkColor,
    label: (0,external_wp_i18n_namespaceObject.__)('Link color'),
    clearable: linkColor === userLinkColor
  }];
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(header, {
    back: parentMenu + '/colors',
    title: (0,external_wp_i18n_namespaceObject.__)('Links'),
    description: (0,external_wp_i18n_namespaceObject.__)('Set the default color used for links across the site.')
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.__experimentalPanelColorGradientSettings, {
    title: (0,external_wp_i18n_namespaceObject.__)('Color'),
    settings: settings,
    colors: colorsPerOrigin,
    disableCustomColors: !areCustomSolidsEnabled,
    __experimentalHasMultipleOrigins: true,
    showTitle: false
  }));
}

/* harmony default export */ var screen_link_color = (ScreenLinkColor);
//# sourceMappingURL=screen-link-color.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/screen-layout.js


/**
 * WordPress dependencies
 */

/**
 * Internal dependencies
 */





function ScreenLayout({
  name
}) {
  const parentMenu = name === undefined ? '' : '/blocks/' + name;
  const hasBorderPanel = useHasBorderPanel(name);
  const hasDimensionsPanel = useHasDimensionsPanel(name);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(header, {
    back: parentMenu ? parentMenu : '/',
    title: (0,external_wp_i18n_namespaceObject.__)('Layout')
  }), hasDimensionsPanel && (0,external_wp_element_namespaceObject.createElement)(DimensionsPanel, {
    name: name
  }), hasBorderPanel && (0,external_wp_element_namespaceObject.createElement)(BorderPanel, {
    name: name
  }));
}

/* harmony default export */ var screen_layout = (ScreenLayout);
//# sourceMappingURL=screen-layout.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/ui.js


/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */












function ContextScreens({
  name
}) {
  const parentMenu = name === undefined ? '' : '/blocks/' + name;
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigatorScreen, {
    path: parentMenu + '/typography'
  }, (0,external_wp_element_namespaceObject.createElement)(screen_typography, {
    name: name
  })), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigatorScreen, {
    path: parentMenu + '/colors'
  }, (0,external_wp_element_namespaceObject.createElement)(screen_colors, {
    name: name
  })), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigatorScreen, {
    path: parentMenu + '/colors/palette'
  }, (0,external_wp_element_namespaceObject.createElement)(screen_color_palette, {
    name: name
  })), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigatorScreen, {
    path: parentMenu + '/colors/background'
  }, (0,external_wp_element_namespaceObject.createElement)(screen_background_color, {
    name: name
  })), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigatorScreen, {
    path: parentMenu + '/colors/text'
  }, (0,external_wp_element_namespaceObject.createElement)(screen_text_color, {
    name: name
  })), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigatorScreen, {
    path: parentMenu + '/colors/link'
  }, (0,external_wp_element_namespaceObject.createElement)(screen_link_color, {
    name: name
  })), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigatorScreen, {
    path: parentMenu + '/layout'
  }, (0,external_wp_element_namespaceObject.createElement)(screen_layout, {
    name: name
  })));
}

function GlobalStylesUI() {
  const blocks = (0,external_wp_blocks_namespaceObject.getBlockTypes)();
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigatorProvider, {
    initialPath: "/"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigatorScreen, {
    path: "/"
  }, (0,external_wp_element_namespaceObject.createElement)(screen_root, null)), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigatorScreen, {
    path: "/blocks"
  }, (0,external_wp_element_namespaceObject.createElement)(screen_block_list, null)), blocks.map(block => (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigatorScreen, {
    key: 'menu-block-' + block.name,
    path: '/blocks/' + block.name
  }, (0,external_wp_element_namespaceObject.createElement)(screen_block, {
    name: block.name
  }))), (0,external_wp_element_namespaceObject.createElement)(ContextScreens, null), blocks.map(block => (0,external_wp_element_namespaceObject.createElement)(ContextScreens, {
    key: 'screens-block-' + block.name,
    name: block.name
  })));
}

/* harmony default export */ var ui = (GlobalStylesUI);
//# sourceMappingURL=ui.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/use-global-styles-output.js
/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */

/**
 * Internal dependencies
 */




function compileStyleValue(uncompiledValue) {
  const VARIABLE_REFERENCE_PREFIX = 'var:';
  const VARIABLE_PATH_SEPARATOR_TOKEN_ATTRIBUTE = '|';
  const VARIABLE_PATH_SEPARATOR_TOKEN_STYLE = '--';

  if ((0,external_lodash_namespaceObject.startsWith)(uncompiledValue, VARIABLE_REFERENCE_PREFIX)) {
    const variable = uncompiledValue.slice(VARIABLE_REFERENCE_PREFIX.length).split(VARIABLE_PATH_SEPARATOR_TOKEN_ATTRIBUTE).join(VARIABLE_PATH_SEPARATOR_TOKEN_STYLE);
    return `var(--wp--${variable})`;
  }

  return uncompiledValue;
}
/**
 * Transform given preset tree into a set of style declarations.
 *
 * @param {Object} blockPresets
 *
 * @return {Array} An array of style declarations.
 */


function getPresetsDeclarations(blockPresets = {}) {
  return (0,external_lodash_namespaceObject.reduce)(PRESET_METADATA, (declarations, {
    path,
    valueKey,
    cssVarInfix
  }) => {
    const presetByOrigin = (0,external_lodash_namespaceObject.get)(blockPresets, path, []);
    ['core', 'theme', 'user'].forEach(origin => {
      if (presetByOrigin[origin]) {
        presetByOrigin[origin].forEach(value => {
          declarations.push(`--wp--preset--${cssVarInfix}--${(0,external_lodash_namespaceObject.kebabCase)(value.slug)}: ${value[valueKey]}`);
        });
      }
    });
    return declarations;
  }, []);
}
/**
 * Transform given preset tree into a set of preset class declarations.
 *
 * @param {string} blockSelector
 * @param {Object} blockPresets
 * @return {string} CSS declarations for the preset classes.
 */


function getPresetsClasses(blockSelector, blockPresets = {}) {
  return (0,external_lodash_namespaceObject.reduce)(PRESET_METADATA, (declarations, {
    path,
    cssVarInfix,
    classes
  }) => {
    if (!classes) {
      return declarations;
    }

    const presetByOrigin = (0,external_lodash_namespaceObject.get)(blockPresets, path, []);
    ['core', 'theme', 'user'].forEach(origin => {
      if (presetByOrigin[origin]) {
        presetByOrigin[origin].forEach(({
          slug
        }) => {
          classes.forEach(({
            classSuffix,
            propertyName
          }) => {
            const classSelectorToUse = `.has-${(0,external_lodash_namespaceObject.kebabCase)(slug)}-${classSuffix}`;
            const selectorToUse = blockSelector.split(',') // Selector can be "h1, h2, h3"
            .map(selector => `${selector}${classSelectorToUse}`).join(',');
            const value = `var(--wp--preset--${cssVarInfix}--${(0,external_lodash_namespaceObject.kebabCase)(slug)})`;
            declarations += `${selectorToUse}{${propertyName}: ${value} !important;}`;
          });
        });
      }
    });
    return declarations;
  }, '');
}

function flattenTree(input = {}, prefix, token) {
  let result = [];
  Object.keys(input).forEach(key => {
    const newKey = prefix + (0,external_lodash_namespaceObject.kebabCase)(key.replace('/', '-'));
    const newLeaf = input[key];

    if (newLeaf instanceof Object) {
      const newPrefix = newKey + token;
      result = [...result, ...flattenTree(newLeaf, newPrefix, token)];
    } else {
      result.push(`${newKey}: ${newLeaf}`);
    }
  });
  return result;
}
/**
 * Transform given style tree into a set of style declarations.
 *
 * @param {Object} blockStyles Block styles.
 *
 * @return {Array} An array of style declarations.
 */


function getStylesDeclarations(blockStyles = {}) {
  return (0,external_lodash_namespaceObject.reduce)(external_wp_blocks_namespaceObject.__EXPERIMENTAL_STYLE_PROPERTY, (declarations, {
    value,
    properties
  }, key) => {
    const pathToValue = value;

    if ((0,external_lodash_namespaceObject.first)(pathToValue) === 'elements') {
      return declarations;
    }

    const styleValue = (0,external_lodash_namespaceObject.get)(blockStyles, pathToValue);

    if (!!properties && !(0,external_lodash_namespaceObject.isString)(styleValue)) {
      Object.entries(properties).forEach(entry => {
        const [name, prop] = entry;

        if (!(0,external_lodash_namespaceObject.get)(styleValue, [prop], false)) {
          // Do not create a declaration
          // for sub-properties that don't have any value.
          return;
        }

        const cssProperty = (0,external_lodash_namespaceObject.kebabCase)(name);
        declarations.push(`${cssProperty}: ${compileStyleValue((0,external_lodash_namespaceObject.get)(styleValue, [prop]))}`);
      });
    } else if ((0,external_lodash_namespaceObject.get)(blockStyles, pathToValue, false)) {
      const cssProperty = key.startsWith('--') ? key : (0,external_lodash_namespaceObject.kebabCase)(key);
      declarations.push(`${cssProperty}: ${compileStyleValue((0,external_lodash_namespaceObject.get)(blockStyles, pathToValue))}`);
    }

    return declarations;
  }, []);
}

const getNodesWithStyles = (tree, blockSelectors) => {
  var _tree$styles, _tree$styles2;

  const nodes = [];

  if (!(tree !== null && tree !== void 0 && tree.styles)) {
    return nodes;
  }

  const pickStyleKeys = treeToPickFrom => (0,external_lodash_namespaceObject.pickBy)(treeToPickFrom, (value, key) => ['border', 'color', 'spacing', 'typography'].includes(key)); // Top-level.


  const styles = pickStyleKeys(tree.styles);

  if (!!styles) {
    nodes.push({
      styles,
      selector: ROOT_BLOCK_SELECTOR
    });
  }

  (0,external_lodash_namespaceObject.forEach)((_tree$styles = tree.styles) === null || _tree$styles === void 0 ? void 0 : _tree$styles.elements, (value, key) => {
    if (!!value && !!external_wp_blocks_namespaceObject.__EXPERIMENTAL_ELEMENTS[key]) {
      nodes.push({
        styles: value,
        selector: external_wp_blocks_namespaceObject.__EXPERIMENTAL_ELEMENTS[key]
      });
    }
  }); // Iterate over blocks: they can have styles & elements.

  (0,external_lodash_namespaceObject.forEach)((_tree$styles2 = tree.styles) === null || _tree$styles2 === void 0 ? void 0 : _tree$styles2.blocks, (node, blockName) => {
    var _blockSelectors$block;

    const blockStyles = pickStyleKeys(node);

    if (!!blockStyles && !!(blockSelectors !== null && blockSelectors !== void 0 && (_blockSelectors$block = blockSelectors[blockName]) !== null && _blockSelectors$block !== void 0 && _blockSelectors$block.selector)) {
      nodes.push({
        styles: blockStyles,
        selector: blockSelectors[blockName].selector
      });
    }

    (0,external_lodash_namespaceObject.forEach)(node === null || node === void 0 ? void 0 : node.elements, (value, elementName) => {
      if (!!value && !!(blockSelectors !== null && blockSelectors !== void 0 && blockSelectors[blockName]) && !!(external_wp_blocks_namespaceObject.__EXPERIMENTAL_ELEMENTS !== null && external_wp_blocks_namespaceObject.__EXPERIMENTAL_ELEMENTS !== void 0 && external_wp_blocks_namespaceObject.__EXPERIMENTAL_ELEMENTS[elementName])) {
        nodes.push({
          styles: value,
          selector: blockSelectors[blockName].selector.split(',').map(sel => sel + ' ' + external_wp_blocks_namespaceObject.__EXPERIMENTAL_ELEMENTS[elementName]).join(',')
        });
      }
    });
  });
  return nodes;
};
const getNodesWithSettings = (tree, blockSelectors) => {
  var _tree$settings, _tree$settings2;

  const nodes = [];

  if (!(tree !== null && tree !== void 0 && tree.settings)) {
    return nodes;
  }

  const pickPresets = treeToPickFrom => {
    const presets = {};
    PRESET_METADATA.forEach(({
      path
    }) => {
      const value = (0,external_lodash_namespaceObject.get)(treeToPickFrom, path, false);

      if (value !== false) {
        (0,external_lodash_namespaceObject.set)(presets, path, value);
      }
    });
    return presets;
  }; // Top-level.


  const presets = pickPresets(tree.settings);
  const custom = (_tree$settings = tree.settings) === null || _tree$settings === void 0 ? void 0 : _tree$settings.custom;

  if (!(0,external_lodash_namespaceObject.isEmpty)(presets) || !!custom) {
    nodes.push({
      presets,
      custom,
      selector: ROOT_BLOCK_SELECTOR
    });
  } // Blocks.


  (0,external_lodash_namespaceObject.forEach)((_tree$settings2 = tree.settings) === null || _tree$settings2 === void 0 ? void 0 : _tree$settings2.blocks, (node, blockName) => {
    const blockPresets = pickPresets(node);
    const blockCustom = node.custom;

    if (!(0,external_lodash_namespaceObject.isEmpty)(blockPresets) || !!blockCustom) {
      nodes.push({
        presets: blockPresets,
        custom: blockCustom,
        selector: blockSelectors[blockName].selector
      });
    }
  });
  return nodes;
};
const toCustomProperties = (tree, blockSelectors) => {
  const settings = getNodesWithSettings(tree, blockSelectors);
  let ruleset = '';
  settings.forEach(({
    presets,
    custom,
    selector
  }) => {
    const declarations = getPresetsDeclarations(presets);
    const customProps = flattenTree(custom, '--wp--custom--', '--');

    if (customProps.length > 0) {
      declarations.push(...customProps);
    }

    if (declarations.length > 0) {
      ruleset = ruleset + `${selector}{${declarations.join(';')};}`;
    }
  });
  return ruleset;
};
const toStyles = (tree, blockSelectors) => {
  const nodesWithStyles = getNodesWithStyles(tree, blockSelectors);
  const nodesWithSettings = getNodesWithSettings(tree, blockSelectors);
  let ruleset = '.wp-site-blocks > * { margin-top: 0; margin-bottom: 0; }.wp-site-blocks > * + * { margin-top: var( --wp--style--block-gap ); }';
  nodesWithStyles.forEach(({
    selector,
    styles
  }) => {
    const declarations = getStylesDeclarations(styles);

    if (declarations.length === 0) {
      return;
    }

    ruleset = ruleset + `${selector}{${declarations.join(';')};}`;
  });
  nodesWithSettings.forEach(({
    selector,
    presets
  }) => {
    if (ROOT_BLOCK_SELECTOR === selector) {
      // Do not add extra specificity for top-level classes.
      selector = '';
    }

    const classes = getPresetsClasses(selector, presets);

    if (!(0,external_lodash_namespaceObject.isEmpty)(classes)) {
      ruleset = ruleset + classes;
    }
  });
  return ruleset;
};

const getBlockSelectors = blockTypes => {
  const result = {};
  blockTypes.forEach(blockType => {
    var _blockType$supports$_, _blockType$supports;

    const name = blockType.name;
    const selector = (_blockType$supports$_ = blockType === null || blockType === void 0 ? void 0 : (_blockType$supports = blockType.supports) === null || _blockType$supports === void 0 ? void 0 : _blockType$supports.__experimentalSelector) !== null && _blockType$supports$_ !== void 0 ? _blockType$supports$_ : '.wp-block-' + name.replace('core/', '').replace('/', '-');
    result[name] = {
      name,
      selector
    };
  });
  return result;
};

function useGlobalStylesOutput() {
  const [stylesheets, setStylesheets] = (0,external_wp_element_namespaceObject.useState)([]);
  const [settings, setSettings] = (0,external_wp_element_namespaceObject.useState)({});
  const {
    merged: mergedConfig
  } = (0,external_wp_element_namespaceObject.useContext)(GlobalStylesContext);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    if (!(mergedConfig !== null && mergedConfig !== void 0 && mergedConfig.styles) || !(mergedConfig !== null && mergedConfig !== void 0 && mergedConfig.settings)) {
      return;
    }

    const blockSelectors = getBlockSelectors((0,external_wp_blocks_namespaceObject.getBlockTypes)());
    const customProperties = toCustomProperties(mergedConfig, blockSelectors);
    const globalStyles = toStyles(mergedConfig, blockSelectors);
    setStylesheets([{
      css: customProperties,
      isGlobalStyles: true,
      __experimentalNoWrapper: true
    }, {
      css: globalStyles,
      isGlobalStyles: true
    }]);
    setSettings(mergedConfig.settings);
  }, [mergedConfig]);
  return [stylesheets, settings];
}
//# sourceMappingURL=use-global-styles-output.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/index.js



//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/sidebar/global-styles-sidebar.js


/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */



function GlobalStylesSidebar() {
  const [canReset, onReset] = useGlobalStylesReset();
  return (0,external_wp_element_namespaceObject.createElement)(DefaultSidebar, {
    className: "edit-site-global-styles-sidebar",
    identifier: "edit-site/global-styles",
    title: (0,external_wp_i18n_namespaceObject.__)('Styles'),
    icon: library_styles,
    closeLabel: (0,external_wp_i18n_namespaceObject.__)('Close global styles sidebar'),
    header: (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Flex, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.FlexBlock, null, (0,external_wp_element_namespaceObject.createElement)("strong", null, (0,external_wp_i18n_namespaceObject.__)('Styles')), (0,external_wp_element_namespaceObject.createElement)("span", {
      className: "edit-site-global-styles-sidebar__beta"
    }, (0,external_wp_i18n_namespaceObject.__)('Beta'))), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.FlexItem, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.DropdownMenu, {
      icon: more_vertical,
      label: (0,external_wp_i18n_namespaceObject.__)('More Global Styles Actions'),
      toggleProps: {
        disabled: !canReset
      },
      controls: [{
        title: (0,external_wp_i18n_namespaceObject.__)('Reset to defaults'),
        onClick: onReset
      }]
    })))
  }, (0,external_wp_element_namespaceObject.createElement)(ui, null));
}
//# sourceMappingURL=global-styles-sidebar.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/sidebar/constants.js
const SIDEBAR_TEMPLATE = 'edit-site/template';
const SIDEBAR_BLOCK = 'edit-site/block-inspector';
//# sourceMappingURL=constants.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/sidebar/settings-header/index.js


/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */




const SettingsHeader = ({
  sidebarName
}) => {
  const {
    enableComplementaryArea
  } = (0,external_wp_data_namespaceObject.useDispatch)(store);

  const openTemplateSettings = () => enableComplementaryArea(STORE_NAME, SIDEBAR_TEMPLATE);

  const openBlockSettings = () => enableComplementaryArea(STORE_NAME, SIDEBAR_BLOCK);

  const [templateAriaLabel, templateActiveClass] = sidebarName === SIDEBAR_TEMPLATE ? // translators: ARIA label for the Template sidebar tab, selected.
  [(0,external_wp_i18n_namespaceObject.__)('Template (selected)'), 'is-active'] : // translators: ARIA label for the Template Settings Sidebar tab, not selected.
  [(0,external_wp_i18n_namespaceObject.__)('Template'), ''];
  const [blockAriaLabel, blockActiveClass] = sidebarName === SIDEBAR_BLOCK ? // translators: ARIA label for the Block Settings Sidebar tab, selected.
  [(0,external_wp_i18n_namespaceObject.__)('Block (selected)'), 'is-active'] : // translators: ARIA label for the Block Settings Sidebar tab, not selected.
  [(0,external_wp_i18n_namespaceObject.__)('Block'), ''];
  /* Use a list so screen readers will announce how many tabs there are. */

  return (0,external_wp_element_namespaceObject.createElement)("ul", null, (0,external_wp_element_namespaceObject.createElement)("li", null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    onClick: openTemplateSettings,
    className: `edit-site-sidebar__panel-tab ${templateActiveClass}`,
    "aria-label": templateAriaLabel // translators: Data label for the Template Settings Sidebar tab.
    ,
    "data-label": (0,external_wp_i18n_namespaceObject.__)('Template')
  }, // translators: Text label for the Template Settings Sidebar tab.
  (0,external_wp_i18n_namespaceObject.__)('Template'))), (0,external_wp_element_namespaceObject.createElement)("li", null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    onClick: openBlockSettings,
    className: `edit-site-sidebar__panel-tab ${blockActiveClass}`,
    "aria-label": blockAriaLabel // translators: Data label for the Block Settings Sidebar tab.
    ,
    "data-label": (0,external_wp_i18n_namespaceObject.__)('Block')
  }, // translators: Text label for the Block Settings Sidebar tab.
  (0,external_wp_i18n_namespaceObject.__)('Block'))));
};

/* harmony default export */ var settings_header = (SettingsHeader);
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/sidebar/template-card/template-areas.js


/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */




function TemplateAreaItem({
  area,
  clientId
}) {
  const {
    selectBlock,
    toggleBlockHighlight
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_blockEditor_namespaceObject.store);

  const highlightBlock = () => toggleBlockHighlight(clientId, true);

  const cancelHighlightBlock = () => toggleBlockHighlight(clientId, false);

  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    className: "edit-site-template-card__template-areas-item",
    icon: (0,external_wp_editor_namespaceObject.getTemplatePartIcon)(area),
    onMouseOver: highlightBlock,
    onMouseLeave: cancelHighlightBlock,
    onFocus: highlightBlock,
    onBlur: cancelHighlightBlock,
    onClick: () => {
      selectBlock(clientId);
    }
  }, TEMPLATE_PART_AREA_TO_NAME[area]);
}

function template_areas_TemplateAreas() {
  const templateParts = (0,external_wp_data_namespaceObject.useSelect)(select => select(store_store).getCurrentTemplateTemplateParts(), []);

  if (!templateParts.length) {
    return null;
  }

  return (0,external_wp_element_namespaceObject.createElement)("section", {
    className: "edit-site-template-card__template-areas"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalHeading, {
    level: 3,
    className: "edit-site-template-card__template-areas-title"
  }, (0,external_wp_i18n_namespaceObject.__)('Areas')), (0,external_wp_element_namespaceObject.createElement)("ul", {
    className: "edit-site-template-card__template-areas-list"
  }, templateParts.map(({
    templatePart,
    block
  }) => (0,external_wp_element_namespaceObject.createElement)("li", {
    key: templatePart.slug
  }, (0,external_wp_element_namespaceObject.createElement)(TemplateAreaItem, {
    area: templatePart.area,
    clientId: block.clientId
  })))));
}
//# sourceMappingURL=template-areas.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/sidebar/template-card/index.js


/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */



function TemplateCard() {
  const {
    title,
    description,
    icon
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      getEditedPostType,
      getEditedPostId
    } = select(store_store);
    const {
      getEntityRecord
    } = select(external_wp_coreData_namespaceObject.store);
    const {
      __experimentalGetTemplateInfo: getTemplateInfo
    } = select(external_wp_editor_namespaceObject.store);
    const postType = getEditedPostType();
    const postId = getEditedPostId();
    const record = getEntityRecord('postType', postType, postId);
    const info = record ? getTemplateInfo(record) : {};
    return info;
  }, []);

  if (!title && !description) {
    return null;
  }

  return (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-site-template-card"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Icon, {
    className: "edit-site-template-card__icon",
    icon: icon
  }), (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-site-template-card__content"
  }, (0,external_wp_element_namespaceObject.createElement)("h2", {
    className: "edit-site-template-card__title"
  }, title), (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-site-template-card__description"
  }, description), (0,external_wp_element_namespaceObject.createElement)(template_areas_TemplateAreas, null)));
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/sidebar/index.js


/**
 * WordPress dependencies
 */







/**
 * Internal dependencies
 */







const {
  Slot: InspectorSlot,
  Fill: InspectorFill
} = (0,external_wp_components_namespaceObject.createSlotFill)('EditSiteSidebarInspector');
const SidebarInspectorFill = InspectorFill;
function SidebarComplementaryAreaFills() {
  const {
    sidebar,
    isEditorSidebarOpened,
    hasBlockSelection
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const _sidebar = select(store).getActiveComplementaryArea(STORE_NAME);

    const _isEditorSidebarOpened = [SIDEBAR_BLOCK, SIDEBAR_TEMPLATE].includes(_sidebar);

    return {
      sidebar: _sidebar,
      isEditorSidebarOpened: _isEditorSidebarOpened,
      hasBlockSelection: !!select(external_wp_blockEditor_namespaceObject.store).getBlockSelectionStart()
    };
  }, []);
  const {
    enableComplementaryArea
  } = (0,external_wp_data_namespaceObject.useDispatch)(store);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    if (!isEditorSidebarOpened) return;

    if (hasBlockSelection) {
      enableComplementaryArea(STORE_NAME, SIDEBAR_BLOCK);
    } else {
      enableComplementaryArea(STORE_NAME, SIDEBAR_TEMPLATE);
    }
  }, [hasBlockSelection, isEditorSidebarOpened]);
  let sidebarName = sidebar;

  if (!isEditorSidebarOpened) {
    sidebarName = hasBlockSelection ? SIDEBAR_BLOCK : SIDEBAR_TEMPLATE;
  }

  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(DefaultSidebar, {
    identifier: sidebarName,
    title: (0,external_wp_i18n_namespaceObject.__)('Settings'),
    icon: library_cog,
    closeLabel: (0,external_wp_i18n_namespaceObject.__)('Close settings sidebar'),
    header: (0,external_wp_element_namespaceObject.createElement)(settings_header, {
      sidebarName: sidebarName
    }),
    headerClassName: "edit-site-sidebar__panel-tabs"
  }, sidebarName === SIDEBAR_TEMPLATE && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.PanelBody, null, (0,external_wp_element_namespaceObject.createElement)(TemplateCard, null)), sidebarName === SIDEBAR_BLOCK && (0,external_wp_element_namespaceObject.createElement)(InspectorSlot, {
    bubblesVirtually: true
  })), (0,external_wp_element_namespaceObject.createElement)(GlobalStylesSidebar, null));
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/template-part-converter/convert-to-regular.js


/**
 * WordPress dependencies
 */




function ConvertToRegularBlocks({
  clientId
}) {
  const {
    getBlocks
  } = (0,external_wp_data_namespaceObject.useSelect)(external_wp_blockEditor_namespaceObject.store);
  const {
    replaceBlocks
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_blockEditor_namespaceObject.store);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.BlockSettingsMenuControls, null, ({
    onClose
  }) => (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuItem, {
    onClick: () => {
      replaceBlocks(clientId, getBlocks(clientId));
      onClose();
    }
  }, (0,external_wp_i18n_namespaceObject.__)('Detach blocks from template part')));
}
//# sourceMappingURL=convert-to-regular.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/template-part-converter/convert-to-template-part.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */












/**
 * Internal dependencies
 */


function ConvertToTemplatePart({
  clientIds,
  blocks
}) {
  const instanceId = (0,external_wp_compose_namespaceObject.useInstanceId)(ConvertToTemplatePart);
  const [isModalOpen, setIsModalOpen] = (0,external_wp_element_namespaceObject.useState)(false);
  const [title, setTitle] = (0,external_wp_element_namespaceObject.useState)('');
  const {
    replaceBlocks
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_blockEditor_namespaceObject.store);
  const {
    saveEntityRecord
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_coreData_namespaceObject.store);
  const {
    createSuccessNotice
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_notices_namespaceObject.store);
  const [area, setArea] = (0,external_wp_element_namespaceObject.useState)(TEMPLATE_PART_AREA_GENERAL);
  const templatePartAreas = (0,external_wp_data_namespaceObject.useSelect)(select => select(external_wp_editor_namespaceObject.store).__experimentalGetDefaultTemplatePartAreas(), []);

  const onConvert = async templatePartTitle => {
    const defaultTitle = (0,external_wp_i18n_namespaceObject.__)('Untitled Template Part');

    const templatePart = await saveEntityRecord('postType', 'wp_template_part', {
      slug: (0,external_lodash_namespaceObject.kebabCase)(templatePartTitle || defaultTitle),
      title: templatePartTitle || defaultTitle,
      content: (0,external_wp_blocks_namespaceObject.serialize)(blocks),
      area
    });
    replaceBlocks(clientIds, (0,external_wp_blocks_namespaceObject.createBlock)('core/template-part', {
      slug: templatePart.slug,
      theme: templatePart.theme
    }));
    createSuccessNotice((0,external_wp_i18n_namespaceObject.__)('Template part created.'), {
      type: 'snackbar'
    });
  };

  return (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.BlockSettingsMenuControls, null, ({
    onClose
  }) => (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuItem, {
    onClick: () => {
      setIsModalOpen(true);
    }
  }, (0,external_wp_i18n_namespaceObject.__)('Make template part')), isModalOpen && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Modal, {
    title: (0,external_wp_i18n_namespaceObject.__)('Create a template part'),
    closeLabel: (0,external_wp_i18n_namespaceObject.__)('Close'),
    onRequestClose: () => {
      setIsModalOpen(false);
      setTitle('');
    },
    overlayClassName: "edit-site-template-part-converter__modal"
  }, (0,external_wp_element_namespaceObject.createElement)("form", {
    onSubmit: event => {
      event.preventDefault();
      onConvert(title);
      setIsModalOpen(false);
      setTitle('');
      onClose();
    }
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.TextControl, {
    label: (0,external_wp_i18n_namespaceObject.__)('Name'),
    value: title,
    onChange: setTitle
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.BaseControl, {
    label: (0,external_wp_i18n_namespaceObject.__)('Area'),
    id: `edit-site-template-part-converter__area-selection-${instanceId}`,
    className: "edit-site-template-part-converter__area-base-control"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalRadioGroup, {
    label: (0,external_wp_i18n_namespaceObject.__)('Area'),
    className: "edit-site-template-part-converter__area-radio-group",
    id: `edit-site-template-part-converter__area-selection-${instanceId}`,
    onChange: setArea,
    checked: area
  }, templatePartAreas.map(({
    icon,
    label,
    area: value,
    description
  }) => (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalRadio, {
    key: label,
    value: value,
    className: "edit-site-template-part-converter__area-radio"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Flex, {
    align: "start",
    justify: "start"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.FlexItem, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Icon, {
    icon: icon
  })), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.FlexBlock, {
    className: "edit-site-template-part-converter__option-label"
  }, label, (0,external_wp_element_namespaceObject.createElement)("div", null, description)), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.FlexItem, {
    className: "edit-site-template-part-converter__checkbox"
  }, area === value && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Icon, {
    icon: library_check
  }))))))), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Flex, {
    className: "edit-site-template-part-converter__convert-modal-actions",
    justify: "flex-end"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.FlexItem, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    variant: "secondary",
    onClick: () => {
      setIsModalOpen(false);
      setTitle('');
    }
  }, (0,external_wp_i18n_namespaceObject.__)('Cancel'))), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.FlexItem, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    variant: "primary",
    type: "submit"
  }, (0,external_wp_i18n_namespaceObject.__)('Create'))))))));
}
//# sourceMappingURL=convert-to-template-part.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/template-part-converter/index.js


/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */



function TemplatePartConverter() {
  var _blocks$;

  const {
    clientIds,
    blocks
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      getSelectedBlockClientIds,
      getBlocksByClientId
    } = select(external_wp_blockEditor_namespaceObject.store);
    const selectedBlockClientIds = getSelectedBlockClientIds();
    return {
      clientIds: selectedBlockClientIds,
      blocks: getBlocksByClientId(selectedBlockClientIds)
    };
  }, []); // Allow converting a single template part to standard blocks.

  if (blocks.length === 1 && ((_blocks$ = blocks[0]) === null || _blocks$ === void 0 ? void 0 : _blocks$.name) === 'core/template-part') {
    return (0,external_wp_element_namespaceObject.createElement)(ConvertToRegularBlocks, {
      clientId: clientIds[0]
    });
  }

  return (0,external_wp_element_namespaceObject.createElement)(ConvertToTemplatePart, {
    clientIds: clientIds,
    blocks: blocks
  });
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/icons/build-module/library/pencil.js


/**
 * WordPress dependencies
 */

const pencil = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M20.1 5.1L16.9 2 6.2 12.7l-1.3 4.4 4.5-1.3L20.1 5.1zM4 20.8h8v-1.5H4v1.5z"
}));
/* harmony default export */ var library_pencil = (pencil);
//# sourceMappingURL=pencil.js.map
;// CONCATENATED MODULE: ./packages/icons/build-module/library/edit.js
/**
 * Internal dependencies
 */

/* harmony default export */ var edit = (library_pencil);
//# sourceMappingURL=edit.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigate-to-link/index.js


/**
 * WordPress dependencies
 */







function NavigateToLink({
  type,
  id,
  activePage,
  onActivePageChange
}) {
  const post = (0,external_wp_data_namespaceObject.useSelect)(select => type && id && type !== 'URL' && select(external_wp_coreData_namespaceObject.store).getEntityRecord('postType', type, id), [type, id]);
  const onClick = (0,external_wp_element_namespaceObject.useMemo)(() => {
    if (!(post !== null && post !== void 0 && post.link)) return null;
    const path = (0,external_wp_url_namespaceObject.getPathAndQueryString)(post.link);
    if (path === (activePage === null || activePage === void 0 ? void 0 : activePage.path)) return null;
    return () => onActivePageChange({
      type,
      slug: post.slug,
      path,
      context: {
        postType: post.type,
        postId: post.id
      }
    });
  }, [post, activePage === null || activePage === void 0 ? void 0 : activePage.path, onActivePageChange]);
  return onClick && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    icon: edit,
    label: (0,external_wp_i18n_namespaceObject.__)('Edit Page Template'),
    onClick: onClick
  });
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/block-editor/block-inspector-button.js


/**
 * WordPress dependencies
 */






/**
 * Internal dependencies
 */




function BlockInspectorButton({
  onClick = () => {}
}) {
  const {
    shortcut,
    isBlockInspectorOpen
  } = (0,external_wp_data_namespaceObject.useSelect)(select => ({
    shortcut: select(external_wp_keyboardShortcuts_namespaceObject.store).getShortcutRepresentation('core/edit-site/toggle-block-settings-sidebar'),
    isBlockInspectorOpen: select(store).getActiveComplementaryArea(store_store.name) === SIDEBAR_BLOCK
  }), []);
  const {
    enableComplementaryArea,
    disableComplementaryArea
  } = (0,external_wp_data_namespaceObject.useDispatch)(store);
  const label = isBlockInspectorOpen ? (0,external_wp_i18n_namespaceObject.__)('Hide more settings') : (0,external_wp_i18n_namespaceObject.__)('Show more settings');
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuItem, {
    onClick: () => {
      if (isBlockInspectorOpen) {
        disableComplementaryArea(STORE_NAME);
        (0,external_wp_a11y_namespaceObject.speak)((0,external_wp_i18n_namespaceObject.__)('Block settings closed'));
      } else {
        enableComplementaryArea(STORE_NAME, SIDEBAR_BLOCK);
        (0,external_wp_a11y_namespaceObject.speak)((0,external_wp_i18n_namespaceObject.__)('Additional settings are now available in the Editor block settings sidebar'));
      } // Close dropdown menu.


      onClick();
    },
    shortcut: shortcut
  }, label);
}
//# sourceMappingURL=block-inspector-button.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/edit-template-part-menu-button/index.js


/**
 * WordPress dependencies
 */






/**
 * Internal dependencies
 */


function EditTemplatePartMenuButton() {
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.BlockSettingsMenuControls, null, ({
    selectedClientIds,
    onClose
  }) => (0,external_wp_element_namespaceObject.createElement)(EditTemplatePartMenuItem, {
    selectedClientId: selectedClientIds[0],
    onClose: onClose
  }));
}

function EditTemplatePartMenuItem({
  selectedClientId,
  onClose
}) {
  const selectedTemplatePart = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const block = select(external_wp_blockEditor_namespaceObject.store).getBlock(selectedClientId);

    if (block && (0,external_wp_blocks_namespaceObject.isTemplatePart)(block)) {
      const {
        theme,
        slug
      } = block.attributes;
      return select(external_wp_coreData_namespaceObject.store).getEntityRecord('postType', 'wp_template_part', // Ideally this should be an official public API.
      `${theme}//${slug}`);
    }
  }, [selectedClientId]);
  const {
    pushTemplatePart
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);

  if (!selectedTemplatePart) {
    return null;
  }

  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuItem, {
    onClick: () => {
      pushTemplatePart(selectedTemplatePart.id);
      onClose();
    }
  },
  /* translators: %s: template part title */
  (0,external_wp_i18n_namespaceObject.sprintf)((0,external_wp_i18n_namespaceObject.__)('Edit %s'), selectedTemplatePart.slug));
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/icons/build-module/library/arrow-left.js


/**
 * WordPress dependencies
 */

const arrowLeft = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M20 10.8H6.7l4.1-4.5-1.1-1.1-5.8 6.3 5.8 5.8 1.1-1.1-4-3.9H20z"
}));
/* harmony default export */ var arrow_left = (arrowLeft);
//# sourceMappingURL=arrow-left.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/block-editor/back-button.js


/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */



function BackButton() {
  const {
    isTemplatePart,
    previousTemplateId
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      getEditedPostType,
      getPreviousEditedPostId
    } = select(store_store);
    return {
      isTemplatePart: getEditedPostType() === 'wp_template_part',
      previousTemplateId: getPreviousEditedPostId()
    };
  }, []);
  const {
    goBack
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);

  if (!isTemplatePart || !previousTemplateId) {
    return null;
  }

  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    className: "edit-site-visual-editor__back-button",
    icon: arrow_left,
    onClick: () => {
      goBack();
    }
  }, (0,external_wp_i18n_namespaceObject.__)('Back'));
}

/* harmony default export */ var back_button = (BackButton);
//# sourceMappingURL=back-button.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/block-editor/resize-handle.js


/**
 * WordPress dependencies
 */



const DELTA_DISTANCE = 20; // The distance to resize per keydown in pixels.

function ResizeHandle({
  direction,
  resizeWidthBy
}) {
  function handleKeyDown(event) {
    const {
      keyCode
    } = event;

    if (direction === 'left' && keyCode === external_wp_keycodes_namespaceObject.LEFT || direction === 'right' && keyCode === external_wp_keycodes_namespaceObject.RIGHT) {
      resizeWidthBy(DELTA_DISTANCE);
    } else if (direction === 'left' && keyCode === external_wp_keycodes_namespaceObject.RIGHT || direction === 'right' && keyCode === external_wp_keycodes_namespaceObject.LEFT) {
      resizeWidthBy(-DELTA_DISTANCE);
    }
  }

  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)("button", {
    className: `resizable-editor__drag-handle is-${direction}`,
    "aria-label": (0,external_wp_i18n_namespaceObject.__)('Drag to resize'),
    "aria-describedby": `resizable-editor__resize-help-${direction}`,
    onKeyDown: handleKeyDown
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.VisuallyHidden, {
    id: `resizable-editor__resize-help-${direction}`
  }, (0,external_wp_i18n_namespaceObject.__)('Use left and right arrow keys to resize the canvas.')));
}
//# sourceMappingURL=resize-handle.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/block-editor/resizable-editor.js



/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */



const DEFAULT_STYLES = {
  width: '100%',
  height: '100%'
}; // Removes the inline styles in the drag handles.

const HANDLE_STYLES_OVERRIDE = {
  position: undefined,
  userSelect: undefined,
  cursor: undefined,
  width: undefined,
  height: undefined,
  top: undefined,
  right: undefined,
  bottom: undefined,
  left: undefined
};

function ResizableEditor({
  enableResizing,
  settings,
  ...props
}) {
  const deviceType = (0,external_wp_data_namespaceObject.useSelect)(select => select(store_store).__experimentalGetPreviewDeviceType(), []);
  const deviceStyles = (0,external_wp_blockEditor_namespaceObject.__experimentalUseResizeCanvas)(deviceType);
  const [width, setWidth] = (0,external_wp_element_namespaceObject.useState)(DEFAULT_STYLES.width);
  const [height, setHeight] = (0,external_wp_element_namespaceObject.useState)(DEFAULT_STYLES.height);
  const iframeRef = (0,external_wp_element_namespaceObject.useRef)();
  const mouseMoveTypingResetRef = (0,external_wp_blockEditor_namespaceObject.__unstableUseMouseMoveTypingReset)();
  const ref = (0,external_wp_compose_namespaceObject.useMergeRefs)([iframeRef, mouseMoveTypingResetRef]);
  (0,external_wp_element_namespaceObject.useEffect)(function autoResizeIframeHeight() {
    const iframe = iframeRef.current;

    if (!iframe || !enableResizing) {
      return;
    }

    const resizeObserver = new iframe.contentWindow.ResizeObserver(() => {
      setHeight(iframe.contentDocument.querySelector(`.edit-site-block-editor__block-list`).offsetHeight);
    }); // Observing the <html> rather than the <body> because the latter
    // gets destroyed and remounted after initialization in <Iframe>.

    resizeObserver.observe(iframe.contentDocument.documentElement);
    return () => {
      resizeObserver.disconnect();
    };
  }, [enableResizing]);
  const resizeWidthBy = (0,external_wp_element_namespaceObject.useCallback)(deltaPixels => {
    if (iframeRef.current) {
      setWidth(iframeRef.current.offsetWidth + deltaPixels);
    }
  }, []);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.ResizableBox, {
    size: {
      width,
      height
    },
    onResizeStop: (event, direction, element) => {
      setWidth(element.style.width);
    },
    minWidth: 300,
    maxWidth: "100%",
    maxHeight: "100%",
    enable: {
      right: enableResizing,
      left: enableResizing
    },
    showHandle: enableResizing // The editor is centered horizontally, resizing it only
    // moves half the distance. Hence double the ratio to correctly
    // align the cursor to the resizer handle.
    ,
    resizeRatio: 2,
    handleComponent: {
      left: (0,external_wp_element_namespaceObject.createElement)(ResizeHandle, {
        direction: "left",
        resizeWidthBy: resizeWidthBy
      }),
      right: (0,external_wp_element_namespaceObject.createElement)(ResizeHandle, {
        direction: "right",
        resizeWidthBy: resizeWidthBy
      })
    },
    handleClasses: undefined,
    handleStyles: {
      left: HANDLE_STYLES_OVERRIDE,
      right: HANDLE_STYLES_OVERRIDE
    }
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.__unstableIframe, _extends({
    style: enableResizing ? undefined : deviceStyles,
    head: (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.__unstableEditorStyles, {
      styles: settings.styles
    }), (0,external_wp_element_namespaceObject.createElement)("style", null, // Forming a "block formatting context" to prevent margin collapsing.
    // @see https://developer.mozilla.org/en-US/docs/Web/Guide/CSS/Block_formatting_context
    `.edit-site-block-editor__block-list { display: flow-root; }`)),
    ref: ref,
    name: "editor-canvas",
    className: "edit-site-visual-editor__editor-canvas"
  }, props)));
}

/* harmony default export */ var resizable_editor = (ResizableEditor);
//# sourceMappingURL=resizable-editor.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/block-editor/index.js



/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */






/**
 * Internal dependencies
 */









const LAYOUT = {
  type: 'default',
  // At the root level of the site editor, no alignments should be allowed.
  alignments: []
};
function BlockEditor({
  setIsInserterOpen
}) {
  const {
    settings,
    templateType,
    templateId,
    page
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      getSettings,
      getEditedPostType,
      getEditedPostId,
      getPage
    } = select(store_store);
    return {
      settings: getSettings(setIsInserterOpen),
      templateType: getEditedPostType(),
      templateId: getEditedPostId(),
      page: getPage()
    };
  }, [setIsInserterOpen]);
  const [blocks, onInput, onChange] = (0,external_wp_coreData_namespaceObject.useEntityBlockEditor)('postType', templateType);
  const {
    setPage
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);
  const contentRef = (0,external_wp_element_namespaceObject.useRef)();
  const mergedRefs = (0,external_wp_compose_namespaceObject.useMergeRefs)([contentRef, (0,external_wp_blockEditor_namespaceObject.__unstableUseTypingObserver)()]);
  const isMobileViewport = (0,external_wp_compose_namespaceObject.useViewportMatch)('small', '<');
  const {
    clearSelectedBlock
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_blockEditor_namespaceObject.store);
  const isTemplatePart = templateType === 'wp_template_part';
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.BlockEditorProvider, {
    settings: settings,
    value: blocks,
    onInput: onInput,
    onChange: onChange,
    useSubRegistry: false
  }, (0,external_wp_element_namespaceObject.createElement)(EditTemplatePartMenuButton, null), (0,external_wp_element_namespaceObject.createElement)(TemplatePartConverter, null), (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.__experimentalLinkControl.ViewerFill, null, (0,external_wp_element_namespaceObject.useCallback)(fillProps => (0,external_wp_element_namespaceObject.createElement)(NavigateToLink, _extends({}, fillProps, {
    activePage: page,
    onActivePageChange: setPage
  })), [page])), (0,external_wp_element_namespaceObject.createElement)(SidebarInspectorFill, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.BlockInspector, null)), (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.BlockTools, {
    className: classnames_default()('edit-site-visual-editor', {
      'is-focus-mode': isTemplatePart
    }),
    __unstableContentRef: contentRef,
    onClick: event => {
      // Clear selected block when clicking on the gray background.
      if (event.target === event.currentTarget) {
        clearSelectedBlock();
      }
    }
  }, (0,external_wp_element_namespaceObject.createElement)(back_button, null), (0,external_wp_element_namespaceObject.createElement)(resizable_editor // Reinitialize the editor and reset the states when the template changes.
  , {
    key: templateId,
    enableResizing: isTemplatePart && // Disable resizing in mobile viewport.
    !isMobileViewport,
    settings: settings,
    contentRef: mergedRefs
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.BlockList, {
    className: "edit-site-block-editor__block-list wp-site-blocks",
    __experimentalLayout: LAYOUT
  })), (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.__unstableBlockSettingsMenuFirstItem, null, ({
    onClose
  }) => (0,external_wp_element_namespaceObject.createElement)(BlockInspectorButton, {
    onClick: onClose
  }))));
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/keyboard-shortcuts/index.js
/**
 * WordPress dependencies
 */






/**
 * Internal dependencies
 */





function KeyboardShortcuts({
  openEntitiesSavedStates
}) {
  const {
    __experimentalGetDirtyEntityRecords,
    isSavingEntityRecord
  } = (0,external_wp_data_namespaceObject.useSelect)(external_wp_coreData_namespaceObject.store);
  const isListViewOpen = (0,external_wp_data_namespaceObject.useSelect)(select => select(store_store).isListViewOpened(), []);
  const isBlockInspectorOpen = (0,external_wp_data_namespaceObject.useSelect)(select => select(store).getActiveComplementaryArea(store_store.name) === SIDEBAR_BLOCK, []);
  const {
    redo,
    undo
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_coreData_namespaceObject.store);
  const {
    setIsListViewOpened
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);
  const {
    enableComplementaryArea,
    disableComplementaryArea
  } = (0,external_wp_data_namespaceObject.useDispatch)(store);
  (0,external_wp_keyboardShortcuts_namespaceObject.useShortcut)('core/edit-site/save', event => {
    event.preventDefault();

    const dirtyEntityRecords = __experimentalGetDirtyEntityRecords();

    const isDirty = !!dirtyEntityRecords.length;
    const isSaving = dirtyEntityRecords.some(record => isSavingEntityRecord(record.kind, record.name, record.key));

    if (!isSaving && isDirty) {
      openEntitiesSavedStates();
    }
  });
  (0,external_wp_keyboardShortcuts_namespaceObject.useShortcut)('core/edit-site/undo', event => {
    undo();
    event.preventDefault();
  });
  (0,external_wp_keyboardShortcuts_namespaceObject.useShortcut)('core/edit-site/redo', event => {
    redo();
    event.preventDefault();
  });
  (0,external_wp_keyboardShortcuts_namespaceObject.useShortcut)('core/edit-site/toggle-list-view', () => {
    setIsListViewOpened(!isListViewOpen);
  });
  (0,external_wp_keyboardShortcuts_namespaceObject.useShortcut)('core/edit-site/toggle-block-settings-sidebar', event => {
    // This shortcut has no known clashes, but use preventDefault to prevent any
    // obscure shortcuts from triggering.
    event.preventDefault();

    if (isBlockInspectorOpen) {
      disableComplementaryArea(STORE_NAME);
    } else {
      enableComplementaryArea(STORE_NAME, SIDEBAR_BLOCK);
    }
  });
  return null;
}

function KeyboardShortcutsRegister() {
  // Registering the shortcuts
  const {
    registerShortcut
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_keyboardShortcuts_namespaceObject.store);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    registerShortcut({
      name: 'core/edit-site/save',
      category: 'global',
      description: (0,external_wp_i18n_namespaceObject.__)('Save your changes.'),
      keyCombination: {
        modifier: 'primary',
        character: 's'
      }
    });
    registerShortcut({
      name: 'core/edit-site/undo',
      category: 'global',
      description: (0,external_wp_i18n_namespaceObject.__)('Undo your last changes.'),
      keyCombination: {
        modifier: 'primary',
        character: 'z'
      }
    });
    registerShortcut({
      name: 'core/edit-site/redo',
      category: 'global',
      description: (0,external_wp_i18n_namespaceObject.__)('Redo your last undo.'),
      keyCombination: {
        modifier: 'primaryShift',
        character: 'z'
      }
    });
    registerShortcut({
      name: 'core/edit-site/toggle-list-view',
      category: 'global',
      description: (0,external_wp_i18n_namespaceObject.__)('Open the block list view.'),
      keyCombination: {
        modifier: 'access',
        character: 'o'
      }
    });
    registerShortcut({
      name: 'core/edit-site/toggle-block-settings-sidebar',
      category: 'global',
      description: (0,external_wp_i18n_namespaceObject.__)('Show or hide the block settings sidebar.'),
      keyCombination: {
        modifier: 'primaryShift',
        character: ','
      }
    });
  }, [registerShortcut]);
  return null;
}

KeyboardShortcuts.Register = KeyboardShortcutsRegister;
/* harmony default export */ var keyboard_shortcuts = (KeyboardShortcuts);
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: external ["wp","htmlEntities"]
var external_wp_htmlEntities_namespaceObject = window["wp"]["htmlEntities"];
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/template-preview.js


/**
 * WordPress dependencies
 */



function TemplatePreview({
  rawContent,
  blockContext
}) {
  const blocks = (0,external_wp_element_namespaceObject.useMemo)(() => rawContent ? (0,external_wp_blocks_namespaceObject.parse)(rawContent) : [], [rawContent]);

  if (!blocks || blocks.length === 0) {
    return null;
  }

  if (blockContext) {
    return (0,external_wp_element_namespaceObject.createElement)("div", {
      className: "edit-site-navigation-panel__preview"
    }, (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.BlockContextProvider, {
      value: blockContext
    }, (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.BlockPreview, {
      blocks: blocks,
      viewportWidth: 1200
    })));
  }

  return (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-site-navigation-panel__preview"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.BlockPreview, {
    blocks: blocks,
    viewportWidth: 1200
  }));
}
//# sourceMappingURL=template-preview.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/content-navigation-item.js


/**
 * WordPress dependencies
 */






/**
 * Internal dependencies
 */





const getTitle = entity => {
  var _entity$title;

  const title = entity.taxonomy ? entity.name : entity === null || entity === void 0 ? void 0 : (_entity$title = entity.title) === null || _entity$title === void 0 ? void 0 : _entity$title.rendered; // Make sure encoded characters are displayed as the characters they represent.

  const titleElement = document.createElement('div');
  titleElement.innerHTML = title;
  return titleElement.textContent || titleElement.innerText || '';
};

function ContentNavigationItem({
  item
}) {
  const [isPreviewVisible, setIsPreviewVisible] = (0,external_wp_element_namespaceObject.useState)(false);
  const previewContent = (0,external_wp_data_namespaceObject.useSelect)(select => {
    if (!isPreviewVisible) {
      return null;
    }

    const template = select(external_wp_coreData_namespaceObject.store).__experimentalGetTemplateForLink(item.link);

    return template === null || template === void 0 ? void 0 : template.content;
  }, [isPreviewVisible]);
  const {
    setPage,
    setIsNavigationPanelOpened
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);
  const onActivateItem = (0,external_wp_element_namespaceObject.useCallback)(() => {
    const {
      type,
      slug,
      link,
      id
    } = item;
    setPage({
      type,
      slug,
      path: (0,external_wp_url_namespaceObject.getPathAndQueryString)(link),
      context: {
        postType: type,
        postId: id
      }
    });
    setIsNavigationPanelOpened(false);
  }, [setPage, item]);

  if (!item) {
    return null;
  }

  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationItem, {
    className: "edit-site-navigation-panel__content-item",
    item: `${item.taxonomy || item.type}-${item.id}`,
    title: getTitle(item) || (0,external_wp_i18n_namespaceObject.__)('(no title)'),
    onClick: onActivateItem,
    onMouseEnter: () => setIsPreviewVisible(true),
    onMouseLeave: () => setIsPreviewVisible(false)
  }), isPreviewVisible && previewContent && (0,external_wp_element_namespaceObject.createElement)(NavigationPanelPreviewFill, null, (0,external_wp_element_namespaceObject.createElement)(TemplatePreview, {
    rawContent: previewContent,
    blockContext: {
      postType: item.type,
      postId: item.id
    }
  })));
}
//# sourceMappingURL=content-navigation-item.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/utils.js
/**
 * External dependencies
 */
 // @see packages/block-editor/src/components/inserter/search-items.js

const normalizeInput = input => (0,external_lodash_namespaceObject.deburr)(input).replace(/^\//, '').toLowerCase();
const normalizedSearch = (title, search) => -1 !== normalizeInput(title).indexOf(normalizeInput(search));
//# sourceMappingURL=utils.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/template-navigation-item.js


/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */




function TemplateNavigationItem({
  item
}) {
  const {
    title,
    description
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    var _item$title;

    return 'wp_template' === item.type ? select(external_wp_editor_namespaceObject.store).__experimentalGetTemplateInfo(item) : {
      title: (item === null || item === void 0 ? void 0 : (_item$title = item.title) === null || _item$title === void 0 ? void 0 : _item$title.rendered) || (item === null || item === void 0 ? void 0 : item.slug),
      description: ''
    };
  }, []);
  const {
    setTemplate,
    setTemplatePart,
    setIsNavigationPanelOpened
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);
  const [isPreviewVisible, setIsPreviewVisible] = (0,external_wp_element_namespaceObject.useState)(false);

  if (!item) {
    return null;
  }

  const onActivateItem = () => {
    if ('wp_template' === item.type) {
      setTemplate(item.id, item.slug);
    } else {
      setTemplatePart(item.id);
    }

    setIsNavigationPanelOpened(false);
  };

  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationItem, {
    className: "edit-site-navigation-panel__template-item",
    item: `${item.type}-${item.id}`
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    onClick: onActivateItem,
    onMouseEnter: () => setIsPreviewVisible(true),
    onMouseLeave: () => setIsPreviewVisible(false)
  }, (0,external_wp_element_namespaceObject.createElement)("span", {
    className: "edit-site-navigation-panel__info-wrapper"
  }, (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-site-navigation-panel__template-item-title"
  }, 'draft' === item.status && (0,external_wp_element_namespaceObject.createElement)("em", null, (0,external_wp_i18n_namespaceObject.__)('[Draft]')), title), description && (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-site-navigation-panel__template-item-description"
  }, description))), isPreviewVisible && (0,external_wp_element_namespaceObject.createElement)(NavigationPanelPreviewFill, null, (0,external_wp_element_namespaceObject.createElement)(TemplatePreview, {
    rawContent: item.content.raw
  })));
}
//# sourceMappingURL=template-navigation-item.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/search-results.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */






function SearchResults({
  items,
  search,
  disableFilter
}) {
  let itemType = null;

  if ((items === null || items === void 0 ? void 0 : items.length) > 0) {
    if (items[0].taxonomy) {
      itemType = 'taxonomy';
    } else {
      itemType = items[0].type;
    }
  }

  const itemInfos = (0,external_wp_data_namespaceObject.useSelect)(select => {
    if (itemType === null || items === null) {
      return [];
    }

    if (itemType === 'wp_template') {
      const {
        __experimentalGetTemplateInfo: getTemplateInfo
      } = select(external_wp_editor_namespaceObject.store);
      return items.map(item => ({
        slug: item.slug,
        ...getTemplateInfo(item)
      }));
    }

    if (itemType === 'taxonomy') {
      return items.map(item => ({
        slug: item.slug,
        title: item.name,
        description: item.description
      }));
    }

    return items.map(item => {
      var _item$title, _item$excerpt;

      return {
        slug: item.slug,
        title: (_item$title = item.title) === null || _item$title === void 0 ? void 0 : _item$title.rendered,
        description: (_item$excerpt = item.excerpt) === null || _item$excerpt === void 0 ? void 0 : _item$excerpt.rendered
      };
    });
  }, [items, itemType]);
  const itemInfosMap = (0,external_wp_element_namespaceObject.useMemo)(() => (0,external_lodash_namespaceObject.keyBy)(itemInfos, 'slug'), [itemInfos]);
  const itemsFiltered = (0,external_wp_element_namespaceObject.useMemo)(() => {
    if (items === null || search.length === 0) {
      return [];
    }

    if (disableFilter) {
      return items;
    }

    return items.filter(({
      slug
    }) => {
      const {
        title,
        description
      } = itemInfosMap[slug];
      return normalizedSearch(slug, search) || normalizedSearch(title, search) || normalizedSearch(description, search);
    });
  }, [items, itemInfos, search]);
  const itemsSorted = (0,external_wp_element_namespaceObject.useMemo)(() => {
    if (!itemsFiltered) {
      return [];
    }

    return (0,external_lodash_namespaceObject.sortBy)(itemsFiltered, [({
      slug
    }) => {
      const {
        title
      } = itemInfosMap[slug];
      return !normalizedSearch(title, search);
    }]);
  }, [itemsFiltered, search]);
  const ItemComponent = itemType === 'wp_template' || itemType === 'wp_template_part' ? TemplateNavigationItem : ContentNavigationItem;
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationGroup, {
    title: (0,external_wp_i18n_namespaceObject.__)('Search results')
  }, (0,external_lodash_namespaceObject.map)(itemsSorted, item => (0,external_wp_element_namespaceObject.createElement)(ItemComponent, {
    item: item,
    key: `${item.taxonomy || item.type}-${item.id}`
  })));
}
//# sourceMappingURL=search-results.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/use-debounced-search.js
/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */


function useDebouncedSearch() {
  // The value used by the NavigationMenu to control the input field.
  const [search, setSearch] = (0,external_wp_element_namespaceObject.useState)(''); // The value used to actually perform the search query.

  const [searchQuery, setSearchQuery] = (0,external_wp_element_namespaceObject.useState)('');
  const [isDebouncing, setIsDebouncing] = (0,external_wp_element_namespaceObject.useState)(false);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    setIsDebouncing(false);
  }, [searchQuery]);
  const debouncedSetSearchQuery = (0,external_wp_element_namespaceObject.useCallback)((0,external_lodash_namespaceObject.debounce)(setSearchQuery, SEARCH_DEBOUNCE_IN_MS), [setSearchQuery]);
  const onSearch = (0,external_wp_element_namespaceObject.useCallback)(value => {
    setSearch(value);
    debouncedSetSearchQuery(value);
    setIsDebouncing(true);
  }, [setSearch, setIsDebouncing, debouncedSetSearchQuery]);
  return {
    search,
    searchQuery,
    isDebouncing,
    onSearch
  };
}
//# sourceMappingURL=use-debounced-search.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/menus/content-pages.js


/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */





function ContentPagesMenu() {
  const {
    search,
    searchQuery,
    onSearch,
    isDebouncing
  } = useDebouncedSearch();
  const {
    pages,
    isResolved
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      getEntityRecords,
      hasFinishedResolution
    } = select(external_wp_coreData_namespaceObject.store);
    const query = searchQuery ? {
      search: searchQuery
    } : undefined;
    const getEntityRecordsArgs = ['postType', 'page', query];
    const hasResolvedPosts = hasFinishedResolution('getEntityRecords', getEntityRecordsArgs);
    return {
      pages: getEntityRecords(...getEntityRecordsArgs),
      isResolved: hasResolvedPosts
    };
  }, [searchQuery]);
  const shouldShowLoadingForDebouncing = search && isDebouncing;
  const showLoading = !isResolved || shouldShowLoadingForDebouncing;
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationMenu, {
    menu: MENU_CONTENT_PAGES,
    title: (0,external_wp_i18n_namespaceObject.__)('Pages'),
    parentMenu: MENU_ROOT,
    hasSearch: true,
    onSearch: onSearch,
    search: search,
    isSearchDebouncing: isDebouncing || !isResolved
  }, search && !isDebouncing && (0,external_wp_element_namespaceObject.createElement)(SearchResults, {
    items: pages,
    search: search,
    disableFilter: true
  }), !search && (pages === null || pages === void 0 ? void 0 : pages.map(page => (0,external_wp_element_namespaceObject.createElement)(ContentNavigationItem, {
    item: page,
    key: `${page.type}-${page.id}`
  }))), showLoading && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationItem, {
    title: (0,external_wp_i18n_namespaceObject.__)('Loading…'),
    isText: true
  }));
}
//# sourceMappingURL=content-pages.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/menus/content-categories.js


/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */





function ContentCategoriesMenu() {
  const {
    search,
    searchQuery,
    onSearch,
    isDebouncing
  } = useDebouncedSearch();
  const {
    categories,
    isResolved
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      getEntityRecords,
      hasFinishedResolution
    } = select(external_wp_coreData_namespaceObject.store);
    const query = searchQuery ? {
      search: searchQuery
    } : undefined;
    const getEntityRecordsArgs = ['taxonomy', 'category', query];
    const hasResolvedPosts = hasFinishedResolution('getEntityRecords', getEntityRecordsArgs);
    return {
      categories: getEntityRecords(...getEntityRecordsArgs),
      isResolved: hasResolvedPosts
    };
  }, [searchQuery]);
  const shouldShowLoadingForDebouncing = search && isDebouncing;
  const showLoading = !isResolved || shouldShowLoadingForDebouncing;
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationMenu, {
    menu: MENU_CONTENT_CATEGORIES,
    title: (0,external_wp_i18n_namespaceObject.__)('Categories'),
    parentMenu: MENU_ROOT,
    hasSearch: true,
    onSearch: onSearch,
    search: search,
    isSearchDebouncing: isDebouncing || !isResolved
  }, search && !isDebouncing && (0,external_wp_element_namespaceObject.createElement)(SearchResults, {
    items: categories,
    search: search,
    disableFilter: true
  }), !search && (categories === null || categories === void 0 ? void 0 : categories.map(category => (0,external_wp_element_namespaceObject.createElement)(ContentNavigationItem, {
    item: category,
    key: `${category.taxonomy}-${category.id}`
  }))), showLoading && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationItem, {
    title: (0,external_wp_i18n_namespaceObject.__)('Loading…'),
    isText: true
  }));
}
//# sourceMappingURL=content-categories.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/menus/content-posts.js


/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */






function ContentPostsMenu() {
  const {
    search,
    searchQuery,
    onSearch,
    isDebouncing
  } = useDebouncedSearch();
  const {
    posts,
    showOnFront,
    isResolved
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      getEntityRecords,
      getEditedEntityRecord,
      hasFinishedResolution
    } = select(external_wp_coreData_namespaceObject.store);
    const query = searchQuery ? {
      search: searchQuery
    } : undefined;
    const getEntityRecodsArgs = ['postType', 'post', query];
    const hasResolvedPosts = hasFinishedResolution('getEntityRecords', getEntityRecodsArgs);
    return {
      posts: getEntityRecords(...getEntityRecodsArgs),
      isResolved: hasResolvedPosts,
      showOnFront: getEditedEntityRecord('root', 'site').show_on_front
    };
  }, [searchQuery]);
  const {
    setPage,
    setIsNavigationPanelOpened
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);
  const onActivateFrontItem = (0,external_wp_element_namespaceObject.useCallback)(() => {
    setPage({
      type: 'page',
      path: '/',
      context: {
        queryContext: {
          page: 1
        }
      }
    });
    setIsNavigationPanelOpened(false);
  }, [setPage, setIsNavigationPanelOpened]);
  const shouldShowLoadingForDebouncing = search && isDebouncing;
  const showLoading = !isResolved || shouldShowLoadingForDebouncing;
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationMenu, {
    menu: MENU_CONTENT_POSTS,
    title: (0,external_wp_i18n_namespaceObject.__)('Posts'),
    parentMenu: MENU_ROOT,
    hasSearch: true,
    onSearch: onSearch,
    search: search,
    isSearchDebouncing: isDebouncing || !isResolved
  }, search && !isDebouncing && (0,external_wp_element_namespaceObject.createElement)(SearchResults, {
    items: posts,
    search: search,
    disableFilter: true
  }), !search && (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, showOnFront === 'posts' && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationItem, {
    item: 'post-/',
    title: (0,external_wp_i18n_namespaceObject.__)('All Posts'),
    onClick: onActivateFrontItem
  }), posts === null || posts === void 0 ? void 0 : posts.map(post => (0,external_wp_element_namespaceObject.createElement)(ContentNavigationItem, {
    item: post,
    key: `${post.type}-${post.id}`
  }))), showLoading && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationItem, {
    title: (0,external_wp_i18n_namespaceObject.__)('Loading…'),
    isText: true
  }));
}
//# sourceMappingURL=content-posts.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/utils/get-closest-available-template.js
/**
 * External dependencies
 */

function getClosestAvailableTemplate(slug, templates) {
  const template = (0,external_lodash_namespaceObject.find)(templates, {
    slug
  });

  if (template) {
    return template;
  }

  switch (slug) {
    case 'single':
    case 'page':
      return getClosestAvailableTemplate('singular', templates);

    case 'author':
    case 'category':
    case 'taxonomy':
    case 'date':
    case 'tag':
      return getClosestAvailableTemplate('archive', templates);

    case 'front-page':
      return getClosestAvailableTemplate('home', templates);

    case 'attachment':
      return getClosestAvailableTemplate('single', templates);

    case 'privacy-policy':
      return getClosestAvailableTemplate('page', templates);
  }

  return (0,external_lodash_namespaceObject.find)(templates, {
    slug: 'index'
  });
}
//# sourceMappingURL=get-closest-available-template.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/new-template-dropdown.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */







/**
 * Internal dependencies
 */




function NewTemplateDropdown() {
  const {
    defaultTemplateTypes,
    templates
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      __experimentalGetDefaultTemplateTypes: getDefaultTemplateTypes
    } = select(external_wp_editor_namespaceObject.store);
    const templateEntities = select(external_wp_coreData_namespaceObject.store).getEntityRecords('postType', 'wp_template');
    return {
      defaultTemplateTypes: getDefaultTemplateTypes(),
      templates: templateEntities
    };
  }, []);
  const {
    addTemplate
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);

  const createTemplate = slug => {
    const closestAvailableTemplate = getClosestAvailableTemplate(slug, templates);
    const {
      title,
      description
    } = (0,external_lodash_namespaceObject.find)(defaultTemplateTypes, {
      slug
    });
    addTemplate({
      content: closestAvailableTemplate.content.raw,
      excerpt: description,
      // Slugs need to be strings, so this is for template `404`
      slug: slug.toString(),
      status: 'publish',
      title
    });
  };

  const existingTemplateSlugs = (0,external_lodash_namespaceObject.map)(templates, 'slug');
  const missingTemplates = (0,external_lodash_namespaceObject.filter)(defaultTemplateTypes, template => (0,external_lodash_namespaceObject.includes)(TEMPLATES_NEW_OPTIONS, template.slug) && !(0,external_lodash_namespaceObject.includes)(existingTemplateSlugs, template.slug));

  if (!missingTemplates.length) {
    return null;
  }

  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.DropdownMenu, {
    className: "edit-site-navigation-panel__new-template-dropdown",
    icon: null,
    label: (0,external_wp_i18n_namespaceObject.__)('Add Template'),
    popoverProps: {
      noArrow: false
    },
    toggleProps: {
      children: (0,external_wp_element_namespaceObject.createElement)(build_module_icon, {
        icon: library_plus
      }),
      isSmall: true,
      variant: 'tertiary'
    }
  }, ({
    onClose
  }) => (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.NavigableMenu, {
    className: "edit-site-navigation-panel__new-template-popover"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuGroup, {
    label: (0,external_wp_i18n_namespaceObject.__)('Add Template')
  }, (0,external_lodash_namespaceObject.map)(missingTemplates, ({
    title,
    description,
    slug
  }) => (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuItem, {
    info: description,
    key: slug,
    onClick: () => {
      createTemplate(slug);
      onClose();
    }
  }, title)))));
}
//# sourceMappingURL=new-template-dropdown.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/menus/templates-sub.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */



function TemplatesSubMenu({
  menu,
  title,
  templates
}) {
  const templatesFiltered = (0,external_wp_element_namespaceObject.useMemo)(() => {
    var _templates$filter$map, _templates$filter;

    return (_templates$filter$map = templates === null || templates === void 0 ? void 0 : (_templates$filter = templates.filter(({
      location
    }) => location === menu)) === null || _templates$filter === void 0 ? void 0 : _templates$filter.map(({
      template
    }) => template)) !== null && _templates$filter$map !== void 0 ? _templates$filter$map : [];
  }, [menu, templates]);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationMenu, {
    menu: menu,
    title: title,
    parentMenu: MENU_TEMPLATES,
    isEmpty: templatesFiltered.length === 0
  }, (0,external_lodash_namespaceObject.map)(templatesFiltered, template => (0,external_wp_element_namespaceObject.createElement)(TemplateNavigationItem, {
    item: template,
    key: `wp_template-${template.id}`
  })));
}
//# sourceMappingURL=templates-sub.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/menus/templates.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */






/**
 * Internal dependencies
 */







function TemplatesMenu() {
  const [search, setSearch] = (0,external_wp_element_namespaceObject.useState)('');
  const onSearch = (0,external_wp_element_namespaceObject.useCallback)(value => {
    setSearch(value);
  });
  const {
    templates,
    showOnFront
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      getEntityRecords,
      getEditedEntityRecord
    } = select(external_wp_coreData_namespaceObject.store);
    return {
      templates: getEntityRecords('postType', 'wp_template'),
      showOnFront: getEditedEntityRecord('root', 'site').show_on_front
    };
  }, []);
  const templatesWithLocation = (0,external_wp_element_namespaceObject.useMemo)(() => {
    if (!templates) {
      return null;
    }

    const unusedTemplates = getUnusedTemplates(templates, showOnFront);
    const templateLocations = getTemplatesLocationMap(templates);
    return templates.map(template => ({
      template,
      location: (0,external_lodash_namespaceObject.find)(unusedTemplates, {
        slug: template.slug
      }) ? MENU_TEMPLATES_UNUSED : templateLocations[template.slug]
    }));
  }, [templates]);
  const topLevelTemplates = (0,external_wp_element_namespaceObject.useMemo)(() => {
    var _templatesWithLocatio, _templatesWithLocatio2;

    return (_templatesWithLocatio = templatesWithLocation === null || templatesWithLocation === void 0 ? void 0 : (_templatesWithLocatio2 = templatesWithLocation.filter(({
      location
    }) => location === MENU_TEMPLATES)) === null || _templatesWithLocatio2 === void 0 ? void 0 : _templatesWithLocatio2.map(({
      template
    }) => template)) !== null && _templatesWithLocatio !== void 0 ? _templatesWithLocatio : [];
  }, [templatesWithLocation]);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationMenu, {
    menu: MENU_TEMPLATES,
    title: (0,external_wp_i18n_namespaceObject.__)('Templates'),
    titleAction: (0,external_wp_element_namespaceObject.createElement)(NewTemplateDropdown, null),
    parentMenu: MENU_ROOT,
    hasSearch: true,
    onSearch: onSearch,
    search: search
  }, search && (0,external_wp_element_namespaceObject.createElement)(SearchResults, {
    items: templates,
    search: search
  }), !search && (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_lodash_namespaceObject.map)(topLevelTemplates, template => (0,external_wp_element_namespaceObject.createElement)(TemplateNavigationItem, {
    item: template,
    key: `wp_template-${template.id}`
  })), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationItem, {
    navigateToMenu: MENU_TEMPLATES_POSTS,
    title: (0,external_wp_i18n_namespaceObject.__)('Post templates'),
    hideIfTargetMenuEmpty: true
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationItem, {
    navigateToMenu: MENU_TEMPLATES_PAGES,
    title: (0,external_wp_i18n_namespaceObject.__)('Page templates'),
    hideIfTargetMenuEmpty: true
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationItem, {
    navigateToMenu: MENU_TEMPLATES_GENERAL,
    title: (0,external_wp_i18n_namespaceObject.__)('General templates'),
    hideIfTargetMenuEmpty: true
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationItem, {
    navigateToMenu: MENU_TEMPLATES_UNUSED,
    title: (0,external_wp_i18n_namespaceObject.__)('Unused templates'),
    hideIfTargetMenuEmpty: true
  })), !search && templates === null && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationItem, {
    title: (0,external_wp_i18n_namespaceObject.__)('Loading…'),
    isText: true
  }), (0,external_wp_element_namespaceObject.createElement)(TemplatesSubMenu, {
    menu: MENU_TEMPLATES_POSTS,
    title: (0,external_wp_i18n_namespaceObject.__)('Post templates'),
    templates: templatesWithLocation
  }), (0,external_wp_element_namespaceObject.createElement)(TemplatesSubMenu, {
    menu: MENU_TEMPLATES_PAGES,
    title: (0,external_wp_i18n_namespaceObject.__)('Page templates'),
    templates: templatesWithLocation
  }), (0,external_wp_element_namespaceObject.createElement)(TemplatesSubMenu, {
    menu: MENU_TEMPLATES_GENERAL,
    title: (0,external_wp_i18n_namespaceObject.__)('General templates'),
    templates: templatesWithLocation
  }), (0,external_wp_element_namespaceObject.createElement)(TemplatesSubMenu, {
    menu: MENU_TEMPLATES_UNUSED,
    title: (0,external_wp_i18n_namespaceObject.__)('Unused templates'),
    templates: templatesWithLocation
  }));
}
//# sourceMappingURL=templates.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/menus/template-parts-sub.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */



function TemplatePartsSubMenu({
  menu,
  title,
  templateParts
}) {
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationMenu, {
    className: "edit-site-navigation-panel__template-parts",
    menu: menu,
    title: title,
    parentMenu: MENU_TEMPLATE_PARTS,
    isEmpty: !templateParts || templateParts.length === 0
  }, (0,external_lodash_namespaceObject.map)(templateParts, templatePart => (0,external_wp_element_namespaceObject.createElement)(TemplateNavigationItem, {
    item: templatePart,
    key: `wp_template_part-${templatePart.id}`
  })));
}
//# sourceMappingURL=template-parts-sub.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/menus/template-parts.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */






/**
 * Internal dependencies
 */




function TemplatePartsMenu() {
  const [search, setSearch] = (0,external_wp_element_namespaceObject.useState)('');
  const onSearch = (0,external_wp_element_namespaceObject.useCallback)(value => {
    setSearch(value);
  });
  const {
    isLoading,
    templateParts,
    templatePartsByArea
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const templatePartRecords = select(external_wp_coreData_namespaceObject.store).getEntityRecords('postType', 'wp_template_part');

    const _templateParts = templatePartRecords || [];

    const _templatePartsByArea = (0,external_lodash_namespaceObject.groupBy)(_templateParts, 'area');

    return {
      isLoading: templatePartRecords === null,
      templateParts: _templateParts,
      templatePartsByArea: _templatePartsByArea
    };
  }, []);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationMenu, {
    menu: MENU_TEMPLATE_PARTS,
    title: (0,external_wp_i18n_namespaceObject.__)('Template Parts'),
    parentMenu: MENU_ROOT,
    hasSearch: true,
    onSearch: onSearch,
    search: search
  }, search && (0,external_wp_element_namespaceObject.createElement)(SearchResults, {
    items: templateParts,
    search: search
  }), !search && TEMPLATE_PARTS_SUB_MENUS.map(({
    title,
    menu
  }) => (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationItem, {
    key: `template-parts-navigate-to-${menu}`,
    className: "edit-site-navigation-panel__template-part-item",
    navigateToMenu: menu,
    title: title,
    hideIfTargetMenuEmpty: true
  })), !search && isLoading && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationItem, {
    title: (0,external_wp_i18n_namespaceObject.__)('Loading…'),
    isText: true
  })), TEMPLATE_PARTS_SUB_MENUS.map(({
    area,
    menu,
    title
  }) => (0,external_wp_element_namespaceObject.createElement)(TemplatePartsSubMenu, {
    key: `template-parts-menu-${menu}`,
    menu: menu,
    title: title,
    templateParts: templatePartsByArea[area]
  })));
}
//# sourceMappingURL=template-parts.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/menus/index.js


/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */







function SiteMenu() {
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationMenu, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationGroup, {
    title: (0,external_wp_i18n_namespaceObject.__)('Theme')
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationItem, {
    title: (0,external_wp_i18n_namespaceObject.__)('Templates'),
    navigateToMenu: MENU_TEMPLATES
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationItem, {
    title: (0,external_wp_i18n_namespaceObject.__)('Template Parts'),
    navigateToMenu: MENU_TEMPLATE_PARTS
  })), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationGroup, {
    title: (0,external_wp_i18n_namespaceObject.__)('Content')
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationItem, {
    title: (0,external_wp_i18n_namespaceObject.__)('Pages'),
    navigateToMenu: MENU_CONTENT_PAGES
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationItem, {
    title: (0,external_wp_i18n_namespaceObject.__)('Categories'),
    navigateToMenu: MENU_CONTENT_CATEGORIES
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationItem, {
    title: (0,external_wp_i18n_namespaceObject.__)('Posts'),
    navigateToMenu: MENU_CONTENT_POSTS
  })), (0,external_wp_element_namespaceObject.createElement)(TemplatesMenu, null), (0,external_wp_element_namespaceObject.createElement)(TemplatePartsMenu, null), (0,external_wp_element_namespaceObject.createElement)(ContentPagesMenu, null), (0,external_wp_element_namespaceObject.createElement)(ContentCategoriesMenu, null), (0,external_wp_element_namespaceObject.createElement)(ContentPostsMenu, null));
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/index.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */








/**
 * Internal dependencies
 */






const NavigationPanel = ({
  isOpen
}) => {
  const {
    page: {
      context: {
        postType,
        postId
      } = {}
    } = {},
    editedPostId,
    editedPostType,
    activeMenu,
    siteTitle
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      getEditedPostType,
      getEditedPostId,
      getNavigationPanelActiveMenu,
      getPage
    } = select(store_store);
    const {
      getEntityRecord
    } = select(external_wp_coreData_namespaceObject.store);
    const siteData = getEntityRecord('root', '__unstableBase', undefined) || {};
    return {
      page: getPage(),
      editedPostId: getEditedPostId(),
      editedPostType: getEditedPostType(),
      activeMenu: getNavigationPanelActiveMenu(),
      siteTitle: siteData.name
    };
  }, []);
  const {
    setNavigationPanelActiveMenu: setActive,
    setIsNavigationPanelOpened
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);
  let activeItem;

  if (activeMenu !== MENU_ROOT) {
    if (activeMenu.startsWith('content')) {
      activeItem = `${postType}-${postId}`;
    } else {
      activeItem = `${editedPostType}-${editedPostId}`;
    }
  } // Ensures focus is moved to the panel area when it is activated
  // from a separate component (such as document actions in the header).


  const panelRef = (0,external_wp_element_namespaceObject.useRef)();
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    if (isOpen) {
      panelRef.current.focus();
    }
  }, [activeMenu, isOpen]);

  const closeOnEscape = event => {
    if (event.keyCode === external_wp_keycodes_namespaceObject.ESCAPE && !event.defaultPrevented) {
      event.preventDefault();
      setIsNavigationPanelOpened(false);
    }
  };

  return (// eslint-disable-next-line jsx-a11y/no-static-element-interactions
    (0,external_wp_element_namespaceObject.createElement)("div", {
      className: classnames_default()(`edit-site-navigation-panel`, {
        'is-open': isOpen
      }),
      ref: panelRef,
      tabIndex: "-1",
      onKeyDown: closeOnEscape
    }, (0,external_wp_element_namespaceObject.createElement)("div", {
      className: "edit-site-navigation-panel__inner"
    }, (0,external_wp_element_namespaceObject.createElement)("div", {
      className: "edit-site-navigation-panel__site-title-container"
    }, (0,external_wp_element_namespaceObject.createElement)("div", {
      className: "edit-site-navigation-panel__site-title"
    }, (0,external_wp_htmlEntities_namespaceObject.decodeEntities)(siteTitle))), (0,external_wp_element_namespaceObject.createElement)("div", {
      className: "edit-site-navigation-panel__scroll-container"
    }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigation, {
      activeItem: activeItem,
      activeMenu: activeMenu,
      onActivateMenu: setActive
    }, activeMenu === MENU_ROOT && (0,external_wp_element_namespaceObject.createElement)(main_dashboard_button.Slot, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalNavigationBackButton, {
      backButtonLabel: (0,external_wp_i18n_namespaceObject.__)('Dashboard'),
      className: "edit-site-navigation-panel__back-to-dashboard",
      href: "index.php"
    })), (0,external_wp_element_namespaceObject.createElement)(SiteMenu, null)))))
  );
};

/* harmony default export */ var navigation_panel = (NavigationPanel);
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-toggle/index.js


/**
 * WordPress dependencies
 */






/**
 * Internal dependencies
 */



function NavigationToggle({
  icon,
  isOpen
}) {
  const {
    isRequestingSiteIcon,
    navigationPanelMenu,
    siteIconUrl
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      getCurrentTemplateNavigationPanelSubMenu
    } = select(store_store);
    const {
      getEntityRecord,
      isResolving
    } = select(external_wp_coreData_namespaceObject.store);
    const siteData = getEntityRecord('root', '__unstableBase', undefined) || {};
    return {
      isRequestingSiteIcon: isResolving('core', 'getEntityRecord', ['root', '__unstableBase', undefined]),
      navigationPanelMenu: getCurrentTemplateNavigationPanelSubMenu(),
      siteIconUrl: siteData.site_icon_url
    };
  }, []);
  const {
    openNavigationPanelToMenu,
    setIsNavigationPanelOpened
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);
  const disableMotion = (0,external_wp_compose_namespaceObject.useReducedMotion)();

  const toggleNavigationPanel = () => {
    if (isOpen) {
      setIsNavigationPanelOpened(false);
      return;
    }

    openNavigationPanelToMenu(navigationPanelMenu);
  };

  let buttonIcon = (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Icon, {
    size: "36px",
    icon: library_wordpress
  });
  const effect = {
    expand: {
      scale: 1.7,
      borderRadius: 0,
      transition: {
        type: 'tween',
        duration: '0.2'
      }
    }
  };

  if (siteIconUrl) {
    buttonIcon = (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__unstableMotion.img, {
      variants: !disableMotion && effect,
      alt: (0,external_wp_i18n_namespaceObject.__)('Site Icon'),
      className: "edit-site-navigation-toggle__site-icon",
      src: siteIconUrl
    });
  } else if (isRequestingSiteIcon) {
    buttonIcon = null;
  } else if (icon) {
    buttonIcon = (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Icon, {
      size: "36px",
      icon: icon
    });
  }

  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__unstableMotion.div, {
    className: 'edit-site-navigation-toggle' + (isOpen ? ' is-open' : ''),
    whileHover: "expand"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    className: "edit-site-navigation-toggle__button has-icon",
    label: (0,external_wp_i18n_namespaceObject.__)('Toggle navigation'),
    onClick: toggleNavigationPanel,
    showTooltip: true
  }, buttonIcon));
}

/* harmony default export */ var navigation_toggle = (NavigationToggle);
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/index.js


/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */




const {
  Fill: NavigationPanelPreviewFill,
  Slot: NavigationPanelPreviewSlot
} = (0,external_wp_components_namespaceObject.createSlotFill)('EditSiteNavigationPanelPreview');
function NavigationSidebar() {
  const isNavigationOpen = (0,external_wp_data_namespaceObject.useSelect)(select => {
    return select(store_store).isNavigationOpened();
  }, []);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(navigation_toggle, {
    isOpen: isNavigationOpen
  }), (0,external_wp_element_namespaceObject.createElement)(navigation_panel, {
    isOpen: isNavigationOpen
  }), (0,external_wp_element_namespaceObject.createElement)(NavigationPanelPreviewSlot, null));
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/url-query-controller/index.js
/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */


function URLQueryController() {
  const {
    setTemplate,
    setTemplatePart,
    showHomepage,
    setPage
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store); // Set correct entity on load.

  (0,external_wp_element_namespaceObject.useEffect)(() => {
    const url = window.location.href;
    const postId = (0,external_wp_url_namespaceObject.getQueryArg)(url, 'postId');

    if (!postId) {
      showHomepage();
      return;
    }

    const postType = (0,external_wp_url_namespaceObject.getQueryArg)(url, 'postType');

    if ('page' === postType || 'post' === postType) {
      setPage({
        context: {
          postType,
          postId
        }
      }); // Resolves correct template based on ID.
    } else if ('wp_template' === postType) {
      setTemplate(postId);
    } else if ('wp_template_part' === postType) {
      setTemplatePart(postId);
    } else {
      showHomepage();
    }
  }, []); // Update page URL when context changes.

  const pageContext = useCurrentPageContext();
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    const newUrl = pageContext ? (0,external_wp_url_namespaceObject.addQueryArgs)(window.location.href, pageContext) : (0,external_wp_url_namespaceObject.removeQueryArgs)(window.location.href, 'postType', 'postId');
    window.history.replaceState({}, '', newUrl);
  }, [pageContext]);
  return null;
}

function useCurrentPageContext() {
  return (0,external_wp_data_namespaceObject.useSelect)(select => {
    var _page$context, _page$context2;

    const {
      getEditedPostType,
      getEditedPostId,
      getPage
    } = select(store_store);
    const page = getPage();

    let _postId = getEditedPostId(),
        _postType = getEditedPostType(); // This doesn't seem right to me,
    // we shouldn't be using the "page" and the "template" in the same way.
    // This need to be investigated.


    if (page !== null && page !== void 0 && (_page$context = page.context) !== null && _page$context !== void 0 && _page$context.postId && page !== null && page !== void 0 && (_page$context2 = page.context) !== null && _page$context2 !== void 0 && _page$context2.postType) {
      _postId = page.context.postId;
      _postType = page.context.postType;
    }

    if (_postId && _postType) {
      return {
        postId: _postId,
        postType: _postType
      };
    }

    return null;
  }, []);
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/icons/build-module/library/close.js


/**
 * WordPress dependencies
 */

const close_close = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M13 11.8l6.1-6.3-1-1-6.1 6.2-6.1-6.2-1 1 6.1 6.3-6.5 6.7 1 1 6.5-6.6 6.5 6.6 1-1z"
}));
/* harmony default export */ var library_close = (close_close);
//# sourceMappingURL=close.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/secondary-sidebar/inserter-sidebar.js



/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */


function InserterSidebar() {
  const {
    setIsInserterOpened
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);
  const insertionPoint = (0,external_wp_data_namespaceObject.useSelect)(select => select(store_store).__experimentalGetInsertionPoint(), []);
  const isMobile = (0,external_wp_compose_namespaceObject.useViewportMatch)('medium', '<');
  const [inserterDialogRef, inserterDialogProps] = (0,external_wp_compose_namespaceObject.__experimentalUseDialog)({
    onClose: () => setIsInserterOpened(false)
  });
  return (0,external_wp_element_namespaceObject.createElement)("div", _extends({
    ref: inserterDialogRef
  }, inserterDialogProps, {
    className: "edit-site-editor__inserter-panel"
  }), (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-site-editor__inserter-panel-header"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    icon: library_close,
    onClick: () => setIsInserterOpened(false)
  })), (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-site-editor__inserter-panel-content"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.__experimentalLibrary, {
    showInserterHelpPanel: true,
    shouldFocusBlock: isMobile,
    rootClientId: insertionPoint.rootClientId,
    __experimentalInsertionIndex: insertionPoint.insertionIndex,
    __experimentalFilterValue: insertionPoint.filterValue
  })));
}
//# sourceMappingURL=inserter-sidebar.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/secondary-sidebar/list-view-sidebar.js


/**
 * WordPress dependencies
 */







/**
 * Internal dependencies
 */


function ListViewSidebar() {
  const {
    setIsListViewOpened
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);
  const {
    clearSelectedBlock,
    selectBlock
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_blockEditor_namespaceObject.store);

  async function selectEditorBlock(clientId) {
    await clearSelectedBlock();
    selectBlock(clientId, -1);
  }

  const focusOnMountRef = (0,external_wp_compose_namespaceObject.useFocusOnMount)('firstElement');
  const focusReturnRef = (0,external_wp_compose_namespaceObject.useFocusReturn)();

  function closeOnEscape(event) {
    if (event.keyCode === external_wp_keycodes_namespaceObject.ESCAPE && !event.defaultPrevented) {
      setIsListViewOpened(false);
    }
  }

  const instanceId = (0,external_wp_compose_namespaceObject.useInstanceId)(ListViewSidebar);
  const labelId = `edit-site-editor__list-view-panel-label-${instanceId}`;
  return (// eslint-disable-next-line jsx-a11y/no-static-element-interactions
    (0,external_wp_element_namespaceObject.createElement)("div", {
      "aria-labelledby": labelId,
      className: "edit-site-editor__list-view-panel",
      onKeyDown: closeOnEscape
    }, (0,external_wp_element_namespaceObject.createElement)("div", {
      className: "edit-site-editor__list-view-panel-header"
    }, (0,external_wp_element_namespaceObject.createElement)("strong", {
      id: labelId
    }, (0,external_wp_i18n_namespaceObject.__)('List view')), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
      icon: close_small,
      label: (0,external_wp_i18n_namespaceObject.__)('Close list view sidebar'),
      onClick: () => setIsListViewOpened(false)
    })), (0,external_wp_element_namespaceObject.createElement)("div", {
      className: "edit-site-editor__list-view-panel-content",
      ref: (0,external_wp_compose_namespaceObject.useMergeRefs)([focusReturnRef, focusOnMountRef])
    }, (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.__experimentalListView, {
      onSelect: selectEditorBlock,
      showNestedBlocks: true,
      __experimentalFeatures: true,
      __experimentalPersistentListViewFeatures: true
    })))
  );
}
//# sourceMappingURL=list-view-sidebar.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/error-boundary/index.js


/**
 * WordPress dependencies
 */






function CopyButton({
  text,
  children
}) {
  const ref = (0,external_wp_compose_namespaceObject.useCopyToClipboard)(text);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    variant: "secondary",
    ref: ref
  }, children);
}

class ErrorBoundary extends external_wp_element_namespaceObject.Component {
  constructor() {
    super(...arguments);
    this.reboot = this.reboot.bind(this);
    this.state = {
      error: null
    };
  }

  static getDerivedStateFromError(error) {
    return {
      error
    };
  }

  reboot() {
    this.props.onError();
  }

  render() {
    const {
      error
    } = this.state;

    if (!error) {
      return this.props.children;
    }

    return (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.Warning, {
      className: "editor-error-boundary",
      actions: [(0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
        key: "recovery",
        onClick: this.reboot,
        variant: "secondary"
      }, (0,external_wp_i18n_namespaceObject.__)('Attempt Recovery')), (0,external_wp_element_namespaceObject.createElement)(CopyButton, {
        key: "copy-error",
        text: error.stack
      }, (0,external_wp_i18n_namespaceObject.__)('Copy Error'))]
    }, (0,external_wp_i18n_namespaceObject.__)('The editor has encountered an unexpected error.'));
  }

}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/editor/global-styles-renderer.js
/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */


/**
 * Internal dependencies
 */



function useGlobalStylesRenderer() {
  const [styles, settings] = useGlobalStylesOutput();
  const {
    getSettings
  } = (0,external_wp_data_namespaceObject.useSelect)(store_store);
  const {
    updateSettings
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    var _currentStoreSettings;

    if (!styles || !settings) {
      return;
    }

    const currentStoreSettings = getSettings();
    const nonGlobalStyles = currentStoreSettings === null || currentStoreSettings === void 0 ? void 0 : (_currentStoreSettings = currentStoreSettings.styles) === null || _currentStoreSettings === void 0 ? void 0 : _currentStoreSettings.filter(style => !style.isGlobalStyles);
    updateSettings({ ...currentStoreSettings,
      styles: [...nonGlobalStyles, ...styles],
      __experimentalFeatures: settings
    });
  }, [styles, settings]);
}

function GlobalStylesRenderer() {
  useGlobalStylesRenderer();
  return null;
}
//# sourceMappingURL=global-styles-renderer.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/global-styles/global-styles-provider.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */




function mergeTreesCustomizer(_, srcValue) {
  // We only pass as arrays the presets,
  // in which case we want the new array of values
  // to override the old array (no merging).
  if (Array.isArray(srcValue)) {
    return srcValue;
  }
}

function mergeBaseAndUserConfigs(base, user) {
  return (0,external_lodash_namespaceObject.mergeWith)({}, base, user, mergeTreesCustomizer);
}

function addUserOriginToSettings(settingsToAdd) {
  const newSettings = (0,external_lodash_namespaceObject.cloneDeep)(settingsToAdd);
  PRESET_METADATA.forEach(({
    path
  }) => {
    const presetData = (0,external_lodash_namespaceObject.get)(newSettings, path);

    if (presetData) {
      (0,external_lodash_namespaceObject.set)(newSettings, path, {
        user: presetData
      });
    }
  });
  return newSettings;
}

function removeUserOriginFromSettings(settingsToRemove) {
  const newSettings = (0,external_lodash_namespaceObject.cloneDeep)(settingsToRemove);
  PRESET_METADATA.forEach(({
    path
  }) => {
    const presetData = (0,external_lodash_namespaceObject.get)(newSettings, path);

    if (presetData) {
      (0,external_lodash_namespaceObject.set)(newSettings, path, (presetData !== null && presetData !== void 0 ? presetData : {}).user);
    }
  });
  return newSettings;
}

const cleanEmptyObject = object => {
  if (!(0,external_lodash_namespaceObject.isObject)(object) || Array.isArray(object)) {
    return object;
  }

  const cleanedNestedObjects = (0,external_lodash_namespaceObject.pickBy)((0,external_lodash_namespaceObject.mapValues)(object, cleanEmptyObject), external_lodash_namespaceObject.identity);
  return (0,external_lodash_namespaceObject.isEmpty)(cleanedNestedObjects) ? undefined : cleanedNestedObjects;
};

function useGlobalStylesUserConfig() {
  const {
    globalStylesId,
    settings,
    styles
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const _globalStylesId = select(external_wp_coreData_namespaceObject.store).__experimentalGetCurrentGlobalStylesId();

    const record = _globalStylesId ? select(external_wp_coreData_namespaceObject.store).getEditedEntityRecord('root', 'globalStyles', _globalStylesId) : undefined;
    return {
      globalStylesId: _globalStylesId,
      settings: record === null || record === void 0 ? void 0 : record.settings,
      styles: record === null || record === void 0 ? void 0 : record.styles
    };
  }, []);
  const {
    getEditedEntityRecord
  } = (0,external_wp_data_namespaceObject.useSelect)(external_wp_coreData_namespaceObject.store);
  const {
    editEntityRecord
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_coreData_namespaceObject.store);
  const config = (0,external_wp_element_namespaceObject.useMemo)(() => {
    return {
      settings: addUserOriginToSettings(settings !== null && settings !== void 0 ? settings : {}),
      styles: styles !== null && styles !== void 0 ? styles : {}
    };
  }, [settings, styles]);
  const setConfig = (0,external_wp_element_namespaceObject.useCallback)(callback => {
    var _record$styles, _record$settings;

    const record = getEditedEntityRecord('root', 'globalStyles', globalStylesId);
    const currentConfig = {
      styles: (_record$styles = record === null || record === void 0 ? void 0 : record.styles) !== null && _record$styles !== void 0 ? _record$styles : {},
      settings: addUserOriginToSettings((_record$settings = record === null || record === void 0 ? void 0 : record.settings) !== null && _record$settings !== void 0 ? _record$settings : {})
    };
    const updatedConfig = callback(currentConfig);
    editEntityRecord('root', 'globalStyles', globalStylesId, {
      styles: cleanEmptyObject(updatedConfig.styles) || {},
      settings: cleanEmptyObject(removeUserOriginFromSettings(updatedConfig.settings)) || {}
    });
  }, [globalStylesId]);
  return [!!settings || !!styles, config, setConfig];
}

function useGlobalStylesBaseConfig() {
  const baseConfig = (0,external_wp_data_namespaceObject.useSelect)(select => {
    return select(external_wp_coreData_namespaceObject.store).__experimentalGetCurrentThemeBaseGlobalStyles();
  }, []);
  return baseConfig;
}

function useGlobalStylesContext() {
  const [isUserConfigReady, userConfig, setUserConfig] = useGlobalStylesUserConfig();
  const baseConfig = useGlobalStylesBaseConfig();
  const mergedConfig = (0,external_wp_element_namespaceObject.useMemo)(() => {
    if (!baseConfig || !userConfig) {
      return {};
    }

    return mergeBaseAndUserConfigs(baseConfig, userConfig);
  }, [userConfig, baseConfig]);
  const context = (0,external_wp_element_namespaceObject.useMemo)(() => {
    return {
      isReady: isUserConfigReady,
      user: userConfig,
      base: baseConfig,
      merged: mergedConfig,
      setUserConfig
    };
  }, [mergedConfig, userConfig, baseConfig, setUserConfig, isUserConfigReady]);
  return context;
}

function GlobalStylesProvider({
  children
}) {
  const context = useGlobalStylesContext();

  if (!context.isReady) {
    return null;
  }

  return (0,external_wp_element_namespaceObject.createElement)(GlobalStylesContext.Provider, {
    value: context
  }, children);
}
//# sourceMappingURL=global-styles-provider.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/editor/index.js


/**
 * WordPress dependencies
 */










/**
 * Internal dependencies
 */













const interfaceLabels = {
  secondarySidebar: (0,external_wp_i18n_namespaceObject.__)('Block Library'),
  drawer: (0,external_wp_i18n_namespaceObject.__)('Navigation Sidebar')
};

function Editor({
  initialSettings,
  onError
}) {
  const {
    isInserterOpen,
    isListViewOpen,
    sidebarIsOpened,
    settings,
    entityId,
    templateType,
    page,
    template,
    templateResolved,
    isNavigationOpen
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      isInserterOpened,
      isListViewOpened,
      getSettings,
      getEditedPostType,
      getEditedPostId,
      getPage,
      isNavigationOpened
    } = select(store_store);
    const {
      hasFinishedResolution,
      getEntityRecord
    } = select(external_wp_coreData_namespaceObject.store);
    const postType = getEditedPostType();
    const postId = getEditedPostId(); // The currently selected entity to display. Typically template or template part.

    return {
      isInserterOpen: isInserterOpened(),
      isListViewOpen: isListViewOpened(),
      sidebarIsOpened: !!select(store).getActiveComplementaryArea(store_store.name),
      settings: getSettings(),
      templateType: postType,
      page: getPage(),
      template: postId ? getEntityRecord('postType', postType, postId) : null,
      templateResolved: postId ? hasFinishedResolution('getEntityRecord', ['postType', postType, postId]) : false,
      entityId: postId,
      isNavigationOpen: isNavigationOpened()
    };
  }, []);
  const {
    updateEditorSettings
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_editor_namespaceObject.store);
  const {
    setPage,
    setIsInserterOpened,
    updateSettings
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);
  const {
    enableComplementaryArea
  } = (0,external_wp_data_namespaceObject.useDispatch)(store);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    updateSettings(initialSettings);
  }, []); // Keep the defaultTemplateTypes in the core/editor settings too,
  // so that they can be selected with core/editor selectors in any editor.
  // This is needed because edit-site doesn't initialize with EditorProvider,
  // which internally uses updateEditorSettings as well.

  const {
    defaultTemplateTypes,
    defaultTemplatePartAreas,
    __experimentalNewMenuSidebar: newMenuSidebar
  } = settings;
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    updateEditorSettings({
      defaultTemplateTypes,
      defaultTemplatePartAreas
    });
  }, [defaultTemplateTypes, defaultTemplatePartAreas]);
  const [isEntitiesSavedStatesOpen, setIsEntitiesSavedStatesOpen] = (0,external_wp_element_namespaceObject.useState)(false);
  const openEntitiesSavedStates = (0,external_wp_element_namespaceObject.useCallback)(() => setIsEntitiesSavedStatesOpen(true), []);
  const closeEntitiesSavedStates = (0,external_wp_element_namespaceObject.useCallback)(() => {
    setIsEntitiesSavedStatesOpen(false);
  }, []);
  const blockContext = (0,external_wp_element_namespaceObject.useMemo)(() => ({ ...(page === null || page === void 0 ? void 0 : page.context),
    queryContext: [(page === null || page === void 0 ? void 0 : page.context.queryContext) || {
      page: 1
    }, newQueryContext => setPage({ ...page,
      context: { ...(page === null || page === void 0 ? void 0 : page.context),
        queryContext: { ...(page === null || page === void 0 ? void 0 : page.context.queryContext),
          ...newQueryContext
        }
      }
    })]
  }), [page === null || page === void 0 ? void 0 : page.context]);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    if (isNavigationOpen) {
      document.body.classList.add('is-navigation-sidebar-open');
    } else {
      document.body.classList.remove('is-navigation-sidebar-open');
    }
  }, [isNavigationOpen]);
  (0,external_wp_element_namespaceObject.useEffect)(function openGlobalStylesOnLoad() {
    const searchParams = new URLSearchParams(window.location.search);

    if (searchParams.get('styles') === 'open') {
      enableComplementaryArea('core/edit-site', 'edit-site/global-styles');
    }
  }, [enableComplementaryArea]); // Don't render the Editor until the settings are set and loaded

  const isReady = (settings === null || settings === void 0 ? void 0 : settings.siteUrl) && templateType !== undefined && entityId !== undefined;

  const secondarySidebar = () => {
    if (isInserterOpen) {
      return (0,external_wp_element_namespaceObject.createElement)(InserterSidebar, null);
    }

    if (isListViewOpen) {
      return (0,external_wp_element_namespaceObject.createElement)(ListViewSidebar, null);
    }

    return null;
  };

  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(URLQueryController, null), isReady && (0,external_wp_element_namespaceObject.createElement)(external_wp_keyboardShortcuts_namespaceObject.ShortcutProvider, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.SlotFillProvider, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_coreData_namespaceObject.EntityProvider, {
    kind: "root",
    type: "site"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_coreData_namespaceObject.EntityProvider, {
    kind: "postType",
    type: templateType,
    id: entityId
  }, (0,external_wp_element_namespaceObject.createElement)(GlobalStylesProvider, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.BlockContextProvider, {
    value: blockContext
  }, (0,external_wp_element_namespaceObject.createElement)(GlobalStylesRenderer, null), (0,external_wp_element_namespaceObject.createElement)(ErrorBoundary, {
    onError: onError
  }, (0,external_wp_element_namespaceObject.createElement)(fullscreen_mode, {
    isActive: true
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_editor_namespaceObject.UnsavedChangesWarning, null), (0,external_wp_element_namespaceObject.createElement)(keyboard_shortcuts.Register, null), (0,external_wp_element_namespaceObject.createElement)(SidebarComplementaryAreaFills, null), (0,external_wp_element_namespaceObject.createElement)(interface_skeleton, {
    labels: interfaceLabels,
    drawer: newMenuSidebar ? undefined : (0,external_wp_element_namespaceObject.createElement)(NavigationSidebar, null),
    secondarySidebar: secondarySidebar(),
    sidebar: sidebarIsOpened && (0,external_wp_element_namespaceObject.createElement)(complementary_area.Slot, {
      scope: "core/edit-site"
    }),
    header: (0,external_wp_element_namespaceObject.createElement)(Header, {
      openEntitiesSavedStates: openEntitiesSavedStates
    }),
    notices: (0,external_wp_element_namespaceObject.createElement)(external_wp_editor_namespaceObject.EditorSnackbars, null),
    content: (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_editor_namespaceObject.EditorNotices, null), template && (0,external_wp_element_namespaceObject.createElement)(BlockEditor, {
      setIsInserterOpen: setIsInserterOpened
    }), templateResolved && !template && (settings === null || settings === void 0 ? void 0 : settings.siteUrl) && entityId && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Notice, {
      status: "warning",
      isDismissible: false
    }, (0,external_wp_i18n_namespaceObject.__)("You attempted to edit an item that doesn't exist. Perhaps it was deleted?")), (0,external_wp_element_namespaceObject.createElement)(keyboard_shortcuts, {
      openEntitiesSavedStates: openEntitiesSavedStates
    })),
    actions: (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, isEntitiesSavedStatesOpen ? (0,external_wp_element_namespaceObject.createElement)(external_wp_editor_namespaceObject.EntitiesSavedStates, {
      close: closeEntitiesSavedStates
    }) : (0,external_wp_element_namespaceObject.createElement)("div", {
      className: "edit-site-editor__toggle-save-panel"
    }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
      variant: "secondary",
      className: "edit-site-editor__toggle-save-panel-button",
      onClick: openEntitiesSavedStates,
      "aria-expanded": false
    }, (0,external_wp_i18n_namespaceObject.__)('Open save panel')))),
    footer: (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.BlockBreadcrumb, null)
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Popover.Slot, null), (0,external_wp_element_namespaceObject.createElement)(external_wp_plugins_namespaceObject.PluginArea, null)))))))));
}

/* harmony default export */ var editor = (Editor);
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/sidebar/plugin-sidebar/index.js



/**
 * WordPress dependencies
 */

/**
 * Renders a sidebar when activated. The contents within the `PluginSidebar` will appear as content within the sidebar.
 * It also automatically renders a corresponding `PluginSidebarMenuItem` component when `isPinnable` flag is set to `true`.
 * If you wish to display the sidebar, you can with use the `PluginSidebarMoreMenuItem` component or the `wp.data.dispatch` API:
 *
 * ```js
 * wp.data.dispatch( 'core/edit-site' ).openGeneralSidebar( 'plugin-name/sidebar-name' );
 * ```
 *
 * @see PluginSidebarMoreMenuItem
 *
 * @param {Object}                props                                 Element props.
 * @param {string}                props.name                            A string identifying the sidebar. Must be unique for every sidebar registered within the scope of your plugin.
 * @param {string}                [props.className]                     An optional class name added to the sidebar body.
 * @param {string}                props.title                           Title displayed at the top of the sidebar.
 * @param {boolean}               [props.isPinnable=true]               Whether to allow to pin sidebar to the toolbar. When set to `true` it also automatically renders a corresponding menu item.
 * @param {WPBlockTypeIconRender} [props.icon=inherits from the plugin] The [Dashicon](https://developer.wordpress.org/resource/dashicons/) icon slug string, or an SVG WP element, to be rendered when the sidebar is pinned to toolbar.
 *
 * @example
 * ```js
 * // Using ES5 syntax
 * var __ = wp.i18n.__;
 * var el = wp.element.createElement;
 * var PanelBody = wp.components.PanelBody;
 * var PluginSidebar = wp.editSite.PluginSidebar;
 * var moreIcon = wp.element.createElement( 'svg' ); //... svg element.
 *
 * function MyPluginSidebar() {
 * 	return el(
 * 			PluginSidebar,
 * 			{
 * 				name: 'my-sidebar',
 * 				title: 'My sidebar title',
 * 				icon: moreIcon,
 * 			},
 * 			el(
 * 				PanelBody,
 * 				{},
 * 				__( 'My sidebar content' )
 * 			)
 * 	);
 * }
 * ```
 *
 * @example
 * ```jsx
 * // Using ESNext syntax
 * import { __ } from '@wordpress/i18n';
 * import { PanelBody } from '@wordpress/components';
 * import { PluginSidebar } from '@wordpress/edit-site';
 * import { more } from '@wordpress/icons';
 *
 * const MyPluginSidebar = () => (
 * 	<PluginSidebar
 * 		name="my-sidebar"
 * 		title="My sidebar title"
 * 		icon={ more }
 * 	>
 * 		<PanelBody>
 * 			{ __( 'My sidebar content' ) }
 * 		</PanelBody>
 * 	</PluginSidebar>
 * );
 * ```
 */

function PluginSidebarEditSite({
  className,
  ...props
}) {
  return (0,external_wp_element_namespaceObject.createElement)(complementary_area, _extends({
    panelClassName: className,
    className: "edit-site-sidebar",
    scope: "core/edit-site"
  }, props));
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/header/plugin-sidebar-more-menu-item/index.js



/**
 * WordPress dependencies
 */

/**
 * Renders a menu item in `Plugins` group in `More Menu` drop down,
 * and can be used to activate the corresponding `PluginSidebar` component.
 * The text within the component appears as the menu item label.
 *
 * @param {Object}                props                                 Component props.
 * @param {string}                props.target                          A string identifying the target sidebar you wish to be activated by this menu item. Must be the same as the `name` prop you have given to that sidebar.
 * @param {WPBlockTypeIconRender} [props.icon=inherits from the plugin] The [Dashicon](https://developer.wordpress.org/resource/dashicons/) icon slug string, or an SVG WP element, to be rendered to the left of the menu item label.
 *
 * @example
 * ```js
 * // Using ES5 syntax
 * var __ = wp.i18n.__;
 * var PluginSidebarMoreMenuItem = wp.editSite.PluginSidebarMoreMenuItem;
 * var moreIcon = wp.element.createElement( 'svg' ); //... svg element.
 *
 * function MySidebarMoreMenuItem() {
 * 	return wp.element.createElement(
 * 		PluginSidebarMoreMenuItem,
 * 		{
 * 			target: 'my-sidebar',
 * 			icon: moreIcon,
 * 		},
 * 		__( 'My sidebar title' )
 * 	)
 * }
 * ```
 *
 * @example
 * ```jsx
 * // Using ESNext syntax
 * import { __ } from '@wordpress/i18n';
 * import { PluginSidebarMoreMenuItem } from '@wordpress/edit-site';
 * import { more } from '@wordpress/icons';
 *
 * const MySidebarMoreMenuItem = () => (
 * 	<PluginSidebarMoreMenuItem
 * 		target="my-sidebar"
 * 		icon={ more }
 * 	>
 * 		{ __( 'My sidebar title' ) }
 * 	</PluginSidebarMoreMenuItem>
 * );
 * ```
 *
 * @return {WPComponent} The component to be rendered.
 */

function PluginSidebarMoreMenuItem(props) {
  return (0,external_wp_element_namespaceObject.createElement)(ComplementaryAreaMoreMenuItem // Menu item is marked with unstable prop for backward compatibility.
  // @see https://github.com/WordPress/gutenberg/issues/14457
  , _extends({
    __unstableExplicitMenuItem: true,
    scope: "core/edit-site"
  }, props));
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/components/header/plugin-more-menu-item/index.js
/**
 * WordPress dependencies
 */



/**
 * Renders a menu item in `Plugins` group in `More Menu` drop down, and can be used to as a button or link depending on the props provided.
 * The text within the component appears as the menu item label.
 *
 * @param {Object}                props                                 Component properties.
 * @param {string}                [props.href]                          When `href` is provided then the menu item is represented as an anchor rather than button. It corresponds to the `href` attribute of the anchor.
 * @param {WPBlockTypeIconRender} [props.icon=inherits from the plugin] The [Dashicon](https://developer.wordpress.org/resource/dashicons/) icon slug string, or an SVG WP element, to be rendered to the left of the menu item label.
 * @param {Function}              [props.onClick=noop]                  The callback function to be executed when the user clicks the menu item.
 * @param {...*}                  [props.other]                         Any additional props are passed through to the underlying [Button](/packages/components/src/button/README.md) component.
 *
 * @example
 * ```js
 * // Using ES5 syntax
 * var __ = wp.i18n.__;
 * var PluginMoreMenuItem = wp.editSite.PluginMoreMenuItem;
 * var moreIcon = wp.element.createElement( 'svg' ); //... svg element.
 *
 * function onButtonClick() {
 * 	alert( 'Button clicked.' );
 * }
 *
 * function MyButtonMoreMenuItem() {
 * 	return wp.element.createElement(
 * 		PluginMoreMenuItem,
 * 		{
 * 			icon: moreIcon,
 * 			onClick: onButtonClick,
 * 		},
 * 		__( 'My button title' )
 * 	);
 * }
 * ```
 *
 * @example
 * ```jsx
 * // Using ESNext syntax
 * import { __ } from '@wordpress/i18n';
 * import { PluginMoreMenuItem } from '@wordpress/edit-site';
 * import { more } from '@wordpress/icons';
 *
 * function onButtonClick() {
 * 	alert( 'Button clicked.' );
 * }
 *
 * const MyButtonMoreMenuItem = () => (
 * 	<PluginMoreMenuItem
 * 		icon={ more }
 * 		onClick={ onButtonClick }
 * 	>
 * 		{ __( 'My button title' ) }
 * 	</PluginMoreMenuItem>
 * );
 * ```
 *
 * @return {WPComponent} The component to be rendered.
 */

/* harmony default export */ var plugin_more_menu_item = ((0,external_wp_compose_namespaceObject.compose)((0,external_wp_plugins_namespaceObject.withPluginContext)((context, ownProps) => {
  return {
    icon: ownProps.icon || context.icon,
    name: 'core/edit-site/plugin-more-menu'
  };
}))(action_item));
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-site/build-module/index.js


/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */





/**
 * Reinitializes the editor after the user chooses to reboot the editor after
 * an unhandled error occurs, replacing previously mounted editor element using
 * an initial state from prior to the crash.
 *
 * @param {Element} target   DOM node in which editor is rendered.
 * @param {?Object} settings Editor settings object.
 */

function reinitializeEditor(target, settings) {
  (0,external_wp_element_namespaceObject.unmountComponentAtNode)(target);
  const reboot = reinitializeEditor.bind(null, target, settings);
  (0,external_wp_element_namespaceObject.render)((0,external_wp_element_namespaceObject.createElement)(editor, {
    initialSettings: settings,
    onError: reboot
  }), target);
}
/**
 * Initializes the site editor screen.
 *
 * @param {string} id       ID of the root element to render the screen in.
 * @param {Object} settings Editor settings.
 */

function initialize(id, settings) {
  settings.__experimentalFetchLinkSuggestions = (search, searchOptions) => (0,external_wp_coreData_namespaceObject.__experimentalFetchLinkSuggestions)(search, searchOptions, settings);

  settings.__experimentalFetchRichUrlData = external_wp_coreData_namespaceObject.__experimentalFetchUrlData;
  settings.__experimentalSpotlightEntityBlocks = ['core/template-part']; // Feature flag for the new menu sidebar which isn't stable yet.

  settings.__experimentalNewMenuSidebar = false;
  const target = document.getElementById(id);
  const reboot = reinitializeEditor.bind(null, target, settings);

  (0,external_wp_data_namespaceObject.dispatch)(external_wp_blocks_namespaceObject.store).__experimentalReapplyBlockTypeFilters();

  (0,external_wp_blockLibrary_namespaceObject.registerCoreBlocks)();

  if (true) {
    (0,external_wp_blockLibrary_namespaceObject.__experimentalRegisterExperimentalCoreBlocks)({
      enableFSEBlocks: true
    });
  }

  (0,external_wp_element_namespaceObject.render)((0,external_wp_element_namespaceObject.createElement)(editor, {
    initialSettings: settings,
    onError: reboot
  }), target);
}





//# sourceMappingURL=index.js.map
}();
(window.wp = window.wp || {}).editSite = __webpack_exports__;
/******/ })()
;