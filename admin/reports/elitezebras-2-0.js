			
/*****************************************************************
*   ELITE ZEBRA TABLES (v.2.0)
*   http://www.eklekt.com/widgets/zebras/index.php
*
*	This javascript will allow html tables to be sorted, striped, selected, and submitted.
*	See website for details and usage.
*
*	Original table sorting script from http://www.kryogenix.org/code/browser/sorttable/
*	Distributed under the MIT license: http://www.kryogenix.org/code/browser/licence.html
*
*	Original table striping script from http://www.alistapart.com/articles/zebratables/
*	Modified by Jop de Klein at http://validweb.nl/artikelen/javascript/better-zebra-tables/
*
*
*	Copyright (c) 1997-2006 Stuart Langridge, David F. Miller, Jop de Klein, Jesse Fulton.
*
*	All scripts added to and modified by Jesse Fulton, 6/17/2006
*
*	Free for use.  Please leave full license intact.
******************************************************************/



	/**
	* Cross-browser event handling for IE5+,  NS6 and Mozilla
	* By Scott Andrew
	*
	*/
	function addEvent(elm, evType, fn, useCapture)	{
	  if (elm.addEventListener){
		elm.addEventListener(evType, fn, useCapture);
		return true;
	  } else if (elm.attachEvent){
		var r = elm.attachEvent("on"+evType, fn);
		return r;
	  } else {
		alert("Handler could not be removed");
	  }
	} 
	
	/**
	* Gets nearest parent (grandparent, etc.) with tagname pTagName
	*/
	function getParent(el, pTagName) {
		if (el == null) return null;
		else if (el.nodeType == 1 && el.tagName.toLowerCase() == pTagName.toLowerCase())	// Gecko bug, supposed to be uppercase
			return el;
		else
			return getParent(el.parentNode, pTagName);
	}
	
	
	
	/**
	* BEGIN TABLE SORTING FUNCTIONALITY
	*/
	
	var SORT_COLUMN_INDEX;
	
	/**
	* Goes through all document tables and calls ts_makeSortable
	* on any table with a class of "sortable".
	*/
	function sortables_init() {
		// Find all tables with class sortable and make them sortable
		if (!document.getElementsByTagName) return;
		tbls = document.getElementsByTagName("table");
		for (ti=0;ti<tbls.length;ti++) {
			thisTbl = tbls[ti];
			if (((' '+thisTbl.className+' ').indexOf("sortable") != -1) && (thisTbl.id)) {
				//initTable(thisTbl.id);
				ts_makeSortable(thisTbl);
			}
		}
	}
	
	/**
	* Adds events and styles to handle sorting behavior.
	*/
	function ts_makeSortable(table) {
		if (table.rows && table.rows.length > 0) {
			var firstRow = table.rows[0];
		}
		if (!firstRow) return;
		
		// We have a first row: assume it's the header, and make its contents clickable links
		for (var i=0;i<firstRow.cells.length;i++) {
			var cell = firstRow.cells[i];
			if (cell.className.indexOf("sortable") < 0) {
				continue;
			}
			var txt = ts_getInnerText(cell);
			cell.innerHTML = '<a href="#" class="sortheader" '+ 
			'onclick="ts_resortTable(this, '+i+');stripe();return false;">' + 
			txt+'<span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a>';
		}
	}
	
	/**
	* Returns the inner text of an element.
	*/
	function ts_getInnerText(el) {
		if (typeof el == "string") return el;
		if (typeof el == "undefined") { return el };
		if (el.innerText) return el.innerText;	//Not needed but it is faster
		var str = "";
		
		var cs = el.childNodes;
		var l = cs.length;
		for (var i = 0; i < l; i++) {
			switch (cs[i].nodeType) {
				case 1: //ELEMENT_NODE
					str += ts_getInnerText(cs[i]);
					break;
				case 3:	//TEXT_NODE
					str += cs[i].nodeValue;
					break;
			}
		}
		return str;
	}
	
	
	/**
	* Calls a sort function a table column for each table body.
	*/
	function ts_resortTable(lnk,clid) {
		// get the span
		var span;
		for (var ci=0;ci<lnk.childNodes.length;ci++) {
			if (lnk.childNodes[ci].tagName && lnk.childNodes[ci].tagName.toLowerCase() == 'span') {
				span = lnk.childNodes[ci];
			}
		}
		
		var spantext = ts_getInnerText(span);
		var td = lnk.parentNode;
		var column = clid || td.cellIndex;
		var table = getParent(td,'table');
		
		// Work out a type for the column
		if (table.rows.length <= 1) return;
		var itm = ts_getInnerText(table.rows[1].cells[column]);
		sortfn = ts_sort_caseinsensitive;
	
		//ts_sort_date currently only handles these two formats
		if (itm.match(/^\d\d[\/-]\d\d[\/-]\d\d\d\d$/)) sortfn = ts_sort_date;
		if (itm.match(/^\d\d[\/-]\d\d[\/-]\d\d$/)) sortfn = ts_sort_date;
		
		if (itm.match(/^[£$]/)) sortfn = ts_sort_currency;
		if (itm.match(/^[\d\.]+$/)) sortfn = ts_sort_numeric;
		if (itm.match(/^(-)?[\d\.]+$/)) sortfn = ts_sort_numeric;
		SORT_COLUMN_INDEX = column;
		
		
		var tbodies = table.getElementsByTagName("tbody");
		for (var h = 0; h < tbodies.length; h++) {
			var tableBody = tbodies[h];
			var newRows = new Array();
			
		
			var trs = tableBody.getElementsByTagName("tr");
	
			for (var i = 0; i < trs.length; i++) {
				newRows[i] = trs[i]; 
			}
			newRows.sort(sortfn);
	
	
			if (span.getAttribute("sortdir") == 'down') {
				newRows.reverse();
			}
			
			// We appendChild rows that already exist to the tbody, so it moves them rather than creating new ones
			// don't do sortbottom rows
			for (var i=0;i<newRows.length;i++) { 
				if (!newRows[i].className || (newRows[i].className && (newRows[i].className.indexOf('sortbottom') == -1))) {
					tableBody.appendChild(newRows[i]);
				}
			}
		
			// do sortbottom rows only
			for (var i=0;i<newRows.length;i++) { 
				if (newRows[i].className && (newRows[i].className.indexOf('sortbottom') != -1)) { 
					tableBody.appendChild(newRows[i]);
				}
			}
	
	
		}

	
		if (span.getAttribute("sortdir") == 'down') {
			ARROW = '&nbsp;&nbsp;&uarr;';
			span.setAttribute('sortdir','up');
		} else {
			ARROW = '&nbsp;&nbsp;&darr;';
			span.setAttribute('sortdir','down');
		}
		
		// Delete any other arrows there may be showing
		var allspans = document.getElementsByTagName("span");
		for (var ci=0;ci<allspans.length;ci++) {
			if (allspans[ci].className == 'sortarrow') {
				if (getParent(allspans[ci],"table") == getParent(lnk,"table")) { // in the same table as us?
					allspans[ci].innerHTML = '&nbsp;&nbsp;&nbsp;';
				}
			}
		}
			
		span.innerHTML = ARROW;
	}
	
	
	/**
	* Sorts strings as dates.
	*/
	function ts_sort_date(a,b) {
		// y2k notes: two digit years less than 50 are treated as 20XX, greater than 50 are treated as 19XX
		aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]);
		bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]);
		if (aa.length == 10) {
			dt1 = aa.substr(6,4)+aa.substr(3,2)+aa.substr(0,2);
		} else {
			yr = aa.substr(6,2);
			if (parseInt(yr) < 50) { yr = '20'+yr; } else { yr = '19'+yr; }
			dt1 = yr+aa.substr(3,2)+aa.substr(0,2);
		}
		if (bb.length == 10) {
			dt2 = bb.substr(6,4)+bb.substr(3,2)+bb.substr(0,2);
		} else {
			yr = bb.substr(6,2);
			if (parseInt(yr) < 50) { yr = '20'+yr; } else { yr = '19'+yr; }
			dt2 = yr+bb.substr(3,2)+bb.substr(0,2);
		}
		if (dt1==dt2) return 0;
		if (dt1<dt2) return -1;
		return 1;
	}
	
	/**
	* Sorts strings as an amount of currency.
	*/
	function ts_sort_currency(a,b) { 
		aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]).replace(/[^0-9.]/g,'');
		bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]).replace(/[^0-9.]/g,'');
		return parseFloat(aa) - parseFloat(bb);
	}
	
	/**
	* Sorts strings numerically.
	*/
	function ts_sort_numeric(a,b) { 
		aa = parseFloat(ts_getInnerText(a.cells[SORT_COLUMN_INDEX]));
		if (isNaN(aa)) aa = 0;
		bb = parseFloat(ts_getInnerText(b.cells[SORT_COLUMN_INDEX])); 
		if (isNaN(bb)) bb = 0;
		return aa-bb;
	}
	
	/**
	* Sorts strings case insensitively.
	*/
	function ts_sort_caseinsensitive(a,b) {
		aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]).toLowerCase();
		bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]).toLowerCase();
		if (aa==bb) return 0;
		if (aa<bb) return -1;
		return 1;
	}
	
	/**
	* Sorts strings by the default sort behavior.
	*/
	function ts_sort_default(a,b) {
		aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]);
		bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]);
		if (aa==bb) return 0;
		if (aa<bb) return -1;
		return 1;
	}



	/**
	* BEGIN ZEBRA STRIPING AND SELECTION/SUBMISSION CODE
	*/


	/**
	* Performs a "zebra striping" of all tables with the class name of "striped".
	*/
	var stripe = function() {
		var tables = document.getElementsByTagName("table");	

		for(var x=0;x!=tables.length;x++){
			var table = tables[x];
			if (! table) { return; }
			
			if (table.className.indexOf("striped") < 0) {
				continue;
			}
			
			var tbodies = table.getElementsByTagName("tbody");
			var even = true;
			for (var h = 0; h < tbodies.length; h++) {
				
				var trs = tbodies[h].getElementsByTagName("tr");
						
				
				for (var i = 0; i < trs.length; i++) {
					if(even) {
						if (trs[i].className.indexOf("even") < 0) {
							trs[i].className += " even";
						}
					}
					else {
						trs[i].className = trs[i].className.replace("even", "");
					}
					
					even = !even;
				}
			}
		}
	}
	
	
	/**
	* Gets all rows within the table body of a table with class name "selectable"
	* and allows them to be selected.  They highlight on mouseover and are selected on click.
	*
	* Since these rows are selectable, the function also tries to make the table
	* submittable.
	* SEE: makeTableSubmittable(table);
	*/
	var makeSelectable = function() {
		var tables = document.getElementsByTagName("table");	



		for(var x=0;x!=tables.length;x++){
			var table = tables[x];
			if (! table) { return; }
	
			hideElements(table.getElementsByTagName("td"));			
	
			if (table.className.indexOf("selectable") < 0) {
				continue;
			}
			
			var tbodies = table.getElementsByTagName("tbody");
			
			for (var h = 0; h < tbodies.length; h++) {

				var trs = tbodies[h].getElementsByTagName("tr");
				
				for (var i = 0; i < trs.length; i++) {
					if (trs[i].className.indexOf("disabled") >= 0) {
						//disableChildCheckBoxes(this);
						continue;
					}
				
							
					if(isChecked(trs[i])) {
						if (trs[i].className.indexOf("selected") < 0) {
							trs[i].className += " selected";
						}
					}
				
					trs[i].onmouseover=function(){
						this.className += " ruled"; return false
					}
					trs[i].onmouseout=function(){
						this.className = this.className.replace("ruled", ""); return false
					}

					trs[i].onclick=function(){
						if (this.className.indexOf("selected") < 0) {
							this.className += " selected";
							checkChildCheckboxes(this, true);
							return true;	
						}
						else {
							this.className = this.className.replace("selected", ""); 
							checkChildCheckboxes(this, false);
							return true;
						}
					}					

				}
			}

			makeTableSubmittable(table);

		}
	}


	
	/**
	* Takes a list of elements and an optional input type (for 'input' elements)
	* and sets their display to none.  If the inputType parameter is set, then it
	* will check to make sure that the type matches.
	*/
	var hideElements = function(els, inputType) {
		for (var i=0; i<els.length; i++) {
			var isRightType = true;
			if (inputType != 'undefined' && els[i].type != inputType) {
				isRightType = false;
			}
			if (isRightType && els[i].className.indexOf("hideme") >= 0) {
				els[i].style.display = 'none';
			}
		}
	}
	
	
	/**
	* Searches the element passed in (a tr in our case) for any child input elements 
	* which are checkboxes.  If it finds one that is checked, it returns true.
	*/
	var isChecked = function(el) {
		var inpts = el.getElementsByTagName("input");
		for (var n=0; n<inpts.length; n++) {
			if (inpts[n].getAttribute("type") == "checkbox") {
				return (inpts[n].checked);
			}
		}
		return false;
	}
	
	
	/**
	* Gets all checkboxes which are a child of the element passed in and sets
	* their "checked" value to the parameter chk.
	*/
	var checkChildCheckboxes = function(el, chk) {
		var inpts = el.getElementsByTagName("input");
		for (var n=0; n<inpts.length; n++) {
			if (inpts[n].getAttribute("type") == "checkbox") {
				inpts[n].checked = chk;
			}
		}
	}
	

	/**
	* Gets forms within the table passed in.
	* Makes any button with a class of "select_all" highlight all rows.
	* Makes any button with a class of "select_none" unhighlight all rows.
	*
	* SEE: selectAllRows(table, highlight);
	*/
	var makeTableSubmittable = function(table) {

		var inputs = table.getElementsByTagName("input");
		for (var h = 0; h < inputs.length; h++) {
			if (inputs[h].type == "button") {
				if (inputs[h].className.indexOf("select_all") >= 0) {
					inputs[h].onclick = function() {selectAllRows(table, true);}
				}
				if (inputs[h].className.indexOf("select_none") >= 0) {
					inputs[h].onclick = function() {selectAllRows(table, false);}
				}				
			}
		}
	}



	/**
	* Will select or deselect all of the rows in the table specified.
	*
	*/
	var selectAllRows = function(table, highlight) { 

			if (! table) { return; }
			
			if (table.className.indexOf("striped") < 0) {
				return;
			}
			
			var tbodies = table.getElementsByTagName("tbody");
			
			for (var h = 0; h < tbodies.length; h++) {
				var trs = tbodies[h].getElementsByTagName("tr");
					for (var i = 0; i < trs.length; i++) {
					
						if (trs[i].className.indexOf("disabled") >= 0) {
							continue;
						}

						if (highlight) {
								if (trs[i].className.indexOf("selected") < 0) {
									trs[i].onclick();
								}
						}
						else {
								if (trs[i].className.indexOf("selected") >= 0) {
									trs[i].onclick();
								}
						}
					}
			}			
	}




addEvent(window, "load", sortables_init);
addEvent(window, "load", stripe);
addEvent(window, "load", makeSelectable);
