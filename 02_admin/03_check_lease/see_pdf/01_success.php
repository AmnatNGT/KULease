<?php {
    session_start();

    if (!$_SESSION['ofc_add']) {
        header("Location: ../../../index.php");
    }

    require('../../../connection.php');
    // เรียกใช้ function แปลงเลขเป็นประโยคสัญลักษณ์
    require('../../../09_function/convert_thai_msg_monney.php');
    // เรียกใช้ function แปลงเลขเป็นเลขไทย
    require('../../../09_function/convert_thai_number.php');
    // เรียกใช้ function แปลงเดือน เป็นเดือนไทย
    require('../../../09_function/convert_thai_month.php');

    //รับ id_สัญญาที่ส่งมา
    $le_id = $_GET['le_id'];

    //นำ id_สัญญา เก็บไว้ที่ session
    $_SESSION['le_id_do'] = $le_id;

    //วันที่ปัจจุบัน
    date_default_timezone_set('Asia/Bangkok');
    $date_today = thainumDigit(date("d"));
    $month_today = cal_month(date("m"));
    $year_today = thainumDigit(date("Y") + 543);

    //ดึงข้อมูลทีใช้
    //สัญญา
    $sql_le  = "SELECT * FROM lease WHERE le_id = $le_id ";
    $result_le  = mysqli_query($conn, $sql_le);
    $row_le = mysqli_fetch_assoc($result_le);

    //ชื่อผู้บริหาร
    $ls_id1 = $row_le['le_sign_boss'];
    $sql_ls = "SELECT * FROM lessor ls WHERE ls_id = $ls_id1";
    $result_ls = mysqli_query($conn, $sql_ls);
    $row_ls = mysqli_fetch_assoc($result_ls);

    //ผู้เช่า
    $sql_tn = "SELECT * FROM lease l, tenant t 
                        WHERE l.le_id = $le_id 
                        AND t.tn_id = l.tn_id ";
    $result_tn = mysqli_query($conn, $sql_tn);
    $row_ch = mysqli_fetch_assoc($result_tn);

    //พื้นที่เช่า
    $sql_area = "SELECT * FROM lease le , area ar
                WHERE le.area_id = ar.area_id
                AND le.le_id = $le_id";
    $result_area = mysqli_query($conn, $sql_area);
    $row_area = mysqli_fetch_assoc($result_area);

    //พยานเจ้าหน้าที่พิเศษ
    $sql_wn_1 = "SELECT * FROM officer ofc, lease le
        WHERE le.le_id = $le_id 
          AND le.le_sign_ofc1 = ofc.ofc_id";
    $result_wn_1 = mysqli_query($conn, $sql_wn_1);
    $row_wn_1 = mysqli_fetch_assoc($result_wn_1);

    //พยานนิติกร
    $sql_wn_2 = "SELECT * FROM officer ofc, lease le
                          WHERE le.le_id = $le_id 
                          AND le.le_sign_ofc2 = ofc.ofc_id";
    $result_wn_2 = mysqli_query($conn, $sql_wn_2);
    $row_wn_2 = mysqli_fetch_assoc($result_wn_2);

    //ค่าเช่ารายเดือน
    $sql_mn_month  = "SELECT * FROM money WHERE le_id = $le_id AND mn_type = 3 ";
    $result_mn_month  = mysqli_query($conn, $sql_mn_month);
    $row_mn_month = mysqli_fetch_assoc($result_mn_month);

    //ค่าเช่าล่วงหน้า
    $sql_mn_ad = "SELECT * FROM money WHERE le_id = $le_id AND mn_type = 2 ";
    $result_mn_ad  = mysqli_query($conn, $sql_mn_ad);
    $row_mn_ad = mysqli_fetch_assoc($result_mn_ad);

    //ค่าเงินประกัน
    $sql_mn_dp = "SELECT * FROM money WHERE le_id = $le_id AND mn_type = 1 ";
    $result_mn_dp  = mysqli_query($conn, $sql_mn_dp);
    $row_mn_dp = mysqli_fetch_assoc($result_mn_dp);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบบริหารสัญญาเช่า</title>
    <link rel="shortcut icon" href="../../../style/ku.png" type="image/x-icon" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script src="../../../script/jquery-3.6.0.js"></script>

    <!--Icon-->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="../../../style/style_navi_bg.css">
    <link rel="stylesheet" href="../../../style/style_btn.css">
    <link rel="stylesheet" href="../../../style/style_bar_name.css">

    <!-- style ตรงกลาง -->
    <link rel="stylesheet" href="../../../style/wp_3_add_le.css">

</head>

<body>

    <?php
    //header
    include('../../header_ofc.php');

    //side bar
    include('../../sidebar_ofc.php');
    ?>

    <!-- ชื่อ บทบาทผู้ใช้ -->
    <div class="bar_name">
        <span class="icon_name"><em class="fa fa-user-circle-o" aria-hidden="true"></em></span>
        <span class="name"><?php echo $_SESSION['ofc_add_name']; ?></span> <br>
        <span class="name"><strong>( ผู้ดูแลระบบ )</strong></span>
    </div>

    <!--ข้อมูล-->
    <main class="data ">

        <div class="wrapper_3">

            <!-- Back -->
            <div>
                <a href="../01_lease/01_lease.php" class="back" style="text-decoration:none;">
                    <span class="icon_b" style="color: blue; font-size: 25px;"><em class="fa fa-arrow-left" aria-hidden="true"></em></span>
                    <span class="name_b" style="color: blue; font-size: 20px;"><strong>BACK</strong> </span>
                </a>
            </div>

            <div class="title">
                ข้อมูลสัญญาเช่า <br> เลขที่อ้างอิงสัญญาเช่า : <?php echo $row_le["le_no"]; ?>
            </div>

            <!-- แสดงข้อมูล -->
            <div class="form">

                <div class="inputfield" id="tn_p_name_oth">
                    <label>ประเภทสัญญาเช่า</label>
                    <input type="text" class="input" value="เพื่อร้านค้าหรือพาณิชย์" readonly>
                </div>

                <div class="title">ข้อมูลผู้ให้เช่า</div>

                <!-- ตรวจสอบข้อมูลผู้ให้เช่า -->
                <div class="inputfield">
                    <label>ชื่อ - นามสกุล ผู้ให้เช่า</label>
                    <input type="text" class="input" value="<?php echo $row_ls['ls_p_name'] . " " . $row_ls['ls_f_name'] . " " . $row_ls['ls_l_name']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ตำแหน่ง ผู้ให้เช่า</label>
                    <input type="text" class="input" value="<?php echo $row_ls['ls_role']; ?>" readonly>
                </div>

                <!-- ผู้เช่า -->
                <div class="title">ข้อมูลผู้เช่า</div>
                <div class="inputfield">
                    <label>ชื่อ - นามสกุล ผู้เช่า</label>
                    <input type="text" class="input" value="<?php echo $row_ch['tn_p_name'] . " " . $row_ch['tn_f_name'] . " " . $row_ch['tn_l_name']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ตำแหน่ง ผู้เช่า</label>
                    <?php
                    if ($row_ch['tn_role'] == null) {
                        $tn_role = "----------";
                    } else {
                        $tn_role = $row_ch['tn_role'];
                    }
                    ?>
                    <input type="text" class="input" value="<?php echo $tn_role; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ที่อยู่ ผู้เช่า</label>

                    <?php if ($row_ch['tn_road'] == null) {
                        $road = "---";
                    } else {
                        $road = $row_ch['tn_road'];
                    }
                    ?>
                    <input type="text" class="input" value="<?php echo "บ้านเลขที่ : " . $row_ch['tn_house_no'] . " , หมู่ : " . $row_ch['tn_moo'] .
                                                                " , ถนน : " . $road . " , ตำบล/แขวง : " . $row_ch['tn_canton'] .
                                                                " , อำเภอ/เขต : " . $row_ch['tn_district'] . " , จังหวัด : " . $row_ch['tn_province'];
                                                            ?>" readonly>
                </div>

                <!-- ข้อมูลสัญญาเช่า -->
                <div class="title">ข้อมูลสัญญาเช่า</div>

                <div class="inputfield">
                    <label>บริเวณที่เช่า</label>
                    <input type="text" class="input" value="<?php echo $row_area["area_name"] ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ขนาดพื้นที่เช่า (ตร.ม.)</label>
                    <input type="text" class="input" value="<?php echo $row_area["area_size"] ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>วัตถุประสงค์การเช่า</label>
                    <input type="text" class="input" value="<?php echo $row_le["le_purpose"] ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ระยะเวลาการเช่า (ปี)</label>
                    <input type="text" class="input" value="<?php echo $row_le["le_duration"] . " ปี"; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ว/ด/ป เริ่มสัญญา</label>
                    <input type="date" class="input" value="<?php echo $row_le["le_start_date"] ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ว/ด/ป สิ้นสุดสัญญา</label>
                    <input type="date" class="input" value="<?php echo $row_le["le_end_date"] ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ราคาเช่า / เดือน (บาท)</label>
                    <input type="text" class="input" value="<?php echo $row_mn_month["mn_cost"] . " บาท" ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ราคาเช่า / ปี (บาท)</label>
                    <input type="text" class="input" value="<?php echo ($row_mn_month["mn_cost"] * 12) . " บาท" ?>" readonly>
                </div>

                <!-- ค่าเช่าล่วงหน้า -->
                <div class="title">ข้อมูลค่าเช่าล่วงหน้า</div>

                <div class="inputfield">
                    <label>ค่าเช่าล่วงหน้า (บาท)</label>
                    <input type="text" class="input" value="<?php echo $row_mn_ad["mn_cost"] . " บาท" ?>" readonly>
                </div>

                <!-- ตรวจสอบว่าจ่ายแบบไหน -->
                <!-- จ่ายแบบ ใบเสร็จรับเงิน เข้า if นี้ -->
                <?php if ($row_mn_ad['mn_no_important'] == null) { ?>
                    <div class="inputfield" id="a3">
                        <label>เล่มที่ใบเสร็จรับเงิน</label>
                        <input type="text" class="input" value="<?php echo $row_mn_ad["mn_volume"] ?>" readonly>
                    </div>

                    <div class="inputfield">
                        <label>เลขที่ใบเสร็จรับเงิน</label>
                        <input type="text" class="input" value="<?php echo $row_mn_ad["mn_no"] ?>" readonly>
                    </div>

                <?php } 
                // จ่ายแบบ เลขที่ใบสำคัญ เข้า if นี้
                else { ?>
                    <div class="inputfield">
                        <label>เลขที่ใบสำคัญ</label>
                        <input type="text" class="input" value="<?php echo $row_mn_ad["mn_no_important"] ?>" readonly>
                    </div>
                <?php } ?>

                <!-- วันที่ชำระ -->
                <div class="inputfield">
                    <label>ว/ด/ป ที่ชำระ</label>
                    <input type="date" class="input" value="<?php echo $row_mn_ad["mn_date_pay"] ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>หลักฐานเงินประกัน</label>
                    <a href="../../../file_uploads/money_dp_ad/<?php echo $row_mn_ad["mn_file"]; ?>" target="_blank" class="input" style="color:blue"> ดูหลักฐาน</a>
                </div>

                <!-- เงินประกัน -->
                <div class="title">ข้อมูลเงินประกัน</div>
                <div class="inputfield">
                    <label>จำนวนเงินประกัน (บาท)</label>
                    <input type="text" class="input" value="<?php echo $row_mn_dp["mn_cost"] . " บาท" ?>" readonly>
                </div>

                <!-- ตรวจสอบว่าจ่ายแบบไหน -->
                <!-- จ่ายแบบ ใบเสร็จรับเงิน เข้า if นี้ -->
                <?php if ($row_mn_ad['mn_no_important'] == null) { ?>
                    <div class="inputfield" id="a3">
                        <label>เล่มที่ใบเสร็จรับเงิน</label>
                        <input type="text" class="input" value="<?php echo $row_mn_dp["mn_volume"] ?>" readonly>
                    </div>

                    <div class="inputfield">
                        <label>เลขที่ใบเสร็จรับเงิน</label>
                        <input type="text" class="input" value="<?php echo $row_mn_dp["mn_no"] ?>" readonly>
                    </div>

                <?php } 
                // จ่ายแบบ เลขที่ใบสำคัญ เข้า if นี้
                else { ?>
                    <div class="inputfield">
                        <label>เลขที่ใบสำคัญ</label>
                        <input type="text" class="input" value="<?php echo $row_mn_dp["mn_no_important"] ?>" readonly>
                    </div>
                <?php } ?>

                <!-- วันที่ชำระ -->
                <div class="inputfield">
                    <label>ว/ด/ป ที่ชำระ</label>
                    <input type="date" class="input" value="<?php echo $row_mn_dp["mn_date_pay"] ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>หลักฐานค่าเช่าล่วงหน้า</label>
                    <a href="../../../file_uploads/money_dp_ad/<?php echo $row_mn_dp["mn_file"]; ?>" target="_blank" class="input" style="color:blue">ดูหลักฐาน</a>
                </div>

                <!-- พยาน เจ้าหน้าที่พิเศษ-->
                <div class="title">ข้อมูลพยาน</div>

                <div class="inputfield">
                    <label>เจ้าหน้าที่ผู้ลงนาม</label>
                    <input type="text" class="input" value="<?php echo $row_wn_1["ofc_p_name"] . " " . $row_wn_1["ofc_f_name"] . " " . $row_wn_1["ofc_l_name"] ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ตำแหน่ง เจ้าหน้าที่ผู้ลงนาม</label>

                    <?php
                    if ($row_wn_1["ofc_role"] == null) {
                        $result_role_1 = '----------';
                    } else {
                        $result_role_1 = $row_wn_1["ofc_role"];
                    }
                    ?>
                    <input type="text" class="input" value="<?php echo $result_role_1 ?>" readonly>
                </div>

                <!-- พยาน นิติกร-->
                <div class="inputfield">
                    <label>นิติกรผู้ลงนาม</label>
                    <input type="text" class="input" value="<?php echo $row_wn_2["ofc_p_name"] . " " . $row_wn_2["ofc_f_name"] . " " . $row_wn_2["ofc_l_name"] ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ตำแหน่ง นิติกรผู้ลงนาม</label>

                    <?php
                    if ($row_wn_2["ofc_role"] == null) {
                        $result_role_2 = '----------';
                    } else {
                        $result_role_2 = $row_wn_2["ofc_role"];
                    }
                    ?>
                    <input type="text" class="input" value="<?php echo $result_role_2 ?>" readonly>
                </div>

            </div>
            <br>

            </form>
            <!-- ถ้ากด  ดูเอกสาร PDF File และ ส่งข้อความแจ้งเตือนผู้เช่าลงนาม ไปที่หน้า 01_pdf_db.php แบบ target="_blank" -->
            <!-- เพื่อส่งข้อความแจ้งเตือนผู้เช่าว่าเพิ่มสัญญาเช่าแล้ว และ Set pdf file -->
            <div class="d-grid gap-2">
                <a href="01_pdf_db.php" target="_blank" class="btn btn-warning" onclick="return confirm('ยืนยัน : การตรวจสอบเอกสาร และแจ้งเตือนไปหาผู้เช่า')">ดูเอกสาร PDF File และ ส่งข้อความแจ้งเตือนผู้เช่าลงนาม</a>
            </div>
        </div>

    </main>

    <!--js-->
    <script src="../../../script/main.js"></script>

</body>

</html>