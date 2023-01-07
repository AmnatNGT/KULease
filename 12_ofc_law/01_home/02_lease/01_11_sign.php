<?php {
    session_start();
    require('../../../connection.php');

    if (!$_SESSION['ofc_law']) {
        header("Location: ../../../index.php");
    }

    //รับ le_id
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
        <span class="name"><?php echo $_SESSION['ofc_law_name']; ?></span> <br>
        <span class="name"><strong>( นิติกร )</strong></span>
    </div>

    <!--Componants-->
    <main class="data ">
        <div class="wrapper_2">

            <!-- Back -->
            <div>
                <a href="01_01_all_lease.php" class="back" style="text-decoration:none;">
                    <span class="icon_b" style="color: blue; font-size: 25px;"><em class="fa fa-arrow-left" aria-hidden="true"></em></span>
                    <span class="name_b" style="color: blue; font-size: 20px;"><strong>BACK</strong> </span>
                </a>
            </div>

            <div class="title">
                ตรวจสอบการลงนาม สัญญาเพื่องานบริการ
            </div>

            <div class="title">
                เลขที่อ้างอิงสัญญาเช่า <?php echo $row['le_no']; ?>
            </div>

            <div class="form">

                <table id="customers">
                    <thead>
                        <tr>
                            <th id="">บทบาท ผู้ลงนาม</th>
                            <th id="">เลขที่อ้างอิง <br> สัญญาเช่าจากระบบ</th>
                            <th id="">ชื่อ - สกุล ผู้ลงนาม</th>
                            <th id="">เบอร์ติดต่อ</th>
                            <th id="">ว/ด/ป ลงนาม</th>
                            <th id="">เวลาที่ ลงนาม</th>
                            <th id="">หมายเหตุ</th>
                        </tr>
                    </thead>

                    <tbody>
                        <!-- ข้อมูลผู้เช่า -->
                        <tr>

                            <td>ผู้เช่า </td>

                            <td><?php echo  $row['le_no']; ?></td>

                            <!--ชื่อผู้เช่า-->
                            <td><?php echo $row["tn_p_name"] . " " . $row["tn_f_name"] . " " . $row["tn_l_name"]; ?></td>
                            <td><?php echo $row['tn_tel'] ?></td>

                            <!-- สถานะการาลงนาม -->
                            <?php
                            $s1 = $row['st_s1'];
                            if ($s1 == 1) {
                            ?>
                                <td><?php echo date('d / m / Y ', strtotime($row["otp_date_tn1"])); ?></td>
                                <td><?php echo $row['otp_time_tn1'] ?></td>
                                <td style="color: green;">ผู้เช่าลงนาม <br> และยืนยันตัวตน <br> ผ่าน OTP สำเร็จ</td>
                            <?php } else { ?>
                                <td>----------</td>
                                <td>----------</td>
                                <td style="color: red;">ผู้เช่ายังไม่ได้ลงนาม</td>
                            <?php } ?>
                        </tr>

                        <!-- พยานของผู้เช่า -->
                        <tr>
                            <?php
                            $wn_id = $row['le_sign_wn'];
                            $sql_wn = "SELECT * FROM witness WHERE wn_id = $wn_id";
                            $result_wn = mysqli_query($conn, $sql_wn);

                            $row_wn = mysqli_fetch_assoc($result_wn);
                            ?>

                            <td>พยานของผู้เช่า </td>

                            <td><?php echo  $row['le_no']; ?></td>

                            <!--ชื่อผู้เช่า-->
                            <td><?php echo $row_wn["wn_p_name"] . " " . $row_wn["wn_f_name"] . " " . $row_wn["wn_l_name"]; ?></td>
                            <td><?php echo $row_wn['wn_phone'] ?></td>

                            <!-- สถานะการาลงนาม -->
                            <?php
                            $s1 = $row['st_s1'];
                            if ($s1 == 1) {
                            ?>
                                <td><?php echo date('d / m / Y ', strtotime($row["otp_date_tn2"])); ?></td>
                                <td><?php echo $row['otp_time_tn2'] ?></td>
                                <td style="color: green;">พยานของผู้เช่า <br> ลงนาม และยืนยันตัวตน <br> ผ่าน OTP สำเร็จ</td>
                            <?php } else { ?>
                                <td>----------</td>
                                <td>----------</td>
                                <td style="color: red;">พยานของผู้เช่ายังไม่ได้ลงนาม</td>
                            <?php } ?>
                        </tr>

                        <!-- เจ้าหน้าที่ผู้ลงนาม -->
                        <tr>

                            <!-- หาข้อมูลเจ้าหน้าที่ -->
                            <?php
                            $ofc_id = $row["le_sign_ofc1"];
                            $sql1 = "SELECT * FROM officer 
                                WHERE $ofc_id = ofc_id";
                            $result1 = mysqli_query($conn, $sql1);
                            $row_ofc = mysqli_fetch_assoc($result1);
                            ?>

                            <!-- พยานเจ้าหน้าที่ -->
                            <td>พยานเจ้าหน้าที่ </td>
                            <td><?php echo  $row['le_no']; ?></td>
                            <td><?php echo $row_ofc["ofc_p_name"] . " " . $row_ofc["ofc_f_name"] . " " . $row_ofc["ofc_l_name"]; ?></td>
                            <td><?php echo $row_ofc['ofc_tel'] ?></td>

                            <!-- สถานะการาลงนาม -->
                            <?php
                            $s2 = $row['st_s2'];
                            if ($s2 == 1) {
                            ?>
                                <td><?php echo date('d / m / Y ', strtotime($row["otp_date_s2"])); ?></td>
                                <td><?php echo $row['otp_time_s2'] ?></td>
                                <td style="color: green;">เจ้าหน้าที่ลงนาม <br> และยืนยันตัวตน <br> ผ่าน OTP สำเร็จ</td>
                            <?php } else { ?>
                                <td>----------</td>
                                <td>----------</td>
                                <td style="color: red;">เจ้าหน้าที่ยังไม่ได้ลงนาม</td>
                            <?php } ?>

                        </tr>

                        <!-- นิติกรผู้ลงนาม -->
                        <tr>
                            <!-- หาข้อมูลนิติกร -->
                            <?php
                            $law_id = $row["le_sign_ofc2"];
                            $sql2 = "SELECT * FROM officer 
                                        WHERE ofc_id = $law_id";
                            $result2 = mysqli_query($conn, $sql2);
                            $row_ofc2 = mysqli_fetch_assoc($result2);
                            ?>

                            <!-- พยานเจ้าหน้าที่ -->
                            <td>นิติกร </td>
                            <td><?php echo  $row['le_no']; ?></td>
                            <td><?php echo $row_ofc2["ofc_p_name"] . " " . $row_ofc2["ofc_f_name"] . " " . $row_ofc2["ofc_l_name"]; ?></td>
                            <td><?php echo $row_ofc2['ofc_tel'] ?></td>

                            <!-- สถานะการาลงนาม -->
                            <?php
                            $s3 = $row['st_s3'];
                            if ($s3 == 1) {
                            ?>
                                <td><?php echo date('d / m / Y ', strtotime($row["otp_date_s3"])); ?></td>
                                <td><?php echo $row['otp_time_s3'] ?></td>
                                <td style="color: green;">นิติกรลงนาม <br> และยืนยันตัวตน <br> ผ่าน OTP สำเร็จ</td>
                            <?php } else { ?>
                                <td>----------</td>
                                <td>----------</td>
                                <td style="color: red;">นิติกรยังไม่ได้ลงนาม</td>
                            <?php } ?>

                        </tr>

                        <!--ผู้ให้เช่าลงนาม -->
                        <tr>
                            <!-- หาข้อมูลผู้ให้เช่า -->
                            <?php
                            $ls_id = $row["le_sign_boss"];
                            $sql3 = "SELECT * FROM lessor 
                                        WHERE ls_id = $ls_id";
                            $result3 = mysqli_query($conn, $sql3);
                            $row_ofc3 = mysqli_fetch_assoc($result3);
                            ?>

                            <!-- พยานเจ้าหน้าที่ -->
                            <td>ผู้ให้เช่า </td>
                            <td><?php echo  $row['le_no']; ?></td>
                            <td><?php echo $row_ofc3["ls_p_name"] . " " . $row_ofc3["ls_f_name"] . " " . $row_ofc3["ls_l_name"]; ?></td>
                            <td><?php echo $row_ofc3['ls_tel'] ?></td>

                            <!-- สถานะการาลงนาม -->
                            <?php
                            $s4 = $row['st_boss'];
                            if ($s4 == 1) {
                            ?>
                                <td><?php echo date('d / m / Y ', strtotime($row["otp_date_boss"])); ?></td>
                                <td><?php echo $row['otp_time_boss'] ?></td>
                                <td style="color: green;">ผู้ให้เช่าลงนาม <br> และยืนยันตัวตน <br> ผ่าน OTP สำเร็จ</td>
                            <?php } else { ?>
                                <td>----------</td>
                                <td>----------</td>
                                <td style="color: red;">ผู้ให้เช่ายังไม่ได้ลงนาม</td>
                            <?php } ?>

                        </tr>

                    </tbody>
                </table>
            </div>

            <br><br>
            <div class="title">
                ตรวจสอบเอกสารสัญญาเช่า
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <!-- ตรวจสอบเอกสาร -->
                <a href="../../../file_uploads/lease_success/<?php echo $row['le_no'] . '.pdf' ?>" class="btn btn-success" target="_blank">ตรวจสอบเอกสาร สัญญาเช่า</a>

                <!-- แนบท้ายสัญญา -->
                <a href="../../../file_uploads/last_file_lease/<?php echo 'last_file_le_' . $row['le_id'] . '.pdf' ?>" class="btn btn-success" target="_blank">ตรวจสอบเอกสาร ข้อตกลงแนบท้ายสัญญาเช่า</a>

            </div>
        </div>

        <!--js-->
        <script src="../../../script/main.js"></script>

    </main>

</body>

</html>