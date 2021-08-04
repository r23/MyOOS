window["wp"] = window["wp"] || {}; window["wp"]["editNavigation"] =
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 503);
/******/ })
/************************************************************************/
/******/ ({

/***/ 0:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["element"]; }());

/***/ }),

/***/ 1:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["i18n"]; }());

/***/ }),

/***/ 10:
/***/ (function(module, exports, __webpack_require__) {

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

/***/ 12:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["coreData"]; }());

/***/ }),

/***/ 123:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(0);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(6);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__);


/**
 * WordPress dependencies
 */

const check = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["SVG"], {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["Path"], {
  d: "M18.3 5.6L9.9 16.9l-4.6-3.4-.9 1.2 5.8 4.3 9.3-12.6z"
}));
/* harmony default export */ __webpack_exports__["a"] = (check);
//# sourceMappingURL=check.js.map

/***/ }),

/***/ 140:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(0);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(6);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__);


/**
 * WordPress dependencies
 */

const closeSmall = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["SVG"], {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["Path"], {
  d: "M12 13.06l3.712 3.713 1.061-1.06L13.061 12l3.712-3.712-1.06-1.06L12 10.938 8.288 7.227l-1.061 1.06L10.939 12l-3.712 3.712 1.06 1.061L12 13.061z"
}));
/* harmony default export */ __webpack_exports__["a"] = (closeSmall);
//# sourceMappingURL=close-small.js.map

/***/ }),

/***/ 197:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(0);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(6);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__);


/**
 * WordPress dependencies
 */

const starFilled = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["SVG"], {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["Path"], {
  d: "M11.776 4.454a.25.25 0 01.448 0l2.069 4.192a.25.25 0 00.188.137l4.626.672a.25.25 0 01.139.426l-3.348 3.263a.25.25 0 00-.072.222l.79 4.607a.25.25 0 01-.362.263l-4.138-2.175a.25.25 0 00-.232 0l-4.138 2.175a.25.25 0 01-.363-.263l.79-4.607a.25.25 0 00-.071-.222L4.754 9.881a.25.25 0 01.139-.426l4.626-.672a.25.25 0 00.188-.137l2.069-4.192z"
}));
/* harmony default export */ __webpack_exports__["a"] = (starFilled);
//# sourceMappingURL=star-filled.js.map

/***/ }),

/***/ 198:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(0);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(6);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__);


/**
 * WordPress dependencies
 */

const starEmpty = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["SVG"], {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["Path"], {
  fillRule: "evenodd",
  d: "M9.706 8.646a.25.25 0 01-.188.137l-4.626.672a.25.25 0 00-.139.427l3.348 3.262a.25.25 0 01.072.222l-.79 4.607a.25.25 0 00.362.264l4.138-2.176a.25.25 0 01.233 0l4.137 2.175a.25.25 0 00.363-.263l-.79-4.607a.25.25 0 01.072-.222l3.347-3.262a.25.25 0 00-.139-.427l-4.626-.672a.25.25 0 01-.188-.137l-2.069-4.192a.25.25 0 00-.448 0L9.706 8.646zM12 7.39l-.948 1.921a1.75 1.75 0 01-1.317.957l-2.12.308 1.534 1.495c.412.402.6.982.503 1.55l-.362 2.11 1.896-.997a1.75 1.75 0 011.629 0l1.895.997-.362-2.11a1.75 1.75 0 01.504-1.55l1.533-1.495-2.12-.308a1.75 1.75 0 01-1.317-.957L12 7.39z",
  clipRule: "evenodd"
}));
/* harmony default export */ __webpack_exports__["a"] = (starEmpty);
//# sourceMappingURL=star-empty.js.map

/***/ }),

/***/ 2:
/***/ (function(module, exports) {

(function() { module.exports = window["lodash"]; }());

/***/ }),

/***/ 207:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";

// CONCATENATED MODULE: ./node_modules/uuid/dist/esm-browser/rng.js
// Unique ID creation requires a high quality random # generator. In the browser we therefore
// require the crypto API and do not support built-in fallback to lower quality random number
// generators (like Math.random()).
// getRandomValues needs to be invoked in a context where "this" is a Crypto implementation. Also,
// find the complete implementation of crypto (msCrypto) on IE11.
var getRandomValues = typeof crypto !== 'undefined' && crypto.getRandomValues && crypto.getRandomValues.bind(crypto) || typeof msCrypto !== 'undefined' && typeof msCrypto.getRandomValues === 'function' && msCrypto.getRandomValues.bind(msCrypto);
var rnds8 = new Uint8Array(16);
function rng() {
  if (!getRandomValues) {
    throw new Error('crypto.getRandomValues() not supported. See https://github.com/uuidjs/uuid#getrandomvalues-not-supported');
  }

  return getRandomValues(rnds8);
}
// CONCATENATED MODULE: ./node_modules/uuid/dist/esm-browser/regex.js
/* harmony default export */ var regex = (/^(?:[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}|00000000-0000-0000-0000-000000000000)$/i);
// CONCATENATED MODULE: ./node_modules/uuid/dist/esm-browser/validate.js


function validate(uuid) {
  return typeof uuid === 'string' && regex.test(uuid);
}

/* harmony default export */ var esm_browser_validate = (validate);
// CONCATENATED MODULE: ./node_modules/uuid/dist/esm-browser/stringify.js

/**
 * Convert array of 16 byte values to UUID string format of the form:
 * XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX
 */

var byteToHex = [];

for (var stringify_i = 0; stringify_i < 256; ++stringify_i) {
  byteToHex.push((stringify_i + 0x100).toString(16).substr(1));
}

function stringify(arr) {
  var offset = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
  // Note: Be careful editing this code!  It's been tuned for performance
  // and works in ways you may not expect. See https://github.com/uuidjs/uuid/pull/434
  var uuid = (byteToHex[arr[offset + 0]] + byteToHex[arr[offset + 1]] + byteToHex[arr[offset + 2]] + byteToHex[arr[offset + 3]] + '-' + byteToHex[arr[offset + 4]] + byteToHex[arr[offset + 5]] + '-' + byteToHex[arr[offset + 6]] + byteToHex[arr[offset + 7]] + '-' + byteToHex[arr[offset + 8]] + byteToHex[arr[offset + 9]] + '-' + byteToHex[arr[offset + 10]] + byteToHex[arr[offset + 11]] + byteToHex[arr[offset + 12]] + byteToHex[arr[offset + 13]] + byteToHex[arr[offset + 14]] + byteToHex[arr[offset + 15]]).toLowerCase(); // Consistency check for valid UUID.  If this throws, it's likely due to one
  // of the following:
  // - One or more input array values don't map to a hex octet (leading to
  // "undefined" in the uuid)
  // - Invalid input values for the RFC `version` or `variant` fields

  if (!esm_browser_validate(uuid)) {
    throw TypeError('Stringified UUID is invalid');
  }

  return uuid;
}

/* harmony default export */ var esm_browser_stringify = (stringify);
// CONCATENATED MODULE: ./node_modules/uuid/dist/esm-browser/v4.js



function v4(options, buf, offset) {
  options = options || {};
  var rnds = options.random || (options.rng || rng)(); // Per 4.4, set bits for version and `clock_seq_hi_and_reserved`

  rnds[6] = rnds[6] & 0x0f | 0x40;
  rnds[8] = rnds[8] & 0x3f | 0x80; // Copy bytes to buffer, if provided

  if (buf) {
    offset = offset || 0;

    for (var i = 0; i < 16; ++i) {
      buf[offset + i] = rnds[i];
    }

    return buf;
  }

  return esm_browser_stringify(rnds);
}

/* harmony default export */ var esm_browser_v4 = __webpack_exports__["a"] = (v4);

/***/ }),

/***/ 21:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["hooks"]; }());

/***/ }),

/***/ 27:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["keyboardShortcuts"]; }());

/***/ }),

/***/ 270:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(0);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(6);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__);


/**
 * WordPress dependencies
 */

const cog = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["SVG"], {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["Path"], {
  fillRule: "evenodd",
  d: "M10.289 4.836A1 1 0 0111.275 4h1.306a1 1 0 01.987.836l.244 1.466c.787.26 1.503.679 2.108 1.218l1.393-.522a1 1 0 011.216.437l.653 1.13a1 1 0 01-.23 1.273l-1.148.944a6.025 6.025 0 010 2.435l1.149.946a1 1 0 01.23 1.272l-.653 1.13a1 1 0 01-1.216.437l-1.394-.522c-.605.54-1.32.958-2.108 1.218l-.244 1.466a1 1 0 01-.987.836h-1.306a1 1 0 01-.986-.836l-.244-1.466a5.995 5.995 0 01-2.108-1.218l-1.394.522a1 1 0 01-1.217-.436l-.653-1.131a1 1 0 01.23-1.272l1.149-.946a6.026 6.026 0 010-2.435l-1.148-.944a1 1 0 01-.23-1.272l.653-1.131a1 1 0 011.217-.437l1.393.522a5.994 5.994 0 012.108-1.218l.244-1.466zM14.929 12a3 3 0 11-6 0 3 3 0 016 0z",
  clipRule: "evenodd"
}));
/* harmony default export */ __webpack_exports__["a"] = (cog);
//# sourceMappingURL=cog.js.map

/***/ }),

/***/ 3:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["components"]; }());

/***/ }),

/***/ 30:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";

// EXPORTS
__webpack_require__.d(__webpack_exports__, "g", function() { return /* reexport */ store; });
__webpack_require__.d(__webpack_exports__, "b", function() { return /* reexport */ complementary_area; });
__webpack_require__.d(__webpack_exports__, "c", function() { return /* reexport */ ComplementaryAreaMoreMenuItem; });
__webpack_require__.d(__webpack_exports__, "d", function() { return /* reexport */ fullscreen_mode; });
__webpack_require__.d(__webpack_exports__, "e", function() { return /* reexport */ interface_skeleton; });
__webpack_require__.d(__webpack_exports__, "f", function() { return /* reexport */ pinned_items; });
__webpack_require__.d(__webpack_exports__, "a", function() { return /* reexport */ action_item; });

// NAMESPACE OBJECT: ./packages/interface/build-module/store/actions.js
var actions_namespaceObject = {};
__webpack_require__.r(actions_namespaceObject);
__webpack_require__.d(actions_namespaceObject, "enableComplementaryArea", function() { return actions_enableComplementaryArea; });
__webpack_require__.d(actions_namespaceObject, "disableComplementaryArea", function() { return actions_disableComplementaryArea; });
__webpack_require__.d(actions_namespaceObject, "pinItem", function() { return actions_pinItem; });
__webpack_require__.d(actions_namespaceObject, "unpinItem", function() { return actions_unpinItem; });

// NAMESPACE OBJECT: ./packages/interface/build-module/store/selectors.js
var selectors_namespaceObject = {};
__webpack_require__.r(selectors_namespaceObject);
__webpack_require__.d(selectors_namespaceObject, "getActiveComplementaryArea", function() { return selectors_getActiveComplementaryArea; });
__webpack_require__.d(selectors_namespaceObject, "isItemPinned", function() { return selectors_isItemPinned; });

// EXTERNAL MODULE: external ["wp","data"]
var external_wp_data_ = __webpack_require__(4);

// EXTERNAL MODULE: external "lodash"
var external_lodash_ = __webpack_require__(2);

// CONCATENATED MODULE: ./packages/interface/build-module/store/reducer.js
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
  if (type !== 'SET_MULTIPLE_ENABLE_ITEM' || !itemType || !scope || !item || Object(external_lodash_["get"])(state, [itemType, scope, item]) === isEnable) {
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
const enableItems = Object(external_wp_data_["combineReducers"])({
  singleEnableItems,
  multipleEnableItems
});
/* harmony default export */ var reducer = (Object(external_wp_data_["combineReducers"])({
  enableItems
}));
//# sourceMappingURL=reducer.js.map
// CONCATENATED MODULE: ./packages/interface/build-module/store/actions.js
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


function actions_enableComplementaryArea(scope, area) {
  return setSingleEnableItem('complementaryArea', scope, area);
}
/**
 * Returns an action object used in signalling that the complementary area of a given scope should be disabled.
 *
 * @param {string} scope Complementary area scope.
 *
 * @return {Object} Action object.
 */

function actions_disableComplementaryArea(scope) {
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


function actions_pinItem(scope, itemId) {
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

function actions_unpinItem(scope, itemId) {
  return setMultipleEnableItem('pinnedItems', scope, itemId, false);
}
//# sourceMappingURL=actions.js.map
// CONCATENATED MODULE: ./packages/interface/build-module/store/selectors.js
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
  return Object(external_lodash_["get"])(state.enableItems.singleEnableItems, [itemType, scope]);
}
/**
 * Returns the complementary area that is active in a given scope.
 *
 * @param {Object} state Global application state.
 * @param {string} scope Item scope.
 *
 * @return {string} The complementary area that is active in the given scope.
 */


function selectors_getActiveComplementaryArea(state, scope) {
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
  return Object(external_lodash_["get"])(state.enableItems.multipleEnableItems, [itemType, scope, item]);
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


function selectors_isItemPinned(state, scope, item) {
  return isMultipleEnabledItemEnabled(state, 'pinnedItems', scope, item) !== false;
}
//# sourceMappingURL=selectors.js.map
// CONCATENATED MODULE: ./packages/interface/build-module/store/constants.js
/**
 * The identifier for the data store.
 *
 * @type {string}
 */
const STORE_NAME = 'core/interface';
//# sourceMappingURL=constants.js.map
// CONCATENATED MODULE: ./packages/interface/build-module/store/index.js
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

const store = Object(external_wp_data_["createReduxStore"])(STORE_NAME, {
  reducer: reducer,
  actions: actions_namespaceObject,
  selectors: selectors_namespaceObject,
  persist: ['enableItems']
}); // Once we build a more generic persistence plugin that works across types of stores
// we'd be able to replace this with a register call.

Object(external_wp_data_["registerStore"])(STORE_NAME, {
  reducer: reducer,
  actions: actions_namespaceObject,
  selectors: selectors_namespaceObject,
  persist: ['enableItems']
});
//# sourceMappingURL=index.js.map
// EXTERNAL MODULE: ./node_modules/@babel/runtime/helpers/esm/extends.js
var esm_extends = __webpack_require__(7);

// EXTERNAL MODULE: external ["wp","element"]
var external_wp_element_ = __webpack_require__(0);

// EXTERNAL MODULE: ./node_modules/classnames/index.js
var classnames = __webpack_require__(10);
var classnames_default = /*#__PURE__*/__webpack_require__.n(classnames);

// EXTERNAL MODULE: external ["wp","components"]
var external_wp_components_ = __webpack_require__(3);

// EXTERNAL MODULE: external ["wp","i18n"]
var external_wp_i18n_ = __webpack_require__(1);

// EXTERNAL MODULE: ./packages/icons/build-module/library/check.js
var check = __webpack_require__(123);

// EXTERNAL MODULE: ./packages/icons/build-module/library/star-filled.js
var star_filled = __webpack_require__(197);

// EXTERNAL MODULE: ./packages/icons/build-module/library/star-empty.js
var star_empty = __webpack_require__(198);

// EXTERNAL MODULE: external ["wp","viewport"]
var external_wp_viewport_ = __webpack_require__(65);

// EXTERNAL MODULE: ./packages/icons/build-module/library/close-small.js
var close_small = __webpack_require__(140);

// EXTERNAL MODULE: external ["wp","plugins"]
var external_wp_plugins_ = __webpack_require__(49);

// CONCATENATED MODULE: ./packages/interface/build-module/components/complementary-area-context/index.js
/**
 * WordPress dependencies
 */

/* harmony default export */ var complementary_area_context = (Object(external_wp_plugins_["withPluginContext"])((context, ownProps) => {
  return {
    icon: ownProps.icon || context.icon,
    identifier: ownProps.identifier || `${context.name}/${ownProps.name}`
  };
}));
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/interface/build-module/components/complementary-area-toggle/index.js



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
  as = external_wp_components_["Button"],
  scope,
  identifier,
  icon,
  selectedIcon,
  ...props
}) {
  const ComponentToUse = as;
  const isSelected = Object(external_wp_data_["useSelect"])(select => select(store).getActiveComplementaryArea(scope) === identifier, [identifier]);
  const {
    enableComplementaryArea,
    disableComplementaryArea
  } = Object(external_wp_data_["useDispatch"])(store);
  return Object(external_wp_element_["createElement"])(ComponentToUse, Object(esm_extends["a" /* default */])({
    icon: selectedIcon && isSelected ? selectedIcon : icon,
    onClick: () => {
      if (isSelected) {
        disableComplementaryArea(scope);
      } else {
        enableComplementaryArea(scope, identifier);
      }
    }
  }, Object(external_lodash_["omit"])(props, ['name'])));
}

/* harmony default export */ var complementary_area_toggle = (complementary_area_context(ComplementaryAreaToggle));
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/interface/build-module/components/complementary-area-header/index.js



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
  const toggleButton = Object(external_wp_element_["createElement"])(complementary_area_toggle, Object(esm_extends["a" /* default */])({
    icon: close_small["a" /* default */]
  }, toggleButtonProps));
  return Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, Object(external_wp_element_["createElement"])("div", {
    className: "components-panel__header interface-complementary-area-header__small"
  }, smallScreenTitle && Object(external_wp_element_["createElement"])("span", {
    className: "interface-complementary-area-header__small-title"
  }, smallScreenTitle), toggleButton), Object(external_wp_element_["createElement"])("div", {
    className: classnames_default()('components-panel__header', 'interface-complementary-area-header', className),
    tabIndex: -1
  }, children, toggleButton));
};

