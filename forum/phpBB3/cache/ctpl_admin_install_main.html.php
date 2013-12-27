<?php if (!defined('IN_PHPBB')) exit; $this->_tpl_include('install_header.html'); ?>


	<h1><?php echo (isset($this->_rootref['TITLE'])) ? $this->_rootref['TITLE'] : ''; ?></h1>
	<p><?php echo (isset($this->_rootref['BODY'])) ? $this->_rootref['BODY'] : ''; ?></p>

<?php $this->_tpl_include('install_footer.html'); ?>