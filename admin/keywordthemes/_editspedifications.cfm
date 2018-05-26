<!--- :: set default field values for a user add :: --->
<CFPARAM name="editMode" default="false">


<CFQUERY name="qTableDatachk" datasource="#request.dsn#">
	SELECT * FROM lonewolf_keyword_theme_specs  WHERE themeid = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.themeid#" maxlength="6">
    order by themeSpecId asc
</CFQUERY>

<cfif qTableDataChk.RecordCount eq 0>
    
    <CFQUERY name="qTableDataDefault" datasource="#request.dsn#">
        SELECT * FROM lonewolf_keyword_theme_specs  WHERE themeid = '1'   order by themeSpecId asc
    </CFQUERY>
    
     <cfloop QUERY="qTableDataDefault">
     <CFQUERY name="qTableDataDefaultinsert" datasource="#request.dsn#">
                INSERT INTO `lonewolf_keyword_theme_specs` (
                                `themeSpecId`,
                                `themeSpecType`,
                                `themeID`,
                                `themeSpecName`,
                                `boxshadow`,
                                `borderTop`,
                                `background`,
                                `background1`,
                                `background2`,
                                `border`,
                                `color`,
                                `containerbg1`,
                                `containerbg2`
                            ) 
                            VALUES ('0',
                                '#themeSpecType#',
                                '#url.themeid#',
                                '#themeSpecName#',
                                '#boxshadow#',
                                '#borderTop#',
                                '#background#',
                                '#background1#',
                                '#background2#',
                                '#border#',
                                '#color#',
                                '#containerbg1#',
                                '#containerbg2#'
                            )
       </CFQUERY>
       </cfloop>

</cfif>


<CFQUERY name="qTableData" datasource="#request.dsn#">
	SELECT * FROM lonewolf_keyword_theme_specs  WHERE themeid = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.themeid#" maxlength="6">
    order by themeSpecId asc
</CFQUERY>

<CFOUTPUT>
<form onsubmit="return confirmFeatured(this);" enctype="multipart/form-data" action="index.cfm?action=updateThemeSpecs" method="post">
<table class="form">
<cfloop QUERY="qTableData">
<tr>
	<td colspan="2">
    <h2>#themeSpecName#</h2>
    <table class="form">
    
<tr>
	<th>Spec Type</th>
	<td><input type="text" name="#themeSpecId#_themeSpecType" value="#themeSpecType#" readonly="readonly" /></td>
</tr>
<tr>
	<th>spec Name</th>
	<td><input type="text" name="#themeSpecId#_themeSpecName" value="#themeSpecName#"  readonly="readonly"  /></td>
</tr>
<tr>
	<th>boxshadow</th>
	<td><input type="text" name="#themeSpecId#_boxshadow" value="#boxshadow#"  /><div style="width:15px;height:15px;background:#boxshadow#"></div></td>
</tr>
<tr>
	<th>borderTop</th>
	<td><input type="text" name="#themeSpecId#_borderTop" value="#borderTop#"  /><div style="width:15px;height:15px;background:#borderTop#"></div></td>
</tr>
<tr>
	<th>background</th>
	<td><input type="text" name="#themeSpecId#_background" value="#background#"  /><div style="width:15px;height:15px;background:#background#"></div></td>
</tr>
<tr>
	<th>background1</th>
	<td><input type="text" name="#themeSpecId#_background1" value="#background1#"  /><div style="width:15px;height:15px;background:#background1#"></div></td>
</tr>
<tr>
	<th>background2</th>
	<td><input type="text" name="#themeSpecId#_background2" value="#background2#"  /><div style="width:15px;height:15px;background:#background2#"></div></td>
</tr>
<tr>
	<th>border</th>
	<td><input type="text" name="#themeSpecId#_border" value="#border#"  /><div style="width:15px;height:15px;background:#border#"></div></td>
</tr>
<tr>
	<th>color</th>
	<td><input type="text" name="#themeSpecId#_color" value="#color#"  /><div style="width:15px;height:15px;background:#color#"></div></td>
</tr>
<tr>
	<th>container background1</th>
	<td><input type="text" name="#themeSpecId#_containerbg1" value="#containerbg1#"  /><div style="width:15px;height:15px;background:#containerbg1#"></div></td>
</tr>
<tr>
	<th>container background2</th>
	<td><input type="text" name="#themeSpecId#_containerbg2" value="#containerbg2#"  /><div style="width:15px;height:15px;background:#containerbg2#"></div></td>
</tr>
    </table>
    
    </td>
   
</tr>    

</cfloop>

<tr>
	<th><input type="hidden" name="themeid" value="#qTableData.themeid#"></th>
	<td><input type="submit" value="Save Changes" /></td>
</tr>
</table>
</form>
</CFOUTPUT>