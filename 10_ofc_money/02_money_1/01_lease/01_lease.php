<?php {
    session_start();
    require('../../../connection.php');

    if (!$_SESSION['ofc_mn']) {
        header("Location: ../../../index.php");
    }

    //หาข้อมูลประเภทสัญญาที่ 1
    $sql = "SELECT * FROM lease l, status_lease stl, tenant t
                     WHERE l.le_status = '0' 
                            AND stl.st_add_lease = '1'
                            AND stl.st_mn_pay = '0'   
                            AND l.le_id = stl.le_id
                            AND l.tn_id = t.tn_id
                            AND l.le_type = 1";
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
    include('../../header_ofc_mn.php');

    //side bar
    include('../../sidebar_ofc_mn.php');
    ?>

    <!-- ชื่อเจ้าหน้าที่ -->
    <div class="bar_name">
        <span class="icon_name"><em class="fa fa-user-circle-o" aria-hidden="true"></em></span>
        <span class="name"><?php echo $_SESSION['ofc_mn_name']; ?></span> <br>
        <span class="name"><strong>( เจ้าหน้าที่ การเงิน )</strong></span>
    </div>

    <!--Componants-->
    <main class="data ">
        <div class="wrapper_2">
            <div class="title">
                สถานะค่าเช่าล่วงหน้าและเงินประกัน ประเภทสัญญาเช่าเพื่อร้านค้าหรือพาณิชย์
            </div>



            <div class="form">

                <!-- ค้นหาด้วย
                    <form action="01_4_1_search_by_no.php" method="POST">
                        <div class="inputfield" style="width: 550px;">
                            <label>ค้นหาสัญญาเช่า</label>
                            <input type="text" class="input" placeholder="เลขที่อ้างอิงสัญญา" name="search_no" required>

                            <input type="submit" class="btn_search" value="ค้นหา" name="smt_no">
                        </div>
                    </form> -->

                <br>

                <table id="customers">
                    <thead>
                        <tr>
                            <th id="">ลำดับที่</th>
                            <th id="">เลขที่อ้างอิงสัญญาจากระบบ</th>
                            <th id="">ชื่อ - สกุล ผู้เช่า</th>
                            <th id="">สถานะสัญญาเช่า</th>
                            <th id="">แก้ไขสถานะการเงิน</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>

                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <td><?php echo $order++; ?> </td>

                                <td><a href="06_see_data_1.php?le_id=<?php echo $row["le_id"]; ?>"> <?php echo  $row['le_no']; ?> </a></td>


                                <td><?php echo $row["tn_p_name"] . " " . $row["tn_f_name"] . " " . $row["tn_l_name"]; ?></td>

                                <!-- สถานะการชำระ -->
                                <td style="color: red;">รอเจ้าหน้าที่การเงินอนุมัติ</td>

                                <!-- แก้ไขสถานะค่าเช่าล่วงหน้า และเงินประกัน ไปหน้า 02_find_lease.php ส่ง id สัญญาเช่าไปด้วย-->
                                <td><a href="02_find_lease.php?le_id=<?php echo $row['le_id']; ?>" class="btn btn-warning">แก้ไขสถานะการเงิน</a></td>


                        </tr>
                    <?php } ?>


                    </tbody>
                </table>
            </div>

        </div>


    </main>

</body>

</html>

<script src="../../../script/main.js"></script>