<?php
require_once "connection.php";


//Email admin
//ดึงข้อมูลทั้งหมดจาก admin
{
    $sql = "SELECT * FROM admin ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    //Set email และ เบอร์ ของ Admin
    $email_admin = $row['ad_email'];
    $number = $row['ad_tel'];
}

//อัพเดต Email ผู้เช่า
//ถ้าผู้เช่ากด Verify email แล้วระบบจะส่งมาทำงานที่ส่วนนี้ โดยจะมี Id ผู้เช่่าส่งมาด้วย
if (isset($_GET['tn_id'])) {

    //รับ id
    $id = $_GET['tn_id'];

    //ถ้ากด verify ที่ Email แล้ว เปลี่ยนสถานะ verify =1
    $sql = "UPDATE tenant SET tn_verify='1' WHERE tn_id=$id ";
    $result = mysqli_query($conn, $sql);

    //alert email admin
    require_once "16_func_email_sms/func_email.php";
    $header = "แจ้งเตือนการอนุมัติการเข้างานระบบของ ผู้เช่า";
    $detail =
        'แจ้งเตือนผู้ดูแลระบบ ขณะนี้ได้มีผู้เช่าลงทะเบียนเข้าใช้งานระบบบริหารสัญญาเช่า มหาวิทยาลัยเกษตรศาสตร์ วิทยาเขตกำแพงแสน
         กรุณาตรวจสอบ เพื่ออนุมัติผู้ใช้ในการเข้าใช้งานระบบ';
    $send_email = sendEmail($email_admin, $header, $detail);

    //alert SMS admin
    require_once "16_func_email_sms/func_sms.php";
    $msg = "มีผู้เช่าลงทะเบียนเข้าใช้งานระบบบริหารสัญญาเช่า กรุณาตรวจสอบ";
    $send_sms = sendSMS($number, $msg);

    if ($result) {
        echo "<script type='text/javascript'>";
        echo "alert('ยืนยัน Email สำเร็จ \\nรอผู้ดูแลระบบอนุมัติเข้าใช้งานระบบ');";
        echo "window.location='index.php';";
        echo "</script>";
    } else {
        echo "<script type='text/javascript'>";
        echo "alert('เกิดข้อผิดพลาด');";
        echo "</script>";
    }
}

//อัพเดต Email เจ้าหน้าที่
//ถ้าเจ้าหน้าที่กด Verify email แล้วระบบจะส่งมาทำงานที่ส่วนนี้ โดยจะมี Id เจ้าหน้าที่ส่งมาด้วย
else if (isset($_GET['ofc_id'])) {

    //รับ id
    $id = $_GET['ofc_id'];

    //ถ้ากด verify ที่ Email แล้ว เปลี่ยนสถานะ verify =1
    $sql = "UPDATE officer SET ofc_verify='1' WHERE ofc_id=$id ";
    $result = mysqli_query($conn, $sql);

    //alert email admin
    require_once "16_func_email_sms/func_email.php";
    $header = "แจ้งเตือนการอนุมัติการเข้างานระบบของ เจ้าหน้าที่";
    $detail =
        'แจ้งเตือนผู้ดูแลระบบ ขณะนี้ได้มีเจ้าหน้าที่ลงทะเบียนเข้าใช้งานระบบบริหารสัญญาเช่า มหาวิทยาลัยเกษตรศาสตร์ วิทยาเขตกำแพงแสน
         กรุณาตรวจสอบ เพื่ออนุมัติผู้ใช้ในการเข้าใช้งานระบบ';
    $send_email = sendEmail($email_admin, $header, $detail);

    //alert SMS admin
    require_once "16_func_email_sms/func_sms.php";
    $msg = "มีเจ้าหน้าที่ลงทะเบียนเข้าใช้งานระบบบริหารสัญญาเช่า กรุณาตรวจสอบ";
    $send_sms = sendSMS($number, $msg);

    if ($result) {
        echo "<script type='text/javascript'>";
        echo "alert('ยืนยัน Email สำเร็จ \\nรอผู้ดูแลระบบอนุมัติเข้าใช้งานระบบ');";
        echo "window.location='index.php';";
        echo "</script>";
    } else {
        echo "<script type='text/javascript'>";
        echo "alert('เกิดข้อผิดพลาด');";
        echo "</script>";
    }
}

//อัพเดต Email ผู้ให้เช่า
//ถ้าผู้ให้เช่ากด Verify email แล้วระบบจะส่งมาทำงานที่ส่วนนี้ โดยจะมี Id ผู้ให้เช่าส่งมาด้วย
else if (isset($_GET['boss_id'])) {

    //รับ id
    $id = $_GET['boss_id'];

    //ถ้ากด verify ที่ Email แล้ว เปลี่ยนสถานะ verify =1
    $sql = "UPDATE lessor SET ls_verify='1' WHERE ls_id=$id ";
    $result = mysqli_query($conn, $sql);

    //alert email admin
    require_once "16_func_email_sms/func_email.php";
    $header = "แจ้งเตือนการอนุมัติการเข้างานระบบของ ผู้ให้เช่า";
    $detail =
        'แจ้งเตือนผู้ดูแลระบบ ขณะนี้ได้มีผู้ให้เช่าลงทะเบียนเข้าใช้งานระบบบริหารสัญญาเช่า มหาวิทยาลัยเกษตรศาสตร์ วิทยาเขตกำแพงแสน
         กรุณาตรวจสอบ เพื่ออนุมัติผู้ใช้ในการเข้าใช้งานระบบ';
    $send_email = sendEmail($email_admin, $header, $detail);

    //alert SMS admin
    require_once "16_func_email_sms/func_sms.php";
    $msg = "มีผู้ให้เช่าลงทะเบียนเข้าใช้งานระบบบริหารสัญญาเช่า กรุณาตรวจสอบ";
    $send_sms = sendSMS($number, $msg);

    if ($result) {
        echo "<script type='text/javascript'>";
        echo "alert('ยืนยัน Email สำเร็จ \\nรอผู้ดูแลระบบอนุมัติเข้าใช้งานระบบ');";
        echo "window.location='index.php';";
        echo "</script>";
    } else {
        echo "<script type='text/javascript'>";
        echo "alert('เกิดข้อผิดพลาด');";
        echo "</script>";
    }
}
