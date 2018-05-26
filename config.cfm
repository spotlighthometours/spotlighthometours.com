<cfsilent>
	<cfscript>
	request.app_name = 'spotlight'; // app_name is the name of the application and the DSN
	request.main_contact = 'bret@spotlighthometours.com';
	request.technical_contact = 'mrigeshr@gmail.com';
	request.new_order_contact = 'neworders@spotlighthometours.com';
	request.testMode = false ;
	request.dsn = request.app_name;
	request.db.dsn = request.dsn; // legacy dsn variable
	request.adminRoot = '/admin';
	request.page_title = 'Spotlight Home Tours';
	request.admin.name = "Spotlight Home Tours";
	request.admin.root = "/admin";
	request.salestax = 0.0685;
	request.lPRUBrokerages = "9,87,66,60,83,59,61,64,65,58,57,27";

	if(ListFindNoCase(cgi.HTTP_HOST,'spotlightexpress','.')
		or ListFindNoCase(cgi.HTTP_HOST,'spotlightxpresstours','.')) {
		request.expressLogin = 1;
	}
	else {
		request.expressLogin = 0;
	}


	if(request.testMode) {
		//for testing
		request.aurgimaLicense = "71050-10000-55BD8-5B980-0CFA1;72050-10000-9CE0A-8DE71-CDAFB";
		request.writeLogs = true;
		if(cgi.REMOTE_ADDR eq '127.0.0.1' OR cgi.REMOTE_ADDR eq '166.70.196.59') {
			request.showdebug = 'yes';
		}
		else {
			request.showdebug = 'no';
		}
	}
	else {
		//for production
		request.aurgimaLicense = "71050-10000-80D69-C1564-A92A8;72050-10000-60E3D-E38B1-EF85C";
		request.writeLogs = false;
		request.showdebug = 'no';
	}
	</cfscript>


</cfsilent>