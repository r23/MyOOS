window["wp"] = window["wp"] || {}; window["wp"]["editSite"] =
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
/******/ 	return __webpack_require__(__webpack_require__.s = 499);
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

/***/ 113:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(0);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
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
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["cloneElement"])(icon, {
    width: size,
    height: size,
    ...props
  });
}

/* harmony default export */ __webpack_exports__["a"] = (Icon);
//# sourceMappingURL=index.js.map

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

/***/ 139:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(0);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(6);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__);


/**
 * WordPress dependencies
 */

const close = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["SVG"], {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["Path"], {
  d: "M13 11.8l6.1-6.3-1-1-6.1 6.2-6.1-6.2-1 1 6.1 6.3-6.5 6.7 1 1 6.5-6.6 6.5 6.6 1-1z"
}));
/* harmony default export */ __webpack_exports__["a"] = (close);
//# sourceMappingURL=close.js.map

/***/ }),

/***/ 14:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["keycodes"]; }());

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

/***/ 150:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(0);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(6);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__);


/**
 * WordPress dependencies
 */

const plus = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["SVG"], {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["Path"], {
  d: "M18 11.2h-5.2V6h-1.6v5.2H6v1.6h5.2V18h1.6v-5.2H18z"
}));
/* harmony default export */ __webpack_exports__["a"] = (plus);
//# sourceMappingURL=plus.js.map

/***/ }),

/***/ 18:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["editor"]; }());

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

/***/ 20:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["url"]; }());

/***/ }),

/***/ 200:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(0);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(6);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__);


/**
 * WordPress dependencies
 */

const chevronDown = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["SVG"], {
  viewBox: "0 0 24 24",
  xmlns: "http://www.w3.org/2000/svg"
}, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["Path"], {
  d: "M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"
}));
/* harmony default export */ __webpack_exports__["a"] = (chevronDown);
//# sourceMappingURL=chevron-down.js.map

/***/ }),

/***/ 201:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(0);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(6);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__);


/**
 * WordPress dependencies
 */

const moreVertical = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["SVG"], {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["Path"], {
  d: "M13 19h-2v-2h2v2zm0-6h-2v-2h2v2zm0-6h-2V5h2v2z"
}));
/* harmony default export */ __webpack_exports__["a"] = (moreVertical);
//# sourceMappingURL=more-vertical.js.map

/***/ }),

/***/ 21:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["hooks"]; }());

/***/ }),

/***/ 248:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(0);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(6);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__);


/**
 * WordPress dependencies
 */

const listView = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["SVG"], {
  viewBox: "0 0 24 24",
  xmlns: "http://www.w3.org/2000/svg"
}, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["Path"], {
  d: "M13.8 5.2H3v1.5h10.8V5.2zm-3.6 12v1.5H21v-1.5H10.2zm7.2-6H6.6v1.5h10.8v-1.5z"
}));
/* harmony default export */ __webpack_exports__["a"] = (listView);
//# sourceMappingURL=list-view.js.map

/***/ }),

/***/ 268:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(0);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(6);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__);


/**
 * WordPress dependencies
 */

const undo = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["SVG"], {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["Path"], {
  d: "M18.3 11.7c-.6-.6-1.4-.9-2.3-.9H6.7l2.9-3.3-1.1-1-4.5 5L8.5 16l1-1-2.7-2.7H16c.5 0 .9.2 1.3.5 1 1 1 3.4 1 4.5v.3h1.5v-.2c0-1.5 0-4.3-1.5-5.7z"
}));
/* harmony default export */ __webpack_exports__["a"] = (undo);
//# sourceMappingURL=undo.js.map

/***/ }),

/***/ 269:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(0);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(6);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__);


/**
 * WordPress dependencies
 */

const redo = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["SVG"], {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["Path"], {
  d: "M15.6 6.5l-1.1 1 2.9 3.3H8c-.9 0-1.7.3-2.3.9-1.4 1.5-1.4 4.2-1.4 5.6v.2h1.5v-.3c0-1.1 0-3.5 1-4.5.3-.3.7-.5 1.3-.5h9.2L14.5 15l1.1 1.1 4.6-4.6-4.6-5z"
}));
/* harmony default export */ __webpack_exports__["a"] = (redo);
//# sourceMappingURL=redo.js.map

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

/***/ 271:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";

// EXTERNAL MODULE: external ["wp","element"]
var external_wp_element_ = __webpack_require__(0);

// EXTERNAL MODULE: external ["wp","primitives"]
var external_wp_primitives_ = __webpack_require__(6);

// CONCATENATED MODULE: ./packages/icons/build-module/library/pencil.js


/**
 * WordPress dependencies
 */

const pencil = Object(external_wp_element_["createElement"])(external_wp_primitives_["SVG"], {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, Object(external_wp_element_["createElement"])(external_wp_primitives_["Path"], {
  d: "M20.1 5.1L16.9 2 6.2 12.7l-1.3 4.4 4.5-1.3L20.1 5.1zM4 20.8h8v-1.5H4v1.5z"
}));
/* harmony default export */ var library_pencil = (pencil);
//# sourceMappingURL=pencil.js.map
// CONCATENATED MODULE: ./packages/icons/build-module/library/edit.js
/**
 * Internal dependencies
 */

/* harmony default export */ var edit = __webpack_exports__["a"] = (library_pencil);
//# sourceMappingURL=edit.js.map

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

/***/ 32:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";


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
/* harmony default export */ __webpack_exports__["a"] = (function( selector, getDependants ) {
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
});


/***/ }),

/***/ 329:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(0);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(6);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__);


/**
 * WordPress dependencies
 */

const wordpress = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["SVG"], {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "-2 -2 24 24"
}, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__["Path"], {
  d: "M20 10c0-5.51-4.49-10-10-10C4.48 0 0 4.49 0 10c0 5.52 4.48 10 10 10 5.51 0 10-4.48 10-10zM7.78 15.37L4.37 6.22c.55-.02 1.17-.08 1.17-.08.5-.06.44-1.13-.06-1.11 0 0-1.45.11-2.37.11-.18 0-.37 0-.58-.01C4.12 2.69 6.87 1.11 10 1.11c2.33 0 4.45.87 6.05 2.34-.68-.11-1.65.39-1.65 1.58 0 .74.45 1.36.9 2.1.35.61.55 1.36.55 2.46 0 1.49-1.4 5-1.4 5l-3.03-8.37c.54-.02.82-.17.82-.17.5-.05.44-1.25-.06-1.22 0 0-1.44.12-2.38.12-.87 0-2.33-.12-2.33-.12-.5-.03-.56 1.2-.06 1.22l.92.08 1.26 3.41zM17.41 10c.24-.64.74-1.87.43-4.25.7 1.29 1.05 2.71 1.05 4.25 0 3.29-1.73 6.24-4.4 7.78.97-2.59 1.94-5.2 2.92-7.78zM6.1 18.09C3.12 16.65 1.11 13.53 1.11 10c0-1.3.23-2.48.72-3.59C3.25 10.3 4.67 14.2 6.1 18.09zm4.03-6.63l2.58 6.98c-.86.29-1.76.45-2.71.45-.79 0-1.57-.11-2.29-.33.81-2.38 1.62-4.74 2.42-7.1z"
}));
/* harmony default export */ __webpack_exports__["a"] = (wordpress);
//# sourceMappingURL=wordpress.js.map

/***/ }),

/***/ 33:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["a11y"]; }());

/***/ }),

/***/ 34:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["apiFetch"]; }());

/***/ }),

/***/ 358:
/***/ (function(module, exports, __webpack_require__) {

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


/***/ }),

/***/ 36:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["dataControls"]; }());

/***/ }),

/***/ 4:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["data"]; }());

/***/ }),

/***/ 40:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["htmlEntities"]; }());

/***/ }),

/***/ 49:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["plugins"]; }());

/***/ }),

/***/ 499:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXPORTS
__webpack_require__.d(__webpack_exports__, "initialize", function() { return /* binding */ initialize; });
__webpack_require__.d(__webpack_exports__, "__experimentalMainDashboardButton", function() { return /* reexport */ main_dashboard_button; });
__webpack_require__.d(__webpack_exports__, "__experimentalNavigationToggle", function() { return /* reexport */ navigation_toggle; });

// NAMESPACE OBJECT: ./packages/edit-site/build-module/store/actions.js
var actions_namespaceObject = {};
__webpack_require__.r(actions_namespaceObject);
__webpack_require__.d(actions_namespaceObject, "toggleFeature", function() { return actions_toggleFeature; });
__webpack_require__.d(actions_namespaceObject, "__experimentalSetPreviewDeviceType", function() { return __experimentalSetPreviewDeviceType; });
__webpack_require__.d(actions_namespaceObject, "setTemplate", function() { return actions_setTemplate; });
__webpack_require__.d(actions_namespaceObject, "addTemplate", function() { return actions_addTemplate; });
__webpack_require__.d(actions_namespaceObject, "removeTemplate", function() { return removeTemplate; });
__webpack_require__.d(actions_namespaceObject, "setTemplatePart", function() { return actions_setTemplatePart; });
__webpack_require__.d(actions_namespaceObject, "setHomeTemplateId", function() { return setHomeTemplateId; });
__webpack_require__.d(actions_namespaceObject, "setPage", function() { return actions_setPage; });
__webpack_require__.d(actions_namespaceObject, "showHomepage", function() { return actions_showHomepage; });
__webpack_require__.d(actions_namespaceObject, "setNavigationPanelActiveMenu", function() { return setNavigationPanelActiveMenu; });
__webpack_require__.d(actions_namespaceObject, "openNavigationPanelToMenu", function() { return actions_openNavigationPanelToMenu; });
__webpack_require__.d(actions_namespaceObject, "setIsNavigationPanelOpened", function() { return actions_setIsNavigationPanelOpened; });
__webpack_require__.d(actions_namespaceObject, "setIsInserterOpened", function() { return actions_setIsInserterOpened; });
__webpack_require__.d(actions_namespaceObject, "updateSettings", function() { return actions_updateSettings; });
__webpack_require__.d(actions_namespaceObject, "setIsListViewOpened", function() { return actions_setIsListViewOpened; });
__webpack_require__.d(actions_namespaceObject, "revertTemplate", function() { return actions_revertTemplate; });

// NAMESPACE OBJECT: ./packages/edit-site/build-module/store/selectors.js
var selectors_namespaceObject = {};
__webpack_require__.r(selectors_namespaceObject);
__webpack_require__.d(selectors_namespaceObject, "isFeatureActive", function() { return isFeatureActive; });
__webpack_require__.d(selectors_namespaceObject, "__experimentalGetPreviewDeviceType", function() { return selectors_experimentalGetPreviewDeviceType; });
__webpack_require__.d(selectors_namespaceObject, "getCanUserCreateMedia", function() { return getCanUserCreateMedia; });
__webpack_require__.d(selectors_namespaceObject, "getSettings", function() { return selectors_getSettings; });
__webpack_require__.d(selectors_namespaceObject, "getHomeTemplateId", function() { return getHomeTemplateId; });
__webpack_require__.d(selectors_namespaceObject, "getEditedPostType", function() { return selectors_getEditedPostType; });
__webpack_require__.d(selectors_namespaceObject, "getEditedPostId", function() { return selectors_getEditedPostId; });
__webpack_require__.d(selectors_namespaceObject, "getPage", function() { return selectors_getPage; });
__webpack_require__.d(selectors_namespaceObject, "getNavigationPanelActiveMenu", function() { return selectors_getNavigationPanelActiveMenu; });
__webpack_require__.d(selectors_namespaceObject, "getCurrentTemplateNavigationPanelSubMenu", function() { return selectors_getCurrentTemplateNavigationPanelSubMenu; });
__webpack_require__.d(selectors_namespaceObject, "isNavigationOpened", function() { return selectors_isNavigationOpened; });
__webpack_require__.d(selectors_namespaceObject, "isInserterOpened", function() { return selectors_isInserterOpened; });
__webpack_require__.d(selectors_namespaceObject, "__experimentalGetInsertionPoint", function() { return __experimentalGetInsertionPoint; });
__webpack_require__.d(selectors_namespaceObject, "isListViewOpened", function() { return selectors_isListViewOpened; });

// EXTERNAL MODULE: external ["wp","element"]
var external_wp_element_ = __webpack_require__(0);

// EXTERNAL MODULE: external ["wp","blockLibrary"]
var external_wp_blockLibrary_ = __webpack_require__(62);

// EXTERNAL MODULE: external ["wp","coreData"]
var external_wp_coreData_ = __webpack_require__(12);

// EXTERNAL MODULE: ./node_modules/downloadjs/download.js
var download = __webpack_require__(358);
var download_default = /*#__PURE__*/__webpack_require__.n(download);

// EXTERNAL MODULE: external ["wp","components"]
var external_wp_components_ = __webpack_require__(3);

// EXTERNAL MODULE: external ["wp","i18n"]
var external_wp_i18n_ = __webpack_require__(1);

// EXTERNAL MODULE: external ["wp","plugins"]
var external_wp_plugins_ = __webpack_require__(49);

// EXTERNAL MODULE: external ["wp","apiFetch"]
var external_wp_apiFetch_ = __webpack_require__(34);
var external_wp_apiFetch_default = /*#__PURE__*/__webpack_require__.n(external_wp_apiFetch_);

// EXTERNAL MODULE: external ["wp","primitives"]
var external_wp_primitives_ = __webpack_require__(6);

// CONCATENATED MODULE: ./packages/icons/build-module/library/download.js


/**
 * WordPress dependencies
 */

const download_download = Object(external_wp_element_["createElement"])(external_wp_primitives_["SVG"], {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, Object(external_wp_element_["createElement"])(external_wp_primitives_["Path"], {
  d: "M18 11.3l-1-1.1-4 4V3h-1.5v11.3L7 10.2l-1 1.1 6.2 5.8 5.8-5.8zm.5 3.7v3.5h-13V15H4v5h16v-5h-1.5z"
}));
/* harmony default export */ var library_download = (download_download);
//# sourceMappingURL=download.js.map
// EXTERNAL MODULE: external "lodash"
var external_lodash_ = __webpack_require__(2);

// CONCATENATED MODULE: ./packages/edit-site/build-module/components/header/tools-more-menu-group/index.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */



const {
  Fill: ToolsMoreMenuGroup,
  Slot
} = Object(external_wp_components_["createSlotFill"])('ToolsMoreMenuGroup');

ToolsMoreMenuGroup.Slot = ({
  fillProps
}) => Object(external_wp_element_["createElement"])(Slot, {
  fillProps: fillProps
}, fills => !Object(external_lodash_["isEmpty"])(fills) && Object(external_wp_element_["createElement"])(external_wp_components_["MenuGroup"], {
  label: Object(external_wp_i18n_["__"])('Tools')
}, fills));

/* harmony default export */ var tools_more_menu_group = (ToolsMoreMenuGroup);
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/plugins/index.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */






/**
 * Internal dependencies
 */


Object(external_wp_plugins_["registerPlugin"])('edit-site', {
  render() {
    return Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, Object(external_wp_element_["createElement"])(tools_more_menu_group, null, Object(external_wp_element_["createElement"])(external_wp_components_["MenuItem"], {
      role: "menuitem",
      icon: library_download,
      onClick: () => external_wp_apiFetch_default()({
        path: '/__experimental/edit-site/v1/export',
        parse: false
      }).then(res => res.blob()).then(blob => download_default()(blob, 'edit-site-export.zip', 'application/zip')),
      info: Object(external_wp_i18n_["__"])('Download your templates and template parts.')
    }, Object(external_wp_i18n_["__"])('Export'))));
  }

});
//# sourceMappingURL=index.js.map
// EXTERNAL MODULE: external ["wp","hooks"]
var external_wp_hooks_ = __webpack_require__(21);

// EXTERNAL MODULE: external ["wp","mediaUtils"]
var external_wp_mediaUtils_ = __webpack_require__(64);

// CONCATENATED MODULE: ./packages/edit-site/build-module/hooks/components.js
/**
 * WordPress dependencies
 */


Object(external_wp_hooks_["addFilter"])('editor.MediaUpload', 'core/edit-site/components/media-upload', () => external_wp_mediaUtils_["MediaUpload"]);
//# sourceMappingURL=components.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/hooks/index.js
/**
 * Internal dependencies
 */

//# sourceMappingURL=index.js.map
// EXTERNAL MODULE: external ["wp","data"]
var external_wp_data_ = __webpack_require__(4);

// EXTERNAL MODULE: external ["wp","dataControls"]
var external_wp_dataControls_ = __webpack_require__(36);

// CONCATENATED MODULE: ./packages/edit-site/build-module/store/defaults.js
const PREFERENCES_DEFAULTS = {
  features: {}
};
//# sourceMappingURL=defaults.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/constants.js
/**
 * WordPress dependencies
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
const TEMPLATE_PART_AREA_HEADER = 'header';
const TEMPLATE_PART_AREA_FOOTER = 'footer';
const TEMPLATE_PART_AREA_SIDEBAR = 'sidebar';
const TEMPLATE_PARTS_SUB_MENUS = [{
  area: TEMPLATE_PART_AREA_HEADER,
  menu: MENU_TEMPLATE_PARTS_HEADERS,
  title: Object(external_wp_i18n_["__"])('Headers')
}, {
  area: TEMPLATE_PART_AREA_FOOTER,
  menu: MENU_TEMPLATE_PARTS_FOOTERS,
  title: Object(external_wp_i18n_["__"])('Footers')
}, {
  area: TEMPLATE_PART_AREA_SIDEBAR,
  menu: MENU_TEMPLATE_PARTS_SIDEBARS,
  title: Object(external_wp_i18n_["__"])('Sidebars')
}, {
  area: 'uncategorized',
  menu: MENU_TEMPLATE_PARTS_GENERAL,
  title: Object(external_wp_i18n_["__"])('General')
}];
//# sourceMappingURL=constants.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/store/reducer.js
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

const preferences = Object(external_wp_data_["combineReducers"])({
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

function reducer_deviceType(state = 'Desktop', action) {
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

function reducer_settings(state = {}, action) {
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
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */

