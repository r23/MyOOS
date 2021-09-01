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
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXPORTS
__webpack_require__.d(__webpack_exports__, {
  "I18nProvider": function() { return /* binding */ I18nProvider; },
  "useI18n": function() { return /* binding */ useI18n; },
  "withI18n": function() { return /* binding */ withI18n; }
});

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
;// CONCATENATED MODULE: external ["wp","element"]
var external_wp_element_namespaceObject = window["wp"]["element"];
;// CONCATENATED MODULE: external ["wp","i18n"]
var external_wp_i18n_namespaceObject = window["wp"]["i18n"];
;// CONCATENATED MODULE: ./packages/react-i18n/build-module/index.js



/**
 * External dependencies
 */
// Disable reason: Type-only import, this is fine. See https://github.com/typescript-eslint/typescript-eslint/issues/2661
// eslint-disable-next-line no-restricted-imports

/**
 * WordPress dependencies
 */



/**
 * Utility to make a new context value
 *
 * @param  i18n
 */
function makeContextValue(i18n) {
  return {
    __: i18n.__.bind(i18n),
    _x: i18n._x.bind(i18n),
    _n: i18n._n.bind(i18n),
    _nx: i18n._nx.bind(i18n),
    isRTL: i18n.isRTL.bind(i18n),
    hasTranslation: i18n.hasTranslation.bind(i18n)
  };
}

const I18nContext = (0,external_wp_element_namespaceObject.createContext)(makeContextValue(external_wp_i18n_namespaceObject.defaultI18n));

/**
 * The `I18nProvider` should be mounted above any localized components:
 *
 * @example
 * ```js
 * import { createI18n } from '@wordpress/react-i18n';
 * import { I18nProvider } from '@wordpress/react-i18n';
 * const i18n = createI18n();
 *
 * ReactDom.render(
 * 	<I18nProvider i18n={ i18n }>
 * 		<App />
 * 	</I18nProvider>,
 * 	el
 * );
 * ```
 *
 * You can also instantiate the provider without the `i18n` prop. In that case it will use the
 * default `I18n` instance exported from `@wordpress/i18n`.
 *
 * @param  props i18n provider props.
 * @return Children wrapped in the I18nProvider.
 */
function I18nProvider(props) {
  const {
    children,
    i18n = external_wp_i18n_namespaceObject.defaultI18n
  } = props;
  const [update, forceUpdate] = (0,external_wp_element_namespaceObject.useReducer)(() => [], []); // rerender translations whenever the i18n instance fires a change event

  (0,external_wp_element_namespaceObject.useEffect)(() => i18n.subscribe(forceUpdate), [i18n]);
  const value = (0,external_wp_element_namespaceObject.useMemo)(() => makeContextValue(i18n), [i18n, update]);
  return (0,external_wp_element_namespaceObject.createElement)(I18nContext.Provider, {
    value: value
  }, children);
}
/**
 * React hook providing access to i18n functions. It exposes the `__`, `_x`, `_n`, `_nx`,
 * `isRTL` and `hasTranslation` functions from [`@wordpress/i18n`](../i18n).
 * Refer to their documentation there.
 *
 * @example
 * ```js
 * import { useI18n } from '@wordpress/react-i18n';
 *
 * function MyComponent() {
 * 	const { __ } = useI18n();
 * 	return __( 'Hello, world!' );
 * }
 * ```
 */

const useI18n = () => (0,external_wp_element_namespaceObject.useContext)(I18nContext);

/**
 * React higher-order component that passes the i18n translate functions (the same set
 * as exposed by the `useI18n` hook) to the wrapped component as props.
 *
 * @example
 * ```js
 * import { withI18n } from '@wordpress/react-i18n';
 *
 * function MyComponent( { __ } ) {
 * 	return __( 'Hello, world!' );
 * }
 *
 * export default withI18n( MyComponent );
 * ```
 *
 * @param  InnerComponent React component to be wrapped and receive the i18n functions like `__`
 * @return The wrapped component
 */
function withI18n(InnerComponent) {
  const EnhancedComponent = props => {
    const i18nProps = useI18n();
    return (0,external_wp_element_namespaceObject.createElement)(InnerComponent, _extends({}, props, i18nProps));
  };

  const innerComponentName = InnerComponent.displayName || InnerComponent.name || 'Component';
  EnhancedComponent.displayName = `WithI18n(${innerComponentName})`;
  return EnhancedComponent;
}
//# sourceMappingURL=index.js.map
(window.wp = window.wp || {}).reactI18n = __webpack_exports__;
/******/ })()
;