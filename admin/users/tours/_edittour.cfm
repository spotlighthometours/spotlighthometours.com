<CFSET editMode = iif(isDefined("url.tour"), true, false)>
<CFIF editMode>
	<CFQUERY name="qTours" datasource="#request.db.dsn#">
		select t.*,(select concat(firstName,' ', lastName) as me from users where userID = t.couserID limit 1) as coagent from tours t where tourID = #url.tour#
	</CFQUERY>
	<CFSET url.user = qTours.userID>
    
    <CFQUERY name="qMLS" datasource="#request.db.dsn#">
        select * from tour_to_mls WHERE tourID = #url.tour#
    </CFQUERY>
</CFIF>
<CFQUERY name="qUsers" datasource="#request.db.dsn#">
	select firstName, lastName, username from users where userID = #url.user#
</CFQUERY>
<CFQUERY name="qTourTypes" datasource="#request.db.dsn#">
	select tourTypeID, tourTypeName from tourtypes order by tourTypeName
</CFQUERY>
<CFQUERY name="qStates" datasource="#request.db.dsn#">
	select stateFullName, stateAbbrName from states order by stateFullName
</CFQUERY>
<CFQUERY name="qMLSProv" datasource="#request.db.dsn#">
	select * from mls_providers order by name ASC
</CFQUERY>
<HTML>
<HEAD>
<TITLE>Tours</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<LINK HREF="/admin/includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
<SCRIPT TYPE="text/javascript" SRC="/javascripts/jquery/jquery.js"></SCRIPT>
	<SCRIPT TYPE="text/javascript" SRC="/javascripts/jquery/jquery.ui.js"></SCRIPT>
	<SCRIPT TYPE="text/javascript" SRC="/javascripts/jquery/jquery.asmselect.js"></SCRIPT>
 
    <SCRIPT SRC="/javascripts/multipleselect_2.js" TYPE="text/javascript" CHARSET="utf-8"></SCRIPT>
    <SCRIPT TYPE="text/javascript" SRC="/javascripts/jquery/jquery.markitup.js"></SCRIPT>
    <SCRIPT TYPE="text/javascript" SRC="/javascripts/html-editor.js"></SCRIPT>

	<SCRIPT TYPE="text/javascript">
		$(document).ready(function() {
			
			$.maillist('#coagent', '#preadded', '#mail-auto',{url:'fetched.cfm',cache:1}, 10, {userfilter:1,casesensetive:0});
		}); 
		function change(){
			$.maillist('#coagent', '#preadded', '#mail-auto',{url:'fetched.cfm',cache:1}, 10, {userfilter:1,casesensetive:0});
		}
    </SCRIPT>

	<LINK REL="stylesheet" TYPE="text/css" HREF="/stylesheets/jquery.asmselect.css" />
    <LINK REL="stylesheet" TYPE="text/css" HREF="/stylesheets/multipleSelectagent.css" />
    <LINK REL="stylesheet" TYPE="text/css" HREF="/stylesheets/markitup-style.css" />
	
</HEAD>

