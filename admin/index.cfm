
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title><cfoutput>#request.admin.name#</cfoutput> :: Management Console</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<frameset rows="55,*" cols="*" frameborder="NO" border="0" framespacing="0">
 <frame src="topframe.cfm" name="topFrame" scrolling="NO" noresize>
 <frameset cols="190,*" frameborder="NO" border="0" framespacing="0">
  <frame src="leftNavBar.php" cols="20,*" name="leftFrame" scrolling="NO">
  <frame src="main.cfm" name="mainFrame">
  
 </frameset>
</frameset>
<noframes>
<body>
Your browser does not support frames. For the love of your mother upgrade your browser.
</body>
</noframes>
</html>
