<!doctype html>
<html>
<head>
	<script>
		if (isset($_POST['registration-submission'])) {
$base_url = 'https://api.citrixonline.com/G2W/rest';
$org_key = $_POST['organizer_key'];
$web_key = $_POST['webinar_key'];
$vals['body'] = (object) array(
'firstName' => $_POST['firstName'],
'lastName' => $_POST['lastName'],
'email' => $_POST['email']
);
$long_url = $base_url.'/organizers/'.$org_key.'/webinars/'.$web_key.'/registrants';
$header = array();
$header[] = 'Accept: application/json';
$header[] = 'Content-type: application/json';
$header[] = 'Accept: application/vnd.citrix.g2wapi-v1.1+json';
$header[] = 'Authorization: OAuth oauth_token=[euYYzlCtKrk1v6JW0jmLbsnyNcuh]';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $long_url);
curl_setopt( $ch, CURLOPT_POST, 1);
curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($vals['body']));
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
$decoded_response = json_decode($response);
if ($decoded_response->status == 'APPROVED') {
$register_result = true;
} else {
$register_result = false;
}
}
	</script>
	
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
	<form action="" method="POST" id="registration-form" class="webinar-signup">
			<input name="webinar_key" type="hidden" value="1899475993109205251" />
			<input name="organizer_key" type="hidden" value="94121843239558661" />
			<input name="firstName" type="text" placeholder="First Name">
			<input name="lastName" type="text" placeholder="Last Name">
			<input name="email" type="text" size="30" maxlength="50" placeholder="Email">
			<input type="submit" value="Register For Event" name="registration-submission" />
			</form>
</body>
</html>