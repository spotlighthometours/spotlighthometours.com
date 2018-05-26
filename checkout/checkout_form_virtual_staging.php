			<div id="virtualstaging" class="hidden" style="height: 100%;" >
				<?php
					$first = true;
					$query = '
						SELECT productID FROM products WHERE productName = "Virtual Staging" ORDER BY productID 
					';
					$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query being run: " . str_replace(Chr(10), "<br>", $query));
					while($result = mysql_fetch_array($r)){
						if ($first) {
							$first = false;
							echo '
				<input id="vs_single" type="hidden" value="' . $result['productID'] . '" />
							';
						} else {
							echo '
				<input id="vs_multi" type="hidden" value="' . $result['productID'] . '" />
							';
						}
							
					}
				?>
                <div class="form_cap form_top_margin" >
					<img class="form_corner left" src="../repository_images/checkout/form-tl.png" />
					<div class="form_cap_spacer form_cap_spacer_top" /></div>
					<img class="form_corner right" src="../repository_images/checkout/form-tr.png" />
				</div>
				<div id="vsform" class="form_frame" >
					<!--- Title Bar --->
						<div class="form_title" >
							<div class="titletext" >Virtual Staging</div>
								
							<div class="button right buttontop" onclick="FinalCheck()" >
								<div class="buttoncap greenbuttonleft" ></div>
								<div class="buttontext green" >Done</div>
								<div class="buttoncap greenbuttonright" ></div>
							</div>
							
							<div id="virtualstagingtotal" class="checkoutamt" >$0.00</div>
							<div class="checkouttext" >Your Total: </div>
						</div>	
					
					<!--- Instructions --->
					<div class="form_line" >Please fill in the room  you would like virtually staged and select your design set below.</div>
					
					
	<!--- ------------------------------------------------------------------------------------------------------------------------- ----
	----- SUBFORM
	----- ------------------------------------------------------------------------------------------------------------------------- --->
					<!--- Selection --->
					<div id="vs_subforms" ></div>
	<!--- ------------------------------------------------------------------------------------------------------------------------- ----
	----- END SUBFORM
	----- ------------------------------------------------------------------------------------------------------------------------- --->
					
					<div class="button room_button" onclick="GetAnother();" >
						<div class="buttoncap bluebuttonleft" ></div>
						<div class="buttontext blue room_button_text" >Add Another Room</div>
						<div class="buttoncap bluebuttonright" ></div>
					</div>
				</div>
				<div class="form_cap" >
					<img class="form_corner left" src="../repository_images/checkout/form-bl.png" />
					<div class="form_cap_spacer form_cap_spacer_bottom" ></div>
					<img class="form_corner right" src="../repository_images/checkout/form-br.png" />
				</div>
			</div>