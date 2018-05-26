<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <cfquery name="qBrokerages" datasource="#request.db.dsn#">
        select brokerageID,brokerageName,brokerageDesc
        from brokerages
        order by brokerageName asc
    </cfquery>
        
	<script type="text/javascript" src="/javascripts/jquery/jquery.js"></script>
	<script type="text/javascript" src="/javascripts/jquery/jquery.ui.js"></script>
	<script type="text/javascript" src="/javascripts/jquery/jquery.asmselect.js"></script>
 
    <script src="/javascripts/multipleselect.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="/javascripts/jquery/jquery.markitup.js"></script>
    <script type="text/javascript" src="/javascripts/html-editor.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			/*$("select[multiple]").asmSelect({
				addItemTarget: 'bottom',
				animate: true,
				highlight: true,
				sortable: true
			});*/
			
			$.maillist('#maildemo', '#preadded', '#mail-auto',{url:'fetched.cfm',cache:1}, 10, {userfilter:1,casesensetive:0});
			$('#html').markItUp(mySettings);
		}); 
		function change(){
			$.maillist('#maildemo', '#preadded', '#mail-auto',{url:'fetched.cfm',cache:1}, 10, {userfilter:1,casesensetive:0});
		}
    </script>

	<link rel="stylesheet" type="text/css" href="/stylesheets/jquery.asmselect.css" />
	<link rel="stylesheet" type="text/css" href="/stylesheets/mail.css" />
    <link rel="stylesheet" type="text/css" href="/stylesheets/multipleSelect.css" />
    <link rel="stylesheet" type="text/css" href="/stylesheets/markitup-style.css" />
	<link rel="stylesheet" type="text/css" href="/stylesheets/html-editor-style.css" />
</head>
<body>
<h3>Mail Sender</h3>
<form action="/admin/mail/?action=sendmail" method="post">
     <ol>
       <h3>Unsubscribed Users</h3>
       <label>Send to Unsubscribed Users <input name="unsubscribe" type="checkbox" value="1" /></label>
    </ol>
   
    <ol> 
    <h3>Brokerage Mail Receiptians</h3>
    <select id="rokerages" multiple="multiple" name="brokerages" title="Click to Select a Brokerage">
    <cfloop query="qBrokerages">
        <cfoutput>
        <option value="#qBrokerages.brokerageID#">#qBrokerages.brokerageName# - #qBrokerages.brokerageDesc#</option>	 
        </cfoutput>
    </cfloop>
    </select>
    </ol>
    <ol>
       <h3>Send Email to all users</h3>
       <label>Send to all <input name="sendtoall" type="checkbox" value="1" /></label>
    </ol>
    <ol>        
       <li id="mail-list" class="input-text">
        <h3>Individual User Mail Receiptians</h3>
        	<input type="text" value="" id="maildemo"  />
            <ul id="preadded" style="display:none">
            </ul>
        <div id="mail-auto">
        	<div class="default">Type the name of  a user</div> 
            <ul id="feed" style="z">
            </ul>
        </div>
        </li>
      </ol> 
      <ol>        
       <li id="mail-list" class="input-text">
        <h3>Subject</h3>
        	<input type="text" value="" name="subject"  />
        
        </div>
        </li>
      </ol>  
      <ol>        
       <li id="mail-list" class="input-text">
        <h3>Body</h3> 
    <textarea  name="htmlbody"id="html" cols="80" rows="20"></textarea>
    	</li>
       </ol> 
    <input type="submit" name="submit" value="Send" />
</form>
</body>
</html>
