<?php
require("../../../connection.php");
require("../../../09_function/cal_budget_year.php");

session_start();


if (!$_SESSION['ofc_mn']) {
    header("Location: ../../../index.php");
}

// รับข้อมูล
$le_id = $_POST["le_id"];
$mn_id = $_POST["mn_id"];
$mn_cost = $_POST["mn_cost"];
$le_duration = $_POST["le_duration"];
$mn_count = $_POST["mn_count"];
$type_pay = $_POST["type_pay"];

$date_pay = $_POST["mn_date_pay"];
$mn_date_pay_month = date("Y-m-d", strtotime("+1 month", strtotime("$date_pay")));
$mn_date_pay_year = date("Y-m-d", strtotime("+1 year", strtotime("$date_pay")));

$mn_type = 3;
$mn_status = 0;

date_default_timezone_set('Asia/Bangkok');
$date_change = date("Y-m-d");
$time_change = date('H:i:s');

//แนบไฟล์
$file = $_FILES['file_1'];

$fileName = $_FILES['file_1']['name'];
$fileTmpName = $_FILES['file_1']['tmp_name'];
$fileSize = $_FILES['file_1']['size'];
$fileError = $_FILES['file_1']['error'];
$fileType = $_FILES['file_1']['type'];

$fileEXT = explode('.', $fileName);
$fileActualExt = strtolower(end($fileEXT));

$allowed = array('pdf');

$budget_year = cal_year($date_pay); //ปีงบประมาณ

// ไอดีผู้ใช้
$ad_id = $_SESSION['ofc_mn'];

if (in_array($fileActualExt, $allowed)) {
    if ($fileError === 0) {

        // ตั้งชื่อไฟล์ MN_01.pdf
        $fileNameNew = "MN_" . $mn_id . "." . $fileActualExt;
        // เก็บไฟล์ที่ folder ../../../file_uploads/money_month_year/
        $fileDestination = '../../../file_uploads/money_month_year/' . $fileNameNew;
        move_uploaded_file($fileTmpName, $fileDestination);

        //แทรกบิล 
        $sql = "UPDATE money SET mn_file = '$fileNameNew',
                                     mn_status = 1,
                                     mn_date_pay_change = '$date_change',
                                     mn_time_pay_change = '$time_change',
                                     mn_budget_year = '$budget_year',
                                     ofc_id = '$ad_id'
                                     WHERE mn_id = $mn_id";
        $result = mysqli_query($conn, $sql);

        //เพิ่มบิลใหม่
        {
            //เพิ่มบิลใหม่ รายเดือน
            if ($type_pay == "month") {

                // 11 เพราะไม่รวมค่าเช่าล่วงหน้า
                if ($le_duration == '1' and $mn_count < 11) {
                    $sql_money = "INSERT INTO money (le_id, mn_type, mn_cost, mn_status, mn_date_pay, type_pay)
                        VALUE ('$le_id','$mn_type', '$mn_cost', '$mn_status', '$mn_date_pay_month', '$type_pay')";
                    $result_money = mysqli_query($conn, $sql_money);
                }

                //เพิ่มบิลใหม่ 2 ปี
                else if ($le_duration == '2' and $mn_count < 23) {
                    $sql_money = "INSERT INTO money (le_id, mn_type, mn_cost, mn_status, mn_date_pay, type_pay)
                            VALUE ('$le_id','$mn_type', '$mn_cost', '$mn_status', '$mn_date_pay_month', '$type_pay')";
                    $result_money = mysqli_query($conn, $sql_money);
                }

                //เพิ่มบิลใหม่ 3 ปี
                else if ($le_duration == '3' and $mn_count < 35) {
                    $sql_money = "INSERT INTO money (le_id, mn_type, mn_cost, mn_status, mn_date_pay, type_pay)
                            VALUE ('$le_id','$mn_type', '$mn_cost', '$mn_status', '$mn_date_pay_month', '$type_pay')";
                    $result_money = mysqli_query($conn, $sql_money);
                }
            }

            //เพิ่มบิลใหม่ รายปี
            if ($type_pay == "year") {

                //เพิ่มบิลใหม่ 2 ปี
                if ($le_duration == '3' and $mn_count < 2) {
                    $sql_money = "INSERT INTO money (le_id, mn_type, mn_cost, mn_status, mn_date_pay, type_pay)
                            VALUE ('$le_id','$mn_type', '$mn_cost', '$mn_status', '$mn_date_pay_year', '$type_pay')";
                    $result_money = mysqli_query($conn, $sql_money);
                }
            }
        }
    } else {
        echo "<script type='text/javascript'>";
        echo "alert('มีความผิดพลาด');";
        echo "window.location='01_2_money_see.php?le_id=$le_id';";
        echo "</script>";
    }
} else {
    echo "<script type='text/javascript'>";
    echo "alert('มีความผิดพลาด');";
    echo "window.location='01_2_money_see.php?le_id=$le_id';";
    echo "</script>";
}

if ($result) {

    //แจ้งเตือนผู้เช่า
    {
        //หาข้อมูลผู้เช่า
        $sql3 = "SELECT * FROM lease l, tenant t 
                        WHERE l.le_id = $le_id
                        AND l.tn_id = t.tn_id";
        $result3 = mysqli_query($conn, $sql3);
        $r3 = mysqli_fetch_assoc($result3);

        if ($r3) {

            $count_pay = $_POST["count_pay"];
            $count_pay += 1;
            $le_no = $_POST["le_no"];

            $email3 = $r3['tn_email'];
            $header3 = "แจ้งเตือน การชำระค่าเช่า รายเดือน/รายปี";
            $detail3 = "เลขที่สัญญาเช่า $le_no : งวดการชำระที่ ' . $count_pay . ' ของวันที่ ' . $date_pay .
                   'ได้ชำระแล้วเมื่อวันที่ ' . $date_change . ' เวลา ' . $time_change";
            $detail4 = "เลขที่สัญญาเช่า $le_no : ชำระค่าเขาของวันที่ ' . $date_pay . 'สำเร็จแล้ว' .";
            $tel3 = $r3['tn_tel'];

            //mail ผู้เช่า
            require_once "../../../16_func_email_sms/func_email.php";
            $send_email3 = sendEmail($email3, $header3, $detail3);

            //SMS ผู้เช่า
            require_once "../../../16_func_email_sms/func_sms.php";
            $send_sms3 = sendSMS($tel3, $detail4);
        }
    }

    echo "<script type='text/javascript'>";
    echo "alert('แก้ไขสถานะการเงิน และ เพิ่มข้อมูลสำเร็จ');";
    // ไปหน้า 01_2_money_see.php และส่ง id สัญญาเช่า ด้วย
    echo "window.location='01_2_money_see.php?le_id=$le_id';";
    echo "</script>";
} else {
    echo "<script type='text/javascript'>";
    echo "alert('มีความผิดพลาด');";
    echo "window.location='01_2_money_see.php?le_id=$le_id';";
    echo "</script>";
}
