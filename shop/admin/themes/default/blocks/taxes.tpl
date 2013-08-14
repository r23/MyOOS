{if $smarty.session.selected_box eq taxes}
						<li class="current">
{else}
						<li>
{/if}
							<a href="{$heading_taxes}"><span class="cus-world-go"></span>{$lang.box_heading_location_and_taxes}</a>
							<ul>
								<li>{$countries}</li>
								<li>{$zones}</li>
								<li>{$geo_zones}</li>
								<li>{$tax_classes}</li>
								<li>{$tax_rates}</li>
							</ul>
						</li>