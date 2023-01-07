<?php

session_start();
require('../../../connection.php');

// รับ id สัญญที่เป็น session
$le_id = $_SESSION['le_id'];

// เปลี่ยนสถานะว่าผู้เช่าได้ยืนยันการตรวจเอกสารแล้ว
$q7 = "UPDATE status_lease SET boss_chk='1' WHERE le_id = $le_id ";
$result_q7 = mysqli_query($conn, $q7);


// เมื่อเสร็จไปหน้า 01_lease.php
echo "<script type='text/javascript'>";
echo "alert('ยืนยันการตรวจสอบเอกสารสัญญาเช่าสำเร็จ');";
echo "window.location='01_lease.php';";
echo "</script>";