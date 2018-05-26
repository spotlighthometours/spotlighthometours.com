en send feedback to photographer
if(isset($_REQUEST['send_email'])){
	if(isset($_REQUEST['negative_selection'])){
		$negativeMsg = "<ul>";
		foreach($_REQUEST['negative_selection'] as $index => $on_off){
			$negativeMsg .= "<li>".$negative[$index]."</li>";
		}
		$negativeMsg .= "</ul>";
	}else{
		$negativeMsg = "None selected.";
	}
	
	if(isset($_REQUEST['positive_selection'])){
		$positiveMsg = "<ul>";
		foreach($_REQUEST['positive_selection'] as $index => $on_off){
			$positiveMsg .= "<li>".$positive[$index]."</li>";
		}
		$positiveMsg .= "</ul>";
	}else{
		$positiveMsg = "None selected.";
	}
	
	if(isset($_REQUEST['comments'])){
		$comments = $_REQUEST['comments'];
	}else{
		$comments = "No comments";
	}
	
	$comments =	$_REQUEST['comments'];
	if(empty($_REQUEST['comments'])){
		$comments = "No comments.";
	}
	
	if ($_REQUEST['video'] == '1') {
		if ($_REQUEST['shootType'] == 'Initial') {
			$editorName = $VideoEditorName;
			$PhotoShootDate = $VideoShootDate;
			$photographerName = $VideoPhotographerName;
			$photographerEmail = $VideoPhotographerEmail;
			$photographerID = $VideoPhotographerID;
		} else {	// Secondary Video
			$editorName = $VideoReEditorName;
			$PhotoShootDate = $VideoReShootDate;
			$photogr