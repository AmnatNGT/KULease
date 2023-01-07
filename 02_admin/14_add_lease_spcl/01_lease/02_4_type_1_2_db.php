<?php

require("../../../connection.php");
session_start();

if (!$_SESSION['ofc_add']) {
    header("Location: ../../../index.php");
}


$sql = "SELECT * FROM lease  ORDER BY le_id DESC";
$result = mysqli_query($conn, $sql);
$ret = mysqli_fetch_assoc($result); // อ่านค่า
$last_id = $ret['le_no']; // คืนค่า id ที่ insert สูงสุด

// ถ้า case ยังไม่มีสัญญาเช่าในฐานข้อมูล และต้องการจะเพิ่ม ให้ mock le_id
if ($last_id == 0) {

    //คำนวณปี ไทย และดึงสองตัวท้ายมาใช้งาน
    date_default_timezone_set('Asia/Bangkok');
    $d = date('Y') + 543;
    $d = strval($d);
    $d = substr($d, -2);

    $random = rand(10000000, 99999999);
    $last_id = $d . "" . $random . "" . $last_id + 1;
} else {
    $last_id = $last_id + 1;
}



//จองข้อมูลที่ lease เพิ่มเลขที่อ้างอิงที่ lease ก่อน
$query_data = "INSERT INTO lease (le_no)
                VALUE('$last_id')";
$result_data = mysqli_query($conn, $query_data);

//หา le_id ล่าสุด
$sql2 = "SELECT * FROM lease  ORDER BY le_id DESC";
$result2 = mysqli_query($conn, $sql2);
$ret2 = mysqli_fetch_assoc($result2); // อ่านค่า
$le_id = $ret2['le_id'];


$ad_id = $_SESSION['ofc_add'];
//รับ Post ข้อมูล
$area_id = $_POST['area_name'];
$le_purpose = $_POST['le_purpose'];
$le_duration = $_POST['le_duration'];
$le_start_date = $_POST['le_start_date'];
$le_no_success = $_POST['le_no_success'];

//สร้าง Algor คำนวณวันสิ้นสุด ปีเริ่ม+ระยะเวลา, วันที่เริ่ม - 1
$le_end_date = date("Y-m-d", strtotime("+$le_duration year, -1 day", strtotime("$le_start_date")));

//ดึงวันที่ เวลา ปัจจุบัน
date_default_timezone_set('Asia/Bangkok');
$sign_do_start_lease_date = date("Y-m-d");
$sign_do_start_lease_time = date('H:i:s');


//file เพิ่มเติมของผู้เช่า + เพิ่มข้อมูลลง lease db
{
    $file_ad = $_FILES['oth_file'];
    $fileName_ad = $_FILES['oth_file']['name'];
    $fileTmpName_ad = $_FILES['oth_file']['tmp_name'];
    $fileSize_ad = $_FILES['oth_file']['size'];
    $fileError_ad = $_FILES['oth_file']['error'];
    $fileType_ad = $_FILES['oth_file']['type'];

    $fileEXT_ad = explode('.', $fileName_ad);
    $fileActualExt_ad = strtolower(end($fileEXT_ad));
    $allowed_ad = array('pdf');

    if (in_array($fileActualExt_ad, $allowed_ad)) {
        if ($fileError_ad === 0) {
            $fileNameNew_ad = "oth_file_le_" . $le_id . "." . $fileActualExt_ad;
            $fileDestination_ad = '../../../file_uploads/other_file_lease/' . $fileNameNew_ad;
            move_uploaded_file($fileTmpName_ad, $fileDestination_ad);

            //เพิ่มข้อมูลลง lease db
            $q3 = "UPDATE lease SET le_type='1', 
                                    area_id='$area_id', 
                                    le_purpose='$le_purpose', 
                                    le_duration='$le_duration',
                                    le_start_date='$le_start_date',
                                    le_end_date='$le_end_date',
                                    ad_id = '$ad_id',
                                    le_oth_file = '$fileNameNew_ad',
                                    le_status = '1',
                                    le_no_success = '$le_no_success'
                                    WHERE le_id = $le_id ";
            $result_q3 = mysqli_query($conn, $q3);
        }
    }
}



//insert ข้อมูลไปที่ status lease table
$q5 = "INSERT INTO status_lease (le_id, st_add_lease, st_mn_pay, st_pass, st_s1, st_s2, st_s3, st_boss, st_add_no)
                            VALUE('$le_id','1', '1', '1', '1', '1', '1', '1', '1')";
