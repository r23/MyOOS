{if $smarty.session.selected_box eq reports}
						<li class="current">
{else}
						<li>
{/if}
							<a href="{$heading_reports}"><span class="cus-report"></span>{$lang.box_heading_reports}</a>
							<ul>
								<li>{$stats_products_viewed}</li>
								<li>{$stats_products_purchased}</li>
								<li>{$stats_low_stock}</li>
								<li>{$stats_customers}</li>
								<li>{$stats_sales_report2}</li>
								<li>{$stats_recover_cart_sales}</li>
							</ul>
						</li>