{include file="default/system/_header.tpl"}

		<!-- Main login container -->
		<div class="login-container">
			
			<!-- Login page logo -->
			<h1><a class="brand" href="http://www.oos-shop.de/">MyOOS</a></h1>
			
			<section>
				
				<!-- alert -->
				<div class="alert alert-info alert-block fade in">
					<button class="close" data-dismiss="alert">&times;</button>
					{$lang.text_welcome}
				</div>		
{foreach item=error from=$oos_error_message}
				<div class="alert alert-error alert-block fade in">
					<button class="close" data-dismiss="alert">&times;</button>
					{$error.text}
				</div>
{/foreach}
				<!-- /alert -->
				
				<!-- Login form -->
				{$form_action}
					<input type="hidden" name="{$oos_session_name}" value="{$oos_session_id}">
					<input type="hidden" name="formid" value="{$formid}">
					<input type="hidden" name="action" value="process">
					<fieldset>
						<div class="control-group">
							<label class="control-label" for="email_address">{$lang.entry_email_address}</label>
							<div class="controls">
								<input id="icon" type="text" placeholder="{$lang.placeholder_email_address}" name="email_address">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="password">{$lang.entry_password}</label>
							<div class="controls">
								<input id="password" type="password" placeholder="{$lang.entry_password}" name="password">
							</div>
						</div>
						<div class="form-actions">
							<button class="btn btn-primary btn-alt" type="submit"><span class="icon-signin"></span> {$lang.button_login}</button>
						</div>
					</fieldset>
				</form>
				<!-- /Login form -->
				
			</section>
			
			<!-- Login page navigation -->
			<nav>
				<ul>
					<li><a href="{$password_forgotten}">{$lang.text_password_forgotten}</a></li>
					<li><a href="http://www.oos-shop.de/">{$lang.header_title_support_site}</a></li>
					<li><a href="{$catalog_link}">{$lang.header_title_online_catalog}</a></li>
				</ul>
			</nav>
			<!-- Login page navigation -->
			
		</div>
		<!-- /Main login container -->

{include file="default/system/_footer.tpl"}
