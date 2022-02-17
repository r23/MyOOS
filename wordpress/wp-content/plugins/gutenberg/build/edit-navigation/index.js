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
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
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
  "__unstableCreateMenuPreloadingMiddleware": function() { return /* reexport */ createMenuPreloadingMiddleware; },
  "initialize": function() { return /* binding */ initialize; }
});

// NAMESPACE OBJECT: ./packages/edit-navigation/build-module/store/resolvers.js
var resolvers_namespaceObject = {};
__webpack_require__.r(resolvers_namespaceObject);
__webpack_require__.d(resolvers_namespaceObject, {
  "getNavigationPostForMenu": function() { return getNavigationPostForMenu; }
});

// NAMESPACE OBJECT: ./packages/edit-navigation/build-module/store/selectors.js
var selectors_namespaceObject = {};
__webpack_require__.r(selectors_namespaceObject);
__webpack_require__.d(selectors_namespaceObject, {
  "getNavigationPostForMenu": function() { return selectors_getNavigationPostForMenu; },
  "getSelectedMenuId": function() { return getSelectedMenuId; },
  "hasResolvedNavigationPost": function() { return hasResolvedNavigationPost; },
  "isInserterOpened": function() { return isInserterOpened; }
});

// NAMESPACE OBJECT: ./packages/edit-navigation/build-module/store/actions.js
var actions_namespaceObject = {};
__webpack_require__.r(actions_namespaceObject);
__webpack_require__.d(actions_namespaceObject, {
  "saveNavigationPost": function() { return saveNavigationPost; },
  "setIsInserterOpened": function() { return setIsInserterOpened; },
  "setSelectedMenuId": function() { return setSelectedMenuId; }
});

// NAMESPACE OBJECT: ./packages/interface/build-module/store/actions.js
var store_actions_namespaceObject = {};
__webpack_require__.r(store_actions_namespaceObject);
__webpack_require__.d(store_actions_namespaceObject, {
  "disableComplementaryArea": function() { return disableComplementaryArea; },
  "enableComplementaryArea": function() { return enableComplementaryArea; },
  "pinItem": function() { return pinItem; },
  "setFeatureDefaults": function() { return setFeatureDefaults; },
  "setFeatureValue": function() { return setFeatureValue; },
  "toggleFeature": function() { return toggleFeature; },
  "unpinItem": function() { return unpinItem; }
});

// NAMESPACE OBJECT: ./packages/interface/build-module/store/selectors.js
var store_selectors_namespaceObject = {};
__webpack_require__.r(store_selectors_namespaceObject);
__webpack_require__.d(store_selectors_namespaceObject, {
  "getActiveComplementaryArea": function() { return getActiveComplementaryArea; },
  "isFeatureActive": function() { return isFeatureActive; },
  "isItemPinned": function() { return isItemPinned; }
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
;// CONCATENATED MODULE: external ["wp","i18n"]
var external_wp_i18n_namespaceObject = window["wp"]["i18n"];
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/constants/index.js
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

const NAVIGATION_POST_POST_TYPE = 'navigationPost';
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
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/store/reducer.js
/**
 * WordPress dependencies
 */

/**
 * Reducer keeping track of selected menu ID.
 *
 * @param {number} state  Current state.
 * @param {Object} action Dispatched action.
 * @return {Object} Updated state.
 */

function selectedMenuId() {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'SET_SELECTED_MENU_ID':
      return action.menuId;
  }

  return state;
}
/**
 * Reducer tracking whether the inserter is open.
 *
 * @param {boolean|Object} state        Current state.
 * @param {Object}         action       Dispatched action.
 * @param {string}         action.type  String indicating action type.
 * @param {boolean}        action.value Flag indicating whether the panel should be open/close.
 */

function blockInserterPanel() {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'SET_IS_INSERTER_OPENED':
      return action.value;
  }

  return state;
}

/* harmony default export */ var reducer = ((0,external_wp_data_namespaceObject.combineReducers)({
  selectedMenuId,
  blockInserterPanel
}));
//# sourceMappingURL=reducer.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/store/utils.js
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
 * Get the internal record id from block.
 *
 * @typedef  {Object} Attributes
 * @property {string}     __internalRecordId The internal record id.
 * @typedef  {Object} Block
 * @property {Attributes} attributes         The attributes of the block.
 *
 * @param    {Block}      block              The block.
 * @return {string} The internal record id.
 */

function getRecordIdFromBlock(block) {
  return block.attributes.__internalRecordId;
}
/**
 * Add internal record id to block's attributes.
 *
 * @param {Block}  block    The block.
 * @param {string} recordId The record id.
 * @return {Block} The updated block.
 */

function addRecordIdToBlock(block, recordId) {
  return { ...block,
    attributes: { ...(block.attributes || {}),
      __internalRecordId: recordId
    }
  };
}
/**
 * Checks if a given block should be persisted as a menu item.
 *
 * @param {Object} block Block to check.
 * @return {boolean} True if a given block should be persisted as a menu item, false otherwise.
 */

const isBlockSupportedInNav = block => ['core/navigation-link', 'core/navigation-submenu'].includes(block.name);
//# sourceMappingURL=utils.js.map
;// CONCATENATED MODULE: external "lodash"
var external_lodash_namespaceObject = window["lodash"];
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/store/transform.js
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

