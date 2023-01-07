<?php

require_once "../../connection.php";

//เรียกจังหวัดจากฐานข้อมูล
$sql_pro = "SELECT * FROM provinces ORDER BY name_th"; //เตรียมข้อมูลไว้
$query_pro = mysqli_query($conn, $sql_pro); //อนุญาติใช้ข้อมูล

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
            ลงทะเบียนผู้เช่า
        </div>

        <!-- กรอกข้อมูลในการสมัครสมาชิก -->
        <form action="tn_register_db.php" method="POST">

            <div class="form">

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
                    <label>บริษัทที่ทำงาน (ถ้ามี)</label>
                    <input type="text" class="input" placeholder="บริษัทที่ทำงาน (ถ้ามี)" name="tn_cpn">
                </div>

                <div class="inputfield">
                    <label>คำนำหน้าชื่อ <strong>*</strong></label>
                    <div class="custom_select">
                        <!-- เมื่อเลือกคำนำหน้าชื่อแล้วให้ส่ง value ที่เลือกไปที่ function type_name() [Row: 77] -->
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

                        // ถ้าคำนำหน้าชื่อเป็น n4 (อื่นๆ) ให้แสดงช่องกรอกข้อมูล tn_p_name_oth ที่อยู่บรรทัด 94 
                        if (index == 'n4') {
                            document.getElementById('tn_p_name_oth').style.display = '';
                        }
                        // ถ้าคำนำหน้าชื่อไม่เป็น n4 (อื่นๆ) ให้ไม่ต้องแสดงช่องกรอกข้อมูล tn_p_name_oth ที่อยู่บรรทัด 94 ให้เป็น style="display:none" เหมือนเดิม
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
                    <label>ตำแหน่ง (ถ้ามี)</label>
                    <input type="text" class="input" placeholder="ตำแหน่ง (ถ้ามี)" name="tn_role">
                </div>

                <div class="inputfield">
                    <label>เชื้อชาติ <strong>*</strong></label>
                    <input type="text" class="input" placeholder="เชื้อชาติ" name="tn_ethnicity" required>
                </div>

                <div class="inputfield">
                    <label>สัญชาติ <strong>*</strong></label>
                    <input type="text" class="input" placeholder="สัญชาติ" name="tn_nationality" required>
                </div>

                <?php
                //เป็นการนำปีปัจจุบัน -18 เพื่อนำไปใช้ที่บรรทัด 135 คือเป็นการ fix ว่าผู้ใช้งานต้องอายุมากกว่า 18
                date_default_timezone_set('Asia/Bangkok');
                $date = date("Y-m-d");
                $date_fix = date("Y-m-d", strtotime("-18 year ", strtotime("$date")));
                ?>

                <div class="inputfield">
                    <label>ว/ด/ป เกิด <strong>*</strong></label>
                    <!-- ให้มีเริ่มต้นที่แสดงในปฏิทิน เป็นปีที่ ปีปัจจุบัน - 18 คือค่า $date_fix ที่ได้รับจากบรรทัด 130 -->
                    <input type="date" class="input" name="tn_birth_date" max="<?php echo $date_fix ?>" required>
                </div>

                <div class="inputfield">
                    <label>เลขประจำตัวประชาชน <strong>*</strong></label>
                    <input type="text" class="input" placeholder="เลขประจำตัวประชาชน 13 หลัก" name="tn_id_card" maxlength="13" required>
                </div>

                <div class="inputfield">
                    <label>เบอร์โทรศัพท์ <strong>*</strong></label>
                    <input type="tel" class="input" placeholder="เบอร์โทรศัพท์" name="tn_tel" maxlength="10" required>
                </div>


                <div class="inputfield">
                    <label>บ้านเลขที่ <strong>*</strong></label>
                    <input type="text" class="input" placeholder="บ้านเลขที่" name="tn_house_no">
                </div>

                <div class="inputfield">
                    <label>ซอย</label>
                    <input type="text" class="input" placeholder="ซอย" name="tn_alley">
                </div>

                <div class="inputfield">
                    <label>หมู่</label>
                    <input type="text" class="input" placeholder="หมู่" name="tn_moo">
                </div>

                <div class="inputfield">
                    <label>ถนน</label>
                    <input type="text" class="input" placeholder="ถนน" name="tn_road">
                </div>


                <div class="inputfield">
                    <label>จังหวัด <strong>*</strong></label>
                    <div class="custom_select">
                        <select name="tn_province" id="provinces" required>
                            <option value="" hidden>เลือกจังหวัด</option>
                            <!-- แสดงข้อมูลจังหวัดทั้งหมดที่ดึงมาจากฐานข้อมูล บรรทัด 5 โดยให้ value เป็น id จังหวัด -->
                            <?php foreach ($query_pro as $value) { ?>
                                <option value="<?= $value['id'] ?>"> <?= $value['name_th'] ?> </option>
                            <?php } ?>

                        </select>
                    </div>
                </div>

                <div class="inputfield">
                    <!-- แสดงผลอำเภอทั้งหมดที่อยู่ในจังหวัดที่เลือก -->
                    <label>อำเภอ/เขต <strong>*</strong></label>
                    <div class="custom_select">
                        <select name="tn_district" id="amphures" required>
                            <option value="" hidden>เลือกอำเภอ/เขต</option>
                        </select>
                    </div>
                </div>

                <div class="inputfield">
                    <!-- แสดงผลตำบลทั้งหมดที่อยู่ในจังหวัดที่เลือก -->
                    <label>ตำบล/แขวง</label>
                    <div class="custom_select">
                        <select name="tn_canton" id="districts">
                            <option value="" hidden>เลือกตำบล/แขวง</option>

                        </select>
                    </div>
                </div>

                <div class="inputfield">
                    <label>รหัสไปรษณีย์</label>
                    <!-- แสดงผลรหัสไปรษณีย์ -->
                    <input type="text" class="input" placeholder="รหัสไปรษณีย์" name="tn_postcode" id="zip_code" readonly>
                </div>

                <br>

                <div>
                    <input type="checkbox" class="largerCheckbox" required>
                    &nbsp; <label for="vehicle1"> <strong>ฉันยินยอม และรับทราบนโยบายคุ้มครองข้อมูลส่วนบุคคล</strong> </label> <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <label for="vehicle1"> <a href="../../file_uploads/policy/policy.pdf" target="_blank"> <strong><u>กดอ่านนโยบายคุ้มครองข้อมูลส่วนบุคคล</u></strong></a>   </label>
                </div>

                <br>

                <!-- toc captcha -->
                <div class="g-recaptcha" data-sitekey="6LeVlS0eAAAAAPRa_miqrN5onth5_BGOqRchmPJD"></div>

                <br>

                <!-- ปุ่ม register -->
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

