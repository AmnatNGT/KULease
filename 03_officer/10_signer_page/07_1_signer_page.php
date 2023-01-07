<?php {
    session_start();

    if (!$_SESSION['ofc_add']) {
        header("Location: ../../index.php");
    }

    require('../../connection.php');

    //พยานของผู้เช่า
    $sql3 = "SELECT * FROM witness WHERE wn_status_show='1' AND chk_in_out_wn =  'out' AND wn_status_otp = 1";
    $result3 = mysqli_query($conn, $sql3);
    $count3 = mysqli_num_rows($result3);
    $order3 = 1; //เก็บลำดับที่

    //เรียกจังหวัด
    $sql_pro = "SELECT * FROM provinces ORDER BY name_th";
    $query_pro = mysqli_query($conn, $sql_pro);
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
        <span class="name"><strong>( เจ้าหน้าที่ เพิ่มสัญญาเช่า )</strong></span>
    </div>

    <!--Componants-->
    <main class="data ">
        <div class="wrapper_2">

            <br><br>
            <!--พยานของผู้เช่า-->
            <div class="title">
                รายชื่อพยาน (บุคคลทั่วไป)
            </div>
            <div class="form">
                <table id="customers">
                    <thead>
                        <tr>
                            <th id="">ลำดับที่</th>
                            <th id="">ชื่อ - สกุล</th>
                            <th id="">ตำแหน่ง</th>
                            <th id="">เบอร์โทรศัพท์</th>
                            <th id="">บัตรประชาชน</th>
                            <th id="">ข้อมูลส่วนตัว</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- แสดงข้อมูลพยาน -->
                        <?php while ($row3 = mysqli_fetch_assoc($result3)) { ?>
                            <tr>
                                <!-- ลำดับที่ -->
                                <td><?php echo $order3++; ?></td>
                                <!-- ชื่อ - สกุล -->
                                <td><?php echo  $row3["wn_p_name"] . " " . $row3["wn_f_name"] . " " . $row3["wn_l_name"]; ?></td>

                                <!-- ตำแหน่ง -->
                                <?php if ($row3['wn_role'] == null) { ?>
                                    <td style="color: red;">ไม่มีตำแหน่ง</td>
                                <?php } else { ?>
                                    <td><?php echo $row3["wn_role"] ?></td>
                                <?php } ?>

                                <!-- เบอร์โทรศัพท์ -->
                                <td><?php echo $row3['wn_phone'] ?></td>

                                <!-- บัตรประชาชน -->
                                <?php if ($row3['wn_id_card'] == null) { ?>
                                    <td> <a class="btn_gray">ดูบัตรประชาชน</a> </td>
                                <?php } else { ?>
                                    <td> <a href="../../file_uploads/witness_id_card/<?php echo $row3['wn_id_card']; ?>" target="_blank" class="btn_pri">ดูบัตรประชาชน</a> </td>
                                <?php } ?>

                                <!-- ข้อมูลส่วนตัว -->
                                <td><a href="07_10_see_data_wn.php?wn_id=<?php echo $row3["wn_id"]; ?>" class="btn_pri"> ดูข้อมูล</td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>

            <!--เพิ่มพยานทั่วไป-->
            <!-- เมื่อกดบันทึกไปหน้า เอาข้อมูลไปหน้า 07_5_signer_add_db.php -->
            <form action="07_5_signer_add_db.php" method="POST" enctype="multipart/form-data">

                <!-- ปุ่ม เพิ่มพยานทั่วไป-->
                <a href="" class="btn_yes" style="float: right;" data-bs-target="#showForm3" data-bs-toggle="modal">เพิ่มข้อมูลพยาน บุคคลทั่วไป</a>

                <!-- Modal เพิ่มข้อมูลพยาน -->
                <div class="modal fade" id="showForm3">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div>เพิ่มข้อมูลบุคคลทั่วไป</div>
                                <a href="signer_page.php" class="btn-close" data-bs-dismiss="modal"></a>
                            </div>
                            <div class="modal-body">

                                <div class="wrapper_md">

                                    <div class="title">
                                        เพิ่มข้อมูลบุคคลทั่วไป
                                    </div>
                                    <div class="form">
                                        <!-- คำนำหน้าชื่อ -->
                                        <div class="inputfield">
                                            <label>คำนำหน้าชื่อ <strong style="color:red;">*</strong>
                                            </label>
                                            <div class="custom_select">
                                                <select name="wn_p_name" id="tn_p_name_2" onchange="type_name_4()" required>
                                                    <option value="" hidden>เลือกคำนำหน้าชื่อ</option>
                                                    <option value="n1">นาย</option>
                                                    <option value="n2">นาง</option>
                                                    <option value="n3">นางสาว</option>
                                                    <option value="n4">อื่นๆ : เช่น ศ. / รศ.</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!--เลือกอื่นๆ JS-->
                                        <script>
                                            function type_name_4() {
                                                index = document.getElementById('tn_p_name_2').value;
                                                if (index == 'n4') {
                                                    document.getElementById('tn_p_name_oth_2').style.display = '';
                                                } else {
                                                    document.getElementById('tn_p_name_oth_2').style.display = 'none';
                                                }

                                            }
                                        </script>
                                        <!-- คำนำหน้าชื่ออื่นๆ -->
                                        <div class="inputfield" id="tn_p_name_oth_2" style="display:none">
                                            <label>คำนำหน้าชื่อ อื่นๆ <strong style="color:red;">*</strong>
                                            </label>
                                            <input type="text" class="input" placeholder="คำนำหน้าชื่อ อื่นๆ : เช่น ศ. / รศ." name="wn_p_name_oth">
                                        </div>

                                        <div class="inputfield">
                                            <label>ชื่อ <strong style="color:red;">*</strong>
                                            </label>
                                            <input type="text" class="input" placeholder="ชื่อ" name="wn_f_name" required>
                                        </div>

                                        <div class="inputfield">
                                            <label>นามสกุล <strong style="color:red;">*</strong>
                                            </label>
                                            <input type="text" class="input" placeholder="นามสกุล" name="wn_l_name" required>
                                        </div>

                                        <div class="inputfield">
                                            <label>ตำแหน่ง (ถ้ามี)</label>
                                            <input type="text" class="input" placeholder="ตำแหน่ง" name="wn_role">
                                        </div>

                                        <div class="inputfield">
                                            <label>เบอร์โทรศัพท์ <strong style="color:red;">*</strong>
                                            </label>
                                            <input type="tel" class="input" placeholder="เบอร์โทรศัพท์" name="wn_phone" required maxlength="10">
                                        </div>

                                        <div class="inputfield">
                                            <label>อีเมล <strong style="color:red;">*</strong>
                                            </label>
                                            <input type="email" class="input" placeholder="อีเมล" name="wn_email" required>
                                        </div>

                                        <div class="inputfield">
                                            <label>บัตรประจำตัวประชาชน (รูปภาพ/PDF) <strong style="color:red;">*</strong>
                                            </label>
                                            <input type="file" class="input" placeholder="อีเมล" name="file_1" accept=".png, .jpg, .jpeg, .pdf" required>
                                        </div>
                                    </div>
                                    <br>

                                    <div class="title">
                                        เพิ่มข้อมูลที่อยู่
                                    </div>
                                    <div class="form">

                                        <div class="inputfield">
                                            <label>บ้านเลขที่ <strong style="color:red;">*</strong></label>
                                                    <input type="text" class="input" placeholder="บ้านเลขที่" name="wn_no" required>
                                        </div>

                                        <div class="inputfield">
                                            <label>ซอย <strong style="color:red;">*</strong></label>
                                                    <input type="text" class="input" placeholder="ซอย" name="wn_alley">
                                        </div>

                                        <div class="inputfield">
                                            <label>หมู่ <strong style="color:red;">*</strong></label>
                                                    <input type="text" class="input" placeholder="หมู่" name="wn_moo">
                                        </div>

                                        <div class="inputfield">
                                            <label>จังหวัด <strong style="color:red;">*</strong></label>
                                            <div class="custom_select">
                                                <select name="wn_province" id="provinces_2" required>
                                                    <option value="" hidden>เลือกจังหวัด</option>

                                                    <?php foreach ($query_pro as $value) { ?>
                                                        <option value="<?= $value['id'] ?>"> <?= $value['name_th'] ?> </option>
                                                    <?php } ?>

                                                </select>
                                            </div>
                                        </div>

                                        <div class="inputfield">
                                            <label>อำเภอ/เขต <strong style="color:red;">*</strong></label>
                                            <div class="custom_select">
                                                <select name="wn_district" id="amphures_2" required>
                                                    <option value="" hidden>เลือกอำเภอ/เขต</option>

                                                </select>
                                            </div>
                                        </div>

                                        <div class="inputfield">
                                            <label>ตำบล/แขวง <strong style="color:red;">*</strong></label>
                                            <div class="custom_select">
                                                <select name="wn_canton" id="districts_2" required>
                                                    <option value="" hidden>เลือกตำบล/แขวง</option>

                                                </select>
                                            </div>
                                        </div>

                                        <!--หาตัวเลือกจังหวัด-->
                                        <script src="../../script/jquery.min.js"></script>
                                        <script type="text/javascript">
                                            $('#provinces_2').change(function() {
                                                var id_provinces = $(this).val();

                                                $.ajax({
                                                    type: "POST",
                                                    url: "../02_add_lease/find/find_provinces_db.php",
                                                    data: {
                                                        id: id_provinces,
                                                        function: 'provinces'
                                                    },
                                                    success: function(data) {
                                                        $('#amphures_2').html(data);
                                                    }
                                                });
                                            });

                                            $('#amphures_2').change(function() {
                                                var id_amphures = $(this).val();

                                                $.ajax({
                                                    type: "POST",
                                                    url: "../02_add_lease/find/find_provinces_db.php",
                                                    data: {
                                                        id: id_amphures,
                                                        function: 'amphures'
                                                    },
                                                    success: function(data) {
                                                        $('#districts_2').html(data);
                                                    }
                                                });
                                            });
                                        </script>

                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <a href="07_1_signer_page.php" class="btn_no_s" data-bs-dismiss="modal">ยกเลิก</a>
                                <button class="btn_yes" name="save_2" onclick="return confirm('ยืนยันการเพิ่มข้อมูล')">บันทึกข้อมูล</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <br><br><br>

        </div>





    </main>

    <!--js-->
    <script src="../../script/main.js"></script>

</body>

</html>