/* harmony default export */ var complementary_area_header = (ComplementaryAreaHeader);
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/interface/build-module/components/action-item/index.js



/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */




function ActionItemSlot({
  name,
  as: Component = external_wp_components_["ButtonGroup"],
  fillProps = {},
  bubblesVirtually,
  ...props
}) {
  return Object(external_wp_element_["createElement"])(external_wp_components_["Slot"], {
    name: name,
    bubblesVirtually: bubblesVirtually,
    fillProps: fillProps
  }, fills => {
    if (Object(external_lodash_["isEmpty"])(external_wp_element_["Children"].toArray(fills))) {
      return null;
    } // Special handling exists for backward compatibility.
    // It ensures that menu items created by plugin authors aren't
    // duplicated with automatically injected menu items coming
    // from pinnable plugin sidebars.
    // @see https://github.com/WordPress/gutenberg/issues/14457


    const initializedByPlugins = [];
    external_wp_element_["Children"].forEach(fills, ({
      props: {
        __unstableExplicitMenuItem,
        __unstableTarget
      }
    }) => {
      if (__unstableTarget && __unstableExplicitMenuItem) {
        initializedByPlugins.push(__unstableTarget);
      }
    });
    const children = external_wp_element_["Children"].map(fills, child => {
      if (!child.props.__unstableExplicitMenuItem && initializedByPlugins.includes(child.props.__unstableTarget)) {
        return null;
      }

      return child;
    });
    return Object(external_wp_element_["createElement"])(Component, props, children);
  });
}

function ActionItem({
  name,
  as: Component = external_wp_components_["Button"],
  onClick,
  ...props
}) {
  return Object(external_wp_element_["createElement"])(external_wp_components_["Fill"], {
    name: name
  }, ({
    onClick: fpOnClick
  }) => {
    return Object(external_wp_element_["createElement"])(Component, Object(esm_extends["a" /* default */])({
      onClick: onClick || fpOnClick ? (...args) => {
        (onClick || external_lodash_["noop"])(...args);
        (fpOnClick || external_lodash_["noop"])(...args);
      } : undefined
    }, props));
  });
}

ActionItem.Slot = ActionItemSlot;
/* harmony default export */ var action_item = (ActionItem);
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/interface/build-module/components/complementary-area-more-menu-item/index.js



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
Object(external_wp_element_["createElement"])(external_wp_components_["MenuItem"], Object(external_lodash_["omit"])(props, ['__unstableExplicitMenuItem', '__unstableTarget']));

function ComplementaryAreaMoreMenuItem({
  scope,
  target,
  __unstableExplicitMenuItem,
  ...props
}) {
  return Object(external_wp_element_["createElement"])(complementary_area_toggle, Object(esm_extends["a" /* default */])({
    as: toggleProps => {
      return Object(external_wp_element_["createElement"])(action_item, Object(esm_extends["a" /* default */])({
        __unstableExplicitMenuItem: __unstableExplicitMenuItem,
        __unstableTarget: `${scope}/${target}`,
        as: PluginsMenuItem,
        name: `${scope}/plugin-more-menu`
      }, toggleProps));
    },
    role: "menuitemcheckbox",
    selectedIcon: check["a" /* default */],
    name: target,
    scope: scope
  }, props));
}
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/interface/build-module/components/pinned-items/index.js



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
  return Object(external_wp_element_["createElement"])(external_wp_components_["Fill"], Object(esm_extends["a" /* default */])({
    name: `PinnedItems/${scope}`
  }, props));
}

function PinnedItemsSlot({
  scope,
  className,
  ...props
}) {
  return Object(external_wp_element_["createElement"])(external_wp_components_["Slot"], Object(esm_extends["a" /* default */])({
    name: `PinnedItems/${scope}`
  }, props), fills => !Object(external_lodash_["isEmpty"])(fills) && Object(external_wp_element_["createElement"])("div", {
    className: classnames_default()(className, 'interface-pinned-items')
  }, fills));
}

PinnedItems.Slot = PinnedItemsSlot;
/* harmony default export */ var pinned_items = (PinnedItems);
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/interface/build-module/components/complementary-area/index.js



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
  return Object(external_wp_element_["createElement"])(external_wp_components_["Slot"], Object(esm_extends["a" /* default */])({
    name: `ComplementaryArea/${scope}`
  }, props));
}

function ComplementaryAreaFill({
  scope,
  children,
  className
}) {
  return Object(external_wp_element_["createElement"])(external_wp_components_["Fill"], {
    name: `ComplementaryArea/${scope}`
  }, Object(external_wp_element_["createElement"])("div", {
    className: className
  }, children));
}

function useAdjustComplementaryListener(scope, identifier, activeArea, isActive, isSmall) {
  const previousIsSmall = Object(external_wp_element_["useRef"])(false);
  const shouldOpenWhenNotSmall = Object(external_wp_element_["useRef"])(false);
  const {
    enableComplementaryArea,
    disableComplementaryArea
  } = Object(external_wp_data_["useDispatch"])(store);
  Object(external_wp_element_["useEffect"])(() => {
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
  closeLabel = Object(external_wp_i18n_["__"])('Close plugin'),
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
  } = Object(external_wp_data_["useSelect"])(select => {
    const {
      getActiveComplementaryArea,
      isItemPinned
    } = select(store);

    const _activeArea = getActiveComplementaryArea(scope);

    return {
      isActive: _activeArea === identifier,
      isPinned: isItemPinned(scope, identifier),
      activeArea: _activeArea,
      isSmall: select(external_wp_viewport_["store"]).isViewportMatch('< medium'),
      isLarge: select(external_wp_viewport_["store"]).isViewportMatch('large')
    };
  }, [identifier, scope]);
  useAdjustComplementaryListener(scope, identifier, activeArea, isActive, isSmall);
  const {
    enableComplementaryArea,
    disableComplementaryArea,
    pinItem,
    unpinItem
  } = Object(external_wp_data_["useDispatch"])(store);
  Object(external_wp_element_["useEffect"])(() => {
    if (isActiveByDefault && activeArea === undefined && !isSmall) {
      enableComplementaryArea(scope, identifier);
    }
  }, [activeArea, isActiveByDefault, scope, identifier, isSmall]);
  return Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, isPinnable && Object(external_wp_element_["createElement"])(pinned_items, {
    scope: scope
  }, isPinned && Object(external_wp_element_["createElement"])(complementary_area_toggle, {
    scope: scope,
    identifier: identifier,
    isPressed: isActive && (!showIconLabels || isLarge),
    "aria-expanded": isActive,
    label: title,
    icon: showIconLabels ? check["a" /* default */] : icon,
    showTooltip: !showIconLabels,
    variant: showIconLabels ? 'tertiary' : undefined
  })), name && isPinnable && Object(external_wp_element_["createElement"])(ComplementaryAreaMoreMenuItem, {
    target: name,
    scope: scope,
    icon: icon
  }, title), isActive && Object(external_wp_element_["createElement"])(ComplementaryAreaFill, {
    className: classnames_default()('interface-complementary-area', className),
    scope: scope
  }, Object(external_wp_element_["createElement"])(complementary_area_header, {
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
  }, header || Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, Object(external_wp_element_["createElement"])("strong", null, title), isPinnable && Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
    className: "interface-complementary-area__pin-unpin-item",
    icon: isPinned ? star_filled["a" /* default */] : star_empty["a" /* default */],
    label: isPinned ? Object(external_wp_i18n_["__"])('Unpin from toolbar') : Object(external_wp_i18n_["__"])('Pin to toolbar'),
    onClick: () => (isPinned ? unpinItem : pinItem)(scope, identifier),
    isPressed: isPinned,
    "aria-expanded": isPinned
  }))), Object(external_wp_element_["createElement"])(external_wp_components_["Panel"], {
    className: panelClassName
  }, children)));
}

const ComplementaryAreaWrapped = complementary_area_context(ComplementaryArea);
ComplementaryAreaWrapped.Slot = ComplementaryAreaSlot;
/* harmony default export */ var complementary_area = (ComplementaryAreaWrapped);
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/interface/build-module/components/fullscreen-mode/index.js
/**
 * WordPress dependencies
 */


const FullscreenMode = ({
  isActive
}) => {
  Object(external_wp_element_["useEffect"])(() => {
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
  Object(external_wp_element_["useEffect"])(() => {
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
// EXTERNAL MODULE: external ["wp","compose"]
var external_wp_compose_ = __webpack_require__(9);

// CONCATENATED MODULE: ./packages/interface/build-module/components/interface-skeleton/index.js


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
  Object(external_wp_element_["useEffect"])(() => {
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
  const fallbackRef = Object(external_wp_element_["useRef"])();
  const regionsClassName = Object(external_wp_components_["__unstableUseNavigateRegions"])(fallbackRef, shortcuts);
  useHTMLClass('interface-interface-skeleton__html-container');
  const defaultLabels = {
    /* translators: accessibility text for the nav bar landmark region. */
    drawer: Object(external_wp_i18n_["__"])('Drawer'),

    /* translators: accessibility text for the top bar landmark region. */
    header: Object(external_wp_i18n_["__"])('Header'),

    /* translators: accessibility text for the content landmark region. */
    body: Object(external_wp_i18n_["__"])('Content'),

    /* translators: accessibility text for the secondary sidebar landmark region. */
    secondarySidebar: Object(external_wp_i18n_["__"])('Block Library'),

    /* translators: accessibility text for the settings landmark region. */
    sidebar: Object(external_wp_i18n_["__"])('Settings'),

    /* translators: accessibility text for the publish landmark region. */
    actions: Object(external_wp_i18n_["__"])('Publish'),

    /* translators: accessibility text for the footer landmark region. */
    footer: Object(external_wp_i18n_["__"])('Footer')
  };
  const mergedLabels = { ...defaultLabels,
    ...labels
  };
  return Object(external_wp_element_["createElement"])("div", {
    ref: Object(external_wp_compose_["useMergeRefs"])([ref, fallbackRef]),
    className: classnames_default()(className, 'interface-interface-skeleton', regionsClassName, !!footer && 'has-footer')
  }, !!drawer && Object(external_wp_element_["createElement"])("div", {
    className: "interface-interface-skeleton__drawer",
    role: "region",
    "aria-label": mergedLabels.drawer
  }, drawer), Object(external_wp_element_["createElement"])("div", {
    className: "interface-interface-skeleton__editor"
  }, !!header && Object(external_wp_element_["createElement"])("div", {
    className: "interface-interface-skeleton__header",
    role: "region",
    "aria-label": mergedLabels.header,
    tabIndex: "-1"
  }, header), Object(external_wp_element_["createElement"])("div", {
    className: "interface-interface-skeleton__body"
  }, !!secondarySidebar && Object(external_wp_element_["createElement"])("div", {
    className: "interface-interface-skeleton__secondary-sidebar",
    role: "region",
    "aria-label": mergedLabels.secondarySidebar,
    tabIndex: "-1"
  }, secondarySidebar), !!notices && Object(external_wp_element_["createElement"])("div", {
    className: "interface-interface-skeleton__notices"
  }, notices), Object(external_wp_element_["createElement"])("div", {
    className: "interface-interface-skeleton__content",
    role: "region",
    "aria-label": mergedLabels.body,
    tabIndex: "-1"
  }, content), !!sidebar && Object(external_wp_element_["createElement"])("div", {
    className: "interface-interface-skeleton__sidebar",
    role: "region",
    "aria-label": mergedLabels.sidebar,
    tabIndex: "-1"
  }, sidebar), !!actions && Object(external_wp_element_["createElement"])("div", {
    className: "interface-interface-skeleton__actions",
    role: "region",
    "aria-label": mergedLabels.actions,
    tabIndex: "-1"
  }, actions))), !!footer && Object(external_wp_element_["createElement"])("div", {
    className: "interface-interface-skeleton__footer",
    role: "region",
    "aria-label": mergedLabels.footer,
    tabIndex: "-1"
  }, footer));
}

/* harmony default export */ var interface_skeleton = (Object(external_wp_element_["forwardRef"])(InterfaceSkeleton));
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/interface/build-module/components/index.js






//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/interface/build-module/index.js


//# sourceMappingURL=index.js.map

/***/ }),

/***/ 31:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["notices"]; }());

/***/ }),

/***/ 34:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["apiFetch"]; }());

/***/ }),

/***/ 4:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["data"]; }());

/***/ }),

/***/ 49:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["plugins"]; }());

/***/ }),

/***/ 5:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["blockEditor"]; }());

