<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
    ini_set('display_errors', 1);
    $pdf="Flyer" . $_REQUEST['tourID'] . ".pdf";
    $quality=100;
    $res='300x300';
	
    $exportName="Flyer" . $_REQUEST['tourID'] . ".jpg";
    $exportPath="";
	     
    set_time_limit(900);
    exec("'gs' '-dNOPAUSE' '-sDEVICE=jpeg' '-dUseCIEColor' '-dTextAlphaBits=4' '-dGraphicsAlphaBits=4' '-o$exportPath' '-r$res' '-dJPEGQ=$quality' '$pdf'",$output);
    
    for($i=0;$i<count($output);$i++)
        echo($output[$i] .'<br/>');
?>
</body>
</html>