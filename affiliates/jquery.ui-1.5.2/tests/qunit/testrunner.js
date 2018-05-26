<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">&#xD;
<!-- saved from url=(0014)about:internet -->&#xD;
<!--#config errmsg=""--><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="book" content="Adobe&reg;&nbsp;ColdFusion&reg;&nbsp;9&nbsp;Language&nbsp;Reference"><meta name="lang" content="-"><link rel="stylesheet" href="style.css" type="text/css" media="screen"><link rel="stylesheet" href="print.css" type="text/css" media="print"><link rel="stylesheet" href="override.css" type="text/css"><link rel="stylesheet" href="ion.css" type="text/css"><title>SQL error detail messages, ids, and arguments (ColdFusion 9)</title></head><body><script language="javascript" type="text/javascript" src="asdoc.js"></script><script language="javascript" type="text/javascript" src="help.js"></script><script language="javascript" type="text/javascript" src="cookies.js"></script><script language="javascript" type="text/javascript">  <!--  
        
         
        asdocTitle = "SQL error detail messages, ids, and arguments - Adobe® ColdFusion® 9 Language Reference"; 
        var baseRef = ''; 
        window.onload = configPage; 
          -->  </script>
<script language="javascript" type="text/javascript"><!--
function submitValue() {
var title="Adobe® ColdFusion® 9 Language Reference";
var searchStr=document.getElementById('search-livedocs').value;

        
        
        if(document.getElementById('checkBoxId').checked){
        window.location="http://community.adobe.com/help/search.html?q=" + searchStr
+"&loc=en_US"
+"&hl=en_US"
+"&lbl=flex_product_adobe_lr"
+"&go=Search"
+"&self=1"
+"&site=communityhelp-cs3";
} 

        else{ 
window.location="http://community.adobe.com/help/search.html?q=" + searchStr
+"&loc=en_US"
+"&hl=en_US"
+"&lbl=flex_product_adobe_lr"
+"&go=Search"
+"&self=1"
;
} 
} 
--></script><script type="text/javascript"> scrollToNameAnchor(); </script><table class="titleTable" cellpadding="0" cellspacing="0" id="titleTable" style="display:none"><tr><td class="titleTableTitleION" align="left" nowrap="true">Adobe® ColdFusion® 9 Language Reference<form class="searchForm" method="get" action="javascript:submitValue('Adobe%C2%AE%C2%A0ColdFusion%C2%AE%C2%A09%C2%A0Language%C2%A0Reference','community.adobe.com/help/search.html','flex_product_adobe_lr','communityhelp-cs3');"><div class="gsa"><input id="checkBoxId" type="checkbox" name="checkbox" value="checkbox">Language Reference only&nbsp;&nbsp;</div><input class="searchinput" id="search-livedocs" name="search_text" value="" title="Search" type="text"> <input type="button" name="action" value="" onClick="submitValue('Adobe&reg;&nbsp;ColdFusion&reg;&nbsp;9&nbsp;Language&nbsp;Reference','community.adobe.com/help/search.html','flex_product_adobe_lr','communityhelp-cs3')" class="searchbutton"></form></td><td class="titleTableTopNavION" align="right"><a href="package-summary.html" onclick="loadClassListFrame('all-classes.html')">All Packages</a>&nbsp;|&nbsp;<a href="class-summary.html" onclick="loadClassListFrame('all-classes.html')">All Classes</a>&nbsp;|&nbsp;<a href="language-elements.html">Language Elements</a>&nbsp;| <a href="all-index-Symbols.html" onclick="loadClassListFrame('index-list.html')">Index</a>&nbsp;|&nbsp;<a href="appendixes.html">Appendixes</a>&nbsp;|&nbsp;<a href="conventions.html">Conventions</a>&nbsp;|&nbsp;<a id="framesLink1" href="index.html?localDatabaseSQLErrorDetails.html&amp;all-classes.html">Frames</a><a id="noFramesLink1" style="display:none" href="" onclick="parent.location=document.location"> No Frames </a></td><td class="titleTableLogo" align="right" rowspan="3"><img src="images/logoION.jpg" class="logoImageION" alt=" Adobe Logo " title=" Adobe Logo "></td></tr><tr class="titleTableRow2"><td class="titleTableSubTitle" id="subTitle" align="left"> SQL error detail messages, ids, and arguments </td><td class="titleTableSubNavION" id="subNav" align="right"></td></tr><tr class="titleTableRow3"><td colspan="3">&nbsp;</td></tr></table><script language="javascript" type="text/javascript" xml:space="preserve">                
                                            
        				
        <!--				
          				    				
          					
          if (!isEclipse() || window.name != ECLIPSE_FRAME_NAME) {titleBar_setSubTitle(" SQL error detail messages, ids, and arguments "); titleBar_setSubNav(false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false);}				    
          				        				    
          				
        -->                
                                            
        			
      </script><div class="MainContent">
      
		<div style="height:15px;" width="100%"></div>
