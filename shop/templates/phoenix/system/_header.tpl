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
<meta name="Language" content="{$smarty.session.languages_name}">
<meta name="Content-Language" content="{$smarty.session.iso_639_1}">
<meta name="resource-type" content="document">
<meta name="creation" content="{$smarty.now|date_format:"%a,%d %b %Y"}">	
<meta name="robots" content="index,follow,noodp,noydir"/>
{if $canonical}
<link rel="canonical" href="{$canonical}" />
{/if}
<base href="{$oos_base}">		
<!-- Bootstrap style  --> 
<link href="{$theme}/css/bootstrap.min.css" rel="stylesheet" />
<link href="{$theme}/css/font-awesome.min.css" rel="stylesheet" />
<link href="{$theme}/css/style.min.css" rel="stylesheet" />

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
<div class="wrapper">

    <div class="header header-static">
        <div class="topbar">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <!-- Topbar Navigation -->
                        <ul class="list-inline left-topbar">
							<li><a href="{html_href_link content=$filename.account connection=SSL}">{$lang.header_title_my_account}</a></li>
							<li><a href="{html_href_link content=$filename.shopping_cart}">{$lang.header_title_cart_contents}</a></li>
							<li><a href="{html_href_link content=$filename.checkout_payment connection=SSL}">{$lang.header_title_checkout}</a></li>
						{if (isset($smarty.session.customer_id)) }
							<li><a href="{html_href_link content=$filename.logoff connection=SSL}">{$lang.header_title_logoff}</a></li>
						{else}
							<li><a href="{html_href_link content=$filename.login connection=SSL}">{$lang.header_title_login}</a></li>
						{/if}						
                        </ul>  
                    </div>
                    <div class="col-sm-6">
                        <ul class="right-topbar pull-right">
						{if $currency_block eq 'true'}
                            <li>
                                <a>{$lang.header_select_currencies}: ({$currency})</a>
                                <ul class="currency">
							{foreach item=currencies from=$currencies_contents}
								{if $currencies.id == $currency}
									<li class="active">
								{else}
									<li>
								{/if}
										<a href="{html_href_link content=$page_file oos_get=$currency_get_parameters currency=$currencies.id connection=$request_type}">{$currencies.text} {if $currencies.id == $currency}<i class="fa fa-check"></i>{/if}</a></li>
							{/foreach}
                                </ul>
                            </li>
						{/if}	
						{if $languages_block eq 'true'}
                            <li>
                                <a>{$lang.header_select_language}: ({$language})</a>
                                <ul class="language">
							{foreach item=languages from=$languages_contents}
								{if $languages.iso_639_2 == $language}
									<li class="active">
								{else}
									<li>
								{/if}
										<a href="{html_href_link content=$page_file oos_get=$lang_get_parameters language=$languages.iso_639_2 connection=$request_type}">{$languages.name} ({$languages.iso_639_2}){if $languages.iso_639_2 == $language}<i class="fa fa-check"></i>{/if}</a>
									</li>
							{/foreach}
                                </ul>
                            </li>
						{/if}							
                        </ul>
                    </div>
                </div>
            </div><!--/container-->
        </div>
    </div>


<!-- header //-->
<table width="870" align="center" cellpadding="0" cellspacing="0">
   <tr>
      <td class="block_head"><a href="{html_href_link content=$filename.main}"><img src="{$theme_image}/logo.gif" border="0" width="140" height="179" alt="{$smarty.const.STORE_NAME}" title=" {$smarty.const.STORE_NAME} "></a></td>
   </tr>
</table>

<!-- header_eof //-->
