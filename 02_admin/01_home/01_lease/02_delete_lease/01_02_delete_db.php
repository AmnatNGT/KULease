<?php

require("../../../../connection.php");
session_start();

if (!$_SESSION['ofc_add']) {
    header("Location: ../../../../index.php");
}

//รับข้อมูลที่ส่งมูล
$le_id = $_POST['le_id'];
$dl_why = $_POST['dl_why'];

$ad_id = $_SESSION['ofc_add'];

//date time now
date_default_timezone_set('Asia/Bangkok');
$date = date("Y-m-d");
$time = date('H:i:s');

//เพิ่มสาเหตุที่ยกเลิก
$sql_dl = " INSERT INTO delete_lease (dl_why, dl_date, dl_time, dl_ad_id) 
                                VALUE('$dl_why', '$date', '$time', '$ad_id') ";
$result_dl = mysqli_query($conn, $sql_dl);

//หา id ล่าสุด จาก table delete lease
$sql_max = "SELECT MAX(dl_id) AS maxid FROM delete_lease";
$result_max = mysqli_query($conn, $sql_max);
$ret = mysqli_fetch_assoc($result_max); // อ่านค่า
$dl_id = $ret['maxid']; // คืนค่า id ที่ insert สูงสุด

//เพิ่มข้อมูลลง dl_id ที่ lease
$sql_le = "UPDATE lease SET dl_id='$dl_id', le_status='3' WHERE le_id='$le_id' ";
$result_le = mysqli_query($conn, $sql_le);

//หา id สัญญาเช่า
$sql_area  = "SELECT * FROM lease WHERE le_id = $le_id ";
$result_area   = mysqli_query($conn, $sql_area );
$row_area = mysqli_fetch_assoc($result_area );
// area id
$area_id = $row_area['area_id'];

// Update status Area
$sql_update_area = "UPDATE area SET area_status='0' WHERE area_id='$area_id' ";
$result_update_area = mysqli_query($conn, $sql_update_area);

if ($result_dl and $result_le) {
    echo "<script type='text/javascript'>";
    echo "alert('ยกเลิกสัญญาเช่าสำเร็จ'); ";
    echo "window.location='../01_01_all_lease.php';";
    echo "</script>";
} else {
    echo "<script type='text/javascript'>";
    echo "alert('มีความผิดพลาด');";
    //echo "window.location='01_tenant_home.php';";
    echo "</script>";
}
