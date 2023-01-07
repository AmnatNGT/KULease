<?php {

    session_start();
    require('../../../connection.php');

    if (!$_SESSION['ofc_add']) {
        header("Location: ../../../index.php");
    }

    // รับ id สัญญาเช่า จาก session
    $le_id = $_SESSION['le_id'];

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
    <link rel="stylesheet" href="../../../style/style_bar_name.css">


    <link href="../../../style/css_sign/jquery.signaturepad.css" rel="stylesheet">
    <script src="../../../script/js_sign/jquery_1_10_2.min.js"></script>
    <script src="../../../script/js_sign/numeric-1.2.6.min.js"></script>
    <script src="../../../script/js_sign/bezier.js"></script>
    <script src="../../../script/js_sign/jquery.signaturepad.js"></script>

    <script type='text/javascript' src="../../../script/js_sign/html2canvas.js"></script>
    <script src="../../../script/js_sign/json2.min.js"></script>

    <style type="text/css">
        #signArea {
            width: 304px;
            margin: 0px auto;
        }

        .sign-container {
            width: 60%;
            margin: auto;
        }

        .sign-preview {
            width: 150px;
            height: 50px;
            border: solid 1px #CFCFCF;
            margin: 10px 5px;
        }
    </style>

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
    <main class="data ">


        <div class="wrapper_2">

            <!-- Back -->
            <div>
                <a href="09_1_chk_data.php?le_id=<?php echo $le_id; ?>" class="back" style="text-decoration:none;">
                    <span class="icon_b" style="color: blue; font-size: 25px;"><em class="fa fa-arrow-left" aria-hidden="true"></em></span>
                    <span class="name_b" style="color: blue; font-size: 20px;"><strong>BACK</strong> </span>
                </a>
            </div>

            <div class="title">
                พยานของผู้เช่า ลงนามสัญญาเช่า <br> เลขที่สัญญาเช่า : <?php echo $row_le['le_no']; ?>
            </div>
            <!-- ช่องลายเซ็น -->
            <div class="form">


                <!-- ชื่อรูปลายเซน 64019220334_tn_sign.png -->
                <input type="hidden" name="fullname" id="fullname" value="<?php echo $row_le['le_no']; ?>_wn_sign.png">

                <div align="center">

                    <div id="signArea">

                        <div id="btnClearSign">
                            <em class="fa fa-eraser" aria-hidden="true" style="cursor: pointer; color:blue;"></em>
                            <a href="#" style="color: blue;">ลบลายเซ็น</a>
                        </div>

                        <!-- กล่องเซน -->
                        <div class="sig sigWrapper" style="height:auto;">
                            <div class="typed"></div>
                            <canvas class="sign-pad" id="sign-pad" width="300" height="100"></canvas>
                        </div>

                    </div>

                </div>

                <br>

                <div align="center">
                    <input type="checkbox" id="myCheck" >
                    <label> <strong style="color: red;">ฉันได้ทำการตรวจสอบเอกสารก่อนแล้ว และ ฉันยินยอมให้นำลายเซ็นของฉันไปใช้ในสัญญาเช่าของฉัน</strong> </label>
                </div>

                <br>

                <div align="center"><input name="submit_bt" id="submit_bt" class="btn_yes" value="บันทึก" type="button" onclick="return confirm('ยืนยัน : การลงนาม')"></div>

                <script>
                    $(document).ready(function() {
                        $('#signArea').signaturePad({
                            drawOnly: true,
                            drawBezierCurves: true,
                            lineTop: 90,
                            lineWidth: 0.1 // ทำให้สีจางที่สุด
                        });
                    });

                    var options = {
                        defaultAction: 'drawIt',
                        penColour: '#2c3e50',
                        lineWidth: 0,
                    }
                    var canvas = $('.sigArea').signaturePad(options);

                    $("#btnClearSign").click(function(e) {
                        $('#signArea').signaturePad().clearCanvas();
                    });

                    $("#submit_bt").click(function(e) {

                        // Validate Field ที่กรอกเข้ามา
                        fullname_fld = document.getElementById('fullname');

                        checkBox = document.getElementById("myCheck");

                        if (fullname_fld.value == "") {
                            alert("โปรดกรอก ชื่อ - นามสกุล ด้วย");
                            fullname_fld.focus();
                            return false;
                        }

                        // ถ้ายังไม่ได้เซนและกด บันทึก จะแจ้งเตือน
                        if (isCanvasBlank(document.getElementById('sign-pad'))) {
                            alert('โปรดเซ็น ลายเซ็น ด้วย');
                            return false;
                        }

                        if (checkBox.checked != true) {
                            alert("กรุณายินยอมการตรวจสอบเอกสาร และการนำลายเซ็นไปใช้ต่อ");
                            checkBox.focus();
                            return false;
                        }

                        // บันทึกลงฐานข้อมูล
                        html2canvas([document.getElementById('sign-pad')], {
                            onrendered: function(canvas) {

                                var canvas_img_data = canvas.toDataURL('image/png');
                                var img_data = canvas_img_data.replace(/^data:image\/(png|jpg);base64,/, "");

                                //ajax call to save image inside folder
                                // ไปหน้า save_sign.php เพื่อ save รูป
                                // โดยส่งข้อมูล รูปภาพ และ ชื่อรูป
                                $.ajax({
                                    url: 'save_sign_2.php',
                                    data: {
                                        img_data: img_data,
                                        fullname: fullname_fld.value
                                    },
                                    type: 'post',
                                    dataType: 'json',
                                    success: function(response) {

                                        //alert(response.id); // ใช้ response.ชื่อ Key ในการดึงข้อมูลที่ส่งกลับมา
                                        //window.location.href = response.file_name;

                                        // รับค่าที่ส่งกลับมาและไปหน้า 06_confirm_otp.php
                                        alert("กรุณาตรวจสอบที่ SMS โทรศัพท์ หรือ Email พยานของผู้เช่า เพื่อยืนยัน OTP");
                                        window.location = "06_confirm_otp.php";

                                    }
                                });
                            }
                        });
                    });

                    function isCanvasBlank(canvas) {

                        txt_tmp = canvas.toDataURL();

                        //console.log(canvas.toDataURL());

                        if ((txt_tmp.length == 1162) | (txt_tmp.length == 1178) | (txt_tmp.length == 586) | (txt_tmp.length == 594) | (txt_tmp.length == 642) | (txt_tmp.length == 654))
                            return true;
                        else
                            return false;

                    }
                </script>


            </div>

            <br><br>

            <!-- แสดงข้อมูลผุ้ลงนาม -->
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
                            <tr>

                                <td>ผู้เช่า </td>



                                <!--ชื่อผู้เช่า-->
                                <td><?php echo $row_le["tn_p_name"] . " " . $row_le["tn_f_name"] . " " . $row_le["tn_l_name"]; ?></td>
                                <td><?php echo $row_le['tn_tel'] ?></td>

                                <td><?php echo date('d / m / Y ', strtotime($row_le["otp_date_tn1"])); ?></td>
                                <td><?php echo $row_le['otp_time_tn1'] ?></td>

                                <td style="color: green;" >ผู้เช่าลงนาม <br> และยืนยันตัวตน  <br> ผ่าน OTP สำเร็จ</td>

                               
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