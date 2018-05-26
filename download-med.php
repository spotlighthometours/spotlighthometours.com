<!DOCTYPE html>
<html>
        <head>
        <title>Medium Res Photo Downloads</title>
        <script src="repository_inc/jquery-1.10.2.min.js"></script>
        <script src="repository_inc/jquery-ui-1.10.2.custom.min.js"></script>
        <link href="repository_css/jquery-ui-1.10.2.custom.min.css" rel="stylesheet" type="text/css" />
        <script src="repository_inc/jquery.fileDownload.js" type="text/javascript"></script>
        <script>
            function isIE () {
                var myNav = navigator.userAgent.toLowerCase();
                return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
            }

			$(document).ready(
				function() {
                    if(isIE () && isIE () < 9){
                        window.location="http://www.spotlighthometours.com/image_processor/get_photos.php?tourid=<?PHP echo intval($_REQUEST['id']) ?>&w=800&h=600&download=1";
                    }
                    else {
                        var $preparingFileModal = $("#preparing-file-modal");
                        $preparingFileModal.dialog({
                            modal: true, open: function (event, ui) {
                                $(".ui-dialog-titlebar-close").hide();
                            }
                        });
                        $.fileDownload('http://www.spotlighthometours.com/image_processor/get_photos.php?tourid=<?PHP echo intval($_REQUEST['id']) ?>&w=800&h=600&download=1', {
                            successCallback: function (url) {
                                $preparingFileModal.dialog('close');
                                $("#complete-file-modal").dialog({modal: true});
                            },
                            failCallback: function (responseHtml, url) {
                                $preparingFileModal.dialog('close');
                                $("#error-modal").dialog({modal: true});
                            }
                        });
                    }
				}
			);
		</script>
        </head>
        <body>
        	<div id="preparing-file-modal" title="Preparing download..." style="display: none;">
<b>Your files have been archived.</b> We are retrieving your files from an archive. Please allow a couple of minutes to download. The more photos in your tour, the longer the wait. Please be patient. Downloads may take as much as <b>10-15</b> minutes to download.
                <p align="center"><img src="images/common/loader-bar.gif" /></p>
            </div>
            <div id="error-modal" title="Error" style="display: none;">
                There was a problem generating your medium resolution photos download, please try again.
            </div>
            <div id="complete-file-modal" title="Downloading..." style="display: none;">
                Your medium resolution photos are downloading, please check your browser's download section for the download progress information and location. You will not be notified by this page when the download completes.
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