/***/ }),

/***/ 503:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXPORTS
__webpack_require__.d(__webpack_exports__, "initialize", function() { return /* binding */ initialize; });

// NAMESPACE OBJECT: ./packages/edit-navigation/build-module/store/resolvers.js
var resolvers_namespaceObject = {};
__webpack_require__.r(resolvers_namespaceObject);
__webpack_require__.d(resolvers_namespaceObject, "getNavigationPostForMenu", function() { return resolvers_getNavigationPostForMenu; });

// NAMESPACE OBJECT: ./packages/edit-navigation/build-module/store/selectors.js
var selectors_namespaceObject = {};
__webpack_require__.r(selectors_namespaceObject);
__webpack_require__.d(selectors_namespaceObject, "getSelectedMenuId", function() { return getSelectedMenuId; });
__webpack_require__.d(selectors_namespaceObject, "getNavigationPostForMenu", function() { return selectors_getNavigationPostForMenu; });
__webpack_require__.d(selectors_namespaceObject, "hasResolvedNavigationPost", function() { return hasResolvedNavigationPost; });
__webpack_require__.d(selectors_namespaceObject, "getMenuItemForClientId", function() { return getMenuItemForClientId; });

// NAMESPACE OBJECT: ./packages/edit-navigation/build-module/store/actions.js
var actions_namespaceObject = {};
__webpack_require__.r(actions_namespaceObject);
__webpack_require__.d(actions_namespaceObject, "setSelectedMenuId", function() { return actions_setSelectedMenuId; });
__webpack_require__.d(actions_namespaceObject, "createMissingMenuItems", function() { return actions_createMissingMenuItems; });
__webpack_require__.d(actions_namespaceObject, "saveNavigationPost", function() { return actions_saveNavigationPost; });

// EXTERNAL MODULE: external ["wp","element"]
var external_wp_element_ = __webpack_require__(0);

// EXTERNAL MODULE: external ["wp","blockLibrary"]
var external_wp_blockLibrary_ = __webpack_require__(62);

// EXTERNAL MODULE: external ["wp","coreData"]
var external_wp_coreData_ = __webpack_require__(12);

// EXTERNAL MODULE: external ["wp","hooks"]
var external_wp_hooks_ = __webpack_require__(21);

// EXTERNAL MODULE: external ["wp","compose"]
var external_wp_compose_ = __webpack_require__(9);

// EXTERNAL MODULE: external ["wp","components"]
var external_wp_components_ = __webpack_require__(3);

// EXTERNAL MODULE: external ["wp","blockEditor"]
var external_wp_blockEditor_ = __webpack_require__(5);

// EXTERNAL MODULE: external ["wp","data"]
var external_wp_data_ = __webpack_require__(4);

// EXTERNAL MODULE: ./packages/interface/build-module/index.js + 15 modules
var build_module = __webpack_require__(30);

// EXTERNAL MODULE: external ["wp","i18n"]
var external_wp_i18n_ = __webpack_require__(1);

// CONCATENATED MODULE: ./packages/edit-navigation/build-module/constants/index.js
/**
 * "Kind" of the menu post.
 *
 * @type {string}
 */
const MENU_KIND = 'root';
/**
 * "post type" of the menu post.
 *
 * @type {string}
 */

const MENU_POST_TYPE = 'menu';
/**
 * "Kind" of the navigation post.
 *
 * @type {string}
 */

const NAVIGATION_POST_KIND = 'root';
/**
 * "post type" of the navigation post.
 *
 * @type {string}
 */

const NAVIGATION_POST_POST_TYPE = 'postType';
/**
 * The scope name of the editor's complementary area.
 *
 * @type {string}
 */

const SIDEBAR_SCOPE = 'core/edit-navigation';
/**
 * The identifier of the editor's menu complementary area.
 *
 * @type {string}
 */

const SIDEBAR_MENU = 'edit-navigation/menu';
/**
 * The identifier of the editor's block complementary area.
 *
 * @type {string}
 */

const SIDEBAR_BLOCK = 'edit-navigation/block-inspector';
/**
 * The string identifier for the menu item's "target" attribute indicating
 * the menu item link should open in a new tab.
 *
 * @type {string}
 */

const NEW_TAB_TARGET_ATTRIBUTE = '_blank';
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/hooks/use-menu-entity.js
/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */


function useMenuEntity(menuId) {
  const {
    editEntityRecord
  } = Object(external_wp_data_["useDispatch"])(external_wp_coreData_["store"]);
  const menuEntityData = [MENU_KIND, MENU_POST_TYPE, menuId];
  const {
    editedMenu,
    hasLoadedEditedMenu
  } = Object(external_wp_data_["useSelect"])(select => {
    return {
      editedMenu: menuId && select(external_wp_coreData_["store"]).getEditedEntityRecord(...menuEntityData),
      hasLoadedEditedMenu: select(external_wp_coreData_["store"]).hasFinishedResolution('getEditedEntityRecord', [...menuEntityData])
    };
  }, [menuId]);
  return {
    editedMenu,
    menuEntityData,
    editMenuEntityRecord: editEntityRecord,
    hasLoadedEditedMenu
  };
}
//# sourceMappingURL=use-menu-entity.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/hooks/use-menu-entity-prop.js
/**
 * WordPress dependencies
 */

/**
 * Internal dependencies
 */


/**
 * Returns the value and setter for the specified
 * property of the menu.
 *
 * @param {string} prop   A Property name.
 * @param {string} menuId A menu ID.
 *
 * @return {[*, Function]} A tuple where the first item is the
 *                         property value and the second is the
 *                         setter.
 */

function useMenuEntityProp(prop, menuId) {
  return Object(external_wp_coreData_["useEntityProp"])(MENU_KIND, MENU_POST_TYPE, prop, menuId);
}
//# sourceMappingURL=use-menu-entity-prop.js.map
// EXTERNAL MODULE: external ["wp","notices"]
var external_wp_notices_ = __webpack_require__(31);

// CONCATENATED MODULE: ./packages/edit-navigation/build-module/store/reducer.js
/**
 * WordPress dependencies
 */

/**
 * Internal to edit-navigation package.
 *
 * Stores menuItemId -> clientId mapping which is necessary for saving the navigation.
 *
 * @param {Object} state  Redux state
 * @param {Object} action Redux action
 * @return {Object} Updated state
 */

function reducer_mapping(state, action) {
  const {
    type,
    postId,
    ...rest
  } = action;

  if (type === 'SET_MENU_ITEM_TO_CLIENT_ID_MAPPING') {
    return { ...state,
      [postId]: rest.mapping
    };
  }

  return state || {};
}
/**
 * Internal to edit-navigation package.
 *
 * Enables serializeProcessing action wrapper by storing the underlying execution
 * state and any pending actions.
 *
 * @param {Object} state  Redux state
 * @param {Object} action Redux action
 * @return {Object} Updated state
 */

function processingQueue(state, action) {
  var _state$postId;

  const {
    type,
    postId,
    ...rest
  } = action;

  switch (type) {
    case 'START_PROCESSING_POST':
      return { ...state,
        [postId]: { ...state[postId],
          inProgress: true
        }
      };

    case 'FINISH_PROCESSING_POST':
      return { ...state,
        [postId]: { ...state[postId],
          inProgress: false
        }
      };

    case 'POP_PENDING_ACTION':
      const postState = { ...state[postId]
      };

      if ('pendingActions' in postState) {
        var _postState$pendingAct;

        postState.pendingActions = (_postState$pendingAct = postState.pendingActions) === null || _postState$pendingAct === void 0 ? void 0 : _postState$pendingAct.filter(item => item !== rest.action);
      }

      return { ...state,
        [postId]: postState
      };

    case 'ENQUEUE_AFTER_PROCESSING':
      const pendingActions = ((_state$postId = state[postId]) === null || _state$postId === void 0 ? void 0 : _state$postId.pendingActions) || [];

      if (!pendingActions.includes(rest.action)) {
        return { ...state,
          [postId]: { ...state[postId],
            pendingActions: [...pendingActions, rest.action]
          }
        };
      }

      break;
  }

  return state || {};
}
/**
 * Reducer keeping track of selected menu ID.
 *
 * @param {number} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */

function reducer_selectedMenuId(state = null, action) {
  switch (action.type) {
    case 'SET_SELECTED_MENU_ID':
      return action.menuId;
  }

  return state;
}
/* harmony default export */ var reducer = (Object(external_wp_data_["combineReducers"])({
  mapping: reducer_mapping,
  processingQueue,
  selectedMenuId: reducer_selectedMenuId
}));
//# sourceMappingURL=reducer.js.map
// EXTERNAL MODULE: external ["wp","blocks"]
var external_wp_blocks_ = __webpack_require__(8);

// EXTERNAL MODULE: external ["wp","apiFetch"]
var external_wp_apiFetch_ = __webpack_require__(34);
var external_wp_apiFetch_default = /*#__PURE__*/__webpack_require__.n(external_wp_apiFetch_);

// EXTERNAL MODULE: external "lodash"
var external_lodash_ = __webpack_require__(2);

// CONCATENATED MODULE: ./packages/edit-navigation/build-module/store/utils.js
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
 * A WP nav_menu_item object.
 * For more documentation on the individual fields present on a menu item please see:
 * https://core.trac.wordpress.org/browser/tags/5.7.1/src/wp-includes/nav-menu.php#L789
 *
 * Changes made here should also be mirrored in packages/block-library/src/navigation/map-menu-items-to-blocks.js.
 *
 * @typedef WPNavMenuItem
 *
 * @property {Object} title       stores the raw and rendered versions of the title/label for this menu item.
 * @property {Array}  xfn         the XFN relationships expressed in the link of this menu item.
 * @property {Array}  classes     the HTML class attributes for this menu item.
 * @property {string} attr_title  the HTML title attribute for this menu item.
 * @property {string} object      The type of object originally represented, such as 'category', 'post', or 'attachment'.
 * @property {string} object_id   The DB ID of the original object this menu item represents, e.g. ID for posts and term_id for categories.
 * @property {string} description The description of this menu item.
 * @property {string} url         The URL to which this menu item points.
 * @property {string} type        The family of objects originally represented, such as 'post_type' or 'taxonomy'.
 * @property {string} target      The target attribute of the link element for this menu item.
 */

/**
 * Builds an ID for a new navigation post.
 *
 * @param {number} menuId Menu id.
 * @return {string} An ID.
 */

const buildNavigationPostId = menuId => `navigation-post-${menuId}`;
/**
 * Builds a query to resolve menu items.
 *
 * @param {number} menuId Menu id.
 * @return {Object} Query.
 */

function menuItemsQuery(menuId) {
  return {
    menus: menuId,
    per_page: -1
  };
}
/**
 * This wrapper guarantees serial execution of data processing actions.
 *
 * Examples:
 * * saveNavigationPost() needs to wait for all the missing items to be created.
 * * Concurrent createMissingMenuItems() could result in sending more requests than required.
 *
 * @param {Function} callback An action creator to wrap
 * @return {Function} Original callback wrapped in a serial execution context
 */

function serializeProcessing(callback) {
  return function* (post) {
    const postId = post.id;
    const isProcessing = yield isProcessingPost(postId);

    if (isProcessing) {
      yield {
        type: 'ENQUEUE_AFTER_PROCESSING',
        postId,
        action: callback
      };
      return {
        status: 'pending'
      };
    }

    yield {
      type: 'POP_PENDING_ACTION',
      postId,
      action: callback
    };
    yield {
      type: 'START_PROCESSING_POST',
      postId
    };

    try {
      yield* callback( // re-select the post as it could be outdated by now
      yield getNavigationPostForMenu(post.meta.menuId));
    } finally {
      yield {
        type: 'FINISH_PROCESSING_POST',
        postId,
        action: callback
      };
      const pendingActions = yield getPendingActions(postId);

      if (pendingActions.length) {
        const serializedCallback = serializeProcessing(pendingActions[0]);
        yield* serializedCallback(post);
      }
    }
  };
}
function computeCustomizedAttribute(blocks, menuId, menuItemsByClientId) {
  const blocksList = blocksTreeToFlatList(blocks);
  const dataList = blocksList.map(({
    block,
    parentId,
    position
  }) => blockToRequestItem(block, parentId, position)); // Create an object like { "nav_menu_item[12]": {...}} }

  const computeKey = item => `nav_menu_item[${item.id}]`;

  const dataObject = Object(external_lodash_["keyBy"])(dataList, computeKey); // Deleted menu items should be sent as false, e.g. { "nav_menu_item[13]": false }

  for (const clientId in menuItemsByClientId) {
    const key = computeKey(menuItemsByClientId[clientId]);

    if (!(key in dataObject)) {
      dataObject[key] = false;
    }
  }

  return JSON.stringify(dataObject);

  function blocksTreeToFlatList(innerBlocks, parentId = 0) {
    return innerBlocks.flatMap((block, index) => {
      var _getMenuItemForBlock;

      return [{
        block,
        parentId,
        position: index + 1
      }].concat(blocksTreeToFlatList(block.innerBlocks, (_getMenuItemForBlock = getMenuItemForBlock(block)) === null || _getMenuItemForBlock === void 0 ? void 0 : _getMenuItemForBlock.id));
    });
  }

  function blockToRequestItem(block, parentId, position) {
    const menuItem = Object(external_lodash_["omit"])(getMenuItemForBlock(block), 'menus', 'meta');
    let attributes;

    if (block.name === 'core/navigation-link') {
      attributes = blockAttributesToMenuItem(block.attributes);
    } else {
      attributes = {
        type: 'block',
        content: Object(external_wp_blocks_["serialize"])(block)
      };
    }

    return { ...menuItem,
      ...attributes,
      position,
      nav_menu_term_id: menuId,
      menu_item_parent: parentId,
      status: 'publish',
      _invalid: false
    };
  }

  function getMenuItemForBlock(block) {
    return Object(external_lodash_["omit"])(menuItemsByClientId[block.clientId] || {}, '_links');
  }
}
/**
 * Convert block attributes to menu item fields.
 *
 * Note that nav_menu_item has defaults provided in Core so in the case of undefined Block attributes
 * we need only include a subset of values in the knowledge that the defaults will be provided in Core.
 *
 * See: https://core.trac.wordpress.org/browser/tags/5.7.1/src/wp-includes/nav-menu.php#L438.
 *
 * @param {Object}  blockAttributes               the block attributes of the block to be converted into menu item fields.
 * @param {string}  blockAttributes.label         the visual name of the block shown in the UI.
 * @param {string}  blockAttributes.url           the URL for the link.
 * @param {string}  blockAttributes.description   a link description.
 * @param {string}  blockAttributes.rel           the XFN relationship expressed in the link of this menu item.
 * @param {string}  blockAttributes.className     the custom CSS classname attributes for this block.
 * @param {string}  blockAttributes.title         the HTML title attribute for the block's link.
 * @param {string}  blockAttributes.type          the type of variation of the block used (eg: 'Post', 'Custom', 'Category'...etc).
 * @param {number}  blockAttributes.id            the ID of the entity optionally associated with the block's link (eg: the Post ID).
 * @param {string}  blockAttributes.kind          the family of objects originally represented, such as 'post-type' or 'taxonomy'.
 * @param {boolean} blockAttributes.opensInNewTab whether or not the block's link should open in a new tab.
 * @return {Object} the menu item (converted from block attributes).
 */

