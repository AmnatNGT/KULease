<?php

session_start();
require_once "../../connection.php";

//พยานของทั่วไป
if (isset($_POST['save_2'])) {

    //จัดข้อมูลคำนำหน้าชื่อ
    if ($_POST['wn_p_name'] == 'n4') {
        $wn_p_name = $_POST['wn_p_name_oth']; //คำนำหน้าชื่ออื่นๆ
    } else if ($_POST['wn_p_name'] == 'n1') {
        $wn_p_name = "นาย";
    } else if ($_POST['wn_p_name'] == 'n2') {
        $wn_p_name = "นาง";
    } else if ($_POST['wn_p_name'] == 'n3') {
        $wn_p_name = "นางสาว";
    }

    // รับข้อมูล
    $wn_f_name = $_POST['wn_f_name'];
    $wn_l_name = $_POST['wn_l_name'];
    $wn_role = $_POST['wn_role'];
    $wn_phone = $_POST['wn_phone'];
    $wn_email = $_POST['wn_email'];
    $wn_province = $_POST['wn_province'];
    $wn_district = $_POST['wn_district'];
    $wn_canton = $_POST['wn_canton'];
    $wn_moo = $_POST['wn_moo'];
    $wn_no = $_POST['wn_no'];

    $chk_in_out_wn = "out";

    $wn_status_show = '1';

    // เจ้าหน้าที่ผู้เพิ่ม
    $ad_id = $_SESSION['ofc_add'];

    date_default_timezone_set('Asia/Bangkok');
    $wn_date_add = date("Y-m-d");
    $wn_time_add = date('H:i:s');

    // จัดการตำแหน่ง
    if (isset($_POST['wn_role'])) {
        $wn_role = $_POST['wn_role'];
    } else {
        $wn_role = null;
    }

    //ข้อมูลที่อยู่
    {
        //เรียกจังหวัด
        $sql_pro = "SELECT * FROM provinces WHERE id = $wn_province ";
        $query_pro = mysqli_query($conn, $sql_pro);
        $row_pro = mysqli_fetch_assoc($query_pro);

        $result_pro = $row_pro['name_th'];
        $_SESSION['tn_province'] = $result_pro;

        //เรียกอำเภอ
        $sql_dis = "SELECT * FROM amphures WHERE id = $wn_district ";
        $query_dis = mysqli_query($conn, $sql_dis);
        $row_dis = mysqli_fetch_assoc($query_dis);

        $result_dis = $row_dis['name_th'];
        $_SESSION['tn_district'] = $result_dis;

        //เรียกตำบล
        $sql_ct = "SELECT * FROM districts WHERE id = $wn_canton ";
        $query_ct = mysqli_query($conn, $sql_ct);
        $row_ct = mysqli_fetch_assoc($query_ct);

        $result_ct = $row_ct['name_th'];
        $_SESSION['tn_canton'] = $result_ct;

        //Get id ล่าสุด จาก witness
        $sql_wn = "SELECT MAX(wn_id) AS maxid FROM witness ";
        $result_wn = mysqli_query($conn, $sql_wn);
        $ret = mysqli_fetch_assoc($result_wn); // อ่านค่า
        $last_id = $ret['maxid']; // คืนค่า id ที่ insert สูงสุด
        $last_id += 1;
    }

    //แนบไฟล์ id card และแจ้งเตือน OTP
    {
        $file = $_FILES['file_1'];

        $fileName = $_FILES['file_1']['name'];
        $fileTmpName = $_FILES['file_1']['tmp_name'];
        $fileSize = $_FILES['file_1']['size'];
        $fileError = $_FILES['file_1']['error'];
        $fileType = $_FILES['file_1']['type'];

        $fileEXT = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileEXT));

        $allowed = array('jpg', 'jpeg', 'png', 'pdf');

        if (in_array($fileActualExt, $allowed)) {
            if ($fileError === 0) {

                // ชื่อ id card
                $fileNameNew = "WN_id_card_" . $last_id . "." . $fileActualExt;
                // folder ที่จะไปเก็บ
                $fileDestination = '../../file_uploads/witness_id_card/' . $fileNameNew;
                move_uploaded_file($fileTmpName, $fileDestination);

                date_default_timezone_set('Asia/Bangkok');
                $otp_date = date("Y-m-d");
                $time = date("H:i:s");

                //time otp end
                $a = strtotime("+5 minutes", strtotime($time));
                $otp_time =  date('H:i:s', $a);

                //random otp
                $otp = mt_rand(10000, 99999);

                // insert ข้อมูล to witness
                $query_wn = "INSERT INTO witness (ad_id, wn_p_name, wn_f_name, wn_l_name, wn_role, 
                                                wn_phone, wn_email, wn_province, wn_district, wn_canton, wn_moo, wn_no,
                                                wn_date_add, wn_time_add, wn_status_show, chk_in_out_wn, wn_id_card,
                                                wn_otp, wn_status_otp, wn_otp_date, wn_otp_time)
                                        VALUE('$ad_id', '$wn_p_name', '$wn_f_name', '$wn_l_name', '$wn_role', 
                                                '$wn_phone', '$wn_email', '$result_pro' ,'$result_dis' ,'$result_ct' , '$wn_moo', '$wn_no ', 
                                                '$wn_date_add', '$wn_time_add', '$wn_status_show', '$chk_in_out_wn', '$fileNameNew',
                                                '$otp', '0', '$otp_date', '$otp_time')";
                $result_wn = mysqli_query($conn, $query_wn);

                //mail หา พยานผู้เช่า
                require_once "../../16_func_email_sms/func_email.php";
                $header = "แจ้งเตือน OTP";
                $send_email = sendEmail($wn_email, $header, $otp);

                //SMS หา พยานผู้เช่า
                require_once "../../16_func_email_sms/func_sms.php";
                $send_sms = sendSMS($wn_phone, "OTP >> $otp");

            } else {
                echo "<script type='text/javascript'>";
                echo "alert('มีความผิดพลาด');";
                echo "window.location='07_1_signer_page.php';";
                echo "</script>";
            }
        } else {
            echo "You cannot upload files of this type!";
        }

        //หา id ล่าสุด ของพยานของผู้เช่า
        $sql_st = "SELECT MAX(wn_id) AS maxid FROM witness ";
        $result_st = mysqli_query($conn, $sql_st);
        $ret = mysqli_fetch_assoc($result_st); // อ่านค่า
        $last_id = $ret['maxid']; // คืนค่า id ที่ insert สูงสุด\

        if ($result_wn) {
            echo "<script type='text/javascript'>";
            echo "alert('กรุณาตรวจสอบที่ SMS โทรศัพท์ หรือ Email ของพยาน เพื่อยืนยัน OTP');";
            // เมื่อเสร็จแล้วไปหน้า 07_12_otp_wn.php และส่ง id พยานไปด้วย เพื่อกรอก otp
            echo "window.location='07_12_otp_wn.php?wn_id=$last_id';";
            echo "</script>";
        } else {
            echo "<script type='text/javascript'>";
            echo "alert('มีความผิดพลาด');";
            echo "</script>";
        }
    }
}
