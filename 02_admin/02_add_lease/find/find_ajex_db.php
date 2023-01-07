<?php
require('../../../connection.php');

//พื้นที่กับขนาด ประเภทสัญญา 1-5
//หาขนาด
if (isset($_POST['function']) && $_POST['function'] == 'area_name') {

    //รับ id พื้นที่
    $id = $_POST['id'];

    //ดึงข้อมูลจากฐานข้อมูลทั้งหมดจาก area ที่ area_id = '$id'
    $sql = "SELECT * FROM area WHERE area_id = '$id'  ";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);

    //ข้อมูลที่จะ return กลับไปที่ ตัวแปร data
    echo $result['area_size'];

    exit();
}

//พื้นที่กับกว้างยาว ใช้กับ ประเภทสัญญา 3
//หากับความกว้า x ยาว
if (isset($_POST['function']) && $_POST['function'] == 'width_height') {

    //รับ id พื้นที่
    $id = $_POST['id'];

    //ดึงข้อมูลจากฐานข้อมูลทั้งหมดจาก area ที่ area_id = '$id'
    $sql = "SELECT * FROM area WHERE area_id = '$id' ";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);

    //ข้อมูลที่จะ return กลับไปที่ ตัวแปร data
    echo $result['area_width'] . " X " . $result['area_height'];

    exit();
}

//พื้นที่กับห้อง ใช้กับประเภทสัญญา 4
//หาเลขที่ห้อง
if (isset($_POST['function']) && $_POST['function'] == 'room') {

    //รับ ชื่อ พื้นที่
    $id = $_POST['id'];

    //ดึงข้อมูลจากฐานข้อมูลทั้งหมดจาก area_name = '$id' AND area_status = '0'
    $sql = "SELECT * FROM area WHERE area_name = '$id' AND area_status = '0' ";
    $query = mysqli_query($conn, $sql);

    //ข้อมูลที่จะ return กลับไปที่ ตัวแปร data
    echo '<option selected disabled hidden value=""> เลือกเลขที่ห้อง </option>';
    foreach ($query as $value) {
        echo '<option value="' . $value['area_id'] . '">' . $value['area_room_no'] . '</option> ';
    }
    exit();
}

// ใช้กับประเภทสัญญา 2-3
//พยาน 1 กับตำแหน่ง
if (isset($_POST['function']) && $_POST['function'] == 'wn_1') {

    //รับ id พยาน
    $id = $_POST['id'];

    //ดึงข้อมูลจากฐานข้อมูลทั้งหมดจาก wn_id = '$id'
    $sql = "SELECT * FROM witness WHERE wn_id = '$id' AND wn_status_show = 1 AND wn_status_otp = 1";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);

    //ถ้ามีตำแหน่งทำ if นี้
    if ($result['wn_role'] == null) {
        $result_role = '----------';
    }
    //ถ้าไม่มีตำแหน่งทำ if นี้
    else {
        $result_role = $result['wn_role'];
    }

    //ข้อมูลที่จะ return กลับไปที่ ตัวแปร data
    echo $result_role;

    exit();
}
