<?php
header('Content-type: text/html; charset=utf-8');

require_once ($_SERVER['DOCUMENT_ROOT'].'/app/Work_integration.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/Capcha.php');

define("DEBUG", true);
define("CAPCHA", false);

// DEBUG и подключенные заголовки
if (DEBUG == true) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

$capcha = new Capcha();
$capcha_res = json_decode($capcha->validate());

if(CAPCHA == false) {
    $capcha_res->status = true;
}
$result = [
    'stat' => 'true',
    'description' => 'Lead success create',
];
if (isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['phone']) && !empty($_POST['phone'])) {
    if ($capcha_res->status == true) {


        $integrate = new Work_integration('6M6u31fDqu*yqu!StP', '=3918:ExLK9onK4bh21$5DKjwSYTiSvbLji$uV');
        $get_locate = file_get_contents('https://freegeoip.live/json/' . $_SERVER['REMOTE_ADDR']);
        $get_locate = json_decode($get_locate);
        $result = $integrate->create_lead([
            'name' => $_POST['name'],
            'lastname' => $_POST['lastname'],
            'phone' => $_POST['phone'],
            'email' => $_POST['email'],
            'domain' => $_SERVER['SERVER_NAME'],
            'country' => $get_locate->country_code,
            'city' => $get_locate->city,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'age' => $_POST['age'],
            'prefix' => $_POST['prefix']??'',
//            'origin' => 'Tests.tst/work'
        ]);
//        var_dump($result);
        $result = json_decode($result, true);
        $result['stat'] = 'true';
        $result['href'] = '/thanks';
//        if($result['status'] !== 1){
//            $result['stat'] = 'false';
//            $result['description'] = 'Ошибка отправки!';
//        }
    } else {
        $result['stat'] = 'false';
        $result['description'] = 'Вы не прошли капчу!';
    }
}
else{
    $result['stat'] = 'false';
    $result['description'] = 'Обязательные поля не заполнены!';
}
echo json_encode($result);
