<?php
        require '../repository_inc/classes/inc.global.php';
        if( !isset($_SESSION['admin_id']) ){
            echo '<html><script>parent.location.href="/admin/login.cfm";</script><body></body></html>';
            exit;
        }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Left Nav Bar</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK HREF="includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
<STYLE TYPE="text/css">
<!--
#container {
	height: 100%;
	width: 200px;
	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: #A2A2A2;
	background-color: #f4f4f4;
	text-align: center;
	padding-top: 15px;
}

body { padding: 0px; }

.divlink {
	font-size: 1em;
	text-align: center;
	color: blue;
	background: none;
	margin: 0;
	padding: 0;
	border: none;
	cursor: pointer;
	text-decoration: underline;
}

-->
</STYLE>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script language = "javascript">
	// Destroy the PHP session then move on to logout of the CF session.
    $(document).ready(function(){
    	$("#logout").bind("click",function(){
    			var url = "../repository_inc/login_logic.php";
    			var params = "logout=true";
    			<?php 
    			     /* #########################################################
    			      * The following is required to kill the coldfusion session
    			      * #########################################################
    			      */
    			?>
    			$.ajax({
     			   type: "POST",
     			   url: "<?php echo SITE_URL ;?>/admin/login.cfm?logout=1",
     			   data: {
     				    foo: "bar"
     			   }
     		    }).done(function(msg){
     	 		    
     		    	  $.ajax({
     	 		    	  type: "POST",
     	 		    	  url: "/repository_inc/login_logic.php?logout=1",
     	 		    	  data: {
     	 	 		    	  logout: "true"
     	 		    	  },
     	 		    	  async: false
     		    	  }).done(function(msg){
     		    		    parent.location = "/admin/login.cfm?logout=2";
     		    	  });
     		    });
    	});
    });	
	window.setInterval(function(){
    		   $.ajax({
    			   type: "POST",
    			   url: "<?php echo SITE_URL; ?>/admin/leftNavBar.php",
    			   data: {
    				    foo: "bar"
    			   }
    		   }).done(function(msg){
    			   $.ajax({
        			   type: "POST",
        			   url: "<?php echo SITE_URL; ?>/admin/index.cfm"
    			   });
    		   });
        },5 * 60 * 1000 /* 5 minutes */
	);
</script>

<BODY>
<DIV ID="container" style="overflow:auto;">
<DIV STYLE="padding: 0px;">
<DIV STYLE="background-color:#ddd;"><A HREF="main.cfm" TARGET="mainFrame">Admin Home</A></DIV>
<DIV ><A HREF="progress/" TARGET="mainFrame">PHOTO/VIDEO QUEUE</A></div>
<DIV STYLE="background-color:#ddd;"><A HREF="photographers/upload-sessions.php" TARGET="mainFrame">[NEW] Photographer Upload Sessions</A></DIV>
    <?php
        $nav = new navbar($_SESSION['admin_id']);
        
		$perm = $nav->get("pageList");
        $i = 0;
		//var_dump($perm);
        $perm = record_sort($perm,"name");
        foreach($perm as $index => $nav){
            $i++;
            echo '<DIV ';
            if( ($i % 2) == 0 ){
                echo 'style="background-color:#ddd;"';
            }
            echo '><A HREF="' . $nav['href'] . '" TARGET="mainFrame">';
            if( $nav['new'] == 1 ){
                echo "[NEW]";
            }
            echo $nav['name'] . '</A></div>';
        }
    ?>
<DIV id='logout' class="divlink" >Logout</div>
</DIV>
<DIV><br></DIV>
</DIV>
</BODY>
</HTML>
