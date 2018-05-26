<?php
/*
 * Admin Contact Sheets Control File
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');

// Create instances of needed Objects
$contactSheets = new contactSheets($db);

// Set status to finalized on all contact sheets that are tied to a finalized tour.
$contactSheets->setAllFinalized();

// Set number per page, sort order, page# and action.
$defaults = Array(
    'page',
    'order_by',
    'order',
    'limit',
    'action'
);
foreach($defaults as $index => $value){
    switch($value){
        case'page':
            if(!isset($_GET[$value])||empty($_GET[$value])){
                $page = 1;
            }else{
                $page = $_GET[$value]; 
            }
        break;
        case'order_by':
            if(!isset($_GET[$value])||empty($_GET[$value])){
                $order_by = "cs.order_date";
            }else{
                $order_by = $_GET[$value]; 
            }
        break;
        case'order':
            if(!isset($_GET[$value])||empty($_GET[$value])){
                $order = 'DESC';
            }else{
                $order = $_GET[$value]; 
            }
        break;
        case'limit':
            if(!isset($_GET[$value])||empty($_GET[$value])){
                $limit = 25;
            }else{
                $limit = $_GET[$value]; 
            }
        break;
        case'action':
            if(!isset($_GET[$value])||empty($_GET[$value])){
                $action = 'list';
            }else{
                $action = $_GET[$value]; 
            }
        break;
    }
}
$start = $limit*($page-1);

// Process Images Action
if($action == "process_img"){
	$numberProcessed = $contactSheets->processImages();
}

// Get number of photos that need to be processed
$process_photo_count = $contactSheets->getProcessPhotoCount();
// Get rand number to force fresh download of CSS and JS to avoid cache issues
$randNum = rand(999999,999999999);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Admin - Contact Sheets</title>
<link rel="stylesheet" type="text/css" href="../../repository_inc/jquery-lightbox-0.5/css/jquery.lightbox-0.5.css" media="screen" />
<link type="text/css" href="../../repository_css/admin.css" rel="stylesheet" />
<script src="../../repository_inc/jquery-1.5.min.js" type="text/javascript"></script>
<script src="../../repository_inc/jquery-lightbox-0.5/js/jquery.js" type="text/javascript" ></script>
<script src="../../repository_inc/jquery-lightbox-0.5/js/jquery.lightbox-0.5.js" type="text/javascript" ></script>
<script src="../../repository_inc/admin.js"></script>
<script src="../../repository_inc/contact-sheets.js?randIt=<?PHP echo $randNum ?>"></script>
<script type="text/javascript">
	$(function() {
		$('a[@rel*=lightbox]').lightBox(); // Select all links that contains lightbox in the attribute rel
	});
</script>
</head>
<body>
<div class="contact-sheet">
  <?PHP

// Check Action
switch($action){
    
    // List Contact Sheets
    case'list':
?>
  <form action="" method="get">
    <input name="action" type="hidden" value="search" />
    <table style="width:800px;">
      <tr>
        <td width="100%" valign="middle"><div class="button_new button_blue button_mid" onclick="GetLoadingScreen('Processing <?PHP echo $process_photo_count ?> Photos');loadPage('text=Processing <?PHP echo $process_photo_count ?> Photos', '?action=process_img');">
            <div class="curve curve_left" ></div>
            <span class="button_caption" >Process <?PHP echo $process_photo_count ?> Photos</span>
            <div class="curve curve_right" ></div>
          </div></td>
        <td>Filter by tour ID#<br />
          <input name="tourID" type="text" value="" /></td>
        <td valign="bottom"><br />
          <input type="submit" name="button" id="button" value="Go" /></td>
      </tr>
    </table>
  </form>
  <?PHP
        print $contactSheets->getListTable($start, $limit, $order_by, $order);
  ?>
  <form action="" method="get">
  <input type="hidden" name="action" value="add" />
  <table style="width:800px;">
      <tr>
        <td align="left">Add by tour ID# <input name="tourID" type="text" value="" /><input type="submit" name="button" id="button" value="Add" /></td>
      </tr>
  </table>
  </form>
  <?PHP
    break;
    
    // Process Images
    case'process_img':
?>
  <form action="" method="get">
    <input name="action" type="hidden" value="search" />
    <table style="width:800px;">
      <tr>
        <td width="100%" valign="middle"><div class="button_new button_blue button_mid" onclick="GetLoadingScreen('Processing <?PHP echo $process_photo_count ?> Photos');loadPage('text=Processing <?PHP echo $process_photo_count ?> Photos', '?action=process_img');">
            <div class="curve curve_left" ></div>
            <span class="button_caption" >Process <?PHP echo $process_photo_count ?> Photos</span>
            <div class="curve curve_right" ></div>
          </div></td>
        <td>Filter by tour ID#<br />
        <input name="tourID" type="text" value="" /></td>
        <td valign="bottom"><br />
          <input type="submit" name="button" id="button" value="Go" /></td>
      </tr>
      <tr>
   	  <td colspan="3">
        	<div class="alerts widthAuto">
<?PHP
				print $numberProcessed . ' Photos Processed!';
?>
  			</div>
        </td>
      </tr>
    </table>
<?PHP    
        print $contactSheets->getListTable($start, $limit, $order_by, $order);
?>
			<form action="" method="get">
              <input type="hidden" name="action" value="add" />
              <table style="width:800px;">
                  <tr>
                    <td align="left">Add by tour ID# <input name="tourID" type="text" value="" /><input type="submit" name="button" id="button" value="Add" /></td>
                  </tr>
              </table>
              </form>
                      </div></td>
                  </tr>
                </table>
            </form>
<?PHP
    break;
	
    // Process Images
    case'add':
?>
  <form action="" method="get">
    <input name="action" type="hidden" value="search" />
    <table style="width:800px;">
      <tr>
        <td width="100%"><div class="button_new button_blue button_mid" onclick="loadPage('text=Processing <?PHP echo $process_photo_count ?> Photos', '?action=process_img');">
            <div class="curve curve_left" ></div>
            <span class="button_caption" >Process <?PHP echo $process_photo_count ?> Photos</span>
            <div class="curve curve_right" ></div>
          </div></td
        >
        <td>Filter by tour ID#<br />
          <input name="tourID" type="text" value="" /></td>
        <td valign="bottom"><br />
          <input type="submit" name="button" id="button" value="Go" /></td>
      </tr>
      <tr>
        <td colspan="3">
<?PHP
        if($contactSheets->addContactSheet($_GET['tourID'])){
			print '<div class="alerts widthAuto">Contact sheet added!</div>';
		}else{
			print '<div class="errors widthAuto">Contact sheet not added! Please check the tour ID and try again.</div>';
		}
?>
          </td>
      </tr>
    </table>
  </form>
  <?PHP    
        print $contactSheets->getListTable($start, $limit, $order_by, $order);
  ?>
  <form action="" method="get">
  <input type="hidden" name="action" value="add" />
  <table style="width:800px;">
      <tr>
        <td align="left">Add by tour ID# <input name="tourID" type="text" value="" /><input type="submit" name="button" id="button" value="Add" /></td>
      </tr>
  </table>
  </form>
<?PHP
    break;

    // List Contact Sheets by Search
    case'search':
?>
  <form action="" method="get">
    <input name="action" type="hidden" value="search" />
    <table style="width:800px;">
      <tr>
        <td width="100%"><div class="button_new button_blue button_mid" onclick="loadPage('text=Processing <?PHP echo $process_photo_count ?> Photos', '?action=process_img');">
            <div class="curve curve_left" ></div>
            <span class="button_caption" >Process <?PHP echo $process_photo_count ?> Photos</span>
            <div class="curve curve_right" ></div>
          </div></td
        >
        <td>Filter by tour ID#<br />
          <input name="tourID" type="text" value="" /></td>
        <td valign="bottom"><br />
          <input type="submit" name="button" id="button" value="Go" /></td>
      </tr>
    </table>
  </form>
  <?PHP
        print $contactSheets->getListTable($start, $limit, $order_by, $order, $_GET['tourID']);
  ?>
  <form action="" method="get">
  <input type="hidden" name="action" value="add" />
  <table style="width:800px;">
      <tr>
        <td align="left">Add by tour ID# <input name="tourID" type="text" value="" /><input type="submit" name="button" id="button" value="Add" /></td>
      </tr>
  </table>
  </form>
<?PHP
    break;
	
 	// Edited Display Msg
    case'edited':
?>
  <form action="" method="get">
    <input name="action" type="hidden" value="search" />
    <table style="width:800px;">
      <tr>
        <td width="100%"><div class="button_new button_blue button_mid" onclick="loadPage('text=Processing <?PHP echo $process_photo_count ?> Photos', '?action=process_img');">
            <div class="curve curve_left" ></div>
            <span class="button_caption" >Process <?PHP echo $process_photo_count ?> Photos</span>
            <div class="curve curve_right" ></div>
          </div></td>
        <td>Filter by tour ID#<br />
          <input name="tourID" type="text" value="" /></td>
        <td valign="bottom"><br />
          <input type="submit" name="button" id="button" value="Go" /></td>
      </tr>
      <tr>
        <td colspan="3"><div class="alerts widthAuto"> Thumbnails submitted! [ <a href="?action=edit&csID=<?PHP echo $_GET['csID']; ?>">Continue Editing</a> | <a href="?action=published&csID=<?PHP echo $_GET['csID']; ?>">Send to Client</a> ]
            <?PHP    
?>
          </div></td>
      </tr>
    </table>
  </form>
  <?PHP
        print $contactSheets->getListTable($start, $limit, $order_by, $order, $_GET['tourID']);
?>
  <form action="" method="get">
  <input type="hidden" name="action" value="add" />
  <table style="width:800px;">
      <tr>
        <td align="left">Add by tour ID# <input name="tourID" type="text" value="" /><input type="submit" name="button" id="button" value="Add" /></td>
      </tr>
  </table>
  </form>
  <?PHP
    break;
 	
	// Publish
    case'published':
?>
  <form action="" method="get">
    <input name="action" type="hidden" value="search" />
    <table style="width:800px;">
      <tr>
        <td width="100%"><div class="button_new button_blue button_mid" onclick="loadPage('text=Processing <?PHP echo $process_photo_count ?> Photos', '?action=process_img');">
            <div class="curve curve_left" ></div>
            <span class="button_caption" >Process <?PHP echo $process_photo_count ?> Photos</span>
            <div class="curve curve_right" ></div>
          </div></td
        >
        <td>Filter by tour ID#<br />
          <input name="tourID" type="text" value="" /></td>
        <td valign="bottom"><br />
          <input type="submit" name="button" id="button" value="Go" /></td>
      </tr>
      <tr>
        <td colspan="3"><div class="alerts widthAuto"> Contact sheet sent to client! </div></td>
      </tr>
    </table>
  </form>
  <?PHP
  		$contactSheets->publish($_GET['csID']);
        print $contactSheets->getListTable($start, $limit, $order_by, $order, $_GET['tourID']);
?>
  <form action="" method="get">
  <input type="hidden" name="action" value="add" />
  <table style="width:800px;">
      <tr>
        <td align="left">Add by tour ID# <input name="tourID" type="text" value="" /><input type="submit" name="button" id="button" value="Add" /></td>
      </tr>
  </table>
  </form>
  <?PHP
    break;

    // Edit Contact Sheet
    case'edit':
		$csID = $_GET['csID'];
		$contactSheetDetails = $contactSheets->getDetails($csID);
		$hdrCount = $contactSheets->hdrCount($csID);
?>
  <input type="hidden" name="csID" id="csID" value="<?PHP echo $csID; ?>" />
  <table style="width:995px;" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><h1>Edit Contact Sheet Thumbnails</h1></td>
      <td align="right" width="390"><div class="button_new button_dgrey button_mid" onclick="saveAdminContactSheet();">
          <div class="curve curve_left" ></div>
          <span class="button_caption" >Submit Thumbnails</span>
          <div class="curve curve_right" ></div>
        </div></td>
    </tr>
    <tr>
      <td style="padding-right:19px; padding-left:11px;"><div class="form_line" >
          <div class="form_direction">Choose thumbnails for tour type: <i><?PHP echo $contactSheetDetails['tourTypeName']; ?> (<?PHP echo $contactSheetDetails['photos']; ?> photos | <?PHP echo $contactSheetDetails['hdr_photos']; ?> HDRs)</i></div>
        </div></td>
      <td style="padding-right:20px; padding-left:14px;"><div class="form_line" >
          <div class="form_direction">
            <div class="floatLeft"> Total Thumbnails Selected
              <input id="photoCount" tpye="text" size="3" readonly="readonly" value="<?PHP echo $contactSheets->photoCount($csID); ?>" />
            </div>
            <div class="floatLeft">&nbsp;&nbsp;HDR
              <input id="HDRCount" tpye="text" size="3" readonly="readonly" value="<?PHP echo $hdrCount; ?>" />
            </div>
            <div class="clear"></div>
          </div>
        </div></td>
    </tr>
  </table>
  <?PHP
	echo $contactSheets->getAdminImageListTable($csID);
    break;
	
	// View client selection
    case'view':
	
		// Pull contact sheet details
		$tourID = $_GET['tourID'];
		$csID = $contactSheets->getID($tourID);
		$contactSheetDetails = $contactSheets->getDetails($csID);
		$photoCount = $contactSheets->photoCount($csID);
		$hdrCount = $contactSheets->hdrCount($csID);
		$regPhotoCount = $photoCount - $hdrCount;
		$additionalPhotoCount = $contactSheets->getAdditionalPhotoCount($tourID);
		$additionalPhotoCount = (empty($additionalPhotoCount))?0:$additionalPhotoCount;
		$additionalHDRCount = $contactSheets->getAdditionalHDRCount($tourID);
		$additionalHDRCount = (empty($additionalHDRCount))?0:$additionalHDRCount;
		$maxHDR = intval($contactSheetDetails['hdr_photos']) + intval($additionalHDRCount) + intval($contactSheetDetails['panoramics']);
		$maxPhoto = intval($contactSheetDetails['photos']) + intval($additionalPhotoCount) + intval($contactSheetDetails['addPhoto']);
		$hdrSelectionCount = $contactSheets->hdrSelectionCount($csID);
		$hdrSelectionCount = (empty($hdrSelectionCount))?0:$hdrSelectionCount;
		$photoSelectionCount = $contactSheets->photoSelectionCount($csID);
		$photoSelectionCount = (empty($photoSelectionCount))?0:$photoSelectionCount;
		$status = $contactSheets->getStatus($csID);
		$selectedImageNames = $contactSheets->getSelectedImageNames($csID);
		$selectedHDRNames = $contactSheets->getSelectedHDRNames($csID);
		$editable = true;
		if($status>=4){
			$editable = false;
		}
		if(isset($_GET['editable'])&&$_GET['editable']){
			$editable = true;
		}
		$contactSheets->editable = $editable;
?>
  <input type="hidden" name="maxHDR" id="maxHDR" value="<?php echo $maxHDR; ?>"/>
  <input type="hidden" name="maxPhoto" id="maxPhoto" value="<?php echo $maxPhoto; ?>"/>
  <input type="hidden" name="tourID" id="tourID" value="<?php echo $tourID; ?>"/>
  <div class="view_frame contact-sheet" >
    <h1 class="title" > Contact Sheet</h1>
    <h2 class="subtitle"><?php echo $contactSheetDetails['address']; ?>, <?php echo $contactSheetDetails['city']; ?>, <?php echo $contactSheetDetails['state']; ?></h2>
    <div class="grey-divider"></div>
    <table border="0" cellpadding="0" cellspacing="0" class="details">
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="5">
            <tr>
              <td style="padding-left:0px;"><strong>Tour Type:</strong></td>
              <td><em><?php echo $contactSheetDetails['tourTypeName']; ?></em></td>
            </tr>
            <tr>
              <td style="padding-left:0px;"><strong>Includes:</strong></td>
              <td><em><?php echo $contactSheetDetails['photos']; ?> images, <?php echo $contactSheetDetails['hdr_photos']; ?> HDR images, <?php echo $contactSheetDetails['motion']; ?> Motion Scenes</em></td>
            </tr>
            <tr>
              <td style="padding-left:0px;"><strong>Additional:</strong></td>
              <td><em><?php echo $additionalPhotoCount; ?> images, <?php echo $additionalHDRCount; ?> HDR images</em></td>
            </tr>
          </table></td>
        <td colspan="2" rowspan="2" align="right" valign="top" style="padding:0px;"><table border="0" cellspacing="0" cellpadding="5" class="totals">
            <tr class="details">
              <td><strong>Total Images:</strong></td>
              <td align="center"><div><?php echo $photoCount; ?></div></td>
            </tr>
            <tr class="details">
              <td><strong>Selected images:</strong></td>
              <td align="center"><div><span id="photoCount"><?php echo $photoSelectionCount ?></span> / <?php echo $maxPhoto; ?></div>
                <input type="hidden" name="photoCounter" id="photoCounter" value="<?php echo $photoSelectionCount ?>"/></td>
            </tr>
            <tr class="details">
              <td><strong>HDR images:</strong></td>
              <td align="center"><div><span id="HDRCount"><?php echo $hdrSelectionCount ?></span> / <?php echo $maxHDR; ?></div>
                <input type="hidden" name="HDRCounter" id="HDRCounter" value="<?php echo $hdrSelectionCount ?>"/></td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
    <div class="grey-divider"></div>
    <?PHP
  	if(!$editable){
  ?>
    <div class="alerts"><strong>This is the non editable view.</strong> If an agent has made an edit request via the phone or email you can authorize and make that change for them by making this page editable. <a href="?action=view&tourID=<?php echo $tourID; ?>&editable=true"><strong>[ Click here to make editable ]</strong></a>. <br/><strong>Note: In edit mode all changes are saved as they are made. Also you will need to refresh the page to see a new list of selected images below</strong></div>
    <?PHP
	}
  ?>
    <div class="thumbnails">
      <?PHP 
	    $contactSheets->admin = true;     
		echo $contactSheets->getUserImageListTable($csID);
	  ?>
    </div>
  <h1 class="title">Tour Info</h1>
  <table width="100%" border="0" cellspacing="0" cellpadding="5" class="details">
    <tr>
      <td align="left" valign="top"><strong>Tour ID:</strong></td>
      <td align="left" valign="top"><?PHP echo $tourID; ?></td>
    </tr>
    <tr>
      <td align="left" valign="top"><strong>Agent Name:</strong></td>
      <td align="left" valign="top"><?PHP print $contactSheetDetails['fname'].' '.$contactSheetDetails['lname']; ?></td>
    </tr>
    <tr>
      <td align="left" valign="top"><strong>Address:</strong></td>
      <td align="left" valign="top"><?php echo $contactSheetDetails['address']; ?>, <?php echo $contactSheetDetails['city']; ?>, <?php echo $contactSheetDetails['state']; ?></td>
    </tr>
  </table>
  <h1 class="title">Special Instructions</h1>
  <ul>
  	<li><?php echo $contactSheetDetails['instructions']; ?></li>
  </ul>
  <h1 class="title" >Selected Images</h1>
  <ul>
<?PHP
	foreach($selectedImageNames as $row => $column){
?>
    <li><?PHP echo $column['img'] ?></li>
<?PHP
	}
?>
  </ul>
  <h1 class="title" >Selected HDRs</h1>
  <ul>
<?PHP
	foreach($selectedHDRNames as $row => $column){
?>
    <li><?PHP echo $column['img'] ?></li>
<?PHP
	}
?>
  </ul>
  </div>
  <?PHP
}

?>
</div>
<!-- MODAL WINDOW -->
<div class="modal">
  <div id="backdrop" style="display: none;" onclick="HidePopUp();"></div>
  <div class="modal-window" id="pop_up_frame">
    <div class="top"><a class="close" href="javascript:HidePopUp();"></a></div>
    <div class="middle">
      <h1 id="pop_up_title"></h1>
      <div id="pop_up_content"> </div>
    </div>
    <div class="bottom"></div>
  </div>
</div>
<!-- END MODAL WINDOW -->
</body>
</html>