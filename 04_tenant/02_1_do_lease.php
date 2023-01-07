<?php {

    session_start();

    // ถ้าไม่มี $_SESSION['tn'] กลับไปที่หน้า index
    if (!$_SESSION['tn']) {
        header("Location: ../index.php");
    }

    require('../connection.php');

    //รับ session ผู้เช่า id
    $id = $_SESSION['tn'];

    //ดึงข้อมูลผู้เช่า
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
    <link rel="stylesheet" href="style/style_1.css">
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

        <div class="wrapper">

            <div class="title">
                ประสงค์ ทำ/ต่อ สัญญาเช่า
            </div>

            <!-- เมื่อกด ส่งความประสงค์ ให้ส่งข้อมูลแบบ POST ไปที่หน้า 02_2_do_lease_send_db.php -->
            <form action="02_2_do_lease_send_db.php" method="POST">

                <!-- ส่งข้อมูลนี้ไปด้วย แบบ hidden -->
                <input name="id" type="hidden" value="<?php echo $row["tn_id"] ?>">
                <input name="id_card" type="hidden" value="<?php echo $row["tn_id_card"] ?>">

                <div class="form">
                    <?php date_default_timezone_set('Asia/Bangkok'); ?>
                    <!-- Show วันที่ปัจจุบัน -->
                    <div class="inputfield">
                        <label>ว/ด/ป ที่ส่งเรื่อง</label>
                        <input type="text" class="input" value="<?php echo date(' d/m/Y ') ?>" readonly>
                    </div>
                    <!-- show เวลา ปัจจุบัน -->
                    <div class="inputfield">
                        <label>เวลาที่ส่งเรื่อง</label>
                        <input type="text" class="input" value="<?php echo date('H:i:s') ?> น." readonly>
                    </div>
                    <div class="inputfield">
                        <label>ชื่อ - สกุล</label>
                        <input type="text" class="input" value="<?php echo $row['tn_p_name'] . " " . $row['tn_f_name'] . " " . $row['tn_l_name'] ?>" readonly>
                    </div>

                    <div class="inputfield">
                        <label>ความประสงค์ <strong style="color: red;">*</strong></label>
                        <div class="custom_select">
                            <select name="le_why_do" required>
                                <option value="" hidden>เลือกความประสงค์</option>
                                <option value="ทำ">ทำสัญญาเช่า</option>
                                <option value="ต่อ">ต่อสัญญาเช่า</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="inputfield">
                        <input type="submit" value="ส่งความประสงค์" name="submit" class="btn_02_1">
                    </div>
                </div>
            </form>
        </div>

    </main>

    <!--main.js เป็นการกดปุ่ม เพื่อเลื่อนเปิด ปิด  side bar ได้-->
    <script src="../script/main.js"></script>

</body>

</html>