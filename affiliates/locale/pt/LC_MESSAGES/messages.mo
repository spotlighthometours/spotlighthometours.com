<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--#config errmsg="" --><!-- saved from url=(0022)http://help.adobe.com/ -->
<html lang="en-us">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="lang" content="en-us" />
    <meta name="area" content="livedocs" />
    <title>Adobe&#160;ColdFusion * IsImageFile</title>
    <link rel="shortcut icon" href="images/ColdFusion.ico" />
    <meta name="book" content="ColdFusion&#160;9&#160;CFML&#160;Reference" />
    <meta name="product" content="ColdFusion" />
    <meta name="keywords" content="" />
    
    <script type="text/javascript" language="Javascript" charset="UTF-8"><!--[CDATA[
        var currentTreeNode = "WSc3ff6d0ea77859461172e0811cbec22c24-7978.html";
// ]]--></script>
<script src="terms.js" type="text/javascript" language="Javascript" charset="UTF-8">...</script> <script src="help.js" type="text/javascript" language="Javascript" charset="UTF-8">...</script>  <script src="utilities.js" type="text/javascript" language="Javascript" charset="UTF-8">...</script> <script src="event.js" type="text/javascript" language="Javascript" charset="UTF-8">...</script> <script src="treeview.js" type="text/javascript" language="Javascript" charset="UTF-8">...</script> <script src="toc.js" language="Javascript" type="text/javascript" charset="UTF-8">..</script> 
<script type="text/javascript">
<!--[CDATA[
var headId = document.getElementsByTagName("head")[0];         
if (use_ie_6_behavior) {
  var linkId = document.createElement("link");
  linkId.href = "content-ie6.css";
  linkId.rel = "stylesheet";
  linkId.type = "text/css";
  headId.appendChild(linkId);
}
function initRoboHelpDOM() {
  if (use_chm_behavior) {
    hideElement("search");
    hideElement("col1");
  }
  if (use_robohelp_behavior) {
    hideElement("search");
    hideElement("productmenu");
    hideElement("notyourversion");
  }
}
var dirname = location.pathname.match( /.*\// );    
function setSearchUserPref(){
  if ( document.cookie.indexOf( "ah_searchpref" ) > -1 ) {
    if ( document.cookie.indexOf( dirname ) > 0 ) {
      document.search.gsa.checked = true ;
    }else{
      document.search.gsa.checked = false ;            
    }
  }
}
YAHOO.util.Event.onDOMReady(initRoboHelpDOM);
YAHOO.util.Event.onDOMReady(setSearchUserPref);
// ]]-->
</script>
<!--#include virtual="/ssi/globalheader.ssi" -->
<!--#include virtual="/en_US/ssi/localheader.ssi" -->
<!--#include virtual="header.ssi" -->
    <link rel="stylesheet" type="text/css" href="tree.css" />
    <link rel="stylesheet" type="text/css" href="content-fonts.css" />
    <link rel="stylesheet" type="text/css" href="content-hyperlinks.css" />
    <link rel="stylesheet" type="text/css" href="content.css" />
    <link rel="stylesheet" type="text/css" href="localeSpecific.css" />
  </head>
  <body id="content_body" onload="window.focus();">
    <a name="top" shape="rect"><!--LeaveCommentHere--></a>
    
    <div id="mnemonic">
      <div class="ColdFusion"><div class="banner">Adobe&#160;ColdFusion</div></div>
    </div>
    
    <div id="searchbar">
      <div id="pdf"><img src="images/PDF.gif" width="16" height="16" hspace="10" />&#160;
           <a title="View Help PDF" href="http://help.adobe.com/en_US/ColdFusion/9.0/CFMLRef/coldfusion_9_cfmlref.pdf">View Help PDF (adobe.com)</a></div>
    </div>

<!-- BEGIN SEARCH CONTENT -->
<form id="search" name="search" action="search.html" target="_self">
<script type="text/javascript">
<!--[CDATA[
if (typeof(terms_AHV_SEARCH_CONSTRAINT) != "undefined" && 
    terms_AHV_SEARCH_CONSTRAINT.length > 0 && 
    document.location.href.indexOf(".adobe.com") > 0){
    if ( typeof(terms_SEARCH_THIS_HELP_ONLY) != "undefined" && terms_SEARCH_THIS_HELP_ONLY == "ON" ) {
        document.write('<div id="searchscope"> \
            <input onchange="setAHSearchPref();" \
            class="gsa" \
            name="gsa" \
            id="gsa" \
            type="checkbox" \
            checked="checked" \
            value="1" ><\/input>'); 
            
    // Leave the "Search this help system only" checkbox unckecked
    }else{    
        document.write('<div id="searchscope"> \
            <input onchange="setAHSearchPref();" \
            class="gsa" \
            name="gsa" \
            id="gsa" \
            type="checkbox" \
            value="1" ><\/input>'); 
    }
    document.write('<span class="gsalabel">' + terms_AHV_SEARCH_CONSTRAINT + '<\/span><\/div>'); 
}
document.write('<input class="searchinput" \
    name="q" \
    id="q" \
    type="text" \
    maxlength="256" \
    value="' + terms_AHV_SEARCH_BUTTON + '" \
    onclick="clearSearch()"><\/input><input \
    type="button" \
    name="searchbutton" \
    class="searchbutton" \
    onclick="submit()" />'); 
 
 
/* 
 * Start Functions 
 */
function clearSearch(){
    if (document.search.q.value == terms_AHV_SEARCH_BUTTON){document.search.q.value = ""}; 
}

// set search preferences
function setAHSearchPref(){
    if (document.search.gsa.checked == 1){
        setAHSearchCookie( dirname );
    }else{
        setAHSearchCookie( "community" );
    }
}

// Set search preferences cookie
function setAHSearchCookie( p ){
    // set cookie ah_searchpref with a value of the document path
    var expire=new Date();
    expire.setDate(expire.getDate()+365); // Cookie expires after 1 year (365 days) 
    document.cookie="ah_searchpref=" +p+ "; expires=" +expire.toGMTString()+ ";";
}

// ]]-->
</script> 
 </form>
<!-- END SEARCH CONTENT -->    



<!-- BEGIN PAGE CONTENT WRAPPER -->
    <div id="page_content_wrapper">
<!-- BEGIN PAGE WRAPPER -->
<table id="page_content_table">
<tr>
<td id="col1">
<!--#include virtual="navheader.ssi" -->
<!-- BEGIN GROUP TOC WRAPPER -->
      <div id="booklist"><p>ColdFusion 9.0 Resources</p><ul xmlns:fn="http://www.w3.org/2005/xpath-functions" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:xs="http://www.w3.org/2001/XMLSchema"><li><p><a href="../Admin/index.html" target="_self">Configuring
and Administering Adobe ColdFusion 9</a></p>
</li><li><p><a href="../CFMLRef/index.html" target="_self">Adobe ColdFusion
9 CFML Reference</a></p>
</li><li><p><a href="../Developing/index.html" target="_self">Developing
Adobe ColdFusion 9 Applications</a></p>
</li><li><p><a href="../Installing/index.html" target="_self">Installing
Adobe ColdFusion 9</a></p>
</li></ul></div>
<!-- END GROUP TOC WRAPPER -->
<!-- BEGIN NAV WRAPPER -->
      <div id="group_toc">
<script type="text/javascript">
<!--[CDATA[
if ( typeof(terms_BOOK_SHORT) != 'undefined' ) {
  document.write("<p>" + terms_BOOK_SHORT + "<\/p>");
}
// ]]-->


</script> 
      </div>
      <div id="nav_content">
      </div>
<!-- END NAV WRAPPER -->
<!--#include virtual="navfooter.ssi" -->
</td>
<td id="col2">
<!-- BEGIN CONTENT WRAPPER -->
<!-- BEGIN BREADCRUMBS -->
      
      <div id="breadcrumb">
        
<ul class="navigation"><li class="prev"><a accesskey="p" class="prev" href="WSc3ff6d0ea77859461172e0811cbec22c24-797b.html" title="IsImage"><img src="images/blank.gif" alt="Previous" width="17" height="17" /></a></li><li class="next"><a accesskey="n" class="next" href="WSc3ff6d0ea77859461172e0811cbec22c24-74f8.html" title="IsInstanceOf"><img src="images/blank.gif" alt="Next" width="17" height="17" /></a></li></ul><div class="hierarchy" id="hierarchy"><a href="WSc3ff6d0ea77859461172e0811cbec22c24-7ff8.html">ColdFusion Functions</a> /
     <a href="WSc3ff6d0ea77859461172e0811cbec1de14-7fdb.html"><b>Functions in-k</b></a> 
     </div>

      </div>
<!-- END BREADCRUMBS -->
      <div id="content_wrapper">
<!-- BEGIN PAGE TITLE -->
        <h1>
          IsImageFile
        </h1>
<!-- END PAGE TITLE -->
<!-- BEGIN IONCOMMENTCOUNT -->
        <div id="ionCount">
        </div>
<!-- END IONCOMMENTCOUNT -->
<!--#include virtual="contentheader.ssi" -->
        <div><div class="section" id="WSc3ff6d0ea77859461172e0811cbec22c24-7978__WSc3ff6d0ea77859461172e0811cbec1de14-7fd5"><a name="WSc3ff6d0ea77859461172e0811cbec22c24-7978__WSc3ff6d0ea77859461172e0811cbec1de14-7fd5"><!-- --></a><h4 class="sectiontitle">Description</h4><p>Verifies
whether an image file is valid.</p>
</div>
<div class="section" id="WSc3ff6d0ea77859461172e0811cbec22c24-7978__WSc3ff6d0ea77859461172e0811cbec1de14-7fd4"><a name="WSc3ff6d0ea77859461172e0811cbec22c24-7978__WSc3ff6d0ea77859461172e0811cbec1de14-7fd4"><!-- --></a><h4 class="sectiontitle">Returns</h4><p>True,
if the value is a valid image file; False, otherwise.</p>
</div>
<div class="section" id="WSc3ff6d0ea77859461172e0811cbec22c24-7978__WSc3ff6d0ea77859461172e0811cbec1de14-7fbf"><a name="WSc3ff6d0ea77859461172e0811cbec22c24-7978__WSc3ff6d0ea77859461172e0811cbec1de14-7fbf"><!-- --></a><h4 class="sectiontitle">Category</h4><p><a href="WSc3ff6d0ea77859461172e0811cbec22c24-66e4.html">Image functions</a></p>
</div>
<div class="section" id="WSc3ff6d0ea77859461172e0811cbec22c24-7978__WSc3ff6d0ea77859461172e0811cbec1de14-7fbe"><a name="WSc3ff6d0ea77859461172e0811cbec22c24-7978__WSc3ff6d0ea77859461172e0811cbec1de14-7fbe"><!-- --></a><h4 class="sectiontitle">Function syntax</h4><pre>IsImageFile("<i xmlns:fn="http://www.w3.org/2005/xpath-functions" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:xs="http://www.w3.org/2001/XMLSchema">path</i>")</pre>
</div>
<div class="section" id="WSc3ff6d0ea77859461172e0811cbec22c24-7978__WSc3ff6d0ea77859461172e0811cbec1de14-7fbd"><a name="WSc3ff6d0ea77859461172e0811cbec22c24-7978__WSc3ff6d0ea77859461172e0811cbec1de14-7fbd"><!-- --></a><h4 class="sectiontitle">See also</h4><p><samp class="codeph"><a href="WSc3ff6d0ea77859461172e0811cbec22c24-7945.html">cfimage</a></samp>, <samp class="codeph"><a href="WSc3ff6d0ea77859461172e0811cbec22c24-7971.html">ImageGetBlob</a></samp>, <samp class="codeph"><a href="WSc3ff6d0ea77859461172e0811cbec22c24-7972.html">ImageInfo</a></samp>, <samp class="codeph"><a href="WSc3ff6d0ea77859461172e0811cbec22c24-796f.html">ImageNew</a></samp>, <samp class="codeph"><a href="WSc3ff6d0ea77859461172e0811cbec22c24-797b.html">IsImage</a></samp></p>
</div>
<div class="section" id="WSc3ff6d0ea77859461172e0811cbec22c24-7978__WSc3ff6d0ea77859461172e0811cbec1de14-7fbc"><a name="WSc3ff6d0ea77859461172e0811cbec22c24-7978__WSc3ff6d0ea77859461172e0811cbec1de14-7fbc"><!-- --></a><h4 class="sectiontitle">History</h4><p>ColdFusion
8: Added this function.</p>
</div>
<div class="section" id="WSc3ff6d0ea77859461172e0811cbec22c24-7978__WSc3ff6d0ea77859461172e0811cbec1de14-7fbb"><a name="WSc3ff6d0ea77859461172e0811cbec22c24-7978__WSc3ff6d0ea77859461172e0811cbec1de14-7fbb"><!-- --></a><h4 class="sectiontitle">Parameters</h4>
<div class="tablenoborder"><table border="1" cellpadding="4" cellspacing="0"><thead align="left"><tr><th valign="top" width="NaN%" id="d16e140935"><p>Parameter</p>
</th>
<th valign="top" width="NaN%" id="d16e140938"><p>Description</p>
</th>
</tr>
</thead>
<tbody><tr><td valign="top" width="NaN%" headers="d16e140935 "><p><samp class="codeph">path</samp></p>
</td>
<td valign="top" width="NaN%" headers="d16e140938 "><p>Required. The pathname of the on-disk or
in-memory file to be checked. The pathname can be absolute or relative to
the CFM page and must be enclosed in quotation marks.</p>
</td>
</tr>
</tbody>
</table>
</div>
</div>
<div class="section" id="WSc3ff6d0ea77859461172e0811cbec22c24-7978__WSc3ff6d0ea77859461172e0811cbec1de14-7fba"><a name="WSc3ff6d0ea77859461172e0811cbec22c24-7978__WSc3ff6d0ea77859461172e0811cbec1de14-7fba"><!-- --></a><h4 class="sectiontitle">Usage</h4><p>Use this
function to determine whether an image file is valid. This function returns
a False value if the image file format is not supported by the server
where ColdFusion is deployed, or if the pathname to the image file
is null or invalid.</p>
<p>For a list of standard image file formats
supported by ColdFusion, see the supported image file formats provided
in <a href="WSc3ff6d0ea77859461172e0811cbec22c24-7945.html">cfimage</a>.
To determine which image file formats are supported on the server
where ColdFusion is deployed, use the <a href="WSc3ff6d0ea77859461172e0811cbec22c24-797a.html">GetReadableImageFormats</a> and <a href="WSc3ff6d0ea77859461172e0811cbec22c24-7979.html">GetWriteableImageFormats</a>.</p>
</div>
<div class="section" id="WSc3ff6d0ea77859461172e0811cbec22c24-7978__WSc3ff6d0ea77859461172e0811cbec1de14-7fb9"><a name="WSc3ff6d0ea77859461172e0811cbec22c24-7978__WSc3ff6d0ea77859461172e0811cbec1de14-7fb9"><!-- --></a><h4 class="sectiontitle">Example</h4><pre>&lt;!--- Use the IsImageFile function to determine whether an image retrieved from the artwork table in the cfartgallery database is a valid image file. ---&gt; 
&lt;cfif IsImageFile("images/#artwork.largeImage#")&gt; 
    &lt;cfset myImage=ImageNew("images/#artwork.largeImage#")&gt; 
    &lt;cfset ImageResize(myImage,50,"")&gt; 
    &lt;cfimage action="writeToBrowser" source="#myImage#"&gt; 
&lt;cfelse&gt; 
    &lt;p&gt;I'm sorry, there is no image associated with the title you selected. Please click the Back button and try again.&lt;/p&gt; 
    &lt;/p&gt; 
&lt;/cfif&gt;</pre>
</div>
</div>


<!-- BEGIN USER PREFERENCES -->
        <d