"use strict";
(globalThis["webpackChunkgutenberg"] = globalThis["webpackChunkgutenberg"] || []).push([[384],{

/***/ 370:
/***/ ((__unused_webpack_module, __unused_webpack___webpack_exports__, __webpack_require__) => {

/* harmony import */ var _utils_interactivity__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(744);
/**
 * Internal dependencies
 */

const focusableSelectors = ['a[href]', 'area[href]', 'input:not([disabled]):not([type="hidden"]):not([aria-hidden])', 'select:not([disabled]):not([aria-hidden])', 'textarea:not([disabled]):not([aria-hidden])', 'button:not([disabled]):not([aria-hidden])', 'iframe', 'object', 'embed', '[contenteditable]', '[tabindex]:not([tabindex^="-"])'];
(0,_utils_interactivity__WEBPACK_IMPORTED_MODULE_0__/* .store */ .h)({
  effects: {
    core: {
      navigation: {
        initMenu: ({
          context,
          ref
        }) => {
          if (context.core.navigation.isMenuOpen) {
            const focusableElements = ref.querySelectorAll(focusableSelectors);
            context.core.navigation.modal = ref;
            context.core.navigation.firstFocusableElement = focusableElements[0];
            context.core.navigation.lastFocusableElement = focusableElements[focusableElements.length - 1];
          }
        },
        focusFirstElement: ({
          context,
          ref
        }) => {
          if (context.core.navigation.isMenuOpen) {
            ref.querySelector('.wp-block-navigation-item > *:first-child').focus();
          }
        }
      }
    }
  },
  selectors: {
    core: {
      navigation: {
        roleAttribute: ({
          context
        }) => {
          return context.core.navigation.overlay && context.core.navigation.isMenuOpen ? 'dialog' : '';
        }
      }
    }
  },
  actions: {
    core: {
      navigation: {
        openMenu: ({
          context,
          ref
        }) => {
          context.core.navigation.isMenuOpen = true;
          context.core.navigation.previousFocus = ref;

          if (context.core.navigation.overlay) {
            // It adds a `has-modal-open` class to the <html> root
            document.documentElement.classList.add('has-modal-open');
          }
        },
        closeMenu: ({
          context
        }) => {
          if (context.core.navigation.isMenuOpen) {
            context.core.navigation.isMenuOpen = false;

            if (context.core.navigation.modal.contains(window.document.activeElement)) {
              context.core.navigation.previousFocus.focus();
            }

            context.core.navigation.modal = null;
            context.core.navigation.previousFocus = null;

            if (context.core.navigation.overlay) {
              document.documentElement.classList.remove('has-modal-open');
            }
          }
        },
        toggleMenu: ({
          context,
          actions,
          ref
        }) => {
          if (context.core.navigation.isMenuOpen) {
            actions.core.navigation.closeMenu({
              context
            });
          } else {
            actions.core.navigation.openMenu({
              context,
              ref
            });
          }
        },
        handleMenuKeydown: ({
          actions,
          context,
          event
        }) => {
          if (context.core.navigation.isMenuOpen) {
            // If Escape close the menu
            if (event?.key === 'Escape' || event?.keyCode === 27) {
              actions.core.navigation.closeMenu({
                context
              });
              return;
            } // Trap focus if it is an overlay (main menu)


            if (context.core.navigation.overlay && (event.key === 'Tab' || event.keyCode === 9)) {
              // If shift + tab it change the direction
              if (event.shiftKey && window.document.activeElement === context.core.navigation.firstFocusableElement) {
                event.preventDefault();
                context.core.navigation.lastFocusableElement.focus();
              } else if (!event.shiftKey && window.document.activeElement === context.core.navigation.lastFocusableElement) {
                event.preventDefault();
                context.core.navigation.firstFocusableElement.focus();
              }
            }
          }
        },
        handleMenuFocusout: ({
          actions,
          context,
          event
        }) => {
          if (context.core.navigation.isMenuOpen) {
            // If focus is outside modal, and in the document, close menu
            // event.target === The element losing focus
            // event.relatedTarget === The element receiving focus (if any)
            // When focusout is outsite the document, `window.document.activeElement` doesn't change
            if (!context.core.navigation.modal.contains(event.relatedTarget) && event.target !== window.document.activeElement) {
              actions.core.navigation.closeMenu({
                context
              });
            }
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
/******/ __webpack_require__.O(0, [666], () => (__webpack_exec__(370)));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);