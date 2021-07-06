<?php
$key = "SUyyWuztzE4jw74N1XXU446EVDkvcJd3";

$num = $_POST['phone'];
$num = str_replace('(', '', $num);
$num = str_replace(')', '', $num);
$num = str_replace('_', '', $num);
$num = str_replace('-', '', $num);
$num = str_replace(' ', '', $num);
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://sms.to/v1/verify/number",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS =>"{\n\t\"to\":\"$num\"}",
    CURLOPT_HTTPHEADER => array(
        "Accept: application/json",
        "Content-Type: application/json",
    "Authorization: Bearer $key"
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;