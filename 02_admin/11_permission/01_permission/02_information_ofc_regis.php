<?php {
    require("../../../connection.php");
    session_start();

    if (!$_SESSION['ofc_add']) {
        header("Location: ../../../index.php");
    }

    // รับ id เจ้าหน้าที่
    $id = $_GET["idsub"];

    // ดึงข้อมูล
    $sql = "SELECT * FROM officer WHERE ofc_id = $id ";  
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
        <span class="name"><?php echo $_SESSION['ofc_add_name']; ?></span> <br>
        <span class="name"><strong>( ผู้ดูแลระบบ )</strong></span>
    </div>

    <!--ข้อมูล-->
    <!--ข้อมูล-->
    <main class="data ">

        <div class="wrapper">

            <!-- Back -->
            <div>
                <a href="01_permission.php" class="back" style="text-decoration:none;">
                    <span class="icon_b" style="color: blue; font-size: 25px;"><em class="fa fa-arrow-left" aria-hidden="true"></em></span>
                    <span class="name_b" style="color: blue; font-size: 20px;"><strong>BACK</strong> </span>
                </a>
            </div>

            <div class="title">
                ข้อมูลส่วนตัว
            </div>
            <div class="form">

                <div class="inputfield">
                    <label>ว/ด/ป ที่ลงทะเบียน</label>
                    <input type="email" class="input" value="<?php echo date('d / m / Y ', strtotime($row["ofc_date_regis"])); ?>" readonly>
                </div>

                <!-- บทบาทการใช้งาน -->
                <?php $t = $row["ofc_type"];
                if ($t == 1) {
                    $at = "เจ้าหน้าที่เพิ่มสัญญาเช่า";
                } else if ($t == 2) {
                    $at = "เจ้าหน้าที่พิเศษ";
                } else if ($t == 3) {
                    $at = "เจ้าหน้าที่พิเศษ";
                } else if ($t == 4) {
                    $at = "ผู้จัดการหอพัก";
                } else if ($t == 5) {
                    $at = "นิติกร";
                } else if ($t == 6) {
                    $at = "ผอ. กองทรัพย์สิน";
                }
                ?>
                <div class="inputfield">
                    <label>บทบาท</label>
                    <input type="text" class="input" value="<?php echo $at; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>Email</label>
                    <input type="email" class="input" value="<?php echo $row['ofc_email']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>คำนำหน้าชื่อ</label>
                    <input type="text" class="input" value="<?php echo $row['ofc_p_name']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ชื่อ</label>
                    <input type="text" class="input" value="<?php echo $row['ofc_f_name'] ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>นามสกุล</label>
                    <input type="text" class="input" value="<?php echo $row['ofc_l_name']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ตำแหน่ง</label>
                    <?php if ($row['ofc_role'] == null) {
                        $role = '----------';
                    } else {
                        $role = $row['ofc_role'];
                    }
                    ?>
                    <input type="text" class="input" value="<?php echo $role; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ว/ด/ป เกิด</label>
                    <input type="date" class="input" value="<?php echo $row['ofc_birth_date']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>เบอร์โทรศัพท์</label>
                    <input type="tel" class="input" value="<?php echo $row['ofc_tel']; ?>" readonly>
                </div>

            </div>
            <br><br>

        </div>


    </main>

    <!--js-->
    <script src="../../../script/main.js"></script>

</body>

</html>