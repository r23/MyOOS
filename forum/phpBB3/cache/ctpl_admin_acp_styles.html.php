<?php if (!defined('IN_PHPBB')) exit; $this->_tpl_include('overall_header.html'); ?>


<a name="maincontent"></a>

<?php if ($this->_rootref['S_DELETE']) {  ?>


	<a href="<?php echo (isset($this->_rootref['U_BACK'])) ? $this->_rootref['U_BACK'] : ''; ?>" style="float: <?php echo (isset($this->_rootref['S_CONTENT_FLOW_END'])) ? $this->_rootref['S_CONTENT_FLOW_END'] : ''; ?>;">&laquo; <?php echo ((isset($this->_rootref['L_BACK'])) ? $this->_rootref['L_BACK'] : ((isset($user->lang['BACK'])) ? $user->lang['BACK'] : '{ BACK }')); ?></a>

	<h1><?php echo ((isset($this->_rootref['L_TITLE'])) ? $this->_rootref['L_TITLE'] : ((isset($user->lang['TITLE'])) ? $user->lang['TITLE'] : '{ TITLE }')); ?></h1>

	<p><?php echo ((isset($this->_rootref['L_EXPLAIN'])) ? $this->_rootref['L_EXPLAIN'] : ((isset($user->lang['EXPLAIN'])) ? $user->lang['EXPLAIN'] : '{ EXPLAIN }')); ?></p>

	<form id="acp_styles" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_TITLE'])) ? $this->_rootref['L_TITLE'] : ((isset($user->lang['TITLE'])) ? $user->lang['TITLE'] : '{ TITLE }')); ?></legend>
	<dl>
		<dt><label><?php echo ((isset($this->_rootref['L_NAME'])) ? $this->_rootref['L_NAME'] : ((isset($user->lang['NAME'])) ? $user->lang['NAME'] : '{ NAME }')); ?>:</label></dt>
		<dd><strong><?php echo (isset($this->_rootref['NAME'])) ? $this->_rootref['NAME'] : ''; ?></strong></dd>
	</dl>
	<dl>
		<dt><label for="new_id"><?php echo ((isset($this->_rootref['L_REPLACE'])) ? $this->_rootref['L_REPLACE'] : ((isset($user->lang['REPLACE'])) ? $user->lang['REPLACE'] : '{ REPLACE }')); ?>:</label><br /><span><?php echo ((isset($this->_rootref['L_REPLACE_EXPLAIN'])) ? $this->_rootref['L_REPLACE_EXPLAIN'] : ((isset($user->lang['REPLACE_EXPLAIN'])) ? $user->lang['REPLACE_EXPLAIN'] : '{ REPLACE_EXPLAIN }')); ?></span></dt>
		<dd><select id="new_id" name="new_id"><?php echo (isset($this->_rootref['S_REPLACE_OPTIONS'])) ? $this->_rootref['S_REPLACE_OPTIONS'] : ''; ?></select></dd>
	</dl>
	<?php if ($this->_rootref['S_DELETE_STYLE']) {  ?>

		<hr />
		<dl>
			<dt><label for="new_template_id"><?php echo ((isset($this->_rootref['L_DELETE_TEMPLATE'])) ? $this->_rootref['L_DELETE_TEMPLATE'] : ((isset($user->lang['DELETE_TEMPLATE'])) ? $user->lang['DELETE_TEMPLATE'] : '{ DELETE_TEMPLATE }')); ?>:</label><br /><span><?php echo ((isset($this->_rootref['L_REPLACE_TEMPLATE_EXPLAIN'])) ? $this->_rootref['L_REPLACE_TEMPLATE_EXPLAIN'] : ((isset($user->lang['REPLACE_TEMPLATE_EXPLAIN'])) ? $user->lang['REPLACE_TEMPLATE_EXPLAIN'] : '{ REPLACE_TEMPLATE_EXPLAIN }')); ?></span></dt>
			<dd><select id="new_template_id" name="new_template_id"><?php echo (isset($this->_rootref['S_REPLACE_TEMPLATE_OPTIONS'])) ? $this->_rootref['S_REPLACE_TEMPLATE_OPTIONS'] : ''; ?></select></dd>
		</dl>
		<dl>
			<dt><label for="new_theme_id"><?php echo ((isset($this->_rootref['L_DELETE_THEME'])) ? $this->_rootref['L_DELETE_THEME'] : ((isset($user->lang['DELETE_THEME'])) ? $user->lang['DELETE_THEME'] : '{ DELETE_THEME }')); ?>:</label><br /><span><?php echo ((isset($this->_rootref['L_REPLACE_THEME_EXPLAIN'])) ? $this->_rootref['L_REPLACE_THEME_EXPLAIN'] : ((isset($user->lang['REPLACE_THEME_EXPLAIN'])) ? $user->lang['REPLACE_THEME_EXPLAIN'] : '{ REPLACE_THEME_EXPLAIN }')); ?></span></dt>
			<dd><select id="new_theme_id" name="new_theme_id"><?php echo (isset($this->_rootref['S_REPLACE_THEME_OPTIONS'])) ? $this->_rootref['S_REPLACE_THEME_OPTIONS'] : ''; ?></select></dd>
		</dl>
		<dl>
			<dt><label for="new_imageset_id"><?php echo ((isset($this->_rootref['L_DELETE_IMAGESET'])) ? $this->_rootref['L_DELETE_IMAGESET'] : ((isset($user->lang['DELETE_IMAGESET'])) ? $user->lang['DELETE_IMAGESET'] : '{ DELETE_IMAGESET }')); ?>:</label><br /><span><?php echo ((isset($this->_rootref['L_REPLACE_IMAGESET_EXPLAIN'])) ? $this->_rootref['L_REPLACE_IMAGESET_EXPLAIN'] : ((isset($user->lang['REPLACE_IMAGESET_EXPLAIN'])) ? $user->lang['REPLACE_IMAGESET_EXPLAIN'] : '{ REPLACE_IMAGESET_EXPLAIN }')); ?></span></dt>
			<dd><select id="new_imageset_id" name="new_imageset_id"><?php echo (isset($this->_rootref['S_REPLACE_IMAGESET_OPTIONS'])) ? $this->_rootref['S_REPLACE_IMAGESET_OPTIONS'] : ''; ?></select></dd>
		</dl>
	<?php } ?>


	<p class="quick">
		<input class="button1" type="submit" name="update" value="<?php echo ((isset($this->_rootref['L_DELETE'])) ? $this->_rootref['L_DELETE'] : ((isset($user->lang['DELETE'])) ? $user->lang['DELETE'] : '{ DELETE }')); ?>" />
		<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

	</p>
	</fieldset>
	</form>

<?php } else if ($this->_rootref['S_EDIT_IMAGESET']) {  ?>


	<a href="<?php echo (isset($this->_rootref['U_BACK'])) ? $this->_rootref['U_BACK'] : ''; ?>" style="float: <?php echo (isset($this->_rootref['S_CONTENT_FLOW_END'])) ? $this->_rootref['S_CONTENT_FLOW_END'] : ''; ?>;">&laquo; <?php echo ((isset($this->_rootref['L_BACK'])) ? $this->_rootref['L_BACK'] : ((isset($user->lang['BACK'])) ? $user->lang['BACK'] : '{ BACK }')); ?></a>

	<h1><?php echo ((isset($this->_rootref['L_TITLE'])) ? $this->_rootref['L_TITLE'] : ((isset($user->lang['TITLE'])) ? $user->lang['TITLE'] : '{ TITLE }')); ?></h1>

	<p><?php echo ((isset($this->_rootref['L_EXPLAIN'])) ? $this->_rootref['L_EXPLAIN'] : ((isset($user->lang['EXPLAIN'])) ? $user->lang['EXPLAIN'] : '{ EXPLAIN }')); ?></p>

	<?php if ($this->_rootref['SUCCESS']) {  ?>

		<div class="successbox">
			<p><?php echo ((isset($this->_rootref['L_IMAGESET_UPDATED'])) ? $this->_rootref['L_IMAGESET_UPDATED'] : ((isset($user->lang['IMAGESET_UPDATED'])) ? $user->lang['IMAGESET_UPDATED'] : '{ IMAGESET_UPDATED }')); ?></p>
		</div>
	<?php } if ($this->_rootref['ERROR']) {  ?>

		<div class="errorbox">
			<p><?php echo ((isset($this->_rootref['L_NO_IMAGE'])) ? $this->_rootref['L_NO_IMAGE'] : ((isset($user->lang['NO_IMAGE'])) ? $user->lang['NO_IMAGE'] : '{ NO_IMAGE }')); ?></p>
		</div>
	<?php } ?>


	<script type="text/javascript" defer="defer">
	// <![CDATA[
		function update_image(newimage)
		{
			document.getElementById('newimg').src = (newimage) ? '../styles/<?php echo (isset($this->_rootref['A_PATH'])) ? $this->_rootref['A_PATH'] : ''; ?>/imageset/' + encodeURI(newimage) : 'images/no_image.png';
		}
	// ]]>
	</script>
	<script type="text/javascript">
	// <![CDATA[
		/**
		* Handle displaying/hiding the dimension fields
		*/
		function display_options(value)
		{
			if (value == 0)
			{
				dE('img_dimensions', -1);
			}
			else
			{
				dE('img_dimensions', 1);
			}
		}

		/**
		* Init the wanted display functionality if javascript is enabled.
		* If javascript is not available, the user is still able to properly administer.
		*/
		onload = function()
		{
			<?php if (! $this->_rootref['IMAGE_SIZE']) {  ?>

				dE('img_dimensions', -1);
			<?php } ?>

		}
	// ]]>
	</script>

	<form method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">

	<fieldset class="quick" style="text-align: left;">
		<legend><?php echo ((isset($this->_rootref['L_SELECT_IMAGE'])) ? $this->_rootref['L_SELECT_IMAGE'] : ((isset($user->lang['SELECT_IMAGE'])) ? $user->lang['SELECT_IMAGE'] : '{ SELECT_IMAGE }')); ?></legend>
		<?php echo ((isset($this->_rootref['L_SELECT_IMAGE'])) ? $this->_rootref['L_SELECT_IMAGE'] : ((isset($user->lang['SELECT_IMAGE'])) ? $user->lang['SELECT_IMAGE'] : '{ SELECT_IMAGE }')); ?>: <select name="imgname" onchange="this.form.submit();">
		<?php $_category_count = (isset($this->_tpldata['category'])) ? sizeof($this->_tpldata['category']) : 0;if ($_category_count) {for ($_category_i = 0; $_category_i < $_category_count; ++$_category_i){$_category_val = &$this->_tpldata['category'][$_category_i]; ?>

			<option class="sep" value="" disabled="disabled"><?php echo $_category_val['NAME']; ?></option>
				<?php $_images_count = (isset($_category_val['images'])) ? sizeof($_category_val['images']) : 0;if ($_images_count) {for ($_images_i = 0; $_images_i < $_images_count; ++$_images_i){$_images_val = &$_category_val['images'][$_images_i]; ?><option value="<?php echo $_images_val['VALUE']; ?>"<?php if ($_images_val['SELECTED']) {  ?> selected="selected"<?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $_images_val['TEXT']; ?></option>
				<?php }} }} ?>

		</select>&nbsp; <input class="button1" type="submit" value="<?php echo ((isset($this->_rootref['L_SELECT'])) ? $this->_rootref['L_SELECT'] : ((isset($user->lang['SELECT'])) ? $user->lang['SELECT'] : '{ SELECT }')); ?>" tabindex="100" />
	</fieldset>

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_EDIT_IMAGESET'])) ? $this->_rootref['L_EDIT_IMAGESET'] : ((isset($user->lang['EDIT_IMAGESET'])) ? $user->lang['EDIT_IMAGESET'] : '{ EDIT_IMAGESET }')); ?></legend>
	<dl>
		<dt><label><?php echo ((isset($this->_rootref['L_CURRENT_IMAGE'])) ? $this->_rootref['L_CURRENT_IMAGE'] : ((isset($user->lang['CURRENT_IMAGE'])) ? $user->lang['CURRENT_IMAGE'] : '{ CURRENT_IMAGE }')); ?>:</label></dt>
		<dd><img src="<?php if ($this->_rootref['IMAGE_REQUEST']) {  echo (isset($this->_rootref['IMAGE_REQUEST'])) ? $this->_rootref['IMAGE_REQUEST'] : ''; } else { ?>images/no_image.png<?php } ?>" alt="" /></dd>
	</dl>
	<dl>
		<dt><label><?php echo ((isset($this->_rootref['L_SELECTED_IMAGE'])) ? $this->_rootref['L_SELECTED_IMAGE'] : ((isset($user->lang['SELECTED_IMAGE'])) ? $user->lang['SELECTED_IMAGE'] : '{ SELECTED_IMAGE }')); ?>:</label></dt>
		<dd><img src="<?php echo (isset($this->_rootref['IMG_SRC'])) ? $this->_rootref['IMG_SRC'] : ''; ?>" id="newimg" alt="" /></dd>
	</dl>
	</fieldset>

	<fieldset>
	<legend><?php echo ((isset($this->_rootref['L_IMAGE'])) ? $this->_rootref['L_IMAGE'] : ((isset($user->lang['IMAGE'])) ? $user->lang['IMAGE'] : '{ IMAGE }')); ?></legend>
	<dl>
		<dt><label for="imgpath"><?php echo ((isset($this->_rootref['L_IMAGE'])) ? $this->_rootref['L_IMAGE'] : ((isset($user->lang['IMAGE'])) ? $user->lang['IMAGE'] : '{ IMAGE }')); ?>:</label></dt>
		<dd><select id="imgpath" name="imgpath" onchange="update_image(this.options[selectedIndex].value);"><option value=""<?php if (! $this->_rootref['IMAGE_SELECT']) {  ?> selected="selected"<?php } ?>><?php echo ((isset($this->_rootref['L_NO_IMAGE'])) ? $this->_rootref['L_NO_IMAGE'] : ((isset($user->lang['NO_IMAGE'])) ? $user->lang['NO_IMAGE'] : '{ NO_IMAGE }')); ?></option>
			<?php $_imagesetlist_count = (isset($this->_tpldata['imagesetlist'])) ? sizeof($this->_tpldata['imagesetlist']) : 0;if ($_imagesetlist_count) {for ($_imagesetlist_i = 0; $_imagesetlist_i < $_imagesetlist_count; ++$_imagesetlist_i){$_imagesetlist_val = &$this->_tpldata['imagesetlist'][$_imagesetlist_i]; ?>

			<option class="sep" value=""><?php if ($_imagesetlist_val['TYPE']) {  echo ((isset($this->_rootref['L_LOCALISED_IMAGES'])) ? $this->_rootref['L_LOCALISED_IMAGES'] : ((isset($user->lang['LOCALISED_IMAGES'])) ? $user->lang['LOCALISED_IMAGES'] : '{ LOCALISED_IMAGES }')); } else { echo ((isset($this->_rootref['L_GLOBAL_IMAGES'])) ? $this->_rootref['L_GLOBAL_IMAGES'] : ((isset($user->lang['GLOBAL_IMAGES'])) ? $user->lang['GLOBAL_IMAGES'] : '{ GLOBAL_IMAGES }')); } ?></option>
				<?php $_images_count = (isset($_imagesetlist_val['images'])) ? sizeof($_imagesetlist_val['images']) : 0;if ($_images_count) {for ($_images_i = 0; $_images_i < $_images_count; ++$_images_i){$_images_val = &$_imagesetlist_val['images'][$_images_i]; ?>

				<option value="<?php echo $_images_val['VALUE']; ?>"<?php if ($_images_val['SELECTED']) {  ?> selected="selected"<?php } ?>><?php echo $_images_val['TEXT']; ?></option>
				<?php }} }} ?>

			</select>
		</dd>
	</dl>
	<dl>
		<dt><label for="imgsize"><?php echo ((isset($this->_rootref['L_INCLUDE_DIMENSIONS'])) ? $this->_rootref['L_INCLUDE_DIMENSIONS'] : ((isset($user->lang['INCLUDE_DIMENSIONS'])) ? $user->lang['INCLUDE_DIMENSIONS'] : '{ INCLUDE_DIMENSIONS }')); ?>:</label><br /><span><?php echo ((isset($this->_rootref['L_DIMENSIONS_EXPLAIN'])) ? $this->_rootref['L_DIMENSIONS_EXPLAIN'] : ((isset($user->lang['DIMENSIONS_EXPLAIN'])) ? $user->lang['DIMENSIONS_EXPLAIN'] : '{ DIMENSIONS_EXPLAIN }')); ?></span></dt>
		<dd><label><input type="radio" class="radio" name="imgsize" id="imgsize" onclick="display_options(1);" value="1"<?php if ($this->_rootref['IMAGE_SIZE']) {  ?> checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label>
			<label><input type="radio" class="radio" name="imgsize" onclick="display_options(0);" value="0"<?php if (! $this->_rootref['IMAGE_SIZE']) {  ?> checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label></dd>
	</dl>
	<div id="img_dimensions">
		<dl>
			<dt><label for="imgwidth"><?php echo ((isset($this->_rootref['L_IMAGE_WIDTH'])) ? $this->_rootref['L_IMAGE_WIDTH'] : ((isset($user->lang['IMAGE_WIDTH'])) ? $user->lang['IMAGE_WIDTH'] : '{ IMAGE_WIDTH }')); ?>:</label><br /><span><?php echo ((isset($this->_rootref['L_AUTOMATIC_EXPLAIN'])) ? $this->_rootref['L_AUTOMATIC_EXPLAIN'] : ((isset($user->lang['AUTOMATIC_EXPLAIN'])) ? $user->lang['AUTOMATIC_EXPLAIN'] : '{ AUTOMATIC_EXPLAIN }')); ?></span></dt>
			<dd><input id="imgwidth" type="text" name="imgwidth" value="<?php echo (isset($this->_rootref['IMAGE_SIZE'])) ? $this->_rootref['IMAGE_SIZE'] : ''; ?>" /></dd>
		</dl>
		<dl>
			<dt><label for="imgheight"><?php echo ((isset($this->_rootref['L_IMAGE_HEIGHT'])) ? $this->_rootref['L_IMAGE_HEIGHT'] : ((isset($user->lang['IMAGE_HEIGHT'])) ? $user->lang['IMAGE_HEIGHT'] : '{ IMAGE_HEIGHT }')); ?>:</label><br /><span><?php echo ((isset($this->_rootref['L_AUTOMATIC_EXPLAIN'])) ? $this->_rootref['L_AUTOMATIC_EXPLAIN'] : ((isset($user->lang['AUTOMATIC_EXPLAIN'])) ? $user->lang['AUTOMATIC_EXPLAIN'] : '{ AUTOMATIC_EXPLAIN }')); ?></span></dt>
			<dd><input id="imgheight" type="text" name="imgheight" value="<?php echo (isset($this->_rootref['IMAGE_HEIGHT'])) ? $this->_rootref['IMAGE_HEIGHT'] : ''; ?>" /></dd>
		</dl>
	</div>
	</fieldset>

	<fieldset class="submit-buttons">
		<legend><?php echo ((isset($this->_rootref['L_SUBMIT'])) ? $this->_rootref['L_SUBMIT'] : ((isset($user->lang['SUBMIT'])) ? $user->lang['SUBMIT'] : '{ SUBMIT }')); ?></legend>
		<input class="button1" type="submit" name="update" value="<?php echo ((isset($this->_rootref['L_SUBMIT'])) ? $this->_rootref['L_SUBMIT'] : ((isset($user->lang['SUBMIT'])) ? $user->lang['SUBMIT'] : '{ SUBMIT }')); ?>" />&nbsp;&nbsp;<input class="button2" type="reset" value="<?php echo ((isset($this->_rootref['L_RESET'])) ? $this->_rootref['L_RESET'] : ((isset($user->lang['RESET'])) ? $user->lang['RESET'] : '{ RESET }')); ?>" />
		<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

	</fieldset>
	</form>

<?php } else if ($this->_rootref['S_EDIT_TEMPLATE'] || $this->_rootref['S_EDIT_THEME']) {  ?>


	<a href="<?php echo (isset($this->_rootref['U_BACK'])) ? $this->_rootref['U_BACK'] : ''; ?>" style="float: <?php echo (isset($this->_rootref['S_CONTENT_FLOW_END'])) ? $this->_rootref['S_CONTENT_FLOW_END'] : ''; ?>;">&laquo; <?php echo ((isset($this->_rootref['L_BACK'])) ? $this->_rootref['L_BACK'] : ((isset($user->lang['BACK'])) ? $user->lang['BACK'] : '{ BACK }')); ?></a>

	<h1><?php echo ((isset($this->_rootref['L_EDIT'])) ? $this->_rootref['L_EDIT'] : ((isset($user->lang['EDIT'])) ? $user->lang['EDIT'] : '{ EDIT }')); ?></h1>

	<p><?php echo ((isset($this->_rootref['L_EDIT_EXPLAIN'])) ? $this->_rootref['L_EDIT_EXPLAIN'] : ((isset($user->lang['EDIT_EXPLAIN'])) ? $user->lang['EDIT_EXPLAIN'] : '{ EDIT_EXPLAIN }')); ?></p>

	<p><?php echo ((isset($this->_rootref['L_SELECTED'])) ? $this->_rootref['L_SELECTED'] : ((isset($user->lang['SELECTED'])) ? $user->lang['SELECTED'] : '{ SELECTED }')); ?>: <strong><?php echo (isset($this->_rootref['SELECTED_TEMPLATE'])) ? $this->_rootref['SELECTED_TEMPLATE'] : ''; ?></strong></p>

	<form id="acp_styles" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">

	<?php if ($this->_rootref['S_EDIT_TEMPLATE'] || ( $this->_rootref['S_EDIT_THEME'] && ! $this->_rootref['S_THEME_IN_DB'] )) {  ?>

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_SELECT'])) ? $this->_rootref['L_SELECT'] : ((isset($user->lang['SELECT'])) ? $user->lang['SELECT'] : '{ SELECT }')); ?></legend>
	<dl>
		<dt><label for="template_file"><?php echo ((isset($this->_rootref['L_FILE'])) ? $this->_rootref['L_FILE'] : ((isset($user->lang['FILE'])) ? $user->lang['FILE'] : '{ FILE }')); ?>:</label></dt>
		<dd><select id="template_file" name="template_file" onchange="if (this.options[this.selectedIndex].value != '') this.form.submit();"><?php echo (isset($this->_rootref['S_TEMPLATES'])) ? $this->_rootref['S_TEMPLATES'] : ''; ?></select> <input class="button2" type="submit" value="<?php echo ((isset($this->_rootref['L_SELECT'])) ? $this->_rootref['L_SELECT'] : ((isset($user->lang['SELECT'])) ? $user->lang['SELECT'] : '{ SELECT }')); ?>" /></dd>
	</dl>
	<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

	</fieldset>
	<?php } ?>

	</form>

	<?php if ($this->_rootref['TEMPLATE_FILE'] || ( $this->_rootref['S_EDIT_THEME'] && $this->_rootref['S_THEME_IN_DB'] )) {  ?>

		<script type="text/javascript" defer="defer">
		// <![CDATA[

			function change_editor_height(height)
			{
				height = Number(height);

				if (isNaN(height))
				{
					return;
				}

				editor = document.getElementById('template_data');
				editor.rows = Math.max(5, Math.min(height, 999));

				append_text_rows('acp_styles', height);
				append_text_rows('acp_template', height);
			}

			function append_text_rows(form_name, value)
			{
				value = Number(value);

				if (isNaN(value))
				{
					return;
				}

				url = document.getElementById(form_name).action;

				// Make sure &amp; is actually... &
				url = url.replace(/&amp;/g, '&');

				var_start = url.indexOf('&text_rows=');
				if (var_start == -1)
				{
					document.getElementById(form_name).action = url + "&text_rows=" + value;
				}
				else
				{
					url_start = url.substring(0, var_start + 1);
					var_end = url.substring(var_start + 1).indexOf('&');
					if (var_end == -1)
					{
						document.getElementById(form_name).action = url_start + "text_rows=" + value;
					}
					else
					{
						document.getElementById(form_name).action = url_start + url.substring(var_end + var_start + 2) + "&text_rows=" + value;
					}
				}
			}

		// ]]>
		</script>

		<form id="acp_template" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">

		<fieldset>
			<legend><?php echo ((isset($this->_rootref['L_EDITOR'])) ? $this->_rootref['L_EDITOR'] : ((isset($user->lang['EDITOR'])) ? $user->lang['EDITOR'] : '{ EDITOR }')); ?></legend>
		<?php if ($this->_rootref['S_EDIT_TEMPLATE'] || ( $this->_rootref['S_EDIT_THEME'] && ! $this->_rootref['S_THEME_IN_DB'] )) {  ?>

		<dl>
			<dt><label><?php echo ((isset($this->_rootref['L_SELECTED_FILE'])) ? $this->_rootref['L_SELECTED_FILE'] : ((isset($user->lang['SELECTED_FILE'])) ? $user->lang['SELECTED_FILE'] : '{ SELECTED_FILE }')); ?>:</label></dt>
			<dd><?php echo (isset($this->_rootref['TEMPLATE_FILE'])) ? $this->_rootref['TEMPLATE_FILE'] : ''; ?></dd>
		</dl>
		<?php } ?>

		<dl>
			<dt><label for="text_rows"><?php echo ((isset($this->_rootref['L_EDITOR_HEIGHT'])) ? $this->_rootref['L_EDITOR_HEIGHT'] : ((isset($user->lang['EDITOR_HEIGHT'])) ? $user->lang['EDITOR_HEIGHT'] : '{ EDITOR_HEIGHT }')); ?>:</label></dt>
			<dd><input id="text_rows" type="text" maxlength="3" value="<?php echo (isset($this->_rootref['TEXT_ROWS'])) ? $this->_rootref['TEXT_ROWS'] : ''; ?>" /> <input class="button2" type="button" name="update" onclick="change_editor_height(this.form.text_rows.value);" value="<?php echo ((isset($this->_rootref['L_UPDATE'])) ? $this->_rootref['L_UPDATE'] : ((isset($user->lang['UPDATE'])) ? $user->lang['UPDATE'] : '{ UPDATE }')); ?>" /></dd>
		</dl>
		<textarea id="template_data" name="template_data" style="font-family:'Courier New', monospace;font-size:9pt;line-height:125%;width:100%;" cols="80" rows="<?php echo (isset($this->_rootref['TEXT_ROWS'])) ? $this->_rootref['TEXT_ROWS'] : ''; ?>"><?php echo (isset($this->_rootref['TEMPLATE_DATA'])) ? $this->_rootref['TEMPLATE_DATA'] : ''; ?></textarea>
		</fieldset>

		<fieldset class="submit-buttons">
			<legend><?php echo ((isset($this->_rootref['L_SUBMIT'])) ? $this->_rootref['L_SUBMIT'] : ((isset($user->lang['SUBMIT'])) ? $user->lang['SUBMIT'] : '{ SUBMIT }')); ?></legend>
			<?php echo (isset($this->_rootref['S_HIDDEN_FIELDS'])) ? $this->_rootref['S_HIDDEN_FIELDS'] : ''; ?>

			<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

			<input class="button1" id="save" type="submit" name="save" value="<?php echo ((isset($this->_rootref['L_SUBMIT'])) ? $this->_rootref['L_SUBMIT'] : ((isset($user->lang['SUBMIT'])) ? $user->lang['SUBMIT'] : '{ SUBMIT }')); ?>" />
		</fieldset>
		</form>
	<?php } } else if ($this->_rootref['S_CACHE']) {  ?>


	<a href="<?php echo (isset($this->_rootref['U_BACK'])) ? $this->_rootref['U_BACK'] : ''; ?>" style="float: <?php echo (isset($this->_rootref['S_CONTENT_FLOW_END'])) ? $this->_rootref['S_CONTENT_FLOW_END'] : ''; ?>;">&laquo; <?php echo ((isset($this->_rootref['L_BACK'])) ? $this->_rootref['L_BACK'] : ((isset($user->lang['BACK'])) ? $user->lang['BACK'] : '{ BACK }')); ?></a>

	<h1><?php echo ((isset($this->_rootref['L_TEMPLATE_CACHE'])) ? $this->_rootref['L_TEMPLATE_CACHE'] : ((isset($user->lang['TEMPLATE_CACHE'])) ? $user->lang['TEMPLATE_CACHE'] : '{ TEMPLATE_CACHE }')); ?></h1>

	<p><?php echo ((isset($this->_rootref['L_TEMPLATE_CACHE_EXPLAIN'])) ? $this->_rootref['L_TEMPLATE_CACHE_EXPLAIN'] : ((isset($user->lang['TEMPLATE_CACHE_EXPLAIN'])) ? $user->lang['TEMPLATE_CACHE_EXPLAIN'] : '{ TEMPLATE_CACHE_EXPLAIN }')); ?></p>

	<form id="acp_styles" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">
	<fieldset class="tabulated">
	<legend><?php echo ((isset($this->_rootref['L_TEMPLATE_CACHE'])) ? $this->_rootref['L_TEMPLATE_CACHE'] : ((isset($user->lang['TEMPLATE_CACHE'])) ? $user->lang['TEMPLATE_CACHE'] : '{ TEMPLATE_CACHE }')); ?></legend>

	<table cellspacing="1">
	<thead>
	<tr>
		<th><?php echo ((isset($this->_rootref['L_CACHE_FILENAME'])) ? $this->_rootref['L_CACHE_FILENAME'] : ((isset($user->lang['CACHE_FILENAME'])) ? $user->lang['CACHE_FILENAME'] : '{ CACHE_FILENAME }')); ?></th>
		<th><?php echo ((isset($this->_rootref['L_CACHE_FILESIZE'])) ? $this->_rootref['L_CACHE_FILESIZE'] : ((isset($user->lang['CACHE_FILESIZE'])) ? $user->lang['CACHE_FILESIZE'] : '{ CACHE_FILESIZE }')); ?></th>
		<th><?php echo ((isset($this->_rootref['L_CACHE_CACHED'])) ? $this->_rootref['L_CACHE_CACHED'] : ((isset($user->lang['CACHE_CACHED'])) ? $user->lang['CACHE_CACHED'] : '{ CACHE_CACHED }')); ?></th>
		<th><?php echo ((isset($this->_rootref['L_CACHE_MODIFIED'])) ? $this->_rootref['L_CACHE_MODIFIED'] : ((isset($user->lang['CACHE_MODIFIED'])) ? $user->lang['CACHE_MODIFIED'] : '{ CACHE_MODIFIED }')); ?></th>
		<th><?php echo ((isset($this->_rootref['L_MARK'])) ? $this->_rootref['L_MARK'] : ((isset($user->lang['MARK'])) ? $user->lang['MARK'] : '{ MARK }')); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php $_file_count = (isset($this->_tpldata['file'])) ? sizeof($this->_tpldata['file']) : 0;if ($_file_count) {for ($_file_i = 0; $_file_i < $_file_count; ++$_file_i){$_file_val = &$this->_tpldata['file'][$_file_i]; if (!($_file_val['S_ROW_COUNT'] & 1)  ) {  ?><tr class="row1"><?php } else { ?><tr class="row2"><?php } ?>

			<td><a href="<?php echo $_file_val['U_VIEWSOURCE']; ?>" onclick="popup(this.href, 750, 550, '_source'); return false;"><?php echo $_file_val['FILENAME_PATH']; ?></a></td>
			<td><?php echo $_file_val['FILESIZE']; ?></td>
			<td><?php echo $_file_val['CACHED']; ?></td>
			<td><?php echo $_file_val['MODIFIED']; ?></td>
			<td><input type="checkbox" class="radio" name="delete[]" value="<?php echo $_file_val['FILENAME']; ?>" /></td>
		</tr>
	<?php }} else { ?>

		<tr class="row1">
			<td colspan="5"><?php echo ((isset($this->_rootref['L_TEMPLATE_CACHE_EMPTY'])) ? $this->_rootref['L_TEMPLATE_CACHE_EMPTY'] : ((isset($user->lang['TEMPLATE_CACHE_EMPTY'])) ? $user->lang['TEMPLATE_CACHE_EMPTY'] : '{ TEMPLATE_CACHE_EMPTY }')); ?></td>
		</tr>
	<?php } ?>

	</tbody>
	</table>

	<p class="quick">
		<span class="small"><a href="#" onclick="marklist('acp_styles', 'delete', true); return false;"><?php echo ((isset($this->_rootref['L_MARK_ALL'])) ? $this->_rootref['L_MARK_ALL'] : ((isset($user->lang['MARK_ALL'])) ? $user->lang['MARK_ALL'] : '{ MARK_ALL }')); ?></a> :: <a href="#" onclick="marklist('acp_styles', 'delete', false); return false;"><?php echo ((isset($this->_rootref['L_UNMARK_ALL'])) ? $this->_rootref['L_UNMARK_ALL'] : ((isset($user->lang['UNMARK_ALL'])) ? $user->lang['UNMARK_ALL'] : '{ UNMARK_ALL }')); ?></a></span><br />
		<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

		<input class="button1" type="submit" id="submit" name="submit" value="<?php echo ((isset($this->_rootref['L_DELETE_MARKED'])) ? $this->_rootref['L_DELETE_MARKED'] : ((isset($user->lang['DELETE_MARKED'])) ? $user->lang['DELETE_MARKED'] : '{ DELETE_MARKED }')); ?>" />
	</p>
	</fieldset>
	</form>

<?php } else if ($this->_rootref['S_EXPORT']) {  ?>


	<a href="<?php echo (isset($this->_rootref['U_BACK'])) ? $this->_rootref['U_BACK'] : ''; ?>" style="float: <?php echo (isset($this->_rootref['S_CONTENT_FLOW_END'])) ? $this->_rootref['S_CONTENT_FLOW_END'] : ''; ?>;">&laquo; <?php echo ((isset($this->_rootref['L_BACK'])) ? $this->_rootref['L_BACK'] : ((isset($user->lang['BACK'])) ? $user->lang['BACK'] : '{ BACK }')); ?></a>

	<h1><?php echo ((isset($this->_rootref['L_TITLE'])) ? $this->_rootref['L_TITLE'] : ((isset($user->lang['TITLE'])) ? $user->lang['TITLE'] : '{ TITLE }')); ?></h1>

	<p><?php echo ((isset($this->_rootref['L_EXPLAIN'])) ? $this->_rootref['L_EXPLAIN'] : ((isset($user->lang['EXPLAIN'])) ? $user->lang['EXPLAIN'] : '{ EXPLAIN }')); ?></p>

	<?php if ($this->_rootref['S_ERROR_MSG']) {  ?>

		<div class="errorbox">
			<h3><?php echo ((isset($this->_rootref['L_WARNING'])) ? $this->_rootref['L_WARNING'] : ((isset($user->lang['WARNING'])) ? $user->lang['WARNING'] : '{ WARNING }')); ?></h3>
			<p><?php echo (isset($this->_rootref['ERROR_MSG'])) ? $this->_rootref['ERROR_MSG'] : ''; ?></p>
		</div>
	<?php } ?>


	<form id="acp_styles" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_TITLE'])) ? $this->_rootref['L_TITLE'] : ((isset($user->lang['TITLE'])) ? $user->lang['TITLE'] : '{ TITLE }')); ?></legend>
	<dl>
		<dt><label><?php echo ((isset($this->_rootref['L_NAME'])) ? $this->_rootref['L_NAME'] : ((isset($user->lang['NAME'])) ? $user->lang['NAME'] : '{ NAME }')); ?>:</label></dt>
		<dd><strong><?php echo (isset($this->_rootref['NAME'])) ? $this->_rootref['NAME'] : ''; ?></strong></dd>
	</dl>
	<?php if ($this->_rootref['S_STYLE']) {  ?>

		<dl>
			<dt><label for="inc_template"><?php echo ((isset($this->_rootref['L_INCLUDE_TEMPLATE'])) ? $this->_rootref['L_INCLUDE_TEMPLATE'] : ((isset($user->lang['INCLUDE_TEMPLATE'])) ? $user->lang['INCLUDE_TEMPLATE'] : '{ INCLUDE_TEMPLATE }')); ?>:</label></dt>
			<dd><label><input type="radio" class="radio" id="inc_template" name="inc_template" value="1" checked="checked" /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label>
				<label><input type="radio" class="radio" name="inc_template" value="0" /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label></dd>
		</dl>
		<dl>
			<dt><label for="inc_theme"><?php echo ((isset($this->_rootref['L_INCLUDE_THEME'])) ? $this->_rootref['L_INCLUDE_THEME'] : ((isset($user->lang['INCLUDE_THEME'])) ? $user->lang['INCLUDE_THEME'] : '{ INCLUDE_THEME }')); ?>:</label></dt>
			<dd><label><input type="radio" class="radio" id="inc_theme" name="inc_theme" value="1" checked="checked" /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label>
				<label><input type="radio" class="radio" name="inc_theme" value="0" /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label></dd>
		</dl>
		<dl>
			<dt><label for="inc_imageset"><?php echo ((isset($this->_rootref['L_INCLUDE_IMAGESET'])) ? $this->_rootref['L_INCLUDE_IMAGESET'] : ((isset($user->lang['INCLUDE_IMAGESET'])) ? $user->lang['INCLUDE_IMAGESET'] : '{ INCLUDE_IMAGESET }')); ?>:</label></dt>
			<dd><label><input type="radio" class="radio" id="inc_imageset" name="inc_imageset" value="1" checked="checked" /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label>
				<label><input type="radio" class="radio" name="inc_imageset" value="0" /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label></dd>
		</dl>
	<?php } ?>

	<dl>
		<dt><label for="store"><?php echo ((isset($this->_rootref['L_DOWNLOAD_STORE'])) ? $this->_rootref['L_DOWNLOAD_STORE'] : ((isset($user->lang['DOWNLOAD_STORE'])) ? $user->lang['DOWNLOAD_STORE'] : '{ DOWNLOAD_STORE }')); ?>:</label><br /><span><?php echo ((isset($this->_rootref['L_DOWNLOAD_STORE_EXPLAIN'])) ? $this->_rootref['L_DOWNLOAD_STORE_EXPLAIN'] : ((isset($user->lang['DOWNLOAD_STORE_EXPLAIN'])) ? $user->lang['DOWNLOAD_STORE_EXPLAIN'] : '{ DOWNLOAD_STORE_EXPLAIN }')); ?></span></dt>
		<dd><label><input type="radio" class="radio" id="store" name="store" value="1" checked="checked" /> <?php echo ((isset($this->_rootref['L_EXPORT_STORE'])) ? $this->_rootref['L_EXPORT_STORE'] : ((isset($user->lang['EXPORT_STORE'])) ? $user->lang['EXPORT_STORE'] : '{ EXPORT_STORE }')); ?></label>
			<label><input type="radio" class="radio" name="store" value="0" /> <?php echo ((isset($this->_rootref['L_EXPORT_DOWNLOAD'])) ? $this->_rootref['L_EXPORT_DOWNLOAD'] : ((isset($user->lang['EXPORT_DOWNLOAD'])) ? $user->lang['EXPORT_DOWNLOAD'] : '{ EXPORT_DOWNLOAD }')); ?></label></dd>
	</dl>
	<dl>
		<dt><label for="format"><?php echo ((isset($this->_rootref['L_ARCHIVE_FORMAT'])) ? $this->_rootref['L_ARCHIVE_FORMAT'] : ((isset($user->lang['ARCHIVE_FORMAT'])) ? $user->lang['ARCHIVE_FORMAT'] : '{ ARCHIVE_FORMAT }')); ?>:</label></dt>
		<dd><?php echo (isset($this->_rootref['FORMAT_BUTTONS'])) ? $this->_rootref['FORMAT_BUTTONS'] : ''; ?></dd>
	</dl>

	<p class="quick">
		<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

		<input class="button1" type="submit" name="update" value="<?php echo ((isset($this->_rootref['L_SUBMIT'])) ? $this->_rootref['L_SUBMIT'] : ((isset($user->lang['SUBMIT'])) ? $user->lang['SUBMIT'] : '{ SUBMIT }')); ?>" />
	</p>
	</fieldset>


	</form>

<?php } else if ($this->_rootref['S_FRONTEND']) {  ?>


	<h1><?php echo ((isset($this->_rootref['L_TITLE'])) ? $this->_rootref['L_TITLE'] : ((isset($user->lang['TITLE'])) ? $user->lang['TITLE'] : '{ TITLE }')); ?></h1>

	<p><?php echo ((isset($this->_rootref['L_EXPLAIN'])) ? $this->_rootref['L_EXPLAIN'] : ((isset($user->lang['EXPLAIN'])) ? $user->lang['EXPLAIN'] : '{ EXPLAIN }')); ?></p>

	<?php if ($this->_rootref['S_STYLE']) {  $this->_tpldata['DEFINE']['.']['COLSPAN'] = 5; } else { $this->_tpldata['DEFINE']['.']['COLSPAN'] = 4; } ?>


	<table cellspacing="1">
		<col class="row1" /><?php if ($this->_rootref['S_STYLE']) {  ?><col class="row1" /><?php } ?><col class="row2" /><col class="row2" />
	<thead>
	<tr>
		<th><?php echo ((isset($this->_rootref['L_NAME'])) ? $this->_rootref['L_NAME'] : ((isset($user->lang['NAME'])) ? $user->lang['NAME'] : '{ NAME }')); ?></th>
		<?php if ($this->_rootref['S_STYLE']) {  ?><th><?php echo ((isset($this->_rootref['L_STYLE_USED_BY'])) ? $this->_rootref['L_STYLE_USED_BY'] : ((isset($user->lang['STYLE_USED_BY'])) ? $user->lang['STYLE_USED_BY'] : '{ STYLE_USED_BY }')); ?></th><?php } ?>

		<th><?php echo ((isset($this->_rootref['L_OPTIONS'])) ? $this->_rootref['L_OPTIONS'] : ((isset($user->lang['OPTIONS'])) ? $user->lang['OPTIONS'] : '{ OPTIONS }')); ?></th>
		<th><?php echo ((isset($this->_rootref['L_ACTIONS'])) ? $this->_rootref['L_ACTIONS'] : ((isset($user->lang['ACTIONS'])) ? $user->lang['ACTIONS'] : '{ ACTIONS }')); ?></th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td class="row3" colspan="<?php echo (isset($this->_tpldata['DEFINE']['.']['COLSPAN'])) ? $this->_tpldata['DEFINE']['.']['COLSPAN'] : ''; ?>"><strong><?php echo ((isset($this->_rootref['L_INSTALLED'])) ? $this->_rootref['L_INSTALLED'] : ((isset($user->lang['INSTALLED'])) ? $user->lang['INSTALLED'] : '{ INSTALLED }')); ?></strong></td>
	</tr>
	<?php $_installed_count = (isset($this->_tpldata['installed'])) ? sizeof($this->_tpldata['installed']) : 0;if ($_installed_count) {for ($_installed_i = 0; $_installed_i < $_installed_count; ++$_installed_i){$_installed_val = &$this->_tpldata['installed'][$_installed_i]; if ($_installed_val['S_INACTIVE'] && ! $this->_tpldata['DEFINE']['.']['INACTIVE_STYLES']) {  $this->_tpldata['DEFINE']['.']['INACTIVE_STYLES'] = 1; ?>

		<tr>
			<td class="row3" colspan="<?php echo (isset($this->_tpldata['DEFINE']['.']['COLSPAN'])) ? $this->_tpldata['DEFINE']['.']['COLSPAN'] : ''; ?>"><strong><?php echo ((isset($this->_rootref['L_INACTIVE_STYLES'])) ? $this->_rootref['L_INACTIVE_STYLES'] : ((isset($user->lang['INACTIVE_STYLES'])) ? $user->lang['INACTIVE_STYLES'] : '{ INACTIVE_STYLES }')); ?></strong></td>
		</tr>
	<?php } ?>

	<tr>
		<td><strong><?php echo $_installed_val['NAME']; ?></strong><?php if ($_installed_val['S_DEFAULT_STYLE']) {  ?> *<?php } ?></td>
		<?php if ($this->_rootref['S_STYLE']) {  ?>

			<td style="text-align: center;"><?php echo $_installed_val['STYLE_COUNT']; ?></td>
		<?php } ?>

		<td style="text-align: center;">
			<?php echo $_installed_val['S_OPTIONS']; ?>

		</td>
		<td style="text-align: center;">
			<?php if ($this->_rootref['S_STYLE']) {  ?>

				<a href="<?php echo $_installed_val['U_STYLE_ACT_DEACT']; ?>"><?php echo $_installed_val['L_STYLE_ACT_DEACT']; ?></a> |
			<?php } ?>

			<?php echo $_installed_val['S_ACTIONS']; ?>

			<?php if ($this->_rootref['S_STYLE']) {  ?>

				| <a href="<?php echo $_installed_val['U_PREVIEW']; ?>"><?php echo ((isset($this->_rootref['L_PREVIEW'])) ? $this->_rootref['L_PREVIEW'] : ((isset($user->lang['PREVIEW'])) ? $user->lang['PREVIEW'] : '{ PREVIEW }')); ?></a>
			<?php } ?>

		</td>
	</tr>
	<?php }} ?>

	<tr>
		<td class="row3" colspan="<?php echo (isset($this->_tpldata['DEFINE']['.']['COLSPAN'])) ? $this->_tpldata['DEFINE']['.']['COLSPAN'] : ''; ?>"><strong><?php echo ((isset($this->_rootref['L_UNINSTALLED'])) ? $this->_rootref['L_UNINSTALLED'] : ((isset($user->lang['UNINSTALLED'])) ? $user->lang['UNINSTALLED'] : '{ UNINSTALLED }')); ?></strong></td>
	</tr>
	<?php if (! sizeof($this->_tpldata['uninstalled'])) {  ?>

		<tr>
			<td class="row1" colspan="<?php echo (isset($this->_tpldata['DEFINE']['.']['COLSPAN'])) ? $this->_tpldata['DEFINE']['.']['COLSPAN'] : ''; ?>" style="text-align: center;"><?php echo ((isset($this->_rootref['L_NO_UNINSTALLED'])) ? $this->_rootref['L_NO_UNINSTALLED'] : ((isset($user->lang['NO_UNINSTALLED'])) ? $user->lang['NO_UNINSTALLED'] : '{ NO_UNINSTALLED }')); ?></td>
		</tr>
	<?php } $_uninstalled_count = (isset($this->_tpldata['uninstalled'])) ? sizeof($this->_tpldata['uninstalled']) : 0;if ($_uninstalled_count) {for ($_uninstalled_i = 0; $_uninstalled_i < $_uninstalled_count; ++$_uninstalled_i){$_uninstalled_val = &$this->_tpldata['uninstalled'][$_uninstalled_i]; ?>

		<tr>
			<td<?php if ($this->_rootref['S_STYLE']) {  ?> colspan="2"<?php } ?>><strong><?php echo $_uninstalled_val['NAME']; ?></strong><br /><span><?php echo ((isset($this->_rootref['L_COPYRIGHT'])) ? $this->_rootref['L_COPYRIGHT'] : ((isset($user->lang['COPYRIGHT'])) ? $user->lang['COPYRIGHT'] : '{ COPYRIGHT }')); ?>: <?php echo $_uninstalled_val['COPYRIGHT']; ?></span></td>
			<td style="text-align: center;" colspan="2"><a href="<?php echo $_uninstalled_val['U_INSTALL']; ?>"><?php echo ((isset($this->_rootref['L_INSTALL'])) ? $this->_rootref['L_INSTALL'] : ((isset($user->lang['INSTALL'])) ? $user->lang['INSTALL'] : '{ INSTALL }')); ?></a></td>
		</tr>
	<?php }} ?>

	</tbody>
	</table>

	<?php if ($this->_rootref['S_STYLE']) {  ?>

		<form id="acp_styles" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">

		<fieldset class="quick">
			<legend><?php echo ((isset($this->_rootref['L_CREATE'])) ? $this->_rootref['L_CREATE'] : ((isset($user->lang['CREATE'])) ? $user->lang['CREATE'] : '{ CREATE }')); ?></legend>
			<?php echo ((isset($this->_rootref['L_CREATE'])) ? $this->_rootref['L_CREATE'] : ((isset($user->lang['CREATE'])) ? $user->lang['CREATE'] : '{ CREATE }')); ?>: <input type="text" name="name" value="" /> <?php echo ((isset($this->_rootref['L_FROM'])) ? $this->_rootref['L_FROM'] : ((isset($user->lang['FROM'])) ? $user->lang['FROM'] : '{ FROM }')); ?> <select name="basis"><?php echo (isset($this->_rootref['S_BASIS_OPTIONS'])) ? $this->_rootref['S_BASIS_OPTIONS'] : ''; ?></select> <input class="button2" type="submit" name="add" value="<?php echo ((isset($this->_rootref['L_SUBMIT'])) ? $this->_rootref['L_SUBMIT'] : ((isset($user->lang['SUBMIT'])) ? $user->lang['SUBMIT'] : '{ SUBMIT }')); ?>" />
		</fieldset>

		</form>
	<?php } } else if ($this->_rootref['S_DETAILS']) {  ?>


	<a href="<?php echo (isset($this->_rootref['U_BACK'])) ? $this->_rootref['U_BACK'] : ''; ?>" style="float: <?php echo (isset($this->_rootref['S_CONTENT_FLOW_END'])) ? $this->_rootref['S_CONTENT_FLOW_END'] : ''; ?>;">&laquo; <?php echo ((isset($this->_rootref['L_BACK'])) ? $this->_rootref['L_BACK'] : ((isset($user->lang['BACK'])) ? $user->lang['BACK'] : '{ BACK }')); ?></a>

	<h1><?php echo ((isset($this->_rootref['L_TITLE'])) ? $this->_rootref['L_TITLE'] : ((isset($user->lang['TITLE'])) ? $user->lang['TITLE'] : '{ TITLE }')); ?></h1>

	<p><?php echo ((isset($this->_rootref['L_EXPLAIN'])) ? $this->_rootref['L_EXPLAIN'] : ((isset($user->lang['EXPLAIN'])) ? $user->lang['EXPLAIN'] : '{ EXPLAIN }')); ?></p>

	<?php if ($this->_rootref['S_ERROR_MSG']) {  ?>

		<div class="errorbox">
			<h3><?php echo ((isset($this->_rootref['L_WARNING'])) ? $this->_rootref['L_WARNING'] : ((isset($user->lang['WARNING'])) ? $user->lang['WARNING'] : '{ WARNING }')); ?></h3>
			<p><?php echo (isset($this->_rootref['ERROR_MSG'])) ? $this->_rootref['ERROR_MSG'] : ''; ?></p>
		</div>
	<?php } ?>


	<form id="acp_styles" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">

	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_TITLE'])) ? $this->_rootref['L_TITLE'] : ((isset($user->lang['TITLE'])) ? $user->lang['TITLE'] : '{ TITLE }')); ?></legend>
	<dl>
		<dt><label for="name"><?php echo ((isset($this->_rootref['L_NAME'])) ? $this->_rootref['L_NAME'] : ((isset($user->lang['NAME'])) ? $user->lang['NAME'] : '{ NAME }')); ?>:</label></dt>
		<dd><?php if ($this->_rootref['S_INSTALL']) {  ?><strong id="name"><?php echo (isset($this->_rootref['NAME'])) ? $this->_rootref['NAME'] : ''; ?></strong><?php } else { ?><input type="text" id="name" name="name" value="<?php echo (isset($this->_rootref['NAME'])) ? $this->_rootref['NAME'] : ''; ?>" /><?php } ?></dd>
	</dl>
	<dl>
		<dt><label for="copyright"><?php echo ((isset($this->_rootref['L_COPYRIGHT'])) ? $this->_rootref['L_COPYRIGHT'] : ((isset($user->lang['COPYRIGHT'])) ? $user->lang['COPYRIGHT'] : '{ COPYRIGHT }')); ?>:</label></dt>
		<dd><?php if ($this->_rootref['S_INSTALL']) {  ?><strong id="copyright"><?php echo (isset($this->_rootref['COPYRIGHT'])) ? $this->_rootref['COPYRIGHT'] : ''; ?></strong><?php } else { ?><input type="text" id="copyright" name="copyright" value="<?php echo (isset($this->_rootref['COPYRIGHT'])) ? $this->_rootref['COPYRIGHT'] : ''; ?>" /><?php } ?></dd>
	</dl>
	<?php if ($this->_rootref['S_SUPERTEMPLATE']) {  ?>

	<dl>
		<dt><label for="inheriting"><?php echo ((isset($this->_rootref['L_INHERITING_FROM'])) ? $this->_rootref['L_INHERITING_FROM'] : ((isset($user->lang['INHERITING_FROM'])) ? $user->lang['INHERITING_FROM'] : '{ INHERITING_FROM }')); ?>:</label></dt>
		<dd><strong id="inheriting"><?php echo (isset($this->_rootref['S_SUPERTEMPLATE'])) ? $this->_rootref['S_SUPERTEMPLATE'] : ''; ?></strong></dd>
	</dl>
	<?php } if ($this->_rootref['S_STYLE'] && ! $this->_rootref['S_BASIS']) {  ?>

		<dl>
			<dt><label for="template_id"><?php echo ((isset($this->_rootref['L_STYLE_TEMPLATE'])) ? $this->_rootref['L_STYLE_TEMPLATE'] : ((isset($user->lang['STYLE_TEMPLATE'])) ? $user->lang['STYLE_TEMPLATE'] : '{ STYLE_TEMPLATE }')); ?>:</label></dt>
			<dd><?php if ($this->_rootref['S_INSTALL']) {  ?><strong id="template_id"><?php echo (isset($this->_rootref['TEMPLATE_NAME'])) ? $this->_rootref['TEMPLATE_NAME'] : ''; ?></strong><?php } else { ?><select id="template_id" name="template_id"><?php echo (isset($this->_rootref['S_TEMPLATE_OPTIONS'])) ? $this->_rootref['S_TEMPLATE_OPTIONS'] : ''; ?></select><?php } ?></dd>
		</dl>
		<dl>
			<dt><label for="theme_id"><?php echo ((isset($this->_rootref['L_STYLE_THEME'])) ? $this->_rootref['L_STYLE_THEME'] : ((isset($user->lang['STYLE_THEME'])) ? $user->lang['STYLE_THEME'] : '{ STYLE_THEME }')); ?>:</label></dt>
			<dd><?php if ($this->_rootref['S_INSTALL']) {  ?><strong id="theme_id"><?php echo (isset($this->_rootref['THEME_NAME'])) ? $this->_rootref['THEME_NAME'] : ''; ?></strong><?php } else { ?><select id="theme_id" name="theme_id"><?php echo (isset($this->_rootref['S_THEME_OPTIONS'])) ? $this->_rootref['S_THEME_OPTIONS'] : ''; ?></select><?php } ?></dd>
		</dl>
		<dl>
			<dt><label for="imageset_id"><?php echo ((isset($this->_rootref['L_STYLE_IMAGESET'])) ? $this->_rootref['L_STYLE_IMAGESET'] : ((isset($user->lang['STYLE_IMAGESET'])) ? $user->lang['STYLE_IMAGESET'] : '{ STYLE_IMAGESET }')); ?>:</label></dt>
			<dd><?php if ($this->_rootref['S_INSTALL']) {  ?><strong id="imageset_id"><?php echo (isset($this->_rootref['IMAGESET_NAME'])) ? $this->_rootref['IMAGESET_NAME'] : ''; ?></strong><?php } else { ?><select id="imageset_id" name="imageset_id"><?php echo (isset($this->_rootref['S_IMAGESET_OPTIONS'])) ? $this->_rootref['S_IMAGESET_OPTIONS'] : ''; ?></select><?php } ?></dd>
		</dl>
	<?php } if (( $this->_rootref['S_TEMPLATE'] || $this->_rootref['S_THEME'] ) && ( $this->_rootref['S_LOCATION'] || ! $this->_rootref['S_INSTALL'] )) {  ?>

		<dl>
			<dt><label for="store_db"><?php echo ((isset($this->_rootref['L_LOCATION'])) ? $this->_rootref['L_LOCATION'] : ((isset($user->lang['LOCATION'])) ? $user->lang['LOCATION'] : '{ LOCATION }')); ?>:</label><br /><span><?php if ($this->_rootref['S_STORE_DB_DISABLED']) {  echo ((isset($this->_rootref['L_LOCATION_DISABLED_EXPLAIN'])) ? $this->_rootref['L_LOCATION_DISABLED_EXPLAIN'] : ((isset($user->lang['LOCATION_DISABLED_EXPLAIN'])) ? $user->lang['LOCATION_DISABLED_EXPLAIN'] : '{ LOCATION_DISABLED_EXPLAIN }')); } else { echo ((isset($this->_rootref['L_LOCATION_EXPLAIN'])) ? $this->_rootref['L_LOCATION_EXPLAIN'] : ((isset($user->lang['LOCATION_EXPLAIN'])) ? $user->lang['LOCATION_EXPLAIN'] : '{ LOCATION_EXPLAIN }')); } ?></span></dt>
			<dd><label><input type="radio" class="radio" name="store_db" value="0"<?php if (! $this->_rootref['S_STORE_DB']) {  ?> id="store_db" checked="checked"<?php } if ($this->_rootref['S_STORE_DB_DISABLED']) {  ?>disabled="disabled" <?php } ?> /><?php echo ((isset($this->_rootref['L_STORE_FILESYSTEM'])) ? $this->_rootref['L_STORE_FILESYSTEM'] : ((isset($user->lang['STORE_FILESYSTEM'])) ? $user->lang['STORE_FILESYSTEM'] : '{ STORE_FILESYSTEM }')); ?></label>
				<label><input type="radio" class="radio" name="store_db" value="1"<?php if ($this->_rootref['S_STORE_DB']) {  ?> id="store_db" checked="checked"<?php } if ($this->_rootref['S_STORE_DB_DISABLED']) {  ?>disabled="disabled" <?php } ?>/> <?php echo ((isset($this->_rootref['L_STORE_DATABASE'])) ? $this->_rootref['L_STORE_DATABASE'] : ((isset($user->lang['STORE_DATABASE'])) ? $user->lang['STORE_DATABASE'] : '{ STORE_DATABASE }')); ?></label></dd>
		</dl>
	<?php } if ($this->_rootref['S_STYLE']) {  ?>

		</fieldset>

		<fieldset>
			<legend><?php echo ((isset($this->_rootref['L_OPTIONS'])) ? $this->_rootref['L_OPTIONS'] : ((isset($user->lang['OPTIONS'])) ? $user->lang['OPTIONS'] : '{ OPTIONS }')); ?></legend>
		<dl>
			<dt><label for="style_active"><?php echo ((isset($this->_rootref['L_STYLE_ACTIVE'])) ? $this->_rootref['L_STYLE_ACTIVE'] : ((isset($user->lang['STYLE_ACTIVE'])) ? $user->lang['STYLE_ACTIVE'] : '{ STYLE_ACTIVE }')); ?>:</label></dt>
			<dd><label><input type="radio" class="radio" name="style_active" value="1"<?php if ($this->_rootref['S_STYLE_ACTIVE']) {  ?> id="style_active" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label>
				<label><input type="radio" class="radio" name="style_active" value="0"<?php if (! $this->_rootref['S_STYLE_ACTIVE']) {  ?> id="style_active" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label></dd>
		</dl>
		<?php if (! $this->_rootref['S_STYLE_DEFAULT']) {  ?>

			<dl>
				<dt><label for="style_default"><?php echo ((isset($this->_rootref['L_STYLE_DEFAULT'])) ? $this->_rootref['L_STYLE_DEFAULT'] : ((isset($user->lang['STYLE_DEFAULT'])) ? $user->lang['STYLE_DEFAULT'] : '{ STYLE_DEFAULT }')); ?>:</label></dt>
				<dd><label><input type="radio" class="radio" name="style_default" value="1" /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label>
					<label><input type="radio" class="radio" id="style_default" name="style_default" value="0" checked="checked" /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label></dd>
			</dl>
		<?php } } ?>

	</fieldset>

	<fieldset class="submit-buttons">
		<legend><?php echo ((isset($this->_rootref['L_SUBMIT'])) ? $this->_rootref['L_SUBMIT'] : ((isset($user->lang['SUBMIT'])) ? $user->lang['SUBMIT'] : '{ SUBMIT }')); ?></legend>
		<input class="button1" type="submit" name="update" value="<?php echo ((isset($this->_rootref['L_SUBMIT'])) ? $this->_rootref['L_SUBMIT'] : ((isset($user->lang['SUBMIT'])) ? $user->lang['SUBMIT'] : '{ SUBMIT }')); ?>" />
		<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

	</fieldset>

	</form>

<?php } $this->_tpl_include('overall_footer.html'); ?>