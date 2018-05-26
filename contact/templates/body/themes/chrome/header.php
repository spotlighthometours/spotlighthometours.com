<?PHP
/*	Author: Jacob Edmond Kerr
*	Desc: Microsite chrome theme header
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link REL="SHORTCUT ICON" HREF="http://www.spotlighthometours.com/repository_images/icon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?PHP echo $metaData['title'] ?></title>
<!-- FOR SEARCH ENGINES -->
<meta name="robots" content="INDEX, FOLLOW" />
<meta name="description" content="<?PHP echo $metaData['description'] ?>" />
<!-- FOR FACEBOOK -->
<meta property="og:title" content="<?php echo $metaData['title']; ?>" />
<meta property="og:description" content="<?PHP echo $metaData['description'] ?>" />
<meta property="og:image" content="<?PHP echo $metaData['icon'] ?>" />
<style type="text/css" media="screen">
@import "http://www.spotlighthometours.com/microsites/styles/<?PHP echo $template ?>.css";
</style>
<!-- JQUERY LIB JS -->
<script src="http://www.spotlighthometours.com/repository_inc/jquery-2.1.4.min.js"></script>
<!-- JQUERY UI LIB -->
<script src="http://www.spotlighthometours.com/repository_inc/slideme/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<!-- Slide Me -->
<script src="http://www.spotlighthometours.com/repository_inc/slideme/jquery.slideme2.js"></script>
<!-- TEMPLATE JS -->
<script src="http://www.spotlighthometours.com/microsites/js/template.js"></script>
<link rel="stylesheet" type="text/css" href="http://www.spotlighthometours.com/repository_inc/slideme/slideme.css" />
</head>
<body>
<div class="top-nav">
<?PHP
	include($microsites->getDocumentRoot().'/templates/body/nav.php');
?>
    <div class="clear"></div>
</div>
<div class="header">
<div class="left-column">
    	<h1><?PHP echo $microsites->tours->title ?></h1>
        <?PHP if($branded){include($microsites->getDocumentRoot().'/templates/body/agent-info.php');} ?>
        <?PHP echo $microsites->tours->address ?><br/>
		<?PHP echo $microsites->tours->city ?>, <?PHP echo $microsites->tours->state ?><br />
		<?PHP echo $microsites->tours->zipCode ?>
    <p class="price">$<?PHP echo number_format($microsites->tours->listPrice, 0, '.', ',') ?></p>
<?PHP
	include($microsites->getDocumentRoot().'/templates/body/share.php');
?>
    </div>
    <div class="right-column">
    	<iframe width="621" height="350" src="<?PHP echo $microsites->getVideoPlayerURL($featuredVideo['type'], $featuredVideo['id'], true) ?>" frameborder="0" mozallowfullscreen="true" webkitallowfullscreen="true" allowfullscreen="true"  scrolling="no"></iframe>
    </div>
    <div class="clear"></div>
</div>
<div class="content">