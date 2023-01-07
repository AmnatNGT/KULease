<?php {
    session_start();
    require('../../../connection.php');

    if (!$_SESSION['ofc_mn']) {
        header("Location: ../../../../index.php");
    }

    // แสดงข้อมูลการเงินประเภทที่ 3
    $sql = "SELECT * FROM lease le, money mn 
                WHERE le.le_type = 3 AND mn.le_id = le.le_id AND mn.mn_type =3 AND mn.mn_first_pay = 1";
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
                สถานะการเงิน ประเภทสัญญาเช่าเพื่องานวิจัย/การเรียนการสอน
            </div>


            <div class="form">

                <!-- ค้นหาด้วย-->
                <form action="01_4_search_1.php" method="POST">
                    <div class="inputfield" style="width: 550px;">
                        <label>ค้นหาข้อมูลการเงิน</label>
                        <input type="text" class="input" placeholder="เลขที่อ้างอิงสัญญา" name="search_no" required>

                        <input type="submit" class="btn_search" value="ค้นหา" name="smt_no">
                    </div>
                </form>

                <br>

                <table id="customers">
                    <thead>
                        <tr>
                            <th id="">ลำดับที่</th>
                            <th id="">เลขที่อ้างอิงสัญญาจากระบบ</th>
                            <th id="">เลขที่สัญญาเช่า</th>
                            <th id="">ชื่อ - สกุล ผู้เช่า</th>
                            <th id="">ดูสถานะการเงิน</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>

                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <td><?php echo $order++; ?> </td>

                                <td><a href="01_6_see_data_1.php?le_id=<?php echo $row["le_id"]; ?>"> <?php echo  $row['le_no']; ?> </a></td>

                                <?php if ($row["le_no_success"] == null) { ?>
                                    <td style="color: red;">ไม่มีเลขที่สัญญาเช่า</td>
                                <?php } else { ?>
                                    <td><?php echo $row["le_no_success"]; ?></td>
                                <?php } ?>

                                <!--ชื่อผู้เช่า-->
                                <?php
                                $le_id = $row["le_id"];
                                $sql_ch = "SELECT * FROM lease l,tenant t
                                                        WHERE l.le_id = $le_id AND l.tn_id = t.tn_id ";
                                $result_ch = mysqli_query($conn, $sql_ch);
                                $row_ch = mysqli_fetch_assoc($result_ch);
                                ?>

                                <td>
                                    <?php if ($row_ch) {
                                        echo $row_ch["tn_p_name"] . " " . $row_ch["tn_f_name"] . " " . $row_ch["tn_l_name"];
                                    } else { ?>
                                        <a style="color: red;">สัญญาเช่ากรณีพิเศษ </a>
                                    <?php } ?>
                                </td>

                                <!-- ดูสถานะการเงิน ไปหน้า 01_2_money_see.php ส่ง ไอดี สัญญาไปด้วย-->
                                <td><a href="01_2_money_see.php?le_id=<?php echo $row['le_id'] ?>" class="btn_pri">ดูสถานะการเงิน</a></td>

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