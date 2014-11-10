/* modified version of a script once published in ALA http://www.alistapart.com/articles/alternate/ */

function setActiveStyleSheetProperty(title,property) {
  var i, a, main;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title") && a.getAttribute("title").indexOf(property) != -1) {
      	a.disabled = true;
      	if(a.getAttribute("title") == title) a.disabled = false;
    }
  }
  return true;
}

function setActiveStyleSheetTxt(title) {  return setActiveStyleSheetProperty(title,"classictext"); }
function setActiveStyleSheetCol(title) {  return setActiveStyleSheetProperty(title,"classiccolors"); }

function getActiveStyleSheetProperty(property) {
  var i, a;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1 && !a.disabled && a.getAttribute("title").indexOf(property) != -1) return a.getAttribute("title");
  }
  return null;
}

function getActiveStyleSheetTxt() { return getActiveStyleSheetProperty("classictext"); }
function getActiveStyleSheetCol() { return getActiveStyleSheetProperty("classiccolors"); }

function getPreferredStyleSheetProperty(property) {
  var i, a;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("rel").indexOf("alt") == -1 && a.getAttribute("title") && a.getAttribute("title").indexOf(property)  != -1) return a.getAttribute("title");
  }
  return null;
}
  
function getPreferredStyleSheetTxt() { return getPreferredStyleSheetProperty("classictext"); }
function getPreferredStyleSheetCol() { return getPreferredStyleSheetProperty("classiccolors"); }


function createCookie(name,value,days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
  }
  else expires = "";
  document.cookie = name+"="+value+expires+"; path=/";
  return true;
}

function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
}

window.onload = function(e) {
  var cookie = readCookie("xarayaclassic_textsize");
  var title = cookie ? cookie : getPreferredStyleSheetTxt();
  setActiveStyleSheetTxt(title);
  var cookie2 = readCookie("xarayaclassic_colscheme");
  var title2 = cookie2 ? cookie2 : getPreferredStyleSheetCol();
  setActiveStyleSheetCol(title2);
  return true;
}

/* window.onunload = function(e) { */
/*   var title = getActiveStyleSheetTxt(); */
/*   createCookie("xarayaclassic_textsize", title, 365); */
/*   var title2 = getActiveStyleSheetCol(); */
/*   createCookie("xarayaclassic_colscheme", title2, 365); */
/* } */

var cookie = readCookie("xarayaclassic_textsize");
var title = cookie ? cookie : getPreferredStyleSheetTxt();
setActiveStyleSheetTxt(title);
var cookie2 = readCookie("xarayaclassic_colscheme");
var title2 = cookie2 ? cookie2 : getPreferredStyleSheetCol();
setActiveStyleSheetCol(title2);
