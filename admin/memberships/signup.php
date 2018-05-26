<?php
/*
 * Admin: Memberships Signup (For Admin ONLY!)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
$errorHandlerCalled = true;
showErrors();
clearCache();

// Create instances of needed objects
$memberships = new memberships();
$listOfMemberships = $memberships->getMemberships();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Membership Signup</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous"><!-- Bootstrap CSS -->
<link rel="stylesheet" href="includes/darklytheme.css"><!-- Bootstrap Darkly Theme CSS -->
<style type="text/css" media="screen">
	@import "includes/signup.css"; /* Membership Signup CSS */
</style>
</head>
<body>
	<div class="container">
		<br/>
		<h1>Signup For Concierge Today!</h1>
		<p class="lead">Membership Signup Form</p>
		<form id="membershipsForm">
		  <fieldset>
			<!-- <legend>Legend</legend> -->
			<div class="row">
				<div class="form-group col-6">
					  <label class="col-form-label" for="name">Name</label>
					  <input type="text" class="form-control form-control-danger" id="fullname" placeholder="First (space) Last Name (letters only no other characters please)" data-vindicate="required|format:alpha">
					  <small class="form-control-feedback"></small>
				</div>
				<div class="form-group col-6">
					  <label class="col-form-label" for="brokerage">Brokerage</label>
					  <input type="text" class="form-control" id="brokerage" placeholder="Brokerage Name - Office">
				</div>
            </div>
            <div class="row">
				<div class="form-group col-6">
					<label class="col-form-label" for="email">Email address</label>
			  		<input type="email" class="form-control" id="email" placeholder="Enter email" data-vindicate="required|format:email|active">
			  		<small class="form-control-feedback"></small>
			  		<small id="emailHelp" class="form-text text-muted">This will be used to load the user if they exist (creates new user otherwise)</small>
				</div>
				<div class="form-group col-6">
					<label class="col-form-label" for="phone">Phone</label>
					<input type="text" class="form-control" id="phone" placeholder="Phone Number">
				</div>
            </div>
            <div class="row">
				<div class="form-group col-6">
					  <label class="col-form-label" for="mlsprovider">MLS Provider</label>
					  <input type="text" class="form-control" id="mlsprovider" placeholder="MLS Provider Name">
					  <small id="emailHelp" class="form-text text-muted">Please add "office" to the end if you're entering MLS Office ID in the next field (brokerage MLS ID)</small>
				</div>
				<div class="form-group col-6">
					  <label class="col-form-label" for="mlsid">MLS ID</label>
					  <input type="text" class="form-control" id="mlsid" placeholder="MLS Agent/Office ID">
					  <small id="emailHelp" class="form-text text-muted">This can be the Agent's MLS ID or the agent's brokerage/office MLS ID</small>
				</div>
            </div>
			<div class="form-group">
			  	<label class="col-form-label" for="address">Address</label>
				<input type="text" class="form-control" id="address" placeholder="Street Address" data-vindicate="required|format:alphanumeric">
			</div>
			<div class="row">
				<div class="form-group col-4">
					  <label class="col-form-label" for="city">City</label>
					  <input type="text" class="form-control" id="city"  placeholder="City" data-vindicate="required|format:alpha">
				</div>
				<div class="form-group col-4">
					  <label class="col-form-label" for="state">State</label>
					  <select id="state" name="state" class="form-control">
						<option value="" selected="">Select one...</option>
						<option value="AL">Alabama</option>
						<option value="AK">Alaska</option>
						<option value="AB">Alberta</option>
						<option value="AI">Anguilla</option>
						<option value="AZ">Arizona</option>
						<option value="AR">Arkansas</option>
						<option value="BC">British Columbia</option>
						<option value="CA">California</option>
						<option value="CO">Colorado</option>
						<option value="CT">Connecticut</option>
						<option value="DE">Delaware</option>
						<option value="FL">Florida</option>
						<option value="GA">Georgia</option>
						<option value="HI">Hawaii</option>
						<option value="ID">Idaho</option>
						<option value="IL" selected>Illinois</option>
						<option value="IN">Indiana</option>
						<option value="IA">Iowa</option>
						<option value="KS">Kansas</option>
						<option value="KY">Kentucky</option>
						<option value="LA">Louisiana</option>
						<option value="ME">Maine</option>
						<option value="MB">Manitoba</option>
						<option value="MD">Maryland</option>
						<option value="MA">Massachusetts</option>
						<option value="MI">Michigan</option>
						<option value="MN">Minnesota</option>
						<option value="MS">Mississippi</option>
						<option value="MO">Missouri</option>
						<option value="MT">Montana</option>
						<option value="NE">Nebraska</option>
						<option value="NV">Nevada</option>
						<option value="NB">New Brunswick</option>
						<option value="NH">New Hampshire</option>
						<option value="NJ">New Jersey</option>
						<option value="NM">New Mexico</option>
						<option value="NY">New York</option>
						<option value="NL">Newfoundland and Labrador</option>
						<option value="NC">North Carolina</option>
						<option value="ND">North Dakota</option>
						<option value="NT">Northwest Territories</option>
						<option value="NS">Nova Scotia</option>
						<option value="NU">Nunavut</option>
						<option value="OH">Ohio</option>
						<option value="OK">Oklahoma</option>
						<option value="ON">Ontario</option>
						<option value="OR">Oregon</option>
						<option value="PA">Pennsylvania</option>
						<option value="PE">Prince Edward Island</option>
						<option value="QC">Quebec</option>
						<option value="RI">Rhode Island</option>
						<option value="SK">Saskatchewan</option>
						<option value="SM">Sint Maarten</option>
						<option value="SC">South Carolina</option>
						<option value="SD">South Dakota</option>
						<option value="TN">Tennessee</option>
						<option value="TX">Texas</option>
						<option value="UT">Utah</option>
						<option value="VT">Vermont</option>
						<option value="VA">Virginia</option>
						<option value="WA">Washington</option>
						<option value="DC">Washington D.C.</option>
						<option value="WV">West Virginia</option>
						<option value="WI">Wisconsin</option>
						<option value="WY">Wyoming</option>
						<option value="YT">Yukon</option>
					  </select>
				</div>
           		<div class="form-group col-4">
					  <label class="col-form-label" for="zip">Zip</label>
					  <input type="text" class="form-control" id="zip" placeholder="Zip Code" data-vindicate="required|format:alphanumeric">
					  <small class="form-control-feedback"></small>
				</div>
            </div>
            <div class="row">
				<div class="form-group col-6">
                  <label class="col-form-label">Card Number</label>
                  <input type="text" class="form-control" id="cardnumber" placeholder="Number on Front of Card" data-vindicate="required|format:numeric">
                  <small class="form-control-feedback"></small>
                </div>
				<div class="col-3">
					<label class="col-form-label" for="expmo">Exp Month</label>
					<select class="form-control" id="cardmonth">
						<option>01</option>
						<option>02</option>
						<option>03</option>
						<option>04</option>
						<option>05</option>
						<option>06</option>
						<option>07</option>
						<option>08</option>
						<option>09</option>
						<option>10</option>
						<option>11</option>
						<option>12</option>
					</select>
				</div>
				<div class="col-3">
					<label class="col-form-label" for="expyr">Exp Year</label>
					<select class="form-control" id="cardyear">
						<option>17</option>
						<option>18</option>
						<option>19</option>
						<option>20</option>
						<option>21</option>
						<option>22</option>
						<option>23</option>
						<option>24</option>
						<option>25</option>
						<option>26</option>
						<option>27</option>
						<option>28</option>
					</select>
				</div>
            </div>
            <p>&nbsp;</p>
            <h2 id="choosePlan">Choose a Concierge Plan</h2>
            <lead>Note: If you choose a membership that comes with other memberships do not worry about checking those items. The system will sign the user up for all of the memberships that come with it's parent membership. For instance most of the concierge memberships if not all have child memberships. When selecting those memberships the user will be signed up for all of it's child memberships as well. I have preloaded all of the child memberships for each concierge membership below and their prices from the database. Please do not check all of the child memberships if you want to sign a user up for Bronze Concierge for instance. Just click Bronze Concierge and the system will give the user all of it's child memberships by default.</lead><br/>
            <br/>
            <div class="row memberships">
            	<div class="col-6">
					<div class="list-group list-group-root well">
					  <div class="list-group-item">
					  	<div class="form-check">
							<label class="form-check-label">
							  <h5 class="row">
							  	<div class="col-7">
									<input type="checkbox" class="form-check-input">
									<select>
									<?PHP
										foreach($listOfMemberships as $membershipIndex => $membership){
									?>
										<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="7")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
									<?PHP
										}
									?>
									</select>
								</div>
								<div class="col-5">
									<div class="input-group">
										<span class="input-group-addon">$</span>
										<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(7) ?>">
										<span class="input-group-addon">mo</span>
									</div>
								</div>
							  </h5>
							</label>
						  </div>
					  </div>
					  <div class="list-group">
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="21")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(21) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="13")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(13) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="14")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(14) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
					  </div>
					  <div class="list-group-item">
					  	<div class="form-check">
							<label class="form-check-label">
							  <h5 class="row">
							  	<div class="col-7">
									<input type="checkbox" class="form-check-input">
									<select>
									<?PHP
										foreach($listOfMemberships as $membershipIndex => $membership){
									?>
										<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="6")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
									<?PHP
										}
									?>
									</select>
								</div>
								<div class="col-5">
									<div class="input-group">
										<span class="input-group-addon">$</span>
										<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(6) ?>">
										<span class="input-group-addon">mo</span>
									</div>
								</div>
							  </h5>
							</label>
						  </div>
					  </div>
					  <div class="list-group">
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="2")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(2) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="3")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(3) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="13")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(13) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="14")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(14) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="15")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(15) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="16")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(16) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
					  </div>
					</div>
				</div>
				<div class="col-6">
					<div class="list-group list-group-root well">
					  <div class="list-group-item">
					  	<div class="form-check">
							<label class="form-check-label">
							  <h5 class="row">
							  	<div class="col-7">
									<input type="checkbox" class="form-check-input">
									<select>
									<?PHP
										foreach($listOfMemberships as $membershipIndex => $membership){
									?>
										<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="5")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
									<?PHP
										}
									?>
									</select>
								</div>
								<div class="col-5">
									<div class="input-group">
										<span class="input-group-addon">$</span>
										<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(5) ?>">
										<span class="input-group-addon">mo</span>
									</div>
								</div>
							  </h5>
							</label>
						  </div>
					  </div>
					  <div class="list-group">
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="2")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(2) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="3")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(3) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="8")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(8) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="13")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(13) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="14")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(14) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="15")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(15) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="16")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(16) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="17")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(17) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="18")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(18) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="22")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(22) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
					  </div>
					  <div class="list-group-item">
					  	<div class="form-check">
							<label class="form-check-label">
							  <h5 class="row">
							  	<div class="col-7">
									<input type="checkbox" class="form-check-input">
									<select>
									<?PHP
										foreach($listOfMemberships as $membershipIndex => $membership){
									?>
										<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="4")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
									<?PHP
										}
									?>
									</select>
								</div>
								<div class="col-5">
									<div class="input-group">
										<span class="input-group-addon">$</span>
										<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(4) ?>">
										<span class="input-group-addon">mo</span>
									</div>
								</div>
							  </h5>
							</label>
						  </div>
					  </div>
					  <div class="list-group">
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="2")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(2) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="3")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(3) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="8")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(8) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="13")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(13) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="14")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(14) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="15")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(15) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="16")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(16) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="17")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(17) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="18")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(18) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="19")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(19) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="20")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(20) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
						<div class="list-group-item">
							<div class="form-check">
								<label class="form-check-label">
									<div class="row">
										<div class="col-7">
											<input type="checkbox" class="form-check-input">
											<select>
											<?PHP
												foreach($listOfMemberships as $membershipIndex => $membership){
											?>
												<option value="<?PHP echo $membership['id'] ?>" <?PHP echo ($membership['id']=="22")?'selected="selected"':'';?>><?PHP echo $membership['name'] ?></option>
											<?PHP
												}
											?>
											</select>
										</div>
										<div class="col-5">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="number" class="form-control input-sm" placeholder="Price" value="<?PHP echo $memberships->getPrice(22) ?>">
												<span class="input-group-addon">mo</span>
											</div>
										</div>
									</div>
								</label>
						  </div>
						</div>
					  </div>
					</div>
				</div>
			</div>
			<p>&nbsp;</p>
			<ul class="list-group totals">
			  <li class="list-group-item justify-content-between">
				Memberships
				<span class="badge badge-default badge-pill numb-memb">0</span>
			  </li>
			  <li class="list-group-item justify-content-between">
				Total
				<span class="badge badge-default badge-pill monthly">$0.00mo</span>
			  </li>
			</ul>
			<br/>
			<button type="button" class="btn btn-primary table-responsive right" style="cursor:pointer;" onClick="processOrder()">Submit</button>
			<small class="text-muted" style="text-align:center">Do not worry, there is a verification popup before anything is processed showing all the memberships and prices you have selected for this user and an option to proceed or to cancel so you can go back and edit this form more before processing the payment etc. Once the payment has been processed the user will be created in Spotlight system with all active memberships and also in infusion soft with the membership tags attached. You will get a response with the Spotlight User ID and the infusion soft User ID. There will also be a transaction ID which can be used to track all information on the order if needed.</small>
			<br/>
			<p>&nbsp;</p>
		  </fieldset>
		</form>
	</div>
	<div id="ajaxMessage"></div>
	<script src="../../repository_inc/jquery-1.11.2.min.js" type="text/javascript"></script><!-- jQuery -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
	<script src='includes/vindicate.js'></script>
	<!-- MEMBERSHIP SIGNUP JS CONTROL FILES -->
	<script src='includes/signup.js'></script>
	<script>

	</script>
</body>
</html>