<?php

require("../../../connection.php");
session_start();

if (!$_SESSION['ofc_add']) {
    header("Location: ../../../index.php");
}

$ad_id = $_SESSION['ofc_add'];

//รับ Post ข้อมูล
$le_id = $_POST['le_id'];
$area_id = $_POST['area_name'];
$le_purpose = $_POST['le_purpose'];
$le_duration = $_POST['le_duration'];
$le_start_date = $_POST['le_start_date'];

//สร้าง Algor คำนวณวันสิ้นสุด ปีเริ่ม+ระยะเวลา, วันที่เริ่ม - 1
$le_end_date = date("Y-m-d", strtotime("+$le_duration year, -1 day", strtotime("$le_start_date")));

//ดึงวันที่ เวลา ปัจจุบัน
date_default_timezone_set('Asia/Bangkok');
$sign_do_start_lease_date = date("Y-m-d");
$sign_do_start_lease_time = date('H:i:s');

//ดึงข้อมูลผู้ลงนาม
{ //ผู้ให้เช่า
    $q = "SELECT * FROM lessor WHERE ls_status_show = 1";
    $result_q = mysqli_query($conn, $q);
    $row_q = mysqli_fetch_assoc($result_q);
    $boss = $row_q['ls_id'];

    //เจ้าหน้าที่ลงนาม
    $ofc_2 = "SELECT * FROM officer WHERE ofc_type = 2";
    $result_ofc_2 = mysqli_query($conn, $ofc_2);
    $row_ofc_2 = mysqli_fetch_assoc($result_ofc_2);
    $ofc_22 = $row_ofc_2['ofc_id'];

    //นิติกร
    $ofc_3 = "SELECT * FROM officer WHERE ofc_type = 4";
    $result_ofc_3 = mysqli_query($conn, $ofc_3);
    $row_ofc_3 = mysqli_fetch_assoc($result_ofc_3);
    $ofc_33 = $row_ofc_3['ofc_id'];
}

//Upload file เพิ่มเติมของผู้เช่า + เพิ่มข้อมูลลง lease db
{
    $file_ad = $_FILES['oth_file'];
    $fileName_ad = $_FILES['oth_file']['name'];
    $fileTmpName_ad = $_FILES['oth_file']['tmp_name'];
    $fileSize_ad = $_FILES['oth_file']['size'];
    $fileError_ad = $_FILES['oth_file']['error'];
    $fileType_ad = $_FILES['oth_file']['type'];

    $fileEXT_ad = explode('.', $fileName_ad);
    $fileActualExt_ad = strtolower(end($fileEXT_ad));
    $allowed_ad = array('pdf');

    if (in_array($fileActualExt_ad, $allowed_ad)) {
        if ($fileError_ad === 0) {

            //ตั้งชื่อไฟล์ เพิ่มเติมของผู้เช่า >> oth_file_le_ไอสัญญา.pdf (oth_file_le_1.pdf)
            $fileNameNew_ad = "oth_file_le_" . $le_id ."." . $fileActualExt_ad;
            //นำไฟล์ไปเก็บไว้ที่ Folder ../../../file_uploads/other_file_lease/
            $fileDestination_ad = '../../../file_uploads/other_file_lease/' . $fileNameNew_ad;
            move_uploaded_file($fileTmpName_ad, $fileDestination_ad);

            //เพิ่มข้อมูลลง lease db
            $q3 = "UPDATE lease SET le_type='1', 
                                    area_id='$area_id', 
                                    le_purpose='$le_purpose', 
                                    le_duration='$le_duration',
                                    le_start_date='$le_start_date',
                                    le_end_date='$le_end_date',
                                    ofc_id = '$ad_id',
                                    le_oth_file = '$fileNameNew_ad',
                                    le_sign_ofc1 = '$ofc_22',
                                    le_sign_ofc2 = '$ofc_33',
                                    le_sign_boss = '$boss'
                                    WHERE le_id = $le_id ";
            $result_q3 = mysqli_query($conn, $q3);
        }
    }
}

//Upload file ข้อตกลงแนบท้าย
{
    $file_ad_2 = $_FILES['last_file'];
    $fileName_ad_2 = $_FILES['last_file']['name'];
    $fileTmpName_ad_2 = $_FILES['last_file']['tmp_name'];
    $fileSize_ad_2 = $_FILES['last_file']['size'];
    $fileError_ad_2 = $_FILES['last_file']['error'];
    $fileType_ad_2 = $_FILES['last_file']['type'];

    $fileEXT_ad_2 = explode('.', $fileName_ad_2);
    $fileActualExt_ad_2 = strtolower(end($fileEXT_ad_2));
    $allowed_ad_2 = array('pdf');

    if (in_array($fileActualExt_ad_2, $allowed_ad_2)) {
        if ($fileError_ad_2 === 0) {
            //ตั้งชื่อไฟล์ เพิ่มเติมของผู้เช่า >> last_file_le_ไอสัญญา.pdf (last_file_le_1.pdf)
            $fileNameNew_ad_2 = "last_file_le_" . $le_id ."." . $fileActualExt_ad_2;
            //นำไฟล์ไปเก็บไว้ที่ Folder ../../../file_uploads/last_file_lease/
            $fileDestination_ad_2 = '../../../file_uploads/last_file_lease/' . $fileNameNew_ad_2;
            move_uploaded_file($fileTmpName_ad_2, $fileDestination_ad_2);

            //เพิ่มข้อมูลลงชื่อไฟล์  ข้อตกลงแนบท้าย ลง lease db
            $q2 = "UPDATE lease SET le_last_file ='$fileNameNew_ad_2' 
                                    WHERE le_id = $le_id ";
            $result_q2 = mysqli_query($conn, $q2);
        }
    }
}

