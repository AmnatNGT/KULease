<?php
include('../../connection.php');

//ถ้ามีข้อมูลที่ส่งมา และ function == privinces ให้ทำส่วนนี้ 
//หาอำเภอ
if (isset($_POST['function']) && $_POST['function'] == 'provinces') {
    
    //รับ id จังหวัดที่ส่งมา
    $id = $_POST['id'];

    //ดึงข้อมูลจากฐานข้อมูลทั้งหมดจาก amphures ที่ province_id = '$id'
    $sql = "SELECT * FROM amphures WHERE province_id = '$id' ORDER BY name_th ";
    $query = mysqli_query($conn, $sql);

    //ข้อมูลที่จะ return กลับไปที่ ตัวแปร data
    echo '<option hidden value="">เลือกอำเภอ/เขต </option>';
    //นำข้อมูลอำเภอมาไว้ที่นี่
    foreach($query as $value){
        echo'<option value="'.$value['id'].'">'.$value['name_th']. '</option> ';
    }
    //กลับไปที่หน้า tn_register.php ตำแหน่งเดิม
    exit();
}

//ถ้ามีข้อมูลที่ส่งมา และ function == amphures ให้ทำส่วนนี้ 
//หาตำบล
if (isset($_POST['function']) && $_POST['function'] == 'amphures') {

    //รับ id อำเภอที่ส่งมา
    $id = $_POST['id'];

    //ดึงข้อมูลจากฐานข้อมูลทั้งหมดจาก districts ที่ amphure_id = '$id'
    $sql = "SELECT * FROM districts WHERE amphure_id = '$id' ORDER BY name_th ";
    $query = mysqli_query($conn, $sql);

    //ข้อมูลที่จะ return กลับไปที่ ตัวแปร data
    echo '<option hidden value="">เลือกตำบล/แขวง <?php echo $id ?> </option>';
    //นำข้อมูลตำบลมาไว้ที่นี่
    foreach($query as $value){
    echo'<option value="'.$value['id'].'">'.$value['name_th']. '</option> ';
    }
    //กลับไปที่หน้า tn_register.php ตำแหน่งเดิม
    exit();
}

//ถ้ามีข้อมูลที่ส่งมา และ function == districts ให้ทำส่วนนี้ 
//หาเลขไปรณีย์
if (isset($_POST['function']) && $_POST['function'] == 'districts') {

    //รับ id ตำบลที่ส่งมา
    $id = $_POST['id'];

    //ดึงข้อมูลจากฐานข้อมูลทั้งหมดจาก districts ที่ id = '$id'
    $sql = "SELECT * FROM districts WHERE id = '$id' ";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);

    //ข้อมูลที่จะ return กลับไปที่ ตัวแปร data
    echo $result['zip_code'];
    //กลับไปที่หน้า tn_register.php ตำแหน่งเดิม
    exit();
}



?>

