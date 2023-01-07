<?php {

    session_start();
    require('../../../connection.php');

    if (!$_SESSION['boss']) {
        header("Location: ../../../index.php");
    }

    // รับ id สัญญาเช่า
    $le_id = $_GET['le_id'];

    // นำ id สัญญา เก็บไว้ที่ session
    $_SESSION['le_id'] = $le_id;

    //ข้อมูลสัญญาสัญญา 
    $sql_le = "SELECT * FROM lease l , status_lease stl, tenant t
                         WHERE l.le_id = $le_id 
                         AND stl.le_id = l.le_id
                         AND t.tn_id = l.tn_id";
    $result_le = mysqli_query($conn, $sql_le);
    $row_le = mysqli_fetch_assoc($result_le);
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

    <!--Icon-->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../../style/style_navi_bg_sign.css">
    <link rel="stylesheet" href="../../../style/style_btn.css">
    <link rel="stylesheet" href="../../../style/style_boss_bar_name.css">

    <style>
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
            border: red 2px solid;
        }
    </style>

</head>

<body>

    <?php
    //header
    include('../../header_ofc.php');

    //side bar
    include('../../sidebar_ofc_otp.php');
    ?>

    <!-- ชื่อผู้ให้เช่า -->
    <div class="bar_boss_name">
        <span class="icon_name"><em class="fa fa-user-circle-o" aria-hidden="true"></em></span>
        <span class="name"><?php echo $_SESSION['boss_name']; ?></span> <br>
        <span class="name"><strong>( ผู้ให้เช่า )</strong></span>
    </div>

    <!--ข้อมูล-->
    <main class="data ">

        <div class="wrapper_2">

            <!-- Back -->
            <div>
                <a href="06_chk_data.php?le_id=<?php echo $le_id; ?>" class="back" style="text-decoration:none;">
                    <span class="icon_b" style="color: blue; font-size: 25px;"><em class="fa fa-arrow-left" aria-hidden="true"></em></span>
                    <span class="name_b" style="color: blue; font-size: 20px;"><strong>BACK</strong> </span>
                </a>
            </div>

            <div class="title">
                ผู้ให้เช่า ลงนามสัญญาเช่า <br> เลขที่สัญญาเช่า : <?php echo $row_le['le_no']; ?>
            </div>

            <!-- ลายเซ็น -->
            <div class="form">

                <!-- เมื่อกดส่งไปหน้า  07_2_insert_img_db.php-->
                <form action="07_2_insert_img_db.php" method="POST" enctype="multipart/form-data">
                    <!-- ชื่อรูปลายเซน -->
                    <input type="hidden" name="name" value="<?php echo $row_le['le_no']; ?>_boss_sign">


                    <div class="inputfield">
                        <label>แนบรูปภาพลายเซ็น <br> (.jpg / .jpeg / .png)<strong style="color: red;">*</strong></label>
                        <input type="file" class="input" placeholder="อีเมล" name="file_1" accept=".png, .jpg, .jpeg" required>
                    </div>

                    <br>

                    <!-- ติกยินยอม -->
                    <div>
                        <input type="checkbox" class="largerCheckbox" required>
                        <label for="vehicle1"> <strong style="color: red;">ฉันได้ทำการตรวจสอบเอกสารก่อนแล้ว และ ฉันยินยอมให้นำลายเซ็นของฉันไปใช้ในสัญญาเช่าของฉัน</strong> </label>
                    </div>

                    <br>

                    <div class="inputfield">
                        <label>เอกสารสัญญาเช่า</label>
                        <a href="../../../file_uploads/lease_success/<?php echo $row_le['le_no'] . '.pdf' ?>" target="_blank" class="input" style="color: blue;">ตรวจสอบเอกสาร ที่ต้องลงนาม</a>
                    </div>

                    <div class="inputfield">
                        <label>ข้อตกลงแนบท้ายสัญญาเช่า</label>
                        <td><a href="../../../file_uploads/last_file_lease/<?php echo 'last_file_le_' . $row_le['le_id'] . '.pdf' ?>" target="_blank" class="input" style="color: blue;">ตรวจสอบเอกสาร ที่ต้องลงนาม</a></td>
                    </div>

                    <button class="btn_no">ส่งรูปภาพลายเซ็นที่แนบ</button>

                </form>

                <br><br>

                <div class="title">
                    ตัวอย่างรูปลายเซ็น
                </div>
                <img src="../../../file_uploads/doc_signs/00_img_sign.jpg" class="center">

                <br><br>

                <!-- ข้อมูลผู้ลงนามก่อนหน้า -->
                <div class="title">
                    ข้อมูลผู้ลงนามก่อนหน้า
                </div>
                <div class="form">

                    <table id="customers">
                        <thead>
                            <tr>
                                <th id="">บทบาท</th>
                                <th id="">ชื่อ - สกุล</th>
                                <th id="">เบอร์ติดต่อ</th>
                                <th id="">ว/ด/ป ลงนาม</th>
                                <th id="">เวลาที่ ลงนาม</th>
                                <th id="">หมายเหตุ</th>
                            </tr>
                        </thead>

                        <tbody>
                            <!-- ผู้เช่า -->
                            <tr>

                                <!--ชื่อผู้เช่า-->
                                <td>ผู้เช่า </td>
                                <td><?php echo $row_le["tn_p_name"] . " " . $row_le["tn_f_name"] . " " . $row_le["tn_l_name"]; ?></td>
                                <td><?php echo $row_le['tn_tel'] ?></td>
                                <td><?php echo date('d / m / Y ', strtotime($row_le["otp_date_tn1"])); ?></td>
                                <td><?php echo $row_le['otp_time_tn1'] ?></td>
                                <td style="color: green;">ผู้เช่าลงนาม <br> และยืนยันตัวตน <br> ผ่าน OTP สำเร็จ</td>

                            </tr>

                            <!-- เจ้าหน้าที่ -->
                            <tr>

                                <!-- หาข้อมูลเจ้าหน้าที่ -->
                                <?php
                                $ofc_id = $row_le["le_sign_ofc1"];
                                $sql1 = "SELECT * FROM officer 
                                                    WHERE $ofc_id = ofc_id";
                                $result1 = mysqli_query($conn, $sql1);
                                $row_ofc = mysqli_fetch_assoc($result1);
                                ?>

                                <!-- พยานเจ้าหน้าที่ -->
                                <td>พยานเจ้าหน้าที่ </td>
                                <td><?php echo $row_ofc["ofc_p_name"] . " " . $row_ofc["ofc_f_name"] . " " . $row_ofc["ofc_l_name"]; ?></td>
                                <td><?php echo $row_ofc['ofc_tel'] ?></td>
                                <td><?php echo date('d / m / Y ', strtotime($row_le["otp_date_s2"])); ?></td>
                                <td><?php echo $row_le['otp_time_s2'] ?></td>
                                <td style="color: green;">เจ้าหน้าที่ลงนาม <br> และยืนยันตัวตน <br> ผ่าน OTP สำเร็จ</td>

                            </tr>

                            <!-- นิติกร -->
                            <tr>

                                <!-- หาข้อมูลเจ้าหน้าที่ -->
                                <?php
                                $law_id = $row_le["le_sign_ofc2"];
                                $sql2 = "SELECT * FROM officer 
                                        WHERE ofc_id = $law_id";
                                $result2 = mysqli_query($conn, $sql2);
                                $row_of2 = mysqli_fetch_assoc($result2);
                                ?>

                                <!-- พยานเจ้าหน้าที่ -->
                                <td>พยานนิติกร </td>
                                <td><?php echo $row_of2["ofc_p_name"] . " " . $row_of2["ofc_f_name"] . " " . $row_of2["ofc_l_name"]; ?></td>
                                <td><?php echo $row_of2['ofc_tel'] ?></td>
                                <td><?php echo date('d / m / Y ', strtotime($row_le["otp_date_s3"])); ?></td>
                                <td><?php echo $row_le['otp_time_s3'] ?></td>
                                <td style="color: green;">นิติกรลงนาม <br> และยืนยันตัวตน <br> ผ่าน OTP สำเร็จ</td>

                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </main>

    <!--js-->
    <script src="../../../script/main.js"></script>

</body>

</html>