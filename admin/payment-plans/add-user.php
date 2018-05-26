<?php
/**********************************************************************************************
Document: add-user.php
Creator: Jacob Edmond Kerr
Date: 06-11-14
Purpose: Outputs user selection HTML (for Ajax request)
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================

	// Include appplication's global configuration
	require_once('../../repository_inc/classes/inc.global.php');
	showErrors();
	
//=======================================================================
// Objects
//=======================================================================

	// Create instances of needed object
	$brokerages = new brokerages();
	
//=======================================================================
// Document
//=======================================================================

	// Pull needed information
	$brokeragesList = $brokerages->listAll();
?>
<h1>Add User</h1>
<div class="add-user">
    <div class="form_line" >
        <div class="input_line w_lg" >
            <div class="input_title" >Brokerage</div>
            <select name="brokerageID">
				<option value="select" selected >Select one...</option>
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
<div class="grey-divider" style="margin-bottom:10px;"></div>
<br/>
<table cellpadding="5">
	<tr>
		<td>
			<div class="button_new button_blue button_mid" onclick="addUser()">
				<div class="curve curve_left" ></div>
				<span class="button_caption" >Add</span>
				<div class="curve curve_right" ></div>
			</div>
		</td>
		<td>
			<div class="button_new button_dgrey button_mid" onclick="hideModal()">
				<div class="curve curve_left" ></div>
				<span class="button_caption" >Cancel</span>
				<div class="curve curve_right" ></div>
			</div>
		</td>
	</tr>
</table>