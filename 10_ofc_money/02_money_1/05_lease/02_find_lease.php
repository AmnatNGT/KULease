<?php {
    session_start();
    require('../../../connection.php');

    if (!$_SESSION['ofc_mn']) {
        header("Location: ../../../index.php");
    }

    $le_id = $_GET['le_id'];

    $sql = "SELECT * FROM lease l, tenant t
                     WHERE l.le_id = $le_id AND l.tn_id = t.tn_id ";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    //เก็บลำดับที่

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

    <!--ข้อมูล-->
    <!--ข้อมูล-->
    <main class="data ">

        <div class="wrapper_3">

            <!-- Back -->
            <div>
                <a href="01_lease.php" class="back" style="text-decoration:none;">
                    <span class="icon_b" style="color: blue; font-size: 25px;"><em class="fa fa-arrow-left" aria-hidden="true"></em></span>
                    <span class="name_b" style="color: blue; font-size: 20px;"><strong>BACK</strong> </span>
                </a>
            </div>

            <div class="title">
                เพิ่มข้อมูลค่าเช่าล่วงหน้าและเงินประกัน <br>
                เลขที่อ้างอิงสัญญาเช่า : <?php echo $row["le_no"]; ?>
            </div>

            <div class="form">

                <div class="inputfield" id="tn_p_name_oth">
                    <label>ประเภทสัญญาเช่า</label>
                    <input type="text" class="input" value="เพื่อโรงอาหาร" readonly>
                </div>
            </div>
            <br>

            <form action="03_add_data_db.php" method="POST" enctype="multipart/form-data">

                <input name="le_id" id="le_id" type="hidden" value="<?php echo $le_id; ?>">


                <br>
                <div class="form">
                    <!-- ผู้เช่า -->
                    <?php
                    $q = "SELECT * FROM lessor WHERE ls_status_show = 1";
                    $result_q = mysqli_query($conn, $q);

                    $row_q = mysqli_fetch_assoc($result_q);

                    ?>

                    <div class="inputfield">
                        <label>ชื่อ - นามสกุล ผู้เช่า</label>
                        <input type="text" class="input" value="<?php echo $row['tn_p_name'] . " " . $row['tn_f_name'] . " " . $row['tn_l_name']; ?>" readonly>
                    </div>

                </div>
                <br>

                <div class="form">

                    <div class="title">
                        ข้อมูลเงินประกัน
                    </div>
                    <div class="inputfield">
                        <label>ประเภทค่าเช่าล่วงหน้า และเงินประกัน </label>
                        <select class="form-select" name="type_ad" id="type_ad" onchange="ad_mn()" >
                            <option selected hidden value=""> เลือกประเภทการชำระ</option>
                            <option value="ad1">ใบสำคัญรับเงิน</option>
                            <option value="ad2">ใบเสร็จรับเงิน</option>
                        </select>
                    </div>
                    <br>
                    <!-- เงินประกัน -->
                    <div id="b1" style="display:none">
                        <h5>เงินประกัน</h5>
                        <div class="inputfield" style="display:none" id="b2">
                            <label>เงินประกัน <strong style="color:red;">*</strong></label>
                            <input type="number" class="input" name="mn_deposit" placeholder="เงินประกัน" >
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
                            <input type="date" class="input" name="dp_date" >
                        </div>

                        <div class="inputfield" style="display:none" id="b7">
                            <label>แนบหลักฐานเงินประกัน (PDF) <strong style="color:red;">*</strong></label>
                            <input type="file" class="input" name="dp_file"  accept="application/pdf">
                        </div>
                    </div>

                    <!--js ค่าเช่าล่วงหน้า เงินประกัน-->
                    <script>
                        function ad_mn() {
                            index = document.getElementById('type_ad').value;
                            if (index == 'ad1') {
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

                    <button class="btn_pri" name="save" onclick="return confirm('ยืนยันการเพิ่ม')">บันทึก</button>
                    <br><br>

            </form>


        </div>


    </main>

</body>

</html>

<script src="../../../script/main.js"></script>