function blockToMenuItem(block, menuItem, parentId, blockPosition, menuId) {
  menuItem = (0,external_lodash_namespaceObject.omit)(menuItem, 'menus', 'meta', '_links');
  menuItem.content = (0,external_lodash_namespaceObject.get)(menuItem.content, 'raw', menuItem.content);
  let attributes;

  if (isBlockSupportedInNav(block)) {
    attributes = blockAttributesToMenuItem(block.attributes);
  } else {
    attributes = {
      type: 'block',
      content: (0,external_wp_blocks_namespaceObject.serialize)(block)
    };
  }

  return { ...menuItem,
    ...attributes,
    content: attributes.content || '',
    id: getRecordIdFromBlock(block),
    menu_order: blockPosition + 1,
    menus: menuId,
    parent: !parentId ? 0 : parentId,
    status: 'publish'
  };
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
 * @return {WPNavMenuItem} the menu item (converted from block attributes).
 */

const blockAttributesToMenuItem = _ref => {
  var _type;

  let {
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
  } = _ref;

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
  // The menuItem should be in menu_order sort order.
  const sortedItems = (0,external_lodash_namespaceObject.sortBy)(menuItems, 'menu_order');
  const blocks = sortedItems.map(menuItem => {
    var _menuItem$children;

    if (menuItem.type === 'block') {
      const [block] = (0,external_wp_blocks_namespaceObject.parse)(menuItem.content.raw);

      if (!block) {
        return (0,external_wp_blocks_namespaceObject.createBlock)('core/freeform', {
          content: menuItem.content
        });
      }

      return block;
    }

    const attributes = menuItemToBlockAttributes(menuItem); // If there are children recurse to build those nested blocks.

    const nestedBlocks = (_menuItem$children = menuItem.children) !== null && _menuItem$children !== void 0 && _menuItem$children.length ? mapMenuItemsToBlocks(menuItem.children) : []; // Create a submenu block when there are inner blocks, or just a link
    // for a standalone item.

    const itemBlockName = nestedBlocks !== null && nestedBlocks !== void 0 && nestedBlocks.length ? 'core/navigation-submenu' : 'core/navigation-link'; // Create block with nested "innerBlocks".

    return (0,external_wp_blocks_namespaceObject.createBlock)(itemBlockName, attributes, nestedBlocks);
  });
  return (0,external_lodash_namespaceObject.zip)(blocks, sortedItems).map(_ref2 => {
    let [block, menuItem] = _ref2;
    return addRecordIdToBlock(block, menuItem.id);
  });
} // A few parameters are using snake case, let's embrace that for convenience:

/* eslint-disable camelcase */

/**
 * Convert block attributes to menu item.
 *
 * @param {WPNavMenuItem} menuItem the menu item to be converted to block attributes.
 * @return {Object} the block attributes converted from the WPNavMenuItem item.
 */


function menuItemToBlockAttributes(_ref3) {
  var _object;

  let {
    title: menuItemTitleField,
    xfn,
    classes,
    attr_title,
    object,
    object_id,
    description,
    url,
    type: menuItemTypeField,
    target
  } = _ref3;

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
}
/* eslint-enable camelcase */

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

function createDataTree(dataset) {
  let id = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'id';
  let relation = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 'parent';
  const hashTable = Object.create(null);
  const dataTree = [];

  for (const data of dataset) {
    hashTable[data[id]] = { ...data,
      children: []
    };
  }

  for (const data of dataset) {
    if (data[relation]) {
      hashTable[data[relation]] = hashTable[data[relation]] || {};
      hashTable[data[relation]].children = hashTable[data[relation]].children || [];
      hashTable[data[relation]].children.push(hashTable[data[id]]);
    } else {
      dataTree.push(hashTable[data[id]]);
    }
  }

  return dataTree;
}
//# sourceMappingURL=transform.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/store/resolvers.js
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

const getNavigationPostForMenu = menuId => async _ref => {
  let {
    registry,
    dispatch
  } = _ref;

  if (!menuId) {
    return;
  }

  const stubPost = createStubPost(menuId); // Persist an empty post to warm up the state

  dispatch(persistPost(stubPost)); // Dispatch startResolution to skip the execution of the real getEntityRecord resolver - it would
  // issue an http request and fail.

  const args = [NAVIGATION_POST_KIND, NAVIGATION_POST_POST_TYPE, stubPost.id];
  registry.dispatch(external_wp_coreData_namespaceObject.store).startResolution('getEntityRecord', args); // Now let's create a proper one hydrated using actual menu items

  const menuItems = await registry.resolveSelect(external_wp_coreData_namespaceObject.store).getMenuItems(menuItemsQuery(menuId));
  const navigationBlock = createNavigationBlock(menuItems); // Persist the actual post containing the navigation block

  const builtPost = createStubPost(menuId, navigationBlock);
  dispatch(persistPost(builtPost)); // Dispatch finishResolution to conclude startResolution dispatched earlier

  registry.dispatch(external_wp_coreData_namespaceObject.store).finishResolution('getEntityRecord', args);
};

const createStubPost = function (menuId) {
  let navigationBlock = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
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

const persistPost = post => _ref2 => {
  let {
    registry
  } = _ref2;
  registry.dispatch(external_wp_coreData_namespaceObject.store).receiveEntityRecords(NAVIGATION_POST_KIND, NAVIGATION_POST_POST_TYPE, post, {
    id: post.id
  }, false);
};
/**
 * Converts an adjacency list of menuItems into a navigation block.
 *
 * @param {Array} menuItems a list of menu items
 * @return {Object} Navigation block
 */


function createNavigationBlock(menuItems) {
  const innerBlocks = menuItemsToBlocks(menuItems);
  return (0,external_wp_blocks_namespaceObject.createBlock)('core/navigation', {
    orientation: 'vertical'
  }, innerBlocks);
}
//# sourceMappingURL=resolvers.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/store/selectors.js
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

const selectors_getNavigationPostForMenu = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => (state, menuId) => {
  // When the record is unavailable, calling getEditedEntityRecord triggers a http
  // request via it's related resolver. Let's return nothing until getNavigationPostForMenu
  // resolver marks the record as resolved.
  if (!hasResolvedNavigationPost(state, menuId)) {
    return null;
  }

  return select(external_wp_coreData_namespaceObject.store).getEditedEntityRecord(NAVIGATION_POST_KIND, NAVIGATION_POST_POST_TYPE, buildNavigationPostId(menuId));
});
/**
 * Returns true if the navigation post related to menuId was already resolved.
 *
 * @param {number} menuId The id of menu.
 * @return {boolean} True if the navigation post related to menuId was already resolved, false otherwise.
 */

const hasResolvedNavigationPost = (0,external_wp_data_namespaceObject.createRegistrySelector)(select => (state, menuId) => {
  return select(external_wp_coreData_namespaceObject.store).hasFinishedResolution('getEntityRecord', [NAVIGATION_POST_KIND, NAVIGATION_POST_POST_TYPE, buildNavigationPostId(menuId)]);
});
/**
 * Returns true if the inserter is opened.
 *
 * @param {Object} state Global application state.
 * @return {boolean} Whether the inserter is opened.
 */

function isInserterOpened() {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
  return !!(state !== null && state !== void 0 && state.blockInserterPanel);
}
//# sourceMappingURL=selectors.js.map
;// CONCATENATED MODULE: external ["wp","notices"]
var external_wp_notices_namespaceObject = window["wp"]["notices"];
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/store/constants.js
/**
 * Module Constants
 */
const STORE_NAME = 'core/edit-navigation';
//# sourceMappingURL=constants.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/store/actions.js
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
 * Returns an action object used to select menu.
 *
 * @param {number} menuId The menu ID.
 * @return {Object} Action object.
 */

function setSelectedMenuId(menuId) {
  return {
    type: 'SET_SELECTED_MENU_ID',
    menuId
  };
}
/**
 * Converts all the blocks into menu items and submits a batch request to save everything at once.
 *
 * @param {Object} post A navigation post to process
 * @return {Function} An action creator
 */

const saveNavigationPost = post => async _ref => {
  let {
    registry,
    dispatch
  } = _ref;
  const lock = await registry.dispatch(external_wp_coreData_namespaceObject.store).__unstableAcquireStoreLock(STORE_NAME, ['savingMenu'], {
    exclusive: true
  });

  try {
    const menuId = post.meta.menuId; // Save menu

    await registry.dispatch(external_wp_coreData_namespaceObject.store).saveEditedEntityRecord('root', 'menu', menuId);
    const error = registry.select(external_wp_coreData_namespaceObject.store).getLastEntitySaveError('root', 'menu', menuId);

    if (error) {
      throw new Error(error.message);
    } // Save menu items


    const updatedBlocks = await dispatch(batchSaveMenuItems(post.blocks[0], menuId)); // Clear "stub" navigation post edits to avoid a false "dirty" state.

    registry.dispatch(external_wp_coreData_namespaceObject.store).receiveEntityRecords(NAVIGATION_POST_KIND, NAVIGATION_POST_POST_TYPE, post, undefined);
    const updatedPost = { ...post,
      blocks: [updatedBlocks]
    };
    registry.dispatch(external_wp_coreData_namespaceObject.store).receiveEntityRecords(NAVIGATION_POST_KIND, NAVIGATION_POST_POST_TYPE, updatedPost, undefined);
    registry.dispatch(external_wp_notices_namespaceObject.store).createSuccessNotice((0,external_wp_i18n_namespaceObject.__)('Navigation saved.'), {
      type: 'snackbar'
    });
  } catch (saveError) {
    const errorMessage = saveError ? (0,external_wp_i18n_namespaceObject.sprintf)(
    /* translators: %s: The text of an error message (potentially untranslated). */
    (0,external_wp_i18n_namespaceObject.__)("Unable to save: '%s'"), saveError.message) : (0,external_wp_i18n_namespaceObject.__)('Unable to save: An error ocurred.');
    registry.dispatch(external_wp_notices_namespaceObject.store).createErrorNotice(errorMessage, {
      type: 'snackbar'
    });
  } finally {
    registry.dispatch(external_wp_coreData_namespaceObject.store).__unstableReleaseStoreLock(lock);
  }
};
/**
 * Executes appropriate insert, update, and delete operations to turn the current
 * menu (with id=menuId) into one represented by the passed navigation block.
 *
 * @param {Object} navigationBlock The navigation block representing the desired state of the menu.
 * @param {number} menuId          Menu Id to process.
 * @return {Function} An action creator
 */

const batchSaveMenuItems = (navigationBlock, menuId) => async _ref2 => {
  let {
    dispatch,
    registry
  } = _ref2;
  // Make sure all the existing menu items are available before proceeding
  const oldMenuItems = await registry.resolveSelect(external_wp_coreData_namespaceObject.store).getMenuItems({
    menus: menuId,
    per_page: -1
  }); // Insert placeholders for new menu items to have an ID to work with.
  // We need that in case these new items have any children. If so,
  // we need to provide a parent id that we don't have yet.

  const navBlockWithRecordIds = await dispatch(batchInsertPlaceholderMenuItems(navigationBlock)); // Update menu items. This is separate from deleting, because there
  // are no consistency guarantees and we don't want to delete something
  // that was a parent node before another node takes it place.

  const navBlockAfterUpdates = await dispatch(batchUpdateMenuItems(navBlockWithRecordIds, menuId)); // Delete menu items

  const deletedIds = (0,external_lodash_namespaceObject.difference)(oldMenuItems.map(_ref3 => {
    let {
      id
    } = _ref3;
    return id;
  }), blocksTreeToList(navBlockAfterUpdates).map(getRecordIdFromBlock));
  await dispatch(batchDeleteMenuItems(deletedIds));
  return navBlockAfterUpdates;
};
/**
 * Creates a menu item for every block that doesn't have an associated menuItem.
 * Sends a batch request with one POST /wp/v2/menu-items for every created menu item.
 *
 * @param {Object} navigationBlock A navigation block to find created menu items in.
 * @return {Function} An action creator
 */


const batchInsertPlaceholderMenuItems = navigationBlock => async _ref4 => {
  let {
    registry
  } = _ref4;
  const blocksWithoutRecordId = blocksTreeToList(navigationBlock).filter(block => isBlockSupportedInNav(block) && !getRecordIdFromBlock(block));
  const tasks = blocksWithoutRecordId.map(() => _ref5 => {
    let {
      saveEntityRecord
    } = _ref5;
    return saveEntityRecord('root', 'menuItem', {
      title: (0,external_wp_i18n_namespaceObject.__)('Menu item'),
      url: '#placeholder',
      menu_order: 1
    });
  });
  const results = await registry.dispatch(external_wp_coreData_namespaceObject.store).__experimentalBatch(tasks); // Return an updated navigation block with all the IDs in

  const blockToResult = new Map((0,external_lodash_namespaceObject.zip)(blocksWithoutRecordId, results));
  return mapBlocksTree(navigationBlock, block => {
    if (!blockToResult.has(block)) {
      return block;
    }

    return addRecordIdToBlock(block, blockToResult.get(block).id);
  });
};
/**
 * Updates every menu item where a related block has changed.
 * Sends a batch request with one PUT /wp/v2/menu-items for every updated menu item.
 *
 * @param {Object} navigationBlock A navigation block to find updated menu items in.
 * @param {number} menuId          Menu ID.
 * @return {Function} An action creator
 */


const batchUpdateMenuItems = (navigationBlock, menuId) => async _ref6 => {
  let {
    registry
  } = _ref6;
  const allMenuItems = blocksTreeToAnnotatedList(navigationBlock);
  const unsupportedMenuItems = allMenuItems.filter(_ref7 => {
    let {
      block
    } = _ref7;
    return !isBlockSupportedInNav(block);
  }).map(_ref8 => {
    let {
      block
    } = _ref8;
    return block.name;
  });

  if (unsupportedMenuItems.length) {
    window.console.warn((0,external_wp_i18n_namespaceObject.sprintf)( // translators: %s: Name of block (i.e. core/legacy-widget)
    (0,external_wp_i18n_namespaceObject.__)('The following blocks haven\'t been saved because they are not supported: "%s".'), unsupportedMenuItems.join('", "')));
  }

  const updatedMenuItems = allMenuItems // Filter out unsupported blocks
  .filter(_ref9 => {
    let {
      block
    } = _ref9;
    return isBlockSupportedInNav(block);
  }) // Transform the blocks into menu items
  .map(_ref10 => {
    let {
      block,
      parentBlock,
      childIndex
    } = _ref10;
    return blockToMenuItem(block, registry.select(external_wp_coreData_namespaceObject.store).getMenuItem(getRecordIdFromBlock(block)), getRecordIdFromBlock(parentBlock), childIndex, menuId);
  }) // Filter out menu items without any edits
  .filter(menuItem => {
    // Update an existing entity record.
    registry.dispatch(external_wp_coreData_namespaceObject.store).editEntityRecord('root', 'menuItem', menuItem.id, menuItem, {
      undoIgnore: true
    });
    return registry.select(external_wp_coreData_namespaceObject.store).hasEditsForEntityRecord('root', 'menuItem', menuItem.id);
  }); // Map the edited menu items to batch tasks

  const tasks = updatedMenuItems.map(menuItem => _ref11 => {
    let {
      saveEditedEntityRecord
    } = _ref11;
    return saveEditedEntityRecord('root', 'menuItem', menuItem.id);
  });
  await registry.dispatch(external_wp_coreData_namespaceObject.store).__experimentalBatch(tasks); // Throw on failure. @TODO failures should be thrown in core-data

  updatedMenuItems.forEach(menuItem => {
    const failure = registry.select(external_wp_coreData_namespaceObject.store).getLastEntitySaveError('root', 'menuItem', menuItem.id);

    if (failure) {
      throw new Error(failure.message);
    }
  }); // Return an updated navigation block reflecting the changes persisted in the batch update.

  return mapBlocksTree(navigationBlock, block => {
    if (!isBlockSupportedInNav(block)) {
      return block;
    }

    const updatedMenuItem = registry.select(external_wp_coreData_namespaceObject.store).getMenuItem(getRecordIdFromBlock(block));
    return addRecordIdToBlock({ ...block,
      attributes: menuItemToBlockAttributes(updatedMenuItem)
    }, updatedMenuItem.id);
  });
};
/**
 * Deletes multiple menu items.
 * Sends a batch request with one DELETE /wp/v2/menu-items for every deleted menu item.
 *
 * @param {Object} deletedIds A list of menu item ids to delete
 * @return {Function} An action creator
 */


const batchDeleteMenuItems = deletedIds => async _ref12 => {
  let {
    registry
  } = _ref12;
  const deleteBatch = deletedIds.map(id => async _ref13 => {
    let {
      deleteEntityRecord
    } = _ref13;
    const success = await deleteEntityRecord('root', 'menuItem', id, {
      force: true
    }); // @TODO failures should be thrown in core-data

    if (!success) {
      throw new Error(id);
    }

    return success;
  });
  return await registry.dispatch(external_wp_coreData_namespaceObject.store).__experimentalBatch(deleteBatch);
};
/**
 * Turns a recursive list of blocks into a flat list of blocks annotated with
 * their child index and parent block.
 *
 * @param {Object} parentBlock A parent block to flatten
 * @return {Object} A flat list of blocks, annotated by their index and parent ID, consisting
 * 							    of all the input blocks and all the inner blocks in the tree.
 */


function blocksTreeToAnnotatedList(parentBlock) {
  return (parentBlock.innerBlocks || []).flatMap((innerBlock, index) => [{
    block: innerBlock,
    parentBlock,
    childIndex: index
  }].concat(blocksTreeToAnnotatedList(innerBlock)));
}

function blocksTreeToList(parentBlock) {
  return blocksTreeToAnnotatedList(parentBlock).map(_ref14 => {
    let {
      block
    } = _ref14;
    return block;
  });
}
/**
 * Maps one tree of blocks into another tree by invoking a callback on every node.
 *
 * @param {Object}   block       The root of the mapped tree.
 * @param {Function} callback    The callback to invoke.
 * @param {Object}   parentBlock Internal. The current parent block.
 * @param {number}   idx         Internal. The current child index.
 * @return {Object} A mapped tree.
 */


function mapBlocksTree(block, callback) {
  let parentBlock = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
  let idx = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 0;
  return { ...callback(block, parentBlock, idx),
    innerBlocks: (block.innerBlocks || []).map((innerBlock, index) => mapBlocksTree(innerBlock, callback, block, index))
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
//# sourceMappingURL=actions.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/store/index.js
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
  selectors: selectors_namespaceObject,
  resolvers: resolvers_namespaceObject,
  actions: actions_namespaceObject,
  persist: ['selectedMenuId'],
  __experimentalUseThunks: true
};
/**
 * Store definition for the edit navigation namespace.
 *
 * @see https://github.com/WordPress/gutenberg/blob/HEAD/packages/data/README.md#createReduxStore
 *
 * @type {Object}
 */

const store = (0,external_wp_data_namespaceObject.createReduxStore)(STORE_NAME, storeConfig); // Once we build a more generic persistence plugin that works across types of stores
// we'd be able to replace this with a register call.

(0,external_wp_data_namespaceObject.registerStore)(STORE_NAME, storeConfig);
//# sourceMappingURL=index.js.map
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
;// CONCATENATED MODULE: external ["wp","hooks"]
var external_wp_hooks_namespaceObject = window["wp"]["hooks"];
;// CONCATENATED MODULE: external ["wp","compose"]
var external_wp_compose_namespaceObject = window["wp"]["compose"];
;// CONCATENATED MODULE: external ["wp","blockEditor"]
var external_wp_blockEditor_namespaceObject = window["wp"]["blockEditor"];
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/filters/add-navigation-editor-custom-appender.js



/**
 * WordPress dependencies
 */





function CustomAppender() {
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.InnerBlocks.ButtonBlockAppender, {
    isToggle: true
  });
}

function EnhancedNavigationBlock(_ref) {
  let {
    blockEdit: BlockEdit,
    ...props
  } = _ref;
  const clientId = props.clientId;
  const {
    noBlockSelected,
    isSelected,
    isImmediateParentOfSelectedBlock,
    selectedBlockHasDescendants
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    var _getClientIdsOfDescen;

    const {
      getClientIdsOfDescendants,
      hasSelectedInnerBlock,
      getSelectedBlockClientId
    } = select(external_wp_blockEditor_namespaceObject.store);

    const _isImmediateParentOfSelectedBlock = hasSelectedInnerBlock(clientId, false);

    const selectedBlockId = getSelectedBlockClientId();

    const _selectedBlockHasDescendants = !!((_getClientIdsOfDescen = getClientIdsOfDescendants([selectedBlockId])) !== null && _getClientIdsOfDescen !== void 0 && _getClientIdsOfDescen.length);

    return {
      isSelected: selectedBlockId === clientId,
      noBlockSelected: !selectedBlockId,
      isImmediateParentOfSelectedBlock: _isImmediateParentOfSelectedBlock,
      selectedBlockHasDescendants: _selectedBlockHasDescendants
    };
  }, [clientId]);
  const customAppender = noBlockSelected || isSelected || isImmediateParentOfSelectedBlock && !selectedBlockHasDescendants ? CustomAppender : false;
  return (0,external_wp_element_namespaceObject.createElement)(BlockEdit, _extends({}, props, {
    customAppender: customAppender
  }));
}

