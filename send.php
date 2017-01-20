<?php
    date_default_timezone_set('Europe/Zaporozhye');
    require_once 'connect.php';
    $fio = htmlspecialchars($_POST['fio']);
    $phone = htmlspecialchars($_POST['phone']);
    $date = $_POST['date'];

    $fio = trim($fio);
    $phone_d = preg_replace('/\D/','', trim($phone)); //оставляю только числа

    
    $errors = array();
    
    if (preg_match("/\d/", $fio))
        array_push($errors, "Имя не может содержать цифры");
    
    //проверка на повторную подачу заявки за день
    $check_phone = mysql_query("SELECT date_request, phone FROM request_number WHERE phone = '{$phone_d}'");
    while ($ph = mysql_fetch_assoc($check_phone)){
        if ($ph['date_request'] > date('Y-m-d H:i:s', strtotime('-1 day'))) {
            //если дата из бд больше чем вчерашняя
            $is_norepeat = false;
            array_push($errors, "Сегодня Вы уже подали заявку");
        } else {
            $is_norepeat = true; //для проверки повторной заявки
        }  
    }
    
    if ($is_norepeat) {
        //выполняю код дальше, значит этого номера нет вообще в базе
        if (empty($errors)) {
            mail("uhnoroma@mail.ru", "Заявка с сайта", "ФИО: ".$fio."\nТелефон: ".$phone."\nЖелаемая дата и время: ".$date, "From: citrus.ho.ua\r\n");
            if (!empty($date)) {
                if (!mysql_query("INSERT INTO request_number (name, phone, date) VALUES ('{$fio}', '{$phone_d}', '{$date}');")) 
                array_push($errors, mysql_error());
            } else {
                if (!mysql_query("INSERT INTO request_number (name, phone) VALUES ('{$fio}', '{$phone_d}');")) 
                array_push($errors, mysql_error());
            }
        }
    }

    $json = array('errors' => $errors, 'name' => $fio, 'phone' => $phone, 'ph' => $phone_d);
    echo json_encode($json);

?>