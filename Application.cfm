<CFSILENT>
<CFINCLUDE template="config.cfm" />
<CFAPPLICATION name="#request.app_name#" sessionmanagement="yes" sessiontimeout="#createTimeSpan(1,0,0,0)#">

<!--- :: Include Standard Library Functions :: --->
<CFINCLUDE template="lib/stdlib.cfm">

<CFIF not StructKeyExists(application,"alert")
	OR (
		StructKeyExists(url,"reset") AND
		url.reset eq "knufflebunny"
	)>
	<CFSET application.alert = createObject('component',"alertAdmin").init() />
</CFIF>

<CFIF not StructKeyExists(application,"tool")
	OR (
		StructKeyExists(url,"reset") AND
		url.reset eq "knufflebunny"
	)>
	<CFSET application.tool = createObject('component',"tool").init() />
</CFIF>

<CFIF not StructKeyExists(application,"music") >
	<CFSET myMusicPart = "/music" />
 	<CFSET myMusicPath = "http://www.spotlighthometours.com" & myMusicPart />

	<CFDIRECTORY directory="#ExpandPath(myMusicPart)#" action="list" name="music">
	<CFSET application.Music = ArrayNew(2) />
	<CFSET i = 0 />
	<CFLOOP query="music">
		<CFIF left(music.name,1) eq '_'>
			<CFSET i = i + 1 />
			<CFSET application.Music[i][1] =  music.name />
			<CFSET application.Music[i][2] = myMusicPath&"/"&music.name />
		</CFIF>
	</CFLOOP>
</CFIF>



<CFIF not StructKeyExists(application,"smsToMobileWeb")>
	<CFSET application.smsToMobileWeb = StructNew() />
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
				
}

if(not StructKeyExists(application,"stcT9Lookup")) {
	application.stcT9Lookup = 	{
		a = 2,
		b = 2,
		c = 2,
		d = 3,
		e = 3,
		f = 3,
		g = 4,
		h = 4,
		i = 4,
		j = 5,
		k = 5,
		l = 5,
		m = 6,
		n = 6,
		o = 6,
		p = 7,
		q = 7,
		r = 7,
		s = 7,
		t = 8,
		u = 8,
		v = 8,
		w = 9,
		x = 9,
		y = 9,
		z = 9
	};
}

</CFSCRIPT>


<CFIF not StructKeyExists(application,"LangPack") >
	<CFQUERY name="qLangInfo" datasource="#request.dsn#">
		select keyword,english_file,spanish_file
		from ivr_rec
	</CFQUERY>

	<CFSET application.LangPack = StructNew() />

	<CFLOOP query="qLangInfo">
		<CFSET application.LangPack[qLangInfo.keyword] = StructNew() />
		<CFSET application.LangPack[qLangInfo.keyword].eng = qLangInfo.english_file />
		<CFSET application.LangPack[qLangInfo.keyword].spn = qLangInfo.spanish_file />
	</CFLOOP>
</CFIF>


<!--- ::	Set encoding of form and url scopes to US-ASCII	:: --->
<CFSET setEncoding("URL", "US-ASCII")>
<CFSET setEncoding("Form", "US-ASCII")>

<!--- ::	Set the output encoding to US-ASCII	:: --->
<CFCONTENT type="text/html; charset=US-ASCII">

<!--- :: Check user status, transfer to request scope :: --->
<CFLOCK scope="session" type="exclusive" timeout="10">
	<CFPARAM name="session.user.loggedIn" default="false">
	<CFPARAM name="session.team.loggedIn" default="false">
	<CFSET request.session = session>
</CFLOCK>

<CFIF StructKeyExists(url,'logout')>
	<CFSET request.session.user.loggedIn = false>
	<CFSET request.session.team.loggedIn = false>
	<CFLOCATION url="http://#cgi.server_name##cgi.script_name#" addtoken="no">
</CFIF>
<CFIF StructKeyExists(url,'reset007')>
<CFSET request.session.user.loggedIn = false>
	<CFSET request.session.team.loggedIn = false>
<CFSET StructClear(application.smscarriers)>
<CFSET StructClear(application.LangPack)>
	<CFLOCATION url="http://#cgi.server_name##cgi.script_name#" addtoken="no">

</CFIF>
</CFSILENT>