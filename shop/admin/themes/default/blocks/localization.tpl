{if $smarty.get.selected_box eq localization}
						<li class="current">
{else}
						<li>
{/if}
							<a href="{$heading_currencies}"><span class="fam-picture"></span>{$lang.box_heading_localization}</a>
							<ul>
								<li>{$currencies}</li>
								<li>{$languages}</li>
							</ul>
						</li>