function editedPost(state = {}, action) {
  switch (action.type) {
    case 'SET_TEMPLATE':
    case 'SET_PAGE':
      return {
        type: 'wp_template',
        id: action.templateId,
        page: action.page
      };

    case 'SET_TEMPLATE_PART':
      return {
        type: 'wp_template_part',
        id: action.templatePartId
      };
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
/* harmony default export */ var reducer = (Object(external_wp_data_["combineReducers"])({
  preferences,
  deviceType: reducer_deviceType,
  settings: reducer_settings,
  editedPost,
  homeTemplateId,
  navigationPanel,
  blockInserterPanel,
  listViewPanel
}));
//# sourceMappingURL=reducer.js.map
// EXTERNAL MODULE: external ["wp","blocks"]
var external_wp_blocks_ = __webpack_require__(8);

// EXTERNAL MODULE: external ["wp","url"]
var external_wp_url_ = __webpack_require__(20);

// EXTERNAL MODULE: external ["wp","notices"]
var external_wp_notices_ = __webpack_require__(31);

// CONCATENATED MODULE: ./packages/edit-site/build-module/store/constants.js
/**
 * The identifier for the data store.
 *
 * @type {string}
 */
const STORE_NAME = 'core/edit-site';
//# sourceMappingURL=constants.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/utils/is-template-revertable.js
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
// CONCATENATED MODULE: ./packages/edit-site/build-module/store/actions.js
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

function* actions_setTemplate(templateId, templateSlug) {
  const pageContext = {
    templateSlug
  };

  if (!templateSlug) {
    const template = yield external_wp_data_["controls"].resolveSelect(external_wp_coreData_["store"].name, 'getEntityRecord', 'postType', 'wp_template', templateId);
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

function* actions_addTemplate(template) {
  const newTemplate = yield external_wp_data_["controls"].dispatch(external_wp_coreData_["store"].name, 'saveEntityRecord', 'postType', 'wp_template', template);

  if (template.content) {
    yield external_wp_data_["controls"].dispatch(external_wp_coreData_["store"].name, 'editEntityRecord', 'postType', 'wp_template', newTemplate.id, {
      blocks: Object(external_wp_blocks_["parse"])(template.content)
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
  yield Object(external_wp_dataControls_["apiFetch"])({
    path: `/wp/v2/templates/${templateId}`,
    method: 'DELETE'
  });
  const page = yield external_wp_data_["controls"].select(STORE_NAME, 'getPage');
  yield external_wp_data_["controls"].dispatch(STORE_NAME, 'setPage', page);
}
/**
 * Returns an action object used to set a template part.
 *
 * @param {number} templatePartId The template part ID.
 *
 * @return {Object} Action object.
 */

function actions_setTemplatePart(templatePartId) {
  return {
    type: 'SET_TEMPLATE_PART',
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

function* actions_setPage(page) {
  var _page$context;

  if (!page.path && (_page$context = page.context) !== null && _page$context !== void 0 && _page$context.postId) {
    const entity = yield external_wp_data_["controls"].resolveSelect(external_wp_coreData_["store"].name, 'getEntityRecord', 'postType', page.context.postType || 'post', page.context.postId);
    page.path = Object(external_wp_url_["getPathAndQueryString"])(entity.link);
  }

  const {
    id: templateId,
    slug: templateSlug
  } = yield external_wp_data_["controls"].resolveSelect(external_wp_coreData_["store"].name, '__experimentalGetTemplateForLink', page.path);
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
 * Displays the site homepage for editing in the editor.
 */

function* actions_showHomepage() {
  const {
    show_on_front: showOnFront,
    page_on_front: frontpageId
  } = yield external_wp_data_["controls"].resolveSelect(external_wp_coreData_["store"].name, 'getEntityRecord', 'root', 'site');
  const {
    siteUrl
  } = yield external_wp_data_["controls"].select(STORE_NAME, 'getSettings');
  const page = {
    path: siteUrl,
    context: showOnFront === 'page' ? {
      postType: 'page',
      postId: frontpageId
    } : {}
  };
  const homeTemplate = yield* actions_setPage(page);
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

function actions_openNavigationPanelToMenu(menu) {
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

function actions_setIsNavigationPanelOpened(isOpen) {
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

function actions_setIsInserterOpened(value) {
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

function actions_updateSettings(settings) {
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

function actions_setIsListViewOpened(isOpen) {
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

function* actions_revertTemplate(template) {
  if (!isTemplateRevertable(template)) {
    yield external_wp_data_["controls"].dispatch(external_wp_notices_["store"], 'createErrorNotice', Object(external_wp_i18n_["__"])('This template is not revertable.'), {
      type: 'snackbar'
    });
    return;
  }

  try {
    var _fileTemplate$content;

    const templateEntity = yield external_wp_data_["controls"].select(external_wp_coreData_["store"], 'getEntity', 'postType', template.type);

    if (!templateEntity) {
      yield external_wp_data_["controls"].dispatch(external_wp_notices_["store"], 'createErrorNotice', Object(external_wp_i18n_["__"])('The editor has encountered an unexpected error. Please reload.'), {
        type: 'snackbar'
      });
      return;
    }

    const fileTemplatePath = Object(external_wp_url_["addQueryArgs"])(`${templateEntity.baseURL}/${template.id}`, {
      context: 'edit',
      source: 'theme'
    });
    const fileTemplate = yield Object(external_wp_dataControls_["apiFetch"])({
      path: fileTemplatePath
    });

    if (!fileTemplate) {
      yield external_wp_data_["controls"].dispatch(external_wp_notices_["store"], 'createErrorNotice', Object(external_wp_i18n_["__"])('The editor has encountered an unexpected error. Please reload.'), {
        type: 'snackbar'
      });
      return;
    }

    const serializeBlocks = ({
      blocks: blocksForSerialization = []
    }) => Object(external_wp_blocks_["__unstableSerializeAndClean"])(blocksForSerialization);

    const edited = yield external_wp_data_["controls"].select(external_wp_coreData_["store"], 'getEditedEntityRecord', 'postType', 'wp_template', template.id); // We are fixing up the undo level here to make sure we can undo
    // the revert in the header toolbar correctly.

    yield external_wp_data_["controls"].dispatch(external_wp_coreData_["store"], 'editEntityRecord', 'postType', 'wp_template', template.id, {
      content: serializeBlocks,
      // required to make the `undo` behave correctly
      blocks: edited.blocks,
      // required to revert the blocks in the editor
      source: 'custom' // required to avoid turning the editor into a dirty state

    }, {
      undoIgnore: true // required to merge this edit with the last undo level

    });
    const blocks = Object(external_wp_blocks_["parse"])(fileTemplate === null || fileTemplate === void 0 ? void 0 : (_fileTemplate$content = fileTemplate.content) === null || _fileTemplate$content === void 0 ? void 0 : _fileTemplate$content.raw);
    yield external_wp_data_["controls"].dispatch(external_wp_coreData_["store"], 'editEntityRecord', 'postType', 'wp_template', fileTemplate.id, {
      content: serializeBlocks,
      blocks,
      source: 'theme'
    });

    const undoRevert = async () => {
      await Object(external_wp_data_["dispatch"])(external_wp_coreData_["store"]).editEntityRecord('postType', 'wp_template', edited.id, {
        content: serializeBlocks,
        blocks: edited.blocks,
        source: 'custom'
      });
    };

    yield external_wp_data_["controls"].dispatch(external_wp_notices_["store"], 'createSuccessNotice', Object(external_wp_i18n_["__"])('Template reverted.'), {
      type: 'snackbar',
      actions: [{
        label: Object(external_wp_i18n_["__"])('Undo'),
        onClick: undoRevert
      }]
    });
  } catch (error) {
    const errorMessage = error.message && error.code !== 'unknown_error' ? error.message : Object(external_wp_i18n_["__"])('Template revert failed. Please reload.');
    yield external_wp_data_["controls"].dispatch(external_wp_notices_["store"], 'createErrorNotice', errorMessage, {
      type: 'snackbar'
    });
  }
}
//# sourceMappingURL=actions.js.map
// EXTERNAL MODULE: ./node_modules/rememo/es/rememo.js
var rememo = __webpack_require__(32);

// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/template-hierarchy.js
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
  const templateSlugs = Object(external_lodash_["map"])(templates, 'slug');
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
// CONCATENATED MODULE: ./packages/edit-site/build-module/store/selectors.js
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
 * Returns whether the given feature is enabled or not.
 *
 * @param {Object} state   Global application state.
 * @param {string} feature Feature slug.
 *
 * @return {boolean} Is active.
 */

function isFeatureActive(state, feature) {
  return Object(external_lodash_["get"])(state.preferences.features, [feature], false);
}
/**
 * Returns the current editing canvas device type.
 *
 * @param {Object} state Global application state.
 *
 * @return {string} Device type.
 */

function selectors_experimentalGetPreviewDeviceType(state) {
  return state.deviceType;
}
/**
 * Returns whether the current user can create media or not.
 *
 * @param {Object} state Global application state.
 *
 * @return {Object} Whether the current user can create media or not.
 */

const getCanUserCreateMedia = Object(external_wp_data_["createRegistrySelector"])(select => () => select(external_wp_coreData_["store"]).canUser('create', 'media'));
/**
 * Returns the settings, taking into account active features and permissions.
 *
 * @param {Object}   state             Global application state.
 * @param {Function} setIsInserterOpen Setter for the open state of the global inserter.
 *
 * @return {Object} Settings.
 */

const selectors_getSettings = Object(rememo["a" /* default */])((state, setIsInserterOpen) => {
  const settings = { ...state.settings,
    outlineMode: true,
    focusMode: isFeatureActive(state, 'focusMode'),
    hasFixedToolbar: isFeatureActive(state, 'fixedToolbar'),
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
    Object(external_wp_mediaUtils_["uploadMedia"])({
      wpAllowedMimeTypes: state.settings.allowedMimeTypes,
      onError: ({
        message
      }) => onError(message),
      ...rest
    });
  };

  return settings;
}, state => [getCanUserCreateMedia(state), state.settings, isFeatureActive(state, 'focusMode'), isFeatureActive(state, 'fixedToolbar')]);
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
/**
 * Returns the current edited post type (wp_template or wp_template_part).
 *
 * @param {Object} state Global application state.
 *
 * @return {number?} Template ID.
 */

function selectors_getEditedPostType(state) {
  return state.editedPost.type;
}
/**
 * Returns the ID of the currently edited template or template part.
 *
 * @param {Object} state Global application state.
 *
 * @return {number?} Post ID.
 */

function selectors_getEditedPostId(state) {
  return state.editedPost.id;
}
/**
 * Returns the current page object.
 *
 * @param {Object} state Global application state.
 *
 * @return {Object} Page.
 */

function selectors_getPage(state) {
  return state.editedPost.page;
}
/**
 * Returns the active menu in the navigation panel.
 *
 * @param {Object} state Global application state.
 *
 * @return {string} Active menu.
 */

function selectors_getNavigationPanelActiveMenu(state) {
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

const selectors_getCurrentTemplateNavigationPanelSubMenu = Object(external_wp_data_["createRegistrySelector"])(select => state => {
  const templateType = selectors_getEditedPostType(state);
  const templateId = selectors_getEditedPostId(state);
  const template = templateId ? select(external_wp_coreData_["store"]).getEntityRecord('postType', templateType, templateId) : null;

  if (!template) {
    return MENU_ROOT;
  }

  if ('wp_template_part' === templateType) {
    var _TEMPLATE_PARTS_SUB_M;

    return ((_TEMPLATE_PARTS_SUB_M = TEMPLATE_PARTS_SUB_MENUS.find(submenu => submenu.area === (template === null || template === void 0 ? void 0 : template.area))) === null || _TEMPLATE_PARTS_SUB_M === void 0 ? void 0 : _TEMPLATE_PARTS_SUB_M.menu) || MENU_TEMPLATE_PARTS;
  }

  const templates = select(external_wp_coreData_["store"]).getEntityRecords('postType', 'wp_template', {
    per_page: -1
  });
  const showOnFront = select(external_wp_coreData_["store"]).getEditedEntityRecord('root', 'site').show_on_front;

  if (isTemplateSuperseded(template.slug, Object(external_lodash_["map"])(templates, 'slug'), showOnFront)) {
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

function selectors_isNavigationOpened(state) {
  return state.navigationPanel.isOpen;
}
/**
 * Returns the current opened/closed state of the inserter panel.
 *
 * @param {Object} state Global application state.
 *
 * @return {boolean} True if the inserter panel should be open; false if closed.
 */

function selectors_isInserterOpened(state) {
  return !!state.blockInserterPanel;
}
/**
 * Get the insertion point for the inserter.
 *
 * @param {Object} state Global application state.
 *
 * @return {Object} The root client ID and index to insert at.
 */

function __experimentalGetInsertionPoint(state) {
  const {
    rootClientId,
    insertionIndex
  } = state.blockInserterPanel;
  return {
    rootClientId,
    insertionIndex
  };
}
/**
 * Returns the current opened/closed state of the list view panel.
 *
 * @param {Object} state Global application state.
 *
 * @return {boolean} True if the list view panel should be open; false if closed.
 */

function selectors_isListViewOpened(state) {
  return state.listViewPanel;
}
//# sourceMappingURL=selectors.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/store/index.js
/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */





const storeConfig = {
  reducer: reducer,
  actions: actions_namespaceObject,
  selectors: selectors_namespaceObject,
  controls: external_wp_dataControls_["controls"],
  persist: ['preferences']
};
const store = Object(external_wp_data_["createReduxStore"])(STORE_NAME, storeConfig); // Once we build a more generic persistence plugin that works across types of stores
// we'd be able to replace this with a register call.

Object(external_wp_data_["registerStore"])(STORE_NAME, storeConfig);
//# sourceMappingURL=index.js.map
// EXTERNAL MODULE: external ["wp","blockEditor"]
var external_wp_blockEditor_ = __webpack_require__(5);

// EXTERNAL MODULE: ./packages/interface/build-module/index.js + 15 modules
var build_module = __webpack_require__(30);

// EXTERNAL MODULE: external ["wp","editor"]
var external_wp_editor_ = __webpack_require__(18);

// EXTERNAL MODULE: external ["wp","compose"]
var external_wp_compose_ = __webpack_require__(9);

// EXTERNAL MODULE: ./packages/icons/build-module/library/plus.js
var plus = __webpack_require__(150);

// EXTERNAL MODULE: ./packages/icons/build-module/library/list-view.js
var list_view = __webpack_require__(248);

// EXTERNAL MODULE: external ["wp","keyboardShortcuts"]
var external_wp_keyboardShortcuts_ = __webpack_require__(27);

// EXTERNAL MODULE: ./packages/icons/build-module/library/more-vertical.js
var more_vertical = __webpack_require__(201);

// EXTERNAL MODULE: ./packages/icons/build-module/library/check.js
var check = __webpack_require__(123);

// EXTERNAL MODULE: external ["wp","a11y"]
var external_wp_a11y_ = __webpack_require__(33);

// CONCATENATED MODULE: ./packages/edit-site/build-module/components/header/feature-toggle/index.js


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
      Object(external_wp_a11y_["speak"])(messageDeactivated || Object(external_wp_i18n_["__"])('Feature deactivated'));
    } else {
      Object(external_wp_a11y_["speak"])(messageActivated || Object(external_wp_i18n_["__"])('Feature activated'));
    }
  };

  const isActive = Object(external_wp_data_["useSelect"])(select => {
    return select(store).isFeatureActive(feature);
  }, []);
  const {
    toggleFeature
  } = Object(external_wp_data_["useDispatch"])(store);
  return Object(external_wp_element_["createElement"])(external_wp_components_["MenuItem"], {
    icon: isActive && check["a" /* default */],
    isSelected: isActive,
    onClick: Object(external_lodash_["flow"])(toggleFeature.bind(null, feature), speakMessage),
    role: "menuitemcheckbox",
    info: info
  }, label);
}
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/header/more-menu/index.js


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

const MoreMenu = () => Object(external_wp_element_["createElement"])(external_wp_components_["DropdownMenu"], {
  className: "edit-site-more-menu",
  icon: more_vertical["a" /* default */],
  label: Object(external_wp_i18n_["__"])('More tools & options'),
  popoverProps: POPOVER_PROPS,
  toggleProps: TOGGLE_PROPS
}, ({
  onClose
}) => Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, Object(external_wp_element_["createElement"])(external_wp_components_["MenuGroup"], {
  label: Object(external_wp_i18n_["_x"])('View', 'noun')
}, Object(external_wp_element_["createElement"])(FeatureToggle, {
  feature: "fixedToolbar",
  label: Object(external_wp_i18n_["__"])('Top toolbar'),
  info: Object(external_wp_i18n_["__"])('Access all block and document tools in a single place'),
  messageActivated: Object(external_wp_i18n_["__"])('Top toolbar activated'),
  messageDeactivated: Object(external_wp_i18n_["__"])('Top toolbar deactivated')
}), Object(external_wp_element_["createElement"])(FeatureToggle, {
  feature: "focusMode",
  label: Object(external_wp_i18n_["__"])('Spotlight mode'),
  info: Object(external_wp_i18n_["__"])('Focus on one block at a time'),
  messageActivated: Object(external_wp_i18n_["__"])('Spotlight mode activated'),
  messageDeactivated: Object(external_wp_i18n_["__"])('Spotlight mode deactivated')
}), Object(external_wp_element_["createElement"])(build_module["a" /* ActionItem */].Slot, {
  name: "core/edit-site/plugin-more-menu",
  label: Object(external_wp_i18n_["__"])('Plugins'),
  as: external_wp_components_["MenuGroup"],
  fillProps: {
    onClick: onClose
  }
})), Object(external_wp_element_["createElement"])(tools_more_menu_group.Slot, {
  fillProps: {
    onClose
  }
})));

/* harmony default export */ var more_menu = (MoreMenu);
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/save-button/index.js


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
  } = Object(external_wp_data_["useSelect"])(select => {
    const {
      __experimentalGetDirtyEntityRecords,
      isSavingEntityRecord
    } = select(external_wp_coreData_["store"]);

    const dirtyEntityRecords = __experimentalGetDirtyEntityRecords();

    return {
      isDirty: dirtyEntityRecords.length > 0,
      isSaving: Object(external_lodash_["some"])(dirtyEntityRecords, record => isSavingEntityRecord(record.kind, record.name, record.key))
    };
  });
  const disabled = !isDirty || isSaving;
  return Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
    variant: "primary",
    className: "edit-site-save-button__button",
    "aria-disabled": disabled,
    "aria-expanded": isEntitiesSavedStatesOpen,
    disabled: disabled,
    isBusy: isSaving,
    onClick: disabled ? undefined : openEntitiesSavedStates
  }, Object(external_wp_i18n_["__"])('Save')));
}
//# sourceMappingURL=index.js.map
// EXTERNAL MODULE: ./packages/icons/build-module/library/undo.js
var library_undo = __webpack_require__(268);

// EXTERNAL MODULE: ./packages/icons/build-module/library/redo.js
var library_redo = __webpack_require__(269);

// EXTERNAL MODULE: external ["wp","keycodes"]
var external_wp_keycodes_ = __webpack_require__(14);

// CONCATENATED MODULE: ./packages/edit-site/build-module/components/header/undo-redo/undo.js


/**
 * WordPress dependencies
 */






function UndoButton() {
  const hasUndo = Object(external_wp_data_["useSelect"])(select => select(external_wp_coreData_["store"]).hasUndo());
  const {
    undo
  } = Object(external_wp_data_["useDispatch"])(external_wp_coreData_["store"]);
  return Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
    icon: !Object(external_wp_i18n_["isRTL"])() ? library_undo["a" /* default */] : library_redo["a" /* default */],
    label: Object(external_wp_i18n_["__"])('Undo'),
    shortcut: external_wp_keycodes_["displayShortcut"].primary('z') // If there are no undo levels we don't want to actually disable this
    // button, because it will remove focus for keyboard users.
    // See: https://github.com/WordPress/gutenberg/issues/3486
    ,
    "aria-disabled": !hasUndo,
    onClick: hasUndo ? undo : undefined
  });
}
//# sourceMappingURL=undo.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/header/undo-redo/redo.js


/**
 * WordPress dependencies
 */






function RedoButton() {
  const hasRedo = Object(external_wp_data_["useSelect"])(select => select(external_wp_coreData_["store"]).hasRedo());
  const {
    redo
  } = Object(external_wp_data_["useDispatch"])(external_wp_coreData_["store"]);
  return Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
    icon: !Object(external_wp_i18n_["isRTL"])() ? library_redo["a" /* default */] : library_undo["a" /* default */],
    label: Object(external_wp_i18n_["__"])('Redo'),
    shortcut: external_wp_keycodes_["displayShortcut"].primaryShift('z') // If there are no undo levels we don't want to actually disable this
    // button, because it will remove focus for keyboard users.
    // See: https://github.com/WordPress/gutenberg/issues/3486
    ,
    "aria-disabled": !hasRedo,
    onClick: hasRedo ? redo : undefined
  });
}
//# sourceMappingURL=redo.js.map
// EXTERNAL MODULE: ./node_modules/classnames/index.js
var classnames = __webpack_require__(10);
var classnames_default = /*#__PURE__*/__webpack_require__.n(classnames);

// EXTERNAL MODULE: ./packages/icons/build-module/library/chevron-down.js
var chevron_down = __webpack_require__(200);

// CONCATENATED MODULE: ./packages/edit-site/build-module/components/header/document-actions/index.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */









function getBlockDisplayText(block) {
  return block ? Object(external_wp_blocks_["__experimentalGetBlockLabel"])(Object(external_wp_blocks_["getBlockType"])(block.name), block.attributes) : null;
}

function useSecondaryText() {
  const {
    getBlock
  } = Object(external_wp_data_["useSelect"])('core/block-editor');
  const activeEntityBlockId = Object(external_wp_data_["useSelect"])(select => select(external_wp_blockEditor_["store"]).__experimentalGetActiveBlockIdByBlockNames(['core/template-part']), []);

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

  const titleRef = Object(external_wp_element_["useRef"])(); // Return a simple loading indicator until we have information to show.

  if (!isLoaded) {
    return Object(external_wp_element_["createElement"])("div", {
      className: "edit-site-document-actions"
    }, Object(external_wp_i18n_["__"])('Loading…'));
  } // Return feedback that the template does not seem to exist.


  if (!entityTitle) {
    return Object(external_wp_element_["createElement"])("div", {
      className: "edit-site-document-actions"
    }, Object(external_wp_i18n_["__"])('Template not found'));
  }

  return Object(external_wp_element_["createElement"])("div", {
    className: classnames_default()('edit-site-document-actions', {
      'has-secondary-label': !!label
    })
  }, Object(external_wp_element_["createElement"])("div", {
    ref: titleRef,
    className: "edit-site-document-actions__title-wrapper"
  }, Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalText"], {
    size: "body",
    className: "edit-site-document-actions__title-prefix"
  }, Object(external_wp_element_["createElement"])(external_wp_components_["VisuallyHidden"], {
    as: "span"
  }, Object(external_wp_i18n_["sprintf"])(
  /* translators: %s: the entity being edited, like "template"*/
  Object(external_wp_i18n_["__"])('Editing %s:'), entityLabel))), Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalText"], {
    size: "body",
    className: "edit-site-document-actions__title",
    as: "h1"
  }, entityTitle), Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalText"], {
    size: "body",
    className: "edit-site-document-actions__secondary-item"
  }, label !== null && label !== void 0 ? label : ''), dropdownContent && Object(external_wp_element_["createElement"])(external_wp_components_["Dropdown"], {
    popoverProps: {
      anchorRef: titleRef.current
    },
    position: "bottom center",
    renderToggle: ({
      isOpen,
      onToggle
    }) => Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
      className: "edit-site-document-actions__get-info",
      icon: chevron_down["a" /* default */],
      "aria-expanded": isOpen,
      "aria-haspopup": "true",
      onClick: onToggle,
      label: Object(external_wp_i18n_["sprintf"])(
      /* translators: %s: the entity to see details about, like "template"*/
      Object(external_wp_i18n_["__"])('Show %s details'), entityLabel)
    }),
    contentClassName: "edit-site-document-actions__info-dropdown",
    renderContent: dropdownContent
  })));
}
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/template-details/index.js


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
  } = Object(external_wp_data_["useSelect"])(select => select(external_wp_editor_["store"]).__experimentalGetTemplateInfo(template), []);
  const {
    openNavigationPanelToMenu,
    revertTemplate
  } = Object(external_wp_data_["useDispatch"])(store);

  if (!template) {
    return null;
  }

  const showTemplateInSidebar = () => {
    onClose();
    openNavigationPanelToMenu(MENU_TEMPLATES);
  };

  const revert = () => {
    revertTemplate(template);
    onClose();
  };

  return Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, Object(external_wp_element_["createElement"])("div", {
    className: "edit-site-template-details"
  }, Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalText"], {
    size: "body",
    weight: 600
  }, title), description && Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalText"], {
    size: "body",
    className: "edit-site-template-details__description"
  }, description)), isTemplateRevertable(template) && Object(external_wp_element_["createElement"])("div", {
    className: "edit-site-template-details__revert"
  }, Object(external_wp_element_["createElement"])(external_wp_components_["MenuItem"], {
    info: Object(external_wp_i18n_["__"])('Restore template to theme default'),
    onClick: revert
  }, Object(external_wp_i18n_["__"])('Clear customizations'))), Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
    className: "edit-site-template-details__show-all-button",
    onClick: showTemplateInSidebar,
    "aria-label": Object(external_wp_i18n_["__"])('Browse all templates. This will open the template menu in the navigation side panel.')
  }, Object(external_wp_i18n_["__"])('Browse all templates')));
}
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/header/index.js


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
  const inserterButton = Object(external_wp_element_["useRef"])();
  const {
    deviceType,
    entityTitle,
    template,
    templateType,
    isInserterOpen,
    isListViewOpen,
    listViewShortcut,
    isLoaded
  } = Object(external_wp_data_["useSelect"])(select => {
    const {
      __experimentalGetPreviewDeviceType,
      getEditedPostType,
      getEditedPostId,
      isInserterOpened,
      isListViewOpened
    } = select(store);
    const {
      getEditedEntityRecord
    } = select(external_wp_coreData_["store"]);
    const {
      __experimentalGetTemplateInfo: getTemplateInfo
    } = select(external_wp_editor_["store"]);
    const {
      getShortcutRepresentation
    } = select(external_wp_keyboardShortcuts_["store"]);
    const postType = getEditedPostType();
    const postId = getEditedPostId();
    const record = getEditedEntityRecord('postType', postType, postId);

    const _entityTitle = 'wp_template' === postType ? getTemplateInfo(record).title : record === null || record === void 0 ? void 0 : record.slug;

    const _isLoaded = !!postId;

    return {
      deviceType: __experimentalGetPreviewDeviceType(),
      entityTitle: _entityTitle,
      isLoaded: _isLoaded,
      template: record,
      templateType: postType,
      isInserterOpen: isInserterOpened(),
      isListViewOpen: isListViewOpened(),
      listViewShortcut: getShortcutRepresentation('core/edit-site/toggle-list-view')
    };
  }, []);
  const {
    __experimentalSetPreviewDeviceType: setPreviewDeviceType,
    setIsInserterOpened,
    setIsListViewOpened
  } = Object(external_wp_data_["useDispatch"])(store);
  const isLargeViewport = Object(external_wp_compose_["useViewportMatch"])('medium');
  const openInserter = Object(external_wp_element_["useCallback"])(() => {
    if (isInserterOpen) {
      // Focusing the inserter button closes the inserter popover
      inserterButton.current.focus();
    } else {
      setIsInserterOpened(true);
    }
  }, [isInserterOpen, setIsInserterOpened]);
  const toggleListView = Object(external_wp_element_["useCallback"])(() => setIsListViewOpened(!isListViewOpen), [setIsListViewOpened, isListViewOpen]);
  return Object(external_wp_element_["createElement"])("div", {
    className: "edit-site-header"
  }, Object(external_wp_element_["createElement"])("div", {
    className: "edit-site-header_start"
  }, Object(external_wp_element_["createElement"])("div", {
    className: "edit-site-header__toolbar"
  }, Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
    ref: inserterButton,
    variant: "primary",
    isPressed: isInserterOpen,
    className: "edit-site-header-toolbar__inserter-toggle",
    onMouseDown: preventDefault,
    onClick: openInserter,
    icon: plus["a" /* default */],
    label: Object(external_wp_i18n_["_x"])('Toggle block inserter', 'Generic label for block inserter button')
  }), isLargeViewport && Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, Object(external_wp_element_["createElement"])(external_wp_blockEditor_["ToolSelector"], null), Object(external_wp_element_["createElement"])(UndoButton, null), Object(external_wp_element_["createElement"])(RedoButton, null), Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
    className: "edit-site-header-toolbar__list-view-toggle",
    icon: list_view["a" /* default */],
    isPressed: isListViewOpen
    /* translators: button label text should, if possible, be under 16 characters. */
    ,
    label: Object(external_wp_i18n_["__"])('List View'),
    onClick: toggleListView,
    shortcut: listViewShortcut
  })))), Object(external_wp_element_["createElement"])("div", {
    className: "edit-site-header_center"
  }, 'wp_template' === templateType && Object(external_wp_element_["createElement"])(DocumentActions, {
    entityTitle: entityTitle,
    entityLabel: "template",
    isLoaded: isLoaded
  }, ({
    onClose
  }) => Object(external_wp_element_["createElement"])(TemplateDetails, {
    template: template,
    onClose: onClose
  })), 'wp_template_part' === templateType && Object(external_wp_element_["createElement"])(DocumentActions, {
    entityTitle: entityTitle,
    entityLabel: "template part",
    isLoaded: isLoaded
  })), Object(external_wp_element_["createElement"])("div", {
    className: "edit-site-header_end"
  }, Object(external_wp_element_["createElement"])("div", {
    className: "edit-site-header__actions"
  }, Object(external_wp_element_["createElement"])(external_wp_blockEditor_["__experimentalPreviewOptions"], {
    deviceType: deviceType,
    setDeviceType: setPreviewDeviceType
  }), Object(external_wp_element_["createElement"])(SaveButton, {
    openEntitiesSavedStates: openEntitiesSavedStates,
    isEntitiesSavedStatesOpen: isEntitiesSavedStatesOpen
  }), Object(external_wp_element_["createElement"])(build_module["f" /* PinnedItems */].Slot, {
    scope: "core/edit-site"
  }), Object(external_wp_element_["createElement"])(more_menu, null))));
}
//# sourceMappingURL=index.js.map
// EXTERNAL MODULE: ./packages/icons/build-module/library/cog.js
var cog = __webpack_require__(270);

