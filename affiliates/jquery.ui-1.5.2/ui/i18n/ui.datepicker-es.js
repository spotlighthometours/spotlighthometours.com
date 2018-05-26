./repository_inc/wysiwyg-controls/wysiwyg.colorpicker.js" type="text/javascript"></script><!-- WYSIWYG Control -->
<script src="../repository_inc/wysiwyg-controls/wysiwyg.cssWrap.js" type="text/javascript"></script><!-- WYSIWYG Control -->
<script src="../repository_inc/wysiwyg-controls/wysiwyg.image.js" type="text/javascript"></script><!-- WYSIWYG Control -->
<script src="../repository_inc/wysiwyg-controls/wysiwyg.link.js" type="text/javascript"></script><!-- WYSIWYG Control -->
<script src="../repository_inc/wysiwyg-controls/wysiwyg.table.js" type="text/javascript"></script><!-- WYSIWYG Control -->
<link rel="Stylesheet" type="text/css" href="../repository_css/jquery.wysiwyg.css" />
<!-- WYSIWYG Style Sheet -->
<style type="text/css" media="screen">
@import "../repository_css/template.css";
 @import "../repository_css/admin-v2.css";
</style>
</head>
<body>
<?PHP if(isset($_REQUEST['send_email'])){ 
?>
    <h1><?php echo($feedbackTitle);?>Photographer feedback sent!</h1>
    <div class="form_line" >
        <div class="button_new button_dgrey button_mid" onclick="window.