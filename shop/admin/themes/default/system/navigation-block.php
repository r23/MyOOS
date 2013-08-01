				<!-- Main page header -->
				<header>
			
					<!-- Main page logo -->
					<h1><a class="brand" href="{$home}">{$lang.page_title}</a></h1>
					
					<!-- Main page headline -->
					<p>{$lang.page_headline}</p>
					
				</header>
				<!-- /Main page header -->
				
				<!-- User profile -->
				<section class="user-profile">
					<figure>
						<img alt="John Pixel avatar" src="http://placekitten.com/50/50">
						<figcaption>
							<strong><a href="#" class="">John Pixel</a></strong>
							<em>Administrator</em>
							<ul>
								<li><a class="btn btn-primary btn-flat" href="#" title="View www.example.com">view website</a></li>
								<li><a class="btn btn-primary btn-flat" href="#" title="Securely logout from application">logout</a></li>
							</ul>
						</figcaption>
					</figure>
				</section>
				<!-- /User profile -->
				
				<!-- Responsive navigation -->
				<a href="#" class="btn btn-navbar btn-large" data-toggle="collapse" data-target=".nav-collapse"><span class="fam-cog"></span> Grid</a>
{foreach item=block from=$oos_blockright}
   {include file="myoos/system/block.html"}
{/foreach}				
				<!-- Main navigation -->
				<nav class="main-navigation nav-collapse" role="navigation">
					<ul>
						<li><a href="index.html" class="no-submenu"><span class="fam-house"></span>Dashboard</a></li>
						<li><a href="forms.html" class="no-submenu"><span class="fam-application-form"></span>Forms</a></li>
						<li><a href="charts.html" class="no-submenu"><span class="fam-chart-line"></span>Charts</a></li>
						<li><a href="tables.html" class="no-submenu"><span class="fam-application-view-columns"></span>Tables</a></li>
						<li>
							<a href="#"><span class="fam-picture"></span>Gallery<span class="badge" title="5 new image uploaded">5</span></a>
							<ul>
								<li><a href="gallery.html">Car Gallery</a></li>
								<li><a href="gallery.html">Food Gallery</a></li>
								<li><a href="gallery.html">Art Gallery</a></li>
								<li><a href="gallery.html">Animal Gallery</a></li>
								<li><a href="gallery.html">Super long name to see how it collapse</a></li>
							</ul>
						</li>
						<li><a href="file-explorer.html" class="no-submenu"><span class="fam-briefcase"></span>File explorer</a></li>
						<li><a href="calendar.html" class="no-submenu"><span class="fam-calendar-view-day"></span>Calendar<span class="badge" title="27 tasks this week">27</span></a></li>
						<li><a href="ui-buttons.html" class="no-submenu"><span class="fam-rosette"></span>UI & Buttons</a></li>
						<li><a href="typo.html" class="no-submenu"><span class="fam-text-padding-left"></span>Typography</a></li>
						<li><a href="grid.html" class="no-submenu"><span class="fam-cog"></span>Grid</a></li>
						<li><a href="goodies.html" class="no-submenu"><span class="fam-heart"></span>Goodies</a></li>
						<li class="current">
							<a href="#"><span class="fam-rainbow"></span>Bonus pages</a>
							<ul>
								<li><a class="current" href="eshop.html">Online store</a></li>
								<li><a href="add-item.html">Add item / product</a></li>
								<li><a href="invoice.html">Invoice</a></li>
								<li><a href="user-profile.html">User profile</a></li>
							</ul>
						</li>
						<li><a href="login.html" class="no-submenu"><span class="fam-door-in"></span>Login page</a></li>
						<li>
							<a href="#"><span class="fam-error"></span>Error pages</a>
							<ul>
								<li><a href="401.html">Error 401</a></li>
								<li><a href="403.html">Error 403</a></li>
								<li><a href="404.html">Error 404</a></li>
								<li><a href="500.html">Error 500</a></li>
								<li><a href="503.html">Error 503</a></li>
							</ul>
						</li>
						<li><a href="docs/index.html" class="no-submenu"><span class="fam-book-open"></span>Docs</a></li>
					</ul>
				</nav>
				<!-- /Main navigation -->
				
				<!-- Side note -->
				<section class="side-note">
					<div class="thumbnail">
						<img src="http://placekitten.com/221/120" alt="Sample Image">
					</div>
					<h2>Side note with image</h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse et dignissim metus. Maecenas id augue ac metus tempus aliquam. Sed pharetra placerat est suscipit sagittis. Phasellus aliquam malesuada blandit. Donec adipiscing sem erat.</p>
					<a class="btn pull-right" href="#" title="This is my title!">Event details</a>
				</section>
				<!-- /Side note -->
				
