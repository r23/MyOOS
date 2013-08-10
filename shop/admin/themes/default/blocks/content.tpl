{if $smarty.get.selected_box eq content}
						<li class="current">
{else}
						<li>
{/if}
							<a href="{$heading_content}"><span class="fam-picture"></span>{$lang.box_heading_content}</a>
							<ul>
								<li>{$content_block}</li>
								<li>{$content_page_type}</li>
							</ul>
						</li>