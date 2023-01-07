<?php {
    require("../../../connection.php");
    session_start();

    if (!$_SESSION['ofc_add']) {
        header("Location: ../../../index.php");
    }

    //หาข้อมูล boss
    $sql_bs = "SELECT * FROM lessor
                        WHERE ls_status_use = '1' ";  
    $r_bs = mysqli_query($conn, $sql_bs); 
    $c_bs = mysqli_num_rows($r_bs);  
    $order_bs = 1; //เก็บลำดับที่

    //หาข้อมูล ofc เพิ่มสัญญาเช่า
    $sql_ofc1 = "SELECT * FROM officer
                            WHERE ofc_status_use = '1'
                            AND ofc_type = '1' "; 
    $r_ofc1 = mysqli_query($conn, $sql_ofc1); 
    $c_ofc1 = mysqli_num_rows($r_ofc1); 
    $order_ofc1 = 1; //เก็บลำดับที่

    //หาข้อมูล ofc พิเศษ
    $sql_ofc2 = "SELECT * FROM officer
        WHERE ofc_status_use = '1'
        AND ofc_type = '2' "; 
    $r_ofc2 = mysqli_query($conn, $sql_ofc2); 
    $c_ofc2 = mysqli_num_rows($r_ofc2); 
    $order_ofc2 = 1; //เก็บลำดับที่

    //หาข้อมูล ofc การเงิน
    $sql_ofc3 = "SELECT * FROM officer
        WHERE ofc_status_use = '1'
        AND ofc_type = '3' "; 
    $r_ofc3 = mysqli_query($conn, $sql_ofc3); 
    $c_ofc3 = mysqli_num_rows($r_ofc3); 
    $order_ofc3 = 1; //เก็บลำดับที่

    //หาข้อมูล ofc นิติกร
    $sql_ofc4 = "SELECT * FROM officer
        WHERE ofc_status_use = '1'
        AND ofc_type = '4' "; 
    $r_ofc4 = mysqli_query($conn, $sql_ofc4); 
    $c_ofc4 = mysqli_num_rows($r_ofc4); 
    $order_ofc4 = 1; //เก็บลำดับที่

    //หาข้อมูล ผู้จัดการหอพัก
    $sql_ofc5 = "SELECT * FROM officer
        WHERE ofc_status_use = '1'
        AND ofc_type = '5' "; 
    $r_ofc5 = mysqli_query($conn, $sql_ofc5); 
    $c_ofc5 = mysqli_num_rows($r_ofc5); 
    $order_ofc5 = 1; //เก็บลำดับที่

    //หาข้อมูล ผู้บริหาร
    $sql_ofc6 = "SELECT * FROM officer
        WHERE ofc_status_use = '1'
        AND ofc_type = '6' "; 
    $r_ofc6 = mysqli_query($conn, $sql_ofc6); 
    $c_ofc6 = mysqli_num_rows($r_ofc6); 
    $order_ofc6 = 1; //เก็บลำดับที่


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
        <span class="name"><strong>( ผู้ดูแลระบบ )</strong></span>
    </div>


    <!--Componants-->
    <main class="data ">
        <div class="wrapper_2">
            <div class="title">
                จัดการสิทธิ์ผู้ใช้งาน
            </div>
            <br>

            <!--ผู้ให้เช่า-->
            <div class="title" style="color: #FF5151;">
                สิทธิ์การใช้งาน ผู้ให้เช่า
            </div>
            <div class="form">
                <table id="customers">
                    <thead>
                        <tr>
                            <th id="">ลำดับที่</th>
                            <th id="">ชื่อ - สกุล</th>
                            <th id="">ตำแหน่ง</th>
                            <th id="">บทบาท</th>
                            <th id="">ยกเลิกสิทธิ์การใช้งาน</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row_bs = mysqli_fetch_assoc($r_bs)) { ?>
                            <tr>
                                <td style="width: 70px;"><?php echo $order_bs++; ?> </td>
                                <td style="width: 500px;">
                                    <a STYLE="color: blue;" href="03_information_boss_regis.php?idsub=<?php echo $row_bs["ls_id"] ?>"> <?php echo  $row_bs["ls_p_name"] . " " . $row_bs["ls_f_name"] . " " . $row_bs["ls_l_name"]; ?> </a>
                                </td>
                                <td style="width: 300px;"><?php echo $row_bs["ls_role"]; ?> </td>
                                <td style="width: 170px;">ผู้ให้เช่า</td>

                                <td>
                                    <!-- ถ้ากด ยกเลิกไปหา  04_cancel_db.php ส่ง id ผู้ใช้งานไปด้วย-->
                                    <a STYLE="color: white;" href="04_cancel_db.php?n1=<?php echo $row_bs["ls_id"] ?>" class="btn_no" onclick="return confirm('ยืนยัน : การยกเลิกสิทธิ์การใช้งาน')">ยกเลิกสิทธิ์การใช้งาน</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <br>
            <hr><br>

            <!--เจ้าหน้าที่เพิ่มสัญญาเช่า-->
            <div class="title" style="color: #FF5151;">
                สิทธิ์การใช้งาน เจ้าหน้าที่เพิ่มสัญญาเช่า
            </div>
            <div class="form">
                <table id="customers">
                    <thead>
                        <tr>
                            <th id="">ลำดับที่</th>
                            <th id="">ชื่อ - สกุล</th>
                            <th id="">ตำแหน่ง</th>
                            <th id="">บทบาท</th>
                            <th id="">ยกเลิกสิทธิ์การใช้งาน</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row_ofc1 = mysqli_fetch_assoc($r_ofc1)) { ?>
                            <tr>
                                <td style="width: 70px;"><?php echo $order_ofc1++; ?> </td>
                                <td style="width: 500px;">
                                    <a STYLE="color: blue;" href="02_information_ofc_regis.php?idsub=<?php echo $row_ofc1["ofc_id"] ?>"> <?php echo  $row_ofc1["ofc_p_name"] . " " . $row_ofc1["ofc_f_name"] . " " . $row_ofc1["ofc_l_name"]; ?> </a>
                                </td>
                                <td style="width: 300px;"><?php echo $row_ofc1["ofc_role"]; ?> </td>
                                <td style="width: 170px;">เจ้าหน้าที่เพิ่มสัญญาเช่า</td>

                                <td>
                                    <!-- ถ้ากด ยกเลิกไปหา  04_cancel_db.php ส่ง id ผู้ใช้งานไปด้วย-->
                                    <a STYLE="color: white;" href="04_cancel_db.php?n2=<?php echo $row_ofc1["ofc_id"] ?>" class="btn_no" onclick="return confirm('ยืนยัน : การยกเลิกสิทธิ์การใช้งาน')">ยกเลิกสิทธิ์การใช้งาน</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <br>
            <hr><br>

            <!--เจ้าหน้าที่พิเศษ-->
            <div class="title" style="color: #FF5151;">
                สิทธิ์การใช้งาน เจ้าหน้าที่พิเศษ
            </div>
            <div class="form">
                <table id="customers">
                    <thead>
                        <tr>
                            <th id="">ลำดับที่</th>
                            <th id="">ชื่อ - สกุล</th>
                            <th id="">ตำแหน่ง</th>
                            <th id="">บทบาท</th>
                            <th id="">ยกเลิกสิทธิ์การใช้งาน</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row_ofc2 = mysqli_fetch_assoc($r_ofc2)) { ?>
                            <tr>
                                <td style="width: 70px;"><?php echo $order_ofc2++; ?> </td>
                                <td style="width: 500px;">
                                    <a STYLE="color: blue;" href="02_information_ofc_regis.php?idsub=<?php echo $row_ofc2["ofc_id"] ?>"> <?php echo  $row_ofc2["ofc_p_name"] . " " . $row_ofc2["ofc_f_name"] . " " . $row_ofc2["ofc_l_name"]; ?> </a>
                                </td>
                                <td style="width: 300px;"><?php echo $row_ofc2["ofc_role"]; ?> </td>
                                <td style="width: 170px;">เจ้าหน้าที่พิเศษ</td>

                                <td>
                                    <!-- ถ้ากด ยกเลิกไปหา  04_cancel_db.php ส่ง id ผู้ใช้งานไปด้วย-->
                                    <a STYLE="color: white;" href="04_cancel_db.php?n3=<?php echo $row_ofc2["ofc_id"] ?>" class="btn_no" onclick="return confirm('ยืนยัน : การยกเลิกสิทธิ์การใช้งาน')">ยกเลิกสิทธิ์การใช้งาน</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <br>
            <hr><br>

            <!--เจ้าหน้าที่การเงิน-->
            <div class="title" style="color: #FF5151;">
                สิทธิ์การใช้งาน เจ้าหน้าที่การเงิน
            </div>
            <div class="form">
                <table id="customers">
                    <thead>
                        <tr>
                            <th id="">ลำดับที่</th>
                            <th id="">ชื่อ - สกุล</th>
                            <th id="">ตำแหน่ง</th>
                            <th id="">บทบาท</th>
                            <th id="">ยกเลิกสิทธิ์การใช้งาน</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row_ofc3 = mysqli_fetch_assoc($r_ofc3)) { ?>
                            <tr>
                                <td style="width: 70px;"><?php echo $order_ofc3++; ?> </td>
                                <td style="width: 500px;">
                                    <a STYLE="color: blue;" href="02_information_ofc_regis.php?idsub=<?php echo $row_ofc3["ofc_id"] ?>"> <?php echo  $row_ofc3["ofc_p_name"] . " " . $row_ofc3["ofc_f_name"] . " " . $row_ofc3["ofc_l_name"]; ?> </a>
                                </td>
                                <td style="width: 300px;"><?php echo $row_ofc3["ofc_role"]; ?> </td>
                                <td style="width: 170px;">เจ้าหน้าการเงิน</td>

                                <td>
                                    <!-- ถ้ากด ยกเลิกไปหา  04_cancel_db.php ส่ง id ผู้ใช้งานไปด้วย-->
                                    <a STYLE="color: white;" href="04_cancel_db.php?n4=<?php echo $row_ofc3["ofc_id"] ?>" class="btn_no" onclick="return confirm('ยืนยัน : การยกเลิกสิทธิ์การใช้งาน')">ยกเลิกสิทธิ์การใช้งาน</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <br>
            <hr><br>

            <!--นิติกร-->
            <div class="title" style="color: #FF5151;">
                สิทธิ์การใช้งาน นิติกร
            </div>
            <div class="form">
                <table id="customers">
                    <thead>
                        <tr>
                            <th id="">ลำดับที่</th>
                            <th id="">ชื่อ - สกุล</th>
                            <th id="">ตำแหน่ง</th>
                            <th id="">บทบาท</th>
                            <th id="">ยกเลิกสิทธิ์การใช้งาน</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row_ofc4 = mysqli_fetch_assoc($r_ofc4)) { ?>
                            <tr>
                                <td style="width: 70px;"><?php echo $order_ofc4++; ?> </td>
                                <td style="width: 500px;">
                                    <a STYLE="color: blue;" href="02_information_ofc_regis.php?idsub=<?php echo $row_ofc4["ofc_id"] ?>"> <?php echo  $row_ofc4["ofc_p_name"] . " " . $row_ofc4["ofc_f_name"] . " " . $row_ofc4["ofc_l_name"]; ?> </a>
                                </td>
                                <td style="width: 300px;"><?php echo $row_ofc4["ofc_role"]; ?> </td>
                                <td style="width: 170px;">นิติกร</td>

                                <td>
                                    <!-- ถ้ากด ยกเลิกไปหา  04_cancel_db.php ส่ง id ผู้ใช้งานไปด้วย-->
                                    <a STYLE="color: white;" href="04_cancel_db.php?n5=<?php echo $row_ofc4["ofc_id"] ?>" class="btn_no" onclick="return confirm('ยืนยัน : การยกเลิกสิทธิ์การใช้งาน')">ยกเลิกสิทธิ์การใช้งาน</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <br>
            <hr><br>

            <!--ผู้จัดการหอพัก-->
            <div class="title" style="color: #FF5151;">
                สิทธิ์การใช้งาน ผู้จัดการหอพัก
            </div>
            <div class="form">
                <table id="customers">
                    <thead>
                        <tr>
                            <th id="">ลำดับที่</th>
                            <th id="">ชื่อ - สกุล</th>
                            <th id="">ตำแหน่ง</th>
                            <th id="">บทบาท</th>
                            <th id="">ยกเลิกสิทธิ์การใช้งาน</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row_ofc5 = mysqli_fetch_assoc($r_ofc5)) { ?>
                            <tr>
                                <td style="width: 70px;"><?php echo $order_ofc5++; ?> </td>
                                <td style="width: 500px;">
                                    <a STYLE="color: blue;" href="02_information_ofc_regis.php?idsub=<?php echo $row_ofc5["ofc_id"] ?>"> <?php echo  $row_ofc5["ofc_p_name"] . " " . $row_ofc5["ofc_f_name"] . " " . $row_ofc5["ofc_l_name"]; ?> </a>
                                </td>
                                <td style="width: 300px;"><?php echo $row_ofc5["ofc_role"]; ?> </td>
                                <td style="width: 170px;">ผู้จัดการหอพัก</td>

                                <td>
                                    <!-- ถ้ากด ยกเลิกไปหา  04_cancel_db.php ส่ง id ผู้ใช้งานไปด้วย-->
                                    <a STYLE="color: white;" href="04_cancel_db.php?n6=<?php echo $row_ofc5["ofc_id"] ?>" class="btn_no" onclick="return confirm('ยืนยัน : การยกเลิกสิทธิ์การใช้งาน')">ยกเลิกสิทธิ์การใช้งาน</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <br>
            <hr><br>

            <!--ผู้บริหาร-->
            <div class="title" style="color: #FF5151;">
                สิทธิ์การใช้งาน ผู้บริหาร
            </div>
            <div class="form">
                <table id="customers">
                    <thead>
                        <tr>
                            <th id="">ลำดับที่</th>
                            <th id="">ชื่อ - สกุล</th>
                            <th id="">ตำแหน่ง</th>
                            <th id="">บทบาท</th>
                            <th id="">ยกเลิกสิทธิ์การใช้งาน</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row_ofc6 = mysqli_fetch_assoc($r_ofc6)) { ?>
                            <tr>
                                <td style="width: 70px;"><?php echo $order_ofc6++; ?> </td>
                                <td style="width: 500px;">
                                    <a STYLE="color: blue;" href="02_information_ofc_regis.php?idsub=<?php echo $row_ofc6["ofc_id"] ?>"> <?php echo  $row_ofc6["ofc_p_name"] . " " . $row_ofc6["ofc_f_name"] . " " . $row_ofc6["ofc_l_name"]; ?> </a>
                                </td>
                                <td style="width: 300px;"><?php echo $row_ofc6["ofc_role"]; ?> </td>
                                <td style="width: 170px;">ผู้บริหาร</td>

                                <td>
                                    <!-- ถ้ากด ยกเลิกไปหา  04_cancel_db.php ส่ง id ผู้ใช้งานไปด้วย-->
                                    <a STYLE="color: white;" href="04_cancel_db.php?n7=<?php echo $row_ofc6["ofc_id"] ?>" class="btn_no" onclick="return confirm('ยืนยัน : การยกเลิกสิทธิ์การใช้งาน')">ยกเลิกสิทธิ์การใช้งาน</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>

    </main>

    <!--js-->
    <script src="../../../script/main.js"></script>

</body>

</html>