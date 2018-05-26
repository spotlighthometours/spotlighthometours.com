<cfsilent>
	<!--- grab the tour id and put it into the url line so that it can be used by the application --->
	<cfparam name="url.tour" default="" />
	<cfset session.reorder.tourid = url.tour />
</cfsilent>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="/admin/includes/admin_styles.css" rel="stylesheet" type="text/css">
	<title>Reorder Media</title>
	<script src="/admin/users/tours/AC_OETags.js" language="javascript"></script>
	<script language="JavaScript" type="text/javascript">
	<!--
	// -----------------------------------------------------------------------------
	// Globals
	// Major version of Flash required
	var requiredMajorVersion = 9;
	// Minor version of Flash required
	var requiredMinorVersion = 0;
	// Minor version of Flash required
	var requiredRevision = 28;
	// -----------------------------------------------------------------------------
	// -->
	</script>
</head>
<body>
	<script language="JavaScript" type="text/javascript">
	<!--
	// Version check for the Flash Player that has the ability to start Player Product Install (6.0r65)
	var hasProductInstall = DetectFlashVer(6, 0, 65);
	
	// Version check based upon the values defined in globals
	var hasRequestedVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
	
	if ( hasProductInstall && !hasRequestedVersion ) {
		// DO NOT MODIFY THE FOLLOWING FOUR LINES
		// Location visited after installation is complete if installation is required
		var MMPlayerType = (isIE == true) ? "ActiveX" : "PlugIn";
		var MMredirectURL = window.location;
	    document.title = document.title.slice(0, 47) + " - Flash Player Installation";
	    var MMdoctitle = document.title;
	
		AC_FL_RunContent(
			"src", "playerProductInstall",
			"FlashVars", "MMredirectURL="+MMredirectURL+'&MMplayerType='+MMPlayerType+'&MMdoctitle='+MMdoctitle+"",
			"width", "100%",
			"height", "100%",
			"align", "middle",
			"id", "PictureOrganizer",
			"quality", "high",
			"bgcolor", "#869ca7",
			"name", "PictureOrganizer",
			"allowScriptAccess","sameDomain",
			"type", "application/x-shockwave-flash",
			"pluginspage", "http://www.adobe.com/go/getflashplayer"
		);
	} else if (hasRequestedVersion) {
		// if we've detected an acceptable version
		// embed the Flash Content SWF when all tests are passed
		AC_FL_RunContent(
				"src", "PictureOrganizer",
				"width", "100%",
				"height", "100%",
				"align", "middle",
				"id", "PictureOrganizer",
				"quality", "high",
				"bgcolor", "#869ca7",
				"name", "PictureOrganizer",
				"allowScriptAccess","sameDomain",
				"type", "application/x-shockwave-flash",
				"pluginspage", "http://www.adobe.com/go/getflashplayer"
		);
	  } else {  // flash is too old or we can't detect the plugin
	    var alternateContent = 'Alternate HTML content should be placed here. '
	  	+ 'This content requires the Adobe Flash Player. '
	   	+ '<a href=http://www.adobe.com/go/getflash/>Get Flash</a>';
	    document.write(alternateContent);  // insert non-flash content
	  }
	// -->
	</script>
	<noscript>
	  	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
				id="PictureOrganizer" width="100%" height="100%"
				codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
				<param name="movie" value="PictureOrganizer.swf" />
				<param name="quality" value="high" />
				<param name="bgcolor" value="#FFFFFF" />
				<param name="allowScriptAccess" value="sameDomain" />
				<embed src="PictureOrganizer.swf" quality="high" bgcolor="#FFFFFF"
					width="100%" height="100%" name="PictureOrganizer" align="middle"
					play="true"
					loop="false"
					quality="high"
					allowScriptAccess="sameDomain"
					type="application/x-shockwave-flash"
					pluginspage="http://www.adobe.com/go/getflashplayer">
				</embed>
		</object>
	</noscript>
</body>	