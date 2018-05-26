f1-96FA-ED6231B9276F" class="nochunk"><a name="WS7F9E1EC8-AD77-46f1-96FA-ED6231B9276F"><!-- --></a><h3 class="topictitle3">Enable SSL</h3><div><ol><li><p>Create the keystore.</p>
</li>
<li><p>Configure Flex.</p>
</li>
<li><p>Enable SSL in the ColdFusion Administrator.</p>
</li>
</ol>
</div></div><div id="WS13F77A98-EB49-423b-9A02-439493C2173D" class="nochunk"><a name="WS13F77A98-EB49-423b-9A02-439493C2173D"><!-- --></a><h3 class="topictitle3">Create the keystore</h3><div><div class="para"><img src="images/dingbat.png" />&#160;To generate the SSL server (ColdFusion) keystore
file, use the keytool utility, with a command similar to the following:<pre>keytool -genkey -v -alias FlexAssembler -dname "cn=FlexAssembler" -keystore cf.keystore -keypass mypassword -storepass mypassword</pre>
<p>The
following table describes the parameters of the keytool utility:</p>

<div class="tablenoborder"><table border="1" cellpadding="4" cellspacing="0"><thead align="left"><tr><th va