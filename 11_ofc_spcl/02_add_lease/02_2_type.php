<?php {
    session_start();


    if (!$_SESSION['ofc_spcl']) {
        header("Location: ../../index.php");
    }

    require('../../connection.php');

    //รับ id สัญญาเช่า
    $id = $_GET["le_id"];

    //ดึงข้อมูลจาก table lease ที่ตรงกับ id ที่รับมา
    $sql = "SELECT * FROM lease WHERE le_id = $id ";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    $row = mysqli_fetch_assoc($result);

    //id ผู้เช่าของสัญญาดังกล่าว
    $l_id = $row["le_id"];

    //หาข้อมูลผู้เช่า
    $sql_ch = "SELECT * FROM lease l ,tenant t
                        WHERE l.tn_id = t.tn_id AND l.le_id = $l_id";
    $result_ch = mysqli_query($conn, $sql_ch);
    $row_ch = mysqli_fetch_assoc($result_ch);

    //ผู้ให้เช่า
    $q = "SELECT * FROM lessor WHERE ls_status_show = 1";
    $result_q = mysqli_query($conn, $q);
    $row_q = mysqli_fetch_assoc($result_q);
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
    <!-- style ตรงกลาง -->
    <link rel="stylesheet" href="../../style/wp_3_add_le.css">

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
        <span class="name"><?php echo $_SESSION['ofc_spcl_name']; ?></span> <br>
        <span class="name"><strong>( เจ้าหน้าที่ พิเศษ )</strong></span>
    </div>

    <!--ข้อมูล-->
    <!--ข้อมูล-->
    <main class="data ">

        <div class="wrapper_3">

            <!-- Back -->
            <div>
                <a href="02_1_do_lease.php" class="back" style="text-decoration:none;">
                    <span class="icon_b" style="color: blue; font-size: 25px;"><em class="fa fa-arrow-left" aria-hidden="true"></em></span>
                    <span class="name_b" style="color: blue; font-size: 20px;"><strong>BACK</strong> </span>
                </a>
            </div>

            <div class="title">
                เลือกประเภทสัญญาเช่า <br> เลขที่อ้างอิงสัญญาเช่า : <?php echo $row["le_no"]; ?>
            </div>

            <!-- เมื่อเลือกประเภทสัญญาเช่าแล้วส่งข้อมูลไปหน้า 02_3_type_db.php-->
            <form action="02_3_type_db.php" method="POST">

                <!-- เลือกประเภทสัญญาเช่า -->
                <div class="form">

                    <!-- ส่งข้อมูลไปหน้า 02_3_type_db.php -->
                    <input name="le_id" id="le_id" type="hidden" value="<?php echo $id;  ?>"> <!-- เลขที่ทำสัญญาเช่า -->

                    <!-- ส่งข้อมูลไปหน้า 02_3_type_db.php -->
                    <div class="inputfield">
                        <label>ประเภทสัญญาเช่า <strong>*</strong></label>
                        <select name="le_type" required autofocus class="form-select">
                            <option selected hidden value="">เลือกประเภทสัญญาเช่า</option>
                            <option value="1">สัญญาเพื่อร้านค้าหรือพาณิชย์</option>
                            <option value="2">สัญญาเพื่องานบริการ</option>
                            <option value="3">สัญญาเพื่องานวิจัย / การเรียนการสอน</option>
                            <option value="4">สัญญาเพื่อที่พักอาศัย</option>
                            <option value="5">สัญญาเพื่อโรงอาหาร</option>
                        </select>

                    </div>

                </div>
                <br><br>

                <!-- แสดงข้อมูล -->
                <div class="title">
                    ตรวจสอบข้อมูลผู้ให้เช่า
                </div>
                <div class="form">
                    <div class="inputfield">
                        <label>ชื่อ - นามสกุล ผู้ให้เช่า</label>
                        <input type="text" class="input" value="<?php echo $row_q['ls_p_name'] . " " . $row_q['ls_f_name'] . " " . $row_q['ls_l_name']; ?>" readonly>
                    </div>

                    <div class="inputfield">
                        <label>ตำแหน่ง ผู้ให้เช่า</label>
                        <input type="text" class="input" value="<?php echo $row_q['ls_role']; ?>" readonly>
                    </div>
                </div>

                <br>
                <div class="title">
                    ตรวจสอบข้อมูลผู้เช่า
                </div>
                <div class="form">

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

                </div>
                <br>

                <button class="btn_b" name="save" onclick="return confirm('ยืนยันการเลือก')">ต่อไป >></button>
                <br><br>
            </form>


        </div>


    </main>

    <!--js-->
    <script src="../../script/main.js"></script>

</body>

</html>