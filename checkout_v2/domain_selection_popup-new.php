<div id="domainMsg"></div>
<div class="form_line" style="height:auto; height:25px; margin-bottom:10px;">
	<div class="form_direction fullWidth">Domain name selection tool</div>
</div>
<div id='domainUrl_0'>
	<div class="form_line widthAuto left">
		<div class="input_line w_md">
			<div class="input_title widthAuto">www.</div>
			<input id="domainName_0" name="domainName" />
		</div>
	</div>
	<div class="left">&nbsp;</div>
	<div class="form_line widthAuto left" >
		<div class="input_line widthAuto" >
			<select name="domainExt" id="domainExt_0">
				<option value=".com">.com</option>
				<option value=".net">.net</option>
				<option value=".org">.org</option>
				<option value=".biz">.biz</option>
				<option value=".name">.name</option>
				<option value=".info">.info</option>
			</select>
		</div>
	</div>
	<div class='left' style='width:80px;'> 

		<select id='domainBranding_0'>
			<option value='branded'>Branded</option>
			<option value='nonbranded'>Non-Branded</option>
		</select>
	</div> 
</div>
<div id='additionalUrls'></div>
<div class="add-more" style='border: 1px solid #555;float:right;padding:10px;'>
	<a href='javascript:void(0);' onClick='addDomainUrl()'>Add More URLs</a>
</div>
<div class="clear"></div>

<div style='padding: 10px;'>
	<div id="domainSaveButton" style='display:none;float:right;' class="button_new button_blue button_mid" onclick="domainSave()">
		<div class="curve curve_left" ></div>
		<span class="button_caption" >Save</span>
		<div class="curve curve_right" ></div>
	</div>

	<div id="domainButton" style='float: left;' class="button_new button_blue button_mid" onclick="checkDomainList()">
		<div class="curve curve_left" ></div>
		<span class="button_caption" >Check Availability</span>
		<div class="curve curve_right" ></div>
	</div>
</div>
