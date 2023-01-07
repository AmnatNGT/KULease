<?php
session_start();
require('../../../connection.php');

if (!$_SESSION['ofc_add']) {
    header("Location: ../../../index.php");
}

// รับ id พื้นที่เช่า
$id = $_GET["idsub"];

// เปลี่ยนสถานะพื้นที่เช่า = -1 ของ id พื้นที่ที่ส่งมา
$sql = "UPDATE area SET area_status_show ='-1' WHERE area_id = $id";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo "<script type='text/javascript'>";
    echo "alert('ลบข้อมูล ประเภทสัญญาเช่าเพื่องานบริการ สำเร็จ');";
    // ถ้าเสร็จแล้วไปหน้า 05_2_1_area.php
    echo "window.location='05_2_1_area.php';";
    echo "</script>";
} else {
    echo "<script type='text/javascript'>";
    echo "alert('มีความผิดพลาด');";
    echo "window.location='05_2_1_area.php';";
    echo "</script>";
}