// CONCATENATED MODULE: ./packages/icons/build-module/library/typography.js


/**
 * WordPress dependencies
 */

const typography = Object(external_wp_element_["createElement"])(external_wp_primitives_["SVG"], {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, Object(external_wp_element_["createElement"])(external_wp_primitives_["Path"], {
  d: "M6.9 7L3 17.8h1.7l1-2.8h4.1l1 2.8h1.7L8.6 7H6.9zm-.7 6.6l1.5-4.3 1.5 4.3h-3zM21.6 17c-.1.1-.2.2-.3.2-.1.1-.2.1-.4.1s-.3-.1-.4-.2c-.1-.1-.1-.3-.1-.6V12c0-.5 0-1-.1-1.4-.1-.4-.3-.7-.5-1-.2-.2-.5-.4-.9-.5-.4 0-.8-.1-1.3-.1s-1 .1-1.4.2c-.4.1-.7.3-1 .4-.2.2-.4.3-.6.5-.1.2-.2.4-.2.7 0 .3.1.5.2.8.2.2.4.3.8.3.3 0 .6-.1.8-.3.2-.2.3-.4.3-.7 0-.3-.1-.5-.2-.7-.2-.2-.4-.3-.6-.4.2-.2.4-.3.7-.4.3-.1.6-.1.8-.1.3 0 .6 0 .8.1.2.1.4.3.5.5.1.2.2.5.2.9v1.1c0 .3-.1.5-.3.6-.2.2-.5.3-.9.4-.3.1-.7.3-1.1.4-.4.1-.8.3-1.1.5-.3.2-.6.4-.8.7-.2.3-.3.7-.3 1.2 0 .6.2 1.1.5 1.4.3.4.9.5 1.6.5.5 0 1-.1 1.4-.3.4-.2.8-.6 1.1-1.1 0 .4.1.7.3 1 .2.3.6.4 1.2.4.4 0 .7-.1.9-.2.2-.1.5-.3.7-.4h-.3zm-3-.9c-.2.4-.5.7-.8.8-.3.2-.6.2-.8.2-.4 0-.6-.1-.9-.3-.2-.2-.3-.6-.3-1.1 0-.5.1-.9.3-1.2s.5-.5.8-.7c.3-.2.7-.3 1-.5.3-.1.6-.3.7-.6v3.4z"
}));
/* harmony default export */ var library_typography = (typography);
//# sourceMappingURL=typography.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/sidebar/default-sidebar.js


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
  return Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, Object(external_wp_element_["createElement"])(build_module["b" /* ComplementaryArea */], {
    className: className,
    scope: "core/edit-site",
    identifier: identifier,
    title: title,
    icon: icon,
    closeLabel: closeLabel,
    header: header,
    headerClassName: headerClassName
  }, children), Object(external_wp_element_["createElement"])(build_module["c" /* ComplementaryAreaMoreMenuItem */], {
    scope: "core/edit-site",
    identifier: identifier,
    icon: icon
  }, title));
}
//# sourceMappingURL=default-sidebar.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/editor/utils.js
/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */


/* Supporting data */

const ROOT_BLOCK_NAME = 'root';
const ROOT_BLOCK_SELECTOR = 'body';
const ROOT_BLOCK_SUPPORTS = ['background', 'backgroundColor', 'color', 'linkColor', 'fontFamily', 'fontSize', 'fontStyle', 'fontWeight', 'lineHeight', 'textDecoration', 'textTransform'];
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
  classes: []
}];
const STYLE_PROPERTIES_TO_CSS_VAR_INFIX = {
  linkColor: 'color',
  backgroundColor: 'color',
  background: 'gradient'
};

function getPresetMetadataFromStyleProperty(styleProperty) {
  if (!getPresetMetadataFromStyleProperty.MAP) {
    getPresetMetadataFromStyleProperty.MAP = {};
    PRESET_METADATA.forEach(({
      cssVarInfix
    }, index) => {
      getPresetMetadataFromStyleProperty.MAP[Object(external_lodash_["camelCase"])(cssVarInfix)] = PRESET_METADATA[index];
    });
    Object(external_lodash_["forEach"])(STYLE_PROPERTIES_TO_CSS_VAR_INFIX, (value, key) => {
      getPresetMetadataFromStyleProperty.MAP[key] = getPresetMetadataFromStyleProperty.MAP[value];
    });
  }

  return getPresetMetadataFromStyleProperty.MAP[styleProperty];
}

const PATHS_WITH_MERGE = {
  'color.gradients': true,
  'color.palette': true,
  'typography.fontFamilies': true,
  'typography.fontSizes': true
};
function useSetting(path, blockName = '') {
  var _get;

  const settings = Object(external_wp_data_["useSelect"])(select => {
    return select(store).getSettings();
  });
  const topLevelPath = `__experimentalFeatures.${path}`;
  const blockPath = `__experimentalFeatures.blocks.${blockName}.${path}`;
  const result = (_get = Object(external_lodash_["get"])(settings, blockPath)) !== null && _get !== void 0 ? _get : Object(external_lodash_["get"])(settings, topLevelPath);

  if (result && PATHS_WITH_MERGE[path]) {
    var _ref, _result$user;

    return (_ref = (_result$user = result.user) !== null && _result$user !== void 0 ? _result$user : result.theme) !== null && _ref !== void 0 ? _ref : result.core;
  }

  return result;
}

