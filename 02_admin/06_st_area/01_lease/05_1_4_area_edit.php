<?php {
    session_start();
    require('../../../connection.php');

    if (!$_SESSION['ofc_add']) {
        header("Location: ../../../index.php");
    }

    // รับ id พื้นที่ ที่ต้องการแก้ไข
    $area_id = $_GET['idsub'];

    //ดึงข้อมูลพื้นที่
    $sql = "SELECT * FROM area WHERE area_id = $area_id ";  
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
    <link rel="stylesheet" href="../../../style/style_edit_area.css">

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
        <div class="wrapper_e">

            <!-- Back -->
            <div>
                <a href="05_1_1_area.php" class="back" style="text-decoration:none;">
                    <span class="icon_b" style="color: blue; font-size: 25px;"><em class="fa fa-arrow-left" aria-hidden="true"></em></span>
                    <span class="name_b" style="color: blue; font-size: 20px;"><strong>BACK</strong> </span>
                </a>
            </div>

            <div class="wrapper_md_ar">
                
                <div class="title">
                    แก้ไขพื้นที่เช่า ประเภทสัญญาเพื่อร้านค้าหรือพาณิชย์
                </div>
                <div class="form">

                    <div class="inputfield">
                        <label>เลขที่พื้นที่เช่า <strong style="color:red;">*</strong></label>
                        <input type="text" class="input" value="<?php echo $row['area_no']; ?>" readonly>
                        <!-- ถ้ากดแก้ไข ให้ไปหา modal  #showForm1 เพื่อแก้ไข เลขที่พื้นที่เช่า-->
                        <a class="btn btn-warning" data-bs-target="#showForm1" data-bs-toggle="modal">แก้ไข</a>
                    </div>

                    <div class="inputfield">
                        <label>บริเวณพื้นที่เช่า <strong style="color:red;">*</strong></label>
                        <input type="text" class="input" value="<?php echo $row['area_name']; ?>" readonly>
                        <!-- ถ้ากดแก้ไข ให้ไปหา modal  #showForm2 เพื่อแก้ไข บริเวณพื้นที่เช่า-->
                        <a class="btn btn-warning" data-bs-target="#showForm2" data-bs-toggle="modal">แก้ไข</a>
                    </div>

                    <div class="inputfield">
                        <label>ขนาดพื้นที่เช่า (ตร.ม.) <strong style="color:red;">*</strong></label>
                        <input type="text" class="input" value="<?php echo $row['area_size']; ?>" readonly>
                        <!-- ถ้ากดแก้ไข ให้ไปหา modal  #showForm3 เพื่อแก้ไข ขนาดพื้นที่เช่า-->
                        <a class="btn btn-warning" data-bs-target="#showForm3" data-bs-toggle="modal">แก้ไข</a>
                    </div>

                </div>
                <br>
            </div>
        </div>

        <!--แก้ไขเลขที่พื้นที่ #showForm1-->
        <!-- เมื่อกดบันทึก ส่งข้อมูลไปที่หน้า  05_1_5_area_edit_db.php-->
        <form action="05_1_5_area_edit_db.php" method="POST">

            <!-- ส่ง id พื้นที่เช่าไปด้วย -->
            <input type="hidden" name="area_id" value="<?php echo $area_id; ?>">

            <div class="modal fade" id="showForm1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div>แก้ไขพื้นที่เช่า</div>
                            <a href="signer_page.php" class="btn-close" data-bs-dismiss="modal"></a>
                        </div>
                        <div class="modal-body">

                            <div class="wrapper_md">
                                 
                                <div class="title">
                                    แก้ไขพื้นที่เช่า
                                </div>
                                <div class="form">

                                    <div class="inputfield">
                                        <label>เลขที่พื้นที่เช่า <strong style="color:red;">*</strong></label>
                                        <input type="text" class="input" placeholder="เลขที่พื้นที่เช่า" name="area_no" required>
                                    </div>

                                </div>
                                <br>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a href="" class="btn_no_s" data-bs-dismiss="modal">ยกเลิก</a>
                            <button class="btn_yes" name="e1">บันทึกข้อมูล</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!--แก้ไขบริเวณที่เช่า #showForm2-->
        <!-- เมื่อกดบันทึก ส่งข้อมูลไปที่หน้า  05_1_5_area_edit_db.php-->
        <form action="05_1_5_area_edit_db.php" method="POST">

            <!-- ส่ง id พื้นที่เช่าไปด้วย -->
            <input type="hidden" name="area_id" value="<?php echo $area_id; ?>">

            <div class="modal fade" id="showForm2">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div>แก้ไขพื้นที่เช่า</div>
                            <a href="signer_page.php" class="btn-close" data-bs-dismiss="modal"></a>
                        </div>
                        <div class="modal-body">

                            <div class="wrapper_md">
                                
                                <div class="title">
                                    แก้ไขพื้นที่เช่า
                                </div>
                                <div class="form">

                                    <div class="inputfield">
                                        <label>บริเวณพื้นที่เช่า <strong style="color:red;">*</strong></label>
                                        <input type="text" class="input" placeholder="บริเวณพื้นที่เช่า" name="area_name" required>
                                    </div>

                                </div>
                                <br>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a href="" class="btn_no_s" data-bs-dismiss="modal">ยกเลิก</a>
                            <button class="btn_yes" name="e2">บันทึกข้อมูล</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!--แก้ไขขนาดที่เช่า #showForm3-->
        <!-- เมื่อกดบันทึก ส่งข้อมูลไปที่หน้า  05_1_5_area_edit_db.php-->
        <form action="05_1_5_area_edit_db.php" method="POST">

            <!-- ส่ง id พื้นที่เช่าไปด้วย -->
            <input type="hidden" name="area_id" value="<?php echo $area_id; ?>">

            <div class="modal fade" id="showForm3">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div>แก้ไขพื้นที่เช่า</div>
                            <a href="signer_page.php" class="btn-close" data-bs-dismiss="modal"></a>
                        </div>
                        <div class="modal-body">

                            <div class="wrapper_md">
                              
                                <div class="title">
                                    แก้ไขพื้นที่เช่า
                                </div>
                                <div class="form">

                                    <div class="inputfield">
                                        <label>ขนาดพื้นที่เช่า (ตร.ม.) <strong style="color:red;">*</strong></label>
                                        <input type="text" class="input" placeholder="ขนาดพื้นที่เช่า (ตร.ม.)" name="area_size" required>
                                    </div>

                                </div>
                                <br>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a href="" class="btn_no_s" data-bs-dismiss="modal">ยกเลิก</a>
                            <button class="btn_yes" name="e3">บันทึกข้อมูล</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </main>

    <!--js-->
    <script src="../../script/main.js"></script>

</body>

</html>