<?php

session_start();
require('../../../connection.php');

if (!$_SESSION['boss']) {
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
		$fileNameNew = $name . '.' . $fileActualExt;
		$fileDestination = '../../../file_uploads/doc_signs/' . $fileNameNew;
		move_uploaded_file($fileTmpName, $fileDestination);

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

		//update lease
		$q7 = "UPDATE status_lease SET otp_boss='$otp', otp_date_boss='$date_otp', otp_time_boss='$otp_time' WHERE le_id = $le_id ";
		$result_q7 = mysqli_query($conn, $q7);

		// id ผู้ให้เช่า
		$ofc_id = $_SESSION['boss'];

		//ดึงข้อมูล ผู้ให้เช่า
		$sql = "SELECT * FROM lessor WHERE ls_id = $ofc_id";
		$result2 = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result2);

		//send new otp
		$tn_email = $row['ls_email'];
		$tn_tel = $row['ls_tel'];

		//mailer ผู้ให้เช่า
		require_once "../../../16_func_email_sms/func_email.php";
		$header = "แจ้งเตือน OTP";
		$send_email = sendEmail($tn_email, $header, $otp);

		//SMS ผู้ให้เช่า
		require_once "../../../16_func_email_sms/func_sms.php";
		$send_sms = sendSMS($tn_tel, "OTP >> $otp");

		echo "<script type='text/javascript'>";
		echo "alert('กรุณาตรวจสอบที่ SMS โทรศัพท์ หรือ Email เพื่อยืนยัน OTP');";
		echo "window.location='05_confirm_otp.php';";
		echo "</script>";
	} else {
		echo "<script type='text/javascript'>";
		echo "alert('มีความผิดพลาด');";
		echo "window.location='01_lease.php';";
		echo "</script>";
	}
} else {
	echo "You cannot upload files of this type!";
}