function findInPresetsBy(styles, context, presetPath, presetProperty, presetValueValue) {
  // Block presets take priority above root level presets.
  const orderedPresetsByOrigin = [Object(external_lodash_["get"])(styles, ['settings', 'blocks', context, ...presetPath]), Object(external_lodash_["get"])(styles, ['settings', ...presetPath])];

  for (const presetByOrigin of orderedPresetsByOrigin) {
    if (presetByOrigin) {
      // Preset origins ordered by priority.
      const origins = ['user', 'theme', 'core'];

      for (const origin of origins) {
        const presets = presetByOrigin[origin];

        if (presets) {
          const presetObject = Object(external_lodash_["find"])(presets, preset => preset[presetProperty] === presetValueValue);

          if (presetObject) {
            if (presetProperty === 'slug') {
              return presetObject;
            } // if there is a highest priority preset with the same slug but different value the preset we found was overwritten and should be ignored.


            const highestPresetObjectWithSameSlug = findInPresetsBy(styles, context, presetPath, 'slug', presetObject.slug);

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

function getPresetVariable(styles, context, propertyName, value) {
  if (!value) {
    return value;
  }

  const metadata = getPresetMetadataFromStyleProperty(propertyName);

  if (!metadata) {
    // The property doesn't have preset data
    // so the value should be returned as it is.
    return value;
  }

  const {
    valueKey,
    path,
    cssVarInfix
  } = metadata;
  const presetObject = findInPresetsBy(styles, context, path, valueKey, value);

  if (!presetObject) {
    // Value wasn't found in the presets,
    // so it must be a custom value.
    return value;
  }

  return `var:preset|${cssVarInfix}|${presetObject.slug}`;
}

function getValueFromPresetVariable(styles, blockName, variable, [presetType, slug]) {
  presetType = Object(external_lodash_["camelCase"])(presetType);
  const metadata = getPresetMetadataFromStyleProperty(presetType);

  if (!metadata) {
    return variable;
  }

  const presetObject = findInPresetsBy(styles, blockName, metadata.path, 'slug', slug);

  if (presetObject) {
    const {
      valueKey
    } = metadata;
    const result = presetObject[valueKey];
    return getValueFromVariable(styles, blockName, result);
  }

  return variable;
}

function getValueFromCustomVariable(styles, blockName, variable, path) {
  var _get2;

  const result = (_get2 = Object(external_lodash_["get"])(styles, ['settings', 'blocks', blockName, 'custom', ...path])) !== null && _get2 !== void 0 ? _get2 : Object(external_lodash_["get"])(styles, ['settings', 'custom', ...path]);

  if (!result) {
    return variable;
  } // A variable may reference another variable so we need recursion until we find the value.


  return getValueFromVariable(styles, blockName, result);
}

function getValueFromVariable(styles, blockName, variable) {
  if (!variable || !Object(external_lodash_["isString"])(variable)) {
    return variable;
  }

  let parsedVar;
  const INTERNAL_REFERENCE_PREFIX = 'var:';
  const CSS_REFERENCE_PREFIX = 'var(--wp--';
  const CSS_REFERENCE_SUFFIX = ')';

  if (variable.startsWith(INTERNAL_REFERENCE_PREFIX)) {
    parsedVar = variable.slice(INTERNAL_REFERENCE_PREFIX.length).split('|');
  } else if (variable.startsWith(CSS_REFERENCE_PREFIX) && variable.endsWith(CSS_REFERENCE_SUFFIX)) {
    parsedVar = variable.slice(CSS_REFERENCE_PREFIX.length, -CSS_REFERENCE_SUFFIX.length).split('--');
  } else {
    // Value is raw.
    return variable;
  }

  const [type, ...path] = parsedVar;

  if (type === 'preset') {
    return getValueFromPresetVariable(styles, blockName, variable, path);
  }

  if (type === 'custom') {
    return getValueFromCustomVariable(styles, blockName, variable, path);
  }

  return variable;
}
//# sourceMappingURL=utils.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/editor/global-styles-renderer.js
/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */



function compileStyleValue(uncompiledValue) {
  const VARIABLE_REFERENCE_PREFIX = 'var:';
  const VARIABLE_PATH_SEPARATOR_TOKEN_ATTRIBUTE = '|';
  const VARIABLE_PATH_SEPARATOR_TOKEN_STYLE = '--';

  if (Object(external_lodash_["startsWith"])(uncompiledValue, VARIABLE_REFERENCE_PREFIX)) {
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
  return Object(external_lodash_["reduce"])(PRESET_METADATA, (declarations, {
    path,
    valueKey,
    cssVarInfix
  }) => {
    const presetByOrigin = Object(external_lodash_["get"])(blockPresets, path, []);
    ['core', 'theme', 'user'].forEach(origin => {
      if (presetByOrigin[origin]) {
        presetByOrigin[origin].forEach(value => {
          declarations.push(`--wp--preset--${cssVarInfix}--${Object(external_lodash_["kebabCase"])(value.slug)}: ${value[valueKey]}`);
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
  return Object(external_lodash_["reduce"])(PRESET_METADATA, (declarations, {
    path,
    cssVarInfix,
    classes
  }) => {
    if (!classes) {
      return declarations;
    }

    const presetByOrigin = Object(external_lodash_["get"])(blockPresets, path, []);
    ['core', 'theme', 'user'].forEach(origin => {
      if (presetByOrigin[origin]) {
        presetByOrigin[origin].forEach(({
          slug
        }) => {
          classes.forEach(({
            classSuffix,
            propertyName
          }) => {
            const classSelectorToUse = `.has-${Object(external_lodash_["kebabCase"])(slug)}-${classSuffix}`;
            const selectorToUse = `${blockSelector}${classSelectorToUse}`;
            const value = `var(--wp--preset--${cssVarInfix}--${Object(external_lodash_["kebabCase"])(slug)})`;
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
    const newKey = prefix + Object(external_lodash_["kebabCase"])(key.replace('/', '-'));
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
  return Object(external_lodash_["reduce"])(external_wp_blocks_["__EXPERIMENTAL_STYLE_PROPERTY"], (declarations, {
    value,
    properties
  }, key) => {
    const pathToValue = value;

    if (Object(external_lodash_["first"])(pathToValue) === 'elements') {
      return declarations;
    }

    const styleValue = Object(external_lodash_["get"])(blockStyles, pathToValue);

    if (!!properties && !Object(external_lodash_["isString"])(styleValue)) {
      Object.entries(properties).forEach(entry => {
        const [name, prop] = entry;

        if (!Object(external_lodash_["get"])(styleValue, [prop], false)) {
          // Do not create a declaration
          // for sub-properties that don't have any value.
          return;
        }

        const cssProperty = Object(external_lodash_["kebabCase"])(name);
        declarations.push(`${cssProperty}: ${compileStyleValue(Object(external_lodash_["get"])(styleValue, [prop]))}`);
      });
    } else if (Object(external_lodash_["get"])(blockStyles, pathToValue, false)) {
      const cssProperty = key.startsWith('--') ? key : Object(external_lodash_["kebabCase"])(key);
      declarations.push(`${cssProperty}: ${compileStyleValue(Object(external_lodash_["get"])(blockStyles, pathToValue))}`);
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

  const pickStyleKeys = treeToPickFrom => Object(external_lodash_["pickBy"])(treeToPickFrom, (value, key) => ['border', 'color', 'spacing', 'typography'].includes(key)); // Top-level.


  const styles = pickStyleKeys(tree.styles);

  if (!!styles) {
    nodes.push({
      styles,
      selector: ROOT_BLOCK_SELECTOR
    });
  }

  Object(external_lodash_["forEach"])((_tree$styles = tree.styles) === null || _tree$styles === void 0 ? void 0 : _tree$styles.elements, (value, key) => {
    if (!!value && !!external_wp_blocks_["__EXPERIMENTAL_ELEMENTS"][key]) {
      nodes.push({
        styles: value,
        selector: external_wp_blocks_["__EXPERIMENTAL_ELEMENTS"][key]
      });
    }
  }); // Iterate over blocks: they can have styles & elements.

  Object(external_lodash_["forEach"])((_tree$styles2 = tree.styles) === null || _tree$styles2 === void 0 ? void 0 : _tree$styles2.blocks, (node, blockName) => {
    var _blockSelectors$block;

    const blockStyles = pickStyleKeys(node);

    if (!!blockStyles && !!(blockSelectors !== null && blockSelectors !== void 0 && (_blockSelectors$block = blockSelectors[blockName]) !== null && _blockSelectors$block !== void 0 && _blockSelectors$block.selector)) {
      nodes.push({
        styles: blockStyles,
        selector: blockSelectors[blockName].selector
      });
    }

    Object(external_lodash_["forEach"])(node === null || node === void 0 ? void 0 : node.elements, (value, elementName) => {
      var _blockSelectors$block2, _blockSelectors$block3;

      if (!!value && !!(blockSelectors !== null && blockSelectors !== void 0 && (_blockSelectors$block2 = blockSelectors[blockName]) !== null && _blockSelectors$block2 !== void 0 && (_blockSelectors$block3 = _blockSelectors$block2.elements) !== null && _blockSelectors$block3 !== void 0 && _blockSelectors$block3[elementName])) {
        nodes.push({
          styles: value,
          selector: blockSelectors[blockName].elements[elementName]
        });
      }
    });
  });
  return nodes;
};
const getNodesWithSettings = (tree, blockSelectors) => {
  var _tree$settings2;

  const nodes = [];

  if (!(tree !== null && tree !== void 0 && tree.settings)) {
    return nodes;
  }

  const pickPresets = treeToPickFrom => {
    const presets = {};
    PRESET_METADATA.forEach(({
      path
    }) => {
      const value = Object(external_lodash_["get"])(treeToPickFrom, path, false);

      if (value !== false) {
        Object(external_lodash_["set"])(presets, path, value);
      }
    });
    return presets;
  }; // Top-level.


  const presets = pickPresets(tree.settings);

  if (!Object(external_lodash_["isEmpty"])(presets)) {
    var _tree$settings;

    nodes.push({
      presets,
      custom: (_tree$settings = tree.settings) === null || _tree$settings === void 0 ? void 0 : _tree$settings.custom,
      selector: ROOT_BLOCK_SELECTOR
    });
  } // Blocks.


  Object(external_lodash_["forEach"])((_tree$settings2 = tree.settings) === null || _tree$settings2 === void 0 ? void 0 : _tree$settings2.blocks, (node, blockName) => {
    const blockPresets = pickPresets(node);

    if (!Object(external_lodash_["isEmpty"])(blockPresets)) {
      nodes.push({
        presets: blockPresets,
        custom: node.custom,
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
  let ruleset = '';
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

    if (!Object(external_lodash_["isEmpty"])(classes)) {
      ruleset = ruleset + classes;
    }
  });
  return ruleset;
};
//# sourceMappingURL=global-styles-renderer.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/editor/global-styles-provider.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */




const EMPTY_CONTENT = {
  isGlobalStylesUserThemeJSON: true,
  version: 1
};
const EMPTY_CONTENT_STRING = JSON.stringify(EMPTY_CONTENT);
const GlobalStylesContext = Object(external_wp_element_["createContext"])({
  /* eslint-disable no-unused-vars */
  getSetting: (context, path) => {},
  setSetting: (context, path, newValue) => {},
  getStyle: (context, propertyName, origin) => {},
  setStyle: (context, propertyName, newValue) => {},
  contexts: {}
  /* eslint-enable no-unused-vars */

});

const mergeTreesCustomizer = (objValue, srcValue) => {
  // We only pass as arrays the presets,
  // in which case we want the new array of values
  // to override the old array (no merging).
  if (Array.isArray(srcValue)) {
    return srcValue;
  }
};

const useGlobalStylesContext = () => Object(external_wp_element_["useContext"])(GlobalStylesContext);

const useGlobalStylesEntityContent = () => {
  return Object(external_wp_coreData_["useEntityProp"])('postType', 'wp_global_styles', 'content');
};

const useGlobalStylesReset = () => {
  const [content, setContent] = useGlobalStylesEntityContent();
  const canRestart = !!content && content !== EMPTY_CONTENT_STRING;
  return [canRestart, Object(external_wp_element_["useCallback"])(() => setContent(EMPTY_CONTENT_STRING), [setContent])];
};

const extractSupportKeys = supports => {
  const supportKeys = [];
  Object.keys(external_wp_blocks_["__EXPERIMENTAL_STYLE_PROPERTY"]).forEach(name => {
    if (Object(external_lodash_["get"])(supports, external_wp_blocks_["__EXPERIMENTAL_STYLE_PROPERTY"][name].support, false)) {
      supportKeys.push(name);
    }
  });
  return supportKeys;
};

const getBlockMetadata = blockTypes => {
  const result = {};
  blockTypes.forEach(blockType => {
    var _blockType$supports$_, _blockType$supports;

    const name = blockType.name;
    const supports = extractSupportKeys(blockType === null || blockType === void 0 ? void 0 : blockType.supports);
    const selector = (_blockType$supports$_ = blockType === null || blockType === void 0 ? void 0 : (_blockType$supports = blockType.supports) === null || _blockType$supports === void 0 ? void 0 : _blockType$supports.__experimentalSelector) !== null && _blockType$supports$_ !== void 0 ? _blockType$supports$_ : '.wp-block-' + name.replace('core/', '').replace('/', '-');
    const blockSelectors = selector.split(',');
    const elements = [];
    Object.keys(external_wp_blocks_["__EXPERIMENTAL_ELEMENTS"]).forEach(key => {
      const elementSelector = [];
      blockSelectors.forEach(blockSelector => {
        elementSelector.push(blockSelector + ' ' + external_wp_blocks_["__EXPERIMENTAL_ELEMENTS"][key]);
      });
      elements[key] = elementSelector.join(',');
    });
    result[name] = {
      name,
      selector,
      supports,
      elements
    };
  });
  return result;
};

function immutableSet(object, path, value) {
  return Object(external_lodash_["setWith"])(object ? Object(external_lodash_["clone"])(object) : {}, path, value, external_lodash_["clone"]);
}

function GlobalStylesProvider({
  children,
  baseStyles
}) {
  const [content, setContent] = useGlobalStylesEntityContent();
  const {
    blockTypes,
    settings
  } = Object(external_wp_data_["useSelect"])(select => {
    return {
      blockTypes: select(external_wp_blocks_["store"]).getBlockTypes(),
      settings: select(store).getSettings()
    };
  });
  const {
    updateSettings
  } = Object(external_wp_data_["useDispatch"])(store);
  const blocks = Object(external_wp_element_["useMemo"])(() => getBlockMetadata(blockTypes), [blockTypes]);
  const {
    __experimentalGlobalStylesBaseStyles: themeStyles
  } = settings;
  const {
    userStyles,
    mergedStyles
  } = Object(external_wp_element_["useMemo"])(() => {
    let newUserStyles;

    try {
      var _newUserStyles;

      newUserStyles = content ? JSON.parse(content) : EMPTY_CONTENT; // At the moment, we ignore previous user config that
      // is in a different version than the theme config.

      if (((_newUserStyles = newUserStyles) === null || _newUserStyles === void 0 ? void 0 : _newUserStyles.version) !== (baseStyles === null || baseStyles === void 0 ? void 0 : baseStyles.version)) {
        newUserStyles = EMPTY_CONTENT;
      }
    } catch (e) {
      /* eslint-disable no-console */
      console.error('User data is not JSON');
      console.error(e);
      /* eslint-enable no-console */

      newUserStyles = EMPTY_CONTENT;
    } // It is very important to verify if the flag isGlobalStylesUserThemeJSON is true.
    // If it is not true the content was not escaped and is not safe.


    if (!newUserStyles.isGlobalStylesUserThemeJSON) {
      newUserStyles = EMPTY_CONTENT;
    }

    const addUserToSettings = settingsToAdd => {
      PRESET_METADATA.forEach(({
        path
      }) => {
        const presetData = Object(external_lodash_["get"])(settingsToAdd, path);

        if (presetData) {
          settingsToAdd = immutableSet(settingsToAdd, path, {
            user: presetData
          });
        }
      });
      return settingsToAdd;
    };

    let userStylesWithOrigin = newUserStyles;

    if (userStylesWithOrigin.settings) {
      userStylesWithOrigin = { ...userStylesWithOrigin,
        settings: addUserToSettings(userStylesWithOrigin.settings)
      };

      if (userStylesWithOrigin.settings.blocks) {
        userStylesWithOrigin.settings = { ...userStylesWithOrigin.settings,
          blocks: Object(external_lodash_["mapValues"])(userStylesWithOrigin.settings.blocks, addUserToSettings)
        };
      }
    } // At this point, the version schema of the theme & user
    // is the same, so we can merge them.


    const newMergedStyles = Object(external_lodash_["mergeWith"])({}, baseStyles, userStylesWithOrigin, mergeTreesCustomizer);
    return {
      userStyles: newUserStyles,
      mergedStyles: newMergedStyles
    };
  }, [content]);
  const nextValue = Object(external_wp_element_["useMemo"])(() => ({
    root: {
      name: ROOT_BLOCK_NAME,
      selector: ROOT_BLOCK_SELECTOR,
      supports: ROOT_BLOCK_SUPPORTS,
      elements: external_wp_blocks_["__EXPERIMENTAL_ELEMENTS"]
    },
    blocks,
    getSetting: (context, propertyPath) => {
      const path = context === ROOT_BLOCK_NAME ? propertyPath : ['blocks', context, ...propertyPath];
      Object(external_lodash_["get"])(userStyles === null || userStyles === void 0 ? void 0 : userStyles.settings, path);
    },
    setSetting: (context, propertyPath, newValue) => {
      const newContent = { ...userStyles
      };
      const path = context === ROOT_BLOCK_NAME ? ['settings'] : ['settings', 'blocks', context];
      let newSettings = Object(external_lodash_["get"])(newContent, path);

      if (!newSettings) {
        newSettings = {};
        Object(external_lodash_["set"])(newContent, path, newSettings);
      }

      Object(external_lodash_["set"])(newSettings, propertyPath, newValue);
      setContent(JSON.stringify(newContent));
    },
    getStyle: (context, propertyName, origin = 'merged') => {
      const propertyPath = external_wp_blocks_["__EXPERIMENTAL_STYLE_PROPERTY"][propertyName].value;
      const path = context === ROOT_BLOCK_NAME ? propertyPath : ['blocks', context, ...propertyPath];

      if (origin === 'theme') {
        const value = Object(external_lodash_["get"])(themeStyles === null || themeStyles === void 0 ? void 0 : themeStyles.styles, path);
        return getValueFromVariable(themeStyles, context, value);
      }

      if (origin === 'user') {
        const value = Object(external_lodash_["get"])(userStyles === null || userStyles === void 0 ? void 0 : userStyles.styles, path); // We still need to use merged styles here because the
        // presets used to resolve user variable may be defined a
        // layer down ( core, theme, or user ).

        return getValueFromVariable(mergedStyles, context, value);
      }

      const value = Object(external_lodash_["get"])(mergedStyles === null || mergedStyles === void 0 ? void 0 : mergedStyles.styles, path);
      return getValueFromVariable(mergedStyles, context, value);
    },
    setStyle: (context, propertyName, newValue) => {
      const newContent = { ...userStyles
      };
      const path = ROOT_BLOCK_NAME === context ? ['styles'] : ['styles', 'blocks', context];
      const propertyPath = external_wp_blocks_["__EXPERIMENTAL_STYLE_PROPERTY"][propertyName].value;
      let newStyles = Object(external_lodash_["get"])(newContent, path);

      if (!newStyles) {
        newStyles = {};
        Object(external_lodash_["set"])(newContent, path, newStyles);
      }

      Object(external_lodash_["set"])(newStyles, propertyPath, getPresetVariable(mergedStyles, context, propertyName, newValue));
      setContent(JSON.stringify(newContent));
    }
  }), [content, mergedStyles, themeStyles]);
  Object(external_wp_element_["useEffect"])(() => {
    const nonGlobalStyles = settings.styles.filter(style => !style.isGlobalStyles);
    const customProperties = toCustomProperties(mergedStyles, blocks);
    const globalStyles = toStyles(mergedStyles, blocks);
    updateSettings({ ...settings,
      styles: [...nonGlobalStyles, {
        css: customProperties,
        isGlobalStyles: true,
        __experimentalNoWrapper: true
      }, {
        css: globalStyles,
        isGlobalStyles: true
      }],
      __experimentalFeatures: mergedStyles.settings
    });
  }, [blocks, mergedStyles]);
  return Object(external_wp_element_["createElement"])(GlobalStylesContext.Provider, {
    value: nextValue
  }, children);
}
//# sourceMappingURL=global-styles-provider.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/sidebar/typography-panel.js


/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */


function useHasTypographyPanel({
  supports,
  name
}) {
  const hasLineHeight = useHasLineHeightControl({
    supports,
    name
  });
  const hasFontAppearance = useHasAppearanceControl({
    supports,
    name
  });
  const hasLetterSpacing = useHasLetterSpacingControl({
    supports,
    name
  });
  return hasLineHeight || hasFontAppearance || hasLetterSpacing || supports.includes('fontSize');
}

function useHasLineHeightControl({
  supports,
  name
}) {
  return useSetting('typography.customLineHeight', name) && supports.includes('lineHeight');
}

function useHasAppearanceControl({
  supports,
  name
}) {
  const hasFontStyles = useSetting('typography.customFontStyle', name) && supports.includes('fontStyle');
  const hasFontWeights = useSetting('typography.customFontWeight', name) && supports.includes('fontWeight');
  return hasFontStyles || hasFontWeights;
}

function useHasLetterSpacingControl({
  supports,
  name
}) {
  return useSetting('typography.customLetterSpacing', name) && supports.includes('letterSpacing');
}

function TypographyPanel({
  context: {
    supports,
    name
  },
  getStyle,
  setStyle
}) {
  const fontSizes = useSetting('typography.fontSizes', name);
  const disableCustomFontSizes = !useSetting('typography.customFontSize', name);
  const fontFamilies = useSetting('typography.fontFamilies', name);
  const hasFontStyles = useSetting('typography.customFontStyle', name) && supports.includes('fontStyle');
  const hasFontWeights = useSetting('typography.customFontWeight', name) && supports.includes('fontWeight');
  const hasLineHeightEnabled = useHasLineHeightControl({
    supports,
    name
  });
  const hasAppearanceControl = useHasAppearanceControl({
    supports,
    name
  });
  const hasLetterSpacingControl = useHasLetterSpacingControl({
    supports,
    name
  });
  return Object(external_wp_element_["createElement"])(external_wp_components_["PanelBody"], {
    title: Object(external_wp_i18n_["__"])('Typography'),
    initialOpen: true
  }, supports.includes('fontFamily') && Object(external_wp_element_["createElement"])(external_wp_blockEditor_["__experimentalFontFamilyControl"], {
    fontFamilies: fontFamilies,
    value: getStyle(name, 'fontFamily'),
    onChange: value => setStyle(name, 'fontFamily', value)
  }), supports.includes('fontSize') && Object(external_wp_element_["createElement"])(external_wp_components_["FontSizePicker"], {
    value: getStyle(name, 'fontSize'),
    onChange: value => setStyle(name, 'fontSize', value),
    fontSizes: fontSizes,
    disableCustomFontSizes: disableCustomFontSizes
  }), hasLineHeightEnabled && Object(external_wp_element_["createElement"])(external_wp_blockEditor_["LineHeightControl"], {
    value: getStyle(name, 'lineHeight'),
    onChange: value => setStyle(name, 'lineHeight', value)
  }), hasAppearanceControl && Object(external_wp_element_["createElement"])(external_wp_blockEditor_["__experimentalFontAppearanceControl"], {
    value: {
      fontStyle: getStyle(name, 'fontStyle'),
      fontWeight: getStyle(name, 'fontWeight')
    },
    onChange: ({
      fontStyle,
      fontWeight
    }) => {
      setStyle(name, 'fontStyle', fontStyle);
      setStyle(name, 'fontWeight', fontWeight);
    },
    hasFontStyles: hasFontStyles,
    hasFontWeights: hasFontWeights
  }), hasLetterSpacingControl && Object(external_wp_element_["createElement"])(external_wp_blockEditor_["__experimentalLetterSpacingControl"], {
    value: getStyle(name, 'letterSpacing'),
    onChange: value => setStyle(name, 'letterSpacing', value)
  }));
}
//# sourceMappingURL=typography-panel.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/sidebar/border-panel.js


/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */


const MIN_BORDER_WIDTH = 0; // Defining empty array here instead of inline avoids unnecessary re-renders of
// color control.

const EMPTY_ARRAY = [];
function useHasBorderPanel({
  supports,
  name
}) {
  const controls = [useHasBorderColorControl({
    supports,
    name
  }), useHasBorderRadiusControl({
    supports,
    name
  }), useHasBorderStyleControl({
    supports,
    name
  }), useHasBorderWidthControl({
    supports,
    name
  })];
  return controls.every(Boolean);
}

function useHasBorderColorControl({
  supports,
  name
}) {
  return useSetting('border.customColor', name) && supports.includes('borderColor');
}

function useHasBorderRadiusControl({
  supports,
  name
}) {
  return useSetting('border.customRadius', name) && supports.includes('borderRadius');
}

function useHasBorderStyleControl({
  supports,
  name
}) {
  return useSetting('border.customStyle', name) && supports.includes('borderStyle');
}

function useHasBorderWidthControl({
  supports,
  name
}) {
  return useSetting('border.customWidth', name) && supports.includes('borderWidth');
}

function BorderPanel({
  context: {
    supports,
    name
  },
  getStyle,
  setStyle
}) {
  const units = Object(external_wp_components_["__experimentalUseCustomUnits"])({
    availableUnits: useSetting('spacing.units') || ['px', 'em', 'rem']
  }); // Border width.

  const hasBorderWidth = useHasBorderWidthControl({
    supports,
    name
  });
  const borderWidthValue = getStyle(name, 'borderWidth'); // Border style.

  const hasBorderStyle = useHasBorderStyleControl({
    supports,
    name
  });
  const borderStyle = getStyle(name, 'borderStyle'); // Border color.

  const colors = useSetting('color.palette') || EMPTY_ARRAY;
  const disableCustomColors = !useSetting('color.custom');
  const disableCustomGradients = !useSetting('color.customGradient');
  const hasBorderColor = useHasBorderColorControl({
    supports,
    name
  });
  const borderColor = getStyle(name, 'borderColor'); // Border radius.

  const hasBorderRadius = useHasBorderRadiusControl({
    supports,
    name
  });
  const borderRadiusValues = getStyle(name, 'borderRadius');
  return Object(external_wp_element_["createElement"])(external_wp_components_["PanelBody"], {
    title: Object(external_wp_i18n_["__"])('Border'),
    initialOpen: true
  }, (hasBorderWidth || hasBorderStyle) && Object(external_wp_element_["createElement"])("div", {
    className: "edit-site-global-styles-sidebar__border-controls-row"
  }, hasBorderWidth && Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalUnitControl"], {
    value: borderWidthValue,
    label: Object(external_wp_i18n_["__"])('Width'),
    min: MIN_BORDER_WIDTH,
    onChange: value => {
      setStyle(name, 'borderWidth', value || undefined);
    },
    units: units
  }), hasBorderStyle && Object(external_wp_element_["createElement"])(external_wp_blockEditor_["__experimentalBorderStyleControl"], {
    value: borderStyle,
    onChange: value => setStyle(name, 'borderStyle', value)
  })), hasBorderColor && Object(external_wp_element_["createElement"])(external_wp_blockEditor_["__experimentalColorGradientControl"], {
    label: Object(external_wp_i18n_["__"])('Color'),
    value: borderColor,
    colors: colors,
    gradients: undefined,
    disableCustomColors: disableCustomColors,
    disableCustomGradients: disableCustomGradients,
    onColorChange: value => setStyle(name, 'borderColor', value)
  }), hasBorderRadius && Object(external_wp_element_["createElement"])(external_wp_blockEditor_["__experimentalBorderRadiusControl"], {
    values: borderRadiusValues,
    onChange: value => setStyle(name, 'borderRadius', value)
  }));
}
//# sourceMappingURL=border-panel.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/sidebar/color-palette-panel.js


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
 * Shared reference to an empty array for cases where it is important to avoid
 * returning a new array reference on every invocation, as in a connected or
 * other pure component which performs `shouldComponentUpdate` check on props.
 * This should be used as a last resort, since the normalized data should be
 * maintained by the reducer result in state.
 *
 * @type {Array}
 */

const color_palette_panel_EMPTY_ARRAY = [];
function ColorPalettePanel({
  contextName,
  getSetting,
  setSetting
}) {
  const colors = useSetting('color.palette', contextName);
  const userColors = getSetting(contextName, 'color.palette');
  const immutableColorSlugs = Object(external_wp_data_["useSelect"])(select => {
    var _ref, _ref2, _contextualBasePalett;

    const baseStyles = select(store).getSettings().__experimentalGlobalStylesBaseStyles;

    const contextualBasePalette = Object(external_lodash_["get"])(baseStyles, ['settings', 'blocks', contextName, 'color', 'palette']);
    const globalPalette = Object(external_lodash_["get"])(baseStyles, ['settings', 'color', 'palette']);
    const basePalette = (_ref = (_ref2 = (_contextualBasePalett = contextualBasePalette === null || contextualBasePalette === void 0 ? void 0 : contextualBasePalette.theme) !== null && _contextualBasePalett !== void 0 ? _contextualBasePalett : contextualBasePalette === null || contextualBasePalette === void 0 ? void 0 : contextualBasePalette.core) !== null && _ref2 !== void 0 ? _ref2 : globalPalette === null || globalPalette === void 0 ? void 0 : globalPalette.theme) !== null && _ref !== void 0 ? _ref : globalPalette === null || globalPalette === void 0 ? void 0 : globalPalette.core;

    if (!basePalette) {
      return color_palette_panel_EMPTY_ARRAY;
    }

    return basePalette.map(({
      slug
    }) => slug);
  }, [contextName]);
  return Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalColorEdit"], {
    immutableColorSlugs: immutableColorSlugs,
    colors: colors,
    onChange: newColors => {
      setSetting(contextName, 'color.palette', newColors);
    },
    emptyUI: Object(external_wp_i18n_["__"])('Colors are empty! Add some colors to create your own color palette.'),
    canReset: colors === userColors
  });
}
//# sourceMappingURL=color-palette-panel.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/sidebar/color-panel.js


/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */



function useHasColorPanel({
  supports
}) {
  return supports.includes('color') || supports.includes('backgroundColor') || supports.includes('background') || supports.includes('linkColor');
}
function ColorPanel({
  context: {
    supports,
    name
  },
  getStyle,
  setStyle,
  getSetting,
  setSetting
}) {
  const colors = useSetting('color.palette', name);
  const disableCustomColors = !useSetting('color.custom', name);
  const gradients = useSetting('color.gradients', name);
  const disableCustomGradients = !useSetting('color.customGradient', name);
  const settings = [];

  if (supports.includes('color')) {
    const color = getStyle(name, 'color');
    const userColor = getStyle(name, 'color', 'user');
    settings.push({
      colorValue: color,
      onColorChange: value => setStyle(name, 'color', value),
      label: Object(external_wp_i18n_["__"])('Text color'),
      clearable: color === userColor
    });
  }

  let backgroundSettings = {};

  if (supports.includes('backgroundColor')) {
    const backgroundColor = getStyle(name, 'backgroundColor');
    const userBackgroundColor = getStyle(name, 'backgroundColor', 'user');
    backgroundSettings = {
      colorValue: backgroundColor,
      onColorChange: value => setStyle(name, 'backgroundColor', value)
    };

    if (backgroundColor) {
      backgroundSettings.clearable = backgroundColor === userBackgroundColor;
    }
  }

  let gradientSettings = {};

  if (supports.includes('background')) {
    const gradient = getStyle(name, 'background');
    const userGradient = getStyle(name, 'background', 'user');
    gradientSettings = {
      gradientValue: gradient,
      onGradientChange: value => setStyle(name, 'background', value)
    };

    if (gradient) {
      gradientSettings.clearable = gradient === userGradient;
    }
  }

  if (supports.includes('background') || supports.includes('backgroundColor')) {
    settings.push({ ...backgroundSettings,
      ...gradientSettings,
      label: Object(external_wp_i18n_["__"])('Background color')
    });
  }

  if (supports.includes('linkColor')) {
    const color = getStyle(name, 'linkColor');
    const userColor = getStyle(name, 'linkColor', 'user');
    settings.push({
      colorValue: color,
      onColorChange: value => setStyle(name, 'linkColor', value),
      label: Object(external_wp_i18n_["__"])('Link color'),
      clearable: color === userColor
    });
  }

  return Object(external_wp_element_["createElement"])(external_wp_blockEditor_["__experimentalPanelColorGradientSettings"], {
    title: Object(external_wp_i18n_["__"])('Color'),
    settings: settings,
    colors: colors,
    gradients: gradients,
    disableCustomColors: disableCustomColors,
    disableCustomGradients: disableCustomGradients
  }, Object(external_wp_element_["createElement"])(ColorPalettePanel, {
    key: 'color-palette-panel-' + name,
    contextName: name,
    getSetting: getSetting,
    setSetting: setSetting
  }));
}
//# sourceMappingURL=color-panel.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/sidebar/spacing-panel.js


/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */


function useHasSpacingPanel(context) {
  const hasPadding = useHasPadding(context);
  const hasMargin = useHasMargin(context);
  return hasPadding || hasMargin;
}

function useHasPadding({
  name,
  supports
}) {
  const settings = useSetting('spacing.customPadding', name);
  return settings && supports.includes('padding');
}

function useHasMargin({
  name,
  supports
}) {
  const settings = useSetting('spacing.customMargin', name);
  return settings && supports.includes('margin');
}

function filterValuesBySides(values, sides) {
  if (!sides) {
    // If no custom side configuration all sides are opted into by default.
    return values;
  } // Only include sides opted into within filtered values.


  const filteredValues = {};
  sides.forEach(side => filteredValues[side] = values[side]);
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

function SpacingPanel({
  context,
  getStyle,
  setStyle
}) {
  const {
    name
  } = context;
  const showPaddingControl = useHasPadding(context);
  const showMarginControl = useHasMargin(context);
  const units = Object(external_wp_components_["__experimentalUseCustomUnits"])({
    availableUnits: useSetting('spacing.units', name) || ['%', 'px', 'em', 'rem', 'vw']
  });
  const paddingValues = splitStyleValue(getStyle(name, 'padding'));
  const paddingSides = Object(external_wp_blockEditor_["__experimentalUseCustomSides"])(name, 'padding');

  const setPaddingValues = newPaddingValues => {
    const padding = filterValuesBySides(newPaddingValues, paddingSides);
    setStyle(name, 'padding', padding);
  };

  const marginValues = splitStyleValue(getStyle(name, 'margin'));
  const marginSides = Object(external_wp_blockEditor_["__experimentalUseCustomSides"])(name, 'margin');

  const setMarginValues = newMarginValues => {
    const margin = filterValuesBySides(newMarginValues, marginSides);
    setStyle(name, 'margin', margin);
  };

  return Object(external_wp_element_["createElement"])(external_wp_components_["PanelBody"], {
    title: Object(external_wp_i18n_["__"])('Spacing')
  }, showPaddingControl && Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalBoxControl"], {
    values: paddingValues,
    onChange: setPaddingValues,
    label: Object(external_wp_i18n_["__"])('Padding'),
    sides: paddingSides,
    units: units
  }), showMarginControl && Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalBoxControl"], {
    values: marginValues,
    onChange: setMarginValues,
    label: Object(external_wp_i18n_["__"])('Margin'),
    sides: marginSides,
    units: units
  }));
}
//# sourceMappingURL=spacing-panel.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/sidebar/global-styles-sidebar.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */








function GlobalStylesPanel({
  wrapperPanelTitle,
  context,
  getStyle,
  setStyle,
  getSetting,
  setSetting
}) {
  const hasBorderPanel = useHasBorderPanel(context);
  const hasColorPanel = useHasColorPanel(context);
  const hasTypographyPanel = useHasTypographyPanel(context);
  const hasSpacingPanel = useHasSpacingPanel(context);

  if (!hasColorPanel && !hasTypographyPanel && !hasSpacingPanel) {
    return null;
  }

  const content = Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, hasTypographyPanel && Object(external_wp_element_["createElement"])(TypographyPanel, {
    context: context,
    getStyle: getStyle,
    setStyle: setStyle
  }), hasColorPanel && Object(external_wp_element_["createElement"])(ColorPanel, {
    context: context,
    getStyle: getStyle,
    setStyle: setStyle,
    getSetting: getSetting,
    setSetting: setSetting
  }), hasSpacingPanel && Object(external_wp_element_["createElement"])(SpacingPanel, {
    context: context,
    getStyle: getStyle,
    setStyle: setStyle
  }), hasBorderPanel && Object(external_wp_element_["createElement"])(BorderPanel, {
    context: context,
    getStyle: getStyle,
    setStyle: setStyle
  }));

  if (!wrapperPanelTitle) {
    return content;
  }

  return Object(external_wp_element_["createElement"])(external_wp_components_["PanelBody"], {
    title: wrapperPanelTitle,
    initialOpen: false
  }, content);
}

function getPanelTitle(blockName) {
  const blockType = Object(external_wp_blocks_["getBlockType"])(blockName); // Protect against blocks that aren't registered
  // eg: widget-area

  if (blockType === undefined) {
    return blockName;
  }

  return blockType.title;
}

function GlobalStylesBlockPanels({
  blocks,
  getStyle,
  setStyle,
  getSetting,
  setSetting
}) {
  const panels = Object(external_wp_element_["useMemo"])(() => Object(external_lodash_["sortBy"])(Object(external_lodash_["map"])(blocks, (block, name) => {
    return {
      block,
      name,
      wrapperPanelTitle: getPanelTitle(name)
    };
  }), ({
    wrapperPanelTitle
  }) => wrapperPanelTitle), [blocks]);
  return Object(external_lodash_["map"])(panels, ({
    block,
    name,
    wrapperPanelTitle
  }) => {
    return Object(external_wp_element_["createElement"])(GlobalStylesPanel, {
      key: 'panel-' + name,
      wrapperPanelTitle: wrapperPanelTitle,
      context: block,
      getStyle: getStyle,
      setStyle: setStyle,
      getSetting: getSetting,
      setSetting: setSetting
    });
  });
}

function GlobalStylesSidebar({
  identifier,
  title,
  icon,
  closeLabel
}) {
  const {
    root,
    blocks,
    getStyle,
    setStyle,
    getSetting,
    setSetting
  } = useGlobalStylesContext();
  const [canRestart, onReset] = useGlobalStylesReset();

  if (typeof blocks !== 'object' || !root) {
    // No sidebar is shown.
    return null;
  }

  return Object(external_wp_element_["createElement"])(DefaultSidebar, {
    className: "edit-site-global-styles-sidebar",
    identifier: identifier,
    title: title,
    icon: icon,
    closeLabel: closeLabel,
    header: Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, Object(external_wp_element_["createElement"])("strong", null, title), Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
      className: "edit-site-global-styles-sidebar__reset-button",
      isSmall: true,
      variant: "tertiary",
      disabled: !canRestart,
      onClick: onReset
    }, Object(external_wp_i18n_["__"])('Reset to defaults')))
  }, Object(external_wp_element_["createElement"])(external_wp_components_["TabPanel"], {
    tabs: [{
      name: 'root',
      title: Object(external_wp_i18n_["__"])('Root')
    }, {
      name: 'block',
      title: Object(external_wp_i18n_["__"])('By Block Type')
    }]
  }, tab => {
    /* Per Block Context */
    if ('block' === tab.name) {
      return Object(external_wp_element_["createElement"])(GlobalStylesBlockPanels, {
        blocks: blocks,
        getStyle: getStyle,
        setStyle: setStyle,
        getSetting: getSetting,
        setSetting: setSetting
      });
    }

    return Object(external_wp_element_["createElement"])(GlobalStylesPanel, {
      hasWrapper: false,
      context: root,
      getStyle: getStyle,
      setStyle: setStyle,
      getSetting: getSetting,
      setSetting: setSetting
    });
  }));
}
//# sourceMappingURL=global-styles-sidebar.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/sidebar/constants.js
const SIDEBAR_TEMPLATE = 'edit-site/template';
const SIDEBAR_BLOCK = 'edit-site/block-inspector';
//# sourceMappingURL=constants.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/sidebar/settings-header/index.js


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
  } = Object(external_wp_data_["useDispatch"])(build_module["g" /* store */]);

  const openTemplateSettings = () => enableComplementaryArea(STORE_NAME, SIDEBAR_TEMPLATE);

  const openBlockSettings = () => enableComplementaryArea(STORE_NAME, SIDEBAR_BLOCK);

  const [templateAriaLabel, templateActiveClass] = sidebarName === SIDEBAR_TEMPLATE ? // translators: ARIA label for the Template sidebar tab, selected.
  [Object(external_wp_i18n_["__"])('Template (selected)'), 'is-active'] : // translators: ARIA label for the Template Settings Sidebar tab, not selected.
  [Object(external_wp_i18n_["__"])('Template'), ''];
  const [blockAriaLabel, blockActiveClass] = sidebarName === SIDEBAR_BLOCK ? // translators: ARIA label for the Block Settings Sidebar tab, selected.
  [Object(external_wp_i18n_["__"])('Block (selected)'), 'is-active'] : // translators: ARIA label for the Block Settings Sidebar tab, not selected.
  [Object(external_wp_i18n_["__"])('Block'), ''];
  /* Use a list so screen readers will announce how many tabs there are. */

  return Object(external_wp_element_["createElement"])("ul", null, Object(external_wp_element_["createElement"])("li", null, Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
    onClick: openTemplateSettings,
    className: `edit-site-sidebar__panel-tab ${templateActiveClass}`,
    "aria-label": templateAriaLabel // translators: Data label for the Template Settings Sidebar tab.
    ,
    "data-label": Object(external_wp_i18n_["__"])('Template')
  }, // translators: Text label for the Template Settings Sidebar tab.
  Object(external_wp_i18n_["__"])('Template'))), Object(external_wp_element_["createElement"])("li", null, Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
    onClick: openBlockSettings,
    className: `edit-site-sidebar__panel-tab ${blockActiveClass}`,
    "aria-label": blockAriaLabel // translators: Data label for the Block Settings Sidebar tab.
    ,
    "data-label": Object(external_wp_i18n_["__"])('Block')
  }, // translators: Text label for the Block Settings Sidebar tab.
  Object(external_wp_i18n_["__"])('Block'))));
};

/* harmony default export */ var settings_header = (SettingsHeader);
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/sidebar/template-card/index.js


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
  } = Object(external_wp_data_["useSelect"])(select => {
    const {
      getEditedPostType,
      getEditedPostId
    } = select(store);
    const {
      getEntityRecord
    } = select(external_wp_coreData_["store"]);
    const {
      __experimentalGetTemplateInfo: getTemplateInfo
    } = select(external_wp_editor_["store"]);
    const postType = getEditedPostType();
    const postId = getEditedPostId();
    const record = getEntityRecord('postType', postType, postId);
    const info = record ? getTemplateInfo(record) : {};
    return info;
  }, []);

  if (!title && !description) {
    return null;
  }

  return Object(external_wp_element_["createElement"])("div", {
    className: "edit-site-template-card"
  }, Object(external_wp_element_["createElement"])(external_wp_components_["Icon"], {
    className: "edit-site-template-card__icon",
    icon: icon
  }), Object(external_wp_element_["createElement"])("div", {
    className: "edit-site-template-card__content"
  }, Object(external_wp_element_["createElement"])("h2", {
    className: "edit-site-template-card__title"
  }, title), Object(external_wp_element_["createElement"])("span", {
    className: "edit-site-template-card__description"
  }, description)));
}
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/sidebar/index.js


/**
 * WordPress dependencies
 */







/**
 * Internal dependencies
 */







const {
  Slot: InspectorSlot,
  Fill: InspectorFill
} = Object(external_wp_components_["createSlotFill"])('EditSiteSidebarInspector');
const SidebarInspectorFill = InspectorFill;
function SidebarComplementaryAreaFills() {
  const {
    sidebar,
    isEditorSidebarOpened,
    hasBlockSelection
  } = Object(external_wp_data_["useSelect"])(select => {
    const _sidebar = select(build_module["g" /* store */]).getActiveComplementaryArea(STORE_NAME);

    const _isEditorSidebarOpened = [SIDEBAR_BLOCK, SIDEBAR_TEMPLATE].includes(_sidebar);

    return {
      sidebar: _sidebar,
      isEditorSidebarOpened: _isEditorSidebarOpened,
      hasBlockSelection: !!select(external_wp_blockEditor_["store"]).getBlockSelectionStart()
    };
  }, []);
  const {
    enableComplementaryArea
  } = Object(external_wp_data_["useDispatch"])(build_module["g" /* store */]);
  Object(external_wp_element_["useEffect"])(() => {
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

  return Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, Object(external_wp_element_["createElement"])(DefaultSidebar, {
    identifier: sidebarName,
    title: Object(external_wp_i18n_["__"])('Settings'),
    icon: cog["a" /* default */],
    closeLabel: Object(external_wp_i18n_["__"])('Close settings sidebar'),
    header: Object(external_wp_element_["createElement"])(settings_header, {
      sidebarName: sidebarName
    }),
    headerClassName: "edit-site-sidebar__panel-tabs"
  }, sidebarName === SIDEBAR_TEMPLATE && Object(external_wp_element_["createElement"])(external_wp_components_["PanelBody"], null, Object(external_wp_element_["createElement"])(TemplateCard, null)), sidebarName === SIDEBAR_BLOCK && Object(external_wp_element_["createElement"])(InspectorSlot, {
    bubblesVirtually: true
  })), Object(external_wp_element_["createElement"])(GlobalStylesSidebar, {
    identifier: "edit-site/global-styles",
    title: Object(external_wp_i18n_["__"])('Global Styles'),
    closeLabel: Object(external_wp_i18n_["__"])('Close global styles sidebar'),
    icon: library_typography
  }));
}
//# sourceMappingURL=index.js.map
// EXTERNAL MODULE: ./node_modules/@babel/runtime/helpers/esm/extends.js
var esm_extends = __webpack_require__(7);

// CONCATENATED MODULE: ./packages/edit-site/build-module/components/template-part-converter/convert-to-regular.js


/**
 * WordPress dependencies
 */




function ConvertToRegularBlocks({
  clientId
}) {
  const {
    innerBlocks
  } = Object(external_wp_data_["useSelect"])(select => select(external_wp_blockEditor_["store"]).__unstableGetBlockWithBlockTree(clientId), [clientId]);
  const {
    replaceBlocks
  } = Object(external_wp_data_["useDispatch"])(external_wp_blockEditor_["store"]);
  return Object(external_wp_element_["createElement"])(external_wp_blockEditor_["BlockSettingsMenuControls"], null, ({
    onClose
  }) => Object(external_wp_element_["createElement"])(external_wp_components_["MenuItem"], {
    onClick: () => {
      replaceBlocks(clientId, innerBlocks);
      onClose();
    }
  }, Object(external_wp_i18n_["__"])('Detach blocks from template part')));
}
//# sourceMappingURL=convert-to-regular.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/template-part-converter/convert-to-template-part.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */












function ConvertToTemplatePart({
  clientIds,
  blocks
}) {
  const instanceId = Object(external_wp_compose_["useInstanceId"])(ConvertToTemplatePart);
  const [isModalOpen, setIsModalOpen] = Object(external_wp_element_["useState"])(false);
  const [title, setTitle] = Object(external_wp_element_["useState"])('');
  const {
    replaceBlocks
  } = Object(external_wp_data_["useDispatch"])(external_wp_blockEditor_["store"]);
  const {
    saveEntityRecord
  } = Object(external_wp_data_["useDispatch"])(external_wp_coreData_["store"]);
  const {
    createSuccessNotice
  } = Object(external_wp_data_["useDispatch"])(external_wp_notices_["store"]);
  const [area, setArea] = Object(external_wp_element_["useState"])('uncategorized');
  const templatePartAreas = Object(external_wp_data_["useSelect"])(select => select(external_wp_editor_["store"]).__experimentalGetDefaultTemplatePartAreas(), []);

  const onConvert = async templatePartTitle => {
    const defaultTitle = Object(external_wp_i18n_["__"])('Untitled Template Part');

    const templatePart = await saveEntityRecord('postType', 'wp_template_part', {
      slug: Object(external_lodash_["kebabCase"])(templatePartTitle || defaultTitle),
      title: templatePartTitle || defaultTitle,
      content: Object(external_wp_blocks_["serialize"])(blocks),
      area
    });
    replaceBlocks(clientIds, Object(external_wp_blocks_["createBlock"])('core/template-part', {
      slug: templatePart.slug,
      theme: templatePart.theme
    }));
    createSuccessNotice(Object(external_wp_i18n_["__"])('Template part created.'), {
      type: 'snackbar'
    });
  };

  return Object(external_wp_element_["createElement"])(external_wp_blockEditor_["BlockSettingsMenuControls"], null, ({
    onClose
  }) => Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, Object(external_wp_element_["createElement"])(external_wp_components_["MenuItem"], {
    onClick: () => {
      setIsModalOpen(true);
    }
  }, Object(external_wp_i18n_["__"])('Make template part')), isModalOpen && Object(external_wp_element_["createElement"])(external_wp_components_["Modal"], {
    title: Object(external_wp_i18n_["__"])('Create a template part'),
    closeLabel: Object(external_wp_i18n_["__"])('Close'),
    onRequestClose: () => {
      setIsModalOpen(false);
      setTitle('');
    },
    overlayClassName: "edit-site-template-part-converter__modal"
  }, Object(external_wp_element_["createElement"])("form", {
    onSubmit: event => {
      event.preventDefault();
      onConvert(title);
      setIsModalOpen(false);
      setTitle('');
      onClose();
    }
  }, Object(external_wp_element_["createElement"])(external_wp_components_["TextControl"], {
    label: Object(external_wp_i18n_["__"])('Name'),
    value: title,
    onChange: setTitle
  }), Object(external_wp_element_["createElement"])(external_wp_components_["BaseControl"], {
    label: Object(external_wp_i18n_["__"])('Area'),
    id: `edit-site-template-part-converter__area-selection-${instanceId}`,
    className: "edit-site-template-part-converter__area-base-control"
  }, Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalRadioGroup"], {
    label: Object(external_wp_i18n_["__"])('Area'),
    className: "edit-site-template-part-converter__area-radio-group",
    id: `edit-site-template-part-converter__area-selection-${instanceId}`,
    onChange: setArea,
    checked: area
  }, templatePartAreas.map(({
    icon,
    label,
    area: value,
    description
  }) => Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalRadio"], {
    key: label,
    value: value,
    className: "edit-site-template-part-converter__area-radio"
  }, Object(external_wp_element_["createElement"])(external_wp_components_["Flex"], {
    align: "start",
    justify: "start"
  }, Object(external_wp_element_["createElement"])(external_wp_components_["FlexItem"], null, Object(external_wp_element_["createElement"])(external_wp_components_["Icon"], {
    icon: icon
  })), Object(external_wp_element_["createElement"])(external_wp_components_["FlexBlock"], {
    className: "edit-site-template-part-converter__option-label"
  }, label, Object(external_wp_element_["createElement"])("div", null, description)), Object(external_wp_element_["createElement"])(external_wp_components_["FlexItem"], {
    className: "edit-site-template-part-converter__checkbox"
  }, area === value && Object(external_wp_element_["createElement"])(external_wp_components_["Icon"], {
    icon: check["a" /* default */]
  }))))))), Object(external_wp_element_["createElement"])(external_wp_components_["Flex"], {
    className: "edit-site-template-part-converter__convert-modal-actions",
    justify: "flex-end"
  }, Object(external_wp_element_["createElement"])(external_wp_components_["FlexItem"], null, Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
    variant: "secondary",
    onClick: () => {
      setIsModalOpen(false);
      setTitle('');
    }
  }, Object(external_wp_i18n_["__"])('Cancel'))), Object(external_wp_element_["createElement"])(external_wp_components_["FlexItem"], null, Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
    variant: "primary",
    type: "submit"
  }, Object(external_wp_i18n_["__"])('Create'))))))));
}
//# sourceMappingURL=convert-to-template-part.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/template-part-converter/index.js


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
  } = Object(external_wp_data_["useSelect"])(select => {
    const {
      getSelectedBlockClientIds,
      getBlocksByClientId
    } = select(external_wp_blockEditor_["store"]);
    const selectedBlockClientIds = getSelectedBlockClientIds();
    return {
      clientIds: selectedBlockClientIds,
      blocks: getBlocksByClientId(selectedBlockClientIds)
    };
  }); // Allow converting a single template part to standard blocks.

  if (blocks.length === 1 && ((_blocks$ = blocks[0]) === null || _blocks$ === void 0 ? void 0 : _blocks$.name) === 'core/template-part') {
    return Object(external_wp_element_["createElement"])(ConvertToRegularBlocks, {
      clientId: clientIds[0]
    });
  }

  return Object(external_wp_element_["createElement"])(ConvertToTemplatePart, {
    clientIds: clientIds,
    blocks: blocks
  });
}
//# sourceMappingURL=index.js.map
// EXTERNAL MODULE: ./packages/icons/build-module/library/edit.js + 1 modules
var edit = __webpack_require__(271);

// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigate-to-link/index.js


/**
 * WordPress dependencies
 */







function NavigateToLink({
  type,
  id,
  activePage,
  onActivePageChange
}) {
  const post = Object(external_wp_data_["useSelect"])(select => type && id && type !== 'URL' && select(external_wp_coreData_["store"]).getEntityRecord('postType', type, id), [type, id]);
  const onClick = Object(external_wp_element_["useMemo"])(() => {
    if (!(post !== null && post !== void 0 && post.link)) return null;
    const path = Object(external_wp_url_["getPathAndQueryString"])(post.link);
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
  return onClick && Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
    icon: edit["a" /* default */],
    label: Object(external_wp_i18n_["__"])('Edit Page Template'),
    onClick: onClick
  });
}
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/block-editor/block-inspector-button.js


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
  } = Object(external_wp_data_["useSelect"])(select => ({
    shortcut: select(external_wp_keyboardShortcuts_["store"]).getShortcutRepresentation('core/edit-site/toggle-block-settings-sidebar'),
    isBlockInspectorOpen: select(build_module["g" /* store */]).getActiveComplementaryArea(store.name) === SIDEBAR_BLOCK
  }), []);
  const {
    enableComplementaryArea,
    disableComplementaryArea
  } = Object(external_wp_data_["useDispatch"])(build_module["g" /* store */]);
  const label = isBlockInspectorOpen ? Object(external_wp_i18n_["__"])('Hide more settings') : Object(external_wp_i18n_["__"])('Show more settings');
  return Object(external_wp_element_["createElement"])(external_wp_components_["MenuItem"], {
    onClick: () => {
      if (isBlockInspectorOpen) {
        disableComplementaryArea(STORE_NAME);
        Object(external_wp_a11y_["speak"])(Object(external_wp_i18n_["__"])('Block settings closed'));
      } else {
        enableComplementaryArea(STORE_NAME, SIDEBAR_BLOCK);
        Object(external_wp_a11y_["speak"])(Object(external_wp_i18n_["__"])('Additional settings are now available in the Editor block settings sidebar'));
      } // Close dropdown menu.


      onClick();
    },
    shortcut: shortcut
  }, label);
}
//# sourceMappingURL=block-inspector-button.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/block-editor/index.js



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
    page,
    deviceType
  } = Object(external_wp_data_["useSelect"])(select => {
    const {
      getSettings,
      getEditedPostType,
      getPage,
      __experimentalGetPreviewDeviceType
    } = select(store);
    return {
      settings: getSettings(setIsInserterOpen),
      templateType: getEditedPostType(),
      page: getPage(),
      deviceType: __experimentalGetPreviewDeviceType()
    };
  }, [setIsInserterOpen]);
  const [blocks, onInput, onChange] = Object(external_wp_coreData_["useEntityBlockEditor"])('postType', templateType);
  const {
    setPage
  } = Object(external_wp_data_["useDispatch"])(store);
  const resizedCanvasStyles = Object(external_wp_blockEditor_["__experimentalUseResizeCanvas"])(deviceType, true);
  const ref = Object(external_wp_blockEditor_["__unstableUseMouseMoveTypingReset"])();
  const contentRef = Object(external_wp_element_["useRef"])();
  const mergedRefs = Object(external_wp_compose_["useMergeRefs"])([contentRef, Object(external_wp_blockEditor_["__unstableUseTypingObserver"])()]);
  return Object(external_wp_element_["createElement"])(external_wp_blockEditor_["BlockEditorProvider"], {
    settings: settings,
    value: blocks,
    onInput: onInput,
    onChange: onChange,
    useSubRegistry: false
  }, Object(external_wp_element_["createElement"])(TemplatePartConverter, null), Object(external_wp_element_["createElement"])(external_wp_blockEditor_["__experimentalLinkControl"].ViewerFill, null, Object(external_wp_element_["useCallback"])(fillProps => Object(external_wp_element_["createElement"])(NavigateToLink, Object(esm_extends["a" /* default */])({}, fillProps, {
    activePage: page,
    onActivePageChange: setPage
  })), [page])), Object(external_wp_element_["createElement"])(SidebarInspectorFill, null, Object(external_wp_element_["createElement"])(external_wp_blockEditor_["BlockInspector"], null)), Object(external_wp_element_["createElement"])(external_wp_blockEditor_["BlockTools"], {
    className: "edit-site-visual-editor",
    __unstableContentRef: contentRef
  }, Object(external_wp_element_["createElement"])(external_wp_blockEditor_["__unstableIframe"], {
    style: resizedCanvasStyles,
    head: Object(external_wp_element_["createElement"])(external_wp_blockEditor_["__unstableEditorStyles"], {
      styles: settings.styles
    }),
    ref: ref,
    contentRef: mergedRefs
  }, Object(external_wp_element_["createElement"])(external_wp_blockEditor_["BlockList"], {
    className: "edit-site-block-editor__block-list",
    __experimentalLayout: LAYOUT
  })), Object(external_wp_element_["createElement"])(external_wp_blockEditor_["__unstableBlockSettingsMenuFirstItem"], null, ({
    onClose
  }) => Object(external_wp_element_["createElement"])(BlockInspectorButton, {
    onClick: onClose
  }))));
}
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/keyboard-shortcuts/index.js
/**
 * WordPress dependencies
 */






