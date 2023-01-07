<?php {
    session_start();

    // ถ้าไม่มี $_SESSION['tn'] กลับไปที่หน้า index
    if (!$_SESSION['tn']) {
        header("Location: index.php");
    }

    require('../connection.php');

    //รับ session ผู้เช่า id
    $id = $_SESSION['tn'];

    //ดึงข้อมูลผู้เช่า
    $sql = "SELECT * FROM tenant WHERE tn_id = $id";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    $order = 1; //เก็บลำดับที่
    $row = mysqli_fetch_assoc($result);

    //ดึงข้อมูลสัญญาสัญญา 
    $sql_le = "SELECT * FROM lease l, status_lease stl 
                     WHERE l.le_id = stl.le_id 
                     AND l.tn_id = $id
                     AND stl.st_s1 = 1
                     ORDER BY l.le_id ASC";
    $result_le = mysqli_query($conn, $sql_le);
    $count_le = mysqli_num_rows($result);
    $order_le = 1;
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
    <link rel="stylesheet" href="style/style_3.css">
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

        <!-- ข้อมูล -->
        <div class="wrapper_2">

            <div class="title">
                ประวัติการเช่าสัญญา
            </div>

            <div class="form">
                <table id="customers">
                    <thead>
                        <tr>
                            <th id="">ลำดับที่</th>
                            <th id="">เลขอ้างอิงสัญญาเช่า</th>
                            <th id="">ประเภทสัญญาเช่า</th>
                            <th id="">ระยะเวลา <br> การเช่า (ปี)</th>
                            <th id="">ว/ด/ป เริ่มสัญญา</th>
                            <th id="">ว/ด/ป สิ้นสุดสัญญา</th>
                            <th id="">สถานะสัญญาเช่า</th>
                            <th id="">เอกสารสัญญาเช่า</th>
                            <th id="">ข้อตกลงแนบท้ายสัญญาเช่า</th>
                            <th id="">เอกสารเพิ่มเติมของผู้เช่า</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <!-- แสดงข้อมูลสัญญาเช่าของผู้เช่าคนนี้ -->
                            <?php while ($row_le = mysqli_fetch_assoc($result_le)) { ?>
                                <?php $type = "" ?>
                        <tr>
                            <!-- ลำดับที่ -->
                            <td> <?php echo $order_le++ ?> </td>
                            <!-- เลขอ้างอิงสัญญาเช่า -->
                            <td> <?php echo $row_le['le_no'] ?> </td>

                            <!--ตรวจสอบประเภทสัญญา-->
                            <?php if ($row_le["le_type"] == '1') {
                                    $type = "สัญญาเช่าเพื่อร้านค้าหรือพาณิชย์";
                                } else if ($row_le["le_type"] == '2') {
                                    $type = "สัญญาเช่าเพื่องานบริการ";
                                } else if ($row_le["le_type"] == '3') {
                                    $type = "สัญญาเช่าเพื่องานวิจัย / การเรียนการสอน";
                                } else if ($row_le["le_type"] == '4') {
                                    $type = "สัญญาเช่าเพื่อที่พักอาศัย";
                                } else if ($row_le["le_type"] == '5') {
                                    $type = "สัญญาเช่าเพื่อโรงอาหาร";
                                } else {
                                    $type = "----------";
                                }
                            ?>
                            <!-- แสดงประเภทสัญญส -->
                            <td><?php echo $type ?></td>

                            <!-- ตรวจสอบระยะเวลา -->
                            <?php if ($row_le['le_duration'] == null) {
                                    $dura = "----------";
                                } else {
                                    $dura = $row_le['le_duration'];
                                }
                            ?>
                            <!-- แสดงระยะเวลา -->
                            <td> <?php echo $dura ?> </td>

                            <!-- ตรวจสอบวันที่เริ่มสัญญา -->
                            <?php if ($row_le['le_start_date'] == null) {
                                    $std = "----------";
                                } else {
                                    $std = $row_le['le_start_date'];
                                }
                            ?>
                            <!-- แสดงวันที่เริ่มสัญญา -->
                            <td> <?php echo date('d / m / Y ', strtotime($std)); ?> </td>

                            <!-- ตรวจสอบวันที่สิ้นสุดสัญญา -->
                            <?php if ($row_le['le_end_date'] == null) {
                                    $edd = "----------";
                                } else {
                                    $edd = $row_le['le_end_date'];
                                }
                            ?>
                            <!-- แสดงวันที่สิ้นสุดสัญญา -->
                            <td> <?php echo date('d / m / Y ', strtotime($edd)); ?> </td>

                            <!--สถานะสัญญา-->
                            <?php if ($row_le["le_status"] == 0) { ?>
                                <td STYLE="color: red;">รอเจ้าหน้าที่ดำเนินการเพิ่มสัญญาเช่า</td>
                            <?php } else if ($row_le["le_status"] == 1) { ?>
                                <?php if ($row_le["le_no_success"] != null) { ?>
                                    <td STYLE="color: green;">อยู่ระหว่างการเช่า</td>
                                <?php } else if ($row_le["st_boss"] != null) { ?>
                                    <td STYLE="color: orange;">ลงนามสำเร็จ รอเจ้าหน้าที่เพิ่มเลขที่สัญญาเช่า</td>
                                <?php } else if ($row_le["st_s3"] != null) { ?>
                                    <td STYLE="color: orange;">อยู่ระหว่าง รอผู้ให้เช่าลงนาม</td>
                                <?php } else if ($row_le["st_s2"] != null) { ?>
                                    <td STYLE="color: orange;">อยู่ระหว่าง รอนิติกรลงนาม</td>
                                <?php } else if ($row_le["st_s1"] == 1) { ?>
                                    <td STYLE="color: orange;">อยู่ระหว่างรอ เจ้าหน้าที่ลงนาม</td>
                                <?php } else if ($row["st_s1"] == 0) { ?>
                                    <td STYLE="color: orange;">อยู่ระหว่างรอ ผู้เช่าลงนาม</td>
                                <?php } ?>
                            <?php } else if ($row_le["le_status"] == 2) { ?>
                                <td STYLE="color: red;">สัญญาเช่าหมดอายุ</td>
                            <?php } else if ($row_le["le_status"] == -1) { ?>
                                <td STYLE="color: red;">ยกเลิกสัญญาเช่า</td>
                            <?php } else if ($row_le["le_status"] == 3) { ?>
                                <td STYLE="color: red;">สัญญาเช่าถูกยกเลิก</td>
                            <?php } ?>


                            <!--ดูหลักฐานเอกสาร-->
                            <?php if ($row_le["st_add_no"] == null) { ?>
                                <td><a href="#" STYLE="color: red;">ไม่มีเอกสาร</a></td>
                                <td><a href="#" STYLE="color: red;">ไม่มีเอกสาร</a></td>
                                <td><a href="#" STYLE="color: red;">ไม่มีเอกสาร</a></td>
                            <?php } else if ($row_le["st_add_no"] != null) { ?>
                                <td><a href="../file_uploads/lease_success/<?php echo $row_le['le_no'] . '.pdf' ?>" target="_blank" STYLE="color: green;">ดูเอกสาร</a></td>
                                <td><a href="../file_uploads/last_file_lease/<?php echo 'last_file_le_' . $row_le['le_id'] . '.pdf'; ?>" target="_blank" STYLE="color: green;">ดูเอกสาร</a></td>
                                <td><a href="../file_uploads/other_file_lease/<?php echo 'oth_file_le_' . $row_le['le_id'] . '.pdf'; ?>" target="_blank" STYLE="color: green;">ดูเอกสาร</a></td>
                            <?php } ?>

                        </tr>
                    <?php } ?>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!--main.js เป็นการกดปุ่ม เพื่อเลื่อนเปิด ปิด  side bar ได้-->
    <script src="../script/main.js"></script>

</body>

</html>