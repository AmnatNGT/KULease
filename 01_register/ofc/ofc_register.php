<?php

require_once "../../connection.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ระบบบริหารสัญญาเช่า</title>
    <link rel="shortcut icon" href="../../style/ku.png" type="image/x-icon" />

    <link rel="stylesheet" href="../../style/style_regis.css">

    <!-- captcha -->
    <script src='https://www.google.com/recaptcha/api.js'></script>

    <style>
        strong {
            color: red;
        }
    </style>

</head>

<body>

    <div class="wrapper">

        <div class="title">
            ลงทะเบียนเจ้าหน้าที่
        </div>

        <form action="ofc_register_db.php" method="POST">
            <div class="form">

                <div class="inputfield">
                    <label>บทบาทการใช้งาน <strong>*</strong></label>
                    <div class="custom_select">
                        <select name="role_use" required>
                            <option value="" hidden>เลือกบทบาทการใช้งาน</option>
                            <option value="r1">เจ้าหน้าที่เพิ่มสัญญาเช่า</option>
                            <option value="r3">เจ้าหน้าที่การเงิน</option>
                            <option value="r2">เจ้าหน้าที่พิเศษ</option>
                            <option value="r5">ผู้จัดการหอพัก</option>
                            <option value="r4">นิติกร</option>
                            <option value="r6">ผู้บริหาร มหาวิทยาลัยเกษตรศาสตร์</option>
                        </select>
                    </div>
                </div>

                <div class="inputfield">
                    <label>Email <strong>*</strong></label>
                    <input type="email" class="input" placeholder="Email" name="tn_email" required>
                </div>

                <div class="inputfield">
                    <label>Password <strong>*</strong></label>
                    <input type="password" class="input" placeholder="Password" name="tn_password" required>
                </div>

                <div class="inputfield">
                    <label>Confirm Password <strong>*</strong></label>
                    <input type="password" class="input" placeholder="Confirm Password" name="tn_password_2" required>
                </div>

                <div class="inputfield">
                    <label>คำนำหน้าชื่อ <strong>*</strong></label>
                    <div class="custom_select">
                        <!-- เมื่อเลือกคำนำหน้าชื่อแล้วให้ส่ง value ที่เลือกไปที่ function type_name Row: 85 -->
                        <select name="tn_p_name" id="tn_p_name" onchange="type_name()" required>
                            <option value="" hidden>เลือกคำนำหน้าชื่อ</option>
                            <option value="n1">นาย</option>
                            <option value="n2">นาง</option>
                            <option value="n3">นางสาว</option>
                            <option value="n4">อื่นๆ : เช่น ศ. / รศ.</option>
                        </select>
                    </div>
                </div>

                <!--ตรวจสอบว่าคำนำหน้าชื่อที่รับมาเป็น อืนๆหรือไม่-->
                <script>
                    function type_name() {
                        index = document.getElementById('tn_p_name').value;

                        // ถ้าคำนำหน้าชื่อเป็น n4 (อื่นๆ) ให้แสดงช่องกรอกข้อมูล tn_p_name_oth ที่อยู่บรรทัด 75
                        if (index == 'n4') {
                            document.getElementById('tn_p_name_oth').style.display = '';
                        }
                        // ถ้าคำนำหน้าชื่อไม่เป็น n4 (อื่นๆ) ให้ไม่ต้องแสดงช่องกรอกข้อมูล tn_p_name_oth ที่อยู่บรรทัด 102 ให้เป็น style="display:none" เหมือนเดิม
                        else {
                            document.getElementById('tn_p_name_oth').style.display = 'none';
                        }

                    }
                </script>

                <!-- ถ้าคำนำหน้าชื่อเป็นอื่นๆช่องนี้จะแสดงขึ้น แต่ตอนเริ่มต้นใช้ซ้อนไว้ก่อน style="display:none" -->
                <div class="inputfield" id="tn_p_name_oth" style="display:none">
                    <label>คำนำหน้าชื่อ อื่นๆ</label>
                    <input type="text" class="input" placeholder="คำนำหน้าชื่อ อื่นๆ : เช่น ศ. / รศ." name="tn_p_name_oth">
                </div>

                <div class="inputfield">
                    <label>ชื่อ <strong>*</strong></label>
                    <input type="text" class="input" placeholder="ชื่อ" name="tn_f_name" required>
                </div>

                <div class="inputfield">
                    <label>นามสกุล <strong>*</strong></label>
                    <input type="text" class="input" placeholder="นามสกุล" name="tn_l_name" required>
                </div>

                <div class="inputfield">
                    <label>ตำแหน่ง <strong>*</strong></label>
                    <input type="text" class="input" placeholder="ตำแหน่ง" name="tn_role" required>
                </div>

                <?php
                //เป็นการนำปีปัจจุบัน -18 เพื่อนำไปใช้ที่บรรทัด 132 คือเป็นการ fix ว่าผู้ใช้งานต้องอายุมากกว่า 18
                date_default_timezone_set('Asia/Bangkok');
                $date = date("Y-m-d");
                $date_fix = date("Y-m-d", strtotime("-18 year ", strtotime("$date")));
                ?>

                <div class="inputfield">
                    <label>ว/ด/ป เกิด <strong>*</strong></label>
                    <!-- ให้มีเริ่มต้นที่แสดงในปฏิทิน เป็นปีที่ ปีปัจจุบัน - 18 คือค่า $date_fix ที่ได้รับจากบรรทัด 124 -->
                    <input type="date" class="input" name="tn_birth_date" max="<?php echo $date_fix ?>" required>
                </div>

                <div class="inputfield">
                    <label>เบอร์โทรศัพท์ <strong>*</strong></label>
                    <input type="tel" class="input" placeholder="เบอร์โทรศัพท์" name="tn_tel" maxlength="10" required>
                </div>

                <br>

                <div>
                    <input type="checkbox" class="largerCheckbox" required>
                    &nbsp; <label for="vehicle1"> <strong>ฉันยินยอม และรับทราบนโยบายคุ้มครองข้อมูลส่วนบุคคล</strong> </label> <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <label for="vehicle1"> <a href="../../file_uploads/policy/policy.pdf" target="_blank"> <strong><u>กดอ่านนโยบายคุ้มครองข้อมูลส่วนบุคคล</u></strong></a> </label>
                </div>

                <br>

                <!-- captcha -->
                <div class="g-recaptcha" data-sitekey="6LeVlS0eAAAAAPRa_miqrN5onth5_BGOqRchmPJD"></div>

                <br>

                <div class="inputfield">
                    <input type="submit" value="Register" name="submit_1" class="btn" onclick="return confirm('ยืนยันการเพิ่มข้อมูล')">
                </div>

                <div class="inputfield">
                    <div>Have account? <a href="../../index.php">Login</a></div>
                </div>

            </div>

        </form>

    </div>

</body>



</html>