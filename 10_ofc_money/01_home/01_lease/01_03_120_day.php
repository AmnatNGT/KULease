<?php {
    session_start();
    require('../../../connection.php');

    if (!$_SESSION['ofc_mn']) {
        header("Location: ../../../index.php");
    }

    date_default_timezone_set('Asia/Bangkok');
    $today = date_create(date("Y-m-d")); //"2022-09-22"  date("Y-m-d")


    //ประเภท 1
    $sql = "SELECT * FROM lease WHERE le_type=1 AND le_status=1";
    $result = mysqli_query($conn, $sql);
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

            <!-- Back -->
            <div>
                <a href="../01_admin_home.php" class="back" style="text-decoration:none;">
                    <span class="icon_b" style="color: blue; font-size: 25px;"><em class="fa fa-arrow-left" aria-hidden="true"></em></span>
                    <span class="name_b" style="color: blue; font-size: 20px;"><strong>BACK</strong> </span>
                </a>
            </div>

            <div class="title">
                ประเภทสัญญาเช่าเพื่อร้านค้าหรือพาณิชย์ ที่หมดอายุสัญญาเช่าภายใน 120 วัน
            </div>



                <div class="form">

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
                                <th id="">เอกสารสัญญาเช่า</th>
                            </tr>
                        </thead>

                        <tbody>
                            <!-- สัญญาเพื่อร้านค้าหรือพาณิชย์ 1-->
                            <?php while ($row = mysqli_fetch_assoc($result)) {
                                $ch_end_1_120 = date_create($row['le_end_date']);
                                $diff = date_diff($today, $ch_end_1_120);
                            ?>
                                <!-- หาสัญญาเช่าประเภทที่ 1 ที่หมดอายุภายใน 120 วัน และแสดง -->
                                <?php if ($diff->format("%R%a") <= 120 and $diff->format("%R%a") > 60) { ?>

                                    <!-- ลำดับที่ -->
                                    <td><?php echo $order++; ?></td>
                                   <!-- เลขที่อ้างอิงสัญญาเช่าจากระบบ -->
                                   <td><?php echo $row['le_no']; ?></td>
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
                                    
                                    <!--ดูเอกสาร-->
                                    <?php if ($row['le_no_success'] == null) { ?>
                                        <td><a class="btn btn-secondary">ดูเอกสาร</a></td>
                                    <?php } else { ?>
                                        <?php if ($row['le_oth_file'] != null) { ?>
                                        <!-- สัญญาเช่ากรณีพิเศษ -->
                                        <td><a href="../../../file_uploads/other_file_lease/<?php echo 'oth_file_le_' . $le_id . '.pdf'; ?>" target="_blank" class="btn btn-primary">ดูเอกสาร</a></td>
                                    <?php } else { ?>
                                        <!-- สัญญาเช่ากรณีทั่วไป -->
                                        <td><a href="../../../file_uploads/lease_success/<?php echo $row['le_no'] . '.pdf' ?>" target="_blank" class="btn btn-primary">ดูเอกสาร</a></td>
                                    <?php } ?>
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