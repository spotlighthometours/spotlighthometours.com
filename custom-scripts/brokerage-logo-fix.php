<?PHP
/**********************************************************************************************
Document: brokerage-logo-fix
Creator: Jacob Edmond Kerr
Date: 08-17-11
Purpose: Scans the logo folder for brokerage logos imgs and checks to see if the img name has been saved to the brokerages table and logo column. If not insert.
Notes: All brokerage logo images are named brokerage_brokerageID.format and located in the images/logos/ folder.
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
	// Connect to MySQL
	require_once ('../repository_inc/clean_query.php');
	
//=======================================================================
// Document
//=======================================================================

$path = '../images/logos/';

if ($handle = opendir($path)) {
    
    /* Loop over the directory. */
    while (false !== ($file = readdir($handle))) {
        
		$brokerageStringPos = strrpos($file, "brokerage_");
		$brokerageID = explode("_", $file);
		$fileFormat = explode(".", $brokerageID[1]);
		$brokerageID = $fileFormat[0];
		$fileFormat = $fileFormat[1];
		
		if($brokerageStringPos!==false&&$brokerageStringPos==0&&strtolower($fileFormat)!=='psd'){
			$query = "SELECT logo, brokerageName FROM brokerages WHERE brokerageID = ".$brokerageID;
			$result = mysql_query($query) or die("Ooops Query Failed!");
			
			if(mysql_num_rows($result)>0){
				if(mysql_result($result, 0, 'logo')==""){
					$brokageName = mysql_result($result, 0, 'brokerageName');
					
					$query = "UPDATE brokerages SET logo='".$file."' WHERE brokerageID=".$brokerageID;
					$result = mysql_query($query) or die("Ooops Update Failed!");
					
					$log_file_name = date('m-d-y');
					
					print "Brokerage Name: ".$brokageName.": ID: ".$brokerageID.". Logo Was Missing In The Database. Update Complete.<br/>\r\n";
					error_log("Brokerage Name: ".$brokageName.": ID: ".$brokerageID.". Logo Was Missing In The Database. Update Complete.\n\r", 3, $log_file_name.".log");
				}
			}
		}
    }

    closedir($handle);
}
?>