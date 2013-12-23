<?php if (!defined('IN_PHPBB')) exit; if ($this->_rootref['S_DISPLAY_DETAILS']) {  if ($this->_rootref['S_NEW_FILES']) {  ?>

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_NEW_FILES'])) ? $this->_rootref['L_NEW_FILES'] : ((isset($user->lang['NEW_FILES'])) ? $user->lang['NEW_FILES'] : '{ NEW_FILES }')); ?></legend>
		<table cellspacing="1">
			<col class="row1" /><col class="row1" /><col class="row2" />
		<thead>
			<tr>
				<th style="width:40%"><?php echo ((isset($this->_rootref['L_SOURCE'])) ? $this->_rootref['L_SOURCE'] : ((isset($user->lang['SOURCE'])) ? $user->lang['SOURCE'] : '{ SOURCE }')); ?></th>
				<th style="width:40%"><?php echo ((isset($this->_rootref['L_TARGET'])) ? $this->_rootref['L_TARGET'] : ((isset($user->lang['TARGET'])) ? $user->lang['TARGET'] : '{ TARGET }')); ?></th>
				<th style="width:20%"><?php echo ((isset($this->_rootref['L_STATUS'])) ? $this->_rootref['L_STATUS'] : ((isset($user->lang['STATUS'])) ? $user->lang['STATUS'] : '{ STATUS }')); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php $_new_files_count = (isset($this->_tpldata['new_files'])) ? sizeof($this->_tpldata['new_files']) : 0;if ($_new_files_count) {for ($_new_files_i = 0; $_new_files_i < $_new_files_count; ++$_new_files_i){$_new_files_val = &$this->_tpldata['new_files'][$_new_files_i]; ?>

			<tr>
				<td>
					<strong><?php echo $_new_files_val['SOURCE']; ?>

					<?php if ($_new_files_val['S_MISSING_FILE']) {  ?>&nbsp;&nbsp;&nbsp;
					<font color="red">(<?php echo ((isset($this->_rootref['L_FILE_MISSING'])) ? $this->_rootref['L_FILE_MISSING'] : ((isset($user->lang['FILE_MISSING'])) ? $user->lang['FILE_MISSING'] : '{ FILE_MISSING }')); ?>)</font>
					<?php } ?>

					</strong>
				</td>
				<td><?php echo $_new_files_val['TARGET']; ?></td>
				<td>
					<?php if ($this->_rootref['S_INSTALL']) {  if ($_new_files_val['S_SUCCESS']) {  ?><font color="green"><?php echo ((isset($this->_rootref['L_SUCCESS'])) ? $this->_rootref['L_SUCCESS'] : ((isset($user->lang['SUCCESS'])) ? $user->lang['SUCCESS'] : '{ SUCCESS }')); ?></font>
						<?php } else if ($_new_files_val['S_NO_COPY_ATTEMPT']) {  echo ((isset($this->_rootref['L_MANUAL_COPY'])) ? $this->_rootref['L_MANUAL_COPY'] : ((isset($user->lang['MANUAL_COPY'])) ? $user->lang['MANUAL_COPY'] : '{ MANUAL_COPY }')); ?>

						<?php } else { ?><font color="red"><?php echo ((isset($this->_rootref['L_ERROR'])) ? $this->_rootref['L_ERROR'] : ((isset($user->lang['ERROR'])) ? $user->lang['ERROR'] : '{ ERROR }')); ?></font><?php } } ?>

				</td>
			</tr>
			<?php }} ?>

		</tbody>
		</table>
	</fieldset>
	<?php } if ($this->_rootref['S_REMOVING_FILES']) {  ?>

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_REMOVING_FILES'])) ? $this->_rootref['L_REMOVING_FILES'] : ((isset($user->lang['REMOVING_FILES'])) ? $user->lang['REMOVING_FILES'] : '{ REMOVING_FILES }')); ?></legend>
		<table cellspacing="1">
			<col class="row1" /><col class="row2" />
		<thead>
			<tr>
				<th style="width:70%"><?php echo ((isset($this->_rootref['L_SOURCE'])) ? $this->_rootref['L_SOURCE'] : ((isset($user->lang['SOURCE'])) ? $user->lang['SOURCE'] : '{ SOURCE }')); ?></th>
				<th style="width:30%"><?php echo ((isset($this->_rootref['L_STATUS'])) ? $this->_rootref['L_STATUS'] : ((isset($user->lang['STATUS'])) ? $user->lang['STATUS'] : '{ STATUS }')); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php $_removing_files_count = (isset($this->_tpldata['removing_files'])) ? sizeof($this->_tpldata['removing_files']) : 0;if ($_removing_files_count) {for ($_removing_files_i = 0; $_removing_files_i < $_removing_files_count; ++$_removing_files_i){$_removing_files_val = &$this->_tpldata['removing_files'][$_removing_files_i]; ?>

			<tr>
				<td>
					<strong><?php echo $_removing_files_val['FILENAME']; ?>

					<?php if ($_removing_files_val['S_MISSING_FILE']) {  ?>&nbsp;&nbsp;&nbsp;
					<font color="red">(<?php echo ((isset($this->_rootref['L_FILE_MISSING'])) ? $this->_rootref['L_FILE_MISSING'] : ((isset($user->lang['FILE_MISSING'])) ? $user->lang['FILE_MISSING'] : '{ FILE_MISSING }')); ?>)</font>&nbsp;
					<?php } ?>

					</strong>
				</td>
				<td>
					<?php if ($this->_rootref['S_UNINSTALL']) {  if ($_removing_files_val['S_SUCCESS']) {  ?><font color="green"><?php echo ((isset($this->_rootref['L_SUCCESS'])) ? $this->_rootref['L_SUCCESS'] : ((isset($user->lang['SUCCESS'])) ? $user->lang['SUCCESS'] : '{ SUCCESS }')); ?></font>
						<?php } else if ($_removing_files_val['S_NO_DELETE_ATTEMPT']) {  echo ((isset($this->_rootref['L_NO_ATTEMPT'])) ? $this->_rootref['L_NO_ATTEMPT'] : ((isset($user->lang['NO_ATTEMPT'])) ? $user->lang['NO_ATTEMPT'] : '{ NO_ATTEMPT }')); ?>

						<?php } else { ?><font color="red"><?php echo ((isset($this->_rootref['L_ERROR'])) ? $this->_rootref['L_ERROR'] : ((isset($user->lang['ERROR'])) ? $user->lang['ERROR'] : '{ ERROR }')); ?></font><?php } } ?>

				</td>
			</tr>
			<?php }} ?>

		</tbody>
		</table>
	</fieldset>
	<?php } if ($this->_rootref['S_SQL']) {  ?>

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_SQL_QUERIES'])) ? $this->_rootref['L_SQL_QUERIES'] : ((isset($user->lang['SQL_QUERIES'])) ? $user->lang['SQL_QUERIES'] : '{ SQL_QUERIES }')); ?></legend>
		<?php $_sql_queries_count = (isset($this->_tpldata['sql_queries'])) ? sizeof($this->_tpldata['sql_queries']) : 0;if ($_sql_queries_count) {for ($_sql_queries_i = 0; $_sql_queries_i < $_sql_queries_count; ++$_sql_queries_i){$_sql_queries_val = &$this->_tpldata['sql_queries'][$_sql_queries_i]; if ($this->_rootref['S_PRE_UNINSTALL']) {  ?>

		<table cellspacing="1">
			<col class="row1" /><col class="row2" />
		<thead>
		<tr>
			<th style="width:50%"><?php echo ((isset($this->_rootref['L_ORIGINAL'])) ? $this->_rootref['L_ORIGINAL'] : ((isset($user->lang['ORIGINAL'])) ? $user->lang['ORIGINAL'] : '{ ORIGINAL }')); ?></th>
			<th style="width:50%"><?php echo ((isset($this->_rootref['L_REVERSE'])) ? $this->_rootref['L_REVERSE'] : ((isset($user->lang['REVERSE'])) ? $user->lang['REVERSE'] : '{ REVERSE }')); ?></th>
		</tr>
		</thead>
		<tbody>
			<?php $_sql_queries_count = (isset($_sql_queries_val['sql_queries'])) ? sizeof($_sql_queries_val['sql_queries']) : 0;if ($_sql_queries_count) {for ($_sql_queries_i = 0; $_sql_queries_i < $_sql_queries_count; ++$_sql_queries_i){$_sql_queries_val = &$_sql_queries_val['sql_queries'][$_sql_queries_i]; ?>

			<tr>
				<td><strong><?php echo $_sql_queries_val['ORIGINAL_QUERY']; ?></strong></td>
				<td>
					<?php if ($_sql_queries_val['S_UNKNOWN_REVERSE']) {  ?>

					&nbsp;&nbsp;&nbsp;<font color="red"><?php echo ((isset($this->_rootref['L_UNKNOWN_QUERY_REVERSE'])) ? $this->_rootref['L_UNKNOWN_QUERY_REVERSE'] : ((isset($user->lang['UNKNOWN_QUERY_REVERSE'])) ? $user->lang['UNKNOWN_QUERY_REVERSE'] : '{ UNKNOWN_QUERY_REVERSE }')); ?>!</font>
					<?php } else { ?><strong><?php echo $_sql_queries_val['REVERSE_QUERY']; ?></strong><?php } ?>

				</td>
			</tr>
			<?php }} ?>

		</tbody>
		</table>
		<?php } else { ?>

		<fieldset>
			<strong><?php echo $_sql_queries_val['QUERY']; ?></strong>
			<?php if ($this->_rootref['S_CHANGE_FILES']) {  if ($_sql_queries_val['S_SUCCESS']) {  ?><font color="green"><?php echo ((isset($this->_rootref['L_SUCCESS'])) ? $this->_rootref['L_SUCCESS'] : ((isset($user->lang['SUCCESS'])) ? $user->lang['SUCCESS'] : '{ SUCCESS }')); ?>:</font><br />
				<?php } else { ?><font color="red"><?php echo ((isset($this->_rootref['L_ERROR'])) ? $this->_rootref['L_ERROR'] : ((isset($user->lang['ERROR'])) ? $user->lang['ERROR'] : '{ ERROR }')); ?>:</font><?php echo $_sql_queries_val['ERROR_MSG']; ?><br />
				<?php } } ?>

		</fieldset>
		<?php } }} ?>

	</fieldset>
	<?php } if ($this->_rootref['S_EDITS']) {  ?>

	<h2><?php echo ((isset($this->_rootref['L_FILE_EDITS'])) ? $this->_rootref['L_FILE_EDITS'] : ((isset($user->lang['FILE_EDITS'])) ? $user->lang['FILE_EDITS'] : '{ FILE_EDITS }')); ?></h2>

    <?php if ($this->_rootref['S_INSTALL'] || $this->_rootref['S_UNINSTALL']) {  ?>

	<script type="text/javascript">
		// <![CDATA[
		function toggle_files()
		{
			var hide_files = document.getElementsByName('hide-file');

			for (var i = 0; i < hide_files.length; i++)
			{
				hide_files[i].style.display = (hide_files[i].style.display == 'none') ? 'block' : 'none';
			}

			if (document.getElementById('hide-files').innerHTML == '<?php echo ((isset($this->_rootref['LA_CLICK_HIDE_FILES'])) ? $this->_rootref['LA_CLICK_HIDE_FILES'] : ((isset($this->_rootref['L_CLICK_HIDE_FILES'])) ? addslashes($this->_rootref['L_CLICK_HIDE_FILES']) : ((isset($user->lang['CLICK_HIDE_FILES'])) ? addslashes($user->lang['CLICK_HIDE_FILES']) : '{ CLICK_HIDE_FILES }'))); ?>')
			{
				document.getElementById('hide-files').innerHTML = '<?php echo ((isset($this->_rootref['LA_CLICK_SHOW_FILES'])) ? $this->_rootref['LA_CLICK_SHOW_FILES'] : ((isset($this->_rootref['L_CLICK_SHOW_FILES'])) ? addslashes($this->_rootref['L_CLICK_SHOW_FILES']) : ((isset($user->lang['CLICK_SHOW_FILES'])) ? addslashes($user->lang['CLICK_SHOW_FILES']) : '{ CLICK_SHOW_FILES }'))); ?>';
			}
			else
			{
				document.getElementById('hide-files').innerHTML = '<?php echo ((isset($this->_rootref['LA_CLICK_HIDE_FILES'])) ? $this->_rootref['LA_CLICK_HIDE_FILES'] : ((isset($this->_rootref['L_CLICK_HIDE_FILES'])) ? addslashes($this->_rootref['L_CLICK_HIDE_FILES']) : ((isset($user->lang['CLICK_HIDE_FILES'])) ? addslashes($user->lang['CLICK_HIDE_FILES']) : '{ CLICK_HIDE_FILES }'))); ?>';
			}
		}

		function toggle_edits()
		{
			var hide_edits = document.getElementsByName('hide-edit');

			for (var i = 0; i < hide_edits.length; i++)
			{
				hide_edits[i].style.display = (hide_edits[i].style.display == 'none') ? 'block' : 'none';
			}

			if (document.getElementById('hide-edits').innerHTML == '<?php echo ((isset($this->_rootref['LA_CLICK_HIDE_EDITS'])) ? $this->_rootref['LA_CLICK_HIDE_EDITS'] : ((isset($this->_rootref['L_CLICK_HIDE_EDITS'])) ? addslashes($this->_rootref['L_CLICK_HIDE_EDITS']) : ((isset($user->lang['CLICK_HIDE_EDITS'])) ? addslashes($user->lang['CLICK_HIDE_EDITS']) : '{ CLICK_HIDE_EDITS }'))); ?>')
			{
				document.getElementById('hide-edits').innerHTML = '<?php echo ((isset($this->_rootref['LA_CLICK_SHOW_EDITS'])) ? $this->_rootref['LA_CLICK_SHOW_EDITS'] : ((isset($this->_rootref['L_CLICK_SHOW_EDITS'])) ? addslashes($this->_rootref['L_CLICK_SHOW_EDITS']) : ((isset($user->lang['CLICK_SHOW_EDITS'])) ? addslashes($user->lang['CLICK_SHOW_EDITS']) : '{ CLICK_SHOW_EDITS }'))); ?>';
			}
			else
			{
				document.getElementById('hide-edits').innerHTML = '<?php echo ((isset($this->_rootref['LA_CLICK_HIDE_EDITS'])) ? $this->_rootref['LA_CLICK_HIDE_EDITS'] : ((isset($this->_rootref['L_CLICK_HIDE_EDITS'])) ? addslashes($this->_rootref['L_CLICK_HIDE_EDITS']) : ((isset($user->lang['CLICK_HIDE_EDITS'])) ? addslashes($user->lang['CLICK_HIDE_EDITS']) : '{ CLICK_HIDE_EDITS }'))); ?>';
			}
		}
		// ]]>
	</script>
	<p>
		<a href="#" onclick="toggle_files(); return false;" id="hide-files"><?php echo ((isset($this->_rootref['L_CLICK_HIDE_FILES'])) ? $this->_rootref['L_CLICK_HIDE_FILES'] : ((isset($user->lang['CLICK_HIDE_FILES'])) ? $user->lang['CLICK_HIDE_FILES'] : '{ CLICK_HIDE_FILES }')); ?></a> &bull; 
		<a href="#" onclick="toggle_edits(); return false;" id="hide-edits"><?php echo ((isset($this->_rootref['L_CLICK_HIDE_EDITS'])) ? $this->_rootref['L_CLICK_HIDE_EDITS'] : ((isset($user->lang['CLICK_HIDE_EDITS'])) ? $user->lang['CLICK_HIDE_EDITS'] : '{ CLICK_HIDE_EDITS }')); ?></a>
	</p>
    <?php } ?>


	<!--	// Per Ticket #40635, the only workaround found to get overflow(-x) working cross-browser is "fake fieldset".
			// It would be best, for XHTML Validation purposes, to put this CSS (and the javascript above) before the </head>
			// tag, but as of phpBB-3.0.8, dynamic linking of css/js in adm/style/overall_header is not yet possible (fixme) -->
	<style type="text/css">
		.fake_fieldset {
			margin: 15px 0;
			padding: 10px;
			border: 1px solid #D7D7D7;
			background: #fff;
			position: relative;
		}

		.fake_fieldset h5 {
			font-family: Tahoma,arial,Verdana,Sans-serif;
			font-size: 0.9em;
			font-weight: bold;
			color: #115098;
		}

		.fake_fieldset h5 span {
			padding: 2px 4px;
			border: 1px solid #ddd;
			background: #fff;
			position: relative;
			top: -1.4em;
		}
	</style>

	<?php $_edit_files_count = (isset($this->_tpldata['edit_files'])) ? sizeof($this->_tpldata['edit_files']) : 0;if ($_edit_files_count) {for ($_edit_files_i = 0; $_edit_files_i < $_edit_files_count; ++$_edit_files_i){$_edit_files_val = &$this->_tpldata['edit_files'][$_edit_files_i]; ?>

	<div<?php if ($_edit_files_val['S_SUCCESS']) {  ?> name="hide-file"<?php } ?> class="fake_fieldset">
		<h5><span><?php echo $_edit_files_val['FILENAME']; ?></span></h5>
		<?php if ($_edit_files_val['S_MISSING_FILE']) {  ?><strong><font color="red"><?php echo ((isset($this->_rootref['L_FILE_MISSING'])) ? $this->_rootref['L_FILE_MISSING'] : ((isset($user->lang['FILE_MISSING'])) ? $user->lang['FILE_MISSING'] : '{ FILE_MISSING }')); ?></font></strong>
		<?php } else if ($_edit_files_val['INHERIT_MSG']) {  ?><strong><?php echo $_edit_files_val['INHERIT_MSG']; ?></strong><?php } $_finds_count = (isset($_edit_files_val['finds'])) ? sizeof($_edit_files_val['finds']) : 0;if ($_finds_count) {for ($_finds_i = 0; $_finds_i < $_finds_count; ++$_finds_i){$_finds_val = &$_edit_files_val['finds'][$_finds_i]; ?>

		<div<?php if ($_finds_val['S_SUCCESS']) {  ?> name="hide-edit"<?php } ?> class="fake_fieldset">
			<h4 style="margin: 0; padding:0;"><?php echo ((isset($this->_rootref['L_FIND'])) ? $this->_rootref['L_FIND'] : ((isset($user->lang['FIND'])) ? $user->lang['FIND'] : '{ FIND }')); ?></h4>
			<?php if ($_finds_val['COMMENT']) {  ?><p><strong><?php echo ((isset($this->_rootref['L_COMMENT'])) ? $this->_rootref['L_COMMENT'] : ((isset($user->lang['COMMENT'])) ? $user->lang['COMMENT'] : '{ COMMENT }')); ?></strong>: <?php echo $_finds_val['COMMENT']; ?></p><?php } ?>

			<pre style="overflow-x: scroll;"><?php echo $_finds_val['FIND_STRING']; ?></pre>
			<?php if ($_finds_val['S_MISSING_FIND']) {  ?><strong><font color="red"><?php echo ((isset($this->_rootref['L_FIND_MISSING'])) ? $this->_rootref['L_FIND_MISSING'] : ((isset($user->lang['FIND_MISSING'])) ? $user->lang['FIND_MISSING'] : '{ FIND_MISSING }')); ?></font></strong><?php } $_actions_count = (isset($_finds_val['actions'])) ? sizeof($_finds_val['actions']) : 0;if ($_actions_count) {for ($_actions_i = 0; $_actions_i < $_actions_count; ++$_actions_i){$_actions_val = &$_finds_val['actions'][$_actions_i]; ?>

				<hr style="padding-top: 10px; margin-top: 10px;" />
				<h4 style="margin: 0; padding:0;"><?php echo $_actions_val['NAME']; ?></h4>
				<pre style="overflow-x: scroll;"><?php echo $_actions_val['COMMAND']; ?></pre>
				<?php $_inline_count = (isset($_actions_val['inline'])) ? sizeof($_actions_val['inline']) : 0;if ($_inline_count) {for ($_inline_i = 0; $_inline_i < $_inline_count; ++$_inline_i){$_inline_val = &$_actions_val['inline'][$_inline_i]; ?>

					<hr style="padding-top: 10px; margin-top: 10px;" />
					<h4 style="margin: 0; padding:0;"><?php echo $_inline_val['NAME']; ?></h4>
					<?php if ($_inline_val['COMMENT']) {  ?>

					<p><strong><?php echo ((isset($this->_rootref['L_COMMENT'])) ? $this->_rootref['L_COMMENT'] : ((isset($user->lang['COMMENT'])) ? $user->lang['COMMENT'] : '{ COMMENT }')); ?></strong>:<p><?php echo $_inline_val['COMMENT']; ?></p>
					<?php } ?>

					<pre style="overflow-x: scroll;"><?php echo $_inline_val['COMMAND']; ?></pre>
					<?php if ($this->_rootref['S_CHANGE_FILES'] && ! $_inline_val['S_SUCCESS']) {  ?>

					<span><?php echo ((isset($this->_rootref['L_INLINE_FIND_MISSING'])) ? $this->_rootref['L_INLINE_FIND_MISSING'] : ((isset($user->lang['INLINE_FIND_MISSING'])) ? $user->lang['INLINE_FIND_MISSING'] : '{ INLINE_FIND_MISSING }')); ?></span>
					<?php } }} if ($this->_rootref['S_CHANGE_FILES']) {  if ($_actions_val['S_SUCCESS']) {  ?><font color="green"><?php echo ((isset($this->_rootref['L_SUCCESS'])) ? $this->_rootref['L_SUCCESS'] : ((isset($user->lang['SUCCESS'])) ? $user->lang['SUCCESS'] : '{ SUCCESS }')); ?></font>
					<?php } else { ?><font color="red"><?php echo ((isset($this->_rootref['L_FIND_MISSING'])) ? $this->_rootref['L_FIND_MISSING'] : ((isset($user->lang['FIND_MISSING'])) ? $user->lang['FIND_MISSING'] : '{ FIND_MISSING }')); ?></font><?php } } }} ?>

		</div>
		<?php }} ?>

	</div>
	<?php }} } } ?>