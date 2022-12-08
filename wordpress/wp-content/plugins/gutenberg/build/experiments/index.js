/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
/******/ 	
/************************************************************************/
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
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "__dangerousOptInToUnstableAPIsOnlyForCoreModules": function() { return /* binding */ __dangerousOptInToUnstableAPIsOnlyForCoreModules; }
/* harmony export */ });
const CORE_MODULES_USING_EXPERIMENTS = ['@wordpress/data', '@wordpress/block-editor', '@wordpress/block-library', '@wordpress/blocks', '@wordpress/core-data', '@wordpress/date', '@wordpress/edit-site', '@wordpress/edit-widgets'];
const registeredExperiments = {};
/*
 * Warning for theme and plugin developers.
 *
 * The use of experimental developer APIs is intended for use by WordPress Core
 * and the Gutenberg plugin exclusively.
 *
 * Dangerously opting in to using these APIs is NOT RECOMMENDED. Furthermore,
 * the WordPress Core philosophy to strive to maintain backward compatibility
 * for third-party developers DOES NOT APPLY to experimental APIs.
 *
 * THE CONSENT STRING FOR OPTING IN TO THESE APIS MAY CHANGE AT ANY TIME AND
 * WITHOUT NOTICE. THIS CHANGE WILL BREAK EXISTING THIRD-PARTY CODE. SUCH A
 * CHANGE MAY OCCUR IN EITHER A MAJOR OR MINOR RELEASE.
 */

const requiredConsent = 'I know using unstable features means my plugin or theme will inevitably break on the next WordPress release.';
const __dangerousOptInToUnstableAPIsOnlyForCoreModules = (consent, moduleName) => {
  if (!CORE_MODULES_USING_EXPERIMENTS.includes(moduleName)) {
    throw new Error(`You tried to opt-in to unstable APIs as a module "${moduleName}". ` + 'This feature is only for JavaScript modules shipped with WordPress core. ' + 'Please do not use it in plugins and themes as the unstable APIs will be removed ' + 'without a warning. If you ignore this error and depend on unstable features, ' + 'your product will inevitably break on one of the next WordPress releases.');
  }

  if (moduleName in registeredExperiments) {
    throw new Error(`You tried to opt-in to unstable APIs as a module "${moduleName}" which is already registered. ` + 'This feature is only for JavaScript modules shipped with WordPress core. ' + 'Please do not use it in plugins and themes as the unstable APIs will be removed ' + 'without a warning. If you ignore this error and depend on unstable features, ' + 'your product will inevitably break on one of the next WordPress releases.');
  }

  if (consent !== requiredConsent) {
    throw new Error(`You tried to opt-in to unstable APIs without confirming you know the consequences. ` + 'This feature is only for JavaScript modules shipped with WordPress core. ' + 'Please do not use it in plugins and themes as the unstable APIs will removed ' + 'without a warning. If you ignore this error and depend on unstable features, ' + 'your product will inevitably break on the next WordPress release.');
  }

  registeredExperiments[moduleName] = {
    accessKey: {},
    apis: {}
  };
  return {
    register: experiments => {
      for (const key in experiments) {
        registeredExperiments[moduleName].apis[key] = experiments[key];
      }

      return registeredExperiments[moduleName].accessKey;
    },
    unlock: accessKey => {
      for (const experiment of Object.values(registeredExperiments)) {
        if (experiment.accessKey === accessKey) {
          return experiment.apis;
        }
      }

      throw new Error('There is no registered module matching the specified access key');
    }
  };
};

(window.wp = window.wp || {}).experiments = __webpack_exports__;
/******/ })()
;