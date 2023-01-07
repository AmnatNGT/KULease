<?php {
    session_start();
    require('../../../connection.php');

    if (!$_SESSION['ofc_add']) {
        header("Location: ../../../index.php");
    }

    // ดึงข้อมูลพื้นที่เช่า ที่เป็นของประเภทสัญญาที่ 2
    $sql = "SELECT * FROM area WHERE area_type = 2 AND area_status_show = 1 ORDER BY area_name ASC";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    $order = 1;
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
                สถานะพื้นที่เช่า ประเภทสัญญาเพื่องานบริการ
            </div>

            <a href="" class="btn_add_area" data-bs-target="#showForm1" data-bs-toggle="modal">เพิ่มพื้นที่เช่า</a>
            <br><br>

            <div class="form">

                <table id="customers">
                    <thead>
                        <tr>
                            <th id="">ลำดับที่</th>
                            <th id="">เลขที่พื้นที่เช่า</th>
                            <th id="">บริเวณพื้นที่เช่า</th>
                            <th id="">ขนาดพื้นที่เช่า (ตร.ม.)</th>
                            <th id="">กว้าง X ยาว (ม.)</th>
                            <th id="">สถานะพื้นที่เช่า</th>
                            <th id="">แก้ไข/ลบ</th>

                        </tr>
                    </thead>

                    <tbody>
                        <!-- แสดงข้อมูล -->
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $order++; ?></td>
                                <td> <?php echo $row["area_no"] ?> </td>
                                <td> <?php echo $row["area_name"] ?> </td>
                                <td> <?php echo $row["area_size"] ?> </td>

                                <td><?php echo $row["area_width"] . " X " . $row["area_height"] ?></td>

                                <!-- สถานะพื้นที่เช่า -->
                                <?php if ($row["area_status"] == '0') { ?>
                                    <td STYLE="color: green;"> ว่าง </td>
                                <?php } else if ($row["area_status"] == '1') { ?>
                                    <td STYLE="color: #C39D01;"> จองแล้ว </td>
                                <?php } else if ($row["area_status"] == '2') { ?>
                                    <td STYLE="color: red;"> ไม่ว่าง </td>
                                <?php } ?>

                                <!-- แก้ไข/ลบ -->
                                <!-- ถ้า พื้นที่เป็นสถานะว่าง ถึง ลบ หรือแก้ไขได้ -->
                                <?php if ($row["area_status"] == '0') { ?>
                                    <td>
                                        <!-- กดแก้ไข ไปหน้า 05_2_4_area_edit.php ส่ง id พื้นที่ -->
                                        <a href="05_2_4_area_edit.php?idsub=<?php echo $row["area_id"] ?>" class="btn btn-warning">แก้ไข</a>
                                        <!-- กดลบ ไปหน้า 05_2_3_delete_area_db.php ส่ง id พื้นที่ -->
                                        <a href="05_2_3_delete_area_db.php?idsub=<?php echo $row["area_id"] ?>" class="btn btn-danger" onclick="return confirm('ยืนยันการลบ')">ลบ</a>
                                    </td>
                                <?php }
                                // ถ้า ไม่ว่างแก้ไขไม่ได้
                                else { ?>
                                    <td>
                                        <a class="btn btn-secondary">แก้ไข</a>
                                        <a class="btn btn-secondary">ลบ</a>
                                    </td>
                                <?php } ?>


                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>

        <!--เพิ่มพื้นที่เช่า-->
        <!-- ส่งข้อมูลไปหน้า 05_2_2_area_add_db.php -->
        <form action="05_2_2_area_add_db.php" method="POST">

            <div class="modal fade" id="showForm1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div>เพิ่มพื้นที่เช่า ประเภทสัญญาเพื่องานบริการ</div>
                            <a href="signer_page.php" class="btn-close" data-bs-dismiss="modal"></a>
                        </div>
                        <div class="modal-body">

                            <div class="wrapper_md">

                                <div class="title">
                                    เพิ่มพื้นที่เช่า
                                </div>
                                <div class="form">

                                    <div class="inputfield">
                                        <label>เลขที่พื้นที่เช่า </label>
                                        <input type="text" class="input" placeholder="เลขที่พื้นที่เช่า" name="area_no" >
                                    </div>

                                    <div class="inputfield">
                                        <label>บริเวณพื้นที่เช่า <strong style="color:red;">*</strong></label>
                                        <input type="text" class="input" placeholder="บริเวณพื้นที่เช่า" name="area_name" required>
                                    </div>

                                    <div class="inputfield">
                                        <label>ขนาดพื้นที่เช่า (ตร.ม.) <strong style="color:red;">*</strong></label>
                                        <input type="text" class="input" placeholder="ขนาดพื้นที่เช่า (ตร.ม.)" name="area_size" required>
                                    </div>

                                    <div class="inputfield">
                                        <label>ขนาดความกว้าง (เมตร) <strong style="color:red;">*</strong></label>
                                        <input type="text" class="input" placeholder="ขนาดความกว้าง (เมตร)" name="area_width" required>
                                    </div>

                                    <div class="inputfield">
                                        <label>ขนาดความยาว (เมตร) <strong style="color:red;">*</strong></label>
                                        <input type="text" class="input" placeholder="ขนาดความยาว (เมตร)" name="area_height" required>
                                    </div>

                                </div>
                                <br>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a href="" class="btn_no_s" data-bs-dismiss="modal">ยกเลิก</a>
                            <button class="btn_yes" name="save_area">บันทึกข้อมูล</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </main>

    <!--js-->
    <script src="../../../script/main.js"></script>

</body>

</html>