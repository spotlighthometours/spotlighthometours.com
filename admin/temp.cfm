<!---<cffunction name="sepText" access="public" hint="get id" returntype="any">
   <cfargument name="inchar" type="string" required="Yes">
	<cfset newChar="">
    <cfloop
        index="intChar"
        from="1"
        to="#Len(arguments.inchar)#"
        step="1">
       <cfset strChar = Mid( arguments.inchar, intChar, 1 )&" " />
     <cfset newChar=newChar&strChar />
    </cfloop>
<cfreturn newChar>
</cffunction>







<cfoutput>
<!--- Loop over the length of the string. --->
#sepText('testmeplease')#
</cfoutput>--->


<!---<cfset tempkey='12124' />

<cfif len(tempkey) gte 6>
			<cfset strMLS = Right(tempkey,Len(tempkey) - 1) />
		<cfelse>
			<cfset strMLS = "" />
		</cfif>

		<!--- look up the keypress, if there was a row returned use the previouw keyword value. if not, use the keyvalue passed in --->
		<cfquery name="qCheck" datasource="#request.db.dsn#">
			select * from (
				select u.firstname,u.lastname,u.phone,m.wavfile, m.tourid_fk, m.userid_fk, t.tourid,t.description, t.address, t.createdon, m.housecode, t.bedrooms, t.bathrooms, t.sqfootage, t.listprice
				from mobilekeys m join tours t on m.tourid_fk = t.tourid join users u on t.userid = u.userid
				where m.housecode = <cfqueryparam cfsqltype="cf_sql_integer" value="#tempkey#">
				and t.suspended is null
				and m.tourid_fk is not null

				<cfif strMLS neq "">
					<!---union

					select users.firstname,users.lastname,users.phone,'' as wavfile,tourID as tourid_fk,users.userid as userid_fk,tours.tourid,tours.description,tours.address,tours.createdon,
					'%#tempkey#%' as housecode, tours.bedrooms, tours.bathrooms, tours.sqfootage, tours.listprice
					from tours join users on tours.userid = users.userid
					where mls like '%#strMLS#%'
					and tours.suspended is null
					and users.brokerageID IN (#request.lPRUBrokerages#)
					and ltrim(description) <> ''
					and tours.sold = 0
					and users.sms = 1--->
				</cfif>

				<!---union <!--- get the prudential tours that have been custom saved for this number--->

				select u2.firstname,u2.lastname,u2.phone,m2.wavfile, m2.tourid_fk, m2.userid_fk, t2.tourid, t2.description, t2.address, t2.createdon, m2.housecode,t2.bedrooms, t2.bathrooms, t2.sqfootage, t2.listprice
				from mobilekeys_pru m2 join tours t2 on m2.tourid_fk = t2.tourid join users u2 on t2.userid = u2.userid
				where m2.housecode = <cfqueryparam cfsqltype="cf_sql_integer" value="#tempkey#">
				and t2.suspended is null
				and m2.tourid_fk is not null--->

				<!---union <!--- get the tours are relying on the default addy--->

				select users.firstname,users.lastname,users.phone,m.wavfile,tourID as tourid_fk,users.userid as userid_fk,tours.tourid,tours.description,tours.address,tours.createdon,
				SUBSTRING(tours.address FROM 1 FOR POSITION(' ' IN tours.address)) as housecode, tours.bedrooms, tours.bathrooms, tours.sqfootage, tours.listprice
				from mobilekeys m right join tours on m.tourid_fk = tours.tourid join users on tours.userid = users.userid
				where SUBSTRING(tours.address FROM 1 FOR POSITION(' ' IN tours.address)) = <cfqueryparam cfsqltype="cf_sql_varchar" value="#tempkey#" />
				and tours.suspended is null
				and tours.sold = 0
				and users.sms = 1--->
			) a
			order by createdon desc
		</cfquery>
        
        <cfdump var="#qCheck#" />--->
		
<!---        <cffunction name="directoryCopy" output="false">
<cfargument name="tourid" required="true" type="string">
<cfset source ="D:\websites\spotlighthometours\public\images\tours\"&arguments.tourid />
<cfset destination ="F:\backup\"&arguments.tourid />


<cfdirectory action="LIST" directory="#source#" name="dirlist">
<cfloop query="dirlist">

<cfif DirectoryExists(source) >
	<cfset _dir = destination  >
    <cfif not DirectoryExists(destination)>
    <cfdirectory action="create" directory="#destination#">
    <cffile action="copy" source="#source#\#dirlist.name#" destination="#destination#">
    <cfelse>
    <cffile action="copy" source="#source#\#dirlist.name#" destination="#destination#">
    </cfif>

</cfif>    

</cfloop>
<cfif DirectoryExists(source) >
<cfdirectory action="delete" directory="#source#" recurse = "yes">
</cfif>
</cffunction>
        
        <cfquery name="qCheck" datasource="#request.db.dsn#">
        select tourID from tours where suspended=1 and year(createdon)='2009' and archived=0 order by createdon desc limit 5
        </cfquery>
        
        <cfloop query="qCheck">
        
<cfset file = directoryCopy(tourID) />
<cfoutput>#tourid#,</cfoutput>
   <cfquery name="qUpdate" datasource="#request.db.dsn#">
        UPDATE tours set archived='1',suspended='1' where tourid='#tourid#' limit 1
        </cfquery>
        
        
        </cfloop>
        
        --->
        
        
        <CFSCRIPT>
		
		
		
		if(not StructKeyExists(application,"smscarriers")) {
			StructDelete(application,"smscarriers");
		}
		

application.smscarriers = StructNew();

		application.smscarriers = {};

				application.smscarriers["VERIZONUS"] = {};
				application.smscarriers["VERIZONUS"].emailtotext = '@vtext.com';
				application.smscarriers["VERIZONUS"].displayname = 'Verizon';

				application.smscarriers["CINGULARUS"] = {};
				application.smscarriers["CINGULARUS"].emailtotext = '@cingularme.com';
				application.smscarriers["CINGULARUS"].displayname = 'Cingular';

				application.smscarriers["ATTUS"] = {};
				application.smscarriers["ATTUS"].emailtotext = '@txt.att.net';
				application.smscarriers["ATTUS"].displayname = 'AT&T';


				application.smscarriers["NEXTELUS"] = {};
				application.smscarriers["NEXTELUS"].emailtotext = '@messaging.nextel.com';
				application.smscarriers["NEXTELUS"].displayname = 'Nextel';


				application.smscarriers["TMOBILEUS"] = {};
				application.smscarriers["TMOBILEUS"].emailtotext = '@tmomail.net';
				application.smscarriers["TMOBILEUS"].displayname = 'TMobile';

				application.smscarriers["SPRINTUS"] = {};
				application.smscarriers["SPRINTUS"].emailtotext = '@messaging.sprintpcs.com';
				application.smscarriers["SPRINTUS"].displayname = 'Sprint';
				
				application.smscarriers["VIRGIN"] = {};
				application.smscarriers["VIRGIN"].emailtotext = '@vmobl.com';
				application.smscarriers["VIRGIN"].displayname = 'Virgin';
				
				
				application.smscarriers["ROGERS"] = {};
				application.smscarriers["ROGERS"].emailtotext = '@pcs.rogers.com';
				application.smscarriers["ROGERS"].displayname = 'Rogers';
				
				
				application.smscarriers["TELUS"] = {};
				application.smscarriers["TELUS"].emailtotext = '@msg.telus.com';
				application.smscarriers["TELUS"].displayname = 'Telus';
				
				application.smscarriers["FIDO"] = {};
				application.smscarriers["FIDO"].emailtotext = '@fido.ca';
				application.smscarriers["FIDO"].displayname = 'Fido';
				
				application.smscarriers["BELL"] = {};
				application.smscarriers["BELL"].emailtotext = '@txt.bell.xa';
				application.smscarriers["BELL"].displayname = 'Bell';
		
				application.smscarriers["KOODOMOBILE"] = {};
				application.smscarriers["KOODOMOBILE"].emailtotext = '@msg.koodomobile.com';
				application.smscarriers["KOODOMOBILE"].displayname = 'Koodomobile';
								
				application.smscarriers["SASKTEL"] = {};
				application.smscarriers["SASKTEL"].emailtotext = '@sms.sasktel.com';
				application.smscarriers["SASKTEL"].displayname = 'SaskTel';
				
				
				application.smscarriers["VIRGINCA"] = {};
				application.smscarriers["VIRGINCA"].emailtotext = '@vmobile.ca';
				application.smscarriers["VIRGINCA"].displayname = 'Virgin CA';		
				
				application.smscarriers["PCMOBILE"] = {};
				application.smscarriers["PCMOBILE"].emailtotext = '@mobiletxt.ca';
				application.smscarriers["PCMOBILE"].displayname = 'PCMobile';		
				
				
				application.smscarriers["ALIANT"] = {};
				application.smscarriers["ALIANT"].emailtotext = '@wirefree.informe.ca';
				application.smscarriers["ALIANT"].displayname = 'Aliant';		
								
								
				application.smscarriers["USCELLULARUS"] = {};
				application.smscarriers["USCELLULARUS"].emailtotext = '@email.uscc.net';
				application.smscarriers["USCELLULARUS"].displayname = 'USCellular US';	
				

		</CFSCRIPT>
        
        <CFDUMP var="#application.smscarriers#" >