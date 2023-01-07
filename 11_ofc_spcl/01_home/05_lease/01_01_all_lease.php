<?php {
    session_start();
    require('../../../connection.php');

    if (!$_SESSION['ofc_spcl']) {
        header("Location: ../../../index.php");
    }

    date_default_timezone_set('Asia/Bangkok');
    $today = date_create(date("Y-m-d")); //"2022-09-22"  date("Y-m-d")

    //สัญญาเช่าทั้งหมดในแต่ละประเภทสัญญา
    //ประเภท 5
    $sql = "SELECT * FROM lease l, status_lease stl , area ar
                    WHERE l.le_type=5 AND l.le_id = stl.le_id AND l.area_id = ar.area_id 
                    ORDER BY ar.area_name";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    $order = 1;
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
        <span class="name"><?php echo $_SESSION['ofc_spcl_name']; ?></span> <br>
        <span class="name"><strong>( เจ้าหน้าที่ พิเศษ )</strong></span>
    </div>

    <!--Componants-->
    <main class="data ">
        <div class="wrapper_2">

            <!-- Back -->
            <div>
                <a href="../01_admin_home.php" class="back" style="text-decoration:none;">
                    <span class="icon_b" style="color: blue; font-size: 25px;"><em class="fa fa-arrow-left" aria-hidden="true"></em></span>
                    <span class="name_b" style="color: blue; font-size: 20px;"><strong>BACK</strong> </span>
                </a>
            </div>

            <div class="title">
                ประเภทสัญญาเช่าเพื่อโรงอาหาร
            </div>



                <div class="form">

                    <!-- ค้นหาด้วย-->
                    <form action="01_search_id/01_01_all_lease.php" method="POST">
                        <div class="inputfield" style="width: 550px;">
                            <label>ค้นหาสัญญาเช่า</label>
                            <input type="text" class="input" placeholder="เลขที่อ้างอิงสัญญา" name="search_no" required>

                            <input type="submit" class="btn_search" value="ค้นหา" name="smt_no">
                        </div>
                    </form>

                    <br>

                    <table id="customers">
                        <thead>
                            <tr>
                                <th id="">ลำดับที่</th>
                                <th id="">เลขที่อ้างอิงสัญญา<br>เช่าจากระบบ</th>
                                <th id="">เลขที่สัญญาเช่า</th>
                                <th id="">ว/ด/ป เริ่มสัญญา</th>
                                <th id="">ว/ด/ป สิ้นสุดสัญญา</th>
                                <th id="">ระยะเวลาการเช่า (ปี)</th>
                                <th id="">บริเวณพื้นที่เช่า</th>
                                <th id="">สถานะสัญญาเช่า</th>
                                <th id="">การลงนาม</th>
                                <th id="">ยกเลิกสัญญาเช่า</th>
                            </tr>
                        </thead>

                        <tbody>
                            <!-- สัญญาเพื่อร้านค้าหรือพาณิชย์ 5-->
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                                <!-- ลำดับที่ -->
                                <td><?php echo $order++; ?></td>
                                
                                <!-- เลขที่อ้างอิงสัญญา<br>เช่าจากระบบ -->
                                <?php if ($row['le_last_file'] == null) { ?>
                                    <td><a href="01_12_see_lease.php?le_id=<?php echo $row["le_id"]; ?>"><?php echo $row['le_no']; ?></a> </td>
                                <?php } else { ?>
                                    <td><a href="01_09_see_lease.php?le_id=<?php echo $row["le_id"]; ?>"><?php echo $row['le_no']; ?></a> </td>
                                <?php } ?>
                                
                                <!-- เลขที่สัญญาเช่า -->
                                <td><?php echo $row['le_no_success'] ?>
                                <!-- ว/ด/ป เริ่มสัญญา -->
                                <td><?php echo date('d / m / y ', strtotime($row["le_start_date"])); ?></td>
                                <!-- ว/ด/ป สิ้นสุดสัญญา -->
                                <td><?php echo date('d / m / y ', strtotime($row['le_end_date'])); ?></td>
                                <!-- ระยะเวลาการเช่า -->
                                <td><?php echo $row['le_duration'] ?></td>

                                <?php
                                //พื้นที่เช่า
                                $le_id = $row['le_id'];
                                $sql_area = "SELECT * FROM lease le , area ar
                                                              WHERE le.area_id = ar.area_id
                                                              AND le.le_id = $le_id";
                                $result_area = mysqli_query($conn, $sql_area);
                                $row_area = mysqli_fetch_assoc($result_area);
                                ?>
                                <td><?php echo $row_area['area_name']; ?></td>
                                <!--ปิดพื้นที่เช่า-->

                                <!-- สถานะการเช่า -->
                                <?php if ($row["le_status"] == 1) { ?>
                                    <?php if ($row["st_s1"] == 0) { ?>
                                        <td style="color: orange;">อยู่ระหว่าง รอผู้เช่าลงนาม</td>
                                    <?php } else if ($row["st_s2"] == 0) { ?>
                                        <td style="color: orange;">อยู่ระหว่าง รอเจ้าหน้าทีลงนาม</td>
                                    <?php } else if ($row["st_s3"] == 0) { ?>
                                        <td style="color: orange;">อยู่ระหว่าง รอนิติกรลงนาม</td>
                                    <?php } else if ($row["st_boss"] == 0) { ?>
                                        <td style="color: orange;">อยู่ระหว่าง รอผู้ให้เช่าลงนาม</td>
                                    <?php } else if ($row["st_add_no"] == 0) { ?>
                                        <td style="color: orange;">ลงนามสำเร็จ รอเจ้าหน้าที่เพิ่มเลขที่สัญญาเช่า</td>
                                    <?php } else if ($row["st_add_no"] == 1) { ?>
                                        <td style="color: orange;">อยู่ระหว่างการเช่า</td>
                                    <?php } ?>
                                <?php } else if ($row["le_status"] == 2) { ?>
                                    <td style="color: red;">สัญญาเช่าหมดอายุ</td>
                                <?php } else if ($row["le_status"] == 3) { ?>
                                    <td style="color: red;">สัญญาเช่าถูกยกเลิก</td>
                                <?php } else if ($row["le_status"] == 0) { ?>
                                    <?php if ($row["st_mn_pay"] == 0) { ?>
                                        <td style="color: orange;">รอเจ้าหน้าที่การเงินอนุมัติ</td>
                                    <?php } else if ($row["st_pass"] == 0) { ?>
                                        <td style="color: orange;">รอเจ้าหน้าที่อนุมัติ</td>
                                    <?php }  ?>
                                <?php } ?>

                                <?php if ($row['le_last_file'] == null) { ?>
                                    <td><a class="btn btn-secondary">การลงนาม</a></td>
                                <?php } else { ?>
                                    <td><a href="01_11_sign.php?le_id=<?php echo $row["le_id"]; ?>" class="btn btn-warning">การลงนาม</a></td>
                                <?php } ?>

                                <!-- ยกเลิกสัญญาเช่า -->
                                <?php if ($row['le_status'] == 3) { ?>
                                    <td><a href="01_10_why_del.php?le_id=<?php echo $row["le_id"]; ?>" class="btn btn-warning">ดูสาเหตุ</a></td>
                                <?php } else { ?>
                                    <?php if ($row['le_last_file'] == null) { ?>
                                        <td><a href="02_delete_lease/01_03_delete.php?le_id=<?php echo $row["le_id"]; ?>" class="btn btn-danger">ยกเลิก</a></td>
                                    <?php } else { ?>
                                        <td><a href="02_delete_lease/01_01_delete.php?le_id=<?php echo $row["le_id"]; ?>" class="btn btn-danger">ยกเลิก</a></td>
                                    <?php } ?>

                                <?php } ?>

                        </tbody>
                    <?php } ?>
                    </table>
                </div>
 
        </div>


    </main>

    <!--js-->
    <script src="../../../script/main.js"></script>

</body>

</html>