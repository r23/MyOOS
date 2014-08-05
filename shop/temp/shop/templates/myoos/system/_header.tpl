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
<link rel="canonical" href="{$canonical}" />
<meta name="robots" content="index,follow,noodp,noydir"/>		
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

<!-- Google fonts -->
<link href='http://fonts.googleapis.com/css?family=Noto+Serif:400,400italic,700|Open+Sans:300,400,600,700' rel='stylesheet' type='text/css' />


</head>
<body>
<!--[if lt IE 7]>
	<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->

	<!-- start top --> 
	<div class="top">
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<ul class="list-unstyled top-contacts">
						<li>
							<a href="mailto:{$smarty.const.STORE_ADDRESS_EMAIL}" class="first-child"><i class="fa fa-envelope"></i> Email<span class="hidden-sm">: {$smarty.const.STORE_ADDRESS_EMAIL}</span></a>
						</li>
						<li>
							<i class="fa fa-phone-square"></i> Hotline: {$smarty.const.STORE_ADDRESS_TELEPHONE_NUMBER}
						</li>
					</ul>
				</div><!--/top-contacts-->

				<div class="col-md-8">
					<div class="header-buttons">
						<ul class="nav navbar-nav navbar-right">
						{if $languages_block eq 'true'}		
							<li id="language-menu" class="dropdown">
								<a href="#" id="drop1" role="button" class="dropdown-toggle" data-toggle="dropdown">{$language} <b class="caret"></b></a>
								<ul class="dropdown-menu" role="menu" aria-labelledby="drop1">
							{foreach item=languages from=$languages_contents}
									<li role="presentation"><a role="menuitem" tabindex="-1" href="{html_href_link content=$content_file oos_get=$lang_get_parameters language=$languages.iso_639_2 connection=$request_type}" title="{$languages.iso_639_2}" >{$languages.name} {if $languages.iso_639_2 == $language}<i class="fa fa-check"></i>{/if}</a></li>
							{/foreach}
								</ul>
							</li>
						{/if}	

						{if $currency_block eq 'true'}		
							<li id="fat-menu" class="dropdown">
								<a href="#" id="drop2" role="button" class="dropdown-toggle" data-toggle="dropdown">{$currency} <b class="caret"></b></a>
								<ul class="dropdown-menu" role="menu" aria-labelledby="drop2">
							{foreach item=currencies from=$currencies_contents}
									<li role="presentation"><a role="menuitem" tabindex="-1" href="{html_href_link content=$content_file oos_get=$currency_get_parameters currency=$currencies.id connection=$request_type}" title="{$currencies.iso_639_2}" >{$currencies.text} {if $currencies.id == $currency}<i class="fa fa-check"></i>{/if}</a></li>
							{/foreach}
								</ul>
							</li>
						{/if}	
						</ul>
					</div>
					<ul class="list-unstyled quick-access">
						<li class="first"><a href="{html_href_link content=$contents.account connection=SSL}" title="{$lang.header_title_my_account}"><i class="fa fa-user"></i> {$lang.header_title_my_account}</a></li>
						<li><a href="{html_href_link content=$contents.main_shopping_cart}" title="{$lang.header_title_cart_contents}"><i class="fa fa-shopping-cart"></i> {$lang.header_title_cart_contents}</a></li>
						<li><a href="{html_href_link content=$contents.checkout_payment connection=SSL}" title="{$lang.header_title_checkout}"><i class="fa fa-folder-open"></i> {$lang.header_title_checkout}</a></li>
					{if (isset($smarty.session.customer_id)) }
						<li class="last"><a href="{html_href_link content=$contents.logoff connection=SSL}" title="{$lang.header_title_logoff}"><i class="fa fa-sign-out"></i> {$lang.header_title_logoff}</a></li>
					{else}
						<li class="last"><a href="{html_href_link content=$contents.login connection=SSL}" title="{$lang.header_title_login}"><i class="fa fa-sign-in"></i> {$lang.header_title_login}</a></li>
					{/if}
					</ul>
				</div>
			</div>        
		</div>
	</div><!--/top-->
	<!-- end top -->

	<!-- start header -->
	<header>
	<div class="container">
		<div class="row nomargin">
			<div class="col-md-12">				
                                       
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
