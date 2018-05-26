<?php 
/* Author:  William Merfalen
 * Date:	10/09/2014
 * Finalize a video of the week email
 */

require("../repository_inc/classes/inc.global.php");
if( $_SERVER['HTTP_HOST'] == 'development' ){
	$server = "http://development/";
}else{
	$server = "http://www.spotlighthometours.com/";
}
//ShowErrors();
if( isset($_POST['title'])){
    $_SESSION['title'] = $_POST['title'];
    $_SESSION['tourId'] = $_POST['tourId'];
}

?>
<style type="text/css">
        body, h1, h2, p {
            margin: 0;
            padding: 0;
        }
        body {
            font-family:Arial, Helvetica, sans-serif;
            font-size:12px;
        }
        #desc-txt {
        color: #969696;
        font-family: 'Roboto', sans-serif;
        font-size: 16px;
        font-weight: 100;
        text-align: center;
        line-height: 28px;
        }
        a {
            color:#fff;
            text-decoration: none;
            font-family:Arial, Helvetica, sans-serif;
        }
        a:link {
            color:#fff;
            text-decoration: none;
            font-family:Arial, Helvetica, sans-serif;
        }
        a:visited {
            color:#fff;
            text-decoration: none;
            font-family:Arial, Helvetica, sans-serif;
        }
        a:active {
            color:#217fae;
            text-decoration: underline;
            font-family:Arial, Helvetica, sans-serif;
        }
        h1 {
            font-size: 36px;
            font-weight: 400;
            color: #fff;
            font-family:Arial, Helvetica, sans-serif;
        }
        h2 {
            font-size: 28px;
            font-weight: 400;
            color: #fff;
            font-family:Arial, Helvetica, sans-serif;
        }
        h3 {
            font-size: 36px;
            font-family:Arial, Helvetica, sans-serif;
        }
        h4 {
            font-size: 20px;
            font-weight: 300;
            color: #DBDBDB;
            font-family:Arial, Helvetica, sans-serif;
        }
        img {
            display:block;
        }
        #contact-info {
        color:#666666;
        font-family:Arial, Helvetica, sans-serif;
        }
        #unsubscribe {
        color:#596666;
        font-size:12px;
        font-family:Arial, Helvetica, sans-serif;
        }
        #contact-hdr {
        color:white;
        font-family:Arial, Helvetica, sans-serif;
        }
        #contact-lnk1 {
        color:#666666;
        font-family:Arial, Helvetica, sans-serif;
        }
        #contact-lnk2 {
        color:#666666;
        font-family:Arial, Helvetica, sans-serif;
        }
        </style>
        </p>

        <table border="0" cellpadding="0" cellspacing="0" width="100%"  bgcolor="#151719">
        <tr>
        <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
        <td>&nbsp;</td>
        <td width="660"><table align="center" border="0" cellpadding="0" cellspacing="0" width="660">
        <tr>
        <td height="30"></td>
        </tr>
        <tbody>
        <tr>
        <td align="left" bgcolor="#151719"><a href="http://www.spotlighthometours.com/"><img src="http://www.spotlighthometours.com/images/email-campaigns/videos-of-the-week/10-7-2013/email-logo.png" alt="logo" border="0"></a></td>
        </tr>
        <tr>
        <td height="30"></td>
        </tr>
        <tr>
        <td align="center" bgcolor="#0e0e0e"><table border="0" cellspacing="0" cellpadding="30">
        <tr>
        <td align="center"><h1>Cinematic Video of the Week!</h1>
        <h4 id='modalTitle'><?php echo htmlentities($_SESSION['title']); ?></h4></td>
        </tr>
        </table>
        <a id='modalTourLink' href="http://www.spotlighthometours.com/us/<?php echo intval($_SESSION['tourId']);?>" target="_blank">
        <?php 
        	//echo "<!-- " ; var_dump($_SESSION); echo "-->";
        	preg_match('|([0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4})|',$_SESSION['img'],$matches);
        	//echo "<!-- " ; var_dump($matches); echo "-->";
        	$date = $matches[1];
        	preg_match('|\.([a-z]{3,4})$|',$_SESSION['img'],$matches);
        	$ext = $matches[1];
        	//echo "<!-- " ; var_dump($matches); echo "-->";
        ?>
        <img id='modalVideoPreview' src="<?php echo "{$server}/images/email-campaigns/videos-of-the-week/$date/email_660.$ext" . randomString(); ?>" alt="Video of the Week" border="0"></a>
        <table width="100%" border="0" cellpadding="20" cellspacing="0">
        <tr>
        <td>&nbsp;</td>
        <td align="center" id="desc-txt"><p><?php echo nl2br(html_entity_decode($_SESSION['primaryDesc']));?></p></td>
        <td>&nbsp;</td>
        </tr>
        </table></td>
        </tr>
        <tr>
        <td height="100" align="center" valign="middle" bgcolor="#0e0e0e"><table width="60%" border="0" cellpadding="0" cellspacing="0">
        <tr>
        <td width="50%" align="center"><a href="http://www.spotlighthometours.com/tour-demos/cinematic-video.php" target="_blank"><img src="http://www.spotlighthometours.com/images/email-campaigns/videos-of-the-week/view-demos.gif" alt="View Demos" border="0" /></a></td>
        <td width="50%" align="center"><a href="http://www.spotlighthometours.com/" target="_blank"><img src="http://www.spotlighthometours.com/images/email-campaigns/videos-of-the-week/learn-more.gif" alt="Learn More" border="0" /></a></td>
        </tr>
        </table></td>
        </tr>
        <?php if( strlen($_SESSION['secondaryImg'])):?>
        <?php 
        	preg_match('|([0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4})|',$_SESSION['secondaryImg'],$matches);
        	$date = $matches[1];
        	preg_match('|\.([a-z]{3,4})$|',$_SESSION['secondaryImg'],$matches);
        	$ext = $matches[1];
        ?>
        
        <tr>
            <td height="30" align="center" valign="middle" bgcolor="#0e0e0e">&nbsp;</td>
        </tr>
        <tr>
            <td height="30" align="center" >&nbsp;</td>
        </tr>
        <tr>
            <td height="80" align="center" ><img src="<?php echo "{$server}/images/email-campaigns/videos-of-the-week/$date/secondary.$ext" . randomString();?>" alt="Spotlight Home Tours" border="0"></td>
        </tr>   
        <?php endif; ?>
        <?php if( strlen($_SESSION['secondaryDesc']) ):?>
        <tr>
            <td height="80" align="center">
                <table width="100%" border="0" cellpadding="20" cellspacing="0">
                    <tr>
                        <td>&nbsp;</td>
                        <?php if( strlen($_SESSION['secondaryDesc'])):?>
                        <td align="center" id="desc-txt" style='line-height: 18px;'><p><?php echo nl2br( html_entity_decode($_SESSION['secondaryDesc']));?><br>
                        <?php endif;?>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php endif;?>

        <tr>
        <td>
            <table width="100%" border="0" cellpadding="2" cellspacing="0" id="contact-info">
                <tr>
                    <td height="40" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td><p><b id="contact-hdr">Contact Us</b></p></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><p><a href="tel:888-838-8810" value="+18888388810" target="_blank" id="contact-lnk1">888-838-8810</a></p></td>
                    <td align="right">New to Spotlight?<a href="http://www.spotlighthometours.com/register/" target="_blank"> Sign up!</a></td>
                </tr>
        <tr>
        <td><p><a href="mailto:info@spotlighthometours.com" target="_blank" id="contact-lnk2">info@spotlighthometours.com</a></p></td>
        <td>&nbsp;</td>
        </tr>
        <tr>
        <td height="40" colspan="2">&nbsp;</td>
        </tr>
        </table></td>
        </tr>
        <!--<tr bgcolor="#101213">
        <td align="center"><table width="660" border="0" align="center" cellpadding="20" cellspacing="0">
        <tr bgcolor="#101213">
        <td align="center"><a href="{{{unsubscribe}}}" id="unsubscribe">Click here to unsubscribe</a></td>
        </tr>
        <tbody>
        </tbody>
        </table></td>
        </tr>-->
        <tr>
        <td height="30" bgcolor="#151719"></td>
        </tr>
        </tbody>
        </table></td>
        <td>&nbsp;</td>
        </tr>
        </table></td>
        </tr>
        </table>
        </p>