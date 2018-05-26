<?php

/*
** connect credentials
*/

include("repository_inc/data.php");

$find_text = "</rules>";
$file_name = "web.config";

function append_file($str_to_insert, $find_text, $file_name, $check = '')
{
    $f = fopen($file_name, "r+");
    $oldstr = file_get_contents($file_name);

    // reading and updating file contents
    if ($buffer = file_get_contents($file_name, true)) {
        if (!empty($buffer)) {
            if (strpos($buffer, $find_text) !== false) {
                if ((strpos($buffer, $check) !== false) && !empty($check)) {
                } else {
                    $pos = strrpos($buffer, $find_text);
                    $newstr = substr_replace($oldstr, $str_to_insert, $pos, 0);
                    file_put_contents($file_name, $newstr);
                }
            }
        }
    }
    fclose($f);
}


/*
** Data pull logic
*/

$con = mysqli_connect($server, $username, $password);
mysqli_select_db($con, $database);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$query = "SELECT t.tourID, t.address
FROM `tours` t
INNER JOIN users u
ON t.userID = u.userID
LEFT JOIN tour_subdomain s
ON s.tourID = t.tourID
WHERE 
/* t. `tourTypeID` =373 AND */
s.tourID IS NULL
AND u.`BrokerageID` IN (
1322,
1336,
1335,
1334,
1333,
1332,
1331,
1330,
1329,
1328,
1327,
1326,
1325,
1324,
1358
)
AND t.createdON >= '2016-07-01'
LIMIT 1000";

$retval = mysqli_query($con, $query);

while ($row = mysqli_fetch_array($retval)) {
    // sanitizing the address for sub domain
    $address = preg_replace('/^-+.|-+$/', '', strtolower(preg_replace('/[^a-zA-Z0-9]+/', '', $row['address'])));

    if (empty($address) || empty($row['tourID'])) {
        continue;
    }

    $insert_query = "INSERT INTO `tour_subdomain`(`tourID`, `sub_domain`) 
    VALUES ('" . $row['tourID'] . "', '" . $address . "')";
    mysqli_query($con, $insert_query);

    /*
    ** web config write logic 
    */
    $str_to_insert = '<rule name="' . $row['address'] . '" stopProcessing="true">
                    <match url="^tours/(.*)$" negate="true" />
                    <conditions>
                        <add input="{HTTP_HOST}" pattern="' . $address . '.cbrbhome.com" />
                    </conditions>
                    <action type="Redirect" url="http://' . $address . '.cbrbhome.com/tours/tour.php?tourid=' . $row['tourID'] . '" appendQueryString="false" />
                </rule>';

    // NOT Appending to web.config now. using a different logic.
    // append_file($str_to_insert, $find_text, $file_name, $address);

}
?>