/**
 * Internal dependencies
 */





function KeyboardShortcuts() {
  const isListViewOpen = Object(external_wp_data_["useSelect"])(select => select(store).isListViewOpened());
  const isBlockInspectorOpen = Object(external_wp_data_["useSelect"])(select => select(build_module["g" /* store */]).getActiveComplementaryArea(store.name) === SIDEBAR_BLOCK, []);
  const {
    redo,
    undo
  } = Object(external_wp_data_["useDispatch"])(external_wp_coreData_["store"]);
  const {
    setIsListViewOpened
  } = Object(external_wp_data_["useDispatch"])(store);
  const {
    enableComplementaryArea,
    disableComplementaryArea
  } = Object(external_wp_data_["useDispatch"])(build_module["g" /* store */]);
  Object(external_wp_keyboardShortcuts_["useShortcut"])('core/edit-site/undo', event => {
    undo();
    event.preventDefault();
  }, {
    bindGlobal: true
  });
  Object(external_wp_keyboardShortcuts_["useShortcut"])('core/edit-site/redo', event => {
    redo();
    event.preventDefault();
  }, {
    bindGlobal: true
  });
  Object(external_wp_keyboardShortcuts_["useShortcut"])('core/edit-site/toggle-list-view', Object(external_wp_element_["useCallback"])(() => {
    setIsListViewOpened(!isListViewOpen);
  }, [isListViewOpen, setIsListViewOpened]), {
    bindGlobal: true
  });
  Object(external_wp_keyboardShortcuts_["useShortcut"])('core/edit-site/toggle-block-settings-sidebar', event => {
    // This shortcut has no known clashes, but use preventDefault to prevent any
    // obscure shortcuts from triggering.
    event.preventDefault();

    if (isBlockInspectorOpen) {
      disableComplementaryArea(STORE_NAME);
    } else {
      enableComplementaryArea(STORE_NAME, SIDEBAR_BLOCK);
    }
  }, {
    bindGlobal: true
  });
  return null;
}

