<?php {
    session_start();


    if (!$_SESSION['ofc_add']) {
        header("Location: ../../index.php");
    }

    require('../../connection.php');
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
        <span class="name"><?php echo $_SESSION['ofc_add_name']; ?></span> <br>
        <span class="name"><strong>( เจ้าหน้าที่ เพิ่มสัญญาเช่า )</strong></span>
    </div>

    <!--ข้อมูล-->
    <!--ข้อมูล-->
    <main class="data ">

        <div class="wrapper_3">

            <div class="title">
                เพิ่มสัญญาเช่า กรณีพิเศษ <br> เลือกประเภทสัญญาเช่า
            </div>
            <form action="02_3_type_db.php" method="POST">

                <!-- เลือกประเภทสัญญาเช่า -->
                <div class="form">

                    <br>

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

                <button class="btn_b" name="save" onclick="return confirm('ยืนยันการเลือก')">ต่อไป >></button>
                <br><br>
            </form>


        </div>


    </main>

    <!--js-->
    <script src="../../script/main.js"></script>

</body>

</html>