<?php

require("../../connection.php");
session_start();

if (!$_SESSION['ofc_add']) {
    header("Location: ../../index.php");
}

//รับ id สัญญา
$le_id = $_GET['le_id'];

$ad_id = $_SESSION['ofc_add'];

//แก้ไขสถานะเป็นไม่ทำ
$sql_le = "UPDATE lease SET le_status='-1',
                            ofc_id = $ad_id
            WHERE le_id='$le_id' ";
$result_le = mysqli_query($conn, $sql_le);

if ($result_le) {
    echo "<script type='text/javascript'>";
    echo "alert('ไม่เพิ่มสัญญาเช่าสำเร็จ'); ";
    echo "window.location='02_1_do_lease.php';";
    echo "</script>";
} else {
    echo "<script type='text/javascript'>";
    echo "alert('มีความผิดพลาด');";
    echo "</script>";
}
