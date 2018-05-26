<?PHP
	// Include appplication's global configuration
	require_once('../../repository_inc/classes/inc.global.php');
	showErrors();
	$socialcontentheader = new socialcontentheader();
	$socialcontentheader = $socialcontentheader->user($_REQUEST['userID'])->get();
	$headerHTML = $socialcontentheader->generatHeaderPage();
	$contentHTML = '
		<section role="banner">
			<div style="background-color:#FFF;">
				<iframe src=\'http://www.spotlighthometours.com/microsites/contentitem.php?contentID='.$_REQUEST['contentID'].'&userID='.$_REQUEST['userID'].'\' style="width:100%; height: 100%; position:absolute; top:'.$socialcontentheader->height.'px;left:0px;" frameborder="0" id="contentFrame"></iframe>
			</div>
		</section>
		<script>
			$("#contentFrame").ready(function(){
				document.getElementById(\'contentFrame\').contentWindow.addXMLRequestCallback = function(callback){
					var oldSend, i;
					if( document.getElementById(\'contentFrame\').contentWindow.XMLHttpRequest.callbacks ) {
						// we\'ve already overridden send() so just add the callback
						document.getElementById(\'contentFrame\').contentWindow.XMLHttpRequest.callbacks.push( callback );
					} else {
						// create a callback queue
						document.getElementById(\'contentFrame\').contentWindow.XMLHttpRequest.callbacks = [callback];
						// store the native send()
						oldSend = document.getElementById(\'contentFrame\').contentWindow.XMLHttpRequest.prototype.open;
						// override the native send()
						document.getElementById(\'contentFrame\').contentWindow.XMLHttpRequest.prototype.open = function(){
							// process the callback queue
							// the xhr instance is passed into each callback but seems pretty useless
							// you can\'t tell what its destination is or call abort() without an error
							// so only really good for logging that a request has happened
							// I could be wrong, I hope so...
							// EDIT: I suppose you could override the onreadystatechange handler though
							if(arguments[1].indexOf(\'repository_queries/proxy.php?url=\')>-1){

							}else{
								arguments[1] = \'http://www.spotlighthometours.com/repository_queries/proxy.php?url=\'+arguments[1];
							}
							console.log(arguments[1]);
							for( i = 0; i < document.getElementById(\'contentFrame\').contentWindow.XMLHttpRequest.callbacks.length; i++ ) {
								document.getElementById(\'contentFrame\').contentWindow.XMLHttpRequest.callbacks[i]( this );
							}
							// call the native send()
							oldSend.apply(this, arguments);
						}
					}
				}

				// e.g.
				document.getElementById(\'contentFrame\').contentWindow.addXMLRequestCallback( function( xhr ) {
					xhr.onerror = function(e){
						console.log(e);
					}
				});
			});

			$("#contentFrame").on("load", function(){
				var iframe = $(\'#contentFrame\').contents();
				iframe.find("a").attr("target", "_parent");
				iframe.find("#pagelet_bluebar, #outdatedBrowserBanner, ._5hn6").css("display", "none");
			});

			function setFrame(){
				$("#contentFrame").height($(window).height()-'.$socialcontentheader->height.');
			}
			setFrame();
			window.addEventListener("orientationchange", function() {
				setFrame();
			});
		</script>';
		$outputHTML = str_replace("</body>",$contentHTML."</body>",$headerHTML);
		echo $outputHTML;
?>