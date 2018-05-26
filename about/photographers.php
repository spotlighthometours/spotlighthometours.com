<?php
/**********************************************************************************************
Document: about/photographers.php
Creator: William Merfalen
Date: 05-07-2015
Purpose: SpotlightHomeTours.com - About Photographers
**********************************************************************************************/
/*
error_reporting(-1);
ini_set('display_errors',1);
*/
//=======================================================================
// Includes
//=======================================================================

	// HEADER TEMPLATE
	$title = 'Photographers of Spotlight Home Tours';
	$header = '<link rel="stylesheet" type="text/css" href="../repository_css/splash.css"/>
<link rel="stylesheet" type="text/css" href="../repository_css/about.css"/>
<link rel="stylesheet" type="text/css" href="../repository_css/magnific.css"/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> 
<script src="../repository_inc/about.js"/></script>
<script src="/repository_inc/jquery.magnific.js"/></script>
';
	require_once('../repository_inc/template-header.php');
	

$lrgWidth = ' width=300 ';
$jokes = (isset($_GET['jokes']) ? 1 : 0 );

//=======================================================================
// Document
//=======================================================================
	
?>
<script>
$(document).ready(function() {
	$('.popup-gallery').magnificPopup({
		delegate: 'a',
		type: 'image',
		tLoading: 'Loading image #%curr%...',
		mainClass: 'mfp-img-mobile',
		gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [1] // Will preload 0 - before current, and 1 after the current image
		},
		image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
			titleSrc: function(item) {
				return '';
			}
		}
	});
});
function openExamples(photogId){
	$( $("#gallery_" +photogId).find(":first-child")[0]).trigger("click");
}
</script>

	<div class="page-intro">
		<h1>Our Photographers</h1>
		<p><!-- <div class="meet-photographers">
        	<a href="#">Meet the Photographers</a>
        </div> -->
		<div class="clear"></div>
    </div>
    <div class="the-team">
<?php
	/*
    <!-- TEAM THUMB PIC, NAME, CAPTION -->
    	<h2>Ownership Group</h2>
        <div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/bret-peterson.jpg" width="184" height="184" alt="Bret Peterson - Owner" /></div>
            <div class="name">Bret Peterson</div>
            <div class="lbl">CEO & Partner</div>
        </div>


        <div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/lance-may.jpg" width="184" height="184" alt="Lance May - Senior Vice President" /></div>
            <div class="name">Lance May</div>
            <div class="lbl">Senior VP & Partner</div>
        </div>
*/
?>
        <div class="clear"></div>

<?php
		global $db;
		function fullStateName($abbrev){
			$abbrev = strtoupper($abbrev);
			$stateList = array(
				"AL" => "Alabama",
				"AK" => "Alaska",
				"AZ" => "Arizona",
				"AR" => "Arkansas",
				"CA" => "California",
				"CO" => "Colorado",
				"CT" => "Connecticut",
				"DE" => "Delaware",
				"FL" => "Florida",
				"GA" => "Georgia",
				"HI" => "Hawaii",
				"ID" => "Idaho",
				"IL" => "Illinois",
				"IN" => "Indiana",
				"IA" => "Iowa",
				"KS" => "Kansas",
				"KY" => "Kentucky",
				"LA" => "Louisiana",
				"ME" => "Maine",
				"MD" => "Maryland",
				"MA" => "Massachusetts",
				"MI" => "Michigan",
				"MN" => "Minnesota",
				"MS" => "Mississippi",
				"MO" => "Missouri",
				"MT" => "Montana",
				"NE" => "Nebraska",
				"NV" => "Nevada",
				"NH" => "New Hampshire",
				"NJ" => "New Jersey",
				"NM" => "New Mexico",
				"NY" => "New York",
				"NC" => "North Carolina",
				"ND" => "North Dakota",
				"OH" => "Ohio",
				"OK" => "Oklahoma",
				"OR" => "Oregon",
				"PA" => "Pennsylvania",
				"RI" => "Rhode Island",
				"SC" => "South Carolina",
				"SD" => "South Dakota",
				"TN" => "Tennessee",
				"TX" => "Texas",
				"UT" => "Utah",
				"VT" => "Vermont",
				"VA" => "Virginia",
				"WA" => "Washington",
				"WV" => "West Virginia",
				"WI" => "Wisconsin",
				"WY" => "Wyoming",
				"AS" => "American Samoa",
				"DC" => "District of Columbia",
				"FM" => "Federated States of Micronesia",
				"GU" => "Guam",
				"MH" => "Marshall Islands",
				"MP" => "Northern Mariana Islands",
				"PW" => "Palau",
				"PR" => "Puerto Rico",
				"VI" => "Virgin Islands"
			);
			return $stateList[$abbrev];
		}
		$q = "SELECT DISTINCT state FROM photographers WHERE state IS NOT NULL ORDER BY state ASC";
		$photogRes = $db->run($q);
		$photogs = array();
		foreach($photogRes as $index => $row){
			$res2 = $db->select("photographers","state='" . $row['state'] . "' and `showOnAbout`=1 ORDER by fullName ASC");
			$photogs[$row['state']] = $res2;
		}
		foreach( $photogs as $state => $p){
			if( count($p) ){
				echo '<h2>' . fullStateName($state) .  ' Photographers</h2>';
			}
			foreach($p as $index => $dude){
        		echo '<div class="team-member">';
				$name = preg_replace('|[^a-zA-Z]{1}|','',$fullName);
				preg_match('|(^[a-zA-Z]{1,} [a-zA-Z]{1,})|',$dude['fullName'],$matches);
				$name = $matches[1];
				$headshot = strtolower($name);
				$headshot = str_replace(' ','-',$headshot);
				if( !file_exists(dirname(__FILE__) . '/../repository_images/new/about/headshots/' . $headshot . '.jpg') ){
					echo '<div class="photo"><img src="../repository_images/missing-avatar.png" width="184" height="184" alt="' . $name . ' - Photographer" /></div>';
				}else{
	        		echo '<div class="photo"><img src="../repository_images/new/about/headshots/' . $headshot . '.jpg" width="184" height="184" alt="' . $name . ' - Photographer" /></div>';
				}
				echo '
            <div class="name">' . $name . '</div>
            <div class="lbl">Photographer</div>
        </div>';
			}
			echo "<div style='clear:both;'>&nbsp;</div>";
		}
