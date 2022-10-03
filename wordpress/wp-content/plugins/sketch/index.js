!function(){"use strict";!function(){function e(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}function t(t,n){if(t){if("string"==typeof t)return e(t,n);var r=Object.prototype.toString.call(t).slice(8,-1);return"Object"===r&&t.constructor&&(r=t.constructor.name),"Map"===r||"Set"===r?Array.from(t):"Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)?e(t,n):void 0}}function n(n){return function(t){if(Array.isArray(t))return e(t)}(n)||function(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}(n)||t(n)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function r(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function o(e,n){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){var n=null==e?null:"undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(null!=n){var r,o,i=[],_n=!0,l=!1;try{for(n=n.call(e);!(_n=(r=n.next()).done)&&(i.push(r.value),!t||i.length!==t);_n=!0);}catch(e){l=!0,o=e}finally{try{_n||null==n.return||n.return()}finally{if(l)throw o}}return i}}(e,n)||t(e,n)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}var i=window.wp.element;function l(e,t,n,r=(e=>e)){return e*r(.5-t*(.5-n))}function c(e,t){return[e[0]+t[0],e[1]+t[1]]}function a(e,t){return[e[0]-t[0],e[1]-t[1]]}function s(e,t){return[e[0]*t,e[1]*t]}function u(e){return[e[1],-e[0]]}function p(e,t){return e[0]*t[0]+e[1]*t[1]}function h(e,t){return e[0]===t[0]&&e[1]===t[1]}function f(e,t){return function(e){return e[0]*e[0]+e[1]*e[1]}(a(e,t))}function d(e){return function(e,t){return[e[0]/t,e[1]/t]}(e,function(e){return Math.hypot(e[0],e[1])}(e))}function g(e,t){return Math.hypot(e[1]-t[1],e[0]-t[0])}function m(e,t,n){let r=Math.sin(n),o=Math.cos(n),i=e[0]-t[0],l=e[1]-t[1],c=i*r+l*o;return[i*o-l*r+t[0],c+t[1]]}function v(e,t,n){return c(e,s(a(t,e),n))}function b(e,t,n){return c(e,s(t,n))}var{min:w,PI:y}=Math,k=y+1e-4,E=function(e,t={}){return function(e,t={}){let{size:n=16,smoothing:r=.5,thinning:o=.5,simulatePressure:i=!0,easing:h=(e=>e),start:g={},end:y={},last:E=!1}=t,{cap:C=!0,taper:P=0,easing:O=(e=>e*(2-e))}=g,{cap:S=!0,taper:_=0,easing:x=(e=>--e*e*e+1)}=y;if(0===e.length||n<=0)return[];let j,B=e[e.length-1].runningLength,R=Math.pow(n*r,2),A=[],D=[],M=e.slice(0,10).reduce(((e,t)=>{let r=t.pressure;if(i){let o=w(1,t.distance/n),i=w(1,1-o);r=w(1,e+.275*o*(i-e))}return(e+r)/2}),e[0].pressure),I=l(n,o,e[e.length-1].pressure,h),z=e[0].vector,L=e[0].point,T=L,V=L,U=T;for(let t=0;t<e.length;t++){let{pressure:r}=e[t],{point:d,vector:g,distance:b,runningLength:y}=e[t];if(t<e.length-1&&B-y<3)continue;if(o){if(i){let e=w(1,b/n),t=w(1,1-e);r=w(1,M+.275*e*(t-M))}I=l(n,o,r,h)}else I=n/2;void 0===j&&(j=I);let E=y<P?O(y/P):1,C=B-y<_?x((B-y)/_):1;if(I=Math.max(.01,I*Math.min(E,C)),t===e.length-1){let e=s(u(g),I);A.push(a(d,e)),D.push(c(d,e));continue}let S=e[t+1].vector,H=p(g,S);if(H<0){let e=s(u(z),I);for(let t=1/13,n=0;n<=1;n+=t)V=m(a(d,e),d,k*n),A.push(V),U=m(c(d,e),d,k*-n),D.push(U);L=V,T=U;continue}let N=s(u(v(S,g,H)),I);V=a(d,N),(t<=1||f(L,V)>R)&&(A.push(V),L=V),U=c(d,N),(t<=1||f(T,U)>R)&&(D.push(U),T=U),M=r,z=g}let H=e[0].point.slice(0,2),N=e.length>1?e[e.length-1].point.slice(0,2):c(e[0].point,[1,1]),G=[],F=[];if(1===e.length){if(!P&&!_||E){let e=b(H,d(u(a(H,N))),-(j||I)),t=[];for(let n=1/13,r=n;r<=1;r+=n)t.push(m(e,H,2*k*r));return t}}else{if(!(P||_&&1===e.length))if(C)for(let e=1/13,t=e;t<=1;t+=e){let e=m(D[0],H,k*t);G.push(e)}else{let e=a(A[0],D[0]),t=s(e,.5),n=s(e,.51);G.push(a(H,t),a(H,n),c(H,n),c(H,t))}let t=u(function(e){return[-e[0],-e[1]]}(e[e.length-1].vector));if(_||P&&1===e.length)F.push(N);else if(S){let e=b(N,t,I);for(let t=1/29,n=t;n<1;n+=t)F.push(m(e,N,3*k*n))}else F.push(c(N,s(t,I)),c(N,s(t,.99*I)),a(N,s(t,.99*I)),a(N,s(t,I)))}return A.concat(F,D.reverse(),G)}(function(e,t={}){var n;let{streamline:r=.5,size:o=16,last:i=!1}=t;if(0===e.length)return[];let l=.15+.85*(1-r),s=Array.isArray(e[0])?e:e.map((({x:e,y:t,pressure:n=.5})=>[e,t,n]));if(2===s.length){let e=s[1];s=s.slice(0,-1);for(let t=1;t<5;t++)s.push(v(s[0],e,t/4))}1===s.length&&(s=[...s,[...c(s[0],[1,1]),...s[0].slice(2)]]);let u=[{point:[s[0][0],s[0][1]],pressure:s[0][2]>=0?s[0][2]:.25,vector:[1,1],distance:0,runningLength:0}],p=!1,f=0,m=u[0],b=s.length-1;for(let e=1;e<s.length;e++){let t=i&&e===b?s[e].slice(0,2):v(m.point,s[e],l);if(h(m.point,t))continue;let n=g(t,m.point);if(f+=n,e<b&&!p){if(f<o)continue;p=!0}m={point:t,pressure:s[e][2]>=0?s[e][2]:.5,vector:d(a(m.point,t)),distance:n,runningLength:f},u.push(m)}return u[0].vector=(null==(n=u[1])?void 0:n.vector)||[0,0],u}(e,t),t)},C=[{size:3,thinning:.3,smoothing:.83,streamline:.45},{size:14,thinning:.6,smoothing:.5,streamline:.75},{size:25,thinning:.5,smoothing:.5,streamline:.6}];function P(e){if(0===e.length)return"";var t=e.reduce((function(e,t,n,r){var i=o(t,2),l=i[0],c=i[1],a=o(r[(n+1)%r.length],2),s=a[0],u=a[1];return e.push(l,c,(l+s)/2,(c+u)/2),e}),["M"].concat(n(e[0]),["Q"]));return t.push("Z"),t.join(" ")}var O=window.wp.blockEditor,S=window.wp.data,_=window.wp.components,x=window.wp.i18n,j=window.wp.primitives,B=(0,i.createElement)(j.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,i.createElement)(j.Path,{d:"M20 5h-5.7c0-1.3-1-2.3-2.3-2.3S9.7 3.7 9.7 5H4v2h1.5v.3l1.7 11.1c.1 1 1 1.7 2 1.7h5.7c1 0 1.8-.7 2-1.7l1.7-11.1V7H20V5zm-3.2 2l-1.7 11.1c0 .1-.1.2-.3.2H9.1c-.1 0-.3-.1-.3-.2L7.2 7h9.6z"})),R=(0,i.createElement)(j.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,i.createElement)(j.Path,{d:"M18.5 15v3.5H13V6.7l4.5 4.1 1-1.1-6.2-5.8-5.8 5.8 1 1.1 4-4v11.7h-6V15H4v5h16v-5z"})),A=window.wp.notices,D=function(e){var t=e.color;return(0,i.createElement)(_.SVG,{width:"24",height:"24",viewBox:"0 0 16 16",fill:"none"},(0,i.createElement)(_.Circle,{cx:"8",cy:"8",r:"6",style:{fill:t,filter:"brightness(0.8)"}}),(0,i.createElement)(_.Circle,{cx:"8",cy:"8",r:"5.5",style:{fill:t}}))},M=function(){return(0,i.createElement)(_.SVG,{width:"24",height:"24",viewBox:"0 0 24 24",fill:"none"},(0,i.createElement)(_.Rect,{x:"9",y:"5",width:"6",height:"2",rx:"1"}),(0,i.createElement)(_.Rect,{x:"7",y:"10",width:"10",height:"3",rx:"1.5"}),(0,i.createElement)(_.Rect,{x:"5",y:"16",width:"14",height:"4",rx:"2"}))},I=function(e){var t=e.radius,n=void 0===t?8:t;return(0,i.createElement)(_.SVG,{width:"24",height:"24",viewBox:"0 0 16 16",fill:"none"},(0,i.createElement)(_.Circle,{cx:"8",cy:"8",r:n}))};function z(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function L(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?z(Object(n),!0).forEach((function(t){r(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):z(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var T=[{value:0,icon:(0,i.createElement)(_.Icon,{icon:(0,i.createElement)(I,{radius:"2"}),type:"svg"})},{value:1,icon:(0,i.createElement)(_.Icon,{icon:(0,i.createElement)(I,{radius:"4"}),type:"svg"})},{value:2,icon:(0,i.createElement)(_.Icon,{icon:(0,i.createElement)(I,{radius:"7"}),type:"svg"})}],V=function(e){var t=e.clear,n=e.color,r=e.setColor,o=e.preset,l=e.setPreset,c=e.isEmpty,a=e.title,s=e.setTitle,u=e.blockRef,p=e.attributes,h=(0,O.useSetting)("color.palette")||[],f=(0,S.useDispatch)(A.store),d=f.createErrorNotice,g=f.createInfoNotice;function m(){if(u.current)return u.current.querySelector("svg")}var v=(0,i.useCallback)((function(){!function(e,t){if(e){e.setAttribute("viewBox","0 0 ".concat(e.getBoundingClientRect().width," ").concat(e.getBoundingClientRect().height));var n=(new XMLSerializer).serializeToString(e),r=self.URL||self.webkitURL||self,o=new Blob([n],{type:"image/svg+xml;charset=utf-8"}),i=document.createElement("canvas"),l=i.getContext("2d");l.canvas.width=e.width.baseVal.value,l.canvas.height=e.height.baseVal.value;var c=new Image,a=r.createObjectURL(o);c.onload=function(){l.drawImage(c,0,0),r.revokeObjectURL(i.toDataURL("image/png")),i.toBlob(t)},c.src=a}}(m(),(function(e){!function(e,t,n){var r=t.title,o=void 0===r?(0,x.__)("Image generated via Sketch block","a8c-sketch"):r,i=t.caption,l=void 0===i?"":i,c=t.description,a=void 0===c?"":c,s=(0,(0,S.select)(O.store).getSettings)().mediaUpload;if(!e)return n((0,x.__)("No valid image","a8c-sketch"));var u=new window.FileReader;u.readAsDataURL(e),u.onloadend=function(){s({additionalData:{title:o,caption:l,description:a},allowedTypes:["image"],filesList:[e],onFileChange:function(e){if(null!=e&&e.length){var t=e[0];null!=t&&t.id&&n(null,t)}},onError:n})}}(e,{description:null==p?void 0:p.title},(function(e,t){if(e)return d(e);g(sprintf((0,x.__)("Image created and added to the library","a8c-sketch"),t.id),{id:"uploaded-image-".concat(t.id),type:"snackbar",isDismissible:!1,actions:[{url:"/wp-admin/upload.php?item=".concat(t.id),label:(0,x.__)("View Image","a8c-sketch")}]})}))}))}),[p,d,g,m,m]);return(0,i.createElement)(i.Fragment,null,(0,i.createElement)(O.BlockControls,{group:"block"},(0,i.createElement)(_.ToolbarDropdownMenu,{isCollapsed:!0,popoverProps:{className:"wp-block-a8c-sketch__brush-style-popover",isAlternate:!0},icon:(0,i.createElement)(_.Icon,{icon:M}),label:(0,x.__)("Brush","a8c-sketch"),controls:T.map((function(e){return L(L({},e),{},{isActive:e.value===o,onClick:function(){e.value!==o&&l(e.value)}})}))}),(0,i.createElement)(_.ToolbarDropdownMenu,{isCollapsed:!0,popoverProps:{isAlternate:!0},icon:(0,i.createElement)(_.Icon,{icon:(0,i.createElement)(D,{color:n})}),label:(0,x.__)("Color","a8c-sketch")},(function(){return(0,i.createElement)(_.ColorPalette,{clearable:!1,colors:h,color:n,disableCustomColors:!0,onChange:r})})),(0,i.createElement)(_.ToolbarButton,{icon:B,onClick:t,label:(0,x.__)("Clear canvas","a8c-sketch"),disabled:c})),(0,i.createElement)(O.BlockControls,{group:"other"},(0,i.createElement)(_.ToolbarButton,{icon:R,disabled:c,onClick:v,label:(0,x.__)("Upload","a8c-sketch")})),(0,i.createElement)(O.InspectorControls,null,(0,i.createElement)(_.PanelBody,{title:(0,x.__)("a8c-sketch")},(0,i.createElement)(_.TextareaControl,{label:(0,x.__)("a8c-sketch"),value:a,onChange:s,help:(0,i.createElement)(i.Fragment,null,(0,x.__)("Add a short-text description so it's recognized as the accessible name for the sketch.","a8c-sketch"))}))))};function U(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function H(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?U(Object(n),!0).forEach((function(t){r(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):U(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var N=function(e){var t=e.currentStroke,n=e.strokes,r=e.handlePointerDown,o=e.handlePointerMove,l=e.handlePointerUp,c=e.title;return(0,i.createElement)("svg",{onPointerDown:r,onPointerMove:o,onPointerUp:l,style:{touchAction:"none"},role:"img"},c&&(0,i.createElement)("title",null,c),n.map((function(e,t){return(0,i.createElement)(G,{key:t,stroke:e})})),t&&(0,i.createElement)(G,{stroke:t}))},G=function(e){var t,n=e.stroke,r=null!==(t=n.color)&&void 0!==t?t:"#f00";return(0,i.createElement)("path",{fill:r,d:P(n.stroke)})},F=JSON.parse('{"attributes":{"strokes":[{"stroke":[[27.482510480169168,269.2003520059833],[27.811075383697677,252.62326943956776],[31.080371494015886,227.3132596630533],[37.295496307526236,196.14562127965496],[46.36859655542409,161.2577201240715],[57.77517240525262,126.1183354835261],[70.86213301432315,94.43405266954574],[83.87765643562746,68.68380571970928],[95.00414909425274,50.162807264328386],[103.36069718845093,38.83564535906072],[113.16469089813955,30.33880521910722],[114.80509822090353,29.787735946126837],[116.4561260143661,30.306118771281106],[117.48713777779531,31.69594907454529],[117.50432206036216,33.42635891866704],[118.00121777590826,39.14041246589355],[117.6110745868549,56.17705323201762],[113.9792842595852,77.56791883546319],[109.77568789832425,101.2815054616511],[106.1787175258647,123.93597409112598],[103.8258569249021,143.7488269897935],[102.55888519855968,160.31684931879],[102.08527515251409,172.17672192770883],[101.26189611484746,178.42391856341513],[103.1530945641885,175.5777665599411],[111.3495440108561,163.95984459256618],[120.40971737765034,150.08916438340054],[130.23500124909796,135.38177854509578],[139.38660580090695,123.22963797505226],[147.43621761284805,115.61277778124884],[154.4101284483585,112.77304678464483],[160.48967509300073,115.59328705834821],[163.50842225064653,124.16452002868009],[164.60544015473246,135.67065711382133],[165.5495102607029,146.5118158465516],[167.8986938716579,154.9252637455598],[175.15136764486354,157.00661551514688],[186.35394540159172,151.62742567449033],[196.72646656160268,144.58863660581187],[207.44049290347877,137.18220833711305],[217.40753446509254,130.94747987360358],[225.85774696809756,127.34141007370778],[233.7543040216643,126.6536494931764],[241.7995321270776,129.24438651864313],[248.1130277673329,133.03422026554998],[256.36642731473967,131.25231804959895],[270.08230409300563,117.89968537440078],[272.82589368447833,116.57314244738527],[275.8252266586026,117.11258817587262],[277.93465976284125,119.31197262666663],[278.34846124836935,122.33120569389202],[279.81909480872537,126.11214492858664],[286.22039750758796,134.73582934628854],[296.3139019853286,139.09094063207345],[309.8799496583604,139.11308386318004],[313.195930442018,141.32219135385486],[313.35780954118025,145.30335696460216],[310.23212905180657,147.77439033978973],[306.39579216257994,146.69809868075797],[305.01158815932763,142.96180857688182],[307.2206956500025,139.64582779322419],[311.20186126074975,139.48394869406195],[313.6728946359373,142.60962918343563],[312.59660297690556,146.44596607266226],[308.86031287302944,147.83017007591454],[293.7719717966829,147.8778863696637],[283.1674451064291,144.8069734891689],[275.4469258910661,138.77350685777046],[271.2585764431361,129.44450293829942],[271.837348534186,119.90253357417292],[272.8258936844784,116.57314244738527],[275.8252266586026,117.11258817587262],[277.93465976284125,119.31197262666663],[278.34846124836935,122.33120569389202],[276.9085730123103,125.01704296582625],[271.09766941647365,130.37662827821973],[264.8630791920111,136.33937034592464],[256.7608589712613,142.3514474312006],[248.82507550325954,144.02330004448982],[241.7098324523355,140.77014956278578],[236.11278280121985,135.3477694652123],[229.2160507203762,134.11178364958923],[220.91133479175065,136.82008288484934],[211.40982511476966,142.69795901876137],[201.04104815372648,150.50847634318654],[190.60942717261938,158.63154218950544],[179.9322886167563,165.12902943096165],[170.04260282580074,166.57911075346885],[163.15905897175116,163.3647453825724],[159.5340073028416,156.53506699226435],[158.00827785349242,147.22114286708367],[156.96532814978838,136.36723278920607],[155.39358669877592,122.81394963464497],[151.08388241912016,119.77962515551144],[143.79111355110732,126.65236343400774],[134.9055836896068,138.47022521239754],[125.38184245889569,153.09951230324836],[117.01128221993326,167.87662657183094],[106.63920388631331,180.55441927308937],[98.51111508872103,180.3216251381348],[96.4900496643839,171.9693155824358],[96.97531431316833,159.90591737492895],[98.26534177303924,143.09521752679024],[100.64781233531221,123.06481128643078],[104.26172506481416,100.30392221183364],[108.45381697545054,76.65988829382947],[111.79386203990705,55.930432446096155],[112.82029302517655,41.475570385076104],[114.05582745474565,32.52657490353667],[114.80509822090353,29.787735946126833],[116.4561260143661,30.306118771281103],[117.48713777779531,31.69594907454529],[117.50432206036216,33.42635891866705],[116.5011150501984,34.83639055765927],[107.80581880223335,42.226456288808265],[99.77322688090533,53.08613046332224],[88.86201283146072,71.23002822069239],[76.02698503124532,96.5861711715254],[63.095809049596625,127.85226142599691],[51.78735399084056,162.66387163465652],[42.78703848251283,197.22862341028645],[36.639721279421614,227.93472617679046],[34.02583867880232,252.62135946668224],[33.66592701983083,267.7019917440167],[35.09170826983083,275.2508198690167],[34.94160156424523,277.2111610675837],[33.66790304247781,278.70887990418436],[31.757122248423222,279.1718986888281],[29.939112500359812,278.4233599832108],[28.908291730169168,276.7491801309833]],"color":"#000"}]}}'),X=window.wp.blocks,q={apiVersion:2,icon:function(){return(0,i.createElement)(_.SVG,{xmlns:"http://www.w3.org/2000/svg",width:"24",height:"24",viewBox:"0 0 24 24",fill:"none"},(0,i.createElement)(_.Path,{d:"M20.497 10.7067C20.4631 9.68723 20.1568 8.71859 19.6833 7.80626C19.2732 7.02257 18.7279 6.30373 18.0796 5.70093C17.4411 5.10396 16.7082 4.61763 15.91 4.25942C15.4973 4.07545 15.0734 3.92555 14.6315 3.82288C14.2873 3.74208 13.9391 3.67871 13.5905 3.63355C12.6822 3.52154 11.7485 3.57221 10.8548 3.77094C10.0878 3.94316 9.34771 4.2239 8.6621 4.6047C7.97649 4.98549 7.34533 5.46634 6.79847 6.0283C6.1934 6.65408 5.68502 7.37108 5.31791 8.15999C5.02315 8.78942 4.80037 9.45694 4.68294 10.1444C4.5667 10.8102 4.52017 11.4916 4.57748 12.1669C4.61314 12.5959 4.67554 13.0198 4.77862 13.4419C4.87978 13.8562 5.01543 14.2636 5.18017 14.6556C5.51041 15.4361 5.9624 16.1633 6.52146 16.8046C7.09135 17.4631 7.77413 18.026 8.52493 18.4651C9.30824 18.9225 10.1759 19.2487 11.0681 19.4157C11.9388 19.5815 12.9653 19.6591 13.8434 19.5301C14.9633 19.3655 16.2722 18.2885 14.4177 18.7277C13.9781 18.8318 13.233 18.8269 12.489 18.7142C9.66796 18.287 8.0557 16.9947 6.8022 14.3656C5.73816 11.1547 6.86245 7.72506 9.63649 6.2899C11.6443 5.25114 13.6007 5.41855 15.3336 6.83164C17.0666 8.24474 17.0046 10.6168 18.1781 11.8151C18.579 12.3125 19.7478 12.4218 20.239 11.7645C20.4733 11.451 20.5089 11.0681 20.497 10.7067Z",strokeWidth:".5"}))},attributes:{strokes:{type:"array",default:[]},height:{type:"number",default:450},title:{type:"string",default:""}},supports:{align:!0},title:(0,x.__)("Sketch","a8c-sketch"),category:"widgets",description:(0,x._x)("“Not a day without a line drawn.” — Apelles of Kos","Block description, based on a quote","a8c-sketch"),keywords:[(0,x.__)("Draw","a8c-sketch")],edit:function(e){var t=e.attributes,r=e.isSelected,l=e.setAttributes,c=t.strokes,a=t.height,s=t.title,u=o((0,i.useState)(),2),p=u[0],h=u[1],f=o((0,i.useState)(1),2),d=f[0],g=f[1],m=o((0,i.useState)(!1),2),v=m[0],b=m[1],w=o((0,i.useState)("#000"),2),y=w[0],k=w[1],P=(0,i.useRef)(null),S=(0,O.useBlockProps)({className:"wp-block-a8c-sketch",ref:P}),x=p&&{stroke:E(p.points,H(H({},C[d]),{},{simulatePressure:"pen"!==p.type})),color:y};return(0,i.createElement)(i.Fragment,null,(0,i.createElement)(V,{clear:function(){return l({strokes:[],height:450})},color:y,setColor:k,preset:d,setPreset:g,isEmpty:!c.length,title:s,setTitle:function(e){return l({title:e})},blockRef:P,attributes:t}),(0,i.createElement)("figure",S,(0,i.createElement)(_.ResizableBox,{size:{height:a},minHeight:200,enable:{top:!1,right:!1,bottom:!0,left:!1,topRight:!1,bottomRight:!1,bottomLeft:!1,topLeft:!1},onResizeStart:function(){b(!0)},onResizeStop:function(e,t,n,r){var o=Math.min(parseInt(a+r.height,10),1e3);l({height:o}),b(!1)},showHandle:r,__experimentalShowTooltip:!0,__experimentalTooltipProps:{axis:"y",position:"bottom",isVisible:v}},(0,i.createElement)(N,{handlePointerDown:function(e){var t=P.current.getBoundingClientRect(),n=t.left,o=t.top;r&&h({type:e.pointerType,points:[[e.clientX-n,e.clientY-o,e.pressure]]})},handlePointerMove:function(e){var t=P.current.getBoundingClientRect(),o=t.left,i=t.top;r&&p&&1===e.buttons&&(e.preventDefault(),h(H(H({},p),{},{points:[].concat(n(p.points),[[e.clientX-o,e.clientY-i,e.pressure]])})))},handlePointerUp:function(){r&&p&&(l({strokes:[].concat(n(c),[{stroke:E(p.points,H(H({},C[d]),{},{simulatePressure:"pen"!==p.type})),color:y}])}),h(void 0))},strokes:c,currentStroke:x,title:s}))))},save:function(){var e=O.useBlockProps.save();return(0,i.createElement)("figure",e)},example:F};(0,X.registerBlockType)("a8c/sketch",q)}()}();