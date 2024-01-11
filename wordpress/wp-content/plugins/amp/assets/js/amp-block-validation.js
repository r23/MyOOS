(()=>{var e={11:(e,t,r)=>{var n={"./amp-block-validation.js":93,"./amp-document-setting-panel.js":877,"./amp-pre-publish-panel.js":327};function i(e){var t=a(e);return r(t)}function a(e){if(!r.o(n,e)){var t=new Error("Cannot find module '"+e+"'");throw t.code="MODULE_NOT_FOUND",t}return n[e]}i.keys=function(){return Object.keys(n)},i.resolve=a,e.exports=i,i.id=11},44:(e,t,r)=>{"use strict";r.d(t,{Z:()=>v});var n=r(196),i=r(736),a=r(609),s=r(818),o=r(883),l=r(201),c=r(361),u=r(504),d=r(590),m=r(93),E=r(307);function p(){const{isAMPEnabled:e,toggleAMP:t}=(0,u.c)(),r=(0,E.useRef)(`amp-toggle-${Math.random().toString(32).substr(-4)}`);return(0,n.createElement)(n.Fragment,null,(0,n.createElement)("label",{htmlFor:r.current},(0,i.__)("Enable AMP","amp")),(0,n.createElement)(a.FormToggle,{checked:e,onChange:t,id:r.current}))}var h=function(e){return(0,n.createElement)("svg",{...e},(0,n.createElement)("g",{clipPath:"url(#clip-amp-validation-errors-kept)",fill:"#BB522E"},(0,n.createElement)("path",{d:"M10.762 2.541c4.4 0 8 3.6 8 8 0 1.6-.5 3-1.2 4.2l1.4 1.5c1.1-1.6 1.8-3.6 1.8-5.7 0-5.5-4.5-10-10-10-2 0-3.9.6-5.5 1.7l1.4 1.5c1.2-.8 2.6-1.2 4.1-1.2ZM.762 10.541c0 5.5 4.5 10 10 10 2.7 0 5.1-1.1 6.9-2.8l-14-14.2c-1.8 1.8-2.9 4.3-2.9 7Zm10 8c-4.4 0-8-3.6-8-8 0-1.5.4-2.8 1.1-4l10.9 10.9c-1.2.7-2.5 1.1-4 1.1Z"}),(0,n.createElement)("path",{d:"M14.262 9.74c.1 0 .1-.1.1-.2 0-.2-.2-.4-.4-.4h-2l1.6 1.6.7-1ZM12.461 4.541h-.8l-1.6 2.6 1.7 1.7.7-4.3ZM7.462 11.541s-.1.1-.1.2c0 .2.2.4.4.4h2.3l-.8 4.5h.7l2.6-4.1-3.5-3.6-1.6 2.6Z"})),(0,n.createElement)("defs",null,(0,n.createElement)("clipPath",{id:"clip-amp-validation-errors-kept"},(0,n.createElement)("path",{fill:"#fff",transform:"translate(.762 .541)",d:"M0 0h20v20H0z"}))))};h.defaultProps={width:"21",height:"21",fill:"none",xmlns:"http://www.w3.org/2000/svg"};var g=function(e){return(0,n.createElement)("svg",{...e},(0,n.createElement)("path",{fill:"#707070",d:"M8 20c1.1 0 2-.9 2-2H6c0 1.1.9 2 2 2zm6-6V9c0-3.07-1.63-5.64-4.5-6.32V2C9.5 1.17 8.83.5 8 .5S6.5 1.17 6.5 2v.68C3.64 3.36 2 5.92 2 9v5l-2 2v1h16v-1l-2-2zm-2 1H4V9c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z"}))};function v(){const{isAMPEnabled:e}=(0,u.c)(),{isFetchingErrors:t,fetchingErrorsMessage:r}=(0,d.P)(),{openGeneralSidebar:E,closePublishSidebar:v}=(0,s.useDispatch)("core/edit-post"),{isPostDirty:_,maybeIsPostDirty:f,keptMarkupValidationErrorCount:w,reviewedValidationErrorCount:P,unreviewedValidationErrorCount:k}=(0,s.useSelect)((e=>({isPostDirty:e(o.h).getIsPostDirty(),maybeIsPostDirty:e(o.h).getMaybeIsPostDirty(),keptMarkupValidationErrorCount:e(o.h).getKeptMarkupValidationErrors().length,reviewedValidationErrorCount:e(o.h).getReviewedValidationErrors().length,unreviewedValidationErrorCount:e(o.h).getUnreviewedValidationErrors().length})),[]);if(!e)return(0,n.createElement)(a.PanelRow,null,(0,n.createElement)(p,null));if(t)return(0,n.createElement)(n.Fragment,null,(0,n.createElement)(a.PanelRow,null,(0,n.createElement)(p,null)),(0,n.createElement)(c.H,{message:r,isLoading:!0,isSmall:!0}));const b=()=>{v(),E(`${m.PLUGIN_NAME}/${m.SIDEBAR_NAME}`)};return _||f?(0,n.createElement)(n.Fragment,null,(0,n.createElement)(a.PanelRow,null,(0,n.createElement)(p,null)),(0,n.createElement)(c.H,{icon:(0,n.createElement)(g,null),message:f?(0,i.__)("Content may have changed. Trigger validation in the AMP Validation sidebar.","amp"):(0,i.__)("Content has changed. Trigger validation in the AMP Validation sidebar.","amp"),isSmall:!0}),(0,n.createElement)(a.PanelRow,null,(0,n.createElement)(a.Button,{onClick:b,isSecondary:!0,isSmall:!0},(0,i.__)("Open AMP Validation","amp")))):w>0?(0,n.createElement)(n.Fragment,null,(0,n.createElement)(a.PanelRow,null,(0,n.createElement)(p,null)),(0,n.createElement)(c.H,{icon:(0,n.createElement)(h,null),message:(0,i.sprintf)(/* translators: %d is count of validation errors whose invalid markup is kept */
(0,i._n)("AMP is blocked due to %d validation issue marked as kept.","AMP is blocked due to %d validation issues marked as kept.",w,"amp"),w),isSmall:!0}),(0,n.createElement)(a.PanelRow,null,(0,n.createElement)(a.Button,{onClick:b,isSecondary:!0,isSmall:!0},(0,i._n)("Review issue","Review issues",w,"amp")))):k>0?(0,n.createElement)(n.Fragment,null,(0,n.createElement)(a.PanelRow,null,(0,n.createElement)(p,null)),(0,n.createElement)(c.H,{icon:(0,n.createElement)(l.Jj,{broken:!0}),message:(0,i.sprintf)(/* translators: %d is count of unreviewed validation error */
(0,i._n)("AMP is valid, but %d issue needs review.","AMP is valid, but %d issues need review.",k,"amp"),k),isSmall:!0}),(0,n.createElement)(a.PanelRow,null,(0,n.createElement)(a.Button,{onClick:b,isSecondary:!0,isSmall:!0},(0,i._n)("Review issue","Review issues",k,"amp")))):(0,n.createElement)(n.Fragment,null,(0,n.createElement)(a.PanelRow,null,(0,n.createElement)(p,null)),(0,n.createElement)(c.H,{icon:(0,n.createElement)(l.Jj,null),message:P>0?(0,i.sprintf)(/* translators: %d is count of unreviewed validation error */
(0,i._n)("AMP is valid. %d issue was reviewed.","AMP is valid. %d issues were reviewed.",P,"amp"),P):(0,i.__)("No AMP validation issues detected.","amp"),isSmall:!0}),P>0&&(0,n.createElement)(a.PanelRow,null,(0,n.createElement)(a.Button,{onClick:b,isSecondary:!0,isSmall:!0},(0,i.__)("Open AMP Validation","amp"))))}g.defaultProps={fill:"none",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 16 20"}},201:(e,t,r)=>{"use strict";r.d(t,{Jj:()=>d,_4:()=>c,mE:()=>u});var n=r(196),i=function(e){return(0,n.createElement)("svg",{...e},(0,n.createElement)("path",{fill:"#0075C2",d:"m13.3 9.1-4 6.6h-.8l.7-4.3H7c-.2 0-.4-.2-.4-.4 0-.1.1-.2.1-.2l4-6.6h.7l-.7 4.3h2.2c.2 0 .4.2.4.4.1.1 0 .2 0 .2zM10 .5C4.7.5.4 4.8.4 10c0 5.3 4.3 9.5 9.6 9.5s9.6-4.3 9.6-9.5c0-5.3-4.3-9.5-9.6-9.5z"}))};i.defaultProps={xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 20 20"};var a=function(e){return(0,n.createElement)("svg",{...e},(0,n.createElement)("path",{d:"M9.863 16.815h-.7l.8-4.5h-2.3c-.2 0-.4-.2-.4-.4 0-.1.1-.2.1-.2l4.2-7h.8l-.8 4.6h2.3c.2 0 .4.2.4.4 0 .1 0 .2-.1.2l-4.3 6.9Zm.8-16.1c-5.5 0-10 4.5-10 10s4.5 10 10 10 10-4.5 10-10-4.5-10-10-10Z"}))};a.defaultProps={width:"21",height:"21",fill:"none",xmlns:"http://www.w3.org/2000/svg"};var s=function(e){return(0,n.createElement)("svg",{...e},(0,n.createElement)("path",{fillRule:"evenodd",clipRule:"evenodd",d:"M.913 10.283c0 5.5 4.5 10 10 10s10-4.5 10-10-4.5-10-10-10-10 4.5-10 10Z",fill:"#fff"}),(0,n.createElement)("path",{d:"M10.113 16.383h-.7l.8-4.5h-2.3c-.2 0-.4-.2-.4-.4 0-.1.1-.2.1-.2l4.2-7h.8l-.8 4.6h2.3c.2 0 .4.2.4.4 0 .1 0 .2-.1.2l-4.3 6.9Zm.8-16.1c-5.5 0-10 4.5-10 10s4.5 10 10 10 10-4.5 10-10-4.5-10-10-10Z",fill:"#37414B"}),(0,n.createElement)("circle",{cx:"10.913",cy:"10.283",r:"9",stroke:"#BB522E",strokeWidth:"2"}),(0,n.createElement)("path",{stroke:"#BB522E",strokeWidth:"2",d:"M16.518 17.346 3.791 4.618"}),(0,n.createElement)("path",{stroke:"#fff",strokeWidth:"2",d:"M19.805 18.118 3.282 1.249"}))};function o({hasBadge:e}){return(0,n.createElement)("span",{className:"amp-toolbar-icon components-menu-items__item-icon"+(e?" amp-toolbar-icon--has-badge":"")},(0,n.createElement)(a,null))}function l({hasBadge:e}){return(0,n.createElement)("span",{className:"amp-toolbar-broken-icon"+(e?" amp-toolbar-broken-icon--has-badge":"")},(0,n.createElement)(s,null))}function c({broken:e=!1,count:t}){return(0,n.createElement)("div",{className:"amp-plugin-icon "+(e?"amp-plugin-icon--broken":"")},e?(0,n.createElement)(l,{hasBadge:Boolean(t)}):(0,n.createElement)(o,{hasBadge:Boolean(t)}),0<t&&(0,n.createElement)("div",{className:"amp-error-count-badge"},t))}function u(){return(0,n.createElement)(o,{hasBadge:!1})}function d({broken:e=!1}){return(0,n.createElement)("div",{className:"amp-status-icon "+(e?"amp-status-icon--broken":"")},(0,n.createElement)(i,null))}s.defaultProps={width:"21",height:"21",fill:"none",xmlns:"http://www.w3.org/2000/svg"}},361:(e,t,r)=>{"use strict";r.d(t,{H:()=>l,Z:()=>c});var n=r(196),i=r(184),a=r.n(i),s=r(609);function o({inline:e=!1}){const t=e?"span":"div";return(0,n.createElement)(t,{className:a()("amp-spinner-container",{"amp-spinner-container--inline":e})},(0,n.createElement)(s.Spinner,null))}function l({action:e,icon:t,isLoading:r=!1,isSmall:i=!1,message:s}){const l=r?(0,n.createElement)(o,null):t;return(0,n.createElement)("div",{className:a()("sidebar-notification",{"is-loading":r,"is-small":i})},l&&(0,n.createElement)("div",{className:"sidebar-notification__icon"},l),(0,n.createElement)("div",{className:"sidebar-notification__content"},(0,n.createElement)("p",null,s),e&&(0,n.createElement)("div",{className:"sidebar-notification__action"},e)))}function c({children:e,isShady:t}){return(0,n.createElement)("div",{className:a()("sidebar-notifications-container",{"is-shady":t})},e)}},504:(e,t,r)=>{"use strict";r.d(t,{c:()=>i});var n=r(818);function i(){const e=(0,n.useSelect)((e=>e("core/editor").getEditedPostAttribute("amp_enabled")||!1),[]),{editPost:t}=(0,n.useDispatch)("core/editor");return{isAMPEnabled:e,toggleAMP:()=>t({amp_enabled:!e})}}},590:(e,t,r)=>{"use strict";r.d(t,{P:()=>l});var n=r(307),i=r(818),a=r(333),s=r(736),o=r(883);function l(){const[e,t]=(0,n.useState)(!1),[r,l]=(0,n.useState)(""),{isEditedPostNew:c,isFetchingErrors:u}=(0,i.useSelect)((e=>({isEditedPostNew:e("core/editor").isEditedPostNew(),isFetchingErrors:e(o.h).getIsFetchingErrors()})),[]),d=(0,a.usePrevious)(c),m=(0,a.usePrevious)(u);return(0,n.useEffect)((()=>{e||!u&&m&&t(!0)}),[e,u,m]),(0,n.useEffect)((()=>{l(e?(0,s.__)("Re-validating content.","amp"):c||d?(0,s.__)("Validating content.","amp"):u?(0,s.__)("Loading…","amp"):"")}),[e,c,u,d]),{isFetchingErrors:u,fetchingErrorsMessage:r}}},93:(e,t,r)=>{"use strict";r.r(t),r.d(t,{PLUGIN_ICON:()=>W,PLUGIN_NAME:()=>G,PLUGIN_TITLE:()=>$,SIDEBAR_NAME:()=>J,default:()=>K});var n=r(196),i=r(736),a=r(67),s=r(818),o=r(883),l=r(201),c=r(609),u=r(307),d=r(361),m=r(590),E=function(e){return(0,n.createElement)("svg",{...e},(0,n.createElement)("path",{fill:"#707070",d:"M8 20c1.1 0 2-.9 2-2H6c0 1.1.9 2 2 2zm6-6V9c0-3.07-1.63-5.64-4.5-6.32V2C9.5 1.17 8.83.5 8 .5S6.5 1.17 6.5 2v.68C3.64 3.36 2 5.92 2 9v5l-2 2v1h16v-1l-2-2zm-2 1H4V9c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z"}))};function p(){const{autosave:e,savePost:t}=(0,s.useDispatch)("core/editor"),{isFetchingErrors:r,fetchingErrorsMessage:a}=(0,m.P)(),{isDraft:l,isPostDirty:u,maybeIsPostDirty:p}=(0,s.useSelect)((e=>({isDraft:-1!==["draft","auto-draft"].indexOf(e("core/editor").getEditedPostAttribute("status")),isPostDirty:e(o.h).getIsPostDirty(),maybeIsPostDirty:e(o.h).getMaybeIsPostDirty()})),[]);return r?(0,n.createElement)(d.H,{message:a,isLoading:!0}):u||p?(0,n.createElement)(d.H,{icon:(0,n.createElement)(E,null),message:p?(0,i.__)("Content may have changed.","amp"):(0,i.__)("Content has changed.","amp"),action:l?(0,n.createElement)(c.Button,{isLink:!0,onClick:()=>t({isPreview:!0})},(0,i.__)("Save draft and validate","amp")):(0,n.createElement)(c.Button,{isLink:!0,onClick:()=>e({isPreview:!0})},(0,i.__)("Re-validate","amp"))}):null}E.defaultProps={fill:"none",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 16 20"};var h=function(e){return(0,n.createElement)("svg",{...e},(0,n.createElement)("g",{clipPath:"url(#clip-amp-validation-errors-kept)",fill:"#BB522E"},(0,n.createElement)("path",{d:"M10.762 2.541c4.4 0 8 3.6 8 8 0 1.6-.5 3-1.2 4.2l1.4 1.5c1.1-1.6 1.8-3.6 1.8-5.7 0-5.5-4.5-10-10-10-2 0-3.9.6-5.5 1.7l1.4 1.5c1.2-.8 2.6-1.2 4.1-1.2ZM.762 10.541c0 5.5 4.5 10 10 10 2.7 0 5.1-1.1 6.9-2.8l-14-14.2c-1.8 1.8-2.9 4.3-2.9 7Zm10 8c-4.4 0-8-3.6-8-8 0-1.5.4-2.8 1.1-4l10.9 10.9c-1.2.7-2.5 1.1-4 1.1Z"}),(0,n.createElement)("path",{d:"M14.262 9.74c.1 0 .1-.1.1-.2 0-.2-.2-.4-.4-.4h-2l1.6 1.6.7-1ZM12.461 4.541h-.8l-1.6 2.6 1.7 1.7.7-4.3ZM7.462 11.541s-.1.1-.1.2c0 .2.2.4.4.4h2.3l-.8 4.5h.7l2.6-4.1-3.5-3.6-1.6 2.6Z"})),(0,n.createElement)("defs",null,(0,n.createElement)("clipPath",{id:"clip-amp-validation-errors-kept"},(0,n.createElement)("path",{fill:"#fff",transform:"translate(.762 .541)",d:"M0 0h20v20H0z"}))))};function g(){const{autosave:e,savePost:t}=(0,s.useDispatch)("core/editor"),{isFetchingErrors:r}=(0,m.P)(),{fetchingErrorsRequestErrorMessage:a,isDraft:u,isEditedPostNew:E,keptMarkupValidationErrorCount:p,reviewLink:g,supportLink:v,unreviewedValidationErrorCount:_,validationErrorCount:f}=(0,s.useSelect)((e=>({fetchingErrorsRequestErrorMessage:e(o.h).getFetchingErrorsRequestErrorMessage(),isDraft:-1!==["draft","auto-draft"].indexOf(e("core/editor").getEditedPostAttribute("status")),isEditedPostNew:e("core/editor").isEditedPostNew(),keptMarkupValidationErrorCount:e(o.h).getKeptMarkupValidationErrors().length,reviewLink:e(o.h).getReviewLink(),supportLink:e(o.h).getSupportLink(),unreviewedValidationErrorCount:e(o.h).getUnreviewedValidationErrors().length,validationErrorCount:e(o.h).getValidationErrors().length})),[]);if(r)return null;if(E)return(0,n.createElement)(d.H,{icon:(0,n.createElement)(l.Jj,null),message:(0,i.__)("Validation will be checked upon saving.","amp")});const w=g&&(0,n.createElement)(n.Fragment,null,(0,n.createElement)(c.ExternalLink,{href:g},(0,i.__)("View technical details","amp")),(0,n.createElement)("br",null),v&&(0,n.createElement)(c.ExternalLink,{href:v},(0,i.__)("Get Support","amp")));return a?(0,n.createElement)(d.H,{icon:(0,n.createElement)(h,null),message:a,action:(0,n.createElement)(c.Button,{isLink:!0,onClick:u?()=>t({isPreview:!0}):()=>e({isPreview:!0})},(0,i.__)("Try again","amp"))}):p>0?(0,n.createElement)(d.H,{icon:(0,n.createElement)(h,null),message:(0,i.sprintf)(/* translators: %d is count of validation errors whose invalid markup is kept */
(0,i._n)("AMP is disabled due to invalid markup being kept for %d issue.","AMP is disabled due to invalid markup being kept for %d issues.",p,"amp"),p),action:w}):_>0?(0,n.createElement)(d.H,{icon:(0,n.createElement)(l.Jj,{broken:!0}),message:(0,i.sprintf)(/* translators: %d is count of unreviewed validation error */
(0,i._n)("AMP is valid, but %d issue needs review.","AMP is valid, but %d issues need review.",_,"amp"),_),action:w}):f>0?(0,n.createElement)(d.H,{icon:(0,n.createElement)(l.Jj,null),message:(0,i.sprintf)(/* translators: %d is count of unreviewed validation error */
(0,i._n)("AMP is valid. %d issue was reviewed.","AMP is valid. %d issues were reviewed.",f,"amp"),f),action:w}):(0,n.createElement)(d.H,{icon:(0,n.createElement)(l.Jj,null),message:(0,i.__)("No AMP validation issues detected.","amp")})}function v(){return(0,n.createElement)(d.Z,{isShady:!0},(0,n.createElement)(p,null),(0,n.createElement)(g,null))}h.defaultProps={width:"21",height:"21",fill:"none",xmlns:"http://www.w3.org/2000/svg"};var _=r(184),f=r.n(_),w=r(422),P=function(e){return(0,n.createElement)("svg",{...e},(0,n.createElement)("path",{d:"m2.45 3.068 3.34 1.178v1.64L.743 3.749V2.365L5.79.227v1.64L2.45 3.068Zm8.19-.017L7.237 1.86V.232l5.104 2.14v1.376L7.236 5.893V4.258l3.405-1.207Z",fill:"#fff"}))};P.defaultProps={width:"13",height:"6",fill:"none",xmlns:"http://www.w3.org/2000/svg"};var k=function(e){return(0,n.createElement)("svg",{...e},(0,n.createElement)("path",{d:"M3.675.959h1.5v4.9c0 .5-.1.9-.3 1.2-.2.3-.5.6-.8.8-.4.2-.8.3-1.2.3-.8 0-1.3-.2-1.8-.6-.4-.4-.6-.9-.6-1.6h1.5c0 .3.1.6.2.8.1.2.4.2.7.2.3 0 .5-.1.7-.3.2-.2.2-.5.2-.8v-4.9h-.1ZM10.075 6.26c0-.3-.1-.5-.3-.6-.2-.1-.5-.3-1.1-.5-.5-.2-.9-.3-1.2-.5-.8-.4-1.2-1-1.2-1.8 0-.4.1-.7.3-1 .2-.3.5-.5.9-.7.5-.2 1-.3 1.5-.3s1 .1 1.4.3c.4.2.7.4.9.8.2.3.3.7.3 1.1h-1.5c0-.3-.1-.6-.3-.8-.2-.2-.5-.3-.9-.3s-.6.1-.8.2c-.2.2-.3.3-.3.6 0 .2.1.4.3.6.2.2.6.3 1 .4.8.3 1.4.6 1.8.9.4.3.6.8.6 1.4 0 .6-.2 1.1-.7 1.4-.5.4-1.1.5-1.9.5-.5 0-1-.1-1.5-.3-.4-.2-.8-.5-1-.8-.2-.3-.4-.8-.4-1.2h1.5c0 .8.5 1.2 1.4 1.2.3 0 .6-.1.8-.2.3 0 .4-.2.4-.4Z",fill:"#fff"}))};k.defaultProps={width:"12",height:"9",fill:"none",xmlns:"http://www.w3.org/2000/svg"};var b=function(e){return(0,n.createElement)("svg",{...e},(0,n.createElement)("path",{d:"M4.13 6.46h-1.2l-.4 2.4h-1.1l.4-2.4H.53v-1h1.5l.3-1.7h-1.3v-1h1.5l.4-2.4h1.1l-.4 2.4h1.1l.4-2.4h1.1l-.4 2.4h1.3v1h-1.5l-.3 1.7h1.3v1h-1.5l-.4 2.4h-1.1l.5-2.4Zm-1-1h1.1l.3-1.7h-1.1l-.3 1.7Z",fill:"#fff"}))};function S({type:e}){switch(e){case w.HTML_ATTRIBUTE_ERROR_TYPE:case w.HTML_ELEMENT_ERROR_TYPE:return(0,n.createElement)(P,null);case w.JS_ERROR_TYPE:return(0,n.createElement)(k,null);case w.CSS_ERROR_TYPE:return(0,n.createElement)(b,null);default:return null}}function R({kept:e,title:t,error:{type:r}}){return(0,n.createElement)("div",{className:"amp-error__panel-title",title:e?(0,i.__)("This error has been kept, making this URL not AMP-compatible.","amp"):""},(0,n.createElement)("div",{className:"amp-error__icons"},r&&(0,n.createElement)("div",{className:`amp-error__error-type-icon amp-error__error-type-icon--${r?.replace(/_/g,"-")}`},(0,n.createElement)(S,{type:r}))),(0,n.createElement)("div",{className:"amp-error__title",dangerouslySetInnerHTML:{__html:t}}))}b.defaultProps={width:"8",height:"9",fill:"none",xmlns:"http://www.w3.org/2000/svg"};var A=r(175),y=function(e){return(0,n.createElement)("svg",{...e},(0,n.createElement)("path",{d:"m10.075 4.055 6.275 10.842H3.8l6.275-10.842Zm0-3.325L.908 16.564h18.333L10.075.73Zm.833 11.667H9.242v1.667h1.666v-1.667Zm0-5H9.242v3.333h1.666V7.397Z",fill:"#BE2C23"}))};y.defaultProps={width:"20",height:"17",fill:"none",xmlns:"http://www.w3.org/2000/svg"};var I=function(e){return(0,n.createElement)("svg",{...e},(0,n.createElement)("path",{d:"M12.258 9.043 10.49 10.81 8.716 9.043l-1.175 1.175 1.775 1.767-1.767 1.767 1.175 1.175 1.767-1.767 1.767 1.767 1.175-1.175-1.767-1.767 1.767-1.767-1.175-1.175Zm1.15-5.391-.834-.834H8.408l-.834.834H4.658v1.666h11.666V3.652h-2.916Zm-7.917 12.5c0 .916.75 1.666 1.667 1.666h6.666c.917 0 1.667-.75 1.667-1.666v-10h-10v10Zm1.667-8.334h6.666v8.334H7.158V7.818Z",fill:"#479696"}))};function T({clientId:e,blockTypeName:t,sources:r}){let a;const s=w.blockSources?.[t];if(e&&"core/shortcode"!==t)switch(s?.type){case"plugin":a=(0,i.sprintf)(/* translators: %s: plugin name. */
(0,i.__)("%s (plugin)","amp"),s.title);break;case"mu-plugin":a=(0,i.sprintf)(/* translators: %s: plugin name. */
(0,i.__)("%s (must-use plugin)","amp"),s.title);break;case"theme":a=(0,i.sprintf)(/* translators: %s: theme name. */
(0,i.__)("%s (theme)","amp"),s.title);break;default:a=s?.title}return a||(a=function(e=[]){const t=function(e){const t={theme:[],plugin:[],"mu-plugin":[],embed:[],core:[],blocks:[]};if(!e?.length)return t;for(const r of e)r.type&&r.type in t?t[r.type].push(r):"block_name"in r&&t.blocks.push(r);return t}(e),r=[],n=new Set(t.plugin.map((({name:e})=>e))),a=new Set(t["mu-plugin"].map((({name:e})=>e)));let s=[...n,...a];if(s.length>1&&(s=s.filter((e=>"gutenberg"!==e))),1===s.length)r.push(w.pluginNames[s[0]]||s[0]);else{const e=n.size,t=a.size;0<e&&r.push((0,i.sprintf)("%1$s (%2$d)",(0,i.__)("Plugins","amp"),e)),0<t&&r.push((0,i.sprintf)("%1$s (%2$d)",(0,i.__)("Must-use plugins","amp"),t))}if(0===t.embed.length){const e=t.theme.filter((({name:e})=>w.themeSlug===e)),n=t.theme.filter((({name:e})=>w.themeSlug!==e));0<e.length&&r.push(w.themeName),0<n.length&&
/* translators: placeholder is the slug of an inactive WordPress theme. */
r.push((0,i.__)("Inactive theme(s)","amp"))}return 0===r.length&&0<t.blocks.length&&r.push(t.blocks[0].block_name),0===r.length&&0<t.embed.length&&r.push((0,i.__)("Embed","amp")),0===r.length&&0<t.core.length&&r.push((0,i.__)("Core","amp")),!r.length&&e?.length&&r.push((0,i.__)("Unknown","amp")),r.join(", ")}(r)),(0,n.createElement)(n.Fragment,null,(0,n.createElement)("dt",null,(0,i.__)("Source","amp")),(0,n.createElement)("dd",null,a))}function M({status:e}){let t,r;return t=[w.VALIDATION_ERROR_NEW_ACCEPTED_STATUS,w.VALIDATION_ERROR_ACK_ACCEPTED_STATUS].includes(e)?(0,n.createElement)("span",{className:"amp-error__kept-removed amp-error__kept-removed--removed"},(0,i.__)("Removed","amp"),(0,n.createElement)("span",null,(0,n.createElement)(I,null))):(0,n.createElement)("span",{className:"amp-error__kept-removed amp-error__kept-removed--kept"},(0,i.__)("Kept","amp"),(0,n.createElement)("span",null,(0,n.createElement)(y,null))),r=[w.VALIDATION_ERROR_ACK_ACCEPTED_STATUS,w.VALIDATION_ERROR_ACK_REJECTED_STATUS].includes(e)?(0,i.__)("Yes","amp"):(0,i.__)("No","amp"),(0,n.createElement)(n.Fragment,null,(0,n.createElement)("dt",null,(0,i.__)("Markup status","amp")),(0,n.createElement)("dd",null,t),(0,n.createElement)("dt",null,(0,i.__)("Reviewed","amp")),(0,n.createElement)("dd",null,r))}function N({blockTypeIcon:e,blockTypeTitle:t}){return(0,n.createElement)(n.Fragment,null,(0,n.createElement)("dt",null,(0,i.__)("Block type","amp")),(0,n.createElement)("dd",null,(0,n.createElement)("span",{className:"amp-error__block-type-description"},t||(0,i.__)("unknown","amp"),e&&(0,n.createElement)("span",{className:"amp-error__block-type-icon"},(0,n.createElement)(A.BlockIcon,{icon:e})))))}function C({blockType:e,clientId:t,error:{sources:r},isExternal:a,removed:s,status:o}){const l=e?.title,c=e?.name,u=e?.icon;return(0,n.createElement)(n.Fragment,null,s&&(0,n.createElement)("p",null,(0,i.__)("This error is no longer detected, either because the block was removed or the editor mode was switched.","amp")),a&&(0,n.createElement)("p",null,(0,i.__)("This error comes from outside the content (e.g. header or footer).","amp")),(0,n.createElement)("dl",{className:"amp-error__details"},!(s||a)&&(0,n.createElement)(N,{blockTypeIcon:u,blockTypeTitle:l}),(0,n.createElement)(T,{blockTypeName:c,clientId:t,sources:r}),(0,n.createElement)(M,{status:o})))}function D({clientId:e,error:t,status:r,term_id:a,title:l}){const{selectBlock:u}=(0,s.useDispatch)("core/block-editor"),d=(0,s.useSelect)((e=>e(o.h).getReviewLink()),[]),m=r===w.VALIDATION_ERROR_ACK_ACCEPTED_STATUS||r===w.VALIDATION_ERROR_ACK_REJECTED_STATUS,E=r===w.VALIDATION_ERROR_ACK_REJECTED_STATUS||r===w.VALIDATION_ERROR_NEW_REJECTED_STATUS,p=!Boolean(e),{blockType:h,removed:g}=(0,s.useSelect)((t=>{const r=t("core/block-editor").getBlockName(e);return{removed:e&&!r,blockType:t("core/blocks").getBlockType(r)}}),[e]);let v=null;d&&(v=new URL(d),v.hash=`#tag-${a}`);const _=f()("amp-error",{"amp-error--reviewed":m,"amp-error--new":!m,"amp-error--removed":g,"amp-error--kept":E,[`error-${e}`]:e});return(0,n.createElement)(c.PanelBody,{className:_,title:(0,n.createElement)(R,{error:t,kept:E,title:l}),initialOpen:!1},(0,n.createElement)(C,{blockType:h,clientId:e,error:t,isExternal:p,removed:g,status:r}),(0,n.createElement)("div",{className:"amp-error__actions"},!(g||p)&&(0,n.createElement)(c.Button,{className:"amp-error__select-block",isSecondary:!0,onClick:()=>{u(e)}},(0,i.__)("Select block","amp")),v&&(0,n.createElement)(c.ExternalLink,{href:v.href,className:"amp-error__details-link"},(0,i.__)("View details","amp"))))}function V(){const{setIsShowingReviewed:e}=(0,s.useDispatch)(o.h),{displayedErrors:t,hasReviewedValidationErrors:r,isShowingReviewed:a}=(0,s.useSelect)((e=>{const t=e(o.h).getIsShowingReviewed();return{displayedErrors:t?e(o.h).getValidationErrors():e(o.h).getUnreviewedValidationErrors(),hasReviewedValidationErrors:e(o.h).getReviewedValidationErrors()?.length>0,isShowingReviewed:t}}),[]);return(0,u.useEffect)((()=>{const e=document.querySelector(".amp-sidebar a, .amp-sidebar button, .amp-sidebar input");e&&e.focus()}),[]),(0,n.createElement)("div",{className:"amp-sidebar"},(0,n.createElement)(v,null),0<t.length&&(0,n.createElement)("ul",{className:"amp-sidebar__errors-list"},t.map(((e,t)=>(0,n.createElement)("li",{key:`${e.clientId}${t}`,className:"amp-sidebar__errors-list-item"},(0,n.createElement)(D,{...e}))))),r&&(0,n.createElement)("div",{className:"amp-sidebar__options"},(0,n.createElement)(c.Button,{isLink:!0,onClick:()=>e(!a)},a?(0,i.__)("Hide reviewed issues","amp"):(0,i.__)("Show reviewed issues","amp"))))}function L(){const e=(0,s.useSelect)((e=>e(o.h).getUnreviewedValidationErrors()),[]),t=(0,u.useMemo)((()=>e.map((({clientId:e})=>e)).filter((e=>e)).map((e=>`#block-${e}::before`))),[e]);return(0,n.createElement)("style",null,`${t.join(",")} {\n\t\t\t\t\tborder-radius: 9px;\n\t\t\t\t\tbottom: -3px;\n\t\t\t\t\tbox-shadow: 0 0 0 2px #bb522e;\n\t\t\t\t\tcontent: '';\n\t\t\t\t\tleft: -3px;\n\t\t\t\t\tpointer-events: none;\n\t\t\t\t\tposition: absolute;\n\t\t\t\t\tright: -3px;\n\t\t\t\t\ttop: -3px;\n\t\t\t\t}`)}I.defaultProps={width:"21",height:"21",fill:"none",xmlns:"http://www.w3.org/2000/svg"};var O=r(333);const B=500,x=window.lodash,U=window.wp.apiFetch;var F=r.n(U);const H=window.wp.url;function Z({validationError:e,source:t,currentPostId:r,blockOrder:n,getBlock:i}){if(!t.block_name||void 0===t.block_content_index)return;if(r!==t.post_id)return;const a=n[t.block_content_index];if(!a)return;const s=i(a);s&&s.name===t.block_name&&(e.clientId=a)}var j=r(504);const G="amp-block-validation",J="amp-editor-sidebar",$=(0,i.__)("AMP Validation","amp"),W=l.mE;function K(){const{broken:e,errorCount:t}=(0,s.useSelect)((e=>({broken:e(o.h).getAMPCompatibilityBroken(),errorCount:e(o.h).getUnreviewedValidationErrors()?.length||0})),[]),{isAMPEnabled:r}=(0,j.c)();return function(){const[e,t]=(0,u.useState)([]),[r,n]=(0,u.useState)(!1),[a,l]=(0,u.useState)([]),[c,d]=(0,u.useState)(!1),{setIsFetchingErrors:m,setFetchingErrorsRequestErrorMessage:E,setReviewLink:p,setSupportLink:h,setValidationErrors:g}=(0,s.useDispatch)(o.h),{currentPostId:v,getBlock:_,getClientIdsWithDescendants:f,isAutosavingPost:w,isEditedPostNew:P,isPreviewingPost:k,isSavingPost:b,previewLink:S,validationErrors:R}=(0,s.useSelect)((e=>({currentPostId:e("core/editor").getCurrentPostId(),getBlock:e("core/block-editor").getBlock,getClientIdsWithDescendants:e("core/block-editor").getClientIdsWithDescendants,isAutosavingPost:e("core/editor").isAutosavingPost(),isEditedPostNew:e("core/editor").isEditedPostNew(),isPreviewingPost:e("core/editor").isPreviewingPost(),isSavingPost:e("core/editor").isSavingPost(),previewLink:e("core/editor").getEditedPostPreviewLink(),validationErrors:e(o.h).getValidationErrors()})),[]),A=(0,O.usePrevious)(P);(0,u.useEffect)((()=>{P||A||d(!0)}),[P,A]),(0,u.useEffect)((()=>{if(b)return k?(d(!0),void n(!0)):void(w||d(!0))}),[w,k,b]),(0,u.useEffect)((()=>{if(!c)return;if(b)return void m(!0);if(r&&!(0,H.isURL)(S))return;const e={id:v};r&&(e.preview_nonce=(0,H.getQueryArg)(S,"preview_nonce")),m(!0),d(!1),n(!1),E(""),t(f()),F()({path:"/amp/v1/validate-post-url/",method:"POST",data:e}).then((e=>{g(e.results),p(e.review_link),h(e.support_link)})).catch((e=>{E(e?.message||(0,i.__)("Whoops! Something went wrong.","amp"))})).finally((()=>{m(!1)}))}),[v,f,r,b,S,E,m,p,h,g,c]),(0,u.useEffect)((()=>{R&&!(0,x.isEqual)(a,R)&&l(R)}),[a,R]),(0,u.useEffect)((()=>{const t=a.map((t=>{if(!t.error.sources?.length)return t;for(const r of t.error.sources){if("clientId"in t)break;Z({validationError:t,source:r,getBlock:_,blockOrder:0<e.length?e:f(),currentPostId:v})}return t}));g(t)}),[e,v,_,f,g,a])}(),function(){const[e,t]=(0,u.useState)(null),[r,n]=(0,u.useState)(),i=(0,u.useRef)(null),{setIsPostDirty:a,setMaybeIsPostDirty:l}=(0,s.useDispatch)(o.h),{getEditedPostContent:c,hasErrorsFromRemovedBlocks:d,hasActiveMetaboxes:m,isPostDirty:E,isSavingOrPreviewingPost:p}=(0,s.useSelect)((e=>({getEditedPostContent:e("core/editor").getEditedPostContent,hasErrorsFromRemovedBlocks:Boolean(e(o.h).getValidationErrors().find((({clientId:t})=>t&&!e("core/block-editor").getBlockName(t)))),hasActiveMetaboxes:e("core/edit-post").hasMetaBoxes(),isPostDirty:e(o.h).getIsPostDirty(),isSavingOrPreviewingPost:e("core/editor").isSavingPost()&&!e("core/editor").isAutosavingPost()||e("core/editor").isPreviewingPost()})),[]);(0,u.useEffect)((()=>()=>{i.current&&i.current()}),[]),(0,u.useEffect)((()=>{E&&p&&(a(!1),t(null))}),[E,p,a]),(0,u.useEffect)((()=>{if(null===e){const e=c();return t(e),void n(e)}r!==e&&a(!0)}),[e,c,a,r]),(0,u.useEffect)((()=>{l(!E&&(m||d))}),[m,d,E,l]);const h=(0,u.useCallback)((()=>{n(c())}),[c]),g=(0,O.useDebounce)(h,B);(0,u.useEffect)((()=>{E&&i.current?(i.current(),i.current=null):p||E||i.current||(i.current=(0,s.subscribe)(g))}),[g,E,p])}(),r?(0,n.createElement)(n.Fragment,null,(0,n.createElement)(a.PluginSidebarMoreMenuItem,{icon:(0,n.createElement)(W,null),target:J},$),(0,n.createElement)(a.PluginSidebar,{className:`${G}-sidebar`,icon:(0,n.createElement)(l._4,{count:t,broken:e}),name:J,title:$},(0,n.createElement)(V,null),(0,n.createElement)(L,null))):null}},877:(e,t,r)=>{"use strict";r.r(t),r.d(t,{PLUGIN_ICON:()=>l,PLUGIN_NAME:()=>o,default:()=>c});var n=r(196),i=r(736),a=r(67),s=r(44);const o="amp-block-validation-document-setting-panel",l="";function c(){return(0,n.createElement)(a.PluginDocumentSettingPanel,{name:o,title:(0,i.__)("AMP","amp"),initialOpen:!0},(0,n.createElement)(s.Z,null))}},327:(e,t,r)=>{"use strict";r.r(t),r.d(t,{PLUGIN_ICON:()=>l,PLUGIN_NAME:()=>o,default:()=>c});var n=r(196),i=r(736),a=r(67),s=r(44);const o="amp-block-validation-pre-publish-panel",l="";function c(){return(0,n.createElement)(a.PluginPrePublishPanel,{title:(0,i.__)("AMP","amp"),initialOpen:!0},(0,n.createElement)(s.Z,null))}},883:(e,t,r)=>{"use strict";r.d(t,{h:()=>E});var n=r(422),i=r(818);const a="SET_FETCHING_ERRORS_REQUEST_ERROR_MESSAGE",s="SET_IS_FETCHING_ERRORS",o="SET_IS_POST_DIRTY",l="SET_IS_SHOWING_REVIEWED",c="SET_MAYBE_IS_POST_DIRTY",u="SET_REVIEW_LINK",d="SET_SUPPORT_LINK",m="SET_VALIDATION_ERRORS",E=(0,i.createReduxStore)("amp/block-validation",{reducer:(e=p,t)=>{switch(t.type){case a:return{...e,fetchingErrorsRequestErrorMessage:t.fetchingErrorsRequestErrorMessage};case s:return{...e,isFetchingErrors:t.isFetchingErrors};case o:return{...e,isPostDirty:t.isPostDirty};case l:return{...e,isShowingReviewed:t.isShowingReviewed};case c:return{...e,maybeIsPostDirty:t.maybeIsPostDirty};case u:return{...e,reviewLink:t.reviewLink};case d:return{...e,supportLink:t.supportLink};case m:return{...e,ampCompatibilityBroken:Boolean(t.validationErrors.filter((({status:e})=>e===n.VALIDATION_ERROR_NEW_REJECTED_STATUS||e===n.VALIDATION_ERROR_ACK_REJECTED_STATUS))?.length),reviewedValidationErrors:t.validationErrors.filter((({status:e})=>e===n.VALIDATION_ERROR_ACK_ACCEPTED_STATUS||e===n.VALIDATION_ERROR_ACK_REJECTED_STATUS)),unreviewedValidationErrors:t.validationErrors.filter((({status:e})=>e===n.VALIDATION_ERROR_NEW_ACCEPTED_STATUS||e===n.VALIDATION_ERROR_NEW_REJECTED_STATUS)),keptMarkupValidationErrors:t.validationErrors.filter((({status:e})=>e===n.VALIDATION_ERROR_NEW_REJECTED_STATUS||e===n.VALIDATION_ERROR_ACK_REJECTED_STATUS)),validationErrors:t.validationErrors};default:return e}},actions:{setFetchingErrorsRequestErrorMessage:e=>({type:a,fetchingErrorsRequestErrorMessage:e}),setIsFetchingErrors:e=>({type:s,isFetchingErrors:e}),setIsPostDirty:e=>({type:o,isPostDirty:e}),setIsShowingReviewed:e=>({type:l,isShowingReviewed:e}),setMaybeIsPostDirty:e=>({type:c,maybeIsPostDirty:e}),setReviewLink:e=>({type:u,reviewLink:e}),setSupportLink:e=>({type:d,supportLink:e}),setValidationErrors:e=>({type:m,validationErrors:e})},selectors:{getAMPCompatibilityBroken:({ampCompatibilityBroken:e})=>e,getFetchingErrorsRequestErrorMessage:({fetchingErrorsRequestErrorMessage:e})=>e,getIsFetchingErrors:({isFetchingErrors:e})=>e,getIsPostDirty:({isPostDirty:e})=>e,getIsShowingReviewed:({isShowingReviewed:e})=>e,getMaybeIsPostDirty:({maybeIsPostDirty:e})=>e,getReviewLink:({reviewLink:e})=>e,getSupportLink:({supportLink:e})=>e,getReviewedValidationErrors:({reviewedValidationErrors:e})=>e,getUnreviewedValidationErrors:({unreviewedValidationErrors:e})=>e,getKeptMarkupValidationErrors:({keptMarkupValidationErrors:e})=>e,getValidationErrors:({validationErrors:e})=>e},initialState:p={ampCompatibilityBroken:!1,fetchingErrorsRequestErrorMessage:"",isPostDirty:!1,isFetchingErrors:!1,isShowingReviewed:!1,keptMarkupValidationErrors:[],maybeIsPostDirty:!1,rawValidationErrors:[],reviewLink:null,supportLink:null,reviewedValidationErrors:[],unreviewedValidationErrors:[],validationErrors:[]}});var p;(0,i.register)(E)},184:(e,t)=>{var r;!function(){"use strict";var n={}.hasOwnProperty;function i(){for(var e=[],t=0;t<arguments.length;t++){var r=arguments[t];if(r){var a=typeof r;if("string"===a||"number"===a)e.push(r);else if(Array.isArray(r)){if(r.length){var s=i.apply(null,r);s&&e.push(s)}}else if("object"===a){if(r.toString!==Object.prototype.toString&&!r.toString.toString().includes("[native code]")){e.push(r.toString());continue}for(var o in r)n.call(r,o)&&r[o]&&e.push(o)}}}return e.join(" ")}e.exports?(i.default=i,e.exports=i):void 0===(r=function(){return i}.apply(t,[]))||(e.exports=r)}()},422:e=>{"use strict";e.exports=ampBlockValidation},196:e=>{"use strict";e.exports=window.React},175:e=>{"use strict";e.exports=window.wp.blockEditor},609:e=>{"use strict";e.exports=window.wp.components},333:e=>{"use strict";e.exports=window.wp.compose},818:e=>{"use strict";e.exports=window.wp.data},67:e=>{"use strict";e.exports=window.wp.editPost},307:e=>{"use strict";e.exports=window.wp.element},736:e=>{"use strict";e.exports=window.wp.i18n}},t={};function r(n){var i=t[n];if(void 0!==i)return i.exports;var a=t[n]={exports:{}};return e[n](a,a.exports,r),a.exports}r.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return r.d(t,{a:t}),t},r.d=(e,t)=>{for(var n in t)r.o(t,n)&&!r.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},r.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),r.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},(()=>{"use strict";const e=window.wp.hooks,t=window.wp.plugins;var n=r(196),i=r(818),a=r(333),s=r(883),o=r(175),l=r(609),c=r(201),u=r(93),d=r(504);function m({clientId:e,count:t}){const{openGeneralSidebar:r}=(0,i.useDispatch)("core/edit-post"),{isAMPEnabled:a}=(0,d.c)();return a?(0,n.createElement)(o.BlockControls,null,(0,n.createElement)(l.ToolbarButton,{onClick:()=>{r(`${u.PLUGIN_NAME}/${u.SIDEBAR_NAME}`),setTimeout((()=>{const t=Array.from(document.querySelectorAll(`.error-${e} button`)),r=t[0];t.reverse();for(const e of t)"false"===e.getAttribute("aria-expanded")&&e.click();r&&r.scrollIntoView({block:"start",inline:"nearest",behavior:"smooth"})}))}},(0,n.createElement)(c._4,{count:t}))):null}function E(e){const{BlockEdit:t,clientId:r}=e,a=(0,i.useSelect)((e=>(e(s.h).getUnreviewedValidationErrors()||[]).filter((({clientId:e})=>r===e)).length||0),[r]);return(0,n.createElement)(n.Fragment,null,0<a&&(0,n.createElement)(m,{clientId:r,count:a}),(0,n.createElement)(t,{...e}))}const p=(0,a.createHigherOrderComponent)((e=>t=>(0,n.createElement)(E,{...t,BlockEdit:e})),"BlockEditWithAMPToolbar"),h=r(11);h.keys().forEach((e=>{const{default:r,PLUGIN_NAME:n,PLUGIN_ICON:i}=h(e);(0,t.registerPlugin)(n,{icon:i,render:r})})),(0,e.addFilter)("editor.BlockEdit","ampBlockValidation/filterEdit",p,-99)})()})();