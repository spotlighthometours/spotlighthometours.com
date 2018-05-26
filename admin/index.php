<?php
	session_start();
	require '../repository_inc/classes/inc.global.php';

//var_DUMP($_SESSION);
    header("Location: /admin/");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title>Spotlight Home Tours :: Management Console</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<frameset rows="55,*" cols="*" frameborder="NO" border="0" framespacing="0">
 <frame src="topframe.cfm" name="topFrame" scrolling="NO" noresize>
 <frameset cols="190,*" frameborder="NO" border="0" framespacing="0">
  <frame src="leftNavBar.php" cols="20,*" name="leftFrame" scrolling="NO">
  	<?php
  		if( isset($_GET['orig']) ){
			/* Sanitize it */
			$url = filter_var($a=SITE_URL . '/' . urldecode($_GET['orig']),
				FILTER_VALIDATE_URL, 
				FILTER_FLAG_PATH_REQUIRED
			);
		
	
            if( strlen($url) ){
			    echo '<frame src="' . $url . '" name="mainFrame">';
			}else{
                echo '<frame src="main.cfm" name="mainFrame">';
            }
		}else{
  			echo '<frame src="main.cfm" name="mainFrame">';
    	}
    ?>
 </frameset>
</frameset>
<noframes>
<body>
Your browser does not support frames. For the love of your mother upgrade your browser.
</body>
</noframes>
</html>
