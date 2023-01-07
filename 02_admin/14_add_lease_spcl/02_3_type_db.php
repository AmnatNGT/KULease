<?php

require("../../connection.php");
session_start();

if (!$_SESSION['ofc_add']) {
    header("Location: ../../index.php");
}

$le_type = $_POST['le_type'];

if($le_type == "0"){
    echo "<script type='text/javascript'>";
    echo "alert('กรุณาเลือกประเภทสัญญา'); ";
    echo "window.location='02_2_type.php';";
    echo "</script>";
}else if ($le_type == "1") {
    echo "<script type='text/javascript'>";
    echo "alert('เพิ่มข้อมูลสัญญาเช่า: ประเภทสัญญาเพื่อร้านค้าหรือพาณิชย์'); ";
    echo "window.location='01_lease/02_4_type_1_1.php';";
    echo "</script>";
}else if ($le_type == "2") {
    echo "<script type='text/javascript'>";
    echo "alert('เพิ่มข้อมูลสัญญาเช่า: ประเภทสัญญาเพื่องานบริการ'); ";
    echo "window.location='02_lease/02_4_type_2_1.php';";
    echo "</script>";
}else if ($le_type == "3") {
    echo "<script type='text/javascript'>";
    echo "alert('เพิ่มข้อมูลสัญญาเช่า: ประเภทสัญญาเพื่องานวิจัย'); ";
    echo "window.location='03_lease/02_4_type_3_1.php';";
    echo "</script>";
}else if ($le_type == "4") {
    echo "<script type='text/javascript'>";
    echo "alert('เพิ่มข้อมูลสัญญาเช่า: ประเภทสัญญาเพื่อที่พักอาศัย'); ";
    echo "window.location='04_lease/02_4_type_4_1.php';";
    echo "</script>";
}else if ($le_type == "5") {
    echo "<script type='text/javascript'>";
    echo "alert('เพิ่มข้อมูลสัญญาเช่า: ประเภทสัญญาเพื่อโรงอาหาร'); ";
    echo "window.location='05_lease/02_4_type_5_1.php';";
    echo "</script>";
}else {
    echo "<script type='text/javascript'>";
    echo "alert('มีความผิดพลาด'); ";
    echo "</script>";
}
