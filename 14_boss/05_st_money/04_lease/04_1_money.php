<?php {
    session_start();
    require('../../../connection.php');

    if (!$_SESSION['boss']) {
        header("Location: ../../../index.php");
    }

    // หาข้อมูลผู้เช่า กับ การเงิน
    // ประเภทสัญญา 4 และ mn_type =3 (เงินรายเดือน/ปี)
    $sql = "SELECT * FROM lease le, money mn, tenant t  
                WHERE le.le_type = 4 
                AND mn.le_id = le.le_id 
                AND mn.mn_type =3 
                AND mn.mn_first_pay = 1
                AND le.tn_id = t.tn_id";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    $order = 1; //เก็บลำดับที่

    // สัญญาเช่ากรณีพิเศษ
    $sql2 = "SELECT * FROM lease le, money mn
        WHERE le.le_type = 4
        AND mn.le_id = le.le_id 
        AND mn.mn_type = 1
        AND le.tn_id = 0";
    $result2 = mysqli_query($conn, $sql2);
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
    <link rel="stylesheet" href="../../../style/style_boss_bar_name.css">
</head>

<body>

    <?php
    //header
    include('../../header_ofc.php');

    //side bar
    include('../../sidebar_ofc.php');
    ?>

    <!-- ชื่อเจ้าหน้าที่ -->
    <div class="bar_boss_name">
        <span class="icon_name"><em class="fa fa-user-circle-o" aria-hidden="true"></em></span>
        <span class="name"><?php echo $_SESSION['boss_name']; ?></span> <br>
        <span class="name"><strong>( ผู้ให้เช่า )</strong></span>
    </div>

    <!--Componants-->
    <main class="data ">
        <div class="wrapper_2">
            <div class="title">
                สถานะการเงิน ประเภทสัญญาเช่าเพื่อที่พักอาศัย
            </div>



            <div class="form">

                <!-- ค้นหาด้วยเลขที่อ้างอิงสัญญา ส่งไปหน้า 04_3_search_money_see.php-->
                <form action="04_3_search_money_see.php" method="POST">
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

                                <td><a href="04_4_see_data_04_1.php?le_id=<?php echo $row["le_id"]; ?>"> <?php echo  $row['le_no']; ?> </a></td>

                                <?php if ($row["le_no_success"] == null) { ?>
                                    <td style="color: red;">ไม่มีเลขที่สัญญาเช่า</td>
                                <?php } else { ?>
                                    <td><?php echo $row["le_no_success"]; ?></td>
                                <?php } ?>

                                <!--ชื่อผู้เช่า-->
                                <td><?php echo $row["tn_p_name"] . " " . $row["tn_f_name"] . " " . $row["tn_l_name"]; ?></td>

                                <!-- ดูสถานะการเงิน ส่ง id สัญญา ไปหน้า 04_2_money_see.php-->
                                <td><a href="04_2_money_see.php?le_id=<?php echo $row['le_id'] ?>" class="btn_pri">ดูสถานะการเงิน</a></td>

                        </tr>
                    <?php } ?>

                    <!-- แสดงข้อมูล -->
                    <?php while ($row2 = mysqli_fetch_assoc($result2)) { ?>
                        <tr>
                            <td><?php echo $order++; ?> </td>

                            <td><a href="04_4_see_data_04_1.php?le_id=<?php echo $row2["le_id"]; ?>"> <?php echo  $row2['le_no']; ?> </a></td>

                            <?php if ($row2["le_no_success"] == null) { ?>
                                <td style="color: red;">ไม่มีเลขที่สัญญาเช่า</td>
                            <?php } else { ?>
                                <td><?php echo $row2["le_no_success"]; ?></td>
                            <?php } ?>

                            <td style="color: red;">สัญญาเช่ากรณีพิเศษ</td>

                            <td><a href="04_2_money_see.php?le_id=<?php echo $row2['le_id'] ?>" class="btn_pri">ดูสถานะการเงิน</a></td>

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