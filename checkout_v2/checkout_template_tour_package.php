<?php

$icon_style = "";
if(strlen(trim($package['icon'])) > 0) {
	$icon_style = 'background-image: url(' . str_replace('../','https://www.spotlighthometours.com/',$package['icon']) . ');';
}

$addClass = '';

if(isset($package['retail_price'])){
	$addClass = 'highlightPackageDeal';
}

$data = '';
if(isset($package['required_fields'])){
	$data = 'data-required="';
	$first = true;
	foreach($package['required_fields'] as $rindex => $required_field){
		if(!$first){
			$data .= '::';
		}
		$data .= $required_field;
		$first = false;	
	}
	$data .= '"';
}

echo '
<div class="tour_package '.$addClass.'">
';
if(isset($package['retail_price'])){
	echo'<div class="package-icon"></div>';
}
echo'
	<div class="icon_frame" >
		<div class="icon" style="' . $icon_style . '" ></div>
	</div>
	<div class="text_frame" >
		<span class="title_text" >' . $package['title'] . '</span><br />
		<span class="tag_text" >' . $package['tagline'] . '</span><br />
		<span class="descrip_text" >
			' . str_replace(chr(10),"<br />", $package['description']) . '
		</span>
	</div>
	<div class="price_frame" >
';
if(isset($package['retail_price'])){
	echo '
		<div class="price packagePrice" >$' . number_format($package['price'], 2, '.', '') . '</div>
		<div class="retailPrice" >Retail: $' . number_format($package['retail_price'], 2, '.', '') . '</div>
	';
}else{
	echo '
		<div class="price" >$' . number_format($package['price'], 2, '.', '') . '</div>
	';
}
echo '
		<div class="button_new button_tour button_sm" onclick="openPopup(' . chr(39) . $package['demo'] . chr(39) . ', 980, 730);" >
			<div class="curve curve_left" ></div>
			<span class="button_caption" >View Demo</span>
			<div class="curve curve_right" ></div>
		</div>
		<div id="tt_' . intval($package['id']) . '" '.$data.' class="button_new button_tour button_sm" onclick="';

// If there is an upgrade prompt
if(intval($package['upgradeId']) > 0) {
	echo "UpgradePackage(" . $package['id'] . ", " . $package['upgradeId'] . ", '" . $package['upgradeDoc'] . "');";
} else {
	echo 'SelectPackage(this);';
}
		
echo '" >
			<div class="curve curve_left" ></div>
			<span class="button_caption" >Select</span>
			<div class="curve curve_right" ></div>
		</div>
	</div>
</div>
';
?>