// shariff add click listener function
function shariff_click() {
    // enabled strict mode
    "use strict";
	// get elements
	var classname = document.getElementsByClassName("shariff-link");
	// set all event listeners
	for ( var i = 0; i < classname.length; i++ ) {
		classname[i].addEventListener('click', shariff_popup, false);
	}
}
// actual popup function
function shariff_popup( evt ) {
	// set variables
	var t = this.getAttribute('href');
	var o = screen.width/2-350;
	var r = screen.height/2-250;
	var l = t.length;
	// open popup if not one of the special services
	if ( t.substring( 0, 7 ) != "mailto:" && t.substring( l-9 ) != "view=mail" && t != "javascript:window.print()" && t != "http://ct.de/-2467514" ) {
		// prevent default action
		evt.preventDefault();
		// open popup
		window.open( t,"_blank","height=500, width=700, status=yes, toolbar=no, menubar=no, location=no, top="+r+", left="+o );
		// return false to prevent tab opening in some browsers
		return false;
	}
}
// add event listener to call shariff popup function after DOM
document.addEventListener( "DOMContentLoaded", shariff_click, false );
