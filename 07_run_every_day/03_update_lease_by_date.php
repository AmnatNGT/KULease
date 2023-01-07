<?php

//ใช้ cron job ให้ code หน้าที่รันเอง เมื่อเวลาใด
//update by day

session_start();
require_once "../connection.php";

//ตรวจสอบสถานะสัญญาเช่า
{ //เวลา
    date_default_timezone_set('Asia/Bangkok');
    $day_today = date("Y-m-d", strtotime("today")); //today

    $day_today_ch = date_create(date("Y-m-d", strtotime("today"))); //use in date diff


    //ดึงข้อมูลทั้งหมดจาก lease มา
    $sql = "SELECT * FROM lease ";
    $result = mysqli_query($conn, $sql);

    //แสดงข้อมูล และแจ้งเตือน
    while ($row = mysqli_fetch_assoc($result)) {

        $le_no = $row['le_no'];

        if ($row['le_type'] == 1) {
            $ty = 'เพื่อร้านค้าหรือพาณิชย์';
        } else if ($row['le_type'] == 2) {
            $ty = 'เพื่องานบริการ';
        } else if ($row['le_type'] == 3) {
            $ty = 'เพื่องานวิจัย/การเรียนการสอน';
        } else if ($row['le_type'] == 4) {
            $ty = 'เพื่อที่พักอาศัย';
        } else if ($row['le_type'] == 5) {
            $ty = 'เพื่อโรงอาหาร';
        }

        $le_date = date_create($row['le_end_date']); //2013-03-15 ดึงวันสิ้นสุดสัญญาเช่า
        $date_diff = date_diff($day_today_ch, $le_date); // คำนวณวันว่าเหลือกี่วัน

        //แจ้งเตือนผู้เช่า 120 วัน
        if ($date_diff->format("%R%a") == 120) {
            //แจ้งเตือนผ่าน
            //หาข้อมูล tenant
            $sql_ch_120 = "SELECT * FROM lease l, tenant t
                        WHERE l.le_id = $le_id
                        AND l.tn_id = t.tn_id ";
            $result_ch_120 = mysqli_query($conn, $sql_ch_120);
            $row_ch_120 = mysqli_fetch_assoc($result_ch_120);

            //ส่งเมลล์ไปหาผู้เช่า
            {
                //mailer
                require_once "../16_func_email_sms/func_email.php";
                $header = "แจ้งเตือนใกล้หมดอายุสัญญาเช่า";
                $detail = "สัญญาเช่า เลขที่สัญญาเช่า : ' . $le_no . 'ใกล้หมดอายุภายใน 120 วัน ";
                $tn_email = $row_ch_120['tn_email'];
                $send_email = sendEmail($tn_email, $header, $otp);

                //SMS
                require_once "../16_func_email_sms/func_sms.php";
                $tn_tel = $row_ch_120['tn_tel'];
                $send_sms = sendSMS($tn_tel, $detail);
            }

            //แจ้งเตือนเจ้าหน้าที่
            {
                //ดึงข้อมูลเจ้าหน้าที่
                $sql_ofc_1 = "SELECT * FROM officer 
                                WHERE ofc_status_use = 1
                                AND ofc_type = 1
                                AND ofc_type = 2
                                AND ofc_type = 3";
                $result_ofc_1 = mysqli_query($conn, $sql_ofc_1);

                while ($row_ofc_1 = mysqli_fetch_assoc($result_ofc_1)) {

                    //mailer
                    require_once "../16_func_email_sms/func_email.php";
                    $header = "แจ้งเตือนใกล้หมดอายุสัญญาเช่า";
                    $detail = "สัญญาเช่า ประเภทสัญญาเช่า '.$ty.' เลขที่สัญญาเช่า : ' . $le_no . ' ใกล้หมดอายุภายใน 120 วัน";
                    $tn_email = $row_ofc_1['ofc_email'];
                    $send_email = sendEmail($tn_email, $header, $otp);

                    //SMS
                    require_once "../16_func_email_sms/func_sms.php";
                    $tn_tel = $row_ofc_1['ofc_tel'];
                    $send_sms = sendSMS($tn_tel, $detail);
                }
            }


            //แจ้งเตือน Admin
            {
                //ดึงข้อมูลเจ้าหน้าที่
                $sql_ad_1 = "SELECT * FROM admin";
                $result_ad_1 = mysqli_query($conn, $sql_ad_1);

                while ($row_ad_1 = mysqli_fetch_assoc($result_ad_1)) {

                    //mailer
                    require_once "../16_func_email_sms/func_email.php";
                    $header = "แจ้งเตือนใกล้หมดอายุสัญญาเช่า";
                    $detail = "สัญญาเช่า ประเภทสัญญาเช่า '.$ty.' เลขที่สัญญาเช่า : ' . $le_no . ' ใกล้หมดอายุภายใน 120 วัน";
                    $tn_email = $row_ad_1['ad_email'];
                    $send_email = sendEmail($tn_email, $header, $otp);

                    //SMS
                    require_once "../16_func_email_sms/func_sms.php";
                    $tn_tel = $row_ad_1['ad_tel'];
                    $send_sms = sendSMS($tn_tel, $detail);
                }
            }
        }

        //แจ้งเตือนผู้เช่า 60 วัน
        else if ($date_diff->format("%R%a") == 60) {
            //แจ้งเตือนผ่าน
            //หาข้อมูล tenant
            $sql_ch_60 = "SELECT * FROM lease l, tenant t
                        WHERE l.le_id = $le_id
                        AND l.tn_id = t.tn_id ";
            $result_ch_60 = mysqli_query($conn, $sql_ch_60);
            $row_ch_60 = mysqli_fetch_assoc($result_ch_60);

            //ส่งเมลล์ไปหาผู้เช่า
            {
                //mailer
                require_once "../16_func_email_sms/func_email.php";
                $header = "แจ้งเตือนใกล้หมดอายุสัญญาเช่า";
                $detail = "สัญญาเช่า เลขที่สัญญาเช่า : ' . $le_no . 'ใกล้หมดอายุภายใน 60 วัน ";
                $tn_email = $row_ch_60['tn_email'];
                $send_email = sendEmail($tn_email, $header, $otp);

                //SMS
                require_once "../16_func_email_sms/func_sms.php";
                $tn_tel = $row_ch_60['tn_tel'];
                $send_sms = sendSMS($tn_tel, $detail);
            }

            //แจ้งเตือนเจ้าหน้าที่
            {
                //ดึงข้อมูลเจ้าหน้าที่
                $sql_ofc_1 = "SELECT * FROM officer 
                                WHERE ofc_status_use = 1
                                AND ofc_type = 1
                                AND ofc_type = 2
                                AND ofc_type = 3";
                $result_ofc_1 = mysqli_query($conn, $sql_ofc_1);

                while ($row_ofc_1 = mysqli_fetch_assoc($result_ofc_1)) {

                    //mailer
                    require_once "../16_func_email_sms/func_email.php";
                    $header = "แจ้งเตือนใกล้หมดอายุสัญญาเช่า";
                    $detail = "สัญญาเช่า ประเภทสัญญาเช่า '.$ty.' เลขที่สัญญาเช่า : ' . $le_no . ' ใกล้หมดอายุภายใน 60 วัน";
                    $tn_email = $row_ofc_1['ofc_email'];
                    $send_email = sendEmail($tn_email, $header, $otp);

                    //SMS
                    require_once "../16_func_email_sms/func_sms.php";
                    $tn_tel = $row_ofc_1['ofc_tel'];
                    $send_sms = sendSMS($tn_tel, $detail);
                }
            }


            //แจ้งเตือน Admin
            {
                //ดึงข้อมูลเจ้าหน้าที่
                $sql_ad_1 = "SELECT * FROM admin";
                $result_ad_1 = mysqli_query($conn, $sql_ad_1);

                while ($row_ad_1 = mysqli_fetch_assoc($result_ad_1)) {

                    //mailer
                    require_once "../16_func_email_sms/func_email.php";
                    $header = "แจ้งเตือนใกล้หมดอายุสัญญาเช่า";
                    $detail = "สัญญาเช่า ประเภทสัญญาเช่า '.$ty.' เลขที่สัญญาเช่า : ' . $le_no . ' ใกล้หมดอายุภายใน 60 วัน";
                    $tn_email = $row_ad_1['ad_email'];
                    $send_email = sendEmail($tn_email, $header, $otp);

                    //SMS
                    require_once "../16_func_email_sms/func_sms.php";
                    $tn_tel = $row_ad_1['ad_tel'];
                    $send_sms = sendSMS($tn_tel, $detail);
                }
            }
        }

        //แจ้งเตือนผู้เช่า 30 วัน
        else if ($date_diff->format("%R%a") == 30) {
            //แจ้งเตือนผ่าน
            //หาข้อมูล tenant
            $sql_ch_30 = "SELECT * FROM lease l, tenant t
                                WHERE l.le_id = $le_id
                                AND l.tn_id = t.tn_id ";
            $result_ch_30 = mysqli_query($conn, $sql_ch_30);
            $row_ch_30 = mysqli_fetch_assoc($result_ch_30);

            //ส่งเมลล์ไปหาผู้เช่า
            {
                //mailer
                require_once "../16_func_email_sms/func_email.php";
                $header = "แจ้งเตือนใกล้หมดอายุสัญญาเช่า";
                $detail = "สัญญาเช่า เลขที่สัญญาเช่า : ' . $le_no . 'ใกล้หมดอายุภายใน 60 วัน ";
                $tn_email = $row_ch_30['tn_email'];
                $send_email = sendEmail($tn_email, $header, $otp);

                //SMS
                require_once "../16_func_email_sms/func_sms.php";
                $tn_tel = $row_ch_30['tn_tel'];
                $send_sms = sendSMS($tn_tel, $detail);
            }

            //แจ้งเตือนเจ้าหน้าที่
            {
                //ดึงข้อมูลเจ้าหน้าที่
                $sql_ofc_1 = "SELECT * FROM officer 
                                WHERE ofc_status_use = 1
                                AND ofc_type = 1
                                AND ofc_type = 2
                                AND ofc_type = 3";
                $result_ofc_1 = mysqli_query($conn, $sql_ofc_1);

                while ($row_ofc_1 = mysqli_fetch_assoc($result_ofc_1)) {

                    //mailer
                    require_once "../16_func_email_sms/func_email.php";
                    $header = "แจ้งเตือนใกล้หมดอายุสัญญาเช่า";
                    $detail = "สัญญาเช่า ประเภทสัญญาเช่า '.$ty.' เลขที่สัญญาเช่า : ' . $le_no . ' ใกล้หมดอายุภายใน 60 วัน";
                    $tn_email = $row_ofc_1['ofc_email'];
                    $send_email = sendEmail($tn_email, $header, $otp);

                    //SMS
                    require_once "../16_func_email_sms/func_sms.php";
                    $tn_tel = $row_ofc_1['ofc_tel'];
                    $send_sms = sendSMS($tn_tel, $detail);
                }
            }


            //แจ้งเตือน Admin
            {
                //ดึงข้อมูลเจ้าหน้าที่
                $sql_ad_1 = "SELECT * FROM admin";
                $result_ad_1 = mysqli_query($conn, $sql_ad_1);

                while ($row_ad_1 = mysqli_fetch_assoc($result_ad_1)) {

                    //mailer
                    require_once "../16_func_email_sms/func_email.php";
                    $header = "แจ้งเตือนใกล้หมดอายุสัญญาเช่า";
                    $detail = "สัญญาเช่า ประเภทสัญญาเช่า '.$ty.' เลขที่สัญญาเช่า : ' . $le_no . ' ใกล้หมดอายุภายใน 60 วัน";
                    $tn_email = $row_ad_1['ad_email'];
                    $send_email = sendEmail($tn_email, $header, $otp);

                    //SMS
                    require_once "../16_func_email_sms/func_sms.php";
                    $tn_tel = $row_ad_1['ad_tel'];
                    $send_sms = sendSMS($tn_tel, $detail);
                }
            }
        }

        //ถ้าวันที่สิ้นสุดสัญญา == วันนี้ ถ้าหมดสัญญาเช่า
        else if ($row['le_end_date'] == $day_today and $row['le_status'] = 1) {

            //update สถานะสัญญาเป็นหมดอายุสัญญา
            $le_id = $row['le_id'];
            $q1 = " UPDATE lease SET le_status=2 WHERE le_id = $le_id";
            $result_q1 = mysqli_query($conn, $q1);

            //นำ area id จาก lease database มา
            $area_id = $row['area_id'];

            //update สถานะที่ area database ที่ l.area_id = ar.area_id
            $q2 = " UPDATE area SET area_status=0 WHERE area_id = $area_id ";
            $result_q2 = mysqli_query($conn, $q2);

            //แจ้งเตือนผ่าน
            //หาข้อมูล tenant
            $sql_ch = "SELECT * FROM lease l, tenant t
                        WHERE l.le_id = $le_id
                        AND l.tn_id = t.tn_id ";
            $result_ch = mysqli_query($conn, $sql_ch);
            $row_ch = mysqli_fetch_assoc($result_ch);

            //ส่งเมลล์ไปหาผู้เช่า
            {
                //mailer
                require_once "../16_func_email_sms/func_email.php";
                $header = "แจ้งเตือนหมดอายุสัญญาเช่า";
                $detail = "สัญญาเช่า เลขที่สัญญาเช่า : ' . $le_no . 'หมดอายุสัญญาเช่าแล้ว ";
                $tn_email = $row_ch['tn_email'];
                $send_email = sendEmail($tn_email, $header, $otp);

                //SMS
                require_once "../16_func_email_sms/func_sms.php";
                $tn_tel = $row_ch['tn_tel'];
                $send_sms = sendSMS($tn_tel, $detail);
            }

            //แจ้งเตือนเจ้าหน้าที่
            {
                //ดึงข้อมูลเจ้าหน้าที่
                $sql_ofc_1 = "SELECT * FROM officer 
                                WHERE ofc_status_use = 1
                                AND ofc_type = 1
                                AND ofc_type = 2
                                AND ofc_type = 3";
                $result_ofc_1 = mysqli_query($conn, $sql_ofc_1);

                while ($row_ofc_1 = mysqli_fetch_assoc($result_ofc_1)) {

                    //mailer
                    require_once "../16_func_email_sms/func_email.php";
                    $header = "แจ้งเตือนหมดอายุสัญญาเช่า";
                    $detail = "สัญญาเช่า ประเภทสัญญาเช่า '.$ty.' เลขที่สัญญาเช่า : ' . $le_no . ' หมดอายุสัญญาเช่าแล้ว";
                    $tn_email = $row_ofc_1['ofc_email'];
                    $send_email = sendEmail($tn_email, $header, $otp);

                    //SMS
                    require_once "../16_func_email_sms/func_sms.php";
                    $tn_tel = $row_ofc_1['ofc_tel'];
                    $send_sms = sendSMS($tn_tel, $detail);
                }
            }

            //แจ้งเตือน Admin
            {
                //ดึงข้อมูลเจ้าหน้าที่
                $sql_ad_1 = "SELECT * FROM admin";
                $result_ad_1 = mysqli_query($conn, $sql_ad_1);

                while ($row_ad_1 = mysqli_fetch_assoc($result_ad_1)) {

                    //mailer
                    require_once "../16_func_email_sms/func_email.php";
                    $header = "แจ้งเตือนหมดอายุสัญญาเช่า";
                    $detail = "สัญญาเช่า ประเภทสัญญาเช่า '.$ty.' เลขที่สัญญาเช่า : ' . $le_no . ' หมดอายุสัญญาเช่าแล้ว";
                    $tn_email = $row_ad_1['ad_email'];
                    $send_email = sendEmail($tn_email, $header, $otp);

                    //SMS
                    require_once "../16_func_email_sms/func_sms.php";
                    $tn_tel = $row_ad_1['ad_tel'];
                    $send_sms = sendSMS($tn_tel, $detail);
                }
            }
        }
    }
}


