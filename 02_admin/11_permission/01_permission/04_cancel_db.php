<?php
require("../../../connection.php");

session_start();

if (!$_SESSION['ofc_add']) {
    header("Location: ../../../index.php");
}


// ผู้ให้เช่า
if (isset($_GET['n1'])) {

    // รับ id
    $id = $_GET['n1'];

    // update สิทธิ์การใช้งาน
    $sql = "UPDATE lessor SET ls_status_use ='-1', 
                              ls_status_show = '0' 
                            WHERE ls_id = $id";
    $result = mysqli_query($conn, $sql);

    //เรียก ข้อมูลผู้ให้เช่า
    $sql_ch = "SELECT * FROM lessor WHERE ls_id= $id ";
    $result_ch = mysqli_query($conn, $sql_ch);
    $row_ch = mysqli_fetch_assoc($result_ch);

    //email ไปหาผู้ให้เช่า
    require_once "../../../16_func_email_sms/func_email.php";
    $email = $row_ch['ls_email'];
    $header = "แจ้งเตือน การยกเลิกสิทธิ์การใช้ระบบบริหารสัญญาเช่า";
    $detail = "ผู้ดูแลระบบ ทำการยกเลิกสิทธิ์การใช้ระบบบริหารสัญญาเช่า เรียบร้อยแล้ว";
    $send_email = sendEmail($email, $header, $detail);

    //SMS ไปหาผู้ให้เช่า
    require_once "../../../16_func_email_sms/func_sms.php";
    $number = $row_ch['ls_tel'];
    $send_sms = sendSMS($number, $detail);

} 
// เจ้าหน้าที่
else if (isset($_GET['n2']) || isset($_GET['n3']) || isset($_GET['n4']) || isset($_GET['n5']) || isset($_GET['n6'])) {

    // รับ id
    if (isset($_GET['n2'])) {
        $id = $_GET['n2'];
    } else if (isset($_GET['n3'])) {
        $id = $_GET['n3'];
    } else if (isset($_GET['n4'])) {
        $id = $_GET['n4'];
    } else if (isset($_GET['n5'])) {
        $id = $_GET['n5'];
    } else if (isset($_GET['n6'])) {
        $id = $_GET['n6'];
    }

    // update สิทธิ์การใช้งาน
    $sql = "UPDATE officer SET ofc_status_use ='-1', 
    ad_id ='$ad_id' 
    WHERE ofc_id = $id";
    $result = mysqli_query($conn, $sql);

    //เรียก ข้อมูลเจ้าหน้าที่
    $sql_ch = "SELECT * FROM officer WHERE ofc_id= $id ";
    $result_ch = mysqli_query($conn, $sql_ch);
    $row_ch = mysqli_fetch_assoc($result_ch);

    //mail หาเจ้าหน้าที่
    require_once "../../../16_func_email_sms/func_email.php";
    $email = $row_ch['ofc_email'];
    $header = "แจ้งเตือน การยกเลิกสิทธิ์การใช้ระบบบริหารสัญญาเช่า";
    $detail = "ผู้ดูแลระบบ ทำการยกเลิกสิทธิ์การใช้ระบบบริหารสัญญาเช่า เรียบร้อยแล้ว";
    $send_email = sendEmail($email, $header, $detail);

    //SMS หา เจ้าหน้าที่
    require_once "../../../16_func_email_sms/func_sms.php";
    $number = $row_ch['ofc_tel'];
    $send_sms = sendSMS($number, $detail);
}

if ($result) {
    echo "<script type='text/javascript'>";
    echo "alert('ยกเลิกสิทธิ์การใช้งานสำเร็จ');";
    // เสร็จแล้วไปหน้า 01_permission.php
    echo "window.location='01_permission.php';";
    echo "</script>";
} else {
    echo "<script type='text/javascript'>";
    echo "alert('มีความผิดพลาด');";
    echo "window.location='01_permission.php';";
    echo "</script>";
}
