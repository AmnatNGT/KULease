<?php

session_start();
require_once "../../../connection.php";

if (!$_SESSION['ofc_add']) {
    header("Location: ../../../index.php");
}

//รับ Id สัญญา ล่าสุด
$le_id = $_SESSION['le_id'];

//ดึงข้อมูล
$sql = "SELECT * FROM status_lease stl, lease l, tenant t
                WHERE stl.le_id = $le_id
                AND stl.le_id = l.le_id
                AND l.tn_id = t.tn_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

//Check OTP
//ถ้าได้รับ $_POST['submit'] ให้ทำงานที่นี้
if (isset($_POST['submit'])) {

    //รับ OTP ที่ส่งมาจากด้านล่าง
    $otp = $_POST['tn_otp'];

    //รับ email ของผู้เช่า จากฐานข้อมูล
    $r_mail = $row['tn_email'];

    //รับ otp ของผู้เช่า จากฐานข้อมูล
    $sql_otp = $row['otp_t2'];
    $tn_email = $r_mail;

    //Check ว่า otp ที่กรอก กับ otp จากฐานข้อมูลเหมือนกันหรือไม่
    //ถ้า otp ที่กรอก === otp จากฐานข้อมูล ให้ทำงานใน if นี้
    if ($sql_otp === $otp) {

        //รับ วัน และเวลา ที่ otp หมดอายุจากฐานข้อมูล
        $ch_d = $row['otp_date_tn2'];
        $ch_t = $row['otp_time_tn2'];

        //หา วัน และเวลา ปัจจุบัน
        date_default_timezone_set('Asia/Bangkok');
        $d_now = date("Y-m-d");
        $t_now = date("H:i:s");

        //ตรวจสอบว่า ถ้า วันและเวลาปัจจุบัน > วันและเวลาที่ otp หมดอายุ ให้แจ้งเตือน
        if ($d_now > $ch_d || $t_now > $ch_t) {
            echo "<script type='text/javascript'>";
            echo "alert('OTP หมดอายุการใช้งานแล้ว \\nกรุณากด >> ขอรหัส OTP ใหม่ << เพื่อรับรหัสใหม่');";
            echo "window.location='06_confirm_otp.php';";
            echo "</script>";
        }
        //ถ้า วันและเวลาปัจจุบัน < วันและเวลาที่ otp หมดอายุ
        else {

            // รับชื่อรูปภาพที่เป็น session
            $fullname = $_SESSION['fullname2'];

            //update status_lease ให้ไปสถานะ เจ้าหน้าที่พิเศษลงนาม
            $q7 = "UPDATE status_lease SET st_s1='1', d_st_s1='$d_now', t_st_s1='$t_now', st_s2='0' WHERE le_id = $le_id ";
            $result_q7 = mysqli_query($conn, $q7);

            //update ลายเซนพยานของผู้เช่า
            $q8 = "UPDATE lease SET wn_sign_pic = '$fullname' WHERE le_id = $le_id ";
            $result_q8 = mysqli_query($conn, $q8);

            if ($result_q7 and $result_q8) {

                echo "<script type='text/javascript'>";
                echo "alert('ยืนยัน OTP สำเร็จ');";
                // เมื่อเสร็จไปหน้า set_pdf/03_set_pdf_db.php
                echo "window.location='set_pdf/03_set_pdf_db.php';";
                echo "</script>";
            } else {
                echo "<script type='text/javascript'>";
                echo "alert('ผิดพลาด');";
                echo "</script>";
            }
        }
    } else {
        echo "<script type='text/javascript'>";
        echo "alert('OTP ไม่ถูกต้อง ');";
        echo "window.location='06_confirm_otp.php';";
        echo "</script>";
    }
}

