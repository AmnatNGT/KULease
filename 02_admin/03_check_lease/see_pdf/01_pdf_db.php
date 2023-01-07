<?php {
    require('../../../connection.php');
    session_start();

    if (!$_SESSION['ofc_add']) {
        header("Location: ../../../index.php");
    }

    require('../../../connection.php');
    // เรียกใช้ function แปลงเลขเป็นประโยคสัญลักษณ์
    require('../../../09_function/convert_thai_msg_monney.php');
    // เรียกใช้ function แปลงเลขเป็นเลขไทย
    require('../../../09_function/convert_thai_number.php');
    // เรียกใช้ function แปลงเดือน เป็นเดือนไทย
    require('../../../09_function/convert_thai_month.php');

    // รับ id_สัญญาเช่า ที่เป็น session
    $le_id = $_SESSION['le_id_do'];

    //อัพเดตว่าได้ตรวจสอบแล้ว
    $up_l = "UPDATE lease SET le_check_success = '1' WHERE le_id = $le_id";
    $result_up_l  = mysqli_query($conn, $up_l);

    //วันที่วันนี้
    date_default_timezone_set('Asia/Bangkok');
    $date_today = thainumDigit(date("d"));
    $month_today = cal_month(date("m"));
    // แปลงเป็นปีไทย +543
    $y6 = date("Y") + 543;
    $year_today = thainumDigit($y6);

    //ดึงข้อมูลที่ใช้ในเอกสาร pdf
    //สัญญา
    $sql_le  = "SELECT * FROM lease WHERE le_id = $le_id ";
    $result_le  = mysqli_query($conn, $sql_le);
    $row_le = mysqli_fetch_assoc($result_le);
    //ข้อมูลในสัญญา
    {
        $le_no = thainumDigit($row_le["le_no"]);

        $le_no2 = $row_le["le_no"];

        $le_purpose = $row_le["le_purpose"];
        $le_duration = thainumDigit($row_le["le_duration"]);
        $le_start_date = thainumDigit(date('d', strtotime($row_le["le_start_date"])));
        $le_start_month = cal_month(date('m', strtotime($row_le["le_start_date"])));

        $le_start_year =  date('Y', strtotime($row_le["le_start_date"]));
        $le_start_year = thainumDigit($le_start_year + 543);


        $le_end_date = thainumDigit(date('d', strtotime($row_le["le_end_date"])));
        $le_end_month = cal_month(date('m', strtotime($row_le["le_end_date"])));

        $le_end_year =  date('Y', strtotime($row_le["le_end_date"]));
        $le_end_year = thainumDigit($le_end_year + 543);
    }

    //ชื่อผู้บริหาร
    $sql_ls = "SELECT * FROM lessor 
    WHERE ls_status_show = 1 ";
    $result_ls = mysqli_query($conn, $sql_ls);
    $row_ls = mysqli_fetch_assoc($result_ls);
    //ข้อมูลผู้บริหาร
    {
        $boss_name = $row_ls['ls_p_name'] . " " . $row_ls['ls_f_name'] . " " . $row_ls['ls_l_name'];
        $boss_role = $row_ls['ls_role'];
    }

    //ผู้เช่า
    $sql_tn = "SELECT * FROM tenant tn, lease le
            WHERE le.le_id = $le_id AND tn.tn_id = le.tn_id ";
    $result_tn = mysqli_query($conn, $sql_tn);
    $row_tn = mysqli_fetch_assoc($result_tn);
    //ข้อมูลผู้เช่า
    {
        $tn_age =  date('Y', strtotime($row_tn['tn_birth_date']));
        $tn_age = thainumDigit(date("Y") - $tn_age);

        $tn_name = $row_tn["tn_p_name"] . " " . $row_tn["tn_f_name"] . " " . $row_tn["tn_l_name"];
        $tn_ethnicity = $row_tn["tn_ethnicity"];
        $tn_nationality = $row_tn["tn_nationality"];
        $tn_house_no = thainumDigit($row_tn["tn_house_no"]);
        $tn_moo = thainumDigit($row_tn["tn_moo"]);
        $tn_canton = $row_tn["tn_canton"];
        $tn_district = $row_tn["tn_district"];
        $tn_province = $row_tn["tn_province"];
        $tn_road = $row_tn["tn_road"];
        $tn_alley = thainumDigit($row_tn["tn_alley"]);
        $tn_role = $row_tn["tn_role"];
    }

    //พื้นที่เช่า
    $sql_area = "SELECT * FROM lease le , area ar
            WHERE le.area_id = ar.area_id
            AND le.le_id = $le_id";
    $result_area = mysqli_query($conn, $sql_area);
    $row_area = mysqli_fetch_assoc($result_area);
    //ข้อมูลพื้นที่
    {
        $area_name = thainumDigit($row_area["area_name"]);
        $area_size = thainumDigit($row_area["area_size"]);
    }

    //พยาน เจ้าหน้าที่พิเศษ
    $sql_wn_1 = "SELECT * FROM lease le, officer ofc
                            WHERE le.le_sign_ofc1 = ofc.ofc_id AND le.le_id = $le_id ";
    $result_wn_1 = mysqli_query($conn, $sql_wn_1);
    $row_wn_1 = mysqli_fetch_assoc($result_wn_1);
    //ข้อมูลพยาน เจ้าหน้าที่พิเศษ
    {
        $wn_1 = $row_wn_1['ofc_p_name'] . " " . $row_wn_1['ofc_f_name'] . " " . $row_wn_1['ofc_l_name'];
        $wn_1_role = $row_wn_1['ofc_role'];
    }

    //พยาน นิติกร
    $sql_wn_2 = "SELECT * FROM lease le, officer ofc
                            WHERE le.le_sign_ofc2 = ofc.ofc_id AND le.le_id = $le_id ";
    $result_wn_2 = mysqli_query($conn, $sql_wn_2);
    $row_wn_2 = mysqli_fetch_assoc($result_wn_2);
    //ข้อมูลพยาน นิติกร
    {
        $wn_2 = $row_wn_2['ofc_p_name'] . " " . $row_wn_2['ofc_f_name'] . " " . $row_wn_2['ofc_l_name'];
        $wn_2_role = $row_wn_2['ofc_role'];
    }

    //ค่าเช่ารายเดือน
    $sql_mn_month  = "SELECT * FROM money WHERE le_id = $le_id AND mn_type = 3 ";
    $result_mn_month  = mysqli_query($conn, $sql_mn_month);
    $row_mn_month = mysqli_fetch_assoc($result_mn_month);
    //ข้อมูลค่าเช่ารายเดือน
    {
        $month_cost = thainumDigit(number_format($row_mn_month['mn_cost']));
        $year_cost = thainumDigit(number_format($row_mn_month['mn_cost'] * 12));

        $th_cost = convert_thai($row_mn_month['mn_cost']);
        $th_y_cost = convert_thai($row_mn_month['mn_cost'] * 12);
    }

    //ค่าเช่าล่วงหน้า
    $sql_mn_ad = "SELECT * FROM money WHERE le_id = $le_id AND mn_type = 2 ";
    $result_mn_ad  = mysqli_query($conn, $sql_mn_ad);
    $row_mn_ad = mysqli_fetch_assoc($result_mn_ad);
    //ข้อมูค่าเช่าล่วงหน้า
    {
        $ad_cost = thainumDigit(number_format($row_mn_ad['mn_cost']));
        $ad_no = thainumDigit($row_mn_ad['mn_no']); //เลขที่ใบเสร็จ
        $ad_volume = thainumDigit($row_mn_ad['mn_volume']);
        $ad_date_pay = thainumDigit(date('d', strtotime($row_mn_ad["mn_date_pay"])));
        $ad_month_pay = cal_month(date('m', strtotime($row_mn_ad["mn_date_pay"])));

        $ad_year_pay =  date('Y', strtotime($row_mn_ad["mn_date_pay"]));
        $ad_year_pay = thainumDigit($ad_year_pay + 543);

        $th_ad_cost = convert_thai($row_mn_ad['mn_cost']);

        $ad_im = thainumDigit($row_mn_ad['mn_no_important']); //เลขที่ใบสำคัญ
    }

    //ค่าเงินประกัน
    $sql_mn_dp = "SELECT * FROM money WHERE le_id = $le_id AND mn_type = 1 ";
    $result_mn_dp  = mysqli_query($conn, $sql_mn_dp);
    $row_mn_dp = mysqli_fetch_assoc($result_mn_dp);
    //ข้อมูลเงินประกัน
    {
        $dp_cost = thainumDigit(number_format($row_mn_dp['mn_cost']));
        $dp_no = thainumDigit($row_mn_dp['mn_no']); //เลขที่ใบเสร็จ
        $dp_volume = thainumDigit($row_mn_dp['mn_volume']);
        $dp_date_pay = thainumDigit(date('d', strtotime($row_mn_dp["mn_date_pay"])));
        $dp_month_pay = cal_month(date('m', strtotime($row_mn_dp["mn_date_pay"])));

        $dp_year_pay =  date('Y', strtotime($row_mn_dp["mn_date_pay"]));
        $dp_year_pay = thainumDigit($dp_year_pay + 543);

        $th_dp_cost = convert_thai($row_mn_dp['mn_cost']);

        $dp_im = thainumDigit($row_mn_dp['mn_no_important']); //เลขที่ใบสำคัญ
    }
}

