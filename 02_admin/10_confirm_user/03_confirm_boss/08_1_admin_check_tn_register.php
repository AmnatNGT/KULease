<?php {
    require("../../../connection.php");
    session_start();

    if (!$_SESSION['ofc_add']) {
        header("Location: ../../../index.php");
    }

    // แสดงข้อมูลผู้ให้เช่า ที่ ls_status_use = '0' and ls_verify= '1'
    $sql_tn = "SELECT * FROM lessor
                        WHERE ls_status_use = '0' 
                        and ls_verify= '1' "; 
    $result_tn = mysqli_query($conn, $sql_tn);  
    $count = mysqli_num_rows($result_tn);  
    $order = 1; //เก็บลำดับที่
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
                รายชื่อผู้ให้เช่า ที่ประสงค์ลงทะเบียนเข้าใช้งานระบบ
            </div>



                <div class="form">

                    <table id="customers">
                        <thead>
                            <tr>
                                <th id="">ลำดับที่</th>
                                <th id="">ชื่อ - สกุล</th>
                                <th id="">ว/ด/ป ที่ลงทะเบียน</th>
                                <th id="">เวลาที่ลงทะเบียน</th>
                                <th id="">บทบาท</th>
                                <th id="">การอนุมัติ</th>

                            </tr>
                        </thead>

                        <tbody>
                            <!-- แสดงรายชื่อผู้เช่าที่ต้อง อนุมัติ -->
                            <?php while ($row_tn = mysqli_fetch_assoc($result_tn)) { ?>
                                <tr>

                                    <td><?php echo $order++; ?> </td>
                                    <td>
                                        <a STYLE="color: blue;" href="08_4_information_tn_regis.php?idsub=<?php echo $row_tn["ls_id"] ?>"> <?php echo  $row_tn["ls_p_name"] . " " . $row_tn["ls_f_name"] . " " . $row_tn["ls_l_name"]; ?> </a>
                                    </td>
                                    <td><?php echo date('d / m / Y ', strtotime($row_tn["ls_date_regis"])); ?> </td>
                                    <td><?php echo $row_tn["ls_time_regis"]; ?> </td>
                                    
                                    <td>ผู้ให้เช่า</td>

                                    <td>
                                        <!-- ถ้ากดอนุมัติ ไปหน้า  08_3_admin_check_tn_register_confirm_db.php ส่ง id ผู้เช่าไปด้วย-->
                                        <a STYLE="color: white;" href="08_3_admin_check_tn_register_confirm_db.php?idsub=<?php echo $row_tn["ls_id"] ?>" class="btn_yes" onclick="return confirm('ยืนยันการอนุมัติ')">อนุมัติ</a>
                                        <!-- ถ้าไม่กดอนุมัติ ไปหน้า  08_2_admin_check_tn_register_delete_db.php ส่ง id ผู้เช่าไปด้วย-->
                                        <a STYLE="color: white;" href="08_2_admin_check_tn_register_delete_db.php?idsub=<?php echo $row_tn["ls_id"] ?>" class="btn_no" onclick="return confirm('ยืนยันการไม่อนุมัติ')">ไม่อนุมัติ</a>
                                    </td>


                                </tr>

                            <?php } ?>
                        </tbody>
                    </table>
                </div>
 
        </div>

    </main>

    <!--js-->
    <script src="../../../script/main.js"></script>

</body>

</html>