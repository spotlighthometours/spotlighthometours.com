<?php
/**********************************************************************************************
Document: admin_promocodes.php
Creator: Brandon Freeman
Date: 04-18-11
Purpose: Lists promocodes.
**********************************************************************************************/

//=======================================================================
// Error Reporting & Output Buffering
//=======================================================================

	ini_set ('display_errors', 1);
	error_reporting (E_ALL & ~E_NOTICE);
	ob_start();

//=======================================================================
// Includes
//=======================================================================

	// Connect to MySQL
	require_once ('../repository_inc/connect.php');
	require_once ('../repository_inc/clean_query.php');
	
//=======================================================================
// Document
//=======================================================================
	// Start the session
	session_start();
	
	$debug = false;
	
	// Require Admin Login
	if (!$debug) {
		require_once ('../repository_inc/require_admin.php');
	}
	
	if (isset($_POST['code'])) {
		$code = CleanQuery($_POST['code']);
	} elseif (isset($_GET['code'])) {
		$code = CleanQuery($_GET['code']);
	}
	
	if (isset($_POST['form'])) {
		$form = CleanQuery($_POST['form']);
	} elseif (isset($_GET['form'])) {
		$form = CleanQuery($_GET['form']);
	}
	
	$index = 0;
	if (isset($_POST['index'])) {
		$index = CleanQuery($_POST['index']);
	} elseif (isset($_GET['index'])) {
		$index = CleanQuery($_GET['index']);
	}
	if ($index < 0) $index = 0;
	
	$max = 20;
	if (isset($_POST['max'])) {
		$max = CleanQuery($_POST['max']);
	} elseif (isset($_GET['max'])) {
		$max = CleanQuery($_GET['max']);
	}
	
	$search = "";
	if (isset($_POST['search'])) {
		$search = CleanQuery($_POST['search']);
	} elseif (isset($_GET['search'])) {
		$search = CleanQuery($_GET['search']);
	}
	
	if (isset($_POST['new']) || isset($_GET['new'])) {
		$new = true;
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Admin - Promocodes</title>
        <style type="text/css" media="screen">@import "../repository_css/admin.css";</style>
		<link type="text/css" href="includes/jquery-ui-1.8.9/css/ui-lightness/jquery-ui-1.8.9.custom.css" rel="stylesheet" />
		<script type="text/javascript" src="includes/jquery-ui-1.8.9/js/jquery-1.4.4.min.js"></script>
		<script type="text/javascript" src="includes/jquery-ui-1.8.9/js/jquery-ui-1.8.9.custom.min.js"></script> 
		<script type="text/javascript">
			$(function() {
				$( "#expiration" ).datepicker();
				$( "#expiration" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
			});
		</script>
        <script type="text/javascript">
			
			function GetForm() {
				try {
					if (document.getElementById('type').selectedIndex > 0 ) {
						var selection = document.getElementById('type').options[document.getElementById('type').selectedIndex].value;
						
						var url = "admin_promocodes.php";
						var params  = "form=" + selection;
						
						var HTTP = false;
						if (window.XMLHttpRequest) {
							HTTP = new XMLHttpRequest();
						} else if (window.ActiveXObject) {
							HTTP = new ActiveXObject("Microsoft.XMLHTTP");
						}
						
						if(HTTP) {
							HTTP.open("POST", url, true);
							HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
							HTTP.setRequestHeader("Content-length", params.length);
							HTTP.setRequestHeader("Connection", "close");

							HTTP.onreadystatechange = function() { 
								if (HTTP.readyState == 4 && HTTP.status == 200) {
									document.getElementById('newform').innerHTML = HTTP.responseText;
								}
							}
							HTTP.send(params);
						}
					}
				} catch(err) {
					window.alert("GetForm: " + err + ' (line: ' + err.line + ')');
				}	
			}
			
			function ConfirmDelete(itemName, url) {
				if (confirm("Delete " + itemName + "?! \nAre you sure?")) {
					window.location.href = url;
				}
			}
		</script>
	</head>

    <body>
    	
	<?php
    	if (isset($_GET['delete'])) {
			if (isset($_GET['code']) && isset($_GET['type']) && isset($_GET['id'])) {
				
				$query = 'DELETE FROM promocode_values WHERE codestr = "' . CleanQuery($_GET['code']) . '" AND type = "' . CleanQuery($_GET['type']) . '" AND Id = "' . CleanQuery($_GET['id']) . '" LIMIT 1';
				mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run:<br />" . $query);
				header('Location: ' . basename($_SERVER['PHP_SELF']) . '?code=' . $_GET['code']);
				ob_flush();
				
			} elseif (isset($_GET['code']) && !isset($_GET['type']) && !isset($_GET['id'])) {
				
				$query = 'DELETE FROM promocodes WHERE codestr = "' . CleanQuery($_GET['code']) . '" LIMIT 1';
				mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run:<br />" . $query);
				header('Location: ' . basename($_SERVER['PHP_SELF'] . '?deletecode'));
				ob_flush();
			} else {
				header('Location: ' . basename($_SERVER['PHP_SELF'] . "?something"));
				ob_flush();
			}
		} elseif (isset($_POST['submit'])) {
			$query = '';
			
			if ($_POST['code'] != $_POST['oldcode']) {
				$query .= 'codestr = "' . trim(CleanQuery($_POST['code'])) . '",';
			}
			
			if (isset($_POST['expiration'])) {
				if (strlen($_POST['expiration']) > 0) {
				$query .= 'expdate = "' . CleanQuery($_POST['expiration']) . ' 00:00:00",';
				}
			}
			
			if (isset($_POST['limit'])) {
				$query .= 'limits = "' . intval(CleanQuery($_POST['limit'])) . '",';
			}
			
			if (isset($_POST['active'])) {
				$query .= 'active = "1",';
			} else {
				$query .= 'active = "0",';
			}
			
			if (isset($_POST['notes'])) {
				$query .= 'notes = "' . CleanQuery($_POST['notes']) . '",';
			}
			
			// Remove the last character, being the comma, from the string.
			if (strlen($query) > 1) {
				$query = substr($query,0,-1);
			}
			
			if (isset($_POST['oldcode'])) {
				$query = 'UPDATE promocodes SET ' . $query . ' WHERE codestr = "' . CleanQuery($_POST['oldcode']) . '" LIMIT 1';
			} else {
				$query = 'INSERT INTO promocodes SET ' . $query;
			}
			
			mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run:<br />" . $query);
			
			if (isset($_POST['type']) && isset($_POST['id']) && isset($_POST['code']) ) {
				$query = 'INSERT INTO promocode_values SET codestr = "' . trim(CleanQuery($_POST['code'])) . '", type = "' . CleanQuery($_POST['type']) . '", Id="' . CleanQuery($_POST['id']) . '", ';
				
				if (isset($_POST['dollar'])) {
					$query .= 'dollarValue = "' . floatval(CleanQuery($_POST['dollar'])) . '",';
				}
				
				if (isset($_POST['percent'])) {
					$query .= 'percentValue = "' . floatval(CleanQuery($_POST['percent'])) . '",';
				}
				
				if (isset($_POST['day'])) {
					$query .= 'dayValue = "' . intval(CleanQuery($_POST['day'])) . '",';
				}
				
				// Remove the last character, being the comma, from the string.
				if (strlen($query) > 1) {
					$query = substr($query,0,-1);
				}
				
				mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run:<br />" . $query);
			}
			
			header('Location: ' . basename($_SERVER['PHP_SELF']) . '?code=' . $_POST['code']);
			ob_flush();
			
		} elseif (!isset($new) && !isset($code) && !isset($form)) {
			
			$query = "SELECT pc.codestr, pc.expdate, pc.limits, pc.active, pc.notes,
					  (SELECT COUNT(*) FROM promocode_values pcv WHERE pcv.type = 'tour' AND pcv.codestr = pc.codestr) AS tours,
					  (SELECT COUNT(*) FROM promocode_values pcv WHERE pcv.type = 'product' AND pcv.codestr = pc.codestr) AS products, 
					  (SELECT COUNT(*) FROM promocode_values pcv WHERE pcv.type = 'order' AND pcv.codestr = pc.codestr) AS orders,
					  (SELECT COUNT(*) FROM promocode_values pcv WHERE pcv.type = 'membership' AND pcv.codestr = pc.codestr) AS memberships,
					  (SELECT COUNT(*) FROM orders o WHERE o.coupon = pc.codestr) AS used
					  FROM promocodes pc
					  ";
					  
			if (strlen($search) > 0) {
				$query .= 'WHERE codestr LIKE "%' . $search . '%"';
			}
			
			$query .= "ORDER BY codestr
					  LIMIT " . $index . "," . $max . " 
					 ";
					  
			$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
			
			$count = intval(@mysql_num_rows($r));
			
			echo '
		<table>
			<tr>
				<th colspan="11" >
					<form action="' . basename($_SERVER['PHP_SELF']) . '" method="get">
						Search: <input type="text" name="search" value="' . $search . '" />
						<input type="submit" id="submit" name="submit" value="submit" />
					</form>
				</th>
			</tr>
            <tr>
			';
			if ($index > $max) {
				echo '<th colspan="2" ><a href="' . basename($_SERVER['PHP_SELF']) . '?index=' . ($index - $max) . '&max=' . $max . '&search=' . $search . '" >[PREV]</a></th>';
			} else {
				echo '<th colspan="2" ></th>';
			}
			
			if ($count >= $max) {
				echo '<th colspan="2" ><a href="' . basename($_SERVER['PHP_SELF']) . '?index=' . ($index + $max) . '&max=' . $max . '&search=' . $search . '" >[NEXT]</a></th>';
			} else {
				echo '<th colspan="2" ></th>';
			}
			
			
			echo '
				<th colspan="7" ></th>
			</tr>
			<tr>
            	<th><a href="' . basename($_SERVER['PHP_SELF']) . '?new=1" ><img src="../repository_images/new.png" /></a></th>
                <th>Code</th>
                <th>Expires</th>
                <th>Limit</th>
                <th>Active</th>
                <th>Tour</th>
                <th>Product</th>
                <th>Order</th>
                <th>Membership</th>
                <th>Notes</th>
                <th></th>
            </tr>
			';
			
			
			$highlight = true;
			while($result = mysql_fetch_array($r)){
				if ($highlight) {
					$class = "highlight";
				} else {
					$class = "nohighlight";
				}
				$highlight = !$highlight;
				
				echo '
					<tr class="' . $class . '" >
						<td><a href="' . basename($_SERVER['PHP_SELF']) . '?code=' . $result['codestr'] . '" ><img src="../repository_images/look.png" /></a></td>
						<td>' . $result['codestr'] . '</td>
						<td>';
				
				$today = date("Y-m-d");
				$expiration = date("Y-m-d", strtotime($result['expdate']));
				if ($expiration > $today) {
					echo $expiration;
				} else {
					echo '<span style="color: red;" >' . $expiration . '</span>';
				}
				echo '</td>
						<td>';
				if ($result['limits'] != null && $result['limits'] != 0) {
					if ($result['used'] < $result['limits']) {
						echo $result['limits'] . ' (' . $result['used'] . ')';
					} else {
						echo '<span style="color: red;" >' . $result['limits'] . ' (' . $result['used'] . ')</span>';
					}
				}
				echo '</td>
						<td>';
						
				if ($result['active'] >= 1) {
					echo '<img src="../repository_images/apply.png" />';
				}
				
				echo '</td>
						<td>';
						
				if ($result['tours'] >= 1) {
					echo '<img src="../repository_images/apply.png" />';
				}
				
				echo '</td>
						<td>';
						
				if ($result['products'] >= 1) {
					echo '<img src="../repository_images/apply.png" />';
				}
				
				echo '</td>
						<td>';
						
				if ($result['orders'] >= 1) {
					echo '<img src="../repository_images/apply.png" />';
				}
				
				echo '</td>
						<td>';
						
				if ($result['memberships'] >= 1) {
					echo '<img src="../repository_images/apply.png" />';
				}
				
				echo '</td>
						<td>';
						
				if (strlen(trim($result['notes'])) > 0) {
					echo '<img src="../repository_images/apply.png" />';
				}
				
				 echo '</td>
						<td><img src="../repository_images/del.png" onclick="ConfirmDelete(' . chr(39) . $result['codestr'] . chr(39) .  ', ' . chr(39) . basename($_SERVER['PHP_SELF']) . '?delete=1&code=' . $result['codestr'] . chr(39) .');" /></td>
				';
						
			}
			echo '
		</table>
			';
		} elseif ((isset($new) || isset($code)) && !isset($form)) {
		
		if (!isset($new)) {
			$query = 'SELECT * FROM promocodes pc WHERE pc.codestr = "' . $code . '" LIMIT 1';
			$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
			$result = mysql_fetch_array($r);
		} else {
			$result = array();	
		}
			echo '
		<form action="' . basename($_SERVER['PHP_SELF']) . '" method="post">
	
			<div class="formrow" >
				<div class="row r_name" >Code</div>
				<div class="row r_content" >
					<input name="code" class="input mid" type="text" value="' . $result['codestr'] . '" />
			';
			
			if (isset($code)) {
				echo '
					<input name="oldcode" type="hidden" value="' . $result['codestr'] . '" />
				';
			}
					
			echo '
				</div>
			</div>
			';
			
			if (!isset($new)) {
				echo '
			<div class="formrow" >
				<div class="row r_name" >Expiration</div>
				<div class="row r_content" >
					' . date("Y-m-d", strtotime($result['expdate'])) . '
				</div>
			</div>
				';
			}
			
			echo '
			<div class="formrow" >
				<div class="row r_name" >Set Expiration</div>
				<div class="row r_content" >
					<input id="expiration" name="expiration" class="input mid exp" type="text" />
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name" >Limit</div>
				<div class="row r_content" >
					<input name="limit" class="input xsm" type="text" value="' . $result['limits'] . ' " />
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name" >Active</div>
				<div class="row r_content" >
				';
				
			if ($result['active'] == 1) {
				echo '
					<input name="active" class="input" type="checkbox" value="1" checked />
				';
			} else {
				echo '
					<input name="active" class="input" type="checkbox" value="1" />
				';
			}
			echo '
				</div>
			</div>
			<div class="formrow frtall" >
				<div class="row r_name" >Notes</div>
				<div class="row r_content r_tall" >
					<textarea name="notes" class="input wide tall" >' . $result['notes'] . '</textarea>
				</div>
			</div>
			
			<table>
				<tr>
					<th>Type</th>
					<th>Item Id</th>
					<th>Dollar Value</th>
					<th>Percentage Value</th>
					<th>Day Value</th>
					<th></th>
				</tr>
				';
				
			$query = 'SELECT * FROM promocode_values WHERE codestr = "' . $code . '"';
			$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
			while ($result = mysql_fetch_array($r)) {
				
				// Inefficient, but it works ... the database structure needs to be fixed to fix this.
				if ($result['type'] == 'tour') {
					$query = 'SELECT tourTypeName AS name FROM tourtypes WHERE tourTypeID = "' . $result['Id'] . '" LIMIT 1';
					$res = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
					$resname = mysql_fetch_array($res);
				} elseif ($result['type'] == 'product') {
					$query = 'SELECT productName AS name FROM products WHERE productID = "' . $result['Id'] . '" LIMIT 1';
					$res = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
					$resname = mysql_fetch_array($res);
				} else {
					$resname['name'] = "-";
				}
				
				if ($result['dollarValue'] == 0) {
					$result['dollarValue'] = "-";
				}
				
				if ($result['percentValue'] == 0) {
					$result['percentValue'] = "-";
				}
				
				if ($result['dayValue'] == 0) {
					$result['dayValue'] = "-";
				}
				
				$query = 'SELECT * FROM promocode_values WHERE codestr = "' . $code . '"';
				echo '
				<tr>
					<td>' . $result['type'] . '</td>
					<td>' . $resname['name'] . '</td>
					<td>' . $result['dollarValue'] . '</td>
					<td>' . $result['percentValue'] . '</td>
					<td>' . $result['dayValue'] . '</td>
					<td><img src="../repository_images/del.png" onclick="ConfirmDelete(' . chr(39) . 'this discount item' . chr(39) .  ', ' . chr(39) . basename($_SERVER['PHP_SELF']) . '?delete=1&code=' . $code .'&type=' . $result['type'] . '&id=' . $result['Id'] . chr(39) .');" /></td>
				</tr>
				
				';	
			}
			
			echo '
			</table>
			<div class="formrow" >
				<div class="row r_name" >Add Discount</div>
				<div class="row r_content" >
					<select id="type" name="type" class="input mid" onchange="GetForm();" >
						<option value="" >Select Type</option>
						<option value="tour">Tour</option>
						<option value="product">Product</option>	
						<option value="order">Order</option>		
						<option value="membership">Membership</option>						
					</select>
				</div>
			</div>
			<div id="newform" ></div>
			<div class="formrow" >
				<div class="row r_name invisible" ></div>
				<div class="row r_content" >
					<input type="submit" name="submit" value="Submit" />
					<a href="' . basename($_SERVER['PHP_SELF']) . '" ><input type="button" value="Cancel" /></a>
				</div>
			</div>
		</form>
			';
		} elseif (isset($form) && !isset($code) && !isset($new)) {
			switch ($form) {
				case "tour":
					$query = 'SELECT tourTypeID AS id, tourTypeName AS name FROM tourtypes ORDER BY tourTypeName ASC';
					break;
				case "product":
					$query = 'SELECT productID AS id, productName AS name FROM products WHERE productName IS NOT NULL ORDER BY tourTypeName ASC';
					break;
			}
			
			if (isset($query)) {
				$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
				echo '
				<div class="formrow" >
					<div class="row r_name" >Select Product</div>
					<div class="row r_content" >
						<select name="id" class="input mid" >
				';
				
				while ($result = mysql_fetch_array($r)) {
					echo '
							<option value="' . $result['id'] . '">' . $result['name'] . '</option>
					';
				}
													
				echo '
						</select>
					</div>
				</div>
				';
			} else {
				echo '
				<input name="id" type="hidden" value="0" />
				';	
			}
			echo '
				<div class="formrow" >
					<div class="row r_name" >Dollar Value</div>
					<div class="row r_content" >
						<input name="dollar" class="input xsm" type="text" /> (ex. 3.20 for $3.20)
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Percent Value</div>
					<div class="row r_content" >
						<input name="percent" class="input xsm" type="text" /> (ex. .67 for 67%)
					</div>
				</div>
			';
			
			if ($form != "order") {
				echo'
				<div class="formrow" >
					<div class="row r_name" >Day Value</div>
					<div class="row r_content" >
						<input name="day" class="input xsm" type="text" /> (ex. 3 for 3 days)
					</div>
				</div>
				';
			} else {
				echo '
				<input name="day" type="hidden" value="0" />
				';
			}
			
			echo '
			<div class="formrow" >
				<div class="row r_name invisible" ></div>
				<div class="row r_content" >
					Note: Dollar values over-rule percent values.
				</div>
			</div>
			';
			
		}
    ?>
    	
    </body>
</html>
