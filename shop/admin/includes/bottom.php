<?php
if ($bForm == TRUE) {
?>
<!-- PARSLEY-->
<script type="text/javascript" src="js/plugins/parsley/parsley.min.js"></script>
<script type="text/javascript" src="js/plugins/parsley/i18n/<?php echo $_SESSION['iso_639_1']; ?>.js"></script>
<script type="text/javascript">
	window.Parsley.setLocale('<?php echo $_SESSION['iso_639_1']; ?>');
</script>
<?php
	} 
?>

<!-- JS Global Compulsory -->           
<script type="text/javascript" src="js/jquery/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script type="text/javascript" src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>


<!-- Custom and plugin javascript -->
<script type="text/javascript" src="js/general.js"></script>

<!-- HTML5 shim and Respond.js IE support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
    <script src="js/plugin/respond.js"></script>
    <script src="js/plugin/html5shiv.js"></script>
<![endif]-->

</body>
</html>