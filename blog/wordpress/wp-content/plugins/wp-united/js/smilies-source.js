/** 
*
* @package WP-United
* @version $Id: 0.9.1.5  2012/12/17 John Wells (Jhong) Exp $
* @copyright (c) 2006-2012 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
* 
* Displays smilies on the comment posting form
*/

function wpuSmlAdd(){
	var tas, ta, tb; 
	ta=null;
	
	text = this.firstChild.getAttribute('alt');
	
	// We try to detect the comment textarea
	tas = document.getElementsByTagName('textarea');
	
	if(tas.length>1) {
		for(var i=0; i<tas.length; i++) {
			if(tas[i].id == 'comment' ) {
				ta=tas[i];
			} else if((tas[i].name == 'comment') && (ta==null)) {
				ta=tas[i];
			} else if((tas[i].className == 'comment') && (ta == null)) {
				ta=tas[i];
			}
		}
		if(ta == null) {
			for(i=0;i<tas.length;i++) {
				try{
					if(tas[i].gotFocus)ta=tas[i];
				} catch(e){ 
				}
			}
		}
		if(ta==null) {
			ta=tas[0];
		}
	
	} else if(tas.length==1) {
		ta=tas[0];
	}
	
	if(ta==null) {
		alert(replace('%s', text, wpuLang['wpu_smiley_error']));
	}

	// We now have a text area
	if (document.selection){ // for IE
		ta.focus();
		sel=document.selection.createRange();
		sel.text= ' ' + text + ' ';
	} else if (ta.selectionStart || ta.selectionStart == 0) { // for decent browsers
		 ta.value = ta.value.substring(0, ta.selectionStart) + ' ' + text + ' ' + ta.value.substring(ta.selectionEnd, ta.value.length);
	} else { // fall back to just dumping the smiley at the end
		 ta.value+= ' '+text+' ';
	}
	return false;
}

	// show / hide additional smilies
function wpuSmlMore() {
	document.getElementById('wpu-smiley-more').style.display='inline';
	var toggle = document.getElementById('wpu-smiley-toggle');
	toggle.setAttribute("onclick", "return wpuSmlLess()");
	toggle.firstChild.nodeValue ="\u00AB\u00A0" + wpuLang['wpu_less_smilies'];
	return false;
	}
    
function wpuSmlLess() {
	document.getElementById('wpu-smiley-more').style.display='none';
	var toggle = document.getElementById('wpu-smiley-toggle');
	toggle.setAttribute("onclick", "return wpuSmlMore();");
	toggle.firstChild.nodeValue = wpuLang['wpu_more_smilies'] + "\u00A0\u00BB";
	return false;
}

// apply smilies if they exist
if(document.getElementById("wpusmls")) {
	var smlCont = document.getElementById("wpusmls");
	var smls = smlCont.getElementsByTagName('a');
	for(var wpuI=0; wpuI<smls.length;wpuI++) {
		if(smls[wpuI].id != 'wpu-smiley-toggle') { 
			smls[wpuI].onclick = wpuSmlAdd;
		}
	}
}