//แจ้งเตือนจ่ายเงินรายเดือน
{
    //เวลา
    date_default_timezone_set('Asia/Bangkok');
    $day_today = date("Y-m-d", strtotime("today")); //today

    $day_chk = date("Y-m-d", strtotime("first day of this month")); //หาต้นเดือนของเดือนปัจจุบัน first day of this month

    //แจ้งเตือนผู้เช่าประเภทสัญญาเช่าที่ 1 รายเดือน
    {
        $sql_1 = "SELECT * FROM lease l, tenant t, money m
        WHERE l.le_type = 1 AND
              l.le_status = 1 AND
              m.le_id = l.le_id AND
              m.mn_type = 3 AND
              m.mn_status = 0 AND
              l.tn_id = t.tn_id";
        $result_1 = mysqli_query($conn, $sql_1);

        if (isset($result)) {
            //ตรวจสอบวันที่
            if ($day_today == $day_chk) {

                while ($row_1 = mysqli_fetch_assoc($result_1)) {
                    $date_pay_1 = $row_1['mn_date_pay'];
                    $date_pay_1 = date("d/m/Y", strtotime($date_pay_1));

                    //ส่งเมลล์ไปหาผู้เช่า
                    {
                        //mailer
                        require_once "../16_func_email_sms/func_email.php";
                        $header = "แจ้งเตือนการชำระเงิน";
                        $detail = "เลขที่อ้างอิงสัญญาเช่า $le_no ชำระค่าเช่าภายในวันที่ $date_pay_1";
                        $tn_email = $row_1['tn_email'];
                        $send_email = sendEmail($tn_email, $header, $otp);

                        //SMS
                        require_once "../16_func_email_sms/func_sms.php";
                        $tn_tel = $row_1['tn_tel'];
                        $send_sms = sendSMS($tn_tel, $detail);
                    }
                }
            }
        }
    }

    //แจ้งเตือนผู้เช่าประเภทสัญญาเช่าที่ 2 รายเดือน
    {
        $sql_1 = "SELECT * FROM lease l, tenant t, money m
            WHERE l.le_type = 2 AND
                  l.le_status = 1 AND
                  m.le_id = l.le_id AND
                  m.mn_status = 0 AND
                  m.mn_type = 3 AND
                  l.tn_id = t.tn_id AND
                  m.type_pay = 'month' ";
        $result_1 = mysqli_query($conn, $sql_1);

        if (isset($result_1)) {
            //ตรวจสอบวันที่
            if ($day_today == $day_chk) {

                while ($row_1 = mysqli_fetch_assoc($result_1)) {
                    $date_pay_1 = $row_1['mn_date_pay'];
                    $date_pay_1 = date("d/m/Y", strtotime($date_pay_1));

                    //ส่งเมลล์ไปหาผู้เช่า
                    {
                        //mailer
                        require_once "../16_func_email_sms/func_email.php";
                        $header = "แจ้งเตือนการชำระเงิน";
                        $detail = "เลขที่อ้างอิงสัญญาเช่า $le_no ชำระค่าเช่าภายในวันที่ $date_pay_1";
                        $tn_email = $row_1['tn_email'];
                        $send_email = sendEmail($tn_email, $header, $otp);

                        //SMS
                        require_once "../16_func_email_sms/func_sms.php";
                        $tn_tel = $row_1['tn_tel'];
                        $send_sms = sendSMS($tn_tel, $detail);
                    }
                }
            }
        }
    }

    //แจ้งเตือนผู้เช่าประเภทสัญญาเช่าที่ 4 รายเดือน
    {
        $sql_1 = "SELECT * FROM lease l, tenant t, money m
        WHERE l.le_type = 4 AND
              l.le_status = 1 AND
              m.le_id = l.le_id AND
              m.mn_status = 0 AND
              m.mn_type = 3 AND
              l.tn_id = t.tn_id";
        $result_1 = mysqli_query($conn, $sql_1);

        if (isset($result)) {
            //ตรวจสอบวันที่
            if ($day_today == $day_chk) {

                while ($row_1 = mysqli_fetch_assoc($result_1)) {
                    $date_pay_1 = $row_1['mn_date_pay'];
                    $date_pay_1 = date("d/m/Y", strtotime($date_pay_1));

                    //ส่งเมลล์ไปหาผู้เช่า
                    {
                        //mailer
                        require_once "../16_func_email_sms/func_email.php";
                        $header = "แจ้งเตือนการชำระเงิน";
                        $detail = "เลขที่อ้างอิงสัญญาเช่า $le_no ชำระค่าเช่าภายในวันที่ $date_pay_1";
                        $tn_email = $row_1['tn_email'];
                        $send_email = sendEmail($tn_email, $header, $otp);

                        //SMS
                        require_once "../16_func_email_sms/func_sms.php";
                        $tn_tel = $row_1['tn_tel'];
                        $send_sms = sendSMS($tn_tel, $detail);
                    }
                }
            }
        }
    }
}


