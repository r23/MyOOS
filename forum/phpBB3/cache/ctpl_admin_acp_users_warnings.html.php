<?php if (!defined('IN_PHPBB')) exit; ?><form id="list" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">

	<?php if (sizeof($this->_tpldata['warn'])) {  ?>

	<table cellspacing="1">
	<thead>
	<tr>
		<th><?php echo ((isset($this->_rootref['L_REPORT_BY'])) ? $this->_rootref['L_REPORT_BY'] : ((isset($user->lang['REPORT_BY'])) ? $user->lang['REPORT_BY'] : '{ REPORT_BY }')); ?></th>
		<th><?php echo ((isset($this->_rootref['L_TIME'])) ? $this->_rootref['L_TIME'] : ((isset($user->lang['TIME'])) ? $user->lang['TIME'] : '{ TIME }')); ?></th>
		<th><?php echo ((isset($this->_rootref['L_FEEDBACK'])) ? $this->_rootref['L_FEEDBACK'] : ((isset($user->lang['FEEDBACK'])) ? $user->lang['FEEDBACK'] : '{ FEEDBACK }')); ?></th>
		<th><?php echo ((isset($this->_rootref['L_MARK'])) ? $this->_rootref['L_MARK'] : ((isset($user->lang['MARK'])) ? $user->lang['MARK'] : '{ MARK }')); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php $_warn_count = (isset($this->_tpldata['warn'])) ? sizeof($this->_tpldata['warn']) : 0;if ($_warn_count) {for ($_warn_i = 0; $_warn_i < $_warn_count; ++$_warn_i){$_warn_val = &$this->_tpldata['warn'][$_warn_i]; if (!($_warn_val['S_ROW_COUNT'] & 1)  ) {  ?><tr class="row1"><?php } else { ?><tr class="row2"><?php } ?>

			<td><?php echo $_warn_val['USERNAME']; ?></td>
			<td style="text-align: center; nowrap: nowrap;"><?php echo $_warn_val['DATE']; ?></td>
			<td><?php echo $_warn_val['ACTION']; ?></td>
			<td style="text-align: center;"><input type="checkbox" class="radio" name="mark[]" value="<?php echo $_warn_val['ID']; ?>" /></td>
		</tr>
	<?php }} ?>

	</tbody>
	</table>
	<?php } else { ?>

		<div class="errorbox">
			<p><?php echo ((isset($this->_rootref['L_NO_WARNINGS'])) ? $this->_rootref['L_NO_WARNINGS'] : ((isset($user->lang['NO_WARNINGS'])) ? $user->lang['NO_WARNINGS'] : '{ NO_WARNINGS }')); ?></p>
		</div>
	<?php } ?>


	<fieldset class="quick">
		<input class="button2" type="submit" name="delall" value="<?php echo ((isset($this->_rootref['L_DELETE_ALL'])) ? $this->_rootref['L_DELETE_ALL'] : ((isset($user->lang['DELETE_ALL'])) ? $user->lang['DELETE_ALL'] : '{ DELETE_ALL }')); ?>" />&nbsp;
		<input class="button2" type="submit" name="delmarked" value="<?php echo ((isset($this->_rootref['L_DELETE_MARKED'])) ? $this->_rootref['L_DELETE_MARKED'] : ((isset($user->lang['DELETE_MARKED'])) ? $user->lang['DELETE_MARKED'] : '{ DELETE_MARKED }')); ?>" />
		<p class="small"><a href="#" onclick="marklist('list', 'mark', true);"><?php echo ((isset($this->_rootref['L_MARK_ALL'])) ? $this->_rootref['L_MARK_ALL'] : ((isset($user->lang['MARK_ALL'])) ? $user->lang['MARK_ALL'] : '{ MARK_ALL }')); ?></a> &bull; <a href="#" onclick="marklist('list', 'mark', false);"><?php echo ((isset($this->_rootref['L_UNMARK_ALL'])) ? $this->_rootref['L_UNMARK_ALL'] : ((isset($user->lang['UNMARK_ALL'])) ? $user->lang['UNMARK_ALL'] : '{ UNMARK_ALL }')); ?></a></p>
	</fieldset>
	<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

	</form>