const blockAttributesToMenuItem = ({
  label = '',
  url = '',
  description,
  rel,
  className,
  title: blockTitleAttr,
  type,
  id,
  kind,
  opensInNewTab
}) => {
  var _type;

  // For historical reasons, the `core/navigation-link` variation type is `tag`
  // whereas WP Core expects `post_tag` as the `object` type.
  // To avoid writing a block migration we perform a conversion here.
  // See also inverse equivalent in `menuItemToBlockAttributes`.
  if (type && type === 'tag') {
    type = 'post_tag';
  }

  return {
    title: label,
    url,
    ...((description === null || description === void 0 ? void 0 : description.length) && {
      description
    }),
    ...((rel === null || rel === void 0 ? void 0 : rel.length) && {
      xfn: rel === null || rel === void 0 ? void 0 : rel.trim().split(' ')
    }),
    ...((className === null || className === void 0 ? void 0 : className.length) && {
      classes: className === null || className === void 0 ? void 0 : className.trim().split(' ')
    }),
    ...((blockTitleAttr === null || blockTitleAttr === void 0 ? void 0 : blockTitleAttr.length) && {
      attr_title: blockTitleAttr
    }),
    ...(((_type = type) === null || _type === void 0 ? void 0 : _type.length) && {
      object: type
    }),
    ...((kind === null || kind === void 0 ? void 0 : kind.length) && {
      type: kind === null || kind === void 0 ? void 0 : kind.replace('-', '_')
    }),
    // Only assign object_id if it's a entity type (ie: not "custom").
    ...(id && 'custom' !== type && {
      object_id: id
    }),
    target: opensInNewTab ? NEW_TAB_TARGET_ATTRIBUTE : ''
  };
};
/**
 * Convert block attributes to menu item.
 *
 * @param {WPNavMenuItem} menuItem the menu item to be converted to block attributes.
 * @return {Object} the block attributes converted from the menu item.
 */

const menuItemToBlockAttributes = ({
  title: menuItemTitleField,
  xfn,
  classes,
  // eslint-disable-next-line camelcase
  attr_title,
  object,
  // eslint-disable-next-line camelcase
  object_id,
  description,
  url,
  type: menuItemTypeField,
  target
}) => {
  var _object;

  // For historical reasons, the `core/navigation-link` variation type is `tag`
  // whereas WP Core expects `post_tag` as the `object` type.
  // To avoid writing a block migration we perform a conversion here.
  // See also inverse equivalent in `blockAttributesToMenuItem`.
  if (object && object === 'post_tag') {
    object = 'tag';
  }

  return {
    label: (menuItemTitleField === null || menuItemTitleField === void 0 ? void 0 : menuItemTitleField.rendered) || '',
    ...(((_object = object) === null || _object === void 0 ? void 0 : _object.length) && {
      type: object
    }),
    kind: (menuItemTypeField === null || menuItemTypeField === void 0 ? void 0 : menuItemTypeField.replace('_', '-')) || 'custom',
    url: url || '',
    ...((xfn === null || xfn === void 0 ? void 0 : xfn.length) && xfn.join(' ').trim() && {
      rel: xfn.join(' ').trim()
    }),
    ...((classes === null || classes === void 0 ? void 0 : classes.length) && classes.join(' ').trim() && {
      className: classes.join(' ').trim()
    }),
    ...((attr_title === null || attr_title === void 0 ? void 0 : attr_title.length) && {
      title: attr_title
    }),
    // eslint-disable-next-line camelcase
    ...(object_id && 'custom' !== object && {
      id: object_id
    }),
    ...((description === null || description === void 0 ? void 0 : description.length) && {
      description
    }),
    ...(target === NEW_TAB_TARGET_ATTRIBUTE && {
      opensInNewTab: true
    })
  };
};
//# sourceMappingURL=utils.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/store/constants.js
/**
 * Module Constants
 */
const STORE_NAME = 'core/edit-navigation';
//# sourceMappingURL=constants.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/store/controls.js
/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */



/**
 * Trigger an API Fetch request.
 *
 * @param {Object} request API Fetch Request Object.
 * @return {Object} control descriptor.
 */

function apiFetch(request) {
  return {
    type: 'API_FETCH',
    request
  };
}
/**
 * Returns a list of pending actions for given post id.
 *
 * @param {number} postId Post ID.
 * @return {Array} List of pending actions.
 */

function getPendingActions(postId) {
  return {
    type: 'GET_PENDING_ACTIONS',
    postId
  };
}
/**
 * Returns boolean indicating whether or not an action processing specified
 * post is currently running.
 *
 * @param {number} postId Post ID.
 * @return {Object} Action.
 */

function isProcessingPost(postId) {
  return {
    type: 'IS_PROCESSING_POST',
    postId
  };
}
/**
 * Selects menuItemId -> clientId mapping (necessary for saving the navigation).
 *
 * @param {number} postId Navigation post ID.
 * @return {Object} Action.
 */

function getMenuItemToClientIdMapping(postId) {
  return {
    type: 'GET_MENU_ITEM_TO_CLIENT_ID_MAPPING',
    postId
  };
}
/**
 * Resolves navigation post for given menuId.
 *
 * @see selectors.js
 * @param {number} menuId Menu ID.
 * @return {Object} Action.
 */

function getNavigationPostForMenu(menuId) {
  return {
    type: 'SELECT',
    registryName: STORE_NAME,
    selectorName: 'getNavigationPostForMenu',
    args: [menuId]
  };
}
/**
 * Resolves menu items for given menu id.
 *
 * @param {number} menuId Menu ID.
 * @return {Object} Action.
 */

function resolveMenuItems(menuId) {
  return {
    type: 'RESOLVE_MENU_ITEMS',
    query: menuItemsQuery(menuId)
  };
}
/**
 * Calls a selector using chosen registry.
 *
 * @param {string} registryName Registry name.
 * @param {string} selectorName Selector name.
 * @param {Array}  args         Selector arguments.
 * @return {Object} control descriptor.
 */

function controls_select(registryName, selectorName, ...args) {
  return {
    type: 'SELECT',
    registryName,
    selectorName,
    args
  };
}
/**
 * Dispatches an action using chosen registry.
 *
 * @param {string} registryName Registry name.
 * @param {string} actionName   Action name.
 * @param {Array}  args         Selector arguments.
 * @return {Object} control descriptor.
 */

function dispatch(registryName, actionName, ...args) {
  return {
    type: 'DISPATCH',
    registryName,
    actionName,
    args
  };
}
const controls = {
  API_FETCH({
    request
  }) {
    return external_wp_apiFetch_default()(request);
  },

  SELECT: Object(external_wp_data_["createRegistryControl"])(registry => ({
    registryName,
    selectorName,
    args
  }) => {
    return registry.select(registryName)[selectorName](...args);
  }),
  GET_PENDING_ACTIONS: Object(external_wp_data_["createRegistryControl"])(registry => ({
    postId
  }) => {
    var _getState$processingQ;

    return ((_getState$processingQ = getState(registry).processingQueue[postId]) === null || _getState$processingQ === void 0 ? void 0 : _getState$processingQ.pendingActions) || [];
  }),
  IS_PROCESSING_POST: Object(external_wp_data_["createRegistryControl"])(registry => ({
    postId
  }) => {
    var _getState$processingQ2;

    return !!((_getState$processingQ2 = getState(registry).processingQueue[postId]) !== null && _getState$processingQ2 !== void 0 && _getState$processingQ2.inProgress);
  }),
  GET_MENU_ITEM_TO_CLIENT_ID_MAPPING: Object(external_wp_data_["createRegistryControl"])(registry => ({
    postId
  }) => {
    return getState(registry).mapping[postId] || {};
  }),
  DISPATCH: Object(external_wp_data_["createRegistryControl"])(registry => ({
    registryName,
    actionName,
    args
  }) => {
    return registry.dispatch(registryName)[actionName](...args);
  }),
  RESOLVE_MENU_ITEMS: Object(external_wp_data_["createRegistryControl"])(registry => ({
    query
  }) => {
    return registry.resolveSelect('core').getMenuItems(query);
  })
};

const getState = registry => registry.stores[STORE_NAME].store.getState();

/* harmony default export */ var store_controls = (controls);
//# sourceMappingURL=controls.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/store/menu-items-to-blocks.js
/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */


/**
 * Convert a flat menu item structure to a nested blocks structure.
 *
 * @param {Object[]} menuItems An array of menu items.
 *
 * @return {WPBlock[]} An array of blocks.
 */

function menuItemsToBlocks(menuItems) {
  if (!menuItems) {
    return null;
  }

  const menuTree = createDataTree(menuItems);
  return mapMenuItemsToBlocks(menuTree);
}
/**
 * A recursive function that maps menu item nodes to blocks.
 *
 * @param {WPNavMenuItem[]} menuItems An array of WPNavMenuItem items.
 * @return {Object} Object containing innerBlocks and mapping.
 */

function mapMenuItemsToBlocks(menuItems) {
  let mapping = {}; // The menuItem should be in menu_order sort order.

  const sortedItems = Object(external_lodash_["sortBy"])(menuItems, 'menu_order');
  const innerBlocks = sortedItems.map(menuItem => {
    var _menuItem$children;

    if (menuItem.type === 'block') {
      const [block] = Object(external_wp_blocks_["parse"])(menuItem.content.raw);

      if (!block) {
        return Object(external_wp_blocks_["createBlock"])('core/freeform', {
          content: menuItem.content
        });
      }

      return block;
    }

    const attributes = menu_items_to_blocks_menuItemToBlockAttributes(menuItem); // If there are children recurse to build those nested blocks.

    const {
      innerBlocks: nestedBlocks = [],
      // alias to avoid shadowing
      mapping: nestedMapping = {} // alias to avoid shadowing

    } = (_menuItem$children = menuItem.children) !== null && _menuItem$children !== void 0 && _menuItem$children.length ? mapMenuItemsToBlocks(menuItem.children) : {}; // Update parent mapping with nested mapping.

    mapping = { ...mapping,
      ...nestedMapping
    }; // Create block with nested "innerBlocks".

    const block = Object(external_wp_blocks_["createBlock"])('core/navigation-link', attributes, nestedBlocks); // Create mapping for menuItem -> block

    mapping[menuItem.id] = block.clientId;
    return block;
  });
  return {
    innerBlocks,
    mapping
  };
}
/**
 * A WP nav_menu_item object.
 * For more documentation on the individual fields present on a menu item please see:
 * https://core.trac.wordpress.org/browser/tags/5.7.1/src/wp-includes/nav-menu.php#L789
 *
 * Changes made here should also be mirrored in packages/edit-navigation/src/store/utils.js.
 *
 * @typedef WPNavMenuItem
 *
 * @property {Object} title       stores the raw and rendered versions of the title/label for this menu item.
 * @property {Array}  xfn         the XFN relationships expressed in the link of this menu item.
 * @property {Array}  classes     the HTML class attributes for this menu item.
 * @property {string} attr_title  the HTML title attribute for this menu item.
 * @property {string} object      The type of object originally represented, such as 'category', 'post', or 'attachment'.
 * @property {string} object_id   The DB ID of the original object this menu item represents, e.g. ID for posts and term_id for categories.
 * @property {string} description The description of this menu item.
 * @property {string} url         The URL to which this menu item points.
 * @property {string} type        The family of objects originally represented, such as 'post_type' or 'taxonomy'.
 * @property {string} target      The target attribute of the link element for this menu item.
 */

/**
 * Convert block attributes to menu item.
 *
 * @param {WPNavMenuItem} menuItem the menu item to be converted to block attributes.
 * @return {Object} the block attributes converted from the WPNavMenuItem item.
 */


function menu_items_to_blocks_menuItemToBlockAttributes({
  title: menuItemTitleField,
  xfn,
  classes,
  // eslint-disable-next-line camelcase
  attr_title,
  object,
  // eslint-disable-next-line camelcase
  object_id,
  description,
  url,
  type: menuItemTypeField,
  target
}) {
  var _object;

  // For historical reasons, the `core/navigation-link` variation type is `tag`
  // whereas WP Core expects `post_tag` as the `object` type.
  // To avoid writing a block migration we perform a conversion here.
  // See also inverse equivalent in `blockAttributesToMenuItem`.
  if (object && object === 'post_tag') {
    object = 'tag';
  }

  return {
    label: (menuItemTitleField === null || menuItemTitleField === void 0 ? void 0 : menuItemTitleField.rendered) || '',
    ...(((_object = object) === null || _object === void 0 ? void 0 : _object.length) && {
      type: object
    }),
    kind: (menuItemTypeField === null || menuItemTypeField === void 0 ? void 0 : menuItemTypeField.replace('_', '-')) || 'custom',
    url: url || '',
    ...((xfn === null || xfn === void 0 ? void 0 : xfn.length) && xfn.join(' ').trim() && {
      rel: xfn.join(' ').trim()
    }),
    ...((classes === null || classes === void 0 ? void 0 : classes.length) && classes.join(' ').trim() && {
      className: classes.join(' ').trim()
    }),
    ...((attr_title === null || attr_title === void 0 ? void 0 : attr_title.length) && {
      title: attr_title
    }),
    // eslint-disable-next-line camelcase
    ...(object_id && 'custom' !== object && {
      id: object_id
    }),
    ...((description === null || description === void 0 ? void 0 : description.length) && {
      description
    }),
    ...(target === '_blank' && {
      opensInNewTab: true
    })
  };
}
/**
 * Creates a nested, hierarchical tree representation from unstructured data that
 * has an inherent relationship defined between individual items.
 *
 * For example, by default, each element in the dataset should have an `id` and
 * `parent` property where the `parent` property indicates a relationship between
 * the current item and another item with a matching `id` properties.
 *
 * This is useful for building linked lists of data from flat data structures.
 *
 * @param {Array}  dataset  linked data to be rearranged into a hierarchical tree based on relational fields.
 * @param {string} id       the property which uniquely identifies each entry within the array.
 * @param {*}      relation the property which identifies how the current item is related to other items in the data (if at all).
 * @return {Array} a nested array of parent/child relationships
 */


function createDataTree(dataset, id = 'id', relation = 'parent') {
  const hashTable = Object.create(null);
  const dataTree = [];

  for (const data of dataset) {
    hashTable[data[id]] = { ...data,
      children: []
    };
  }

  for (const data of dataset) {
    if (data[relation]) {
      hashTable[data[relation]].children.push(hashTable[data[id]]);
    } else {
      dataTree.push(hashTable[data[id]]);
    }
  }

  return dataTree;
}
//# sourceMappingURL=menu-items-to-blocks.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/store/resolvers.js
/**
 * WordPress dependencies
 */

/**
 * Internal dependencies
 */





