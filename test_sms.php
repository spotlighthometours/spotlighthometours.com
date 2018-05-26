<?php 

$to = "8017830197@tmomail.net";
$from = "info@spotlighthometours.com";
$message = "This is a text message\nNew line...";
$headers = "From: $from\n";
mail($to, '', $message, $headers);
print "<p class='success' >SMS Sended!</p>";
?>