{if $smarty.get.selected_box eq gv_admin}
						<li class="current">
{else}
						<li>
{/if}
							<a href="{$heading_coupon_admin}"><span class="fam-picture"></span>{$lang.box_heading_gv_admin}</a>
							<ul>
								<li>{$coupon_admin}</li>
								<li>{$gv_queue}</li>
								<li>{$gv_mail}</li>
								<li>{$gv_sent}</li>								
							</ul>
						</li>