//แจ้งเตือนจ่ายเงินรายปี
date_default_timezone_set('Asia/Bangkok');
$day_today = date_create(date("Y-m-d", strtotime("today"))); //use in date diff
$day_today_2 = date("Y-m-d", strtotime("today")); //today

//จ่ายเงินรายปี สัญญาที่ 2 รายปี
{
    $sql_1 = "SELECT * FROM lease l, tenant t, money m
    WHERE l.le_type = 2 AND
          l.le_status = 1 AND
          m.le_id = l.le_id AND
          m.mn_status = 0 AND
          m.mn_type = 3 AND
          l.tn_id = t.tn_id AND
          m.type_pay = 'year' ";
    $result_1 = mysqli_query($conn, $sql_1);

    while ($row_1 = mysqli_fetch_assoc($result_1)) {

        if ($row_1['le_duration'] == 2 or $row_1['le_duration'] == 3) {

            $le_date = date_create($row_1['mn_date_pay']); //2013-03-15 ดึงวันสิ้นสุดสัญญาเช่า

            $date_diff = date_diff($day_today, $le_date); // คำนวณวันว่าเหลือกี่วัน

            $le_no = $row_1['le_no'];

            $date_pay_1 = $row_1['mn_date_pay']; //วันที่ต้องชำระ
            $date_pay_1 = date("d/m/Y", strtotime($date_pay_1));

            //แจ้งเตือนผู้เช่า 120 วัน
            if ($date_diff->format("%R%a") == 120) {

                //ส่งเมลล์ไปหาผู้เช่า
                {
                    //mailer
                    require_once "../16_func_email_sms/func_email.php";
                    $header = "แจ้งเตือนการชำระเงิน";
                    $detail = "สัญญาเช่า เลขที่สัญญาเช่า : ' . $le_no . 'เหลือเวลาอีก 120 วัน ในการชำระเงินสัญญาเช่า วันที่ต้องชำระ $date_pay_1 ";
                    $tn_email = $row_1['tn_email'];
                    $send_email = sendEmail($tn_email, $header, $otp);

                    //SMS
                    require_once "../16_func_email_sms/func_sms.php";
                    $tn_tel = $row_1['tn_tel'];
                    $send_sms = sendSMS($tn_tel, $detail);
                }

                //แจ้งเตือนเจ้าหน้าที่
                {
                    //ดึงข้อมูลเจ้าหน้าที่
                    $sql_ofc_1 = "SELECT * FROM officer 
                    WHERE ofc_status_use = 1
                    AND ofc_type = 1
                    AND ofc_type = 2
                    AND ofc_type = 3";
                    $result_ofc_1 = mysqli_query($conn, $sql_ofc_1);

                    while ($row_ofc_1 = mysqli_fetch_assoc($result_ofc_1)) {

                        //mailer
                        require_once "../16_func_email_sms/func_email.php";
                        $header = "แจ้งเตือนการชำระเงิน";
                        $detail = "ประเภทสัญญาเช่าเพื่องานบริการ เลขที่สัญญาเช่า : ' . $le_no . 'เหลือเวลาอีก 120 วัน ในการชำระเงินสัญญาเช่า วันที่ต้องชำระ $date_pay_1 ";
                        $tn_email = $row_ofc_1['ofc_email'];
                        $send_email = sendEmail($tn_email, $header, $otp);

                        //SMS
                        require_once "../16_func_email_sms/func_sms.php";
                        $tn_tel = $row_ofc_1['ofc_tel'];
                        $send_sms = sendSMS($tn_tel, $detail);
                    }
                }


                //แจ้งเตือน Admin
                {
                    //ดึงข้อมูลเจ้าหน้าที่
                    $sql_ad_1 = "SELECT * FROM admin";
                    $result_ad_1 = mysqli_query($conn, $sql_ad_1);

                    while ($row_ad_1 = mysqli_fetch_assoc($result_ad_1)) {

                        //mailer
                        require_once "../16_func_email_sms/func_email.php";
                        $header = "แจ้งเตือนการชำระเงิน";
                        $detail = "ประเภทสัญญาเช่า เพื่องานบริการ เลขที่สัญญาเช่า : ' . $le_no . 'เหลือเวลาอีก 120 วัน ในการชำระเงินสัญญาเช่า วันที่ต้องชำระ $date_pay_1 ";
                        $tn_email = $row_ad_1['ad_email'];
                        $send_email = sendEmail($tn_email, $header, $otp);

                        //SMS
                        require_once "../16_func_email_sms/func_sms.php";
                        $tn_tel = $row_ad_1['ad_tel'];
                        $send_sms = sendSMS($tn_tel, $detail);
                    }
                }
            }
            //แจ้งเตือนผู้เช่า 60 วัน
            else if ($date_diff->format("%R%a") == 60) {

                //ส่งเมลล์ไปหาผู้เช่า
                {
                    //mailer
                    require_once "../16_func_email_sms/func_email.php";
                    $header = "แจ้งเตือนการชำระเงิน";
                    $detail = "สัญญาเช่า เลขที่สัญญาเช่า : ' . $le_no . 'เหลือเวลาอีก 60 วัน ในการชำระเงินสัญญาเช่า วันที่ต้องชำระ $date_pay_1 ";
                    $tn_email = $row_1['tn_email'];
                    $send_email = sendEmail($tn_email, $header, $otp);

                    //SMS
                    require_once "../16_func_email_sms/func_sms.php";
                    $tn_tel = $row_1['tn_tel'];
                    $send_sms = sendSMS($tn_tel, $detail);
                }

                //แจ้งเตือนเจ้าหน้าที่
                {
                    //ดึงข้อมูลเจ้าหน้าที่
                    $sql_ofc_1 = "SELECT * FROM officer 
                                WHERE ofc_status_use = 1
                                AND ofc_type = 1
                                AND ofc_type = 2
                                AND ofc_type = 3";
                    $result_ofc_1 = mysqli_query($conn, $sql_ofc_1);

                    while ($row_ofc_1 = mysqli_fetch_assoc($result_ofc_1)) {

                        //mailer
                        require_once "../16_func_email_sms/func_email.php";
                        $header = "แจ้งเตือนการชำระเงิน";
                        $detail = "ประเภทสัญญาเช่าเพื่องานบริการ เลขที่สัญญาเช่า : ' . $le_no . 'เหลือเวลาอีก 60 วัน ในการชำระเงินสัญญาเช่า วันที่ต้องชำระ $date_pay_1 ";
                        $tn_email = $row_ofc_1['ofc_email'];
                        $send_email = sendEmail($tn_email, $header, $otp);

                        //SMS
                        require_once "../16_func_email_sms/func_sms.php";
                        $tn_tel = $row_ofc_1['ofc_tel'];
                        $send_sms = sendSMS($tn_tel, $detail);
                    }
                }


                //แจ้งเตือน Admin
                {
                    //ดึงข้อมูลเจ้าหน้าที่
                    $sql_ad_1 = "SELECT * FROM admin";
                    $result_ad_1 = mysqli_query($conn, $sql_ad_1);

                    while ($row_ad_1 = mysqli_fetch_assoc($result_ad_1)) {

                        //mailer
                        require_once "../16_func_email_sms/func_email.php";
                        $header = "แจ้งเตือนการชำระเงิน";
                        $detail = "ประเภทสัญญาเช่า เพื่องานบริการ เลขที่สัญญาเช่า : ' . $le_no . 'เหลือเวลาอีก 60 วัน ในการชำระเงินสัญญาเช่า วันที่ต้องชำระ $date_pay_1 ";
                        $tn_email = $row_ad_1['ad_email'];
                        $send_email = sendEmail($tn_email, $header, $otp);

                        //SMS
                        require_once "../16_func_email_sms/func_sms.php";
                        $tn_tel = $row_ad_1['ad_tel'];
                        $send_sms = sendSMS($tn_tel, $detail);
                    }
                }
            }
            //แจ้งเตือนผู้เช่า 30 วัน
            else if ($date_diff->format("%R%a") == 30) {

                //ส่งเมลล์ไปหาผู้เช่า
                {
                    //mailer
                    require_once "../16_func_email_sms/func_email.php";
                    $header = "แจ้งเตือนการชำระเงิน";
                    $detail = "สัญญาเช่า เลขที่สัญญาเช่า : ' . $le_no . 'เหลือเวลาอีก 30 วัน ในการชำระเงินสัญญาเช่า วันที่ต้องชำระ $date_pay_1 ";
                    $tn_email = $row_1['tn_email'];
                    $send_email = sendEmail($tn_email, $header, $otp);

                    //SMS
                    require_once "../16_func_email_sms/func_sms.php";
                    $tn_tel = $row_1['tn_tel'];
                    $send_sms = sendSMS($tn_tel, $detail);
                }

                //แจ้งเตือนเจ้าหน้าที่
                {
                    //ดึงข้อมูลเจ้าหน้าที่
                    $sql_ofc_1 = "SELECT * FROM officer 
                                            WHERE ofc_status_use = 1
                                            AND ofc_type = 1
                                            AND ofc_type = 2
                                            AND ofc_type = 3";
                    $result_ofc_1 = mysqli_query($conn, $sql_ofc_1);

                    while ($row_ofc_1 = mysqli_fetch_assoc($result_ofc_1)) {

                        //mailer
                        require_once "../16_func_email_sms/func_email.php";
                        $header = "แจ้งเตือนการชำระเงิน";
                        $detail = "ประเภทสัญญาเช่าเพื่องานบริการ เลขที่สัญญาเช่า : ' . $le_no . 'เหลือเวลาอีก 30 วัน ในการชำระเงินสัญญาเช่า วันที่ต้องชำระ $date_pay_1 ";
                        $tn_email = $row_ofc_1['ofc_email'];
                        $send_email = sendEmail($tn_email, $header, $otp);

                        //SMS
                        require_once "../16_func_email_sms/func_sms.php";
                        $tn_tel = $row_ofc_1['ofc_tel'];
                        $send_sms = sendSMS($tn_tel, $detail);
                    }
                }


                //แจ้งเตือน Admin
                {
                    //ดึงข้อมูลเจ้าหน้าที่
                    $sql_ad_1 = "SELECT * FROM admin";
                    $result_ad_1 = mysqli_query($conn, $sql_ad_1);

                    while ($row_ad_1 = mysqli_fetch_assoc($result_ad_1)) {

                        //mailer
                        require_once "../16_func_email_sms/func_email.php";
                        $header = "แจ้งเตือนการชำระเงิน";
                        $detail = "ประเภทสัญญาเช่า เพื่องานบริการ เลขที่สัญญาเช่า : ' . $le_no . 'เหลือเวลาอีก 30 วัน ในการชำระเงินสัญญาเช่า วันที่ต้องชำระ $date_pay_1 ";
                        $tn_email = $row_ad_1['ad_email'];
                        $send_email = sendEmail($tn_email, $header, $otp);

                        //SMS
                        require_once "../16_func_email_sms/func_sms.php";
                        $tn_tel = $row_ad_1['ad_tel'];
                        $send_sms = sendSMS($tn_tel, $detail);
                    }
                }
            }
            //แจ้งเตือนผู้เช่า 5 วัน
            else if ($date_diff->format("%R%a") == 5) {

                //ส่งเมลล์ไปหาผู้เช่า
                {
                    //mailer
                    require_once "../16_func_email_sms/func_email.php";
                    $header = "แจ้งเตือนการชำระเงิน";
                    $detail = "สัญญาเช่า เลขที่สัญญาเช่า : ' . $le_no . ' ครบกำหนดชำระค่าเช่ารายปีแล้ว ต้องชำระภายใน วันที่ต้องชำระ $date_pay_1 ";
                    $tn_email = $row_1['tn_email'];
                    $send_email = sendEmail($tn_email, $header, $otp);

                    //SMS
                    require_once "../16_func_email_sms/func_sms.php";
                    $tn_tel = $row_1['tn_tel'];
                    $send_sms = sendSMS($tn_tel, $detail);
                }
            }
        }
    }
}

