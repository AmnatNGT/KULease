<?php {
    session_start();
    require('../../../connection.php');

    if (!$_SESSION['ofc_law']) {
        header("Location: ../../../index.php");
    }

    // id ผูใช้
    $id = $_SESSION['ofc_law'];

    //หาข้อมูล 
    $sql = "SELECT * FROM lease le, status_lease stl, tenant t
            WHERE le.le_sign_ofc2 = $id
                AND le.le_type = 5
                AND stl.st_pass = 1
                AND le.le_id = stl.le_id 
                AND le.tn_id = t.tn_id
                AND stl.st_s3 = 1 ";
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

    <!-- ชื่อนิติกร -->
    <div class="bar_name">
        <span class="icon_name"><em class="fa fa-user-circle-o" aria-hidden="true"></em></span>
        <span class="name"><?php echo $_SESSION['ofc_law_name']; ?></span> <br>
        <span class="name"><strong>( นิติกร )</strong></span>
    </div>

    <!--Componants-->
    <main class="data ">
        <div class="wrapper_2">
            <div class="title">
                ประวัตินิติกร ลงนามสัญญาเช่า ประเภทสัญญาเพื่อโรงอาหาร
            </div>



            <div class="form">

                <table id="customers">
                    <thead>
                        <tr>
                            <th id="">ลำดับที่</th>
                            <th id="">เลขที่อ้างอิงสัญญาจากระบบ</th>
                            <th id="">ว / ด / ป ที่ลงนาม</th>
                            <th id="">เวลา ที่ลงนาม</th>
                            <th id="">เอกสารสัญญาเช่า</th>
                            <th id="">ข้อตกลงแนบท้ายสัญญาเช่า</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>

                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <td><?php echo $order++; ?> </td>

                                <td><a href="03_see_lease.php?le_id=<?php echo $row["le_id"]; ?>"> <?php echo  $row['le_no']; ?> </a></td>

                                <td><?php echo date('d / m / Y ', strtotime($row["d_st_s2"])); ?></td>

                                <td><?php echo $row["t_st_s2"]; ?></td>

                                <!-- ตรวจสอบเอกสาร -->
                                <?php if ($row['st_mn_pay'] != 1) { ?>
                                    <td><a href="#" class="btn btn-secondary">ตรวจสอบเอกสารสัญญาเช่า</a></td>
                                <?php } else { ?>
                                    <td><a href="../../../file_uploads/lease_success/<?php echo $row['le_no'] . '.pdf' ?>" class="btn btn-primary" target="_blank">ตรวจสอบเอกสารสัญญาเช่า</a></td>
                                <?php } ?>

                                <!-- ตรวจสอบเอกสาร -->
                                <?php if ($row['st_mn_pay'] != 1) { ?>
                                    <td><a href="#" class="btn btn-secondary">ตรวจสอบข้อตกลงแนบท้ายสัญญาเช่า</a></td>
                                <?php } else { ?>
                                    <td><a href="../../../file_uploads/last_file_lease/<?php echo 'last_file_le_' . $row['le_id'] . '.pdf'; ?>" target="_blank" class="btn btn-primary">ข้อตกลงแนบท้ายสัญญาเช่า</a></td>
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