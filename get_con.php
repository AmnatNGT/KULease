<?php

if (stristr(htmlentities($_SERVER['PHP_SELF']), "get_con.php")) {
    Header("Location: https://cskps.flas.kps.ku.ac.th/kulease2/"); // ไปหน้าหลัก
    die();
}

//Server : $conn = mysqli_connect("10.36.16.16","kulease64","kulease64@com123456","kulease64");
//define ชื่อ server
// define("server","158.108.195.4");
// //define username
// define("username","kulease2");
// //define password
// define("password","kulease2@com123456");
// //define database
// define("database","kulease2");


// // Computer : $conn = mysqli_connect("localhost", "root", "12345678", "project");
define("server","localhost");
define("username","root");
define("password","");
define("database","kulease2");