//แจ้งเตือนผู้เช่า และเปลี่ยนแปลงสถานะที่ status lease
{
    //ดึงวันที่ เวลา ปัจจุบัน
    date_default_timezone_set('Asia/Bangkok');
    $date = date("Y-m-d");
    $time = date('H:i:s');

    //update status lease
    // ให้ st_pass='1', st_s1='0'
    $q8 = "UPDATE status_lease SET st_pass='1',
                                   st_s1='0',
                                   d_st_pass = '$date',
                                   t_st_pass = '$time'
                                WHERE le_id = $le_id ";
    $result_q8 = mysqli_query($conn, $q8);

    //update lease ว่าเพิ่มสัญญาเช่าแล้ว
    $q7 = "UPDATE lease SET le_status='1' WHERE le_id = $le_id ";
    $result_q7 = mysqli_query($conn, $q7);

    //หาข้อมูลผู้เช่า 
    $sql1 = "SELECT * FROM lease le, tenant t
        WHERE le.le_id = $le_id 
        AND le.tn_id = t.tn_id";
    $result1 = mysqli_query($conn, $sql1);
    $r1 = mysqli_fetch_assoc($result1);

    // email ผู้เช่า
    $tn_email = $r1['tn_email'];
    $le_no1 = $r1['le_no'];
    $detail = "เจ้าหน้าที่ได้ทำการเพิ่ม และตรวจสอบสัญญาเช่า เลขที่สัญญาเช่า $le_no1 สำเร็จแล้ว กรุณาติดต่อเจ้าหน้าที่เพื่อลงนามสัญญาเช่า";
    // เบอร์ผู้เช่า
    $tn_tel = $r1['tn_tel'];

    //email ไปหาผู้เช่า
    require_once "../../../16_func_email_sms/func_email.php";
    $header = "แจ้งเตือน สัญญาเช่า";
    $send_email = sendEmail($tn_email, $header, $detail);

    //SMS ไปหาผู้เช่า
    require_once "../../../16_func_email_sms/func_sms.php";
    $send_sms = sendSMS($tn_tel, $detail);

    if ($result_q7 && $result_q8) {
        echo "<script type='text/javascript'>";
        echo "alert('แจ้งเตือนผู้เช่า สำเร็จ'); ";
        echo "</script>";
    } else {
        echo "<script type='text/javascript'>";
        echo "alert('มีความผิดพลาด');";
        echo "</script>";
    }
}

