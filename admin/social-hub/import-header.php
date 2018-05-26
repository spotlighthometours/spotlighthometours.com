<?PHP
	// Include appplication's global configuration
	require_once('../../repository_inc/classes/inc.global.php');
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Import Header</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <link rel="stylesheet" href="includes/darkly/darklytheme.css" media="screen">
      <link rel="stylesheet" href="includes/darkly/darkly-custom.css">
      <link rel="stylesheet" href="includes/import-header.css"><!-- Import Header Main Style Sheet -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"><!-- Font Awesome CSS -->
   </head>
   <body>
      <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-light bg-primary">
         <a class="navbar-brand" href="#">IMPORT HEADER</a>
         <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor03" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarColor03">
            <ul class="navbar-nav mr-auto">
               <li class="nav-item active">
                  <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" href="#">Features</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" href="#">Pricing</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" href="#">About</a>
               </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
               <button type="button" class="btn btn-primary btn-lg btn-block">Preview</button>
            </form>
         </div> -->
      </nav>
      <div class="container">
         <div class="page-header" id="banner">
            <h1>Import Header</h1>
            <p class="lead">Import header from web URL ie: http://www.spotlighthometours.com</p>
         </div>
         <p>&nbsp;</p>
         <div class="step-1">
            <div class="page-header">
               <div class="row">
                  <div class="col-lg-12">
                     <h2>Step 1: <small class="text-muted">Enter URL for the web site you would like us to extract the header from</small></h2>
                  </div>
               </div>
            </div>
            <form>
               <fieldset>
                  <div class="form-group">
                     <label class="form-control-label" for="url">Website URL</label>
                     <div class="input-group">
                        <input type="text" class="form-control" id="url" placeholder="http://www.thewebsite.com/">
                        <div class="input-group-addon"><button id="processURL" type="button" class="btn btn-success">Process URL</button></div>
                     </div>
                     <div class="form-control-feedback text-danger"></div>
                     <small id="emailHelp" class="form-text text-muted">Please try to include a valid URL which includes http:// at the beginning.</small>
                  </div>
               </fieldset>
            </form>
         </div>
         <p>&nbsp;</p>
         <div class="step-2">
            <div class="page-header">
               <div class="row">
                  <div class="col-lg-12">
                     <h2>Step 2: <small class="text-muted">Select and extract the header from the web page below</small></h2>
                     <lead>Hover over the section of the website below until see see the whole header highlighted. Once you have the header highlighted then please click on the highlighted headers to extract the header for import into the Spotlight Concierge Social system.</lead>
					  <iframe id="webpage" src="" width="100%" height="800" frameborder="0" scrolling="no"></iframe>
                  </div>
               </div>
            </div>
         </div>
         <p>&nbsp;</p>
         <div class="step-3">
         	<div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-secondary" id="decreaseFrameHeight"><i class="fa fa-minus-square"></i> height</button>
                <button type="button" class="btn btn-secondary" id="increaseFrameHeight"><i class="fa fa-plus-square"></i> height</button>
            </div>
         	<button type="button" class="btn btn-success float-right" onclick="saveHeader()">Save this header</button><button type="button" class="btn btn-secondary float-right mr-3" onclick="doesURLExists()">Let's start over</button>
         	<div class="clearfix"></div>
         </div>
         <p>&nbsp;</p>
		  <p>&nbsp;</p>
      </div>
      <div id="ajaxMessage"></div>
      <script src="includes/darkly/jquery.min.js"></script>
      <script src="includes/darkly/popper.min.js"></script>
      <script src="includes/darkly/bootstrap.min.js"></script>
      <script src="includes/darkly/custom.js"></script>
      <script src="includes/import-header.js"></script> <!-- Import Header Main Control File -->
      <script>
	  	userID = <?PHP echo (isset($_REQUEST['userID']))?$_REQUEST['userID']:0; ?>;
		userType = '<?PHP echo (isset($_REQUEST['userType']))?$_REQUEST['userType']:"user"; ?>';
	  </script>
   </body>
</html>