
function imgSwap(oImg)
{
   var strOver  = '_over.jpg'    // image to be used with mouse over
   var strOff = '.jpg'     // normal image
   var strImg = oImg.src
   if (strImg.indexOf(strOver) != -1) 
      oImg.src = strImg.replace(strOver,strOff)
   else
      oImg.src = strImg.replace(strOff,strOver)
}