$result_q5 = mysqli_query($conn, $q5);

//เปลี่ยนแปลงสถานะ status lease
$q8 = "UPDATE status_lease SET st_add_lease='1', 
                                       st_mn_pay='1',
                                       d_ad_le = '$sign_do_start_lease_date',
                                       t_ad_le = '$sign_do_start_lease_time',
                                       st_pass = '1',
                                       st_s1 = '1',
                                       st_s2 = '1',
                                       st_s3 = '1',
                                       st_boss = '1',
                                       st_add_no = '1'
                                       WHERE le_id = $le_id ";
$result_q8 = mysqli_query($conn, $q8);

//จองพื้นที่ money
$l = $le_id;
//ค่าเงินประกัน
{
    $ad_type = 1;
    $q5 = "INSERT INTO money (le_id, mn_type)
                                    VALUE('$l','$ad_type')";
    $result_q5 = mysqli_query($conn, $q5);
}

//จองพื้นที่ money
//ค่าเช่าล่วงหน้า
{
    $type = 2;
    $q5 = "INSERT INTO money (le_id, mn_type)
                            VALUE('$l','$type')";
    $result_q5 = mysqli_query($conn, $q5);
}

//จองพื้นที่ money
//ค่ารายเดือนหรือปี
{
    $type = 3;
    $q5 = "INSERT INTO money (le_id, mn_type)
                            VALUE('$l','$type')";
    $result_q5 = mysqli_query($conn, $q5);
}

//จองพื้นที่ money
//10 % สำหรับสัญญาที่ 5
{
    $type = 4;
    $q5 = "INSERT INTO money (le_id, mn_type)
                            VALUE('$l','$type')";
    $result_q5 = mysqli_query($conn, $q5);
}


//คำนวณวันที่ต้องจ่าย ทุกวันที่ 5 ของทุกเดือน
$cal_le_start_date = $_POST['le_start_date'];
//สมมุติวันที่ 30/1/2022
$date = date("d", strtotime(("$cal_le_start_date")));
//30-5 = 25
$date = $date - 5;
//เดือน + 1 >> 30/2/2022
//และ -วันที่ ที่คำนวณ >> 30-25 = 5 >> 5/2/2022
$date_pay = date("Y-m-d", strtotime("+1 month, -$date day", strtotime("$cal_le_start_date")));


//รับค่าเช่าเดือนละ
$mn_month = $_POST['mn_month'];
$mn_status = 0;
$mn_first_pay = 1;


//update ลง tabel money
$q6 = "UPDATE money SET mn_cost = '$mn_month',
                        mn_date_pay = '$date_pay',
                        mn_first_pay = '$mn_first_pay',
                        mn_status = '$mn_status'
                        WHERE mn_type='3' AND le_id = $le_id ";
$result_q6 = mysqli_query($conn, $q6);


// เพิ่มข้อมูลค่าเช่าล่วงหน้า และเงินประกัน
require("../../../09_function/cal_budget_year.php");
// วัน เวลาปัจจุบัน
date_default_timezone_set('Asia/Bangkok');
$sign_do_start_lease_date = date("Y-m-d");
$sign_do_start_lease_time = date('H:i:s');

//ประเภทการชำระ เงินประกัน
if ($_POST['type_ad'] == null) {
    $type_dp = 'ad1';
}else{
    $type_dp = $_POST['type_ad'];
}

