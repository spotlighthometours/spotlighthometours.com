<?PHP
	try{
		mail('jacob@spotlighthometours.com','test','test');
	}catch(Exception $e){
		print $e->message();
	}
?>