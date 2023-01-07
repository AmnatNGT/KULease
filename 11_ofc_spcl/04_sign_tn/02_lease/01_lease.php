<?php {
    session_start();
    require('../../../connection.php');

    if (!$_SESSION['ofc_spcl']) {
        header("Location: ../../../index.php");
    }

    //หาข้อมูลสัญญา
    //ที่ ประเภทสัญญ=2 และ สถานะสัญญา=1
    // และ stl.st_pass= 1 AND stl.st_s1 = 0
    $sql = "SELECT * FROM lease le, status_lease stl, tenant t
            WHERE le.le_type = 2
                AND le.le_status = 1
                AND le.le_id = stl.le_id 
                AND le.tn_id = t.tn_id
                AND ( stl.st_pass= 1 AND stl.st_s1 = 0 )";
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
        <span class="name"><?php echo $_SESSION['ofc_spcl_name']; ?></span> <br>
        <span class="name"><strong>( เจ้าหน้าที่ พิเศษ )</strong></span>
    </div>

    <!--Componants-->
    <main class="data ">
        <div class="wrapper_2">
            <div class="title">
                ผู้เช่า ลงนามสัญญาเช่า ประเภทสัญญาเพื่องานบริการ
            </div>



                <div class="form">

                    <table id="customers">
                        <thead>
                            <tr>
                                <th id="">ลำดับที่</th>
                                <th id="">เลขที่อ้างอิงสัญญาจากระบบ</th>
                                <th id="">ชื่อ - สกุล ผู้เช่า</th>
                                <th id="">เอกสารสัญญาเช่า</th>
                                <th id="">เอกสารข้อตกลง <br> แนบท้ายสัญญาเช่า</th>
                                <th id="">ผู้เช่า และพยาน <br> ของผู้เช่า ลงนาม</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>

                                <!-- แสดงข้อมูล -->
                                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                    <td><?php echo $order++; ?> </td>

                                    <td><a href="03_see_lease.php?le_id=<?php echo $row["le_id"]; ?>"> <?php echo  $row['le_no']; ?> </a></td>


                                    <!--ชื่อผู้เช่า-->
                                    <td><?php echo $row["tn_p_name"] . " " . $row["tn_f_name"] . " " . $row["tn_l_name"]; ?></td>

                                    <!-- ตรวจสอบเอกสาร -->
                                    <?php if ($row['st_mn_pay'] != 1) { ?>
                                        <td><a href="#" class="btn btn-secondary">ตรวจสอบเอกสาร</a></td>
                                    <?php } else { ?>
                                        <td><a href="../../../file_uploads/lease_success/<?php echo $row['le_no'] . '.pdf' ?>" class="btn btn-primary" target="_blank">ตรวจสอบเอกสาร</a></td>
                                    <?php } ?>

                                    <!-- ข้อตกลงแนบท้าย -->
                                    <?php if ($row['st_mn_pay'] != 1) { ?>
                                        <td><a href="#" class="btn btn-secondary">ตรวจสอบเอกสาร</a></td>
                                    <?php } else { ?>
                                        <td><a href="../../../file_uploads/last_file_lease/<?php echo 'last_file_le_' . $row['le_id'] . '.pdf' ?>" class="btn btn-primary" target="_blank">ตรวจสอบเอกสาร</a></td>
                                    <?php } ?>

                                    <!-- อนุมัตเอกสาร -->
                                    <?php if ($row['st_mn_pay'] != 1) { ?>
                                        <td><a href="#" class="btn btn-secondary">ลงนาม</a></td>
                                    <?php } else { ?>
                                        <td><a href="08_1_chk_data.php?le_id=<?php echo $row['le_id']; ?>" class="btn btn-success">ลงนาม</a></td>
                                    <?php } ?>

                            </tr>
                        <?php } ?>


                        </tbody>
                    </table>
                </div>
 
        </div>

        <!--js-->
        <script src="../../../script/main.js"></script>

    </main>

</body>

</html>