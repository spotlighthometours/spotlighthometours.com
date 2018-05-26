<?PHP
	include('../repository_inc/classes/inc.global.php');
	showErrors();
	$propertyurls = new propertyurls();
	if(isset($_REQUEST['propertyURL'])){
		$userID = $propertyurls->getUserID($_REQUEST['propertyURL']);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Community Data</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<script src="../repository_inc/jquery-1.7.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body style="padding:10px;">
	<div class="container">    
        <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
            <div class="panel panel-info" >
                    <div class="panel-heading">
                        <div class="panel-title">Get user from Property URL</div>
                    </div>     
                    <div style="padding-top:30px" class="panel-body" >
<?PHP
						if(isset($userID)&&$userID){
?>
                        <div class="alert alert-info col-sm-12">User found! Click here to <a target="_blank" href="http://www.spotlighthometours.com/admin/users/users.cfm?pg=editUser&user=<?PHP echo $userID ?>">[view user]</a></div>   
<?PHP
						}
?>
                        <form id="loginform" class="form-horizontal" role="form">      
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input id="propertyURL" type="text" class="form-control" name="propertyurl" value="" placeholder="property url">                                        
                                    </div> 
                                <div style="margin-top:10px" class="form-group">
                                    <!-- Button -->
                                    <div class="col-sm-12 controls">
                                      <a id="btn-login" href="javascript:window.location='?propertyURL='+$('#propertyURL').val()" class="btn btn-success">Get User</a>
                                    </div>
                                </div>    
                            </form>     
                        </div>                     
                    </div> 
		</div> 
    </div>
</body>
</html>