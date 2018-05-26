<?php
    require '../../repository_inc/classes/inc.global.php';
    showErrors();
    clearCache();
    $users = new users($db);
    $users->authenticateAdmin();
    
    //###################################
    // Create instances of needed objects
    //###################################
    $nb = new navbar($_SESSION['admin_id']);
    if( !$nb->canEdit($_SESSION['admin_id']) ){
        die("<h1>You do not have sufficient permissions to use this page</h1>");
    }
    
    if( isset($_POST['save'])){
        $nb = new navbar($_POST['employees']);
        $nb->clearPermissions($_POST['employees']);
        foreach($_POST as $key => $value){
            if( preg_match('|^checkbox_([\d]+)|',$key,$matches)){
                $nb->addPermission($_POST['employees'],$matches[1]);
            }
        }
        
        //Save username and password
        $db->update("administrators",['username' => $_POST['userName'], 
            'password' => $_POST['password']],
            "administratorID=" . intval($_POST['employees'])
        );
    }
    
    
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Memberships</title>
<script src="../../repository_inc/jquery-1.6.2.min.js"
	type="text/javascript"></script>
<!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js"
	type="text/javascript"></script>
<!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script>
<!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script>
<!-- Admin JS file -->
<script lanuage="javascript">
$(document).ready(function(){
    $("#tableList").hide("fade").show("clip");
    $("#selectAllTop").bind("click",function(e){
        prop = $(this).prop("checked");
        $("input[id^='checkbox_']").each(function(){
            console.log("1");
            $(this).prop("checked",prop);
        });
        
    });
    $("tr").not(':first').hover(
    		  function () {
    		    $(this).css("background-color","rgb(226,240,250)");
    		  }, 
    		  function () {
    		    $(this).css("background","");
    		  }
    );
    if( browser.chrome ){
        $("input[id^='checkbox_']").each(function(){
            $(this).bind("click",function(e){
                e.stopPropagation();
            });
        });
    }
    $("input[id^='checkbox_']").each(function(){
        $(this).bind("click",function(e){
            e.stopPropagation();
        });
    });
    $("tr[id^='row_'] td").each(function(){
        $(this).bind("click",function(){
        	a = $(this).parent().prop("id").split('_')[1];
            obj = $("#checkbox_"+a);
            if( obj.is(":checked") ){
                $(obj).prop("checked",false);
            }else{
                $(obj).prop("checked",true);
            }
        });
    });

    $("#employee").bind("change",function(){
        val = $(this).prop("value");
        window.location.href="?employees=" + val;
    });


    $("#go").bind("click",function(e){
        $("#unameForm").trigger("submit");
    });

});

</script>
<style type="text/css" media="screen">
	@import "../../repository_css/template.css";
 	@import "../../repository_css/admin-v2.css"; 
    @import "../../repository_css/jquery-ui-1.8.16.custom.css";
#viewNotesTWrapper{
    float: left;
    left: -320px;
}
    
#go {
	position: relative;
	top: 0px;
	left: 320px;
}
#goWrapper {
	width: 500px;
	height: 240px;
	border: 1px solid #ccc;
	display: inline-block;
	position: relative;
	-webkit-border-top-left-radius: 5px;
	-webkit-border-top-right-radius: 5px;
	-moz-border-radius-topleft: 5px;
	-moz-border-radius-topright: 5px;
	border-top-left-radius: 5px;
	border-top-right-radius: 5px;
	border-bottom: 0px;
	margin: 10px;
	background-color: white;
}
a{
border: 1px solid green;
}
#modalSaveChanges b{
    float: left;
    margin-bottom: 70px;
}
#modalCancel{
    clear: left;
    
}

#modalSave {
    
}
h3 {
	padding: 10px 5px 10px 20px;
	display: inline-block;
}

#tabSpan {
	position: relative;
	left: -12px;
	float: right;
	width: 370px;
	height: 230px;
	border-bottom: 1px solid #ccc;
	margin-top: 10px;
	margin-right: 6px;
	white-space: no-wrap;
	display: inline-block;
}

#tableWrapper {
	background-color: white;
	position: relative;
	top: -20px;
	height: 2000px;
}

#saveTop {
	float: right;
}