/**
 * Creates a "stub" navigation post reflecting the contents of menu with id=menuId. The
 * post is meant as a convenient to only exists in runtime and should never be saved. It
 * enables a convenient way of editing the navigation by using a regular post editor.
 *
 * Fetches all menu items, converts them into blocks, and hydrates a new post with them.
 *
 * @param {number} menuId The id of menu to create a post from
 * @return {void}
 */

function* resolvers_getNavigationPostForMenu(menuId) {
  if (!menuId) {
    return;
  }

  const stubPost = createStubPost(menuId); // Persist an empty post to warm up the state

  yield persistPost(stubPost); // Dispatch startResolution to skip the execution of the real getEntityRecord resolver - it would
  // issue an http request and fail.

  const args = [NAVIGATION_POST_KIND, NAVIGATION_POST_POST_TYPE, stubPost.id];
  yield dispatch('core', 'startResolution', 'getEntityRecord', args); // Now let's create a proper one hydrated using actual menu items

  const menuItems = yield resolveMenuItems(menuId);
  const [navigationBlock, menuItemIdToClientId] = createNavigationBlock(menuItems);
  yield {
    type: 'SET_MENU_ITEM_TO_CLIENT_ID_MAPPING',
    postId: stubPost.id,
    mapping: menuItemIdToClientId
  }; // Persist the actual post containing the navigation block

  yield persistPost(createStubPost(menuId, navigationBlock)); // Dispatch finishResolution to conclude startResolution dispatched earlier

  yield dispatch('core', 'finishResolution', 'getEntityRecord', args);
}

const createStubPost = (menuId, navigationBlock = null) => {
  const id = buildNavigationPostId(menuId);
  return {
    id,
    slug: id,
    status: 'draft',
    type: 'page',
    blocks: navigationBlock ? [navigationBlock] : [],
    meta: {
      menuId
    }
  };
};

const persistPost = post => dispatch('core', 'receiveEntityRecords', NAVIGATION_POST_KIND, NAVIGATION_POST_POST_TYPE, post, {
  id: post.id
}, false);
/**
 * Converts an adjacency list of menuItems into a navigation block.
 *
 * @param {Array} menuItems a list of menu items
 * @return {Object} Navigation block
 */


function createNavigationBlock(menuItems) {
  const {
    innerBlocks,
    mapping: menuItemIdToClientId
  } = menuItemsToBlocks(menuItems);
  const navigationBlock = Object(external_wp_blocks_["createBlock"])('core/navigation', {
    orientation: 'vertical'
  }, innerBlocks);
  return [navigationBlock, menuItemIdToClientId];
}
//# sourceMappingURL=resolvers.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/store/selectors.js
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
 * Returns the selected menu ID.
 *
 * @param {Object} state Global application state.
 *
 * @return {number} The selected menu ID.
 */

function getSelectedMenuId(state) {
  var _state$selectedMenuId;

  return (_state$selectedMenuId = state.selectedMenuId) !== null && _state$selectedMenuId !== void 0 ? _state$selectedMenuId : null;
}
/**
 * Returns a "stub" navigation post reflecting the contents of menu with id=menuId. The
 * post is meant as a convenient to only exists in runtime and should never be saved. It
 * enables a convenient way of editing the navigation by using a regular post editor.
 *
 * Related resolver fetches all menu items, converts them into blocks, and hydrates a new post with them.
 *
 * @param {number} menuId The id of menu to create a post from.
 * @return {null|Object} Post once the resolver fetches it, otherwise null
 */

const selectors_getNavigationPostForMenu = Object(external_wp_data_["createRegistrySelector"])(select => (state, menuId) => {
  // When the record is unavailable, calling getEditedEntityRecord triggers a http
  // request via it's related resolver. Let's return nothing until getNavigationPostForMenu
  // resolver marks the record as resolved.
  if (!hasResolvedNavigationPost(state, menuId)) {
    return null;
  }

  return select(external_wp_coreData_["store"].name).getEditedEntityRecord(NAVIGATION_POST_KIND, NAVIGATION_POST_POST_TYPE, buildNavigationPostId(menuId));
});
/**
 * Returns true if the navigation post related to menuId was already resolved.
 *
 * @param {number} menuId The id of menu.
 * @return {boolean} True if the navigation post related to menuId was already resolved, false otherwise.
 */

const hasResolvedNavigationPost = Object(external_wp_data_["createRegistrySelector"])(select => (state, menuId) => {
  return select(external_wp_coreData_["store"].name).hasFinishedResolution('getEntityRecord', [NAVIGATION_POST_KIND, NAVIGATION_POST_POST_TYPE, buildNavigationPostId(menuId)]);
});
/**
 * Returns a menu item represented by the block with id clientId.
 *
 * @param {number} postId   Navigation post id
 * @param {number} clientId Block clientId
 * @return {Object|null} Menu item entity
 */

const getMenuItemForClientId = Object(external_wp_data_["createRegistrySelector"])(select => (state, postId, clientId) => {
  const mapping = Object(external_lodash_["invert"])(state.mapping[postId]);
  return select(external_wp_coreData_["store"].name).getMenuItem(mapping[clientId]);
});
//# sourceMappingURL=selectors.js.map
// EXTERNAL MODULE: ./node_modules/uuid/dist/esm-browser/v4.js + 4 modules
var v4 = __webpack_require__(207);

// CONCATENATED MODULE: ./packages/edit-navigation/build-module/store/actions.js
/**
 * External dependencies
 */


/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */




const {
  ajaxurl
} = window;
/**
 * Returns an action object used to select menu.
 *
 * @param {number} menuId The menu ID.
 * @return {Object} Action object.
 */

function actions_setSelectedMenuId(menuId) {
  return {
    type: 'SET_SELECTED_MENU_ID',
    menuId
  };
}
/**
 * Creates a menu item for every block that doesn't have an associated menuItem.
 * Requests POST /wp/v2/menu-items once for every menu item created.
 *
 * @param {Object} post A navigation post to process
 * @return {Function} An action creator
 */

const actions_createMissingMenuItems = serializeProcessing(function* (post) {
  const menuId = post.meta.menuId;
  const mapping = yield getMenuItemToClientIdMapping(post.id);
  const clientIdToMenuId = Object(external_lodash_["invert"])(mapping);
  const stack = [post.blocks[0]];

  while (stack.length) {
    const block = stack.pop();

    if (!(block.clientId in clientIdToMenuId)) {
      const menuItem = yield apiFetch({
        path: `/__experimental/menu-items`,
        method: 'POST',
        data: {
          title: 'Placeholder',
          url: 'Placeholder',
          menu_order: 0
        }
      });
      mapping[menuItem.id] = block.clientId;
      const menuItems = yield resolveMenuItems(menuId);
      yield dispatch('core', 'receiveEntityRecords', 'root', 'menuItem', [...menuItems, menuItem], menuItemsQuery(menuId), false);
    }

    stack.push(...block.innerBlocks);
  }

  yield {
    type: 'SET_MENU_ITEM_TO_CLIENT_ID_MAPPING',
    postId: post.id,
    mapping
  };
});
/**
 * Converts all the blocks into menu items and submits a batch request to save everything at once.
 *
 * @param {Object} post A navigation post to process
 * @return {Function} An action creator
 */

const actions_saveNavigationPost = serializeProcessing(function* (post) {
  const menuId = post.meta.menuId;
  const menuItemsByClientId = mapMenuItemsByClientId(yield resolveMenuItems(menuId), yield getMenuItemToClientIdMapping(post.id));

  try {
    // Save edits to the menu, like the menu name.
    yield dispatch('core', 'saveEditedEntityRecord', 'root', 'menu', menuId);
    const error = yield controls_select('core', 'getLastEntitySaveError', 'root', 'menu', menuId);

    if (error) {
      throw new Error(error.message);
    } // Save blocks as menu items.


    const batchSaveResponse = yield* batchSave(menuId, menuItemsByClientId, post.blocks[0]);

    if (!batchSaveResponse.success) {
      throw new Error(batchSaveResponse.data.message);
    } // Clear "stub" navigation post edits to avoid a false "dirty" state.


    yield dispatch('core', 'receiveEntityRecords', NAVIGATION_POST_KIND, NAVIGATION_POST_POST_TYPE, [post], undefined);
    yield dispatch(external_wp_notices_["store"], 'createSuccessNotice', Object(external_wp_i18n_["__"])('Navigation saved.'), {
      type: 'snackbar'
    });
  } catch (saveError) {
    const errorMessage = saveError ? Object(external_wp_i18n_["sprintf"])(
    /* translators: %s: The text of an error message (potentially untranslated). */
    Object(external_wp_i18n_["__"])("Unable to save: '%s'"), saveError.message) : Object(external_wp_i18n_["__"])('Unable to save: An error ocurred.');
    yield dispatch(external_wp_notices_["store"], 'createErrorNotice', errorMessage, {
      type: 'snackbar'
    });
  }
});

function mapMenuItemsByClientId(menuItems, clientIdsByMenuId) {
  const result = {};

  if (!menuItems || !clientIdsByMenuId) {
    return result;
  }

  for (const menuItem of menuItems) {
    const clientId = clientIdsByMenuId[menuItem.id];

    if (clientId) {
      result[clientId] = menuItem;
    }
  }

  return result;
}

function* batchSave(menuId, menuItemsByClientId, navigationBlock) {
  const {
    nonce,
    stylesheet
  } = yield apiFetch({
    path: '/__experimental/customizer-nonces/get-save-nonce'
  });

  if (!nonce) {
    throw new Error();
  } // eslint-disable-next-line no-undef


  const body = new FormData();
  body.append('wp_customize', 'on');
  body.append('customize_theme', stylesheet);
  body.append('nonce', nonce);
  body.append('customize_changeset_uuid', Object(v4["a" /* default */])());
  body.append('customize_autosaved', 'on');
  body.append('customize_changeset_status', 'publish');
  body.append('action', 'customize_save');
  body.append('customized', computeCustomizedAttribute(navigationBlock.innerBlocks, menuId, menuItemsByClientId));
  return yield apiFetch({
    url: ajaxurl || '/wp-admin/admin-ajax.php',
    method: 'POST',
    body
  });
}
//# sourceMappingURL=actions.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/store/index.js
/**
 * WordPress dependencies
 */

/**
 * Internal dependencies
 */







/**
 * Block editor data store configuration.
 *
 * @see https://github.com/WordPress/gutenberg/blob/HEAD/packages/data/README.md#registerStore
 *
 * @type {Object}
 */

const storeConfig = {
  reducer: reducer,
  controls: store_controls,
  selectors: selectors_namespaceObject,
  resolvers: resolvers_namespaceObject,
  actions: actions_namespaceObject,
  persist: ['selectedMenuId']
};
/**
 * Store definition for the edit navigation namespace.
 *
 * @see https://github.com/WordPress/gutenberg/blob/HEAD/packages/data/README.md#createReduxStore
 *
 * @type {Object}
 */

const store = Object(external_wp_data_["createReduxStore"])(STORE_NAME, storeConfig); // Once we build a more generic persistence plugin that works across types of stores
// we'd be able to replace this with a register call.

Object(external_wp_data_["registerStore"])(STORE_NAME, storeConfig);
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/hooks/use-navigation-editor.js
/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */





const getMenusData = select => {
  const selectors = select('core');
  const params = {
    per_page: -1
  };
  return {
    menus: selectors.getMenus(params),
    hasLoadedMenus: selectors.hasFinishedResolution('getMenus', [params])
  };
};

function useNavigationEditor() {
  var _menus$find;

  const {
    deleteMenu: _deleteMenu
  } = Object(external_wp_data_["useDispatch"])(external_wp_coreData_["store"]);
  const [selectedMenuId, setSelectedMenuId] = useSelectedMenuId();
  const [hasFinishedInitialLoad, setHasFinishedInitialLoad] = Object(external_wp_element_["useState"])(false);
  const {
    editedMenu,
    hasLoadedEditedMenu
  } = useMenuEntity(selectedMenuId);
  const {
    menus,
    hasLoadedMenus
  } = Object(external_wp_data_["useSelect"])(getMenusData, []);
  /**
   * If the Menu being edited has been requested from API and it has
   * no values then it has been deleted so reset the selected menu ID.
   */

  Object(external_wp_element_["useEffect"])(() => {
    var _Object$keys;

    if (hasLoadedEditedMenu && !((_Object$keys = Object.keys(editedMenu)) !== null && _Object$keys !== void 0 && _Object$keys.length)) {
      setSelectedMenuId(null);
    }
  }, [hasLoadedEditedMenu, editedMenu]);
  const {
    createErrorNotice,
    createInfoNotice
  } = Object(external_wp_data_["useDispatch"])(external_wp_notices_["store"]);
  const isMenuBeingDeleted = Object(external_wp_data_["useSelect"])(select => select(external_wp_coreData_["store"]).isDeletingEntityRecord('root', 'menu', selectedMenuId), [selectedMenuId]);
  const selectedMenuName = (menus === null || menus === void 0 ? void 0 : (_menus$find = menus.find(({
    id
  }) => id === selectedMenuId)) === null || _menus$find === void 0 ? void 0 : _menus$find.name) || '';
  Object(external_wp_element_["useEffect"])(() => {
    if (hasLoadedMenus) {
      setHasFinishedInitialLoad(true);
    }
  }, [hasLoadedMenus]);
  const navigationPost = Object(external_wp_data_["useSelect"])(select => {
    if (!selectedMenuId) {
      return;
    }

    return select(store).getNavigationPostForMenu(selectedMenuId);
  }, [selectedMenuId]);

  const deleteMenu = async () => {
    const didDeleteMenu = await _deleteMenu(selectedMenuId, {
      force: true
    });

    if (didDeleteMenu) {
      setSelectedMenuId(null);
      createInfoNotice(Object(external_wp_i18n_["sprintf"])( // translators: %s: the name of a menu.
      Object(external_wp_i18n_["__"])('"%s" menu has been deleted'), selectedMenuName), {
        type: 'snackbar',
        isDismissible: true
      });
    } else {
      createErrorNotice(Object(external_wp_i18n_["__"])('Menu deletion unsuccessful'));
    }
  };

  return {
    menus,
    hasLoadedMenus,
    hasFinishedInitialLoad,
    selectedMenuId,
    navigationPost,
    isMenuBeingDeleted,
    selectMenu: setSelectedMenuId,
    deleteMenu,
    isMenuSelected: !!selectedMenuId
  };
}
//# sourceMappingURL=use-navigation-editor.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/hooks/use-navigation-block-editor.js
/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */



function useNavigationBlockEditor(post) {
  const {
    createMissingMenuItems
  } = Object(external_wp_data_["useDispatch"])(store);
  const [blocks, onInput, onEntityChange] = Object(external_wp_coreData_["useEntityBlockEditor"])(NAVIGATION_POST_KIND, NAVIGATION_POST_POST_TYPE, {
    id: post === null || post === void 0 ? void 0 : post.id
  });
  const onChange = Object(external_wp_element_["useCallback"])(async (...args) => {
    await onEntityChange(...args);
    createMissingMenuItems(post);
  }, [onEntityChange, post]);
  return [blocks, onInput, onChange];
}
//# sourceMappingURL=use-navigation-block-editor.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/hooks/use-menu-notifications.js
/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */


