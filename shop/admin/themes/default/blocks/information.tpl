{if $smarty.session.selected_box eq information}
						<li class="current">
{else}
						<li>
{/if}
							<a href="{$heading_information}" class="no-submenu"><span class="cus-stop"></span>{$lang.box_heading_information}</a>
						</li>