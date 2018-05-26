var vsformname = 'virtualstaging';

var virtualstaging_packages = new Array();

var sliderWidth = new Array();
var sliderPos = new Array();
var currentPos = new Array();

var isClicked = false;
var selectedSlider = false;
var xPos = 0;

var vsFrameCount = 0;

var HTTP = false; //Ajax http request object
//Create the http request object
if (window.XMLHttpRequest) {
	HTTP = new XMLHttpRequest();
} else if (window.ActiveXObject) {
	HTTP = new ActiveXObject("Microsoft.XMLHTTP");
}

function GetAnother() {
	try {
		if (HTTP) {
			var url = "checkout_subform_virtual_staging.php";
			var params = "index=" + vsFrameCount + "&brokerid=" + brokerid + "&zip=" + document.getElementById("zip").value + "&city=" + document.getElementById("city").value;
			
			if (AllFormsCompleted()) {
				HTTP.open("POST", url, true);
				HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				HTTP.setRequestHeader("Content-length", params.length);
				HTTP.setRequestHeader("Connection", "close");

				HTTP.onreadystatechange = function() { 
					if (HTTP.readyState == 4 && HTTP.status == 200) {
						document.getElementById('vs_subforms').innerHTML += HTTP.responseText;
						SelectForm(vsFrameCount);
						vsFrameCount++;
					}
				}
				HTTP.send(params);
			} else {
				window.alert("Please finish the current room.");
			}
		}
	} catch(err) {
		window.alert('GetAnother: ' + err);
	}

}

function RemoveForm(group) {
	try {
		if (confirm("Delete?! \nAre you sure?")) {
			document.getElementById(group + "-subform-mini").className = "hidden";
			document.getElementById(group + "-subform").className = "hidden";
		}
		VsCalculateCost();
	} catch(err) {
		window.alert('RemoveForm: ' + err);
	}
}

function SwitchClass(itemID, searchFor, replaceWith) {
	try {
		var item = document.getElementById(itemID);
		var classes = item.className.split(" ");
		var newclasses = '';
		for (var i=0;i < classes.length;i++) {
			if (classes[i] == searchFor) {
				classes[i] = replaceWith;
			} 
			if (i > 0) {
				newclasses += ' ';
			} 
			newclasses += classes[i];
		}
		item.className = newclasses;
	} catch(err) {
		window.alert('SwitchClass: ' + err);
	}
}

function MemberOfClass(itemID, className) {
	try {
		var item = document.getElementById(itemID);
		var classes = item.className.split(" ");
		var found = false;
		for (var i=0;i < classes.length;i++) {
			if (classes[i] == className) {
				found = true;
			} 
		}
		return found;
	} catch(err) {
		window.alert('MemberOfClass: ' + err);
	}
}


function SelectRoom(group, selectionID) {
	try {
		// De-highlight all of the options.
		for ( var i = 0; i < document.getElementById(group + '-rooms').value; i++) {
			SwitchClass(group + "-step1-" + i, 'form_select_line_selected', 'form_select_line_deselected');
		}
		// Highlight the selected option.
		SwitchClass(selectionID, 'form_select_line_deselected', 'form_select_line_selected');
		
		// Set the minimized display to show the selection.
		var room = document.getElementById(selectionID).innerHTML;
		document.getElementById(group + "-room-mini").innerHTML = room;
		
		// Disable the slider bar.
		DisableDesign(group);
		
		// Reset the rest of the form that might have stuff filled in.
		document.getElementById(group + "-style-mini").innerHTML = "";
		document.getElementById(group + "-photo-mini").src = "../repository_images/pixel.png";
		document.getElementById(group + "-selected").src = "../repository_images/pixel.png";
		document.getElementById(group + "-info").innerHTML = '';
		document.getElementById(group + '-slider_frame').innerHTML = '';
		VsCalculateCost(); // This should clear out anything that was selected.
		
		if (HTTP) {
			var url = "checkout_subform_virtual_staging_styles.php";
			var params = "room=" + room + "&index=" + group;
			
			HTTP.open("POST", url, true);
			HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			HTTP.setRequestHeader("Content-length", params.length);
			HTTP.setRequestHeader("Connection", "close");
			
			HTTP.onreadystatechange = function() { 
				if (HTTP.readyState == 4 && HTTP.status == 200) {
					SwitchClass(group + "-step2", 'disabled', 'enabled');
					document.getElementById(group + '-step2-select').innerHTML = HTTP.responseText; // Insert the slider bar
				}
			}
			HTTP.send(params);
		}
	} catch(err) {
		window.alert('SelectRoom: ' + err);
	}
}

