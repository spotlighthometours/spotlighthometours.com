<?php
/*
 * Admin: Themes
 */

// Include appplication's global configuration
require_once('../../../repository_inc/classes/inc.global.php');
//showErrors();

clearCache();

// Create instances of needed objects
$users = new users($db);
$brokerages = new brokerages();

// Require admin
$users->authenticateAdmin();

$brokeragesList = $brokerages->listAll();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Themes</title>
<script src="../../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<script src="../../../repository_inc/admin-themes.js" type="text/javascript"></script><!-- Themes JS file -->
<style type="text/css" media="screen">
	@import "../../../repository_css/template.css";
 	@import "../../../repository_css/admin-v2.css";
	#step2,#step3{
		display:none;
	}
</style>
</head>
<body>
<h1>Theme Editor</h1>
<div id="themesMsg"></div>
<!-- STEP 1: Select Scope -->
<div id="step1">
  <h2>Step 1: Select Theme Scope</h2>
  <i>Select a brokerage, user or tour theme to edit. If you select just a brokerage in the theme scope below then the theme for that brokerage will be loaded and ready to edit. If you select a user the user's theme will then be loaded and ready to edit, same with the tour selection. Only select the scope you want to edit the theme for! :)</i>
  <br/><br/>
  <div class="add-user">
    <div class="form_line" >
      <div class="input_line w_lg" >
        <div class="input_title" >Brokerage</div>
        <select name="brokerageID">
          <option value="0" selected >Select one...</option>
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
    <div class="form_line" >
      <div class="input_line w_lg" >
        <div class="input_title" >Tours</div>
        <div class="tour-select">
          <select name="tourID" disabled="disabled">
            <option value="0">Select a user to load tours!</option>
          </select>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="step2" style="margin-bottom:100px;">
  <h2>Step 2: Edit Theme</h2>
  <iframe id="editor" src="" width="1050" height="800" frameborder="0"/>
</div>
<?PHP
	include('../../repository_inc/html/modal.html');
?>
</body>
</html>