//เพิ่มข้อมูล
if ($result_q3 && $result_q2) {
    //ข้อมูล money db
    //ค่าเช่ารายเดือน
    {
        //คำนวณวันที่ต้องจ่าย ทุกวันที่ 5 ของทุกเดือน
        $cal_le_start_date = $_POST['le_start_date'];
        //สมมุติวันที่ 30/1/2022
        $date = date("d", strtotime(("$cal_le_start_date")));
        //30-5 = 25
        $date = $date - 5;
        //เดือน + 1 >> 30/2/2022
        //และ -วันที่ ที่คำนวณ >> 30-25 = 5 >> 5/2/2022
        $date_pay = date("Y-m-d", strtotime("+1 month, -$date day", strtotime("$cal_le_start_date")));

        //รับค่าเช่าเดือนละ
        $mn_month = $_POST['mn_month'];
        $mn_status = 0;
        $mn_first_pay = 1;

        //update ลง tabel money
        $q6 = "UPDATE money SET mn_cost = '$mn_month',
                                                mn_date_pay = '$date_pay',
                                                mn_first_pay = '$mn_first_pay',
                                                mn_status = '$mn_status'
                                                WHERE mn_type='3' AND le_id = $le_id ";
        $result_q6 = mysqli_query($conn, $q6);
    }

    //แก้ไขสถานะพื้นที่เช่า =1
    {
        $q7 = "UPDATE area SET area_status='1'
                           WHERE area_id = $area_id ";
        $result_q7 = mysqli_query($conn, $q7);
    }

    //update status lease 
    //ให้ st_add_lease='1' 
    //st_mn_pay='0',
    {
        $q8 = "UPDATE status_lease SET st_add_lease='1', 
                                       st_mn_pay='0',
                                       d_ad_le = '$sign_do_start_lease_date',
                                       t_ad_le = '$sign_do_start_lease_time'
                                       WHERE le_id = $le_id ";
        $result_q8 = mysqli_query($conn, $q8);
    }
} else {
    echo "<script type='text/javascript'>";
    echo "alert('มีความผิดพลาด');";
    echo "window.location='../02_1_do_lease.php';";
    echo "</script>";
}

//ตรวจสอบซ้ำก่อนไปหน้าถัดไป
if ($result_q2 && $result_q3 && $result_q6 && $result_q7 && $result_q8) {

    //แจ้งเตือนเจ้าหน้าที่การเงินทุกคน
    {
        //หาข้อมูลเจ้าหน้าที่การเงิน 
        $sql1 = "SELECT * FROM officer 
        WHERE ofc_type = 3
        AND ofc_status_use = 1";
        $result1 = mysqli_query($conn, $sql1);

        //หาเลขที่อ้างอิงสัญญา
        $sql2 = "SELECT * FROM lease 
        WHERE le_id = $le_id";
        $result2 = mysqli_query($conn, $sql2);
        $r2 = mysqli_fetch_assoc($result2);
        //เลขที่อ้างอิงสัญญาเช่า
        $le_no = $r2['le_no'];

        while ($r1 = mysqli_fetch_assoc($result1)) { // วนลูปเจ้าหน้าที่การเงินทุกคน

            //email tel ของเจ้าหน้าที่การเงิน
            $email = $r1['ofc_email'];
            $tel = $r1['ofc_tel'];
            $header = "แจ้งเตือน การชำระค่าเช่าล่วงหน้า และเงินประกัน";
            $detail = "สัญญาเพื่อร้านค้าหรือพาณิชย์ เลขที่สัญญาเช่า $le_no ต้องชำระค่าเช่าล่วงหน้า และเงินประกัน";

            //send email to เจ้าหน้าที่การเงิน
            require_once "../../../16_func_email_sms/func_email.php";
            $send_email = sendEmail($email, $header, $detail);

            //send sms to เจ้าหน้าที่การเงิน
            require_once "../../../16_func_email_sms/func_sms.php";
            $send_sms = sendSMS($tel, $detail);
        }
    }

    // เมื่อเสร็จแล้วไปที่หน้า ../02_1_do_lease.php
    echo "<script type='text/javascript'>";
    echo "alert('เพิ่มข้อมูลสัญญาเช่า: ประเภทสัญญาเพื่อร้านค้าหรือพาณิชย์ สำเร็จ \\nรอเจ้าหน้าที่การเงิน เพิ่มข้อมูลค่าเช่าล่วงหน้า และเงินประกัน'); ";
    echo "window.location='../02_1_do_lease.php';";
    echo "</script>";
} else {
    echo "<script type='text/javascript'>";
    echo "alert('มีความผิดพลาด');";
    echo "window.location='../02_1_do_lease.php';";
    echo "</script>";
}
