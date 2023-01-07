<?php {
    session_start();

    // ถ้าไม่มี $_SESSION['tn'] กลับไปที่หน้า index
    if (!$_SESSION['tn']) {
        header("Location: index.php");
    }

    require('../connection.php');

    //รับ session ของ id ผู้เช่า
    $id = $_SESSION['tn'];

    //ดึงข้อมูลของผู้เช่า
    $sql = "SELECT * FROM tenant WHERE tn_id = $id";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    $order = 1; //เก็บลำดับที่

    $row = mysqli_fetch_assoc($result);

    //รับ POST ที่มาจาก search
    $search_id = $_POST["search_id"];

    //ดึงข้อมูลทั้งหมดจากตาราง lease, status_lease ที่เลขที่อ้างอิงสัญญาเช่าตรงกันกับฐานข้อมูล
    $sql_do_lease = "SELECT * FROM lease l, status_lease stl
                                WHERE l.le_no = $search_id 
                                AND l.le_id = stl.le_id";
    $result_do = mysqli_query($conn, $sql_do_lease);
    $row_do = mysqli_fetch_assoc($result_do);


    $type_le = $row_do['le_type']; //ตรวจสอบประเภทสัญญา ใช้ในการแสดงประเภทการเงิน ถ้าเป็นประเภทที่ 5 แสดงแค่เงินประกัน

    //id area
    $area_id_chk = $row_do['area_id'];
    //หาพื้นที่เช่าที่เป้นของสัญญาเช่าฉบับนั้นๆ
    $sql_area = "SELECT * FROM area a
                    WHERE $area_id_chk = a.area_id ";
    $result_area = mysqli_query($conn, $sql_area);
    $row_area = mysqli_fetch_assoc($result_area);

    //หาข้อมูลเงินประกัน
    // เรียกข้อมูลทั้งหมดจากตาราง lease และ mn ที่ 
    $sql_1 = "SELECT * FROM lease le, money mn 
                WHERE mn.le_id = le.le_id  AND mn.mn_type =1 AND le.le_no = $search_id";
    $result_1 = mysqli_query($conn, $sql_1);
    $count_1 = mysqli_num_rows($result_1);
    $order_1 = 1; //เก็บลำดับที่
    $row_1 = mysqli_fetch_assoc($result_1);

    //หาข้อมูลค่าเช่าล่วงหน้า
    $sql_2 = "SELECT * FROM lease le, money mn 
                WHERE mn.le_id = le.le_id  AND mn.mn_type =2 AND le.le_no = $search_id";
    $result_2 = mysqli_query($conn, $sql_2);
    $count_2 = mysqli_num_rows($result_2);
    $order_2 = 1; //เก็บลำดับที่
    $row_2 = mysqli_fetch_assoc($result_2);

    //หาข้อมูลค่าเช่ารายเดือน
    $sql_3 = "SELECT * FROM lease le, money mn 
                WHERE mn.le_id = le.le_id  AND mn.mn_type =3 AND le.le_no = $search_id
                ORDER BY mn_id DESC";
    $result_3 = mysqli_query($conn, $sql_3);
    $count_3 = mysqli_num_rows($result_3);
    $order_3 = 1; //เก็บลำดับที่
    $count_pay = $count_3 + 1;

    //หาข้อมูลค่าเช่า 10 % ใช้กับสัญญาที่ 5
    $sql_44 = "SELECT * FROM lease le, money mn 
                WHERE mn.le_id = le.le_id  AND mn.mn_type =4 AND le.le_no = $search_id";
    $result_44 = mysqli_query($conn, $sql_44);
    $row_10 = mysqli_fetch_assoc($result_44);
    $persen = $row_10['mn_cost']; // 10% ใช้กับสัญญาที่ 5

    if ($row_do["le_type"] == "3" || $row_do["le_type"] == "2") {

        //หาว่าสัญญาเช่าดังกล่าวเป็นการจ่ายแบบรายเดือน หรือ ปี
        $sql_4 = "SELECT * FROM lease le, money mn 
            WHERE mn.le_id = le.le_id  AND mn.mn_type =3 AND le.le_no = $search_id AND mn.mn_first_pay = 1";
        $result_4 = mysqli_query($conn, $sql_4);
        $row_4 = mysqli_fetch_assoc($result_4);

        //ถ้าเป็นสัญญา 2 และจ่ายรายปี หรือ สัญญา 3 แสดง ปี
        if (($row_4["type_pay"] == "year" and $row_do["le_type"] == "2") or $row_do["le_type"] == "3") {
            $type_pay = "ปี";
        } else {
            $type_pay = "เดือน";
        }
    } else {
        $type_pay = "เดือน";
    }
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
    <link rel="stylesheet" href="style/style_1.css">
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

        <!-- ข้อมูลสัญญาเช่า -->
        <div class="wrapper">

            <div class="title">
                ข้อมูลสัญญาเช่า
            </div>

            <div class="form">

                <!--Search id-->
                <?php if ($row['tn_count_use'] == 1) { ?>
                    <!-- เมื่อ search และกด ค้นหา ส่งข้อมูลแบบ POST ไปหน้า 02_3_do_search.php -->
                    <!-- เมื่อ search และกด ค้นหา ส่งข้อมูลแบบ POST ไปหน้า 02_3_do_search.php -->
                    <form action="02_3_do_search.php" method="POST">
                        <div class="inputfield">
                            <label>ค้นหา</label>
                            <div class="custom_select">
                                <select name="search_id" required>
                                    <option selected hidden value="">เลือกเลขที่ทำสัญญาเช่า</option>
                                    <!-- หาเลขที่อ้างอิงสัญญาเช่าที่เป็นของผู้เช่าคนนี้ -->
                                    <?php
                                    $q = "SELECT * FROM lease WHERE tn_id=$id ORDER BY le_id ASC";
                                    $result_q = mysqli_query($conn, $q);

                                    // แสดงเลขที่สัญญาเช่าที่เป็นของผู้เช่าคนนี้
                                    while ($row_q = mysqli_fetch_array($result_q)) {
                                    ?>
                                        <option value="<?php echo $row_q['le_no'] ?>"> <?php echo $row_q['le_no'] ?> </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <input type="submit" name="submit" id="" value="ค้นหา" class="btn">
                        </div>
                    </form>
                <?php } ?>

                <br><br>

                <!--เลขที่อ้างอิงสัญญา-->
                <div class="inputfield">
                    <label>เลขที่อ้างอิงสัญญาเช่าจากระบบ</label>
                    <!-- ถ้ายังไม่เคยส่งเรื่องทำสัญญาเช่าจะแสดงอันนี้ -->
                    <?php if ($row['tn_count_use'] == 0) { ?>
                        <input type="text" class="input" placeholder="  >>> ยังไม่มีสัญญาเช่า <<<" readonly>
                    <?php }
                    // ถ้าส่งเรื่องทำสัญญาเช่าจะแสดงอันนี้
                    else if ($row['tn_count_use'] == 1) { ?>
                        <input type="text" class="input" readonly value="<?php echo $row_do["le_no"] ?>">
                    <?php } ?>
                </div>

                <!--เลขที่สัญญาเช่า-->
                <div class="inputfield">
                    <label>เลขที่สัญญาเช่า</label>
                    <!-- ถ้ายังไม่เคยส่งเรื่องทำสัญญาเช่าจะแสดงอันนี้ -->
                    <?php if ($row['tn_count_use'] == 0) { ?>
                        <input type="text" class="input" placeholder="  >>> ยังไม่มีสัญญาเช่า <<<" readonly>
                    <?php }
                    // ถ้าส่งเรื่องทำสัญญาเช่าจะแสดงอันนี้
                    else if ($row['tn_count_use'] == 1) { ?>
                        <!-- ถ้าสัญญาเช่ามีสถานะ = 0 สัญญายังไม่เสร็จ ทำอันนี้-->
                        <?php if ($row_do["le_status"] == 0) { ?>
                            <input type="text" class="input" placeholder="  ----------" readonly>
                        <?php }
                        // ถ้าสัญญาเช่ามีสถานะ =1 เสร็จ, 2 หมดอายุ, -1 ไม่เพิ่ม, 3 ถูกยกเลิก ทำอันนี้
                        else if ($row_do["le_status"] == 1 or $row_do["le_status"] == 2 or $row_do["le_status"] == -1 or $row_do["le_status"] == 3) { ?>
                            <!-- สัญญาเช่ายังไม่เส็จ คือจะยังไม่มี le_no_success-->
                            <?php if ($row_do["le_no_success"] == null) {
                                $le_no = "----------";
                            }
                            // สัญญาเช่าเส็จ
                            else {
                                $le_no = $row_do["le_no_success"];
                            } ?>
                            <!-- แสดงข้อมูล -->
                            <input type="text" class="input" readonly value="<?php echo $le_no; ?> ">
                        <?php } ?>
                    <?php } ?>
                </div>

                <!--ประเภทสัญญาเช่า-->
                <div class="inputfield">
                    <label>ประเภทสัญญาเช่า</label>
                    <!-- ถ้ายังไม่เคยส่งเรื่องทำสัญญาเช่าจะแสดงอันนี้ -->
                    <?php if ($row['tn_count_use'] == 0) { ?>
                        <input type="text" class="input" placeholder="  >>> ยังไม่มีสัญญาเช่า <<<" readonly="readonly">
                    <?php } // ถ้าส่งเรื่องทำสัญญาเช่าจะแสดงอันนี้
                    else if ($row['tn_count_use'] == 1) { ?>
                        <!-- ถ้าสัญญาเช่ามีสถานะ = 0 สัญญายังไม่เสร็จ ทำอันนี้-->
                        <?php if ($row_do["le_status"] == 0) { ?>
                            <input type="text" class="input" placeholder="  ----------" readonly="readonly">
                        <?php }
                        // ถ้าสัญญาเช่ามีสถานะ =1 เสร็จ, 2 หมดอายุ, -1 ไม่เพิ่ม, 3 ถูกยกเลิก ทำอันนี้
                        else if ($row_do["le_status"] == 1 or $row_do["le_status"] == 2 or $row_do["le_status"] == -1 or $row_do["le_status"] == 3) { ?>

                            <!--หาประเภทสัญญา-->
                            <?php if ($row_do["le_type"] == '1') {
                                $type = "สัญญาเช่าเพื่อร้านค้าหรือพาณิชย์";
                            } else if ($row_do["le_type"] == '2') {
                                $type = "สัญญาเช่าเพื่องานบริการ";
                            } else if ($row_do["le_type"] == '3') {
                                $type = "สัญญาเช่าเพื่องานวิจัย / การเรียนการสอน";
                            } else if ($row_do["le_type"] == '4') {
                                $type = "สัญญาเช่าเพื่อที่พักอาศัย";
                            } else if ($row_do["le_type"] == '5') {
                                $type = "สัญญาเช่าเพื่อโรงอาหาร";
                            }
                            ?>
                            <?php ?>
                            <!-- แสดงข้อมูล -->
                            <input type="text" class="input" readonly="readonly" value="<?php echo $type ?> ">
                        <?php } ?>
                    <?php } ?>
                </div>

                <!--บริเวณที่เช่า-->
                <div class="inputfield">
                    <label>บริเวณที่เช่า</label>
                    <!-- ถ้ายังไม่เคยส่งเรื่องทำสัญญาเช่าจะแสดงอันนี้ -->
                    <?php if ($row['tn_count_use'] == 0) { ?>
                        <input type="text" class="input" placeholder="  >>> ยังไม่มีสัญญาเช่า <<<" readonly="readonly">
                    <?php }
                    // ถ้าส่งเรื่องทำสัญญาเช่าจะแสดงอันนี้
                    else if ($row['tn_count_use'] == 1) { ?>
                        <?php if ($row_do["le_status"] == 0) { ?>
                            <input type="text" class="input" placeholder="  ----------" readonly="readonly">
                        <?php }
                        // ถ้าสัญญาเช่ามีสถานะ =1 เสร็จ, 2 หมดอายุ, -1 ไม่เพิ่ม, 3 ถูกยกเลิก ทำอันนี้
                        else if ($row_do["le_status"] == 1 or $row_do["le_status"] == 2  or $row_do["le_status"] == -1 or $row_do["le_status"] == 3) { ?>
                            <!-- ถ้าเป็นสัญญาเช่าประเภทที่ 4 ทำอันนี้ -->
                            <?php if ($row_do['le_type'] == 4) { ?>
                                <input type="text" class="input" readonly="readonly" value="<?php echo $row_area["area_name"] . "  :  เลขที่ห้อง " . $row_area["area_room_no"]; ?> ">
                            <?php }
                            // ถ้าไม่ใช่สัญญาเช่าประเภทที่ 4 ทำอันนี้
                            else { ?>
                                <input type="text" class="input" readonly="readonly" value="<?php echo $row_area["area_name"] ?> ">
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </div>

                <!--ระยะเวลาการเช่า-->
                <div class="inputfield">
                    <label>ระยะเวลาที่เช่า</label>
                    <!-- ถ้ายังไม่เคยส่งเรื่องทำสัญญาเช่าจะแสดงอันนี้ -->
                    <?php if ($row['tn_count_use'] == 0) { ?>
                        <input type="text" class="input" placeholder="  >>> ยังไม่มีสัญญาเช่า <<<" readonly="readonly">
                    <?php }
                    // ถ้าส่งเรื่องทำสัญญาเช่าจะแสดงอันนี้
                    else if ($row['tn_count_use'] == 1) { ?>
                        <!-- ถ้าสัญญาเช่ามีสถานะ = 0 สัญญายังไม่เสร็จ ทำอันนี้-->
                        <?php if ($row_do["le_status"] == 0) { ?>
                            <input type="text" class="input" placeholder="  ----------" readonly="readonly">
                        <?php }
                        // ถ้าสัญญาเช่ามีสถานะ =1 เสร็จ, 2 หมดอายุ, -1 ไม่เพิ่ม, 3 ถูกยกเลิก ทำอันนี้
                        else if ($row_do["le_status"] == 1 or $row_do["le_status"] == 2  or $row_do["le_status"] == -1 or $row_do["le_status"] == 3) { ?>
                            <input type="text" class="input" readonly="readonly" value="<?php echo $row_do["le_duration"] . " ปี" ?> ">
                        <?php } ?>
                    <?php } ?>
                </div>

                <!--ว/ด/ป เริ่มสัญญา-->
                <div class="inputfield">
                    <label>ว/ด/ป เริ่มสัญญา</label>
                    <!-- ถ้ายังไม่เคยส่งเรื่องทำสัญญาเช่าจะแสดงอันนี้ -->
                    <?php if ($row['tn_count_use'] == 0) { ?>
                        <input type="text" class="input" placeholder="  >>> ยังไม่มีสัญญาเช่า <<<" readonly="readonly">
                    <?php }
                    // ถ้าส่งเรื่องทำสัญญาเช่าจะแสดงอันนี้
                    else if ($row['tn_count_use'] == 1) { ?>
                        <!-- ถ้าสัญญาเช่ามีสถานะ = 0 สัญญายังไม่เสร็จ ทำอันนี้-->
                        <?php if ($row_do["le_status"] == 0) { ?>
                            <input type="text" class="input" placeholder="  ----------" readonly="readonly">
                        <?php }
                        // ถ้าสัญญาเช่ามีสถานะ =1 เสร็จ, 2 หมดอายุ, -1 ไม่เพิ่ม, 3 ถูกยกเลิก ทำอันนี้
                        else if ($row_do["le_status"] == 1 or $row_do["le_status"] == 2  or $row_do["le_status"] == -1 or $row_do["le_status"] == 3) { ?>
                            <input type="text" class="input" readonly="readonly" value="<?php echo date('d / m / Y ', strtotime($row_do["le_start_date"])); ?> ">
                        <?php } ?>
                    <?php } ?>
                </div>

                <!--ว/ด/ป สิ้นสุดสัญญา-->
                <div class="inputfield">
                    <label>ว/ด/ป สิ้นสุดสัญญา</label>
                    <!-- ถ้ายังไม่เคยส่งเรื่องทำสัญญาเช่าจะแสดงอันนี้ -->
                    <?php if ($row['tn_count_use'] == 0) { ?>
                        <input type="text" class="input" name="" id="" placeholder="  >>> ยังไม่มีสัญญาเช่า <<<" readonly="readonly">
                    <?php }
                    // ถ้าส่งเรื่องทำสัญญาเช่าจะแสดงอันนี้
                    else if ($row['tn_count_use'] == 1) { ?>
                        <!-- ถ้าสัญญาเช่ามีสถานะ = 0 สัญญายังไม่เสร็จ ทำอันนี้-->
                        <?php if ($row_do["le_status"] == 0) { ?>
                            <input type="text" class="input" name="" id="" placeholder="  ----------" readonly="readonly">
                        <?php }
                        // ถ้าสัญญาเช่ามีสถานะ =1 เสร็จ, 2 หมดอายุ, -1 ไม่เพิ่ม, 3 ถูกยกเลิก ทำอันนี้
                        else if ($row_do["le_status"] == 1 or $row_do["le_status"] == 2  or $row_do["le_status"] == -1 or $row_do["le_status"] == 3) { ?>
                            <input type="text" class="input" name="" id="" readonly="readonly" value="<?php echo date('d / m / Y ', strtotime($row_do["le_end_date"])); ?> ">
                        <?php } ?>
                    <?php } ?>
                </div>

                <!-- ถ้าเป็นสัญญาที่ 5 แสดงอันนี้  -->
                <?php if ($type_le == '5') {  ?>
                    <div class="inputfield">
                        <label>ราคาเช่าร้อยละ </label>
                        <input type="text" class="input" value="<?php echo $persen; ?> % ของยอดขายแต่ละเดือน" readonly="readonly">
                    </div>
                <?php } ?>

                <!-- สถานะสัญญา -->
                <div class="inputfield">
                    <label>สถานะสัญญาเช่า</label>
                    <!-- ถ้ายังไม่เคยส่งเรื่องทำสัญญาเช่าจะแสดงอันนี้ -->
                    <?php if ($row['tn_count_use'] == 0) { ?>
                        <input type="text" class="input" placeholder="  >>> ยังไม่มีสัญญาเช่า <<<" readonly>
                    <?php }
                    // ถ้าส่งเรื่องทำสัญญาเช่าจะแสดงอันนี้
                    else if ($row['tn_count_use'] == 1) { ?>
                        <!-- ถ้าสัญญาเช่ามีสถานะ = 0 สัญญายังไม่เสร็จ ทำอันนี้-->
                        <?php if ($row_do["le_status"] == 0) { ?>
                            <input type="text" STYLE="color: red;" class="input" value="รอเจ้าหน้าที่ดำเนินการเพิ่มสัญญาเช่า" readonly>
                        <?php }
                        // ถ้าสถานะสัญญาเช่า =1 เสร็จแล้ว หรืออยู่ระหว่างการลงนาม
                        else if ($row_do["le_status"] == 1) { ?>

                            <!-- แสดงสถานะว่าอยู่ขั้นตอนใด -->
                            <?php if ($row_do["st_s1"] == 0) { ?>
                                <input type="text" STYLE="color: orange;" class="input" readonly value="อยู่ระหว่างรอ ผู้เช่าลงนาม">
                            <?php } else if ($row_do["st_s2"] == 0) { ?>
                                <input type="text" STYLE="color: orange;" class="input" readonly value="อยู่ระหว่างรอ เจ้าหน้าที่ลงนาม">
                            <?php } else if ($row_do["st_s3"] == 0) { ?>
                                <input type="text" STYLE="color: orange;" class="input" readonly value="อยู่ระหว่าง รอนิติกรลงนาม">
                            <?php } else if ($row_do["st_boss"] == 0) { ?>
                                <input type="text" STYLE="color: orange;" class="input" readonly value="อยู่ระหว่าง รอผู้ให้เช่าลงนาม">
                            <?php } else if ($row_do["st_add_no"] == 0) { ?>
                                <input type="text" STYLE="color: orange;" class="input" readonly value="ลงนามสำเร็จ รอเจ้าหน้าที่เพิ่มเลขที่สัญญาเช่า">
                            <?php } else if ($row_do["st_add_no"] == 1) { ?>
                                <input type="text" STYLE="color: green;" class="input" readonly value="อยู่ระหว่างการเช่า">
                            <?php } ?>

                        <?php }

                        //ถ้าสถานะ =2 สัญญาเช่าหมดอายุ ทำอันนี้
                        else if ($row_do["le_status"] == 2) { ?>
                            <input type="text" STYLE="color: red;" class="input" readonly value="สัญญาเช่าหมดอายุ">
                        <?php }
                        //ถ้าสถานะ =-1 ไม่เพิ่มสัญญาเช่า ทำอันนี้
                        else if ($row_do["le_status"] == -1) { ?>
                            <input type="text" STYLE="color: red;" class="input" readonly value="ไม่เพิ่มสัญญาเช่า">
                        <?php }
                        //ถ้าสถานะ =3 สัญญาถูกยกเลิก ทำอันนี้
                        else if ($row_do["le_status"] == 3) { ?>
                            <input type="text" STYLE="color: red;" class="input" readonly value="สัญญาเช่าถูกยกเลิก">
                        <?php } ?>

                    <?php }  ?>
                </div>

                <!-- ดูเอกสารสัญญาเช่า-->
                <!-- ต้องเพิ่ม st_add_no เลขที่สัญญาเช่า เช่าแล้วถึงแสดงอันนี้ -->
                <?php if ($row_do["st_add_no"] != null) { ?>
                    <div class="inputfield">
                        <label>เอกสารสัญญาเช่า</label>
                        <a href="../file_uploads/lease_success/<?php echo $row_do['le_no'] . '.pdf' ?>" target="_blank" class="input" STYLE="color: green;">ดูเอกสาร</a>
                    </div>

                    <div class="inputfield">
                        <label>เอกสารหลักฐานเพิ่มเติมของผู้เช่า</label>
                        <a href="../file_uploads/other_file_lease/<?php echo 'oth_file_le_' . $row_do['le_id'] . '.pdf'; ?>" target="_blank" class="input" STYLE="color: green;">ดูเอกสาร</a>
                    </div>

                    <div class="inputfield">
                        <label>เอกสารข้อตกลงแนบท้ายสัญญาเช่า</label>
                        <a href="../file_uploads/last_file_lease/<?php echo 'last_file_le_' . $row_do['le_id'] . '.pdf'; ?>" target="_blank" class="input" STYLE="color: green;">ดูเอกสาร</a>
                    </div>

                    <div class="inputfield">
                        <label>เอกสารอากรแสตมป์</label>
                        <a href="../file_uploads/stamp_file/<?php echo $row_do['le_stamp'] ?>" target="_blank" class="input" STYLE="color: green;">ดูเอกสาร</a>
                    </div>

                <?php } ?>

            </div>

            <br><br>

        </div>

        <!-- เจ้าหน้าที่ต้องทำสัญญาถึงจะแสดง -->
        <?php if ($row_do['le_status'] != 0) { ?>
            <!-- ข้อมูลการเงิน -->
            <div class="wrapper_2">

                <!-- le_status ต้อง == 1 || 2 || -1 || 3 ถึงแสดง-->
                <?php if ($row_do["le_status"] == 1 or $row_do["le_status"] == 2  or $row_do["le_status"] == -1 or $row_do["le_status"] == 3) { ?>
                    <!-- เงินประกัน -->
                    <div class="title">
                        ข้อมูลเงินประกัน
                    </div>
                    <div class="form">
                        <table id="customers">
                            <thead>
                                <tr>
                                    <th id="">ว/ด/ป ที่ชำระ</th>
                                    <th id="">จำนวนเงิน (บาท)</th>
                                    <th id="">สถานะการชำระ</th>
                                    <th id="">หลักฐานการชำระ</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if ($row1['mn_status'] != 100) { ?>
                                    <tr>
                                        <td><?php echo date('d / m / Y ', strtotime($row_1["mn_date_pay"])); ?> </td>
                                        <td><?php echo $row_1["mn_cost"]; ?></td>
                                        <td STYLE="color: green;">ชำระแล้ว</td>
                                        <td><a href="../file_uploads/money_dp_ad/<?php echo $row_1["mn_file"]; ?>" target="_blank" STYLE="color: green;">ดูหลักฐาน</a></td>
                                    </tr>
                                <?php } else { ?>

                                    <tr>
                                        <td>----------</td>
                                        <td>----------</td>
                                        <td STYLE="color: red;">ได้รับยกเว้น</td>
                                        <td><a STYLE="color: red;">ได้รับยกเว้น</a></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                <?php } ?>

                <br><br>

                <?php
                //ถ้าไม่ใช้สัญญาประเภทที่ 5 ถึงจะแสดง เพราะสัญญาเช่าที่ 5 ไม่มีค่าเช่าล่วงหน้า
                if ($type_le != '5') { ?>
                    <!-- le_status ต้อง == 1 || 2 || -1 || 3 ถึงแสดง-->
                    <?php if ($row_do["le_status"] == 1 or $row_do["le_status"] == 2  or $row_do["le_status"] == -1 or $row_do["le_status"] == 3) { ?>
                        <!-- ค่าเช่าล่วงหน้า -->
                        <div class="title">
                            ข้อมูลค่าเช่าล่วงหน้า
                        </div>
                        <div class="form">
                            <table id="customers">
                                <thead>
                                    <tr>
                                        <th id="">งวดที่ชำระ</th>
                                        <th id="">ว/ด/ป ที่ชำระ</th>
                                        <th id="">จำนวนเงิน (บาท)</th>
                                        <th id="">สถานะการชำระ</th>
                                        <th id="">หลักฐานการชำระ</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php if ($row2['mn_status'] != 100) { ?>
                                        <tr>
                                            <td>1</td>
                                            <td><?php echo date('d / m / Y ', strtotime($row_2["mn_date_pay"])); ?> </td>
                                            <td><?php echo $row_2["mn_cost"]; ?></td>
                                            <td STYLE="color: green;">ชำระแล้ว</td>
                                            <td><a href="../file_uploads/money_dp_ad/<?php echo $row_2["mn_file"]; ?>" target="_blank" class="" STYLE="color: green;">ดูหลักฐาน</a></td>
                                        </tr>
                                    <?php } else { ?>

                                        <tr>
                                            <td>1</td>
                                            <td>----------</td>
                                            <td>----------</td>
                                            <td STYLE="color: red;">ได้รับยกเว้น</td>
                                            <td><a STYLE="color: red;">ได้รับยกเว้น</a></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                    <?php } ?>

                    <br><br>

                    <!-- le_status ต้อง == 1 || 2 || -1 || 3 ถึงแสดง-->
                    <?php if ($row_do["le_status"] == 1 or $row_do["le_status"] == 2  or $row_do["le_status"] == -1 or $row_do["le_status"] == 3) { ?>
                        <!-- ค่าเช่ารายเดือนหรือปี-->
                        <div class="title">
                            ข้อมูลค่าเช่าราย<?php echo $type_pay; ?>
                        </div>
                        <div class="form">
                            <table id="customers">
                                <thead>
                                    <tr>
                                        <th id="">งวดที่ชำระ</th>
                                        <th id="">งวด ว/ด/ป ที่ชำระ</th>
                                        <th id="">จำนวนเงิน (บาท)</th>
                                        <th id="">สถานะการชำระเงิน</th>
                                        <th id="">ว/ด/ป ที่แนบหลักฐาน</th>
                                        <th id="">เวลา ที่แนบหลักฐาน</th>
                                        <th id="">หลักฐานการชำระ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <!-- แสดงข้อมูลการชำระเงินรายเดือน หรือ ปี -->
                                        <?php while ($row_3 = mysqli_fetch_assoc($result_3)) { ?>

                                            <!-- งวดที่ชำระ -->
                                            <td><?php echo $count_pay-- ?></td>
                                            <!-- งวด ว/ด/ป ที่ชำระ -->
                                            <td><?php echo date('d / m / Y ', strtotime($row_3["mn_date_pay"])); ?> </td>
                                            <!-- จำนวนเงิน -->
                                            <td><?php echo $row_3["mn_cost"]; ?></td>
                                            <!-- สถานะการชำระเงิน -->
                                            <?php if ($row_3["le_no_success"] == null) { ?>
                                                <td STYLE="color: red;">ยังไม่ได้ชำระ </td>
                                                <?php } else {
                                                if ($row_3["mn_status"] == 0) { ?>
                                                    <td STYLE="color: red;">ยังไม่ได้ชำระ </td>
                                                <?php } else if ($row_3["mn_status"] == 1) { ?>
                                                    <td STYLE="color: green;">ชำระแล้ว</td>
                                            <?php }
                                            } ?>
                                            <!-- ว/ด/ป ที่แนบหลักฐาน -->
                                            <?php if ($row_3['mn_date_pay_change'] != null) { ?>
                                                <td><?php echo date('d / m / Y ', strtotime($row_3["mn_date_pay_change"])); ?> </td>
                                            <?php } else { ?>
                                                <td>----------</td>
                                            <?php } ?>
                                            <!-- เวลา ที่แนบหลักฐาน -->
                                            <?php if ($row_3['mn_time_pay_change'] != null) { ?>
                                                <td><?php echo $row_3["mn_time_pay_change"]; ?></td>
                                            <?php } else { ?>
                                                <td>----------</td>
                                            <?php } ?>
                                            <!-- หลักฐานการชำระ -->
                                            <?php if ($row_3["mn_status"] == 0) { ?>
                                                <td><a class="" STYLE="color: red;">ไม่มีหลักฐาน</a></td>
                                            <?php } else if ($row_3["mn_status"] == 1) { ?>
                                                <td><a href="../file_uploads/money_month_year/<?php echo $row_3["mn_file"]; ?>" target="_blank" class="" STYLE="color: green;">ดูหลักฐาน</a></td>
                                            <?php } ?>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>

                    <?php } ?>

                <?php } ?>

            </div>
        <?php } ?>

    </main>

    <!--main.js เป็นการกดปุ่ม เพื่อเลื่อนเปิด ปิด  side bar ได้-->
    <script src="../script/main.js"></script>

</body>

</html>