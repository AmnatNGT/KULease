<?php

session_start();
require_once "../../connection.php";

//รับ Id ล่าสุด
$tn_id = $_GET['tn_id'];

//ดึงข้อมูลผู้เช่าจากฐานข้อมูล
$sql = "SELECT * FROM tenant WHERE tn_id = $tn_id ";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

//Check OTP
//ถ้าได้รับ $_POST['submit'] ให้ทำงานที่นี้
if (isset($_POST['submit'])) {

    //รับ OTP ที่ส่งมาจากด้านล่าง [Row:219]
    $otp = $_POST['tn_otp'];

    //รับ email ของผู้เช่า จากฐานข้อมูล [Row:12]
    $r_mail = $row['tn_email'];

    //รับ otp ของผู้เช่า จากฐานข้อมูล [Row:12]
    $sql_otp = $row['tn_otp'];

    $tn_email = $r_mail;

    //Check ว่า otp ที่กรอก กับ otp จากฐานข้อมูลเหมือนกันหรือไม่
    //ถ้า otp ที่กรอก === otp จากฐานข้อมูล ให้ทำงานใน if นี้
    if ($sql_otp === $otp) {

        //รับ วัน และเวลา ที่ otp หมดอายุจากฐานข้อมูล [Row:12]
        $ch_d = $row['tn_otp_date'];
        $ch_t = $row['tn_otp_time'];

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

            //ให้ UPDATE tn_status_otp =1
            $sql_otp_2 = "UPDATE tenant SET tn_status_otp = 1
                                    WHERE tn_id = $tn_id";
            $result_otp_2 = mysqli_query($conn, $sql_otp_2);

            //ถ้า UPDATE สำเร็จ 
            if ($result_otp_2) {

                //สร้างรูปแบบ confirm email 
                //เมื่อกด  Verify your email address ให้ไปที่หน้า https://cskps.flas.kps.ku.ac.th/kulease2/user_verify_email.php [Row:76]
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
                            <a href="https://cskps.flas.kps.ku.ac.th/kulease2/user_verify_email.php?tn_id=' . $tn_id . ' ">
                               >>> Verify your email address <<<
                            </a>
                        </div>
                        </body>
            
                        </html>';

                //ส่งข้อมูล confirm email
                require_once "../../16_func_email_sms/func_email.php";
                $header = "แจ้งเตือนการยืนยัน Email";
                $send_email = sendEmail($tn_email, $header, $detail);


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

        $count_otp = $count_otp + 1;

        //random otp
        $otp = mt_rand(10000, 99999);

        //วัน เวลาปัจจุบัน
        date_default_timezone_set('Asia/Bangkok');
        $date_otp = date("Y-m-d");
        $time_n = date("H:i:s");

        //time otp end เอาเวลาปัจจุบัน + 5 นาที
        $a = strtotime("+5 minutes", strtotime($time_n));
        $time_otp =  date('H:i:s', $a);

        //update new otp และ วันเวลาที่  otp หมดอายุ ไปเก็บไว้ที่ ตาราง tenant
        $upd_otp = "UPDATE tenant SET  tn_otp = '$otp',
                                   tn_otp_date = '$date_otp',
                                   tn_otp_time = '$time_otp',
                                   count_otp = '$count_otp'
                               WHERE tn_id = $tn_id";
        $result_upd_otp = mysqli_query($conn, $upd_otp);

        //เตรียม email และ เบอร์ ของผู้เช่า
        $tn_email = $row['tn_email'];
        $tn_tel = $row['tn_tel'];

        //ส่ง OTP ไปที่ email
        require_once "../../16_func_email_sms/func_email.php";
        $header = "แจ้งเตือน OTP";
        $send_email = sendEmail($tn_email, $header, $otp);

        //ส่ง OTP ไปที่ SMS
        require_once "../../16_func_email_sms/func_sms.php";
        $send_sms = sendSMS($tn_tel, "OTP >> $otp");

        //รับ id ผู้เช่า
        $id = $row['tn_id'];

        //เมื่อแจ้งเตือน OTP เสร็จ และ update ตาราง otp สำเร็จ
        if ($result_upd_otp) {
            echo "<script type='text/javascript'>";
            echo "alert('OTP ใหม่ส่งสำเร็จ\\nกรุณาตรวจสอบที่ SMS โทรศัพท์ หรือ Email เพื่อยืนยัน OTP'); ";
            //ให้กลับมาหน้า tn_confirm_otp.php page (มาหน้าเดิม)และ ส่งค่า $id แบบ get ไปด้วย
            echo "window.location='tn_confirm_otp.php?tn_id=$id'; ";
            echo "</script>";
        } else {
            echo "<script type='text/javascript'>";
            echo "alert('มีความผิดพลาด'); ";
            echo "</script>";
        }
    }
    // ส่งไปที่ Email เท่านั้น
    else {

        $count_otp = $count_otp + 1;

        //random otp
        $otp = mt_rand(10000, 99999);

        //วัน เวลาปัจจุบัน
        date_default_timezone_set('Asia/Bangkok');
        $date_otp = date("Y-m-d");
        $time_n = date("H:i:s");

        //time otp end เอาเวลาปัจจุบัน + 5 นาที
        $a = strtotime("+5 minutes", strtotime($time_n));
        $time_otp =  date('H:i:s', $a);

        //update new otp และ วันเวลาที่  otp หมดอายุ ไปเก็บไว้ที่ ตาราง tenant
        $upd_otp = "UPDATE tenant SET  tn_otp = '$otp',
                                   tn_otp_date = '$date_otp',
                                   tn_otp_time = '$time_otp',
                                   count_otp = '$count_otp'
                               WHERE tn_id = $tn_id";
        $result_upd_otp = mysqli_query($conn, $upd_otp);

        //เตรียม email  ของผู้เช่า
        $tn_email = $row['tn_email'];

        //ส่ง OTP ไปที่ email
        require_once "../../16_func_email_sms/func_email.php";
        $header = "แจ้งเตือน OTP";
        $send_email = sendEmail($tn_email, $header, $otp);

        //รับ id ผู้เช่า
        $id = $row['tn_id'];

        //เมื่อแจ้งเตือน OTP เสร็จ และ update ตาราง otp สำเร็จ
        if ($result_upd_otp) {
            echo "<script type='text/javascript'>";
            echo "alert('OTP ใหม่ส่งสำเร็จ\\nกรุณาตรวจสอบที่ Email เพื่อยืนยัน OTP'); ";
            //ให้กลับมาหน้า tn_confirm_otp.php page (มาหน้าเดิม)และ ส่งค่า $id แบบ get ไปด้วย
            echo "window.location='tn_confirm_otp.php?tn_id=$id'; ";
            echo "</script>";
        } else {
            echo "<script type='text/javascript'>";
            echo "alert('มีความผิดพลาด'); ";
            echo "</script>";
        }
    }
}

date_default_timezone_set('Asia/Bangkok');

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
                    <input type="text" class="input" placeholder="หมายเลข OTP" name="tn_otp" maxlength="5" required>
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
            <input type="text" class="input" value="<?php echo date('d / m / Y ', strtotime($row['tn_otp_date'])); ?>" readonly>
        </div>

        <div class="inputfield">
            <label>เวลา</label>
            <input type="text" class="input" value="<?php echo $row['tn_otp_time']; ?> น." readonly>
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