<p>The SQLError class represents various errors that can occur while working with an Adobe AIR local SQL database. For any given exception, the SQLError instance has a <code>details</code> property containing an English error message. In addition, each error message has an associated unique identifier that is available in the SQLError object's <code>detailID</code> property. Using the <code>detailID</code> property, an application can identify the specific <code>details</code> error message. The application can provide alternate text for the end user in the language of his or her locale. The argument values in the <code>detailArguments</code> array can be substituted in the appropriate position in the error message string. This is useful for applications that wish to display the <code>details</code> property error message for an error directly to end users in a specific locale.</p>
<p>The following table contains a list of the <code>detailID</code> values and the associated English error message text. Placeholder text in the messages indicates where <code>detailArguments</code> values are substituted in by the runtime. This list can be used as a source for localizing the error messages that can occur in SQL database operations.</p>
<table class="innertable">
  <thead>
    <tr>
      <th>SQLError <code>detailID</code></th>
	  <th>English error detail message and parameters</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>1001</td>
      <td>Connection closed.</td>
    </tr>
    <tr>
      <td>1002</td>
      <td>Database must be open to perform this operation.</td>
    </tr>
    <tr>
      <td>1003</td>
      <td>%s [,|and %s] parameter name(s) found in parameters property but not in the SQL specified.</td>
    </tr>
	<tr>
	  <td>1004</td>
	  <td>Mismatch in parameter count. Found %d in SQL specified and %d value(s) set in parameters property.<br />Expecting values for %s [,|and %s].</td>
	</tr>
    <tr>
      <td>1005</td>
      <td>Auto compact could not be turned on.</td>
    </tr>
    <tr>
      <td>1006</td>
      <td>The pageSize value could not be set.</td>
    </tr>
    <tr>
      <td>1007</td>
      <td>The schema object with name '%s' of type '%s' in database '%s' was not found.</td>
    </tr>
    <tr>
      <td>1008</td>
      <td>The schema object with name '%s' in database '%s' was not found.</td>
    </tr>
    <tr>
      <td>1009</td>
      <td>No schema objects with type '%s' in database '%s' were found.</td>
    </tr>
	<tr>
	  <td>1010</td>
	  <td>No schema objects in database '%s' were found.</td>
	</tr>
    <tr>
      <td>2001</td>
      <td>parser stack overflow</td>
    </tr>
    <tr>
      <td>2002</td>
      <td>too many arguments on function '%s'</td>
    </tr>
    <tr>
      <td>2003</td>
      <td>near '%s': syntax error</td>
    </tr>
    <tr>
      <td>2004</td>
      <td>there is already another table or index with this name: '%s'</td>
    </tr>
    <tr>
      <td>2005</td>
      <td>PRAGMA is not allowed in SQL.</td>
    </tr>
    <tr>
      <td>2006</td>
      <td>not a writable directory</td>
    </tr>
    <tr>
      <td>2007</td>
      <td>unknown or unsupported join type: '%s %s %s'</td>
    </tr>
    <tr>
      <td>2008</td>
      <td>RIGHT and FULL OUTER JOINs are not currently supported</td>
    </tr>
    <tr>
      <td>2009</td>
      <td>a NATURAL join may not have an ON or USING clause</td>
    </tr>
    <tr>
      <td>2010</td>
      <td>cannot have both ON and USING clauses in the same join</td>
    </tr>
    <tr>
      <td>2011</td>
      <td>cannot join using column '%s' - column not present in both tables</td>
    </tr>
    <tr>
      <td>2012</td>
      <td>only a single result allowed for a SELECT that is part of an expression</td>
    </tr>
    <tr>
      <td>2013</td>
      <td>no such table: '[%s.]%s'</td>
    </tr>
    <tr>
      <td>2014</td>
      <td>no tables specified</td>
    </tr>
    <tr>
      <td>2015</td>
      <td>too many columns in result set|too many columns on '%s'</td>
    </tr>
    <tr>
      <td>2016</td>
      <td>%s ORDER|GROUP BY term out of range - should be between 1 and %d</td>
    </tr>
    <tr>
      <td>2017</td>
      <td>too many terms in ORDER BY clause</td>
    </tr>
    <tr>
      <td>2018</td>
      <td>%s ORDER BY term out of range - should be between 1 and %d</td>
    </tr>
    <tr>
      <td>2019</td>
      <td>%r ORDER BY term does not match any column in the result set</td>
    </tr>
    <tr>
      <td>2020</td>
      <td>ORDER BY clause should come after '%s' not before</td>
    </tr>
    <tr>
      <td>2021</td>
      <td>LIMIT clause should come after '%s' not before</td>
    </tr>
    <tr>
      <td>2022</td>
      <td>SELECTs to the left and right of '%s' do not have the same number of result columns</td>
    </tr>
    <tr>
      <td>2023</td>
      <td>a GROUP BY clause is required before HAVING</td>
    </tr>
    <tr>
      <td>2024</td>
      <td>aggregate functions are not allowed in the GROUP BY clause</td>
    </tr>
    <tr>
      <td>2025</td>
      <td>DISTINCT in aggregate must be followed by an expression</td>
    </tr>
    <tr>
      <td>2026</td>
      <td>too many terms in compound SELECT</td>
    </tr>
    <tr>
      <td>2027</td>
      <td>too many terms in ORDER|GROUP BY clause</td>
    </tr>
    <tr>
      <td>2028</td>
      <td>temporary trigger may not have