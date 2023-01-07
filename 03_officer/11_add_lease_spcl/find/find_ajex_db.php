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

//พื้นที่กับห้อง ใช้กับ 4
if (isset($_POST['function']) && $_POST['function'] == 'room') {
    $id = $_POST['id'];

    $sql = "SELECT * FROM area WHERE area_name = '$id' AND area_status = '0' ";
    $query = mysqli_query($conn, $sql);

    echo '<option selected readonly hidden value=""> เลือกเลขที่ห้อง </option>';
    foreach ($query as $value) {
        echo '<option value="' . $value['area_id'] . '">' . $value['area_room_no'] . '</option> ';
    }
    exit();
}

//พยาน 1 กับตำแหน่ง
if (isset($_POST['function']) && $_POST['function'] == 'wn_1') {
    $id = $_POST['id'];

    $sql = "SELECT * FROM witness WHERE wn_id = '$id' AND wn_status_show = 1 AND wn_status_otp = 1";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);

    if ($result['wn_role'] == null) {
        $result_role = '----------';
    } else {
        $result_role = $result['wn_role'];
    }

    echo $result_role;

    exit();
}

//พยาน 1 กับ พยาน 2
if (isset($_POST['function']) && $_POST['function'] == 'wn_1_2') {
    $id = $_POST['id'];

    $sql = "SELECT * FROM witness WHERE wn_id != '$id' AND wn_status_show = 1 AND chk_in_out_wn = 'in' ";
    $query = mysqli_query($conn, $sql);

    echo '<option selected readonly hidden value=""> เลือกพยานคนที่ 2 </option>';
    foreach ($query as $value) {
        echo '<option value="' . $value['wn_id'] . '">' . $value['wn_p_name'] . " " . $value['wn_f_name'] . " " . $value['wn_l_name'] . '</option> ';
    }
    exit();
}


//พยาน 2 กับตำแหน่ง
if (isset($_POST['function']) && $_POST['function'] == 'wn_2') {
    $id = $_POST['id'];

    $sql = "SELECT * FROM witness WHERE wn_id = '$id' AND wn_status_show = 1";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);

    if ($result['wn_role'] == null) {
        $result_role = '----------';
    } else {
        $result_role = $result['wn_role'];
    }

    echo $result_role;

    exit();
}