?>

    <!-- END TEAM THUMB PIC, NAME, CAPTION -->



    <!-- TEAM BIO POPUPS -->
<?php
	foreach($photogs as $index2 => $photogs2){
		foreach($photogs2 as $index => $dude){
				$name = preg_replace('|[^a-zA-Z]{1}|','',$dude['fullName']);
				preg_match('|(^[a-zA-Z]{1,} [a-zA-Z]{1,})|',$dude['fullName'],$matches);
				$name = strtolower($matches[1]);
				$a = str_replace(' ','-',$name);
				$displayName = explode(" ",$name);
				$displayName = ucfirst($displayName[0]) . " " . ucfirst($displayName[1]);
			echo '
    <div class="team-bio-popup" id="' . $a  . '-bio">
    	<div class="team-bio">
        	<div class="close"></div>';
			if( !file_exists(dirname(__FILE__) . '/../repository_images/new/about/headshots/lrg/' . $a . '.jpg') ){
				echo "<div class='photo'><img width=290 src='/repository_images/missing-avatar.png' alt='" . $dude['name'] . " - " . $dude['position'] . "'></div>";
			}else{
				echo '<div class="photo"><img ' . $lrgWidth . ' src="../repository_images/new/about/headshots/lrg/' . $a . '.jpg" alt="' . $dude['name'] . ' - ' . $dude['position'] . '" /></div>';
			}
			echo '
            <div class="team-bio-txt">
            	<h2 class="team-member-name">' . $displayName . '</h2>
                <div class="bio-txt">
                	<h4>' . $dude['position'] . '</h4>
<p>';
			if( strlen($dude['bio']) == 0 ){
				echo "
Bio Coming soon...
				";
			}else{
				echo $dude['bio'];
		
			echo "</p>";
				$hasExamples = false;
				$res3 = $db->select("photographers_examples","photographerId=" . $dude['photographerID']);
				$hasExamples = count($res3);
				if( $hasExamples ){
					echo "<div class='portfolio'>
						<a href='javascript:void(0);' onClick='openExamples(" . $dude['photographerID'] . ")'>See examples of my work</a><br>
					  </div>
<div class='popup-gallery' id='gallery_" . $dude['photographerID'] . "' style='visibility: hidden;'>
					";
					foreach($res3 as $index3 => $row3){
						echo "<a href='http://spotlight-f-images-tours.s3.amazonaws.com/tours/" . $row3['tourId']  . "/photo_960_" . $row3['mediaId'] . ".jpg'>";
						echo "<img src='http://spotlight-f-images-tours.s3.amazonaws.com/tours/" . $row3['tourId'] . "/photo_960_" . $row3['mediaId'] . ".jpg' height='1' width='1'>";
						echo "</a>";

					}
					echo "</div>";

				}
			}


				echo "</div></div>";
			/*
			echo '
                <div class="email-form">
                    <h4 class="email-address">kariann@spotlighthometours.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon linkedin"></div>
                <div class="icon facebook"></div>
                <div class="icon video"></div>
                <div class="icon email" title="Email Me"></div>
            </div>';
			*/
			echo '
       </div>
	</div>
	';
	}
	}
?>
	<div style='clear:both;'>&nbsp;</div>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
	<div class="image-reel-pattern"></div>
<?PHP
	// FOOTER TEMPLATE
	require_once('../repository_inc/template-footer.php');
?>
