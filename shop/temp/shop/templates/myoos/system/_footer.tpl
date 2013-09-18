	<footer>
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<div class="widget">
					<h5 class="widgetheading">{$lang.header_title_my_account}</h5>
					<ul class="link-list">
						<li><a href="{html_href_link content=$contents.account connection=SSL}" title="{$lang.login_block_my_account}">{$lang.login_block_my_account}</a></li>
						<li><a href="{html_href_link content=$contents.account_edit connection=SSL}" title="{$lang.login_block_account_edit}">{$lang.login_block_account_edit}</a></li>
						<li><a href="{html_href_link content=$contents.account_history connection=SSL}" title="{$lang.login_block_account_history}">{$lang.login_block_account_history}</a></li>
						<li><a href="{html_href_link content=$contents.account_order_history connection=SSL}" title="{$lang.login_block_order_history}">{$lang.login_block_order_history}</a></li>
						<li><a href="{html_href_link content=$contents.account_address_book connection=SSL}" title="{$lang.login_block_address_book}">{$lang.login_block_address_book}</a></li>
					</ul>
				</div>
			</div>
			<div class="col-md-3">
				<div class="widget">
					<h5 class="widgetheading">{$block_heading_information}</h5>			
					<ul class="link-list">
					{foreach item=info from=$information}
						<li><a href="{html_href_link content=$contents.information information_id=$info.information_id}" title="{$info.information_name}" rel="nofollow">{$info.information_name}</a></li>
					{/foreach}	
					</ul>					
				</div>
			</div>
			<div class="col-md-3">
				<div class="widget">
					<h5 class="widgetheading">{$lang.header_title_service}</h5>			
					<ul class="link-list">
						<li><a href="{html_href_link content=$contents.products_new}" title="{$lang.block_service_new}"><i class="icon-bullhorn"></i> {$lang.block_service_new}</a></li>
						<li><a href="{html_href_link content=$contents.specials}" title="{$lang.block_service_specials}"><i class="icon-star"></i> {$lang.block_service_specials}</a></li>
						<li><a href="{html_href_link content=$contents.info_sitemap}" title="{$lang.block_service_sitemap}"><i class="icon-sitemap"></i> {$lang.block_service_sitemap}</a></li>
						<li><a href="{html_href_link content=$contents.advanced_search}" title="{$lang.block_service_advanced_search}"><i class="icon-search"></i> {$lang.block_service_advanced_search}</a></li>
{if $oEvent->installed_plugin('reviews')}
						<li><a href="{html_href_link content=$contents.reviews_reviews}" title="{$lang.block_service_reviews}"><i class="icon-comments"></i> {$lang.block_service_reviews}</a></li>
{else}
						<li><a href="{html_href_link content=$contents.main_shopping_cart}" title="{$lang.block_service_shopping_cart}"><i class="icon-shopping-cart"></i> {$lang.block_service_shopping_cart}</a></li>
{/if}
					</ul>					
				</div>
			</div>
			<div class="col-md-3">
				<div class="widget">
					<h5 class="widgetheading">{$lang.get_in_touch_with_us}</h5>
					<address>
					<strong>{$smarty.const.STORE_NAME}</strong><br>
					 {$smarty.const.STORE_ADDRESS_STREET}<br>
					 {$smarty.const.STORE_ADDRESS_POSTCODE} {$smarty.const.STORE_ADDRESS_CITY} </address>
					<p>
						<i class="icon-phone"></i> {$smarty.const.STORE_ADDRESS_TELEPHONE_NUMBER} <br>
						<a href="{html_href_link content=$contents.contact_us}" title="{$lang.block_service_contact}"><i class="icon-envelope-alt"></i> {$smarty.const.STORE_ADDRESS_EMAIL}</a>
					</p>
				</div>
			</div>
		</div>
	</div>
	<div id="sub-footer">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<div class="copyright">	
						<p>
						{*
							GERMAN:
							Diese Rueckverlinkung darf nur entfernt werden,
							wenn Sie eine MyOOS Lizenz besitzen.
							:: Lizenzbedingungen: 
							http://www.myoos.de/Projektbezogene-Gebuehr-p-38.html

							ENGLISH:
							This back linking maybe only removed,
							if you possess a MyOOS Lizenz license.
							:: License conditions: 
							http://www.myoos.de/Projektbezogene-Gebuehr-p-38.html
						*}		
							<span>Copyright &copy; 2003 - {$smarty.now|date_format:"%Y"}</span> <a href="{$smarty.const.OOS_HOME}" target="_blank">MyOOS [Shopsystem]</a> Alle Rechte vorbehalten. 
						</p>
					</div>
				</div>
				<div class="col-md-6">
					<ul class="social-network">
						<li><a href="#" data-toggle="tooltip" data-placement="bottom" title="Facebook"><i class="icon-facebook icon-square"></i></a></li>
						<li><a href="#" data-toggle="tooltip" data-placement="bottom" title="Twitter"><i class="icon-twitter icon-square"></i></a></li>
						<li><a href="#" data-toggle="tooltip" data-placement="bottom" title="Linkedin"><i class="icon-linkedin icon-square"></i></a></li>
						<li><a href="#" data-toggle="tooltip" data-placement="bottom" title="Pinterest"><i class="icon-pinterest icon-square"></i></a></li>
						<li><a href="#" data-toggle="tooltip" data-placement="bottom" title="Google plus"><i class="icon-google-plus icon-square"></i></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	</footer>
</div>
<a href="#" class="scrollup"><i class="icon-chevron-up icon-square icon-32 active"></i></a>
<!-- Placed at the end of the document so the pages load faster -->
<script src="{$theme}/js/bootstrap.min.js"></script>
<script src="{$theme}/js/jquery.nivo.slider.pack.js"></script>

<script src="{$theme}/js/application.js"></script>

</body>
</html>