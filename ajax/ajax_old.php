<?php
$ip = fopen('ip.txt', 'a+');
$listip = $_SERVER['REMOTE_ADDR']."\r\n";
fwrite($ip, $listip);
fclose($ip);
$blocked = [
    "104.227.102.65",
    "185.217.196.183",
];
if(in_array($_SERVER['REMOTE_ADDR'], $blocked)){
    header('Content-type: text/html; charset=utf-8');
    header('HTTP/1.0 403 Forbidden');
    echo "Доступ закрыт";
}
else {
//Константы
    define("DEBUG", true);
    define("EMAIL", true);
    define("TMTGROUP", true);
    define("AFFBOARD", false);
    define("BOT", false);
    define("TABLE", false);
//END Константы

// DEBUG и подключенные заголовки
    if (DEBUG == true) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    } else {
        error_reporting(0);
    }
// Подключение способов отправки
    include($_SERVER['DOCUMENT_ROOT'] . "/app/MailSend.php");
    include($_SERVER['DOCUMENT_ROOT'] . '/app/tmtintegra.php');
    include($_SERVER['DOCUMENT_ROOT'] . '/app/AffboardLead.php');
    include($_SERVER['DOCUMENT_ROOT'] . '/app/Spliter_count.php');
    include($_SERVER['DOCUMENT_ROOT'] . '/app/Capcha.php');
