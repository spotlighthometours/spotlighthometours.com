<?PHP
	// Include appplication's global configuration
	require_once('../../repository_inc/classes/inc.global.php');
	showErrors();
	$socialcontentheader = new socialcontentheader();
	$socialcontentheader = $socialcontentheader->user($_REQUEST['userID'])->get();
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Import Header</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <link rel="stylesheet" href="includes/generate-header.css"><!-- Import Header Main Style Sheet -->
   </head>
   <body>
		<iframe id="webpage" src="" width="100%" height="800" frameborder="0" scrolling="no"></iframe>
   		<script src="includes/darkly/jquery.min.js"></script>
      	<script src="includes/generate-header.js"></script> <!-- Import Header Main Control File -->
      	<script>
			userID = <?PHP echo $socialcontentheader->userID ?>;
			userType = '<?PHP echo $socialcontentheader->userType ?>';
			URL = '<?PHP echo $socialcontentheader->URL ?>';
			headerParentTag = '<?PHP echo $socialcontentheader->parentTag ?>';
			backgroundImageURL = "<?PHP echo $socialcontentheader->backgroundImageURL ?>";
			backgroundSize = "<?PHP echo $socialcontentheader->backgroundSize ?>";
			headerHeight = <?PHP echo $socialcontentheader->height ?>;
			headerWrapperTag = '<?PHP echo $socialcontentheader->wrapperTag ?>';
			generateHeader();
	  	</script>
   </body>
</html>