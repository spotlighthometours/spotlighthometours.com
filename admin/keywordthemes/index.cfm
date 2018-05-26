<CFPARAM name="url.action" default="">
<CFPARAM name="url.pg" default="list">
<CFPARAM name="rel_filepath" default="../../../img/friends">
<CFPARAM name="filepath" default="#expandPath(rel_filepath)#">
<CFPARAM name="form.featured" default="0">



<CFSWITCH expression="#url.action#">
	<CFCASE value="insertTheme">
		<!--- :: perform main data insertion :: --->
		<CFQUERY datasource="#request.dsn#">
			INSERT INTO lonewolf_keyword_theme_types
            (themeId,
  			themeName,
  			active)
             VALUES ('0',
             <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.themename#" maxlength="45">,              
               <cfif isDefined('form.themeactive')>
               '1'
                <cfelse>
                '0'
                </cfif>)
		</CFQUERY>
		<CFLOCATION url="index.cfm?pg=list" addtoken="no">
	</CFCASE>

	<CFCASE value="updateTheme">		
		<!--- :: perform main data update :: --->
		<CFQUERY datasource="#request.dsn#">
			UPDATE lonewolf_keyword_theme_types SET			
				themeName=<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.themename#" maxlength="45">,
                <cfif isDefined('form.themeactive')>
                active='1'
                <cfelse>
                active ='0'
                </cfif>
			WHERE themeid = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.themeid#">
		</CFQUERY>
		<CFLOCATION url="index.cfm?pg=list" addtoken="no">
	</CFCASE>
    
	<CFCASE value="updateThemeSpecs">		
		
		 <CFQUERY name="qTableDataDefault" datasource="#request.dsn#">
            SELECT themespecid FROM lonewolf_keyword_theme_specs  WHERE themeid = '#form.themeid#'   order by themeSpecId asc
        </CFQUERY>

		<cfloop QUERY="qTableDataDefault">        
            <CFQUERY datasource="#request.dsn#">
                UPDATE lonewolf_keyword_theme_specs SET			
                
                    themeSpecType='#Form[themespecid&"_themeSpecType"]#',
                    themeSpecName='#Form[themespecid&"_themeSpecName"]#',
                    boxshadow='#Form[themespecid&"_boxshadow"]#',
                    borderTop='#Form[themespecid&"_borderTop"]#',
                    background='#Form[themespecid&"_background"]#',
                    background1='#Form[themespecid&"_background1"]#',
                    background2='#Form[themespecid&"_background2"]#',
                    border='#Form[themespecid&"_border"]#',
                    color='#Form[themespecid&"_color"]#',
                    containerbg1='#Form[themespecid&"_containerbg1"]#',
                    containerbg2='#Form[themespecid&"_containerbg2"]#'
                    
                WHERE themeid = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.themeid#"> and themeSpecID='#themespecid#'
            </CFQUERY>
        </cfloop>    
            
		<CFLOCATION url="index.cfm?pg=editspedifications&themeid=#form.themeid#" addtoken="no">
	</CFCASE>
    
    
	
	<CFCASE value="delete">
		<!--- :: remove entry from database, more deletes will need to happen here :: --->
		<CFQUERY datasource="#request.dsn#">
			DELETE FROM themes WHERE themeid = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.themeid#">
		</CFQUERY>
		<CFLOCATION url="index.cfm?pg=list" addtoken="no">
	</CFCASE>	
</CFSWITCH>

<!--- :: Display the page :: --->
<HTML>
<HEAD>
<TITLE>Themes</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK HREF="../includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
</HEAD>
<BODY>
<CFINCLUDE template="_subnav.cfm">
<CFINCLUDE template="_#url.pg#.cfm">
</BODY>
</HTML>