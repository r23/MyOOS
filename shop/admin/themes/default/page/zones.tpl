{include file="default/system/_header.tpl"}
		
		<!-- Main page container -->
		<div class="container-fluid">
		
			<!-- Navigation block toggle button -->
			<a href="#" class="nav-toggle" title="{$lang.collapse_menu}"><span class="icon-chevron-left"></span></a>
			
			<!-- Left (navigation) side -->
			<section class="navigation-block">
{$navigation-block}			
			</section>
			<!-- /Left (navigation) side -->
			
			<!-- Right (content) side -->
			<section class="content-block" role="main">
				
				<!-- Breadcrumbs -->
				<ul class="breadcrumb">
					<li><a href="{$home}"><span class="icon-home"></span> Home</a></li>
					<li><a href="#">Chromatron template</a></li>
					<li class="active">{$lang.heading_title}</li>
				</ul>
				<!-- Breadcrumbs -->
				
				<!-- Page header -->
				<article class="page-header">
					<h1>{$lang.heading_title}</h1>
					<p class="lead"></p>
				</article>
				<!-- /Page header -->
				

				<!-- Grid row -->
				<div class="row">
						<div class="data-header-actions">
							<ul>
								<li><a class="btn btn-primary" href="{$new_zone}" title="{$lang.button_new_zone}">{$lang.button_new_zone}</a></li>
							</ul>
						</div>
					<!-- Data block -->
					<article class="col-lg-12 data-block">
						<div class="data-container">

							<section>
								<table class="table table-striped table-bordered table-condensed table-hover table-media">
								<thead>
									<tr>
										<th><input id="optionsCheckbox" type="checkbox" value="option1"></th>
										<th>{$lang.table_heading_country_name}</th>
										<th>{$lang.table_heading_zone_name}</th>
										<th>{$lang.table_heading_zone_code}</th>
										<th class="span1">{$lang.table_heading_action}</th>
									</tr>
								</thead>
								<tbody>
{foreach $zones as $zone}
									<tr>
									    <td>
											<label>
												<input type="checkbox" name="zone[]" value="{$zone.zone_id}" />
											</label>
										</td>
										<td>{$zone.countries_name}</td>
										<td>{$zone.zone_name}</td>
										<td>{$zone.zone_code}</td>
										<td>
											<div class="btn-group">
											    <a class="btn btn-success btn-small" href="{$zone.info_link}" title="{$lang.button_icon_info}"><span class="icon-eye-open"></span></a>
												<a class="btn btn-primary btn-small" href="{$zone.edit_link}" title="{$lang.button_edit}"><span class="icon-pencil"></span></a>
												<a class="btn btn-danger btn-small" href="{$zone.delete_link}" title="{$lang.button_delete}"><span class="icon-trash"></span></a>
											</div>
										</td>
									</tr>
{/foreach}
								</tbody>
								<thead>
									<tr>
										<th><input id="optionsCheckbox" type="checkbox" value="option1"></th>
										<th>{$lang.table_heading_country_name}</th>
										<th>{$lang.table_heading_zone_name}</th>
										<th>{$lang.table_heading_zone_code}</th>
										<th class="span1">{$lang.table_heading_action}</th>
									</tr>
								</thead>
							</table>
							</section>



						</div>
					</article>
					<!-- /Data block -->

				</div>
				<!-- /Grid row -->


				
				<div class="row">
					<div class="col-6 col-sm-4 col-lg-4">
						<div class="dataTables_info">
							<p>{$display_count}</p>
						</div>
					</div>
					<div class="col-12 col-sm-8 col-lg-8">
						<div class="dataTables_paginate">{$display_links}</div>
					</div>
				</div>
					
				
			</section>
			<!-- /Right (content) side -->

			

			
		</div>
		<!-- /Main page container -->
		
		<!-- Scripts -->

{literal}
		<script>
			$(document).ready(function(){
			
				$('table th input:checkbox').on('click' , function(){
					var that = this;
					$(this).closest('table').find('tr > td:first-child input:checkbox')
					.each(function(){
						this.checked = that.checked;
						$(this).closest('tr').toggleClass('selected');
					});
						
				});
	
			
				
				// Tooltips for brand & nav toggle button
				$('.nav-toggle, .brand').tooltip({
					placement: 'bottom',
					container: 'body'
				});

				// Tooltips
				$('[title]').tooltip({
					placement: 'top',
					container: 'body'
				});
				
				// Close button for widgets
				$('.widget').alert();
				
				// Remove tooltip when widget is closed
				$('.widget').bind('close', function () {
					$(this).find('.close').tooltip('destroy');
				})
				
			});
		</script>		
{/literal}

{include file="default/system/_footer.tpl"}