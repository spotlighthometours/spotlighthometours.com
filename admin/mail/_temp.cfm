<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <cfquery name="qBrokerages" datasource="#request.db.dsn#">
        select brokerageID,brokerageName,brokerageDesc
        from brokerages
        order by brokerageName asc
    </cfquery>
        
		<script type="text/javascript" src="/javascripts/jquery/jquery.js"></script>

    <script src="/javascripts/multipleselect_1.js" type="text/javascript" charset="utf-8"></script>
 
	<script type="text/javascript">
		$(document).ready(function() {
		
			$.maillist('#maildemo', '#preadded', '#mail-auto',{url:'fetchcities.cfm',cache:1}, 5, {userfilter:1,casesensetive:0});
			
		}); 
		function change(){
			$.maillist('#maildemo', '#preadded', '#mail-auto',{url:'fetchcities.cfm',cache:1}, 5, {userfilter:1,casesensetive:0});
		}
    </script>


    <link rel="stylesheet" type="text/css" href="/stylesheets/multipleSelect_1.css" />

</head>
<body>
<h3>Mail</h3>
<form action="/admin/mail/_temp.cfm" method="post"><li id="mail-list" class="input-text"><h3>Individual User Mail Receiptians</h3>
       	 <input type="text" value="" id="maildemo"  />
       
      <div id="mail-auto">
       	  <div class="default">Type the name of  a city.eg. pr,ut for "PROVO,UT"</div> 
          <ul id="feed" style="z">
          </ul>
      </div>
        </li>
<ol>        
       <li id="mail-list" class="input-text">
         <input type="submit" name="submit" value="Send" />
</li>
      </ol>  
</form>
</body>
</html>
