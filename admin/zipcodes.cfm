<cfset zipList = '84006,84020,84044,84047,84065,84070,84084,84088,84092,84093,84094,84095,84101,84102,84103,84104,84105,84106,84107,84108,84109,84111,84112,84113,84115,84116,84117,84118,84119,84120,84121,84123,84124,84128,84003,84004,84042,84043,84057,84058,84062,84097,84601,84602,84604,84606,84651,84653,84655,84660,84663,84664,84049,84082,84017,84060,84098,84029,84074,84067,84310,84315,84317,84403,84404,84401,84405,84408,84414,84010,84014,84015,84025,84037,84040,84041,84054,84056,84075,84087,84405,84018,84050,84621,84622,84627,84629,84630,84634,84642,84647'>


<cfquery datasource="#request.db.dsn#">
update uszipcodes set available = 0
</cfquery>
<cfquery datasource="#request.db.dsn#">
update uszipcodes set available = 1 where zip in (#zipList#)
</cfquery>