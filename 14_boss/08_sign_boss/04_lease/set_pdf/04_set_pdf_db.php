<?php

use Mpdf\Mpdf;

require_once __DIR__ . '/vendor/autoload.php';

require('../../../../connection.php');

session_start();

if (!$_SESSION['boss']) {
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
// ชื่อรูปลายเซนของผู้ให้เช่า
$pic = $row_do['ls_sign_pic'];

// จัดตำแหน่งที่จะวางลายเซน
$html = "<p style='color:red; position:absolute; top:734; left:465;'><img src='../../../../file_uploads/doc_signs/$pic'  width='190' height='45' ></p>";


// $html = "<p style='color:red; position:absolute; top:647; left:149;'><img src='../../../../file_uploads/doc_signs/$pic'  width='190' height='45' style='border: solid 1px red;';></p>";

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

//$mpdf->Output('เอกสารสัญญาเช่า.pdf', 'I');

//แจ้งเตือนเจ้าหน้าที่เพิ่มสัญญา เจ้าหน้าที่พิเศษ
{
    //หาข้อมูลเจ้าหน้าที่ 
    $sql1 = "SELECT * FROM officer 
                WHERE ( ofc_type = 2 OR ofc_type = 1 )
                AND ofc_status_use = 1";
    $result1 = mysqli_query($conn, $sql1);

    //หาเลขที่อ้างอิงสัญญา
    $sql2 = "SELECT * FROM lease 
                WHERE le_id = $le_id";
    $result2 = mysqli_query($conn, $sql2);
    $r2 = mysqli_fetch_assoc($result2);
    $le_no = $r2['le_no'];

    while ($r1 = mysqli_fetch_assoc($result1)) { // วนลูปเจ้าหน้าที่ทุกคน

        $email = $r1['ofc_email'];
        $tel = $r1['ofc_tel'];
        $header = "แจ้งเตือน การลงนาม";
        $detail = "สัญญาเพื่อที่พักอาศัย เลขที่สัญญาเช่า $le_no คณะนี้ผู้มีส่วนเกี่ยวข้องลงนามครบแล้ว ต่อไปอยู่ในสถานะเพิ่มเลขที่สัญญา โปรดตรวจสอบ";

        //mailer
        require_once "../../../../16_func_email_sms/func_email.php";
        $send_email = sendEmail($email, $header, $detail);

        //SMS
        require_once "../../../../16_func_email_sms/func_sms.php";
        $send_sms = sendSMS($tel, $detail);
    }
}

echo "<script type='text/javascript'>";
echo "alert('ลงนามสัญญาเช่าสำเร็จ'); ";
echo "window.location='../04_see_sign.php';";
echo "</script>";
