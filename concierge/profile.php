<?php
/**********************************************************************************************
Document: concierge/profile.php
Creator: Jeff Sylvester & Jacob Edmond Kerr
Date: 09-08-16
Purpose: Spotlight's Concierge profile edit page 
**********************************************************************************************/
//=======================================================================
// Includes
//=======================================================================

	// HEADER TEMPLATE
	// Get rand number to force fresh download of CSS and JS to avoid cache issues
	$homeRandNum = rand(999999,999999999);
	$title = 'Spotlight | Edit Your Concierge Profile';
	$header = '
<script type="text/javascript" src="../repository_inc/jquery.cookie.js"></script><!-- JQuery Cookie Plugin -->
<script src="../repository_inc/jquery.qtip.js" type="text/javascript"></script><!-- QTip -->
<script type="text/javascript" src="../uploader/swfupload.js"></script><!-- SWF Upload JS file -->
<script type="text/javascript" src="../repository_inc/imgareaselect/jquery.imgareaselect.js"></script><!-- Crop Selection JS file -->
<script type="text/javascript" src="../repository_inc/user-cp.js"></script><!-- User CP JS For The Profile Pic -->
<script src="js/profile.js"></script>
<link href="https://fonts.googleapis.com/css?family=Nunito:300" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/profile.css"/>
<link rel="stylesheet" type="text/css" href="../repository_css/jquery.qtip.css" />
<link rel="stylesheet" type="text/css" href="../repository_inc/imgareaselect/imgareaselect-animated.css" />
<link rel="stylesheet" type="text/css" href="../repository_css/user-cp.css" />
';
	require_once('../repository_inc/template-header.php');
	
//=======================================================================
// OBJECTS (USER OBJECT IN HEADER TEMPLATE)
//=======================================================================

	$brokerages = new brokerages($db);
	
//=======================================================================
// Document
//=======================================================================

	$brokerages = new brokerages($db);
	$userTypes = $users->getTypes();
	$brokeragesList = $brokerages->listAll("", false);
	$states = listStates();
	$providers = $db->run("SELECT * FROM mls_providers ORDER BY name");
	
	// Load user info into user object
	$users->loadUser();
		