//จ่ายเงินรายปี สัญญาเช่าที่ 3 รายปี
{
    $sql_1 = "SELECT * FROM lease l, tenant t, money m
    WHERE l.le_type = 3 AND
          l.le_status = 1 AND
          m.le_id = l.le_id AND
          m.mn_status = 0 AND
          m.mn_type = 3 AND
          l.tn_id = t.tn_id  ";
    $result_1 = mysqli_query($conn, $sql_1);

    while ($row_1 = mysqli_fetch_assoc($result_1)) {

        if ($row_1['le_duration'] == 2 or $row_1['le_duration'] == 3) {

            $le_date = date_create($row_1['mn_date_pay']); //2013-03-15 ดึงวันสิ้นสุดสัญญาเช่า

            $date_diff = date_diff($day_today, $le_date); // คำนวณวันว่าเหลือกี่วัน

            $le_no = $row_1['le_no'];

            $date_pay_1 = $row_1['mn_date_pay']; //วันที่ต้องชำระ
            $date_pay_1 = date("d/m/Y", strtotime($date_pay_1));

            //แจ้งเตือนผู้เช่า 120 วัน
            if ($date_diff->format("%R%a") == 120) {

                //ส่งเมลล์ไปหาผู้เช่า
                {
                    //mailer
                    require_once "../16_func_email_sms/func_email.php";
                    $header = "แจ้งเตือนการชำระเงิน";
                    $detail = "สัญญาเช่า เลขที่สัญญาเช่า : ' . $le_no . 'เหลือเวลาอีก 120 วัน ในการชำระเงินสัญญาเช่า วันที่ต้องชำระ $date_pay_1 ";
                    $tn_email = $row_1['tn_email'];
                    $send_email = sendEmail($tn_email, $header, $otp);

                    //SMS
                    require_once "../16_func_email_sms/func_sms.php";
                    $tn_tel = $row_1['tn_tel'];
                    $send_sms = sendSMS($tn_tel, $detail);
                }

                //แจ้งเตือนเจ้าหน้าที่
                {
                    //ดึงข้อมูลเจ้าหน้าที่
                    $sql_ofc_1 = "SELECT * FROM officer 
                    WHERE ofc_status_use = 1
                    AND ofc_type = 1
                    AND ofc_type = 2
                    AND ofc_type = 3";
                    $result_ofc_1 = mysqli_query($conn, $sql_ofc_1);

                    while ($row_ofc_1 = mysqli_fetch_assoc($result_ofc_1)) {

                        //mailer
                        require_once "../16_func_email_sms/func_email.php";
                        $header = "แจ้งเตือนการชำระเงิน";
                        $detail = "ประเภทสัญญา เพื่องานวิจัย/การเรียนการสอน เลขที่สัญญาเช่า : ' . $le_no . 'เหลือเวลาอีก 120 วัน ในการชำระเงินสัญญาเช่า วันที่ต้องชำระ $date_pay_1 ";
                        $tn_email = $row_ofc_1['ofc_email'];
                        $send_email = sendEmail($tn_email, $header, $otp);

                        //SMS
                        require_once "../16_func_email_sms/func_sms.php";
                        $tn_tel = $row_ofc_1['ofc_tel'];
                        $send_sms = sendSMS($tn_tel, $detail);
                    }
                }


                //แจ้งเตือน Admin
                {
                    //ดึงข้อมูลเจ้าหน้าที่
                    $sql_ad_1 = "SELECT * FROM admin";
                    $result_ad_1 = mysqli_query($conn, $sql_ad_1);

                    while ($row_ad_1 = mysqli_fetch_assoc($result_ad_1)) {

                        //mailer
                        require_once "../16_func_email_sms/func_email.php";
                        $header = "แจ้งเตือนการชำระเงิน";
                        $detail = "ประเภทสัญญาเช่า เพื่องานวิจัย/การเรียนการสอน เลขที่สัญญาเช่า : ' . $le_no . 'เหลือเวลาอีก 120 วัน ในการชำระเงินสัญญาเช่า วันที่ต้องชำระ $date_pay_1 ";
                        $tn_email = $row_ad_1['ad_email'];
                        $send_email = sendEmail($tn_email, $header, $otp);

                        //SMS
                        require_once "../16_func_email_sms/func_sms.php";
                        $tn_tel = $row_ad_1['ad_tel'];
                        $send_sms = sendSMS($tn_tel, $detail);
                    }
                }
            }
            //แจ้งเตือนผู้เช่า 60 วัน
            else if ($date_diff->format("%R%a") == 60) {

                //ส่งเมลล์ไปหาผู้เช่า
                {
                    //mailer
                    require_once "../16_func_email_sms/func_email.php";
                    $header = "แจ้งเตือนการชำระเงิน";
                    $detail = "สัญญาเช่า เลขที่สัญญาเช่า : ' . $le_no . 'เหลือเวลาอีก 60 วัน ในการชำระเงินสัญญาเช่า วันที่ต้องชำระ $date_pay_1 ";
                    $tn_email = $row_1['tn_email'];
                    $send_email = sendEmail($tn_email, $header, $otp);

                    //SMS
                    require_once "../16_func_email_sms/func_sms.php";
                    $tn_tel = $row_1['tn_tel'];
                    $send_sms = sendSMS($tn_tel, $detail);
                }

                //แจ้งเตือนเจ้าหน้าที่
                {
                    //ดึงข้อมูลเจ้าหน้าที่
                    $sql_ofc_1 = "SELECT * FROM officer 
                                WHERE ofc_status_use = 1
                                AND ofc_type = 1
                                AND ofc_type = 2
                                AND ofc_type = 3";
                    $result_ofc_1 = mysqli_query($conn, $sql_ofc_1);

                    while ($row_ofc_1 = mysqli_fetch_assoc($result_ofc_1)) {

                        //mailer
                        require_once "../16_func_email_sms/func_email.php";
                        $header = "แจ้งเตือนการชำระเงิน";
                        $detail = "ประเภทสัญญาเพื่องานวิจัย/การเรียนการสอน เลขที่สัญญาเช่า : ' . $le_no . 'เหลือเวลาอีก 60 วัน ในการชำระเงินสัญญาเช่า วันที่ต้องชำระ $date_pay_1 ";
                        $tn_email = $row_ofc_1['ofc_email'];
                        $send_email = sendEmail($tn_email, $header, $otp);

                        //SMS
                        require_once "../16_func_email_sms/func_sms.php";
                        $tn_tel = $row_ofc_1['ofc_tel'];
                        $send_sms = sendSMS($tn_tel, $detail);
                    }
                }


                //แจ้งเตือน Admin
                {
                    //ดึงข้อมูลเจ้าหน้าที่
                    $sql_ad_1 = "SELECT * FROM admin";
                    $result_ad_1 = mysqli_query($conn, $sql_ad_1);

                    while ($row_ad_1 = mysqli_fetch_assoc($result_ad_1)) {

                        //mailer
                        require_once "../16_func_email_sms/func_email.php";
                        $header = "แจ้งเตือนการชำระเงิน";
                        $detail = "ประเภทสัญญาเช่าเพื่องานวิจัย/การเรียนการสอน เลขที่สัญญาเช่า : ' . $le_no . 'เหลือเวลาอีก 60 วัน ในการชำระเงินสัญญาเช่า วันที่ต้องชำระ $date_pay_1 ";
                        $tn_email = $row_ad_1['ad_email'];
                        $send_email = sendEmail($tn_email, $header, $otp);

                        //SMS
                        require_once "../16_func_email_sms/func_sms.php";
                        $tn_tel = $row_ad_1['ad_tel'];
                        $send_sms = sendSMS($tn_tel, $detail);
                    }
                }
            }
            //แจ้งเตือนผู้เช่า 30 วัน
            else if ($date_diff->format("%R%a") == 30) {

                //ส่งเมลล์ไปหาผู้เช่า
                {
                    //mailer
                    require_once "../16_func_email_sms/func_email.php";
                    $header = "แจ้งเตือนการชำระเงิน";
                    $detail = "สัญญาเช่า เลขที่สัญญาเช่า : ' . $le_no . 'เหลือเวลาอีก 30 วัน ในการชำระเงินสัญญาเช่า วันที่ต้องชำระ $date_pay_1 ";
                    $tn_email = $row_1['tn_email'];
                    $send_email = sendEmail($tn_email, $header, $otp);

                    //SMS
                    require_once "../16_func_email_sms/func_sms.php";
                    $tn_tel = $row_1['tn_tel'];
                    $send_sms = sendSMS($tn_tel, $detail);
                }

                //แจ้งเตือนเจ้าหน้าที่
                {
                    //ดึงข้อมูลเจ้าหน้าที่
                    $sql_ofc_1 = "SELECT * FROM officer 
                                            WHERE ofc_status_use = 1
                                            AND ofc_type = 1
                                            AND ofc_type = 2
                                            AND ofc_type = 3";
                    $result_ofc_1 = mysqli_query($conn, $sql_ofc_1);

                    while ($row_ofc_1 = mysqli_fetch_assoc($result_ofc_1)) {

                        //mailer
                        require_once "../16_func_email_sms/func_email.php";
                        $header = "แจ้งเตือนการชำระเงิน";
                        $detail = "ประเภทสัญญา เพื่องานวิจัย/การเรียนการสอน เลขที่สัญญาเช่า : ' . $le_no . 'เหลือเวลาอีก 30 วัน ในการชำระเงินสัญญาเช่า วันที่ต้องชำระ $date_pay_1 ";
                        $tn_email = $row_ofc_1['ofc_email'];
                        $send_email = sendEmail($tn_email, $header, $otp);

                        //SMS
                        require_once "../16_func_email_sms/func_sms.php";
                        $tn_tel = $row_ofc_1['ofc_tel'];
                        $send_sms = sendSMS($tn_tel, $detail);
                    }
                }


                //แจ้งเตือน Admin
                {
                    //ดึงข้อมูลเจ้าหน้าที่
                    $sql_ad_1 = "SELECT * FROM admin";
                    $result_ad_1 = mysqli_query($conn, $sql_ad_1);

                    while ($row_ad_1 = mysqli_fetch_assoc($result_ad_1)) {

                        //mailer
                        require_once "../16_func_email_sms/func_email.php";
                        $header = "แจ้งเตือนการชำระเงิน";
                        $detail = "ประเภทสัญญาเช่า เพื่องานวิจัย/การเรียนการสอน เลขที่สัญญาเช่า : ' . $le_no . 'เหลือเวลาอีก 30 วัน ในการชำระเงินสัญญาเช่า วันที่ต้องชำระ $date_pay_1 ";
                        $tn_email = $row_ad_1['ad_email'];
                        $send_email = sendEmail($tn_email, $header, $otp);

                        //SMS
                        require_once "../16_func_email_sms/func_sms.php";
                        $tn_tel = $row_ad_1['ad_tel'];
                        $send_sms = sendSMS($tn_tel, $detail);
                    }
                }
            }
            //แจ้งเตือนผู้เช่า 5 วัน
            else if ($date_diff->format("%R%a") == 5) {

                //ส่งเมลล์ไปหาผู้เช่า
                {
                    //mailer
                    require_once "../16_func_email_sms/func_email.php";
                    $header = "แจ้งเตือนการชำระเงิน";
                    $detail = "สัญญาเช่า เลขที่สัญญาเช่า : ' . $le_no . ' ครบกำหนดชำระค่าเช่ารายปีแล้ว ต้องชำระภายใน วันที่ต้องชำระ $date_pay_1 ";
                    $tn_email = $row_1['tn_email'];
                    $send_email = sendEmail($tn_email, $header, $otp);

                    //SMS
                    require_once "../16_func_email_sms/func_sms.php";
                    $tn_tel = $row_1['tn_tel'];
                    $send_sms = sendSMS($tn_tel, $detail);
                }
            }
        }
    }
}
