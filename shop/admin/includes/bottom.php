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
<script type="text/javascript" src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="js/plugins/screenfull/dist/screenfull.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery-storage-api/jquery.storageapi.min.js"></script>
<?php
if ($bUpload == TRUE) {
?>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included-->
<script src="js/jquery-ui/ui/widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings-->
<script src="js/plugins/blueimp-tmpl/js/tmpl.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality-->
<script src="js/plugins/blueimp-load-image/js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality-->
<script src="js/plugins/blueimp-canvas-to-blob/js/canvas-to-blob.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="js/plugins/jquery-file-upload/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="js/plugins/jquery-file-upload/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="js/plugins/jquery-file-upload/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="js/plugins/jquery-file-upload/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="js/plugins/jquery-file-upload/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="js/plugins/jquery-file-upload/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="js/plugins/jquery-file-upload/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="js/plugins/jquery-file-upload/jquery.fileupload-ui.js"></script>
<script src="js/app/upload.js"></script>
<?php
} 
?>
<script type="text/javascript" src="js/general.js"></script>

<!-- HTML5 shim and Respond.js IE support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
    <script src="js/plugin/respond.js"></script>
    <script src="js/plugin/html5shiv.js"></script>
<![endif]-->

</body>
</html>