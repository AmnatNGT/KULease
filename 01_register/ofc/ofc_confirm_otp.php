<?php

session_start();
require_once "../../connection.php";

date_default_timezone_set('Asia/Bangkok');

//รับ Id ล่าสุด
$ofc_id = $_GET['ofc_id'];

//ดึงข้อมูลเจ้าหน้าที่จากฐานข้อมูล
$sql = "SELECT * FROM officer WHERE ofc_id = $ofc_id ";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

//Check OTP
//ถ้าได้รับ $_POST['submit'] ให้ทำงานที่นี้
if (isset($_POST['submit'])) {

    //รับ OTP ที่ส่งมาจากด้านล่าง [Row:219]
    $otp = $_POST['ofc_otp'];

    //รับ email ของเจ้าหน้าที่ จากฐานข้อมูล [Row:14]
    $r_mail = $row['ofc_email'];

    //รับ otp ของเจ้าหน้าที่ จากฐานข้อมูล
    $sql_otp = $row['ofc_otp'];

    $ofc_email = $r_mail;

    //Check ว่า otp ที่กรอก กับ otp จากฐานข้อมูลเหมือนกันหรือไม่
    //ถ้า otp ที่กรอก === otp จากฐานข้อมูล ให้ทำงานใน if นี้
    if ($sql_otp === $otp) {

        //รับ วัน และเวลา ที่ otp หมดอายุจากฐานข้อมูล [Row:14]
        $ch_d = $row['ofc_otp_date'];
        $ch_t = $row['ofc_otp_time'];

        //หา วัน และเวลา ปัจจุบัน
        date_default_timezone_set('Asia/Bangkok');
        $d_now = date("Y-m-d");
        $t_now = date("H:i:s");

        //ตรวจสอบว่า ถ้า วันและเวลาปัจจุบัน > วันและเวลาที่ otp หมดอายุ ให้แจ้งเตือน
        if ($d_now > $ch_d || $t_now > $ch_t) {
            echo "<script type='text/javascript'>";
            echo "alert('OTP หมดอายุการใช้งานแล้ว \\nกรุณากด >> ขอรหัส OTP ใหม่ << เพื่อรับรหัสใหม่');";
            echo "</script>";
        }
        //ถ้า วันและเวลาปัจจุบัน < วันและเวลาที่ otp หมดอายุ
        else {

            //ให้ UPDATE ofc_status_otp =1
            $sql_otp_2 = "UPDATE officer SET ofc_status_otp = 1
               WHERE ofc_id = $ofc_id";
            $result_otp_2 = mysqli_query($conn, $sql_otp_2);

            //ถ้า UPDATE สำเร็จ 
            if ($result_otp_2) {

                //สร้างรูปแบบ confirm email 
                //เมื่อกด  Verify your email address ให้ไปที่หน้า https://cskps.flas.kps.ku.ac.th/kulease2/user_verify_email.php [Row:78]
                $detail =
                    '<!DOCTYPE html>
                        <html lang="en">
                        <head>
                        <meta charset="UTF-8">
                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Verify Email</title>
                        </head>
            
                        <body>
                        <div class="wrapper">
                            <p>
                                กรุณากด Verify your email address เพื่อยืนยันการลงทะเบียน
                            </p>
                            <a href="https://cskps.flas.kps.ku.ac.th/kulease2/user_verify_email.php?ofc_id=' . $ofc_id . ' ">
                               >>> Verify your email address <<<
                            </a>
                        </div>
                        </body>
            
                        </html>';

                //ส่งข้อมูล confirm email
                require_once "../../16_func_email_sms/func_email.php";
                $header = "แจ้งเตือนการยืนยัน Email";
                $send_email = sendEmail($ofc_email, $header, $detail);

                echo "<script type='text/javascript'>";
                echo "alert('ยืนยัน OTP สำเร็จ \\nกรุณายืนยันตัวตนที่ Email เพื่อสำเร็จกระบวนการลงทะเบียน');";
                echo "window.location='../../index.php';";
                echo "</script>";
            } else {
                echo "<script type='text/javascript'>";
                echo "alert('ผิดพลาด');";
                echo "</script>";
            }
        }
    }
    //ถ้า otp ที่กรอก ไม่ตรงกัน otp จากฐานข้อมูล ให้แจ้งเตือน
    else {
        echo "<script type='text/javascript'>";
        echo "alert('OTP ไม่ถูกต้อง ');";
        echo "</script>";
    }
}

