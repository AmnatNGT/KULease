<?php

session_start();
require('../../../connection.php');

if (!$_SESSION['ofc_dmt']) {
	header("Location: ../../../index.php");
}

//ดึงวันที่ เวลา ปัจจุบัน
date_default_timezone_set('Asia/Bangkok');
$date = date("Y-m-d");
$time = date('H:i:s');

// รับ id_ สัญญา ที่เป็น session
$le_id = $_SESSION['le_id'];

$result = array();
$datetime_txt = date("YmdHis");
$imagedata = base64_decode($_POST['img_data']);

// ชื่อรูป
$fullname = $_POST['fullname'];
// นำชื่อรูปไว้ใน session
$_SESSION['fullname'] = $fullname;

//Location to where you want to created sign image
// ตำแหน่งที่จะให้เก็บรูป
$file_name = '../../../file_uploads/doc_signs/' . $fullname ;
file_put_contents($file_name, $imagedata);

// รายละเอียดข้อมูลที่จะส่งกลับ
$result['id'] = $datetime_txt; // เลขไอดีของ Record นี้ (ในที่นี้กำหนดเป็นวันเวลาบันทึก)
$result['file_name'] = $file_name; // ชื่อไฟล์รูปลายเซ็น
$result['fullname'] = $fullname; // ชื่อ-นามสกุล

//Set otp
date_default_timezone_set('Asia/Bangkok');
$date_otp = date("Y-m-d");
$time_otp = date('H:i:s');

//time otp end
$a = strtotime("+5 minutes", strtotime($time_otp));
$otp_time =  date('H:i:s', $a);

//random otp
$otp = mt_rand(10000, 99999);

//update status_lease
// ใส่ otp วันเวลาที่ otp หมดอายุ ของผู้จัดการพอพัก
$q7 = "UPDATE status_lease SET otp_s2='$otp', otp_date_s2='$date_otp', otp_time_s2='$otp_time' WHERE le_id = $le_id ";
$result_q7 = mysqli_query($conn, $q7);

// id ผู้จัดการพอพัก
$ofc_id = $_SESSION['ofc_dmt'];

//ดึงข้อมูล ผู้จัดการพอพัก
$sql = "SELECT * FROM officer WHERE ofc_id = $ofc_id";
$result2 = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result2);

//send new otp
$tn_email = $row['ofc_email'];
$tn_tel = $row['ofc_tel'];

//mail ผู้จัดการพอพัก
require_once "../../../16_func_email_sms/func_email.php";
$header = "แจ้งเตือน OTP";
$send_email = sendEmail($tn_email, $header, $otp);

//SMS ผู้จัดการพอพัก
require_once "../../../16_func_email_sms/func_sms.php";
$send_sms = sendSMS($tn_tel, "OTP >> $otp");

// ส่งค่ากลับไปที่หน้า 02_sign.php
echo json_encode($result);
