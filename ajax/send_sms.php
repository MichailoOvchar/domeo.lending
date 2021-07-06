<?php
$curl = curl_init();
$key = "SUyyWuztzE4jw74N1XXU446EVDkvcJd3";

$phone = $_POST['phone'];
$code = rand(100000, 999999);

$phone = str_replace('+', '', $phone);
$phone = str_replace('(', '', $phone);
$phone = str_replace(')', '', $phone);
$phone = str_replace(' ', '', $phone);
$phone = str_replace('-', '', $phone);
$phone = str_replace('_', '', $phone);

$code_file = fopen($phone.'.vsr', 'w+');
fwrite($code_file, $code);
fclose($code_file);


curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.sms.to/sms/send?api_key=$key&to=$phone&message=$code&sender_id=Verify",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
));

$responsed = curl_exec($curl);
$response = [
    'status' => true,
];
curl_close($curl);
echo json_encode($response);