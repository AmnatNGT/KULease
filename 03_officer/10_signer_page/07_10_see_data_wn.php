<?php {
    session_start();
    if (!$_SESSION['ofc_add']) {
        header("Location: ../../index.php");
    }

    require('../../connection.php');

    // รับ id พยาน
    $wn_id = $_GET["wn_id"];

    //สัญญา
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
        <span class="name"><strong>( เจ้าหน้าที่ เพิ่มสัญญาเช่า )</strong></span>
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

                <div class="inputfield" id="tn_p_name_oth" style="display:none">
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

            <br><br>


        </div>


    </main>


    <!--js-->
    <script src="../../script/main.js"></script>

</body>

</html>