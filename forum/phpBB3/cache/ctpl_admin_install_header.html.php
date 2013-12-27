<?php if (!defined('IN_PHPBB')) exit; ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo (isset($this->_rootref['S_CONTENT_DIRECTION'])) ? $this->_rootref['S_CONTENT_DIRECTION'] : ''; ?>" lang="<?php echo (isset($this->_rootref['S_USER_LANG'])) ? $this->_rootref['S_USER_LANG'] : ''; ?>" xml:lang="<?php echo (isset($this->_rootref['S_USER_LANG'])) ? $this->_rootref['S_USER_LANG'] : ''; ?>">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo (isset($this->_rootref['S_CONTENT_ENCODING'])) ? $this->_rootref['S_CONTENT_ENCODING'] : ''; ?>" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Language" content="<?php echo (isset($this->_rootref['S_USER_LANG'])) ? $this->_rootref['S_USER_LANG'] : ''; ?>" />
<meta http-equiv="imagetoolbar" content="no" />
<?php if ($this->_rootref['META']) {  echo (isset($this->_rootref['META'])) ? $this->_rootref['META'] : ''; } ?>

<title><?php echo (isset($this->_rootref['PAGE_TITLE'])) ? $this->_rootref['PAGE_TITLE'] : ''; ?></title>

<link href="../adm/style/admin.css" rel="stylesheet" type="text/css" media="screen" />

<script type="text/javascript">
// <![CDATA[

/**
* Set display of page element
* s[-1,0,1] = hide,toggle display,show
*/
function dE(n, s, type)
{
	if (!type)
	{
		type = 'block';
	}

	var e = document.getElementById(n);
	if (!s)
	{
		s = (e.style.display == '' || e.style.display == 'block') ? -1 : 1;
	}
	e.style.display = (s == 1) ? type : 'none';
}

// ]]>
</script>

</head>

<body class="<?php echo (isset($this->_rootref['S_CONTENT_DIRECTION'])) ? $this->_rootref['S_CONTENT_DIRECTION'] : ''; ?>">
<div id="wrap">
	<div id="page-header">
		<h1><?php echo ((isset($this->_rootref['L_INSTALL_PANEL'])) ? $this->_rootref['L_INSTALL_PANEL'] : ((isset($user->lang['INSTALL_PANEL'])) ? $user->lang['INSTALL_PANEL'] : '{ INSTALL_PANEL }')); ?></h1>
		<p id="skip"><a href="#acp"><?php echo ((isset($this->_rootref['L_SKIP'])) ? $this->_rootref['L_SKIP'] : ((isset($user->lang['SKIP'])) ? $user->lang['SKIP'] : '{ SKIP }')); ?></a></p>
		<?php if ($this->_rootref['S_LANG_SELECT']) {  ?>

		<form method="post" action="">
			<fieldset class="nobg">
				<label for="language"><?php echo ((isset($this->_rootref['L_SELECT_LANG'])) ? $this->_rootref['L_SELECT_LANG'] : ((isset($user->lang['SELECT_LANG'])) ? $user->lang['SELECT_LANG'] : '{ SELECT_LANG }')); ?>:</label>
				<?php echo (isset($this->_rootref['S_LANG_SELECT'])) ? $this->_rootref['S_LANG_SELECT'] : ''; ?>

				<input class="button1" type="submit" id="change_lang" name="change_lang" value="<?php echo ((isset($this->_rootref['L_CHANGE'])) ? $this->_rootref['L_CHANGE'] : ((isset($user->lang['CHANGE'])) ? $user->lang['CHANGE'] : '{ CHANGE }')); ?>" />
			</fieldset>
		</form>
		<?php } ?>

	</div>
	
	<div id="page-body">
		<div id="tabs">
			<ul>
			<?php $_t_block1_count = (isset($this->_tpldata['t_block1'])) ? sizeof($this->_tpldata['t_block1']) : 0;if ($_t_block1_count) {for ($_t_block1_i = 0; $_t_block1_i < $_t_block1_count; ++$_t_block1_i){$_t_block1_val = &$this->_tpldata['t_block1'][$_t_block1_i]; ?>

				<li<?php if ($_t_block1_val['S_SELECTED']) {  ?> id="activetab"<?php } ?>><a href="<?php echo $_t_block1_val['U_TITLE']; ?>"><span><?php echo $_t_block1_val['L_TITLE']; ?></span></a></li>
			<?php }} ?>

			</ul>
		</div>

		<div id="acp">
		<div class="panel">
			<span class="corners-top"><span></span></span>
				<div id="content">
					<div id="menu">
						<ul>
						<?php $_l_block1_count = (isset($this->_tpldata['l_block1'])) ? sizeof($this->_tpldata['l_block1']) : 0;if ($_l_block1_count) {for ($_l_block1_i = 0; $_l_block1_i < $_l_block1_count; ++$_l_block1_i){$_l_block1_val = &$this->_tpldata['l_block1'][$_l_block1_i]; ?>

							<li<?php if ($_l_block1_val['S_SELECTED']) {  ?> id="activemenu"<?php } ?>><a href="<?php echo $_l_block1_val['U_TITLE']; ?>"><span><?php echo $_l_block1_val['L_TITLE']; ?></span></a></li>
						<?php }} $_l_block2_count = (isset($this->_tpldata['l_block2'])) ? sizeof($this->_tpldata['l_block2']) : 0;if ($_l_block2_count) {for ($_l_block2_i = 0; $_l_block2_i < $_l_block2_count; ++$_l_block2_i){$_l_block2_val = &$this->_tpldata['l_block2'][$_l_block2_i]; ?>

							<li<?php if ($_l_block2_val['S_SELECTED']) {  ?> id="activemenu"<?php } ?>><span<?php if ($_l_block2_val['S_COMPLETE']) {  ?> class="completed"<?php } ?>><?php echo $_l_block2_val['L_TITLE']; ?></span></li>
						<?php }} ?>

						</ul>
					</div>
	
					<div id="main" class="install-body">