function KeyboardShortcutsRegister() {
  // Registering the shortcuts
  const {
    registerShortcut
  } = Object(external_wp_data_["useDispatch"])(external_wp_keyboardShortcuts_["store"]);
  Object(external_wp_element_["useEffect"])(() => {
    registerShortcut({
      name: 'core/edit-site/undo',
      category: 'global',
      description: Object(external_wp_i18n_["__"])('Undo your last changes.'),
      keyCombination: {
        modifier: 'primary',
        character: 'z'
      }
    });
    registerShortcut({
      name: 'core/edit-site/redo',
      category: 'global',
      description: Object(external_wp_i18n_["__"])('Redo your last undo.'),
      keyCombination: {
        modifier: 'primaryShift',
        character: 'z'
      }
    });
    registerShortcut({
      name: 'core/edit-site/toggle-list-view',
      category: 'global',
      description: Object(external_wp_i18n_["__"])('Open the block list view.'),
      keyCombination: {
        modifier: 'access',
        character: 'o'
      }
    });
    registerShortcut({
      name: 'core/edit-site/toggle-block-settings-sidebar',
      category: 'global',
      description: Object(external_wp_i18n_["__"])('Show or hide the block settings sidebar.'),
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
// EXTERNAL MODULE: external ["wp","htmlEntities"]
var external_wp_htmlEntities_ = __webpack_require__(40);

// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/template-preview.js


/**
 * WordPress dependencies
 */



function TemplatePreview({
  rawContent,
  blockContext
}) {
  const blocks = Object(external_wp_element_["useMemo"])(() => rawContent ? Object(external_wp_blocks_["parse"])(rawContent) : [], [rawContent]);

  if (!blocks || blocks.length === 0) {
    return null;
  }

  if (blockContext) {
    return Object(external_wp_element_["createElement"])("div", {
      className: "edit-site-navigation-panel__preview"
    }, Object(external_wp_element_["createElement"])(external_wp_blockEditor_["BlockContextProvider"], {
      value: blockContext
    }, Object(external_wp_element_["createElement"])(external_wp_blockEditor_["BlockPreview"], {
      blocks: blocks,
      viewportWidth: 1200
    })));
  }

  return Object(external_wp_element_["createElement"])("div", {
    className: "edit-site-navigation-panel__preview"
  }, Object(external_wp_element_["createElement"])(external_wp_blockEditor_["BlockPreview"], {
    blocks: blocks,
    viewportWidth: 1200
  }));
}
//# sourceMappingURL=template-preview.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/content-navigation-item.js


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
  const [isPreviewVisible, setIsPreviewVisible] = Object(external_wp_element_["useState"])(false);
  const previewContent = Object(external_wp_data_["useSelect"])(select => {
    var _template$content;

    if (!isPreviewVisible) {
      return null;
    }

    const template = select(external_wp_coreData_["store"]).__experimentalGetTemplateForLink(item.link);

    return template === null || template === void 0 ? void 0 : (_template$content = template.content) === null || _template$content === void 0 ? void 0 : _template$content.raw;
  }, [isPreviewVisible]);
  const {
    setPage,
    setIsNavigationPanelOpened
  } = Object(external_wp_data_["useDispatch"])(store);
  const onActivateItem = Object(external_wp_element_["useCallback"])(() => {
    const {
      type,
      slug,
      link,
      id
    } = item;
    setPage({
      type,
      slug,
      path: Object(external_wp_url_["getPathAndQueryString"])(link),
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

  return Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationItem"], {
    className: "edit-site-navigation-panel__content-item",
    item: `${item.taxonomy || item.type}-${item.id}`,
    title: getTitle(item) || Object(external_wp_i18n_["__"])('(no title)'),
    onClick: onActivateItem,
    onMouseEnter: () => setIsPreviewVisible(true),
    onMouseLeave: () => setIsPreviewVisible(false)
  }), isPreviewVisible && previewContent && Object(external_wp_element_["createElement"])(NavigationPanelPreviewFill, null, Object(external_wp_element_["createElement"])(TemplatePreview, {
    rawContent: previewContent,
    blockContext: {
      postType: item.type,
      postId: item.id
    }
  })));
}
//# sourceMappingURL=content-navigation-item.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/utils.js
/**
 * External dependencies
 */
 // @see packages/block-editor/src/components/inserter/search-items.js

const normalizeInput = input => Object(external_lodash_["deburr"])(input).replace(/^\//, '').toLowerCase();
const normalizedSearch = (title, search) => -1 !== normalizeInput(title).indexOf(normalizeInput(search));
//# sourceMappingURL=utils.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/template-navigation-item.js


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
  } = Object(external_wp_data_["useSelect"])(select => {
    var _item$title;

    return 'wp_template' === item.type ? select(external_wp_editor_["store"]).__experimentalGetTemplateInfo(item) : {
      title: (item === null || item === void 0 ? void 0 : (_item$title = item.title) === null || _item$title === void 0 ? void 0 : _item$title.rendered) || (item === null || item === void 0 ? void 0 : item.slug),
      description: ''
    };
  }, []);
  const {
    setTemplate,
    setTemplatePart,
    setIsNavigationPanelOpened
  } = Object(external_wp_data_["useDispatch"])(store);
  const [isPreviewVisible, setIsPreviewVisible] = Object(external_wp_element_["useState"])(false);

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

  return Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationItem"], {
    className: "edit-site-navigation-panel__template-item",
    item: `${item.type}-${item.id}`
  }, Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
    onClick: onActivateItem,
    onMouseEnter: () => setIsPreviewVisible(true),
    onMouseLeave: () => setIsPreviewVisible(false)
  }, Object(external_wp_element_["createElement"])("span", {
    className: "edit-site-navigation-panel__info-wrapper"
  }, Object(external_wp_element_["createElement"])("div", {
    className: "edit-site-navigation-panel__template-item-title"
  }, 'draft' === item.status && Object(external_wp_element_["createElement"])("em", null, Object(external_wp_i18n_["__"])('[Draft]')), title), description && Object(external_wp_element_["createElement"])("div", {
    className: "edit-site-navigation-panel__template-item-description"
  }, description))), isPreviewVisible && Object(external_wp_element_["createElement"])(NavigationPanelPreviewFill, null, Object(external_wp_element_["createElement"])(TemplatePreview, {
    rawContent: item.content.raw
  })));
}
//# sourceMappingURL=template-navigation-item.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/search-results.js


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

  const itemInfos = Object(external_wp_data_["useSelect"])(select => {
    if (itemType === null || items === null) {
      return [];
    }

    if (itemType === 'wp_template') {
      const {
        __experimentalGetTemplateInfo: getTemplateInfo
      } = select(external_wp_editor_["store"]);
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
  const itemInfosMap = Object(external_wp_element_["useMemo"])(() => Object(external_lodash_["keyBy"])(itemInfos, 'slug'), [itemInfos]);
  const itemsFiltered = Object(external_wp_element_["useMemo"])(() => {
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
  const itemsSorted = Object(external_wp_element_["useMemo"])(() => {
    if (!itemsFiltered) {
      return [];
    }

    return Object(external_lodash_["sortBy"])(itemsFiltered, [({
      slug
    }) => {
      const {
        title
      } = itemInfosMap[slug];
      return !normalizedSearch(title, search);
    }]);
  }, [itemsFiltered, search]);
  const ItemComponent = itemType === 'wp_template' || itemType === 'wp_template_part' ? TemplateNavigationItem : ContentNavigationItem;
  return Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationGroup"], {
    title: Object(external_wp_i18n_["__"])('Search results')
  }, Object(external_lodash_["map"])(itemsSorted, item => Object(external_wp_element_["createElement"])(ItemComponent, {
    item: item,
    key: `${item.taxonomy || item.type}-${item.id}`
  })));
}
//# sourceMappingURL=search-results.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/use-debounced-search.js
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
  const [search, setSearch] = Object(external_wp_element_["useState"])(''); // The value used to actually perform the search query.

  const [searchQuery, setSearchQuery] = Object(external_wp_element_["useState"])('');
  const [isDebouncing, setIsDebouncing] = Object(external_wp_element_["useState"])(false);
  Object(external_wp_element_["useEffect"])(() => {
    setIsDebouncing(false);
  }, [searchQuery]);
  const debouncedSetSearchQuery = Object(external_wp_element_["useCallback"])(Object(external_lodash_["debounce"])(setSearchQuery, SEARCH_DEBOUNCE_IN_MS), [setSearchQuery]);
  const onSearch = Object(external_wp_element_["useCallback"])(value => {
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
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/menus/content-pages.js


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
  } = Object(external_wp_data_["useSelect"])(select => {
    const {
      getEntityRecords,
      hasFinishedResolution
    } = select(external_wp_coreData_["store"]);
    const getEntityRecordsArgs = ['postType', 'page', {
      search: searchQuery
    }];
    const hasResolvedPosts = hasFinishedResolution('getEntityRecords', getEntityRecordsArgs);
    return {
      pages: getEntityRecords(...getEntityRecordsArgs),
      isResolved: hasResolvedPosts
    };
  }, [searchQuery]);
  const shouldShowLoadingForDebouncing = search && isDebouncing;
  const showLoading = !isResolved || shouldShowLoadingForDebouncing;
  return Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationMenu"], {
    menu: MENU_CONTENT_PAGES,
    title: Object(external_wp_i18n_["__"])('Pages'),
    parentMenu: MENU_ROOT,
    hasSearch: true,
    onSearch: onSearch,
    search: search,
    isSearchDebouncing: isDebouncing || !isResolved
  }, search && !isDebouncing && Object(external_wp_element_["createElement"])(SearchResults, {
    items: pages,
    search: search,
    disableFilter: true
  }), !search && (pages === null || pages === void 0 ? void 0 : pages.map(page => Object(external_wp_element_["createElement"])(ContentNavigationItem, {
    item: page,
    key: `${page.type}-${page.id}`
  }))), showLoading && Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationItem"], {
    title: Object(external_wp_i18n_["__"])('Loading…'),
    isText: true
  }));
}
//# sourceMappingURL=content-pages.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/menus/content-categories.js


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
  } = Object(external_wp_data_["useSelect"])(select => {
    const {
      getEntityRecords,
      hasFinishedResolution
    } = select(external_wp_coreData_["store"]);
    const getEntityRecordsArgs = ['taxonomy', 'category', {
      search: searchQuery
    }];
    const hasResolvedPosts = hasFinishedResolution('getEntityRecords', getEntityRecordsArgs);
    return {
      categories: getEntityRecords(...getEntityRecordsArgs),
      isResolved: hasResolvedPosts
    };
  }, [searchQuery]);
  const shouldShowLoadingForDebouncing = search && isDebouncing;
  const showLoading = !isResolved || shouldShowLoadingForDebouncing;
  return Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationMenu"], {
    menu: MENU_CONTENT_CATEGORIES,
    title: Object(external_wp_i18n_["__"])('Categories'),
    parentMenu: MENU_ROOT,
    hasSearch: true,
    onSearch: onSearch,
    search: search,
    isSearchDebouncing: isDebouncing || !isResolved
  }, search && !isDebouncing && Object(external_wp_element_["createElement"])(SearchResults, {
    items: categories,
    search: search,
    disableFilter: true
  }), !search && (categories === null || categories === void 0 ? void 0 : categories.map(category => Object(external_wp_element_["createElement"])(ContentNavigationItem, {
    item: category,
    key: `${category.taxonomy}-${category.id}`
  }))), showLoading && Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationItem"], {
    title: Object(external_wp_i18n_["__"])('Loading…'),
    isText: true
  }));
}
//# sourceMappingURL=content-categories.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/menus/content-posts.js


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
  } = Object(external_wp_data_["useSelect"])(select => {
    const {
      getEntityRecords,
      getEditedEntityRecord,
      hasFinishedResolution
    } = select(external_wp_coreData_["store"]);
    const getEntityRecodsArgs = ['postType', 'post', {
      search: searchQuery
    }];
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
  } = Object(external_wp_data_["useDispatch"])(store);
  const onActivateFrontItem = Object(external_wp_element_["useCallback"])(() => {
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
  return Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationMenu"], {
    menu: MENU_CONTENT_POSTS,
    title: Object(external_wp_i18n_["__"])('Posts'),
    parentMenu: MENU_ROOT,
    hasSearch: true,
    onSearch: onSearch,
    search: search,
    isSearchDebouncing: isDebouncing || !isResolved
  }, search && !isDebouncing && Object(external_wp_element_["createElement"])(SearchResults, {
    items: posts,
    search: search,
    disableFilter: true
  }), !search && Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, showOnFront === 'posts' && Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationItem"], {
    item: 'post-/',
    title: Object(external_wp_i18n_["__"])('All Posts'),
    onClick: onActivateFrontItem
  }), posts === null || posts === void 0 ? void 0 : posts.map(post => Object(external_wp_element_["createElement"])(ContentNavigationItem, {
    item: post,
    key: `${post.type}-${post.id}`
  }))), showLoading && Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationItem"], {
    title: Object(external_wp_i18n_["__"])('Loading…'),
    isText: true
  }));
}
//# sourceMappingURL=content-posts.js.map
// EXTERNAL MODULE: ./packages/icons/build-module/icon/index.js
var build_module_icon = __webpack_require__(113);

// CONCATENATED MODULE: ./packages/edit-site/build-module/utils/get-closest-available-template.js
/**
 * External dependencies
 */

function getClosestAvailableTemplate(slug, templates) {
  const template = Object(external_lodash_["find"])(templates, {
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

  return Object(external_lodash_["find"])(templates, {
    slug: 'index'
  });
}
//# sourceMappingURL=get-closest-available-template.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/new-template-dropdown.js


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
  } = Object(external_wp_data_["useSelect"])(select => {
    const {
      __experimentalGetDefaultTemplateTypes: getDefaultTemplateTypes
    } = select(external_wp_editor_["store"]);
    const templateEntities = select(external_wp_coreData_["store"]).getEntityRecords('postType', 'wp_template');
    return {
      defaultTemplateTypes: getDefaultTemplateTypes(),
      templates: templateEntities
    };
  }, []);
  const {
    addTemplate
  } = Object(external_wp_data_["useDispatch"])(store);

  const createTemplate = slug => {
    const closestAvailableTemplate = getClosestAvailableTemplate(slug, templates);
    const {
      title,
      description
    } = Object(external_lodash_["find"])(defaultTemplateTypes, {
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

  const existingTemplateSlugs = Object(external_lodash_["map"])(templates, 'slug');
  const missingTemplates = Object(external_lodash_["filter"])(defaultTemplateTypes, template => Object(external_lodash_["includes"])(TEMPLATES_NEW_OPTIONS, template.slug) && !Object(external_lodash_["includes"])(existingTemplateSlugs, template.slug));

  if (!missingTemplates.length) {
    return null;
  }

  return Object(external_wp_element_["createElement"])(external_wp_components_["DropdownMenu"], {
    className: "edit-site-navigation-panel__new-template-dropdown",
    icon: null,
    label: Object(external_wp_i18n_["__"])('Add Template'),
    popoverProps: {
      noArrow: false
    },
    toggleProps: {
      children: Object(external_wp_element_["createElement"])(build_module_icon["a" /* default */], {
        icon: plus["a" /* default */]
      }),
      isSmall: true,
      variant: 'tertiary'
    }
  }, ({
    onClose
  }) => Object(external_wp_element_["createElement"])(external_wp_components_["NavigableMenu"], {
    className: "edit-site-navigation-panel__new-template-popover"
  }, Object(external_wp_element_["createElement"])(external_wp_components_["MenuGroup"], {
    label: Object(external_wp_i18n_["__"])('Add Template')
  }, Object(external_lodash_["map"])(missingTemplates, ({
    title,
    description,
    slug
  }) => Object(external_wp_element_["createElement"])(external_wp_components_["MenuItem"], {
    info: description,
    key: slug,
    onClick: () => {
      createTemplate(slug);
      onClose();
    }
  }, title)))));
}
//# sourceMappingURL=new-template-dropdown.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/menus/templates-sub.js


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
  const templatesFiltered = Object(external_wp_element_["useMemo"])(() => {
    var _templates$filter$map, _templates$filter;

    return (_templates$filter$map = templates === null || templates === void 0 ? void 0 : (_templates$filter = templates.filter(({
      location
    }) => location === menu)) === null || _templates$filter === void 0 ? void 0 : _templates$filter.map(({
      template
    }) => template)) !== null && _templates$filter$map !== void 0 ? _templates$filter$map : [];
  }, [menu, templates]);
  return Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationMenu"], {
    menu: menu,
    title: title,
    parentMenu: MENU_TEMPLATES,
    isEmpty: templatesFiltered.length === 0
  }, Object(external_lodash_["map"])(templatesFiltered, template => Object(external_wp_element_["createElement"])(TemplateNavigationItem, {
    item: template,
    key: `wp_template-${template.id}`
  })));
}
//# sourceMappingURL=templates-sub.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/menus/templates.js


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
  const [search, setSearch] = Object(external_wp_element_["useState"])('');
  const onSearch = Object(external_wp_element_["useCallback"])(value => {
    setSearch(value);
  });
  const {
    templates,
    showOnFront
  } = Object(external_wp_data_["useSelect"])(select => {
    const {
      getEntityRecords,
      getEditedEntityRecord
    } = select(external_wp_coreData_["store"]);
    return {
      templates: getEntityRecords('postType', 'wp_template', {
        per_page: -1
      }),
      showOnFront: getEditedEntityRecord('root', 'site').show_on_front
    };
  }, []);
  const templatesWithLocation = Object(external_wp_element_["useMemo"])(() => {
    if (!templates) {
      return null;
    }

    const unusedTemplates = getUnusedTemplates(templates, showOnFront);
    const templateLocations = getTemplatesLocationMap(templates);
    return templates.map(template => ({
      template,
      location: Object(external_lodash_["find"])(unusedTemplates, {
        slug: template.slug
      }) ? MENU_TEMPLATES_UNUSED : templateLocations[template.slug]
    }));
  }, [templates]);
  const topLevelTemplates = Object(external_wp_element_["useMemo"])(() => {
    var _templatesWithLocatio, _templatesWithLocatio2;

    return (_templatesWithLocatio = templatesWithLocation === null || templatesWithLocation === void 0 ? void 0 : (_templatesWithLocatio2 = templatesWithLocation.filter(({
      location
    }) => location === MENU_TEMPLATES)) === null || _templatesWithLocatio2 === void 0 ? void 0 : _templatesWithLocatio2.map(({
      template
    }) => template)) !== null && _templatesWithLocatio !== void 0 ? _templatesWithLocatio : [];
  }, [templatesWithLocation]);
  return Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationMenu"], {
    menu: MENU_TEMPLATES,
    title: Object(external_wp_i18n_["__"])('Templates'),
    titleAction: Object(external_wp_element_["createElement"])(NewTemplateDropdown, null),
    parentMenu: MENU_ROOT,
    hasSearch: true,
    onSearch: onSearch,
    search: search
  }, search && Object(external_wp_element_["createElement"])(SearchResults, {
    items: templates,
    search: search
  }), !search && Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, Object(external_lodash_["map"])(topLevelTemplates, template => Object(external_wp_element_["createElement"])(TemplateNavigationItem, {
    item: template,
    key: `wp_template-${template.id}`
  })), Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationItem"], {
    navigateToMenu: MENU_TEMPLATES_POSTS,
    title: Object(external_wp_i18n_["__"])('Post templates'),
    hideIfTargetMenuEmpty: true
  }), Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationItem"], {
    navigateToMenu: MENU_TEMPLATES_PAGES,
    title: Object(external_wp_i18n_["__"])('Page templates'),
    hideIfTargetMenuEmpty: true
  }), Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationItem"], {
    navigateToMenu: MENU_TEMPLATES_GENERAL,
    title: Object(external_wp_i18n_["__"])('General templates'),
    hideIfTargetMenuEmpty: true
  }), Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationItem"], {
    navigateToMenu: MENU_TEMPLATES_UNUSED,
    title: Object(external_wp_i18n_["__"])('Unused templates'),
    hideIfTargetMenuEmpty: true
  })), !search && templates === null && Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationItem"], {
    title: Object(external_wp_i18n_["__"])('Loading…'),
    isText: true
  }), Object(external_wp_element_["createElement"])(TemplatesSubMenu, {
    menu: MENU_TEMPLATES_POSTS,
    title: Object(external_wp_i18n_["__"])('Post templates'),
    templates: templatesWithLocation
  }), Object(external_wp_element_["createElement"])(TemplatesSubMenu, {
    menu: MENU_TEMPLATES_PAGES,
    title: Object(external_wp_i18n_["__"])('Page templates'),
    templates: templatesWithLocation
  }), Object(external_wp_element_["createElement"])(TemplatesSubMenu, {
    menu: MENU_TEMPLATES_GENERAL,
    title: Object(external_wp_i18n_["__"])('General templates'),
    templates: templatesWithLocation
  }), Object(external_wp_element_["createElement"])(TemplatesSubMenu, {
    menu: MENU_TEMPLATES_UNUSED,
    title: Object(external_wp_i18n_["__"])('Unused templates'),
    templates: templatesWithLocation
  }));
}
//# sourceMappingURL=templates.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/menus/template-parts-sub.js


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
  return Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationMenu"], {
    menu: menu,
    title: title,
    parentMenu: MENU_TEMPLATE_PARTS,
    isEmpty: !templateParts || templateParts.length === 0
  }, Object(external_lodash_["map"])(templateParts, templatePart => Object(external_wp_element_["createElement"])(TemplateNavigationItem, {
    item: templatePart,
    key: `wp_template_part-${templatePart.id}`
  })));
}
//# sourceMappingURL=template-parts-sub.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/menus/template-parts.js


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
  const [search, setSearch] = Object(external_wp_element_["useState"])('');
  const onSearch = Object(external_wp_element_["useCallback"])(value => {
    setSearch(value);
  });
  const {
    isLoading,
    templateParts,
    templatePartsByArea
  } = Object(external_wp_data_["useSelect"])(select => {
    const templatePartRecords = select(external_wp_coreData_["store"]).getEntityRecords('postType', 'wp_template_part');

    const _templateParts = templatePartRecords || [];

    const _templatePartsByArea = Object(external_lodash_["groupBy"])(_templateParts, 'area');

    return {
      isLoading: templatePartRecords === null,
      templateParts: _templateParts,
      templatePartsByArea: _templatePartsByArea
    };
  }, []);
  return Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationMenu"], {
    menu: MENU_TEMPLATE_PARTS,
    title: Object(external_wp_i18n_["__"])('Template Parts'),
    parentMenu: MENU_ROOT,
    hasSearch: true,
    onSearch: onSearch,
    search: search
  }, search && Object(external_wp_element_["createElement"])(SearchResults, {
    items: templateParts,
    search: search
  }), !search && TEMPLATE_PARTS_SUB_MENUS.map(({
    title,
    menu
  }) => Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationItem"], {
    key: `template-parts-navigate-to-${menu}`,
    navigateToMenu: menu,
    title: title,
    hideIfTargetMenuEmpty: true
  })), !search && isLoading && Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationItem"], {
    title: Object(external_wp_i18n_["__"])('Loading…'),
    isText: true
  })), TEMPLATE_PARTS_SUB_MENUS.map(({
    area,
    menu,
    title
  }) => Object(external_wp_element_["createElement"])(TemplatePartsSubMenu, {
    key: `template-parts-menu-${menu}`,
    menu: menu,
    title: title,
    templateParts: templatePartsByArea[area]
  })));
}
//# sourceMappingURL=template-parts.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/menus/index.js


/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */







function SiteMenu() {
  return Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationMenu"], null, Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationGroup"], {
    title: Object(external_wp_i18n_["__"])('Theme')
  }, Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationItem"], {
    title: Object(external_wp_i18n_["__"])('Templates'),
    navigateToMenu: MENU_TEMPLATES
  }), Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationItem"], {
    title: Object(external_wp_i18n_["__"])('Template Parts'),
    navigateToMenu: MENU_TEMPLATE_PARTS
  })), Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationGroup"], {
    title: Object(external_wp_i18n_["__"])('Content')
  }, Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationItem"], {
    title: Object(external_wp_i18n_["__"])('Pages'),
    navigateToMenu: MENU_CONTENT_PAGES
  }), Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationItem"], {
    title: Object(external_wp_i18n_["__"])('Categories'),
    navigateToMenu: MENU_CONTENT_CATEGORIES
  }), Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationItem"], {
    title: Object(external_wp_i18n_["__"])('Posts'),
    navigateToMenu: MENU_CONTENT_POSTS
  })), Object(external_wp_element_["createElement"])(TemplatesMenu, null), Object(external_wp_element_["createElement"])(TemplatePartsMenu, null), Object(external_wp_element_["createElement"])(ContentPagesMenu, null), Object(external_wp_element_["createElement"])(ContentCategoriesMenu, null), Object(external_wp_element_["createElement"])(ContentPostsMenu, null));
}
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/main-dashboard-button/index.js


