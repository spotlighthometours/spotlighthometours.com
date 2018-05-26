<cfparam name="url.action" default="">
<cfparam name="msg" default="">
<cfparam name="url.pg" default="listproducts">

<cfswitch expression="#url.action#">
	<cfcase value="insertProduct">
		<cfquery datasource="#request.db.dsn#">
			insert into products (productName, unitPrice, onePerOrder, chargeSalesTax, description)
			values (<cfqueryparam value="#form.productName#" cfsqltype="cf_sql_varchar" maxlength="50">,
					  <cfqueryparam value="#reReplace(form.unitPrice, "[^0-9.]", "", "all")#" cfsqltype="cf_sql_money">,
					  <cfif isDefined('form.onePerOrder')>1<cfelse>0</cfif>,
					  <cfif isDefined('form.chargeSalesTax')>1<cfelse>0</cfif>,
					  <cfqueryparam value="#form.description#" cfsqltype="cf_sql_varchar" maxlength="1000">
			)
		</cfquery>
		<cfset msg = "Product has been added successfully.">
	</cfcase>
	
	<cfcase value="updateProduct">
		<cfquery datasource="#request.db.dsn#">
			update products
			set productName = <cfqueryparam value="#form.productName#" cfsqltype="cf_sql_varchar" maxlength="50">,
				 unitPrice =   <cfqueryparam value="#reReplace(form.unitPrice, "[^0-9.]", "", "all")#" cfsqltype="cf_sql_money">,
				 onePerOrder =   <cfif isDefined('form.onePerOrder')>1<cfelse>0</cfif>,
				 chargeSalesTax = <cfif isDefined('form.chargeSalesTax')>1<cfelse>0</cfif>,
				 description = <cfqueryparam value="#form.description#" cfsqltype="cf_sql_varchar" maxlength="1000">
			where productID = #form.productID#
		</cfquery>
		<cfset msg = "Product has been successfully updated.">
	</cfcase>
	
	<cfcase value="deleteProduct">
		<cfquery datasource="#request.db.dsn#">
			delete from products where productID = #url.product#
		</cfquery>
		<cfset msg = "Product has been successfully removed.">
	</cfcase>
</cfswitch>
<cfinclude template="_#url.pg#.cfm">