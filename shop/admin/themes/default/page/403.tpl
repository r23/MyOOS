{include file="default/system/_header.tpl"}
	
		<!-- Error page container -->
		<section class="error-container">
		
			<h1>403</h1>
			<p class="description">{$lang.heading_title}</p>
			<p>{$lang.text_main} {mailto address="{$smarty.const.STORE_OWNER_EMAIL_ADDRESS}" encode="javascript"}.</p>
			<a href="{$catalog_link}" class="btn btn-alt btn-primary btn-large" title="{$lang.text_back}">{$lang.text_back}</a>
		
		</section>
		<!-- /Error page container -->
		
		<!-- Bootstrap scripts -->
{literal}
		<script>
			$(document).ready(function(){
				
				// Tooltips
				$('[title]').tooltip({
					placement: 'top'
				});
				
			});
		</script>
{/literal}		
{include file="default/system/_footer.tpl"}