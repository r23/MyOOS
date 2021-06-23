{MOD_HEADER}
{MOD_HEADLINE}
<!-- BEGIN CONFIG_REFRESH_TRUE -->
	{CONFIG_REFRESH}
<!-- END CONFIG_REFRESH_TRUE -->

<!-- BEGIN DB_REFRESH -->
	<script>
	var curl=parent.MyOOS_Dumper_content.location.href.split("/");
	var cdatei=curl.pop();
	var ca=cdatei.split(".");
	if(ca[0]!="dump" && ca[0]!="restore" && ca[0]!="frameset" && ca[0]!="crondump") {
		parent.MyOOS_Dumper_content.location.href=parent.MyOOS_Dumper_content.location.href;
	}
	if(ca[0]=="sql")
	{
		parent.MyOOS_Dumper_content.location.href='sql.php';
		{DB_REFRESH_INDEX}
	}
	</script>
<!-- END DB_REFRESH -->

<!-- BEGIN CHANGED_LANGUAGE -->
	<script>self.location.href='menu.php';</script>
<!-- END CHANGED_LANGUAGE -->
<a href="{CONFIG_HOMEPAGE}" target="_blank" title="{L_VISIT_HOMEPAGE} {CONFIG_HOMEPAGE}"><img src="css/{CONFIG_THEME}/pics/h1_logo.gif" alt="MyOOS [Dumper] - Homepage"></a>
<div id="version">
	<a href="main.php" title="{L_HOME}" target="MyOOS_Dumper_content" style="text-decoration: none">
		<span class="version-line">Version {MOD_VERSION}</span>
		<img src="css/{CONFIG_THEME}/pics/navi_bg.jpg" alt="">
	</a>
</div>

