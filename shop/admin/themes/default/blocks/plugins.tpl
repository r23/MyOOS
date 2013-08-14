{if $smarty.session.selected_box eq plugins}
						<li class="current">
{else}
						<li>
{/if}
							<a href="{$heading_plugins}" class="no-submenu"><span class="cus-plugin"></span>{$lang.box_heading_plugins}</a>
						</li>