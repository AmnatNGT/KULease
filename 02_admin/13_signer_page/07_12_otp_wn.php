<?php

session_start();

if (!$_SESSION['ofc_add']) {
    header("Location: ../../index.php");
}

require('../../connection.php');

//data
{
    // รับ id พยานผู้เช่า
    $wn_id = $_GET['wn_id'];

    // ดึงข้อมูลพยานผู้เช่า
    $sql  = "SELECT * FROM witness WHERE wn_id = $wn_id ";
    $result  = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    //ชื่อ admin
    if (isset($row['ad_id'])) {
        $admin = $row['ad_id'];
        $sql_ad = "SELECT * FROM admin WHERE ad_id = $admin";
        $result_ad  = mysqli_query($conn, $sql_ad);
        $row_ad = mysqli_fetch_assoc($result_ad);
    }

    //ชื่อ เจ้าหน้าที่
    if (isset($row['ofc_id'])) {
        $ofc = $row['ofc_id'];
        $sql_ofc = "SELECT * FROM officer WHERE ofc_id = $ofc";
        $result_ofc  = mysqli_query($conn, $sql_ofc);
        $row_ofc = mysqli_fetch_assoc($result_ofc);
    }

    //Check OTP
    //ถ้าได้รับ $_POST['submit'] ให้ทำงานที่นี้
    if (isset($_POST['submit'])) {

        //รับ OTP ที่ส่งมาจากด้านล่าง
        $otp = $_POST['wn_otp'];

        //ดึง otp พยานของผู้เช่า
        $sql = "SELECT * FROM witness WHERE wn_id = $wn_id ";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        // otp ของพยานของผู้เช่า
        $sql_otp = $row['wn_otp'];

        //Check ว่า otp ที่กรอก กับ otp จากฐานข้อมูลเหมือนกันหรือไม่
        //ถ้า otp ที่กรอก === otp จากฐานข้อมูล ให้ทำงานใน if นี้
        if ($sql_otp === $otp) {

            //รับ วัน และเวลา ที่ otp หมดอายุจากฐานข้อมูล
            $ch_d = $row['wn_otp_date'];
            $ch_t = $row['wn_otp_time'];

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

                // update สถานะพยานผู้เช่า 
                $sql_otp = "UPDATE witness SET wn_status_otp ='1'
                                           WHERE wn_id = $wn_id";
                $result_otp = mysqli_query($conn, $sql_otp);

                if ($result_otp) {

                    echo "<script type='text/javascript'>";
                    echo "alert('ยืนยัน OTP สำเร็จ');";
                    // เมื่อเสร็จไปหน้า 07_1_signer_page.php
                    echo "window.location='07_1_signer_page.php';";
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

            $count_otp = $count_otp + 1;

            //วัน เวลาปัจจุบัน
            date_default_timezone_set('Asia/Bangkok');
            $date_otp = date("Y-m-d");
            $time_n = date("H:i:s");

            //time otp end เอาเวลาปัจจุบัน + 5 นาที
            $a = strtotime("+5 minutes", strtotime($time_n));
            $time_otp =  date('H:i:s', $a);

            //update new otp และ วันเวลาที่  otp หมดอายุ ไปเก็บไว้ที่ ตาราง witness
            $upd_otp = "UPDATE witness SET wn_otp = '$otp',
                                       wn_otp_date = '$date_otp',
                                       wn_otp_time = '$time_otp',
                                       count_otp = '$count_otp'
                                   WHERE wn_id = $wn_id";
            $result_upd_otp = mysqli_query($conn, $upd_otp);

            //เตรียม email และ เบอร์ ของพยานผู้เช่า
            $tn_email = $row['wn_email'];
            $tn_tel = $row['wn_phone'];

            //ส่ง OTP ไปที่ email
            require_once "../../16_func_email_sms/func_email.php";
            $header = "แจ้งเตือน OTP";
            $send_email = sendEmail($tn_email, $header, $otp);

            //ส่ง OTP ไปที่ SMS
            require_once "../../16_func_email_sms/func_sms.php";
            $send_sms = sendSMS($tn_tel, "OTP >> $otp");


            // id พยานของผู้เช่า
            $id = $row['wn_id'];

            if ($result_upd_otp) {
                echo "<script type='text/javascript'>";
                echo "alert('OTP ใหม่ส่งสำเร็จ\\nกรุณาตรวจสอบที่ SMS โทรศัพท์ หรือ Email เพื่อยืนยัน OTP'); ";
                // เมื่อเสร็จไปหน้า 07_12_otp_wn.php และส่ง id พยานของผู้เช่าไปด้วย
                echo "window.location='07_12_otp_wn.php?wn_id=$id'; ";
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

            $count_otp = $count_otp + 1;

            //วัน เวลาปัจจุบัน
            date_default_timezone_set('Asia/Bangkok');
            $date_otp = date("Y-m-d");
            $time_n = date("H:i:s");

            //time otp end เอาเวลาปัจจุบัน + 5 นาที
            $a = strtotime("+5 minutes", strtotime($time_n));
            $time_otp =  date('H:i:s', $a);

            //update new otp และ วันเวลาที่  otp หมดอายุ ไปเก็บไว้ที่ ตาราง witness
            $upd_otp = "UPDATE witness SET wn_otp = '$otp',
                                           wn_otp_date = '$date_otp',
                                           wn_otp_time = '$time_otp',
                                           count_otp = '$count_otp'
                                        WHERE wn_id = $wn_id";
            $result_upd_otp = mysqli_query($conn, $upd_otp);

            //เตรียม email  ของพยานผู้เช่า
            $tn_email = $row['wn_email'];

            //ส่ง OTP ไปที่ email
            require_once "../../16_func_email_sms/func_email.php";
            $header = "แจ้งเตือน OTP";
            $send_email = sendEmail($tn_email, $header, $otp);

            // id พยานของผู้เช่า
            $id = $row['wn_id'];

            if ($result_upd_otp) {
                echo "<script type='text/javascript'>";
                echo "alert('OTP ใหม่ส่งสำเร็จ\\nกรุณาตรวจสอบที่ Email เพื่อยืนยัน OTP'); ";
                // เมื่อเสร็จไปหน้า 07_12_otp_wn.php และส่ง id พยานของผู้เช่าไปด้วย
                echo "window.location='07_12_otp_wn.php?wn_id=$id'; ";
                echo "</script>";
            } else {
                echo "<script type='text/javascript'>";
                echo "alert('มีความผิดพลาด'); ";
                echo "</script>";
            }
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบบริหารสัญญาเช่า</title>
    <link rel="shortcut icon" href="../../style/ku.png" type="image/x-icon" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script src="../../script/jquery-3.6.0.js"></script>

    <!--Icon-->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="../../style/style_navi_bg.css">
    <link rel="stylesheet" href="../../style/style_btn.css">
    <link rel="stylesheet" href="../../style/style_bar_name.css">

</head>

<body>

    <?php
    //header
    include('../header_ofc.php');

    //side bar
    include('../sidebar_ofc.php');
    ?>

    <!-- ชื่อเจ้าหน้าที่ -->
    <div class="bar_name">
        <span class="icon_name"><em class="fa fa-user-circle-o" aria-hidden="true"></em></span>
        <span class="name"><?php echo $_SESSION['ofc_add_name']; ?></span> <br>
        <span class="name"><strong>( ผู้ดูแลระบบ )</strong></span>
    </div>

    <!--ข้อมูล-->
    <main class="data ">

        <div class="wrapper">

            <!-- Back -->
            <div>
                <a href="07_1_signer_page.php" class="back" style="text-decoration:none;">
                    <span class="icon_b" style="color: blue; font-size: 25px;"><em class="fa fa-arrow-left" aria-hidden="true"></em></span>
                    <span class="name_b" style="color: blue; font-size: 20px;"><strong>BACK</strong> </span>
                </a>
            </div>

            <!-- เมื่อกรอก OTP แล้วกด ส่ง ระบบจะส่งมาหน้าเดิม -->
            <form action="" method="POST">
                <div class="title">
                    ยืนยัน OTP
                </div>

                <div class="form">
                    <div class="inputfield">
                        <label>OTP จากพยาน <strong style="color:red;">*</strong></label>
                        <input type="text" class="input" placeholder="กรอก OTP" maxlength="5" autofocus name="wn_otp">
                    </div>
                </div>
                <br>
                <input type="submit" name="submit" id="" value="ส่ง OTP" class="btn_b" onclick="return confirm('ยืนยันการกรอก OTP')">
            </form>

            <br>

            <div class="form">

                <!-- แสดงข้อมูล วันเวลาที่ OTP หมดอายุ -->
                <div class="inputfield">
                    <label>OTP หมดอายุการใช้งานภายใน</label>
                </div>

                <div class="inputfield">
                    <label>วัน/เดือน/ปี</label>
                    <input type="text" class="input" value="<?php echo date('d / m / Y ', strtotime($row['wn_otp_date'])); ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>เวลา</label>
                    <input type="text" class="input" value="<?php echo $row['wn_otp_time']; ?> น." readonly>
                </div>

                <!-- ขอ OTP ใหม่ -->
                <form action="" method="POST">

                    <input type="hidden">

                    <div class="inputfield">
                        <input type="submit" value="ขอรหัส OTP ใหม่" name="new_otp" class="btn_2" onclick="return confirm('ยืนยันการส่ง OTP ใหม่')">
                    </div>

                </form>
            </div>

            <br>

            <!-- ข้อมูลส่วนตัวพยาน -->
            <div class="title">
                ข้อมูลส่วนตัวพยาน
            </div>
            <div class="form">

                <!--adminเพิ่มสัญญา-->
                <?php if ($row['ad_id'] != null) { ?>
                    <div class="inputfield">
                        <label>เจ้าหน้าที่ผู้เพิ่มข้อมูล</label>
                        <input type="email" class="input" value="<?php echo $row_ad['ad_p_name'] . " " . $row_ad['ad_f_name'] . " " . $row_ad['ad_l_name']; ?>" readonly>
                    </div>
                <?php } ?>

                <!--เจ้าหน้าที่เพิ่มสัญญา-->
                <?php if ($row['ofc_id'] != null) { ?>
                    <div class="inputfield">
                        <label>เจ้าหน้าที่ผู้เพิ่มข้อมูล</label>
                        <input type="email" class="input" value="<?php echo $row_ofc['ofc_p_name'] . " " . $row_ofc['ofc_f_name'] . " " . $row_ofc['ofc_l_name']; ?>" readonly>
                    </div>
                <?php } ?>

                <div class="inputfield">
                    <label>ว/ด/ป ที่เพิ่ม</label>
                    <input type="email" class="input" value="<?php echo date('d / m / Y ', strtotime($row["wn_date_add"])); ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>คำนำหน้าชื่อ</label>
                    <input type="text" class="input" value="<?php echo $row['wn_p_name']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ชื่อ</label>
                    <input type="text" class="input" value="<?php echo $row['wn_f_name'] ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>นามสกุล</label>
                    <input type="text" class="input" value="<?php echo $row['wn_l_name']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ตำแหน่ง</label>

                    <?php if ($row['wn_role'] == null) {
                        $role = '-----------';
                    } else {
                        $role = $row['wn_role'];
                    }
                    ?>
                    <input type="text" class="input" value="<?php echo $role; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>เบอร์โทรศัพท์</label>
                    <input type="tel" class="input" value="<?php echo $row['wn_phone']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>อีเมล</label>
                    <input type="tel" class="input" value="<?php echo $row['wn_email']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>บัตรประชาชน</label>
                    <a href="../../file_uploads/witness_id_card/<?php echo $row['wn_id_card']; ?>" target="_blank" class="input" style="color:blue;">ดูบัตรประชาชน</a>
                </div>

            </div>
            <br><br>

            <div class="title">
                ข้อมูลที่อยู่
            </div>
            <div class="form">

                <div class="inputfield">
                    <label>จังหวัด</label>
                    <input type="text" class="input" value="<?php echo $row['wn_province']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>อำเภอ/เขต</label>
                    <input type="text" class="input" value="<?php echo $row['wn_district']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ตำบล/แขวง</label>
                    <input type="text" class="input" value="<?php echo $row['wn_canton']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>หมู่</label>
                    <input type="text" class="input" value="<?php echo $row['wn_moo']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>บ้านเลขที่</label>
                    <input type="text" class="input" value="<?php echo $row['wn_no']; ?>" readonly>
                </div>

            </div>


        </div>


    </main>


    <!--js-->
    <script src="../../script/main.js"></script>

</body>

</html>