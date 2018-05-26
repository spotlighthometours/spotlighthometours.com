<?php
/*
 * Admin: Custom Orders / Create
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
showErrors();
clearCache();

// Create instances of needed objects
$users = new users();
$brokerages = new brokerages();
$mls = new mls();

// Require admin
//$users->authenticateAdmin();

$brokeragesList = $brokerages->listAll();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Custom Orders | Create</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery.mousewheel.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/jquery-ui-1.10.4.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/globalize.js" type="text/javascript"></script><!-- Globalize JS -->
<script src="../../repository_inc/jquery.autocomplete.js" type="text/javascript"></script><!--- For Autocomplete --->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<script src="../../repository_inc/admin-custom-orders.js" type="text/javascript"></script>
<style type="text/css" media="screen">
@import "../../repository_css/jquery-ui-1.10.2.custom.css";
@import "../../repository_css/jquery.autocomplete.css";
@import "../../repository_css/template.css";
@import "../../repository_css/admin-v2.css";
.ajaxMessage.processing {
	background-image:url('../../repository_images/loader.gif');
	background-position:15px 9px;
	background-repeat:no-repeat;
	background-color:#333;
}
#step2, #step3, #step4, #step5 {
	display:none;
}
.form_line .input_option input {
	position: absolute;
	border: 0px;
	margin: 0px;
	top: 50%;
	left: 2px;
	margin-top: -7px;
	height: 20px;
	width: 20px;
	font-size: 14px;
	line-height: 36px;
	color: FFFFFF;
}
</style>
</head>
<body>
<div id="ajaxMessage"></div>
<h1>Create Custom Order</h1>

<!-- STEP 1: Select User -->
<div id="step1">
  <h2>Step 1: Select User</h2>
  <br/>
  <div class="add-user">
    <div class="form_line" >
      <div class="input_line w_lg" >
        <div class="input_title" >Brokerage</div>
        <select name="brokerageID">
          <option value="0" selected >Select one...</option>
          <?PHP
                    foreach($brokeragesList as $row => $column){
                        $desc = '';
                        if(isset($column['brokerageDesc'])&&!empty($column['brokerageDesc'])){
                            $desc = ' - '.$column['brokerageDesc'];
                        }
    ?>
          <option value="<?PHP echo $column['brokerageID'] ?>"><?PHP echo $column['brokerageName'].$desc; ?></option>
          <?PHP
                    }
    ?>
        </select>
      </div>
      <div class="required_line w_lg" > <span class="required" >select to load users</span> </div>
    </div>
    <div class="form_line" >
      <div class="input_line w_lg" >
        <div class="input_title" >Users</div>
        <div class="user-select">
          <select name="userID" disabled="disabled">
            <option value="0">Select a brokerage to load users!</option>
          </select>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- STEP 2: Enter Property Information? -->
<div id="step2">
  <h2>Step 2: Enter Property Information?</h2>
  <br/>
  <div class="form_line" >
    <div class="input_line w_lg">
      <div class="input_title">I want</div>
      <select name="property_info">
        <option value="0">The user to enter it</option>
        <option value="1">To enter it myself</option>
      </select>
    </div>
  </div>
  <div id="property_info" style="display:none;">
    <div class="form_line">
      <div class="form_direction">Property Information<span style="margin-left:365px;cursor:pointer;font-size:12px;marign-bottom:5px;" id="showhideprop">Hide (-)</span></div>
    </div>
    <div id="property_info_lines">
      <div class="form_line">
        <div class="input_line w_lg">
          <div class="input_title" style="font-size:14px; line-height:18px;">Property<br>
            Title</div>
          <input id="tour_tTitle" name="tour_tTitle" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" maxlength="49">
          <div class="input_desc" style="line-height:18px; margin-left:10px;">&nbsp;(Property Headline)<i class="left" style="font-size:10px;">Example: Fabulous Rambler</i></div>
          <div class="input_info" style="display: none;">
            <div class="info_text">Description of tour, or property address. (Appears on tour)</div>
          </div>
        </div>
        <div class="required_line w_lg"> <span class="required">required</span> </div>
      </div>
      <div class="form_line">
        <div class="input_line w_lg">
          <div class="input_title">Address</div>
          <input id="tour_tAddress" name="tour_tAddress" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);">
          <div class="input_option"> do not display
            <input id="tour_tHideAddress" name="tour_tHideAddress" type="checkbox" value="1">
          </div>
          <div class="input_info" style="display: none;">
            <div class="info_text">Do not include state or zip code.</div>
          </div>
        </div>
        <div class="required_line w_lg"> <span class="required">required</span> </div>
      </div>
      <div class="form_line">
        <div class="input_line w_sm">
          <div class="input_title">Unit No.</div>
          <input id="tour_tUnitNumber" name="tour_tUnitNumber" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);">
          <div class="input_info" style="display: none;">
            <div class="info_text">Property Unit Number</div>
          </div>
        </div>
      </div>
      <div class="form_line">
        <div class="input_line w_mid">
          <div class="input_title">City</div>
          <input id="tour_tCity" name="tour_tCity" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);">
          <div class="input_info" style="display: none;">
            <div class="info_text">The city of the tour.</div>
          </div>
        </div>
        <div class="required_line w_mid"> <span class="required">required</span> </div>
      </div>
      <div class="form_line">
        <div class="input_line w_sm">
          <div class="input_title">Country</div>
          <?PHP echo countrySelectMenu("USA", "tour_country", "tour_country", "country", ""); ?> </div>
        <div class="required_line w_sm"> <span class="required">required</span> </div>
      </div>
      <div class="form_line" id="state_form_line">
        <div class="input_line w_sm">
          <div class="input_title">State</div>
          <?PHP echo stateSelectMenu("UT", "tour_tState", "tour_tState", "stateAbbrName", "Select state...", "USA"); ?> </div>
        <div class="required_line w_sm"> <span class="required">required</span> </div>
      </div>
      <div class="form_line">
        <div class="input_line w_sm">
          <div class="input_title">Zip</div>
          <input id="tour_tZip" name="tour_tZip" maxlength="7" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);">
          <div class="input_info" style="display: none;">
            <div class="info_text">Zip code...</div>
          </div>
        </div>
        <div class="required_line w_sm"> <span class="required">required</span> </div>
      </div>
      <div class="form_line">
        <div class="input_line w_sm">
          <div class="input_title">Beds</div>
          <input id="tour_tBedrooms" name="tour_tBedrooms" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);">
          <div class="input_option"> do not display
            <input id="tour_tHideBeds" name="tour_tHideBeds" type="checkbox" value="1">
          </div>
          <div class="input_info" style="display: none;">
            <div class="info_text">No commas.</div>
          </div>
        </div>
      </div>
      <div class="form_line">
        <div class="input_line w_sm">
          <div class="input_title">Baths</div>
          <input id="tour_tBathrooms" name="tour_tBathrooms" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);">
          <div class="input_option"> do not display
            <input id="tour_tHideBaths" name="tour_tHideBaths" type="checkbox" value="1">
          </div>
          <div class="input_info" style="display: none;">
            <div class="info_text">No commas.</div>
          </div>
        </div>
      </div>
      <div class="form_line">
        <div class="input_line w_sm">
          <div class="input_title">Sq. Ft.</div>
          <input id="tour_tSqFootage" name="tour_tSqFootage" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);">
          <div class="input_option"> do not display
            <input id="tour_tHideSqFoot" name="tour_tHideSqFoot" type="checkbox" value="1">
          </div>
          <div class="input_info" style="display: none;">
            <div class="info_text">No commas.</div>
          </div>
        </div>
      </div>
      <div class="form_line">
        <div class="input_line w_sm">
          <div class="input_title">Price</div>
          <input id="tour_tListPrice" name="tour_tListPrice" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);">
          <div class="input_option"> do not display
            <input id="tour_tHidePrice" name="tour_tHidePrice" type="checkbox" value="1">
          </div>
          <div class="input_info" style="display: none;">
            <div class="info_text">No "$" or ",".</div>
          </div>
        </div>
      </div>
      <div class="form_line">
        <div class="form_direction">Property MLS</div>
      </div>
      <div id="mls_frame">
        <div class="form_line left widthAuto">
          <div class="input_line w_sm">
            <div class="input_title">ID#</div>
            <input onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" name="mls[]" value="">
            <div class="input_info" style="display: none;">
              <div class="info_text">One MLS# per line, please ...</div>
            </div>
          </div>
          <div class="required_line w_sm"> <span class="required">if you do not have MLS# yet leave blank.</span> </div>
        </div>
        <div class="left">&nbsp;&nbsp;&nbsp;&nbsp;</div>
        <div class="form_line left widthAuto">
          <div class="input_line w_mid">
            <div class="input_title">Provider</div>
            <?PHP echo $mls->providerSelectHTML("mls_provider[]") ?>
            <div class="input_option" onclick="addMLSInput();"> + Add Another </div>
          </div>
        </div>
        <div class="clear"></div>
      </div>
      <div id="mls_source" style="display: none;">
        <div>
          <div class="form_line left widthAuto">
            <div class="input_line w_sm">
              <div class="input_title">ID#</div>
              <input onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" name="mls[]" value="">
              <div class="input_info" style="display: none;">
                <div class="info_text">One MLS# per line, please ...</div>
              </div>
            </div>
          </div>
          <div class="left">&nbsp;&nbsp;&nbsp;&nbsp;</div>
          <div class="form_line left widthAuto">
            <div class="input_line w_mid">
              <div class="input_title">Provider</div>
              <?PHP echo $mls->providerSelectHTML("mls_provider[]") ?>
              <div class="input_option" onclick="removeMLSInput(this);"> - Remove </div>
            </div>
          </div>
          <div class="clear"></div>
        </div>
      </div>
      <div class="form_line">
        <div class="form_direction">Property Description</div>
      </div>
      <div class="form_line text_field">
        <div class="input_line w_lg">
          <div class="input_title"></div>
          <textarea id="tour_tDescription" name="tour_tDescription" onkeydown="CharacterCount(this, 2000);" onkeyup="CharacterCount(this, 2000);"></textarea>
        </div>
        <div class="required_line w_lg"> <span id="char_count" class="required">2000 Characters Left</span> </div>
      </div>
      <div class="form_line">
        <div class="form_direction">Property Additional Instructions, Preferred Photographer, Etc.</div>
      </div>
      <div class="form_line text_field">
        <div class="input_line w_lg">
          <div class="input_title"></div>
          <textarea id="tour_tInstructions" name="tour_tInstructions"></textarea>
        </div>
      </div>
      <div class="form_line">
        <div class="form_direction">Property Co-Listing Agent</div>
      </div>
      <div class="form_line">
        <div class="input_line w_mid">
          <div class="input_title"></div>
          <input id="tour_coagent" name="tour_coagent" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" autocomplete="off" class="ac_input">
        </div>
        <div class="required_line w_mid"><span class="required">Leave blank unless applicable.</span> </div>
      </div>
      <input id="tour_tCoUserID" type="hidden" value="">
    </div>
  </div>
</div>

<!-- STEP 3: Label this custom order -->
<div id="step3">
  <h2>Step 3: Label this custom order</h2>
  <br/>
  <div class="form_line">
    <div class="input_line w_lg">
      <div class="input_title">Label</div>
      <input id="name" name="name" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" maxlength="49">
      <div class="input_info" style="display: none;">
        <div class="info_text">The label / title for this custom order.</div>
      </div>
    </div>
    <div class="required_line w_lg"> <span class="required">required</span> </div>
  </div>
</div>

<!-- STEP 4: Create line items -->
<div id="step4">
  <h2>Step 4: Create line items</h2>
  <br/>
  <div class="button_new button_blue button_mid" onclick="getItemLinePopup()">
    <div class="curve curve_left"></div>
    <span class="button_caption">Add</span>
    <div class="curve curve_right"></div>
  </div>
  <div id="line_item_form" style="display:none;">
    <div style="height:400px;overflow:auto;">
      <h1>Add Line Item</h1>
      <div class="form_line">
        <div class="input_line w_lg">
          <div class="input_title">Type</div>
          <select name="item_type">
            <option value="tour">Tour</option>
            <option value="product">Product</option>
          </select>
        </div>
      </div>
      <div class="form_line">
        <div class="input_line w_lg">
          <div class="input_title">Label</div>
          <input id="item_name" name="item_name" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" maxlength="49">
          <div class="input_info" style="display: none;">
            <div class="info_text">Line item label/name.</div>
          </div>
        </div>
      </div>
      <div class="form_line" style="height:35px;">
        <div class="form_direction">Description</div>
      </div>
      <div class="form_line text_field">
        <div class="input_line w_lg">
          <div class="input_title"></div>
          <textarea id="item_description" name="item_description" onkeydown="CharacterCount(this, 2000);" onkeyup="CharacterCount(this, 2000);"></textarea>
        </div>
        <div class="required_line w_lg"> <span id="char_count" class="required">2000 Characters Left</span> </div>
      </div>
      <div class="form_line">
        <div class="input_line w_sm">
          <div class="input_title">Price</div>
          <input id="item_price" name="item_price" style="width:98px;" value="$0.00">
        </div>
        <div class="required_line w_sm"> <span class="required">required</span> </div>
      </div>
      <div class="form_line">
        <div class="input_line w_sm">
          <div class="input_title">Walkthrus</div>
          <input id="item_walkthrus" name="item_walkthrus" style="width:98px;" value="0">
        </div>
      </div>
      <div class="form_line">
        <div class="input_line w_sm">
          <div class="input_title">Videos</div>
          <input id="item_walkthrus" name="item_videos" style="width:98px;" value="0">
        </div>
      </div>
      <div class="form_line">
        <div class="input_line w_sm">
          <div class="input_title">Slideshows</div>
          <input id="item_motion" name="item_motion" style="width:98px;" value="0">
        </div>
      </div>
      <div class="form_line">
        <div class="input_line w_sm">
          <div class="input_title">Photos</div>
          <input id="item_photos" name="item_photos" style="width:98px;" value="0">
        </div>
      </div>
      <div class="form_line">
        <div class="input_line w_sm">
          <div class="input_title">HDR</div>
          <input id="item_hdr_photos" name="item_hdr_photos" style="width:98px;" value="0">
        </div>
      </div>
      <div class="form_line">
        <div class="input_line w_lg">
          <div class="input_title">Multiple?</div>
          <select name="item_oneperorder">
            <option value="0">1 per order</option>
            <option value="1">1+ per order (allow user to select)</option>
          </select>
        </div>
      </div>
      <div class="form_line">
        <div class="input_line w_sm">
          <div class="input_title">Quantity</div>
          <input id="item_qty" name="item_qty" style="width:98px;" value="1">
        </div>
      </div>
      <table>
        <tr>
          <td><div class="button_new button_blue button_mid item_cta" onclick="addLineItem()">
              <div class="curve curve_left"></div>
              <span class="button_caption">Add</span>
              <div class="curve curve_right"></div>
            </div></td>
          <td><div class="button_new button_dgrey button_mid" onclick="hideModal()">
              <div class="curve curve_left"></div>
              <span class="button_caption">Canel</span>
              <div class="curve curve_right"></div>
            </div></td>
        </tr>
      </table>
    </div>
  </div>
  <div id="line_items"> </div>
</div>
<!-- STEP 5: Send Custom Order to the Selected User -->
<div id="step5">
  <h2>Step 5: Send Custom Order to the Selected User</h2>
  <br/>
  <div class="button_new button_blue button_mid" onclick="sendCustomOrder()">
    <div class="curve curve_left"></div>
    <span class="button_caption">Send</span>
    <div class="curve curve_right"></div>
  </div>
  <br/>
  <br/>
  <br/>
  <br/>
</div>
<div class="modal-bg"></div>
<div class="modal">
  <div class="content"> </div>
</div>
</body>
</html>