//ขอ New OTP
//ถ้าได้รับ $_POST['new_otp'] ให้ทำงานที่นี้
else if (isset($_POST['new_otp'])) {

    $count_otp = $row['count_otp'];

    // ถ้า count_otp < 3 ส่งไปที่ Email และ SMS
    if ($count_otp < 3) {
        //random otp
        $otp = mt_rand(10000, 99999);

        $count_otp = $count_otp+1;

        //วัน เวลาปัจจุบัน
        date_default_timezone_set('Asia/Bangkok');
        $date_otp = date("Y-m-d");
        $time_n = date("H:i:s");

        //time otp end เอาเวลาปัจจุบัน + 5 นาที
        $a = strtotime("+5 minutes", strtotime($time_n));
        $time_otp =  date('H:i:s', $a);

        //update new otp และ วันเวลาที่  otp หมดอายุ ไปเก็บไว้ที่ ตาราง officer
        $upd_otp = "UPDATE officer SET ofc_otp = '$otp',
                                   ofc_otp_date = '$date_otp',
                                   ofc_otp_time = '$time_otp',
                                   count_otp = '$count_otp'
                               WHERE ofc_id = $ofc_id";
        $result_upd_otp = mysqli_query($conn, $upd_otp);

        //เตรียม email และ เบอร์ ของเจ้าหน้าที่
        $tn_email = $row['ofc_email'];
        $tn_tel = $row['ofc_tel'];

        //ส่ง OTP ไปที่ email
        require_once "../../16_func_email_sms/func_email.php";
        $header = "แจ้งเตือน OTP";
        $send_email = sendEmail($tn_email, $header, $otp);

        //ส่ง OTP ไปที่ SMS
        require_once "../../16_func_email_sms/func_sms.php";
        $send_sms = sendSMS($tn_tel, "OTP >> $otp");

        //รับ id ผู้เช่า
        $id = $row['ofc_id'];

        //เมื่อแจ้งเตือน OTP เสร็จ และ update ตาราง otp สำเร็จ
        if ($result_upd_otp) {
            echo "<script type='text/javascript'>";
            echo "alert('OTP ใหม่ส่งสำเร็จ\\nกรุณาตรวจสอบที่ SMS โทรศัพท์ หรือ Email เพื่อยืนยัน OTP'); ";
            //ให้กลับมาหน้า ofc_confirm_otp.php page (มาหน้าเดิม)และ ส่งค่า $id แบบ get ไปด้วย
            echo "window.location='ofc_confirm_otp.php?ofc_id=$id'; ";
            echo "</script>";
        } else {
            echo "<script type='text/javascript'>";
            echo "alert('มีความผิดพลาด'); ";
            echo "</script>";
        }
    }
    // ส่งไปที่ Email เท่านั้น
    else {

        //random otp
        $otp = mt_rand(10000, 99999);

        $count_otp = $count_otp+1;

        //วัน เวลาปัจจุบัน
        date_default_timezone_set('Asia/Bangkok');
        $date_otp = date("Y-m-d");
        $time_n = date("H:i:s");

        //time otp end เอาเวลาปัจจุบัน + 5 นาที
        $a = strtotime("+5 minutes", strtotime($time_n));
        $time_otp =  date('H:i:s', $a);

        //update new otp และ วันเวลาที่  otp หมดอายุ ไปเก็บไว้ที่ ตาราง officer
        $upd_otp = "UPDATE officer SET ofc_otp = '$otp',
                                   ofc_otp_date = '$date_otp',
                                   ofc_otp_time = '$time_otp',
                                   count_otp = '$count_otp'
                               WHERE ofc_id = $ofc_id";
        $result_upd_otp = mysqli_query($conn, $upd_otp);

        //เตรียม email  ของเจ้าหน้าที่
        $tn_email = $row['ofc_email'];

        //ส่ง OTP ไปที่ email
        require_once "../../16_func_email_sms/func_email.php";
        $header = "แจ้งเตือน OTP";
        $send_email = sendEmail($tn_email, $header, $otp);

        //รับ id ผู้เช่า
        $id = $row['ofc_id'];

        //เมื่อแจ้งเตือน OTP เสร็จ และ update ตาราง otp สำเร็จ
        if ($result_upd_otp) {
            echo "<script type='text/javascript'>";
            echo "alert('OTP ใหม่ส่งสำเร็จ\\nกรุณาตรวจสอบที่ Email เพื่อยืนยัน OTP'); ";
            //ให้กลับมาหน้า ofc_confirm_otp.php page (มาหน้าเดิม)และ ส่งค่า $id แบบ get ไปด้วย
            echo "window.location='ofc_confirm_otp.php?ofc_id=$id'; ";
            echo "</script>";
        } else {
            echo "<script type='text/javascript'>";
            echo "alert('มีความผิดพลาด'); ";
            echo "</script>";
        }

    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบบริหารสัญญาเช่า</title>
    <link rel="shortcut icon" href="../../style/ku.png" type="image/x-icon" />

    <link rel="stylesheet" href="../../style/style_regis.css">

    <!-- style ของปุ่ม ขอ otp ใหม่ Row:247-->
    <style>
        .wrapper .form .inputfield .btn_2 {
            width: 100%;
            padding: 8px 10px;
            font-size: 15px;
            border: 0px;
            background: #1597E5;
            color: #fff;
            cursor: pointer;
            border-radius: 3px;
            outline: none;
            text-align: center;
        }

        .wrapper .form .inputfield .btn_2:hover {
            background: #FF5151;
        }
    </style>

    <style>
        strong {
            color: red;
        }
    </style>

</head>

<body>

    <div class="wrapper">

        <div class="title">
            ยืนยัน OTP
        </div>

        <!-- เมื่อกรอก OTP แล้วกด ส่ง ระบบจะส่งมาหน้าเดิม -->
        <form action="" method="POST">
            <div class="form">

                <div class="inputfield">
                    <label>หมายเลข OTP <strong>*</strong></label>
                    <!-- กรอกเลข OTP -->
                    <input type="text" class="input" placeholder="หมายเลข OTP" name="ofc_otp" maxlength="5" required>
                </div>

                <div class="inputfield">
                    <input type="submit" value="ส่ง OTP" name="submit" class="btn" onclick="return confirm('ยืนยันการกรอก OTP')">
                </div>
        </form>

        <!-- แสดงข้อมูล วันเวลาที่ OTP หมดอายุ -->
        <div class="inputfield">
            <label>OTP หมดอายุการใช้งานภายใน</label>
        </div>

        <div class="inputfield">
            <label>วัน/เดือน/ปี</label>
            <input type="text" class="input" value="<?php echo date('d / m / Y ', strtotime($row['ofc_otp_date'])); ?>" readonly>
        </div>

        <div class="inputfield">
            <label>เวลา</label>
            <input type="text" class="input" value="<?php echo $row['ofc_otp_time']; ?> น." readonly>
        </div>

        <!-- ขอ OTP ใหม่ -->
        <form action="" method="POST">

            <div class="inputfield">
                <input type="submit" value="ขอรหัส OTP ใหม่" name="new_otp" class="btn_2" onclick="return confirm('ยืนยันการส่ง OTP ใหม่')">
            </div>

        </form>

        <br>

        <div class="inputfield">
            <div>Have account? <a href="../../index.php">Login</a></div>
        </div>

    </div>



    </div>

</body>


</html>