<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบบริหารสัญญาเช่า</title>

    <link rel="shortcut icon" href="style/ku.png" type="image/x-icon" />



    <!-- Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script src="script/jquery-3.6.0.js"></script>

    <link rel="stylesheet" href="style/style_index.css">

    <!-- captcha -->
    <script src='https://www.google.com/recaptcha/api.js'></script>

    <!-- ดึงจากหน้า style_index.css แล้วไม่ทำงาน -->
    <style>
        .sign_in {
            position: absolute;
            top: -40px;
            left: 30px;
            height: 60px;
            width: 300px;
            background: #D1E8E4;
            color: red;
            font-size: 1.5rem;
            text-transform: uppercase;
            font-weight: 700;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
        }
    </style>

</head>

<body>

    <!-- ส่งข้อมูลไปที่ 08_login_logout_db/login_db.php -->
    <form action="08_login_logout_db/login_db.php" method="POST" class="card">

        <div class="sign_in">ระบบบริหารสัญญาเช่า</div>

        <i id="logo" class="fas fa-user-circle"></i>

        <div class="login_form">
            <i class="fas fa-user" id="logo_input"></i>
            <input type="email" name="username" id="username" class="input" required>
            <span data-placeholder="Email"></span>
        </div>

        <div class="login_form">
            <i class="fas fa-lock" id="logo_input"></i>
            <input type="password" name="password" id="password" class="input" required>
            <span data-placeholder="Password"></span>
        </div>

        <div class="forgot_pass">
            <span><a href="06_forgot_pass/01_forgot_pass" style="color: blue;"> Forgot Password ! </a></span>
        </div>

        <br>

        

        <!-- captcha -->
        <div class="g-recaptcha" data-sitekey="6LeVlS0eAAAAAPRa_miqrN5onth5_BGOqRchmPJD"></div>

        <input type="submit" name="submit" value="Login" class="btn_submit">

        <div class="dropdown">

            <span> Don't have account ?</span>

            <button class="btn btn-warning dropdown-toggle" type="button" data-bs-toggle="dropdown" id="dropdownMenu2" aria-expanded="false">
                Register
            </button>

            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <li><a class="dropdown-item" href="01_register/tn/tn_register.php">ผู้เช่า</a></li>
                <li><a class="dropdown-item" href="01_register/boss/ofc_register.php">ผู้ให้เช่า</a></li>
                <li><a class="dropdown-item" href="01_register/ofc/ofc_register.php">เจ้าหน้าที่</a></li>
            </ul>
        </div>

    </form>

    <script src="script/jquery-3.6.0.js"></script>

    <!-- การกระทำเมื่อป้อนข้อมูล -->
    <script type="text/javascript">
        $(document).ready(function() {
            $(".input").on("focus", function() {
                $(this).addClass("focus");
            });

            $(".input").on("blur", function() {
                if ($(this).val() == "")
                    $(this).removeClass("focus");

                //เปลี่ยนสี logo
                if ($("#username").val() != "" && $("#password").val() != "")
                    $("#logo").addClass("change_color");
                else
                    $("#logo").removeClass("change_color");
            });


        });
    </script>

</body>

</html>