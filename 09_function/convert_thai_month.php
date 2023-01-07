<?php

//function แปลงเดือนเป็นเลขไทย

function cal_month($le_month)
{
    if ($le_month == '01') {
        $month = "มกราคม";
    } else if ($le_month == '02') {
        $month = "กุมภาพันธ์";
    } else if ($le_month == '03') {
        $month = "มีนาคม";
    } else if ($le_month == '04') {
        $month = "เมษายน";
    } else if ($le_month == '05') {
        $month = "พฤษภาคม";
    } else if ($le_month == '06') {
        $month = "มิถุนายน";
    } else if ($le_month == '07') {
        $month = "กรกฎาคม";
    } else if ($le_month == '08') {
        $month = "สิงหาคม";
    } else if ($le_month == '09') {
        $month = "กันยายน";
    } else if ($le_month == '10') {
        $month = "ตุลาคม";
    } else if ($le_month == '11') {
        $month = "พฤศจิกายน";
    } else if ($le_month == '12') {
        $month = "ธันวาคม";
    }
    return $month;
}