const addNavigationEditorCustomAppender = (0,external_wp_compose_namespaceObject.createHigherOrderComponent)(BlockEdit => props => {
  if (props.name !== 'core/navigation') {
    return (0,external_wp_element_namespaceObject.createElement)(BlockEdit, props);
  } // Use a separate component so that `useSelect` only run on the navigation block.


  return (0,external_wp_element_namespaceObject.createElement)(EnhancedNavigationBlock, _extends({
    blockEdit: BlockEdit
  }, props));
}, 'withNavigationEditorCustomAppender');
/* harmony default export */ var add_navigation_editor_custom_appender = (() => (0,external_wp_hooks_namespaceObject.addFilter)('editor.BlockEdit', 'core/edit-navigation/with-navigation-editor-custom-appender', addNavigationEditorCustomAppender));
//# sourceMappingURL=add-navigation-editor-custom-appender.js.map
;// CONCATENATED MODULE: external ["wp","components"]
var external_wp_components_namespaceObject = window["wp"]["components"];
;// CONCATENATED MODULE: external ["wp","primitives"]
var external_wp_primitives_namespaceObject = window["wp"]["primitives"];
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
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/hooks/use-menu-entity.js
/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */


function useMenuEntity(menuId) {
  const {
    editEntityRecord
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_coreData_namespaceObject.store);
  const menuEntityData = [MENU_KIND, MENU_POST_TYPE, menuId];
  const {
    editedMenu,
    hasLoadedEditedMenu
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    return {
      editedMenu: menuId && select(external_wp_coreData_namespaceObject.store).getEditedEntityRecord(...menuEntityData),
      hasLoadedEditedMenu: select(external_wp_coreData_namespaceObject.store).hasFinishedResolution('getEditedEntityRecord', [...menuEntityData])
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
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/hooks/use-menu-entity-prop.js
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
  return (0,external_wp_coreData_namespaceObject.useEntityProp)(MENU_KIND, MENU_POST_TYPE, prop, menuId);
}
//# sourceMappingURL=use-menu-entity-prop.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/hooks/use-navigation-editor.js
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
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_coreData_namespaceObject.store);
  const [selectedMenuId, setSelectedMenuId] = useSelectedMenuId();
  const [hasFinishedInitialLoad, setHasFinishedInitialLoad] = (0,external_wp_element_namespaceObject.useState)(false);
  const {
    editedMenu,
    hasLoadedEditedMenu
  } = useMenuEntity(selectedMenuId);
  const {
    menus,
    hasLoadedMenus
  } = (0,external_wp_data_namespaceObject.useSelect)(getMenusData, []);
  /**
   * If the Menu being edited has been requested from API and it has
   * no values then it has been deleted so reset the selected menu ID.
   */

  (0,external_wp_element_namespaceObject.useEffect)(() => {
    var _Object$keys;

    if (hasLoadedEditedMenu && !((_Object$keys = Object.keys(editedMenu)) !== null && _Object$keys !== void 0 && _Object$keys.length)) {
      setSelectedMenuId(null);
    }
  }, [hasLoadedEditedMenu, editedMenu]);
  const {
    createErrorNotice,
    createInfoNotice
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_notices_namespaceObject.store);
  const isMenuBeingDeleted = (0,external_wp_data_namespaceObject.useSelect)(select => select(external_wp_coreData_namespaceObject.store).isDeletingEntityRecord('root', 'menu', selectedMenuId), [selectedMenuId]);
  const selectedMenuName = (menus === null || menus === void 0 ? void 0 : (_menus$find = menus.find(_ref => {
    let {
      id
    } = _ref;
    return id === selectedMenuId;
  })) === null || _menus$find === void 0 ? void 0 : _menus$find.name) || '';
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    if (hasLoadedMenus) {
      setHasFinishedInitialLoad(true);
    }
  }, [hasLoadedMenus]);
  const navigationPost = (0,external_wp_data_namespaceObject.useSelect)(select => {
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
      createInfoNotice((0,external_wp_i18n_namespaceObject.sprintf)( // translators: %s: the name of a menu.
      (0,external_wp_i18n_namespaceObject.__)('"%s" menu has been deleted'), selectedMenuName), {
        type: 'snackbar',
        isDismissible: true
      });
    } else {
      createErrorNotice((0,external_wp_i18n_namespaceObject.__)('Menu deletion unsuccessful'));
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
;// CONCATENATED MODULE: external ["wp","dom"]
var external_wp_dom_namespaceObject = window["wp"]["dom"];
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/hooks/use-menu-notifications.js
/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */


function useMenuNotifications(menuId) {
  const {
    createErrorNotice
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_notices_namespaceObject.store);
  const lastDeleteError = (0,external_wp_data_namespaceObject.useSelect)(select => {
    return select(external_wp_coreData_namespaceObject.store).getLastEntityDeleteError(MENU_KIND, MENU_POST_TYPE, menuId);
  }, [menuId]);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    if (lastDeleteError) {
      createErrorNotice((0,external_wp_dom_namespaceObject.__unstableStripHTML)(lastDeleteError === null || lastDeleteError === void 0 ? void 0 : lastDeleteError.message), {
        id: 'edit-navigation-error'
      });
    }
  }, [lastDeleteError]);
}
//# sourceMappingURL=use-menu-notifications.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/hooks/use-selected-menu-id.js
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
  const selectedMenuId = (0,external_wp_data_namespaceObject.useSelect)(select => select(store).getSelectedMenuId(), []);
  const {
    setSelectedMenuId
  } = (0,external_wp_data_namespaceObject.useDispatch)(store);
  return [selectedMenuId, setSelectedMenuId];
}
//# sourceMappingURL=use-selected-menu-id.js.map
;// CONCATENATED MODULE: external ["wp","apiFetch"]
var external_wp_apiFetch_namespaceObject = window["wp"]["apiFetch"];
var external_wp_apiFetch_default = /*#__PURE__*/__webpack_require__.n(external_wp_apiFetch_namespaceObject);
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/hooks/use-menu-locations.js
/**
 * WordPress dependencies
 */


/**
 * External dependencies
 */


/**
 * Internal dependencies
 */



const locationsForMenuId = (menuLocationsByName, id) => Object.values(menuLocationsByName).filter(_ref => {
  let {
    menu
  } = _ref;
  return menu === id;
}).map(_ref2 => {
  let {
    name
  } = _ref2;
  return name;
});

function useMenuLocations() {
  const [menuLocationsByName, setMenuLocationsByName] = (0,external_wp_element_namespaceObject.useState)(null);
  const [menuId] = useSelectedMenuId();
  const {
    editMenuEntityRecord,
    menuEntityData
  } = useMenuEntity(menuId);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    let isMounted = true;

    const fetchMenuLocationsByName = async () => {
      const newMenuLocationsByName = await external_wp_apiFetch_default()({
        method: 'GET',
        path: '/wp/v2/menu-locations'
      });

      if (isMounted) {
        setMenuLocationsByName(newMenuLocationsByName);
      }
    };

    fetchMenuLocationsByName();
    return () => isMounted = false;
  }, []);
  const assignMenuToLocation = (0,external_wp_element_namespaceObject.useCallback)(async (locationName, newMenuId) => {
    const oldMenuId = menuLocationsByName[locationName].menu;
    const newMenuLocationsByName = (0,external_lodash_namespaceObject.merge)(menuLocationsByName, {
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

  const menuLocations = (0,external_wp_element_namespaceObject.useMemo)(() => Object.values(menuLocationsByName || {}), [menuLocationsByName]);
  return {
    menuLocations,
    assignMenuToLocation,
    toggleMenuLocationAssignment
  };
}
//# sourceMappingURL=use-menu-locations.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/hooks/use-navigation-editor-root-block.js
/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */

const useNavigationEditorRootBlock = () => {
  return (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      getBlockOrder
    } = select(external_wp_blockEditor_namespaceObject.store);
    const lockedNavigationBlock = getBlockOrder()[0];
    return {
      navBlockClientId: lockedNavigationBlock,
      lastNavBlockItemIndex: getBlockOrder(lockedNavigationBlock).length
    };
  }, []);
};

/* harmony default export */ var use_navigation_editor_root_block = (useNavigationEditorRootBlock);
//# sourceMappingURL=use-navigation-editor-root-block.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/hooks/index.js
/**
 * WordPress dependencies
 */


const untitledMenu = (0,external_wp_i18n_namespaceObject.__)('(untitled menu)');
const IsMenuNameControlFocusedContext = (0,external_wp_element_namespaceObject.createContext)();







//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/block-placeholder/use-navigation-entities.js
/**
 * WordPress dependencies
 */


/**
 * @typedef {Object} NavigationEntitiesData
 * @property {Array|undefined} pages                - a collection of WP Post entity objects of post type "Page".
 * @property {boolean}         isResolvingPages     - indicates whether the request to fetch pages is currently resolving.
 * @property {boolean}         hasResolvedPages     - indicates whether the request to fetch pages has finished resolving.
 * @property {Array|undefined} menus                - a collection of Menu entity objects.
 * @property {boolean}         isResolvingMenus     - indicates whether the request to fetch menus is currently resolving.
 * @property {boolean}         hasResolvedMenus     - indicates whether the request to fetch menus has finished resolving.
 * @property {Array|undefined} menusItems           - a collection of Menu Item entity objects for the current menuId.
 * @property {boolean}         hasResolvedMenuItems - indicates whether the request to fetch menuItems has finished resolving.
 * @property {boolean}         hasPages             - indicates whether there is currently any data for pages.
 * @property {boolean}         hasMenus             - indicates whether there is currently any data for menus.
 */

/**
 * Manages fetching and resolution state for all entities required
 * for the Navigation block.
 *
 * @param {number} menuId the menu for which to retrieve menuItem data.
 * @return { NavigationEntitiesData } the entity data.
 */

function useNavigationEntities(menuId) {
  return { ...usePageEntities(),
    ...useMenuEntities(),
    ...useMenuItemEntities(menuId)
  };
}

function useMenuEntities() {
  const {
    menus,
    isResolvingMenus,
    hasResolvedMenus
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      getMenus,
      isResolving,
      hasFinishedResolution
    } = select(external_wp_coreData_namespaceObject.store);
    const menusParameters = [{
      per_page: -1
    }];
    return {
      menus: getMenus(...menusParameters),
      isResolvingMenus: isResolving('getMenus', menusParameters),
      hasResolvedMenus: hasFinishedResolution('getMenus', menusParameters)
    };
  }, []);
  return {
    menus,
    isResolvingMenus,
    hasResolvedMenus,
    hasMenus: !!(hasResolvedMenus && menus !== null && menus !== void 0 && menus.length)
  };
}

function useMenuItemEntities(menuId) {
  const {
    menuItems,
    hasResolvedMenuItems
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      getMenuItems,
      hasFinishedResolution
    } = select(external_wp_coreData_namespaceObject.store);
    const hasSelectedMenu = menuId !== undefined;
    const menuItemsParameters = hasSelectedMenu ? [{
      menus: menuId,
      per_page: -1
    }] : undefined;
    return {
      menuItems: hasSelectedMenu ? getMenuItems(...menuItemsParameters) : undefined,
      hasResolvedMenuItems: hasSelectedMenu ? hasFinishedResolution('getMenuItems', menuItemsParameters) : false
    };
  }, [menuId]);
  return {
    menuItems,
    hasResolvedMenuItems
  };
}

function usePageEntities() {
  const {
    pages,
    isResolvingPages,
    hasResolvedPages
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      getEntityRecords,
      isResolving,
      hasFinishedResolution
    } = select(external_wp_coreData_namespaceObject.store);
    const pagesParameters = ['postType', 'page', {
      parent: 0,
      order: 'asc',
      orderby: 'id',
      per_page: -1
    }];
    return {
      pages: getEntityRecords(...pagesParameters) || null,
      isResolvingPages: isResolving('getEntityRecords', pagesParameters),
      hasResolvedPages: hasFinishedResolution('getEntityRecords', pagesParameters)
    };
  }, []);
  return {
    pages,
    isResolvingPages,
    hasResolvedPages,
    hasPages: !!(hasResolvedPages && pages !== null && pages !== void 0 && pages.length)
  };
}
//# sourceMappingURL=use-navigation-entities.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/block-placeholder/index.js


