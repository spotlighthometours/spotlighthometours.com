<cfcomponent>
	<CFFUNCTION name="SetMembersActive" access="public">
    <CFARGUMENT name="userID" type="string" required="Yes">
    <CFARGUMENT name="MembershipID" type="string" required="Yes">
    <CFARGUMENT name="Active" type="string" required="Yes">
    
    
        <CFQUERY name="qMembers" datasource="#request.db.dsn#">
            SELECT Active FROM members WHERE userID = '#arguments.userID#' and typeID = '#arguments.MembershipID#'
        </CFQUERY>
        <CFIF qMembers.RecordCount gt 0>
            <CFQUERY datasource="#request.db.dsn#">
                UPDATE members SET
                active = #arguments.Active#,
                trial = 0
                WHERE userID = #arguments.userID# AND typeID=#arguments.MembershipID#
            </CFQUERY>
        <CFELSE>
            <CFIF #arguments.Active# eq 1>
                <CFQUERY datasource="#request.db.dsn#">
                    INSERT INTO members (typeID, userID, trial, createDate, active)
                    VALUES (#arguments.MembershipID#, #arguments.userID#, 0, now(), 1)
                </CFQUERY>
            </CFIF>
        </CFIF>
    </CFFUNCTION>
</cfcomponent>