use Mpdf\Mpdf;

//เอกสารแนบท้าย
{
    require_once __DIR__ . '/vendor/autoload.php';
    $mpdf = new mPDF([
        'default_font_size' => 16,
        'default_font' => 'sarabun'
    ]);

    //Set ตำแหน่งข้อมูล
    {
        $html_last_no = '<div style="position:absolute; top:20px; left:40px; "> เลขที่อ้างอิงสัญญาเช่า : ' . $le_no . '</div>'; //473

        $html_last = '<div style="position:absolute;top:825;left:90px;width:240px; text-align:center;">' . $tn_name . '</div>';
        $html_last .= '<div style="position:absolute;top:852;left:90px;width:240px; text-align:center;">' . $tn_role . '</div>';
        $html_last .= '<div style="position:absolute;top:825;left:390px;width:350px; text-align:center;">' . $boss_name . '</div>';
        $html_last .= '<div style="position:absolute;top:852;left:390px;width:350px; text-align:center;">' . $boss_role . ' ปฏิบัติหน้าที่แทน</div>';

        $html_last .= '<div style="position:absolute;top:994;left:90px;width:240px; text-align:center;">' . $wn_1 . '</div>';
        $html_last .= '<div style="position:absolute;top:1020;left:85px;width:260px; text-align:center;">' . $wn_1_role . '</div>';
        $html_last .= '<div style="position:absolute;top:994;left:400px;width:305px; text-align:center;">' . $wn_2 . '</div>';
        $html_last .= '<div style="position:absolute;top:1020;left:400px;width:305px; text-align:center;">' . $wn_2_role . '</div>';

        $html_last .= '<div style="position:absolute;top:877;left:390px;width:350px; text-align:center;"> อธิการบดีมหาวิทยาลัยเกษตรศาสตร์ </div>';

    }

    // เรียกไฟล์ ข้อตกลงแนบท้าย ที่จะใส่ข้อมูล >> last_file_le_1.pdf
    // นับหน้า
    $pagecount = $mpdf->SetSourceFile('../../../file_uploads/last_file_lease/last_file_le_' . $le_id . '.pdf');

    // for loop แต่ละหน้า
    for ($i = 1; $i <= ($pagecount); $i++) {

        $mpdf->AddPage();
        $import_page = $mpdf->ImportPage($i);
        $mpdf->UseTemplate($import_page);

        // ใส่ เลขที่อ้างอิงสัญญาเช่า ทุกหน้า
        $mpdf->writeHTML($html_last_no);

        // ถ้า ถึงหน้าสุดท้าย ใส่ข้อมูล $html_last
        if ($i == $pagecount) {
            $mpdf->writeHTML($html_last);
        }
    }

    //upload file กลับไปที่ ../../../file_uploads/last_file_lease/last_file_le_' . $le_id . '.pdf
    $mpdf->Output('../../../file_uploads/last_file_lease/last_file_le_' . $le_id . '.pdf');

    //open file
    //$mpdf->Output('เอกสารสัญญาเช่า.pdf', 'I');
}