/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */




/**
 * Convert pages to blocks.
 *
 * @param {Object[]} pages An array of pages.
 *
 * @return {WPBlock[]} An array of blocks.
 */

function convertPagesToBlocks(pages) {
  if (!(pages !== null && pages !== void 0 && pages.length)) {
    return null;
  }

  return pages.map(_ref => {
    let {
      title,
      type,
      link: url,
      id
    } = _ref;
    return (0,external_wp_blocks_namespaceObject.createBlock)('core/navigation-link', {
      type,
      id,
      url,
      label: !title.rendered ? (0,external_wp_i18n_namespaceObject.__)('(no title)') : title.rendered,
      opensInNewTab: false
    });
  });
}

const TOGGLE_PROPS = {
  variant: 'tertiary'
};
const POPOVER_PROPS = {
  position: 'bottom center'
};

function BlockPlaceholder(_ref2, ref) {
  let {
    onCreate
  } = _ref2;
  const [selectedMenu, setSelectedMenu] = (0,external_wp_element_namespaceObject.useState)();
  const [isCreatingFromMenu, setIsCreatingFromMenu] = (0,external_wp_element_namespaceObject.useState)(false);
  const [selectedMenuId] = useSelectedMenuId();
  const [menuName] = useMenuEntityProp('name', selectedMenuId);
  const {
    isResolvingPages,
    menus,
    isResolvingMenus,
    menuItems,
    hasResolvedMenuItems,
    pages,
    hasPages,
    hasMenus
  } = useNavigationEntities(selectedMenu);
  const isLoading = isResolvingPages || isResolvingMenus;
  const createFromMenu = (0,external_wp_element_namespaceObject.useCallback)(() => {
    const {
      innerBlocks: blocks
    } = menuItemsToBlocks(menuItems);
    const selectNavigationBlock = true;
    onCreate(blocks, selectNavigationBlock);
  }, [menuItems, menuItemsToBlocks, onCreate]);

  const onCreateFromMenu = () => {
    // If we have menu items, create the block right away.
    if (hasResolvedMenuItems) {
      createFromMenu();
      return;
    } // Otherwise, create the block when resolution finishes.


    setIsCreatingFromMenu(true);
  };

  const onCreateEmptyMenu = () => {
    onCreate([]);
  };

  const onCreateAllPages = () => {
    const blocks = convertPagesToBlocks(pages);
    const selectNavigationBlock = true;
    onCreate(blocks, selectNavigationBlock);
  };

  (0,external_wp_element_namespaceObject.useEffect)(() => {
    // If the user selected a menu but we had to wait for menu items to
    // finish resolving, then create the block once resolution finishes.
    if (isCreatingFromMenu && hasResolvedMenuItems) {
      createFromMenu();
      setIsCreatingFromMenu(false);
    }
  }, [isCreatingFromMenu, hasResolvedMenuItems]);
  const selectableMenus = menus === null || menus === void 0 ? void 0 : menus.filter(menu => menu.id !== selectedMenuId);
  const hasSelectableMenus = !!(selectableMenus !== null && selectableMenus !== void 0 && selectableMenus.length);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Placeholder, {
    className: "edit-navigation-block-placeholder",
    label: menuName,
    instructions: (0,external_wp_i18n_namespaceObject.__)('This menu is empty. You can start blank and choose what to add,' + ' add your existing pages, or add the content of another menu.')
  }, (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-navigation-block-placeholder__controls"
  }, isLoading && (0,external_wp_element_namespaceObject.createElement)("div", {
    ref: ref
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Spinner, null)), !isLoading && (0,external_wp_element_namespaceObject.createElement)("div", {
    ref: ref,
    className: "edit-navigation-block-placeholder__actions"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    variant: "tertiary",
    onClick: onCreateEmptyMenu
  }, (0,external_wp_i18n_namespaceObject.__)('Start blank')), hasPages ? (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    variant: hasMenus ? 'tertiary' : 'primary',
    onClick: onCreateAllPages
  }, (0,external_wp_i18n_namespaceObject.__)('Add all pages')) : undefined, hasSelectableMenus ? (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.DropdownMenu, {
    text: (0,external_wp_i18n_namespaceObject.__)('Copy existing menu'),
    icon: chevron_down,
    toggleProps: TOGGLE_PROPS,
    popoverProps: POPOVER_PROPS
  }, _ref3 => {
    let {
      onClose
    } = _ref3;
    return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuGroup, null, selectableMenus.map(menu => {
      return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuItem, {
        onClick: () => {
          setSelectedMenu(menu.id);
          onCreateFromMenu();
        },
        onClose: onClose,
        key: menu.id
      }, menu.name);
    }));
  }) : undefined)));
}

/* harmony default export */ var block_placeholder = ((0,external_wp_element_namespaceObject.forwardRef)(BlockPlaceholder));
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/filters/add-navigation-editor-placeholder.js



/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */


const addNavigationEditorPlaceholder = (0,external_wp_compose_namespaceObject.createHigherOrderComponent)(BlockEdit => props => {
  if (props.name !== 'core/navigation') {
    return (0,external_wp_element_namespaceObject.createElement)(BlockEdit, props);
  }

  return (0,external_wp_element_namespaceObject.createElement)(BlockEdit, _extends({}, props, {
    customPlaceholder: block_placeholder
  }));
}, 'withNavigationEditorPlaceholder');
/* harmony default export */ var add_navigation_editor_placeholder = (() => (0,external_wp_hooks_namespaceObject.addFilter)('editor.BlockEdit', 'core/edit-navigation/with-navigation-editor-placeholder', addNavigationEditorPlaceholder));
//# sourceMappingURL=add-navigation-editor-placeholder.js.map
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

