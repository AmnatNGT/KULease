<?php

require("../../connection.php");
session_start();

if (!$_SESSION['ofc_add']) {
    header("Location: ../../index.php");
}

// Email เจ้าหน้าที่
if (isset($_POST['email1'])) {
    $a = $_POST['email1'];

    $sql = "UPDATE contact set c_email_admin = '$a' ";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script type='text/javascript'>";
        echo "alert('แก้ไขข้อมูลสำเร็จ'); ";
        echo "window.location='01_contact.php';";
        echo "</script>";
    } else {
        echo "<script type='text/javascript'>";
        echo "alert('มีความผิดพลาด');";
        echo "window.location='01_contact.php';";
        echo "</script>";
    }
}
// Email กองบริหารทรัพย์สิน
else if (isset($_POST['email2'])) {
    $a = $_POST['email2'];

    $sql = "UPDATE contact set c_email_agc = '$a' ";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script type='text/javascript'>";
        echo "alert('แก้ไขข้อมูลสำเร็จ'); ";
        echo "window.location='01_contact.php';";
        echo "</script>";
    } else {
        echo "<script type='text/javascript'>";
        echo "alert('มีความผิดพลาด');";
        echo "window.location='01_contact.php';";
        echo "</script>";
    }
}
// Facebook กองบริหารทรัพย์สิน
else if (isset($_POST['fb'])) {
    $a = $_POST['fb'];

    $sql = "UPDATE contact set c_facebook = '$a' ";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script type='text/javascript'>";
        echo "alert('แก้ไขข้อมูลสำเร็จ'); ";
        echo "window.location='01_contact.php';";
        echo "</script>";
    } else {
        echo "<script type='text/javascript'>";
        echo "alert('มีความผิดพลาด');";
        echo "window.location='01_contact.php';";
        echo "</script>";
    }
}
// โทรศัพท์
else if (isset($_POST['tel'])) {
    $a = $_POST['tel'];

    $sql = "UPDATE contact set c_tel = '$a' ";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script type='text/javascript'>";
        echo "alert('แก้ไขข้อมูลสำเร็จ'); ";
        echo "window.location='01_contact.php';";
        echo "</script>";
    } else {
        echo "<script type='text/javascript'>";
        echo "alert('มีความผิดพลาด');";
        echo "window.location='01_contact.php';";
        echo "</script>";
    }
}
// บ้านเลขที่
else if (isset($_POST['no'])) {
    $a = $_POST['no'];

    $sql = "UPDATE contact set c_ad_no = '$a' ";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script type='text/javascript'>";
        echo "alert('แก้ไขข้อมูลสำเร็จ'); ";
        echo "window.location='01_contact.php';";
        echo "</script>";
    } else {
        echo "<script type='text/javascript'>";
        echo "alert('มีความผิดพลาด');";
        echo "window.location='01_contact.php';";
        echo "</script>";
    }
}
// หมู่ที่
else if (isset($_POST['moo'])) {
    $a = $_POST['moo'];

    $sql = "UPDATE contact set c_ad_moo = '$a' ";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script type='text/javascript'>";
        echo "alert('แก้ไขข้อมูลสำเร็จ'); ";
        echo "window.location='01_contact.php';";
        echo "</script>";
    } else {
        echo "<script type='text/javascript'>";
        echo "alert('มีความผิดพลาด');";
        echo "window.location='01_contact.php';";
        echo "</script>";
    }
}
//ตำบล
else if (isset($_POST['ct'])) {
    $a = $_POST['ct'];

    $sql = "UPDATE contact set c_ad_canton = '$a' ";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script type='text/javascript'>";
        echo "alert('แก้ไขข้อมูลสำเร็จ'); ";
        echo "window.location='01_contact.php';";
        echo "</script>";
    } else {
        echo "<script type='text/javascript'>";
        echo "alert('มีความผิดพลาด');";
        echo "window.location='01_contact.php';";
        echo "</script>";
    }
}
//อำเภอ
else if (isset($_POST['dt'])) {
    $a = $_POST['dt'];

    $sql = "UPDATE contact set c_ad_district = '$a' ";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script type='text/javascript'>";
        echo "alert('แก้ไขข้อมูลสำเร็จ'); ";
        echo "window.location='01_contact.php';";
        echo "</script>";
    } else {
        echo "<script type='text/javascript'>";
        echo "alert('มีความผิดพลาด');";
        echo "window.location='01_contact.php';";
        echo "</script>";
    }
}
//จังหวัด
else if (isset($_POST['pv'])) {
    $a = $_POST['pv'];

    $sql = "UPDATE contact set c_ad_province = '$a' ";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script type='text/javascript'>";
        echo "alert('แก้ไขข้อมูลสำเร็จ'); ";
        echo "window.location='01_contact.php';";
        echo "</script>";
    } else {
        echo "<script type='text/javascript'>";
        echo "alert('มีความผิดพลาด');";
        echo "window.location='01_contact.php';";
        echo "</script>";
    }
}
//ไปรษณีย์
else if (isset($_POST['psc'])) {
    $a = $_POST['psc'];

    $sql = "UPDATE contact set c_ad_post_ofc = '$a' ";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script type='text/javascript'>";
        echo "alert('แก้ไขข้อมูลสำเร็จ'); ";
        echo "window.location='01_contact.php';";
        echo "</script>";
    } else {
        echo "<script type='text/javascript'>";
        echo "alert('มีความผิดพลาด');";
        echo "window.location='01_contact.php';";
        echo "</script>";
    }
}
