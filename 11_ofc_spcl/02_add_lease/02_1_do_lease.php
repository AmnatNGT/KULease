<?php {
    session_start();

    if (!$_SESSION['ofc_spcl']) {
        header("Location: ../../index.php");
    }

    require('../../connection.php');
    
    //ดึงข้อมูลจากฐานข้อมู จาก tabel lease, status_lease
    $sql = "SELECT * FROM lease l, status_lease stl 
                     WHERE l.le_status = 0
                     AND stl.st_add_lease = 0
                     AND l.le_id = stl.le_id";  
    $result = mysqli_query($conn, $sql);  
    $count = mysqli_num_rows($result);  
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
        <span class="name"><?php echo $_SESSION['ofc_spcl_name']; ?></span> <br>
        <span class="name"><strong>( เจ้าหน้าที่ พิเศษ )</strong></span>
    </div>

    <!--Componants-->
    <main class="data ">
        <div class="wrapper_2">
            <div class="title">
                เพิ่มสัญญาเช่า
            </div>



                <div class="form">

                    <!-- ค้นหาส่ง เลขที่อ้างอิงสัญญาเช่า ไปหน้า  02_6_search.php-->
                    <form action="02_6_search.php" method="POST">
                        <div class="inputfield" style="width: 550px;">
                            <label>ค้นหาเลขที่อ้างอิงสัญญาเช่า</label>
                            <input type="text" class="input" placeholder="เลขที่อ้างอิงสัญญาเช่า" name="search_no" required>

                            <input type="submit" class="btn_search" value="ค้นหา">
                        </div>
                    </form>

                    <br>
                    <!-- ข้อมูล -->
                    <table id="customers">
                        <thead>
                            <tr>
                                <th id="">ลำดับที่</th>
                                <th id="">เลขที่อ้างอิงสัญญาเช่า</th>
                                <th id="">ชื่อ-สกุล ผู้เช่า</th>
                                <th id="">ว/ด/ป ที่ยื่นเรื่อง</th>
                                <th id="">เวลาที่ส่งเรื่อง</th>
                                <th id="">ความประสงค์</th>
                                <th id="">การเพิ่มสัญญาเช่า</th>
                            </tr>
                        </thead>

                        <tbody>
                            <!-- แสดงข้อมูล -->
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td><?php echo $order++; ?> </td>

                                    <td><?php echo $row["le_no"] ?></td>

                                    <!-- ชื่อ-สกุล ผู้เช่า -->
                                    <td>
                                        <?php
                                        
                                        $l_id = $row['le_id'];

                                        $sql_ch = "SELECT * FROM lease l ,tenant t
                                                    WHERE l.tn_id = t.tn_id AND l.le_id = $l_id";

                                        $result_ch = mysqli_query($conn, $sql_ch);
                                        $row_ch = mysqli_fetch_assoc($result_ch);

                                        // ไอดีผู้เช่า
                                        $id_sub = $row_ch['tn_id'];

                                        ?>
                                        <!-- แสดงชื่อ สกุลผู้เช่า ถ้ากดชื่อให้ไปหน้า 02_5_information_tn.php และส่ง id ผู้เช่าไปด้วย -->
                                        <a href="02_5_information_tn.php?idsub=<?php echo $id_sub ?>"><?php echo $row_ch["tn_p_name"] . " " . $row_ch["tn_f_name"] . " " . $row_ch["tn_l_name"]; ?> </a>
                                    </td>


                                    <td><?php echo date('d / m / Y ', strtotime($row["le_date_why_do"])); ?></td>

                                    <td><?php echo $row["le_time_why_do"]; ?></td>

                                    <td>
                                        <?php if ($row["le_why_do"] == "ทำ") {
                                            echo "ทำสัญญา";
                                        } else if ($row["le_why_do"] == "ต่อ") {
                                            echo "ต่อสัญญา";
                                        }
                                        ?>
                                    </td>

                                    <!-- การเพิ่มสัญญาเช่า -->
                                    <td>
                                        <!-- ถ้ากด เพิ่มสัญญาเช่า ไปที่หน้า 02_2_type.php และส่ง id สัญญาเช่าไปด้วย-->
                                        <a href="02_2_type.php?le_id=<?php echo $row["le_id"] ?>" class="btn_yes" onclick="return confirm('ยืนยัน : การเพิ่มสัญญา')">เพิ่มสัญญาเช่า</a>
                                        <!-- ถ้ากด ไม่เพิ่มสัญญาเช่า ไปที่หน้า 02_4_cancel_lease_db.php และส่ง id สัญญาเช่าไปด้วย-->
                                        <a href="02_4_cancel_lease_db.php?le_id=<?php echo $row["le_id"] ?>" class="btn_no" onclick="return confirm('ยืนยัน : การไม่เพิ่มสัญญา')">ไม่เพิ่มสัญญาเช่า</a>
                                    </td>


                                </tr>

                            <?php } ?>
                        </tbody>
                    </table>
                </div>
 
        </div>


    </main>

    <!--js-->
    <script src="../../script/main.js"></script>

</body>

</html>