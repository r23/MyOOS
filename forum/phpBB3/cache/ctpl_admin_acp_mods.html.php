<?php if (!defined('IN_PHPBB')) exit; $this->_tpl_include('overall_header.html'); ?>


<a name="maincontent"></a>

<?php if ($this->_rootref['S_STORE_WRITABLE_WARN']) {  ?>

	<p class="errorbox"><?php echo ((isset($this->_rootref['L_STORE_NOT_WRITABLE'])) ? $this->_rootref['L_STORE_NOT_WRITABLE'] : ((isset($user->lang['STORE_NOT_WRITABLE'])) ? $user->lang['STORE_NOT_WRITABLE'] : '{ STORE_NOT_WRITABLE }')); ?></p>
<?php } if ($this->_rootref['S_MODS_WRITABLE_WARN']) {  ?>

	<p class="errorbox"><?php echo ((isset($this->_rootref['L_MODS_NOT_WRITABLE'])) ? $this->_rootref['L_MODS_NOT_WRITABLE'] : ((isset($user->lang['MODS_NOT_WRITABLE'])) ? $user->lang['MODS_NOT_WRITABLE'] : '{ MODS_NOT_WRITABLE }')); ?></p>
<?php } if ($this->_rootref['S_FRONTEND']) {  ?>


	<h1><?php echo ((isset($this->_rootref['L_ACP_MODS'])) ? $this->_rootref['L_ACP_MODS'] : ((isset($user->lang['ACP_MODS'])) ? $user->lang['ACP_MODS'] : '{ ACP_MODS }')); ?></h1>

	<p><?php echo ((isset($this->_rootref['L_MODS_EXPLAIN'])) ? $this->_rootref['L_MODS_EXPLAIN'] : ((isset($user->lang['MODS_EXPLAIN'])) ? $user->lang['MODS_EXPLAIN'] : '{ MODS_EXPLAIN }')); ?></p>

	<table cellspacing="1">
		<col class="row1" /><col class="row2" /><col class="row2" /><col class="row2" />
	<thead>
	<tr>
		<th><?php echo ((isset($this->_rootref['L_NAME'])) ? $this->_rootref['L_NAME'] : ((isset($user->lang['NAME'])) ? $user->lang['NAME'] : '{ NAME }')); ?></th>
		<th><?php echo ((isset($this->_rootref['L_OPTIONS'])) ? $this->_rootref['L_OPTIONS'] : ((isset($user->lang['OPTIONS'])) ? $user->lang['OPTIONS'] : '{ OPTIONS }')); ?></th>
		<th><?php echo ((isset($this->_rootref['L_ACTIONS'])) ? $this->_rootref['L_ACTIONS'] : ((isset($user->lang['ACTIONS'])) ? $user->lang['ACTIONS'] : '{ ACTIONS }')); ?></th>
        <th><?php echo ((isset($this->_rootref['L_INSTALL_DATE'])) ? $this->_rootref['L_INSTALL_DATE'] : ((isset($user->lang['INSTALL_DATE'])) ? $user->lang['INSTALL_DATE'] : '{ INSTALL_DATE }')); ?></th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td class="row3" colspan="4"><strong><?php echo ((isset($this->_rootref['L_INSTALLED_MODS'])) ? $this->_rootref['L_INSTALLED_MODS'] : ((isset($user->lang['INSTALLED_MODS'])) ? $user->lang['INSTALLED_MODS'] : '{ INSTALLED_MODS }')); ?></strong></td>
	</tr>
	<?php if (! sizeof($this->_tpldata['installed'])) {  ?>

	<tr>
		<td class="row1" colspan="4" style="text-align: center;"><?php echo ((isset($this->_rootref['L_NO_INSTALLED_MODS'])) ? $this->_rootref['L_NO_INSTALLED_MODS'] : ((isset($user->lang['NO_INSTALLED_MODS'])) ? $user->lang['NO_INSTALLED_MODS'] : '{ NO_INSTALLED_MODS }')); ?></td>
	</tr>
	<?php } $_installed_count = (isset($this->_tpldata['installed'])) ? sizeof($this->_tpldata['installed']) : 0;if ($_installed_count) {for ($_installed_i = 0; $_installed_i < $_installed_count; ++$_installed_i){$_installed_val = &$this->_tpldata['installed'][$_installed_i]; ?>

	<tr>
		<td><strong><?php echo $_installed_val['MOD_NAME']; ?></strong></td>
		<td style="text-align: center;"><a href="<?php echo $_installed_val['U_DETAILS']; ?>"><?php echo ((isset($this->_rootref['L_DETAILS'])) ? $this->_rootref['L_DETAILS'] : ((isset($user->lang['DETAILS'])) ? $user->lang['DETAILS'] : '{ DETAILS }')); ?></a></td>
		<td style="text-align: center;"><a href="<?php echo $_installed_val['U_UNINSTALL']; ?>"><?php echo ((isset($this->_rootref['L_UNINSTALL'])) ? $this->_rootref['L_UNINSTALL'] : ((isset($user->lang['UNINSTALL'])) ? $user->lang['UNINSTALL'] : '{ UNINSTALL }')); ?></a></td>
        <td style="text-align: center;"><p><?php echo $_installed_val['MOD_TIME']; ?></p></td>
	</tr>
	<?php }} ?>

	</tbody>
	</table>
	<table cellspacing="1" style="margin-top: 5px;">
		<col class="row1" /><col class="row2" /><col class="row2" /><col class="row2" />
	<thead>
	<tr>
		<th><?php echo ((isset($this->_rootref['L_NAME'])) ? $this->_rootref['L_NAME'] : ((isset($user->lang['NAME'])) ? $user->lang['NAME'] : '{ NAME }')); ?></th>
		<th><?php echo ((isset($this->_rootref['L_TARGET_VERSION'])) ? $this->_rootref['L_TARGET_VERSION'] : ((isset($user->lang['TARGET_VERSION'])) ? $user->lang['TARGET_VERSION'] : '{ TARGET_VERSION }')); ?></th>
		<th><?php echo ((isset($this->_rootref['L_OPTIONS'])) ? $this->_rootref['L_OPTIONS'] : ((isset($user->lang['OPTIONS'])) ? $user->lang['OPTIONS'] : '{ OPTIONS }')); ?></th>
        <th><?php echo ((isset($this->_rootref['L_ACTIONS'])) ? $this->_rootref['L_ACTIONS'] : ((isset($user->lang['ACTIONS'])) ? $user->lang['ACTIONS'] : '{ ACTIONS }')); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php if (! sizeof($this->_tpldata['uninstalled'])) {  ?>

	<tr>
		<td class="row1" colspan="4" style="text-align: center;"><?php echo ((isset($this->_rootref['L_NO_UNINSTALLED_MODS'])) ? $this->_rootref['L_NO_UNINSTALLED_MODS'] : ((isset($user->lang['NO_UNINSTALLED_MODS'])) ? $user->lang['NO_UNINSTALLED_MODS'] : '{ NO_UNINSTALLED_MODS }')); ?></td>
	</tr>
	<?php } else { ?>

	<tr>
		<td class="row3" style="margin-top: 5px;" colspan="4"><strong><?php echo ((isset($this->_rootref['L_UNINSTALLED_MODS'])) ? $this->_rootref['L_UNINSTALLED_MODS'] : ((isset($user->lang['UNINSTALLED_MODS'])) ? $user->lang['UNINSTALLED_MODS'] : '{ UNINSTALLED_MODS }')); ?></strong></td>
	</tr>
	<?php } $_uninstalled_count = (isset($this->_tpldata['uninstalled'])) ? sizeof($this->_tpldata['uninstalled']) : 0;if ($_uninstalled_count) {for ($_uninstalled_i = 0; $_uninstalled_i < $_uninstalled_count; ++$_uninstalled_i){$_uninstalled_val = &$this->_tpldata['uninstalled'][$_uninstalled_i]; ?>

	<tr>
		<td><strong><?php echo $_uninstalled_val['MOD_NAME']; ?></strong></td>
		<td><?php if ($_uninstalled_val['S_PHPBB_VESION']) {  ?><span style="font-weight: bold; color: red;"><?php echo $_uninstalled_val['PHPBB_VERSION']; ?></span><?php } else { echo $_uninstalled_val['PHPBB_VERSION']; } ?></td>
		<td style="text-align: center;"><a href="<?php echo $_uninstalled_val['U_DETAILS']; ?>"><?php echo ((isset($this->_rootref['L_DETAILS'])) ? $this->_rootref['L_DETAILS'] : ((isset($user->lang['DETAILS'])) ? $user->lang['DETAILS'] : '{ DETAILS }')); ?></a></td>
		<td style="text-align: center;"><a href="<?php echo $_uninstalled_val['U_INSTALL']; ?>"><?php echo ((isset($this->_rootref['L_INSTALL'])) ? $this->_rootref['L_INSTALL'] : ((isset($user->lang['INSTALL'])) ? $user->lang['INSTALL'] : '{ INSTALL }')); ?></a>&nbsp;|&nbsp;<a href="<?php echo $_uninstalled_val['U_DELETE']; ?>"><?php echo ((isset($this->_rootref['L_DELETE'])) ? $this->_rootref['L_DELETE'] : ((isset($user->lang['DELETE'])) ? $user->lang['DELETE'] : '{ DELETE }')); ?></a></td>
	</tr>
	<?php }} ?>

	</tbody>
	</table>
    <?php if (sizeof($this->_tpldata['installed'])) {  ?>

	<form action="<?php echo (isset($this->_rootref['U_SORT_ACTION'])) ? $this->_rootref['U_SORT_ACTION'] : ''; ?>" method="post" id="list">
	<fieldset class="display-options">
	<?php echo ((isset($this->_rootref['L_SORT_BY'])) ? $this->_rootref['L_SORT_BY'] : ((isset($user->lang['SORT_BY'])) ? $user->lang['SORT_BY'] : '{ SORT_BY }')); ?>: <?php echo (isset($this->_rootref['S_SORT_KEY'])) ? $this->_rootref['S_SORT_KEY'] : ''; ?> <?php echo (isset($this->_rootref['S_SORT_DIR'])) ? $this->_rootref['S_SORT_DIR'] : ''; ?>

	<input class="button2" value="<?php echo ((isset($this->_rootref['L_GO'])) ? $this->_rootref['L_GO'] : ((isset($user->lang['GO'])) ? $user->lang['GO'] : '{ GO }')); ?>" name="sort" type="submit">
	</fieldset></form>
	<?php } if ($this->_rootref['S_MOD_UPLOAD']) {  ?>

	<br />

	<form action="<?php echo (isset($this->_rootref['U_UPLOAD'])) ? $this->_rootref['U_UPLOAD'] : ''; ?>" method="post" id="mod_upload"<?php echo (isset($this->_rootref['S_FORM_ENCTYPE'])) ? $this->_rootref['S_FORM_ENCTYPE'] : ''; ?>>
	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_MOD_UPLOAD'])) ? $this->_rootref['L_MOD_UPLOAD'] : ((isset($user->lang['MOD_UPLOAD'])) ? $user->lang['MOD_UPLOAD'] : '{ MOD_UPLOAD }')); ?></legend>
		<dl>
			<p><?php echo ((isset($this->_rootref['L_MOD_UPLOAD_EXPLAIN'])) ? $this->_rootref['L_MOD_UPLOAD_EXPLAIN'] : ((isset($user->lang['MOD_UPLOAD_EXPLAIN'])) ? $user->lang['MOD_UPLOAD_EXPLAIN'] : '{ MOD_UPLOAD_EXPLAIN }')); ?></p>
			<input type="file" name="modupload" id="modupload" value="" style="width:50%" />
			<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

			<?php echo (isset($this->_rootref['S_HIDDEN_FIELDS'])) ? $this->_rootref['S_HIDDEN_FIELDS'] : ''; ?>

			<br /><br /><input class="button1" type="submit" name="submit" value="<?php echo ((isset($this->_rootref['L_UPLOAD'])) ? $this->_rootref['L_UPLOAD'] : ((isset($user->lang['UPLOAD'])) ? $user->lang['UPLOAD'] : '{ UPLOAD }')); ?>" id="submit" />
		</dl>
		<?php if (sizeof($this->_tpldata['data'])) {  if ($this->_rootref['S_CONNECTION_SUCCESS']) {  ?>

		<div class="successbox">
			<p><?php echo ((isset($this->_rootref['L_CONNECTION_SUCCESS'])) ? $this->_rootref['L_CONNECTION_SUCCESS'] : ((isset($user->lang['CONNECTION_SUCCESS'])) ? $user->lang['CONNECTION_SUCCESS'] : '{ CONNECTION_SUCCESS }')); ?></p>
		</div>
		<?php } else if ($this->_rootref['S_CONNECTION_FAILED']) {  ?>

		<div class="errorbox">
			<p><?php echo ((isset($this->_rootref['L_CONNECTION_FAILED'])) ? $this->_rootref['L_CONNECTION_FAILED'] : ((isset($user->lang['CONNECTION_FAILED'])) ? $user->lang['CONNECTION_FAILED'] : '{ CONNECTION_FAILED }')); ?><br /><?php echo (isset($this->_rootref['ERROR_MSG'])) ? $this->_rootref['ERROR_MSG'] : ''; ?></p>
		</div>
		<?php } $this->_tpl_include('acp_mods_ftp.html'); ?>

		<dl>
			<input class="button1" type="submit" name="test_connection" value="<?php echo ((isset($this->_rootref['L_TEST_CONNECTION'])) ? $this->_rootref['L_TEST_CONNECTION'] : ((isset($user->lang['TEST_CONNECTION'])) ? $user->lang['TEST_CONNECTION'] : '{ TEST_CONNECTION }')); ?>" />
			<input class="button1" type="submit" name="submit" value="<?php echo ((isset($this->_rootref['L_UPLOAD'])) ? $this->_rootref['L_UPLOAD'] : ((isset($user->lang['UPLOAD'])) ? $user->lang['UPLOAD'] : '{ UPLOAD }')); ?>" id="submit" />
		</dl>
		<?php } ?>

	</fieldset>
	</form>
	<?php } } else if ($this->_rootref['S_MOD_DELETE']) {  ?>


	<form action="<?php echo (isset($this->_rootref['U_DELETE'])) ? $this->_rootref['U_DELETE'] : ''; ?>" method="post" id="mod_delete">
	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_DELETE'])) ? $this->_rootref['L_DELETE'] : ((isset($user->lang['DELETE'])) ? $user->lang['DELETE'] : '{ DELETE }')); ?></legend>
		<dl>
			<p><?php echo ((isset($this->_rootref['L_DELETE_CONFIRM'])) ? $this->_rootref['L_DELETE_CONFIRM'] : ((isset($user->lang['DELETE_CONFIRM'])) ? $user->lang['DELETE_CONFIRM'] : '{ DELETE_CONFIRM }')); ?></p>
			<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

			<?php echo (isset($this->_rootref['S_HIDDEN_FIELDS'])) ? $this->_rootref['S_HIDDEN_FIELDS'] : ''; ?>

			<br /><input type="submit" name="submit" value="<?php echo ((isset($this->_rootref['L_DELETE'])) ? $this->_rootref['L_DELETE'] : ((isset($user->lang['DELETE'])) ? $user->lang['DELETE'] : '{ DELETE }')); ?>" id="submit" class="button1" />
		</dl>
	<?php if (sizeof($this->_tpldata['data'])) {  if ($this->_rootref['S_CONNECTION_SUCCESS']) {  ?>

		<div class="successbox">
			<p><?php echo ((isset($this->_rootref['L_CONNECTION_SUCCESS'])) ? $this->_rootref['L_CONNECTION_SUCCESS'] : ((isset($user->lang['CONNECTION_SUCCESS'])) ? $user->lang['CONNECTION_SUCCESS'] : '{ CONNECTION_SUCCESS }')); ?></p>
		</div>
		<?php } else if ($this->_rootref['S_CONNECTION_FAILED']) {  ?>

		<div class="errorbox">
			<p><?php echo ((isset($this->_rootref['L_CONNECTION_FAILED'])) ? $this->_rootref['L_CONNECTION_FAILED'] : ((isset($user->lang['CONNECTION_FAILED'])) ? $user->lang['CONNECTION_FAILED'] : '{ CONNECTION_FAILED }')); ?><br /><?php echo (isset($this->_rootref['ERROR_MSG'])) ? $this->_rootref['ERROR_MSG'] : ''; ?></p>
		</div>
		<?php } $this->_tpl_include('acp_mods_ftp.html'); ?>

		<dl>
			<input class="button1" type="submit" name="test_connection" value="<?php echo ((isset($this->_rootref['L_TEST_CONNECTION'])) ? $this->_rootref['L_TEST_CONNECTION'] : ((isset($user->lang['TEST_CONNECTION'])) ? $user->lang['TEST_CONNECTION'] : '{ TEST_CONNECTION }')); ?>" />
			<input class="button1" type="submit" name="submit" value="<?php echo ((isset($this->_rootref['L_DELETE'])) ? $this->_rootref['L_DELETE'] : ((isset($user->lang['DELETE'])) ? $user->lang['DELETE'] : '{ DELETE }')); ?>" id="submit" />
		</dl>
	<?php } ?>

	</fieldset>
	</form>

<?php } else if ($this->_rootref['S_MOD_SUCCESSBOX']) {  ?>


	<div class="successbox">
		<p><?php echo (isset($this->_rootref['MESSAGE'])) ? $this->_rootref['MESSAGE'] : ''; ?></p>
		<br />
		<p><a href="<?php echo (isset($this->_rootref['U_RETURN'])) ? $this->_rootref['U_RETURN'] : ''; ?>"><?php echo ((isset($this->_rootref['L_RETURN_MODS'])) ? $this->_rootref['L_RETURN_MODS'] : ((isset($user->lang['RETURN_MODS'])) ? $user->lang['RETURN_MODS'] : '{ RETURN_MODS }')); ?></a></p>
	</div>

<?php } else if ($this->_rootref['S_DETAILS']) {  ?>


	<a href="<?php echo (isset($this->_rootref['U_BACK'])) ? $this->_rootref['U_BACK'] : ''; ?>" style="float: right">&laquo; <?php echo ((isset($this->_rootref['L_BACK'])) ? $this->_rootref['L_BACK'] : ((isset($user->lang['BACK'])) ? $user->lang['BACK'] : '{ BACK }')); ?></a>

	<h1><?php echo ((isset($this->_rootref['L_MOD_DETAILS'])) ? $this->_rootref['L_MOD_DETAILS'] : ((isset($user->lang['MOD_DETAILS'])) ? $user->lang['MOD_DETAILS'] : '{ MOD_DETAILS }')); ?></h1>

	<p><?php echo ((isset($this->_rootref['L_MOD_DETAILS_EXPLAIN'])) ? $this->_rootref['L_MOD_DETAILS_EXPLAIN'] : ((isset($user->lang['MOD_DETAILS_EXPLAIN'])) ? $user->lang['MOD_DETAILS_EXPLAIN'] : '{ MOD_DETAILS_EXPLAIN }')); ?></p>

	<?php if ($this->_rootref['S_PHPBB_VESION']) {  ?>

		<div class="errorbox">
			<p><?php echo (isset($this->_rootref['VERSION_WARNING'])) ? $this->_rootref['VERSION_WARNING'] : ''; ?></p>
		</div>
	<?php } ?>


	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_MOD_DETAILS'])) ? $this->_rootref['L_MOD_DETAILS'] : ((isset($user->lang['MOD_DETAILS'])) ? $user->lang['MOD_DETAILS'] : '{ MOD_DETAILS }')); ?></legend>
		<dl>
			<dt><label for="name"><?php echo ((isset($this->_rootref['L_NAME'])) ? $this->_rootref['L_NAME'] : ((isset($user->lang['NAME'])) ? $user->lang['NAME'] : '{ NAME }')); ?>:</label></dt>
			<dd><strong id="name"><?php echo (isset($this->_rootref['MOD_NAME'])) ? $this->_rootref['MOD_NAME'] : ''; ?></strong></dd>
		</dl>
		<dl>
			<dt><label for="version"><?php echo ((isset($this->_rootref['L_VERSION'])) ? $this->_rootref['L_VERSION'] : ((isset($user->lang['VERSION'])) ? $user->lang['VERSION'] : '{ VERSION }')); ?>:</label></dt>
			<dd><p id="version"><?php echo (isset($this->_rootref['MOD_VERSION'])) ? $this->_rootref['MOD_VERSION'] : ''; ?></p></dd>
		</dl>
		<dl>
			<dt><label for="path"><?php echo ((isset($this->_rootref['L_PATH'])) ? $this->_rootref['L_PATH'] : ((isset($user->lang['PATH'])) ? $user->lang['PATH'] : '{ PATH }')); ?>:</label></dt>
			<dd><p id="path"><?php echo (isset($this->_rootref['MOD_PATH'])) ? $this->_rootref['MOD_PATH'] : ''; ?></p></dd>
		</dl>
		<?php if ($this->_rootref['S_INSTALL_TIME']) {  ?>

		<dl>
			<dt><label for="install_time"><?php echo ((isset($this->_rootref['L_INSTALL_TIME'])) ? $this->_rootref['L_INSTALL_TIME'] : ((isset($user->lang['INSTALL_TIME'])) ? $user->lang['INSTALL_TIME'] : '{ INSTALL_TIME }')); ?>:</label></dt>
			<dd><p id="install_time"><?php echo (isset($this->_rootref['MOD_INSTALL_TIME'])) ? $this->_rootref['MOD_INSTALL_TIME'] : ''; ?></p></dd>
		</dl>
		<?php } ?>

		<dl>
			<dt><label for="description"><?php echo ((isset($this->_rootref['L_DESCRIPTION'])) ? $this->_rootref['L_DESCRIPTION'] : ((isset($user->lang['DESCRIPTION'])) ? $user->lang['DESCRIPTION'] : '{ DESCRIPTION }')); ?>:</label></dt>
			<dd><p id="description"><?php echo (isset($this->_rootref['MOD_DESCRIPTION'])) ? $this->_rootref['MOD_DESCRIPTION'] : ''; ?></p></dd>
		</dl>
	</fieldset>

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_AUTHOR_INFORMATION'])) ? $this->_rootref['L_AUTHOR_INFORMATION'] : ((isset($user->lang['AUTHOR_INFORMATION'])) ? $user->lang['AUTHOR_INFORMATION'] : '{ AUTHOR_INFORMATION }')); ?></legend>
		<?php $_author_list_count = (isset($this->_tpldata['author_list'])) ? sizeof($this->_tpldata['author_list']) : 0;if ($_author_list_count) {for ($_author_list_i = 0; $_author_list_i < $_author_list_count; ++$_author_list_i){$_author_list_val = &$this->_tpldata['author_list'][$_author_list_i]; ?>

		<dl>
			<dt><label for="author_name"><?php echo ((isset($this->_rootref['L_AUTHOR_NAME'])) ? $this->_rootref['L_AUTHOR_NAME'] : ((isset($user->lang['AUTHOR_NAME'])) ? $user->lang['AUTHOR_NAME'] : '{ AUTHOR_NAME }')); ?>:</label></dt>
			<dd><strong id="author_name"><?php echo $_author_list_val['AUTHOR_NAME']; ?></strong></dd>
		</dl>
		<?php if ($_author_list_val['AUTHOR_EMAIL']) {  ?>

		<dl>
			<dt><label for="author_email"><?php echo ((isset($this->_rootref['L_AUTHOR_EMAIL'])) ? $this->_rootref['L_AUTHOR_EMAIL'] : ((isset($user->lang['AUTHOR_EMAIL'])) ? $user->lang['AUTHOR_EMAIL'] : '{ AUTHOR_EMAIL }')); ?>:</label></dt>
			<dd><strong id="author_email"><a href="mailto:<?php echo $_author_list_val['AUTHOR_EMAIL']; ?>"><?php echo $_author_list_val['AUTHOR_EMAIL']; ?></a></strong></dd>
		</dl>
		<?php } if ($_author_list_val['AUTHOR_WEBSITE']) {  ?>

		<dl>
			<dt><label for="author_url"><?php echo ((isset($this->_rootref['L_AUTHOR_URL'])) ? $this->_rootref['L_AUTHOR_URL'] : ((isset($user->lang['AUTHOR_URL'])) ? $user->lang['AUTHOR_URL'] : '{ AUTHOR_URL }')); ?>:</label></dt>
			<dd><strong id="author_url"><a href="<?php echo $_author_list_val['AUTHOR_WEBSITE']; ?>"><?php echo $_author_list_val['AUTHOR_WEBSITE']; ?></a></strong></dd>
		</dl>
		<?php } }} ?>

	</fieldset>

	<?php if ($this->_rootref['S_AUTHOR_NOTES']) {  ?>

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_AUTHOR_NOTES'])) ? $this->_rootref['L_AUTHOR_NOTES'] : ((isset($user->lang['AUTHOR_NOTES'])) ? $user->lang['AUTHOR_NOTES'] : '{ AUTHOR_NOTES }')); ?></legend>
		<?php echo (isset($this->_rootref['AUTHOR_NOTES'])) ? $this->_rootref['AUTHOR_NOTES'] : ''; ?>

	</fieldset>
	<?php } if ($this->_rootref['S_DIY']) {  ?>

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_DIY_INSTRUCTIONS'])) ? $this->_rootref['L_DIY_INSTRUCTIONS'] : ((isset($user->lang['DIY_INSTRUCTIONS'])) ? $user->lang['DIY_INSTRUCTIONS'] : '{ DIY_INSTRUCTIONS }')); ?></legend>
		<?php $_diy_instructions_count = (isset($this->_tpldata['diy_instructions'])) ? sizeof($this->_tpldata['diy_instructions']) : 0;if ($_diy_instructions_count) {for ($_diy_instructions_i = 0; $_diy_instructions_i < $_diy_instructions_count; ++$_diy_instructions_i){$_diy_instructions_val = &$this->_tpldata['diy_instructions'][$_diy_instructions_i]; ?>

		<div><?php echo $_diy_instructions_val['DIY_INSTRUCTION']; ?></div>
		<?php }} ?>

	</fieldset>
	<?php } if ($this->_rootref['S_CONTRIB_AVAILABLE'] || $this->_rootref['S_UNKNOWN_LANGUAGES'] || $this->_rootref['S_UNKNOWN_TEMPLATES'] || ( sizeof($this->_tpldata['board_templates']) && sizeof($this->_tpldata['avail_templates']) )) {  ?>

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_ADDITIONAL_CHANGES'])) ? $this->_rootref['L_ADDITIONAL_CHANGES'] : ((isset($user->lang['ADDITIONAL_CHANGES'])) ? $user->lang['ADDITIONAL_CHANGES'] : '{ ADDITIONAL_CHANGES }')); ?></legend>
		<?php $_contrib_count = (isset($this->_tpldata['contrib'])) ? sizeof($this->_tpldata['contrib']) : 0;if ($_contrib_count) {for ($_contrib_i = 0; $_contrib_i < $_contrib_count; ++$_contrib_i){$_contrib_val = &$this->_tpldata['contrib'][$_contrib_i]; ?>

		<dl>
			<dt><label><?php echo ((isset($this->_rootref['L_MOD_NAME'])) ? $this->_rootref['L_MOD_NAME'] : ((isset($user->lang['MOD_NAME'])) ? $user->lang['MOD_NAME'] : '{ MOD_NAME }')); ?>:</label></dt>
			<dd><strong><?php echo $_contrib_val['MOD_NAME']; ?></strong> <?php if ($_contrib_val['U_INSTALL']) {  ?>(<a href="<?php echo $_contrib_val['U_INSTALL']; ?>"><?php echo ((isset($this->_rootref['L_INSTALL_MOD'])) ? $this->_rootref['L_INSTALL_MOD'] : ((isset($user->lang['INSTALL_MOD'])) ? $user->lang['INSTALL_MOD'] : '{ INSTALL_MOD }')); ?></a>)<?php } else if ($_contrib_val['U_UNINSTALL']) {  ?>(<a href="<?php echo $_contrib_val['U_UNINSTALL']; ?>"><?php echo ((isset($this->_rootref['L_UNINSTALL'])) ? $this->_rootref['L_UNINSTALL'] : ((isset($user->lang['UNINSTALL'])) ? $user->lang['UNINSTALL'] : '{ UNINSTALL }')); ?></a>)<?php } ?></dd>
		</dl>
		<?php }} $_unknown_templates_count = (isset($this->_tpldata['unknown_templates'])) ? sizeof($this->_tpldata['unknown_templates']) : 0;if ($_unknown_templates_count) {for ($_unknown_templates_i = 0; $_unknown_templates_i < $_unknown_templates_count; ++$_unknown_templates_i){$_unknown_templates_val = &$this->_tpldata['unknown_templates'][$_unknown_templates_i]; ?>

		<dl>
			<dt><label><?php echo ((isset($this->_rootref['L_STYLE_NAME'])) ? $this->_rootref['L_STYLE_NAME'] : ((isset($user->lang['STYLE_NAME'])) ? $user->lang['STYLE_NAME'] : '{ STYLE_NAME }')); ?>:</label></dt>
			<dd><strong><?php echo $_unknown_templates_val['TEMPLATE_NAME']; ?></strong> <?php if ($_unknown_templates_val['U_INSTALL']) {  ?>(<a href="<?php echo $_unknown_templates_val['U_INSTALL']; ?>"><?php echo ((isset($this->_rootref['L_INSTALL_MOD'])) ? $this->_rootref['L_INSTALL_MOD'] : ((isset($user->lang['INSTALL_MOD'])) ? $user->lang['INSTALL_MOD'] : '{ INSTALL_MOD }')); ?></a>)<?php } ?></dd>
		</dl>
		<?php }} $_unknown_lang_count = (isset($this->_tpldata['unknown_lang'])) ? sizeof($this->_tpldata['unknown_lang']) : 0;if ($_unknown_lang_count) {for ($_unknown_lang_i = 0; $_unknown_lang_i < $_unknown_lang_count; ++$_unknown_lang_i){$_unknown_lang_val = &$this->_tpldata['unknown_lang'][$_unknown_lang_i]; ?>

		<dl>
			<dt><label><?php echo ((isset($this->_rootref['L_LANGUAGE_NAME'])) ? $this->_rootref['L_LANGUAGE_NAME'] : ((isset($user->lang['LANGUAGE_NAME'])) ? $user->lang['LANGUAGE_NAME'] : '{ LANGUAGE_NAME }')); ?>:</label></dt>
			<dd><strong><?php echo $_unknown_lang_val['ENGLISH_NAME']; ?></strong> &ndash; <?php echo $_unknown_lang_val['LOCAL_NAME']; ?> <?php if ($_unknown_lang_val['U_INSTALL']) {  ?>(<a href="<?php echo $_unknown_lang_val['U_INSTALL']; ?>"><?php echo ((isset($this->_rootref['L_INSTALL_MOD'])) ? $this->_rootref['L_INSTALL_MOD'] : ((isset($user->lang['INSTALL_MOD'])) ? $user->lang['INSTALL_MOD'] : '{ INSTALL_MOD }')); ?></a>)<?php } else if ($_unknown_lang_val['U_UNINSTALL']) {  ?>(<a href="<?php echo $_unknown_lang_val['U_UNINSTALL']; ?>"><?php echo ((isset($this->_rootref['L_UNINSTALL'])) ? $this->_rootref['L_UNINSTALL'] : ((isset($user->lang['UNINSTALL'])) ? $user->lang['UNINSTALL'] : '{ UNINSTALL }')); ?></a>)<?php } ?></dd>
		</dl>
		<?php }} if (sizeof($this->_tpldata['board_templates']) && sizeof($this->_tpldata['avail_templates'])) {  ?>

		<form action="<?php echo (isset($this->_rootref['S_FORM_ACTION'])) ? $this->_rootref['S_FORM_ACTION'] : ''; ?>" method="post">
		<?php if (sizeof($this->_tpldata['unknown_templates']) || sizeof($this->_tpldata['unknown_lang']) || sizeof($this->_tpldata['contrib'])) {  ?>

		<br /><hr /><br />
		<?php } ?>

		<div><?php echo ((isset($this->_rootref['L_APPLY_THESE_CHANGES'])) ? $this->_rootref['L_APPLY_THESE_CHANGES'] : ((isset($user->lang['APPLY_THESE_CHANGES'])) ? $user->lang['APPLY_THESE_CHANGES'] : '{ APPLY_THESE_CHANGES }')); ?>

		<select name="source">
		<?php $_avail_templates_count = (isset($this->_tpldata['avail_templates'])) ? sizeof($this->_tpldata['avail_templates']) : 0;if ($_avail_templates_count) {for ($_avail_templates_i = 0; $_avail_templates_i < $_avail_templates_count; ++$_avail_templates_i){$_avail_templates_val = &$this->_tpldata['avail_templates'][$_avail_templates_i]; ?>

			<option value="<?php echo $_avail_templates_val['XML_FILE']; ?>"><?php echo $_avail_templates_val['TEMPLATE_NAME']; ?></option>
		<?php }} ?>

		</select>

		<?php echo ((isset($this->_rootref['L_APPLY_TEMPLATESET'])) ? $this->_rootref['L_APPLY_TEMPLATESET'] : ((isset($user->lang['APPLY_TEMPLATESET'])) ? $user->lang['APPLY_TEMPLATESET'] : '{ APPLY_TEMPLATESET }')); ?>

		<select name="dest">
		<?php $_board_templates_count = (isset($this->_tpldata['board_templates'])) ? sizeof($this->_tpldata['board_templates']) : 0;if ($_board_templates_count) {for ($_board_templates_i = 0; $_board_templates_i < $_board_templates_count; ++$_board_templates_i){$_board_templates_val = &$this->_tpldata['board_templates'][$_board_templates_i]; ?>

			<option value="<?php echo $_board_templates_val['TEMPLATE_NAME']; ?>"><?php echo $_board_templates_val['TEMPLATE_NAME']; ?></option>
		<?php }} ?>

		</select>
		<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

		<?php echo (isset($this->_rootref['S_HIDDEN_FIELDS'])) ? $this->_rootref['S_HIDDEN_FIELDS'] : ''; ?>

		<br />
		<input type="submit" name="template_submit" class="button1" value="<?php echo ((isset($this->_rootref['L_SUBMIT'])) ? $this->_rootref['L_SUBMIT'] : ((isset($user->lang['SUBMIT'])) ? $user->lang['SUBMIT'] : '{ SUBMIT }')); ?>" />
		</div>
		</form>
		<?php } ?>

	</fieldset>
	<?php } if ($this->_rootref['S_CHANGELOG']) {  ?>

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_MOD_CHANGELOG'])) ? $this->_rootref['L_MOD_CHANGELOG'] : ((isset($user->lang['MOD_CHANGELOG'])) ? $user->lang['MOD_CHANGELOG'] : '{ MOD_CHANGELOG }')); ?></legend>
		<?php $_changelog_count = (isset($this->_tpldata['changelog'])) ? sizeof($this->_tpldata['changelog']) : 0;if ($_changelog_count) {for ($_changelog_i = 0; $_changelog_i < $_changelog_count; ++$_changelog_i){$_changelog_val = &$this->_tpldata['changelog'][$_changelog_i]; ?>

		<dl>
			<dt><label for="change_date"><?php echo ((isset($this->_rootref['L_CHANGE_DATE'])) ? $this->_rootref['L_CHANGE_DATE'] : ((isset($user->lang['CHANGE_DATE'])) ? $user->lang['CHANGE_DATE'] : '{ CHANGE_DATE }')); ?></label></dt>
			<dt><strong id="change_date"><?php echo $_changelog_val['DATE']; ?></strong></dt>
		</dl>
		<dl>
			<dt><label for="change_version"><?php echo ((isset($this->_rootref['L_CHANGE_VERSION'])) ? $this->_rootref['L_CHANGE_VERSION'] : ((isset($user->lang['CHANGE_VERSION'])) ? $user->lang['CHANGE_VERSION'] : '{ CHANGE_VERSION }')); ?></label></dt>
			<dt><strong id="change_version"><?php echo $_changelog_val['VERSION']; ?></strong></dt>
		</dl>
		<dl>
			<dt><label for="changes"><?php echo ((isset($this->_rootref['L_CHANGES'])) ? $this->_rootref['L_CHANGES'] : ((isset($user->lang['CHANGES'])) ? $user->lang['CHANGES'] : '{ CHANGES }')); ?></label></dt>
			<dt id="changes">
				<ul>
					<?php $_changes_count = (isset($_changelog_val['changes'])) ? sizeof($_changelog_val['changes']) : 0;if ($_changes_count) {for ($_changes_i = 0; $_changes_i < $_changes_count; ++$_changes_i){$_changes_val = &$_changelog_val['changes'][$_changes_i]; ?>

					<li><?php echo $_changes_val['CHANGE']; ?></li>
					<?php }} ?>

				</ul>
			</dt>
		</dl>
		<?php }} ?>

	</fieldset>
	<?php } ?>


	<a href="<?php echo (isset($this->_rootref['U_BACK'])) ? $this->_rootref['U_BACK'] : ''; ?>" style="float: right">&laquo; <?php echo ((isset($this->_rootref['L_BACK'])) ? $this->_rootref['L_BACK'] : ((isset($user->lang['BACK'])) ? $user->lang['BACK'] : '{ BACK }')); ?></a>

<?php } else if ($this->_rootref['S_PRE_INSTALL']) {  ?>


	<a href="<?php echo (isset($this->_rootref['U_BACK'])) ? $this->_rootref['U_BACK'] : ''; ?>" style="float: right">&laquo; <?php echo ((isset($this->_rootref['L_BACK'])) ? $this->_rootref['L_BACK'] : ((isset($user->lang['BACK'])) ? $user->lang['BACK'] : '{ BACK }')); ?></a>

	<h1><?php echo ((isset($this->_rootref['L_PRE_INSTALL'])) ? $this->_rootref['L_PRE_INSTALL'] : ((isset($user->lang['PRE_INSTALL'])) ? $user->lang['PRE_INSTALL'] : '{ PRE_INSTALL }')); ?></h1>

	<p><?php echo ((isset($this->_rootref['L_PRE_INSTALL_EXPLAIN'])) ? $this->_rootref['L_PRE_INSTALL_EXPLAIN'] : ((isset($user->lang['PRE_INSTALL_EXPLAIN'])) ? $user->lang['PRE_INSTALL_EXPLAIN'] : '{ PRE_INSTALL_EXPLAIN }')); ?></p>

	<form id="acp_mods" method="post" action="<?php echo (isset($this->_rootref['U_INSTALL'])) ? $this->_rootref['U_INSTALL'] : ''; ?>">

	<fieldset class="submit-buttons">
		<legend><?php echo ((isset($this->_rootref['L_INSTALL'])) ? $this->_rootref['L_INSTALL'] : ((isset($user->lang['INSTALL'])) ? $user->lang['INSTALL'] : '{ INSTALL }')); ?></legend>
		<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

		<?php echo (isset($this->_rootref['S_HIDDEN_FIELDS'])) ? $this->_rootref['S_HIDDEN_FIELDS'] : ''; ?>

		<input type="hidden" name="mod_path" value="<?php echo (isset($this->_rootref['MOD_PATH'])) ? $this->_rootref['MOD_PATH'] : ''; ?>" />
		<?php if (sizeof($this->_tpldata['data'])) {  ?>

		<input class="button1" type="submit" name="test_connection" value="<?php echo ((isset($this->_rootref['L_TEST_CONNECTION'])) ? $this->_rootref['L_TEST_CONNECTION'] : ((isset($user->lang['TEST_CONNECTION'])) ? $user->lang['TEST_CONNECTION'] : '{ TEST_CONNECTION }')); ?>" />
		<?php } ?>

		<input class="button1" type="submit" name="install" value="<?php echo ((isset($this->_rootref['L_INSTALL'])) ? $this->_rootref['L_INSTALL'] : ((isset($user->lang['INSTALL'])) ? $user->lang['INSTALL'] : '{ INSTALL }')); ?>" />
	</fieldset>

	<?php if ($this->_rootref['S_PHPBB_VESION']) {  ?>

		<div class="errorbox">
			<p><?php echo (isset($this->_rootref['VERSION_WARNING'])) ? $this->_rootref['VERSION_WARNING'] : ''; ?></p>
		</div>
	<?php } if ($this->_rootref['S_AUTHOR_NOTES']) {  ?>

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_AUTHOR_NOTES'])) ? $this->_rootref['L_AUTHOR_NOTES'] : ((isset($user->lang['AUTHOR_NOTES'])) ? $user->lang['AUTHOR_NOTES'] : '{ AUTHOR_NOTES }')); ?></legend>
		<?php echo (isset($this->_rootref['AUTHOR_NOTES'])) ? $this->_rootref['AUTHOR_NOTES'] : ''; ?>

	</fieldset>
	<?php } if (sizeof($this->_tpldata['data'])) {  if ($this->_rootref['S_CONNECTION_SUCCESS']) {  ?>

		<div class="successbox">
			<p><?php echo ((isset($this->_rootref['L_CONNECTION_SUCCESS'])) ? $this->_rootref['L_CONNECTION_SUCCESS'] : ((isset($user->lang['CONNECTION_SUCCESS'])) ? $user->lang['CONNECTION_SUCCESS'] : '{ CONNECTION_SUCCESS }')); ?></p>
		</div>
	<?php } else if ($this->_rootref['S_CONNECTION_FAILED']) {  ?>

		<div class="errorbox">
			<p><?php echo ((isset($this->_rootref['L_CONNECTION_FAILED'])) ? $this->_rootref['L_CONNECTION_FAILED'] : ((isset($user->lang['CONNECTION_FAILED'])) ? $user->lang['CONNECTION_FAILED'] : '{ CONNECTION_FAILED }')); ?><br /><?php echo (isset($this->_rootref['ERROR_MSG'])) ? $this->_rootref['ERROR_MSG'] : ''; ?></p>
		</div>
	<?php } $this->_tpl_include('acp_mods_ftp.html'); } $this->_tpl_include('acp_mods_actions.html'); if ($this->_rootref['S_NEW_FILES'] || $this->_rootref['S_EDITS'] || $this->_rootref['S_SQL'] || $this->_rootref['S_AUTHOR_NOTES'] || sizeof($this->_tpldata['data'])) {  ?>

	<fieldset class="submit-buttons">
		<legend><?php echo ((isset($this->_rootref['L_INSTALL'])) ? $this->_rootref['L_INSTALL'] : ((isset($user->lang['INSTALL'])) ? $user->lang['INSTALL'] : '{ INSTALL }')); ?></legend>
		<input type="hidden" name="mod_path" value="<?php echo (isset($this->_rootref['MOD_PATH'])) ? $this->_rootref['MOD_PATH'] : ''; ?>" />
		<input class="button1" type="submit" name="install" value="<?php echo ((isset($this->_rootref['L_INSTALL'])) ? $this->_rootref['L_INSTALL'] : ((isset($user->lang['INSTALL'])) ? $user->lang['INSTALL'] : '{ INSTALL }')); ?>" />
	</fieldset>
	<?php } ?>

	</form>

	<br />

	<a href="<?php echo (isset($this->_rootref['U_BACK'])) ? $this->_rootref['U_BACK'] : ''; ?>" style="float: right">&laquo; <?php echo ((isset($this->_rootref['L_BACK'])) ? $this->_rootref['L_BACK'] : ((isset($user->lang['BACK'])) ? $user->lang['BACK'] : '{ BACK }')); ?></a>

<?php } else if ($this->_rootref['S_INSTALL'] || $this->_rootref['S_UNINSTALL']) {  if (! $this->_rootref['S_ERROR']) {  if ($this->_rootref['S_UNINSTALL']) {  ?>

	<h1><?php echo ((isset($this->_rootref['L_UNINSTALLED'])) ? $this->_rootref['L_UNINSTALLED'] : ((isset($user->lang['UNINSTALLED'])) ? $user->lang['UNINSTALLED'] : '{ UNINSTALLED }')); ?></h1>

	<p><?php echo ((isset($this->_rootref['L_UNINSTALLED_EXPLAIN'])) ? $this->_rootref['L_UNINSTALLED_EXPLAIN'] : ((isset($user->lang['UNINSTALLED_EXPLAIN'])) ? $user->lang['UNINSTALLED_EXPLAIN'] : '{ UNINSTALLED_EXPLAIN }')); ?></p>
	<?php } else { ?>

	<h1><?php echo ((isset($this->_rootref['L_INSTALLED'])) ? $this->_rootref['L_INSTALLED'] : ((isset($user->lang['INSTALLED'])) ? $user->lang['INSTALLED'] : '{ INSTALLED }')); ?></h1>

	<p><?php echo ((isset($this->_rootref['L_INSTALLED_EXPLAIN'])) ? $this->_rootref['L_INSTALLED_EXPLAIN'] : ((isset($user->lang['INSTALLED_EXPLAIN'])) ? $user->lang['INSTALLED_EXPLAIN'] : '{ INSTALLED_EXPLAIN }')); ?></p>
	<?php } } if ($this->_rootref['S_MANUAL_INSTRUCTIONS']) {  ?><p><?php echo ((isset($this->_rootref['L_AM_MANUAL_INSTRUCTIONS'])) ? $this->_rootref['L_AM_MANUAL_INSTRUCTIONS'] : ((isset($user->lang['AM_MANUAL_INSTRUCTIONS'])) ? $user->lang['AM_MANUAL_INSTRUCTIONS'] : '{ AM_MANUAL_INSTRUCTIONS }')); ?></p><?php } if ($this->_rootref['S_ERROR'] || sizeof($this->_tpldata['error'])) {  ?>

	<div class="errorbox">
		<p><?php echo ((isset($this->_rootref['L_INSTALL_ERROR'])) ? $this->_rootref['L_INSTALL_ERROR'] : ((isset($user->lang['INSTALL_ERROR'])) ? $user->lang['INSTALL_ERROR'] : '{ INSTALL_ERROR }')); ?></p>

		<?php $_error_count = (isset($this->_tpldata['error'])) ? sizeof($this->_tpldata['error']) : 0;if ($_error_count) {for ($_error_i = 0; $_error_i < $_error_count; ++$_error_i){$_error_val = &$this->_tpldata['error'][$_error_i]; ?>

		<span><?php echo $_error_val['ERROR']; ?></span><br />
		<?php }} ?>

		<br />

		<form id="acp_mods_err" method="post" action="<?php echo (isset($this->_rootref['U_RETRY'])) ? $this->_rootref['U_RETRY'] : ''; ?>">
		<fieldset class="submit-buttons">
			<legend><?php echo ((isset($this->_rootref['L_RETRY'])) ? $this->_rootref['L_RETRY'] : ((isset($user->lang['RETRY'])) ? $user->lang['RETRY'] : '{ RETRY }')); ?></legend>
			<input class="button1" type="submit" name="retry" value="<?php echo ((isset($this->_rootref['L_RETRY'])) ? $this->_rootref['L_RETRY'] : ((isset($user->lang['RETRY'])) ? $user->lang['RETRY'] : '{ RETRY }')); ?>" />
			<input class="button2" type="submit" name="force" value="<?php echo ((isset($this->_rootref['L_FORCE_INSTALL'])) ? $this->_rootref['L_FORCE_INSTALL'] : ((isset($user->lang['FORCE_INSTALL'])) ? $user->lang['FORCE_INSTALL'] : '{ FORCE_INSTALL }')); ?>" onclick="javascript:return confirm('<?php echo ((isset($this->_rootref['LA_FORCE_CONFIRM'])) ? $this->_rootref['LA_FORCE_CONFIRM'] : ((isset($this->_rootref['L_FORCE_CONFIRM'])) ? addslashes($this->_rootref['L_FORCE_CONFIRM']) : ((isset($user->lang['FORCE_CONFIRM'])) ? addslashes($user->lang['FORCE_CONFIRM']) : '{ FORCE_CONFIRM }'))); ?>'); " />
			<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

			<?php echo (isset($this->_rootref['S_HIDDEN_FIELDS'])) ? $this->_rootref['S_HIDDEN_FIELDS'] : ''; ?>

		</fieldset>
		</form>
	</div>
	<?php } else { ?>

	<div class="successbox">
		<p><?php if ($this->_rootref['S_INSTALL']) {  echo ((isset($this->_rootref['L_INSTALLED'])) ? $this->_rootref['L_INSTALLED'] : ((isset($user->lang['INSTALLED'])) ? $user->lang['INSTALLED'] : '{ INSTALLED }')); } else { echo ((isset($this->_rootref['L_UNINSTALLED'])) ? $this->_rootref['L_UNINSTALLED'] : ((isset($user->lang['UNINSTALLED'])) ? $user->lang['UNINSTALLED'] : '{ UNINSTALLED }')); } ?></p>

		<?php if ($this->_rootref['S_FORCE']) {  ?>

		<p><strong><?php echo ((isset($this->_rootref['L_INSTALL_FORCED'])) ? $this->_rootref['L_INSTALL_FORCED'] : ((isset($user->lang['INSTALL_FORCED'])) ? $user->lang['INSTALL_FORCED'] : '{ INSTALL_FORCED }')); ?></strong></p>
		<?php } if ($this->_rootref['U_PHP_INSTALLER']) {  ?>

		<p class="errorbox notice"><a href="<?php echo (isset($this->_rootref['U_PHP_INSTALLER'])) ? $this->_rootref['U_PHP_INSTALLER'] : ''; ?>"><?php echo ((isset($this->_rootref['L_GO_PHP_INSTALLER'])) ? $this->_rootref['L_GO_PHP_INSTALLER'] : ((isset($user->lang['GO_PHP_INSTALLER'])) ? $user->lang['GO_PHP_INSTALLER'] : '{ GO_PHP_INSTALLER }')); ?></a></p>
		<?php } ?>


		<p><a href="<?php echo (isset($this->_rootref['U_RETURN'])) ? $this->_rootref['U_RETURN'] : ''; ?>"><?php echo ((isset($this->_rootref['L_RETURN_MODS'])) ? $this->_rootref['L_RETURN_MODS'] : ((isset($user->lang['RETURN_MODS'])) ? $user->lang['RETURN_MODS'] : '{ RETURN_MODS }')); ?></a></p>
	</div>
	<?php } if ($this->_rootref['S_DIY'] && ( ! $this->_rootref['S_ERROR'] || $this->_rootref['S_FORCE'] )) {  ?>

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_DIY_INSTRUCTIONS'])) ? $this->_rootref['L_DIY_INSTRUCTIONS'] : ((isset($user->lang['DIY_INSTRUCTIONS'])) ? $user->lang['DIY_INSTRUCTIONS'] : '{ DIY_INSTRUCTIONS }')); ?></legend>
		<?php $_diy_instructions_count = (isset($this->_tpldata['diy_instructions'])) ? sizeof($this->_tpldata['diy_instructions']) : 0;if ($_diy_instructions_count) {for ($_diy_instructions_i = 0; $_diy_instructions_i < $_diy_instructions_count; ++$_diy_instructions_i){$_diy_instructions_val = &$this->_tpldata['diy_instructions'][$_diy_instructions_i]; ?>

		<div><?php echo $_diy_instructions_val['DIY_INSTRUCTION']; ?></div>
		<?php }} ?>

	</fieldset>
	<?php } $this->_tpl_include('acp_mods_actions.html'); ?>


	<form id="acp_mods" method="post" action="<?php echo (isset($this->_rootref['U_RETURN'])) ? $this->_rootref['U_RETURN'] : ''; ?>">
	<fieldset class="submit-buttons">
		<legend><?php echo ((isset($this->_rootref['L_RETURN_MODS'])) ? $this->_rootref['L_RETURN_MODS'] : ((isset($user->lang['RETURN_MODS'])) ? $user->lang['RETURN_MODS'] : '{ RETURN_MODS }')); ?></legend>
		<input class="button1" type="submit" name="return" value="<?php echo ((isset($this->_rootref['L_RETURN_MODS'])) ? $this->_rootref['L_RETURN_MODS'] : ((isset($user->lang['RETURN_MODS'])) ? $user->lang['RETURN_MODS'] : '{ RETURN_MODS }')); ?>" />
	</fieldset>
	</form>

<?php } else if ($this->_rootref['S_PRE_UNINSTALL']) {  ?>


	<a href="<?php echo (isset($this->_rootref['U_BACK'])) ? $this->_rootref['U_BACK'] : ''; ?>" style="float: right">&laquo; <?php echo ((isset($this->_rootref['L_BACK'])) ? $this->_rootref['L_BACK'] : ((isset($user->lang['BACK'])) ? $user->lang['BACK'] : '{ BACK }')); ?></a>

	<h1><?php echo ((isset($this->_rootref['L_PRE_UNINSTALL'])) ? $this->_rootref['L_PRE_UNINSTALL'] : ((isset($user->lang['PRE_UNINSTALL'])) ? $user->lang['PRE_UNINSTALL'] : '{ PRE_UNINSTALL }')); ?></h1>

	<p><?php echo ((isset($this->_rootref['L_PRE_UNINSTALL_EXPLAIN'])) ? $this->_rootref['L_PRE_UNINSTALL_EXPLAIN'] : ((isset($user->lang['PRE_UNINSTALL_EXPLAIN'])) ? $user->lang['PRE_UNINSTALL_EXPLAIN'] : '{ PRE_UNINSTALL_EXPLAIN }')); ?></p>

	<form id="acp_mods" method="post" action="<?php echo (isset($this->_rootref['U_UNINSTALL'])) ? $this->_rootref['U_UNINSTALL'] : ''; ?>">
	<fieldset class="submit-buttons">
		<legend><?php echo ((isset($this->_rootref['L_UNINSTALL'])) ? $this->_rootref['L_UNINSTALL'] : ((isset($user->lang['UNINSTALL'])) ? $user->lang['UNINSTALL'] : '{ UNINSTALL }')); ?></legend>
		<input type="hidden" name="mod_id" value="<?php echo (isset($this->_rootref['MOD_ID'])) ? $this->_rootref['MOD_ID'] : ''; ?>" />
		<?php if (sizeof($this->_tpldata['data'])) {  ?>

		<input class="button1" type="submit" name="test_connection" value="<?php echo ((isset($this->_rootref['L_TEST_CONNECTION'])) ? $this->_rootref['L_TEST_CONNECTION'] : ((isset($user->lang['TEST_CONNECTION'])) ? $user->lang['TEST_CONNECTION'] : '{ TEST_CONNECTION }')); ?>" />
		<?php } ?>

		<input class="button1" type="submit" name="uninstall" value="<?php echo ((isset($this->_rootref['L_UNINSTALL'])) ? $this->_rootref['L_UNINSTALL'] : ((isset($user->lang['UNINSTALL'])) ? $user->lang['UNINSTALL'] : '{ UNINSTALL }')); ?>" />
		<?php echo (isset($this->_rootref['S_HIDDEN_FIELDS'])) ? $this->_rootref['S_HIDDEN_FIELDS'] : ''; ?>

	</fieldset>

	<?php if ($this->_rootref['S_AUTHOR_NOTES']) {  ?>

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_AUTHOR_NOTES'])) ? $this->_rootref['L_AUTHOR_NOTES'] : ((isset($user->lang['AUTHOR_NOTES'])) ? $user->lang['AUTHOR_NOTES'] : '{ AUTHOR_NOTES }')); ?></legend>
		<?php echo (isset($this->_rootref['AUTHOR_NOTES'])) ? $this->_rootref['AUTHOR_NOTES'] : ''; ?>

	</fieldset>
	<?php } if (sizeof($this->_tpldata['data'])) {  if ($this->_rootref['S_CONNECTION_SUCCESS']) {  ?>

		<div class="successbox">
			<p><?php echo ((isset($this->_rootref['L_CONNECTION_SUCCESS'])) ? $this->_rootref['L_CONNECTION_SUCCESS'] : ((isset($user->lang['CONNECTION_SUCCESS'])) ? $user->lang['CONNECTION_SUCCESS'] : '{ CONNECTION_SUCCESS }')); ?></p>
		</div>
	<?php } else if ($this->_rootref['S_CONNECTION_FAILED']) {  ?>

		<div class="errorbox">
			<p><?php echo ((isset($this->_rootref['L_CONNECTION_FAILED'])) ? $this->_rootref['L_CONNECTION_FAILED'] : ((isset($user->lang['CONNECTION_FAILED'])) ? $user->lang['CONNECTION_FAILED'] : '{ CONNECTION_FAILED }')); ?><br /><?php echo (isset($this->_rootref['ERROR_MSG'])) ? $this->_rootref['ERROR_MSG'] : ''; ?></p>
		</div>
	<?php } $this->_tpl_include('acp_mods_ftp.html'); } $this->_tpl_include('acp_mods_actions.html'); if ($this->_rootref['S_REMOVING_FILES'] || $this->_rootref['S_EDITS'] || $this->_rootref['S_SQL'] || $this->_rootref['S_AUTHOR_NOTES'] || sizeof($this->_tpldata['data'])) {  ?>

	<fieldset class="submit-buttons">
		<legend><?php echo ((isset($this->_rootref['L_UNINSTALL'])) ? $this->_rootref['L_UNINSTALL'] : ((isset($user->lang['UNINSTALL'])) ? $user->lang['UNINSTALL'] : '{ UNINSTALL }')); ?></legend>
		<?php if (sizeof($this->_tpldata['data'])) {  ?>

		<input class="button1" type="submit" name="test_connection" value="<?php echo ((isset($this->_rootref['L_TEST_CONNECTION'])) ? $this->_rootref['L_TEST_CONNECTION'] : ((isset($user->lang['TEST_CONNECTION'])) ? $user->lang['TEST_CONNECTION'] : '{ TEST_CONNECTION }')); ?>" />
		<?php } ?>

		<input type="hidden" name="mod_id" value="<?php echo (isset($this->_rootref['MOD_ID'])) ? $this->_rootref['MOD_ID'] : ''; ?>" />
		<input class="button1" type="submit" name="uninstall" value="<?php echo ((isset($this->_rootref['L_UNINSTALL'])) ? $this->_rootref['L_UNINSTALL'] : ((isset($user->lang['UNINSTALL'])) ? $user->lang['UNINSTALL'] : '{ UNINSTALL }')); ?>" />
	</fieldset>
	<?php } ?>

	</form>

	<br />

	<a href="<?php echo (isset($this->_rootref['U_BACK'])) ? $this->_rootref['U_BACK'] : ''; ?>" style="float: right">&laquo; <?php echo ((isset($this->_rootref['L_BACK'])) ? $this->_rootref['L_BACK'] : ((isset($user->lang['BACK'])) ? $user->lang['BACK'] : '{ BACK }')); ?></a>

<?php } if ($this->_rootref['S_CONFIG']) {  ?>

	<h1><?php echo ((isset($this->_rootref['L_ACP_AUTOMOD_CONFIG'])) ? $this->_rootref['L_ACP_AUTOMOD_CONFIG'] : ((isset($user->lang['ACP_AUTOMOD_CONFIG'])) ? $user->lang['ACP_AUTOMOD_CONFIG'] : '{ ACP_AUTOMOD_CONFIG }')); ?></h1>

	<?php if ($this->_rootref['ERROR']) {  ?>

	<div class="errorbox">
		<p><?php echo (isset($this->_rootref['ERROR'])) ? $this->_rootref['ERROR'] : ''; ?></p>
	</div>
	<?php } ?>


	<p><?php echo ((isset($this->_rootref['L_MODS_CONFIG_EXPLAIN'])) ? $this->_rootref['L_MODS_CONFIG_EXPLAIN'] : ((isset($user->lang['MODS_CONFIG_EXPLAIN'])) ? $user->lang['MODS_CONFIG_EXPLAIN'] : '{ MODS_CONFIG_EXPLAIN }')); ?></p>
	<form action="<?php echo (isset($this->_rootref['U_CONFIG'])) ? $this->_rootref['U_CONFIG'] : ''; ?>" method="post" id="automod_config">
	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_VERSION'])) ? $this->_rootref['L_VERSION'] : ((isset($user->lang['VERSION'])) ? $user->lang['VERSION'] : '{ VERSION }')); ?></legend>
		<dl>
			<dt><?php echo ((isset($this->_rootref['L_AUTOMOD_VERSION'])) ? $this->_rootref['L_AUTOMOD_VERSION'] : ((isset($user->lang['AUTOMOD_VERSION'])) ? $user->lang['AUTOMOD_VERSION'] : '{ AUTOMOD_VERSION }')); ?></dt>
			<dd><?php echo (isset($this->_rootref['AUTOMOD_VERSION'])) ? $this->_rootref['AUTOMOD_VERSION'] : ''; ?></dd>
		</dl>
	</fieldset>

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_MOD_CONFIG'])) ? $this->_rootref['L_MOD_CONFIG'] : ((isset($user->lang['MOD_CONFIG'])) ? $user->lang['MOD_CONFIG'] : '{ MOD_CONFIG }')); ?></legend>
		<dl>
			<dt><label for="write_method"><?php echo ((isset($this->_rootref['L_WRITE_METHOD'])) ? $this->_rootref['L_WRITE_METHOD'] : ((isset($user->lang['WRITE_METHOD'])) ? $user->lang['WRITE_METHOD'] : '{ WRITE_METHOD }')); ?>:</label><br /><span><?php echo ((isset($this->_rootref['L_WRITE_METHOD_EXPLAIN'])) ? $this->_rootref['L_WRITE_METHOD_EXPLAIN'] : ((isset($user->lang['WRITE_METHOD_EXPLAIN'])) ? $user->lang['WRITE_METHOD_EXPLAIN'] : '{ WRITE_METHOD_EXPLAIN }')); ?></span></dt>
			<dd><label><input type="radio" class="radio" name="write_method" value="<?php echo (isset($this->_rootref['WRITE_METHOD_DIRECT'])) ? $this->_rootref['WRITE_METHOD_DIRECT'] : ''; ?>"<?php echo (isset($this->_rootref['WRITE_DIRECT'])) ? $this->_rootref['WRITE_DIRECT'] : ''; ?> onclick="dE('ftp_details', -1, 'block');" /> <?php echo ((isset($this->_rootref['L_WRITE_METHOD_DIRECT'])) ? $this->_rootref['L_WRITE_METHOD_DIRECT'] : ((isset($user->lang['WRITE_METHOD_DIRECT'])) ? $user->lang['WRITE_METHOD_DIRECT'] : '{ WRITE_METHOD_DIRECT }')); ?></label>
				<label><input type="radio" class="radio" name="write_method" value="<?php echo (isset($this->_rootref['WRITE_METHOD_FTP'])) ? $this->_rootref['WRITE_METHOD_FTP'] : ''; ?>"<?php echo (isset($this->_rootref['WRITE_FTP'])) ? $this->_rootref['WRITE_FTP'] : ''; ?> onclick="dE('ftp_details', 1, 'block');" /> <?php echo ((isset($this->_rootref['L_WRITE_METHOD_FTP'])) ? $this->_rootref['L_WRITE_METHOD_FTP'] : ((isset($user->lang['WRITE_METHOD_FTP'])) ? $user->lang['WRITE_METHOD_FTP'] : '{ WRITE_METHOD_FTP }')); ?></label>
				<label><input type="radio" class="radio" name="write_method" value="<?php echo (isset($this->_rootref['WRITE_METHOD_MANUAL'])) ? $this->_rootref['WRITE_METHOD_MANUAL'] : ''; ?>"<?php echo (isset($this->_rootref['WRITE_MANUAL'])) ? $this->_rootref['WRITE_MANUAL'] : ''; ?> onclick="dE('ftp_details', -1, 'block');" /> <?php echo ((isset($this->_rootref['L_WRITE_METHOD_MANUAL'])) ? $this->_rootref['L_WRITE_METHOD_MANUAL'] : ((isset($user->lang['WRITE_METHOD_MANUAL'])) ? $user->lang['WRITE_METHOD_MANUAL'] : '{ WRITE_METHOD_MANUAL }')); ?></label>
			</dd>
		</dl>
		<dl>
			<dt><label for="compress_method"><?php echo ((isset($this->_rootref['L_FILE_TYPE'])) ? $this->_rootref['L_FILE_TYPE'] : ((isset($user->lang['FILE_TYPE'])) ? $user->lang['FILE_TYPE'] : '{ FILE_TYPE }')); ?>:</label><br /><span><?php echo ((isset($this->_rootref['L_FILE_TYPE_EXPLAIN'])) ? $this->_rootref['L_FILE_TYPE_EXPLAIN'] : ((isset($user->lang['FILE_TYPE_EXPLAIN'])) ? $user->lang['FILE_TYPE_EXPLAIN'] : '{ FILE_TYPE_EXPLAIN }')); ?></span></dt>
			<dd><?php $_compress_count = (isset($this->_tpldata['compress'])) ? sizeof($this->_tpldata['compress']) : 0;if ($_compress_count) {for ($_compress_i = 0; $_compress_i < $_compress_count; ++$_compress_i){$_compress_val = &$this->_tpldata['compress'][$_compress_i]; ?><label><input type="radio" class="radio" name="compress_method"<?php if ($_compress_val['METHOD'] == $this->_rootref['COMPRESS_METHOD']) {  ?> id="method" checked="checked"<?php } ?> value="<?php echo $_compress_val['METHOD']; ?>" /> <?php echo $_compress_val['METHOD']; ?></label><?php }} ?></dd>
		</dl>
		<dl>
			<dt><label for="file_perms"><?php echo ((isset($this->_rootref['L_FILE_PERMS'])) ? $this->_rootref['L_FILE_PERMS'] : ((isset($user->lang['FILE_PERMS'])) ? $user->lang['FILE_PERMS'] : '{ FILE_PERMS }')); ?>:</label><br /><span><?php echo ((isset($this->_rootref['L_FILE_PERMS_EXPLAIN'])) ? $this->_rootref['L_FILE_PERMS_EXPLAIN'] : ((isset($user->lang['FILE_PERMS_EXPLAIN'])) ? $user->lang['FILE_PERMS_EXPLAIN'] : '{ FILE_PERMS_EXPLAIN }')); ?></span></dt>
			<dd><input type="text" class="input" name="file_perms" value="<?php echo (isset($this->_rootref['FILE_PERMS'])) ? $this->_rootref['FILE_PERMS'] : ''; ?>" /></dd>
		</dl>
		<dl>
			<dt><label for="file_perms"><?php echo ((isset($this->_rootref['L_DIR_PERMS'])) ? $this->_rootref['L_DIR_PERMS'] : ((isset($user->lang['DIR_PERMS'])) ? $user->lang['DIR_PERMS'] : '{ DIR_PERMS }')); ?>:</label><br /><span><?php echo ((isset($this->_rootref['L_DIR_PERMS_EXPLAIN'])) ? $this->_rootref['L_DIR_PERMS_EXPLAIN'] : ((isset($user->lang['DIR_PERMS_EXPLAIN'])) ? $user->lang['DIR_PERMS_EXPLAIN'] : '{ DIR_PERMS_EXPLAIN }')); ?></span></dt>
			<dd><input type="text" class="input" name="dir_perms" value="<?php echo (isset($this->_rootref['DIR_PERMS'])) ? $this->_rootref['DIR_PERMS'] : ''; ?>" /></dd>
		</dl>
		<dl>
			<dt><label for="preview_changes"><?php echo ((isset($this->_rootref['L_PREVIEW_CHANGES'])) ? $this->_rootref['L_PREVIEW_CHANGES'] : ((isset($user->lang['PREVIEW_CHANGES'])) ? $user->lang['PREVIEW_CHANGES'] : '{ PREVIEW_CHANGES }')); ?>:</label><br /><span><?php echo ((isset($this->_rootref['L_PREVIEW_CHANGES_EXPLAIN'])) ? $this->_rootref['L_PREVIEW_CHANGES_EXPLAIN'] : ((isset($user->lang['PREVIEW_CHANGES_EXPLAIN'])) ? $user->lang['PREVIEW_CHANGES_EXPLAIN'] : '{ PREVIEW_CHANGES_EXPLAIN }')); ?></span></dt>
			<dd><label><input type="radio" class="radio" name="preview_changes" value="1"<?php echo (isset($this->_rootref['PREVIEW_CHANGES_YES'])) ? $this->_rootref['PREVIEW_CHANGES_YES'] : ''; ?> /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label>
				<label><input type="radio" class="radio" name="preview_changes" value="0"<?php echo (isset($this->_rootref['PREVIEW_CHANGES_NO'])) ? $this->_rootref['PREVIEW_CHANGES_NO'] : ''; ?> /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label>
			</dd>
		</dl>
	</fieldset>

	<?php $this->_tpl_include('acp_mods_ftp.html'); ?>


	<fieldset class="submit-buttons">
		<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

		<input type="submit" name="submit" value="<?php echo ((isset($this->_rootref['L_SUBMIT'])) ? $this->_rootref['L_SUBMIT'] : ((isset($user->lang['SUBMIT'])) ? $user->lang['SUBMIT'] : '{ SUBMIT }')); ?>" id="submit" class="button1" />
	</fieldset>
	</form>
<?php } $this->_tpl_include('overall_footer.html'); ?>