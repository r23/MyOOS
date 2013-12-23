<?php if (!defined('IN_PHPBB')) exit; ?><fieldset id="ftp_details" <?php if ($this->_rootref['S_HIDE_FTP']) {  ?>style="display: none"<?php } ?>>
		<legend><?php echo ((isset($this->_rootref['L_FTP_SETTINGS'])) ? $this->_rootref['L_FTP_SETTINGS'] : ((isset($user->lang['FTP_SETTINGS'])) ? $user->lang['FTP_SETTINGS'] : '{ FTP_SETTINGS }')); ?></legend>
		<dl>
			<dt><label><?php echo ((isset($this->_rootref['L_UPLOAD_METHOD'])) ? $this->_rootref['L_UPLOAD_METHOD'] : ((isset($user->lang['UPLOAD_METHOD'])) ? $user->lang['UPLOAD_METHOD'] : '{ UPLOAD_METHOD }')); ?>:</label><br /><span><?php echo ((isset($this->_rootref['L_FTP_METHOD_EXPLAIN'])) ? $this->_rootref['L_FTP_METHOD_EXPLAIN'] : ((isset($user->lang['FTP_METHOD_EXPLAIN'])) ? $user->lang['FTP_METHOD_EXPLAIN'] : '{ FTP_METHOD_EXPLAIN }')); ?></span></dt>
			<dd><label><input type="radio" class="radio" name="ftp_method" value="ftp"<?php echo (isset($this->_rootref['UPLOAD_METHOD_FTP'])) ? $this->_rootref['UPLOAD_METHOD_FTP'] : ''; ?> /> <?php echo ((isset($this->_rootref['L_FTP_METHOD_FTP'])) ? $this->_rootref['L_FTP_METHOD_FTP'] : ((isset($user->lang['FTP_METHOD_FTP'])) ? $user->lang['FTP_METHOD_FTP'] : '{ FTP_METHOD_FTP }')); ?></label> 
				<label><input type="radio" class="radio" name="ftp_method" value="ftp_fsock"<?php echo (isset($this->_rootref['UPLOAD_METHOD_FSOCK'])) ? $this->_rootref['UPLOAD_METHOD_FSOCK'] : ''; ?> /> <?php echo ((isset($this->_rootref['L_FTP_METHOD_FSOCK'])) ? $this->_rootref['L_FTP_METHOD_FSOCK'] : ((isset($user->lang['FTP_METHOD_FSOCK'])) ? $user->lang['FTP_METHOD_FSOCK'] : '{ FTP_METHOD_FSOCK }')); ?></label></dd>
		</dl>
		<?php $_data_count = (isset($this->_tpldata['data'])) ? sizeof($this->_tpldata['data']) : 0;if ($_data_count) {for ($_data_i = 0; $_data_i < $_data_count; ++$_data_i){$_data_val = &$this->_tpldata['data'][$_data_i]; ?>

		<dl>
			<dt><label for="<?php echo $_data_val['DATA']; ?>"><?php echo $_data_val['NAME']; ?>:</label><br /><span><?php echo $_data_val['EXPLAIN']; ?></span></dt>
			<dd><input type="<?php if ($_data_val['DATA'] == ('password')) {  ?>password<?php } else { ?>text<?php } ?>" id="<?php echo $_data_val['DATA']; ?>" name="<?php echo $_data_val['DATA']; ?>" value="<?php echo $_data_val['DEFAULT']; ?>" /></dd>
		</dl>
		<?php }} ?>

		<?php echo (isset($this->_rootref['S_HIDDEN_FIELDS_FTP'])) ? $this->_rootref['S_HIDDEN_FIELDS_FTP'] : ''; ?>

	</fieldset>