<?php
require("../../../connection.php");


session_start();

if (!$_SESSION['ofc_add']) {
    header("Location: ../../../index.php");
}

// รับ id เจ้าหน้าที่
$id = $_GET["idsub"];

// session id admin
$ad_id = $_SESSION['ofc_add'];

// เปลียนสถานะเจ้าหน้าที่ 1
$sql = "UPDATE officer SET ofc_status_use ='1', 
               ad_id ='$ad_id' 
               WHERE ofc_id = $id";
$result = mysqli_query($conn, $sql);

//เรียก ข้อมูลเจ้าหน้าที่
$sql_ch = "SELECT * FROM officer WHERE ofc_id= $id ";
$result_ch = mysqli_query($conn, $sql_ch);
$row_ch = mysqli_fetch_assoc($result_ch);

//email ไปหาเจ้าหน้าที่
require_once "../../../16_func_email_sms/func_email.php";
$email = $row_ch['ofc_email'];
$header = "แจ้งเตือน การอนุมัติเข้าใช้งานระบบ";
$detail = "ผู้ดูแลระบบ อนุมัติการเข้างานระบบบริหารสัญญาเช่า เรียบร้อยแล้ว";
$send_email = sendEmail($email, $header, $detail);

//SMS ไปหาเจ้าหน้าที่
require_once "../../../16_func_email_sms/func_sms.php";
$number = $row_ch['ofc_tel'];
$send_sms = sendSMS($number, $detail);


if ($result) {
    echo "<script type='text/javascript'>";
    echo "alert('อนุมัติ สำเร็จ');";
    // เสร็จแล้วไปหน้า 08_1_admin_check_tn_register.php
    echo "window.location='08_1_admin_check_tn_register.php';";
    echo "</script>";
} else {
    echo "<script type='text/javascript'>";
    echo "alert('มีความผิดพลาด');";
    echo "window.location='08_1_admin_check_tn_register.php';";
    echo "</script>";
}
