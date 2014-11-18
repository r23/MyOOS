{if count($new_products) > 1}
<h3>{$block_heading_new_products}</h3>
	<ul class="products-grid row">
	{foreach $new_products as $product}
		<li class="item span3">
			<a href="{product_info_link products_id=$product.id}" class="product-image">{small_product_image image=$product.image alt=$product.name|strip_tags}</a>
		
			<div class="product-shop">
				<div class="price-box">
		{if (!empty($product.special_price))}
					<p class="old-price"><span class="price">{$product.price}{$product.units}</span></p>
					<p class="special-price"><span class="price">{$product.special_price}{$product.units}</span></p>
		{else}
					<p class="regular-price"><span class="price">{$product.price}{$product.units}</span></p>
		{/if}
				</div>
				<h3 class="product-name"><a href="{product_info_link products_id=$product.id}" title="{$product.name}">{$product.name}</a></h3>
				<p class="desc_grid">{$product.description|truncate:120:" [...]":false|close_tags}</p>
				<div class="actions">
		{if $smarty.session.member->group.show_price eq 1 }
					<a class="btn btn-success" href="{html_href_link content=$content_file action=buy_now products_id=$product.id}"><i class="icon-shopping-cart icon-large"></i> {$lang.button_in_cart}</a>
		{/if}
					
				</div>
			</div>				
		</li>
	{/foreach}
	</ul>
{/if}
