<CFSILENT>
	<CFPARAM name="url.page" default="1" />
	<CFPARAM name="url.pagination" default="viewResults" />
	<CFPARAM name="url.itemsperpage" default="50" />
    <CFPARAM name="url.totalpages" default="0" /> 
    <CFPARAM name="url.orderby" default="dateCreated">   
    
    
    <CFPARAM name="url.name" default="" />
<CFPARAM name="url.BrokerageID" default="" />


</CFSILENT>
<p align="right">
<CFOUTPUT>
	#url.totalpages#
	User(s) Found, Page #url.page# of #(ceiling(url.totalpages / url.itemsperpage))# [
</CFOUTPUT>
<CFIF url.page gt 1>
    <CFSET nPage = url.page - 1>
    <CFOUTPUT>
		<a href="?orderby=#url.orderby#&page=#nPage#&name=#url.name#&brokerageid=#brokerageID#&username=#url.username#&userid=#url.userid#">Previous Page</a>
	</CFOUTPUT>
<CFELSE>
    Previous Page
</CFIF>
/
<CFIF url.page  lt ceiling(url.totalpages / url.itemsperpage)>
    <CFSET nPage = url.page + 1>
    <CFOUTPUT>
		<a href="?orderby=#url.orderby#&page=#nPage#&name=#url.name#&brokerageid=#brokerageID#&username=#url.username#&userid=#url.userid#">Next Page</a>
	</CFOUTPUT>
<CFELSE>
    Next Page
</CFIF>
]
</p>