function useMenuNotifications(menuId) {
  const {
    lastSaveError,
    lastDeleteError
  } = Object(external_wp_data_["useSelect"])(select => ({
    lastSaveError: select(external_wp_coreData_["store"]).getLastEntitySaveError(MENU_KIND, MENU_POST_TYPE),
    lastDeleteError: select(external_wp_coreData_["store"]).getLastEntityDeleteError(MENU_KIND, MENU_POST_TYPE, menuId)
  }), [menuId]);
  const {
    createErrorNotice
  } = Object(external_wp_data_["useDispatch"])(external_wp_notices_["store"]);

  const processError = error => {
    const document = new window.DOMParser().parseFromString(error.message, 'text/html');
    const errorText = document.body.textContent || '';
    createErrorNotice(errorText, {
      id: 'edit-navigation-error'
    });
  };

  Object(external_wp_element_["useEffect"])(() => {
    if (lastSaveError) {
      processError(lastSaveError);
    }
  }, [lastSaveError]);
  Object(external_wp_element_["useEffect"])(() => {
    if (lastDeleteError) {
      processError(lastDeleteError);
    }
  }, [lastDeleteError]);
}
//# sourceMappingURL=use-menu-notifications.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/hooks/use-selected-menu-id.js
/**
 * WordPress dependencies
 */

/**
 * Internal dependencies
 */


/**
 * Returns selected menu ID and the setter.
 *
 * @return {[number, Function]} A tuple where first item is the
 *                              selected menu ID and second is
 *                              the setter.
 */

function useSelectedMenuId() {
  const selectedMenuId = Object(external_wp_data_["useSelect"])(select => select(store).getSelectedMenuId(), []);
  const {
    setSelectedMenuId
  } = Object(external_wp_data_["useDispatch"])(store);
  return [selectedMenuId, setSelectedMenuId];
}
//# sourceMappingURL=use-selected-menu-id.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/hooks/use-menu-locations.js
/**
 * WordPress dependencies
 */


/**
 * External dependencies
 */


/**
 * Internal dependencies
 */



const locationsForMenuId = (menuLocationsByName, id) => Object.values(menuLocationsByName).filter(({
  menu
}) => menu === id).map(({
  name
}) => name);

function useMenuLocations() {
  const [menuLocationsByName, setMenuLocationsByName] = Object(external_wp_element_["useState"])(null);
  const [menuId] = useSelectedMenuId();
  const {
    editMenuEntityRecord,
    menuEntityData
  } = useMenuEntity(menuId);
  Object(external_wp_element_["useEffect"])(() => {
    let isMounted = true;

    const fetchMenuLocationsByName = async () => {
      const newMenuLocationsByName = await external_wp_apiFetch_default()({
        method: 'GET',
        path: '/__experimental/menu-locations/'
      });

      if (isMounted) {
        setMenuLocationsByName(newMenuLocationsByName);
      }
    };

    fetchMenuLocationsByName();
    return () => isMounted = false;
  }, []);
  const assignMenuToLocation = Object(external_wp_element_["useCallback"])(async (locationName, newMenuId) => {
    const oldMenuId = menuLocationsByName[locationName].menu;
    const newMenuLocationsByName = Object(external_lodash_["merge"])(menuLocationsByName, {
      [locationName]: {
        menu: newMenuId
      }
    });
    setMenuLocationsByName(newMenuLocationsByName);
    const activeMenuId = newMenuId || oldMenuId;
    editMenuEntityRecord(...menuEntityData, {
      locations: locationsForMenuId(newMenuLocationsByName, activeMenuId)
    });
  }, [menuLocationsByName]);

  const toggleMenuLocationAssignment = (locationName, newMenuId) => {
    const idToSet = menuLocationsByName[locationName].menu === newMenuId ? 0 : newMenuId;
    assignMenuToLocation(locationName, idToSet);
  };

  const menuLocations = Object(external_wp_element_["useMemo"])(() => Object.values(menuLocationsByName || {}), [menuLocationsByName]);
  return {
    menuLocations,
    assignMenuToLocation,
    toggleMenuLocationAssignment
  };
}
//# sourceMappingURL=use-menu-locations.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/hooks/index.js
/**
 * WordPress dependencies
 */


const untitledMenu = Object(external_wp_i18n_["__"])('(untitled menu)');
const IsMenuNameControlFocusedContext = Object(external_wp_element_["createContext"])();







//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/name-display/index.js


/**
 * WordPress dependencies
 */






/**
 * Internal dependencies
 */



function NameDisplay() {
  const {
    enableComplementaryArea
  } = Object(external_wp_data_["useDispatch"])(build_module["g" /* store */]);
  const [menuId] = useSelectedMenuId();
  const [name] = useMenuEntityProp('name', menuId);
  const [, setIsMenuNameEditFocused] = Object(external_wp_element_["useContext"])(IsMenuNameControlFocusedContext);
  const menuName = name !== null && name !== void 0 ? name : untitledMenu;
  return Object(external_wp_element_["createElement"])(external_wp_blockEditor_["BlockControls"], null, Object(external_wp_element_["createElement"])(external_wp_components_["ToolbarGroup"], null, Object(external_wp_element_["createElement"])(external_wp_components_["ToolbarButton"], {
    "aria-label": Object(external_wp_i18n_["sprintf"])( // translators: %s: the name of a menu.
    Object(external_wp_i18n_["__"])(`Edit menu name: %s`), menuName),
    onClick: () => {
      enableComplementaryArea(SIDEBAR_SCOPE, SIDEBAR_MENU);
      setIsMenuNameEditFocused(true);
    }
  }, menuName)));
}
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/filters/add-menu-name-editor.js


/**
 * WordPress dependencies
 */

/**
 * Internal dependencies
 */



const addMenuNameEditor = Object(external_wp_compose_["createHigherOrderComponent"])(BlockEdit => props => {
  if (props.name !== 'core/navigation') {
    return Object(external_wp_element_["createElement"])(BlockEdit, props);
  }

  return Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, Object(external_wp_element_["createElement"])(NameDisplay, null), Object(external_wp_element_["createElement"])(BlockEdit, props));
}, 'withMenuName');
/* harmony default export */ var add_menu_name_editor = (() => Object(external_wp_hooks_["addFilter"])('editor.BlockEdit', 'core/edit-navigation/with-menu-name', addMenuNameEditor));
//# sourceMappingURL=add-menu-name-editor.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/filters/disable-inserting-non-navigation-blocks.js
/**
 * WordPress dependencies
 */

/**
 * External dependencies
 */



function disableInsertingNonNavigationBlocks(settings, name) {
  if (!['core/navigation', 'core/navigation-link'].includes(name)) {
    Object(external_lodash_["set"])(settings, ['supports', 'inserter'], false);
  }

  return settings;
}

/* harmony default export */ var disable_inserting_non_navigation_blocks = (() => Object(external_wp_hooks_["addFilter"])('blocks.registerBlockType', 'core/edit-navigation/disable-inserting-non-navigation-blocks', disableInsertingNonNavigationBlocks));
//# sourceMappingURL=disable-inserting-non-navigation-blocks.js.map
// EXTERNAL MODULE: ./node_modules/@babel/runtime/helpers/esm/extends.js
var esm_extends = __webpack_require__(7);

// CONCATENATED MODULE: ./packages/edit-navigation/build-module/filters/remove-edit-unsupported-features.js



/**
 * WordPress dependencies
 */


const removeNavigationBlockEditUnsupportedFeatures = Object(external_wp_compose_["createHigherOrderComponent"])(BlockEdit => props => {
  if (props.name !== 'core/navigation') {
    return Object(external_wp_element_["createElement"])(BlockEdit, props);
  }

  return Object(external_wp_element_["createElement"])(BlockEdit, Object(esm_extends["a" /* default */])({}, props, {
    hasSubmenuIndicatorSetting: false,
    hasItemJustificationControls: false,
    hasColorSettings: false
  }));
}, 'removeNavigationBlockEditUnsupportedFeatures');
/* harmony default export */ var remove_edit_unsupported_features = (() => Object(external_wp_hooks_["addFilter"])('editor.BlockEdit', 'core/edit-navigation/remove-navigation-block-edit-unsupported-features', removeNavigationBlockEditUnsupportedFeatures));
//# sourceMappingURL=remove-edit-unsupported-features.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/filters/remove-settings-unsupported-features.js
/**
 * WordPress dependencies
 */


function removeNavigationBlockSettingsUnsupportedFeatures(settings, name) {
  if (name !== 'core/navigation') {
    return settings;
  }

  return { ...settings,
    supports: {
      customClassName: false,
      html: false,
      inserter: true
    },
    // Remove any block variations.
    variations: undefined
  };
}

/* harmony default export */ var remove_settings_unsupported_features = (() => Object(external_wp_hooks_["addFilter"])('blocks.registerBlockType', 'core/edit-navigation/remove-navigation-block-settings-unsupported-features', removeNavigationBlockSettingsUnsupportedFeatures));
//# sourceMappingURL=remove-settings-unsupported-features.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/filters/index.js
/**
 * Internal dependencies
 */




const addFilters = shouldAddDisableInsertingNonNavigationBlocksFilter => {
  add_menu_name_editor();

  if (shouldAddDisableInsertingNonNavigationBlocksFilter) {
    disable_inserting_non_navigation_blocks();
  }

  remove_edit_unsupported_features();
  remove_settings_unsupported_features();
};
//# sourceMappingURL=index.js.map
// EXTERNAL MODULE: ./node_modules/classnames/index.js
var classnames = __webpack_require__(10);
var classnames_default = /*#__PURE__*/__webpack_require__.n(classnames);

// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/add-menu/index.js


/**
 * External dependencies
 */


/**
 * WordPress dependencies
 */









const menuNameMatches = menuName => menu => menu.name.toLowerCase() === menuName.toLowerCase();

function AddMenu({
  className,
  menus,
  onCreate,
  titleText,
  helpText,
  focusInputOnMount = false
}) {
  const [menuName, setMenuName] = Object(external_wp_element_["useState"])('');
  const {
    createErrorNotice,
    createInfoNotice,
    removeNotice
  } = Object(external_wp_data_["useDispatch"])(external_wp_notices_["store"]);
  const [isCreatingMenu, setIsCreatingMenu] = Object(external_wp_element_["useState"])(false);
  const {
    saveMenu
  } = Object(external_wp_data_["useDispatch"])(external_wp_coreData_["store"]);
  const inputRef = Object(external_wp_compose_["useFocusOnMount"])(focusInputOnMount);

  const createMenu = async event => {
    event.preventDefault();

    if (!menuName.length) {
      return;
    } // Remove any existing notices so duplicates aren't created.


    removeNotice('edit-navigation-error');

    if (Object(external_lodash_["some"])(menus, menuNameMatches(menuName))) {
      const message = Object(external_wp_i18n_["sprintf"])( // translators: %s: the name of a menu.
      Object(external_wp_i18n_["__"])('The menu name %s conflicts with another menu name. Please try another.'), menuName);
      createErrorNotice(message, {
        id: 'edit-navigation-error'
      });
      return;
    }

    setIsCreatingMenu(true);
    const menu = await saveMenu({
      name: menuName
    });

    if (menu) {
      createInfoNotice(Object(external_wp_i18n_["__"])('Menu created'), {
        type: 'snackbar',
        isDismissible: true
      });

      if (onCreate) {
        onCreate(menu.id);
      }
    }

    setIsCreatingMenu(false);
  };

  return Object(external_wp_element_["createElement"])("form", {
    className: classnames_default()('edit-navigation-add-menu', className),
    onSubmit: createMenu
  }, titleText && Object(external_wp_element_["createElement"])("h3", {
    className: "edit-navigation-add-menu__title"
  }, titleText), Object(external_wp_element_["createElement"])(external_wp_components_["TextControl"], {
    ref: inputRef,
    label: Object(external_wp_i18n_["__"])('Menu name'),
    value: menuName,
    onChange: setMenuName,
    help: helpText
  }), Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
    className: "edit-navigation-add-menu__create-menu-button",
    type: "submit",
    variant: "primary",
    disabled: !menuName.length,
    isBusy: isCreatingMenu
  }, Object(external_wp_i18n_["__"])('Create menu')));
}
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/menu-switcher/index.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */


function MenuSwitcher({
  menus,
  selectedMenuId,
  onSelectMenu = external_lodash_["noop"]
}) {
  const [isModalVisible, setIsModalVisible] = Object(external_wp_element_["useState"])(false);

  const openModal = () => setIsModalVisible(true);

  const closeModal = () => setIsModalVisible(false);

  return Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, Object(external_wp_element_["createElement"])(external_wp_components_["MenuGroup"], null, Object(external_wp_element_["createElement"])(external_wp_components_["MenuItemsChoice"], {
    value: selectedMenuId,
    onSelect: onSelectMenu,
    choices: menus.map(({
      id,
      name
    }) => ({
      value: id,
      label: name,
      'aria-label': Object(external_wp_i18n_["sprintf"])(
      /* translators: %s: The name of a menu. */
      Object(external_wp_i18n_["__"])("Switch to '%s'"), name)
    }))
  })), Object(external_wp_element_["createElement"])(external_wp_components_["MenuGroup"], {
    hideSeparator: true
  }, Object(external_wp_element_["createElement"])(external_wp_components_["MenuItem"], {
    variant: "primary",
    onClick: openModal
  }, Object(external_wp_i18n_["__"])('Create a new menu')), isModalVisible && Object(external_wp_element_["createElement"])(external_wp_components_["Modal"], {
    title: Object(external_wp_i18n_["__"])('Create a new menu'),
    onRequestClose: closeModal
  }, Object(external_wp_element_["createElement"])(AddMenu, {
    className: "edit-navigation-menu-switcher__add-menu",
    menus: menus,
    onCreate: menuId => {
      closeModal();
      onSelectMenu(menuId);
    },
    helpText: Object(external_wp_i18n_["__"])('A short descriptive name for your menu.')
  }))));
}
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/layout/unselected-menu-state.js


/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */



function UnselectedMenuState({
  onCreate,
  onSelectMenu,
  menus
}) {
  const showMenuSwitcher = (menus === null || menus === void 0 ? void 0 : menus.length) > 0;
  return Object(external_wp_element_["createElement"])("div", {
    className: "edit-navigation-empty-state"
  }, showMenuSwitcher && Object(external_wp_element_["createElement"])("h4", null, Object(external_wp_i18n_["__"])('Choose a menu to edit:')), Object(external_wp_element_["createElement"])(external_wp_components_["Card"], null, Object(external_wp_element_["createElement"])(external_wp_components_["CardBody"], null, showMenuSwitcher ? Object(external_wp_element_["createElement"])(external_wp_components_["NavigableMenu"], null, Object(external_wp_element_["createElement"])(MenuSwitcher, {
    onSelectMenu: onSelectMenu,
    menus: menus
  })) : Object(external_wp_element_["createElement"])(AddMenu, {
    onCreate: onCreate,
    titleText: Object(external_wp_i18n_["__"])('Create your first menu'),
    helpText: Object(external_wp_i18n_["__"])('A short descriptive name for your menu.'),
    focusInputOnMount: true
  }))));
}
//# sourceMappingURL=unselected-menu-state.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/error-boundary/index.js


