<?PHP
	// APPLICATION GLOBAL CONFIG
	require_once($_SERVER['DOCUMENT_ROOT'].'/repository_inc/classes/inc.global.php');
	
	$randNum = rand(9999,9999999999999999) . uniqid();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Curt Casper | 801.671.1725 | Utah Real Estate Agent</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/css/bootstrapValidator.css">
<link rel="stylesheet" type="text/css" href="styles/agent-sm.css?randNum=<?PHP echo $randNum ?>" media="screen, handheld" />
<link rel="stylesheet" type="text/css" href="styles/agent-lrg.css?randNum=<?PHP echo $randNum ?>" media="screen  and (min-width: 40.5em)" />
<link rel="stylesheet" type="text/css" href="../repository_css/fancy-box/jquery.fancybox.css"/>
<link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic|Raleway:400,200,300,500,700,600,800,900' rel='stylesheet' type='text/css'>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../repository_css/contact.css"/>
</head>

<body class="active" data-role="page">
<header role="banner" class="banner">
	<div class="content">
		<div class="agent"> <img src="images/curt-casper.jpg" height="80" />
			<div class="contact-info"> Curt Casper
				<div class="phone">(801) 671.1725</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="brokerage"><a href="#"><img src="images/equity-real-estate.jpg" height="80" border="0" /></a></div>
		<div class="clear"></div>
	</div>
	<nav class="navbar navbar-default navbar-static-top">
		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav" aria-expanded="false"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
		<div style="margin-top:13px;">MENU</div>
		</button>
		<div class="collapse navbar-collapse" id="main-nav">
			<ul role="navigation" class="nav navbar-nav">
				<li><a rel="external" href="#home">Home</a></li>
				<li><a rel="external" href="#search">Search</a></li>
				<li><a rel="external" href="agent-listing.php?mlsID=1412442">My Listings</a></li>
				<li><a rel="external" href="#communites">Communites</a></li>
				<li><a rel="external" href="#financing">Financing</a></li>
				<li><a rel="external" href="#about">About</a></li>
				<li><a rel="external" href="#contact">Contact</a></li>
			</ul>
		</div>
	</nav>
</header>

<div class="container center-block" style="padding-top: 245px; ">

  <div class="row">
    <div class="center-block col-md-4">
     <h2>Nick Lovato</h2>
      <h3 class="loanOfficer_title" >Loan Officer</h3>
      <br />
      <p style="font-size: 20px;">Thank you for visiting my website. I take great pride in offering superior service to my clients. If you&apos;re in the market to buy or sell a home please give me a call. I&apos;d be happy to discuss your needs and provide advice at no obligation to you.
My website provides you with access to all listings available on the MLS system regardless of who the listing agent or brokerage may be. Listings are updated frequently throughout the day giving you the information you need. When you need it
	  </p>
	  <br /><br />
	  <form action="" class="form-inline">
	  	<div class="form-group col-md-6 text-right" >
        	<input type="text" class="form-control" placeholder="Name">
    	</div>

    	<div class="form-group col-md-6" >    
         	<input type="text" class="form-control" placeholder="Email">   
    	</div>
    	<br /><br /><br />
	    <div class="col-md-12 text-center ">
	     	<textarea rows="4" cols="47" placeholder="Comments" class="form-control" ></textarea>
	    </div>
	</div>
 </div>

	<br/><br />
	<div class="text-center">
      	 <button type="button" class="btn btn-success sharp">Submit</button>
	</div></form>
	
</div>
<br/><br /><br />

<footer class="row footer">
	<div class="content">
		<ul class="social-media">
			<li><a rel="external" href="#"><img src="images/icon/facebook.png" border="0" height="40" /></a></li>
			<li><a rel="external" href="#"><img src="images/icon/instagram.png" border="0" height="40" /></a></li>
			<li><a rel="external" href="#"><img src="images/icon/twitter.png" border="0" height="40" /></a></li>
			<li><a rel="external" href="#"><img src="images/icon/linkedin.png" border="0" height="40" /></a></li>
			<li><a rel="external" href="#"><img src="images/icon/youtube.png" border="0" height="40" /></a></li>
		</ul>
		<div class="vcenter logo"><a href="#"><img src="images/powered-by-spotlight.png" border="0" width="200" /></a></div>
		<div class="clear"></div>
	</div>
</footer>

</body>
</html>