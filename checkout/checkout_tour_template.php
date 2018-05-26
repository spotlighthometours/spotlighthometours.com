<?php
/**********************************************************************************************
Document: tour_template.php
Creator: Brandon Freeman
Date: 02-14-11
Purpose: Template for the tours on step-2.
**********************************************************************************************/
	//variables needed:
	//$tour['DemoLinkLink']
	//$tour['tourTypeID']
	//$tour['tourTypeName']
	//$tour['tagline']
	//$tour['description']
	//$price
	//$tour['iconImage']

	echo '
	<div id="tour' . $tour['tourTypeID'] . '" class="tourframe tourstd" >
		<img id="remove' . $tour['tourTypeID'] . '" class="removetour hidden" src="../repository_images/X.png" onclick="DeSelectTour(' . chr(39) . $tour['tourTypeID'] . chr(39) . ');" />
	';
	if (isset($tour['iconImage'] ) && strlen($tour['iconImage']) > 0) {
		echo'
		<img class="tourpicture" src="' . $tour['iconImage'] . '" />
		';
	} else {
		echo'
		<div class="tourpicture" >No Picture</div>
		';
	}
	
	// If there is a demo available, set the onclick for the demo button.
	if (isset($tour['DemoLinkLink']) && strlen($tour['DemoLinkLink']) > 0) {
		$demo = "openPopup('" . $tour['DemoLinkLink'] . "',780,570);";
	} else {
		$demo = "alert('No Demo Available');";
	}
		echo'
		<div class="tourtext">
			<div id="' . $tour['tourTypeID'] . '-tourtitle" class="tourtitle" >' . $tour['tourTypeName'] . '</div>
			<div class="toursubtitle" >' . $tour['tagline'] . '</div>
			<div class="tourdescription" >' . str_replace(Chr(13), "<br />",str_replace(Chr(10), "<br />", $tour['description'])) . '</div>			
		</div>
		<div class="tourpricing">
			<div class="tourprice" >$' . number_format($price, 2, '.', ',') . '</div>
			<input id="tour' . $tour['tourTypeID'] . 'price" type="hidden" name="tour' . $tour['tourTypeID'] . 'price" value=' . $price . '>
			<div id="btnTour' . $tour['tourTypeID'] . 'Demo" class="optionbutton visible" onmouseover="HighlightBtn(' . chr(39) . 'btnTour' . $tour['tourTypeID'] . 'Demo' . chr(39) . ');" onmouseout="DeHighlightBtn(' . chr(39) . 'btnTour' . $tour['tourTypeID'] . 'Demo' . chr(39) . ');" onclick="' . $demo . '" >
				<div id="btnTour' . $tour['tourTypeID'] . 'Democapl" class="btncap btncapl" ></div>
				<div id="btnTour' . $tour['tourTypeID'] . 'Demoicon" class="btnicon btnbody" >
					<img class="iconimage" src="../repository_images/demo.png" />
				</div>
				<div id="btnTour' . $tour['tourTypeID'] . 'Demotxt" class="btntxt btnbody" >View Demo</div>
				<div id="btnTour' . $tour['tourTypeID'] . 'Democapr" class="btncap btncapr" ></div>
			</div>
			
			<div id="btnTour' . $tour['tourTypeID'] . 'Cart" class="optionbutton visible" onmouseover="HighlightBtn(' . chr(39) . 'btnTour' . $tour['tourTypeID'] . 'Cart' . chr(39) . ');" onmouseout="DeHighlightBtn(' . chr(39) . 'btnTour' . $tour['tourTypeID'] . 'Cart' . chr(39) . ');" onclick="SelectTour(' . chr(39) . $tour['tourTypeID'] . chr(39) . ');" >
				<div id="btnTour' . $tour['tourTypeID'] . 'Cartcapl" class="btncap btncapl" ></div>
				<div id="btnTour' . $tour['tourTypeID'] . 'Carticon" class="btnicon btnbody" >+</div>
				<div id="btnTour' . $tour['tourTypeID'] . 'Carttxt" class="btntxt btnbody" >Select Tour</div>
				<div id="btnTour' . $tour['tourTypeID'] . 'Cartcapr" class="btncap btncapr" ></div>
			</div>
			
			<div id="btnSelect' . $tour['tourTypeID'] . '" class="optionbutton hidden" onclick="DeSelectTour(' . chr(39) . $tour['tourTypeID'] . chr(39) . ');" >
				<div class="btncap bl_opt_cap_l" ></div>
				<div class="bl_opt_text" >Selected</div>
				<div class="btncap bl_opt_cap_r" ></div>
			</div>
		</div>
	</div>
	';


?>