<?php
$verify = $_POST['verify'];
$phone = $_POST['phone'];
$phone = str_replace('+', '', $phone);
$phone = str_replace('(', '', $phone);
$phone = str_replace(')', '', $phone);
$phone = str_replace(' ', '', $phone);
$phone = str_replace('-', '', $phone);
$phone = str_replace('_', '', $phone);

$get_code = file_get_contents($phone.'.vsr');

if($verify == $get_code){
    $response = [
        "status" => "success",
    ];
    unlink($phone.'.vsr');
}
else{
    $response = [
        "status" => "error",
        "desc" => "Код из смс не верный!"
    ];
}

echo json_encode($response);