//เอกสารสัญญาเช่า
//ถ้าที่ mn_volume ของเงินประกัน != ช่องว่างถึงทำ 
// จ่ายแบบใบเสร็จ
if ($row_mn_dp['mn_volume'] != null) {

    require_once __DIR__ . '/vendor/autoload.php';

    $mpdf = new mPDF([
        'default_font_size' => 16,
        'default_font' => 'sarabun'
    ]);

    //Set ตำแหน่งข้อมูล
    {
        // หน้าที่ 1
        $html = '<div style="position:absolute; top:20px; left:40px; "> เลขที่อ้างอิงสัญญาเช่า : ' . $le_no . '</div>';

        $html .= '<div style="position:absolute; top:177px; right: 191px;width:25px; text-align:center;"> ' . $date_today . '</div>';
        $html .= '<div style="position:absolute; top:177px; right: 80px;width:67px; text-align:center;"> ' . $month_today . ' </div>';
        $html .= '<div style="position:absolute;top:205;left:140px;width:60px; text-align:center;"> ' . $year_today . ' </div>';
        $html .= '<div style="position:absolute;top:233;left:140px;"> ' . $boss_name . ' </div>';
        $html .= '<div style="position:absolute;top:261;left:165px;width:231px; text-align:center;"> ' . $boss_role . ' </div>';
        $html .= '<div style="position:absolute;top:289;left:390px;"> ' . $tn_name . ' </div>';
        $html .= '<div style="position:absolute;top:317;left:135px;width:38px; text-align:center;"> ' . $tn_age . ' </div>';
        $html .= '<div style="position:absolute;top:317;left:233px;width:45px; text-align:center;"> ' . $tn_ethnicity . ' </div>';
        $html .= '<div style="position:absolute;top:317;left:325px;width:45px; text-align:center;"> ' . $tn_nationality . ' </div>';
        $html .= '<div style="position:absolute;top:317;left:450px;width:70px; text-align:center;"> ' . $tn_house_no . ' </div>';
        $html .= '<div style="position:absolute;top:317;left:530px;width:55px; text-align:center;"> ' . $tn_moo . ' </div>';
        $html .= '<div style="position:absolute;top:317;left:658px;width:85px; text-align:center;"> ' . $tn_alley . ' </div>';
        $html .= '<div style="position:absolute;top:345;left:140px;width:100px; text-align:center;"> ' . $tn_road . ' </div>';
        $html .= '<div style="position:absolute;top:345;left:300px;width:100px; text-align:center;"> ' . $tn_canton . ' </div>';
        $html .= '<div style="position:absolute;top:345;left:470px;width:105px; text-align:center;">' . $tn_district . ' </div>';
        $html .= '<div style="position:absolute;top:345;left:620px;width:115px; text-align:center;"> ' . $tn_province . ' </div>';

        $html .= '<div style="position:absolute;top:457;left:500px;">' . $area_name . ' </div>';
        $html .= '<div style="position:absolute;top:540;left:575px;width:80px; text-align:center;"> ' . $area_size . '</div>';
        $html .= '<div style="position:absolute;top:568;left:113px;">' . $le_purpose . '</div>';
        $html .= '<div style="position:absolute;top:596;left:195px;width:20px; text-align:center;">  ' . $le_duration . '</div>';
        $html .= '<div style="position:absolute;top:596;left:320px;width:25px; text-align:center;"> ' . $le_start_date . '</div>';
        $html .= '<div style="position:absolute;top:596;left:385px;width:70px; text-align:center;">' . $le_start_month . '</div>';
        $html .= '<div style="position:absolute;top:596;left:495px;width:40px; text-align:center;"> ' . $le_start_year . '</div>';
        $html .= '<div style="position:absolute;top:596;left:585px;width:25px; text-align:center;"> ' . $le_end_date . '</div>';
        $html .= '<div style="position:absolute;top:596;left:645px;width:90px; text-align:center;"> ' . $le_end_month . '</div>';
        $html .= '<div style="position:absolute;top:624;left:140px;width:60px; text-align:center;"> ' . $le_end_year . '</div>';

        $html .= '<div style="position:absolute;top:694;left:630px;width:90px; text-align:center;"> ' . $month_cost . '</div>';
        $html .= '<div style="position:absolute;top:722;left:120px;width:210px; text-align:center;">' . $th_cost . '</div>';
        $html .= '<div style="position:absolute;top:722;left:400px;"> ' . $year_cost . '</div>';
        $html .= '<div style="position:absolute;top:722;left:510px;width:220px; text-align:center;">' . $th_y_cost . '</div>';

        $html .= '<div style="position:absolute;top:820;left:480px;width:70px; text-align:center;"> ' . $ad_cost . '</div>';
        $html .= '<div style="position:absolute;top:848;left:120px;width:220px; text-align:center;"> ' . $th_ad_cost . '</div>';
        $html .= '<div style="position:absolute;top:848;left:580px;width:70px; text-align:center;"> ' . $ad_no . '</div>';
        $html .= '<div style="position:absolute;top:848;left:680px;width:60px; text-align:center;"> ' . $ad_volume . '</div>';
        $html .= '<div style="position:absolute;top:876;left:155px;width:45px; text-align:center;"> ' . $ad_date_pay . '</div>';
        $html .= '<div style="position:absolute;top:876;left:220px;width:120px; text-align:center;"> ' . $ad_month_pay . '</div>';
        $html .= '<div style="position:absolute;top:876;left:375px;width:60px; text-align:center;"> ' . $ad_year_pay . '</div>';

        //หน้าที่ 2
        $html2 .= '<div style="position:absolute; top:20px; left:40px; "> เลขที่อ้างอิงสัญญาเช่า : ' . $le_no . '</div>';

        $html2 .= '<div style="position:absolute;top:131;left:610px;width:90px; text-align:center;"> ' . $dp_cost . '</div>';
        $html2 .= '<div style="position:absolute;top:159;left:120px;width:235px; text-align:center;">' . $th_dp_cost . '</div>';
        $html2 .= '<div style="position:absolute;top:187;left:280px;width:70px; text-align:center;"> ' . $dp_no . '</div>';
        $html2 .= '<div style="position:absolute;top:187;left:385px;width:40px; text-align:center;"> ' . $dp_volume . '</div>';
        $html2 .= '<div style="position:absolute;top:187;left:475px;width:35px; text-align:center;"> ' . $dp_date_pay . '</div>';
        $html2 .= '<div style="position:absolute;top:187;left:550px;width:100px; text-align:center;"> ' . $dp_month_pay . '</div>';
        $html2 .= '<div style="position:absolute;top:187;left:690px;width:40px; text-align:center;"> ' . $dp_year_pay . '</div>';

        //หน้าที่ 5
        $html3 .= '<div style="position:absolute; top:20px; left:40px; "> เลขที่อ้างอิงสัญญาเช่า : ' . $le_no . '</div>';

        $html3 .= '<div style="position:absolute;top:552;left:130px;width:210px; text-align:center;">' . $tn_name . '</div>';
        $html3 .= '<div style="position:absolute;top:579;left:130px;width:210px; text-align:center;">' . $tn_role . '</div>';
        $html3 .= '<div style="position:absolute;top:552;left:420px;width:350px; text-align:center;">' . $boss_name . '</div>';
        $html3 .= '<div style="position:absolute;top:579;left:420px;width:350px; text-align:center;">' . $boss_role . ' ปฏิบัติหน้าที่แทน</div>';
        $html3 .= '<div style="position:absolute;top:722;left:140px;width:210px; text-align:center;">' . $wn_1 . '</div>';
        $html3 .= '<div style="position:absolute;top:749;left:135px;width:260px; text-align:center;">' . $wn_1_role . '</div>';
        $html3 .= '<div style="position:absolute;top:720;left:490px;width:240px; text-align:center;">' . $wn_2 . '</div>';
        $html3 .= '<div style="position:absolute;top:747;left:490px;width:240px; text-align:center;">' . $wn_2_role . '</div>';

        $html3 .= '<div style="position:absolute;top:604;left:420px;width:350px; text-align:center;"> อธิการบดีมหาวิทยาลัยเกษตรศาสตร์ </div>';


        //หน้าที่ 3-4
        $html4 .= '<div style="position:absolute; top:20px; left:40px; "> เลขที่อ้างอิงสัญญาเช่า : ' . $le_no . '</div>';
        $html5 .= '<div style="position:absolute; top:20px; left:40px; "> เลขที่อ้างอิงสัญญาเช่า : ' . $le_no . '</div>';
    }

    // เรียกไฟล์ สัญญาเช่า ที่จะใส่ข้อมูล จาก pdf_file_lease/lease_1_1.pdf
    //นับหน้า
    $pagecount = $mpdf->SetSourceFile('pdf_file_lease/lease_1_1.pdf'); //จากไฟล์ PDF ไหน
    for ($i = 1; $i <= ($pagecount); $i++) {
        $mpdf->AddPage();
        $import_page = $mpdf->ImportPage($i);
        $mpdf->UseTemplate($import_page);

        if ($i == 1) { //หน้าที่ 1
            // ข้อมูลที่ใส่
            $mpdf->writeHTML($html);
        } else if ($i == 2) { //หน้าที่ 2
            // ข้อมูลที่ใส่
            $mpdf->writeHTML($html2);
        } else if ($i == 5) { //หน้าที่ 5
            // ข้อมูลที่ใส่
            $mpdf->writeHTML($html3);
        } else if ($i == 3) { //หน้าที่ 3
            // ข้อมูลที่ใส่
            $mpdf->writeHTML($html4);
        } else if ($i == 4) { //หน้าที่ 4
            // ข้อมูลที่ใส่
            $mpdf->writeHTML($html5);
        }
    }

    //upload file ไปที่ file_uploads/lease_success ตั้งชื่อไฟล์ 64002001021.pdf
    $mpdf->Output('../../../file_uploads/lease_success/' . $le_no2 . '.pdf');

    //open file
    $mpdf->Output('เอกสารสัญญาเช่า.pdf', 'I');
}