//END DEBUG и подключенные заголовки


    $capcha = new Capcha();
    $capcha_res = json_decode($capcha->validate());
    if($capcha_res->status == true){

    if (isset($_POST['employer'])) {
        $result = [
            'stat' => 'true',
            'href' => '/thanksempl'
        ];
        $mail = new MailSend();

        $mail->sendMail(
            [
                "smedia.id@gmail.com",
                "smedia.perederko@gmail.com",
//                "newskyi.vs@gmail.com",
            ],

            $_SERVER['SERVER_NAME']." - Работодатель",

            [
                ["name" => "Название компании", "value" => $_POST['company']],
                ["name" => "Введите должность", "value" => $_POST['position']],
                ["name" => "Телефон", "value" => $_POST['phone']],
                ["name" => "Email", "value" => $_POST['email']],
//                ["name" => "Готов работать", "value" => $comment],
                ["name" => "Тип занятости", "value" => $_POST['type']],
                ["name" => "Описание работы", "value" => $_POST['description']],

            ],
            $_SERVER['SERVER_NAME']."/employer"
        );
        echo json_encode($result);
        die();
    }
//Поля с формы
    $result['href'] = "/thanks";
    if (isset($_POST['name'])) {
        $name = htmlspecialchars($_POST['name']);
    } else {
        $name = "";
    }
    if (isset($_POST['lastname'])) {
        $lastname = htmlspecialchars($_POST['lastname']);
    } else {
        $lastname = "";
    }
    if (isset($_POST['email'])) {
        $email = htmlspecialchars($_POST['email']);
    } else {
        $email = "";
    }
    if (isset($_POST['phone'])) {
        $phone = htmlspecialchars($_POST['phone']);
        $phone = str_replace('(', '', $phone);
        $phone = str_replace(')', '', $phone);
        $phone = str_replace(' ', '', $phone);
        $phone = str_replace('_', '', $phone);
        $phone = str_replace('-', '', $phone);
    } else {
        $phone = "";
    }
    if (isset($_POST['vacant'])) {
        $comment = htmlspecialchars($_POST['vacant']);
    } else {
        $comment = "";
    }
    if (isset($_POST['country'])) {
        $country = htmlspecialchars($_POST['country']);
        $country = json_decode(file_get_contents('https://freegeoip.live/json/'.$_SERVER['REMOTE_ADDR']))->country_code;
    } else {
        $country = "";
    }
    $ip = $_SERVER['REMOTE_ADDR'];


//END Поля с формы
    $result['stat'] = 'false';
    $result['description'] = 'Ошибка! Поля не заполнены!';
//Отправка формы
    if (isset($email) && !empty($email) && isset($lastname) && !empty($lastname) && isset($phone) && !empty($phone)) {
        $result['stat'] = 'true';

        $desc = 1;

        if (TMTGROUP == true) {

            $resp = new Spliter_count();

            $data = json_decode($resp->get_data());
            $limit = $data->response->limit;
            $count = $data->response->counter;
            $oper = $data->response->oper;
            $desc_limit = $data->response->desc;

//        $dbase = new database_pd();
//        $dbconnect = $dbase->connect('advbnbe1_bestbo', 'gjgjj785ghY554R', 'advbnbe1_bestbo', 'localhost');
            $prevoper = $oper;
            $next_oper = (int)$prevoper;


            if ($next_oper == 0) {
                $desc = 1;
            } else {
                $desc = 5;
            }
            if ($desc == $desc_limit && $count <= $limit) {
                $resp->count();
            }
            if ($count >= $limit) {
                if ($desc_limit == 1) {
                    $desc = 5;
                } else {
                    $desc = 1;
                }
            }
            $result['desc'] = $desc;
        }

        $pegi = fopen('pegi.txt', 'a+');
        fwrite($pegi, $_POST['peg'] . "\r\n");
        fclose($pegi);
        $desci = fopen('desc.txt', 'a+');
        fwrite($desci, $desc . "\r\n");
        fclose($desci);
      if ($_POST['peg'] >= 20) {

//        if ($_POST['peg'] !== "до 20" && $_POST['peg'] !== 0) {
            //EMAIL отправка
            if (EMAIL == true) {
                $mail = new MailSend();

                $mail->sendMail(
                    [
                        "smedia.id@gmail.com",
                        "smedia.perederko@gmail.com",
//                "newskyi.vs@gmail.com",
                    ],

                    $_SERVER['SERVER_NAME']." - Лид",

                    [
                        ["name" => "Имя", "value" => $name],
                        ["name" => "Фамилия", "value" => $lastname],
                        ["name" => "Телефон", "value" => $phone],
                        ["name" => "Email", "value" => $email],
//                ["name" => "Готов работать", "value" => $comment],
                        ["name" => "Код страны", "value" => $country],
                        ["name" => "Возраст", "value" => $_POST["peg"]],
                        ["name" => "desc", "value" => $desc],

                    ],
                    $_SERVER['SERVER_NAME']
                );

            }

            // интеграция TMTGROUP
            if (TMTGROUP == true) {
                $tmtapi = new tmtintegra();
                $desc = 5;
                $tmt = $tmtapi->createLead([
                    'first_name' => $name,
                    'second_name' => $lastname,
                    'status' => 1,
                    //'description' => isset($_POST['vacant']) ? "Желаемая должность: " . $_POST['vacant'] : "lead",
                    'description' => "lead",
                    'email' => trim($email),
                    'phone' => $phone,
                    'desk_id' => $desc,
                    'affiliate_id' => 'smedia',
                    'campaign_id' => $_SERVER['SERVER_NAME'],
                    'country' => $country
                ]);
                $best = fopen($_SERVER['SERVER_NAME'].'-' . date('d-i') . '.json', 'w+');
                fwrite($best, $tmt);
                fclose($best);
                if ($tmt == false) {
                    $result['stat'] = 'false';
                }

            }
            //END интеграция TMTGROUP
        } else {
            $mail = new MailSend();

            $mail->sendMail(
                [
                    "smedia.id@gmail.com",
                    "smedia.perederko@gmail.com",
//                "newskyi.vs@gmail.com",
                ],

                $_SERVER['SERVER_NAME']." - Лид | младше 20лет",

                [
                    ["name" => "Имя", "value" => $name],
                    ["name" => "Фамилия", "value" => $lastname],
                    ["name" => "Телефон", "value" => $phone],
                    ["name" => "Email", "value" => $email],
//                ["name" => "Готов работать", "value" => $comment],
                    ["name" => "Код страны", "value" => $country],
                    ["name" => "Возраст", "value" => $_POST["peg"]],

                ],
                $_SERVER['SERVER_NAME']
            );
        }
        //интеграция AFFBOARD
        if (AFFBOARD == true) {
            $affboard = new AffboardLead();

            $affboard->sendForm([
                'fname' => $name,
                'lname' => $lastname,
                'fullphone' => $_POST['phone'],
                'email' => $_POST['email'],
                'ip' => $_SERVER['REMOTE_ADDR'],
                'description' => $_POST['vacant'],
                'domain' => 'work-home.com',
            ]);

        }
        //END интеграция AFFBOARD

    }
//END Отправка формы

    }
    else{
        $result['stat'] = 'false';
        $result['description'] = 'Вы не прошли капчу!';
    }
    echo json_encode($result);
}
