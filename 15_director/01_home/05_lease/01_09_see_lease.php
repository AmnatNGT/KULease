<?php {
    require("../../../connection.php");
    session_start();

    if (!$_SESSION['boss']) {
        header("Location: ../../../index.php");
    }

    // รับ id สัญญา
    $le_id = $_GET['le_id'];

    //สัญญา
    $sql_le  = "SELECT * FROM lease l, status_lease stl 
                         WHERE l.le_id = $le_id 
                           AND l.le_id = stl.le_id";
    $result_le  = mysqli_query($conn, $sql_le);
    $row_le = mysqli_fetch_assoc($result_le);

    //ชื่อผู้บริหาร
    $sql_ls = "SELECT * FROM lessor ls, lease le
                WHERE le.le_id = $le_id 
                  AND le.le_sign_boss = ls.ls_id";
    $result_ls = mysqli_query($conn, $sql_ls);
    $row_ls = mysqli_fetch_assoc($result_ls);

    //ผู้เช่า
    $sql_tn  = "SELECT * FROM lease l, tenant t 
                         WHERE l.le_id = $le_id 
                           AND t.tn_id = l.tn_id ";
    $result_tn  = mysqli_query($conn, $sql_tn);
    $row_tn = mysqli_fetch_assoc($result_tn);


    //พื้นที่เช่า
    $sql_area = "SELECT * FROM lease le , area ar
                WHERE le.area_id = ar.area_id
                AND le.le_id = $le_id";
    $result_area = mysqli_query($conn, $sql_area);
    $row_area = mysqli_fetch_assoc($result_area);


    //พยาน 1
    $sql_wn_1 = "SELECT * FROM officer ofc, lease le
                          WHERE le.le_id = $le_id 
                            AND le.le_sign_ofc1 = ofc.ofc_id";
    $result_wn_1 = mysqli_query($conn, $sql_wn_1);
    $row_wn_1 = mysqli_fetch_assoc($result_wn_1);

    //พยาน 2
    $sql_wn_2 = "SELECT * FROM officer ofc, lease le
                          WHERE le.le_id = $le_id 
                            AND le.le_sign_ofc2 = ofc.ofc_id";
    $result_wn_2 = mysqli_query($conn, $sql_wn_2);
    $row_wn_2 = mysqli_fetch_assoc($result_wn_2);

    //ค่าเช่ารายเดือน
    $sql_mn_month  = "SELECT * FROM money WHERE le_id = $le_id AND mn_type = 3 ";
    $result_mn_month  = mysqli_query($conn, $sql_mn_month);
    $row_mn_month = mysqli_fetch_assoc($result_mn_month);

    //ชื่อ admin
    if (isset($row_le['ad_id'])) {
        $admin = $row_le['ad_id'];
        $sql_ad = "SELECT * FROM admin WHERE ad_id = $admin";
        $result_ad  = mysqli_query($conn, $sql_ad);
        $row_ad = mysqli_fetch_assoc($result_ad);
    }

    //ชื่อ เจ้าหน้าที่
    if (isset($row_le['ofc_id'])) {
        $ofc = $row_le['ofc_id'];
        $sql_ofc = "SELECT * FROM officer WHERE ofc_id = $ofc";
        $result_ofc  = mysqli_query($conn, $sql_ofc);
        $row_ofc = mysqli_fetch_assoc($result_ofc);
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

    <!-- wrapper3 -->
    <style>
        /* wrapper เพิ่มข้อมูล */
        .wrapper_3 {
            max-width: 1000px;
            width: 100%;
            background: #fff;
            margin: 20px auto;
            box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.125);
            padding: 30px;
        }

        .wrapper_3 .title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 25px;
            color: var(--first-color);
            text-transform: uppercase;
            text-align: center;
        }

        .wrapper_3 .form {
            width: 100%;
        }

        .wrapper_3 .form .inputfield {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .wrapper_3 .form .inputfield label {
            width: 200px;
            color: #757575;
            margin-right: 10px;
            font-size: 14px;
        }

        .wrapper_3 .form .inputfield .input {
            width: 100%;
            outline: none;
            border: 1px solid #d5dbd9;
            font-size: 15px;
            padding: 8px 10px;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .wrapper_3 .form .inputfield .custom_select {
            position: static;
            width: 100%;
            height: 37px;
        }

        .wrapper_3 .form .inputfield .custom_select select {
            outline: none;
            width: 100%;
            height: 100%;

            padding: 8px 10px;
            font-size: 15px;
            border: 1px solid #d5dbd9;
            border-radius: 3px;
        }

        .wrapper_3 .form .inputfield .input:focus,
        .wrapper_3 .form .inputfield .textarea:focus,
        .wrapper_3 .form .inputfield .custom_select select:focus {
            border: 1px solid var(--first-color);
        }

        .wrapper_3 .form .inputfield p {
            font-size: 14px;
            color: #757575;
        }

        .wrapper_3 .form .inputfield .btn {
            width: 30%;
            padding: 8px 10px;
            font-size: 15px;
            border: 0px;
            background: var(--first-color);
            color: #fff;
            cursor: pointer;
            border-radius: 3px;
            outline: none;
            float: right;
            margin-left: 5px;
        }

        .wrapper_3 .form .inputfield .btn:hover {
            background: var(--first-color-light);
        }

        .wrapper_3 .form .inputfield:last-child {
            margin-bottom: 0;
        }
    </style>

    <!-- btn_pdf -->
    <style>
        .btn_pdf {
            width: 100%;
            padding: 8px 410px;
            font-size: 15px;
            border: 0px;
            background: #ff6600;
            color: #fff;
            cursor: pointer;
            border-radius: 3px;
            outline: none;

        }

        .btn_pdf:hover {
            background: #464660;
            color: #fff;
        }
    </style>


</head>

<body>

    <?php
    //header
    include('../../header_ofc.php');

    //side bar
    include('../../sidebar_ofc.php');
    ?>

    <!-- ชื่อเจ้าหน้าที่ -->
    <div class="bar_name">
        <span class="icon_name"><em class="fa fa-user-circle-o" aria-hidden="true"></em></span>
        <span class="name"><?php echo $_SESSION['boss_name']; ?></span> <br>
        <span class="name"><strong>( ผู้บริหาร มก. )</strong></span>
    </div>

    <!--ข้อมูล-->
    <main class="data ">

        <div class="wrapper_3">

            <!-- Back -->
            <div>
                <a href="01_01_all_lease.php" class="back" style="text-decoration:none;">
                    <span class="icon_b" style="color: blue; font-size: 25px;"><em class="fa fa-arrow-left" aria-hidden="true"></em></span>
                    <span class="name_b" style="color: blue; font-size: 20px;"><strong>BACK</strong> </span>
                </a>
            </div>

            <div class="title">
                ข้อมูลสัญญาเช่า <br> เลขที่อ้างอิงสัญญาเช่า : <?php echo $row_le["le_no"]; ?>
            </div>


            <div class="form">

                <!--adminเพิ่มสัญญา-->
                <?php if ($row_le['ad_id'] != null) { ?>
                    <div class="inputfield">
                        <label>เจ้าหน้าที่ผู้เพิ่มสัญญาเช่า</label>
                        <input type="text" class="input" value="<?php echo $row_ad['ad_p_name'] . " " . $row_ad['ad_f_name'] . " " . $row_ad['ad_l_name']; ?>" readonly>
                    </div>
                <?php } ?>

                <!--เจ้าหน้าที่เพิ่มสัญญา-->
                <?php if ($row_le['ofc_id'] != null) { ?>
                    <div class="inputfield">
                        <label>เจ้าหน้าที่ผู้เพิ่มสัญญาเช่า</label>
                        <input type="text" class="input" value="<?php echo $row_ofc['ofc_p_name'] . " " . $row_ofc['ofc_f_name'] . " " . $row_ofc['ofc_l_name']; ?>" readonly>
                    </div>
                <?php } ?>

                <div class="inputfield">
                    <label>ว/ด/ป เพิ่มสัญญาเช่า</label>
                    <input type="date" class="input" value="<?php echo $row_le['d_ad_le'] ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>เวลา เพิ่มสัญญาเช่า</label>
                    <input type="time" class="input" value="<?php echo $row_le['t_ad_le'] ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ประเภทสัญญาเช่า</label>
                    <input type="text" class="input" value="เพื่อโรงอาหาร" readonly>
                </div>

                <div class="inputfield">
                    <label>เลขที่สัญญาเช่า</label>

                    <?php
                    if ($row_le["le_no_success"] == null) {
                        $no_success = "----------";
                    } else {
                        $no_success = $row_le["le_no_success"];
                    }
                    ?>
                    <input type="text" class="input" value="<?php echo $no_success; ?>" readonly>
                </div>

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
                <div class="inputfield">
                    <label>ชื่อ - นามสกุล ผู้เช่า</label>
                    <input type="text" class="input" value="<?php echo $row_tn["tn_p_name"] . " " . $row_tn["tn_f_name"] . " " . $row_tn["tn_l_name"]; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ตำแหน่ง ผู้เช่า</label>
                    <?php
                    if ($row_tn['tn_role'] == null) {
                        $tn_role = "----------";
                    } else {
                        $tn_role = $row_tn['tn_role'];
                    }
                    ?>
                    <input type="text" class="input" value="<?php echo $tn_role; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ที่อยู่ ผู้เช่า</label>

                    <?php if ($row_tn['tn_road'] == null) {
                        $road = "---";
                    } else {
                        $road = $row_tn['tn_road'];
                    }
                    ?>
                    <input type="text" class="input" value="<?php echo "บ้านเลขที่ : " . $row_tn['tn_house_no'] . " , หมู่ : " . $row_tn['tn_moo'] .
                                                                " , ถนน : " . $road . " , ตำบล/แขวง : " . $row_tn['tn_canton'] .
                                                                " , อำเภอ/เขต : " . $row_tn['tn_district'] . " , จังหวัด : " . $row_tn['tn_province'];
                                                            ?>" readonly>
                </div>

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

                <div class="inputfield">
                    <label>เอกสารของผู้เช่าเพิ่มเติม</label>
                    <a href="../../../file_uploads/other_file_lease/<?php echo 'oth_file_le_' . $le_id . '.pdf'; ?>" target="_blank" class="input" STYLE="color: green;">ดูเอกสาร</a>
                </div>

                <?php if ($row_le['le_no_success'] != null) { ?>

                    <div class="inputfield">
                        <label>เอกสารข้อตกลงแนบท้ายสัญญาเช่า</label>
                        <a href="../../../file_uploads/last_file_lease/<?php echo 'last_file_le_' . $le_id . '.pdf'; ?>" target="_blank" class="input" STYLE="color: green;">ดูเอกสาร</a>
                    </div>

                    <div class="inputfield">
                        <label>เอกสารสัญญาเช่า</label>
                        <a href="../../../file_uploads/lease_success/<?php echo $row_le['le_no'] . '.pdf' ?>" target="_blank" class="input" STYLE="color: green;">ดูเอกสาร</a>
                    </div>

                    <div class="inputfield">
                        <label>เอกสารอากรแสตมป์</label>
                        <a href="../../../file_uploads/stamp_file/<?php echo $row_le['le_stamp'] ?>" target="_blank" class="input" STYLE="color: green;">ดูเอกสาร</a>
                    </div>
                <?php } ?>

            </div>
            <br>


        </div>


    </main>

    <!--js-->
    <script src="../../../script/main.js"></script>



</body>

</html>