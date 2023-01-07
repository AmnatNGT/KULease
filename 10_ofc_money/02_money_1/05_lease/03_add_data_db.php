<?php

require("../../../connection.php");
require("../../../09_function/cal_budget_year.php");
session_start();


if (!$_SESSION['ofc_mn']) {
    header("Location: ../../../index.php");
}

// id เจ้าหน้าที่การเงิน
$ad_id = $_SESSION['ofc_mn'];

// รับ id สัญญา
$le_id = $_POST['le_id'];

// วัน เวลาปัจจุบัน
date_default_timezone_set('Asia/Bangkok');
$sign_do_start_lease_date = date("Y-m-d");
$sign_do_start_lease_time = date('H:i:s');


//ข้อมูล money db

//ประเภทการชำระ เงินประกัน
if ($_POST['type_ad'] == null) {
    $type_dp = 'ad1';
}else{
    $type_dp = $_POST['type_ad'];
}

//เงินประกัน ที่เป็นใบเสร็จรับเงิน
if ($type_dp == 'ad2') {

    // CHECK ว่ามีข้อมูลมั้ย
    if ($_POST['mn_deposit']!= null and $_POST['dp_volume']!= null and $_POST['dp_no']!= null and $_POST['dp_date']!= null) {

        // ถ้ามีข้อมูล
        // รับข้อมูล
        $mn_deposit = $_POST['mn_deposit'];
        $dp_volume = $_POST['dp_volume'];
        $dp_no = $_POST['dp_no'];
        $dp_date = $_POST['dp_date'];

        $dp_budget_year = cal_year($dp_date);
        $dp_type = 1;
        $dp_status = 1;

        // file
        $file_dp = $_FILES['dp_file'];
        $fileName_dp = $_FILES['dp_file']['name'];
        $fileTmpName_dp = $_FILES['dp_file']['tmp_name'];
        $fileSize_dp = $_FILES['dp_file']['size'];
        $fileError_dp = $_FILES['dp_file']['error'];
        $fileType_dp = $_FILES['dp_file']['type'];

        $fileEXT_dp = explode('.', $fileName_dp);
        $fileActualExt_dp = strtolower(end($fileEXT_dp));
        $allowed_dp = array('pdf');
        if (in_array($fileActualExt_dp, $allowed_dp)) {
            if ($fileError_dp === 0) {

                // ชื่อไฟล์ DP_1_01_.pdf
                $fileNameNew_dp = "DP_1_" . $le_id . "." . $fileActualExt_dp;
                // เก็บที่ folder ../../../file_uploads/money_dp_ad/
                $fileDestination_dp = '../../../file_uploads/money_dp_ad/' . $fileNameNew_dp;
                move_uploaded_file($fileTmpName_dp, $fileDestination_dp);

                // up ข้อมูลขึ้น table money
                $q4 = "UPDATE money SET mn_cost = '$mn_deposit',
                                    mn_date_pay = '$dp_date',
                                    mn_volume = '$dp_volume',
                                    mn_no = '$dp_no',
                                    mn_file = '$fileNameNew_dp',
                                    mn_status = '$dp_status',
                                    ofc_id = '$ad_id',
                                    mn_date_pay_change = '$sign_do_start_lease_date',
                                    mn_time_pay_change='$sign_do_start_lease_time',
                                    mn_budget_year = '$dp_budget_year'
                                WHERE mn_type='1' AND le_id = $le_id ";
                $result_q4 = mysqli_query($conn, $q4);
            } else {
                echo "There was an error uploading your file!";
            }
        } else {
            echo "You cannot upload files of this type!";
        }
    } else {
        // ถ้าไม่มีข้อมูล
        // up ข้อมูลขึ้น table money
        $q4 = "UPDATE money SET mn_cost = '0',
                                mn_date_pay = null,
                                mn_volume = 'ได้รับยกเว้น',
                                mn_no = 'ได้รับยกเว้น',
                                mn_file = null,
                                mn_status = '100',
                                ofc_id = '$ad_id',
                                mn_date_pay_change = '$sign_do_start_lease_date',
                                mn_time_pay_change='$sign_do_start_lease_time',
                                mn_budget_year = null
                WHERE mn_type='1' AND le_id = $le_id ";
        $result_q4 = mysqli_query($conn, $q4);
    }
}
//เงินประกัน ที่เป็นใบสำคัญ
else if ($type_dp == 'ad1') {

    // CHECK ว่ามีข้อมูลมั้ย
    if ($_POST['mn_deposit']!= null and $_POST['dp_mn_no_im']!= null and $_POST['dp_no']!= null and $_POST['dp_date']!= null) {

        // ถ้ามีข้อมูล
        // รับข้อมูล
        $mn_deposit = $_POST['mn_deposit'];
        $dp_mn_no_im = $_POST['dp_mn_no_im'];
        $dp_date = $_POST['dp_date'];
        $dp_budget_year = cal_year($dp_date);
        $dp_type = 1;
        $dp_status = 1;

        // file
        $file_dp = $_FILES['dp_file'];
        $fileName_dp = $_FILES['dp_file']['name'];
        $fileTmpName_dp = $_FILES['dp_file']['tmp_name'];
        $fileSize_dp = $_FILES['dp_file']['size'];
        $fileError_dp = $_FILES['dp_file']['error'];
        $fileType_dp = $_FILES['dp_file']['type'];

        $fileEXT_dp = explode('.', $fileName_dp);
        $fileActualExt_dp = strtolower(end($fileEXT_dp));
        $allowed_dp = array('pdf');
        if (in_array($fileActualExt_dp, $allowed_dp)) {
            if ($fileError_dp === 0) {

                // ชื่อไฟล์ DP_1_01_.pdf// ชื่อไฟล์ DP_1_01_.pdf
                $fileNameNew_dp = "DP_1_" . $le_id . "." . $fileActualExt_dp;
                // เก็บที่ folder ../../../file_uploads/money_dp_ad/
                $fileDestination_dp = '../../../file_uploads/money_dp_ad/' . $fileNameNew_dp;
                move_uploaded_file($fileTmpName_dp, $fileDestination_dp);

                // up ข้อมูลขึ้น table money
                $q4 = "UPDATE money SET mn_cost = '$mn_deposit',
                                    mn_date_pay = '$dp_date',
                                    mn_no_important = '$dp_mn_no_im',
                                    mn_file = '$fileNameNew_dp',
                                    mn_status = '$dp_status',
                                    ofc_id = '$ad_id',
                                    mn_date_pay_change = '$sign_do_start_lease_date',
                                    mn_time_pay_change='$sign_do_start_lease_time',
                                    mn_budget_year = '$dp_budget_year'
                                WHERE mn_type='1' AND le_id = $le_id ";
                $result_q4 = mysqli_query($conn, $q4);
            } else {
                echo "There was an error uploading your file!";
            }
        } else {
            echo "You cannot upload files of this type!";
        }
    } else {
        // ถ้าไม่มีข้อมูล
        // up ข้อมูลขึ้น table money
        $q4 = "UPDATE money SET mn_cost = '0',
                        mn_date_pay = null,
                        mn_no_important = 'ได้รับยกเว้น',
                        mn_file = null,
                        mn_status = '100',
                        ofc_id = '$ad_id',
                        mn_date_pay_change = '$sign_do_start_lease_date',
                        mn_time_pay_change='$sign_do_start_lease_time',
                        mn_budget_year = null
                    WHERE mn_type='1' AND le_id = $le_id ";
        $result_q4 = mysqli_query($conn, $q4);
    }
}




