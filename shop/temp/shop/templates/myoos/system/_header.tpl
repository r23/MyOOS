<!DOCTYPE html>
<html lang="{$smarty.const.LANG}">
<head>
<meta charset="utf-8" />
<title>{$pagetitle}</title>
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
<link rel="canonical" href="{$canonical}" />
<meta name="robots" content="index,follow,noodp,noydir"/>		
<!-- Bootstrap style  --> 
<link href="{$theme}/css/bootstrap.min.css" rel="stylesheet" />
<link href="{$theme}/css/style.css" rel="stylesheet" />
<link href="{$theme}/css/font-awesome.min.css" rel="stylesheet" />
<!-- Included Custom CSS Files -->

<!-- Place favicon.ico and apple-touch-icon.png -->
<link rel="shortcut icon" href="{$theme}/images/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{$theme}/images/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{$theme}/images/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{$theme}/images/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="{$theme}/images/ico/apple-touch-icon-57-precomposed.png">

<!-- Google fonts -->
<link href='http://fonts.googleapis.com/css?family=Noto+Serif:400,400italic,700|Open+Sans:300,400,600,700' rel='stylesheet' type='text/css' />

<!-- IE Fix for HTML5 Tags -->
<!--[if lt IE 9]>
	<script src="{$theme}/js/html5.js"></script>
<![endif]-->
<script src="{$theme}/js/jquery.min.js"></script>
</head>
<body>
<!--[if lt IE 7]>
	<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->
<div id="wrapper">
	<!-- start header -->
	<header>
	<div class="container">
		<div class="row nomargin">
			<div class="col-md-12">				
				<div class="headnav">
					<ul>
						<li class="first"><a href="{html_href_link content=$contents.account connection=SSL}" title="{$lang.header_title_my_account}"><i class="icon-user"></i> {$lang.header_title_my_account}</a></li>
						<li><a href="{html_href_link content=$contents.main_shopping_cart}" title="{$lang.header_title_cart_contents}"><i class="icon-shopping-cart"></i> {$lang.header_title_cart_contents}</a></li>
						<li><a href="{html_href_link content=$contents.checkout_payment connection=SSL}" title="{$lang.header_title_checkout}"><i class="icon-folder-open"></i> {$lang.header_title_checkout}</a></li>
{if (isset($smarty.session.customer_id)) }
						<li class="last"><a href="{html_href_link content=$contents.logoff connection=SSL}" title="{$lang.header_title_logoff}"><i class="icon-signout"></i> {$lang.header_title_logoff}</a></li>
{else}
						<li class="last"><a href="{html_href_link content=$contents.login connection=SSL}" title="{$lang.header_title_login}"><i class="icon-signin"></i> {$lang.header_title_login}</a></li>
{/if}
					</ul>		
				</div>
			</div>
		</div>			
		<div class="row">
			<div class="col-md-6 logo">
					<a href="{html_href_link content=$contents.main}"><img src="{$theme_image}/logo.gif" width="140" height="179" alt="{$smarty.const.STORE_NAME}" title="{$smarty.const.STORE_NAME}" class="logo" /></a>
					<h1>bootstrap template</h1>
			</div>

		</div>
	</div>
	</header>
	<!-- end header -->
			
{if $oos_info_warning}
	{foreach item=warning from=$oos_info_warning}
		{include file="myoos/system/warning.tpl"}
	{/foreach}
{/if}
{foreach item=error from=$oos_error_message}
	{include file="myoos/system/error_message.tpl"}
{/foreach}
{foreach item=info from=$oos_info_message}
	{include file="myoos/system/info_message.tpl"}
{/foreach}
