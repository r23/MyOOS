			<!-- Left (navigation) side -->
			<section class="navigation-block">
			
				<!-- Main page header -->
				<header>
				
					<!-- Main page logo -->
					<h1><a class="brand" href="http://www.oos-shop.de/">MyOOS</a></h1>
					
					
				</header>
				<!-- /Main page header -->


				
				<!-- User profile -->
				<section class="user-profile">
					<figure>
						<figcaption>
							<strong><a href="#" class="">John Pixel</a></strong>
							<em>Administrator</em>
							<ul>
								<li><a class="btn btn-primary btn-flat" href="{$admin_account}" title="{$lang.header_title_account}">{$lang.header_title_account}</a></li>
								<li><a class="btn btn-primary btn-flat" href="{$logoff}" title="{$lang.header_title_logoff}">{$lang.header_title_logoff}</a></li>
							</ul>
						</figcaption>
					</figure>
				</section>
				<!-- /User profile -->
				
				<!-- Responsive navigation -->
				
				<!-- Main navigation -->
				<nav class="main-navigation nav-collapse" role="navigation">
					<ul>
						<li>
							<a href="{$heading_information}" class="no-submenu"><span class="cus-house"></span>Dashboard</a>
						</li>
			{foreach item=block from=$oos_block}
				{if $block.content}
					{$block.content}
				{/if}
			{/foreach}
					</ul>
				</nav>
				<!-- /Main navigation -->
				
				
			</section>
			<!-- /Left (navigation) side -->