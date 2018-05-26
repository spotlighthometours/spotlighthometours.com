<?php
/*
 * Admin: Media Manager
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');

showErrors();
$parentType = $_REQUEST['parentType'];
$parentID = $_REQUEST['parentID'];

// Create instances of needed objects
$users = new users($db);
$mediamanager = new mediamanager($parentType, $parentID);

// Require user login
$userType = 'admin';
if(isset($_REQUEST['userType'])){
	$userType = $_REQUEST['userType'];
}
switch($userType){
	case 'admin':
		$users->authenticateAdmin();		
	break;
	case 'user':
		$users->authenticate();
	break;
}

$mediamanager->loadMedia();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Media Manager</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link href="../../repository_inc/bootstrap-fileinput/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="../../repository_inc/bootstrap-fileinput/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="../../repository_inc/bootstrap-fileinput/js/plugins/sortable.js" type="text/javascript"></script>
<script src="../../repository_inc/bootstrap-fileinput/js/fileinput.js" type="text/javascript"></script>
<script src="../../repository_inc/bootstrap-fileinput/js/locales/fr.js" type="text/javascript"></script>
<script src="../../repository_inc/bootstrap-fileinput/js/locales/es.js" type="text/javascript"></script>
<script src="../../repository_inc/bootstrap-fileinput/themes/explorer/theme.js" type="text/javascript"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" type="text/javascript"></script>
<style>
	.kv-file-content{
		overflow:hidden;
	}
</style>
</head>
<body>
<h1>Media Manager</h1>
<input id="uploader" name="userfile" type="file" class="file" data-preview-file-type="text" >
<!-- Modal -->
<div class="modal fade" id="uploadOptions" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
        	Upload Title
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          		<span aria-hidden="true">&times;</span>
        	</button>
        </h5>
      </div>
      <div class="modal-body">
      	<div class="input-group">
          <input type="text" class="form-control" name="uploadtitle" "placeholder="File title...">
    	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary save-upload-options">Save changes</button>
      </div>
    </div>
  </div>
</div>
<script>
	var btns = '<button type="button" class="edit-upload-btn btn btn-xs btn-default" title="Edit" data-key="{dataKey}" onClick="editUpload(this)">' +
    '<i class="glyphicon glyphicon-pencil"></i>' +
    '</button>';
	$("#uploader").fileinput({
		'otherActionButtons': btns,
		'showUpload':false, 
		'previewFileType':'any', 
		'uploadUrl':'../../repository_queries/media-manager-upload.php', 
		'uploadExtraData':{parentType: '<?PHP echo $parentType; ?>', parentID: <?PHP echo $parentID; ?>},
        'uploadExtraData': function (previewId, index) {
        	var uploadTitle = $("#"+previewId).find(".file-footer-caption").attr("title");
            var info = {parentType: '<?PHP echo $parentType; ?>', parentID: <?PHP echo $parentID; ?>, title: uploadTitle};
            return info;
        }, 
		'deleteUrl':'../../repository_queries/media-manager-delete.php',
		initialPreview: [
<?PHP
	$firstPreview = true;
	foreach($mediamanager->mediaData as $mediaDataRow => $mediaData){
?>
		<?PHP if(!$firstPreview){echo ',';}?>
<?PHP
		if($mediaData['mediaType']=='video'){
?>
				'<video width="320" height="240" controls><source src="<?PHP echo $mediamanager->getMediaURL($mediaData) ?>" type="video/mp4"></video>'
<?PHP
		}else{
?>
				'<?PHP echo $mediamanager->getMediaURL($mediaData) ?>',
<?PHP	
		}
?>			
<?PHP
		$firstPreview = false;
	}
?>
		],
		initialPreviewAsData: false,
		overwriteInitial: false,
		initialPreviewConfig: [
<?PHP
	$firstPreview = true;
	foreach($mediamanager->mediaData as $mediaDataRow => $mediaData){
?>
			<?PHP if(!$firstPreview){echo ',';}?>{
				caption: '<?PHP echo $mediaData['title'] ?>',
				key: <?PHP echo $mediaData['id'] ?>,
<?PHP
		if($mediaData['mediaType']=='video'){
?>
				previewAsData: false,
<?PHP
		}else{
?>
				previewAsData: true,
<?PHP	
		}
?>
				extra: {id: <?PHP echo $mediaData['id'] ?>, parentType: '<?PHP echo $parentType ?>', parentID: <?PHP echo $parentID ?>}
			}	
<?PHP
		$firstPreview = false;
	}
?>
		]

	});
	var uploadEditID = '';
    function editUpload(uploadObj){
    	var currentTitle = $(uploadObj).parent().parent().parent().find(".file-footer-caption").attr("title");
		$('#uploadOptions').find('input[name="uploadtitle"]').val(currentTitle);
    	$('#uploadOptions').modal('show');
        uploadEditID = $(uploadObj).parent().parent().parent().parent().attr("id");
    }
    $('.save-upload-options').on('click', function() {
        var setTitle = $('#uploadOptions').find('input[name="uploadtitle"]').val();
        $("#"+uploadEditID).find(".file-footer-caption").attr("title", setTitle);
        $("#"+uploadEditID).find(".file-footer-caption").html(setTitle+"<br/>");
       	var id = $("#"+uploadEditID).find(".kv-file-remove").data('key'); 
        $('#uploadOptions').modal('hide');
        if(id){
        	saveUploadTitle(id, setTitle);
        }
    });
    function saveUploadTitle(id, title){
    	$.ajax({
          method: "POST",
          url: "../../repository_queries/media-manager-update.php",
          data: { id: id, parentType: "<?PHP echo $parentType ?>", parentID: <?PHP echo $parentID ?>, title: title}
        }).done(function( msg ) {
            
        });
    }
</script>
</body>
</html>