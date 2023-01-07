<?php {
    session_start();
    require('../../../connection.php');

    if (!$_SESSION['ofc_law']) {
        header("Location: ../../../index.php");
    }

     // วิเคราะห์รายรับ { รายปีงบประมาณ } ประเภทสัญญาเพื่อร้านค้าหรือพาณิชย์

    //ดึงข้อมูล
    // mn_cost บวกกัน
    // ที่ประเภทการจ่ายเงิน 2 หรือ 3 และ ที่ mn_status = 1
    // และ GROUP BY m.mn_budget_year
    $sql = "SELECT m.mn_cost, SUM(m.mn_cost) AS total, m.mn_budget_year AS budget_year , count(l.le_id) as c_le
        FROM money m , lease l 
        WHERE l.le_status!=3 AND l.le_type = 1 AND l.le_id = m.le_id AND m.mn_status = 1 AND (m.mn_type = 3 or m.mn_type = 2)
        GROUP BY m.mn_budget_year";
    $result = mysqli_query($conn, $sql);
    $result2 = mysqli_query($conn, $sql);


    //for chart
    $ch_name = array();
    $ch_price = array();
    while ($rs = mysqli_fetch_array($result)) {
        $ch_name[] = "\" ปีงบประมาณ : " . $rs['budget_year'] . "\" ";
        $ch_price[] = "\" " . $rs['total'] . "\" ";
    }
    $ch_name = implode(",", $ch_name);
    $ch_price = implode(",", $ch_price);
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
        <span class="name"><?php echo $_SESSION['ofc_law_name']; ?></span> <br>
        <span class="name"><strong>( นิติกร )</strong></span>
    </div>

    <!--Componants-->
    <main class="data ">
        <div class="wrapper_2">

            <!-- ปุ่มข้อมูล -->
            <a href="03_01_lease.php" class="btn btn-warning">ข้อมูลรายวัน</a>
            <a href="02_01_lease.php" class="btn btn-primary">ข้อมูลรายเดือน</a>
            <a href="01_01_lease.php" class="btn btn-success">ข้อมูลรายปีงบประมาณ</a>

            <br>
            <br>

            <!-- กราฟ -->
            <div class="title">
                วิเคราะห์รายรับ { รายปีงบประมาณ } ประเภทสัญญาเพื่อร้านค้าหรือพาณิชย์
            </div>
            <div class="form">

                <script type="text/javascript" src="../../../script/Chart.bundle.js"></script>
                <p align="center" style="width: 500px; height:500px;">
                    <!-- show graph -->
                    <canvas id="myChart" style="width: 200px; height:200px;"></canvas>

                    <!-- Set graph -->
                    <script>
                        var ctx = document.getElementById("myChart").getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: [<?php echo $ch_name; ?>],
                                datasets: [{
                                    label: 'รายได้ (฿)',
                                    data: [<?php echo $ch_price; ?>],
                                    backgroundColor: [
                                        'rgba(255, 99, 132, 0.2)',
                                        'rgba(54, 162, 235, 0.2)',
                                        'rgba(255, 206, 86, 0.2)',
                                        'rgba(75, 192, 192, 0.2)',
                                        'rgba(153, 102, 255, 0.2)',
                                        'rgba(255, 159, 64, 0.2)',
                                        'rgba(255, 99, 132, 0.2)',
                                        'rgba(54, 162, 235, 0.2)',
                                        'rgba(255, 206, 86, 0.2)',
                                        'rgba(75, 192, 192, 0.2)',
                                        'rgba(153, 102, 255, 0.2)',
                                        'rgba(255, 159, 64, 0.2)'
                                    ],
                                    borderColor: [
                                        'rgba(255,99,132,1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(153, 102, 255, 1)',
                                        'rgba(255, 159, 64, 1)',
                                        'rgba(255,99,132,1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(153, 102, 255, 1)',
                                        'rgba(255, 159, 64, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }
                            }
                        });
                    </script>
                </p>
            </div>

            <br>
            <hr>

            <!-- ตารางข้อมูลสัญญาเช่า -->
            <div class="title">
                ตารางข้อมูลสัญญาเช่า { รายปีงบประมาณ } ประเภทสัญญาเพื่อร้านค้าหรือพาณิชย์
            </div>

            <div class="form">
                <table id="customers">
                    <thead>
                        <tr>
                            <th id="">ปีงบประมาณ (พ.ศ.)</th>
                            <th id="">จำนวนสัญญาเช่า (ฉบับ)</th>
                            <th id="">รายได้ (บาท)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row2 = mysqli_fetch_array($result2)) { ?>
                            <tr>
                                <!-- ปีงบประมาณ  -->
                                <td><?php echo $row2['budget_year']; ?></td>
                                <!-- จำนวนสัญญาเช่า -->
                                <td><?php echo $row2['c_le']; ?></td>
                                <!-- รายได้ -->
                                <td align="right"><?php echo number_format($row2['total'], 2); ?></td>
                            </tr>
                        <?php
                            // ยอดรวมจำนวนเงิน
                            @$amount_total += $row2['total'];
                        }
                        ?>
                        <!-- รวม -->
                        <tr class="table-danger" style="color: red;">
                            <td align="center"><strong>รวม</strong> </td>
                            <td></td>
                            <td align="right">
                                <?php if (isset($amount_total)) { ?>
                                    <!-- ยอดรวมจำนวนเงิน -->
                                    <strong><?php echo number_format($amount_total, 2); ?></strong>
                                <?php } ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>



    </main>

    <!--js-->
    <script src="../../../script/main.js"></script>

</body>

</html>