<script src="../../script/jquery.min.js"></script>

<script type="text/javascript">
    // ถ้าข้องมูลของจังหวัดเกิดการเปลี่ยนแปลงจากบรรทัด 153 ให้ส่งค่า id จังหวัดมาที่นี่
    $('#provinces').change(function() {
        var id_provinces = $(this).val();

        //ส่ง ID จังหวัดที่รับมาเป็นแบบ POST ไปที่ หน้า find_provinces_db.php โดยส่งข้อมูล id_จังหวัด และชื่อ function ที่ให้ทำงานด้วยคือ province
        //เมื่อเสร็จแล้วส่งค่าอำเภอทั้งหมดที่อยู่ในจังหวัดที่เลือก กลับมาที่ตัวแปร data และแสดงผลที่ id amphures บรรทัด 165
        $.ajax({
            type: "POST",
            url: "find_provinces_db.php",
            data: {
                id: id_provinces,
                function: 'provinces'
            },
            success: function(data) {
                $('#amphures').html(data);
            }
        });
    });

    // ถ้าข้องมูลของอำเภอเกิดการเปลี่ยนแปลงจากบรรทัด 168 ให้ส่งค่า id อำเภอมาที่นี่
    $('#amphures').change(function() {
        var id_amphures = $(this).val();

        //ส่ง ID อำเภอที่รับมาเป็นแบบ POST ไปที่ หน้า find_provinces_db.php โดยส่งข้อมูล id_อำเภอ และชื่อ function ที่ให้ทำงานด้วยคือ amphures
        //เมื่อเสร็จแล้วส่งค่าตำบลทั้งหมดที่อยู่ในอำเภอที่เลือก กลับมาที่ตัวแปร data และแสดงผลที่ id districts บรรทัด 175
        $.ajax({
            type: "POST",
            url: "find_provinces_db.php",
            data: {
                id: id_amphures,
                function: 'amphures'
            },
            success: function(data) {
                $('#districts').html(data);
            }
        });
    });

    // ถ้าข้องมูลของตำบลเกิดการเปลี่ยนแปลงจากบรรทัด 178 ให้ส่งค่า id ตำบลมาที่นี่
    $('#districts').change(function() {
        var id_districts = $(this).val();

        //ส่ง ID ตำบลที่รับมาเป็นแบบ POST ไปที่ หน้า find_provinces_db.php โดยส่งข้อมูล id_อำเภอ และชื่อ function ที่ให้ทำงานด้วยคือ districts
        //เมื่อเสร็จแล้วส่งค่ารหัสไปรษณีย์ที่อยู่ในตำบลที่เลือก กลับมาที่ตัวแปร data และแสดงผลที่ id zip_code บรรทัด 187
        $.ajax({
            type: "POST",
            url: "find_provinces_db.php",
            data: {
                id: id_districts,
                function: 'districts'
            },
            success: function(data) {
                //console.log(data)
                $('#zip_code').val(data);
            }
        });
    });
</script>

</html>