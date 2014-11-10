/*

Correctly handle PNG transparency in Win IE 5.5 & 6.
http://homepage.ntlworld.com/bobosola. Updated 18-Jan-2006.

Use in <HEAD> with DEFER keyword wrapped in conditional comments:
<!--[if lt IE 7]>
<script defer type="text/javascript" src="pngfix.js"></script>
<![endif]-->

*/

var arVersion = navigator.appVersion.split("MSIE")
var version = parseFloat(arVersion[1])

function correctPNG() // correctly handle PNG transparency in Win IE 5.5 and 6.
{
   if ((version >= 5.5) && (document.body.filters)) 
   {
       for(var i=0; i<document.images.length; i++)
       {
	      var img = document.images[i]
	      var imgName = img.src.toUpperCase()
	      if (imgName.substring(imgName.length-3, imgName.length) == "PNG")
	      {
		     var imgID = (img.id) ? "id='" + img.id + "' " : ""
		     var imgClass = (img.className) ? "class='" + img.className + "' " : ""
		     var imgTitle = (img.title) ? "title='" + img.title + "' " : "title='" + img.alt + "' "
		     var imgStyle = "display:inline-block;" + img.style.cssText 
		     var imgAttribs = img.attributes;
		     for (var j=0; j<imgAttribs.length; j++)
			 {
			    var imgAttrib = imgAttribs[j];
			    if (imgAttrib.nodeName == "align")
			    {		  
			       if (imgAttrib.nodeValue == "left") imgStyle = "float:left;" + imgStyle
			       if (imgAttrib.nodeValue == "right") imgStyle = "float:right;" + imgStyle
			       break
			    }
             }
		     var strNewHTML = "<span " + imgID + imgClass + imgTitle
		     strNewHTML += " style=\"" + "width:" + img.width + "px; height:" + img.height + "px;" + imgStyle + ";"
	         strNewHTML += "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"
		     strNewHTML += "(src='" + img.src + "', sizingMethod='scale');\""
		     strNewHTML += " onmouseover=\"PNGswap('" + img.id + "');\" onmouseout=\"PNGswap('" + img.id +"');\""
		     strNewHTML += "></span>" 
		     img.outerHTML = strNewHTML
		     i = i-1
	      }
       }
   }
}
window.attachEvent("onload", correctPNG);

function PNGswap(myID)
{
   var strOver  = "_on"
   var strOff = "_off"
   var oSpan = document.getElementById(myID)
   var currentAlphaImg = oSpan.filters(0).src
   if (currentAlphaImg.indexOf(strOver) != -1)
      oSpan.filters(0).src = currentAlphaImg.replace(strOver,strOff)
   else
      oSpan.filters(0).src = currentAlphaImg.replace(strOff,strOver)
}