//update status ที่ status_lease ว่าเจ้าหน้าที่แก้ไขสถานะค่าเช่าล่วงหน้าและเงินประกันแล้ว
$q8 = "UPDATE status_lease SET st_mn_pay='1', st_pass = '0' WHERE le_id = $le_id ";
$result_q8 = mysqli_query($conn, $q8);


if ($result_q4 and $result_q8) {

    //แจ้งเตือนเจ้าหน้าที่เพิ่มสัญญา เจ้าหน้าที่พิเศษ
    {
        //หาข้อมูลเจ้าหน้าที่ 
        $sql1 = "SELECT * FROM officer 
                WHERE ( ofc_type = 2 OR ofc_type = 1 )
                AND ofc_status_use = 1";
        $result1 = mysqli_query($conn, $sql1);

        //หาเลขที่อ้างอิงสัญญา
        $sql2 = "SELECT * FROM lease 
                WHERE le_id = $le_id";
        $result2 = mysqli_query($conn, $sql2);
        $r2 = mysqli_fetch_assoc($result2);
        $le_no = $r2['le_no'];

        while ($r1 = mysqli_fetch_assoc($result1)) { // วนลูปเจ้าหน้าที่ทุกคน

            $email = $r1['ofc_email'];
            $tel = $r1['ofc_tel'];
            $header = "แจ้งเตือน การชำระค่าเช่าล่วงหน้า และเงินประกัน";
            $detail = "สัญญาเพื่อโรงอาหาร เลขที่สัญญาเช่า $le_no ชำระค่าเช่าล่วงหน้า และเงินประกัน สำเร็จแล้ว";

            //mail เจ้าหน้าที่เพิ่มสัญญา เจ้าหน้าที่พิเศษ
            require_once "../../../16_func_email_sms/func_email.php";
            $send_email = sendEmail($email, $header, $detail);

            //SMS เจ้าหน้าที่เพิ่มสัญญา เจ้าหน้าที่พิเศษ
            require_once "../../../16_func_email_sms/func_sms.php";
            $send_sms = sendSMS($tel, $detail);
        }
    }

    //แจ้งเตือนผู้เช่า
    {
        //หาข้อมูลผู้เช่า
        $sql3 = "SELECT * FROM lease l, tenant t 
                        WHERE l.le_id = $le_id
                        AND l.tn_id = t.tn_id";
        $result3 = mysqli_query($conn, $sql3);
        $r3 = mysqli_fetch_assoc($result3);

        $email3 = $r3['tn_email'];
        $header3 = "แจ้งเตือน การชำระค่าเช่าล่วงหน้า และเงินประกัน";
        $detail3 ="เลขที่สัญญาเช่า $le_no ชำระค่าเช่าล่วงหน้า และเงินประกัน สำเร็จแล้ว";
        $tel3 = $r3['tn_tel'];

        //mail ผู้เช่า
        require_once "../../../16_func_email_sms/func_email.php";
        $send_email3 = sendEmail($email3, $header3, $detail3);

        //SMS ผู้เช่า
        require_once "../../../16_func_email_sms/func_sms.php";
        $send_sms3 = sendSMS($tel3, $detail3);
    }

    echo "<script type='text/javascript'>";
    echo "alert('เพิ่มข้อมูลค่าเช่าล่วงหน้า และเงินประกันสำเร็จ'); ";
    // เสร็จแล้วไปหน้า 01_lease.php
    echo "window.location='01_lease.php';";
    echo "</script>";
} else {
    echo "<script type='text/javascript'>";
    echo "alert('มีความผิดพลาด');";
    //echo "window.location='01_tenant_home.php';";
    echo "</script>";
}
