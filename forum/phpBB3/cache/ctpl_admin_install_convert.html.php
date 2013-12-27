<?php if (!defined('IN_PHPBB')) exit; $this->_tpl_include('install_header.html'); if ($this->_rootref['S_NOT_INSTALLED']) {  ?>


	<h1><?php echo (isset($this->_rootref['TITLE'])) ? $this->_rootref['TITLE'] : ''; ?></h1>

	<p><?php echo (isset($this->_rootref['BODY'])) ? $this->_rootref['BODY'] : ''; ?></p>

<?php } else { ?>


	<form id="install_convert" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">

	<h1><?php echo (isset($this->_rootref['TITLE'])) ? $this->_rootref['TITLE'] : ''; ?></h1>

	<p><?php echo (isset($this->_rootref['BODY'])) ? $this->_rootref['BODY'] : ''; ?></p>

	<?php if ($this->_rootref['S_ERROR_BOX']) {  ?>

	<div class="errorbox">
		<h3><?php echo (isset($this->_rootref['ERROR_TITLE'])) ? $this->_rootref['ERROR_TITLE'] : ''; ?></h3>
		<p><?php echo (isset($this->_rootref['ERROR_MSG'])) ? $this->_rootref['ERROR_MSG'] : ''; ?></p>
	</div>
	<?php } if ($this->_rootref['S_LIST']) {  ?>

		<table cellspacing="1">
			<caption><?php echo ((isset($this->_rootref['L_AVAILABLE_CONVERTORS'])) ? $this->_rootref['L_AVAILABLE_CONVERTORS'] : ((isset($user->lang['AVAILABLE_CONVERTORS'])) ? $user->lang['AVAILABLE_CONVERTORS'] : '{ AVAILABLE_CONVERTORS }')); ?></caption>
			<col class="col1" /><col class="col2" /><col class="col1" /><col class="col2" />
		<thead>
		<tr>
			<th><?php echo ((isset($this->_rootref['L_SOFTWARE'])) ? $this->_rootref['L_SOFTWARE'] : ((isset($user->lang['SOFTWARE'])) ? $user->lang['SOFTWARE'] : '{ SOFTWARE }')); ?></th>
			<th><?php echo ((isset($this->_rootref['L_VERSION'])) ? $this->_rootref['L_VERSION'] : ((isset($user->lang['VERSION'])) ? $user->lang['VERSION'] : '{ VERSION }')); ?></th>
			<th><?php echo ((isset($this->_rootref['L_AUTHOR'])) ? $this->_rootref['L_AUTHOR'] : ((isset($user->lang['AUTHOR'])) ? $user->lang['AUTHOR'] : '{ AUTHOR }')); ?></th>
			<th><?php echo ((isset($this->_rootref['L_OPTIONS'])) ? $this->_rootref['L_OPTIONS'] : ((isset($user->lang['OPTIONS'])) ? $user->lang['OPTIONS'] : '{ OPTIONS }')); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php if (sizeof($this->_tpldata['convertors'])) {  $_convertors_count = (isset($this->_tpldata['convertors'])) ? sizeof($this->_tpldata['convertors']) : 0;if ($_convertors_count) {for ($_convertors_i = 0; $_convertors_i < $_convertors_count; ++$_convertors_i){$_convertors_val = &$this->_tpldata['convertors'][$_convertors_i]; ?>

			<tr>
				<td><?php echo $_convertors_val['SOFTWARE']; ?></td>
				<td><?php echo $_convertors_val['VERSION']; ?></td>
				<td><?php echo $_convertors_val['AUTHOR']; ?></td>
				<td><a href="<?php echo $_convertors_val['U_CONVERT']; ?>"><?php echo ((isset($this->_rootref['L_CONVERT'])) ? $this->_rootref['L_CONVERT'] : ((isset($user->lang['CONVERT'])) ? $user->lang['CONVERT'] : '{ CONVERT }')); ?></a></td>
			</tr>
			<?php }} } else { ?>

			<tr>
				<td><?php echo ((isset($this->_rootref['L_NO_CONVERTORS'])) ? $this->_rootref['L_NO_CONVERTORS'] : ((isset($user->lang['NO_CONVERTORS'])) ? $user->lang['NO_CONVERTORS'] : '{ NO_CONVERTORS }')); ?></td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
			</tr>
		<?php } ?>

		</tbody>
		</table>
	<?php } if ($this->_rootref['S_CONTINUE']) {  ?>

		</form>

		<fieldset class="submit-buttons">
			<form method="post" action="<?php echo (isset($this->_rootref['U_NEW_ACTION'])) ? $this->_rootref['U_NEW_ACTION'] : ''; ?>">
				<input class="button1" type="submit" name="submit_new" value="<?php echo ((isset($this->_rootref['L_NEW'])) ? $this->_rootref['L_NEW'] : ((isset($user->lang['NEW'])) ? $user->lang['NEW'] : '{ NEW }')); ?>" />
			</form>
			<br />
			<form method="post" action="<?php echo (isset($this->_rootref['U_CONTINUE_ACTION'])) ? $this->_rootref['U_CONTINUE_ACTION'] : ''; ?>">
				<input class="button1" type="submit" name="submit_cont" value="<?php echo ((isset($this->_rootref['L_CONTINUE'])) ? $this->_rootref['L_CONTINUE'] : ((isset($user->lang['CONTINUE'])) ? $user->lang['CONTINUE'] : '{ CONTINUE }')); ?>" />
			</form>
		</fieldset>

		<form method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">
	<?php } if (sizeof($this->_tpldata['checks'])) {  ?>

		<fieldset>

		<?php $_checks_count = (isset($this->_tpldata['checks'])) ? sizeof($this->_tpldata['checks']) : 0;if ($_checks_count) {for ($_checks_i = 0; $_checks_i < $_checks_count; ++$_checks_i){$_checks_val = &$this->_tpldata['checks'][$_checks_i]; if ($_checks_val['S_LEGEND']) {  if (! $_checks_val['S_FIRST_ROW']) {  ?>

				</fieldset>

				<fieldset>
				<?php } ?>

					<legend><?php echo $_checks_val['LEGEND']; ?></legend>
					<?php if ($_checks_val['LEGEND_EXPLAIN']) {  ?><p><?php echo $_checks_val['LEGEND_EXPLAIN']; ?></p><?php } } else { ?>


				<dl>
					<dt><label><?php echo $_checks_val['TITLE']; ?>:</label><?php if ($_checks_val['S_EXPLAIN']) {  ?><br /><span class="explain"><?php echo $_checks_val['TITLE_EXPLAIN']; ?></span><?php } ?></dt>
					<dd><?php echo $_checks_val['RESULT']; ?></dd>
				</dl>
			<?php } }} ?>


		</fieldset>
	<?php } if (sizeof($this->_tpldata['options'])) {  ?>

		<fieldset>

		<?php $_options_count = (isset($this->_tpldata['options'])) ? sizeof($this->_tpldata['options']) : 0;if ($_options_count) {for ($_options_i = 0; $_options_i < $_options_count; ++$_options_i){$_options_val = &$this->_tpldata['options'][$_options_i]; if ($_options_val['S_LEGEND']) {  if (! $_options_val['S_FIRST_ROW']) {  ?>

					</fieldset>

					<fieldset>
				<?php } ?>

					<legend><?php echo $_options_val['LEGEND']; ?></legend>
			<?php } else { ?>


				<dl>
					<dt><label for="<?php echo $_options_val['KEY']; ?>"><?php echo $_options_val['TITLE']; ?>:</label><?php if ($_options_val['S_EXPLAIN']) {  ?><br /><span class="explain"><?php echo $_options_val['TITLE_EXPLAIN']; ?></span><?php } ?></dt>
					<dd><?php echo $_options_val['CONTENT']; ?></dd>
				</dl>

			<?php } }} ?>


		</fieldset>
	<?php } if ($this->_rootref['L_SUBMIT']) {  if ($this->_rootref['L_MESSAGE']) {  ?><p><?php echo ((isset($this->_rootref['L_MESSAGE'])) ? $this->_rootref['L_MESSAGE'] : ((isset($user->lang['MESSAGE'])) ? $user->lang['MESSAGE'] : '{ MESSAGE }')); ?></p><?php } ?>


		<fieldset class="submit-buttons">
			<?php echo (isset($this->_rootref['S_HIDDEN'])) ? $this->_rootref['S_HIDDEN'] : ''; ?>

			<?php if ($this->_rootref['L_SUBMIT']) {  ?><input class="button1<?php if ($this->_rootref['S_REFRESH']) {  ?> disabled<?php } ?>" type="submit" id="submit" <?php if ($this->_rootref['S_REFRESH']) {  ?>disabled="disabled" <?php } else { ?> onclick="this.className = 'button1 disabled';" onsubmit="this.disabled = 'disabled';" <?php } ?>name="submit" value="<?php echo ((isset($this->_rootref['L_SUBMIT'])) ? $this->_rootref['L_SUBMIT'] : ((isset($user->lang['SUBMIT'])) ? $user->lang['SUBMIT'] : '{ SUBMIT }')); ?>" /><?php } ?>

		</fieldset>
	<?php } ?>


	</form>
<?php } $this->_tpl_include('install_footer.html'); ?>