{if $smarty.session.selected_box eq administrator}
						<li class="current">
{else}
						<li>
{/if}
							<a href="{$heading_administrator}"><span class="cus-user-edit"></span>{$lang.box_heading_administrator}</a>
							<ul>
								<li>{$admin_members}</li>
								<li>{$admin_files}</li>
							</ul>
						</li>