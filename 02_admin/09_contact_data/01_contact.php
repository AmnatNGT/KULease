<?php {
    require("../../connection.php");
    session_start();

    if (!$_SESSION['ofc_add']) {
        header("Location: ../../index.php");
    }

    //ดึงข้อมูล contact
    $sql_c = "SELECT * FROM contact WHERE c_st_use = 1";  
    $result_c = mysqli_query($conn, $sql_c);  
    $row_c = mysqli_fetch_assoc($result_c);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบบริหารสัญญาเช่า</title>
    <link rel="shortcut icon" href="../../style/ku.png" type="image/x-icon" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script src="../../script/jquery-3.6.0.js"></script>

    <!--Icon-->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="../../style/style_navi_bg.css">
    <link rel="stylesheet" href="../../style/style_btn.css">
    <link rel="stylesheet" href="../../style/style_bar_name.css">

    <!-- wrapper3 -->
    <style>
        /* wrapper เพิ่มข้อมูล */
        .wrapper_3 {
            max-width: 1000px;
            width: 100%;
            background: #fff;
            margin: 20px auto;
            box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.125);
            padding: 30px;
        }

        .wrapper_3 .title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 25px;
            color: var(--first-color);
            text-transform: uppercase;
            text-align: center;
        }

        .wrapper_3 .form {
            width: 100%;
        }

        .wrapper_3 .form .inputfield {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .wrapper_3 .form .inputfield label {
            width: 200px;
            color: #757575;
            margin-right: 10px;
            font-size: 14px;
        }

        .wrapper_3 .form .inputfield .input {
            width: 100%;
            outline: none;
            border: 1px solid #d5dbd9;
            font-size: 15px;
            padding: 8px 10px;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .wrapper_3 .form .inputfield .custom_select {
            position: static;
            width: 100%;
            height: 37px;
        }

        .wrapper_3 .form .inputfield .custom_select select {
            outline: none;
            width: 100%;
            height: 100%;

            padding: 8px 10px;
            font-size: 15px;
            border: 1px solid #d5dbd9;
            border-radius: 3px;
        }

        .wrapper_3 .form .inputfield .input:focus,
        .wrapper_3 .form .inputfield .textarea:focus,
        .wrapper_3 .form .inputfield .custom_select select:focus {
            border: 1px solid var(--first-color);
        }

        .wrapper_3 .form .inputfield p {
            font-size: 14px;
            color: #757575;
        }

        .wrapper_3 .form .inputfield .btn {
            width: 30%;
            padding: 8px 10px;
            font-size: 15px;
            border: 0px;
            background: var(--first-color);
            color: #fff;
            cursor: pointer;
            border-radius: 3px;
            outline: none;
            float: right;
            margin-left: 5px;
        }

        .wrapper_3 .form .inputfield .btn:hover {
            background: var(--first-color-light);
        }

        .wrapper_3 .form .inputfield:last-child {
            margin-bottom: 0;
        }
    </style>

    <!-- btn_pdf -->
    <style>
        .btn_pdf {
            width: 100%;
            padding: 8px 410px;
            font-size: 15px;
            border: 0px;
            background: #ff6600;
            color: #fff;
            cursor: pointer;
            border-radius: 3px;
            outline: none;

        }

        .btn_pdf:hover {
            background: #464660;
            color: #fff;
        }
    </style>


</head>

<body>

    <?php
    //header
    include('../header_ofc.php');

    //side bar
    include('../sidebar_ofc.php');
    ?>

    <!-- ชื่อเจ้าหน้าที่ -->
    <div class="bar_name">
        <span class="icon_name"><em class="fa fa-user-circle-o" aria-hidden="true"></em></span>
        <span class="name"><?php echo $_SESSION['ofc_add_name']; ?></span> <br>
        <span class="name"><strong>( ผู้ดูแลระบบ )</strong></span>
    </div>

    <!--ข้อมูล-->
    <main class="data ">

        <div class="wrapper_3">

            <div class="title">
                ข้อมูลสัญญาเช่าติดต่อเจ้าหน้าที่
            </div>

            <div class="form">

                <!-- Email เจ้าหน้าที่ -->
                <div class="inputfield">
                    <label>Email เจ้าหน้าที่</label>
                    <a href="#" class="input"><?php echo $row_c['c_email_admin']; ?></a>

                    <a href="" class="btn" data-bs-target="#showForm1" data-bs-toggle="modal">แก้ไข</a>
                </div>
                <!--แก้ไข Email เจ้าหน้าที่-->
                <form action="02_contact_db.php" method="POST">

                    <div class="modal fade" id="showForm1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div>แก้ไขข้อมูล</div>
                                    <a href="" class="btn-close" data-bs-dismiss="modal"></a>
                                </div>
                                <div class="modal-body">

                                    <div class="wrapper_md">
                                        <!-- เพิ่มข้อมูลเจ้าหน้าที่ -->
                                        <div class="title">
                                            แก้ไข Email เจ้าหน้าที่
                                        </div>
                                        <div class="form">

                                            <div class="inputfield">
                                                <label>Email เจ้าหน้าที่ <strong style="color: red;">*</strong></label>
                                                <input type="text" class="input" placeholder="Email เจ้าหน้าที่" name="email1" required>
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

                <!-- Email กองบริหารทรัพย์สิน -->
                <div class="inputfield">
                    <label>Email กองบริหารทรัพย์สิน</label>
                    <a href="#" class="input"><?php echo $row_c['c_email_agc']; ?></a>

                    <a href="" class="btn" data-bs-target="#showForm2" data-bs-toggle="modal">แก้ไข</a>

                </div>
                <!--แก้ไข Email กองบริหารทรัพย์สิน-->
                <form action="02_contact_db.php" method="POST">

                    <div class="modal fade" id="showForm2">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div>แก้ไขข้อมูล</div>
                                    <a href="" class="btn-close" data-bs-dismiss="modal"></a>
                                </div>
                                <div class="modal-body">

                                    <div class="wrapper_md">
                                        <!-- เพิ่มข้อมูลเจ้าหน้าที่ -->
                                        <div class="title">
                                            แก้ไข Email กองบริหารทรัพย์สิน
                                        </div>
                                        <div class="form">

                                            <div class="inputfield">
                                                <label>Email กองบริหารทรัพย์สิน <strong style="color: red;">*</strong></label>
                                                <input type="text" class="input" placeholder="Email กองบริหารทรัพย์สิน" name="email2" required>
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

                <!-- Facebook กองบริหารทรัพย์สิน -->
                <div class="inputfield">
                    <label>Facebook กองบริหารทรัพย์สิน</label>
                    <a href="<?php echo $row_c['c_facebook']; ?>" class="input" target="_blank" rel="noopener">กองบริหารทรัพย์สิน กำแพงแสน</a>

                    <a href="" class="btn" data-bs-target="#showForm3" data-bs-toggle="modal">แก้ไข</a>
                </div>
                <!--แก้ไข Facebook กองบริหารทรัพย์สิน-->
                <form action="02_contact_db.php" method="POST">

                    <div class="modal fade" id="showForm3">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div>แก้ไขข้อมูล</div>
                                    <a href="" class="btn-close" data-bs-dismiss="modal"></a>
                                </div>
                                <div class="modal-body">

                                    <div class="wrapper_md">
                                        <!-- เพิ่มข้อมูลเจ้าหน้าที่ -->
                                        <div class="title">
                                            แก้ไข Link Facebook กองบริหารทรัพย์สิน
                                        </div>
                                        <div class="form">

                                            <div class="inputfield">
                                                <label>Link Facebook กองบริหารทรัพย์สิน <strong style="color: red;">*</strong></label>
                                                <input type="text" class="input" placeholder="Link Facebook กองบริหารทรัพย์สิน" name="fb" required>
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


                <!-- โทรศัพท์ -->
                <div class="inputfield">
                    <label>โทรศัพท์</label>
                    <input type="text" class="input" value="<?php echo $row_c['c_tel']; ?>" readonly>

                    <a href="" class="btn" data-bs-target="#showForm4" data-bs-toggle="modal">แก้ไข</a>
                </div>
                <!--แก้ไข โทรศัพท์-->
                <form action="02_contact_db.php" method="POST">

                    <div class="modal fade" id="showForm4">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div>แก้ไขข้อมูล</div>
                                    <a href="" class="btn-close" data-bs-dismiss="modal"></a>
                                </div>
                                <div class="modal-body">

                                    <div class="wrapper_md">
                                        <!-- เพิ่มข้อมูลเจ้าหน้าที่ -->
                                        <div class="title">
                                            แก้ไขเบอร์โทรศัพท์
                                        </div>
                                        <div class="form">

                                            <div class="inputfield">
                                                <label>เบอร์โทรศัพท์ <strong style="color: red;">*</strong></label>
                                                <input type="text" class="input" placeholder="เบอร์โทรศัพท์" name="tel" required>
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

            </div>

            <br>

            <div class="title">
                ที่อยู่หน่วยงาน
            </div>

            <div class="form">

                <!--เลขที่-->
                <div class="inputfield">
                    <label>เลขที่</label>
                    <input type="text" class="input" value="<?php echo $row_c['c_ad_no']; ?>" readonly>

                    <a href="" class="btn" data-bs-target="#showForm5" data-bs-toggle="modal">แก้ไข</a>
                </div>
                <!--แก้ไขเลขที่-->
                <form action="02_contact_db.php" method="POST">

                    <div class="modal fade" id="showForm5">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div>แก้ไขข้อมูล</div>
                                    <a href="" class="btn-close" data-bs-dismiss="modal"></a>
                                </div>
                                <div class="modal-body">

                                    <div class="wrapper_md">
                                        <div class="title">
                                            แก้ไขบ้านเลขที่
                                        </div>
                                        <div class="form">

                                            <div class="inputfield">
                                                <label>บ้านเลขที่ <strong style="color: red;">*</strong></label>
                                                <input type="text" class="input" placeholder="บ้านเลขที่" name="no" required>
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

                <!--หมู่ที่-->
                <div class="inputfield">
                    <label>หมู่ที่</label>
                    <input type="text" class="input" value="<?php echo $row_c['c_ad_moo']; ?>" readonly>

                    <a href="" class="btn" data-bs-target="#showForm6" data-bs-toggle="modal">แก้ไข</a>
                </div>
                <!--แก้ไข หมู่ที่-->
                <form action="02_contact_db.php" method="POST">

                    <div class="modal fade" id="showForm6">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div>แก้ไขข้อมูล</div>
                                    <a href="" class="btn-close" data-bs-dismiss="modal"></a>
                                </div>
                                <div class="modal-body">

                                    <div class="wrapper_md">
                                        <div class="title">
                                            แก้ไขหมู่ที่
                                        </div>
                                        <div class="form">

                                            <div class="inputfield">
                                                <label>หมู่ที่ <strong style="color: red;">*</strong></label>
                                                <input type="text" class="input" placeholder="หมู่ที่" name="moo" required>
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

                <!--ตำบล/แขวง-->
                <div class="inputfield">
                    <label>ตำบล/แขวง</label>
                    <input type="text" class="input" value="<?php echo $row_c['c_ad_canton']; ?>" readonly>

                    <a href="" class="btn" data-bs-target="#showForm7" data-bs-toggle="modal">แก้ไข</a>
                </div>
                <!--แก้ไข ตำบล/แขวง-->
                <form action="02_contact_db.php" method="POST">

                    <div class="modal fade" id="showForm7">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div>แก้ไขข้อมูล</div>
                                    <a href="" class="btn-close" data-bs-dismiss="modal"></a>
                                </div>
                                <div class="modal-body">

                                    <div class="wrapper_md">
                                        <div class="title">
                                            แก้ไข ตำบล/แขวง
                                        </div>
                                        <div class="form">

                                            <div class="inputfield">
                                                <label>ตำบล/แขวง <strong style="color: red;">*</strong></label>
                                                <input type="text" class="input" placeholder="ตำบล/แขวง" name="ct" required>
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

                <!--อำเภอ/เขต-->
                <div class="inputfield">
                    <label>อำเภอ/เขต</label>
                    <input type="text" class="input" value="<?php echo $row_c['c_ad_district']; ?>" readonly>

                    <a href="" class="btn" data-bs-target="#showForm8" data-bs-toggle="modal">แก้ไข</a>
                </div>
                <!--แก้ไข อำเภอ/เขต-->
                <form action="02_contact_db.php" method="POST">

                    <div class="modal fade" id="showForm8">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div>แก้ไขข้อมูล</div>
                                    <a href="" class="btn-close" data-bs-dismiss="modal"></a>
                                </div>
                                <div class="modal-body">

                                    <div class="wrapper_md">
                                        <div class="title">
                                            แก้ไข อำเภอ/เขต
                                        </div>
                                        <div class="form">

                                            <div class="inputfield">
                                                <label>อำเภอ/เขต <strong style="color: red;">*</strong></label>
                                                <input type="text" class="input" placeholder="อำเภอ/เขต" name="dt" required>
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

                <!--จังหวัด-->
                <div class="inputfield">
                    <label>จังหวัด</label>
                    <input type="text" class="input" value="<?php echo $row_c['c_ad_province']; ?>" readonly>

                    <a href="" class="btn" data-bs-target="#showForm9" data-bs-toggle="modal">แก้ไข</a>
                </div>
                <!--แก้ไข จังหวัด-->
                <form action="02_contact_db.php" method="POST">

                    <div class="modal fade" id="showForm9">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div>แก้ไขข้อมูล</div>
                                    <a href="" class="btn-close" data-bs-dismiss="modal"></a>
                                </div>
                                <div class="modal-body">

                                    <div class="wrapper_md">
                                        <div class="title">
                                            แก้ไข จังหวัด
                                        </div>
                                        <div class="form">

                                            <div class="inputfield">
                                                <label>จังหวัด <strong style="color: red;">*</strong></label>
                                                <input type="text" class="input" placeholder="จังหวัด" name="pv" required>
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

                <!--รหัสไปรษณีย์-->
                <div class="inputfield">
                    <label>รหัสไปรษณีย์</label>
                    <input type="text" class="input" value="<?php echo $row_c['c_ad_post_ofc']; ?>" readonly>

                    <a href="" class="btn" data-bs-target="#showForm10" data-bs-toggle="modal">แก้ไข</a>
                </div>
                <!--แก้ไข รหัสไปรษณีย์-->
                <form action="02_contact_db.php" method="POST">

                    <div class="modal fade" id="showForm10">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div>แก้ไขข้อมูล</div>
                                    <a href="" class="btn-close" data-bs-dismiss="modal"></a>
                                </div>
                                <div class="modal-body">

                                    <div class="wrapper_md">
                                        <div class="title">
                                            แก้ไข รหัสไปรษณีย์
                                        </div>
                                        <div class="form">

                                            <div class="inputfield">
                                                <label>รหัสไปรษณีย์ <strong style="color: red;">*</strong></label>
                                                <input type="text" class="input" placeholder="รหัสไปรษณีย์" name="psc" required>
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

            </div>
            <br>


        </div>


    </main>

    <!--js-->
    <script src="../../script/main.js"></script>



</body>

</html>