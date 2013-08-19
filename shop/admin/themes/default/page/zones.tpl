{include file="default/system/_header.tpl"}
		
		<!-- Main page container -->
		<div class="container-fluid">
		
			<!-- Navigation block toggle button -->
			<a href="#" class="nav-toggle" title="{$lang.collapse_menu}"><span class="icon-chevron-left"></span></a>
			
			<!-- Left (navigation) side -->
			{include file="default/system/_block.tpl"}
			<!-- /Left (navigation) side -->
			
			<!-- Right (content) side -->
			<section class="content-block" role="main">
				
				<!-- Breadcrumbs -->
				<ul class="breadcrumb">
					<li><a href="{$home}"><span class="icon-home"></span> Home</a></li>
					<li><a href="{$heading_taxes}">{$lang.box_heading_location_and_taxes}</a></li>
					<li class="active">{$lang.heading_title}</li>
				</ul>
				<!-- Breadcrumbs -->
				
				<!-- Page header -->
				<article class="page-header">
					<h1>{$lang.heading_title}</h1>
					<p class="lead"></p>
				</article>
				<!-- /Page header -->
				
				{$form_action}
				<input type="hidden" name="{$oos_session_name}" value="{$oos_session_id}">
				<input type="hidden" name="formid" value="{$formid}">
				
				<!-- Grid row -->
				<div class="row">
					<div class="col-6 col-lg-6">
					Anzeige
						<select name="pagination" onchange="submit()">
							
															<option value="20">20</option>
															<option value="50">50</option>
															<option value="100" selected="selected" >100</option>
															<option value="300">300</option>
													</select>
					</div>
					<div class="col-6 col-lg-6">
						<div class="data-header-actions">
							<ul>
								<li>
									<a class="btn btn-primary" href="{$new_zone}" title="{$lang.button_new_zone}">{$lang.button_new_zone}</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="row">					
					<!-- Data block -->
					<article class="col-lg-12 data-block">
						<div class="data-container">
								<table class="table table-striped table-bordered table-condensed table-hover table-media">
								<thead>
									<tr>
										<th><input type="checkbox" name="checkme" onclick="checkAll(this.form, 'zone[]', this.checked)" /></th>
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
										<th><input type="checkbox" name="checkme" onclick="checkAll(this.form, 'zone[]', this.checked)" /></th>
										<th>{$lang.table_heading_country_name}</th>
										<th>{$lang.table_heading_zone_name}</th>
										<th>{$lang.table_heading_zone_code}</th>
										<th class="span1">{$lang.table_heading_action}</th>
									</tr>
								</thead>
							</table>

						</div>
					</article>
					<!-- /Data block -->
					
				</div>
				
				<div class="row">
					<div class="col-6 col-lg-6">
					<p>{$display_count}</p>
			<select name='action'>
<option value='-1' selected='selected'>Aktion wählen</option>
	<option value='trash'>In den Papierkorb legen</option>
</select>
<input type="submit" name="" id="doaction" class="button action" value="Übernehmen"  />
						
					</div>
					<div class="col-6 col-lg-6"></div>
				</div>
				<!-- /Grid row -->
				
				<div class="row">				
					<div class="col-12 col-sm-8 col-lg-8">
						<div class="dataTables_paginate">{$display_links}</div>
					</div>
				</div>
				</form>					
				
			</section>
			<!-- /Right (content) side -->
	

			
		</div>
		<!-- /Main page container -->
		
		<!-- Scripts -->

{literal}
		<script>
			$(document).ready(function(){			
			
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