//เงินประกัน ที่เป็นใบเสร็จรับเงิน
if ($type_dp == 'ad2') {

    // CHECK ว่ามีข้อมูลมั้ย
    if ($_POST['mn_deposit']!= null and $_POST['dp_volume']!= null and $_POST['dp_no']!= null and $_POST['dp_date']!= null) {

        // ถ้ามีข้อมูล
        // รับข้อมูล
        $mn_deposit = $_POST['mn_deposit'];
        $dp_volume = $_POST['dp_volume'];
        $dp_no = $_POST['dp_no'];
        $dp_date = $_POST['dp_date'];

        $dp_budget_year = cal_year($dp_date);
        $dp_type = 1;
        $dp_status = 1;

        // file
        $file_dp = $_FILES['dp_file'];
        $fileName_dp = $_FILES['dp_file']['name'];
        $fileTmpName_dp = $_FILES['dp_file']['tmp_name'];
        $fileSize_dp = $_FILES['dp_file']['size'];
        $fileError_dp = $_FILES['dp_file']['error'];
        $fileType_dp = $_FILES['dp_file']['type'];

        $fileEXT_dp = explode('.', $fileName_dp);
        $fileActualExt_dp = strtolower(end($fileEXT_dp));
        $allowed_dp = array('pdf');
        if (in_array($fileActualExt_dp, $allowed_dp)) {
            if ($fileError_dp === 0) {

                // ชื่อไฟล์ DP_1_01_.pdf
                $fileNameNew_dp = "DP_1_" . $le_id . "." . $fileActualExt_dp;
                // เก็บที่ folder ../../../file_uploads/money_dp_ad/
                $fileDestination_dp = '../../../file_uploads/money_dp_ad/' . $fileNameNew_dp;
                move_uploaded_file($fileTmpName_dp, $fileDestination_dp);

                // up ข้อมูลขึ้น table money
                $q4 = "UPDATE money SET mn_cost = '$mn_deposit',
                                    mn_date_pay = '$dp_date',
                                    mn_volume = '$dp_volume',
                                    mn_no = '$dp_no',
                                    mn_file = '$fileNameNew_dp',
                                    mn_status = '$dp_status',
                                    ofc_id = '$ad_id',
                                    mn_date_pay_change = '$sign_do_start_lease_date',
                                    mn_time_pay_change='$sign_do_start_lease_time',
                                    mn_budget_year = '$dp_budget_year'
                                WHERE mn_type='1' AND le_id = $le_id ";
                $result_q4 = mysqli_query($conn, $q4);
            } else {
                echo "There was an error uploading your file!";
            }
        } else {
            echo "You cannot upload files of this type!";
        }
    } else {
        // ถ้าไม่มีข้อมูล
        // up ข้อมูลขึ้น table money
        $q4 = "UPDATE money SET mn_cost = '0',
                                mn_date_pay = null,
                                mn_volume = 'ได้รับยกเว้น',
                                mn_no = 'ได้รับยกเว้น',
                                mn_file = null,
                                mn_status = '100',
                                ofc_id = '$ad_id',
                                mn_date_pay_change = '$sign_do_start_lease_date',
                                mn_time_pay_change='$sign_do_start_lease_time',
                                mn_budget_year = null
                WHERE mn_type='1' AND le_id = $le_id ";
        $result_q4 = mysqli_query($conn, $q4);
    }
}
//เงินประกัน ที่เป็นใบสำคัญ
else if ($type_dp == 'ad1') {

    // CHECK ว่ามีข้อมูลมั้ย
    if ($_POST['mn_deposit']!= null and $_POST['dp_mn_no_im']!= null and $_POST['dp_no']!= null and $_POST['dp_date']!= null) {

        // ถ้ามีข้อมูล
        // รับข้อมูล
        $mn_deposit = $_POST['mn_deposit'];
        $dp_mn_no_im = $_POST['dp_mn_no_im'];
        $dp_date = $_POST['dp_date'];
        $dp_budget_year = cal_year($dp_date);
        $dp_type = 1;
        $dp_status = 1;

        // file
        $file_dp = $_FILES['dp_file'];
        $fileName_dp = $_FILES['dp_file']['name'];
        $fileTmpName_dp = $_FILES['dp_file']['tmp_name'];
        $fileSize_dp = $_FILES['dp_file']['size'];
        $fileError_dp = $_FILES['dp_file']['error'];
        $fileType_dp = $_FILES['dp_file']['type'];

        $fileEXT_dp = explode('.', $fileName_dp);
        $fileActualExt_dp = strtolower(end($fileEXT_dp));
        $allowed_dp = array('pdf');
        if (in_array($fileActualExt_dp, $allowed_dp)) {
            if ($fileError_dp === 0) {

                // ชื่อไฟล์ DP_1_01_.pdf// ชื่อไฟล์ DP_1_01_.pdf
                $fileNameNew_dp = "DP_1_" . $le_id . "." . $fileActualExt_dp;
                // เก็บที่ folder ../../../file_uploads/money_dp_ad/
                $fileDestination_dp = '../../../file_uploads/money_dp_ad/' . $fileNameNew_dp;
                move_uploaded_file($fileTmpName_dp, $fileDestination_dp);

                // up ข้อมูลขึ้น table money
                $q4 = "UPDATE money SET mn_cost = '$mn_deposit',
                                    mn_date_pay = '$dp_date',
                                    mn_no_important = '$dp_mn_no_im',
                                    mn_file = '$fileNameNew_dp',
                                    mn_status = '$dp_status',
                                    ofc_id = '$ad_id',
                                    mn_date_pay_change = '$sign_do_start_lease_date',
                                    mn_time_pay_change='$sign_do_start_lease_time',
                                    mn_budget_year = '$dp_budget_year'
                                WHERE mn_type='1' AND le_id = $le_id ";
                $result_q4 = mysqli_query($conn, $q4);
            } else {
                echo "There was an error uploading your file!";
            }
        } else {
            echo "You cannot upload files of this type!";
        }
    } else {
        // ถ้าไม่มีข้อมูล
        // up ข้อมูลขึ้น table money
        $q4 = "UPDATE money SET mn_cost = '0',
                        mn_date_pay = null,
                        mn_no_important = 'ได้รับยกเว้น',
                        mn_file = null,
                        mn_status = '100',
                        ofc_id = '$ad_id',
                        mn_date_pay_change = '$sign_do_start_lease_date',
                        mn_time_pay_change='$sign_do_start_lease_time',
                        mn_budget_year = null
                    WHERE mn_type='1' AND le_id = $le_id ";
        $result_q4 = mysqli_query($conn, $q4);
    }
}