function singleEnableItems() {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let {
    type,
    itemType,
    scope,
    item
  } = arguments.length > 1 ? arguments[1] : undefined;

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

function multipleEnableItems() {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let {
    type,
    itemType,
    scope,
    item,
    isEnable
  } = arguments.length > 1 ? arguments[1] : undefined;

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
  features() {
    let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    let action = arguments.length > 1 ? arguments[1] : undefined;

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

const preferences = (0,external_wp_data_namespaceObject.combineReducers)({
  features() {
    let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    let action = arguments.length > 1 ? arguments[1] : undefined;

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
  preferences
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
  return function (_ref) {
    let {
      select,
      dispatch
    } = _ref;
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

const store_store = (0,external_wp_data_namespaceObject.createReduxStore)(constants_STORE_NAME, {
  reducer: store_reducer,
  actions: store_actions_namespaceObject,
  selectors: store_selectors_namespaceObject,
  persist: ['enableItems', 'preferences'],
  __experimentalUseThunks: true
}); // Once we build a more generic persistence plugin that works across types of stores
// we'd be able to replace this with a register call.

(0,external_wp_data_namespaceObject.registerStore)(constants_STORE_NAME, {
  reducer: store_reducer,
  actions: store_actions_namespaceObject,
  selectors: store_selectors_namespaceObject,
  persist: ['enableItems', 'preferences'],
  __experimentalUseThunks: true
});
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: external ["wp","plugins"]
var external_wp_plugins_namespaceObject = window["wp"]["plugins"];
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




function ComplementaryAreaToggle(_ref) {
  let {
    as = external_wp_components_namespaceObject.Button,
    scope,
    identifier,
    icon,
    selectedIcon,
    ...props
  } = _ref;
  const ComponentToUse = as;
  const isSelected = (0,external_wp_data_namespaceObject.useSelect)(select => select(store_store).getActiveComplementaryArea(scope) === identifier, [identifier]);
  const {
    enableComplementaryArea,
    disableComplementaryArea
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);
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



const ComplementaryAreaHeader = _ref => {
  let {
    smallScreenTitle,
    children,
    className,
    toggleButtonProps
  } = _ref;
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




function ActionItemSlot(_ref) {
  let {
    name,
    as: Component = external_wp_components_namespaceObject.ButtonGroup,
    fillProps = {},
    bubblesVirtually,
    ...props
  } = _ref;
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
    external_wp_element_namespaceObject.Children.forEach(fills, _ref2 => {
      let {
        props: {
          __unstableExplicitMenuItem,
          __unstableTarget
        }
      } = _ref2;

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

function ActionItem(_ref3) {
  let {
    name,
    as: Component = external_wp_components_namespaceObject.Button,
    onClick,
    ...props
  } = _ref3;
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Fill, {
    name: name
  }, _ref4 => {
    let {
      onClick: fpOnClick
    } = _ref4;
    return (0,external_wp_element_namespaceObject.createElement)(Component, _extends({
      onClick: onClick || fpOnClick ? function () {
        (onClick || external_lodash_namespaceObject.noop)(...arguments);
        (fpOnClick || external_lodash_namespaceObject.noop)(...arguments);
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

function ComplementaryAreaMoreMenuItem(_ref) {
  let {
    scope,
    target,
    __unstableExplicitMenuItem,
    ...props
  } = _ref;
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



function PinnedItems(_ref) {
  let {
    scope,
    ...props
  } = _ref;
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Fill, _extends({
    name: `PinnedItems/${scope}`
  }, props));
}

function PinnedItemsSlot(_ref2) {
  let {
    scope,
    className,
    ...props
  } = _ref2;
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








function ComplementaryAreaSlot(_ref) {
  let {
    scope,
    ...props
  } = _ref;
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Slot, _extends({
    name: `ComplementaryArea/${scope}`
  }, props));
}

function ComplementaryAreaFill(_ref2) {
  let {
    scope,
    children,
    className
  } = _ref2;
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
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);
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

function ComplementaryArea(_ref3) {
  let {
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
  } = _ref3;
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
    } = select(store_store);

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
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);
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

function InterfaceSkeleton(_ref, ref) {
  let {
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
  } = _ref;
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
    "aria-label": mergedLabels.drawer,
    tabIndex: "-1"
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
;// CONCATENATED MODULE: ./packages/interface/build-module/components/more-menu-dropdown/index.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */




function MoreMenuDropdown(_ref) {
  let {
    as: DropdownComponent = external_wp_components_namespaceObject.DropdownMenu,
    className,

    /* translators: button label text should, if possible, be under 16 characters. */
    label = (0,external_wp_i18n_namespaceObject.__)('Options'),
    popoverProps,
    toggleProps,
    children
  } = _ref;
  return (0,external_wp_element_namespaceObject.createElement)(DropdownComponent, {
    className: classnames_default()('interface-more-menu-dropdown', className),
    icon: more_vertical,
    label: label,
    popoverProps: {
      position: 'bottom left',
      ...popoverProps,
      className: classnames_default()('interface-more-menu-dropdown__content', popoverProps === null || popoverProps === void 0 ? void 0 : popoverProps.className)
    },
    toggleProps: {
      tooltipPosition: 'bottom',
      ...toggleProps
    }
  }, onClose => children(onClose));
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/interface/build-module/components/index.js








//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/interface/build-module/index.js


//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: external ["wp","htmlEntities"]
var external_wp_htmlEntities_namespaceObject = window["wp"]["htmlEntities"];
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/name-display/index.js


/**
 * WordPress dependencies
 */







/**
 * Internal dependencies
 */



function NameDisplay() {
  const {
    enableComplementaryArea
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);
  const [menuId] = useSelectedMenuId();
  const [name] = useMenuEntityProp('name', menuId);
  const [, setIsMenuNameEditFocused] = (0,external_wp_element_namespaceObject.useContext)(IsMenuNameControlFocusedContext);
  const menuName = (0,external_wp_htmlEntities_namespaceObject.decodeEntities)(name !== null && name !== void 0 ? name : untitledMenu);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.BlockControls, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.ToolbarGroup, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.ToolbarButton, {
    "aria-label": (0,external_wp_i18n_namespaceObject.sprintf)( // translators: %s: the name of a menu.
    (0,external_wp_i18n_namespaceObject.__)(`Edit menu name: %s`), menuName),
    onClick: () => {
      enableComplementaryArea(SIDEBAR_SCOPE, SIDEBAR_MENU);
      setIsMenuNameEditFocused(true);
    }
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalText, {
    limit: 24,
    ellipsizeMode: "tail",
    truncate: true
  }, menuName))));
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/filters/add-menu-name-editor.js


/**
 * WordPress dependencies
 */

/**
 * Internal dependencies
 */



const addMenuNameEditor = (0,external_wp_compose_namespaceObject.createHigherOrderComponent)(BlockEdit => props => {
  if (props.name !== 'core/navigation') {
    return (0,external_wp_element_namespaceObject.createElement)(BlockEdit, props);
  }

  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(NameDisplay, null), (0,external_wp_element_namespaceObject.createElement)(BlockEdit, props));
}, 'withMenuName');
/* harmony default export */ var add_menu_name_editor = (() => (0,external_wp_hooks_namespaceObject.addFilter)('editor.BlockEdit', 'core/edit-navigation/with-menu-name', addMenuNameEditor));
//# sourceMappingURL=add-menu-name-editor.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/filters/disable-inserting-non-navigation-blocks.js
/**
 * WordPress dependencies
 */

/**
 * External dependencies
 */



function disableInsertingNonNavigationBlocks(settings, name) {
  if (!['core/navigation', 'core/navigation-link', 'core/navigation-submenu'].includes(name)) {
    (0,external_lodash_namespaceObject.set)(settings, ['supports', 'inserter'], false);
  }

  return settings;
}

/* harmony default export */ var disable_inserting_non_navigation_blocks = (() => (0,external_wp_hooks_namespaceObject.addFilter)('blocks.registerBlockType', 'core/edit-navigation/disable-inserting-non-navigation-blocks', disableInsertingNonNavigationBlocks));
//# sourceMappingURL=disable-inserting-non-navigation-blocks.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/filters/remove-edit-unsupported-features.js



/**
 * WordPress dependencies
 */


const removeNavigationBlockEditUnsupportedFeatures = (0,external_wp_compose_namespaceObject.createHigherOrderComponent)(BlockEdit => props => {
  if (props.name !== 'core/navigation') {
    return (0,external_wp_element_namespaceObject.createElement)(BlockEdit, props);
  }

  return (0,external_wp_element_namespaceObject.createElement)(BlockEdit, _extends({}, props, {
    hasSubmenuIndicatorSetting: false,
    hasItemJustificationControls: false,
    hasColorSettings: false
  }));
}, 'removeNavigationBlockEditUnsupportedFeatures');
/* harmony default export */ var remove_edit_unsupported_features = (() => (0,external_wp_hooks_namespaceObject.addFilter)('editor.BlockEdit', 'core/edit-navigation/remove-navigation-block-edit-unsupported-features', removeNavigationBlockEditUnsupportedFeatures));
//# sourceMappingURL=remove-edit-unsupported-features.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/filters/remove-settings-unsupported-features.js
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

/* harmony default export */ var remove_settings_unsupported_features = (() => (0,external_wp_hooks_namespaceObject.addFilter)('blocks.registerBlockType', 'core/edit-navigation/remove-navigation-block-settings-unsupported-features', removeNavigationBlockSettingsUnsupportedFeatures));
//# sourceMappingURL=remove-settings-unsupported-features.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/filters/index.js
/**
 * Internal dependencies
 */






const addFilters = shouldAddDisableInsertingNonNavigationBlocksFilter => {
  add_navigation_editor_custom_appender();
  add_navigation_editor_placeholder();
  add_menu_name_editor();

  if (shouldAddDisableInsertingNonNavigationBlocksFilter) {
    disable_inserting_non_navigation_blocks();
  }

  remove_edit_unsupported_features();
  remove_settings_unsupported_features();
};
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: external ["wp","keyboardShortcuts"]
var external_wp_keyboardShortcuts_namespaceObject = window["wp"]["keyboardShortcuts"];
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/add-menu/index.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */









/**
 * Internal dependencies
 */



function AddMenu(_ref) {
  let {
    className,
    onCreate,
    titleText,
    helpText,
    focusInputOnMount = false,
    noticeUI,
    noticeOperations
  } = _ref;
  const inputRef = (0,external_wp_compose_namespaceObject.useFocusOnMount)(focusInputOnMount);
  const [menuName, setMenuName] = (0,external_wp_element_namespaceObject.useState)('');
  const [isCreatingMenu, setIsCreatingMenu] = (0,external_wp_element_namespaceObject.useState)(false);
  const {
    createInfoNotice
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_notices_namespaceObject.store);
  const {
    saveMenu
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_coreData_namespaceObject.store);
  const {
    createErrorNotice,
    removeAllNotices
  } = noticeOperations;
  const lastSaveError = (0,external_wp_data_namespaceObject.useSelect)(select => {
    return select(external_wp_coreData_namespaceObject.store).getLastEntitySaveError(MENU_KIND, MENU_POST_TYPE);
  }, []);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    if (lastSaveError) {
      createErrorNotice((0,external_wp_dom_namespaceObject.__unstableStripHTML)(lastSaveError === null || lastSaveError === void 0 ? void 0 : lastSaveError.message));
    }
  }, [lastSaveError]);

  const createMenu = async event => {
    event.preventDefault();

    if (!menuName.length || isCreatingMenu) {
      return;
    }

    setIsCreatingMenu(true); // Remove any existing notices.

    removeAllNotices();
    const menu = await saveMenu({
      name: menuName
    });
    setIsCreatingMenu(false);

    if (menu) {
      createInfoNotice((0,external_wp_i18n_namespaceObject.__)('Menu created'), {
        type: 'snackbar',
        isDismissible: true
      });

      if (onCreate) {
        onCreate(menu.id);
      }
    }
  };

  return (0,external_wp_element_namespaceObject.createElement)("form", {
    className: classnames_default()('edit-navigation-add-menu', className),
    onSubmit: createMenu
  }, noticeUI, titleText && (0,external_wp_element_namespaceObject.createElement)("h3", {
    className: "edit-navigation-add-menu__title"
  }, titleText), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.TextControl, {
    ref: inputRef,
    label: (0,external_wp_i18n_namespaceObject.__)('Menu name'),
    value: menuName,
    onChange: setMenuName,
    help: helpText
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    className: "edit-navigation-add-menu__create-menu-button",
    type: "submit",
    variant: "primary",
    disabled: !menuName.length,
    isBusy: isCreatingMenu
    /* Button is disabled but still focusable */
    ,
    "aria-disabled": !menuName.length || isCreatingMenu
  }, (0,external_wp_i18n_namespaceObject.__)('Create menu')));
}

/* harmony default export */ var add_menu = ((0,external_wp_components_namespaceObject.withNotices)(AddMenu));
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/menu-switcher/index.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */


function MenuSwitcher(_ref) {
  let {
    menus,
    selectedMenuId,
    onSelectMenu = external_lodash_namespaceObject.noop
  } = _ref;
  const [isModalVisible, setIsModalVisible] = (0,external_wp_element_namespaceObject.useState)(false);

  const openModal = () => setIsModalVisible(true);

  const closeModal = () => setIsModalVisible(false);

  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuGroup, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuItemsChoice, {
    value: selectedMenuId,
    onSelect: onSelectMenu,
    choices: menus.map(_ref2 => {
      let {
        id,
        name
      } = _ref2;
      return {
        value: id,
        label: (0,external_wp_htmlEntities_namespaceObject.decodeEntities)(name),
        'aria-label': (0,external_wp_i18n_namespaceObject.sprintf)(
        /* translators: %s: The name of a menu. */
        (0,external_wp_i18n_namespaceObject.__)("Switch to '%s'"), name)
      };
    })
  })), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuGroup, {
    hideSeparator: true
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuItem, {
    className: "edit-navigation-menu-switcher__new-button",
    onClick: openModal
  }, (0,external_wp_i18n_namespaceObject.__)('Create a new menu')), isModalVisible && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Modal, {
    title: (0,external_wp_i18n_namespaceObject.__)('Create a new menu'),
    className: "edit-navigation-menu-switcher__modal",
    onRequestClose: closeModal
  }, (0,external_wp_element_namespaceObject.createElement)(add_menu, {
    onCreate: menuId => {
      closeModal();
      onSelectMenu(menuId);
    },
    helpText: (0,external_wp_i18n_namespaceObject.__)('A short descriptive name for your menu.')
  }))));
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/layout/unselected-menu-state.js


/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */



function UnselectedMenuState(_ref) {
  let {
    onCreate,
    onSelectMenu,
    menus
  } = _ref;
  const showMenuSwitcher = (menus === null || menus === void 0 ? void 0 : menus.length) > 0;
  return (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-navigation-empty-state"
  }, showMenuSwitcher && (0,external_wp_element_namespaceObject.createElement)("h4", null, (0,external_wp_i18n_namespaceObject.__)('Choose a menu to edit:')), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Card, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.CardBody, null, showMenuSwitcher ? (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.NavigableMenu, null, (0,external_wp_element_namespaceObject.createElement)(MenuSwitcher, {
    onSelectMenu: onSelectMenu,
    menus: menus
  })) : (0,external_wp_element_namespaceObject.createElement)(add_menu, {
    onCreate: onCreate,
    titleText: (0,external_wp_i18n_namespaceObject.__)('Create your first menu'),
    helpText: (0,external_wp_i18n_namespaceObject.__)('A short descriptive name for your menu.'),
    focusInputOnMount: true
  }))));
}
//# sourceMappingURL=unselected-menu-state.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/error-boundary/index.js


/**
 * WordPress dependencies
 */





class ErrorBoundary extends external_wp_element_namespaceObject.Component {
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

    return (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.Warning, {
      className: "navigation-editor-error-boundary",
      actions: [(0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
        key: "recovery",
        onClick: this.reboot,
        variant: "secondary"
      }, (0,external_wp_i18n_namespaceObject.__)('Attempt Recovery'))]
    }, (0,external_wp_i18n_namespaceObject.__)('The navigation editor has encountered an unexpected error.'));
  }

}

/* harmony default export */ var error_boundary = (ErrorBoundary);
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/layout/shortcuts.js
/**
 * WordPress dependencies
 */






function NavigationEditorShortcuts(_ref) {
  let {
    saveBlocks
  } = _ref;
  (0,external_wp_keyboardShortcuts_namespaceObject.useShortcut)('core/edit-navigation/save-menu', event => {
    event.preventDefault();
    saveBlocks();
  });
  const {
    redo,
    undo
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_coreData_namespaceObject.store);
  (0,external_wp_keyboardShortcuts_namespaceObject.useShortcut)('core/edit-navigation/undo', event => {
    undo();
    event.preventDefault();
  });
  (0,external_wp_keyboardShortcuts_namespaceObject.useShortcut)('core/edit-navigation/redo', event => {
    redo();
    event.preventDefault();
  });
  return null;
}

function RegisterNavigationEditorShortcuts() {
  const {
    registerShortcut
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_keyboardShortcuts_namespaceObject.store);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    registerShortcut({
      name: 'core/edit-navigation/save-menu',
      category: 'global',
      description: (0,external_wp_i18n_namespaceObject.__)('Save the navigation currently being edited.'),
      keyCombination: {
        modifier: 'primary',
        character: 's'
      }
    });
    registerShortcut({
      name: 'core/edit-navigation/undo',
      category: 'global',
      description: (0,external_wp_i18n_namespaceObject.__)('Undo your last changes.'),
      keyCombination: {
        modifier: 'primary',
        character: 'z'
      }
    });
    registerShortcut({
      name: 'core/edit-navigation/redo',
      category: 'global',
      description: (0,external_wp_i18n_namespaceObject.__)('Redo your last undo.'),
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
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/sidebar/sidebar-header.js


/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */


function SidebarHeader(_ref) {
  let {
    sidebarName
  } = _ref;
  const {
    enableComplementaryArea
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);

  const openMenuSettings = () => enableComplementaryArea(SIDEBAR_SCOPE, SIDEBAR_MENU);

  const openBlockSettings = () => enableComplementaryArea(SIDEBAR_SCOPE, SIDEBAR_BLOCK);

  const [menuAriaLabel, menuActiveClass] = sidebarName === SIDEBAR_MENU ? // translators: ARIA label for the Menu sidebar tab, selected.
  [(0,external_wp_i18n_namespaceObject.__)('Menu (selected)'), 'is-active'] : // translators: ARIA label for the Menu Settings Sidebar tab, not selected.
  [(0,external_wp_i18n_namespaceObject.__)('Menu'), ''];
  const [blockAriaLabel, blockActiveClass] = sidebarName === SIDEBAR_BLOCK ? // translators: ARIA label for the Block Settings Sidebar tab, selected.
  [(0,external_wp_i18n_namespaceObject.__)('Block (selected)'), 'is-active'] : // translators: ARIA label for the Block Settings Sidebar tab, not selected.
  [(0,external_wp_i18n_namespaceObject.__)('Block'), ''];
  /* Use a list so screen readers will announce how many tabs there are. */

  return (0,external_wp_element_namespaceObject.createElement)("ul", null, (0,external_wp_element_namespaceObject.createElement)("li", null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    onClick: openMenuSettings,
    className: `edit-navigation-sidebar__panel-tab ${menuActiveClass}`,
    "aria-label": menuAriaLabel // translators: Data label for the Menu Settings Sidebar tab.
    ,
    "data-label": (0,external_wp_i18n_namespaceObject.__)('Menu')
  }, // translators: Text label for the Menu Settings Sidebar tab.
  (0,external_wp_i18n_namespaceObject.__)('Menu'))), (0,external_wp_element_namespaceObject.createElement)("li", null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    onClick: openBlockSettings,
    className: `edit-navigation-sidebar__panel-tab ${blockActiveClass}`,
    "aria-label": blockAriaLabel // translators: Data label for the Block Settings Sidebar tab.
    ,
    "data-label": (0,external_wp_i18n_namespaceObject.__)('Block')
  }, // translators: Text label for the Block Settings Sidebar tab.
  (0,external_wp_i18n_namespaceObject.__)('Block'))));
}
//# sourceMappingURL=sidebar-header.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/name-editor/index.js


/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */


function NameEditor() {
  const [isMenuNameEditFocused, setIsMenuNameEditFocused] = (0,external_wp_element_namespaceObject.useContext)(IsMenuNameControlFocusedContext);
  const [menuId] = useSelectedMenuId();
  const [name, setName] = useMenuEntityProp('name', menuId);
  const inputRef = (0,external_wp_element_namespaceObject.useRef)();
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    if (isMenuNameEditFocused) inputRef.current.focus();
  }, [isMenuNameEditFocused]);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.TextControl, {
    ref: inputRef,
    help: (0,external_wp_i18n_namespaceObject.__)('A short, descriptive name used to refer to this menu elsewhere.'),
    label: (0,external_wp_i18n_namespaceObject.__)('Name'),
    onBlur: () => setIsMenuNameEditFocused(false),
    className: "edit-navigation-name-editor__text-control",
    value: (0,external_wp_htmlEntities_namespaceObject.decodeEntities)(name || ''),
    onChange: setName
  });
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/sidebar/auto-add-pages.js


/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */


function AutoAddPages(_ref) {
  let {
    menuId
  } = _ref;
  const [autoAddPages, setAutoAddPages] = useMenuEntityProp('auto_add', menuId);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.ToggleControl, {
    label: (0,external_wp_i18n_namespaceObject.__)('Add new pages'),
    help: (0,external_wp_i18n_namespaceObject.__)('Automatically add published top-level pages to this menu.'),
    checked: autoAddPages !== null && autoAddPages !== void 0 ? autoAddPages : false,
    onChange: setAutoAddPages
  });
}
//# sourceMappingURL=auto-add-pages.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/sidebar/menu-settings.js


