<CFAPPLICATION name="administration" sessionmanagement="yes" sessiontimeout="#createTimeSpan(0,1,0,0)#">
<!--- <cferror type="exception" exception="any" mailto="webmaster@sitecreativenetwork.com" template="includes/_error.cfm">
<cferror type="request" exception="any" mailto="webmaster@sitecreativenetwork.com" template="includes/_error.cfm">--->
<CFSETTING showdebugoutput="no">
<!--- ::	Set encoding of form and url scopes to UTF-8.	:: --->
<CFSET setEncoding("URL", "UTF-8")>
<CFSET setEncoding("Form", "UTF-8")>

<!--- ::	Set the output encoding to UTF-8	:: --->
<CFCONTENT type="text/html; charset=UTF-8">

<!--- :: Set global site variables :: --->
<CFINCLUDE template="../config.cfm">

<!--- :: User logout :: --->
<CFIF isDefined("url.logout")>
	<CFSET structClear(session)>
    <CFSET structClear(application)>
</CFIF>

<CFSCRIPT>
if(not StructKeyExists(application,"smscarriers")) {

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
				
				application.smscarriers["CELLULARSOUTHUS"] = {};
				application.smscarriers["CELLULARSOUTHUS"].emailtotext = '@swmsg.com';
				application.smscarriers["CELLULARSOUTHUS"].displayname = 'CELLULAR SOUTH US';	
				
				
				
}
</CFSCRIPT>

<!--- :: Include security page to validate user login :: --->
<CFINCLUDE template="includes/security.cfm">

<!--- :: Check if administrator has logged in, if not send to login page :: --->
<CFIF not isDefined("session.administratorID") and right(cgi.SCRIPT_NAME, 9) neq "login.cfm">
	<CFLOCATION url="#request.admin.root#/login.cfm" addtoken="no">
</CFIF>

<CFIF StructKeyExists(url,'reset007')>

<CFSET StructClear(application)>
	<CFLOCATION url="http://#cgi.server_name##cgi.script_name#" addtoken="no">

</CFIF>