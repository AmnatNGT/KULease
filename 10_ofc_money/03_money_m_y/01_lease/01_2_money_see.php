<?php {
    session_start();
    require('../../../connection.php');

    if (!$_SESSION['ofc_mn']) {
        header("Location: ../../../index.php");
    }

    // รับ ไอดีสัญญา
    $le_id = $_GET["le_id"];

    //เงินประกัน
    $sql_1 = "SELECT * FROM lease le, money mn 
                WHERE mn.le_id = $le_id  AND mn.mn_type =1 AND le.le_id = $le_id";
    $result_1 = mysqli_query($conn, $sql_1);
    $count_1 = mysqli_num_rows($result_1);
    $order_1 = 1; //เก็บลำดับที่
    $row_1 = mysqli_fetch_assoc($result_1);

    //ค่าเช่าล่วงหน้า
    $sql_2 = "SELECT * FROM lease le, money mn 
                WHERE mn.le_id = $le_id  AND mn.mn_type =2 AND le.le_id = $le_id";
    $result_2 = mysqli_query($conn, $sql_2);
    $count_2 = mysqli_num_rows($result_2);
    $order_2 = 1; //เก็บลำดับที่
    $row_2 = mysqli_fetch_assoc($result_2);

    //ค่าเช่ารายเดือน
    $sql_3 = "SELECT * FROM lease le, money mn 
                WHERE mn.le_id = $le_id  AND mn.mn_type =3 AND le.le_id = $le_id
                ORDER BY mn_id DESC";
    $result_3 = mysqli_query($conn, $sql_3);
    $count_3 = mysqli_num_rows($result_3);
    $order_3 = 1; //เก็บลำดับที่
    $count_pay = $count_3 + 1;

    //ข้อมูลพื้นที่
    $sql = "SELECT * FROM lease WHERE le_id = $le_id ";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    //เก็บลำดับที่
    $row = mysqli_fetch_assoc($result);

    //หาข้อมูล tenant
    $le_id = $row["le_id"];
    $sql_ch = "SELECT * FROM lease l,tenant t
        WHERE l.le_id = $le_id AND l.tn_id = t.tn_id ";
    $result_ch = mysqli_query($conn, $sql_ch);
    $row_ch = mysqli_fetch_assoc($result_ch);
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
                <a href="01_1_money.php" class="back" style="text-decoration:none;">
                    <span class="icon_b" style="color: blue; font-size: 25px;"><em class="fa fa-arrow-left" aria-hidden="true"></em></span>
                    <span class="name_b" style="color: blue; font-size: 20px;"><strong>BACK</strong> </span>
                </a>
            </div>

            <div class="title">
                สถานะการเงิน ประเภทสัญญาเช่าเพื่อร้านค้าหรือพาณิชย์
            </div>
            <hr>

            <!-- แสดงข้อมูล  เงินประกัน-->
            <div class="title">
                เงินประกัน
            </div>
            <div class="form">

                <table id="customers">
                    <thead>
                        <tr>
                            <th id="">ว/ด/ป ที่ชำระ</th>
                            <th id="">เลขที่อ้างอิงสัญญาเช่า</th>
                            <th id="">เลขที่สัญญาเช่า</th>
                            <th id="">จำนวนเงิน (บาท)</th>
                            <th id="">สถานะการชำระเงิน</th>
                            <th id="">หลักฐานการชำระ</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if ($row_1['mn_status'] != 100) { ?>
                            <tr>
                                <td><?php echo date('d / m / Y ', strtotime($row_1["mn_date_pay"])); ?></td>

                                <td><a href="01_5_see_data_2_4.php?le_id=<?php echo $row_1["le_id"]; ?>"> <?php echo  $row_1['le_no']; ?> </a></td>

                                <?php if ($row_1["le_no_success"] == null) { ?>
                                    <td style="color: red;">ไม่มีเลขที่สัญญาเช่า</td>
                                <?php } else { ?>
                                    <td><?php echo $row_1["le_no_success"]; ?></td>
                                <?php } ?>

                                <td><?php echo $row_1["mn_cost"]; ?></td>

                                <td STYLE="color: green;">ชำระแล้ว</td>

                                <td><a href="../../../file_uploads/money_dp_ad/<?php echo $row_1["mn_file"]; ?>" target="_blank" class="btn_pri">ดูหลักฐาน</a></td>

                            </tr>
                        <?php } else { ?>
                            <tr>
                                <td>---------</td>
                                <td>----------</td>
                                <td>----------</td>
                                <td>----------</td>
                                <td STYLE="color: red;">ได้รับยกเว้น</td>
                                <td><a class="btn_gray">ดูหลักฐาน</a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <br>
            <hr>
            <br>

            <!-- แสดงข้อมูล  ค่าเช่าล่วงหน้า-->
            <div class="title">
                ค่าเช่าล่วงหน้า
            </div>
            <div class="form">

                <table id="customers">
                    <thead>
                        <tr>
                            <th id="">งวดที่ชำระ</th>
                            <th id="">ว/ด/ป ที่ชำระ</th>
                            <th id="">เลขที่อ้างอิงสัญญาเช่า</th>
                            <th id="">เลขที่สัญญาเช่า</th>
                            <th id="">จำนวนเงิน (บาท)</th>
                            <th id="">สถานะการชำระเงิน</th>
                            <th id="">หลักฐานการชำระ</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if ($row_2['mn_status'] != 100) { ?>
                            <tr>
                                <td>1</td>

                                <td><?php echo date('d / m / Y ', strtotime($row_2["mn_date_pay"])); ?></td>

                                <td><a href="01_5_see_data_2_4.php?le_id=<?php echo $row_2["le_id"]; ?>"> <?php echo  $row_2['le_no']; ?> </a></td>

                                <?php if ($row_2["le_no_success"] == null) { ?>
                                    <td style="color: red;">ไม่มีเลขที่สัญญาเช่า</td>
                                <?php } else { ?>
                                    <td><?php echo $row_2["le_no_success"]; ?></td>
                                <?php } ?>

                                <td><?php echo $row_2["mn_cost"]; ?></td>

                                <td STYLE="color: green;">ชำระแล้ว</td>

                                <td><a href="../../../file_uploads/money_dp_ad/<?php echo $row_2["mn_file"]; ?>" target="_blank" class="btn_pri">ดูหลักฐาน</a></td>

                            </tr>
                        <?php } else { ?>
                            <tr>
                                <td>1</td>
                                <td>---------</td>
                                <td>----------</td>
                                <td>----------</td>
                                <td>----------</td>
                                <td STYLE="color: red;">ได้รับยกเว้น</td>
                                <td><a class="btn_gray">ดูหลักฐาน</a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <br>
            <hr>
            <br>

            <!-- แสดงข้อมูล  ค่าเช่า-->
            <div class="title">
                ค่าเช่ารายเดือน
            </div>
            <div class="form">

                <table id="customers">
                    <thead>
                        <tr>
                            <th id="">งวดที่ชำระ</th>
                            <th id="">งวด ว/ด/ป ที่ชำระ</th>
                            <th id="">เลขที่อ้างอิงสัญญาจากระบบ</th>
                            <th id="">เลขที่สัญญาเช่า</th>
                            <th id="">จำนวนเงิน (บาท)</th>
                            <th id="">สถานะการชำระเงิน</th>
                            <th id="">ว/ด/ป ที่แนบหลักฐาน</th>
                            <th id="">เวลา ที่แนบหลักฐาน</th>
                            <th id="">หลักฐานการชำระ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php while ($row_3 = mysqli_fetch_assoc($result_3)) { ?>

                                <!-- งวดที่ชำระ -->
                                <td><?php echo $count_pay-- ?></td>

                                <!-- >งวด ว/ด/ป ที่ชำระ -->
                                <td><?php echo date('d / m / Y ', strtotime($row_3["mn_date_pay"])); ?></td>

                                <!-- เลขที่อ้างอิงสัญญาจากระบบ -->
                                <td><a href="01_5_see_data_2_4.php?le_id=<?php echo $row_3["le_id"]; ?>"> <?php echo  $row_3['le_no']; ?> </a></td>

                                <!-- เลขที่สัญญาเช่า -->
                                <?php if ($row_3["le_no_success"] == null) { ?>
                                    <td style="color: red;">ไม่มีเลขที่สัญญาเช่า</td>
                                <?php } else { ?>
                                    <td><?php echo $row_3["le_no_success"]; ?></td>
                                <?php } ?>

                                <!-- จำนวนเงิน -->
                                <td><?php echo $row_3["mn_cost"]; ?></td>

                                <!-- สถานะการชำระเงิน -->
                                <?php if ($row_3["le_no_success"] == null) { ?>

                                    <td STYLE="color: red;">ยังไม่ได้ชำระ</td>

                                    <?php } else {
                                    if ($row_3["mn_status"] == 0) { ?>

                                        <td STYLE="color: red;">ยังไม่ได้ชำระ</td>

                                    <?php } else if ($row_3["mn_status"] == 1) { ?>

                                        <td STYLE="color: green;">ชำระแล้ว</td>
                                <?php }
                                } ?>

                                <!-- ว/ด/ป ที่แนบหลักฐาน -->
                                <?php if (($row_3["mn_date_pay_change"]) != null) { ?>
                                    <td><?php echo date('d / m / Y ', strtotime($row_3["mn_date_pay_change"])); ?></td>
                                <?php } else { ?>
                                    <td></td>
                                <?php } ?>

                                <!-- เวลา ที่แนบหลักฐาน -->
                                <td><?php echo $row_3["mn_time_pay_change"]; ?></td>


                                <!-- หลักฐานการชำระ -->
                                <!-- ถ้าสัญญายังไม่เสร็จแนบหลักฐานไม่ได้เข้า if นี้ -->
                                <?php if ($row_3["le_no_success"] == null) { ?>

                                    <td><a class="btn_gray">แนบหลักฐาน</a></td>

                                <?php }
                                // แนบหลักฐานการชำระ เข้า if นี้ 
                                else if ($row_3["mn_status"] == 0) { ?>

                                    <!-- เมือกดให้เรียก modal #showForm1-->
                                    <td><a class="btn_b" data-bs-target="#showForm1" data-bs-toggle="modal">แนบหลักฐาน</a></td>

                                <?php }
                                // แนบชำระแล้ว ะ เข้า if นี้
                                else if ($row_3["mn_status"] == 1) { ?>
                                    <td> <a href="../../../file_uploads/money_month_year/<?php echo $row_3['mn_file']; ?>" target="_blank" class="btn_pri">ดูหลักฐาน</a> </td>

                                <?php } ?>

                                <!--แสดงแนบไฟล์ ไปหน้า 01_3_pay_money_db.php -->
                                <form action="01_3_pay_money_db.php" method="POST" enctype="multipart/form-data">

                                    <!-- ส่งข้อมูลไปด้วย -->
                                    <input name="le_id" type="hidden" value="<?php echo $row_3["le_id"] ?>">
                                    <input name="mn_id" type="hidden" value="<?php echo $row_3["mn_id"]  ?>">
                                    <input name="mn_cost" type="hidden" value="<?php echo $row_3["mn_cost"];  ?>">
                                    <input name="mn_date_pay" type="hidden" value="<?php echo $row_3["mn_date_pay"];  ?>">
                                    <input name="le_duration" type="hidden" value="<?php echo $row_3["le_duration"] ?>">
                                    <input name="mn_count" type="hidden" value="<?php echo $count_3 ?>">

                                    <input name="count_pay" type="hidden" value="<?php echo $count_pay ?>">
                                    <input name="pay_date" type="hidden" value="<?php echo $row_3["mn_date_pay"]; ?>">
                                    <input name="le_no" type="hidden" value="<?php echo $row_3["le_no"]; ?>">
                                    <input name="tn_email" type="hidden" value="<?php echo $row_ch["tn_email"]; ?>">
                                    <input name="tn_tel" type="hidden" value="<?php echo $row_ch["tn_tel"]; ?>">

                                    <!-- แนบหลักฐาน -->
                                    <div class="modal fade" id="showForm1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <div>แนบหลักฐานการชำระเงิน </div>
                                                    <a href="#" class="btn-close" data-bs-dismiss="modal"></a>
                                                </div>
                                                <div class="modal-body">


                                                    <div class="wrapper_md">

                                                        <div class="title">
                                                            แนบหลักฐานการชำระเงิน
                                                        </div>
                                                        <div class="form">

                                                            <div class="inputfield">
                                                                <label>หลักฐานการชำระเงิน (PDF) <strong style="color:red;">*</strong></label>
                                                                <input type="file" class="input" name="file_1" accept="application/pdf" required>
                                                            </div>

                                                        </div>
                                                        <br>
                                                    </div>


                                                </div>

                                                <div class="modal-footer">
                                                    <a href="#" class="btn btn-danger" data-bs-dismiss="modal">ยกเลิก</a>
                                                    <button class="btn btn-success" name="save" onclick="return confirm('ยืนยัน : การบันทึก')">บันทึกข้อมูล</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </form>


                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>


    </main>

    <!--js-->
    <script src="../../../script/main.js"></script>

</body>

</html>