/**
 * WordPress dependencies
 */





class error_boundary_ErrorBoundary extends external_wp_element_["Component"] {
  constructor() {
    super(...arguments);
    this.reboot = this.reboot.bind(this);
    this.state = {
      error: null
    };
  }

  componentDidCatch(error) {
    this.setState({
      error
    });
  }

  reboot() {
    if (this.props.onError) {
      this.props.onError();
    }
  }

  render() {
    const {
      error
    } = this.state;

    if (!error) {
      return this.props.children;
    }

    return Object(external_wp_element_["createElement"])(external_wp_blockEditor_["Warning"], {
      className: "navigation-editor-error-boundary",
      actions: [Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
        key: "recovery",
        onClick: this.reboot,
        variant: "secondary"
      }, Object(external_wp_i18n_["__"])('Attempt Recovery'))]
    }, Object(external_wp_i18n_["__"])('The navigation editor has encountered an unexpected error.'));
  }

}

/* harmony default export */ var error_boundary = (error_boundary_ErrorBoundary);
//# sourceMappingURL=index.js.map
// EXTERNAL MODULE: external ["wp","keyboardShortcuts"]
var external_wp_keyboardShortcuts_ = __webpack_require__(27);

// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/layout/shortcuts.js
/**
 * WordPress dependencies
 */






function NavigationEditorShortcuts({
  saveBlocks
}) {
  Object(external_wp_keyboardShortcuts_["useShortcut"])('core/edit-navigation/save-menu', Object(external_wp_element_["useCallback"])(event => {
    event.preventDefault();
    saveBlocks();
  }), {
    bindGlobal: true
  });
  const {
    redo,
    undo
  } = Object(external_wp_data_["useDispatch"])(external_wp_coreData_["store"]);
  Object(external_wp_keyboardShortcuts_["useShortcut"])('core/edit-navigation/undo', event => {
    undo();
    event.preventDefault();
  }, {
    bindGlobal: true
  });
  Object(external_wp_keyboardShortcuts_["useShortcut"])('core/edit-navigation/redo', event => {
    redo();
    event.preventDefault();
  }, {
    bindGlobal: true
  });
  return null;
}

function RegisterNavigationEditorShortcuts() {
  const {
    registerShortcut
  } = Object(external_wp_data_["useDispatch"])(external_wp_keyboardShortcuts_["store"]);
  Object(external_wp_element_["useEffect"])(() => {
    registerShortcut({
      name: 'core/edit-navigation/save-menu',
      category: 'global',
      description: Object(external_wp_i18n_["__"])('Save the navigation currently being edited.'),
      keyCombination: {
        modifier: 'primary',
        character: 's'
      }
    });
    registerShortcut({
      name: 'core/edit-navigation/undo',
      category: 'global',
      description: Object(external_wp_i18n_["__"])('Undo your last changes.'),
      keyCombination: {
        modifier: 'primary',
        character: 'z'
      }
    });
    registerShortcut({
      name: 'core/edit-navigation/redo',
      category: 'global',
      description: Object(external_wp_i18n_["__"])('Redo your last undo.'),
      keyCombination: {
        modifier: 'primaryShift',
        character: 'z'
      }
    });
  }, [registerShortcut]);
  return null;
}

NavigationEditorShortcuts.Register = RegisterNavigationEditorShortcuts;
/* harmony default export */ var shortcuts = (NavigationEditorShortcuts);
//# sourceMappingURL=shortcuts.js.map
// EXTERNAL MODULE: ./packages/icons/build-module/library/cog.js
var cog = __webpack_require__(270);

// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/sidebar/sidebar-header.js


/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */


function SidebarHeader({
  sidebarName
}) {
  const {
    enableComplementaryArea
  } = Object(external_wp_data_["useDispatch"])(build_module["g" /* store */]);

  const openMenuSettings = () => enableComplementaryArea(SIDEBAR_SCOPE, SIDEBAR_MENU);

  const openBlockSettings = () => enableComplementaryArea(SIDEBAR_SCOPE, SIDEBAR_BLOCK);

  const [menuAriaLabel, menuActiveClass] = sidebarName === SIDEBAR_MENU ? // translators: ARIA label for the Menu sidebar tab, selected.
  [Object(external_wp_i18n_["__"])('Menu (selected)'), 'is-active'] : // translators: ARIA label for the Menu Settings Sidebar tab, not selected.
  [Object(external_wp_i18n_["__"])('Menu'), ''];
  const [blockAriaLabel, blockActiveClass] = sidebarName === SIDEBAR_BLOCK ? // translators: ARIA label for the Block Settings Sidebar tab, selected.
  [Object(external_wp_i18n_["__"])('Block (selected)'), 'is-active'] : // translators: ARIA label for the Block Settings Sidebar tab, not selected.
  [Object(external_wp_i18n_["__"])('Block'), ''];
  /* Use a list so screen readers will announce how many tabs there are. */

  return Object(external_wp_element_["createElement"])("ul", null, Object(external_wp_element_["createElement"])("li", null, Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
    onClick: openMenuSettings,
    className: `edit-navigation-sidebar__panel-tab ${menuActiveClass}`,
    "aria-label": menuAriaLabel // translators: Data label for the Menu Settings Sidebar tab.
    ,
    "data-label": Object(external_wp_i18n_["__"])('Menu')
  }, // translators: Text label for the Menu Settings Sidebar tab.
  Object(external_wp_i18n_["__"])('Menu'))), Object(external_wp_element_["createElement"])("li", null, Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
    onClick: openBlockSettings,
    className: `edit-navigation-sidebar__panel-tab ${blockActiveClass}`,
    "aria-label": blockAriaLabel // translators: Data label for the Block Settings Sidebar tab.
    ,
    "data-label": Object(external_wp_i18n_["__"])('Block')
  }, // translators: Text label for the Block Settings Sidebar tab.
  Object(external_wp_i18n_["__"])('Block'))));
}
//# sourceMappingURL=sidebar-header.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/name-editor/index.js


/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */


function NameEditor() {
  const [isMenuNameEditFocused, setIsMenuNameEditFocused] = Object(external_wp_element_["useContext"])(IsMenuNameControlFocusedContext);
  const [menuId] = useSelectedMenuId();
  const [name, setName] = useMenuEntityProp('name', menuId);
  const inputRef = Object(external_wp_element_["useRef"])();
  Object(external_wp_element_["useEffect"])(() => {
    if (isMenuNameEditFocused) inputRef.current.focus();
  }, [isMenuNameEditFocused]);
  return Object(external_wp_element_["createElement"])(external_wp_components_["TextControl"], {
    ref: inputRef,
    help: Object(external_wp_i18n_["__"])('A short, descriptive name used to refer to this menu elsewhere.'),
    label: Object(external_wp_i18n_["__"])('Name'),
    onBlur: () => setIsMenuNameEditFocused(false),
    className: "edit-navigation-name-editor__text-control",
    value: name || '',
    onChange: setName
  });
}
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/sidebar/auto-add-pages.js


/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */


function AutoAddPages({
  menuId
}) {
  const [autoAddPages, setAutoAddPages] = useMenuEntityProp('auto_add', menuId);
  return Object(external_wp_element_["createElement"])(external_wp_components_["ToggleControl"], {
    label: Object(external_wp_i18n_["__"])('Add new pages'),
    help: Object(external_wp_i18n_["__"])('Automatically add published top-level pages to this menu.'),
    checked: autoAddPages !== null && autoAddPages !== void 0 ? autoAddPages : false,
    onChange: setAutoAddPages
  });
}
//# sourceMappingURL=auto-add-pages.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/sidebar/menu-settings.js


/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */



function MenuSettings({
  menuId
}) {
  return Object(external_wp_element_["createElement"])(external_wp_components_["PanelBody"], {
    title: Object(external_wp_i18n_["__"])('Menu settings')
  }, Object(external_wp_element_["createElement"])(NameEditor, null), Object(external_wp_element_["createElement"])(AutoAddPages, {
    menuId: menuId
  }));
}
//# sourceMappingURL=menu-settings.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/sidebar/manage-locations.js


/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */


function ManageLocations({
  menus,
  selectedMenuId,
  onSelectMenu
}) {
  const {
    menuLocations,
    assignMenuToLocation,
    toggleMenuLocationAssignment
  } = useMenuLocations();
  const [isModalOpen, setIsModalOpen] = Object(external_wp_element_["useState"])(false);

  const openModal = () => setIsModalOpen(true);

  const closeModal = () => setIsModalOpen(false);

  if (!menuLocations || !(menus !== null && menus !== void 0 && menus.length)) {
    return Object(external_wp_element_["createElement"])(external_wp_components_["Spinner"], null);
  }

  if (!menuLocations.length) {
    return Object(external_wp_element_["createElement"])(external_wp_components_["PanelBody"], {
      title: Object(external_wp_i18n_["__"])('Theme locations')
    }, Object(external_wp_element_["createElement"])("p", null, Object(external_wp_i18n_["__"])('There are no available menu locations.')));
  }

  const themeLocationCountTextMain = Object(external_wp_i18n_["sprintf"])( // translators: Number of available theme locations.
  Object(external_wp_i18n_["__"])('Your current theme provides %d different locations to place menu.'), menuLocations.length);
  const themeLocationCountTextModal = Object(external_wp_i18n_["sprintf"])( // translators: Number of available theme locations.
  Object(external_wp_i18n_["__"])('Your current theme supports %d different locations. Select which menu appears in each location.'), menuLocations.length);
  const menusWithSelection = menuLocations.map(({
    name,
    description,
    menu
  }) => {
    const menuOnLocation = menus.filter(({
      id
    }) => ![0, selectedMenuId].includes(id)).find(({
      id
    }) => id === menu);
    return Object(external_wp_element_["createElement"])("li", {
      key: name,
      className: "edit-navigation-manage-locations__checklist-item"
    }, Object(external_wp_element_["createElement"])(external_wp_components_["CheckboxControl"], {
      className: "edit-navigation-manage-locations__menu-location-checkbox",
      checked: menu === selectedMenuId,
      onChange: () => toggleMenuLocationAssignment(name, selectedMenuId),
      label: description,
      help: menuOnLocation && Object(external_wp_i18n_["sprintf"])( // translators: menu name.
      Object(external_wp_i18n_["__"])('Currently using %s'), menuOnLocation.name)
    }));
  });
  const menuLocationCard = menuLocations.map(menuLocation => Object(external_wp_element_["createElement"])("div", {
    key: menuLocation.name,
    className: "edit-navigation-manage-locations__menu-entry"
  }, Object(external_wp_element_["createElement"])(external_wp_components_["SelectControl"], {
    key: menuLocation.name,
    className: "edit-navigation-manage-locations__select-menu",
    label: menuLocation.description,
    labelPosition: "top",
    value: menuLocation.menu,
    options: [{
      value: 0,
      label: Object(external_wp_i18n_["__"])('-'),
      key: 0
    }, ...menus.map(({
      id,
      name
    }) => ({
      key: id,
      value: id,
      label: name
    }))],
    onChange: menuId => {
      assignMenuToLocation(menuLocation.name, Number(menuId));
    }
  }), Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
    variant: "secondary",
    style: {
      visibility: !!menuLocation.menu ? 'visible' : 'hidden'
    },
    className: "edit-navigation-manage-locations__edit-button",
    onClick: () => (closeModal(), onSelectMenu(menuLocation.menu))
  }, Object(external_wp_i18n_["__"])('Edit'))));
  return Object(external_wp_element_["createElement"])(external_wp_components_["PanelBody"], {
    title: Object(external_wp_i18n_["__"])('Theme locations')
  }, Object(external_wp_element_["createElement"])("div", {
    className: "edit-navigation-manage-locations__theme-location-text-main"
  }, themeLocationCountTextMain), Object(external_wp_element_["createElement"])("ul", {
    className: "edit-navigation-manage-locations__checklist"
  }, menusWithSelection), Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
    variant: "secondary",
    className: "edit-navigation-manage-locations__open-menu-locations-modal-button",
    "aria-expanded": isModalOpen,
    onClick: openModal
  }, Object(external_wp_i18n_["__"])('Manage locations')), isModalOpen && Object(external_wp_element_["createElement"])(external_wp_components_["Modal"], {
    className: "edit-navigation-manage-locations__modal",
    title: Object(external_wp_i18n_["__"])('Manage locations'),
    onRequestClose: closeModal
  }, Object(external_wp_element_["createElement"])("div", {
    className: "edit-navigation-manage-locations__theme-location-text-modal"
  }, themeLocationCountTextModal), menuLocationCard));
}
//# sourceMappingURL=manage-locations.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/sidebar/delete-menu.js


/**
 * WordPress dependencies
 */


function DeleteMenu({
  onDeleteMenu,
  isMenuBeingDeleted
}) {
  return Object(external_wp_element_["createElement"])(external_wp_components_["PanelBody"], null, Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
    className: "edit-navigation-inspector-additions__delete-menu-button",
    variant: "secondary",
    isDestructive: true,
    isBusy: isMenuBeingDeleted,
    onClick: () => {
      if ( // eslint-disable-next-line no-alert
      window.confirm(Object(external_wp_i18n_["__"])('Are you sure you want to delete this navigation? This action cannot be undone.'))) {
        onDeleteMenu();
      }
    }
  }, Object(external_wp_i18n_["__"])('Delete menu')));
}
//# sourceMappingURL=delete-menu.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/sidebar/index.js


/**
 * WordPress dependencies
 */






/**
 * Internal dependencies
 */






