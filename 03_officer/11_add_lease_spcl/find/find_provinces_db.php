<?php
include('../../../connection.php');

//หาอำเภอ
if (isset($_POST['function']) && $_POST['function'] == 'provinces') {
    $id = $_POST['id'];

    $sql = "SELECT * FROM amphures WHERE province_id = '$id' ORDER BY name_th ";
    $query = mysqli_query($conn, $sql);

    echo '<option hidden value="">เลือกอำเภอ/เขต</option>';
    foreach($query as $value){
        echo'<option value="'.$value['id'].'">'.$value['name_th']. '</option> ';
    }

    exit();
}

//หาตำบล
if (isset($_POST['function']) && $_POST['function'] == 'amphures') {
    $id = $_POST['id'];

    $sql = "SELECT * FROM districts WHERE amphure_id = '$id' ORDER BY name_th ";
    $query = mysqli_query($conn, $sql);

    echo '<option hidden >เลือกตำบล/แขวง<?php echo $id ?> </option>';
    foreach($query as $value){
    echo'<option value="'.$value['id'].'">'.$value['name_th']. '</option> ';
    }

    exit();
}

//หารหัสไปรษณีย์
if (isset($_POST['function']) && $_POST['function'] == 'districts') {
    $id = $_POST['id'];

    $sql = "SELECT * FROM districts WHERE id = '$id' ";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);

    echo $result['zip_code'];

    exit();
}



?>