//ขอ New OTP
//ถ้าได้รับ $_POST['new_otp'] ให้ทำงานที่
else if (isset($_POST['new_otp'])) {

    $count_otp = $row['count_otp_tn2'];

    // ถ้า count_otp < 3 ส่งไปที่ Email และ SMS
    if ($count_otp < 3) {

        $count_otp = $count_otp + 1;

        //random otp
        $otp = mt_rand(10000, 99999);

        //เวลา
        date_default_timezone_set('Asia/Bangkok');
        $date_otp = date("Y-m-d");
        $time_n = date("H:i:s");

        //time otp end
        $a = strtotime("+5 minutes", strtotime($time_n));
        $time_otp =  date('H:i:s', $a);

        //update new otp และ วันเวลาที่  otp หมดอายุ ไปเก็บไว้ที่ ตาราง status_lease
        $q7 = "UPDATE status_lease SET otp_t2='$otp',
                                otp_date_tn2='$date_otp', 
                                otp_time_tn2='$time_otp',
                                count_otp_tn2 ='$count_otp'  
                                WHERE le_id = $le_id ";
        $result_q7 = mysqli_query($conn, $q7);

        //เตรียม email และ เบอร์ พยานของผู้เช่า
        $sql = "SELECT * FROM status_lease stl, lease l, witness wn
                WHERE stl.le_id = $le_id
                AND stl.le_id = l.le_id
                AND l.le_sign_wn = wn.wn_id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        //เตรียม email และ เบอร์ พยานของผู้เช่า
        $tn_email = $row['wn_email'];
        $tn_tel = $row['wn_phone'];

        //ส่ง OTP ไปที่ email
        require_once "../../../16_func_email_sms/func_email.php";
        $header = "แจ้งเตือน OTP";
        $send_email = sendEmail($tn_email, $header, $otp);

        //ส่ง OTP ไปที่ SMS
        require_once "../../../16_func_email_sms/func_sms.php";
        $send_sms = sendSMS($tn_tel, "OTP >> $otp");

        if ($result_q7) {
            echo "<script type='text/javascript'>";
            echo "alert('OTP ใหม่ส่งสำเร็จ\\nกรุณาตรวจสอบที่ SMS โทรศัพท์ หรือ Email เพื่อยืนยัน OTP'); ";
            echo "window.location='06_confirm_otp.php'; ";
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

        //เวลา
        date_default_timezone_set('Asia/Bangkok');
        $date_otp = date("Y-m-d");
        $time_n = date("H:i:s");

        //time otp end
        $a = strtotime("+5 minutes", strtotime($time_n));
        $time_otp =  date('H:i:s', $a);

        //update new otp และ วันเวลาที่  otp หมดอายุ ไปเก็บไว้ที่ ตาราง status_lease
        $q7 = "UPDATE status_lease SET otp_t2='$otp',
                                otp_date_tn2='$date_otp', 
                                otp_time_tn2='$time_otp',
                                count_otp_tn2 ='$count_otp'  
                                WHERE le_id = $le_id ";
        $result_q7 = mysqli_query($conn, $q7);

        //เตรียม email และ เบอร์ พยานของผู้เช่า
        $sql = "SELECT * FROM status_lease stl, lease l, witness wn
                WHERE stl.le_id = $le_id
                AND stl.le_id = l.le_id
                AND l.le_sign_wn = wn.wn_id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        //เตรียม email พยานของผู้เช่า
        $tn_email = $row['wn_email'];

        //ส่ง OTP ไปที่ email
        require_once "../../../16_func_email_sms/func_email.php";
        $header = "แจ้งเตือน OTP";
        $send_email = sendEmail($tn_email, $header, $otp);

        if ($result_q7) {
            echo "<script type='text/javascript'>";
            echo "alert('OTP ใหม่ส่งสำเร็จ\\nกรุณาตรวจสอบที่ Email เพื่อยืนยัน OTP'); ";
            echo "window.location='06_confirm_otp.php'; ";
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
    <link rel="shortcut icon" href="../../../style/ku.png" type="image/x-icon" />

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

    <!--Icon-->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="../../../style/style_navi_bg.css">
    <link rel="stylesheet" href="../../../style/style_btn.css">
    <link rel="stylesheet" href="../../../style/style_bar_name.css">

</head>

<body>

    <?php
    //header
    include('../../header_ofc.php');

    //side bar
    include('../../sidebar_ofc_sign_page.php');
    ?>

    <!-- ชื่อเจ้าหน้าที่ -->
    <div class="bar_name">
        <span class="icon_name"><em class="fa fa-user-circle-o" aria-hidden="true"></em></span>
        <span class="name"><?php echo $_SESSION['ofc_add_name']; ?></span> <br>
        <span class="name"><strong>( เจ้าหน้าที่ เพิ่มสัญญาเช่า )</strong></span>
    </div>

    <div class="wrapper">

        <div class="title">
            ยืนยัน OTP
        </div>

        <!-- เมื่อกรอก OTP แล้วกด ส่ง ระบบจะส่งมาหน้าเดิม -->
        <form action="" method="POST">
            <div class="form">

                <div class="inputfield">
                    <label>หมายเลข OTP <strong style="color:red;">*</strong></label>
                    <input type="text" class="input" placeholder="หมายเลข OTP" name="tn_otp" maxlength="5" required>
                </div>

                <div class="inputfield">
                    <input type="submit" value="ส่ง OTP" name="submit" class="btn_no" onclick="return confirm('ยืนยันการกรอก OTP')">
                </div>

        </form>

        <!-- แสดงข้อมูล วันเวลาที่ OTP หมดอายุ -->
        <div class="inputfield">
            <label>OTP หมดอายุการใช้งานภายใน</label>
        </div>

        <div class="inputfield">
            <label>วัน/เดือน/ปี</label>
            <input type="text" class="input" value="<?php echo date('d / m / Y ', strtotime($row['otp_date_tn2'])); ?>" readonly>
        </div>

        <div class="inputfield">
            <label>เวลา</label>
            <input type="text" class="input" value="<?php echo $row['otp_time_tn2']; ?> น." readonly>
        </div>

        <!-- ขอ OTP ใหม่ -->
        <form action="" method="POST">

            <input type="hidden">

            <div class="inputfield">
                <input type="submit" value="ขอรหัส OTP ใหม่" name="new_otp" class="btn_2" onclick="return confirm('ยืนยันการส่ง OTP ใหม่')">
            </div>

        </form>

        <br>

        <div class="inputfield">
            <div>ยกเลิกการลงนาม <a href="01_lease.php">ย้อนกลับ</a></div>
        </div>

    </div>



    </div>

    <!--js-->
    <script src="../../../script/main.js"></script>

</body>


</html>