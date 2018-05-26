<cfsilent>
	<cfquery name="qCodes" datasource="#request.dsn#">
		select m.keyword, m.housecode,m.wavfile,m.modifiedon,
		u.firstname,u.lastname,t.title,t.tourid, b.brokeragename
		from mobilekeys m join users u on m.userid_fk = u.userid
		join tours t on m.tourid_fk = t.tourid left join brokerages b on
		u.brokerageid = b.brokerageid
		order by t.tourID DESC
	</cfquery>
</cfsilent>
<cfoutput>
<h3>Keyword/House Code Management</h3>
<table>
	<tr>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Brokerage</th>
		<th>Tour ID</th>
		<th>Tour Title</th>
		<th>Keyword (Txt)</th>
		<th>House Code (IVR)</th>
		<th>Wav File</th>
		<th>Last Modified</th>
	</tr>
	<cfloop query="qCodes">
		<tr bgcolor="###iif(qCodes.currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
			<td>#qCodes.firstName#</td>
			<td>#qCodes.LastName#</td>
			<td>#qCodes.BrokerageName#</td>
			<td>#qCodes.TourID#</td>
			<td>#qCodes.Title#</td>
			<td>#qCodes.Keyword#</td>
			<td>#qCodes.HouseCode#</td>
			<td>#qCodes.WavFile#</td>
			<td>#DateFormat(qCodes.ModifiedOn,"mm/dd")# #Timeformat(qCodes.ModifiedOn,"hh:mm")#</td>
		</tr>
	</cfloop>
</table>
</cfoutput>