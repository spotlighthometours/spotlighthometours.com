// JavaScript Document

function AddTourID() {
	try {
		var tourid_box = document.getElementById("tourid_box");
		var output = '';
		for(var i = 0; i < index; i++) {
			if(document.getElementById("tourid_" + i)) {
				var box = document.getElementById("tourid_" + i);
				output += '<div class="formrow" ><div class="row r_name invisible" ></div><div class="row r_content" ><input id="tourid_' + i + '" name="tourid_' + i + '" class="input mid exp" type="text" value="' + box.value + '" /></div></div>';
			}
		}
		
		output += '<div class="formrow" ><div class="row r_name invisible" ></div><div class="row r_content" ><input id="tourid_' + index + '" name="tourid_' + index + '" class="input mid exp" type="text" /></div></div>';
		tourid_box.innerHTML = output;
		index++;
	} catch(err) {
		window.alert("AddTourID: " + err + ' (line: ' + err.line + ')');
	}	
}