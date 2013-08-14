{if $smarty.session.selected_box eq customers}
						<li class="current">
{else}
						<li>
{/if}
							<a href="{$heading_customers}"><span class="cus-cart"></span>{$lang.box_heading_customers}</a>
							<ul>							
								<li>{$customers}</li>
								<li>{$orders}</li>
								<li>{$customers_status}</li>
								<li>{$orders_status}</li>
								<li>{$campaigns}</li>
								<li>{$manual_loging}</li>
							</ul>
						</li>