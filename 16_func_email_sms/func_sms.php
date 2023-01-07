<?php

require_once "define_email_sms_user.php";

//SMS
// รับข้อมูลที่ส่งมา
// รับ number = เบอร์ ปลายทาง
// รับ data = เนื้อหาที่จะส่ง
function sendSMS($number, $data)
{

    // username และ password ที่ใช้ส่ง
    $un = un_sms; 
    $pw = pw_sms;

    $curl = curl_init();
    
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://thsms.com/api/rest?username=$un&password=$pw&method=send&from=SMSOTP&to=$number&message=KU_SMS : $data",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    //echo $response;

    return "send success";
    
}
