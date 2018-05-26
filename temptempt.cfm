<CFFUNCTION NAME="mainproducts" ACCESS="public" RETURNTYPE="STRUCT">
      <CFARGUMENT NAME="zip" TYPE="string" REQUIRED="yes">
      <CFQUERY name="qZip" datasource="#request.dsn#">
        Select zipid,city,county,
        state_prefix,zip_code from zip_code where zip_code = '#arguments.zip#' 
     </CFQUERY>
     <CFIF qZip.RecordCount gt 1>
     	<CFLOOP QUERY="qZip">
          <CFQUERY name="qCity" datasource="#request.dsn#">
            Select 	zip_code_state_id,
                    city,
                    county,
                    state 
            from 	zip_code_state_county_city 
            where 	city='#qZip.city#' and 
                    county='#qZip.county#' and 
                    state='#qZip.state_prefix#' 
            limit 1
         </CFQUERY>
          <CFQUERY name="qCounty" datasource="#request.dsn#">
            Select 	zip_code_state_id,
                    county,
                    state
            from 	zip_code_state_county
            where 	county='#qCity.county#' and 
                    state='#qCity.state#'  
            limit 1
         </CFQUERY>
          <CFQUERY name="qState" datasource="#request.dsn#">
            Select 	zip_code_state_id,
                    state,
                    string
            from 	zip_code_state
            where 	state='#qCounty.state#'  
            limit 1
         </CFQUERY>
        
        <CFSCRIPT>
         stcGetOrigin = {
            zipid=qZip.zipid,
            zip=qZip.zip_code,
            cityid=qCity.zip_code_state_id,
            city=qCity.city,
            Countyid=qCounty.zip_code_state_id,
            County=qCounty.county,
            stateid=qState.zip_code_state_id,
            state=qState.state
            };
         </CFSCRIPT>
               <CFQUERY name="qTourTypes" datasource="#request.db.dsn#">
            select tourTypeID, tourTypeName, unitPrice
            from tourTypes
            order by tourCategory ASC
        </CFQUERY>
          <CFQUERY name="qBrokerages" datasource="#request.db.dsn#">
                select b.state_prefix as state, b.county ,b.city,b.color,b.zip_code,b.zipid as zip_code_state_id
                from zip_code b
                where state_prefix='#stcGetOrigin.state#' and county='#stcGetOrigin.county#' and city='#stcGetOrigin.city#' limit 1
            
            </CFQUERY>
          <CFQUERY name="qTourTypes" datasource="#request.db.dsn#">
                select tourTypeID, tourTypeName, unitPrice,tourCategory
                from tourTypes
                order by tourCategory ASC
            </CFQUERY>
          <CFQUERY name="qStatePricing" datasource="#request.db.dsn#">
                select state_ID, tourtype_id, unitprice
                from pricing_states
            </CFQUERY>
          <CFQUERY name="qCountyPricing" datasource="#request.db.dsn#">
                select state_ID, tourtype_id,county_id, unitprice
                from pricing_counties
                where state_id='#stcGetOrigin.stateid#'
            </CFQUERY>
          <CFQUERY name="qCityPricing" datasource="#request.db.dsn#">
                select state_ID, tourtype_id, unitprice,county_id,city_id
                from pricing_cities
                where state_id='#stcGetOrigin.stateid#' and county_id='#stcGetOrigin.countyid#'
            </CFQUERY>
          <CFQUERY name="qZipPricing" datasource="#request.db.dsn#">
                select state_ID, tourtype_id, unitprice,county_id,city_id,zip_id
                from pricing_zips
                where state_id='#stcGetOrigin.stateid#' and county_id='#stcGetOrigin.countyid#' and city_id='#stcGetOrigin.cityid#'
                
            </CFQUERY>
          <CFSET arrData = {} />
          
          <CFLOOP query="qBrokerages">
              <CFLOOP index="i" from="1" to="#qTourTypes.RecordCount#">
              <CFQUERY name="qTemp" dbtype="query">
                                select unitPrice
                                from qStatePricing
                                where state_ID = #stcGetOrigin.stateid#
                                and tourtype_id = #qTourTypes['tourTypeID'][i]#
                            </CFQUERY>
              <CFIF qTemp.RecordCount gt 0>
                <CFSET arrData[ qTourTypes['tourTypeID'][i] ] = { data = qTourTypes['tourTypeName'][i],category = qTourTypes['tourCategory'][i], key = qTemp.unitPrice } />
                <CFELSE>
              </CFIF>
              </CFLOOP>
          </CFLOOP>
          <CFLOOP query="qBrokerages">
              <CFLOOP index="i" from="1" to="#qTourTypes.RecordCount#">
              <CFQUERY name="qTemp" dbtype="query">
                                select unitPrice
                                from qCountyPricing
                                where county_ID = #stcGetOrigin.countyid#
                                and tourtype_id = #qTourTypes['tourTypeID'][i]#
                            </CFQUERY>
              <CFIF qTemp.RecordCount gt 0>
                <CFSET arrData[ qTourTypes['tourTypeID'][i] ] = { data =qTourTypes['tourTypeName'][i],category = qTourTypes['tourCategory'][i], key = qTemp.unitPrice } />
                <CFELSE>
              </CFIF>
              </CFLOOP>
          </CFLOOP>
          <CFLOOP query="qBrokerages">
              <CFLOOP index="i" from="1" to="#qTourTypes.RecordCount#">
              <CFQUERY name="qTemp" dbtype="query">
                                select unitPrice
                                from qCityPricing
                                where city_ID = #stcGetOrigin.cityid#
                                and tourtype_id = #qTourTypes['tourTypeID'][i]#
                            </CFQUERY>
              <CFIF qTemp.RecordCount gt 0>
                <CFSET arrData[ qTourTypes['tourTypeID'][i] ] = { data = qTourTypes['tourTypeName'][i],category = qTourTypes['tourCategory'][i], key = qTemp.unitPrice } />
                <CFELSE>
              </CFIF>
              </CFLOOP>
          </CFLOOP>
          <CFLOOP query="qBrokerages">
              <CFLOOP index="i" from="1" to="#qTourTypes.RecordCount#">
              <CFQUERY name="qTemp" dbtype="query">
                                select unitPrice
                                from qZipPricing
                                where zip_ID = #stcGetOrigin.zipid#
                                and tourtype_id = #qTourTypes['tourTypeID'][i]#
                            </CFQUERY>
              <CFIF qTemp.RecordCount gt 0>
                <CFIF qTemp.unitPrice gt 0>
                    <CFSET arrData[ qTourTypes['tourTypeID'][i] ] = { data = qTourTypes['tourTypeName'][i],category = qTourTypes['tourCategory'][i], key = qTemp.unitPrice } />
                </CFIF>
                <CFELSE>
              </CFIF>
              </CFLOOP>
          </CFLOOP>
 	 	</CFLOOP>
     <CFELSEIF qZip.RecordCount eq 1>
         <CFQUERY name="qCity" datasource="#request.dsn#">
            Select 	zip_code_state_id,
                    city,
                    county,
                    state 
            from 	zip_code_state_county_city 
            where 	city='#qZip.city#' and 
                    county='#qZip.county#' and 
                    state='#qZip.state_prefix#' 
            limit 1
         </CFQUERY>
          <CFQUERY name="qCounty" datasource="#request.dsn#">
            Select 	zip_code_state_id,
                    county,
                    state
            from 	zip_code_state_county
            where 	county='#qCity.county#' and 
                    state='#qCity.state#'  
            limit 1
         </CFQUERY>
          <CFQUERY name="qState" datasource="#request.dsn#">
            Select 	zip_code_state_id,
                    state,
                    string
            from 	zip_code_state
            where 	state='#qCounty.state#'  
            limit 1
         </CFQUERY>
          <CFDUMP VAR="#qCity#" />
          <CFDUMP VAR="#qCounty#" />
          <CFDUMP VAR="#qState#" />
         <CFABORT>
        <CFSCRIPT>
         stcGetOrigin = {
            zipid=qZip.zipid,
            zip=qZip.zip_code,
            cityid=qCity.zip_code_state_id,
            city=qCity.city,
            Countyid=qCounty.zip_code_state_id,
            County=qCounty.county,
            stateid=qState.zip_code_state_id,
            state=qState.state
            };
         </CFSCRIPT>
               <CFQUERY name="qTourTypes" datasource="#request.db.dsn#">
            select tourTypeID, tourTypeName, unitPrice
            from tourTypes
            order by tourCategory ASC
        </CFQUERY>
      <CFQUERY name="qBrokerages" datasource="#request.db.dsn#">
            select b.state_prefix as state, b.county ,b.city,b.color,b.zip_code,b.zipid as zip_code_state_id
            from zip_code b
            where state_prefix='#stcGetOrigin.state#' and county='#stcGetOrigin.county#' and city='#stcGetOrigin.city#' limit 1
        
        </CFQUERY>
      <CFQUERY name="qTourTypes" datasource="#request.db.dsn#">
            select tourTypeID, tourTypeName, unitPrice,tourCategory
            from tourTypes
            order by tourCategory ASC
        </CFQUERY>
      <CFQUERY name="qStatePricing" datasource="#request.db.dsn#">
            select state_ID, tourtype_id, unitprice
            from pricing_states
        </CFQUERY>
      <CFQUERY name="qCountyPricing" datasource="#request.db.dsn#">
            select state_ID, tourtype_id,county_id, unitprice
            from pricing_counties
            where state_id='#stcGetOrigin.stateid#'
        </CFQUERY>
      <CFQUERY name="qCityPricing" datasource="#request.db.dsn#">
            select state_ID, tourtype_id, unitprice,county_id,city_id
            from pricing_cities
            where state_id='#stcGetOrigin.stateid#' and county_id='#stcGetOrigin.countyid#'
        </CFQUERY>
      <CFQUERY name="qZipPricing" datasource="#request.db.dsn#">
            select state_ID, tourtype_id, unitprice,county_id,city_id,zip_id
            from pricing_zips
            where state_id='#stcGetOrigin.stateid#' and county_id='#stcGetOrigin.countyid#' and city_id='#stcGetOrigin.cityid#'
            
        </CFQUERY>
      <CFSET arrData = {} />
      
      <CFLOOP query="qBrokerages">
          <CFLOOP index="i" from="1" to="#qTourTypes.RecordCount#">
          <CFQUERY name="qTemp" dbtype="query">
                            select unitPrice
                            from qStatePricing
                            where state_ID = #stcGetOrigin.stateid#
                            and tourtype_id = #qTourTypes['tourTypeID'][i]#
                        </CFQUERY>
          <CFIF qTemp.RecordCount gt 0>
            <CFSET arrData[ qTourTypes['tourTypeID'][i] ] = { data = qTourTypes['tourTypeName'][i],category = qTourTypes['tourCategory'][i], key = qTemp.unitPrice } />
            <CFELSE>
          </CFIF>
          </CFLOOP>
      </CFLOOP>
      <CFLOOP query="qBrokerages">
          <CFLOOP index="i" from="1" to="#qTourTypes.RecordCount#">
          <CFQUERY name="qTemp" dbtype="query">
                            select unitPrice
                            from qCountyPricing
                            where county_ID = #stcGetOrigin.countyid#
                            and tourtype_id = #qTourTypes['tourTypeID'][i]#
                        </CFQUERY>
          <CFIF qTemp.RecordCount gt 0>
            <CFSET arrData[ qTourTypes['tourTypeID'][i] ] = { data =qTourTypes['tourTypeName'][i],category = qTourTypes['tourCategory'][i], key = qTemp.unitPrice } />
            <CFELSE>
          </CFIF>
          </CFLOOP>
      </CFLOOP>
      <CFLOOP query="qBrokerages">
          <CFLOOP index="i" from="1" to="#qTourTypes.RecordCount#">
          <CFQUERY name="qTemp" dbtype="query">
                            select unitPrice
                            from qCityPricing
                            where city_ID = #stcGetOrigin.cityid#
                            and tourtype_id = #qTourTypes['tourTypeID'][i]#
                        </CFQUERY>
          <CFIF qTemp.RecordCount gt 0>
            <CFSET arrData[ qTourTypes['tourTypeID'][i] ] = { data = qTourTypes['tourTypeName'][i],category = qTourTypes['tourCategory'][i], key = qTemp.unitPrice } />
            <CFELSE>
          </CFIF>
          </CFLOOP>
      </CFLOOP>
      <CFLOOP query="qBrokerages">
          <CFLOOP index="i" from="1" to="#qTourTypes.RecordCount#">
          <CFQUERY name="qTemp" dbtype="query">
                            select unitPrice
                            from qZipPricing
                            where zip_ID = #stcGetOrigin.zipid#
                            and tourtype_id = #qTourTypes['tourTypeID'][i]#
                        </CFQUERY>
          <CFIF qTemp.RecordCount gt 0>
          	<CFIF qTemp.unitPrice gt 0>
				<CFSET arrData[ qTourTypes['tourTypeID'][i] ] = { data = qTourTypes['tourTypeName'][i],category = qTourTypes['tourCategory'][i], key = qTemp.unitPrice } />
            </CFIF>
            <CFELSE>
          </CFIF>
          </CFLOOP>
      </CFLOOP>
     </CFIF>

      <CFSET tours ={} />
      <CFLOOP query="qBrokerages">
          <CFLOOP index="i" from="1" to="#qTourTypes.RecordCount#">
         <!--- <cfif isdefined(qTourTypes['tourTypeID'][i]) >--->
         	  <CFIF structKeyExists(arrData,qTourTypes['tourTypeID'][i]) >	
			  	<CFSET finalprice=arrData[ qTourTypes['tourTypeID'][i] ].key />
                <CFSET tourtype=arrData[ qTourTypes['tourTypeID'][i] ].data/>
                <CFSET tourcat=arrData[ qTourTypes['tourTypeID'][i] ].category/>
              <CFELSE>
              	<CFSET finalprice=0 />
                <CFSET tourtype=""/>
                <CFSET tourcat=""/>
			  </CFIF>
              <CFIF structKeyExists(arrData,qTourTypes['tourTypeID'][i]) >	
			  	<CFSET finalprice=arrData[ qTourTypes['tourTypeID'][i] ].key />
              <CFELSE>
              	<CFSET finalprice=0 />
			  </CFIF>
              
              
              <CFSET tours[qTourTypes['tourTypeID'][i]] ={} />
              <CFSET tours[qTourTypes['tourTypeID'][i]].price =finalprice />
              <CFSET tours[qTourTypes['tourTypeID'][i]].data =tourtype />
              <CFIF tours[qTourTypes['tourTypeID'][i]].price gt 0 >
                <CFSET tours[qTourTypes['tourTypeID'][i]].hide =0 />
              <CFELSE>
                <CFSET tours[qTourTypes['tourTypeID'][i]].hide =1 />
              </CFIF>
              <CFSET tours[qTourTypes['tourTypeID'][i]].category =tourcat />
              <CFSET tours[qTourTypes['tourTypeID'][i]].tourid=qTourTypes['tourTypeID'][i] />
          <!---</cfif>--->
          </CFLOOP>
      </CFLOOP>
      <CFRETURN tours>
    </CFFUNCTION>
    
 <CFDUMP VAR="#mainproducts(url.zip)#" />