// ประเภทการชำระ ค่าเช่าล่วงหน้า
if ($_POST['type_ad'] == null) {
    $type_ad = 'ad1';
}else{
    $type_ad = $_POST['type_ad'];
}

//ค่าเช่าล่วงหน้า ที่เป็นใบเสร็จรับเงิน
if ($type_ad == 'ad2') {

    // CHECK ว่ามีข้อมูลมั้ย
    if ($_POST['mn_advance']!= null and $_POST['ad_volume']!= null and $_POST['ad_no']!= null and $_POST['ad_date']!= null) {

        // ถ้ามีข้อมูล
        // รับข้อมูล
        $mn_advance = $_POST['mn_advance'];
        $ad_volume = $_POST['ad_volume'];
        $ad_no = $_POST['ad_no'];
        $ad_date = $_POST['ad_date'];

        $ad_budget_year = cal_year($ad_date);
        $ad_type = 2;
        $ad_status = 1;

        // ไฟล์
        $file_ad = $_FILES['ad_file'];
        $fileName_ad = $_FILES['ad_file']['name'];
        $fileTmpName_ad = $_FILES['ad_file']['tmp_name'];
        $fileSize_ad = $_FILES['ad_file']['size'];
        $fileError_ad = $_FILES['ad_file']['error'];
        $fileType_ad = $_FILES['ad_file']['type'];

        $fileEXT_ad = explode('.', $fileName_ad);
        $fileActualExt_ad = strtolower(end($fileEXT_ad));
        $allowed_ad = array('pdf');
        if (in_array($fileActualExt_ad, $allowed_ad)) {
            if ($fileError_ad === 0) {

                // ชื่อ ไฟล์ AD_1_2.pdf
                $fileNameNew_ad = "AD_1_" . $le_id . "." . $fileActualExt_ad;
                // เก็บไฟล์ที่ 
                $fileDestination_ad = '../../../file_uploads/money_dp_ad/' . $fileNameNew_ad;
                move_uploaded_file($fileTmpName_ad, $fileDestination_ad);

                // up ข้อมูลขึ้น table money
                $q5 = "UPDATE money SET mn_cost = '$mn_advance',
                                    mn_date_pay = '$ad_date',
                                    mn_volume = '$ad_volume',
                                    mn_no = '$ad_no',
                                    mn_file = '$fileNameNew_ad',
                                    mn_status = '$ad_status',
                                    ofc_id = '$ad_id',
                                    mn_date_pay_change = '$sign_do_start_lease_date',
                                    mn_time_pay_change='$sign_do_start_lease_time',
                                    mn_budget_year = '$ad_budget_year'
                                WHERE mn_type='2' AND le_id = $le_id ";

                $result_q5 = mysqli_query($conn, $q5);
            } else {
                echo "There was an error uploading your file!";
            }
        } else {
            echo "You cannot upload files of this type!";
        }
    } else {
        // ถ้าไม่มีข้อมูล
        // up ข้อมูลขึ้น table money
        $q5 = "UPDATE money SET mn_cost = '0',
                                    mn_date_pay = null,
                                    mn_volume = '$ad_volume',
                                    mn_no = 'ได้รับยกเว้น',
                                    mn_file = null,
                                    mn_status = '100',
                                    ofc_id = '$ad_id',
                                    mn_date_pay_change = '$sign_do_start_lease_date',
                                    mn_time_pay_change='$sign_do_start_lease_time',
                                    mn_budget_year = null
                                WHERE mn_type='2' AND le_id = $le_id ";

        $result_q5 = mysqli_query($conn, $q5);
    }
}
//เงินประกัน ที่เป็นใบสำคัญ
else if ($type_ad == 'ad1') {

    // CHECK ว่ามีข้อมูลมั้ย
    if ($_POST['mn_advance']!= null and $_POST['ad_mn_no_im']!= null and $_POST['ad_date']!= null) {

        // ถ้ามีข้อมูล
        // รับข้อมูล
        $mn_advance = $_POST['mn_advance'];
        $ad_mn_no_im = $_POST['ad_mn_no_im'];
        $ad_date = $_POST['ad_date'];
        $ad_budget_year = cal_year($ad_date);
        $ad_type = 2;
        $ad_status = 1;

        // ไฟล์
        $file_ad = $_FILES['ad_file'];
        $fileName_ad = $_FILES['ad_file']['name'];
        $fileTmpName_ad = $_FILES['ad_file']['tmp_name'];
        $fileSize_ad = $_FILES['ad_file']['size'];
        $fileError_ad = $_FILES['ad_file']['error'];
        $fileType_ad = $_FILES['ad_file']['type'];

        $fileEXT_ad = explode('.', $fileName_ad);
        $fileActualExt_ad = strtolower(end($fileEXT_ad));
        $allowed_ad = array('pdf');
        if (in_array($fileActualExt_ad, $allowed_ad)) {
            if ($fileError_ad === 0) {

                // ชื่อ ไฟล์
                $fileNameNew_ad = "AD_1_" . $le_id . "." . $fileActualExt_ad;
                // เก็บไฟล์ที่ 
                $fileDestination_ad = '../../../file_uploads/money_dp_ad/' . $fileNameNew_ad;
                move_uploaded_file($fileTmpName_ad, $fileDestination_ad);

                // up ข้อมูลขึ้น table money
                $q5 = "UPDATE money SET mn_cost = '$mn_advance',
                                    mn_date_pay = '$ad_date',
                                    mn_no_important = '$ad_mn_no_im',
                                    mn_file = '$fileNameNew_ad',
                                    mn_status = '$ad_status',
                                    ofc_id = '$ad_id',
                                    mn_date_pay_change = '$sign_do_start_lease_date',
                                    mn_time_pay_change='$sign_do_start_lease_time',
                                    mn_budget_year = '$ad_budget_year'
                                WHERE mn_type='2' AND le_id = $le_id ";

                $result_q5 = mysqli_query($conn, $q5);
            } else {
                echo "There was an error uploading your file!";
            }
        } else {
            echo "You cannot upload files of this type!";
        }
    } else {
        // ถ้าไม่มีข้อมูล
        // up ข้อมูลขึ้น table money
        $q5 = "UPDATE money SET mn_cost = '0',
                                    mn_date_pay = null,
                                    mn_no_important = 'ได้รับยกเว้น',
                                    mn_file = null,
                                    mn_status = '100',
                                    ofc_id = '$ad_id',
                                    mn_date_pay_change = '$sign_do_start_lease_date',
                                    mn_time_pay_change='$sign_do_start_lease_time',
                                    mn_budget_year = null
                                WHERE mn_type='2' AND le_id = $le_id ";

        $result_q5 = mysqli_query($conn, $q5);
    }
}


//แก้ไขสถานะพื้นที่เช่า =1
{
    $q7 = "UPDATE area SET area_status='1'
                       WHERE area_id = $area_id ";
    $result_q7 = mysqli_query($conn, $q7);
}

//ไปหน้าถัดไป
echo "<script type='text/javascript'>";
echo "alert('เพิ่มข้อมูลสัญญาเช่า: ประเภทสัญญาเพื่อร้านค้าหรือพาณิชย์ (สัญญาเช่าพิเศษ) สำเร็จ \\nเลขที่อ้างอิงสัญญาเช่า : $last_id'); ";
echo "window.location='../02_2_type.php';";
echo "</script>";
