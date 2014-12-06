<!DOCTYPE html>
<html lang="de">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<title>{$pagetitle}</title>
<meta name="description" content="{$meta_description}">
<meta name="author" content="{$smarty.const.OOS_META_AUTHOR}">
<meta name="copyright" content="Copyright (c) {$smarty.now|date_format:"%Y"} by {$smarty.const.OOS_META_COPYRIGHT}">
<meta name="robots" content="noodp,noydir">
<meta name="Language" content="{$smarty.session.languages_name}">
<meta name="Content-Language" content="{$smarty.session.iso_639_1}">
<meta name="resource-type" content="document">
<meta name="creation" content="{$smarty.now|date_format:"%a,%d %b %Y"}">
<meta name="revision" content="{$oos_revision_date}">
{*
 *	DO NOT REMOVE THE FOLLOWING - FAILURE TO COMPLY IS A DIRECT VIOLATION
 *	OF THE GNU GENERAL PUBLIC LICENSE - http://www.gnu.org/copyleft/gpl.html
*}
<meta name="Generator" content="{$smarty.const.OOS_FULL_NAME} - {$smarty.const.OOS_HOME}  All rights reserved.">
{*
 *	END OF COPYRIGHT
*}
<meta name="rating" content="General">
<base href="{$oos_base}">
<link rel="shortcut icon" href="{$oos_base}favicon.ico" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="{$theme_css}/style.css">
<link rel="stylesheet" type="text/css" href="{$theme_css}/stylesheet.css">

<link rel="top" type="text/html" href="{$oos_base}">
<link rel="contents" type="text/html" href="{html_href_link content=$filename.sitemap connection='NONSSL' add_session_id='false' search_engine_safe='false'}">
<link rel="search" type="text/html" href="{html_href_link content=$filename.advanced_search connection='NONSSL' add_session_id='false' search_engine_safe='false'}">
<link rel="bookmark" type="text/html" href="{html_href_link content=$page_file oos_get=$get_params connection='NONSSL' add_session_id='false' search_engine_safe='false'}">

{literal}
<script type="text/javascript" language="JavaScript">
<!--
function couponpopupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=280,screenX=150,screenY=150,top=150,left=150')
}

function popupZoom(url) {
  var width  = 900;
  var height = 636;
  var y = (screen.availHeight - height) /2;
  var x = (screen.availWidth - width) /2;
  var micro = window.open(url,'micro','resizable=0,scrollbars=0,status=0,location=0,width='+width+',height='+height+', top='+y+',left='+x);
  micro.focus();
}
//-->
</script>
{/literal}

{$oos_js}

</head>
<body>

{foreach item=warning from=$oos_info_warning}
   {include file="default/system/warning.html"}
{/foreach}

<!-- header //-->
<table width="870" align="center" cellpadding="0" cellspacing="0">
   <tr>
      <td class="block_head"><a href="{html_href_link content=$filename.main}"><img src="{$theme_image}/logo.gif" border="0" width="140" height="179" alt="{$smarty.const.STORE_NAME}" title=" {$smarty.const.STORE_NAME} "></a></td>
   </tr>
</table>


<table width="870" border="0" align="center" cellspacing="0" cellpadding="1">
  <tr class="oos-HeadNavi">
    <td class="oos-HeadNavi"></td>
    <td align="right" class="oos-HeadNavi">

{if (isset($smarty.session.customer_id)) }
<a href="{html_href_link content=$filename.logoff connection=SSL}" class="headerNavigation">{$lang.header_title_logoff}</a>&nbsp;
{else}
<a href="{html_href_link content=$filename.login connection=SSL}" class="headerNavigation">{$lang.header_title_login}</a>&nbsp;
{/if}
|&nbsp;<a href="{html_href_link content=$filename.account connection=SSL}" class="headerNavigation">{$lang.header_title_my_account}</a>&nbsp;
|&nbsp;<a href="{html_href_link content=$filename.shopping_cart}" class="headerNavigation">{$lang.header_title_cart_contents}</a>&nbsp;
|&nbsp;<a href="{html_href_link content=$filename.checkout_payment connection=SSL}" class="headerNavigation">{$lang.header_title_checkout}</a>&nbsp;&nbsp;

    </td>
  </tr>
</table>


{foreach item=error from=$oos_error_message}
   {include file="oos/system/error_message.html"}
{/foreach}
{foreach item=info from=$oos_info_message}
   {include file="oos/system/info_message.html"}
{/foreach}
<!-- header_eof //-->

<!-- body //-->
<table width="870" border="0" align="center" cellspacing="0" cellpadding="0">
  <tr>
    <td width="150" valign="top"><table border="0" width="150" cellspacing="0" cellpadding="2">
{foreach item=block from=$oos_blockleft}
   {include file="oos/system/_block.tpl"}
{/foreach}
    </table></td>
    <td><img src="{$theme_image}/trans.gif" border="0" alt=" " width="5" height="1"></td>
    <td width="100%" valign="top">