<BODY>
<CFOUTPUT>
<FORM ACTION="#cgi.script_name#?action=<cfif editMode>updateTour<cfelse>insertTour</cfif>" METHOD="post">
<TABLE WIDTH="500" BORDER="0" CELLPADDING="4" CELLSPACING="2">
      <TR>
        <TD CLASS="rowHead">Tour Title</TD>
        <TD CLASS="rowData"><INPUT NAME="title" TYPE="text" SIZE="32" MAXLENGTH="50"<cfif editMode> value="#qTours.title#"</cfif>></TD>
      </TR>
		<TR>
        <TD CLASS="rowHead">Tour Type</TD>
        <TD CLASS="rowData">
		  <SELECT NAME="tourTypeID">
		  <CFLOOP query="qTourTypes">
		  		<OPTION VALUE="#tourTypeID#"<cfif editMode and tourTypeID eq qTours.tourTypeID> selected</cfif>>#tourTypeName#</OPTION>
			</CFLOOP>
		  </SELECT>
		  </TD>
        <TR>
        <TD CLASS="rowHead">Tour Window</TD>
        <TD CLASS="rowData">
		  <SELECT NAME="TourWindowType">
          	<option value="Both" <cfif editMode and qTours.TourWindowType eq "Both"> selected</cfif> />Both</option>
		  	<option value="Old" <cfif editMode and qTours.TourWindowType eq "Old"> selected</cfif> />Old</option>
            <option value="New" <cfif editMode and qTours.TourWindowType eq "New"> selected</cfif> />New</option>
            <option value="higginsgroup" <cfif editMode and qTours.TourWindowType eq "higginsgroup"> selected</cfif> />higginsgroup</option>
            <option value="berkshire" <cfif editMode and qTours.TourWindowType eq "berkshire"> selected</cfif> />berkshire</option>
		  </SELECT>
		  </TD>
			</TR>
			<TR>
        <TD CLASS="rowHead">Featured</TD>
        <TD CLASS="rowData"><INPUT NAME="featured" TYPE="checkbox" VALUE="1"<cfif editmode and qTours.featured> checked="checked"</cfif>></TD>
      </TR>
	 <TR>
        <TD CLASS="rowHead">Sold</TD>
        <TD CLASS="rowData"><INPUT NAME="sold" TYPE="checkbox" VALUE="1"<cfif editmode and qTours.sold> checked="checked"</cfif>></TD>
      </TR>
	 <TR>
        <TD CLASS="rowHead">Inactive/Archived</TD>
        <TD CLASS="rowData"><INPUT NAME="suspended" TYPE="checkbox" <cfif editmode and (qTours.suspended neq "")> checked="checked"</cfif>></TD>
      </TR>
      </tr>
      <TR>
        <TD CLASS="rowHead">Hide Contact on tour?</TD>
        <TD CLASS="rowData"><INPUT NAME="hidecontact" TYPE="checkbox" VALUE="0"<cfif editmode and qTours.hidecontact eq 1> checked="checked"</cfif>></TD>
      </TR>
	  <TR>
        <TD CLASS="rowHead">Use secondary broker img?</TD>
        <TD CLASS="rowData"><INPUT NAME="use_secondary_bkr_img" TYPE="checkbox" VALUE="0"<cfif editmode and qTours.use_secondary_bkr_img eq 1> checked="checked"</cfif>></TD>
      </TR>
      </tr>
      <TR>
        <TD CLASS="rowHead">User</TD>
        <TD CLASS="rowData"><INPUT TYPE="hidden" NAME="userID" VALUE="#url.user#">#qUsers.firstName# #qUsers.lastName#</TD>
      </TR>
		<TR>
			<TD CLASS="sectionHead" COLSPAN="2">Property Information</TD>
		</TR>
      <TR>
        <TD CLASS="rowHead">Address</TD>
        <TD CLASS="rowData"><INPUT NAME="address" TYPE="text" SIZE="36" MAXLENGTH="200"<cfif editMode> value="#qTours.address#"</cfif>><LABEL>Unit Number:<INPUT NAME="unitNumber" TYPE="text" SIZE="10" MAXLENGTH="20"<cfif editMode> value="#qTours.unitNumber#"</cfif>><LABEL>  Hide on Tour?<INPUT NAME="hideaddress" TYPE="checkbox" VALUE="0"<cfif editmode and qTours.hideaddress eq 1> checked="checked"</cfif>></LABEL></TD>
      </TR>

		<TR>
        <TD CLASS="rowHead">City</TD>
        <TD CLASS="rowData"><INPUT NAME="city" TYPE="text" SIZE="36" MAXLENGTH="50"<cfif editMode> value="#qTours.city#"</cfif>></TD>
      </TR>
		<TR>
        <TD CLASS="rowHead">State</TD>
        <TD CLASS="rowData">
		   <SELECT NAME="state">
            <CFIF not editMode>
              <OPTION VALUE="">Select One...</OPTION>
            </CFIF>
            <CFLOOP query="qStates">
              <OPTION VALUE="#stateAbbrName#"<cfif editMode and stateAbbrName eq qTours.state> selected</cfif>>#stateFullName#</OPTION>
            </CFLOOP>
          </SELECT>
		  </TD>
      </TR>
		<TR>
        <TD CLASS="rowHead">Zip Code</TD>
        <TD CLASS="rowData"><INPUT NAME="zipCode" TYPE="text" SIZE="10" MAXLENGTH="10"<cfif editMode> value="#qTours.zipCode#"</cfif>></TD>
      </TR>
		<TR>

      <TD CLASS="rowHead">List Price</TD>

      <TD CLASS="rowData">
        <INPUT NAME="listPrice" TYPE="text" SIZE="15" MAXLENGTH="20"<cfif editMode> value="#dollarFormat(qTours.listPrice)#"</cfif>><LABEL>Hide on Tour?<INPUT NAME="hideprice" TYPE="checkbox" VALUE="0"<cfif editmode and qTours.hideprice eq 1> checked="checked"</cfif>></LABEL>
      </TD>
      </TR>
      <TR>

      <TD CLASS="rowHead">Max Price</TD>

      <TD CLASS="rowData">
        <INPUT NAME="maxPrice" TYPE="text" SIZE="15" MAXLENGTH="20"<cfif editMode> value="#dollarFormat(qTours.maxPrice)#"</cfif>><LABEL>This is used for price range (multiple units)</LABEL>
      </TD>
      </TR>
		<TR>
        <TD CLASS="rowHead">Total Square Feet</TD>
        <TD CLASS="rowData"><INPUT NAME="sqFootage" TYPE="text" SIZE="10" MAXLENGTH="10"<cfif editMode> value="#qTours.sqFootage#"</cfif>><LABEL>Hide on Tour?<INPUT NAME="hidesqfoot" TYPE="checkbox" VALUE="0"<cfif editmode and qTours.hidesqfoot eq 1> checked="checked"</cfif>></LABEL></TD>
      </TR>
      </TR>
		<TR>
        <TD CLASS="rowHead">Acres</TD>
        <TD CLASS="rowData"><INPUT NAME="acres" TYPE="text" SIZE="10" MAXLENGTH="10"<cfif editMode> value="#qTours.acres#"</cfif>></TD>
      </TR>
		<TR>
        <TD CLASS="rowHead">Bedrooms</TD>
        <TD CLASS="rowData"><INPUT NAME="bedrooms" TYPE="text" SIZE="6" MAXLENGTH="10"<cfif editMode> value="#qTours.bedrooms#"</cfif>><LABEL>Hide on Tour?<INPUT NAME="hidebeds" TYPE="checkbox" VALUE="0"<cfif editmode and qTours.hidebeds eq 1> checked="checked"</cfif>></LABEL></TD>
      </TR>
		<TR>
        <TD CLASS="rowHead">Bathrooms</TD>
        <TD CLASS="rowData"><INPUT NAME="bathrooms" TYPE="text" SIZE="6" MAXLENGTH="10"<cfif editMode> value="#qTours.bathrooms#"</cfif>><LABEL>Hide on Tour?<INPUT NAME="hidebaths" TYPE="checkbox" VALUE="0"<cfif editmode and qTours.hidebaths eq 1> checked="checked"</cfif>></LABEL></TD>
      </TR>
		<TR>
        <TD CLASS="rowHead">MLS ##</TD>
        <TD CLASS="rowData">
        <cfif editmode >
			<CFLOOP query="qMLS">
				<div>
					<INPUT NAME="mls[]" TYPE="text" readonly SIZE="36" value="#mlsID#">&nbsp;&nbsp;
					<select name="mls_provider[]" disabled="disabled">
					<CFLOOP query="qMLSProv">
						<cfset selected = "">
						<cfif id eq qMLS.mlsProvider>
							<cfset selected = "selected='selected'">
						</cfif>
						<option value="#id#" #selected# >#name#</option>
					</CFLOOP>
						<cfset selected = "">
						<cfif qMLS.mlsProvider eq "0">
							<cfset selected = "selected='selected'">
						</cfif>
						<option value="0" #selected#>Other</option>
					</select>
				</div>
			</CFLOOP>
         <cfelse>
         		<div>
					<INPUT NAME="mls[]" TYPE="text" readonly SIZE="36">&nbsp;&nbsp;
					<select name="mls_provider[]" disabled="disabled">
                        <CFLOOP query="qMLSProv">
                            <option value="#id#" >#name#</option>
                        </CFLOOP>
						<option value="0">Other</option>
					</select>
				</div>
         </cfif>   
			<i>Please edit this in the user's account only. [ <a href="../../users/auto-login.php?username=#QUsers.username#" target="_blank">login</a> ]</i>
		</TD>
      </TR>
      <TR>
        <TD CLASS="rowHead">CO-Listing Agent ##</TD>
        <TD CLASS="rowData">
        <INPUT TYPE="text" VALUE="" ID="coagent"  />
             <UL ID="preadded" STYLE="display:none">
             <CFIF editMode><LI REL="#qTours.couserID#">#qTours.coagent#
             </LI>
             </CFIF>
            </UL>
        <DIV ID="mail-auto">
        	<DIV CLASS="default">Type the name of  a agent</DIV> 
            <UL ID="feed" STYLE="z">
            </UL>
        </DIV>
        
        
        </TD>
      </TR>
      
			<TR>

      <TD VALIGN="top" CLASS="rowHead">Excerpt<BR>
        (short description)</TD>
        <TD CLASS="rowData"><TEXTAREA NAME="excerpt" STYLE="width: 300px;" ROWS="5" WRAP="VIRTUAL" ID="excerpt"><CFIF editMode>#qTours.excerpt#</CFIF></TEXTAREA> </TD>
      </TR>

	<TR>
		<TR>

      <TD VALIGN="top" CLASS="rowHead">Description<BR>
        max 1000 characters</TD>
        <TD CLASS="rowData"><TEXTAREA NAME="description" STYLE="width: 300px;" ROWS="5" WRAP="VIRTUAL" ID="description"><CFIF editMode>#qTours.description#</CFIF></TEXTAREA> </TD>
      </TR>
	<TR>
		<TD VALIGN="top" CLASS="rowHead">Features<BR></TD>
        <TD CLASS="rowData"><TEXTAREA NAME="features" STYLE="width: 300px;" ROWS="5" WRAP="VIRTUAL" ID="features"><CFIF editMode>#qTours.features#</CFIF></TEXTAREA> </TD>
	</TR>
	<TR>
		<TD VALIGN="top" CLASS="rowHead">Additional Instructions<BR></TD>
        <TD CLASS="rowData"><TEXTAREA NAME="additionalinstructions" STYLE="width: 300px;" ROWS="5" WRAP="VIRTUAL" ID="additionalinstructions"><CFIF editMode>#qTours.additionalinstructions#</CFIF></TEXTAREA> </TD>
	</TR>

	<TR>
    	<TR>

	 <TD CLASS="sectionHead" COLSPAN="2">Additional Info</TD>
		</TR>
        <TR>
        <TD CLASS="rowHead">3D Tour ID 1</TD>
        <TD CLASS="rowData">
	  <INPUT NAME="matterportID" TYPE="text" class="maininput" VALUE="<cfif editMode>#qTours.matterportID#<cfelse></cfif>"> <i>The ID from: my.matterport.com/show/?m={3d Tour ID}</i>
    <br />
      <INPUT NAME="mtpTitle1" TYPE="text" class="maininput"  VALUE="<cfif editMode>#qTours.mtpTitle1#<cfelse></cfif>" /><i>Display Title</i>
	 </TD>
      </TR>

        <TR>
        <TD CLASS="rowHead">3D Tour ID 2</TD>
        <TD CLASS="rowData">
    <INPUT NAME="matterportID2" TYPE="text" class="maininput" VALUE="<cfif editMode>#qTours.matterportID2#<cfelse></cfif>"> <i>The ID from: my.matterport.com/show/?m={3d Tour ID}</i>
    <br />
      <INPUT NAME="mtpTitle2" TYPE="text" class="maininput"  VALUE="<cfif editMode>#qTours.mtpTitle2#<cfelse></cfif>" /><i>Display Title</i>
   </TD>
      </TR>


    <TR>
        <TD CLASS="rowHead">Looped-Background</TD>
        <TD CLASS="rowData">
    <INPUT NAME="backgroundVideo" TYPE="text" VALUE="<cfif editMode>#qTours.backgroundVideo#<cfelse></cfif>">
   </TD>
      </TR>
	<TR>

	 <TD CLASS="sectionHead" COLSPAN="2">Additional Media</TD>
		</TR>
		<TR>
        <TD CLASS="rowHead">Video Walk Thrus</TD>
        <TD CLASS="rowData">
	  <INPUT NAME="walkthrus" TYPE="text" SIZE="3" MAXLENGTH="3" VALUE="<cfif editMode>#qTours.walkthrus#<cfelse>0</cfif>">
	 </TD>
      </TR>
		<TR>
        <TD CLASS="rowHead">Room/Scene Videos</TD>
        <TD CLASS="rowData">
	  <INPUT NAME="videos" TYPE="text" SIZE="3" MAXLENGTH="3" VALUE="<cfif editMode>#qTours.videos#<cfelse>0</cfif>">
	 </TD>
      </TR>
		<TR>
        <TD CLASS="rowHead">HDR</TD>
        <TD CLASS="rowData">
	  <INPUT NAME="panoramics" TYPE="text" SIZE="3" MAXLENGTH="3" VALUE="<cfif editMode>#qTours.panoramics#<cfelse>0</cfif>">
	 </TD>
      </TR>
		<TR>
        <TD CLASS="rowHead">Photos</TD>
        <TD CLASS="rowData">
	  <INPUT NAME="photos" TYPE="text" SIZE="3" MAXLENGTH="3" VALUE="<cfif editMode>#qTours.photos#<cfelse>0</cfif>">
	 </TD>
      </TR>
		<CFIF editMode>
		<TR>
        <TD CLASS="rowHead">Date Created</TD>
        <TD CLASS="rowData">
		  <CFSET createdOnDate = dateFormat(qTours.createdOn, "mm/dd/yyyy") & " " & timeFormat(qTours.createdOn, "hh:mm tt")>
		  #createdOnDate#</TD>
      </TR>
		<TR>
        <TD CLASS="rowHead">Date Modified</TD>
        <TD CLASS="rowData">
		  <CFSET modifiedOnDate = dateFormat(qTours.modifiedOn, "mm/dd/yyyy") & " " & timeFormat(qTours.modifiedOn, "hh:mm tt")>
		  #modifiedOnDate#</TD>
      </TR>
		</CFIF>
      <TR>
      	<td>Generate zips:</td>
        <td><a href="http://cfd342.cfdynamics.com/repository_queries/createzips.php?tourID=#qTours.tourID#" target="_blank">generate zips</a></td>
      </TR>
		<TR>
        <TD CLASS="rowHead"><CFIF editMode>
            <INPUT TYPE="hidden" NAME="tourID" VALUE="#qTours.tourID#">
          </CFIF></TD>
        <TD CLASS="rowData"><INPUT TYPE="submit" VALUE="<cfif EditMode>Update<cfelse>Add</cfif> Tour"></TD>
      </TR>
 </TABLE>
 </FORM>
 </CFOUTPUT>
</BODY>
</HTML>
