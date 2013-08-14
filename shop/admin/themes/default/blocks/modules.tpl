{if $smarty.session.selected_box eq modules}
						<li class="current">
{else}
						<li>
{/if}
							<a href="{$heading_modules}"><span class="cus-plugin-edit"></span>{$lang.box_heading_modules}</a>
							<ul>
								<li>{$payment}</li>
								<li>{$shipping}</li>
								<li>{$ordertotal}</li>								
							</ul>
						</li>