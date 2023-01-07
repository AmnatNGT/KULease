<?php {
    session_start();

    // ถ้าไม่มี $_SESSION['tn'] กลับไปที่หน้า index
    if (!$_SESSION['tn']) {
        header("Location: ../index.php");
    }

    require('../connection.php');

    //รับ session ผู้เช่า id
    $id = $_SESSION['tn'];

    //ดึงข้อมูล ผู้เช่า
    $sql = "SELECT * FROM tenant WHERE tn_id = $id";
    $result = mysqli_query($conn, $sql);
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

    <link rel="shortcut icon" href="../style/ku.png" type="image/x-icon" />

    <!--Icon-->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="../style/style_bar_name.css">

</head>

<body>

    <?php
    // Header
    include('header_tn.php');
    //Side bar
    include('sidebar_tn.php');
    ?>

    <!-- ชื่อ และบทบาท ผู้ใช้งาน-->
    <div class="bar_name">
        <span class="icon_name"><em class="fa fa-user-circle-o" aria-hidden="true"></em></span>
        <span class="name"><?php echo $_SESSION['tn_name']; ?></span> <br>
        <span class="name"><strong>( ผู้เช่า )</strong></span>
    </div>

    <!--ข้อมูล-->
    <main class="data ">

        <!--แจ้งเตือนผู้เช่าใหม่ ถ้ายังไม่เคยส่งเรื่องทำสัญญาเช่าจะแสดงอันนี้-->
        <?php if ($row['tn_count_use'] == 0) { ?>
            <div class="alert">
                ผู้เช่าใหม่โปรดทราบ <strong>Click >>> ทำ/ต่อ สัญญาเช่า <<< </strong> เพื่อดำเนินการทำสัญญาเช่า
            </div>
        <?php } ?>

        <!-- แสดงข้อมูลผู้เช่า -->
        <div class="wrapper">
            <div class="title">
                ข้อมูลส่วนตัว
            </div>

            <div class="form">

                <div class="inputfield">
                    <label>Email</label>
                    <input type="email" class="input" value="<?php echo $row['tn_email']; ?>" readonly>
                </div>

                <div class="inputfield" id="tn_p_name_oth">
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
                        $role = "----------";
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
                        $road = "----------";
                    } else {
                        $road = $row['tn_road'];
                    }
                    ?>
                    <input type="text" class="input" value="<?php echo $road ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>หมู่</label>

                    <?php if ($row['tn_moo'] == null) {
                        $moo = "----------";
                    } else {
                        $moo = $row['tn_moo'];
                    }
                    ?>
                    <input type="text" class="input" value="<?php echo $moo; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ซอย</label>

                    <?php if ($row['tn_alley'] == null) {
                        $alley = "----------";
                    } else {
                        $alley = $row['tn_alley'];
                    }
                    ?>
                    <input type="text" class="input" value="<?php echo $alley; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>บ้านเลขที่</label>

                    <?php if ($row['tn_house_no'] == null) {
                        $hn = "----------";
                    } else {
                        $hn = $row['tn_house_no'];
                    }
                    ?>
                    <input type="text" class="input" value="<?php echo $hn; ?>" readonly>
                </div>
            </div>
        </div>
    </main>

    <!--main.js เป็นการกดปุ่ม เพื่อเลื่อนเปิด ปิด  side bar ได้-->
    <script src="../script/main.js"></script>

</body>

</html>