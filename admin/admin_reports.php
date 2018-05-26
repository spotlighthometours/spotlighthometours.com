<?php
/**********************************************************************************************
Document: admin_reports.php
Creator: Edward Seniw
Date: 01/4/2013
Purpose: Display all pricing adjustements for the brokerages (both tourtypes and additional products)
		 Display all tours that are Pay When Sold and don't have the MLS Set
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

	// Include appplication's global configuration
	require_once('../repository_inc/classes/inc.global.php');
	ShowErrors();
	
	// Connect to MySQL
	if (!isset($dbc)) {
		require_once ('../repository_inc/connect.php');
		require_once ('../repository_inc/clean_query.php');
	}
	
//=======================================================================
// Document
//=======================================================================
	// Start the session
	session_start();
	
	$debug = true;
	
	// Require Admin Login
	if (!$debug) {
		require_once ('../repository_inc/require_admin.php');
	}
	if ( !isset($_REQUEST['section']) )
		$_REQUEST['section'] = "tourTypePricing";
			
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<SCRIPT SRC="../repository_inc/jquery-ui-timepicker-addon.js" TYPE="text/javascript"></SCRIPT>
    <style type="text/css" media="screen">
    @import "../repository_css/template.css?rand=382515075";
    @import "../repository_css/user-cp.css?rand=382515075";
    @import "../repository_css/jquery.qtip.css?rand=382515075";
    @import "../repository_inc/imgareaselect/imgareaselect-animated.css?rand=382515075";
    @import "../repository_css/user-cp-settings.css?rand=382515075";
    
    </style>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Admin - Reports</title>
        <link type="text/css" href="../repository_css/admin.css" rel="stylesheet" />
        <style>	
			#attempt {
				padding-left: 6px;
				padding-right: 6px;
				font-size: 14px;
				font-weight: bold;
				font-family: Arial, Helvetica, sans-serif;
				color: white;
				line-height: 30px;
				border: 6px solid #d6e8c9;
				background-color: #6da941; 
			}
			table {
				width:95% !important;
			}
			table TH {
				background-color: #C3D9FF; 
			}
			
		</style>
<style>
	/* css for timepicker */
	.ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
	.ui-timepicker-div dl { text-align: left; }
	.ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
	.ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
	.ui-timepicker-div td { font-size: 90%; }
	.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }
</style>
<SCRIPT TYPE="text/javascript">
	$('#startDate').datetimepicker({
		dateFormat: 'yy-mm-dd'
	});  						
	$('#endDate').datetimepicker({
		dateFormat: 'yy-mm-dd'
	});  										
</script>
        
    </head>
    <body>
    	
    <div class="content settings">
        <ul class="tabs">
            <li class="first"><a href="?section=tourTypePricing" <?PHP echo ($_REQUEST['section']=="tourTypePricing")?'class="selected"':'';?>>Tour Type Pricing</a></li>
            <li><a href="?section=productPricing" <?PHP echo ($_REQUEST['section']=="productPricing")?'class="selected"':'';?>>Product Pricing</a></li>
            <li><a href="?section=payWhenSold" <?PHP echo ($_REQUEST['section']=="payWhenSold")?'class="selected"':'';?>>Pay When Sold</a></li>
            <li><a href="?section=payWhenSoldNoMLS" <?PHP echo ($_REQUEST['section']=="payWhenSoldNoMLS")?'class="selected"':'';?>>Pay When Sold Without MLS</a></li>
            <li><a href="?section=soldTours" <?PHP echo ($_REQUEST['section']=="soldTours")?'class="selected"':'';?>>Sold Tours</a></li>
            <li><a href="?section=finalizedTours" <?PHP echo ($_REQUEST['section']=="finalizedTours")?'class="selected"':'';?>>Finalized Tours</a></li>
            <li><a href="?section=orders" <?PHP echo ($_REQUEST['section']=="orders")?'class="selected"':'';?>>Orders</a></li>
            <li><a href="?section=affiliateTotalTours" <?PHP echo ($_REQUEST['section']=="affiliateTotalTours")?'class="selected"':'';?>>Affiliate Total Tours</a></li>
            <li><a href="?section=brokerageAgents" <?PHP echo ($_REQUEST['section']=="brokerageAgents")?'class="selected"':'';?>>Brokerage Agents</a></li>
            <div class="clear"></div>
        </ul>	
<?PHP
//		if (empty($_REQUEST['orderBy']))
//			$orderBy = "";
//		else
//			$orderBy = "?orderBy=".$_REQUEST['orderBy'];
			
		switch($_REQUEST['section']){
			case "tourTypePricing":
				include_once("reports/admin_tourtype_pricing_report.php");
			break;
			case "productPricing":
				include_once("reports/admin_product_pricing_report.php");
			break;
			case "payWhenSold":
				include_once("reports/admin_PayWhenSold_report.php");
			break;
			case "payWhenSoldNoMLS":
				include_once("reports/admin_PayWhenSold_Without_MLS_report.php");
			break;
			case "soldTours":
				include_once("reports/admin_Sold_Tours.php");
			break;
			case "finalizedTours":
				include_once("reports/admin_Finalized_Tours.php");
			break;
			case "orders":
				include_once("reports/admin_Orders_report.php");
			break;
			case "affiliateTotalTours":
				include_once("reports/admin_Affiliate_Tours_Totals.php");
			break;
			case "brokerageAgents":
				include_once("reports/admin_Brokerage_Agents_report.php");
			break;
		}
?>
	</div>
    </body>
</html>