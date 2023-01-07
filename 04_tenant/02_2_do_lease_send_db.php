<?php

session_start();
require('../connection.php');

if (!$_SESSION['tn']) {
    header("Location: index.php");
}

//หา le_id ล่าสุดจาก tabel lease 
$sql = "SELECT MAX(le_id) AS maxid FROM lease ";
$result = mysqli_query($conn, $sql);
$ret = mysqli_fetch_assoc($result); // อ่านค่า
$last_id = $ret['maxid']; // คืนค่า id ที่ insert สูงสุด


if (isset($_POST['submit'])) {

    //รับข้อมูล
    //tn_id = id ผู้เช่า
    $id = $_POST["id"]; //tn_id
    //id_card
    $id_card = $_POST["id_card"];
    //ความประสงค์
    $le_why_do = $_POST["le_why_do"];
    $le_status = 0;

    //หา วันเวลาปัจจุบัร
    date_default_timezone_set('Asia/Bangkok');
    $le_date_why_do = date("Y-m-d");
    $le_time_why_do = date('H:i:s');

    //เลขที่อ้างอิงสัญญาเช่า มาจาก ปีปัจจุบัน 2 ตัวท้าย + ID card 3 ตัวท้าย + id ในฐานข้อมูล login_user [tn_id] 3 ตัวท้าย + id lease [le_id] 3 ตัวท้าย

    //ดึง id card ของผู้เช่า 3 ตัวท้าย
    $con_id_card = substr($id_card, -3);

    //คำนวณปี ไทย และดึงสองตัวท้ายมาใช้งาน
    date_default_timezone_set('Asia/Bangkok');
    $d = date('Y') + 543;
    $d = strval($d);
    $d = substr($d, -2);

    //นำ id ล่าสุด +1
    $l = $last_id + 1;
    //check lenght ของ id ล่าสุด
    $check_l = mb_strlen($l);

    //ตรวจสอบว่า ID ผู้ใช้มีความยาวเท่าไหร่
    $check_num = mb_strlen($id);

    //สร้างเลขที่อ้างอิงสัญญาเช่า
    //จัดรูปตัวเลข date[2 ตัวท้าย] + id_card[3 ตัวท้าย] + tn_id + le_id
    {
        //ถ้า ID ผู้เช่า ยาว 1 เพิ่ม 0 2 ตัว ไปข่างหน้า id 
        if ($check_num == 1) {

            //ถ้า ID lease ยาว 1 เพิ่ม 0 2 ตัว ไปข่างหน้า id 
            if ($check_l == 1) {
                // date[2 ตัวท้าย] + id_card[3 ตัวท้าย] + 00 กับ tn_id + 00 กับ le_id
                $id_do = $d . "" . $con_id_card . "00" . $id . "00" . $l;
            }
            //ถ้า ID ยาว 2 เพิ่ม 0 1ตัว
            else if ($check_l == 2) {
                // date[2 ตัวท้าย] + id_card[3 ตัวท้าย] + 00 กับ tn_id + 0 กับ le_id
                $id_do = $d . "" . $con_id_card . "00" . $id . "0" . $l;
            }
            //ถ้า ID ยาว 3 ไม่เพิ่ม 0
            else if ($check_l == 3) {
                // date[2 ตัวท้าย] + id_card[3 ตัวท้าย] + 00 กับ tn_id + le_id
                $id_do = $d . "" . $con_id_card . "00" . $id . "" . $l;
            }
            //ถ้า ID ยาว >3 ไม่เพิ่ม 0 และ ดึง 3 ตัวท้ายไปใช้งาน
            else {
                // date[2 ตัวท้าย] + id_card[3 ตัวท้าย] + 00 กับ tn_id + le_id [3 ตัวท้าย]
                $id_do = $d . "" . $con_id_card . "00" . $id . "" . substr($l, -3);
            }
        }

        //ถ้า ID ผู้เช่า ยาว 2 เพิ่ม 0 1 ตัว ไปข่างหน้า id 
        else if ($check_num == 2) {

            //ถ้า ID lease ยาว 1 เพิ่ม 0 2 ตัว ไปข่างหน้า id 
            if ($check_l == 1) {
                // date[2 ตัวท้าย] + id_card[3 ตัวท้าย] + 0 กับ tn_id + 00 กับ le_id
                $id_do = $d . "" . $con_id_card . "0" . $id . "00" . $l;
            }
            //ถ้า ID ยาว 2 เพิ่ม 0 1ตัว
            else if ($check_l == 2) {
                // date[2 ตัวท้าย] + id_card[3 ตัวท้าย] + 0 กับ tn_id + 0 กับ le_id
                $id_do = $d . "" . $con_id_card . "0" . $id . "0" . $l;
            }
            //ถ้า ID ยาว 3 ไม่เพิ่ม 0
            else if ($check_l == 3) {
                // date[2 ตัวท้าย] + id_card[3 ตัวท้าย] + 0 กับ tn_id + le_id
                $id_do = $d . "" . $con_id_card . "0" . $id . "" . $l;
            }
            //ถ้า ID ยาว >3 ไม่เพิ่ม 0 และ ดึง 3 ตัวท้ายไปใช้งาน
            else {
                // date[2 ตัวท้าย] + id_card[3 ตัวท้าย] + 0 กับ tn_id + le_id [3 ตัวท้าย]
                $id_do = $d . "" . $con_id_card . "0" . $id . "" . substr($l, -3);
            }
        }
        //ถ้า ID ยาว 3 ไม่เพิ่ม 0
        else if ($check_num == 3) {

            //ถ้า ID lease ยาว 1 เพิ่ม 0 2 ตัว ไปข่างหน้า id 
            if ($check_l == 1) {
                // date[2 ตัวท้าย] + id_card[3 ตัวท้าย] + tn_id + 00 กับ le_id
                $id_do = $d . "" . $con_id_card . "" . $id . "00" . $l;
            }
            //ถ้า ID ยาว 2 เพิ่ม 0 1ตัว
            else if ($check_l == 2) {
                // date[2 ตัวท้าย] + id_card[3 ตัวท้าย] + tn_id + 0 กับ le_id
                $id_do = $d . "" . $con_id_card . "" . $id . "0" . $l;
            }
            //ถ้า ID ยาว 3 ไม่เพิ่ม 0
            else if ($check_l == 3) {
                // date[2 ตัวท้าย] + id_card[3 ตัวท้าย] + tn_id + le_id
                $id_do = $d . "" . $con_id_card . "" . $id . "" . $l;
            }
            //ถ้า ID ยาว >3 ไม่เพิ่ม 0 และ ดึง 3 ตัวท้ายไปใช้งาน
            else {
                // date[2 ตัวท้าย] + id_card[3 ตัวท้าย] + tn_id + le_id [3 ตัวท้าย]
                $id_do = $d . "" . $con_id_card . "" . $id . "" . substr($l, -3);
            }
        }

        //ถ้า ID ยาว >3 ไม่เพิ่ม 0 และ ดึง 3 ตัวท้ายไปใช้งาน
        else {

            //ถ้า ID lease ยาว 1 เพิ่ม 0 2 ตัว ไปข่างหน้า id 
            if ($check_l == 1) {
                // date[2 ตัวท้าย] + id_card[3 ตัวท้าย] + tn_id [3 ตัวท้าย] + 00 กับ le_id
                $id_do = $d . "" . $con_id_card . "" . substr($id, -3) . "00" . $l;
            }
            //ถ้า ID ยาว 2 เพิ่ม 0 1ตัว
            else if ($check_l == 2) {
                // date[2 ตัวท้าย] + id_card[3 ตัวท้าย] + tn_id [3 ตัวท้าย] + 0 กับ le_id
                $id_do = $d . "" . $con_id_card . "" . substr($id, -3) . "0" . $l;
            }
            //ถ้า ID ยาว 3 ไม่เพิ่ม 0
            else if ($check_l == 3) {
                // date[2 ตัวท้าย] + id_card[3 ตัวท้าย] + tn_id [3 ตัวท้าย] + le_id
                $id_do = $d . "" . $con_id_card . "" . substr($id, -3) . "" . $l;
            }
            //ถ้า ID ยาว >3 ไม่เพิ่ม 0 และ ดึง 3 ตัวท้ายไปใช้งาน
            else {
                // date[2 ตัวท้าย] + id_card[3 ตัวท้าย] + tn_id[3 ตัวท้าย] + le_id [3 ตัวท้าย]
                $id_do = $d . "" . $con_id_card . "" . substr($id, -3) . "" . substr($l, -3);
            }
        }
    }

    //จองนำข้อมูลฝากไว้ status_lease เพื่อจองพื้นที่ไว้
    $qu1 = "INSERT INTO status_lease (le_id, st_add_lease) VALUE('$l', 0)";
    $result_data2 = mysqli_query($conn, $qu1);

    //จองพื้นที่หน่วยงาน
    {
        $agc_q = "INSERT INTO agency_address (agc_name) VALUE('wait')";
        $result_agc_q = mysqli_query($conn, $agc_q);

        //หา id ล่าสุดใน agency_address db
        $sql_agc = "SELECT MAX(agc_id) AS maxid FROM agency_address ";
        $result_agc_max = mysqli_query($conn, $sql_agc);
        $ret_agc_max = mysqli_fetch_assoc($result_agc_max);
        $agc_max = $ret_agc_max['maxid'];
    }

    //จองพื้นที่ใน lease
    $query_data = "INSERT INTO lease (tn_id, le_no, le_date_why_do, le_time_why_do, le_why_do, le_status, agency_id)
                                    VALUE('$id', '$id_do', '$le_date_why_do', '$le_time_why_do', '$le_why_do', '$le_status', '$agc_max')";
    $result_data = mysqli_query($conn, $query_data);

    //จองพื้นที่ money
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

    //Update สถานะผู้เช่าว่าไม่ได้ใช้ครั้งแรกแล้วนะ
    $sql_update = "UPDATE tenant SET tn_count_use= 1  WHERE tn_id = $id";
    $result = mysqli_query($conn, $sql_update);

    if ($result_data and $result_data2 and $result) {
        echo "<script type='text/javascript'>";
        echo "alert('ส่งเรื่องสำเร็จ ติดต่อเจ้าหน้าที่ภายใน 7 วัน \\nเลขที่อ้างอิงสัญญาเช่า : $id_do'); ";
        echo "window.location='01_tenant_home.php';";
        echo "</script>";
    } else {
        echo "<script type='text/javascript'>";
        echo "alert('มีความผิดพลาด');";
        echo "window.location='01_tenant_home.php';";
        echo "</script>";
    }
}
