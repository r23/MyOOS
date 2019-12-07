<div id="menu">
<ul>
	<li id="m1" class="active"><a href="main.php" target="MyOOS_Dumper_content" onclick="setMenuActive('m1')">{L_HOME}</a></li>
	<li id="m2" class=""><a href="config_overview.php" target="MyOOS_Dumper_content" onclick="setMenuActive('m2')">{L_CONFIG}</a></li>
	
	<!-- BEGIN MAINTENANCE -->
	<li id="m3" class=""><a href="filemanagement.php?action=dump"
		target="MyOOS_Dumper_content" onclick="setMenuActive('m3')">{L_DUMP}</a></li>
	<li id="m4" class=""><a href="filemanagement.php?action=restore"
		target="MyOOS_Dumper_content" onclick="setMenuActive('m4')">{L_RESTORE}</a></li>
	<li id="m5" class=""><a href="filemanagement.php?action=files"
		target="MyOOS_Dumper_content" onclick="setMenuActive('m5')">{L_FILE_MANAGE}</a></li>
	<li id="m6" class=""><a	href="sql.php?db={DB_ACTUAL}&amp;dbid={DB_SELECTED_INDEX}"
		target="MyOOS_Dumper_content" onclick="setMenuActive('m6')">{L_SQL_BROWSER}</a></li>
	<li id="m7" class=""><a href="log.php" target="MyOOS_Dumper_content"
		onclick="setMenuActive('m7')">{L_LOG}</a></li>
	<!-- END MAINTENANCE -->
	<li id="m8" class=""><a href="help.php" target="MyOOS_Dumper_content" onclick="setMenuActive('m8')">{L_CREDITS}</a></li>
</ul>
</div>

<div id="selectConfig">
<form action="menu.php" method="post">
<fieldset id="configSelect"><legend>{L_CONFIG}:</legend>
	<select name="selected_config" style="width: 157px;" onchange="this.form.submit()">{GET_FILELIST}</select></fieldset>
</form>
<form action="menu.php" method="post">
<fieldset id="dbSelect"><legend>{L_CHOOSE_DB}:</legend>
	<!-- BEGIN DB_LIST -->
		<select name="dbindex" style="width:157px;" onchange="this.form.submit()">
		<!-- BEGIN DB_ROW -->
			<option value="{DB_LIST.DB_ROW.ID}"{DB_LIST.DB_ROW.SELECTED}>&nbsp;&nbsp;{DB_LIST.DB_ROW.NAME}</option>
		<!-- END DB_ROW -->
		</select>
	<!-- END DB_LIST -->

	<!-- BEGIN NO_DB_FOUND -->
		{L_NO_DB_FOUND}
	<!-- END NO DB_FOUND --> 
	<p><a href="menu.php?action=dbrefresh">{L_LOAD_DATABASE}</a></p>
</fieldset>
</form>
</div>
						
