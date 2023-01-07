<?php

session_start();
require_once "../../connection.php";

//check captcha [7-27]
if (isset($_POST['g-recaptcha-response'])) {
    $captcha = $_POST['g-recaptcha-response']; //ถ้าไม่ได้ติก ค่าตรงนี้ก็ไม่มี
}
// ถ้าไม่ได้ tic captcha ให้กลับไปที่หน้า ofc_register.php 
if (!$captcha) {
    echo "<script type='text/javascript'>";
    echo "alert('โปรดยืนยันตัวตนของคุณ');";
    echo "window.location='ofc_register.php';";
    echo "</script>";
}

$secretKey = "6LeVlS0eAAAAANqoHgTO1ftXg6D6cv-QERsuzhVa";
$ip = $_SERVER['REMOTE_ADDR'];
$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $captcha . "&remoteip=" . $ip);
$responseKeys = json_decode($response, true);
//ตรวจสอบคำถามที่ถาม
if (intval($responseKeys["success"]) != 1) {
    echo "<script type='text/javascript'>";
    echo "alert('โปรดทำการยันยืนให้ถูกต้อง');";
    echo "window.location='ofc_register.php';";
    echo "</script>";
}
//ถ้า tic captcha captcha ให้ทำงานสวนต่อไป
else {

    //ถ้ามีค่า $_POST['submit_1'])
    if (isset($_POST['submit_1'])) {

        //จัดการข้อมูลบทบาทการใช้งาน
        if ($_POST['role_use'] == 'r1') {
            $_SESSION['role_use'] = "1";
        } else if ($_POST['role_use'] == 'r2') {
            $_SESSION['role_use'] = "2";
        } else if ($_POST['role_use'] == 'r3') {
            $_SESSION['role_use'] = "3";
        } else if ($_POST['role_use'] == 'r4') {
            $_SESSION['role_use'] = "4";
        } else if ($_POST['role_use'] == 'r5') {
            $_SESSION['role_use'] = "5";
        } else if ($_POST['role_use'] == 'r6') {
            $_SESSION['role_use'] = "6";
        }

        //จัดการข้อมูลคำนำหน้าชื่อ
        if ($_POST['tn_p_name'] == 'n4') {
            $_SESSION['tn_p_name'] = $_POST['tn_p_name_oth']; //คำนำหน้าชื่ออื่นๆ ให้รับ post tn_p_name_oth
        } else if ($_POST['tn_p_name'] == 'n1') {
            $_SESSION['tn_p_name'] = "นาย";
        } else if ($_POST['tn_p_name'] == 'n2') {
            $_SESSION['tn_p_name'] = "นาง";
        } else if ($_POST['tn_p_name'] == 'n3') {
            $_SESSION['tn_p_name'] = "นางสาว";
        }

        //รับข้อมูลจากหน้าก่อนหน้าและเก็บไว้ที่ session
        $_SESSION['tn_email'] = mysqli_real_escape_string($conn,$_POST['tn_email']);
        $_SESSION['tn_password'] = mysqli_real_escape_string($conn,$_POST['tn_password']);
        $_SESSION['tn_password_2'] = mysqli_real_escape_string($conn,$_POST['tn_password_2']);
        $_SESSION['tn_f_name'] = $_POST['tn_f_name'];
        $_SESSION['tn_l_name'] = $_POST['tn_l_name'];
        $_SESSION['tn_role'] = $_POST['tn_role'];
        $_SESSION['tn_birth_date'] = $_POST['tn_birth_date'];
        $_SESSION['tn_tel'] = $_POST['tn_tel'];
    }

    //ถ้ามี่ค่า $_POST['submit_1']
    if (isset($_POST['submit_1'])) {

        //Set ข้อมูล $tn_email
        $tn_email = $_SESSION['tn_email'];

        //check email ว่ามีอยู่ในตาราง admin มั้ย
        $check_email_ad = "SELECT * FROM admin WHERE ad_email ='$tn_email' LIMIT 1 ";
        $result_ad = mysqli_query($conn, $check_email_ad);
        $ch3 = mysqli_fetch_assoc($result_ad);

        //check email ว่ามีอยู่ในตาราง officer มั้ย
        $check_email_ofc = "SELECT * FROM officer WHERE ofc_email ='$tn_email' AND ofc_verify = '1' LIMIT 1 ";
        $result_ofc = mysqli_query($conn, $check_email_ofc);
        $ch1 = mysqli_fetch_assoc($result_ofc);

        //check email ว่ามีอยู่ในตาราง tenant มั้ย
        $check_email_tn = "SELECT * FROM tenant WHERE tn_email ='$tn_email' AND tn_verify = '1' LIMIT 1 ";
        $result_tn = mysqli_query($conn, $check_email_tn);
        $ch2 = mysqli_fetch_assoc($result_tn);

        //check email ว่ามีอยู่ในตาราง lessor มั้ย
        $check_email_bs = "SELECT * FROM lessor WHERE ls_email ='$tn_email' AND ls_verify = '1' LIMIT 1 ";
        $result_bs = mysqli_query($conn, $check_email_bs);
        $ch4 = mysqli_fetch_assoc($result_bs);

        //ตรวจสอบบทบาทที่สมัครได้แค่คนเดียว [Row:100-126] ไปตรวจสอบที่ [Row: 160-177]
        //เจ้าหน้าที่พิเศษ
        //ถ้า $_SESSION['role_use'] == '2' ให้ทำจุดนี้
        if ($_SESSION['role_use'] == '2') {
            $check_email_ofc2 = "SELECT * FROM officer 
                                        WHERE ofc_status_use = '1'
                                        AND ofc_type = '2' ";
            $result_ofc2 = mysqli_query($conn, $check_email_ofc2);
            $count = mysqli_num_rows($result_ofc2);
        }
        //ผู้จัดการหอพัก
        //ถ้า $_SESSION['role_use'] == '5' ให้ทำจุดนี้
        else if ($_SESSION['role_use'] == '5') {
            $check_email_ofc2 = "SELECT * FROM officer 
                                        WHERE ofc_status_use = '1'
                                        AND ofc_type = '5' ";
            $result_ofc3 = mysqli_query($conn, $check_email_ofc2);
            $count = mysqli_num_rows($result_ofc3);
        }
        //นิติกร
        //ถ้า $_SESSION['role_use'] == '4' ให้ทำจุดนี้
        else if ($_SESSION['role_use'] == '4') {
            $check_email_ofc2 = "SELECT * FROM officer 
                                        WHERE ofc_status_use = '1'
                                        AND ofc_type = '4' ";
            $result_ofc4 = mysqli_query($conn, $check_email_ofc2);
            $count = mysqli_num_rows($result_ofc4);
        }

        //ถ้า check แล้วพบว่า เคยมี email ดังกล่าวแล้วให้กลับไปทีหน้า tn_register.php ใหม่ [Row: 129-157]
        if (isset($ch3)) {
            if ($ch3['ad_email'] === $tn_email) {
                echo "<script type='text/javascript'>";
                echo "alert('Email นี้เคยลงทะเบียนแล้ว');";
                echo "window.location='ofc_register.php';";
                echo "</script>";
            }
        } else if (isset($ch1)) {
            if ($ch1['ofc_email'] === $tn_email) {
                echo "<script type='text/javascript'>";
                echo "alert('Email นี้เคยลงทะเบียนแล้ว');";
                echo "window.location='ofc_register.php';";
                echo "</script>";
            }
        } else if (isset($ch2)) {
            if ($ch2['tn_email'] === $tn_email) {
                echo "<script type='text/javascript'>";
                echo "alert('Email นี้เคยลงทะเบียนแล้ว');";
                echo "window.location='ofc_register.php';";
                echo "</script>";
            }
        } else if (isset($ch4)) {
            if ($ch4['ls_email'] === $tn_email) {
                echo "<script type='text/javascript'>";
                echo "alert('Email นี้เคยลงทะเบียนแล้ว');";
                echo "window.location='ofc_register.php';";
                echo "</script>";
            }
        }
        //จาก Row[100-126] ตรวจสอบบทบาทที่สมัครได้แค่คนเดียว ถ้าตรวจสอบแล้วพบว่า count > 0 หรือเคยมีบทบาทดังกล่าวแล้ว 
        //ให้มีการแจ้งเตือน และไปที่หน้า index.php [Row:160-177]
        else if ($_SESSION['role_use'] == '2' and $count > 0) { {
                echo "<script type='text/javascript'>";
                echo "alert('บทบาทเจ้าหน้าที่พิเศษมีอยู่ในระบบแล้ว ไม่สามารถสมัครสมาชิกได้ \\nกรุณาติดต่อ Admin');";
                echo "window.location='../../index.php';";
                echo "</script>";
            }
        } else if ($_SESSION['role_use'] == '5' and $count > 0) { {
                echo "<script type='text/javascript'>";
                echo "alert('บทบาทผู้จัดการหอพักมีอยู่ในระบบแล้ว ไม่สามารถสมัครสมาชิกได้ \\nกรุณาติดต่อ Admin');";
                echo "window.location='../../index.php';";
                echo "</script>";
            }
        } else if ($_SESSION['role_use'] == '4' and $count > 0) { {
                echo "<script type='text/javascript'>";
                echo "alert('บทบาทนิติกรมีอยู่ในระบบแล้ว ไม่สามารถสมัครสมาชิกได้ \\nกรุณาติดต่อ Admin');";
                echo "window.location='../../index.php';";
                echo "</script>";
            }
        }
        //ถ้าไม่เคยมี Email ดังกล่าว หรือบทบาทถูกต้องแล้ว ให้ทำขั้นต่อไป
        else {

            $tn_password = $_SESSION['tn_password'];
            $tn_password_2 = $_SESSION['tn_password_2'];

            //ถ้า Password ที่กรอกทั้ง 2 ไม่ตรงกัน ให้กลับไปที่หน้า ofc_register.php ใหม่
            if ($tn_password != $tn_password_2) {
                echo "<script type='text/javascript'>";
                echo "alert('Password ไม่ตรงกัน') ;";
                echo "window.location='ofc_register.php'; ";
                echo "</script>";
            }
            //ถ้า Password ตรงกัน ให้ทำขั้นต่อไป
            else {

                //เตรียมข้อมูล [Row: 195-221]
                $role_use = $_SESSION['role_use'];

                $tn_email = $_SESSION['tn_email'];

                $tn_password = $_SESSION['tn_password'];
                //นำ Password เข้ารหัสด้วย sha256
                $tn_password = hash('sha256', $tn_password);

                $tn_p_name = $_SESSION['tn_p_name'];
                $tn_f_name = $_SESSION['tn_f_name'];
                $tn_l_name = $_SESSION['tn_l_name'];
                $tn_birth_date = $_SESSION['tn_birth_date'];
                $tn_tel = $_SESSION['tn_tel'];
                $tn_role = $_SESSION['tn_role'];

                $tn_status_use = 0;

                date_default_timezone_set('Asia/Bangkok');
                $tn_date_regis = date("Y-m-d");
                $tn_time_regis = date("H:i:s");

                //time otp end เอาเวลาปัจจุบัน + 5 นาที
                $a = strtotime("+5 minutes", strtotime($tn_time_regis));
                //เวลาที่ OTP หมดอายุ
                $otp_time =  date('H:i:s', $a);

                //random otp
                $otp = mt_rand(10000, 99999);

                //insert data to tabel officer
                $query_data = "INSERT INTO officer (ofc_email, ofc_password, ofc_p_name, ofc_f_name, ofc_l_name,
                                                   ofc_birth_date, ofc_tel, ofc_status_use, ofc_role, ofc_verify, ofc_otp, ofc_status_otp,
                                                   ofc_date_regis, ofc_time_regis, ofc_otp_date, ofc_otp_time, ofc_type)
                                            VALUE('$tn_email', '$tn_password', '$tn_p_name', '$tn_f_name', '$tn_l_name',
                                                  '$tn_birth_date','$tn_tel', '$tn_status_use', '$tn_role', '0', '$otp', '0', 
                                                  '$tn_date_regis', '$tn_time_regis', '$tn_date_regis', '$otp_time', '$role_use')";
                $result_data = mysqli_query($conn, $query_data);

                //alert email to officer
                require_once "../../16_func_email_sms/func_email.php";
                $header = "แจ้งเตือน OTP";
                $send_email = sendEmail($tn_email, $header, $otp);

                //alert SMS to officer
                require_once "../../16_func_email_sms/func_sms.php";
                $send_sms = sendSMS($tn_tel, "OTP >> $otp");

                //find last id of officer
                $query2 = "SELECT MAX(ofc_id) AS maxid FROM officer"; // query อ่านค่า id สูงสุด
                $res = mysqli_query($conn, $query2); // ทำคำสั่ง
                $ret = mysqli_fetch_assoc($res); // อ่านค่า
                $last_id = $ret['maxid']; // คืนค่า id ที่ insert สูงสุด

                //if insert data success [Row: 226] goto tn_confirm_otp.php page และ ส่งค่า $ofc_id แบบ get ไปด้วย
                if ($result_data) {
                    echo "<script type='text/javascript'>";
                    echo "alert('กรุณาตรวจสอบที่ SMS โทรศัพท์ หรือ Email เพื่อยืนยัน OTP'); ";
                    echo "window.location='ofc_confirm_otp.php?ofc_id=$last_id'; ";
                    echo "</script>";
                } else {
                    echo "<script type='text/javascript'>";
                    echo "alert('มีความผิดพลาด'); ";
                    echo "</script>";
                }
            }
        }
    }
}
