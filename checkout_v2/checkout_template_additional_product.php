<?php

$icon_style = "";
if(strlen(trim($product['icon'])) > 0) {
	$icon_style = 'background-image: url(' . rawurlencode($product['icon']) . ');';
}

if(isset($product['retail_price'])){
	$tagline = '<span class="retail">Retail: $' . number_format($product['retail_price'], 2, '.', '') . '</span><span class="package">Package price: <span class="price">$' . number_format($product['price'], 2, '.', '') . '</span></span>';
}else{
	$tagline = '$' . number_format($product['price'], 2, '.', '');
}
if (strlen($product['tagline']) > 0 ) {
	$tagline = $product['tagline'];
}

$data = '';
if(isset($product['required_fields'])){
	$data = 'data-required="';
	$first = true;
	foreach($product['required_fields'] as $rindex => $required_field){
		if(!$first){
			$data .= '::';
		}
		$data .= $required_field;
		$first = false;	
	}
	$data .= '"';
}

$addMedia = false;
if($media->hasAttachments($product['id'])){
	$addMedia = true;
}

if($addMedia){
	$button = '
		<div id="aps_' . intval($product['id']) . '" '.$data.' class="button_new button_tour button_sm col_right" onclick="SelectProduct(this);" >
			<div class="curve curve_left" ></div>
			<span class="button_caption" >+ Media</span>
			<div class="curve curve_right" ></div>
		</div>
	';
	if(intval($product['onePerOrder']) == 0) {
		$button = '
			<div id="aps_' . intval($product['id']) . '" '.$data.' class="button_new button_tour button_sm col_right" onclick="IncrementProduct(this);" >
				<div class="curve curve_left" ></div>
				<span class="button_caption" >+ Media</span>
				<div class="curve curve_right" ></div>
			</div>
		';
	}
}else{
	if(intval($product['onePerOrder']) == 1) {
		$button = '
			<div id="aps_' . intval($product['id']) . '" '.$data.' class="button_new button_tour button_sm col_right" onclick="SelectProduct(this);" >
				<div class="curve curve_left" ></div>
				<span class="button_caption" >Select</span>
				<div class="curve curve_right" ></div>
			</div>
		';	
	} else {
		$button = '
			<div class="button_new button_tour button_sm col_right" >
				<div class="curve col_left" ></div>
				<span class="button_caption" >0</span>
				<div class="curve col_right" ></div>
				<div id="apm_' . intval($product['id']) . '" class="increment minus" onclick="DecrementProduct(this);" ></div>
				<div id="app_' . intval($product['id']) . '" class="increment plus" onclick="IncrementProduct(this);" ></div>
			</div>
		';
	}
}

$addClass = '';

if(isset($product['retail_price'])){
	$addClass = 'highlightPackageDeal';
}
$addMediaData = ($addMedia)?'true':'false';
echo '

<div id="ap_' . intval($product['id']) . '" class="additional_product ' . $style . ' '.$addClass.'" data-addmedia="'.$addMediaData.'">
';
if(isset($product['retail_price'])){
	echo'<div class="package-icon"></div>';
}
echo'
	<div class="icon_frame" style="' . $icon_style . '" ></div>
	<div class="title_frame" >' . $product['title'] . '</div>
	<div class="tagline_frame" >' . $tagline . '</div>
	<div class="button_frame" >
		<div id="aplm_' . intval($product['id']) . '" class="button_new button_tour button_sm col_left" onclick="LearnMore(this, ' . chr(39) . $product['title'] . chr(39) . ');" >
			<div class="curve curve_left" ></div>
			<span class="button_caption" >Learn More</span>
			<div class="curve curve_right" ></div>
		</div>
		' . $button . '
	</div>
</div>

';
?>