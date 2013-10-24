<?php if (!defined('IN_PHPBB')) exit; $this->_tpl_include('install_header.html'); ?>


<script type="text/javascript">
// <![CDATA[
	function popup(url, width, height, name)
	{
		if (!name)
		{
			name = '_popup';
		}

		window.open(url.replace(/&amp;/g, '&'), name, 'height=' + height + ',resizable=yes,scrollbars=yes, width=' + width);
		return false;
	}

	function diff_popup(url)
	{
		popup(url, 950, 600, '_diff');
		return false;
	}
// ]]>
</script>

<?php if ($this->_rootref['S_ERROR']) {  ?>

	<div class="errorbox" style="margin-top: 0;">
		<h3><?php echo ((isset($this->_rootref['L_NOTICE'])) ? $this->_rootref['L_NOTICE'] : ((isset($user->lang['NOTICE'])) ? $user->lang['NOTICE'] : '{ NOTICE }')); ?></h3>
		<p><?php echo (isset($this->_rootref['ERROR_MSG'])) ? $this->_rootref['ERROR_MSG'] : ''; ?></p>
	</div>
<?php } if ($this->_rootref['S_IN_PROGRESS']) {  ?>


	<div class="successbox" style="margin-top: 0;">
		<h3><?php echo ((isset($this->_rootref['L_IN_PROGRESS'])) ? $this->_rootref['L_IN_PROGRESS'] : ((isset($user->lang['IN_PROGRESS'])) ? $user->lang['IN_PROGRESS'] : '{ IN_PROGRESS }')); ?></h3>
		<p><?php echo ((isset($this->_rootref['L_IN_PROGRESS_EXPLAIN'])) ? $this->_rootref['L_IN_PROGRESS_EXPLAIN'] : ((isset($user->lang['IN_PROGRESS_EXPLAIN'])) ? $user->lang['IN_PROGRESS_EXPLAIN'] : '{ IN_PROGRESS_EXPLAIN }')); ?></p>
	</div>

<?php } else if ($this->_rootref['S_INTRO']) {  if ($this->_rootref['S_WARNING']) {  ?>

	<div class="successbox" style="margin-top: 0;">
		<h3><?php echo ((isset($this->_rootref['L_NOTICE'])) ? $this->_rootref['L_NOTICE'] : ((isset($user->lang['NOTICE'])) ? $user->lang['NOTICE'] : '{ NOTICE }')); ?></h3>
		<p><?php echo (isset($this->_rootref['WARNING_MSG'])) ? $this->_rootref['WARNING_MSG'] : ''; ?></p>
	</div>
	<?php } ?>

	
	<div class="errorbox" style="margin-top: 0;">
		<h3><?php echo ((isset($this->_rootref['L_NOTICE'])) ? $this->_rootref['L_NOTICE'] : ((isset($user->lang['NOTICE'])) ? $user->lang['NOTICE'] : '{ NOTICE }')); ?></h3>
		<p><?php echo ((isset($this->_rootref['L_BACKUP_NOTICE'])) ? $this->_rootref['L_BACKUP_NOTICE'] : ((isset($user->lang['BACKUP_NOTICE'])) ? $user->lang['BACKUP_NOTICE'] : '{ BACKUP_NOTICE }')); ?></p>
	</div>

	<form id="install_update" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">

	<h1><?php echo ((isset($this->_rootref['L_UPDATE_INSTALLATION'])) ? $this->_rootref['L_UPDATE_INSTALLATION'] : ((isset($user->lang['UPDATE_INSTALLATION'])) ? $user->lang['UPDATE_INSTALLATION'] : '{ UPDATE_INSTALLATION }')); ?></h1>
	<p><?php echo ((isset($this->_rootref['L_UPDATE_INSTALLATION_EXPLAIN'])) ? $this->_rootref['L_UPDATE_INSTALLATION_EXPLAIN'] : ((isset($user->lang['UPDATE_INSTALLATION_EXPLAIN'])) ? $user->lang['UPDATE_INSTALLATION_EXPLAIN'] : '{ UPDATE_INSTALLATION_EXPLAIN }')); ?></p>

	<fieldset class="submit-buttons">
		<input class="button1" type="submit" name="submit" value="<?php echo ((isset($this->_rootref['L_NEXT_STEP'])) ? $this->_rootref['L_NEXT_STEP'] : ((isset($user->lang['NEXT_STEP'])) ? $user->lang['NEXT_STEP'] : '{ NEXT_STEP }')); ?>" />
	</fieldset>

	</form>

<?php } else if ($this->_rootref['S_UPLOAD_SUCCESS']) {  ?>


	<form id="install_update" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">

	<h1><?php echo ((isset($this->_rootref['L_UPDATE_SUCCESS'])) ? $this->_rootref['L_UPDATE_SUCCESS'] : ((isset($user->lang['UPDATE_SUCCESS'])) ? $user->lang['UPDATE_SUCCESS'] : '{ UPDATE_SUCCESS }')); ?></h1>
	<p><?php echo ((isset($this->_rootref['L_UPDATE_SUCCESS_EXPLAIN'])) ? $this->_rootref['L_UPDATE_SUCCESS_EXPLAIN'] : ((isset($user->lang['UPDATE_SUCCESS_EXPLAIN'])) ? $user->lang['UPDATE_SUCCESS_EXPLAIN'] : '{ UPDATE_SUCCESS_EXPLAIN }')); ?></p>

	<fieldset class="submit-buttons">
		<input class="button1" type="submit" name="check_again" value="<?php echo ((isset($this->_rootref['L_CONTINUE_UPDATE'])) ? $this->_rootref['L_CONTINUE_UPDATE'] : ((isset($user->lang['CONTINUE_UPDATE'])) ? $user->lang['CONTINUE_UPDATE'] : '{ CONTINUE_UPDATE }')); ?>" />
	</fieldset>

	</form>

<?php } if ($this->_rootref['S_VERSION_CHECK']) {  ?>


	<h1><?php echo ((isset($this->_rootref['L_VERSION_CHECK'])) ? $this->_rootref['L_VERSION_CHECK'] : ((isset($user->lang['VERSION_CHECK'])) ? $user->lang['VERSION_CHECK'] : '{ VERSION_CHECK }')); ?></h1>

	<p><?php echo ((isset($this->_rootref['L_VERSION_CHECK_EXPLAIN'])) ? $this->_rootref['L_VERSION_CHECK_EXPLAIN'] : ((isset($user->lang['VERSION_CHECK_EXPLAIN'])) ? $user->lang['VERSION_CHECK_EXPLAIN'] : '{ VERSION_CHECK_EXPLAIN }')); ?></p>

	<?php if ($this->_rootref['S_UP_TO_DATE']) {  ?>

		<div class="successbox">
			<p><?php echo ((isset($this->_rootref['L_VERSION_UP_TO_DATE'])) ? $this->_rootref['L_VERSION_UP_TO_DATE'] : ((isset($user->lang['VERSION_UP_TO_DATE'])) ? $user->lang['VERSION_UP_TO_DATE'] : '{ VERSION_UP_TO_DATE }')); ?></p>
		</div>
	<?php } else { ?>

		<div class="errorbox">
			<p><?php echo ((isset($this->_rootref['L_VERSION_NOT_UP_TO_DATE'])) ? $this->_rootref['L_VERSION_NOT_UP_TO_DATE'] : ((isset($user->lang['VERSION_NOT_UP_TO_DATE'])) ? $user->lang['VERSION_NOT_UP_TO_DATE'] : '{ VERSION_NOT_UP_TO_DATE }')); ?></p>
		</div>
	<?php } ?>


	<fieldset>
		<legend></legend>
	<dl>
		<dt><label><?php echo ((isset($this->_rootref['L_CURRENT_VERSION'])) ? $this->_rootref['L_CURRENT_VERSION'] : ((isset($user->lang['CURRENT_VERSION'])) ? $user->lang['CURRENT_VERSION'] : '{ CURRENT_VERSION }')); ?></label></dt>
		<dd><strong><?php echo (isset($this->_rootref['CURRENT_VERSION'])) ? $this->_rootref['CURRENT_VERSION'] : ''; ?></strong></dd>
	</dl>
	<dl>
		<dt><label><?php echo ((isset($this->_rootref['L_LATEST_VERSION'])) ? $this->_rootref['L_LATEST_VERSION'] : ((isset($user->lang['LATEST_VERSION'])) ? $user->lang['LATEST_VERSION'] : '{ LATEST_VERSION }')); ?></label></dt>
		<dd><strong><?php echo (isset($this->_rootref['LATEST_VERSION'])) ? $this->_rootref['LATEST_VERSION'] : ''; ?></strong></dd>
	</dl>
	<?php if ($this->_rootref['PACKAGE_VERSION'] && ! $this->_rootref['S_UP_TO_DATE']) {  ?>

	<dl>
		<dt><label><?php echo ((isset($this->_rootref['L_PACKAGE_UPDATES_TO'])) ? $this->_rootref['L_PACKAGE_UPDATES_TO'] : ((isset($user->lang['PACKAGE_UPDATES_TO'])) ? $user->lang['PACKAGE_UPDATES_TO'] : '{ PACKAGE_UPDATES_TO }')); ?></label></dt>
		<dd><strong><?php echo (isset($this->_rootref['PACKAGE_VERSION'])) ? $this->_rootref['PACKAGE_VERSION'] : ''; ?></strong></dd>
	</dl>
	<?php } ?>

	</fieldset>

	<?php if (! $this->_rootref['S_UP_TO_DATE']) {  ?>


		<form id="install_dbupdate" method="post" action="<?php echo (isset($this->_rootref['U_DB_UPDATE_ACTION'])) ? $this->_rootref['U_DB_UPDATE_ACTION'] : ''; ?>">

		<fieldset class="submit-buttons">
			<p><?php echo ((isset($this->_rootref['L_UPDATE_DATABASE_EXPLAIN'])) ? $this->_rootref['L_UPDATE_DATABASE_EXPLAIN'] : ((isset($user->lang['UPDATE_DATABASE_EXPLAIN'])) ? $user->lang['UPDATE_DATABASE_EXPLAIN'] : '{ UPDATE_DATABASE_EXPLAIN }')); ?></p>
			<input class="button1" type="submit" name="db_update" value="<?php echo ((isset($this->_rootref['L_UPDATE_DATABASE'])) ? $this->_rootref['L_UPDATE_DATABASE'] : ((isset($user->lang['UPDATE_DATABASE'])) ? $user->lang['UPDATE_DATABASE'] : '{ UPDATE_DATABASE }')); ?>" />
		</fieldset>

		</form>

	<?php } else { ?>

		<form id="install_update" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">

		<fieldset class="submit-buttons">
			<p><?php echo ((isset($this->_rootref['L_CHECK_FILES_UP_TO_DATE'])) ? $this->_rootref['L_CHECK_FILES_UP_TO_DATE'] : ((isset($user->lang['CHECK_FILES_UP_TO_DATE'])) ? $user->lang['CHECK_FILES_UP_TO_DATE'] : '{ CHECK_FILES_UP_TO_DATE }')); ?></p>
			<input class="button1" type="submit" name="submit" value="<?php echo ((isset($this->_rootref['L_CHECK_FILES'])) ? $this->_rootref['L_CHECK_FILES'] : ((isset($user->lang['CHECK_FILES'])) ? $user->lang['CHECK_FILES'] : '{ CHECK_FILES }')); ?>" />
		</fieldset>

		</form>
	<?php } } else if ($this->_rootref['S_DB_UPDATE']) {  if (! $this->_rootref['S_DB_UPDATE_FINISHED']) {  ?>


		<h1><?php echo ((isset($this->_rootref['L_PERFORM_DATABASE_UPDATE'])) ? $this->_rootref['L_PERFORM_DATABASE_UPDATE'] : ((isset($user->lang['PERFORM_DATABASE_UPDATE'])) ? $user->lang['PERFORM_DATABASE_UPDATE'] : '{ PERFORM_DATABASE_UPDATE }')); ?></h1>

		<p>
			<?php echo ((isset($this->_rootref['L_PERFORM_DATABASE_UPDATE_EXPLAIN'])) ? $this->_rootref['L_PERFORM_DATABASE_UPDATE_EXPLAIN'] : ((isset($user->lang['PERFORM_DATABASE_UPDATE_EXPLAIN'])) ? $user->lang['PERFORM_DATABASE_UPDATE_EXPLAIN'] : '{ PERFORM_DATABASE_UPDATE_EXPLAIN }')); ?><br />
		</p>

		<br /><br />

		<form id="install_dbupdate" method="post" action="<?php echo (isset($this->_rootref['U_DB_UPDATE_ACTION'])) ? $this->_rootref['U_DB_UPDATE_ACTION'] : ''; ?>">

		<fieldset class="submit-buttons">
			<a href="<?php echo (isset($this->_rootref['U_DB_UPDATE'])) ? $this->_rootref['U_DB_UPDATE'] : ''; ?>" class="button1"><?php echo ((isset($this->_rootref['L_RUN_DATABASE_SCRIPT'])) ? $this->_rootref['L_RUN_DATABASE_SCRIPT'] : ((isset($user->lang['RUN_DATABASE_SCRIPT'])) ? $user->lang['RUN_DATABASE_SCRIPT'] : '{ RUN_DATABASE_SCRIPT }')); ?></a>

			<!-- input class="button1" type="submit" name="db_update" value="<?php echo ((isset($this->_rootref['L_CHECK_UPDATE_DATABASE'])) ? $this->_rootref['L_CHECK_UPDATE_DATABASE'] : ((isset($user->lang['CHECK_UPDATE_DATABASE'])) ? $user->lang['CHECK_UPDATE_DATABASE'] : '{ CHECK_UPDATE_DATABASE }')); ?>" / -->
		</fieldset>

		</form>

	<?php } else { ?>


		<h1><?php echo ((isset($this->_rootref['L_UPDATE_DB_SUCCESS'])) ? $this->_rootref['L_UPDATE_DB_SUCCESS'] : ((isset($user->lang['UPDATE_DB_SUCCESS'])) ? $user->lang['UPDATE_DB_SUCCESS'] : '{ UPDATE_DB_SUCCESS }')); ?></h1>

		<br /><br />

		<form id="install_update" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">

		<fieldset class="submit-buttons">
			<p><?php echo ((isset($this->_rootref['L_CHECK_FILES_EXPLAIN'])) ? $this->_rootref['L_CHECK_FILES_EXPLAIN'] : ((isset($user->lang['CHECK_FILES_EXPLAIN'])) ? $user->lang['CHECK_FILES_EXPLAIN'] : '{ CHECK_FILES_EXPLAIN }')); ?></p>
			<input class="button1" type="submit" name="submit" value="<?php echo ((isset($this->_rootref['L_CHECK_FILES'])) ? $this->_rootref['L_CHECK_FILES'] : ((isset($user->lang['CHECK_FILES'])) ? $user->lang['CHECK_FILES'] : '{ CHECK_FILES }')); ?>" />
		</fieldset>

		</form>

	<?php } } else if ($this->_rootref['S_FILE_CHECK']) {  if ($this->_rootref['S_ALL_UP_TO_DATE']) {  ?>


		<div class="successbox">
			<h3><?php echo ((isset($this->_rootref['L_UPDATE_SUCCESS'])) ? $this->_rootref['L_UPDATE_SUCCESS'] : ((isset($user->lang['UPDATE_SUCCESS'])) ? $user->lang['UPDATE_SUCCESS'] : '{ UPDATE_SUCCESS }')); ?></h3>
			<p><?php echo ((isset($this->_rootref['L_ALL_FILES_UP_TO_DATE'])) ? $this->_rootref['L_ALL_FILES_UP_TO_DATE'] : ((isset($user->lang['ALL_FILES_UP_TO_DATE'])) ? $user->lang['ALL_FILES_UP_TO_DATE'] : '{ ALL_FILES_UP_TO_DATE }')); ?></p>
		</div>

	<?php } else { ?>

		<h1><?php echo ((isset($this->_rootref['L_COLLECTED_INFORMATION'])) ? $this->_rootref['L_COLLECTED_INFORMATION'] : ((isset($user->lang['COLLECTED_INFORMATION'])) ? $user->lang['COLLECTED_INFORMATION'] : '{ COLLECTED_INFORMATION }')); ?></h1>

		<p><?php echo ((isset($this->_rootref['L_COLLECTED_INFORMATION_EXPLAIN'])) ? $this->_rootref['L_COLLECTED_INFORMATION_EXPLAIN'] : ((isset($user->lang['COLLECTED_INFORMATION_EXPLAIN'])) ? $user->lang['COLLECTED_INFORMATION_EXPLAIN'] : '{ COLLECTED_INFORMATION_EXPLAIN }')); ?></p>

		<?php if ($this->_rootref['S_NO_UPDATE_FILES']) {  ?>

			<div class="errorbox">
				<h3><?php echo ((isset($this->_rootref['L_NO_UPDATE_FILES'])) ? $this->_rootref['L_NO_UPDATE_FILES'] : ((isset($user->lang['NO_UPDATE_FILES'])) ? $user->lang['NO_UPDATE_FILES'] : '{ NO_UPDATE_FILES }')); ?></h3>

				<p><?php echo ((isset($this->_rootref['L_NO_UPDATE_FILES_EXPLAIN'])) ? $this->_rootref['L_NO_UPDATE_FILES_EXPLAIN'] : ((isset($user->lang['NO_UPDATE_FILES_EXPLAIN'])) ? $user->lang['NO_UPDATE_FILES_EXPLAIN'] : '{ NO_UPDATE_FILES_EXPLAIN }')); ?></p><br />

				<strong><?php echo (isset($this->_rootref['NO_UPDATE_FILES'])) ? $this->_rootref['NO_UPDATE_FILES'] : ''; ?></strong>

			</div>
		<?php } ?>


		<form id="install_update" method="post" action="<?php echo (isset($this->_rootref['U_UPDATE_ACTION'])) ? $this->_rootref['U_UPDATE_ACTION'] : ''; ?>">

		<?php if (sizeof($this->_tpldata['up_to_date'])) {  ?>

			<h2><?php echo ((isset($this->_rootref['L_FILES_UP_TO_DATE'])) ? $this->_rootref['L_FILES_UP_TO_DATE'] : ((isset($user->lang['FILES_UP_TO_DATE'])) ? $user->lang['FILES_UP_TO_DATE'] : '{ FILES_UP_TO_DATE }')); ?></h2>
			<p><?php echo ((isset($this->_rootref['L_FILES_UP_TO_DATE_EXPLAIN'])) ? $this->_rootref['L_FILES_UP_TO_DATE_EXPLAIN'] : ((isset($user->lang['FILES_UP_TO_DATE_EXPLAIN'])) ? $user->lang['FILES_UP_TO_DATE_EXPLAIN'] : '{ FILES_UP_TO_DATE_EXPLAIN }')); ?></p>

			<fieldset>
				<legend><img src="<?php echo (isset($this->_rootref['T_IMAGE_PATH'])) ? $this->_rootref['T_IMAGE_PATH'] : ''; ?>file_up_to_date.gif" alt="<?php echo ((isset($this->_rootref['L_STATUS_UP_TO_DATE'])) ? $this->_rootref['L_STATUS_UP_TO_DATE'] : ((isset($user->lang['STATUS_UP_TO_DATE'])) ? $user->lang['STATUS_UP_TO_DATE'] : '{ STATUS_UP_TO_DATE }')); ?>" /></legend>
			<?php $_up_to_date_count = (isset($this->_tpldata['up_to_date'])) ? sizeof($this->_tpldata['up_to_date']) : 0;if ($_up_to_date_count) {for ($_up_to_date_i = 0; $_up_to_date_i < $_up_to_date_count; ++$_up_to_date_i){$_up_to_date_val = &$this->_tpldata['up_to_date'][$_up_to_date_i]; ?>

				<dl>
					<dd class="full" style="text-align: <?php echo (isset($this->_rootref['S_CONTENT_FLOW_BEGIN'])) ? $this->_rootref['S_CONTENT_FLOW_BEGIN'] : ''; ?>;"><strong><?php echo $_up_to_date_val['FILENAME']; ?></strong></dd>
				</dl>
			<?php }} ?>

			</fieldset>

		<?php } if (sizeof($this->_tpldata['new'])) {  ?>

			<h2><?php echo ((isset($this->_rootref['L_FILES_NEW'])) ? $this->_rootref['L_FILES_NEW'] : ((isset($user->lang['FILES_NEW'])) ? $user->lang['FILES_NEW'] : '{ FILES_NEW }')); ?></h2>
			<p><?php echo ((isset($this->_rootref['L_FILES_NEW_EXPLAIN'])) ? $this->_rootref['L_FILES_NEW_EXPLAIN'] : ((isset($user->lang['FILES_NEW_EXPLAIN'])) ? $user->lang['FILES_NEW_EXPLAIN'] : '{ FILES_NEW_EXPLAIN }')); ?></p>

			<fieldset>
				<legend><img src="<?php echo (isset($this->_rootref['T_IMAGE_PATH'])) ? $this->_rootref['T_IMAGE_PATH'] : ''; ?>file_new.gif" alt="<?php echo ((isset($this->_rootref['L_STATUS_NEW'])) ? $this->_rootref['L_STATUS_NEW'] : ((isset($user->lang['STATUS_NEW'])) ? $user->lang['STATUS_NEW'] : '{ STATUS_NEW }')); ?>" /></legend>
			<?php $_new_count = (isset($this->_tpldata['new'])) ? sizeof($this->_tpldata['new']) : 0;if ($_new_count) {for ($_new_i = 0; $_new_i < $_new_count; ++$_new_i){$_new_val = &$this->_tpldata['new'][$_new_i]; ?>

				<dl>
					<dt style="width: 60%;"><strong><?php if ($_new_val['DIR_PART']) {  echo $_new_val['DIR_PART']; ?><br /><?php } echo $_new_val['FILE_PART']; ?></strong>
					<?php if ($_new_val['S_CUSTOM']) {  ?><br /><span><em><?php echo ((isset($this->_rootref['L_FILE_USED'])) ? $this->_rootref['L_FILE_USED'] : ((isset($user->lang['FILE_USED'])) ? $user->lang['FILE_USED'] : '{ FILE_USED }')); ?>: </em><?php echo $_new_val['CUSTOM_ORIGINAL']; ?></span><?php } ?>

				</dt>
				<dd style="margin-<?php echo (isset($this->_rootref['S_CONTENT_FLOW_BEGIN'])) ? $this->_rootref['S_CONTENT_FLOW_BEGIN'] : ''; ?>: 60%;">
					<?php if (! $_new_val['S_BINARY']) {  ?>[<a href="<?php echo $_new_val['U_SHOW_DIFF']; ?>" onclick="diff_popup(this.href); return false;"><?php echo $_new_val['L_SHOW_DIFF']; ?></a>]<?php } else { echo ((isset($this->_rootref['L_BINARY_FILE'])) ? $this->_rootref['L_BINARY_FILE'] : ((isset($user->lang['BINARY_FILE'])) ? $user->lang['BINARY_FILE'] : '{ BINARY_FILE }')); } ?>

				</dd>
				<?php if ($_new_val['S_CUSTOM']) {  ?>

					<dd style="margin-<?php echo (isset($this->_rootref['S_CONTENT_FLOW_BEGIN'])) ? $this->_rootref['S_CONTENT_FLOW_BEGIN'] : ''; ?>: 60%;"><label><input type="checkbox" name="no_update[]" value="<?php echo $_new_val['FILENAME']; ?>" class="radio" /> <?php echo ((isset($this->_rootref['L_DO_NOT_UPDATE'])) ? $this->_rootref['L_DO_NOT_UPDATE'] : ((isset($user->lang['DO_NOT_UPDATE'])) ? $user->lang['DO_NOT_UPDATE'] : '{ DO_NOT_UPDATE }')); ?></label></dd>
				<?php } ?>

				</dl>
			<?php }} ?>

			</fieldset>

		<?php } if (sizeof($this->_tpldata['not_modified'])) {  ?>

			<h2><?php echo ((isset($this->_rootref['L_FILES_NOT_MODIFIED'])) ? $this->_rootref['L_FILES_NOT_MODIFIED'] : ((isset($user->lang['FILES_NOT_MODIFIED'])) ? $user->lang['FILES_NOT_MODIFIED'] : '{ FILES_NOT_MODIFIED }')); ?></h2>
			<div style="float: <?php echo (isset($this->_rootref['S_CONTENT_FLOW_END'])) ? $this->_rootref['S_CONTENT_FLOW_END'] : ''; ?>;">&raquo; <a href="#" onclick="dE('not_modified', 0); return false;"><?php echo ((isset($this->_rootref['L_TOGGLE_DISPLAY'])) ? $this->_rootref['L_TOGGLE_DISPLAY'] : ((isset($user->lang['TOGGLE_DISPLAY'])) ? $user->lang['TOGGLE_DISPLAY'] : '{ TOGGLE_DISPLAY }')); ?></a></div>
			<p><?php echo ((isset($this->_rootref['L_FILES_NOT_MODIFIED_EXPLAIN'])) ? $this->_rootref['L_FILES_NOT_MODIFIED_EXPLAIN'] : ((isset($user->lang['FILES_NOT_MODIFIED_EXPLAIN'])) ? $user->lang['FILES_NOT_MODIFIED_EXPLAIN'] : '{ FILES_NOT_MODIFIED_EXPLAIN }')); ?></p>

			<fieldset id="not_modified" style="display: none;">
				<legend><img src="<?php echo (isset($this->_rootref['T_IMAGE_PATH'])) ? $this->_rootref['T_IMAGE_PATH'] : ''; ?>file_not_modified.gif" alt="<?php echo ((isset($this->_rootref['L_STATUS_NOT_MODIFIED'])) ? $this->_rootref['L_STATUS_NOT_MODIFIED'] : ((isset($user->lang['STATUS_NOT_MODIFIED'])) ? $user->lang['STATUS_NOT_MODIFIED'] : '{ STATUS_NOT_MODIFIED }')); ?>" /></legend>
			<?php $_not_modified_count = (isset($this->_tpldata['not_modified'])) ? sizeof($this->_tpldata['not_modified']) : 0;if ($_not_modified_count) {for ($_not_modified_i = 0; $_not_modified_i < $_not_modified_count; ++$_not_modified_i){$_not_modified_val = &$this->_tpldata['not_modified'][$_not_modified_i]; ?>

				<dl>
					<dt style="width: 60%;"><strong><?php if ($_not_modified_val['DIR_PART']) {  echo $_not_modified_val['DIR_PART']; ?><br /><?php } echo $_not_modified_val['FILE_PART']; ?></strong>
						<?php if ($_not_modified_val['S_CUSTOM']) {  ?><br /><span><em><?php echo ((isset($this->_rootref['L_FILE_USED'])) ? $this->_rootref['L_FILE_USED'] : ((isset($user->lang['FILE_USED'])) ? $user->lang['FILE_USED'] : '{ FILE_USED }')); ?>: </em><?php echo $_not_modified_val['CUSTOM_ORIGINAL']; ?></span><?php } ?>

					</dt>
					<dd style="margin-<?php echo (isset($this->_rootref['S_CONTENT_FLOW_BEGIN'])) ? $this->_rootref['S_CONTENT_FLOW_BEGIN'] : ''; ?>: 60%;"><?php if (! $_not_modified_val['S_BINARY']) {  ?>[<a href="<?php echo $_not_modified_val['U_SHOW_DIFF']; ?>" onclick="diff_popup(this.href); return false;"><?php echo $_not_modified_val['L_SHOW_DIFF']; ?></a>]<?php } else { echo ((isset($this->_rootref['L_BINARY_FILE'])) ? $this->_rootref['L_BINARY_FILE'] : ((isset($user->lang['BINARY_FILE'])) ? $user->lang['BINARY_FILE'] : '{ BINARY_FILE }')); } ?></dd>
					<?php if ($_not_modified_val['S_CUSTOM']) {  ?>

						<dd style="margin-<?php echo (isset($this->_rootref['S_CONTENT_FLOW_BEGIN'])) ? $this->_rootref['S_CONTENT_FLOW_BEGIN'] : ''; ?>: 60%;"><label><input type="checkbox" name="no_update[]" value="<?php echo $_not_modified_val['FILENAME']; ?>" class="radio" /> <?php echo ((isset($this->_rootref['L_DO_NOT_UPDATE'])) ? $this->_rootref['L_DO_NOT_UPDATE'] : ((isset($user->lang['DO_NOT_UPDATE'])) ? $user->lang['DO_NOT_UPDATE'] : '{ DO_NOT_UPDATE }')); ?></label></dd>
					<?php } ?>

				</dl>
			<?php }} ?>

			</fieldset>

		<?php } if (sizeof($this->_tpldata['modified'])) {  ?>

			<h2><?php echo ((isset($this->_rootref['L_FILES_MODIFIED'])) ? $this->_rootref['L_FILES_MODIFIED'] : ((isset($user->lang['FILES_MODIFIED'])) ? $user->lang['FILES_MODIFIED'] : '{ FILES_MODIFIED }')); ?></h2>
			<p><?php echo ((isset($this->_rootref['L_FILES_MODIFIED_EXPLAIN'])) ? $this->_rootref['L_FILES_MODIFIED_EXPLAIN'] : ((isset($user->lang['FILES_MODIFIED_EXPLAIN'])) ? $user->lang['FILES_MODIFIED_EXPLAIN'] : '{ FILES_MODIFIED_EXPLAIN }')); ?></p>

			<?php $_modified_count = (isset($this->_tpldata['modified'])) ? sizeof($this->_tpldata['modified']) : 0;if ($_modified_count) {for ($_modified_i = 0; $_modified_i < $_modified_count; ++$_modified_i){$_modified_val = &$this->_tpldata['modified'][$_modified_i]; ?>

			<fieldset>
				<legend><img src="<?php echo (isset($this->_rootref['T_IMAGE_PATH'])) ? $this->_rootref['T_IMAGE_PATH'] : ''; ?>file_modified.gif" alt="<?php echo ((isset($this->_rootref['L_STATUS_MODIFIED'])) ? $this->_rootref['L_STATUS_MODIFIED'] : ((isset($user->lang['STATUS_MODIFIED'])) ? $user->lang['STATUS_MODIFIED'] : '{ STATUS_MODIFIED }')); ?>" /></legend>
				<dl>
					<dt style="width: 60%;"><strong><?php if ($_modified_val['DIR_PART']) {  echo $_modified_val['DIR_PART']; ?><br /><?php } echo $_modified_val['FILE_PART']; ?></strong>
					<?php if ($_modified_val['S_CUSTOM']) {  ?><br /><span><em><?php echo ((isset($this->_rootref['L_FILE_USED'])) ? $this->_rootref['L_FILE_USED'] : ((isset($user->lang['FILE_USED'])) ? $user->lang['FILE_USED'] : '{ FILE_USED }')); ?>: </em><?php echo $_modified_val['CUSTOM_ORIGINAL']; ?></span><?php } ?>

				</dt>
				<dd style="margin-<?php echo (isset($this->_rootref['S_CONTENT_FLOW_BEGIN'])) ? $this->_rootref['S_CONTENT_FLOW_BEGIN'] : ''; ?>: 60%;">&nbsp;</dd>
				<?php if ($_modified_val['S_CUSTOM']) {  ?>

					<dd style="margin-<?php echo (isset($this->_rootref['S_CONTENT_FLOW_BEGIN'])) ? $this->_rootref['S_CONTENT_FLOW_BEGIN'] : ''; ?>: 60%;"><label><input type="checkbox" name="no_update[]" value="<?php echo $_modified_val['FILENAME']; ?>" class="radio" /> <?php echo ((isset($this->_rootref['L_DO_NOT_UPDATE'])) ? $this->_rootref['L_DO_NOT_UPDATE'] : ((isset($user->lang['DO_NOT_UPDATE'])) ? $user->lang['DO_NOT_UPDATE'] : '{ DO_NOT_UPDATE }')); ?></label></dd>
				<?php } ?>

				</dl>
				<dl>
					<dt style="width: 60%"><label><input type="radio" class="radio" name="modified[<?php echo $_modified_val['FILENAME']; ?>]" value="0" checked="checked" /> <?php echo ((isset($this->_rootref['L_MERGE_MODIFICATIONS_OPTION'])) ? $this->_rootref['L_MERGE_MODIFICATIONS_OPTION'] : ((isset($user->lang['MERGE_MODIFICATIONS_OPTION'])) ? $user->lang['MERGE_MODIFICATIONS_OPTION'] : '{ MERGE_MODIFICATIONS_OPTION }')); ?></label></dt>
					<dd style="margin-<?php echo (isset($this->_rootref['S_CONTENT_FLOW_BEGIN'])) ? $this->_rootref['S_CONTENT_FLOW_BEGIN'] : ''; ?>: 60%;"><?php if (! $_modified_val['S_BINARY']) {  ?>[<a href="<?php echo $_modified_val['U_SHOW_DIFF']; ?>" onclick="diff_popup(this.href); return false;"><?php echo $_modified_val['L_SHOW_DIFF']; ?></a>]<?php } else { echo ((isset($this->_rootref['L_BINARY_FILE'])) ? $this->_rootref['L_BINARY_FILE'] : ((isset($user->lang['BINARY_FILE'])) ? $user->lang['BINARY_FILE'] : '{ BINARY_FILE }')); } ?></dd>
				</dl>
				<dl>
					<dt style="width: 60%"><label><input type="radio" class="radio" name="modified[<?php echo $_modified_val['FILENAME']; ?>]" value="1" /> <?php echo ((isset($this->_rootref['L_MERGE_NO_MERGE_NEW_OPTION'])) ? $this->_rootref['L_MERGE_NO_MERGE_NEW_OPTION'] : ((isset($user->lang['MERGE_NO_MERGE_NEW_OPTION'])) ? $user->lang['MERGE_NO_MERGE_NEW_OPTION'] : '{ MERGE_NO_MERGE_NEW_OPTION }')); ?></label></dt>
					<dd style="margin-<?php echo (isset($this->_rootref['S_CONTENT_FLOW_BEGIN'])) ? $this->_rootref['S_CONTENT_FLOW_BEGIN'] : ''; ?>: 60%;"><?php if (! $_modified_val['S_BINARY']) {  ?>[<a href="<?php echo $_modified_val['U_VIEW_NO_MERGE_NEW']; ?>" onclick="diff_popup(this.href); return false;"><?php echo ((isset($this->_rootref['L_SHOW_DIFF_FINAL'])) ? $this->_rootref['L_SHOW_DIFF_FINAL'] : ((isset($user->lang['SHOW_DIFF_FINAL'])) ? $user->lang['SHOW_DIFF_FINAL'] : '{ SHOW_DIFF_FINAL }')); ?></a>]<?php } else { ?>&nbsp;<?php } ?></dd>
				</dl>
				<dl>
					<dt style="width: 60%"><label><input type="radio" class="radio" name="modified[<?php echo $_modified_val['FILENAME']; ?>]" value="2" /> <?php echo ((isset($this->_rootref['L_MERGE_NO_MERGE_MOD_OPTION'])) ? $this->_rootref['L_MERGE_NO_MERGE_MOD_OPTION'] : ((isset($user->lang['MERGE_NO_MERGE_MOD_OPTION'])) ? $user->lang['MERGE_NO_MERGE_MOD_OPTION'] : '{ MERGE_NO_MERGE_MOD_OPTION }')); ?></label></dt>
					<dd style="margin-<?php echo (isset($this->_rootref['S_CONTENT_FLOW_BEGIN'])) ? $this->_rootref['S_CONTENT_FLOW_BEGIN'] : ''; ?>: 60%;"><?php if (! $_modified_val['S_BINARY']) {  ?>[<a href="<?php echo $_modified_val['U_VIEW_NO_MERGE_MOD']; ?>" onclick="diff_popup(this.href); return false;"><?php echo ((isset($this->_rootref['L_SHOW_DIFF_FINAL'])) ? $this->_rootref['L_SHOW_DIFF_FINAL'] : ((isset($user->lang['SHOW_DIFF_FINAL'])) ? $user->lang['SHOW_DIFF_FINAL'] : '{ SHOW_DIFF_FINAL }')); ?></a>]<?php } else { ?>&nbsp;<?php } ?></dd>
				</dl>
			</fieldset>
			<?php }} } if (sizeof($this->_tpldata['new_conflict'])) {  ?>

			<h2><?php echo ((isset($this->_rootref['L_FILES_NEW_CONFLICT'])) ? $this->_rootref['L_FILES_NEW_CONFLICT'] : ((isset($user->lang['FILES_NEW_CONFLICT'])) ? $user->lang['FILES_NEW_CONFLICT'] : '{ FILES_NEW_CONFLICT }')); ?></h2>
			<p><?php echo ((isset($this->_rootref['L_FILES_NEW_CONFLICT_EXPLAIN'])) ? $this->_rootref['L_FILES_NEW_CONFLICT_EXPLAIN'] : ((isset($user->lang['FILES_NEW_CONFLICT_EXPLAIN'])) ? $user->lang['FILES_NEW_CONFLICT_EXPLAIN'] : '{ FILES_NEW_CONFLICT_EXPLAIN }')); ?></p>

			<fieldset>
				<legend><img src="<?php echo (isset($this->_rootref['T_IMAGE_PATH'])) ? $this->_rootref['T_IMAGE_PATH'] : ''; ?>file_new_conflict.gif" alt="<?php echo ((isset($this->_rootref['L_STATUS_NEW_CONFLICT'])) ? $this->_rootref['L_STATUS_NEW_CONFLICT'] : ((isset($user->lang['STATUS_NEW_CONFLICT'])) ? $user->lang['STATUS_NEW_CONFLICT'] : '{ STATUS_NEW_CONFLICT }')); ?>" /></legend>
			<?php $_new_conflict_count = (isset($this->_tpldata['new_conflict'])) ? sizeof($this->_tpldata['new_conflict']) : 0;if ($_new_conflict_count) {for ($_new_conflict_i = 0; $_new_conflict_i < $_new_conflict_count; ++$_new_conflict_i){$_new_conflict_val = &$this->_tpldata['new_conflict'][$_new_conflict_i]; ?>

				<dl>
					<dt style="width: 60%;"><strong><?php if ($_new_conflict_val['DIR_PART']) {  echo $_new_conflict_val['DIR_PART']; ?><br /><?php } echo $_new_conflict_val['FILE_PART']; ?></strong>
					<?php if ($_new_conflict_val['S_CUSTOM']) {  ?><br /><span><em><?php echo ((isset($this->_rootref['L_FILE_USED'])) ? $this->_rootref['L_FILE_USED'] : ((isset($user->lang['FILE_USED'])) ? $user->lang['FILE_USED'] : '{ FILE_USED }')); ?>: </em><?php echo $_new_conflict_val['CUSTOM_ORIGINAL']; ?></span><?php } ?>

				</dt>
				<dd style="margin-<?php echo (isset($this->_rootref['S_CONTENT_FLOW_BEGIN'])) ? $this->_rootref['S_CONTENT_FLOW_BEGIN'] : ''; ?>: 60%;">
					<?php if (! $_new_conflict_val['S_BINARY']) {  ?>[<a href="<?php echo $_new_conflict_val['U_SHOW_DIFF']; ?>" onclick="diff_popup(this.href); return false;"><?php echo $_new_conflict_val['L_SHOW_DIFF']; ?></a>]<?php } else { echo ((isset($this->_rootref['L_BINARY_FILE'])) ? $this->_rootref['L_BINARY_FILE'] : ((isset($user->lang['BINARY_FILE'])) ? $user->lang['BINARY_FILE'] : '{ BINARY_FILE }')); } ?>

				</dd>
				<?php if ($_new_conflict_val['S_CUSTOM']) {  ?>

					<dd style="margin-<?php echo (isset($this->_rootref['S_CONTENT_FLOW_BEGIN'])) ? $this->_rootref['S_CONTENT_FLOW_BEGIN'] : ''; ?>: 60%;"><label><input type="checkbox" name="no_update[]" value="<?php echo $_new_conflict_val['FILENAME']; ?>" class="radio" /> <?php echo ((isset($this->_rootref['L_DO_NOT_UPDATE'])) ? $this->_rootref['L_DO_NOT_UPDATE'] : ((isset($user->lang['DO_NOT_UPDATE'])) ? $user->lang['DO_NOT_UPDATE'] : '{ DO_NOT_UPDATE }')); ?></label></dd>
				<?php } ?>

				</dl>
			<?php }} ?>

			</fieldset>

		<?php } if (sizeof($this->_tpldata['conflict'])) {  ?>

			<h2><?php echo ((isset($this->_rootref['L_FILES_CONFLICT'])) ? $this->_rootref['L_FILES_CONFLICT'] : ((isset($user->lang['FILES_CONFLICT'])) ? $user->lang['FILES_CONFLICT'] : '{ FILES_CONFLICT }')); ?></h2>
			<p><?php echo ((isset($this->_rootref['L_FILES_CONFLICT_EXPLAIN'])) ? $this->_rootref['L_FILES_CONFLICT_EXPLAIN'] : ((isset($user->lang['FILES_CONFLICT_EXPLAIN'])) ? $user->lang['FILES_CONFLICT_EXPLAIN'] : '{ FILES_CONFLICT_EXPLAIN }')); ?></p>

			<?php $_conflict_count = (isset($this->_tpldata['conflict'])) ? sizeof($this->_tpldata['conflict']) : 0;if ($_conflict_count) {for ($_conflict_i = 0; $_conflict_i < $_conflict_count; ++$_conflict_i){$_conflict_val = &$this->_tpldata['conflict'][$_conflict_i]; ?>

			<fieldset>
				<legend><img src="<?php echo (isset($this->_rootref['T_IMAGE_PATH'])) ? $this->_rootref['T_IMAGE_PATH'] : ''; ?>file_conflict.gif" alt="<?php echo ((isset($this->_rootref['L_STATUS_CONFLICT'])) ? $this->_rootref['L_STATUS_CONFLICT'] : ((isset($user->lang['STATUS_CONFLICT'])) ? $user->lang['STATUS_CONFLICT'] : '{ STATUS_CONFLICT }')); ?>" /></legend>
				<dl>
					<dt style="width: 60%;"><strong><?php if ($_conflict_val['DIR_PART']) {  echo $_conflict_val['DIR_PART']; ?><br /><?php } echo $_conflict_val['FILE_PART']; ?></strong>
						<?php if ($_conflict_val['S_CUSTOM']) {  ?><br /><span><em><?php echo ((isset($this->_rootref['L_FILE_USED'])) ? $this->_rootref['L_FILE_USED'] : ((isset($user->lang['FILE_USED'])) ? $user->lang['FILE_USED'] : '{ FILE_USED }')); ?>: </em><?php echo $_conflict_val['CUSTOM_ORIGINAL']; ?></span><?php } if ($_conflict_val['NUM_CONFLICTS']) {  ?><br /><span><?php echo ((isset($this->_rootref['L_NUM_CONFLICTS'])) ? $this->_rootref['L_NUM_CONFLICTS'] : ((isset($user->lang['NUM_CONFLICTS'])) ? $user->lang['NUM_CONFLICTS'] : '{ NUM_CONFLICTS }')); ?>: <?php echo $_conflict_val['NUM_CONFLICTS']; ?></span><?php } ?>

					</dt>
					<dd style="margin-<?php echo (isset($this->_rootref['S_CONTENT_FLOW_BEGIN'])) ? $this->_rootref['S_CONTENT_FLOW_BEGIN'] : ''; ?>: 60%;">
						<?php if (! $_conflict_val['S_BINARY']) {  ?>[<a href="<?php echo $_conflict_val['U_SHOW_DIFF']; ?>"><?php echo ((isset($this->_rootref['L_DOWNLOAD_CONFLICTS'])) ? $this->_rootref['L_DOWNLOAD_CONFLICTS'] : ((isset($user->lang['DOWNLOAD_CONFLICTS'])) ? $user->lang['DOWNLOAD_CONFLICTS'] : '{ DOWNLOAD_CONFLICTS }')); ?></a>]<br /><?php echo ((isset($this->_rootref['L_DOWNLOAD_CONFLICTS_EXPLAIN'])) ? $this->_rootref['L_DOWNLOAD_CONFLICTS_EXPLAIN'] : ((isset($user->lang['DOWNLOAD_CONFLICTS_EXPLAIN'])) ? $user->lang['DOWNLOAD_CONFLICTS_EXPLAIN'] : '{ DOWNLOAD_CONFLICTS_EXPLAIN }')); ?>

						<?php } else { echo ((isset($this->_rootref['L_BINARY_FILE'])) ? $this->_rootref['L_BINARY_FILE'] : ((isset($user->lang['BINARY_FILE'])) ? $user->lang['BINARY_FILE'] : '{ BINARY_FILE }')); } ?>

					</dd>
					<?php if ($_conflict_val['S_CUSTOM']) {  ?>

						<dd style="margin-<?php echo (isset($this->_rootref['S_CONTENT_FLOW_BEGIN'])) ? $this->_rootref['S_CONTENT_FLOW_BEGIN'] : ''; ?>: 60%;"><label><input type="checkbox" name="no_update[]" value="<?php echo $_conflict_val['FILENAME']; ?>" class="radio" /> <?php echo ((isset($this->_rootref['L_DO_NOT_UPDATE'])) ? $this->_rootref['L_DO_NOT_UPDATE'] : ((isset($user->lang['DO_NOT_UPDATE'])) ? $user->lang['DO_NOT_UPDATE'] : '{ DO_NOT_UPDATE }')); ?></label></dd>
					<?php } ?>

				</dl>
				<?php if ($_conflict_val['S_BINARY']) {  ?>

					<dl>
						<dt style="width: 60%"><label><input type="radio" class="radio" name="conflict[<?php echo $_conflict_val['FILENAME']; ?>]" value="1" checked="checked" /> <?php echo ((isset($this->_rootref['L_MERGE_NO_MERGE_NEW_OPTION'])) ? $this->_rootref['L_MERGE_NO_MERGE_NEW_OPTION'] : ((isset($user->lang['MERGE_NO_MERGE_NEW_OPTION'])) ? $user->lang['MERGE_NO_MERGE_NEW_OPTION'] : '{ MERGE_NO_MERGE_NEW_OPTION }')); ?></label></dt>
						<dd style="margin-<?php echo (isset($this->_rootref['S_CONTENT_FLOW_BEGIN'])) ? $this->_rootref['S_CONTENT_FLOW_BEGIN'] : ''; ?>: 60%;">&nbsp;</dd>
					</dl>
				<?php } else { ?>

					<dl>
						<dt style="width: 60%"><label><input type="radio" class="radio" name="conflict[<?php echo $_conflict_val['FILENAME']; ?>]" value="3" checked="checked" /> <?php echo ((isset($this->_rootref['L_MERGE_NEW_FILE_OPTION'])) ? $this->_rootref['L_MERGE_NEW_FILE_OPTION'] : ((isset($user->lang['MERGE_NEW_FILE_OPTION'])) ? $user->lang['MERGE_NEW_FILE_OPTION'] : '{ MERGE_NEW_FILE_OPTION }')); ?></label></dt>
						<dd style="margin-<?php echo (isset($this->_rootref['S_CONTENT_FLOW_BEGIN'])) ? $this->_rootref['S_CONTENT_FLOW_BEGIN'] : ''; ?>: 60%;">[<a href="<?php echo $_conflict_val['U_VIEW_NEW_FILE']; ?>" onclick="diff_popup(this.href); return false;"><?php echo ((isset($this->_rootref['L_SHOW_DIFF_MODIFIED'])) ? $this->_rootref['L_SHOW_DIFF_MODIFIED'] : ((isset($user->lang['SHOW_DIFF_MODIFIED'])) ? $user->lang['SHOW_DIFF_MODIFIED'] : '{ SHOW_DIFF_MODIFIED }')); ?></a>]</dd>
					</dl>
					<dl>
						<dt style="width: 60%"><label><input type="radio" class="radio" name="conflict[<?php echo $_conflict_val['FILENAME']; ?>]" value="4" /> <?php echo ((isset($this->_rootref['L_MERGE_MOD_FILE_OPTION'])) ? $this->_rootref['L_MERGE_MOD_FILE_OPTION'] : ((isset($user->lang['MERGE_MOD_FILE_OPTION'])) ? $user->lang['MERGE_MOD_FILE_OPTION'] : '{ MERGE_MOD_FILE_OPTION }')); ?></label></dt>
						<dd style="margin-<?php echo (isset($this->_rootref['S_CONTENT_FLOW_BEGIN'])) ? $this->_rootref['S_CONTENT_FLOW_BEGIN'] : ''; ?>: 60%;">[<a href="<?php echo $_conflict_val['U_VIEW_MOD_FILE']; ?>" onclick="diff_popup(this.href); return false;"><?php echo ((isset($this->_rootref['L_SHOW_DIFF_MODIFIED'])) ? $this->_rootref['L_SHOW_DIFF_MODIFIED'] : ((isset($user->lang['SHOW_DIFF_MODIFIED'])) ? $user->lang['SHOW_DIFF_MODIFIED'] : '{ SHOW_DIFF_MODIFIED }')); ?></a>]</dd>
					</dl>
					<dl>
						<dt style="width: 60%"><label><input type="radio" class="radio" name="conflict[<?php echo $_conflict_val['FILENAME']; ?>]" value="1" /> <?php echo ((isset($this->_rootref['L_MERGE_NO_MERGE_NEW_OPTION'])) ? $this->_rootref['L_MERGE_NO_MERGE_NEW_OPTION'] : ((isset($user->lang['MERGE_NO_MERGE_NEW_OPTION'])) ? $user->lang['MERGE_NO_MERGE_NEW_OPTION'] : '{ MERGE_NO_MERGE_NEW_OPTION }')); ?></label></dt>
						<dd style="margin-<?php echo (isset($this->_rootref['S_CONTENT_FLOW_BEGIN'])) ? $this->_rootref['S_CONTENT_FLOW_BEGIN'] : ''; ?>: 60%;">[<a href="<?php echo $_conflict_val['U_VIEW_NO_MERGE_NEW']; ?>" onclick="diff_popup(this.href); return false;"><?php echo ((isset($this->_rootref['L_SHOW_DIFF_FINAL'])) ? $this->_rootref['L_SHOW_DIFF_FINAL'] : ((isset($user->lang['SHOW_DIFF_FINAL'])) ? $user->lang['SHOW_DIFF_FINAL'] : '{ SHOW_DIFF_FINAL }')); ?></a>]</dd>
					</dl>
					<dl>
						<dt style="width: 60%"><label><input type="radio" class="radio" name="conflict[<?php echo $_conflict_val['FILENAME']; ?>]" value="2" /> <?php echo ((isset($this->_rootref['L_MERGE_NO_MERGE_MOD_OPTION'])) ? $this->_rootref['L_MERGE_NO_MERGE_MOD_OPTION'] : ((isset($user->lang['MERGE_NO_MERGE_MOD_OPTION'])) ? $user->lang['MERGE_NO_MERGE_MOD_OPTION'] : '{ MERGE_NO_MERGE_MOD_OPTION }')); ?></label></dt>
						<dd style="margin-<?php echo (isset($this->_rootref['S_CONTENT_FLOW_BEGIN'])) ? $this->_rootref['S_CONTENT_FLOW_BEGIN'] : ''; ?>: 60%;">[<a href="<?php echo $_conflict_val['U_VIEW_NO_MERGE_MOD']; ?>" onclick="diff_popup(this.href); return false;"><?php echo ((isset($this->_rootref['L_SHOW_DIFF_FINAL'])) ? $this->_rootref['L_SHOW_DIFF_FINAL'] : ((isset($user->lang['SHOW_DIFF_FINAL'])) ? $user->lang['SHOW_DIFF_FINAL'] : '{ SHOW_DIFF_FINAL }')); ?></a>]</dd>
					</dl>
				<?php } ?>

			</fieldset>
			<?php }} } ?>


		<br />

		<fieldset class="quick">
			<input class="button1" type="submit" name="check_again" value="<?php echo ((isset($this->_rootref['L_CHECK_FILES_AGAIN'])) ? $this->_rootref['L_CHECK_FILES_AGAIN'] : ((isset($user->lang['CHECK_FILES_AGAIN'])) ? $user->lang['CHECK_FILES_AGAIN'] : '{ CHECK_FILES_AGAIN }')); ?>" />
		</fieldset>

		<br />

		<h1><?php echo ((isset($this->_rootref['L_UPDATE_METHOD'])) ? $this->_rootref['L_UPDATE_METHOD'] : ((isset($user->lang['UPDATE_METHOD'])) ? $user->lang['UPDATE_METHOD'] : '{ UPDATE_METHOD }')); ?></h1>

		<p><?php echo ((isset($this->_rootref['L_UPDATE_METHOD_EXPLAIN'])) ? $this->_rootref['L_UPDATE_METHOD_EXPLAIN'] : ((isset($user->lang['UPDATE_METHOD_EXPLAIN'])) ? $user->lang['UPDATE_METHOD_EXPLAIN'] : '{ UPDATE_METHOD_EXPLAIN }')); ?></p>

		<fieldset class="submit-buttons">
			<input class="button1" type="submit" name="ftp_upload" value="<?php echo ((isset($this->_rootref['L_FTP_UPDATE_METHOD'])) ? $this->_rootref['L_FTP_UPDATE_METHOD'] : ((isset($user->lang['FTP_UPDATE_METHOD'])) ? $user->lang['FTP_UPDATE_METHOD'] : '{ FTP_UPDATE_METHOD }')); ?>" />&nbsp; &nbsp;<input class="button1" type="submit" name="download" value="<?php echo ((isset($this->_rootref['L_DOWNLOAD_UPDATE_METHOD_BUTTON'])) ? $this->_rootref['L_DOWNLOAD_UPDATE_METHOD_BUTTON'] : ((isset($user->lang['DOWNLOAD_UPDATE_METHOD_BUTTON'])) ? $user->lang['DOWNLOAD_UPDATE_METHOD_BUTTON'] : '{ DOWNLOAD_UPDATE_METHOD_BUTTON }')); ?>" />
		</fieldset>

		</form>

	<?php } } else if ($this->_rootref['S_DOWNLOAD_FILES']) {  ?>


	<h1><?php echo ((isset($this->_rootref['L_DOWNLOAD_UPDATE_METHOD'])) ? $this->_rootref['L_DOWNLOAD_UPDATE_METHOD'] : ((isset($user->lang['DOWNLOAD_UPDATE_METHOD'])) ? $user->lang['DOWNLOAD_UPDATE_METHOD'] : '{ DOWNLOAD_UPDATE_METHOD }')); ?></h1>

	<p><?php echo ((isset($this->_rootref['L_DOWNLOAD_UPDATE_METHOD_EXPLAIN'])) ? $this->_rootref['L_DOWNLOAD_UPDATE_METHOD_EXPLAIN'] : ((isset($user->lang['DOWNLOAD_UPDATE_METHOD_EXPLAIN'])) ? $user->lang['DOWNLOAD_UPDATE_METHOD_EXPLAIN'] : '{ DOWNLOAD_UPDATE_METHOD_EXPLAIN }')); ?></p>

	<form id="install_update" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_SELECT_DOWNLOAD_FORMAT'])) ? $this->_rootref['L_SELECT_DOWNLOAD_FORMAT'] : ((isset($user->lang['SELECT_DOWNLOAD_FORMAT'])) ? $user->lang['SELECT_DOWNLOAD_FORMAT'] : '{ SELECT_DOWNLOAD_FORMAT }')); ?></legend>
	<dl>
		<dt><label for="use_method"><?php echo ((isset($this->_rootref['L_DOWNLOAD_AS'])) ? $this->_rootref['L_DOWNLOAD_AS'] : ((isset($user->lang['DOWNLOAD_AS'])) ? $user->lang['DOWNLOAD_AS'] : '{ DOWNLOAD_AS }')); ?>:</label></dt>
		<dd><?php echo (isset($this->_rootref['RADIO_BUTTONS'])) ? $this->_rootref['RADIO_BUTTONS'] : ''; ?></dd>
	</dl>
	</fieldset>

	<fieldset class="submit-buttons">
		<?php echo (isset($this->_rootref['S_HIDDEN_FIELDS'])) ? $this->_rootref['S_HIDDEN_FIELDS'] : ''; ?>

		<input type="submit" class="button2" value="<?php echo ((isset($this->_rootref['L_CONTINUE_UPDATE'])) ? $this->_rootref['L_CONTINUE_UPDATE'] : ((isset($user->lang['CONTINUE_UPDATE'])) ? $user->lang['CONTINUE_UPDATE'] : '{ CONTINUE_UPDATE }')); ?>" name="check_again" />&nbsp; &nbsp;<input type="submit" class="button1" value="<?php echo ((isset($this->_rootref['L_DOWNLOAD'])) ? $this->_rootref['L_DOWNLOAD'] : ((isset($user->lang['DOWNLOAD'])) ? $user->lang['DOWNLOAD'] : '{ DOWNLOAD }')); ?>" name="download" />
	</fieldset>

	</form>

	<br /><br />

	<p><?php echo ((isset($this->_rootref['L_MAPPING_FILE_STRUCTURE'])) ? $this->_rootref['L_MAPPING_FILE_STRUCTURE'] : ((isset($user->lang['MAPPING_FILE_STRUCTURE'])) ? $user->lang['MAPPING_FILE_STRUCTURE'] : '{ MAPPING_FILE_STRUCTURE }')); ?></p>

	<table cellspacing="1">
		<col class="row1" /><col class="row2" /><col class="row1" />
	<thead>
	<tr>
		<th style="width: 49%"><?php echo ((isset($this->_rootref['L_ARCHIVE_FILE'])) ? $this->_rootref['L_ARCHIVE_FILE'] : ((isset($user->lang['ARCHIVE_FILE'])) ? $user->lang['ARCHIVE_FILE'] : '{ ARCHIVE_FILE }')); ?></th>
		<th style="width: 2%">&nbsp;</th>
		<th style="width: 49%"><?php echo ((isset($this->_rootref['L_DESTINATION'])) ? $this->_rootref['L_DESTINATION'] : ((isset($user->lang['DESTINATION'])) ? $user->lang['DESTINATION'] : '{ DESTINATION }')); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php $_location_count = (isset($this->_tpldata['location'])) ? sizeof($this->_tpldata['location']) : 0;if ($_location_count) {for ($_location_i = 0; $_location_i < $_location_count; ++$_location_i){$_location_val = &$this->_tpldata['location'][$_location_i]; ?>

	<tr>
		<td><?php echo $_location_val['SOURCE']; ?></td>
		<td><strong>&raquo;</strong></td>
		<td><?php echo $_location_val['DESTINATION']; ?></td>
	</tr>
	<?php }} ?>

	</tbody>
	</table>

<?php } else if ($this->_rootref['S_FTP_UPLOAD']) {  ?>


	<h1><?php echo ((isset($this->_rootref['L_SELECT_FTP_SETTINGS'])) ? $this->_rootref['L_SELECT_FTP_SETTINGS'] : ((isset($user->lang['SELECT_FTP_SETTINGS'])) ? $user->lang['SELECT_FTP_SETTINGS'] : '{ SELECT_FTP_SETTINGS }')); ?></h1>

	<form id="install_update" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">

	<?php if ($this->_rootref['S_CONNECTION_SUCCESS']) {  ?>

		<div class="successbox">
			<p><?php echo ((isset($this->_rootref['L_CONNECTION_SUCCESS'])) ? $this->_rootref['L_CONNECTION_SUCCESS'] : ((isset($user->lang['CONNECTION_SUCCESS'])) ? $user->lang['CONNECTION_SUCCESS'] : '{ CONNECTION_SUCCESS }')); ?></p>
		</div>
	<?php } else if ($this->_rootref['S_CONNECTION_FAILED']) {  ?>

		<div class="successbox">
			<p><?php echo ((isset($this->_rootref['L_TRY_DOWNLOAD_METHOD'])) ? $this->_rootref['L_TRY_DOWNLOAD_METHOD'] : ((isset($user->lang['TRY_DOWNLOAD_METHOD'])) ? $user->lang['TRY_DOWNLOAD_METHOD'] : '{ TRY_DOWNLOAD_METHOD }')); ?></p>

			<fieldset class="quick">
				<input class="button1" type="submit" name="download" value="<?php echo ((isset($this->_rootref['L_TRY_DOWNLOAD_METHOD_BUTTON'])) ? $this->_rootref['L_TRY_DOWNLOAD_METHOD_BUTTON'] : ((isset($user->lang['TRY_DOWNLOAD_METHOD_BUTTON'])) ? $user->lang['TRY_DOWNLOAD_METHOD_BUTTON'] : '{ TRY_DOWNLOAD_METHOD_BUTTON }')); ?>" />
			</fieldset>
		</div>

		<div class="errorbox">
			<p><?php echo ((isset($this->_rootref['L_CONNECTION_FAILED'])) ? $this->_rootref['L_CONNECTION_FAILED'] : ((isset($user->lang['CONNECTION_FAILED'])) ? $user->lang['CONNECTION_FAILED'] : '{ CONNECTION_FAILED }')); ?><br /><?php echo (isset($this->_rootref['ERROR_MSG'])) ? $this->_rootref['ERROR_MSG'] : ''; ?></p>
		</div>
	<?php } ?>


	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_FTP_SETTINGS'])) ? $this->_rootref['L_FTP_SETTINGS'] : ((isset($user->lang['FTP_SETTINGS'])) ? $user->lang['FTP_SETTINGS'] : '{ FTP_SETTINGS }')); ?></legend>
	<dl>
		<dt><label><?php echo ((isset($this->_rootref['L_UPLOAD_METHOD'])) ? $this->_rootref['L_UPLOAD_METHOD'] : ((isset($user->lang['UPLOAD_METHOD'])) ? $user->lang['UPLOAD_METHOD'] : '{ UPLOAD_METHOD }')); ?>:</label></dt>
		<dd><strong><?php echo (isset($this->_rootref['UPLOAD_METHOD'])) ? $this->_rootref['UPLOAD_METHOD'] : ''; ?></strong></dd>
	</dl>
	<?php $_data_count = (isset($this->_tpldata['data'])) ? sizeof($this->_tpldata['data']) : 0;if ($_data_count) {for ($_data_i = 0; $_data_i < $_data_count; ++$_data_i){$_data_val = &$this->_tpldata['data'][$_data_i]; ?>

	<dl>
		<dt><label for="<?php echo $_data_val['DATA']; ?>"><?php echo $_data_val['NAME']; ?>:</label><br /><span><?php echo $_data_val['EXPLAIN']; ?></span></dt>
		<dd><input type="<?php if ($_data_val['DATA'] == ('password')) {  ?>password<?php } else { ?>text<?php } ?>" id="<?php echo $_data_val['DATA']; ?>" name="<?php echo $_data_val['DATA']; ?>" value="<?php echo $_data_val['DEFAULT']; ?>" /></dd>
	</dl>
	<?php }} ?>

	</fieldset>

	<fieldset class="submit-buttons">
		<?php echo (isset($this->_rootref['S_HIDDEN_FIELDS'])) ? $this->_rootref['S_HIDDEN_FIELDS'] : ''; ?>

		<input class="button2" type="submit" name="check_again" value="<?php echo ((isset($this->_rootref['L_BACK'])) ? $this->_rootref['L_BACK'] : ((isset($user->lang['BACK'])) ? $user->lang['BACK'] : '{ BACK }')); ?>" />
		<input class="button1" type="submit" name="test_connection" value="<?php echo ((isset($this->_rootref['L_TEST_CONNECTION'])) ? $this->_rootref['L_TEST_CONNECTION'] : ((isset($user->lang['TEST_CONNECTION'])) ? $user->lang['TEST_CONNECTION'] : '{ TEST_CONNECTION }')); ?>" />
		<input class="button1" type="submit" name="submit" value="<?php echo ((isset($this->_rootref['L_UPDATE_FILES'])) ? $this->_rootref['L_UPDATE_FILES'] : ((isset($user->lang['UPDATE_FILES'])) ? $user->lang['UPDATE_FILES'] : '{ UPDATE_FILES }')); ?>" />
	</fieldset>

	</form>

<?php } $this->_tpl_include('install_footer.html'); ?>