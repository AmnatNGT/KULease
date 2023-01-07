<?php
require_once "../connection.php";

// เมื่อผู้เช่ากดแก้ไข password จากอีเมล แล้วจะมาหน้านี้

// รับ id ผู้เช่า
$tn_id = $_GET['id'];

// ถ้ากด submit แล้วทำงานส่วนนี้
if (isset($_POST['submit'])) {

    //check captcha
    if (isset($_POST['g-recaptcha-response'])) {
        $captcha = $_POST['g-recaptcha-response']; //ถ้าไม่ได้ติก ค่าตรงนี้ก็ไม่มี
    }
    if (!$captcha) {
        echo "<script type='text/javascript'>";
        // echo "alert('โปรดยืนยันตัวตนของคุณ');";
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
        echo "</script>";
    }
    //ถ้า tic captcha แล้ว
    else {

        // รับ Password
        $p1 = $_POST['p1'];
        $p2 = $_POST['p2'];

        // Check ว่า PW ตรงกันมั้ย
        if ($p1 != $p2) {
            echo "<script type='text/javascript'>";
            echo "alert('Password ไม่ตรงกัน');";
            echo "</script>";
        }
        //  ถ้าตรงกัน
        else {

            // นำ PW เข้ารหัส
            $pw = hash('sha256', $p1);

            //update password ใหม่ที่ id ผู้เช่านั้นๆ
            $sql = "UPDATE tenant SET tn_password = '$pw' WHERE tn_id = '$tn_id' ";
            $result = mysqli_query($conn, $sql);

            echo "<script type='text/javascript'>";
            echo "alert('แก้ไข Password สำเร็จ');";
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
            แก้ไขรหัสผ่าน !
        </div>

        <!-- เมื่อกด submit กลับมาหน้าเดิม ไปทำงานด้านบน -->
        <form action="" method="POST">
            <div class="form">

                <div class="inputfield">
                    <label>Password</label>
                    <input type="password" class="input" placeholder="Password" name="p1" required>
                </div>

                <div class="inputfield">
                    <label>Confirm Password</label>
                    <input type="password" class="input" placeholder="Password" name="p2" required>
                </div>

                <!-- captcha -->
                <div class="g-recaptcha" data-sitekey="6LeVlS0eAAAAAPRa_miqrN5onth5_BGOqRchmPJD"></div>

                <br>

                <div class="inputfield">
                    <input type="submit" value="บันทึก" name="submit" class="btn" onclick="return confirm('ยืนยันการแก้ไข')">
                </div>

                <div class="inputfield">
                    <div>Go to page ? <a href="../index.php">Login</a></div>
                </div>

            </div>

        </form>

    </div>

</body>


</html>