function SelectStyle(group, selectionID) {
	try {
		if (MemberOfClass(group + "-step2", "enabled")) {  // Only continue if Step 2 is enabled.
			// De-highlight all the options.
			for ( var i = 0; i < document.getElementById(group + '-styles').value; i++) {
				SwitchClass(group + "-step2-" + i, 'form_select_line_selected', 'form_select_line_deselected');
			}
			// Highlight the selected option. 
			SwitchClass(selectionID, 'form_select_line_deselected', 'form_select_line_selected');
			// Set the minimized display to show the selected option.
			document.getElementById(group + "-style-mini").innerHTML = document.getElementById(selectionID).innerHTML;
			
			// Clear later options that might have been selected.
			document.getElementById(group + "-photo-mini").src = "../repository_images/pixel.png";
			document.getElementById(group + "-selected").src = "../repository_images/pixel.png";
			document.getElementById(group + "-info").innerHTML = '';
			
			// Get the selected set images.
			GetSets(group);
		}
	} catch(err) {
		window.alert('SelectStyle: ' + err);
	}
}

function GetSets(group) {
	try {
		// Get the selected room.
		var room = "";
		for (var i = 0; i < document.getElementById(group + '-rooms').value; i++) {
			if (MemberOfClass(group + "-step1-" + i, 'form_select_line_selected')) {
				room = document.getElementById(group + "-step1-" + i).innerHTML;
			}
		}
		// Get the selected style.
		var style = "";
		for (var i = 0; i < document.getElementById(group + '-styles').value; i++) {
			if (MemberOfClass(group + "-step2-" + i, 'form_select_line_selected')) {
				style = document.getElementById(group + "-step2-" + i).innerHTML;
			}
		}
		// If we have both room and style, grab the photo slider.
		if (room.length > 0 && style.length > 0) {
			if (HTTP) {
				var url = "checkout_subform_virtual_staging_slider.php";
				var params = "style=" + style + "&room=" + room + "&index=" + group;
				
				HTTP.open("POST", url, true);
				HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				HTTP.setRequestHeader("Content-length", params.length);
				HTTP.setRequestHeader("Connection", "close");

				HTTP.onreadystatechange = function() { 
					if (HTTP.readyState == 4 && HTTP.status == 200) {
						document.getElementById(group + '-slider_frame').innerHTML = HTTP.responseText; // Insert the slider bar
						// Reset the margin values for the slider bar.
						sliderWidth[group] = document.getElementById(group + '-sliderwidth').value; // Update the width of the photo slider
						sliderPos[group] = 0;
						currentPos[group] = 0;
						document.getElementById(group + '-slider_bar').style.marginLeft = "0px"; // Reset Slider Bar
						document.getElementById(group + '-photoslider').style.marginLeft = "0px"; // Reset Photo Slider
						// Clear the picture information.
						document.getElementById(group + "-info").innerHTML = "";
						// Enable the slider bar.
						EnableDesign(group);
					}
				}
				HTTP.send(params);
			}
		}
	} catch(err) {
		window.alert('GetSets: ' + err);
	}
}

function SelectPackage(group, value) {
	document.getElementById(group + "-price-mini").innerHTML = "$" + parseFloat(value).toFixed(2);
	VsCalculateCost();
}

function EnableDesign(group) {
	try {
		SwitchClass(group + "-design", 'disabled', 'enabled');
		SwitchClass(group + "-slider", 'hidden', 'visible');
	} catch(err) {
		window.alert('EnableStyle: ' + err);
	}
}

function DisableDesign(group) {
	try {
		SwitchClass(group + "-design", 'enabled', 'disabled');
		SwitchClass(group + "-slider", 'visible', 'hidden');
	} catch(err) {
		window.alert('EnableStyle: ' + err);
	}
}

function SelectPhoto(group, itemID) {
	try {
		for (var i = 0; i < document.getElementById(group + '-imagecount').value; i++) {
			SwitchClass(group + "-sl_img-" + i, 'photo_selected', 'photo_deselected');
		}
		SwitchClass(itemID, 'photo_deselected', 'photo_selected');
		document.getElementById(group + "-photo-mini").src = document.getElementById(itemID).src;
		document.getElementById(group + "-selected").src = document.getElementById(itemID).src;
		document.getElementById(group + "-info").innerHTML = document.getElementById(itemID + "-desc").value;
		VsCalculateCost();
	} catch(err) {
		window.alert('SelectPhoto: ' + err);
	}
}

function SelectForm(enabledForm) {
	try {
		if (AllFormsCompleted()) {
			for (var i = 0; i < vsFrameCount; i++ ) {
				if (!FormDisabled(i)) {
					if (enabledForm != i) {
						SwitchClass(i +  "-subform-mini", 'hidden', 'visible');
						SwitchClass(i +  "-subform", 'visible', 'hidden');
					} else {
						SwitchClass(i +  "-subform-mini", 'visible', 'hidden');
						SwitchClass(i +  "-subform", 'hidden', 'visible');
					}
				}
			}
		}
	} catch(err) {
		window.alert('SelectForm: ' + err);
	}
}

function FormDisabled(group) {
	var disabled = false;
	
	try {
		var completed = true;
		if (document.getElementById(group + "-subform-mini").className == "hidden" && document.getElementById(group + "-subform").className == "hidden") {
			disabled = true;
		}
		return disabled;
	} catch(err) {
		window.alert('AllFormsCompleted: ' + err);
	}
}