//ถ้าเป็นจ่ายแบบใบสำคัญ
else {

    require_once __DIR__ . '/vendor/autoload.php';

    $mpdf = new mPDF([
        'default_font_size' => 16,
        'default_font' => 'sarabun'
    ]);

    //Set ตำแหน่งข้อมูล
    {
        // หน้าที่ 1
        $html = '<div style="position:absolute; top:20px; left:40px; "> เลขที่อ้างอิงสัญญาเช่า : ' . $le_no . '</div>';

        $html .= '<div style="position:absolute; top:177px; right: 191px;width:25px; text-align:center;"> ' . $date_today . '</div>';
        $html .= '<div style="position:absolute; top:177px; right: 80px;width:67px; text-align:center;"> ' . $month_today . ' </div>';
        $html .= '<div style="position:absolute;top:205;left:140px;width:60px; text-align:center;"> ' . $year_today . ' </div>';
        $html .= '<div style="position:absolute;top:233;left:140px;"> ' . $boss_name . ' </div>';
        $html .= '<div style="position:absolute;top:261;left:165px;width:231px; text-align:center;"> ' . $boss_role . ' </div>';
        $html .= '<div style="position:absolute;top:289;left:390px;"> ' . $tn_name . ' </div>';
        $html .= '<div style="position:absolute;top:317;left:135px;width:38px; text-align:center;"> ' . $tn_age . ' </div>';
        $html .= '<div style="position:absolute;top:317;left:233px;width:45px; text-align:center;"> ' . $tn_ethnicity . ' </div>';
        $html .= '<div style="position:absolute;top:317;left:325px;width:45px; text-align:center;"> ' . $tn_nationality . ' </div>';
        $html .= '<div style="position:absolute;top:317;left:450px;width:70px; text-align:center;"> ' . $tn_house_no . ' </div>';
        $html .= '<div style="position:absolute;top:317;left:542px; width:50px; text-align:center;"> ' . $tn_moo . ' </div>';
        $html .= '<div style="position:absolute;top:317;left:658px;width:85px; text-align:center;"> ' . $tn_alley . ' </div>';
        $html .= '<div style="position:absolute;top:345;left:140px;width:100px; text-align:center;"> ' . $tn_road . ' </div>';
        $html .= '<div style="position:absolute;top:345;left:300px;width:100px; text-align:center;"> ' . $tn_canton . ' </div>';
        $html .= '<div style="position:absolute;top:345;left:470px;width:105px; text-align:center;">' . $tn_district . ' </div>';
        $html .= '<div style="position:absolute;top:345;left:620px;width:115px; text-align:center;"> ' . $tn_province . ' </div>';

        $html .= '<div style="position:absolute;top:457;left:500px;">' . $area_name . ' </div>';
        $html .= '<div style="position:absolute;top:540;left:575px;width:80px; text-align:center;"> ' . $area_size . '</div>';
        $html .= '<div style="position:absolute;top:568;left:113px;">' . $le_purpose . '</div>';
        $html .= '<div style="position:absolute;top:596;left:195px;width:20px; text-align:center;">  ' . $le_duration . '</div>';
        $html .= '<div style="position:absolute;top:596;left:320px;width:25px; text-align:center;"> ' . $le_start_date . '</div>';
        $html .= '<div style="position:absolute;top:596;left:385px;width:70px; text-align:center;">' . $le_start_month . '</div>';
        $html .= '<div style="position:absolute;top:596;left:495px;width:40px; text-align:center;"> ' . $le_start_year . '</div>';
        $html .= '<div style="position:absolute;top:596;left:585px;width:25px; text-align:center;"> ' . $le_end_date . '</div>';
        $html .= '<div style="position:absolute;top:596;left:645px;width:90px; text-align:center;"> ' . $le_end_month . '</div>';
        $html .= '<div style="position:absolute;top:624;left:140px;width:60px; text-align:center;"> ' . $le_end_year . '</div>';

        $html .= '<div style="position:absolute;top:694;left:630px;width:90px; text-align:center;"> ' . $month_cost . '</div>';
        $html .= '<div style="position:absolute;top:722;left:120px;width:210px; text-align:center;">' . $th_cost . '</div>';
        $html .= '<div style="position:absolute;top:722;left:400px;"> ' . $year_cost . '</div>';
        $html .= '<div style="position:absolute;top:722;left:510px;width:220px; text-align:center;">' . $th_y_cost . '</div>';

        $html .= '<div style="position:absolute;top:820;left:480px;width:70px; text-align:center;"> ' . $ad_cost . '</div>';
        $html .= '<div style="position:absolute;top:848;left:120px;width:220px; text-align:center;"> ' . $th_ad_cost . '</div>';
        $html .= '<div style="position:absolute;top:848;left:560px;width:180px; text-align:center;"> ' . $ad_im . '</div>';

        $html .= '<div style="position:absolute;top:876;left:155px;width:45px; text-align:center;"> ' . $ad_date_pay . '</div>';
        $html .= '<div style="position:absolute;top:876;left:220px;width:120px; text-align:center;"> ' . $ad_month_pay . '</div>';
        $html .= '<div style="position:absolute;top:876;left:375px;width:60px; text-align:center;"> ' . $ad_year_pay . '</div>';

        //หน้าที่ 2
        $html2 .= '<div style="position:absolute; top:20px; left:40px; "> เลขที่อ้างอิงสัญญาเช่า : ' . $le_no . '</div>';

        $html2 .= '<div style="position:absolute;top:131;left:610px;width:90px; text-align:center;"> ' . $dp_cost . '</div>';
        $html2 .= '<div style="position:absolute;top:159;left:120px;width:235px; text-align:center;">' . $th_dp_cost . '</div>';
        $html2 .= '<div style="position:absolute;top:187;left:210px;width:210px; text-align:center;"> ' . $dp_im . '</div>';

        $html2 .= '<div style="position:absolute;top:187;left:475px;width:35px; text-align:center;"> ' . $dp_date_pay . '</div>';
        $html2 .= '<div style="position:absolute;top:187;left:550px;width:100px; text-align:center;"> ' . $dp_month_pay . '</div>';
        $html2 .= '<div style="position:absolute;top:187;left:690px;width:40px; text-align:center;"> ' . $dp_year_pay . '</div>';

        //หน้าที่ 5
        $html3 .= '<div style="position:absolute; top:20px; left:40px; "> เลขที่อ้างอิงสัญญาเช่า : ' . $le_no . '</div>';

        $html3 .= '<div style="position:absolute;top:552;left:130px;width:210px; text-align:center;">' . $tn_name . '</div>';
        $html3 .= '<div style="position:absolute;top:579;left:130px;width:210px; text-align:center;">' . $tn_role . '</div>';
        $html3 .= '<div style="position:absolute;top:552;left:420px;width:350px; text-align:center;">' . $boss_name . '</div>';
        $html3 .= '<div style="position:absolute;top:579;left:420px;width:350px; text-align:center;">' . $boss_role . ' ปฏิบัติหน้าที่แทน</div>';
        $html3 .= '<div style="position:absolute;top:722;left:140px;width:210px; text-align:center;">' . $wn_1 . '</div>';
        $html3 .= '<div style="position:absolute;top:749;left:135px;width:260px; text-align:center;">' . $wn_1_role . '</div>';
        $html3 .= '<div style="position:absolute;top:720;left:490px;width:240px; text-align:center;">' . $wn_2 . '</div>';
        $html3 .= '<div style="position:absolute;top:747;left:490px;width:240px; text-align:center;">' . $wn_2_role . '</div>';

        $html3 .= '<div style="position:absolute;top:604;left:420px;width:350px; text-align:center;"> อธิการบดีมหาวิทยาลัยเกษตรศาสตร์ </div>';

        //หน้าที่ 3-4
        $html4 .= '<div style="position:absolute; top:20px; left:40px; "> เลขที่อ้างอิงสัญญาเช่า : ' . $le_no . '</div>';
        $html5 .= '<div style="position:absolute; top:20px; left:40px; "> เลขที่อ้างอิงสัญญาเช่า : ' . $le_no . '</div>';
    }


    // เรียกไฟล์ สัญญาเช่า ที่จะใส่ข้อมูล จาก pdf_file_lease/lease_1_1.pdf
    //นับหน้า
    $pagecount = $mpdf->SetSourceFile('pdf_file_lease/lease_1_2.pdf'); //จากไฟล์ PDF ไหน
    for ($i = 1; $i <= ($pagecount); $i++) {
        $mpdf->AddPage();
        $import_page = $mpdf->ImportPage($i);
        $mpdf->UseTemplate($import_page);

        if ($i == 1) { //หน้าที่ 1
            // ข้อมูลที่ใส่
            $mpdf->writeHTML($html);
        } else if ($i == 2) { //หน้าที่ 2
            // ข้อมูลที่ใส่
            $mpdf->writeHTML($html2);
        } else if ($i == 5) { //หน้าที่ 5
            // ข้อมูลที่ใส่
            $mpdf->writeHTML($html3);
        } else if ($i == 3) { //หน้าที่ 3
            // ข้อมูลที่ใส่
            $mpdf->writeHTML($html4);
        } else if ($i == 4) { //หน้าที่ 4
            // ข้อมูลที่ใส่
            $mpdf->writeHTML($html5);
        }
    }
    //upload file ไปที่ file_uploads/lease_success ตั้งชื่อไฟล์ 64002001021.pdf
    $mpdf->Output('../../../file_uploads/lease_success/' . $le_no2 . '.pdf');

    //open file
    $mpdf->Output('เอกสารสัญญาเช่า.pdf', 'I');
}
