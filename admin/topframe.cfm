<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>TopFrame</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="includes/admin_styles.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.headText {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 24px;
	font-weight: bold;
	color: #FFFFFF;
	margin-left: 20px;
}

body { padding: 0px; }
-->
</style>
    
<script type="text/javascript" src="/admin/includes/jquery-ui-1.8.9/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="/admin/includes/jquery-ui-1.8.9/js/jquery-ui-1.8.9.custom.min.js"></script> 
<script>
	function openPopup(url, x, y) {
		try {
			window.open(url,'Preview',"location=0,status=0,scrollbars=0, width=" + x + ",height=" + y);
		} catch(err) {
			alert("openPopup: " + err);
		}
	}

	function matchEmail(email){
		var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
		return re.test(email);		
	}

	function pop(){
		tourId = $("#searchTour").val();
		if( matchEmail(tourId) ){
			parent.frames['mainFrame'].document.location.href='http://www.spotlighthometours.com/admin/users/users.php?email=' + tourId;
		}else{
			parent.frames['mainFrame'].document.location.href='http://www.spotlighthometours.com/admin/recenttours/?tourid=' + tourId + '&GO2=GO';
		}
        
	}
$(document).ready(function(){
    $("#searchTourBtn").click(function(){
        pop();
    });
    $("#searchTour").keypress(function(e) {
        if(e.which == 13) {
            pop();
        }
    });
});
var AJAX = function (params) {
    this.server ={};
    this.url = params.url;
    this.method = params.method;
    this.dataType = params.dataType;
    this.formData = params.formData;

    this.init = function(){
        if(typeof XMLHttpRequest != 'undefined'){
            this.server = new XMLHttpRequest();

            //Open first, before setting the request headers.
            this.server.open(this.method, this.url, true);

            //Now set the request headers.
            this.server.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			this.server.setRequestHeader( 'Access-Control-Allow-Origin', '*');
            //this.server.setRequestHeader('Content-length', this.formData.length);
            //this.server.setRequestHeader('Connection', 'close');
            console.log("XMLHttpRequest created.");
            return true;
        }
    };

    this.send = function(){
        if(this.init()){
            this.server.send(this.formData);
        }
    };

};
</script>
</head>

<body>
<cfoutput>
<table width="100%" height="50" border="0" cellpadding="0" cellspacing="0">
 <tr>
  <td width="800" background="images/top_frame_bg.gif">
  <div class="headText">#request.admin.name# Administrator</div>
  </td>
  <td bgcolor="##5A616B">&nbsp;</td>
 </tr>
</table>
</cfoutput>
</body>
</html>
