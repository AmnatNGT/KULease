<?php {
    session_start();
    require('../../connection.php');

    if (!$_SESSION['ofc_add']) {
        header("Location: ../../index.php");
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
            <div class="title">
                ดาวน์โหลดฟอร์มแนบท้ายสัญญาเช่า (File : Microsoft word )
            </div>

            <div class="form">

                <table id="customers">
                    <thead>
                        <tr>
                            <th id="">ลำดับที่</th>
                            <th id="">ประเภทสัญญาเช่า</th>
                            <th id="">ดาวน์โหลดฟอร์ม</th>
                        </tr>
                    </thead>

                    <tbody>

                        <!-- ประเภท 1 -->
                        <tr>

                            <td>1</td>
                            <td>ประเภทสัญญาเช่าเพื่อ ร้านค้าหรือพาณิชย์</td>
                            <td><a href="../../file_uploads/file_lease_last/le_last_01.docx" target="_blank" class="btn_pri">ดาวน์โหลดฟอร์ม</a></td>

                        </tr>

                        <!-- ประเภท 2 -->
                        <tr>

                            <td>2</td>
                            <td>ประเภทสัญญาเช่าเพื่อ งานบริการ</td>
                            <td><a href="../../file_uploads/file_lease_last/le_last_02.docx" target="_blank" class="btn_pri">ดาวน์โหลดฟอร์ม</a></td>

                        </tr>

                        <!-- ประเภท 3 -->
                        <tr>

                            <td>3</td>
                            <td>ประเภทสัญญาเช่าเพื่อ งานวิจัย/การเรียนการสอน</td>
                            <td><a href="../../file_uploads/file_lease_last/le_last_03.docx" target="_blank" class="btn_pri">ดาวน์โหลดฟอร์ม</a></td>

                        </tr>

                        <!-- ประเภท 4 -->
                        <tr>

                            <td>4</td>
                            <td>ประเภทสัญญาเช่าเพื่อ ที่พักอาศัย</td>
                            <td><a href="../../file_uploads/file_lease_last/le_last_04.docx" target="_blank" class="btn_pri">ดาวน์โหลดฟอร์ม</a></td>

                        </tr>

                        <!-- ประเภท 5 -->
                        <tr>

                            <td>5</td>
                            <td>ประเภทสัญญาเช่าเพื่อ โรงอาหาร</td>
                            <td><a href="../../file_uploads/file_lease_last/le_last_05.docx" target="_blank" class="btn_pri">ดาวน์โหลดฟอร์ม</a></td>

                        </tr>

                    </tbody>
                </table>
            </div>

        </div>

        <!--js-->
        <script src="../../script/main.js"></script>

    </main>

</body>

</html>