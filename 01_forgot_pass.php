<?php

require_once "../connection.php";
// รับ email ที่ป้อนมาจากด้านล่าง
if (isset($_POST['submit'])) {

    //check captcha
    if (isset($_POST['g-recaptcha-response'])) {
        $captcha = $_POST['g-recaptcha-response']; //ถ้าไม่ได้ติก ค่าตรงนี้ก็ไม่มี
    }
    if (!$captcha) {
        echo "<script type='text/javascript'>";
        echo "alert('โปรดยืนยันตัวตนของคุณ');";
        echo "window.location='01_forgot_pass.php';";
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
        echo "window.location='01_forgot_pass.php';";
        echo "</script>";
    }
    //ถ้า tic captcha แล้ว
    else {

        // รับ email ของผู้ส่งมา
        $email = $_POST['email'];

        //email tenant ผู้เช่า
        $sql = "SELECT * FROM tenant WHERE tn_email = '$email' ";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        //email officer เจ้าหน้าที่
        $sql_2 = "SELECT * FROM officer WHERE ofc_email = '$email' ";
        $result_2 = mysqli_query($conn, $sql_2);
        $row_2 = mysqli_fetch_assoc($result_2);

        //email admin
        $sql_3 = "SELECT * FROM admin WHERE ad_email = '$email' ";
        $result_3 = mysqli_query($conn, $sql_3);
        $row_3 = mysqli_fetch_assoc($result_3);

        //email ผู้ให้เช่า
        $sql_4 = "SELECT * FROM lessor WHERE ls_email = '$email' ";
        $result_4 = mysqli_query($conn, $sql_4);
        $row_4 = mysqli_fetch_assoc($result_4);


        // ถ้า email ที่ส่งมาเป็นของผู้เช่า
        if (isset($row['tn_id'])) {

            $tn_id = $row['tn_id'];
            //mailer ยืนยันตัวตนที่ Email ผู้เช่า
            {
                require_once "../16_func_email_sms/func_email.php";
                $header = "แจ้งเตือนการแก้ไขรหัสผ่าน";
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
                                        ต้องการแก้ไขรหัสผ่าน ของระบบบริหารสัญญาเช่า กรุณากด แก้ไขรหัสผ่าน
                                    </p>
                                    <a href="https://cskps.flas.kps.ku.ac.th/kulease2/06_forgot_pass/02_edit_pass_tn.php?id=' . $tn_id . ' ">
                                       >>> แก้ไขรหัสผ่าน <<<
                                    </a>
                                </div>
                                </body>
                    
                                </html>';

                $send_email = sendEmail($email, $header, $detail);
            }

            echo "<script type='text/javascript'>";
            echo "alert('กรุณาตรวจสอบที่ Email เพื่อทำการแก้ไข รหัสผ่าน');";
            echo "window.location='../index.php';";
            echo "</script>";
        }
        // ถ้า email ที่ส่งมาเป็นของเจ้าหน้าที่
        else if (isset($row_2['ofc_id'])) {

            $ofc_id = $row_2['ofc_id'];

            //mailer ยืนยันตัวตนที่ Email เจ้าหน้าที่
            {
                require_once "../16_func_email_sms/func_email.php";
                $header = "แจ้งเตือนการแก้ไขรหัสผ่าน";
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
                                        ต้องการแก้ไขรหัสผ่าน ของระบบบริหารสัญญาเช่า กรุณากด แก้ไขรหัสผ่าน 
                                    </p>
                                    <a href="https://cskps.flas.kps.ku.ac.th/kulease2/06_forgot_pass/03_edit_pass_ofc.php?id=' . $ofc_id . ' ">
                                       >>> แก้ไขรหัสผ่าน <<<
                                    </a>
                                </div>
                                </body>
                    
                                </html>';

                $header = "แจ้งเตือนการแก้ไขรหัสผ่าน";
                $send_email = sendEmail($email, $header, $detail);
            }

            echo "<script type='text/javascript'>";
            echo "alert('กรุณาตรวจสอบที่ Email เพื่อทำการแก้ไข รหัสผ่าน');";
            echo "window.location='../index.php';";
            echo "</script>";
        }
        // ถ้า email ที่ส่งมาเป็นของ admin
        else if (isset($row_3['ad_id'])) {

            $ad_id = $row_3['ad_id'];

            //mailer ยืนยันตัวตนที่ Email admin
            {
                $header = "แจ้งเตือนการแก้ไขรหัสผ่าน";
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
                                        ต้องการแก้ไขรหัสผ่าน ของระบบบริหารสัญญาเช่า กรุณากด แก้ไขรหัสผ่าน 
                                    </p>
                                    <a href="https://cskps.flas.kps.ku.ac.th/kulease2/06_forgot_pass/04_edit_pass_ad.php?id=' . $ad_id . ' ">
                                       >>> แก้ไขรหัสผ่าน <<<
                                    </a>
                                </div>
                                </body>
                    
                                </html>';

                require_once "../16_func_email_sms/func_email.php";
                $header = "แจ้งเตือนการแก้ไขรหัสผ่าน";
                $send_email = sendEmail($email, $header, $detail);
            }

            echo "<script type='text/javascript'>";
            echo "alert('กรุณาตรวจสอบที่ Email เพื่อทำการแก้ไข รหัสผ่าน');";
            echo "window.location='../index.php';";
            echo "</script>";
        }
        // ถ้า email ที่ส่งมาเป็นของผู้ให้เช่า
        else if (isset($row_4['ls_id'])) {

            $ad_id = $row_4['ls_id'];

            //mailer ยืนยันตัวตนที่ Email
            {
                $header = "แจ้งเตือนการแก้ไขรหัสผ่าน";
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
                                        ต้องการแก้ไขรหัสผ่าน ของระบบบริหารสัญญาเช่า กรุณากด แก้ไขรหัสผ่าน 
                                    </p>
                                    <a href="https://cskps.flas.kps.ku.ac.th/kulease2/06_forgot_pass/05_edit_pass_boss.php?id=' . $ad_id . ' ">
                                       >>> แก้ไขรหัสผ่าน <<<
                                    </a>
                                </div>
                                </body>
                    
                                </html>';

                require_once "../16_func_email_sms/func_email.php";
                $header = "แจ้งเตือนการแก้ไขรหัสผ่าน";
                $send_email = sendEmail($email, $header, $detail);
            }

            echo "<script type='text/javascript'>";
            echo "alert('กรุณาตรวจสอบที่ Email เพื่อทำการแก้ไข รหัสผ่าน');";
            echo "window.location='../index.php';";
            echo "</script>";
        }
        // ถ้า email ที่ส่งมาไม่มีในฐานข้อมูลเรา
        else { //กรณีเมลมั่วๆ ไม่ส่งอะไรทั้งนั้น หลอกผู้ใช้ว่าส่งแล้ว
            echo "<script type='text/javascript'>";
            echo "alert('กรุณาตรวจสอบที่ Email เพื่อทำการแก้ไข รหัสผ่าน');";
            echo "window.location='../index.php';";
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
    <link rel="shortcut icon" href="../style/ku.png" type="image/x-icon" />

    <link rel="stylesheet" href="../style/style_forgot_pass.css">

    <!-- captcha -->
    <script src='https://www.google.com/recaptcha/api.js'></script>

</head>

<body>

    <div class="wrapper">

        <div class="title">
            ลืมรหัสผ่าน !
        </div>

        <!-- เมื่อกด submit ส่งมาที่หน้าเดิม ทำงานส่วนบน -->
        <form action="" method="POST">
            <div class="form">

                <!-- กรอก email ที่เราใช้งาน -->
                <div class="inputfield">
                    <label>Email ที่ใช้งาน <strong style="color: red;">*</strong> </label>
                    <input type="email" class="input" placeholder="Email" name="email" required>
                </div>

                <!-- captcha -->
                <div class="g-recaptcha" data-sitekey="6LeVlS0eAAAAAPRa_miqrN5onth5_BGOqRchmPJD"></div>

                <br>

                <div class="inputfield">
                    <input type="submit" value="ส่ง Email" name="submit" class="btn" onclick="return confirm('ยืนยันการแก้ไขรหัสผ่าน')">
                </div>

                <div class="inputfield">
                    <div>Go to page ? <a href="../index">Login</a></div>
                </div>

            </div>

        </form>

    </div>

</body>


</html>