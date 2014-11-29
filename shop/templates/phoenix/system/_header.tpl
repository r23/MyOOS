<!DOCTYPE html>
<html lang="{$smarty.const.LANG}">
<head>
<meta charset="utf-8" />
<title>{$pagetitle} {$smarty.const.OOS_META_TITLE}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />	
<meta name="description" content="{$meta_description}" />
<meta name="author" content="{$smarty.const.OOS_META_AUTHOR}" />
{*
	DO NOT REMOVE THE FOLLOWING - FAILURE TO COMPLY IS A DIRECT VIOLATION
	OF THE GNU GENERAL PUBLIC LICENSE - http://www.gnu.org/copyleft/gpl.html
*}
<meta name="Generator" content="{$smarty.const.OOS_FULL_NAME} - {$smarty.const.OOS_HOME}  All rights reserved.">
{*
	END OF COPYRIGHT
*}
<meta name="Language" content="{$smarty.session.languages_name}">
<meta name="Content-Language" content="{$smarty.session.iso_639_1}">
<meta name="resource-type" content="document">
<meta name="creation" content="{$smarty.now|date_format:"%a,%d %b %Y"}">	
<meta name="robots" content="index,follow,noodp,noydir"/>

<link rel="canonical" href="{$canonical}" />
<base href="{$oos_base}">		
<!-- Bootstrap style  --> 
<link href="{$theme}/css/bootstrap.min.css" rel="stylesheet" />
<link href="{$theme}/css/style.css" rel="stylesheet" />
<link href="{$theme}/css/font-awesome.min.css" rel="stylesheet" />
<!-- Included Custom CSS Files -->

<!-- Place favicon.ico and apple-touch-icon.png -->
<link rel="shortcut icon" href="{$theme}/images/ico/favicon.ico">
<link rel="apple-touch-fa fa-precomposed" sizes="144x144" href="{$theme}/images/ico/apple-touch-fa fa-144-precomposed.png">
<link rel="apple-touch-fa fa-precomposed" sizes="114x114" href="{$theme}/images/ico/apple-touch-fa fa-114-precomposed.png">
<link rel="apple-touch-fa fa-precomposed" sizes="72x72" href="{$theme}/images/ico/apple-touch-fa fa-72-precomposed.png">
<link rel="apple-touch-fa fa-precomposed" href="{$theme}/images/ico/apple-touch-fa fa-57-precomposed.png">

<!-- Fonts -->
<link href="//fonts.googleapis.com/css?family=Lato:400,900,300,700" rel="stylesheet">
<link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:400,700,400italic,700italic" rel="stylesheet">

{$oos_js}

</head>
<body>

<!-- header //-->
<table width="870" align="center" cellpadding="0" cellspacing="0">
   <tr>
      <td class="block_head"><a href="{html_href_link content=$filename.main}"><img src="{$theme_image}/logo.gif" border="0" width="140" height="179" alt="{$smarty.const.STORE_NAME}" title=" {$smarty.const.STORE_NAME} "></a></td>
   </tr>
</table>


<table width="870" border="0" align="center" cellspacing="0" cellpadding="1">
  <tr class="oos-HeadNavi">
    <td class="oos-HeadNavi">&nbsp;&nbsp;{$oos_breadcrumb}</td>
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

<!-- header_eof //-->

<!-- body //-->
<table width="870" border="0" align="center" cellspacing="0" cellpadding="0">
  <tr>
    <td width="150" valign="top"><table border="0" width="150" cellspacing="0" cellpadding="2">
{foreach item=block from=$oos_blockleft}
   {include file="phoenix/system/_block.tpl"}
{/foreach}
    </table></td>
    <td><img src="{$theme_image}/trans.gif" border="0" alt=" " width="5" height="1"></td>
    <td width="100%" valign="top">