/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */



function MenuSettings(_ref) {
  let {
    menuId
  } = _ref;
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.PanelBody, {
    title: (0,external_wp_i18n_namespaceObject.__)('Menu settings')
  }, (0,external_wp_element_namespaceObject.createElement)(NameEditor, null), (0,external_wp_element_namespaceObject.createElement)(AutoAddPages, {
    menuId: menuId
  }));
}
//# sourceMappingURL=menu-settings.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/sidebar/manage-locations.js


/**
 * WordPress dependencies
 */







/**
 * Internal dependencies
 */


function ManageLocations(_ref) {
  let {
    menus,
    selectedMenuId,
    onSelectMenu
  } = _ref;
  const {
    menuLocations,
    assignMenuToLocation,
    toggleMenuLocationAssignment
  } = useMenuLocations();
  const [isModalOpen, setIsModalOpen] = (0,external_wp_element_namespaceObject.useState)(false);

  const openModal = () => setIsModalOpen(true);

  const closeModal = () => setIsModalOpen(false);

  const {
    createSuccessNotice,
    createErrorNotice
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_notices_namespaceObject.store);

  const validateBatchResponse = batchResponse => {
    if (batchResponse.failed) {
      return false;
    }

    const errorResponses = batchResponse.responses.filter(response => {
      return 200 > response.status || 300 <= response.status;
    });
    return 1 > errorResponses.length;
  };

  const handleUpdateMenuLocations = async () => {
    const method = 'POST';
    const batchRequests = menus.map(_ref2 => {
      let {
        id
      } = _ref2;
      const locations = menuLocations.filter(menuLocation => menuLocation.menu === id).map(menuLocation => menuLocation.name);
      return {
        path: `/wp/v2/menus/${id}`,
        body: {
          locations
        },
        method
      };
    });
    const batchResponse = await external_wp_apiFetch_default()({
      path: 'batch/v1',
      data: {
        validation: 'require-all-validate',
        requests: batchRequests
      },
      method
    });
    const isSuccess = validateBatchResponse(batchResponse);

    if (isSuccess) {
      createSuccessNotice((0,external_wp_i18n_namespaceObject.__)('Menu locations have been updated.'), {
        type: 'snackbar'
      });
      closeModal();
      return;
    }

    createErrorNotice((0,external_wp_i18n_namespaceObject.__)('An error occurred while trying to update menu locations.'), {
      type: 'snackbar'
    });
  };

  if (!menuLocations || !(menus !== null && menus !== void 0 && menus.length)) {
    return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Spinner, null);
  }

  if (!menuLocations.length) {
    return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.PanelBody, {
      title: (0,external_wp_i18n_namespaceObject.__)('Theme locations')
    }, (0,external_wp_element_namespaceObject.createElement)("p", null, (0,external_wp_i18n_namespaceObject.__)('There are no available menu locations.')));
  }

  const themeLocationCountTextMain = (0,external_wp_i18n_namespaceObject.sprintf)( // translators: Number of available theme locations.
  (0,external_wp_i18n_namespaceObject.__)('Your current theme provides %d different locations to place menu.'), menuLocations.length);
  const themeLocationCountTextModal = (0,external_wp_i18n_namespaceObject.sprintf)( // translators: Number of available theme locations.
  (0,external_wp_i18n_namespaceObject.__)('Your current theme supports %d different locations. Select which menu appears in each location.'), menuLocations.length);
  const menusWithSelection = menuLocations.map(_ref3 => {
    let {
      name,
      description,
      menu
    } = _ref3;
    const menuOnLocation = menus.filter(_ref4 => {
      let {
        id
      } = _ref4;
      return ![0, selectedMenuId].includes(id);
    }).find(_ref5 => {
      let {
        id
      } = _ref5;
      return id === menu;
    });
    return (0,external_wp_element_namespaceObject.createElement)("li", {
      key: name,
      className: "edit-navigation-manage-locations__checklist-item"
    }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.CheckboxControl, {
      className: "edit-navigation-manage-locations__menu-location-checkbox",
      checked: menu === selectedMenuId,
      onChange: () => toggleMenuLocationAssignment(name, selectedMenuId),
      label: description,
      help: menuOnLocation && (0,external_wp_i18n_namespaceObject.sprintf)( // translators: menu name.
      (0,external_wp_i18n_namespaceObject.__)('Currently using %s'), (0,external_wp_htmlEntities_namespaceObject.decodeEntities)(menuOnLocation.name))
    }));
  });
  const menuLocationCard = menuLocations.map(menuLocation => (0,external_wp_element_namespaceObject.createElement)("div", {
    key: menuLocation.name,
    className: "edit-navigation-manage-locations__menu-entry"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.SelectControl, {
    key: menuLocation.name,
    className: "edit-navigation-manage-locations__select-menu",
    label: menuLocation.description,
    labelPosition: "top",
    value: (0,external_wp_htmlEntities_namespaceObject.decodeEntities)(menuLocation.menu),
    options: [{
      value: 0,
      label: (0,external_wp_i18n_namespaceObject.__)('Select a Menu'),
      key: 0
    }, ...menus.map(_ref6 => {
      let {
        id,
        name
      } = _ref6;
      return {
        key: id,
        value: id,
        label: (0,external_wp_htmlEntities_namespaceObject.decodeEntities)(name)
      };
    })],
    onChange: menuId => {
      assignMenuToLocation(menuLocation.name, Number(menuId));
    }
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    variant: "secondary",
    style: {
      visibility: !!menuLocation.menu ? 'visible' : 'hidden'
    },
    className: "edit-navigation-manage-locations__edit-button",
    onClick: () => (closeModal(), onSelectMenu(menuLocation.menu))
  }, (0,external_wp_i18n_namespaceObject.__)('Edit'))));
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.PanelBody, {
    title: (0,external_wp_i18n_namespaceObject.__)('Theme locations')
  }, (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-navigation-manage-locations__theme-location-text-main"
  }, themeLocationCountTextMain), (0,external_wp_element_namespaceObject.createElement)("ul", {
    className: "edit-navigation-manage-locations__checklist"
  }, menusWithSelection), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    variant: "secondary",
    className: "edit-navigation-manage-locations__open-menu-locations-modal-button",
    "aria-expanded": isModalOpen,
    onClick: openModal
  }, (0,external_wp_i18n_namespaceObject.__)('Manage locations')), isModalOpen && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Modal, {
    className: "edit-navigation-manage-locations__modal",
    title: (0,external_wp_i18n_namespaceObject.__)('Manage locations'),
    onRequestClose: closeModal
  }, (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-navigation-manage-locations__theme-location-text-modal"
  }, themeLocationCountTextModal), menuLocationCard, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    className: "edit-navigation-manage-locations__save-button",
    variant: "primary",
    onClick: handleUpdateMenuLocations
  }, (0,external_wp_i18n_namespaceObject.__)('Update'))));
}
//# sourceMappingURL=manage-locations.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/sidebar/delete-menu.js


/**
 * WordPress dependencies
 */



function DeleteMenu(_ref) {
  let {
    onDeleteMenu,
    isMenuBeingDeleted
  } = _ref;
  const [showConfirmDialog, setShowConfirmDialog] = (0,external_wp_element_namespaceObject.useState)(false);

  const handleConfirm = () => {
    setShowConfirmDialog(false);
    onDeleteMenu();
  };

  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.PanelBody, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    className: "edit-navigation-inspector-additions__delete-menu-button",
    variant: "secondary",
    isDestructive: true,
    isBusy: isMenuBeingDeleted,
    onClick: () => {
      setShowConfirmDialog(true);
    }
  }, (0,external_wp_i18n_namespaceObject.__)('Delete menu')), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalConfirmDialog, {
    isOpen: showConfirmDialog,
    onConfirm: handleConfirm,
    onCancel: () => setShowConfirmDialog(false)
  }, (0,external_wp_i18n_namespaceObject.__)('Are you sure you want to delete this navigation? This action cannot be undone.'))));
}
//# sourceMappingURL=delete-menu.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/sidebar/index.js


/**
 * WordPress dependencies
 */







/**
 * Internal dependencies
 */






function Sidebar(_ref) {
  let {
    menuId,
    menus,
    isMenuBeingDeleted,
    onDeleteMenu,
    onSelectMenu
  } = _ref;
  const isLargeViewport = (0,external_wp_compose_namespaceObject.useViewportMatch)('medium');
  const {
    sidebar,
    hasBlockSelection,
    hasSidebarEnabled
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const _sidebar = select(store_store).getActiveComplementaryArea(SIDEBAR_SCOPE);

    const _hasSidebarEnabled = [SIDEBAR_MENU, SIDEBAR_BLOCK].includes(_sidebar);

    return {
      sidebar: _sidebar,
      hasBlockSelection: !!select(external_wp_blockEditor_namespaceObject.store).getBlockSelectionStart(),
      hasSidebarEnabled: _hasSidebarEnabled
    };
  }, []);
  const {
    enableComplementaryArea
  } = (0,external_wp_data_namespaceObject.useDispatch)(store_store);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
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

  return (0,external_wp_element_namespaceObject.createElement)(complementary_area, {
    className: "edit-navigation-sidebar"
    /* translators: button label text should, if possible, be under 16 characters. */
    ,
    title: (0,external_wp_i18n_namespaceObject.__)('Settings'),
    closeLabel: (0,external_wp_i18n_namespaceObject.__)('Close settings'),
    scope: SIDEBAR_SCOPE,
    identifier: sidebarName,
    icon: library_cog,
    isActiveByDefault: isLargeViewport,
    header: (0,external_wp_element_namespaceObject.createElement)(SidebarHeader, {
      sidebarName: sidebarName
    }),
    headerClassName: "edit-navigation-sidebar__panel-tabs",
    isPinnable: true
  }, sidebarName === SIDEBAR_MENU && (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(MenuSettings, {
    menuId: menuId
  }), (0,external_wp_element_namespaceObject.createElement)(ManageLocations, {
    menus: menus,
    selectedMenuId: menuId,
    onSelectMenu: onSelectMenu
  }), (0,external_wp_element_namespaceObject.createElement)(DeleteMenu, {
    onDeleteMenu: onDeleteMenu,
    isMenuBeingDeleted: isMenuBeingDeleted
  })), sidebarName === SIDEBAR_BLOCK && (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.BlockInspector, null));
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/header/menu-actions.js


/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */



