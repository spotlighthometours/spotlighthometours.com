<form action="#" method="post" class="adm-community-form" id="adm-community-form">
	<table>
  	<tr>
  		<td class="name-td">Community Name</td>
  		<td>
			<input type="text" name="comm_name" value="<?php echo issetVar($currentSetting['community_name'])?>" >
			 <!-- <select name="comm_name" id="comm_name"></select> -->
		</td>
  	</tr>
  	<tr>
  		<td style="width:30%" class="name-td">State</td>
  		<td class="select-state">
  			<select name="state">
  			<?php 
  			$currentState = issetVar($currentSetting['state']);
  			foreach($states as $state){
  				$selectedState = ($currentState == $state['stateAbbrName'])?"selected='selected'":'';
  				?>
  			
  				<option value="<?php echo $state['stateAbbrName']; ?>" <?php echo $selectedState;?> > <?php echo $state['stateFullName']; ?></option>
  				
  				<?php } ?>
  			</select>
  		</td>
  	</tr>
  	<tr>
  		<td colspan="2" style="padding:0;margin:0;" id="mlsinfo">
  			<?php echo getMLSFrmHTML($mlsProviders, 0);?>
  		</td>
  	</tr>

  	
  	<tr>
  		<td class="name-td">Upload Video</td>
  		<td>
  			<table id="vdotblcontainer" style="width:100%">
  				
					<tr style="border:0;">
						<td style="width:70%;border:0;">
							<input type="file" name="videoupload[]" value="<?php echo issetVar($currentSetting['video'])?>" id="videoupload" multiple="multiple"/>
							<p class="exam-vid">(e.g: .mp4, .ogv, .mov)</p>
						</td>
						<td style="width:30%;border:0">
							<img id="loading-image3" style="display: none" src="../images/ajax-loader.gif">
							<input type="button" name="btn_vdo" value="video upload" id="btn_vdo">
						</td>
					</tr>
  					
  			</table>
  			
  			<span class="error"><?php echo $erorr; ?></span>
  			<div class="vdoMsg"></div>
  			<input type="text" name="vdoupload" style="border: 0;height: 0;width: 0;" value="<?php echo issetVar($currentSetting['video'])?>" id="vdoupload">
  		</td>
  	</tr>
  	<tr>
  		<td class="name-td">Upload Photo</td>
  		<td>

  			<table id="imgtblcontainer" style="width:100%">
  				
  					<tr style="border:0;">
							<td style="width:70%;border:0;">
								<input type="file" name="imageupload" value="imageupload" id="photoupload">
								<p class="exam-vid">(e.g: .jpg, .jpeg, .png)</p>
							</td>
							<td style="width:30%;border:0">
								<img id="loading-image2" style="display: none" src="../images/ajax-loader.gif">
								<input type="button" name="btn_mg" value="image upload" id="btn_img" >
							</td>
  					</tr>
  					
  			</table>
  			
  			<input type="text" name="imgupload" style="border: 0;height: 0;width: 0;" value="<?php echo issetVar($currentSetting['photo'])?>" id="imgupload">

  			<div class="imgMsg"></div>

  		</td>
  	</tr>
  	<tr>
  		<td class="sub-up">
  			<img id="loading-image1" style="display: none" src="../images/ajax-loader.gif">
  			<input type="submit"id="sub_btn" value="Save"></td>
  	</tr>
  	
	</table>

	<?php
		if($mode == "edit"){
			echo '<input type="hidden" name="uid" value="' .issetVar($currentSetting['id']).'">';
		}
		?>
		<div id="success_message" class="ajax_response" style="float:left; display:none;"><p>Community successfully Update!</p></div>
</form>