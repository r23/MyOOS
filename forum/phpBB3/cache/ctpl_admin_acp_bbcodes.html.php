<?php if (!defined('IN_PHPBB')) exit; $this->_tpl_include('overall_header.html'); ?>


<a name="maincontent"></a>

<?php if ($this->_rootref['S_EDIT_BBCODE']) {  ?>


	<a href="<?php echo (isset($this->_rootref['U_BACK'])) ? $this->_rootref['U_BACK'] : ''; ?>" style="float: <?php echo (isset($this->_rootref['S_CONTENT_FLOW_END'])) ? $this->_rootref['S_CONTENT_FLOW_END'] : ''; ?>;">&laquo; <?php echo ((isset($this->_rootref['L_BACK'])) ? $this->_rootref['L_BACK'] : ((isset($user->lang['BACK'])) ? $user->lang['BACK'] : '{ BACK }')); ?></a>

	<h1><?php echo ((isset($this->_rootref['L_ACP_BBCODES'])) ? $this->_rootref['L_ACP_BBCODES'] : ((isset($user->lang['ACP_BBCODES'])) ? $user->lang['ACP_BBCODES'] : '{ ACP_BBCODES }')); ?></h1>

	<p><?php echo ((isset($this->_rootref['L_ACP_BBCODES_EXPLAIN'])) ? $this->_rootref['L_ACP_BBCODES_EXPLAIN'] : ((isset($user->lang['ACP_BBCODES_EXPLAIN'])) ? $user->lang['ACP_BBCODES_EXPLAIN'] : '{ ACP_BBCODES_EXPLAIN }')); ?></p>

	<form id="acp_bbcodes" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_BBCODE_USAGE'])) ? $this->_rootref['L_BBCODE_USAGE'] : ((isset($user->lang['BBCODE_USAGE'])) ? $user->lang['BBCODE_USAGE'] : '{ BBCODE_USAGE }')); ?></legend>
		<p><?php echo ((isset($this->_rootref['L_BBCODE_USAGE_EXPLAIN'])) ? $this->_rootref['L_BBCODE_USAGE_EXPLAIN'] : ((isset($user->lang['BBCODE_USAGE_EXPLAIN'])) ? $user->lang['BBCODE_USAGE_EXPLAIN'] : '{ BBCODE_USAGE_EXPLAIN }')); ?></p>
	<dl>
		<dt><label for="bbcode_match"><?php echo ((isset($this->_rootref['L_EXAMPLES'])) ? $this->_rootref['L_EXAMPLES'] : ((isset($user->lang['EXAMPLES'])) ? $user->lang['EXAMPLES'] : '{ EXAMPLES }')); ?></label><br /><br /><span><?php echo ((isset($this->_rootref['L_BBCODE_USAGE_EXAMPLE'])) ? $this->_rootref['L_BBCODE_USAGE_EXAMPLE'] : ((isset($user->lang['BBCODE_USAGE_EXAMPLE'])) ? $user->lang['BBCODE_USAGE_EXAMPLE'] : '{ BBCODE_USAGE_EXAMPLE }')); ?></span></dt>
		<dd><textarea id="bbcode_match" name="bbcode_match" cols="60" rows="5"><?php echo (isset($this->_rootref['BBCODE_MATCH'])) ? $this->_rootref['BBCODE_MATCH'] : ''; ?></textarea></dd>
	</dl>
	</fieldset>

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_HTML_REPLACEMENT'])) ? $this->_rootref['L_HTML_REPLACEMENT'] : ((isset($user->lang['HTML_REPLACEMENT'])) ? $user->lang['HTML_REPLACEMENT'] : '{ HTML_REPLACEMENT }')); ?></legend>
		<p><?php echo ((isset($this->_rootref['L_HTML_REPLACEMENT_EXPLAIN'])) ? $this->_rootref['L_HTML_REPLACEMENT_EXPLAIN'] : ((isset($user->lang['HTML_REPLACEMENT_EXPLAIN'])) ? $user->lang['HTML_REPLACEMENT_EXPLAIN'] : '{ HTML_REPLACEMENT_EXPLAIN }')); ?></p>
	<dl>
		<dt><label for="bbcode_tpl"><?php echo ((isset($this->_rootref['L_EXAMPLES'])) ? $this->_rootref['L_EXAMPLES'] : ((isset($user->lang['EXAMPLES'])) ? $user->lang['EXAMPLES'] : '{ EXAMPLES }')); ?></label><br /><br /><span><?php echo ((isset($this->_rootref['L_HTML_REPLACEMENT_EXAMPLE'])) ? $this->_rootref['L_HTML_REPLACEMENT_EXAMPLE'] : ((isset($user->lang['HTML_REPLACEMENT_EXAMPLE'])) ? $user->lang['HTML_REPLACEMENT_EXAMPLE'] : '{ HTML_REPLACEMENT_EXAMPLE }')); ?></span></dt>
		<dd><textarea id="bbcode_tpl" name="bbcode_tpl" cols="60" rows="8"><?php echo (isset($this->_rootref['BBCODE_TPL'])) ? $this->_rootref['BBCODE_TPL'] : ''; ?></textarea></dd>
	</dl>
	</fieldset>

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_BBCODE_HELPLINE'])) ? $this->_rootref['L_BBCODE_HELPLINE'] : ((isset($user->lang['BBCODE_HELPLINE'])) ? $user->lang['BBCODE_HELPLINE'] : '{ BBCODE_HELPLINE }')); ?></legend>
		<p><?php echo ((isset($this->_rootref['L_BBCODE_HELPLINE_EXPLAIN'])) ? $this->_rootref['L_BBCODE_HELPLINE_EXPLAIN'] : ((isset($user->lang['BBCODE_HELPLINE_EXPLAIN'])) ? $user->lang['BBCODE_HELPLINE_EXPLAIN'] : '{ BBCODE_HELPLINE_EXPLAIN }')); ?></p>
	<dl>
		<dt><label for="bbcode_helpline"><?php echo ((isset($this->_rootref['L_BBCODE_HELPLINE_TEXT'])) ? $this->_rootref['L_BBCODE_HELPLINE_TEXT'] : ((isset($user->lang['BBCODE_HELPLINE_TEXT'])) ? $user->lang['BBCODE_HELPLINE_TEXT'] : '{ BBCODE_HELPLINE_TEXT }')); ?></label></dt>
		<dd><input type="text" id="bbcode_helpline" name="bbcode_helpline" size="60" maxlength="255" value="<?php echo (isset($this->_rootref['BBCODE_HELPLINE'])) ? $this->_rootref['BBCODE_HELPLINE'] : ''; ?>" /></dd>
	</dl>
	</fieldset>

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_SETTINGS'])) ? $this->_rootref['L_SETTINGS'] : ((isset($user->lang['SETTINGS'])) ? $user->lang['SETTINGS'] : '{ SETTINGS }')); ?></legend>
	<dl>
		<dt><label for="display_on_posting"><?php echo ((isset($this->_rootref['L_DISPLAY_ON_POSTING'])) ? $this->_rootref['L_DISPLAY_ON_POSTING'] : ((isset($user->lang['DISPLAY_ON_POSTING'])) ? $user->lang['DISPLAY_ON_POSTING'] : '{ DISPLAY_ON_POSTING }')); ?></label></dt>
		<dd><input type="checkbox" class="radio" name="display_on_posting" id="display_on_posting" value="1"<?php if ($this->_rootref['DISPLAY_ON_POSTING']) {  ?> checked="checked"<?php } ?> /></dd>
	</dl>
	</fieldset>

	<fieldset class="submit-buttons">
		<legend><?php echo ((isset($this->_rootref['L_SUBMIT'])) ? $this->_rootref['L_SUBMIT'] : ((isset($user->lang['SUBMIT'])) ? $user->lang['SUBMIT'] : '{ SUBMIT }')); ?></legend>
		<input class="button1" type="submit" id="submit" name="submit" value="<?php echo ((isset($this->_rootref['L_SUBMIT'])) ? $this->_rootref['L_SUBMIT'] : ((isset($user->lang['SUBMIT'])) ? $user->lang['SUBMIT'] : '{ SUBMIT }')); ?>" />&nbsp;
		<input class="button2" type="reset" id="reset" name="reset" value="<?php echo ((isset($this->_rootref['L_RESET'])) ? $this->_rootref['L_RESET'] : ((isset($user->lang['RESET'])) ? $user->lang['RESET'] : '{ RESET }')); ?>" />
		<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

	</fieldset>
	
	<br />

	<table cellspacing="1" id="down">
	<thead>
	<tr>
		<th colspan="2"><?php echo ((isset($this->_rootref['L_TOKENS'])) ? $this->_rootref['L_TOKENS'] : ((isset($user->lang['TOKENS'])) ? $user->lang['TOKENS'] : '{ TOKENS }')); ?></th>
	</tr>
	<tr>
		<td class="row3" colspan="2"><?php echo ((isset($this->_rootref['L_TOKENS_EXPLAIN'])) ? $this->_rootref['L_TOKENS_EXPLAIN'] : ((isset($user->lang['TOKENS_EXPLAIN'])) ? $user->lang['TOKENS_EXPLAIN'] : '{ TOKENS_EXPLAIN }')); ?></td>
	</tr>
	<tr>
		<th><?php echo ((isset($this->_rootref['L_TOKEN'])) ? $this->_rootref['L_TOKEN'] : ((isset($user->lang['TOKEN'])) ? $user->lang['TOKEN'] : '{ TOKEN }')); ?></th>
		<th><?php echo ((isset($this->_rootref['L_TOKEN_DEFINITION'])) ? $this->_rootref['L_TOKEN_DEFINITION'] : ((isset($user->lang['TOKEN_DEFINITION'])) ? $user->lang['TOKEN_DEFINITION'] : '{ TOKEN_DEFINITION }')); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php $_token_count = (isset($this->_tpldata['token'])) ? sizeof($this->_tpldata['token']) : 0;if ($_token_count) {for ($_token_i = 0; $_token_i < $_token_count; ++$_token_i){$_token_val = &$this->_tpldata['token'][$_token_i]; ?>

		<tr valign="top">
			<td class="row1"><?php echo $_token_val['TOKEN']; ?></td>
			<td class="row2"><?php echo $_token_val['EXPLAIN']; ?></td>
		</tr>
	<?php }} ?>

	</tbody>
	</table>
	</form>

<?php } else { ?>


	<h1><?php echo ((isset($this->_rootref['L_ACP_BBCODES'])) ? $this->_rootref['L_ACP_BBCODES'] : ((isset($user->lang['ACP_BBCODES'])) ? $user->lang['ACP_BBCODES'] : '{ ACP_BBCODES }')); ?></h1>

	<p><?php echo ((isset($this->_rootref['L_ACP_BBCODES_EXPLAIN'])) ? $this->_rootref['L_ACP_BBCODES_EXPLAIN'] : ((isset($user->lang['ACP_BBCODES_EXPLAIN'])) ? $user->lang['ACP_BBCODES_EXPLAIN'] : '{ ACP_BBCODES_EXPLAIN }')); ?></p>
	
	<form id="acp_bbcodes" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">
	<fieldset class="tabulated">
	<legend><?php echo ((isset($this->_rootref['L_ACP_BBCODES'])) ? $this->_rootref['L_ACP_BBCODES'] : ((isset($user->lang['ACP_BBCODES'])) ? $user->lang['ACP_BBCODES'] : '{ ACP_BBCODES }')); ?></legend>

	<table cellspacing="1" id="down">
	<thead>
	<tr>
		<th><?php echo ((isset($this->_rootref['L_BBCODE_TAG'])) ? $this->_rootref['L_BBCODE_TAG'] : ((isset($user->lang['BBCODE_TAG'])) ? $user->lang['BBCODE_TAG'] : '{ BBCODE_TAG }')); ?></th>
		<th><?php echo ((isset($this->_rootref['L_ACTION'])) ? $this->_rootref['L_ACTION'] : ((isset($user->lang['ACTION'])) ? $user->lang['ACTION'] : '{ ACTION }')); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php $_bbcodes_count = (isset($this->_tpldata['bbcodes'])) ? sizeof($this->_tpldata['bbcodes']) : 0;if ($_bbcodes_count) {for ($_bbcodes_i = 0; $_bbcodes_i < $_bbcodes_count; ++$_bbcodes_i){$_bbcodes_val = &$this->_tpldata['bbcodes'][$_bbcodes_i]; if (!($_bbcodes_val['S_ROW_COUNT'] & 1)  ) {  ?><tr class="row1"><?php } else { ?><tr class="row2"><?php } ?>

			<td style="text-align: center;"><?php echo $_bbcodes_val['BBCODE_TAG']; ?></td>
			<td style="text-align: right; width: 40px;"><a href="<?php echo $_bbcodes_val['U_EDIT']; ?>"><?php echo (isset($this->_rootref['ICON_EDIT'])) ? $this->_rootref['ICON_EDIT'] : ''; ?></a> <a href="<?php echo $_bbcodes_val['U_DELETE']; ?>"><?php echo (isset($this->_rootref['ICON_DELETE'])) ? $this->_rootref['ICON_DELETE'] : ''; ?></a></td>
		</tr>
	<?php }} else { ?>

		<tr class="row3">
			<td colspan="2"><?php echo ((isset($this->_rootref['L_ACP_NO_ITEMS'])) ? $this->_rootref['L_ACP_NO_ITEMS'] : ((isset($user->lang['ACP_NO_ITEMS'])) ? $user->lang['ACP_NO_ITEMS'] : '{ ACP_NO_ITEMS }')); ?></td>
		</tr>
	<?php } ?>

	</tbody>
	</table>

	<p class="quick">
		<input class="button2" name="submit" type="submit" value="<?php echo ((isset($this->_rootref['L_ADD_BBCODE'])) ? $this->_rootref['L_ADD_BBCODE'] : ((isset($user->lang['ADD_BBCODE'])) ? $user->lang['ADD_BBCODE'] : '{ ADD_BBCODE }')); ?>" />
	</p>
	<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

	</fieldset>

	</form>

<?php } $this->_tpl_include('overall_footer.html'); ?>