<?php

session_start();
require('../connection.php');

//check 
if (isset($_POST['submit'])) {

    //check captcha
    if (isset($_POST['g-recaptcha-response'])) {
        $captcha = $_POST['g-recaptcha-response']; //ถ้าไม่ได้ติก ค่าตรงนี้ก็ไม่มี
    }
    if (!$captcha) {
        echo "<script type='text/javascript'>";
        echo "alert('โปรดยืนยันตัวตนของคุณ');";
        echo "window.location='../index.php';";
        echo "</script>";
    }

    $secretKey = "6LeVlS0eAAAAANqoHgTO1ftXg6D6cv-QERsuzhVa";
    $ip = $_SERVER['REMOTE_ADDR'];
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $captcha . "&remoteip=" . $ip);
    $responseKeys = json_decode($response, true);

    //ตรวจสอบคำถามที่ถาม
    if (intval($responseKeys["success"]) != 1) {
        echo "<script type='text/javascript'>";
        echo "alert('โปรดทำการยันยืนให้ถูกต้อง');";
        echo "window.location='../index.php';";
        echo "</script>";
    }
    //ถ้า tic captcha แล้ว
    else {

        //รับ username password / mysqli_real_escape_string() เป็นฟังก์ชันสำหรับเลี่ยงการใช้ตัวอักขระพิเศษในคำสั่ง
        $username = mysqli_real_escape_string($conn,$_POST['username']);
        $password = mysqli_real_escape_string($conn,$_POST['password']);
        
        //นำ PW เข้ารหัส sha256
        $password = hash('sha256', $password);

        //ตรวจสอบว่า uername และ PW ดังกล่าวอยู่ในสถานะ รอเจ้าหน้าที่อนุมัติ
        //chk ofc = 0
        $query_ofc1 = "SELECT * FROM officer 
                            WHERE ofc_email = '$username' 
                            AND ofc_password = '$password' 
                            AND ofc_status_use = '0' ";
        $result_ofc1 = mysqli_query($conn, $query_ofc1);
        //chk tn = 0
        $query_tn1 = "SELECT * FROM tenant 
                            WHERE tn_email = '$username' 
                            AND tn_password = '$password' 
                            AND tn_status_use = '0' ";
        $result_tn1 = mysqli_query($conn, $query_tn1);
        //chk boss = 0
        $query_bs = "SELECT * FROM tenant 
                            WHERE tn_email = '$username' 
                            AND tn_password = '$password' 
                            AND tn_status_use = '0' ";
        $result_bs = mysqli_query($conn, $query_bs);


        //ตรวจสอบเข้าสู่ระบบในแต่ละบทบาท ว่า username และ password ที่กรอกมา นั้นตรงกับบทบาทใด
        //chk admin
        $query_admin = "SELECT * FROM admin 
                            WHERE ad_email = '$username' 
                            AND ad_password = '$password' ";
        $result_admin = mysqli_query($conn, $query_admin);
        //chk ผู้เช่า
        $query_tn = "SELECT * FROM tenant 
                            WHERE tn_email = '$username' 
                            AND tn_password = '$password' 
                            AND tn_status_use = '1' ";
        $result_tn = mysqli_query($conn, $query_tn);
        //chk เจ้าหน้าที่ทั่วไป
        $query_ofc1 = "SELECT * FROM officer 
                            WHERE ofc_email = '$username' 
                            AND ofc_password = '$password' 
                            AND ofc_status_use = '1' 
                            AND ofc_type = '1' ";
        $result_ofc1 = mysqli_query($conn, $query_ofc1);
        //chk เจ้าหน้าที่ พิเศษ
        $query_ofc2 = "SELECT * FROM officer 
                            WHERE ofc_email = '$username' 
                            AND ofc_password = '$password' 
                            AND ofc_status_use = '1' 
                            AND ofc_type = '2' ";
        $result_ofc2 = mysqli_query($conn, $query_ofc2);
        //chk เจ้าหน้าที่การเงิน
        $query_ofc3 = "SELECT * FROM officer 
                            WHERE ofc_email = '$username' 
                            AND ofc_password = '$password' 
                            AND ofc_status_use = '1' 
                            AND ofc_type = '3' ";
        $result_ofc3 = mysqli_query($conn, $query_ofc3);
        //chk นิติกร
        $query_ofc4 = "SELECT * FROM officer 
                            WHERE ofc_email = '$username' 
                            AND ofc_password = '$password' 
                            AND ofc_status_use = '1'
                            AND ofc_type = '4' ";
        $result_ofc4 = mysqli_query($conn, $query_ofc4);
        //chk ผู้จัดการหอพัก
        $query_ofc5 = "SELECT * FROM officer 
                            WHERE ofc_email = '$username' 
                            AND ofc_password = '$password' 
                            AND ofc_status_use = '1' 
                            AND ofc_type = '5' ";
        $result_ofc5 = mysqli_query($conn, $query_ofc5);
        //chk ผู้บริหาร
        $query_ofc6 = "SELECT * FROM officer 
                            WHERE ofc_email = '$username' 
                            AND ofc_password = '$password' 
                            AND ofc_status_use = '1' 
                            AND ofc_type = '6' ";
        $result_ofc6 = mysqli_query($conn, $query_ofc6);
        //chk ผู้ให้เช่า
        $query_bs = "SELECT * FROM lessor
                            WHERE ls_email = '$username' 
                            AND ls_password = '$password' 
                            AND ls_status_show = '1' ";
        $result_bs = mysqli_query($conn, $query_bs);


        //เข้าสู่ระบบของ admin
        if (mysqli_num_rows($result_admin) == 1) {

            $row_ad = mysqli_fetch_array($result_admin);

            $_SESSION['ofc_add'] = $row_ad['ad_id'];
            $_SESSION['ofc_add_name'] = $row_ad['ad_p_name'] . "" . $row_ad['ad_f_name'] . " " . $row_ad['ad_l_name'];
            //หน้าที่ต้องไป
            header("Location: ../02_admin/01_home/01_admin_home.php");
        }
        //เข้าสู่ระบบของ เจ้าหน้าที่เพิ่มสัญญา
        else if (mysqli_num_rows($result_ofc1) == 1) {
            //เจ้าหน้าที่เพิ่มสัญญา

            $row_ofc = mysqli_fetch_array($result_ofc1);

            $_SESSION['ofc_add'] = $row_ofc['ofc_id'];
            $_SESSION['ofc_add_name'] = $row_ofc['ofc_p_name'] . "" . $row_ofc['ofc_f_name'] . " " . $row_ofc['ofc_l_name'];
            //หน้าที่ต้องไป
            header("Location: ../03_officer/01_home/01_admin_home.php");
        }
        //เข้าสู่ระบบของ เจ้าหน้าที่พิเศษ
        else if (mysqli_num_rows($result_ofc2) == 1) {
            //เจ้าหน้าที่พิเศษ

            $row_ofc = mysqli_fetch_array($result_ofc2);

            $_SESSION['ofc_spcl'] = $row_ofc['ofc_id'];
            $_SESSION['ofc_spcl_name'] = $row_ofc['ofc_p_name'] . "" . $row_ofc['ofc_f_name'] . " " . $row_ofc['ofc_l_name'];
            //หน้าที่ต้องไป
            header("Location: ../11_ofc_spcl/01_home/01_admin_home.php");
        }
        //เข้าสู่ระบบของ เจ้าหน้าที่การเงิน
        else if (mysqli_num_rows($result_ofc3) == 1) {
            //เจ้าหน้าที่การเงิน

            $row_ofc = mysqli_fetch_array($result_ofc3);

            $_SESSION['ofc_mn'] = $row_ofc['ofc_id'];
            $_SESSION['ofc_mn_name'] = $row_ofc['ofc_p_name'] . "" . $row_ofc['ofc_f_name'] . " " . $row_ofc['ofc_l_name'];
            //หน้าที่ต้องไป
            header("Location: ../10_ofc_money/01_home/01_admin_home.php");
        }
        //เข้าสู่ระบบของ นิติกร
        else if (mysqli_num_rows($result_ofc4) == 1) {
            //นิติกร

            $row_ofc = mysqli_fetch_array($result_ofc4);

            $_SESSION['ofc_law'] = $row_ofc['ofc_id'];
            $_SESSION['ofc_law_name'] = $row_ofc['ofc_p_name'] . "" . $row_ofc['ofc_f_name'] . " " . $row_ofc['ofc_l_name'];
            //หน้าที่ต้องไป
            header("Location: ../12_ofc_law/01_home/01_admin_home.php");
        }
        //เข้าสู่ระบบของ ผู้จัดการหอพัก
        else if (mysqli_num_rows($result_ofc5) == 1) {
            //ผู้จัดการหอพัก

            $row_ofc = mysqli_fetch_array($result_ofc5);

            $_SESSION['ofc_dmt'] = $row_ofc['ofc_id'];
            $_SESSION['ofc_dmt_name'] = $row_ofc['ofc_p_name'] . "" . $row_ofc['ofc_f_name'] . " " . $row_ofc['ofc_l_name'];
            //หน้าที่ต้องไป
            header("Location: ../13_ofc_dmt/01_sign_dmt/04_lease/01_lease.php");
        }
        //เข้าสู่ระบบของ ผู้บริการ
        else if (mysqli_num_rows($result_ofc6) == 1) {

            $row_ofc = mysqli_fetch_array($result_ofc6);

            $_SESSION['boss'] = $row_ofc['ofc_id'];
            $_SESSION['boss_name'] = $row_ofc['ofc_p_name'] . "" . $row_ofc['ofc_f_name'] . " " . $row_ofc['ofc_l_name'];
            //หน้าที่ต้องไป
            header("Location: ../15_director/01_home/01_admin_home.php");
        }
        //เข้าสู่ระบบของ ผู้เช่า
        else if (mysqli_num_rows($result_tn) == 1) {
            //ผู้เช่า

            $row_tn = mysqli_fetch_array($result_tn);

            $_SESSION['tn'] = $row_tn['tn_id'];
            $_SESSION['tn_name'] = $row_tn['tn_p_name'] . "" . $row_tn['tn_f_name'] . " " . $row_tn['tn_l_name'];
            //หน้าที่ต้องไป
            header("Location: ../04_tenant/01_tenant_home.php");
        }
        //เข้าสู่ระบบของ ผู้ให้เช่า
        else if (mysqli_num_rows($result_bs) == 1) {
            //ผู้ให้เช่า

            $row_tn = mysqli_fetch_array($result_bs);

            $_SESSION['boss'] = $row_tn['ls_id'];
            $_SESSION['boss_name'] = $row_tn['ls_p_name'] . "" . $row_tn['ls_f_name'] . " " . $row_tn['ls_l_name'];
            //หน้าที่ต้องไป
            header("Location: ../14_boss/01_home/01_admin_home.php");
        }
        //แจ้งเตือนถ้าตรวจพบว่าต้องรอผู้ดูแลระบบอนุมัติของ ผู้เช่า
        else if (mysqli_num_rows($result_tn1) == 1) {

            echo "<script type='text/javascript'>";
            echo "alert('รอผู้ดูแลระบบอนุมัติ');";
            //หน้าที่ต้องไป
            echo "window.location='../index.php';";
            echo "</script>";
        }
        //แจ้งเตือนถ้าตรวจพบว่าต้องรอผู้ดูแลระบบอนุมัติของ เจ้าหน้าที่
        else if (mysqli_num_rows($result_ofc1) == 1) {

            echo "<script type='text/javascript'>";
            echo "alert('รอผู้ดูแลระบบอนุมัติ');";
            echo "window.location='../index.php';";
            echo "</script>";
        }
        //แจ้งเตือนถ้าตรวจพบว่าต้องรอผู้ดูแลระบบอนุมัติของ ผู้ให้เช่า
        else if (mysqli_num_rows($result_bs) == 1) {

            echo "<script type='text/javascript'>";
            echo "alert('รอผู้ดูแลระบบอนุมัติ');";
            //หน้าที่ต้องไป
            echo "window.location='../index.php';";
            echo "</script>";
        }
        //แจ้งเตือนถ้าตรวจพบว่า กรอกข้อมูลมาผิด
        else {
            echo "<script type='text/javascript'>";
            echo "alert('อีเมล หรือ รหัสผ่าน ผิด !!!');";
            //หน้าที่ต้องไป
            echo "window.location='../index.php';";
            echo "</script>";
        }
    }
}
