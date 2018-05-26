<?php
	// pr($currentSetting);
?>
<h3>Edit Community</h3>
<form action="#" method="post" class="adm-community-form" id="adm-community-form">
	<table>
  	<tr>
  		<td class="name-td">Community Name</td>
  		<td><input type="text" name="comm_name" value="<?php echo issetVar($currentSetting['community_name'])?>" ></td>
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
  		<td colspan="2" style="padding:0;margin:0;">
  			<table id="mlsinfo" style="width:100%; margin: 0;">
  				<tr>
			  		<td style="width:30%" class="name-td">MLS Provides</td>
			  		<td>
			  			<select id="mls_name" name="mls_name[]">
			  				<?php
			  				$currentMLSProvider = issetVar($currentSetting['mls_name']);
			  				
			  				foreach($mlsProviders as $mlsProvider){
			  					$selectedMLSProvider = ($currentMLSProvider == $mlsProvider['id'])?"selected='selected'":'';
			  					?>
			  				
			  					<option value="<?php echo $mlsProvider['id']; ?>" <?php echo $selectedMLSProvider;?> > <?php echo $mlsProvider['name']; ?></option>
			  				
			  				<?php } ?>
			  			</select>
			  		</td>
			  	</tr>
			  	<tr>
			  		<td class="name-td">MLS City Name</td>
			  		<td><input type="text" name="mls_city_name[]" value="<?php echo issetVar($currentSetting['mls_city_name'])?>" ></td>
			  	</tr>
  			</table>
  		</td>
  	</tr>

  	
  	<tr>
  		<td class="name-td">Upload Video</td>
  		<td>
  			<table id="vdotblcontainer" style="width:100%">
  				<?php if($mode == "edit" && trim(issetVar($currentSetting['video'])) != '') { ?>
  					<tr style="border:0;">
							<td style="width:70%;border:0;">
								<video id="pre_<?php echo issetVar($currentSetting['id'])?>" style="width:100px;height:60px;" >
									<source class="vdo_thumb" src= "<?php echo issetVar($currentSetting['video'])?>" >
								</video>
							</td>
							<td style="width:30%;border:0">
								<img id="loading-image3" style="display: none" src="../images/ajax-loader.gif">
								<input type="button" name="btn_vdo_del" value="remove" id="btn_vdo_del">
							</td>
  					</tr>
  					<tr style="border:0;">
							<td style="width:70%;border:0;">
								<input type="file" name="videoupload[]" value="<?php echo issetVar($currentSetting['video'])?>" id="videoupload" multiple="multiple"/>
								<p class="exam-vid">(e.g: .mp4, .ogv, .mov)</p>
							</td>
							<td style="width:30%;border:0">
								<img id="loading-image3" style="display: none" src="../images/ajax-loader.gif">
								<input type="button" name="btn_vdo" value="replace upload" id="btn_vdo">
							</td>
  					</tr>
  				<?php }else{ ?>
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
  				<?php }	?>
  					
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
  				<?php if($mode == "edit"  && trim(issetVar($currentSetting['photo'])) != '') { ?>
  					<tr style="border:0;">
							<td style="width:70%;border:0;">
								<a class='html5lightbox' href='<?php echo issetVar($currentSetting['photo'])?>'>
			  			    <img class='img_thumb' src='<?php echo issetVar($currentSetting['photo'])?>' style="width:100px;height:60px;"  />
			  			    </a>
							</td>
							<td style="width:30%;border:0">
								<img id="loading-image3" style="display: none" src="../images/ajax-loader.gif">
								<input type="button" name="btn_img_del" value="remove" id="btn_img_del">
							</td>
  					</tr>
  					<tr style="border:0;">
							<td style="width:70%;border:0;">
								<input type="file" name="imageupload" value="imageupload" id="photoupload">
								<p class="exam-vid">(e.g: .jpg, .jpeg, .png)</p>
							</td>
							<td style="width:30%;border:0">
								<img id="loading-image2" style="display: none" src="../images/ajax-loader.gif">
								<input type="button" name="btn_mg" value="Replace Image" id="btn_img">
							</td>
  					</tr>
  				<?php }else{	?>
  					<tr style="border:0;">
							<td style="width:70%;border:0;">
								<input type="file" name="imageupload" value="imageupload" id="photoupload">
								<p class="exam-vid">(e.g: .jpg, .jpeg, .png)</p>
							</td>
							<td style="width:30%;border:0">
								<img id="loading-image2" style="display: none" src="../images/ajax-loader.gif">
								<input type="button" name="btn_mg" value="image upload" id="btn_img">
							</td>
  					</tr>
  				<?php }	?>
  					
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