?>
	<div id="ajaxMessage"></div>
	<div class="concierge-set-up">
		<div class="setup"><strong>Step 1:</strong> Setup your Concierge account</div>
		<div class="concierge-line"><img src="images/concierge-line.png" alt="line" /></div>
		<div class="concierge-logo"><img src="images/concierge-logo.png" alt="logo" /></div>
		<div class="leftbar">
			<!-- <div class="profile-logo"><img src="images/profile.png" alt="profile" width="150px"/></div> -->
			<div class="user-cp"><div class="header"><a href="javascript: changeProfileImgPopup('<?php echo session_id(); ?>')" title="Change Profile Photo"><span></span><img src="<?PHP echo $users->getUserPhoto() . "?a=$randNum"; ?>" id="headerProfilePhoto" width="75" height="75" /></a></div></div>
			<div class="concierge-line2"><img src="images/concierge-line2.png" alt="line" height="1450px"/></div>
		</div>
		<div class="form">
			<div id="userInfoMsg" style="margin-left:130px;"></div>
			<div class="my-info">My Info</div>
			<form name="userInfoForm" id="userInfoForm" action="" method="post" style="margin-top:25px;">
				<input type="hidden" name="userID" id="userID" value="<?PHP echo $users->userID ?>" />
				<div class="firstName">
					<label for="firstName">first name:</label>
					<input type="text" id="firstName" name="firstName" value="<?PHP echo $users->firstName ?>" />
				</div>
				<div  class="lastName">
					<label for="lastName">last name:</label>
					<input type="text" id="lastName" name="lastName" value="<?PHP echo $users->lastName ?>" />
				</div>
				<div class="username">
					<label for="username">username:</label>
					<span><?PHP echo $users->username ?></span>
				</div>
				<div class="password">
					<label for="password">password:</label>
					<input type="password" id="password" name="password" autocomplete="off" value='<?php echo $users->password;?>' />
				</div>
				<div class="verify-password">
					<label for="verify-password">verify-password:</label>
					<input type="password" id="password2" name="password2"  autocomplete="off" value='<?php echo $users->password;?>' />
				</div>
				<div class="brokerage">
					<label for="brokerage">brokerage:</label>
					<select id="BrokerageID" name="BrokerageID" onkeyup="setFocusOnEnter(event, 'otherBrokerage')">
					<option value="" selected >Select one...</option>
					<?PHP
						foreach($brokeragesList as $row => $column){
							$desc = '';
							if(isset($column['brokerageDesc'])&&!empty($column['brokerageDesc'])){
								$desc = ' - '.$column['brokerageDesc'];
							}
					?>
					<option value="<?PHP echo $column['brokerageID'] ?>" <?PHP echo ($users->BrokerageID==$column['brokerageID'])?'SELECTED="SELECTED"':''?>><?PHP echo $column['brokerageName'].$desc; ?></option>
					<?PHP
						}
					?>
					<option value="0" <?PHP echo ($users->BrokerageID==0)?'SELECTED="SELECTED"':''?>>None/Other (Enter below)</option>
				  </select>
				</div>
				<div class="other">
					<label for="otherBrokerage">If other:</label>
					<input id="otherBrokerage" name="otherBrokerage" value="<?PHP echo $users->otherBrokerage ?>" />
				</div>
				<div class="email">
					<label for="email">email:</label>
					<input type="text" name="email" id="email" value="<?PHP echo $users->email ?>" />
				</div>
				<div class="address">
					<label for="address">address:</label>
					<input type="text" name="address" id="address" value="<?PHP echo $users->address ?>" />
				</div>
				<div class="city">
					<label for="city">city:</label>
					<input type="text" name="city" id="city" value="<?PHP echo $users->city ?>" />
				</div>
				<div class="state">
					<label for="state">state:</label>
					<select id="state" name="state" onkeyup="setFocusOnEnter(event, 'zipCode')">
						<option value="" selected >Select one...</option>
					<?PHP
						foreach($states as $row => $column){
					?>
						<option value="<?PHP echo $column['stateAbbrName'] ?>" <?PHP echo (strtoupper($users->state)==strtoupper($column['stateAbbrName']))?'SELECTED="SELECTED"':'' ?>><?PHP echo $column['stateFullName'] ?></option>
					<?PHP
						}
					?>
				  	</select>
				</div>
				<div class="zip">
					<label for="zipCode">zip:</label>
					<input type="text" id="zipCode" name="zipCode" value="<?PHP echo $users->zipCode ?>" />
				</div>
				<div class="cell-phone">
					<label for="phone">cell phone:</label>
					<input type="text" id="phone" name="phone" value="<?PHP echo $users->phone ?>" />
				</div>
				<div class="cell-provider">
					<label for="phonecarrier">cell-provider:</label>
					<select id="phonecarrier" name="phonecarrier" onkeyup="setFocusOnEnter(event, 'phone2')">
						<option value="" selected >Select one...</option>
					<?PHP
						foreach($phoneProviders as $provider => $providerLabel){
					?>
						<option value="<?PHP echo $provider ?>" <?PHP echo ( @strpos(strtoupper($provider), strtoupper(substr($users->phonecarrier, 0, -2)))===false)?'':'SELECTED="SELECTED"'?>><?PHP echo $providerLabel ?></option>
					<?PHP
						}
					?>
				  	</select>
				</div>
				<div class="alt-phone">
					<label for="phone2">alt. phone:</label>
					<input type="text" id="phone2" name="phone2" value="<?PHP echo $users->phone2 ?>" />
				</div>
				<div class="fax">
					<label for="fax">fax:</label>
					<input type="text" id="fax" name="fax" value="<?PHP echo $users->fax ?>" />
				</div>
				<div class="website">
					<label for="uri">website:</label>
					<input type="text" id="uri" name="uri" value="<?PHP echo $users->uri ?>" />
				</div>
			<div class="concierge-line3"> <img src="images/concierge-line3.png" /> </div>
			<div class="about-me">About Me</div>
				<div class="bio">
					<label for="about">bio:</label>
					<textarea id="about" name="about" maxlength="4000"><?PHP echo $users->about ?></textarea>
				</div>
			<div class="concierge-line3"> <img src="images/concierge-line3.png" /> </div>
			<div class="assistant">Assistant Info</div>
				<div  class="assistant-firstname">
					<label for="assistName">name:</label>
					<input type="text" id="assistName" name="assistName" value="<?PHP echo $users->assistName ?>" />
				</div>
				<!--<div  class="assistant-lastname">
					<label for="assistant-lastname">last name:</label>
					<input type="text" name="assistant-lastname" />
				</div>-->
				<!--<div class="assistant-email">
					<label for="assistant-email">email:</label>
					<input type="text" name="assistant-email" />
				</div>-->
				<div  class="assistant-phone">
					<label for="assistPhone">phone:</label>
					<input type="text" id="assistPhone" name="assistPhone" value="<?PHP echo $users->assistPhone ?>" />
				</div>
				<!--<div  class="assistant-alt-phone">
					<label for="assistant-alt-phone">alt. phone:</label>
					<input type="text" name="assistant-alt-phone" />
				</div>-->
			<div class="concierge-line3"> <img src="images/concierge-line3.png" /> </div>
			<div class="mls">MLS Info</div>
			<div class="clear"></div>
			<div id="mls_frame">
			<?PHP
            // Pull saved MLS ID's for this user
			$mls = new mls();
			$mlsIDs = $mls->getUserIDs($users->userID);
            $first = true;
            if(count($mlsIDs)>0&&!empty($mlsIDs)){
            	foreach($mlsIDs as $row => $column){
                	if($first){
                    	$first = false;
        	?>		
				<div>
					<div  class="agent-id">
						<label for="mls[]">MLS agent ID#:</label>
						<input type="text" name="mls[]" value="<?PHP echo $column['mlsID'] ?>" />
					</div>
					<div  class="mls-provider">
						<label for="mls_provider[]">MLS provider:</label>
						<?PHP echo $mls->providerSelectHTML("mls_provider[]", $column['mlsProvider'], "", true); ?>
						<div class="input_option" onclick="addMLSInput();" > + Add Another </div>
					</div>
				</div>
			<?PHP
            		}else{
        	?>
				<div>
					<div class="agent-id">
						<label for="mls[]">MLS agent ID#:</label>
						<input type="text" name="mls[]" value="<?PHP echo $column['mlsID'] ?>" />
					</div>
					<div class="mls-provider">
						<label for="mls_provider[]">MLS provider:</label>
						<?PHP echo $mls->providerSelectHTML("mls_provider[]", $column['mlsProvider'], "", true); ?>
						<div class="input_option" onclick="removeMLSInput(this);" > - Remove </div>
					</div>
				</div>
        <?PHP
                	}
            	}
            }else{
        ?>
				<div>
					<div class="agent-id">
						<label for="mls[]">MLS agent ID#:</label>
						<input type="text" name="mls[]" value="" />
					</div>
					<div class="mls-provider">
						<label for="mls_provider[]">MLS provider:</label>
						<?PHP echo $mls->providerSelectHTML("mls_provider[]","","",true)?>
						<div class="input_option" onclick="addMLSInput();" > + Add Another </div>
					</div>
				</div>
		<?PHP
			}
		?>
				<div id="mls_source" style="display: none;" >
					<div>
						<div class="agent-id">
							<label for="mls[]">MLS agent ID#:</label>
							<input type="text" name="mls[]" value="" />
						</div>
						<div class="mls-provider">
							<label for="mls_provider[]">MLS provider:</label>
							<?PHP echo $mls->providerSelectHTML("mls_provider[]","","",true)?>
							<div class="input_option" onclick="removeMLSInput(this);" > - Remove </div>
						</div>
					</div>
				</div>
			</div>
			</form>
			<div class="save-exit"><a href="javascript:validateUserInfo(false)"><img src="images/save-exit.png" alt="save-exit" width="150px"/></a></div>
			<div class="next-step"><a href="javascript:validateUserInfo(true)"><img src="images/next-step.png" alt="next-step" width="150px"/></a></div>
		</div>
	</div>
<?PHP
	// FOOTER TEMPLATE
	require_once('../repository_inc/template-footer.php');
?>
