<?php

use Mpdf\Mpdf;

require_once __DIR__ . '/vendor/autoload.php';

require('../../../../connection.php');

session_start();

if (!$_SESSION['ofc_add']) {
    header("Location: ../../../../index.php");
}

//รับ Id สัญญา ล่าสุด
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

// ชื่อรูปลายเซนของผู้เช่า
$pic1 = $row_do['tn_sign_pic'];
// ชื่อรูปลายเซนของ พยานผู้เช่า
$pic2 = $row_do['wn_sign_pic'];

// จัดตำแหน่งที่จะวางลายเซน
$html = "<p style='color:red; position:absolute; top:675; left:140;'><img src='../../../../file_uploads/doc_signs/$pic1'  width='190' height='45' ></p>";
$html .= "<p style='color:red; position:absolute; top:834; left:142;'><img src='../../../../file_uploads/doc_signs/$pic2'  width='190' height='45'></p>";
// $html = "<p style='color:red; position:absolute; top:802; left:492;'><img src='../../../../file_uploads/doc_signs/$pic'  width='190' height='45' style='border: solid 1px red;';></p>";

// ใส่ข้อมูลใน file ข้อตกลงแนบท้ายสัญญา
// เรียกไฟล์ สัญญาเช่า ที่จะใส่ข้อมูล 
//นับหน้า
$pagecount = $mpdf->SetSourceFile('../../../../file_uploads/last_file_lease/last_file_le_'.$le_id.'.pdf');
for ($i = 1; $i <= ($pagecount); $i++) {
    $mpdf->AddPage();
    $import_page = $mpdf->ImportPage($i);
    $mpdf->UseTemplate($import_page);

    if ($i == $pagecount) {
        $mpdf->writeHTML($html);
    }
}

//upload file ไปที่ file_uploads/last_file_lease/last_file_le_1.pdf'
$mpdf->Output('../../../../file_uploads/last_file_lease/last_file_le_'.$le_id.'.pdf');

//open file
// $mpdf->Output('เอกสารสัญญาเช่า.pdf', 'I');

//แจ้งเตือนเจ้าหน้าที่พิเศษ หรือพี่เล็ก ลงนาม
{
    //หาข้อมูลเลขที่อ้างอิงสัญญา
    $sql1 = "SELECT * FROM lease WHERE le_id = $le_id";
    $result1 = mysqli_query($conn, $sql1);
    $row1 = mysqli_fetch_assoc($result1);
    // เลขที่อ้างอิงสัญญา
    $leno = $row1['le_no'];

    //หาข้อมูลเจ้าหน้าที่
    $sql2 = "SELECT * FROM officer WHERE ofc_type = 2";
    $result2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($result2);

    //send otp
    // email เจ้าหน้าที่พิเศษ
    $tn_email = $row2['ofc_email'];
    // phone เจ้าหน้าที่พิเศษ
    $tn_tel = $row2['ofc_tel'];
    $detail = "สัญญาเพื่องานบริการ เลขที่สัญญาเช่า : $leno ผู้เช่าลงนามเสร็จแล้ว ต่อไปอยู่ในสถานะเจ้าหน้าที่ลงนาม โปรดตรวจสอบ";

    //send otp
    // send email เจ้าหน้าที่พิเศษ
    require_once "../../../../16_func_email_sms/func_email.php";
    $header = "แจ้งเตือนการลงนามสัญญาเช่า";
    $send_email = sendEmail($tn_email, $header, $detail);

    //send sms เจ้าหน้าที่พิเศษ
    require_once "../../../../16_func_email_sms/func_sms.php";
    $send_sms = sendSMS($tn_tel, $detail);
}

echo "<script type='text/javascript'>";
echo "alert('ลงนามสัญญาเช่าสำเร็จ'); ";
// ไปหน้า ../04_see_sign.php
echo "window.location='../04_see_sign.php';";
echo "</script>";