function MenuActions(_ref) {
  let {
    menus,
    isLoading
  } = _ref;
  const [selectedMenuId, setSelectedMenuId] = useSelectedMenuId();
  const [menuName] = useMenuEntityProp('name', selectedMenuId); // The title ref is passed to the popover as the anchorRef so that the
  // dropdown is centered over the whole title area rather than just one
  // part of it.

  const titleRef = (0,external_wp_element_namespaceObject.useRef)();

  if (isLoading) {
    return (0,external_wp_element_namespaceObject.createElement)("div", {
      className: "edit-navigation-menu-actions"
    }, (0,external_wp_i18n_namespaceObject.__)('Loading…'));
  }

  return (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-navigation-menu-actions"
  }, (0,external_wp_element_namespaceObject.createElement)("div", {
    ref: titleRef,
    className: "edit-navigation-menu-actions__subtitle-wrapper"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.__experimentalText, {
    size: "body",
    className: "edit-navigation-menu-actions__subtitle",
    as: "h2",
    limit: 24,
    ellipsizeMode: "tail",
    truncate: true
  }, (0,external_wp_htmlEntities_namespaceObject.decodeEntities)(menuName)), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.DropdownMenu, {
    icon: chevron_down,
    toggleProps: {
      label: (0,external_wp_i18n_namespaceObject.__)('Switch menu'),
      className: 'edit-navigation-menu-actions__switcher-toggle',
      showTooltip: false,
      __experimentalIsFocusable: true
    },
    popoverProps: {
      className: 'edit-navigation-menu-actions__switcher-dropdown',
      position: 'bottom center',
      anchorRef: titleRef.current
    }
  }, _ref2 => {
    let {
      onClose
    } = _ref2;
    return (0,external_wp_element_namespaceObject.createElement)(MenuSwitcher, {
      menus: menus,
      selectedMenuId: selectedMenuId,
      onSelectMenu: menuId => {
        setSelectedMenuId(menuId);
        onClose();
      }
    });
  })));
}
//# sourceMappingURL=menu-actions.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/header/new-button.js


/**
 * WordPress dependencies
 */



/**
 * Internal dependencies
 */



function NewButton() {
  const [isModalOpen, setIsModalOpen] = (0,external_wp_element_namespaceObject.useState)(false);
  const [, setSelectedMenuId] = useSelectedMenuId();
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    variant: "tertiary",
    onClick: () => setIsModalOpen(true)
  }, (0,external_wp_i18n_namespaceObject.__)('New menu')), isModalOpen && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Modal, {
    title: (0,external_wp_i18n_namespaceObject.__)('Create a new menu'),
    className: "edit-navigation-menu-switcher__modal",
    onRequestClose: () => setIsModalOpen(false)
  }, (0,external_wp_element_namespaceObject.createElement)(add_menu, {
    helpText: (0,external_wp_i18n_namespaceObject.__)('A short descriptive name for your menu.'),
    onCreate: menuId => {
      setIsModalOpen(false);
      setSelectedMenuId(menuId);
    }
  })));
}
//# sourceMappingURL=new-button.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/header/save-button.js


/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */


function SaveButton(_ref) {
  let {
    navigationPost
  } = _ref;
  const {
    isDirty
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      __experimentalGetDirtyEntityRecords
    } = select(external_wp_coreData_namespaceObject.store);

    const dirtyEntityRecords = __experimentalGetDirtyEntityRecords();

    return {
      isDirty: dirtyEntityRecords.length > 0
    };
  }, []);
  const {
    saveNavigationPost
  } = (0,external_wp_data_namespaceObject.useDispatch)(store);
  const disabled = !isDirty || !navigationPost;
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    className: "edit-navigation-toolbar__save-button",
    variant: "primary",
    onClick: () => {
      saveNavigationPost(navigationPost);
    },
    disabled: disabled
  }, (0,external_wp_i18n_namespaceObject.__)('Save'));
}
//# sourceMappingURL=save-button.js.map
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
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/header/undo-button.js


/**
 * WordPress dependencies
 */






function UndoButton() {
  const hasUndo = (0,external_wp_data_namespaceObject.useSelect)(select => select(external_wp_coreData_namespaceObject.store).hasUndo(), []);
  const {
    undo
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_coreData_namespaceObject.store);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.ToolbarButton, {
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
//# sourceMappingURL=undo-button.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/header/redo-button.js


/**
 * WordPress dependencies
 */






function RedoButton() {
  const hasRedo = (0,external_wp_data_namespaceObject.useSelect)(select => select(external_wp_coreData_namespaceObject.store).hasRedo(), []);
  const {
    redo
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_coreData_namespaceObject.store);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.ToolbarButton, {
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
//# sourceMappingURL=redo-button.js.map
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
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/header/inserter-toggle.js


/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */




function InserterToggle() {
  const {
    navBlockClientId
  } = use_navigation_editor_root_block();
  const {
    isInserterOpened,
    hasInserterItems
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    return {
      hasInserterItems: select(external_wp_blockEditor_namespaceObject.store).hasInserterItems(navBlockClientId),
      isInserterOpened: select(store).isInserterOpened()
    };
  }, [navBlockClientId]);
  const {
    setIsInserterOpened
  } = (0,external_wp_data_namespaceObject.useDispatch)(store);
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.ToolbarItem, {
    as: external_wp_components_namespaceObject.Button,
    className: "edit-navigation-header-inserter-toggle",
    variant: "primary",
    isPressed: isInserterOpened,
    onMouseDown: event => {
      event.preventDefault();
    },
    onClick: () => setIsInserterOpened(!isInserterOpened),
    icon: library_plus
    /* translators: button label text should, if possible, be under 16
    		characters. */
    ,
    label: (0,external_wp_i18n_namespaceObject._x)('Toggle block inserter', 'Generic label for block inserter button'),
    disabled: !hasInserterItems
  });
}

/* harmony default export */ var inserter_toggle = (InserterToggle);
//# sourceMappingURL=inserter-toggle.js.map
;// CONCATENATED MODULE: ./packages/icons/build-module/library/external.js


/**
 * WordPress dependencies
 */

const external = (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,external_wp_element_namespaceObject.createElement)(external_wp_primitives_namespaceObject.Path, {
  d: "M18.2 17c0 .7-.6 1.2-1.2 1.2H7c-.7 0-1.2-.6-1.2-1.2V7c0-.7.6-1.2 1.2-1.2h3.2V4.2H7C5.5 4.2 4.2 5.5 4.2 7v10c0 1.5 1.2 2.8 2.8 2.8h10c1.5 0 2.8-1.2 2.8-2.8v-3.6h-1.5V17zM14.9 3v1.5h3.7l-6.4 6.4 1.1 1.1 6.4-6.4v3.7h1.5V3h-6.3z"
}));
/* harmony default export */ var library_external = (external);
//# sourceMappingURL=external.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/header/tools-more-menu-group.js


/**
 * WordPress dependencies
 */

const {
  Fill: ToolsMoreMenuGroup,
  Slot
} = (0,external_wp_components_namespaceObject.createSlotFill)('EditNavigationToolsMoreMenuGroup');

ToolsMoreMenuGroup.Slot = _ref => {
  let {
    fillProps
  } = _ref;
  return (0,external_wp_element_namespaceObject.createElement)(Slot, {
    fillProps: fillProps
  }, fills => fills);
};

/* harmony default export */ var tools_more_menu_group = (ToolsMoreMenuGroup);
//# sourceMappingURL=tools-more-menu-group.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/header/more-menu.js


/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */


function MoreMenu() {
  return (0,external_wp_element_namespaceObject.createElement)(MoreMenuDropdown, null, onClose => (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuGroup, {
    label: (0,external_wp_i18n_namespaceObject.__)('Tools')
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.MenuItem, {
    role: "menuitem",
    icon: library_external,
    href: "https://github.com/WordPress/gutenberg/tree/trunk/packages/edit-navigation/docs/user-documentation.md",
    target: "_blank",
    rel: "noopener noreferrer"
  }, (0,external_wp_i18n_namespaceObject.__)('Help'), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.VisuallyHidden, {
    as: "span"
  },
  /* translators: accessibility text */
  (0,external_wp_i18n_namespaceObject.__)('(opens in a new tab)'))), (0,external_wp_element_namespaceObject.createElement)(tools_more_menu_group.Slot, {
    fillProps: {
      onClose
    }
  })));
}
//# sourceMappingURL=more-menu.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/header/index.js


/**
 * WordPress dependencies
 */




/**
 * Internal dependencies
 */








function Header(_ref) {
  let {
    isMenuSelected,
    menus,
    isPending,
    navigationPost
  } = _ref;
  const isMediumViewport = (0,external_wp_compose_namespaceObject.useViewportMatch)('medium');

  if (!isMenuSelected) {
    return (0,external_wp_element_namespaceObject.createElement)("div", {
      className: "edit-navigation-header"
    }, (0,external_wp_element_namespaceObject.createElement)("div", {
      className: "edit-navigation-header__toolbar-wrapper"
    }, (0,external_wp_element_namespaceObject.createElement)("h1", {
      className: "edit-navigation-header__title"
    }, (0,external_wp_i18n_namespaceObject.__)('Navigation'))));
  }

  return (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-navigation-header"
  }, (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-navigation-header__toolbar-wrapper"
  }, isMediumViewport && (0,external_wp_element_namespaceObject.createElement)("h1", {
    className: "edit-navigation-header__title"
  }, (0,external_wp_i18n_namespaceObject.__)('Navigation')), (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.NavigableToolbar, {
    className: "edit-navigation-header__toolbar",
    "aria-label": (0,external_wp_i18n_namespaceObject.__)('Document tools')
  }, (0,external_wp_element_namespaceObject.createElement)(inserter_toggle, null), isMediumViewport && (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(UndoButton, null), (0,external_wp_element_namespaceObject.createElement)(RedoButton, null)))), (0,external_wp_element_namespaceObject.createElement)(MenuActions, {
    menus: menus,
    isLoading: isPending
  }), (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-navigation-header__actions"
  }, isMediumViewport && (0,external_wp_element_namespaceObject.createElement)(NewButton, null), (0,external_wp_element_namespaceObject.createElement)(SaveButton, {
    navigationPost: navigationPost
  }), (0,external_wp_element_namespaceObject.createElement)(pinned_items.Slot, {
    scope: "core/edit-navigation"
  }), (0,external_wp_element_namespaceObject.createElement)(MoreMenu, null)));
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/notices/index.js


/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */




function EditNavigationNotices() {
  const {
    removeNotice
  } = (0,external_wp_data_namespaceObject.useDispatch)(external_wp_notices_namespaceObject.store);
  const notices = (0,external_wp_data_namespaceObject.useSelect)(select => select(external_wp_notices_namespaceObject.store).getNotices(), []);
  const dismissibleNotices = (0,external_lodash_namespaceObject.filter)(notices, {
    isDismissible: true,
    type: 'default'
  });
  const nonDismissibleNotices = (0,external_lodash_namespaceObject.filter)(notices, {
    isDismissible: false,
    type: 'default'
  });
  const snackbarNotices = (0,external_lodash_namespaceObject.filter)(notices, {
    type: 'snackbar'
  });
  return (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.NoticeList, {
    notices: nonDismissibleNotices,
    className: "edit-navigation-notices__notice-list"
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.NoticeList, {
    notices: dismissibleNotices,
    className: "edit-navigation-notices__notice-list",
    onRemove: removeNotice
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.SnackbarList, {
    notices: snackbarNotices,
    className: "edit-navigation-notices__snackbar-list",
    onRemove: removeNotice
  }));
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/editor/index.js


/**
 * WordPress dependencies
 */


function Editor(_ref) {
  let {
    isPending
  } = _ref;
  return (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-navigation-editor"
  }, isPending ? (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Spinner, null) : (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "editor-styles-wrapper"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.WritingFlow, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.ObserveTyping, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.BlockList, null)))));
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
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/inserter-sidebar/index.js



/**
 * WordPress dependencies
 */





/**
 * Internal dependencies
 */



const SHOW_PREVIEWS = false;

function InserterSidebar() {
  const isMobileViewport = (0,external_wp_compose_namespaceObject.useViewportMatch)('medium', '<');
  const {
    navBlockClientId,
    lastNavBlockItemIndex
  } = use_navigation_editor_root_block();
  const {
    hasInserterItems,
    selectedBlockClientId
  } = (0,external_wp_data_namespaceObject.useSelect)(select => {
    var _select$getSelectedBl;

    return {
      hasInserterItems: select(external_wp_blockEditor_namespaceObject.store).hasInserterItems(navBlockClientId),
      selectedBlockClientId: (_select$getSelectedBl = select(external_wp_blockEditor_namespaceObject.store).getSelectedBlock()) === null || _select$getSelectedBl === void 0 ? void 0 : _select$getSelectedBl.clientId
    };
  }, [navBlockClientId]);
  const {
    setIsInserterOpened
  } = (0,external_wp_data_namespaceObject.useDispatch)(store);
  const [inserterDialogRef, inserterDialogProps] = (0,external_wp_compose_namespaceObject.__experimentalUseDialog)({
    onClose: () => setIsInserterOpened(false)
  }); // Only concerned with whether there are items to display. If not then
  // we shouldn't render.

  if (!hasInserterItems) {
    return null;
  }

  const shouldInsertInNavBlock = !selectedBlockClientId || navBlockClientId === selectedBlockClientId;
  return (0,external_wp_element_namespaceObject.createElement)("div", _extends({
    ref: inserterDialogRef
  }, inserterDialogProps, {
    className: "edit-navigation-layout__inserter-panel"
  }), (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-navigation-layout__inserter-panel-header"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Button, {
    icon: library_close,
    onClick: () => setIsInserterOpened(false)
  })), (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "edit-navigation-layout__inserter-panel-content"
  }, (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.__experimentalLibrary // If the root Nav block is selected then any items inserted by the
  // global inserter should append after the last nav item. Otherwise
  // simply allow default Gutenberg behaviour.
  , {
    rootClientId: shouldInsertInNavBlock ? navBlockClientId : undefined // If set, insertion will be into the block with this ID.
    ,
    __experimentalInsertionIndex: // If set, insertion will be into this explicit position.
    shouldInsertInNavBlock ? lastNavBlockItemIndex : undefined,
    shouldFocusBlock: isMobileViewport,
    showInserterHelpPanel: SHOW_PREVIEWS
  })));
}

