"use strict";
(globalThis["webpackChunkgutenberg"] = globalThis["webpackChunkgutenberg"] || []).push([[666],{

/***/ 744:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {


// EXPORTS
__webpack_require__.d(__webpack_exports__, {
  "h": () => (/* reexport */ store)
});

// EXTERNAL MODULE: ./node_modules/preact/hooks/dist/hooks.module.js
var hooks_module = __webpack_require__(396);
// EXTERNAL MODULE: ./node_modules/deepsignal/dist/deepsignal.module.js
var deepsignal_module = __webpack_require__(944);
// EXTERNAL MODULE: ./node_modules/preact/dist/preact.module.js
var preact_module = __webpack_require__(400);
;// CONCATENATED MODULE: ./packages/block-library/src/utils/interactivity/portals.js
/**
 * External dependencies
 */

/**
 * @param {import('../../src/index').RenderableProps<{ context: any }>} props
 */

function ContextProvider(props) {
  this.getChildContext = () => props.context;

  return props.children;
}
/**
 * Portal component
 *
 * @this {import('./internal').Component}
 * @param {object | null | undefined} props
 *
 *                                          TODO: use createRoot() instead of fake root
 */


function Portal(props) {
  const _this = this;

  const container = props._container;

  _this.componentWillUnmount = function () {
    (0,preact_module/* render */.sY)(null, _this._temp);
    _this._temp = null;
    _this._container = null;
  }; // When we change container we should clear our old container and
  // indicate a new mount.


  if (_this._container && _this._container !== container) {
    _this.componentWillUnmount();
  } // When props.vnode is undefined/false/null we are dealing with some kind of
  // conditional vnode. This should not trigger a render.


  if (props._vnode) {
    if (!_this._temp) {
      _this._container = container; // Create a fake DOM parent node that manages a subset of `container`'s children:

      _this._temp = {
        nodeType: 1,
        parentNode: container,
        childNodes: [],

        appendChild(child) {
          this.childNodes.push(child);

          _this._container.appendChild(child);
        },

        insertBefore(child) {
          this.childNodes.push(child);

          _this._container.appendChild(child);
        },

        removeChild(child) {
          this.childNodes.splice( // eslint-disable-next-line no-bitwise
          this.childNodes.indexOf(child) >>> 1, 1);

          _this._container.removeChild(child);
        }

      };
    } // Render our wrapping element into temp.


    (0,preact_module/* render */.sY)((0,preact_module/* createElement */.az)(ContextProvider, {
      context: _this.context
    }, props._vnode), _this._temp);
  } // When we come from a conditional render, on a mounted
  // portal we should clear the DOM.
  else if (_this._temp) {
    _this.componentWillUnmount();
  }
}
/**
 * Create a `Portal` to continue rendering the vnode tree at a different DOM node
 *
 * @param {import('./internal').VNode}         vnode     The vnode to render
 * @param {import('./internal').PreactElement} container The DOM node to continue rendering in to.
 */


function createPortal(vnode, container) {
  const el = (0,preact_module/* createElement */.az)(Portal, {
    _vnode: vnode,
    _container: container
  });
  el.containerInfo = container;
  return el;
}
// EXTERNAL MODULE: ./node_modules/@preact/signals/dist/signals.module.js
var signals_module = __webpack_require__(854);
;// CONCATENATED MODULE: ./packages/block-library/src/utils/interactivity/utils.js
/**
 * External dependencies
 */



function afterNextFrame(callback) {
  const done = () => {
    window.cancelAnimationFrame(raf);
    setTimeout(callback);
  };

  const raf = window.requestAnimationFrame(done);
} // Using the mangled properties:
// this.c: this._callback
// this.x: this._compute
// https://github.com/preactjs/signals/blob/main/mangle.json


function createFlusher(compute, notify) {
  let flush;
  const dispose = (0,signals_module/* effect */.cE)(function () {
    flush = this.c.bind(this);
    this.x = compute;
    this.c = notify;
    return compute();
  });
  return {
    flush,
    dispose
  };
} // Version of `useSignalEffect` with a `useEffect`-like execution. This hook
// implementation comes from this PR:
// https://github.com/preactjs/signals/pull/290.
//
// We need to include it here in this repo until the mentioned PR is merged.


function useSignalEffect(cb) {
  const callback = (0,hooks_module/* useRef */.sO)(cb);
  callback.current = cb;
  (0,hooks_module/* useEffect */.d4)(() => {
    const execute = () => callback.current();

    const notify = () => afterNextFrame(eff.flush);

    const eff = createFlusher(execute, notify);
    return eff.dispose;
  }, []);
} // For wrapperless hydration.
// See https://gist.github.com/developit/f4c67a2ede71dc2fab7f357f39cff28c

const createRootFragment = (parent, replaceNode) => {
  replaceNode = [].concat(replaceNode);
  const s = replaceNode[replaceNode.length - 1].nextSibling;

  function insert(c, r) {
    parent.insertBefore(c, r || s);
  }

  return parent.__k = {
    nodeType: 1,
    parentNode: parent,
    firstChild: replaceNode[0],
    childNodes: replaceNode,
    insertBefore: insert,
    appendChild: insert,

    removeChild(c) {
      parent.removeChild(c);
    }

  };
};
;// CONCATENATED MODULE: ./packages/block-library/src/utils/interactivity/store.js
/**
 * External dependencies
 */


const isObject = item => item && typeof item === 'object' && !Array.isArray(item);

const deepMerge = (target, source) => {
  if (isObject(target) && isObject(source)) {
    for (const key in source) {
      if (isObject(source[key])) {
        if (!target[key]) Object.assign(target, {
          [key]: {}
        });
        deepMerge(target[key], source[key]);
      } else {
        Object.assign(target, {
          [key]: source[key]
        });
      }
    }
  }
};

const getSerializedState = () => {
  // TODO: change the store tag ID for a better one.
  const storeTag = document.querySelector(`script[type="application/json"]#store`);
  if (!storeTag) return {};

  try {
    const {
      state
    } = JSON.parse(storeTag.textContent);
    if (isObject(state)) return state;
    throw Error('Parsed state is not an object');
  } catch (e) {
    // eslint-disable-next-line no-console
    console.log(e);
  }

  return {};
};

const rawState = getSerializedState();
const rawStore = {
  state: (0,deepsignal_module/* deepSignal */.Aj)(rawState)
};
const store = ({
  state,
  ...block
}) => {
  deepMerge(rawStore, block);
  deepMerge(rawState, state);
};
// EXTERNAL MODULE: ./node_modules/preact/jsx-runtime/dist/jsxRuntime.module.js
var jsxRuntime_module = __webpack_require__(584);
;// CONCATENATED MODULE: ./packages/block-library/src/utils/interactivity/hooks.js
/**
 * External dependencies
 */


/**
 * Internal dependencies
 */

 // Main context.


const context = (0,preact_module/* createContext */.kr)({}); // WordPress Directives.

const directiveMap = {};
const directivePriorities = {};
const directive = (name, cb, {
  priority = 10
} = {}) => {
  directiveMap[name] = cb;
  directivePriorities[name] = priority;
}; // Resolve the path to some property of the store object.

const resolve = (path, ctx) => {
  let current = { ...rawStore,
    context: ctx
  };
  path.split('.').forEach(p => current = current[p]);
  return current;
}; // Generate the evaluate function.


const getEvaluate = ({
  ref
} = {}) => (path, extraArgs = {}) => {
  // If path starts with !, remove it and save a flag.
  const hasNegationOperator = path[0] === '!' && !!(path = path.slice(1));
  const value = resolve(path, extraArgs.context);
  const returnValue = typeof value === 'function' ? value({
    ref: ref.current,
    ...rawStore,
    ...extraArgs
  }) : value;
  return hasNegationOperator ? !returnValue : returnValue;
}; // Separate directives by priority. The resulting array contains objects
// of directives grouped by same priority, and sorted in ascending order.


const usePriorityLevels = directives => (0,hooks_module/* useMemo */.Ye)(() => {
  const byPriority = Object.entries(directives).reduce((acc, [name, values]) => {
    const priority = directivePriorities[name];
    if (!acc[priority]) acc[priority] = {};
    acc[priority][name] = values;
    return acc;
  }, {});
  return Object.entries(byPriority).sort(([p1], [p2]) => p1 - p2).map(([, obj]) => obj);
}, [directives]); // Directive wrapper.


const Directive = ({
  type,
  directives,
  props: originalProps
}) => {
  const ref = (0,hooks_module/* useRef */.sO)(null);
  const element = (0,preact_module.h)(type, { ...originalProps,
    ref
  });
  const evaluate = (0,hooks_module/* useMemo */.Ye)(() => getEvaluate({
    ref
  }), []); // Add wrappers recursively for each priority level.

  const byPriorityLevel = usePriorityLevels(directives);
  return (0,jsxRuntime_module/* jsx */.tZ)(RecursivePriorityLevel, {
    directives: byPriorityLevel,
    element: element,
    evaluate: evaluate,
    originalProps: originalProps
  });
}; // Priority level wrapper.


const RecursivePriorityLevel = ({
  directives: [directives, ...rest],
  element,
  evaluate,
  originalProps
}) => {
  // This element needs to be a fresh copy so we are not modifying an already
  // rendered element with Preact's internal properties initialized. This
  // prevents an error with changes in `element.props.children` not being
  // reflected in `element.__k`.
  element = (0,preact_module/* cloneElement */.Tm)(element); // Recursively render the wrapper for the next priority level.
  //
  // Note that, even though we're instantiating a vnode with a
  // `RecursivePriorityLevel` here, its render function will not be executed
  // just yet. Actually, it will be delayed until the current render function
  // has finished. That ensures directives in the current priorty level have
  // run (and thus modified the passed `element`) before the next level.

  const children = rest.length > 0 ? (0,jsxRuntime_module/* jsx */.tZ)(RecursivePriorityLevel, {
    directives: rest,
    element: element,
    evaluate: evaluate,
    originalProps: originalProps
  }) : element;
  const props = { ...originalProps,
    children
  };
  const directiveArgs = {
    directives,
    props,
    element,
    context,
    evaluate
  };

  for (const d in directives) {
    const wrapper = directiveMap[d]?.(directiveArgs);
    if (wrapper !== undefined) props.children = wrapper;
  }

  return props.children;
}; // Preact Options Hook called each time a vnode is created.


const old = preact_module/* options.vnode */.YM.vnode;

preact_module/* options.vnode */.YM.vnode = vnode => {
  if (vnode.props.__directives) {
    const props = vnode.props;
    const directives = props.__directives;
    delete props.__directives;
    vnode.props = {
      type: vnode.type,
      directives,
      props
    };
    vnode.type = Directive;
  }

  if (old) old(vnode);
};
;// CONCATENATED MODULE: ./packages/block-library/src/utils/interactivity/directives.js
/**
 * External dependencies
 */


/**
 * Internal dependencies
 */


/**
 * Internal dependencies
 */





const directives_isObject = item => item && typeof item === 'object' && !Array.isArray(item);

const mergeDeepSignals = (target, source) => {
  for (const k in source) {
    if (typeof (0,deepsignal_module/* peek */.fj)(target, k) === 'undefined') {
      target[`$${k}`] = source[`$${k}`];
    } else if (directives_isObject((0,deepsignal_module/* peek */.fj)(target, k)) && directives_isObject((0,deepsignal_module/* peek */.fj)(source, k))) {
      mergeDeepSignals(target[`$${k}`].peek(), source[`$${k}`].peek());
    }
  }
};

/* harmony default export */ const directives = (() => {
  // data-wp-context
  directive('context', ({
    directives: {
      context: {
        default: context
      }
    },
    props: {
      children
    },
    context: inherited
  }) => {
    const {
      Provider
    } = inherited;
    const inheritedValue = (0,hooks_module/* useContext */.qp)(inherited);
    const value = (0,hooks_module/* useMemo */.Ye)(() => {
      const localValue = (0,deepsignal_module/* deepSignal */.Aj)(context);
      mergeDeepSignals(localValue, inheritedValue);
      return localValue;
    }, [context, inheritedValue]);
    return (0,jsxRuntime_module/* jsx */.tZ)(Provider, {
      value: value,
      children: children
    });
  }, {
    priority: 5
  }); // data-wp-body

  directive('body', ({
    props: {
      children
    },
    context: inherited
  }) => {
    const {
      Provider
    } = inherited;
    const inheritedValue = (0,hooks_module/* useContext */.qp)(inherited);
    return createPortal((0,jsxRuntime_module/* jsx */.tZ)(Provider, {
      value: inheritedValue,
      children: children
    }), document.body);
  }); // data-wp-effect.[name]

  directive('effect', ({
    directives: {
      effect
    },
    context,
    evaluate
  }) => {
    const contextValue = (0,hooks_module/* useContext */.qp)(context);
    Object.values(effect).forEach(path => {
      useSignalEffect(() => {
        return evaluate(path, {
          context: contextValue
        });
      });
    });
  }); // data-wp-init.[name]

  directive('init', ({
    directives: {
      init
    },
    context,
    evaluate
  }) => {
    const contextValue = (0,hooks_module/* useContext */.qp)(context);
    Object.values(init).forEach(path => {
      (0,hooks_module/* useEffect */.d4)(() => {
        return evaluate(path, {
          context: contextValue
        });
      }, []);
    });
  }); // data-wp-on.[event]

  directive('on', ({
    directives: {
      on
    },
    element,
    evaluate,
    context
  }) => {
    const contextValue = (0,hooks_module/* useContext */.qp)(context);
    Object.entries(on).forEach(([name, path]) => {
      element.props[`on${name}`] = event => {
        evaluate(path, {
          event,
          context: contextValue
        });
      };
    });
  }); // data-wp-class.[classname]

  directive('class', ({
    directives: {
      class: className
    },
    element,
    evaluate,
    context
  }) => {
    const contextValue = (0,hooks_module/* useContext */.qp)(context);
    Object.keys(className).filter(n => n !== 'default').forEach(name => {
      const result = evaluate(className[name], {
        className: name,
        context: contextValue
      });
      const currentClass = element.props.class || '';
      const classFinder = new RegExp(`(^|\\s)${name}(\\s|$)`, 'g');
      if (!result) element.props.class = currentClass.replace(classFinder, ' ').trim();else if (!classFinder.test(currentClass)) element.props.class = currentClass ? `${currentClass} ${name}` : name;
      (0,hooks_module/* useEffect */.d4)(() => {
        // This seems necessary because Preact doesn't change the class
        // names on the hydration, so we have to do it manually. It doesn't
        // need deps because it only needs to do it the first time.
        if (!result) {
          element.ref.current.classList.remove(name);
        } else {
          element.ref.current.classList.add(name);
        }
      }, []);
    });
  }); // data-wp-bind.[attribute]

  directive('bind', ({
    directives: {
      bind
    },
    element,
    context,
    evaluate
  }) => {
    const contextValue = (0,hooks_module/* useContext */.qp)(context);
    Object.entries(bind).filter(n => n !== 'default').forEach(([attribute, path]) => {
      const result = evaluate(path, {
        context: contextValue
      });
      element.props[attribute] = result; // This seems necessary because Preact doesn't change the attributes
      // on the hydration, so we have to do it manually. It doesn't need
      // deps because it only needs to do it the first time.

      (0,hooks_module/* useEffect */.d4)(() => {
        // aria- and data- attributes have no boolean representation.
        // A `false` value is different from the attribute not being
        // present, so we can't remove it.
        // We follow Preact's logic: https://github.com/preactjs/preact/blob/ea49f7a0f9d1ff2c98c0bdd66aa0cbc583055246/src/diff/props.js#L131C24-L136
        if (result === false && attribute[4] !== '-') {
          element.ref.current.removeAttribute(attribute);
        } else {
          element.ref.current.setAttribute(attribute, result === true && attribute[4] !== '-' ? '' : result);
        }
      }, []);
    });
  }); // data-wp-ignore

  directive('ignore', ({
    element: {
      type: Type,
      props: {
        innerHTML,
        ...rest
      }
    }
  }) => {
    // Preserve the initial inner HTML.
    const cached = (0,hooks_module/* useMemo */.Ye)(() => innerHTML, []);
    return (0,jsxRuntime_module/* jsx */.tZ)(Type, {
      dangerouslySetInnerHTML: {
        __html: cached
      },
      ...rest
    });
  });
});
;// CONCATENATED MODULE: ./packages/block-library/src/utils/interactivity/constants.js
const directivePrefix = 'data-wp-';
;// CONCATENATED MODULE: ./packages/block-library/src/utils/interactivity/vdom.js
/**
 * External dependencies
 */

/**
 * Internal dependencies
 */


const ignoreAttr = `${directivePrefix}ignore`;
const islandAttr = `${directivePrefix}island`;
const directiveParser = new RegExp(`${directivePrefix}([^.]+)\.?(.*)$`);
const hydratedIslands = new WeakSet(); // Recursive function that transforms a DOM tree into vDOM.

function toVdom(root) {
  const treeWalker = document.createTreeWalker(root, 205 // ELEMENT + TEXT + COMMENT + CDATA_SECTION + PROCESSING_INSTRUCTION
  );

  function walk(node) {
    const {
      attributes,
      nodeType
    } = node;
    if (nodeType === 3) return [node.data];

    if (nodeType === 4) {
      const next = treeWalker.nextSibling();
      node.replaceWith(new window.Text(node.nodeValue));
      return [node.nodeValue, next];
    }

    if (nodeType === 8 || nodeType === 7) {
      const next = treeWalker.nextSibling();
      node.remove();
      return [null, next];
    }

    const props = {};
    const children = [];
    const directives = {};
    let hasDirectives = false;
    let ignore = false;
    let island = false;

    for (let i = 0; i < attributes.length; i++) {
      const n = attributes[i].name;

      if (n[directivePrefix.length] && n.slice(0, directivePrefix.length) === directivePrefix) {
        if (n === ignoreAttr) {
          ignore = true;
        } else if (n === islandAttr) {
          island = true;
        } else {
          hasDirectives = true;
          let val = attributes[i].value;

          try {
            val = JSON.parse(val);
          } catch (e) {}

          const [, prefix, suffix] = directiveParser.exec(n);
          directives[prefix] = directives[prefix] || {};
          directives[prefix][suffix || 'default'] = val;
        }
      } else if (n === 'ref') {
        continue;
      }

      props[n] = attributes[i].value;
    }

    if (ignore && !island) return [(0,preact_module.h)(node.localName, { ...props,
      innerHTML: node.innerHTML,
      __directives: {
        ignore: true
      }
    })];
    if (island) hydratedIslands.add(node);
    if (hasDirectives) props.__directives = directives;
    let child = treeWalker.firstChild();

    if (child) {
      while (child) {
        const [vnode, nextChild] = walk(child);
        if (vnode) children.push(vnode);
        child = nextChild || treeWalker.nextSibling();
      }

      treeWalker.parentNode();
    }

    return [(0,preact_module.h)(node.localName, props, children)];
  }

  return walk(treeWalker.currentNode);
}
;// CONCATENATED MODULE: ./packages/block-library/src/utils/interactivity/hydration.js
/**
 * External dependencies
 */

/**
 * Internal dependencies
 */




const init = async () => {
  document.querySelectorAll(`[${directivePrefix}island]`).forEach(node => {
    if (!hydratedIslands.has(node)) {
      const fragment = createRootFragment(node.parentNode, node);
      const vdom = toVdom(node);
      (0,preact_module/* hydrate */.ZB)(vdom, fragment);
    }
  });
};
;// CONCATENATED MODULE: ./packages/block-library/src/utils/interactivity/index.js
/**
 * Internal dependencies
 */



/**
 * Initialize the Interactivity API.
 */

directives();
document.addEventListener('DOMContentLoaded', async () => {
  await init(); // eslint-disable-next-line no-console

  console.log('Interactivity API started');
});

/***/ })

}]);