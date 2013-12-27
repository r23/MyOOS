<?php if (!defined('IN_PHPBB')) exit; $this->_tpl_include('install_header.html'); ?>


<form id="install_install" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>" onsubmit="submit.disabled = 'disabled';">

<?php if ($this->_rootref['TITLE']) {  ?><h1><?php echo (isset($this->_rootref['TITLE'])) ? $this->_rootref['TITLE'] : ''; ?></h1><?php } if ($this->_rootref['BODY']) {  ?><p><?php echo (isset($this->_rootref['BODY'])) ? $this->_rootref['BODY'] : ''; ?></p><?php } if (sizeof($this->_tpldata['checks'])) {  ?>

	<fieldset>

	<?php $_checks_count = (isset($this->_tpldata['checks'])) ? sizeof($this->_tpldata['checks']) : 0;if ($_checks_count) {for ($_checks_i = 0; $_checks_i < $_checks_count; ++$_checks_i){$_checks_val = &$this->_tpldata['checks'][$_checks_i]; if ($_checks_val['S_LEGEND']) {  if (! $_checks_val['S_FIRST_ROW']) {  ?>

			</fieldset>

			<fieldset>
			<?php } ?>

				<legend><?php echo $_checks_val['LEGEND']; ?></legend>
				<?php if ($_checks_val['LEGEND_EXPLAIN']) {  ?><p><?php echo $_checks_val['LEGEND_EXPLAIN']; ?></p><?php } } else { ?>


			<dl>
				<dt><?php echo $_checks_val['TITLE']; ?>:<?php if ($_checks_val['S_EXPLAIN']) {  ?><br /><span class="explain"><?php echo $_checks_val['TITLE_EXPLAIN']; ?></span><?php } ?></dt>
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
<?php } if ($this->_rootref['S_SHOW_DOWNLOAD']) {  ?>

	<h1><?php echo ((isset($this->_rootref['L_DL_CONFIG'])) ? $this->_rootref['L_DL_CONFIG'] : ((isset($user->lang['DL_CONFIG'])) ? $user->lang['DL_CONFIG'] : '{ DL_CONFIG }')); ?></h1>
	<p><?php echo ((isset($this->_rootref['L_DL_CONFIG_EXPLAIN'])) ? $this->_rootref['L_DL_CONFIG_EXPLAIN'] : ((isset($user->lang['DL_CONFIG_EXPLAIN'])) ? $user->lang['DL_CONFIG_EXPLAIN'] : '{ DL_CONFIG_EXPLAIN }')); ?></p>

	<fieldset class="submit-buttons">
		<legend><?php echo ((isset($this->_rootref['L_DL_CONFIG'])) ? $this->_rootref['L_DL_CONFIG'] : ((isset($user->lang['DL_CONFIG'])) ? $user->lang['DL_CONFIG'] : '{ DL_CONFIG }')); ?></legend>
		<?php echo (isset($this->_rootref['S_HIDDEN'])) ? $this->_rootref['S_HIDDEN'] : ''; ?>

		<input class="button1" type="submit" id="dlconfig" name="dlconfig" value="<?php echo ((isset($this->_rootref['L_DL_DOWNLOAD'])) ? $this->_rootref['L_DL_DOWNLOAD'] : ((isset($user->lang['DL_DOWNLOAD'])) ? $user->lang['DL_DOWNLOAD'] : '{ DL_DOWNLOAD }')); ?>" />&nbsp;<input class="button1" type="submit" id="dldone" name="dldone" value="<?php echo ((isset($this->_rootref['L_DL_DONE'])) ? $this->_rootref['L_DL_DONE'] : ((isset($user->lang['DL_DONE'])) ? $user->lang['DL_DONE'] : '{ DL_DONE }')); ?>" />
	</fieldset>
<?php } if ($this->_rootref['L_SUBMIT']) {  ?>

	<fieldset class="submit-buttons">
		<legend><?php echo ((isset($this->_rootref['L_SUBMIT'])) ? $this->_rootref['L_SUBMIT'] : ((isset($user->lang['SUBMIT'])) ? $user->lang['SUBMIT'] : '{ SUBMIT }')); ?></legend>
		<?php echo (isset($this->_rootref['S_HIDDEN'])) ? $this->_rootref['S_HIDDEN'] : ''; ?>

		<?php if ($this->_rootref['L_SUBMIT']) {  ?><input class="button1" type="submit" id="submit" onclick="this.className = 'button1 disabled';" name="submit" value="<?php echo ((isset($this->_rootref['L_SUBMIT'])) ? $this->_rootref['L_SUBMIT'] : ((isset($user->lang['SUBMIT'])) ? $user->lang['SUBMIT'] : '{ SUBMIT }')); ?>" /><?php } ?>

	</fieldset>
<?php } ?>


</form>

<?php $this->_tpl_include('install_footer.html'); ?>