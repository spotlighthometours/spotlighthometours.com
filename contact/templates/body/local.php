<?PHP
	$city = $microsites->tours->city;
	$state = $microsites->tours->state;
	$res = $db->select("microsite_local_alias","source='" . $city . ", " . $state . "'");
	if( count($res) ){
		$a = explode(",",$res[0]['destination']);
		$city = trim($a[0]);
		$state = trim($a[1]);
		$desc = $microsites->getLocalPageDesc($city,$state);
	}else{
		$desc = $microsites->getLocalPageDesc($city,$state);
	}
	function kelvin($k){
		$f = (($k - 273.15)*9/5)+32;
		return $f;
	}	

	//Print the description
	//var_dump($desc);
	if( count($desc) ){
		$desc = $desc[0];

		//Print the description
		echo "<h2>$city, $state</h2>";
		echo $desc['description'];

		if( strlen($desc['history']) ){
			echo "<h2>History</h2>";
			echo $desc['history'];
		}
		
		if( strlen($desc['demographics']) ){
			echo "<h2>Demographics</h2>";
			echo $desc['demographics'];
		}

		if( strlen($desc['geography']) ){
			echo "<h2>Geography</h2>";
			echo $desc['geography']; 
		}

	}else{
		mail("william@spotlighthometours.com","$city $state needed for local pages","$city $state");
		echo "-- Description coming soon --";
	}

	$weather = $microsites->getWeatherData();
	$w = $weather;
	$desc = $w->weather[0]->description;
	$temp = $w->main->temp;
	
	echo "<div class='weather'>";
	echo "<b>Temperature:</b> " . intval(kelvin($temp)) . " F<br>";
	echo "<b>Description:</b> $desc<br>";
	echo "</div>";
?>
