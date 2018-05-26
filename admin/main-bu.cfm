<CFSILENT>
<CFSET kb = 1024>
<CFSET mb = kb * 1024> 
<CFSET gb = mb * 1024> 
<CFSET tb = gb * 1024> 
<CFSET fileOb = createObject("java", "java.io.File").init("d:\")>
<CFSET freespace=val(fileOb.getFreeSpace()) />
<CFSET usablespace=val(fileOb.getTotalSpace())-val(fileOb.getFreeSpace()) />
<CFSET totalspace=val(fileOb.getTotalSpace()) />
<CFSET stringTotal=(round(totalspace/ gb)) />
<CFSET freespaceUable=(round(freespace/ gb)) />
<CFSET takenString=(round(usablespace/ gb)) />


<CFSET fileObf = createObject("java", "java.io.File").init("f:\")>
<CFSET freespacef=val(fileObf.getFreeSpace()) />
<CFSET usablespacef=val(fileObf.getTotalSpace())-val(fileObf.getFreeSpace()) />
<CFSET totalspacef=val(fileObf.getTotalSpace()) />
<CFSET stringTotalf=(round(totalspacef/ tb)) />
<CFSET freespaceUablef=(round(freespacef/ gb)) />
<CFSET takenStringf=(round(usablespacef/ gb)) />

<cfset runtime = CreateObject("java","java.lang.Runtime").getRuntime()>
<cfset freeMemory = runtime.freeMemory() / 1024 / 1024>
<cfset totalMemory = runtime.totalMemory() / 1024 / 1024>
<cfset maxMemory = runtime.maxMemory() / 1024 / 1024>


<cfset percentFreeAllocated = Round((freeMemory / totalMemory) * 100)>
<cfset percentAllocated = Round((totalMemory / maxMemory ) * 100)>


</CFSILENT>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Main</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK HREF="includes/admin_styles.css" REL="stylesheet" TYPE="text/css">

</HEAD>

<BODY>



<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
  <TR>
    <TD><h2>D Drive (Production)</h2>
Total Space: <CFOUTPUT>#stringTotal# GB</CFOUTPUT> <BR>
Space In Use: <CFOUTPUT>#takenString# GB</CFOUTPUT> <BR>
Free Space: <CFOUTPUT>#freespaceUable# GB</CFOUTPUT> <BR>
<CFCHART
         format="png"
         scalefrom="0"
      
         pieslicestyle="solid">
	<CFCHARTSERIES
	             type="pie"
	             serieslabel="HD Space"
	             seriescolor="blue">
		<CFCHARTDATA item="Free Space" value="#freespaceUable#">
		<CFCHARTDATA item="Used Space" value="#takenString#">
		</cfchartseries>
</CFCHART>
</TD>
    <TD><h2>F Drive (Backup)</h2>
Total Space: <CFOUTPUT>#stringTotalf# TB</CFOUTPUT> <BR>
Space In Use: <CFOUTPUT>#takenStringf# GB</CFOUTPUT> <BR>
Free Space: <CFOUTPUT>#freespaceUablef# GB</CFOUTPUT> <BR>
<CFCHART
         format="png"
         scalefrom="0"
      
         pieslicestyle="solid">
	<CFCHARTSERIES
	             type="pie"
	             serieslabel="HD Space"
	             seriescolor="blue">
		<CFCHARTDATA item="Free Space" value="#freespaceUablef#">
		<CFCHARTDATA item="Used Space" value="#takenStringf#">
		</cfchartseries>
</CFCHART></TD>
  </TR>
  <TR>
    <TD><cfoutput>
   <STRONG> Total Memory Allocated:</STRONG> #Round(totalMemory)#mb<br>
    <STRONG>Max Memory Available to JVM:</STRONG> #Round(maxMemory)#mb<br>
    <STRONG>Free Allocated Memory:</STRONG> #Round(freeMemory)#mb<br>
</cfoutput>
<br>
<cfoutput>
    <STRONG> % of Available Memory Allocated:</STRONG> #percentAllocated#%<br>
	<STRONG>% of Free Allocated Memory:</STRONG> #percentFreeAllocated#%<br>
</cfoutput>
</TD>
    <TD></TD>
  </TR>
</TABLE>
</BODY>
</HTML>
