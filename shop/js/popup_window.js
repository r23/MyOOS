winprops = 'toolbar=no,location=no,directories=no,status=no,menubar=no,copyhistory=no,';

function win_autologon(url){
  win = window.open(url, 'color', winprops + 'scrollbars=yes,top=150,left=180,width=240,height=390')
  if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}
function session_win(url) {
  win = window.open(url, 'myCart', 'height=460,width=430,toolbar=no,statusbar=no,scrollbars=yes')
  if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}
function popupWindow(url) {
  win = window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=auto,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
  if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}
function searchWindow(url) {
  win = window.open(url,'searchWindow',winprops + 'scrollbars=no,width=450,height=280,screenX=150,screenY=150,top=150,left=150')
  if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}
function helpWindow(url) {
  win = window.open(url,'helpWindow',winprops + 'scrollbars=no,width=450,height=150,screenX=150,screenY=150,top=150,left=150')
  if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}
function popupImageWindow(url) {
  win = window.open(url,'popupImageWindow',winprops + 'scrollbars=no,resizable=yes,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
  if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}