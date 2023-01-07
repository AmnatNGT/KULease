<?php
session_start();
require('../../../connection.php');

if (!$_SESSION['ofc_add']) {
    header("Location: ../../../index.php");
}

// ถ้า save_area ถูกส่งมา
if (isset($_POST['save_area'])) 
{
    // $_SESSION เจ้าหน้าที่
    $ad_id = $_SESSION['ofc_add'];

    // รับข้อมูล
    $area_no = $_POST['area_no'];
    $area_name = $_POST['area_name'];
    $area_size = $_POST['area_size'];
    
    $area_status = '0';
    $area_type = '1';
    $area_status_show = '1';

    date_default_timezone_set('Asia/Bangkok');
    $area_date_add = date("Y-m-d");
    $area_time_add = date('H:i:s');

    // นำข้อมูลไปเก็บไว้ที่ table area
    $query_area = "INSERT INTO area (ofc_id, area_type, area_no, area_name,
                                    area_size, area_status, area_status_show, area_date_add, area_time_add)
                                VALUE('$ad_id', '$area_type', '$area_no', '$area_name',
                                            '$area_size', '$area_status', '$area_status_show' , '$area_date_add', '$area_time_add')";
    $result_area = mysqli_query($conn, $query_area);

    if ($result_area) {
        echo "<script type='text/javascript'>";
            echo "alert('เพิ่มข้อมูลพื้นที่เช่าสำเร็จ');";
            // ถ้าเสร็จแล้วไปหน้า 05_1_1_area.php
            echo "window.location='05_1_1_area.php';";
        echo "</script>";
    } else {
        echo "<script type='text/javascript'>";
            echo "alert('มีความผิดพลาด');";

        echo "</script>";

    }

}
