{include file="default/system/_header.tpl"}

		<!-- Main login container -->
		<div class="login-container">
			
			<!-- Login page logo -->
			<h1><a class="brand" href="http://www.oos-shop.de/">MyOOS</a></h1>
			
			<section>
				
				<!-- alert -->
				<div class="alert alert-info alert-block fade in">
					<button class="close" data-dismiss="alert">&times;</button>
					{$lang.text_main}
				</div>		
				<!-- /alert -->
				
				
			</section>
			
			<!-- Login page navigation -->
			<nav>
				<ul>
					<li><a href="{$login_link}">{$lang.text_relogin}</a></li>
					<li><a href="http://www.oos-shop.de/">{$lang.header_title_support_site}</a></li>
					<li><a href="{$catalog_link}">{$lang.header_title_online_catalog}</a></li>
				</ul>
			</nav>
			<!-- Login page navigation -->
			
		</div>
		<!-- /Main login container -->

{include file="default/system/_footer.tpl"}
