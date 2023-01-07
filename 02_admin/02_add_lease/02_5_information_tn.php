<?php {
    session_start();

    if (!$_SESSION['ofc_add']) {
        header("Location: ../../index.php");
    }

    require('../../connection.php');

    //รับ id สัญญา
    $id = $_GET["idsub"];

    // หาข้อมูลของผู้เช่า ที่ตรงกับ id สัญญา
    $sql = "SELECT * FROM tenant WHERE tn_id = $id "; 
    $result = mysqli_query($conn, $sql);  
    $count = mysqli_num_rows($result);  
    $row = mysqli_fetch_assoc($result);
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

    <!-- ชื่อ บทบาทผู้ใช้งาน -->
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
                <a href="02_1_do_lease.php" class="back" style="text-decoration:none;">
                    <span class="icon_b" style="color: blue; font-size: 25px;"><em class="fa fa-arrow-left" aria-hidden="true"></em></span>
                    <span class="name_b" style="color: blue; font-size: 20px;"><strong>BACK</strong> </span>
                </a>
            </div>

            <div class="title">
                ข้อมูลส่วนตัว
            </div>
            <!-- แสดงข้อมูล -->
            <div class="form">

                <div class="inputfield">
                    <label>ว/ด/ป ที่ลงทะเบียน</label>
                    <input type="email" class="input" value="<?php echo date('d / m / Y ', strtotime($row["tn_date_regis"])); ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>Email</label>
                    <input type="email" class="input" value="<?php echo $row['tn_email']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>คำนำหน้าชื่อ</label>
                    <input type="text" class="input" value="<?php echo $row['tn_p_name']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ชื่อ</label>
                    <input type="text" class="input" value="<?php echo $row['tn_f_name'] ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>นามสกุล</label>
                    <input type="text" class="input" value="<?php echo $row['tn_l_name']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ตำแหน่ง</label>

                    <?php if ($row['tn_role'] == null) {
                        $role = '----------';
                    } else {
                        $role = $row['tn_role'];
                    }
                    ?>
                    <input type="text" class="input" value="<?php echo $role; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>เชื้อชาติ</label>
                    <input type="text" class="input" value="<?php echo $row['tn_ethnicity']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>สัญชาติ</label>
                    <input type="text" class="input" value="<?php echo $row['tn_nationality']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ว/ด/ป เกิด</label>
                    <input type="date" class="input" value="<?php echo $row['tn_birth_date']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>เลขประจำตัวประชาชน</label>
                    <input type="text" class="input" value="<?php echo $row['tn_id_card']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>เบอร์โทรศัพท์</label>
                    <input type="tel" class="input" value="<?php echo $row['tn_tel']; ?>" readonly>
                </div>

            </div>
            <br><br>

            <div class="title">
                ข้อมูลที่อยู่
            </div>
            <div class="form">

                <div class="inputfield">
                    <label>จังหวัด</label>
                    <input type="text" class="input" value="<?php echo $row['tn_province']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>อำเภอ/เขต</label>
                    <input type="text" class="input" value="<?php echo $row['tn_district']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ตำบล/แขวง</label>
                    <input type="text" class="input" value="<?php echo $row['tn_canton']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>รหัสไปรษณีย์</label>
                    <input type="text" class="input" value="<?php echo $row['tn_postcode']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ถนน</label>
                    <?php if ($row['tn_road'] == null) {
                        $road = '----------';
                    } else {
                        $road = $row['tn_road'];
                    }
                    ?>
                    <input type="text" class="input" value="<?php echo $road; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>หมู่</label>
                    <input type="text" class="input" value="<?php echo $row['tn_moo']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ซอย</label>
                    <input type="text" class="input" value="<?php echo $row['tn_alley']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>บ้านเลขที่</label>
                    <input type="text" class="input" value="<?php echo $row['tn_house_no']; ?>" readonly>
                </div>

            </div>

            <br><br>

        </div>


    </main>

    <!--js-->
    <script src="../../script/main.js"></script>

</body>

</html>