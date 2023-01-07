<?php

session_start();
require('../../../connection.php');

if (!$_SESSION['ofc_spcl']) {
    header("Location: ../../../index.php");
}

// รับข้อมูล
$le_id = $_POST['le_id'];
$le_no = $_POST['le_no'];


// นำ id สัญญาเก็บไว้ที่ session
$_SESSION['le_id'] = $le_id;

//ดึงวันที่ เวลา ปัจจุบัน
date_default_timezone_set('Asia/Bangkok');
$date = date("Y-m-d");
$time = date('H:i:s');

// File อากรแสตมป์
$file = $_FILES['le_stamp'];

$fileName = $_FILES['le_stamp']['name'];
$fileTmpName = $_FILES['le_stamp']['tmp_name'];
$fileSize = $_FILES['le_stamp']['size'];
$fileError = $_FILES['le_stamp']['error'];
$fileType = $_FILES['le_stamp']['type'];

$fileEXT = explode('.', $fileName);
$fileActualExt = strtolower(end($fileEXT));

$allowed = array('pdf');

if (in_array($fileActualExt, $allowed)) {
    if ($fileError === 0) {

        // ชื่อไฟล์
        $fileNameNew = "stamp_" . $le_id . "." . $fileActualExt;
        // folder ที่จะไปเก็บ
        $fileDestination = '../../../file_uploads/stamp_file/' . $fileNameNew;
        move_uploaded_file($fileTmpName, $fileDestination);
    }
}

//update status lease
// st_add_no='1'
$q8 = "UPDATE status_lease SET st_add_no='1',
                               d_add_no = '$date',
                               t_add_no = '$time'
                         WHERE le_id = $le_id ";
$result_q8 = mysqli_query($conn, $q8);

//update lease เพิ่มเลขที่สัญญา
$q7 = "UPDATE lease SET le_no_success='$le_no', 
                        le_stamp='$fileNameNew'
                        WHERE le_id = $le_id ";
$result_q7 = mysqli_query($conn, $q7);

//แจ้งเตือนผู้เช่า
{
    //หาข้อมูลผู้เช่า
    $sql3 = "SELECT * FROM lease l, tenant t 
                        WHERE l.le_id = $le_id
                        AND l.tn_id = t.tn_id";
    $result3 = mysqli_query($conn, $sql3);
    $r3 = mysqli_fetch_assoc($result3);

    // email ผู้เช่า
    $email3 = $r3['tn_email'];
    $header3 = "แจ้งเตือน สัญญาเช่า";
    $detail3 = "เลขที่สัญญาเช่า $le_no ขณะนี้อยู่ระหว่างการเช่า เรียบร้อยแล้ว";
     // เบอร์ ผู้เช่า
     $tel3 = $r3['tn_tel'];

    //ส่ง email ไปหาผู้เช่า
    require_once "../../../16_func_email_sms/func_email.php";
    $send_email3 = sendEmail($email3, $header3, $detail3);

    //ส่ง SMS หาผู้เช่า
    require_once "../../../16_func_email_sms/func_sms.php";
    $send_sms3 = sendSMS($tel3, $detail3);
}

//แก้ไขสถานะพื้นที่เช่า =2
{
    // id พื้นที่ของ สัญญานั้นๆ
    $area_id = $r3['area_id'];

    $q6 = "UPDATE area SET area_status='2'
                           WHERE area_id = $area_id ";
    $result_q6 = mysqli_query($conn, $q6);
}

if ($result_q7 && $result_q8) {
    echo "<script type='text/javascript'>";
    echo "alert('เพิ่มข้อมูลสำเร็จ'); ";
    // เมื่อเสร็จแล้วไปหน้า set_pdf/04_set_pdf_db.php
    echo "window.location='set_pdf/04_set_pdf_db.php';";
    echo "</script>";
} else {
    echo "<script type='text/javascript'>";
    echo "alert('มีความผิดพลาด');";
    echo "window.location='02_add_number.php?le_id=$le_id';";
    echo "</script>";
}