// Run this when done to validate the data.
// Let the user know that something is hanging out there.
function FinalCheck() {
	try {
		if (AllFormsCompleted()) {
			FormOff('virtualstaging');
		} else {
			window.alert("One of your rooms appears to be incomplete.\nPlease finish the room or remove it.");
		}
	} catch(err) {
		window.alert('FinalCheck: ' + err);
	}
}

// Check the form before being done and moving on to a new form.
function CheckForm(group) {
	try {
		if (FormCompleted(group)) {
			// Minimize all forms.
			SelectForm(-1);
		} else {
			window.alert("This room appears to be incomplete.\nPlease finish the room or remove it.");
		}
	} catch(err) {
		window.alert('CheckForm: ' + err);
	}
}

function FormCompleted(group) {
	var room = false;
	var style = false;
	var picture = false;
	var price = false;
	var output = false;
	try {
		if (document.getElementById(group + "-photo-mini").src.indexOf("pixel.png") == -1) {
			picture = true;
		}
		if (document.getElementById(group + "-style-mini").innerHTML.length > 0) {
			style = true;
		}
		if (document.getElementById(group + "-room-mini").innerHTML.length > 0) {
			room = true;
		}
		var checkbox = document.getElementsByName(group + "-pricing");
		for (var j=0; j < checkbox.length; j++) {
			if (checkbox[j].checked) {
				price = true;
			}
		}
		//alert("room: " + room + " style: " + style + " picture: " + picture + " price: " + price);
		if (room && style && picture && price) {
			output = true;
		}
		return output;
	} catch(err) {
		window.alert('FormCompleted: ' + err);
	}
}

function AllFormsCompleted() {
	try {
		var completed = true;
		for (var i = 0; i < vsFrameCount; i++ ) {
			if (!FormDisabled(i)) {
				completed = FormCompleted(i);
			}
		}
		return completed;
	} catch(err) {
		window.alert('AllFormsCompleted: ' + err);
	}
}

function VsCalculateCost() {
	try {
		var singleID = parseInt(document.getElementById("vs_single").value);
		var multiID = document.getElementById("vs_multi");
		if (multiID != null){
			multiID = parseInt(multiID.value);
		}
		var singleCount = 0;
		var multiCount = 0;
		var singlePrice = 0;
		var multiPrice = 0;
		
		var item = false;
		var checkbox = false;
		for (var i = 0; i < vsFrameCount; i++ ) {
			if (!FormDisabled(i)) {
				if (FormCompleted(i)) {
					checkbox = document.getElementsByName(i + "-pricing");
					for (var j=0; j < checkbox.length; j++) {
						if (checkbox[j].checked) {
							if (j == 0) { // If it's the first option, its a single.
								singleCount++;
								singlePrice = parseFloat(checkbox[j].value);
							} else { // Otherwise its a multi.
								multiCount++;
								multiPrice = parseFloat(checkbox[j].value);
							}
						}
					}
				}
			}
		}
		
		if (singleCount > 0) {
			order_additional_co[singleID] = new Array(singleID, singleCount, singlePrice);
		} else {
			order_additional_co[singleID] = undefined;
		}
		
		if (multiCount > 0) {
			//order_additional_co[multiID] = new Array(multiID, multiCount, multiPrice);
		} else {
			//order_additional_co[multiID] = undefined;
		}

		UpdateTotalOrder();
	} catch(err) {
		window.alert('VsCalculateCost: ' + err);
	}
}

function MouseDown(index, e) {
	try {
		evt = e || window.event;
		isClicked = true;
		xPos = evt.clientX;
		selectedSlider = index;
	} catch(err) {
		window.alert('MouseDown: ' + err);
	}
	
}

function MouseUp(e) {
	try {
		if (isClicked) {
			isClicked = false;
			currentPos[selectedSlider] = pos;
		}
	} catch(err) {
		window.alert('MouseUp: ' + err);
	}
}

function MouseMove(e) {
	try {
		var sliderSpace = 652;
		if (isClicked) {
			if (sliderWidth[selectedSlider] > sliderSpace) {
				//window.alert(selectedSlider);
				evt = e || window.event;  // Friggin firefox not liking window.event ...
				var unit = (sliderWidth[selectedSlider] - 840 + 20) / sliderSpace; // (slider width - frame width + 20px margin) / slider space
				pos = currentPos[selectedSlider] + evt.clientX - xPos;
				if (pos < 0) pos = 0;  // Don't go past the left bounds.
				if (pos > sliderSpace) pos = sliderSpace;  // Don't go past the right bounds.
				document.getElementById(selectedSlider + '-slider_bar').style.marginLeft = pos + "px";
				document.getElementById(selectedSlider + '-photoslider').style.marginLeft = (-1 * unit * pos) + "px";  // -1 to make it move left.
			}
		}
	} catch(err) {
		window.alert('MouseMove: ' + err);
	}
}			
