<?php

session_start();
require_once "../../connection.php";

//check captcha [7-27]
if (isset($_POST['g-recaptcha-response'])) {
    $captcha = $_POST['g-recaptcha-response']; //ถ้าไม่ได้ติก ค่าตรงนี้ก็ไม่มี
}
// ถ้าไม่ได้ tic captcha ให้กลับไปที่หน้า tn_register.php 
if (!$captcha) {
    echo "<script type='text/javascript'>";
    echo "alert('โปรดยืนยันตัวตนของคุณ');";
    echo "window.location='tn_register.php';";
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
    echo "window.location='tn_register.php';";
    echo "</script>";
}
//ถ้า tic captcha captcha ให้ทำงานสวนต่อไป
else {

    //ถ้ามีค่า $_POST['submit_1'])
    if (isset($_POST['submit_1'])) {

        //รับ id ตำบล อำเภอ จังหวัด [Row: 37-39]
        $id_canton = $_POST['tn_canton'];
        $id_district = $_POST['tn_district'];
        $id_province = $_POST['tn_province'];

        //หาชื่อจังหวัดจากฐานข้อมูลจาก $id_province
        $sql_pro = "SELECT * FROM provinces WHERE id = $id_province ";
        $query_pro = mysqli_query($conn, $sql_pro);
        $row_pro = mysqli_fetch_assoc($query_pro);
        //เอาชื่อจังหวัดไว้ที่ตัวแปร $result_pro
        $result_pro = $row_pro['name_th'];
        $_SESSION['tn_province'] = $result_pro;

        //หาชื่ออำเภอจากฐานข้อมูลจาก $id_district
        $sql_dis = "SELECT * FROM amphures WHERE id = $id_district ";
        $query_dis = mysqli_query($conn, $sql_dis);
        $row_dis = mysqli_fetch_assoc($query_dis);
        //เอาชื่ออำเภอไว้ที่ตัวแปร $result_dis
        $result_dis = $row_dis['name_th'];
        $_SESSION['tn_district'] = $result_dis;

        //หาชื่อตำบลจากฐานข้อมูลจาก $id_canton
        $sql_ct = "SELECT * FROM districts WHERE id = $id_canton ";
        $query_ct = mysqli_query($conn, $sql_ct);
        $row_ct = mysqli_fetch_assoc($query_ct);
        //เอาชื่อตำบลไว้ที่ตัวแปร $result_ct
        $result_ct = $row_ct['name_th'];
        $_SESSION['tn_canton'] = $result_ct;

        //หารหัสไปรณีย์ของตำบลที่เลือกจาก $id_canton
        $sql_ps = "SELECT * FROM districts WHERE id = $id_canton ";
        $query_ps = mysqli_query($conn, $sql_ps);
        $row_ps = mysqli_fetch_assoc($query_ps);
        //เอารหัสไปรณีย์ไว้ที่ตัวแปร $result_ps
        $result_ps = $row_ps['zip_code'];
        $_SESSION['tn_postcode'] = $result_ps;

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
        $_SESSION['tn_cpn'] = $_POST['tn_cpn'];
        $_SESSION['tn_f_name'] = $_POST['tn_f_name'];
        $_SESSION['tn_l_name'] = $_POST['tn_l_name'];
        $_SESSION['tn_role'] = $_POST['tn_role'];
        $_SESSION['tn_birth_date'] = $_POST['tn_birth_date'];
        $_SESSION['tn_tel'] = $_POST['tn_tel'];
        $_SESSION['tn_ethnicity'] = $_POST['tn_ethnicity'];
        $_SESSION['tn_nationality'] = $_POST['tn_nationality'];
        $_SESSION['tn_house_no'] = $_POST['tn_house_no'];
        $_SESSION['tn_alley'] = $_POST['tn_alley'];
        $_SESSION['tn_moo'] = $_POST['tn_moo'];
        $_SESSION['tn_road'] = $_POST['tn_road'];
        $_SESSION['tn_id_card'] = $_POST['tn_id_card'];
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

        //ถ้า check แล้วพบว่า เคยมี email ดังกล่าวแล้วให้กลับไปทีหน้า tn_register.php ใหม่ [Row: 129-157]
        if (isset($ch3)) {
            if ($ch3['ad_email'] === $tn_email) {
                echo "<script type='text/javascript'>";
                echo "alert('Email นี้เคยลงทะเบียนแล้ว');";
                echo "window.location='tn_register.php';";
                echo "</script>";
            }
        } else if (isset($ch1)) {
            if ($ch1['ofc_email'] === $tn_email) {
                echo "<script type='text/javascript'>";
                echo "alert('Email นี้เคยลงทะเบียนแล้ว');";
                echo "window.location='tn_register.php';";
                echo "</script>";
            }
        } else if (isset($ch2)) {
            if ($ch2['tn_email'] === $tn_email) {
                echo "<script type='text/javascript'>";
                echo "alert('Email นี้เคยลงทะเบียนแล้ว');";
                echo "window.location='tn_register.php';";
                echo "</script>";
            }
        } else if (isset($ch4)) {
            if ($ch4['ls_email'] === $tn_email) {
                echo "<script type='text/javascript'>";
                echo "alert('Email นี้เคยลงทะเบียนแล้ว');";
                echo "window.location='tn_register.php';";
                echo "</script>";
            }
        }
        //ถ้าไม่เคยมี Email ดังกล่าว ให้ทำขั้นต่อไป
        else {

            $tn_password = $_SESSION['tn_password'];
            $tn_password_2 = $_SESSION['tn_password_2'];

            //ถ้า Password ที่กรอกทั้ง 2 ไม่ตรงกัน ให้กลับไปที่หน้า tn_register.php ใหม่
            if ($tn_password != $tn_password_2) {
                echo "<script type='text/javascript'>";
                echo "alert('Password ไม่ตรงกัน');";
                echo "window.location='tn_register.php';";
                echo "</script>";
            }
            //ถ้า Password ตรงกัน ให้ทำขั้นต่อไป
            else {

                //function ตรวจสอบ id card
                function checkidCard($id_card)
                {
                    $id_card = str_split(str_replace('-', '', $id_card));
                    $sum = 0;
                    $digi = 13;

                    foreach ($id_card as $key) {
                        $digi > 1 ? $sum += $digi * intval($key) : null;
                        $digi--;
                    }

                    $x = $sum % 11;
                    $n13 = $x <= 1 ? 1 - $x : 11 - $x;

                    if ($n13 != $id_card[12]) {
                        //ถ้า Id card ไม่ถูกต้องตาม logic return 0
                        return "0";
                    } else {
                        //ถ้า Id card ถูกต้องตาม logic return 1
                        return "1";
                    }
                }

                //ส่งค่า tn_id_card ไปตรวจสอบที่ function checkidCard [Row 174] และรับค่าที่ return มาไว้ที่ตัวแปร $id_d_card
                $id_d_card = checkidCard($_SESSION['tn_id_card']);
                
                //ถ้า $id_d_card == "0" ให้กลับไปที่หน้า tn_register.php ใหม่
                if ($id_d_card == "0") {
                    echo "<script type='text/javascript'>";
                    echo "alert('เลขบัตรประชาชนไม่ถูกต้อง');";
                    echo "window.location='tn_register.php';";
                    echo "</script>";
                } 
                //ถ้า $id_d_card == "1" ให้ทำขั้นต่อไป
                else if ($id_d_card == "1") {

                    //เตรียมข้อมูล [Row: 211-248]
                    $tn_email = $_SESSION['tn_email'];

                    $tn_password = $_SESSION['tn_password'];
                    //นำ Password เข้ารหัสด้วย sha256
                    $tn_password = hash('sha256', $tn_password);

                    $tn_p_name = $_SESSION['tn_p_name'];
                    $tn_f_name = $_SESSION['tn_f_name'];
                    $tn_l_name = $_SESSION['tn_l_name'];
                    $tn_id_card = $_SESSION['tn_id_card'];
                    $tn_birth_date = $_SESSION['tn_birth_date'];
                    $tn_tel = $_SESSION['tn_tel'];
                    $tn_ethnicity = $_SESSION['tn_ethnicity'];
                    $tn_nationality = $_SESSION['tn_nationality'];
                    $tn_house_no = $_SESSION['tn_house_no'];
                    $tn_alley = $_SESSION['tn_alley'];
                    $tn_moo = $_SESSION['tn_moo'];
                    $tn_road = $_SESSION['tn_road'];
                    $tn_canton = $_SESSION['tn_canton'];
                    $tn_district = $_SESSION['tn_district'];
                    $tn_province = $_SESSION['tn_province'];
                    $tn_postcode = $_SESSION['tn_postcode'];
                    $tn_role = $_SESSION['tn_role'];
                    $tn_cpn = $_SESSION['tn_cpn'];

                    $tn_count_use = 0;
                    $tn_status_use = 0;

                    date_default_timezone_set('Asia/Bangkok');
                    $tn_date_regis = date("Y-m-d");
                    $tn_time_regis = date('H:i:s');

                    //time otp end เอาเวลาปัจจุบัน + 5 นาที
                    $a = strtotime("+5 minutes", strtotime($tn_time_regis));
                    //เวลาที่ OTP หมดอายุ
                    $otp_time =  date('H:i:s', $a);

                    //random otp
                    $otp = mt_rand(10000, 99999);

                    //insert data to tabel tenant
                    $query_data = "INSERT INTO tenant (tn_email, tn_password, tn_p_name, tn_f_name, tn_l_name, tn_id_card,
                                                   tn_birth_date, tn_tel, tn_ethnicity, tn_nationality, tn_moo,
                                                   tn_house_no, tn_alley, tn_road, tn_canton, tn_district, tn_province, tn_postcode,
                                                   tn_date_regis, tn_time_regis, tn_count_use, tn_status_use, tn_role, tn_verify, tn_otp, tn_status_otp,
                                                   tn_otp_date, tn_otp_time, tn_company)
                                            VALUE('$tn_email', '$tn_password', '$tn_p_name', '$tn_f_name', '$tn_l_name', '$tn_id_card', 
                                                  '$tn_birth_date','$tn_tel', '$tn_ethnicity', '$tn_nationality', '$tn_moo',
                                                  '$tn_house_no', '$tn_alley', '$tn_road', '$tn_canton', '$tn_district', '$tn_province', '$tn_postcode', 
                                                  '$tn_date_regis', '$tn_time_regis', '$tn_count_use', '$tn_status_use', '$tn_role', '0', '$otp', '0',
                                                  '$tn_date_regis', '$otp_time', '$tn_cpn')";
                    $result_data = mysqli_query($conn, $query_data);

                    //alert email to tenant
                    require_once "../../16_func_email_sms/func_email.php";
                    $header = "แจ้งเตือน OTP";
                    $send_email = sendEmail($tn_email, $header, $otp);

                    //alert SMS to tenant
                    require_once "../../16_func_email_sms/func_sms.php";
                    $send_sms = sendSMS($tn_tel, "OTP >> $otp");

                    //find last id of tenant
                    $query2 = "SELECT MAX(tn_id) AS maxid FROM tenant"; // query อ่านค่า id สูงสุด
                    $res = mysqli_query($conn, $query2); // ทำคำสั่ง
                    $ret = mysqli_fetch_assoc($res); // อ่านค่า
                    $last_id = $ret['maxid']; // คืนค่า id ที่ insert สูงสุด

                    //if insert data success [Row: 251] goto tn_confirm_otp.php page และ ส่งค่า $tn_id แบบ get ไปด้วย
                    if ($result_data) {
                        echo "<script type='text/javascript'>";
                        echo "alert('กรุณาตรวจสอบที่ SMS โทรศัพท์ หรือ Email เพื่อยืนยัน OTP');";
                        echo "window.location='tn_confirm_otp.php?tn_id=$last_id';";
                        echo "</script>";
                    } else {
                        echo "<script type='text/javascript'>";
                        echo "alert('มีความผิดพลาด');";
                        echo "window.location='tn_register.php';";
                        echo "</script>";
                    }
                }
            }
        }
    }
}
