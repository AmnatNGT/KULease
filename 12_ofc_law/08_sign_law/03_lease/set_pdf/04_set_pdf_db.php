<?php

use Mpdf\Mpdf;

require_once __DIR__ . '/vendor/autoload.php';

require('../../../../connection.php');

session_start();

if (!$_SESSION['ofc_law']) {
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
// ชื่อรูปลายเซนของนิติกร
$pic = $row_do['ofc2_sign_pic'];

// $html = "<p style='color:red; position:absolute; top:647; left:149;'><img src='../../../../file_uploads/doc_signs/$pic'  width='190' height='45' style='border: solid 1px red;';></p>";

// จัดตำแหน่งที่จะวางลายเซน
$html = "<p style='color:red; position:absolute; top:960; left:485;'><img src='../../../../file_uploads/doc_signs/$pic'  width='190' height='45' ></p>";

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

//upload file
$mpdf->Output('../../../../file_uploads/last_file_lease/last_file_le_'.$le_id.'.pdf');

//open file
//$mpdf->Output('เอกสารสัญญาเช่า.pdf', 'I');

//แจ้งเตือนผู้ให้เช่า
{
    //หาข้อมูลเลขที่อ้างอิงสัญญา
    $sql1 = "SELECT * FROM lease WHERE le_id = $le_id";
    $result1 = mysqli_query($conn, $sql1);
    $row1 = mysqli_fetch_assoc($result1);
    // เลขที่อ้างอิงสัญญา
    $leno = $row1['le_no'];

    //หาข้อมูลผู้ให้เช่า
    $sql2 = "SELECT * FROM lessor WHERE ls_status_show = 1";
    $result2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($result2);

    //send new otp
    $tn_email = $row2['ls_email'];
    $tn_tel = $row2['ls_tel'];
    $detail = "สัญญาเพื่องานวิจัย/การเรียนการสอน เลขที่สัญญาเช่า : $leno เจ้าหน้าที่ลงนามเสร็จแล้ว ต่อไปอยู่ในสถานะนิติกรลงนาม โปรดตรวจสอบ";

    //mail ผู้ให้เช่า
    require_once "../../../../16_func_email_sms/func_email.php";
    $header = "แจ้งเตือนการลงนามสัญญาเช่า";
    $send_email = sendEmail($tn_email, $header, $detail);

    //SMS ผู้ให้เช่า
    require_once "../../../../16_func_email_sms/func_sms.php";
    $send_sms = sendSMS($tn_tel, $detail);
}

echo "<script type='text/javascript'>";
echo "alert('ลงนามสัญญาเช่าสำเร็จ'); ";
// ไปหน้า ../04_see_sign.php
echo "window.location='../04_see_sign.php';";
echo "</script>";
