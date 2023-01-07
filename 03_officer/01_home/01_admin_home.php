<?php {
    session_start();

    if (!$_SESSION['ofc_add']) {
        header("Location: ../../index.php");
    }

    require('../../connection.php');

    //สัญญาเช่าทั้งหมดในแต่ละประเภทสัญญา
    //ประเภท 1
    $all_1 = "SELECT * FROM lease WHERE le_type=1";
    $result_all_1 = mysqli_query($conn, $all_1);
    $count_all_1 = mysqli_num_rows($result_all_1);
    //ประเภท 2
    $all_2 = "SELECT * FROM lease WHERE le_type=2  ";
    $result_all_2 = mysqli_query($conn, $all_2);
    $count_all_2 = mysqli_num_rows($result_all_2);
    //ประเภท 3
    $all_3 = "SELECT * FROM lease WHERE le_type=3 ";
    $result_all_3 = mysqli_query($conn, $all_3);
    $count_all_3 = mysqli_num_rows($result_all_3);
    //ประเภท4
    $all_4 = "SELECT * FROM lease WHERE le_type=4 ";
    $result_all_4 = mysqli_query($conn, $all_4);
    $count_all_4 = mysqli_num_rows($result_all_4);
    //ประเภท 5
    $all_5 = "SELECT * FROM lease WHERE le_type=5";
    $result_all_5 = mysqli_query($conn, $all_5);
    $count_all_5 = mysqli_num_rows($result_all_5);

    //ดึงวันที่ปัจจุบัน
    date_default_timezone_set('Asia/Bangkok');
    $today = date_create(date("Y-m-d")); //"2022-09-22"  date("Y-m-d")
    $sign_do_start_lease_time = date('H:em:s');

    //หาสัญญาเช่าที่ใกล้หมดอายุสัญญาเช่าภายใน 120 60 30 วัน
    //ประเภท 1
    {
        //ดึงข้อมูลสัญญาเช่าประเภทที 1
        $c_1 = "SELECT * FROM lease WHERE le_type=1 AND le_status = 1";
        $re_c_1 = mysqli_query($conn, $c_1);

        $count_1_120 = 0;
        $count_1_60 = 0;
        $count_1_30 = 0;

        while ($r_1_120 = mysqli_fetch_assoc($re_c_1)) {

            //วันที่สัญญาเช่าหมดอายุ
            $ch_end_1_120 = date_create($r_1_120['le_end_date']);

            //หาว่าอีกกี่วันหมดอายุสัญญาเช่า โดยใช้ date_diff
            $diff_1 = date_diff($today, $ch_end_1_120);

            //check 120 วัน : 120 ถึง 61 วัน
            if ($diff_1->format("%R%a") <= 120 and $diff_1->format("%R%a") > 60) {
                $count_1_120 += 1;
            }

            //check 60 วัน : 60 ถึง 31 วัน
            else if ($diff_1->format("%a") <= 60 and $diff_1->format("%R%a") > 30) {
                $count_1_60 += 1;
            }

            //check 30 วัน :30 ถึง 1
            else if ($diff_1->format("%R%a") <= 30 and $diff_1->format("%R%a") >= 0) {
                $count_1_30 += 1;
            }
        }
    }

    //ประเภท 2
    {
        //ดึงข้อมูลสัญญาเช่าประเภทที 2
        $c_2 = "SELECT * FROM lease WHERE le_type=2 AND le_status = 1";
        $re_c_2 = mysqli_query($conn, $c_2);

        $count_2_120 = 0;
        $count_2_60 = 0;
        $count_2_30 = 0;
        while ($r_2_120 = mysqli_fetch_assoc($re_c_2)) {

            //วันที่สัญญาเช่าหมดอายุ
            $ch_end_2_120 = date_create($r_2_120['le_end_date']);

            //หาว่าอีกกี่วันหมดอายุสัญญาเช่า โดยใช้ date_diff
            $diff_2 = date_diff($today, $ch_end_2_120);

            //check 120 วัน : 120 ถึง 61 วัน
            if ($diff_2->format("%R%a") <= 120 and $diff_2->format("%R%a") > 60) {
                $count_2_120 += 1;
            }

            //check 60 วัน : 60 ถึง 31 วัน
            else if ($diff_2->format("%R%a") <= 60 and $diff_2->format("%R%a") > 30) {
                $count_2_60 += 1;
            }

            //check 30 วัน :30 ถึง 1
            else if ($diff_2->format("%R%a") <= 30 and $diff_2->format("%R%a") >= 0) {
                $count_2_30 += 1;
            }
        }
    }

    //ประเภท 3
    {
        //ดึงข้อมูลสัญญาเช่าประเภทที 3
        $c_3 = "SELECT * FROM lease WHERE le_type=3 AND le_status = 1";
        $re_c_3 = mysqli_query($conn, $c_3);

        $count_3_120 = 0;
        $count_3_60 = 0;
        $count_3_30 = 0;
        while ($r_3_120 = mysqli_fetch_assoc($re_c_3)) {

            //วันที่สัญญาเช่าหมดอายุ
            $ch_end_3_120 = date_create($r_3_120['le_end_date']);

            //หาว่าอีกกี่วันหมดอายุสัญญาเช่า โดยใช้ date_diff
            $diff_3 = date_diff($today, $ch_end_3_120);

            //check 120 วัน : 120 ถึง 61 วัน
            if ($diff_3->format("%R%a") <= 120 and $diff_3->format("%R%a") > 60) {
                $count_3_120 += 1;
            }

            //check 60 วัน : 60 ถึง 31 วัน
            else if ($diff_3->format("%R%a") <= 60 and $diff_3->format("%R%a") > 30) {
                $count_3_60 += 1;
            }

            //check 30 วัน :30 ถึง 1
            else if ($diff_3->format("%R%a") <= 30 and $diff_3->format("%R%a") >= 0) {
                $count_3_30 += 1;
            }
        }
    }

    //ประเภท 4
    {
        //ดึงข้อมูลสัญญาเช่าประเภทที 4
        $c_4 = "SELECT * FROM lease WHERE le_type=4 AND le_status = 1";
        $re_c_4 = mysqli_query($conn, $c_4);

        $count_4_120 = 0;
        $count_4_60 = 0;
        $count_4_30 = 0;

        while ($r_4_120 = mysqli_fetch_assoc($re_c_4)) {

            //วันที่สัญญาเช่าหมดอายุ
            $ch_end_4_120 = date_create($r_4_120['le_end_date']);

            //หาว่าอีกกี่วันหมดอายุสัญญาเช่า โดยใช้ date_diff
            $diff_4 = date_diff($today, $ch_end_4_120);

            //check 120 วัน : 120 ถึง 61 วัน
            if ($diff_4->format("%R%a") <= 120 and $diff_4->format("%R%a") > 60) {
                $count_4_120 += 1;
            }

            //check 60 วัน : 60 ถึง 31 วัน
            else if ($diff_4->format("%R%a") <= 60 and $diff_4->format("%R%a") > 30) {
                $count_4_60 += 1;
            }

            //check 30 วัน :30 ถึง 1
            else if ($diff_4->format("%R%a") <= 30 and $diff_4->format("%R%a") >= 0) {
                $count_4_30 += 1;
            }
        }
    }

    //ประเภท 5
    {
        //ดึงข้อมูลสัญญาเช่าประเภทที 5
        $c_5 = "SELECT * FROM lease WHERE le_type=5 AND le_status = 1";
        $re_c_5 = mysqli_query($conn, $c_5);

        $count_5_120 = 0;
        $count_5_60 = 0;
        $count_5_30 = 0;
        while ($r_5_120 = mysqli_fetch_assoc($re_c_5)) {

            //วันที่สัญญาเช่าหมดอายุ
            $ch_end_5_120 = date_create($r_5_120['le_end_date']);

            //หาว่าอีกกี่วันหมดอายุสัญญาเช่า โดยใช้ date_diff
            $diff_5 = date_diff($today, $ch_end_5_120);

            //check 120 วัน : 120 ถึง 61 วัน
            if ($diff_5->format("%R%a") <= 120 and $diff_5->format("%R%a") > 60) {
                $count_5_120 += 1;
            }

            //check 60 วัน : 60 ถึง 31 วัน
            else if ($diff_5->format("%R%a") <= 60 and $diff_5->format("%R%a") > 30) {
                $count_5_60 += 1;
            }

            //check 30 วัน :30 ถึง 1
            else if ($diff_5->format("%R%a") <= 30 and $diff_5->format("%R%a") >= 0) {
                $count_5_30 += 1;
            }
        }
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
                ข้อมูลสัญญาเช่า
            </div>

            <div class="form">

                <table id="customers">
                    <thead>
                        <tr>
                            <th id="">ประเภทสัญญาเช่า</th>
                            <th id="">สัญญาเช่าทั้งหมด</th>
                            <th id="">สัญญาเช่าที่หมดอายุสัญญาเช่า <br> ภายใน 120 วัน</th>
                            <th id="">สัญญาเช่าที่หมดอายุสัญญาเช่า <br> ภายใน 60 วัน</th>
                            <th id="">สัญญาเช่าที่หมดอายุสัญญาเช่า <br> ภายใน 30 วัน</th>
                        </tr>
                    </thead>

                    <tbody>
                        <!-- สัญญาเพื่อร้านค้าหรือพาณิชย์ 1-->
                        <tr>
                            <td>สัญญาเพื่อร้านค้าหรือพาณิชย์</td>
                            <td><a href="01_lease/01_01_all_lease.php"><?php echo $count_all_1; ?></a></td>
                            <td><a href="01_lease/01_03_120_day.php"><?php echo $count_1_120; ?></a></td>
                            <td><a href="01_lease/01_05_60_day.php"><?php echo $count_1_60; ?></a></td>
                            <td><a href="01_lease/01_07_30_day.php"><?php echo $count_1_30; ?></a></td>
                        </tr>

                        <!-- สัญญาเพื่องานบริการ 2 -->
                        <tr>
                            <td>สัญญาเพื่องานบริการ</td>
                            <td><a href="02_lease/01_01_all_lease.php"><?php echo $count_all_2; ?></a></td>
                            <td><a href="02_lease/01_03_120_day.php"><?php echo $count_2_120; ?></a></td>
                            <td><a href="02_lease/01_05_60_day.php"><?php echo $count_2_60; ?></a></td>
                            <td><a href="02_lease/01_07_30_day.php"><?php echo $count_2_30; ?></a></td>
                        </tr>

                        <!-- สัญญาเพื่องานวิจัย/การเรียนการสอน 3-->
                        <tr>
                            <td>สัญญาเพื่องานวิจัย/การเรียนการสอน</td>
                            <td><a href="03_lease/01_01_all_lease.php"><?php echo $count_all_3; ?></a></td>
                            <td><a href="03_lease/01_03_120_day.php"><?php echo $count_3_120; ?></a></td>
                            <td><a href="03_lease/01_05_60_day.php"><?php echo $count_3_60; ?></a></td>
                            <td><a href="03_lease/01_07_30_day.php"><?php echo $count_3_30; ?></a></td>
                        </tr>

                        <!-- สัญญาเพื่อที่พักอาศัย 4-->
                        <tr>
                            <td>สัญญาเพื่อที่พักอาศัย</td>
                            <td><a href="04_lease/01_01_all_lease.php"><?php echo $count_all_4; ?></a></td>
                            <td><a href="04_lease/01_03_120_day.php"><?php echo $count_4_120; ?></a></td>
                            <td><a href="04_lease/01_05_60_day.php"><?php echo $count_4_60; ?></a></td>
                            <td><a href="04_lease/01_07_30_day.php"><?php echo $count_4_30; ?></a></td>
                        </tr>

                        <!-- สัญญาเพื่อโรงอาหาร 5-->
                        <tr>
                            <td>สัญญาเพื่อโรงอาหาร</td>
                            <td><a href="05_lease/01_01_all_lease.php"><?php echo $count_all_5; ?></a></td>
                            <td><a href="05_lease/01_03_120_day.php"><?php echo $count_5_120; ?></a></td>
                            <td><a href="05_lease/01_05_60_day.php"><?php echo $count_5_60; ?></a></td>
                            <td><a href="05_lease/01_07_30_day.php"><?php echo $count_5_30; ?></a></td>
                        </tr>


                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!--js-->
    <script src="../../script/main.js"></script>

</body>

</html>