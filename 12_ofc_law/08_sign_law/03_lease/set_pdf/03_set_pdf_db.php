<?php

use Mpdf\Mpdf;

require_once __DIR__ . '/vendor/autoload.php';

require('../../../../connection.php');

session_start();

if (!$_SESSION['ofc_law']) {
    header("Location: ../../../../index.php");
}

//รับ Id สัญญา ที่เป็น session
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

// เลขทีอ้างอิงสัญญา
$le_no = $row_do['le_no'];
// ชื่อรูปลายเซนของนิติกร
$pic = $row_do['ofc2_sign_pic'];

// $html = "<p style='color:red; position:absolute; top:483; left:492;'><img src='../../../../file_uploads/doc_signs/$pic'  width='190' height='45'></p>";

// จัดตำแหน่งที่จะวางลายเซน
$html = "<p style='color:red; position:absolute; top:666; left:480;'><img src='../../../../file_uploads/doc_signs/$pic'  width='190' height='45'></p>";

// ใส่ข้อมูลใน file เอกสารสัญญาเช่า
// เรียกไฟล์ สัญญาเช่า ที่จะใส่ข้อมูล จาก pdf_file_lease/lease_1_1.pdf
//นับหน้า
$pagecount = $mpdf->SetSourceFile('../../../../file_uploads/lease_success/'.$le_no.'.pdf');
for ($i = 1; $i <= ($pagecount); $i++) {
    $mpdf->AddPage();
    $import_page = $mpdf->ImportPage($i);
    $mpdf->UseTemplate($import_page);

    if ($i == 4) {
        $mpdf->writeHTML($html);
    }
}

//upload file ไปที่ file_uploads/lease_success ตั้งชื่อไฟล์ 64002001021.pdf
$mpdf->Output('../../../../file_uploads/lease_success/'.$le_no.'.pdf');

//open file
// $mpdf->Output('เอกสารสัญญาเช่า.pdf', 'I');

echo "<script type='text/javascript'>";
// ไปหน้า 04_set_pdf_db.php
echo "window.location='04_set_pdf_db.php';";
echo "</script>";
