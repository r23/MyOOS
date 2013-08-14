{if $smarty.session.selected_box eq catalog}
						<li class="current">
{else}
						<li>
{/if}
							<a href="{$heading_catalog}"><span class="cus-tag-blue-add"></span>{$lang.box_heading_catalog}</a>
							<ul>
								<li>{$categories}</li>
								<li>{$new_product}</li>
								<li>{$specials}</li>
								<li>{$products_expected}</li>
								<li>{$featured}</li>
								<li>{$products_attributes}</li>
								<li>{$products_status}</li>
								<li>{$products_units}</li>
								<li>{$xsell_products}</li>
								<li>{$up_sell_products}</li>
								<li>{$export_excel}</li>
								<li>{$import_excel}</li>
								<li>{$manufacturers}</li>
								<li>{$reviews}</li>							
{if $smarty.const.STOCK_CHECK eq true}								
								<li>{$quick_stockupdate}</li>
{/if}
							</ul>
						</li>