/* harmony default export */ var inserter_sidebar = (InserterSidebar);
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/layout/unsaved-changes-warning.js
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
  const isDirty = (0,external_wp_data_namespaceObject.useSelect)(select => {
    const {
      __experimentalGetDirtyEntityRecords
    } = select(external_wp_coreData_namespaceObject.store);

    const dirtyEntityRecords = __experimentalGetDirtyEntityRecords();

    return dirtyEntityRecords.length > 0;
  }, []);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    /**
     * Warns the user if there are unsaved changes before leaving the editor.
     *
     * @param {Event} event `beforeunload` event.
     *
     * @return {?string} Warning prompt message, if unsaved changes exist.
     */
    const warnIfUnsavedChanges = event => {
      if (isDirty) {
        event.returnValue = (0,external_wp_i18n_namespaceObject.__)('You have unsaved changes. If you proceed, they will be lost.');
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
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/components/layout/index.js


/**
 * WordPress dependencies
 */









/**
 * Internal dependencies
 */













const interfaceLabels = {
  /* translators: accessibility text for the navigation screen top bar landmark region. */
  header: (0,external_wp_i18n_namespaceObject.__)('Navigation top bar'),

  /* translators: accessibility text for the navigation screen content landmark region. */
  body: (0,external_wp_i18n_namespaceObject.__)('Navigation menu blocks'),

  /* translators: accessibility text for the navigation screen settings landmark region. */
  sidebar: (0,external_wp_i18n_namespaceObject.__)('Navigation settings'),
  secondarySidebar: (0,external_wp_i18n_namespaceObject.__)('Block library')
};
function Layout(_ref) {
  let {
    blockEditorSettings
  } = _ref;
  const contentAreaRef = (0,external_wp_blockEditor_namespaceObject.__unstableUseBlockSelectionClearer)();
  const [isMenuNameControlFocused, setIsMenuNameControlFocused] = (0,external_wp_element_namespaceObject.useState)(false);
  const {
    saveNavigationPost
  } = (0,external_wp_data_namespaceObject.useDispatch)(store);

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
  const [blocks, onInput, onChange] = (0,external_wp_coreData_namespaceObject.useEntityBlockEditor)(NAVIGATION_POST_KIND, NAVIGATION_POST_POST_TYPE, {
    id: navigationPost === null || navigationPost === void 0 ? void 0 : navigationPost.id
  });
  const {
    hasSidebarEnabled,
    isInserterOpened
  } = (0,external_wp_data_namespaceObject.useSelect)(select => ({
    hasSidebarEnabled: !!select(store_store).getActiveComplementaryArea('core/edit-navigation'),
    isInserterOpened: select(store).isInserterOpened()
  }), []);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    if (!selectedMenuId && menus !== null && menus !== void 0 && menus.length) {
      selectMenu(menus[0].id);
    }
  }, [selectedMenuId, menus]);
  useMenuNotifications(selectedMenuId);
  const hasMenus = !!(menus !== null && menus !== void 0 && menus.length);
  const isBlockEditorReady = !!(hasMenus && navigationPost && isMenuSelected);
  return (0,external_wp_element_namespaceObject.createElement)(error_boundary, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_keyboardShortcuts_namespaceObject.ShortcutProvider, null, (0,external_wp_element_namespaceObject.createElement)("div", {
    hidden: !isMenuBeingDeleted,
    className: 'edit-navigation-layout__overlay'
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.SlotFillProvider, null, (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.BlockEditorKeyboardShortcuts.Register, null), (0,external_wp_element_namespaceObject.createElement)(shortcuts.Register, null), (0,external_wp_element_namespaceObject.createElement)(shortcuts, {
    saveBlocks: savePost
  }), (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.BlockEditorProvider, {
    value: blocks,
    onInput: onInput,
    onChange: onChange,
    settings: { ...blockEditorSettings,
      templateLock: 'all'
    },
    useSubRegistry: false
  }, (0,external_wp_element_namespaceObject.createElement)(IsMenuNameControlFocusedContext.Provider, {
    value: (0,external_wp_element_namespaceObject.useMemo)(() => [isMenuNameControlFocused, setIsMenuNameControlFocused], [isMenuNameControlFocused])
  }, (0,external_wp_element_namespaceObject.createElement)(interface_skeleton, {
    className: "edit-navigation-layout",
    labels: interfaceLabels,
    header: (0,external_wp_element_namespaceObject.createElement)(Header, {
      isMenuSelected: isMenuSelected,
      isPending: !hasLoadedMenus,
      menus: menus,
      navigationPost: navigationPost
    }),
    content: (0,external_wp_element_namespaceObject.createElement)(external_wp_element_namespaceObject.Fragment, null, (0,external_wp_element_namespaceObject.createElement)(EditNavigationNotices, null), !hasFinishedInitialLoad && (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Spinner, null), !isMenuSelected && hasFinishedInitialLoad && (0,external_wp_element_namespaceObject.createElement)(UnselectedMenuState, {
      onSelectMenu: selectMenu,
      onCreate: selectMenu,
      menus: menus
    }), isBlockEditorReady && (0,external_wp_element_namespaceObject.createElement)("div", {
      className: "edit-navigation-layout__content-area",
      ref: contentAreaRef
    }, (0,external_wp_element_namespaceObject.createElement)(external_wp_blockEditor_namespaceObject.BlockTools, null, (0,external_wp_element_namespaceObject.createElement)(Editor, {
      isPending: !hasLoadedMenus
    })))),
    sidebar: hasSidebarEnabled && (0,external_wp_element_namespaceObject.createElement)(complementary_area.Slot, {
      scope: "core/edit-navigation"
    }),
    secondarySidebar: isInserterOpened && (0,external_wp_element_namespaceObject.createElement)(inserter_sidebar, null)
  }), isMenuSelected && (0,external_wp_element_namespaceObject.createElement)(Sidebar, {
    menus: menus,
    menuId: selectedMenuId,
    onSelectMenu: selectMenu,
    onDeleteMenu: deleteMenu,
    isMenuBeingDeleted: isMenuBeingDeleted
  })), (0,external_wp_element_namespaceObject.createElement)(UnsavedChangesWarning, null)), (0,external_wp_element_namespaceObject.createElement)(external_wp_components_namespaceObject.Popover.Slot, null), (0,external_wp_element_namespaceObject.createElement)(external_wp_plugins_namespaceObject.PluginArea, null))));
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: external ["wp","url"]
var external_wp_url_namespaceObject = window["wp"]["url"];
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/utils/index.js
/**
 * WordPress dependencies
 */

/**
 * The purpose of this function is to create a middleware that is responsible for preloading menu-related data.
 * It uses data that is returned from the /wp/v2/menus endpoint for requests
 * to the /wp/v2/menu/<menuId> endpoint, because the data is the same.
 * This way, we can avoid making additional REST API requests.
 * This middleware can be removed if/when we implement caching at the wordpress/core-data level.
 *
 * @param {Object} preloadedData
 * @return {Function} Preloading middleware.
 */

function createMenuPreloadingMiddleware(preloadedData) {
  const cache = Object.keys(preloadedData).reduce((result, path) => {
    result[(0,external_wp_url_namespaceObject.normalizePath)(path)] = preloadedData[path];
    return result;
  },
  /** @type {Record<string, any>} */
  {});
  let menusDataLoaded = false;
  let menuDataLoaded = false;
  return (options, next) => {
    var _Object$keys, _cache$key;

    const {
      parse = true
    } = options;

    if ('string' !== typeof options.path) {
      return next(options);
    }

    const method = options.method || 'GET';

    if ('GET' !== method) {
      return next(options);
    }

    const path = (0,external_wp_url_namespaceObject.normalizePath)(options.path);

    if (!menusDataLoaded && cache[path]) {
      menusDataLoaded = true;
      return sendSuccessResponse(cache[path], parse);
    }

    if (menuDataLoaded) {
      return next(options);
    }

    const matches = path.match(/^\/wp\/v2\/menus\/(\d+)\?context=edit$/);

    if (!matches) {
      return next(options);
    }

    const key = (_Object$keys = Object.keys(cache)) === null || _Object$keys === void 0 ? void 0 : _Object$keys[0];
    const menuData = (_cache$key = cache[key]) === null || _cache$key === void 0 ? void 0 : _cache$key.body;

    if (!menuData) {
      return next(options);
    }

    const menuId = parseInt(matches[1]);
    const menu = menuData.filter(_ref => {
      let {
        id
      } = _ref;
      return id === menuId;
    });

    if (menu.length > 0) {
      menuDataLoaded = true; // We don't have headers because we "emulate" this request

      return sendSuccessResponse({
        body: menu[0],
        headers: {}
      }, parse);
    }

    return next(options);
  };
}
/**
 * This is a helper function that sends a success response.
 *
 * @param {Object}  responseData An object with the menu data
 * @param {boolean} parse        A boolean that controls whether to send a response or just the response data
 * @return {Object} Resolved promise
 */

function sendSuccessResponse(responseData, parse) {
  return Promise.resolve(parse ? responseData.body : new window.Response(JSON.stringify(responseData.body), {
    status: 200,
    statusText: 'OK',
    headers: responseData.headers
  }));
}
//# sourceMappingURL=index.js.map
;// CONCATENATED MODULE: ./packages/edit-navigation/build-module/index.js


/**
 * WordPress dependencies
 */






/**
 * Internal dependencies
 */






function NavEditor(_ref) {
  let {
    settings
  } = _ref;
  const {
    setIsInserterOpened
  } = (0,external_wp_data_namespaceObject.useDispatch)(store); // Allows the QuickInserter to toggle the sidebar inserter.
  // This is marked as experimental to give time for the quick inserter to mature.

  const __experimentalSetIsInserterOpened = setIsInserterOpened; // Provide link suggestions handler to fetch search results for Link UI.

  const __experimentalFetchLinkSuggestions = (search, searchOptions) => {
    // Bump the default number of suggestions.
    // See https://github.com/WordPress/gutenberg/issues/34283.
    searchOptions.perPage = 10;
    return (0,external_wp_coreData_namespaceObject.__experimentalFetchLinkSuggestions)(search, searchOptions, settings);
  };

  const editorSettings = (0,external_wp_element_namespaceObject.useMemo)(() => {
    return { ...settings,
      __experimentalFetchLinkSuggestions,
      __experimentalSetIsInserterOpened,
      __experimentalFetchRichUrlData: external_wp_coreData_namespaceObject.__experimentalFetchUrlData
    };
  }, [settings, __experimentalFetchLinkSuggestions, __experimentalSetIsInserterOpened]);
  return (0,external_wp_element_namespaceObject.createElement)(Layout, {
    blockEditorSettings: editorSettings
  });
}
/**
 * Setup and registration of editor.
 *
 * @param {Object} settings blockEditor settings.
 */


function setUpEditor(settings) {
  addFilters(!settings.blockNavMenus); // Set up the navigation post entity.

  (0,external_wp_data_namespaceObject.dispatch)(external_wp_coreData_namespaceObject.store).addEntities([{
    kind: NAVIGATION_POST_KIND,
    name: NAVIGATION_POST_POST_TYPE,
    transientEdits: {
      blocks: true,
      selection: true
    },
    label: (0,external_wp_i18n_namespaceObject.__)('Navigation Post'),
    __experimentalNoFetch: true
  }]);

  (0,external_wp_data_namespaceObject.dispatch)(external_wp_blocks_namespaceObject.store).__experimentalReapplyBlockTypeFilters();

  (0,external_wp_blockLibrary_namespaceObject.registerCoreBlocks)();

  if (true) {
    (0,external_wp_blockLibrary_namespaceObject.__experimentalRegisterExperimentalCoreBlocks)();
  }
}
/**
 * Initalise and render editor into DOM.
 *
 * @param {string} id       ID of HTML element into which the editor will be rendered.
 * @param {Object} settings blockEditor settings.
 */


function initialize(id, settings) {
  setUpEditor(settings);
  (0,external_wp_element_namespaceObject.render)((0,external_wp_element_namespaceObject.createElement)(NavEditor, {
    settings: settings
  }), document.getElementById(id));
}

//# sourceMappingURL=index.js.map
}();
(window.wp = window.wp || {}).editNavigation = __webpack_exports__;
/******/ })()
;