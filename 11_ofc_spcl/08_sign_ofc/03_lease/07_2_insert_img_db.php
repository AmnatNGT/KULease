<?php

session_start();
require('../../../connection.php');

if (!$_SESSION['ofc_spcl']) {
	header("Location: ../../../index.php");
}

//ดึงวันที่ เวลา ปัจจุบัน
date_default_timezone_set('Asia/Bangkok');
$date = date("Y-m-d");
$time = date('H:i:s');

// รับ id สัญญที่เป็น session
$le_id = $_SESSION['le_id'];
// ชื่อรูป
$name = $_POST['name'];

// รูปลายเซน
$file = $_FILES['file_1'];
$fileName = $_FILES['file_1']['name'];
$fileTmpName = $_FILES['file_1']['tmp_name'];
$fileSize = $_FILES['file_1']['size'];
$fileError = $_FILES['file_1']['error'];
$fileType = $_FILES['file_1']['type'];

$fileEXT = explode('.', $fileName);
$fileActualExt = strtolower(end($fileEXT));

$allowed = array('jpeg', 'png', 'jpg');

if (in_array($fileActualExt, $allowed)) {
	if ($fileError === 0) {
		// ตั้งชื่อรูป 64003002192_tn_sign.png
		$fileNameNew = $name . '.' . $fileActualExt;
		$fileDestination = '../../../file_uploads/doc_signs/' . $fileNameNew;
		move_uploaded_file($fileTmpName, $fileDestination);

		// นำชื่อรูปเก็บไว้ที่ session
		$_SESSION['fullname'] = $fileNameNew;

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
		// ใส่ otp วันเวลาที่ otp หมดอายุ ของเจ้าหน้าที่พิเศษลงนาม
		$q7 = "UPDATE status_lease SET otp_s2='$otp', otp_date_s2='$date_otp', otp_time_s2='$otp_time' WHERE le_id = $le_id ";
		$result_q7 = mysqli_query($conn, $q7);

		// id เจ้าหน้าที่พิเศษ
		$ofc_id = $_SESSION['ofc_spcl'];

		//ดึงข้อมูลเจ้าหน้าที่พิเศษ
		$sql = "SELECT * FROM officer WHERE ofc_id = $ofc_id";
		$result2 = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result2);

		//send new otp
		$tn_email = $row['ofc_email'];
		$tn_tel = $row['ofc_tel'];

		//send otp
		//mailer เจ้าหน้าที่พิเศษ
		require_once "../../../16_func_email_sms/func_email.php";
		$header = "แจ้งเตือน OTP";
		$send_email = sendEmail($tn_email, $header, $otp);

		//SMS เจ้าหน้าที่พิเศษ
		require_once "../../../16_func_email_sms/func_sms.php";
		$send_sms = sendSMS($tn_tel, "OTP >> $otp");

		// เมื่อเสร็จไปหน้า 05_confirm_otp.php
		echo "<script type='text/javascript'>";
		echo "alert('กรุณาตรวจสอบที่ SMS โทรศัพท์ หรือ Email ของเจ้าหน้าที่ เพื่อยืนยัน OTP');";
		echo "window.location='05_confirm_otp.php';";
		echo "</script>";
	} else {
		echo "<script type='text/javascript'>";
		echo "alert('มีความผิดพลาด');";
		echo "window.location='01_lease.php'";
		echo "</script>";
	}
} else {
	echo "You cannot upload files of this type!";
}
