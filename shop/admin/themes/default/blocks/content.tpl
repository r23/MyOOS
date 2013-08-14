{if $smarty.session.selected_box eq content}
						<li class="current">
{else}
						<li>
{/if}
							<a href="{$heading_content}"><span class="cus-calendar-view-month"></span>{$lang.box_heading_content}</a>
							<ul>
								<li>{$content_block}</li>
								<li>{$content_page_type}</li>
							</ul>
						</li>