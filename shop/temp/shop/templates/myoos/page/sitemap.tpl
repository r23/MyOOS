{include file="myoos/system/_header.tpl"}
	<!-- Wrapper -->
	<div class="wrapper">

    <section id="inner-headline">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="inner-heading">
                    <h2>{$heading_title}</h2>
                </div>
            </div>
            <div class="col-md-8">
                {$breadcrumb}
            </div>
        </div>
    </div>
    </section>
     <section id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="inner-heading">
                    <h2>{$sitemap}</h2>
                </div>
            </div>
            <div class="col-md-6">
              <ul>
<li><a href="{html_href_link content=$contents.main_shopping_cart}">{$lang.page_shopping_cart}</a></li>
<li><a href="{html_href_link content=$contents.checkout_shipping}">{$lang.page_checkout_shipping}</a></li>
<li><a href="{html_href_link content=$contents.advanced_search}">{$lang.page_advanced_search}</a></li>
<li><a href="{html_href_link content=$contents.products_new}">{$lang.page_products_new}</a></li>
<li><a href="{html_href_link content=$contents.specials}">{$lang.page_specials}</a></li>
{if $oEvent->installed_plugin('reviews')}
<li><a href="{html_href_link content=$contents.reviews_reviews}">{$lang.page_reviews}</a></li>
{/if}

                <li>{$lang.page_service}
                    <ul>
                    <li><a href="{html_href_link content=$contents.advanced_search}" title="{$lang.block_service_advanced_search}">{$lang.block_service_advanced_search}</a></li>
{if $oEvent->installed_plugin('reviews')}
                    <li><a href="{html_href_link content=$contents.reviews_reviews}" title="{$lang.block_service_reviews}">{$lang.block_service_reviews}</a></li>
{/if}
                    <li><a href="{html_href_link content=$contents.main_shopping_cart}" title="{$lang.block_service_shopping_cart}">{$lang.block_service_shopping_cart}</a></li>

                    <li><a href="{html_href_link content=$contents.info_sitemap}">{$lang.block_service_sitemap}</a></li>
                  </ul>
                </li>

                <li>{$lang.heading_information}
                  <ul>

{foreach item=info from=$information}
                    <li><a href="{html_href_link content=$contents.information information_id=$info.information_id}">{$info.information_name}</a></li>
{/foreach}
                    <li><a href="{html_href_link content=$contents.contact_us}">{$lang.block_service_contact}</a></li>
                  </ul>
                </li>
              </ul>

            </div>
        </div>
    </div>
    </section>
	
	</div> <!-- / .wrapper -->	
{include file="myoos/system/_footer.tpl"}