function Sidebar({
  menuId,
  menus,
  isMenuBeingDeleted,
  onDeleteMenu,
  onSelectMenu,
  hasPermanentSidebar
}) {
  const {
    sidebar,
    hasBlockSelection,
    hasSidebarEnabled
  } = Object(external_wp_data_["useSelect"])(select => {
    const _sidebar = select(build_module["g" /* store */]).getActiveComplementaryArea(SIDEBAR_SCOPE);

    const _hasSidebarEnabled = [SIDEBAR_MENU, SIDEBAR_BLOCK].includes(_sidebar);

    return {
      sidebar: _sidebar,
      hasBlockSelection: !!select(external_wp_blockEditor_["store"]).getBlockSelectionStart(),
      hasSidebarEnabled: _hasSidebarEnabled
    };
  }, []);
  const {
    enableComplementaryArea
  } = Object(external_wp_data_["useDispatch"])(build_module["g" /* store */]);
  Object(external_wp_element_["useEffect"])(() => {
    if (!hasSidebarEnabled) {
      return;
    }

    if (hasBlockSelection) {
      enableComplementaryArea(SIDEBAR_SCOPE, SIDEBAR_BLOCK);
    } else {
      enableComplementaryArea(SIDEBAR_SCOPE, SIDEBAR_MENU);
    }
  }, [hasBlockSelection, hasSidebarEnabled]);
  let sidebarName = sidebar;

  if (!hasSidebarEnabled) {
    sidebarName = hasBlockSelection ? SIDEBAR_BLOCK : SIDEBAR_MENU;
  }

  return Object(external_wp_element_["createElement"])(build_module["b" /* ComplementaryArea */], {
    className: "edit-navigation-sidebar"
    /* translators: button label text should, if possible, be under 16 characters. */
    ,
    title: Object(external_wp_i18n_["__"])('Settings'),
    closeLabel: Object(external_wp_i18n_["__"])('Close settings'),
    scope: SIDEBAR_SCOPE,
    identifier: sidebarName,
    icon: cog["a" /* default */],
    isActiveByDefault: hasPermanentSidebar,
    header: Object(external_wp_element_["createElement"])(SidebarHeader, {
      sidebarName: sidebarName
    }),
    headerClassName: "edit-navigation-sidebar__panel-tabs",
    isPinnable: !hasPermanentSidebar
  }, sidebarName === SIDEBAR_MENU && Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, Object(external_wp_element_["createElement"])(MenuSettings, {
    menuId: menuId
  }), Object(external_wp_element_["createElement"])(ManageLocations, {
    menus: menus,
    selectedMenuId: menuId,
    onSelectMenu: onSelectMenu
  }), Object(external_wp_element_["createElement"])(DeleteMenu, {
    onDeleteMenu: onDeleteMenu,
    isMenuBeingDeleted: isMenuBeingDeleted
  })), sidebarName === SIDEBAR_BLOCK && Object(external_wp_element_["createElement"])(external_wp_blockEditor_["BlockInspector"], null));
}
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/header/save-button.js


/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */


function SaveButton({
  navigationPost
}) {
  const {
    isDirty
  } = Object(external_wp_data_["useSelect"])(select => {
    const {
      __experimentalGetDirtyEntityRecords
    } = select(external_wp_coreData_["store"]);

    const dirtyEntityRecords = __experimentalGetDirtyEntityRecords();

    return {
      isDirty: dirtyEntityRecords.length > 0
    };
  }, []);
  const {
    saveNavigationPost
  } = Object(external_wp_data_["useDispatch"])(store);
  const disabled = !isDirty || !navigationPost;
  return Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
    className: "edit-navigation-toolbar__save-button",
    variant: "primary",
    onClick: () => {
      saveNavigationPost(navigationPost);
    },
    disabled: disabled
  }, Object(external_wp_i18n_["__"])('Save'));
}
//# sourceMappingURL=save-button.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/header/index.js


/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */




function Header({
  isMenuSelected,
  menus,
  selectedMenuId,
  onSelectMenu,
  isPending,
  navigationPost
}) {
  const [menuName] = useMenuEntityProp('name', selectedMenuId);
  let actionHeaderText;

  if (menuName) {
    actionHeaderText = Object(external_wp_i18n_["sprintf"])( // translators: Name of the menu being edited, e.g. 'Main Menu'.
    Object(external_wp_i18n_["__"])('Editing: %s'), menuName);
  } else if (isPending) {
    // Loading text won't be displayed if menus are preloaded.
    actionHeaderText = Object(external_wp_i18n_["__"])('Loading …');
  } else {
    actionHeaderText = Object(external_wp_i18n_["__"])('No menus available');
  }

  return Object(external_wp_element_["createElement"])("div", {
    className: "edit-navigation-header"
  }, Object(external_wp_element_["createElement"])("div", {
    className: "edit-navigation-header__title-subtitle"
  }, Object(external_wp_element_["createElement"])("h1", {
    className: "edit-navigation-header__title"
  }, Object(external_wp_i18n_["__"])('Navigation')), Object(external_wp_element_["createElement"])("h2", {
    className: "edit-navigation-header__subtitle"
  }, isMenuSelected && actionHeaderText)), isMenuSelected && Object(external_wp_element_["createElement"])("div", {
    className: "edit-navigation-header__actions"
  }, Object(external_wp_element_["createElement"])(external_wp_components_["DropdownMenu"], {
    icon: null,
    toggleProps: {
      children: Object(external_wp_i18n_["__"])('Switch menu'),
      'aria-label': Object(external_wp_i18n_["__"])('Switch menu, or create a new menu'),
      showTooltip: false,
      variant: 'tertiary',
      disabled: !(menus !== null && menus !== void 0 && menus.length),
      __experimentalIsFocusable: true
    },
    popoverProps: {
      className: 'edit-navigation-header__menu-switcher-dropdown',
      position: 'bottom center'
    }
  }, ({
    onClose
  }) => Object(external_wp_element_["createElement"])(MenuSwitcher, {
    menus: menus,
    selectedMenuId: selectedMenuId,
    onSelectMenu: menuId => {
      onSelectMenu(menuId);
      onClose();
    }
  })), Object(external_wp_element_["createElement"])(SaveButton, {
    navigationPost: navigationPost
  }), Object(external_wp_element_["createElement"])(build_module["f" /* PinnedItems */].Slot, {
    scope: "core/edit-navigation"
  })));
}
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/notices/index.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */




function EditNavigationNotices() {
  const {
    removeNotice
  } = Object(external_wp_data_["useDispatch"])(external_wp_notices_["store"]);
  const notices = Object(external_wp_data_["useSelect"])(select => select(external_wp_notices_["store"]).getNotices(), []);
  const dismissibleNotices = Object(external_lodash_["filter"])(notices, {
    isDismissible: true,
    type: 'default'
  });
  const nonDismissibleNotices = Object(external_lodash_["filter"])(notices, {
    isDismissible: false,
    type: 'default'
  });
  const snackbarNotices = Object(external_lodash_["filter"])(notices, {
    type: 'snackbar'
  });
  return Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, Object(external_wp_element_["createElement"])(external_wp_components_["NoticeList"], {
    notices: nonDismissibleNotices,
    className: "edit-navigation-notices__notice-list"
  }), Object(external_wp_element_["createElement"])(external_wp_components_["NoticeList"], {
    notices: dismissibleNotices,
    className: "edit-navigation-notices__notice-list",
    onRemove: removeNotice
  }), Object(external_wp_element_["createElement"])(external_wp_components_["SnackbarList"], {
    notices: snackbarNotices,
    className: "edit-navigation-notices__snackbar-list",
    onRemove: removeNotice
  }));
}
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/editor/index.js


/**
 * WordPress dependencies
 */


function Editor({
  isPending
}) {
  return Object(external_wp_element_["createElement"])("div", {
    className: "edit-navigation-editor"
  }, isPending ? Object(external_wp_element_["createElement"])(external_wp_components_["Spinner"], null) : Object(external_wp_element_["createElement"])("div", {
    className: "editor-styles-wrapper"
  }, Object(external_wp_element_["createElement"])(external_wp_blockEditor_["WritingFlow"], null, Object(external_wp_element_["createElement"])(external_wp_blockEditor_["ObserveTyping"], null, Object(external_wp_element_["createElement"])(external_wp_blockEditor_["BlockList"], null)))));
}
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/layout/unsaved-changes-warning.js
/**
 * WordPress dependencies
 */




/**
 * Warns the user if there are unsaved changes before leaving the editor.
 *
 * This is a duplicate of the component implemented in the editor package.
 * Duplicated here as edit-navigation doesn't depend on editor.
 *
 * @return {WPComponent} The component.
 */

function UnsavedChangesWarning() {
  const isDirty = Object(external_wp_data_["useSelect"])(select => {
    const {
      __experimentalGetDirtyEntityRecords
    } = select(external_wp_coreData_["store"]);

    const dirtyEntityRecords = __experimentalGetDirtyEntityRecords();

    return dirtyEntityRecords.length > 0;
  }, []);
  Object(external_wp_element_["useEffect"])(() => {
    /**
     * Warns the user if there are unsaved changes before leaving the editor.
     *
     * @param {Event} event `beforeunload` event.
     *
     * @return {?string} Warning prompt message, if unsaved changes exist.
     */
    const warnIfUnsavedChanges = event => {
      if (isDirty) {
        event.returnValue = Object(external_wp_i18n_["__"])('You have unsaved changes. If you proceed, they will be lost.');
        return event.returnValue;
      }
    };

    window.addEventListener('beforeunload', warnIfUnsavedChanges);
    return () => {
      window.removeEventListener('beforeunload', warnIfUnsavedChanges);
    };
  }, [isDirty]);
  return null;
}
//# sourceMappingURL=unsaved-changes-warning.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/layout/index.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */








/**
 * Internal dependencies
 */











const interfaceLabels = {
  /* translators: accessibility text for the navigation screen top bar landmark region. */
  header: Object(external_wp_i18n_["__"])('Navigation top bar'),

  /* translators: accessibility text for the navigation screen content landmark region. */
  body: Object(external_wp_i18n_["__"])('Navigation menu blocks'),

  /* translators: accessibility text for the navigation screen settings landmark region. */
  sidebar: Object(external_wp_i18n_["__"])('Navigation settings')
};
function Layout({
  blockEditorSettings
}) {
  const contentAreaRef = Object(external_wp_blockEditor_["__unstableUseBlockSelectionClearer"])();
  const isLargeViewport = Object(external_wp_compose_["useViewportMatch"])('medium');
  const [isMenuNameControlFocused, setIsMenuNameControlFocused] = Object(external_wp_element_["useState"])(false);
  const {
    saveNavigationPost
  } = Object(external_wp_data_["useDispatch"])(store);

  const savePost = () => saveNavigationPost(navigationPost);

  const {
    menus,
    hasLoadedMenus,
    hasFinishedInitialLoad,
    selectedMenuId,
    navigationPost,
    isMenuBeingDeleted,
    selectMenu,
    deleteMenu,
    isMenuSelected
  } = useNavigationEditor();
  const [blocks, onInput, onChange] = useNavigationBlockEditor(navigationPost);
  const {
    hasSidebarEnabled
  } = Object(external_wp_data_["useSelect"])(select => ({
    hasSidebarEnabled: !!select(build_module["g" /* store */]).getActiveComplementaryArea('core/edit-navigation')
  }), []);
  Object(external_wp_element_["useEffect"])(() => {
    if (!selectedMenuId && menus !== null && menus !== void 0 && menus.length) {
      selectMenu(menus[0].id);
    }
  }, [selectedMenuId, menus]);
  useMenuNotifications(selectedMenuId);
  const hasMenus = !!(menus !== null && menus !== void 0 && menus.length);
  const hasPermanentSidebar = isLargeViewport && isMenuSelected;
  const isBlockEditorReady = !!(hasMenus && navigationPost && isMenuSelected);
  return Object(external_wp_element_["createElement"])(error_boundary, null, Object(external_wp_element_["createElement"])("div", {
    hidden: !isMenuBeingDeleted,
    className: 'edit-navigation-layout__overlay'
  }), Object(external_wp_element_["createElement"])(external_wp_components_["SlotFillProvider"], null, Object(external_wp_element_["createElement"])(external_wp_blockEditor_["BlockEditorKeyboardShortcuts"].Register, null), Object(external_wp_element_["createElement"])(shortcuts.Register, null), Object(external_wp_element_["createElement"])(shortcuts, {
    saveBlocks: savePost
  }), Object(external_wp_element_["createElement"])(EditNavigationNotices, null), Object(external_wp_element_["createElement"])(external_wp_blockEditor_["BlockEditorProvider"], {
    value: blocks,
    onInput: onInput,
    onChange: onChange,
    settings: { ...blockEditorSettings,
      templateLock: 'all'
    },
    useSubRegistry: false
  }, Object(external_wp_element_["createElement"])(IsMenuNameControlFocusedContext.Provider, {
    value: Object(external_wp_element_["useMemo"])(() => [isMenuNameControlFocused, setIsMenuNameControlFocused], [isMenuNameControlFocused])
  }, Object(external_wp_element_["createElement"])(build_module["e" /* InterfaceSkeleton */], {
    className: classnames_default()('edit-navigation-layout', {
      'has-permanent-sidebar': hasPermanentSidebar
    }),
    labels: interfaceLabels,
    header: Object(external_wp_element_["createElement"])(Header, {
      isMenuSelected: isMenuSelected,
      isPending: !hasLoadedMenus,
      menus: menus,
      selectedMenuId: selectedMenuId,
      onSelectMenu: selectMenu,
      navigationPost: navigationPost
    }),
    content: Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, !hasFinishedInitialLoad && Object(external_wp_element_["createElement"])(external_wp_components_["Spinner"], null), !isMenuSelected && hasFinishedInitialLoad && Object(external_wp_element_["createElement"])(UnselectedMenuState, {
      onSelectMenu: selectMenu,
      onCreate: selectMenu,
      menus: menus
    }), isBlockEditorReady && Object(external_wp_element_["createElement"])("div", {
      className: "edit-navigation-layout__content-area",
      ref: contentAreaRef
    }, Object(external_wp_element_["createElement"])(external_wp_blockEditor_["BlockTools"], null, Object(external_wp_element_["createElement"])(Editor, {
      isPending: !hasLoadedMenus,
      blocks: blocks
    })))),
    sidebar: (hasPermanentSidebar || hasSidebarEnabled) && Object(external_wp_element_["createElement"])(build_module["b" /* ComplementaryArea */].Slot, {
      scope: "core/edit-navigation"
    })
  }), isMenuSelected && Object(external_wp_element_["createElement"])(Sidebar, {
    menus: menus,
    menuId: selectedMenuId,
    onSelectMenu: selectMenu,
    onDeleteMenu: deleteMenu,
    isMenuBeingDeleted: isMenuBeingDeleted,
    hasPermanentSidebar: hasPermanentSidebar
  })), Object(external_wp_element_["createElement"])(UnsavedChangesWarning, null)), Object(external_wp_element_["createElement"])(external_wp_components_["Popover"].Slot, null)));
}
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-navigation/build-module/index.js


/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */


/**
 * Internal dependencies
 */



function initialize(id, settings) {
  addFilters(!settings.blockNavMenus);
  Object(external_wp_blockLibrary_["registerCoreBlocks"])();

  if (true) {
    Object(external_wp_blockLibrary_["__experimentalRegisterExperimentalCoreBlocks"])();
  }

  settings.__experimentalFetchLinkSuggestions = (search, searchOptions) => Object(external_wp_coreData_["__experimentalFetchLinkSuggestions"])(search, searchOptions, settings);

  Object(external_wp_element_["render"])(Object(external_wp_element_["createElement"])(Layout, {
    blockEditorSettings: settings
  }), document.getElementById(id));
}
//# sourceMappingURL=index.js.map

/***/ }),

/***/ 6:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["primitives"]; }());

/***/ }),

/***/ 62:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["blockLibrary"]; }());

/***/ }),

/***/ 65:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["viewport"]; }());

/***/ }),

/***/ 7:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return _extends; });
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

/***/ }),

/***/ 8:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["blocks"]; }());

/***/ }),

/***/ 9:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["compose"]; }());

/***/ })

/******/ });