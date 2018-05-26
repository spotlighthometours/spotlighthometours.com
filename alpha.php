<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Alpha Test</title>
<style>
	#frame {
		position: absolute;
		width: 400px;
		height: 400px;
		top: 50%;
		left: 50%;
		margin-left: -200px;
		margin-top: -200px;
		font-size: 22px;
		font-weight: bold;
		line-height: 400px;
		text-align: center;
		background-color: yellow;
	}
	
	#transparent {
		position: absolute;
		width: 100%;
		height: 100%;
		top: 0px;
		left: 0px;
		filter: alpha(opacity=80);
		opacity: 0.8;	
		background-color: white;
	}

</style>
</head>

<body>
	<div id="frame" >
    	CAN YOU SEE THIS?
    	<div id="transparent" ></div>
    </div>
</body>
</html>