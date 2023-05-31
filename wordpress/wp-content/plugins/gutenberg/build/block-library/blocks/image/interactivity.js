"use strict";
(globalThis["webpackChunkgutenberg"] = globalThis["webpackChunkgutenberg"] || []).push([[9],{

/***/ 9:
/***/ ((__unused_webpack_module, __unused_webpack___webpack_exports__, __webpack_require__) => {

/* harmony import */ var _utils_interactivity__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(744);
/**
 * Internal dependencies
 */

const focusableSelectors = ['a[href]', 'area[href]', 'input:not([disabled]):not([type="hidden"]):not([aria-hidden])', 'select:not([disabled]):not([aria-hidden])', 'textarea:not([disabled]):not([aria-hidden])', 'button:not([disabled]):not([aria-hidden])', 'iframe', 'object', 'embed', '[contenteditable]', '[tabindex]:not([tabindex^="-"])'];
(0,_utils_interactivity__WEBPACK_IMPORTED_MODULE_0__/* .store */ .h)({
  actions: {
    core: {
      image: {
        showLightbox: ({
          context
        }) => {
          context.core.image.initialized = true;
          context.core.image.lightboxEnabled = true;
          context.core.image.lastFocusedElement = window.document.activeElement;
          context.core.image.scrollPosition = window.scrollY;
          document.documentElement.classList.add('has-lightbox-open');
        },
        hideLightbox: async ({
          context,
          event
        }) => {
          if (context.core.image.lightboxEnabled) {
            // If scrolling, wait a moment before closing the lightbox.
            if (event.type === 'mousewheel' && Math.abs(window.scrollY - context.core.image.scrollPosition) < 5) {
              return;
            }

            document.documentElement.classList.remove('has-lightbox-open');
            context.core.image.lightboxEnabled = false;
            context.core.image.lastFocusedElement.focus();
          }
        },
        handleKeydown: ({
          context,
          actions,
          event
        }) => {
          if (context.core.image.lightboxEnabled) {
            if (event.key === 'Tab' || event.keyCode === 9) {
              // If shift + tab it change the direction
              if (event.shiftKey && window.document.activeElement === context.core.image.firstFocusableElement) {
                event.preventDefault();
                context.core.image.lastFocusableElement.focus();
              } else if (!event.shiftKey && window.document.activeElement === context.core.image.lastFocusableElement) {
                event.preventDefault();
                context.core.image.firstFocusableElement.focus();
              }
            }

            if (event.key === 'Escape' || event.keyCode === 27) {
              actions.core.image.hideLightbox({
                context,
                event
              });
            }
          }
        }
      }
    }
  },
  selectors: {
    core: {
      image: {
        roleAttribute: ({
          context
        }) => {
          return context.core.image.lightboxEnabled ? 'dialog' : '';
        }
      }
    }
  },
  effects: {
    core: {
      image: {
        initLightbox: async ({
          context,
          ref
        }) => {
          if (context.core.image.lightboxEnabled) {
            const focusableElements = ref.querySelectorAll(focusableSelectors);
            context.core.image.firstFocusableElement = focusableElements[0];
            context.core.image.lastFocusableElement = focusableElements[focusableElements.length - 1];
            ref.querySelector('.close-button').focus();
          }
        }
      }
    }
  }
});

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, [666], () => (__webpack_exec__(9)));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);