<cfsilent>
	<cfsetting showdebugoutput="false">
	<cfxml variable="sampleConfig">
<config>
  <config>
    <initialFileURL>apinit.cfm</initialFileURL>
    <saveFileURL>apsave.cfm</saveFileURL>
    <deleteFileURL>apdelete.cfm</deleteFileURL>
  </config>
</config>
	</cfxml>
</cfsilent><cfcontent type="text/xml"><cfoutput>#sampleConfig#</cfoutput>