{if $smarty.session.selected_box eq configuration}
						<li class="current">
{else}
						<li>
{/if}
							<a href="{$heading_configuration}"><span class="cus-cog-go"></span>{$lang.box_heading_configuration}</a>
							<ul>
							{foreach $cfg_groups as $configuration}
								<li>{$configuration.link}</li>
							{/foreach}
							</ul>
						</li>