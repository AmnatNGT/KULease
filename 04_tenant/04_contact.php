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


    //ดึงข้อมูล contact
    $sql_c = "SELECT * FROM contact WHERE c_st_use = 1"; 
    $result_c = mysqli_query($conn, $sql_c);
    $row_c = mysqli_fetch_assoc($result_c);
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

        <!-- แสดงข้อมูล contact -->
        <div class="wrapper">

            <div class="title">
                ติดต่อเจ้าหน้าที่
            </div>

            <div class="form">

                <div class="inputfield">
                    <label>Email เจ้าหน้าที่</label>
                    <a href="#" class="input"><?php echo $row_c['c_email_admin']; ?></a>
                </div>

                <div class="inputfield">
                    <label>Email กองบริหารทรัพย์สิน</label>
                    <a href="#" class="input"><?php echo $row_c['c_email_agc']; ?></a>

                </div>

                <div class="inputfield">
                    <label>Facebook กองบริหารทรัพย์สิน</label>
                    <a href="<?php echo $row_c['c_facebook']; ?>" class="input" target="_blank" rel="noopener">กองบริหารทรัพย์สิน กำแพงแสน</a>
                </div>

                <div class="inputfield">
                    <label>โทรศัพท์</label>
                    <input type="text" class="input" value="<?php echo $row_c['c_tel']; ?>" readonly>
                </div>

            </div>

            <br><br>

            <div class="title">
                ที่อยู่หน่วยงาน
            </div>
            <div class="form">

                <div class="inputfield">
                    <label>เลขที่</label>
                    <input type="text" class="input" value="<?php echo $row_c['c_ad_no']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>หมู่ที่</label>
                    <input type="text" class="input" value="<?php echo $row_c['c_ad_moo']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>ตำบล/แขวง</label>
                    <input type="text" class="input" value="<?php echo $row_c['c_ad_canton']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>อำเภอ/เขต</label>
                    <input type="text" class="input" value="<?php echo $row_c['c_ad_district']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>จังหวัด</label>
                    <input type="text" class="input" value="<?php echo $row_c['c_ad_province']; ?>" readonly>
                </div>

                <div class="inputfield">
                    <label>รหัสไปรษณีย์</label>
                    <input type="text" class="input" value="<?php echo $row_c['c_ad_post_ofc']; ?>" readonly>
                </div>
            </div>
        </div>

    </main>

    <!--main.js เป็นการกดปุ่ม เพื่อเลื่อนเปิด ปิด  side bar ได้-->
    <script src="../script/main.js"></script>

</body>

</html>