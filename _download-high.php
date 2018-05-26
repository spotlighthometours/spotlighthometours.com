<!DOCTYPE html>
<html>
        <head>
        <title>High Res Photo Downloads</title>
        <script src="repository_inc/jquery-1.10.2.min.js"></script>
        <script src="repository_inc/jquery-ui-1.10.2.custom.min.js"></script>
        <link href="repository_css/jquery-ui-1.10.2.custom.min.css" rel="stylesheet" type="text/css" />
        <script src="repository_inc/jquery.fileDownload.js" type="text/javascript"></script>
        <script>
			$(document).ready(
				function() {
					var $preparingFileModal = $("#preparing-file-modal");
					$preparingFileModal.dialog({ modal: true, open: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); }});
					$.fileDownload('http://www.spotlighthometours.com/image_processor/_get_photos.php?tourid=<?PHP echo intval($_REQUEST['id']) ?>&w=0&h=0&m=high&download=1', {
						successCallback: function (url) {
							$preparingFileModal.dialog('close');
							var $completeFileModal = $("#complete-file-modal");
							$completeFileModal.dialog({ modal: true, open: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); }});
						},
						failCallback: function (responseHtml, url) {
							$preparingFileModal.dialog('close');
							$("#error-modal").dialog({ modal: true });
						}
					});
				}
			);
		</script>
        </head>
        <body>
        	<div id="preparing-file-modal" title="Preparing download..." style="display: none;">
                We are preparing your high resolution photos download. Our server may takes several minutes to prepare your download. Once the download is ready this dialog will change and your download will start, please wait...
                <p align="center"><img src="images/common/loader-bar.gif" /></p>
            </div>
            <div id="error-modal" title="Error" style="display: none;">
                There was a problem generating your high resolution photos download, please try again.
            </div>
            <div id="complete-file-modal" title="Downloading..." style="display: none; width:auto;">
                Your high resolution photos are downloading, please check your browser's download section for the download progress information and location. You will not be notified by this page when the download completes.
                <h2>Default Locations for Web Downloads</h2>
				<p>When downloading files from your browser, they'll typically be saved in a "Downloads" folder on your computer (or, in some cases, your Desktop, depending on your setup).</p>
                <ul>
                    <li>On Windows XP, it's under \Documents and Settings\[username]\My Documents\Downloads</li>
                    <li>Vista and Windows 7, the path is \Users\[username]\Downloads</li>
                    <li>For Mac, the full path is /Users/[username]/Downloads</li>
                    <li>On Linux it's home\[username]\Downloads</li>
                </ul>
            </div>
		</body>
</html>