/**
 * WordPress dependencies
 */

const slotName = '__experimentalMainDashboardButton';
const {
  Fill,
  Slot: MainDashboardButtonSlot
} = Object(external_wp_components_["createSlotFill"])(slotName);
const MainDashboardButton = Fill;

const main_dashboard_button_Slot = ({
  children
}) => {
  const slot = Object(external_wp_components_["__experimentalUseSlot"])(slotName);
  const hasFills = Boolean(slot.fills && slot.fills.length);

  if (!hasFills) {
    return children;
  }

  return Object(external_wp_element_["createElement"])(MainDashboardButtonSlot, {
    bubblesVirtually: true
  });
};

MainDashboardButton.Slot = main_dashboard_button_Slot;
/* harmony default export */ var main_dashboard_button = (MainDashboardButton);
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-panel/index.js


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
  } = Object(external_wp_data_["useSelect"])(select => {
    const {
      getEditedPostType,
      getEditedPostId,
      getNavigationPanelActiveMenu,
      getPage
    } = select(store);
    const {
      getEntityRecord
    } = select(external_wp_coreData_["store"]);
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
  } = Object(external_wp_data_["useDispatch"])(store);
  let activeItem;

  if (activeMenu !== MENU_ROOT) {
    if (activeMenu.startsWith('content')) {
      activeItem = `${postType}-${postId}`;
    } else {
      activeItem = `${editedPostType}-${editedPostId}`;
    }
  } // Ensures focus is moved to the panel area when it is activated
  // from a separate component (such as document actions in the header).


  const panelRef = Object(external_wp_element_["useRef"])();
  Object(external_wp_element_["useEffect"])(() => {
    if (isOpen) {
      panelRef.current.focus();
    }
  }, [activeMenu, isOpen]);

  const closeOnEscape = event => {
    if (event.keyCode === external_wp_keycodes_["ESCAPE"] && !event.defaultPrevented) {
      event.preventDefault();
      setIsNavigationPanelOpened(false);
    }
  };

  return (// eslint-disable-next-line jsx-a11y/no-static-element-interactions
    Object(external_wp_element_["createElement"])("div", {
      className: classnames_default()(`edit-site-navigation-panel`, {
        'is-open': isOpen
      }),
      ref: panelRef,
      tabIndex: "-1",
      onKeyDown: closeOnEscape
    }, Object(external_wp_element_["createElement"])("div", {
      className: "edit-site-navigation-panel__inner"
    }, Object(external_wp_element_["createElement"])("div", {
      className: "edit-site-navigation-panel__site-title-container"
    }, Object(external_wp_element_["createElement"])("div", {
      className: "edit-site-navigation-panel__site-title"
    }, Object(external_wp_htmlEntities_["decodeEntities"])(siteTitle))), Object(external_wp_element_["createElement"])("div", {
      className: "edit-site-navigation-panel__scroll-container"
    }, Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigation"], {
      activeItem: activeItem,
      activeMenu: activeMenu,
      onActivateMenu: setActive
    }, activeMenu === MENU_ROOT && Object(external_wp_element_["createElement"])(main_dashboard_button.Slot, null, Object(external_wp_element_["createElement"])(external_wp_components_["__experimentalNavigationBackButton"], {
      backButtonLabel: Object(external_wp_i18n_["__"])('Dashboard'),
      className: "edit-site-navigation-panel__back-to-dashboard",
      href: "index.php"
    })), Object(external_wp_element_["createElement"])(SiteMenu, null)))))
  );
};

/* harmony default export */ var navigation_panel = (NavigationPanel);
//# sourceMappingURL=index.js.map
// EXTERNAL MODULE: ./packages/icons/build-module/library/wordpress.js
var wordpress = __webpack_require__(329);

// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/navigation-toggle/index.js


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
  } = Object(external_wp_data_["useSelect"])(select => {
    const {
      getCurrentTemplateNavigationPanelSubMenu
    } = select(store);
    const {
      getEntityRecord,
      isResolving
    } = select(external_wp_coreData_["store"]);
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
  } = Object(external_wp_data_["useDispatch"])(store);

  const toggleNavigationPanel = () => {
    if (isOpen) {
      setIsNavigationPanelOpened(false);
      return;
    }

    openNavigationPanelToMenu(navigationPanelMenu);
  };

  let buttonIcon = Object(external_wp_element_["createElement"])(external_wp_components_["Icon"], {
    size: "36px",
    icon: wordpress["a" /* default */]
  });

  if (siteIconUrl) {
    buttonIcon = Object(external_wp_element_["createElement"])("img", {
      alt: Object(external_wp_i18n_["__"])('Site Icon'),
      className: "edit-site-navigation-toggle__site-icon",
      src: siteIconUrl
    });
  } else if (isRequestingSiteIcon) {
    buttonIcon = null;
  } else if (icon) {
    buttonIcon = Object(external_wp_element_["createElement"])(external_wp_components_["Icon"], {
      size: "36px",
      icon: icon
    });
  }

  return Object(external_wp_element_["createElement"])("div", {
    className: 'edit-site-navigation-toggle' + (isOpen ? ' is-open' : '')
  }, Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
    className: "edit-site-navigation-toggle__button has-icon",
    label: Object(external_wp_i18n_["__"])('Toggle navigation'),
    onClick: toggleNavigationPanel,
    showTooltip: true
  }, buttonIcon));
}

/* harmony default export */ var navigation_toggle = (NavigationToggle);
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/navigation-sidebar/index.js


/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */




const {
  Fill: NavigationPanelPreviewFill,
  Slot: NavigationPanelPreviewSlot
} = Object(external_wp_components_["createSlotFill"])('EditSiteNavigationPanelPreview');
function NavigationSidebar() {
  const isNavigationOpen = Object(external_wp_data_["useSelect"])(select => {
    return select(store).isNavigationOpened();
  });
  return Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, Object(external_wp_element_["createElement"])(navigation_toggle, {
    isOpen: isNavigationOpen
  }), Object(external_wp_element_["createElement"])(navigation_panel, {
    isOpen: isNavigationOpen
  }), Object(external_wp_element_["createElement"])(NavigationPanelPreviewSlot, null));
}
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/url-query-controller/index.js
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
  } = Object(external_wp_data_["useDispatch"])(store); // Set correct entity on load.

  Object(external_wp_element_["useEffect"])(() => {
    const url = window.location.href;
    const postId = Object(external_wp_url_["getQueryArg"])(url, 'postId');

    if (!postId) {
      showHomepage();
      return;
    }

    const postType = Object(external_wp_url_["getQueryArg"])(url, 'postType');

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
  Object(external_wp_element_["useEffect"])(() => {
    const newUrl = pageContext ? Object(external_wp_url_["addQueryArgs"])(window.location.href, pageContext) : Object(external_wp_url_["removeQueryArgs"])(window.location.href, 'postType', 'postId');
    window.history.replaceState({}, '', newUrl);
  }, [pageContext]);
  return null;
}

function useCurrentPageContext() {
  return Object(external_wp_data_["useSelect"])(select => {
    var _page$context, _page$context2;

    const {
      getEditedPostType,
      getEditedPostId,
      getPage
    } = select(store);
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
  });
}
//# sourceMappingURL=index.js.map
// EXTERNAL MODULE: ./packages/icons/build-module/library/close.js
var library_close = __webpack_require__(139);

// CONCATENATED MODULE: ./packages/edit-site/build-module/components/secondary-sidebar/inserter-sidebar.js



/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */


function InserterSidebar() {
  const {
    setIsInserterOpened
  } = Object(external_wp_data_["useDispatch"])(store);
  const insertionPoint = Object(external_wp_data_["useSelect"])(select => select(store).__experimentalGetInsertionPoint(), []);
  const isMobile = Object(external_wp_compose_["useViewportMatch"])('medium', '<');
  const [inserterDialogRef, inserterDialogProps] = Object(external_wp_compose_["__experimentalUseDialog"])({
    onClose: () => setIsInserterOpened(false)
  });
  return Object(external_wp_element_["createElement"])("div", Object(esm_extends["a" /* default */])({
    ref: inserterDialogRef
  }, inserterDialogProps, {
    className: "edit-site-editor__inserter-panel"
  }), Object(external_wp_element_["createElement"])("div", {
    className: "edit-site-editor__inserter-panel-header"
  }, Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
    icon: library_close["a" /* default */],
    onClick: () => setIsInserterOpened(false)
  })), Object(external_wp_element_["createElement"])("div", {
    className: "edit-site-editor__inserter-panel-content"
  }, Object(external_wp_element_["createElement"])(external_wp_blockEditor_["__experimentalLibrary"], {
    showInserterHelpPanel: true,
    shouldFocusBlock: isMobile,
    rootClientId: insertionPoint.rootClientId,
    __experimentalInsertionIndex: insertionPoint.insertionIndex
  })));
}
//# sourceMappingURL=inserter-sidebar.js.map
// EXTERNAL MODULE: ./packages/icons/build-module/library/close-small.js
var close_small = __webpack_require__(140);

// CONCATENATED MODULE: ./packages/edit-site/build-module/components/secondary-sidebar/list-view-sidebar.js


/**
 * WordPress dependencies
 */







/**
 * Internal dependencies
 */


function ListViewSidebar() {
  const {
    setIsListViewOpened
  } = Object(external_wp_data_["useDispatch"])(store);
  const {
    clearSelectedBlock,
    selectBlock
  } = Object(external_wp_data_["useDispatch"])(external_wp_blockEditor_["store"]);

  async function selectEditorBlock(clientId) {
    await clearSelectedBlock();
    selectBlock(clientId, -1);
  }

  const focusOnMountRef = Object(external_wp_compose_["useFocusOnMount"])('firstElement');
  const focusReturnRef = Object(external_wp_compose_["useFocusReturn"])();

  function closeOnEscape(event) {
    if (event.keyCode === external_wp_keycodes_["ESCAPE"] && !event.defaultPrevented) {
      setIsListViewOpened(false);
    }
  }

  const instanceId = Object(external_wp_compose_["useInstanceId"])(ListViewSidebar);
  const labelId = `edit-site-editor__list-view-panel-label-${instanceId}`;
  return (// eslint-disable-next-line jsx-a11y/no-static-element-interactions
    Object(external_wp_element_["createElement"])("div", {
      "aria-labelledby": labelId,
      className: "edit-site-editor__list-view-panel",
      onKeyDown: closeOnEscape
    }, Object(external_wp_element_["createElement"])("div", {
      className: "edit-site-editor__list-view-panel-header"
    }, Object(external_wp_element_["createElement"])("strong", {
      id: labelId
    }, Object(external_wp_i18n_["__"])('List view')), Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
      icon: close_small["a" /* default */],
      label: Object(external_wp_i18n_["__"])('Close list view sidebar'),
      onClick: () => setIsListViewOpened(false)
    })), Object(external_wp_element_["createElement"])("div", {
      className: "edit-site-editor__list-view-panel-content",
      ref: Object(external_wp_compose_["useMergeRefs"])([focusReturnRef, focusOnMountRef])
    }, Object(external_wp_element_["createElement"])(external_wp_blockEditor_["__experimentalListView"], {
      onSelect: selectEditorBlock,
      showNestedBlocks: true,
      __experimentalPersistentListViewFeatures: true
    })))
  );
}
//# sourceMappingURL=list-view-sidebar.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/components/editor/index.js


/**
 * WordPress dependencies
 */









/**
 * Internal dependencies
 */











const interfaceLabels = {
  secondarySidebar: Object(external_wp_i18n_["__"])('Block Library'),
  drawer: Object(external_wp_i18n_["__"])('Navigation Sidebar')
};

function Editor({
  initialSettings
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
  } = Object(external_wp_data_["useSelect"])(select => {
    const {
      isInserterOpened,
      isListViewOpened,
      getSettings,
      getEditedPostType,
      getEditedPostId,
      getPage,
      isNavigationOpened
    } = select(store);
    const {
      hasFinishedResolution,
      getEntityRecord
    } = select(external_wp_coreData_["store"]);
    const postType = getEditedPostType();
    const postId = getEditedPostId(); // The currently selected entity to display. Typically template or template part.

    return {
      isInserterOpen: isInserterOpened(),
      isListViewOpen: isListViewOpened(),
      sidebarIsOpened: !!select(build_module["g" /* store */]).getActiveComplementaryArea(store.name),
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
  } = Object(external_wp_data_["useDispatch"])(external_wp_editor_["store"]);
  const {
    setPage,
    setIsInserterOpened,
    updateSettings
  } = Object(external_wp_data_["useDispatch"])(store);
  Object(external_wp_element_["useEffect"])(() => {
    updateSettings(initialSettings);
  }, []); // Keep the defaultTemplateTypes in the core/editor settings too,
  // so that they can be selected with core/editor selectors in any editor.
  // This is needed because edit-site doesn't initialize with EditorProvider,
  // which internally uses updateEditorSettings as well.

  const {
    defaultTemplateTypes,
    defaultTemplatePartAreas
  } = settings;
  Object(external_wp_element_["useEffect"])(() => {
    updateEditorSettings({
      defaultTemplateTypes,
      defaultTemplatePartAreas
    });
  }, [defaultTemplateTypes, defaultTemplatePartAreas]);
  const [isEntitiesSavedStatesOpen, setIsEntitiesSavedStatesOpen] = Object(external_wp_element_["useState"])(false);
  const openEntitiesSavedStates = Object(external_wp_element_["useCallback"])(() => setIsEntitiesSavedStatesOpen(true), []);
  const closeEntitiesSavedStates = Object(external_wp_element_["useCallback"])(() => {
    setIsEntitiesSavedStatesOpen(false);
  }, []);
  const blockContext = Object(external_wp_element_["useMemo"])(() => ({ ...(page === null || page === void 0 ? void 0 : page.context),
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
  Object(external_wp_element_["useEffect"])(() => {
    if (isNavigationOpen) {
      document.body.classList.add('is-navigation-sidebar-open');
    } else {
      document.body.classList.remove('is-navigation-sidebar-open');
    }
  }, [isNavigationOpen]); // Don't render the Editor until the settings are set and loaded

  if (!(settings !== null && settings !== void 0 && settings.siteUrl)) {
    return null;
  }

  const secondarySidebar = () => {
    if (isInserterOpen) {
      return Object(external_wp_element_["createElement"])(InserterSidebar, null);
    }

    if (isListViewOpen) {
      return Object(external_wp_element_["createElement"])(external_wp_data_["AsyncModeProvider"], {
        value: "true"
      }, Object(external_wp_element_["createElement"])(ListViewSidebar, null));
    }

    return null;
  };

  return Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, Object(external_wp_element_["createElement"])(URLQueryController, null), Object(external_wp_element_["createElement"])(build_module["d" /* FullscreenMode */], {
    isActive: true
  }), Object(external_wp_element_["createElement"])(external_wp_editor_["UnsavedChangesWarning"], null), Object(external_wp_element_["createElement"])(external_wp_components_["SlotFillProvider"], null, Object(external_wp_element_["createElement"])(external_wp_coreData_["EntityProvider"], {
    kind: "root",
    type: "site"
  }, Object(external_wp_element_["createElement"])(external_wp_coreData_["EntityProvider"], {
    kind: "postType",
    type: templateType,
    id: entityId
  }, Object(external_wp_element_["createElement"])(external_wp_coreData_["EntityProvider"], {
    kind: "postType",
    type: "wp_global_styles",
    id: settings.__experimentalGlobalStylesUserEntityId
  }, Object(external_wp_element_["createElement"])(external_wp_blockEditor_["BlockContextProvider"], {
    value: blockContext
  }, Object(external_wp_element_["createElement"])(GlobalStylesProvider, {
    baseStyles: settings.__experimentalGlobalStylesBaseStyles
  }, Object(external_wp_element_["createElement"])(keyboard_shortcuts.Register, null), Object(external_wp_element_["createElement"])(SidebarComplementaryAreaFills, null), Object(external_wp_element_["createElement"])(build_module["e" /* InterfaceSkeleton */], {
    labels: interfaceLabels,
    drawer: Object(external_wp_element_["createElement"])(NavigationSidebar, null),
    secondarySidebar: secondarySidebar(),
    sidebar: sidebarIsOpened && Object(external_wp_element_["createElement"])(build_module["b" /* ComplementaryArea */].Slot, {
      scope: "core/edit-site"
    }),
    header: Object(external_wp_element_["createElement"])(Header, {
      openEntitiesSavedStates: openEntitiesSavedStates
    }),
    notices: Object(external_wp_element_["createElement"])(external_wp_editor_["EditorSnackbars"], null),
    content: Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, Object(external_wp_element_["createElement"])(external_wp_editor_["EditorNotices"], null), template && Object(external_wp_element_["createElement"])(BlockEditor, {
      setIsInserterOpen: setIsInserterOpened
    }), templateResolved && !template && (settings === null || settings === void 0 ? void 0 : settings.siteUrl) && entityId && Object(external_wp_element_["createElement"])(external_wp_components_["Notice"], {
      status: "warning",
      isDismissible: false
    }, Object(external_wp_i18n_["__"])("You attempted to edit an item that doesn't exist. Perhaps it was deleted?")), Object(external_wp_element_["createElement"])(keyboard_shortcuts, null)),
    actions: Object(external_wp_element_["createElement"])(external_wp_element_["Fragment"], null, isEntitiesSavedStatesOpen ? Object(external_wp_element_["createElement"])(external_wp_editor_["EntitiesSavedStates"], {
      close: closeEntitiesSavedStates
    }) : Object(external_wp_element_["createElement"])("div", {
      className: "edit-site-editor__toggle-save-panel"
    }, Object(external_wp_element_["createElement"])(external_wp_components_["Button"], {
      variant: "secondary",
      className: "edit-site-editor__toggle-save-panel-button",
      onClick: openEntitiesSavedStates,
      "aria-expanded": false
    }, Object(external_wp_i18n_["__"])('Open save panel')))),
    footer: Object(external_wp_element_["createElement"])(external_wp_blockEditor_["BlockBreadcrumb"], null)
  }), Object(external_wp_element_["createElement"])(external_wp_components_["Popover"].Slot, null), Object(external_wp_element_["createElement"])(external_wp_plugins_["PluginArea"], null))))))));
}

/* harmony default export */ var editor = (Editor);
//# sourceMappingURL=index.js.map
// CONCATENATED MODULE: ./packages/edit-site/build-module/index.js


/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */





/**
 * Initializes the site editor screen.
 *
 * @param {string} id       ID of the root element to render the screen in.
 * @param {Object} settings Editor settings.
 */

function initialize(id, settings) {
  settings.__experimentalFetchLinkSuggestions = (search, searchOptions) => Object(external_wp_coreData_["__experimentalFetchLinkSuggestions"])(search, searchOptions, settings);

  settings.__experimentalSpotlightEntityBlocks = ['core/template-part'];
  Object(external_wp_blockLibrary_["registerCoreBlocks"])();

  if (true) {
    Object(external_wp_blockLibrary_["__experimentalRegisterExperimentalCoreBlocks"])({
      enableFSEBlocks: true
    });
  }

  Object(external_wp_element_["render"])(Object(external_wp_element_["createElement"])(editor, {
    initialSettings: settings
  }), document.getElementById(id));
}


//# sourceMappingURL=index.js.map

/***/ }),

/***/ 5:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["blockEditor"]; }());

/***/ }),

/***/ 6:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["primitives"]; }());

/***/ }),

/***/ 62:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["blockLibrary"]; }());

/***/ }),

/***/ 64:
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["mediaUtils"]; }());

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