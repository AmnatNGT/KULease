<?php {
    session_start();
    require('../../../connection.php');

    if (!$_SESSION['ofc_add']) {
        header("Location: ../../../index.php");
    }

    // รับ id สัญญาเช่า
    $le_id = $_GET['le_id'];

    //หาข้อมูล 
    $sql = "SELECT * FROM lease l, tenant t, status_lease stl
            WHERE l.le_id = $le_id
            AND t.tn_id = l.tn_id
            AND stl.le_id = l.le_id";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);
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
        <span class="name"><?php echo $_SESSION['ofc_add_name']; ?></span> <br>
        <span class="name"><strong>( เจ้าหน้าที่ เพิ่มสัญญาเช่า )</strong></span>
    </div>

    <!--Componants-->
    <main class="data ">
        <div class="wrapper_2">

            <!-- Back -->
            <div>
                <a href="01_lease.php" class="back" style="text-decoration:none;">
                    <span class="icon_b" style="color: blue; font-size: 25px;"><em class="fa fa-arrow-left" aria-hidden="true"></em></span>
                    <span class="name_b" style="color: blue; font-size: 20px;"><strong>BACK</strong> </span>
                </a>
            </div>

            <div class="title">
                ตรวจสอบข้อมูลผู้ลงนามก่อนหน้า
            </div>
            <div class="form">

                <table id="customers">
                    <thead>
                        <tr>
                            <th id="">บทบาท</th>
                            <th id="">เลขที่อ้างอิง <br> สัญญาเช่าจากระบบ</th>
                            <th id="">ชื่อ - สกุล</th>
                            <th id="">เบอร์ติดต่อ</th>
                            <th id="">ว/ด/ป ลงนาม</th>
                            <th id="">เวลาที่ ลงนาม</th>
                            <th id="">หมายเหตุ</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>

                            <td>ผู้เช่า </td>

                            <td><?php echo  $row['le_no']; ?></td>

                            <!--ชื่อผู้เช่า-->
                            <td><?php echo $row["tn_p_name"] . " " . $row["tn_f_name"] . " " . $row["tn_l_name"]; ?></td>
                            <td><?php echo $row['tn_tel'] ?></td>

                            <td>----------</td>
                            <td>----------</td>
                            <td style="color: red;">รอผู้เช่าลงนาม</td>


                        </tr>

                    </tbody>
                </table>
            </div>

            <br><br>
            <div class="title">
                ตรวจสอบเอกสารสัญญาเช่า ที่ต้องลงนาม
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <!-- ตรวจสอบเอกสาร -->
                <a href="../../../file_uploads/lease_success/<?php echo $row['le_no'] . '.pdf' ?>" class="btn btn-success" target="_blank">ตรวจสอบเอกสาร สัญญาเช่า</a>

                <!-- แนบท้ายสัญญา -->
                <a href="../../../file_uploads/last_file_lease/<?php echo 'last_file_le_' . $row['le_id'] . '.pdf' ?>" class="btn btn-success" target="_blank">ตรวจสอบเอกสาร ข้อตกลงแนบท้ายสัญญาเช่า</a>

            </div>

            <br><br>

            <div class="title">
                เลือกรูปแบบการลงนาม
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <a href="02_sign.php?le_id=<?php echo $row['le_id'] ?>" class="btn btn-warning" type="button">ลงนาม</a>
                <a href="07_1_insert_sign_img.php?le_id=<?php echo $row['le_id'] ?>" class="btn btn-primary" type="button">แนบรูปภาพลายเซ็น</a>
                

            </div>

        </div>

        <!--js-->
        <script src="../../../script/main.js"></script>

    </main>

</body>

</html>