<?php {
    session_start();

    // ถ้าไม่มี $_SESSION['tn'] กลับไปที่หน้า index
    if (!$_SESSION['tn']) {
        header("Location: ../index.php");
    }

    require('../connection.php');

    //รับ session ผู้เช่า id
    $id = $_SESSION['tn'];

    //ดึงข้อมูล สัญญา, สถานะสัญญา, ผู้เช่า
    $sql = "SELECT * FROM lease le, status_lease stl, tenant t
            WHERE le.tn_id = $id
                AND stl.st_s1 = 1
                AND le.le_id = stl.le_id 
                AND le.tn_id = t.tn_id ";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    $order = 1; //เก็บลำดับที่

    //ดึงข้อมูลผู้เช่า
    $sql_2 = "SELECT * FROM tenant WHERE tn_id = $id";
    $result_2 = mysqli_query($conn, $sql_2);
    $row_a = mysqli_fetch_assoc($result_2);

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

    <!--Componants-->
    <main class="data ">

        <!--แจ้งเตือนผู้เช่าใหม่ ถ้ายังไม่เคยส่งเรื่องทำสัญญาเช่าจะแสดงอันนี้-->
        <?php if ($row_a['tn_count_use'] == 0) { ?>
            <div class="alert">
                ผู้เช่าใหม่โปรดทราบ <strong>Click >>> ทำ/ต่อ สัญญาเช่า <<< </strong> เพื่อดำเนินการทำสัญญาเช่า
            </div>
        <?php } ?>

        <div class="wrapper_2">
            <div class="title">
                ประวัติผู้เช่า ลงนามสัญญาเช่า
            </div>

            <div class="form">
                <table id="customers">
                    <thead>
                        <tr>
                            <th id="">ลำดับที่</th>
                            <th id="">เลขที่อ้างอิงสัญญาจากระบบ</th>
                            <th id="">ว / ด / ป ที่ลงนาม</th>
                            <th id="">เวลา ที่ลงนาม</th>
                            <th id="">ตรวจสอบเอกสารสัญญาเช่า</th>
                            <th id="">ตรวจสอบข้อตกลงแนบท้ายสัญญาเช่า</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <!-- แสดงข้อมูล -->
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                                <!-- ลำดับที่ -->
                                <td><?php echo $order++; ?> </td>
                                <!-- เลขที่อ้างอิงสัญญาจากระบบ -->
                                <td> <?php echo  $row['le_no']; ?></td>
                                <!-- ว / ด / ป ที่ลงนาม -->
                                <td><?php echo date('d / m / Y ', strtotime($row["d_st_s1"])); ?></td>
                                <!-- เวลา ที่ลงนาม -->
                                <td><?php echo $row["t_st_s1"]; ?></td>

                                <!-- ตรวจสอบเอกสารสัญญาเช่า -->
                                <?php if ($row['st_mn_pay'] != 1) { ?>
                                    <td><a href="#" class="btn btn-secondary">เอกสารสัญญาเช่า</a></td>
                                <?php } else { ?>
                                    <td><a href="../file_uploads/lease_success/<?php echo $row['le_no'] . '.pdf' ?>" class="btn" target="_blank">เอกสารสัญญาเช่า</a></td>
                                <?php } ?>

                                <!--ตรวจสอบข้อตกลงแนบท้ายสัญญาเช่า -->
                                <?php if ($row['st_mn_pay'] != 1) { ?>
                                    <td><a href="#" class="btn btn-secondary">ข้อตกลงแนบท้ายสัญญาเช่า</a></td>
                                <?php } else { ?>
                                    <td><a href="../file_uploads/last_file_lease/<?php echo 'last_file_le_' . $row['le_id'] . '.pdf'; ?>" target="_blank" class="btn">ข้อตกลงแนบท้ายสัญญาเช่า</a></td>
                                <?php } ?>
                        </tr>
                    <?php } ?>

                    </tbody>
                </table>
            </div>

        </div>

        <!--main.js เป็นการกดปุ่ม เพื่อเลื่อนเปิด ปิด  side bar ได้-->
        <script src="../script/main.js"></script>

    </main>

</body>

</html>