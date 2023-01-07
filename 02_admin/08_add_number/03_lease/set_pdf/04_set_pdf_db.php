<?php

use Mpdf\Mpdf;

require_once __DIR__ . '/vendor/autoload.php';

require('../../../../connection.php');

session_start();

if (!$_SESSION['ofc_add']) {
    header("Location: ../../../../index.php");
}

// รับ id สัญญา จาก session
$le_id = $_SESSION['le_id'];

//หาสัญญา 
$sql_do_lease = "SELECT * FROM lease l, status_lease stl
                    WHERE l.le_id = $le_id 
                    AND l.le_id = stl.le_id";
$result_do = mysqli_query($conn, $sql_do_lease);
$row_do = mysqli_fetch_assoc($result_do);



$mpdf = new mPDF([
    'default_font_size' => 16,
    'default_font' => 'sarabun'
]);

// เลขที่อ้างอิงสัญญา 
$le_no = $row_do['le_no'];

// เรียกใช้ function แปลงเลขเป็นเลขไทย
require('../../../../09_function/convert_thai_number.php');
// เลขที่สัญญาที่เพิ่งเพิม เลขไทย
$le_no_success = thainumDigit($row_do['le_no_success']);

// set ตำแหน่ง // เลขที่สัญญาที่เพิ่งเพิม
$html = "<p style='position:absolute; top:19; left:600; width:120; text-align:center; '>$le_no_success</p>";

// เรียกไฟล์ สัญญาเช่า ที่จะใส่ข้อมูล จาก pdf_file_lease/lease_1_1.pdf
//นับหน้า
$pagecount = $mpdf->SetSourceFile('../../../../file_uploads/lease_success/'.$le_no.'.pdf');
for ($i = 1; $i <= ($pagecount); $i++) {
    $mpdf->AddPage();
    $import_page = $mpdf->ImportPage($i);
    $mpdf->UseTemplate($import_page);

    // เอาข้อมูลใส่แค่หน้าแรก
    if ($i == 1) {
        $mpdf->writeHTML($html);
    }
}

//upload file ไปที่ file_uploads/lease_success ตั้งชื่อไฟล์ 64002001021.pdf
$mpdf->Output('../../../../file_uploads/lease_success/'.$le_no.'.pdf');

//open file
// $mpdf->Output('เอกสารสัญญาเช่า.pdf', 'I');

echo "<script type='text/javascript'>";
// เมื่อเสร็จแล้วไปหน้า ../04_see_data.php
echo "window.location='../04_see_data.php';";
echo "</script>";
