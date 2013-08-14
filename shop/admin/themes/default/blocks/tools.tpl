{if $smarty.session.selected_box eq tools}
						<li class="current">
{else}
						<li>
{/if}
							<a href="{$heading_tools}"><span class="cus-wrench"></span>{$lang.box_heading_tools}</a>
							<ul>
								<li>{$mail}</li>
								<li>{$newsletters}</li>
								<li>{$whos_online}</li>
								<li>{$recover_cart_sales}</li>
							</ul>
						</li>