<?php
session_start();
require('../../../connection.php');

if (!$_SESSION['ofc_spcl']) {
    header("Location: ../../../index.php");
}

//เลขที่พื้นที่ e1
if (isset($_POST['e1'])) {
    // รับข้อมูล
    $area_id = $_POST['area_id'];
    $data = $_POST['area_no'];

    // update พื้นที่
    $sql = "UPDATE area set area_no = '$data' WHERE area_id = $area_id ";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script type='text/javascript'>";
        echo "alert('แก้ไขข้อมูลพื้นที่เช่าสำเร็จ'); ";
        // เมื่อเสร็จแล้วไปที่หน้า 05_5_4_area_edit.php ส่ง id พื้นที่เช่าไปด้วย
        echo "window.location='05_5_4_area_edit.php?idsub=$area_id';";
        echo "</script>";
    } else {
        echo "<script type='text/javascript'>";
        echo "alert('มีความผิดพลาด');";
        echo "window.location='05_5_4_area_edit.php?idsub=$area_id';";
        echo "</script>";
    }
}

//ชื่อพื้นที่ e2
else if (isset($_POST['e2'])) {
    // รับข้อมูล
    $area_id = $_POST['area_id'];
    $data = $_POST['area_name'];

    // update พื้นที่
    $sql = "UPDATE area set area_name = '$data' WHERE area_id = $area_id ";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script type='text/javascript'>";
        echo "alert('แก้ไขข้อมูลพื้นที่เช่าสำเร็จ'); ";
         // เมื่อเสร็จแล้วไปที่หน้า 05_5_4_area_edit.php ส่ง id พื้นที่เช่าไปด้วย
         echo "window.location='05_5_4_area_edit.php?idsub=$area_id';";
        echo "</script>";
    } else {
        echo "<script type='text/javascript'>";
        echo "alert('มีความผิดพลาด');";
        echo "window.location='05_5_4_area_edit.php?idsub=$area_id';";
        echo "</script>";
    }
}

//ชื่อพื้นที่ e3
else if (isset($_POST['e3'])) {
    // รับข้อมูล
    $area_id = $_POST['area_id'];
    $data = $_POST['area_size'];

    // update พื้นที่
    $sql = "UPDATE area set area_size = '$data' WHERE area_id = $area_id ";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script type='text/javascript'>";
        echo "alert('แก้ไขข้อมูลพื้นที่เช่าสำเร็จ'); ";
        // เมื่อเสร็จแล้วไปที่หน้า 05_5_4_area_edit.php ส่ง id พื้นที่เช่าไปด้วย
        echo "window.location='05_5_4_area_edit.php?idsub=$area_id';";
        echo "</script>";
    } else {
        echo "<script type='text/javascript'>";
        echo "alert('มีความผิดพลาด');";
        echo "window.location='05_5_4_area_edit.php?idsub=$area_id';";
        echo "</script>";
    }
}
