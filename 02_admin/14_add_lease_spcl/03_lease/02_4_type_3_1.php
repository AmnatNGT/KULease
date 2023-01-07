<?php {
    session_start();

    if (!$_SESSION['ofc_add']) {
        header("Location: ../../../index.php");
    }

    require('../../../connection.php');

    //หาข้อมูลพื้นที่
    $sql_area = "SELECT * FROM area WHERE area_type = '3' AND area_status_show = 1 AND area_status = '0' ";
    $result_area = mysqli_query($conn, $sql_area);
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
        <span class="name"><?php echo $_SESSION['ofc_add_name']; ?></span> <br>
        <span class="name"><strong>( เจ้าหน้าที่ เพิ่มสัญญาเช่า )</strong></span>
    </div>

    <!--ข้อมูล-->
    <!--ข้อมูล-->
    <main class="data ">

        <div class="wrapper_3">

            <!-- Back -->
            <div>
                <a href="../02_2_type.php" class="back" style="text-decoration:none;">
                    <span class="icon_b" style="color: blue; font-size: 25px;"><em class="fa fa-arrow-left" aria-hidden="true"></em></span>
                    <span class="name_b" style="color: blue; font-size: 20px;"><strong>BACK</strong> </span>
                </a>
            </div>

            <div class="title">
                เพิ่มสัญญาเช่า กรณีพิเศษ
            </div>

            <div class="form">

                <div class="inputfield" id="tn_p_name_oth">
                    <label>ประเภทสัญญาเช่า</label>
                    <input type="text" class="input" value="เพื่องานวิจัย / การเรียนการสอน" readonly>
                </div>
            </div>
            <br>

            <form action="02_4_type_3_2_db.php" method="POST" enctype="multipart/form-data">

                <div class="form">

                    <div class="inputfield">
                        <label>เลขที่สัญญาเช่า <strong>*</strong></label>
                        <input type="text" class="input" name="le_no_success" placeholder="เลขที่สัญญาเช่า" required autofocus>
                    </div>

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

                    <div class="title">
                        แนบเอกสาร
                    </div>
                    <div class="form">
                        <div class="inputfield">
                            <label>เอกสารสัญญาเช่า (PDF)<strong>*</strong></label>
                            <input type="file" class="input" name="oth_file" required accept="application/pdf">
                        </div>
                    </div>
                </div>

                <br>

                <!-- เพิ่มข้อมูล -->
                <div class="form">

                    <div class="title">
                        เพิ่มข้อมูลค่าเช่าล่วงหน้า และเงินประกัน
                    </div>

                    <!-- เลือกรูปแบบการชำระ -->
                    <div class="inputfield">
                        <label>ประเภทค่าเช่าล่วงหน้า และเงินประกัน</label>
                        <select class="form-select" name="type_ad" id="type_ad" onchange="ad_mn()" >
                            <option selected hidden value=""> เลือกประเภทการชำระ</option>
                            <option value="ad1">ใบสำคัญรับเงิน</option>
                            <option value="ad2">ใบเสร็จรับเงิน</option>
                        </select>
                    </div>

                    <!-- ถ้าชำระ ค่าเช่าล่วงหน้า style="display: ไม่เป็น none"-->
                    <div id="a1" style="display:none">
                        <h5>ค่าเช่าล่วงหน้า</h5>
                        <div class="inputfield" style="display:none" id="a2">
                            <label>ค่าเช่าล่วงหน้า <strong style="color:red;">*</strong></label>
                            <input type="number" class="input" name="mn_advance" id="mn_advance" placeholder="ค่าเช่าล่วงหน้า">
                        </div>

                        <div class="inputfield" style="display:none" id="a3">
                            <label>เล่มที่ใบเสร็จรับเงิน <strong style="color:red;">*</strong></label>
                            <input type="text" class="input" name="ad_volume" id="ad_volume" placeholder="เล่มที่ใบเสร็จรับเงิน">
                        </div>

                        <div class="inputfield" style="display:none" id="a4">
                            <label>เลขที่ใบเสร็จรับเงิน <strong style="color:red;">*</strong></label>
                            <input type="text" class="input" name="ad_no" id="ad_no" placeholder="เลขที่ใบเสร็จรับเงิน">
                        </div>

                        <div class="inputfield" style="display:none" id="a5">
                            <label>เลขที่ใบสำคัญ <strong style="color:red;">*</strong></label>
                            <input type="text" class="input" name="ad_mn_no_im" id="ad_mn_no_im" placeholder="เลขที่ใบสำคัญ">
                        </div>

                        <div class="inputfield" style="display:none" id="a6">
                            <label>ว/ด/ป ที่ชำระ <strong style="color:red;">*</strong></label>
                            <input type="date" class="input" name="ad_date" id="ad_date">
                        </div>

                        <div class="inputfield" style="display:none" id="a7">
                            <label>แนบหลักฐานค่าเช่าล่วงหน้า (PDF) <strong style="color:red;">*</strong></label>
                            <input type="file" class="input" name="ad_file" id="ad_date" accept="application/pdf">
                        </div>
                    </div>
                    <br>

                    <!-- ถ้าชำระ เงินประกัน style="display: ไม่เป็น none"-->
                    <div id="b1" style="display:none">
                        <h5>เงินประกัน</h5>
                        <div class="inputfield" style="display:none" id="b2">
                            <label>เงินประกัน <strong style="color:red;">*</strong></label>
                            <input type="number" class="input" name="mn_deposit" placeholder="เงินประกัน">
                        </div>

                        <div class="inputfield" style="display:none" id="b3">
                            <label>เล่มที่ใบเสร็จรับเงิน <strong style="color:red;">*</strong></label>
                            <input type="text" class="input" name="dp_volume" placeholder="เล่มที่ใบเสร็จรับเงิน">
                        </div>

                        <div class="inputfield" style="display:none" id="b4">
                            <label>เลขที่ใบเสร็จรับเงิน <strong style="color:red;">*</strong></label>
                            <input type="text" class="input" name="dp_no" placeholder="เลขที่ใบเสร็จรับเงิน">
                        </div>

                        <div class="inputfield" style="display:none" id="b5">
                            <label>เลขที่ใบสำคัญ <strong style="color:red;">*</strong></label>
                            <input type="text" class="input" name="dp_mn_no_im" placeholder="เลขที่ใบสำคัญ">
                        </div>

                        <div class="inputfield" style="display:none" id="b6">
                            <label>ว/ด/ป ที่ชำระ <strong style="color:red;">*</strong></label>
                            <input type="date" class="input" name="dp_date">
                        </div>

                        <div class="inputfield" style="display:none" id="b7">
                            <label>แนบหลักฐานเงินประกัน (PDF) <strong style="color:red;">*</strong></label>
                            <input type="file" class="input" name="dp_file" accept="application/pdf">
                        </div>
                    </div>

                    <!--js ค่าเช่าล่วงหน้า เงินประกัน-->
                    <script>
                        function ad_mn() {
                            index = document.getElementById('type_ad').value;
                            if (index == 'ad1') {
                                //ค่าเช่าล่วงหน้า
                                document.getElementById('a1').style.display = '';
                                document.getElementById('a2').style.display = '';
                                document.getElementById('a5').style.display = '';
                                document.getElementById('a6').style.display = '';
                                document.getElementById('a7').style.display = '';
                                document.getElementById('a3').style.display = 'none';
                                document.getElementById('a4').style.display = 'none';


                                //เงินประกัน
                                document.getElementById('b1').style.display = '';
                                document.getElementById('b2').style.display = '';
                                document.getElementById('b5').style.display = '';
                                document.getElementById('b6').style.display = '';
                                document.getElementById('b7').style.display = '';

                                document.getElementById('b3').style.display = 'none';
                                document.getElementById('b4').style.display = 'none';
                            }

                            if (index == 'ad2') {
                                //ค่าเช่าล่วงหน้า
                                document.getElementById('a1').style.display = '';
                                document.getElementById('a2').style.display = '';
                                document.getElementById('a6').style.display = '';
                                document.getElementById('a7').style.display = '';
                                document.getElementById('a3').style.display = '';
                                document.getElementById('a4').style.display = '';

                                document.getElementById('a5').style.display = 'none';

                                //เงินประกัน
                                document.getElementById('b1').style.display = '';
                                document.getElementById('b2').style.display = '';
                                document.getElementById('b6').style.display = '';
                                document.getElementById('b7').style.display = '';
                                document.getElementById('b3').style.display = '';
                                document.getElementById('b4').style.display = '';

                                document.getElementById('b5').style.display = 'none';
                            }
                        }
                    </script>

                    <br>

                    <button class="btn_pri" name="save" onclick="return confirm('ยืนยันการเพิ่ม')">บันทึกสัญญาเช่า</button>
                    <br><br>

            </form>


        </div>


    </main>

    <!--js-->
    <script src="../../../script/main.js"></script>

    <!--script-->
    <script src="../../../script/jquery.min.js"></script>
    <script>
        //พื้นที่กับขนาด
        $('#area_name').change(function() {
            var area_name = $(this).val();

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
    </script>

</body>

</html>