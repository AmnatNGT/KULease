<?php

if (stristr(htmlentities($_SERVER['PHP_SELF']), "connection.php")) {
    Header("Location: http://cskps.flas.kps.ku.ac.th/kulease2/"); // ไปหน้าหลัก
    die();
}

//เรียกใช้ข้อมูลที่ define
require_once "get_con.php";

//นำค่าจากหน้า get_con.php ที่ define มาเก็บไว้ที่ตัวแปร
$server = server;
$database = database;
$username = username;
$password = password;

//เชื่อมต่อกับฐานข้อมูล
$conn = mysqli_connect("$server", "$username", "$password", "$database");

if (!$conn) {
    die("Failed");
}
