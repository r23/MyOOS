<?php
/* Smarty version 3.1.39, created on 2021-07-09 14:55:51
  from 'C:\xampp\htdocs\ent\MyOOS\myoos\templates\phoenix\canvas\_account.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_60e847576c33b4_95411312',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4c576b574bc1e7f3ee2181432f068c66890215bf' => 
    array (
      0 => 'C:\\xampp\\htdocs\\ent\\MyOOS\\myoos\\templates\\phoenix\\canvas\\_account.html',
      1 => 1625832921,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_60e847576c33b4_95411312 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\xampp\\htdocs\\ent\\MyOOS\\myoos\\includes\\lib\\smarty-plugins\\function.html_get_link.php','function'=>'smarty_function_html_get_link',),1=>array('file'=>'C:\\xampp\\htdocs\\ent\\MyOOS\\myoos\\includes\\lib\\smarty-plugins\\function.html_href_link.php','function'=>'smarty_function_html_href_link',),));
?>
<!-- Off-canvas account-->
<div class="offcanvas offcanvas-reverse" id="offcanvas-account">
	<div class="offcanvas-header d-flex justify-content-between align-items-center">
        <h3 class="offcanvas-title"><?php echo (defined('STORE_NAME') ? constant('STORE_NAME') : null);?>
</h3>
        <button class="close" type="button" data-dismiss="offcanvas" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	</div>
	<div class="offcanvas-body">
		<div class="offcanvas-body-inner">
			<ul class="nav nav-tabs nav-justified" role="tablist">
				<li class="nav-item"><a class="nav-link active" href="#signin" data-toggle="tab" role="tab"><i data-feather="log-in"></i>&nbsp;<?php echo $_smarty_tpl->tpl_vars['lang']->value['header_title_login'];?>
</a></li>
				<li class="nav-item"></li>
			</ul>
			<div class="tab-content pt-1">
				<div class="tab-pane fade show active" id="signin" role="tabpanel">
					<form name="login" action="<?php echo smarty_function_html_get_link(array(),$_smarty_tpl);?>
" method="POST" class="needs-validation" novalidate>
					<?php if ($_smarty_tpl->tpl_vars['mySystem']->value['sed']) {?>
						<input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['mySystem']->value['session_name'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['mySystem']->value['session_id'];?>
">
						<input type="hidden" name="formid" value="<?php echo $_smarty_tpl->tpl_vars['mySystem']->value['formid'];?>
">
					<?php }?>
						<input type="hidden" name="action" value="process">
						<input type="hidden" name="content" value="<?php echo $_smarty_tpl->tpl_vars['filename']->value['login'];?>
">			  

						<div class="form-group">
							<label class="sr-only" for="signin-email">Email</label>
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text" id="signin-email-icon"><i data-feather="mail"></i></span></div>
								<input class="form-control" type="email" name="email_address" id="signin-email" placeholder="<?php echo $_smarty_tpl->tpl_vars['lang']->value['entry_email_address'];?>
" aria-label="Email" aria-describedby="signin-email-icon" required>
								<div class="invalid-feedback"><?php echo $_smarty_tpl->tpl_vars['lang']->value['text_please_provide_email_address'];?>
.</div>
							</div>
						</div>
								
						<div class="form-group">
							<label class="sr-only" for="signin-password">Password</label>
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text" id="signin-password-icon"><i data-feather="lock"></i></span></div>
								<input class="form-control" name="password" type="password" id="signin-password" placeholder="<?php echo $_smarty_tpl->tpl_vars['lang']->value['entry_password'];?>
" aria-label="Password" aria-describedby="signin-password-icon" required>
								<div class="invalid-feedback"><?php echo $_smarty_tpl->tpl_vars['lang']->value['text_please_enter_a_password'];?>
.</div>
							</div>
						</div>
				
						<button class="btn btn-primary btn-block" type="submit"><?php echo $_smarty_tpl->tpl_vars['lang']->value['button_login'];?>
</button>
					</form>
					<div class="text-left pt-4">
						<p><?php echo $_smarty_tpl->tpl_vars['lang']->value['text_password_forgotten'];?>
 <a href="<?php echo smarty_function_html_href_link(array('content'=>$_smarty_tpl->tpl_vars['filename']->value['password_forgotten']),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['lang']->value['link_password_forgotten'];?>
</a></p>
					<div>	
				</div>
				
				<div class="tab-pane fade" id="signup" role="tabpanel">

				</div>
			</div>
			<div class="d-flex align-items-center pt-5">
				<hr class="w-100">
				<div class="px-3 w-100 text-nowrap font-weight-semibold"><?php echo $_smarty_tpl->tpl_vars['lang']->value['login_block_no_account_yet'];?>
</div>
				<hr class="w-100">
			</div>
			<div class="text-center pt-4">
				<a href="<?php echo smarty_function_html_href_link(array('content'=>$_smarty_tpl->tpl_vars['filename']->value['create_account']),$_smarty_tpl);?>
">
					<?php echo sprintf($_smarty_tpl->tpl_vars['lang']->value['login_block_book_now'],(defined('STORE_NAME') ? constant('STORE_NAME') : null));?>

					<i class="fa fa-arrow-right" aria-hidden="true"></i>
				</a>
			<div>
        </div>
	</div>
</div><?php }
}
