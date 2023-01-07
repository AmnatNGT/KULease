<?php {
    session_start();

    if (!$_SESSION['ofc_spcl']) {
        header("Location: ../../../index.php");
    }

    require('../../../connection.php');

    // รับ id สัญญาเช่า
    $id = $_GET["le_id"];

    //ดึงข้อมูลจาก table lease ที่ตรงกับ id สัญญาเช่าที่รับมา
    $sql = "SELECT * FROM lease WHERE le_id = $id ";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    $row = mysqli_fetch_assoc($result);

    //หาข้อมูล tenant
    $sql_ch = "SELECT * FROM lease l ,tenant t
                       WHERE l.tn_id = t.tn_id 
                       AND l.le_id = $id";
    $result_ch = mysqli_query($conn, $sql_ch);
    $row_ch = mysqli_fetch_assoc($result_ch);

    //หาข้อมูลพื้นที่
    $sql_area = "SELECT * FROM area WHERE area_type = '3' AND area_status_show = 1 AND area_status = '0' ";
    $result_area = mysqli_query($conn, $sql_area);

    //เรียกจังหวัด
    $sql_pro = "SELECT * FROM provinces ORDER BY name_th";
    $query_pro = mysqli_query($conn, $sql_pro);

    //ผู้ให้เช่า
    $q = "SELECT * FROM lessor WHERE ls_status_show = 1";
    $result_q = mysqli_query($conn, $q);
    $row_q = mysqli_fetch_assoc($result_q);
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

    <!-- style ตรงกลาง -->
    <link rel="stylesheet" href="../../../style/wp_3_add_le.css">


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
        <span class="name"><?php echo $_SESSION['ofc_spcl_name']; ?></span> <br>
        <span class="name"><strong>( เจ้าหน้าที่ พิเศษ )</strong></span>
    </div>

    <!--ข้อมูล-->
    <!--ข้อมูล-->
    <main class="data ">

        <div class="wrapper_3">

            <!-- Back -->
            <div>
                <a href="../02_2_type.php?le_id=<?php echo $id ?>" class="back" style="text-decoration:none;">
                    <span class="icon_b" style="color: blue; font-size: 25px;"><em class="fa fa-arrow-left" aria-hidden="true"></em></span>
                    <span class="name_b" style="color: blue; font-size: 20px;"><strong>BACK</strong> </span>
                </a>
            </div>

            <div class="title">
                เพิ่มสัญญาเช่า <br> เลขที่อ้างอิงสัญญาเช่า : <?php echo $row["le_no"]; ?>
            </div>

            <div class="form">

                <div class="inputfield" id="tn_p_name_oth">
                    <label>ประเภทสัญญาเช่า</label>
                    <input type="text" class="input" value="เพื่องานวิจัย / การเรียนการสอน" readonly>
                </div>
            </div>
            <br>

            <!-- เพิ่มข้อมูลและส่งไปหน้า 02_4_type_3_2_db.php แบบ POST -->
            <form action="02_4_type_3_2_db.php" method="POST" enctype="multipart/form-data">

                <!-- ส่ง id สัญญาเช่าไปด้วย -->
                <input name="le_id" id="le_id" type="hidden" value="<?php echo $id; ?>">

                <!-- แสดงข้อมูล -->
                <div class="title">
                    ตรวจสอบข้อมูลผู้ให้เช่า
                </div>
                <div class="form">
                    <div class="inputfield">
                        <label>ชื่อ - นามสกุล ผู้ให้เช่า</label>
                        <input type="text" class="input" value="<?php echo $row_q['ls_p_name'] . " " . $row_q['ls_f_name'] . " " . $row_q['ls_l_name']; ?>" readonly>
                    </div>

                    <div class="inputfield">
                        <label>ตำแหน่ง ผู้ให้เช่า</label>
                        <input type="text" class="input" value="<?php echo $row_q['ls_role']; ?>" readonly>
                    </div>
                </div>

                <br>
                <div class="title">
                    ตรวจสอบข้อมูลผู้เช่า
                </div>
                <div class="form">
                    <!-- ผู้เช่า -->
 

                    <div class="inputfield">
                        <label>บริษัท/หน่วยงาน/ภาควิชา/ศูนย์</label>
                        <?php
                        if ($row_ch['tn_company'] == null) {
                            $tn_cpn = "----------";
                        } else {
                            $tn_cpn = $row_ch['tn_company'];
                        }
                        ?>
                        <input type="text" class="input" value="<?php echo $tn_cpn; ?>" readonly>
                    </div>
                    
                    <div class="inputfield">
                        <label>ชื่อ - นามสกุล ผู้เช่า</label>
                        <input type="text" class="input" value="<?php echo $row_ch['tn_p_name'] . " " . $row_ch['tn_f_name'] . " " . $row_ch['tn_l_name']; ?>" readonly>
                    </div>

                    <div class="inputfield">
                        <label>ตำแหน่ง ผู้เช่า</label>
                        <?php
                        if ($row_ch['tn_role'] == null) {
                            $tn_role = "----------";
                        } else {
                            $tn_role = $row_ch['tn_role'];
                        }
                        ?>
                        <input type="text" class="input" value="<?php echo $tn_role; ?>" readonly>
                    </div>

                    <div class="inputfield">
                        <label>ที่อยู่ ผู้เช่า</label>

                        <?php if ($row_ch['tn_road'] == null) {
                            $road = "---";
                        } else {
                            $road = $row_ch['tn_road'];
                        }
                        ?>
                        <input type="text" class="input" value="<?php echo "บ้านเลขที่ : " . $row_ch['tn_house_no'] . " , หมู่ : " . $row_ch['tn_moo'] .
                                                                    " , ถนน : " . $road . " , ตำบล/แขวง : " . $row_ch['tn_canton'] .
                                                                    " , อำเภอ/เขต : " . $row_ch['tn_district'] . " , จังหวัด : " . $row_ch['tn_province'];
                                                                ?>" readonly>
                    </div>

                </div>
                <br>

                <!-- กรอกข้อมูล -->
                <div class="title">
                    เพิ่มข้อมูลสัญญาเช่า
                </div>
                <div class="form">

                    <div class="title">ข้อมูลหน่วยงาน</div>

                    <div class="inputfield">
                        <label>ชื่อหน่วยงาน <strong>*</strong></label>
                        <input type="text" class="input" name="agc_name" placeholder="ชื่อหน่วยงาน" autofocus required>
                    </div>

                    <div class="inputfield">
                        <label>ที่อยู่หน่วยงาน เลขที่ <strong>*</strong></label>
                        <input type="text" class="input" name="agc_no" placeholder="เลขที่" required>
                    </div>

                    <div class="inputfield">
                        <label>ที่อยู่หน่วยงาน ถนน</label>
                        <input type="text" class="input" name="agc_road" placeholder="ถนน">
                    </div>

                    <div class="inputfield">
                        <label>ที่อยู่หน่วยงาน จังหวัด <strong>*</strong></label>
                        <select name="agc_province" id="provinces" class="form-select" required>
                            <option value="" hidden>เลือกจังหวัด</option>
                            <?php foreach ($query_pro as $value) { ?>
                                <option value="<?= $value['id'] ?>"> <?= $value['name_th'] ?> </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="inputfield">
                        <label>ที่อยู่หน่วยงาน อำเภอ / เขต <strong>*</strong></label>
                        <select name="agc_district" id="amphures" class="form-select" required>
                            <option value="" hidden>เลือกอำเภอ/เขต</option>
                        </select>
                    </div>

                    <div class="inputfield">
                        <label>ที่อยู่หน่วยงาน ตำบล / แขวง <strong>*</strong></label>
                        <select name="agc_canton" id="districts" class="form-select" required>
                            <option value="" hidden>เลือกตำบล/แขวง</option>
                        </select>
                    </div>

                    <!--Script หาตัวเลือกจังหวัด-->
                    <script src="../../../script/jquery.min.js"></script>
                    <script type="text/javascript">
                        // ถ้าข้องมูลของจังหวัดเกิดการเปลี่ยนแปลงจากบรรทัด 192 ให้ส่งค่า id จังหวัดมาที่นี่
                        $('#provinces').change(function() {
                            var id_provinces = $(this).val();

                            //ส่ง ID จังหวัดที่รับมาเป็นแบบ POST ไปที่ หน้า find_provinces_db.php โดยส่งข้อมูล id_จังหวัด และชื่อ function ที่ให้ทำงานด้วยคือ province
                            //เมื่อเสร็จแล้วส่งค่าอำเภอทั้งหมดที่อยู่ในจังหวัดที่เลือก กลับมาที่ตัวแปร data และแสดงผลที่ id amphures บรรทัด 202
                            $.ajax({
                                type: "POST",
                                url: "../find/find_provinces_db.php",
                                data: {
                                    id: id_provinces,
                                    function: 'provinces'
                                },
                                success: function(data) {
                                    $('#amphures').html(data);
                                }
                            });
                        });

                        // ถ้าข้องมูลของอำเภอเกิดการเปลี่ยนแปลงจากบรรทัด 202 ให้ส่งค่า id อำเภอมาที่นี่
                        $('#amphures').change(function() {
                            var id_amphures = $(this).val();

                            //ส่ง ID อำเภอที่รับมาเป็นแบบ POST ไปที่ หน้า find_provinces_db.php โดยส่งข้อมูล id_อำเภอ และชื่อ function ที่ให้ทำงานด้วยคือ amphures
                            //เมื่อเสร็จแล้วส่งค่าตำบลทั้งหมดที่อยู่ในอำเภอที่เลือก กลับมาที่ตัวแปร data และแสดงผลที่ id districts บรรทัด 209
                            $.ajax({
                                type: "POST",
                                url: "../find/find_provinces_db.php",
                                data: {
                                    id: id_amphures,
                                    function: 'amphures'
                                },
                                success: function(data) {
                                    $('#districts').html(data);
                                }
                            });
                        });
                    </script>

                    <div class="title">ข้อมูลสัญญาเช่า</div>

                    <div class="inputfield">
                        <label>บริเวณที่เช่า <strong>*</strong></label>
                        <select class="form-select" name="area_name" id="area_name" required>
                            <option selected hidden value="">เลือกบริเวณที่เช่า</option>
                            <?php
                            while ($row_area = mysqli_fetch_array($result_area)) {
                            ?>
                                <option value="<?php echo $row_area['area_id'] ?>"> <?php echo $row_area['area_name']; ?> </option>
                            <?php } ?>

                        </select>
                    </div>

                    <div class="inputfield">
                        <label>ขนาดพื้นที่เช่า (ไร่)</label>
                        <input type="text" class="input" name="area_size" id="area_size" placeholder="ขนาดพื้นที่เช่า" readonly>
                    </div>

                    <div class="inputfield">
                        <label>วัตถุประสงค์การเช่า <strong>*</strong></label>
                        <input type="text" class="input" name="le_purpose" id="le_purpose" placeholder="วัตถุประสงค์การเช่า" required>
                    </div>

                    <div class="inputfield">
                        <label>ระยะเวลาการเช่า (ปี) <strong>*</strong></label>
                        <select class="form-select" name="le_duration" id="le_duration" required>
                            <option selected hidden value="">เลือกระยะเวลาการเช่า</option>
                            <option value="1">1 ปี</option>
                            <option value="2">2 ปี</option>
                            <option value="3">3 ปี</option>
                        </select>
                    </div>

                    <div class="inputfield">
                        <label>ว/ด/ป เริ่มสัญญา <strong>*</strong></label>
                        <input type="date" class="input" name="le_start_date" id="le_start_date" required>
                    </div>

                    <div class="inputfield">
                        <label>ราคาเช่า / ปี <strong>*</strong></label>
                        <input type="number" class="input" name="mn_year" id="" placeholder="ราคาเช่า" required>
                    </div>

                    <div class="title">ข้อมูลพยาน</div>
                    <div class="inputfield">
                        <label>พยานคนที่ 1 <strong>*</strong></label>
                        <select class="form-select" name="wn_1" id="wn_1" required>
                            <option selected readonly hidden value="">เลือกพยานคนที่ 1</option>

                            <?php
                            //ดึงข้อมูลพยานของผู้เช่า
                            $q = "SELECT * FROM witness WHERE wn_status_show = 1 AND wn_status_otp = 1";
                            $result_q = mysqli_query($conn, $q);
                            while ($row_q = mysqli_fetch_array($result_q)) {
                            ?>
                                <option value="<?php echo $row_q['wn_id'] ?>"> <?php echo $row_q['wn_p_name'] . " " . $row_q['wn_f_name'] . " " . $row_q['wn_l_name']; ?> </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="inputfield">
                        <label>ตำแหน่ง</label>
                        <input type="text" class="input" placeholder="ตำแหน่ง" name="wn_role_1" id="wn_role_1" readonly>
                    </div>

                </div>

                <br>

                <div class="title">
                    แนบหลักฐานเพิ่มเติม
                </div>
                <div class="form">
                    <div class="inputfield">
                        <label>หลักฐานเพิ่มเติมของผู้เช่า (PDF) <strong>*</strong></label>
                        <input type="file" class="input" name="oth_file" required accept="application/pdf">
                    </div>

                    <div class="inputfield">
                        <label>ข้อตกลงแนบท้ายสัญญาเช่า (PDF) <strong>*</strong></label>
                        <input type="file" class="input" name="last_file" required accept="application/pdf">
                    </div>
                </div>

                <br><br>

                <button class="btn_pri" name="save" onclick="return confirm('ยืนยันการเพิ่ม')">บันทึกสัญญาเช่า</button>
                <br><br>

            </form>


        </div>


    </main>

    <!--js-->
    <script src="../../../script/main.js"></script>

    <!--script-->
    <script>
        // ถ้าข้องมูลของพื้นที่เกิดการเปลี่ยนแปลงจากบรรทัด 272 ให้ส่งค่า id พื้นที่มาที่นี่
        $('#area_name').change(function() {
            var area_name = $(this).val();

            //ส่ง ID พื้นที่ ที่รับมาเป็นแบบ POST ไปที่ หน้า ../find/find_ajex_db.php โดยส่งข้อมูล id_พื้นที่ และชื่อ function ที่ให้ทำงานด้วยคือ area_name
            //เมื่อเสร็จแล้วส่งขนาดพื้นที่ ที่ตรงกับพื้นที่ที่เลือก กลับมาที่ตัวแปร data และแสดงผลที่ area_size บรรทัด 281
            $.ajax({
                type: "POST",
                url: "../find/find_ajex_db.php",
                data: {
                    id: area_name,
                    function: 'area_name'
                },
                success: function(data) {
                    // console.log(data);
                    $('#area_size').val(data);
                }
            });
        });

        // ถ้าข้องมูลของพื้นที่เกิดการเปลี่ยนแปลงจากบรรทัด 281 ให้ส่งค่า id พื้นที่มาที่นี่
        $('#area_name').change(function() {
            var area_name = $(this).val();

            //ส่ง ID พื้นที่ ที่รับมาเป็นแบบ POST ไปที่ หน้า ../find/find_ajex_db.php โดยส่งข้อมูล id_พื้นที่ และชื่อ function ที่ให้ทำงานด้วยคือ width_height
            //เมื่อเสร็จแล้วส่งความ กว้าง x ยาว ที่ตรงกับพื้นที่ที่เลือก กลับมาที่ตัวแปร data และแสดงผลที่ wh บรรทัด 286
            $.ajax({
                type: "POST",
                url: "../find/find_ajex_db.php",
                data: {
                    id: area_name,
                    function: 'width_height'
                },
                success: function(data) {
                    // console.log(data);
                    $('#wh').val(data);
                }
            });
        });

        //ชื่อพยาน 1 กับตำแหน่ง
        // ถ้าข้องมูลของพยานเกิดการเปลี่ยนแปลงจากบรรทัด 320 ให้ส่งค่า id พื้นที่มาที่นี่
        $('#wn_1').change(function() {
            var wn_1 = $(this).val();

            //ส่ง ID พยาน ที่รับมาเป็นแบบ POST ไปที่ หน้า ../find/find_ajex_db.php โดยส่งข้อมูล id_พื้นที่ และชื่อ function ที่ให้ทำงานด้วยคือ wn_1
            //เมื่อเสร็จแล้วส่งความตำแหน่งพยาน ที่ตรงกับพยานที่เลือก กลับมาที่ตัวแปร data และแสดงผลที่ wn_role_1 บรรทัด 329
            $.ajax({
                type: "POST",
                url: "../find/find_ajex_db.php",
                data: {
                    id: wn_1,
                    function: 'wn_1'
                },
                success: function(data) {
                    // console.log(data);
                    $('#wn_role_1').val(data);
                }
            });
        });
    </script>

</body>

</html>