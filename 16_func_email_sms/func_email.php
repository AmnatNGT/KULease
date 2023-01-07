<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';

require_once "define_email_sms_user.php";

// รับข้อมูลที่ส่งมา
// รับ email = email ปลายทาง
// รับ header = หัวเรื่อง
// รับ detail = เนื้อหาที่จะส่ง
function sendEmail($email, $header, $detail)
{

    $mail = new PHPMailer();

    //SMTP setting
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    // username email ที่ใช่ส่ง
    $mail->Username   = un_email;
    //รหัสเมล์ที่ใช้ส่ง
    $mail->Password   = pw_email;
    $mail->Port = 465; //465
    $mail->SMTPSecure = "ssl"; //ssl tls
    $mail->CharSet = "utf-8"; // ภาษาไทย

    //Email Setting
    $mail->isHTML(true);
    $mail->setFrom('kulease2565kps@gmail.com', 'KU ระบบบริหารสัญญาเช่า');
    $mail->addAddress($email); //Email ปลายทาง
    $mail->Subject = $header;
    $mail->Body = $detail;

    if ($mail->send()) {
        $status = "success";
        $responce = "Email is sent";

        return "send success";
        
    } else {
        $status = "failed";
        $responce = "Somthing is wrong" . $mail->ErrorInfo;
    }

    //exit(json_encode(array("status" => $status, "responce" => $responce)));
}
