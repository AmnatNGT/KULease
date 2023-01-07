<?php {
    session_start();
    require('../../../connection.php');

    if (!$_SESSION['boss']) {
        header("Location: ../../../index.php");
    }

    // ดึงข้อมูลพื้นที่เช่าที่เป็นประเภทที่ 1
    $sql = "SELECT * FROM area WHERE area_type = 1 AND area_status_show = 1 ORDER BY area_name ASC";  
    $result = mysqli_query($conn, $sql);  
    $count = mysqli_num_rows($result);  
    $order = 1; //เก็บลำดับที่

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
        <span class="name"><?php echo $_SESSION['boss_name']; ?></span> <br>
        <span class="name"><strong>( ผู้บริหาร มก. )</strong></span>
    </div>

    <!--Componants-->
    <main class="data ">
        <div class="wrapper_2">
            <div class="title">
                สถานะพื้นที่เช่า ประเภทสัญญาเพื่อร้านค้าหรือพาณิชย์
            </div>



                <div class="form">

                    <table id="customers">
                        <thead>
                            <tr>
                                <th id="">ลำดับที่</th>
                                <th id="">เลขที่พื้นที่เช่า</th>
                                <th id="">บริเวณพื้นที่เช่า</th>
                                <th id="">ขนาดพื้นที่เช่า (ตร.ม.)</th>
                                <th id="">สถานะพื้นที่เช่า</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td><?php echo $order++; ?></td>
                                    <td> <?php echo $row["area_no"] ?> </td>
                                    <td> <?php echo $row["area_name"] ?> </td>
                                    <td> <?php echo $row["area_size"] ?> </td>

                                    <?php if ($row["area_status"] == '0') { ?>
                                        <td STYLE="color: green;"> ว่าง </td>
                                    <?php } else if ($row["area_status"] == '1') { ?>
                                        <td STYLE="color: #C39D01;"> จองแล้ว </td>
                                    <?php } else if ($row["area_status"] == '2') { ?>
                                        <td STYLE="color: red;"> ไม่ว่าง </td>
                                    <?php } ?>

                                </tr>

                            <?php } ?>
                        </tbody>
                    </table>
                </div>
 
        </div>


    </main>

    <!--js-->
    <script src="../../../script/main.js"></script>

</body>

</html>