body {
	margin: 0px;
	padding: 0px;
	width: 900px;
	background: rgb(254, 255, 255);
	background: -moz-radial-gradient(center, ellipse cover, rgba(254, 255, 255, 1)
		0%, rgba(210, 235, 249, 1) 100%);
	background: -webkit-gradient(radial, center center, 0px, center center, 100%,
		color-stop(0%, rgba(254, 255, 255, 1)),
		color-stop(100%, rgba(210, 235, 249, 1)));
	background: -webkit-radial-gradient(center, ellipse cover, rgba(254, 255, 255, 1)
		0%, rgba(210, 235, 249, 1) 100%);
	background: -o-radial-gradient(center, ellipse cover, rgba(254, 255, 255, 1)
		0%, rgba(210, 235, 249, 1) 100%);
	background: -ms-radial-gradient(center, ellipse cover, rgba(254, 255, 255, 1)
		0%, rgba(210, 235, 249, 1) 100%);
	background: radial-gradient(ellipse at center, rgba(254, 255, 255, 1) 0%,
		rgba(210, 235, 249, 1) 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#feffff',
		endColorstr='#d2ebf9', GradientType=1);
}
</style>
</head>
<body>
<form method=POST id='unameForm'>

		<div id='goWrapper'>
		  <!--  <form id='empForm' method=GET>-->
			<div id='goText'>
    				<h3>Choose an employee:</h3>
    				<span> <select name='employees' id='employee'>
                        <?php 
                            global $db;
                            $currentPass = $currentUser = null;
                            $res = $db->run("SELECT * FROM administrators ORDER BY fullName ASC");
                            foreach($res as $index => $info):
                        ?>
                        
                            <option value='<?php echo $info['administratorID']; ?>'
            							<?php if( $info['administratorID'] == $_GET['employees'] ){ 
                							    $currentUser = $info['username'];
                							    $currentPass = $info['password'];
                							    echo ' selected=selected '; 
            							     }?>><?php echo $info['fullName'];?></option>
                        <?php 
                            endforeach;
                        ?>
                            </select>
    				</span>
            </div><!--  goText -->    				
		
			<div id='userNameWrapper'>
			     <h3>Username: </h3> 
			     <input type='text' id='userName' name='userName' value="<?php echo $currentUser;?>">
			</div>
			<div id='passwordWrapper'>
			     <h3>Password:</h3>
			     <input type='text' id='password' name='password' value="<?php echo $currentPass;?>">
			</div>
			<div id='go'>
				<div class="button_new button_blue button_mid">
					<div class="curve curve_left"></div>
					<span class="button_caption">Save</span>
					<div class="curve curve_right"></div>
				</div>
			</div>
			<input type='hidden' name='save' value='save'>
		
	</div><!--  goWrapper -->
	<div id='tabSpan'>&nbsp;</div>


	
	
	<div id='tableWrapper'>&nbsp;
<?php 
    if( isset($_GET['employees']) ):
?>
	
			<table id='tableList' class='list'>
				<thead>
					<tr>
						<th align="center"><input type='checkbox' id='selectAllTop'></th>
						<th>Title</th>
						<th align="center">Link</th>
					</tr>
				</thead>
				<tbody>
                    
                        <?php 
                            $nb = new navbar($_GET['employees']);
                            /*
                            $all = $db->run( "SELECT * FROM navbar_permissions " .
                           		" RIGHT JOIN navbar n ON n.id = navbar_permissions.navbarId " .
                                " INNER JOIN administrators a on a.administratorID = navbar_permissions.adminId " . 
                                " WHERE a.administratorID = " . intval($_GET['employees'])
                            );
                            */
                            $nav = $db->run($q="SELECT * FROM navbar_permissions WHERE navbar_permissions.adminId = " . intval($_GET['employees']));
                            $a = [];
                            foreach($nav as $i => $navItem){
                                $a[] = $navItem['navbarId'];
                            }
                            $all = $db->run( "SELECT * FROM navbar ORDER BY name ASC");
                            foreach($all as $index => $array){
                                $id = $array['id'];
                                echo "<tr id='row_$id'>";
                                if( in_array($array['id'],$a) ){
                                    $selected = "checked=checked";
                                }else{
                                    $selected = "";
                                }
                                echo "<td><input type='checkbox' name='checkbox_$id' value='$id' $selected id='checkbox_$id' ></td>";
                                echo "<td>" . $array['name'] . "</td>";
                                echo "<td>" . $array['href'] . "</td>";
                                echo "</tr>";
                            }
                        ?>        
                </tbody>
			</table>
			<input type=hidden name='cbSubmit' value=1>
		</form>
	</div>
		
		
		<?php 
                
    endif;
?>

</body>
</html>
