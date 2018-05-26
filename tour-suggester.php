<?php
/**********************************************************************************************
Document: tour-suggester.php
Creator: Jacob Edmond Kerr
Date: 03-02-12
Purpose: SpotlightHomeTours.com - Tour Suggester
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================

	// HEADER TEMPLATE
	$title = 'Spotlight | Tour Suggester';
	$header = '<link rel="stylesheet" type="text/css" href="repository_css/splash.css"/>
<link rel="stylesheet" type="text/css" href="repository_css/product.css"/>
<link rel="stylesheet" type="text/css" href="repository_css/tour-suggester.css"/>
<script src="repository_inc/products.js"></script>
';
	require_once('repository_inc/template-header.php');
	
//=======================================================================
// Objects
//=======================================================================

	// Create Instances of Needed Objects
	$tours = new tours($db);
	$toursuggestions = new toursuggestions();
	$demos = new demos();
	
//=======================================================================
// Document
//=======================================================================

	// Zip Code Set?
	$zipSet = false;
	if(isset($_SESSION['zipCode'])&!empty($_SESSION['zipCode'])){
		$zipSet = true;
	}
	
	// Get Tour Categories
	$tourCategories = $tours->getTourCategoryList();
	
	
	// Set conditions for suggestion search
	$priceSet = false;
	$sqftSet = false;
	$action='';
	$suggestions = array();
	if(isset($_REQUEST['price'])&&!empty($_REQUEST['price'])){
		$priceSet = true;
		$action = 'price';
	}
	if(isset($_REQUEST['sqft'])&&!empty($_REQUEST['sqft'])){
		$sqftSet = true;
		$action = 'sqft';
	}
	if($sqftSet&&$priceSet){
		$action = 'both';
	}
	
	
	
	switch($action){
		case'price':
			$resultText = "$".number_format($_REQUEST['price'], 2, '.', ',')." property.";
		break;
		case'sqft':
			$resultText = number_format($_REQUEST['sqft'], 0, '.', ',')." square foot property.";
		break;
		case'both':
			$resultText = "$".number_format($_REQUEST['price'], 2, '.', ',')." property with ".number_format($_REQUEST['sqft'], 0, '.', ',')." square feet.";;
		break;
	}
	
?>
	<div class="page-intro">
		<h1>Tour Suggester</h1>
	</div>
	<div class="image-reel-pattern"></div>
	<div class="direction">Scroll down to see suggestions</div>
	<div class="page-content">
		<div class="main-column left">
<?PHP
	echo '<div class="clear"></div><div class="result-txt"><div class="left">Suggestions for: <strong id="setZip" onClick="chooserRangeWindow()" style="cursor:pointer;">'.$resultText.'</strong></div><div class="v2button v2button-gray left" style="margin-left:10px; margin-top:-3px;" onClick="chooserRangeWindow()">change</div></div>';
	
	foreach($tourCategories as $row => $category){
		switch($action){
			case'price':
				$suggestions = $toursuggestions->getSuggestionByCategory($category['category_name'], intval($_REQUEST['price']));
			break;
			case'sqft':
				$suggestions = $toursuggestions->getSuggestionByCategory($category['category_name'], $price="", intval($_REQUEST['sqft']));
			break;
			case'both':
				$suggestions = $toursuggestions->getSuggestionByCategory($category['category_name'], intval($_REQUEST['price']), intval($_REQUEST['sqft']));
			break;
		}
		if(count($suggestions)>0){
?>
			<h2 class="product-header"><img src="repository_images/new/icons/<?PHP echo $iconToCategory[$category['category_name']]; ?>" alt="<?PHP echo $category['category_name']; ?> Icon" style="margin-right:10px;" width="40" height="40" border="0" align="absmiddle" /><?PHP echo $category['category_name']; ?></h2>
<?PHP
			foreach($suggestions as $row => $column){
				$tourTypeID = $column['tourTypeID'];
				$demoID = $demos->getByTourType($tourTypeID);
				$demoID = $demoID[0]['tourID'];
?>
			<div class="product" id="<?PHP echo $tourTypeID ?>">
					<?PHP
						// Pull tour type description
						$tourType = $tours->getTourType($tourTypeID, 'description, tagline, tourTypeName ');
						$image = explode(" ", strtolower($tourType['tourTypeName']));
						$image = implode("-", $image);
						$image = $image.'.jpg';
						
					?>
				<div class="product-img" onClick="viewDemoTour(<?PHP echo $demoID; ?>)"><img src="repository_images/new/products/<?PHP echo $image ?>" width="250" height="200" alt="Still Photo Tour" /></div>
				<div class="product-desc">
					<h2><?PHP echo $tourType['tourTypeName'] ?></h2>
					<p><?PHP echo (empty($tourType['tagline']))?$tourType['description']:'<strong>'.$tourType['tagline'].':</strong> '.$tourType['description']; ?></p>
					<div class="v2button" onClick="viewDemoTour(<?PHP echo $demoID; ?>)">View Demo</div>
					<div id="price"></div>
				</div>
				<div class="clear"></div>
			</div>
<?PHP
			}
		}
	}
?>
		</div>
		<div class="side-column right">
<?PHP
	include("repository_inc/template-side-column.php");
?>
		</div>
		<div class="clear"></div>
	</div>
<?PHP
	if($zipSet){
		echo '<script>loadPricing();</script>';
	}
	// FOOTER TEMPLATE
	require_once('repository_inc/template-footer.php');
?>