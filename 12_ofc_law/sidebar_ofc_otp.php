<link rel="stylesheet" href="../../style/style_bar_name.css">

<!-- dropdown -->
<style>
    /* drop down */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #AA82E0;
        min-width: 80%;
        overflow: auto;
        box-shadow: 0px 8px 16px 0px rgba(54, 54, 54, 0.9);
        z-index: 1;
        margin-left: 60px;
    }

    .dropdown-content a {
        color: black;
        padding: 10px 14px;
        text-decoration: none;
        display: block;
        height: 33px;
        font-size: 15px;
    }

    .dropdown a:hover {
        background-color: #464660;
        cursor: pointer;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .navigation ul li a .icon_d {
        position: relative;
        display: block;
        min-width: 50px;
        height: 50px;
        line-height: 50px;
        text-align: end;
    }

    .navigation ul li a .icon_d .fa {
        font-size: 20px;
    }
</style>

<style>
    @import url("https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap");
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans&display=swap');

    :root {
        /*Colors*/
        --first-color: #368B85;
        --first-color-light: #464660;
        --white-color: #F1E9E5;
        --body-color: #C2FFD9;
        --header-height: 7rem;
    }

    .header {
        width: 100%;
        height: var(--header-height);
        background-color: var(--first-color);
        display: block;
        align-items: center;
        text-align: center;
        padding-top: 20px;
        position: -webkit-sticky;
        position: sticky;
        top: 0;
    }

    .ku {
        font-size: 1.5rem;
        color: white;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Nunito', sans-serif;
        ;
    }

    body {
        min-height: 100vh;
        background: var(--body-color);
    }

    .navigation {
        position: fixed;
        width: 60px;
        height: 100%;
        background: var(--first-color);
        transition: 0.5s;
        overflow: hidden;
    }

    .navigation:hover,
    .navigation.active {
        width: 400px;
    }

    .navigation ul {
        position: absolute;
        top: 0;
        left: 30px;
        width: 100%;
    }

    .navigation ul li {
        position: relative;
        width: 100%;
        list-style: none;
        margin-left: 20px;
    }

    .navigation ul li:hover {
        background: var(--first-color-light);
    }

    .navigation ul li a {
        position: relative;
        display: none;
        width: 100%;
        display: flex;
        text-decoration: none;
        color: var(--white-color);
    }

    .navigation ul li a .icon {
        position: relative;
        display: block;
        min-width: 50px;
        height: 50px;
        line-height: 50px;
        text-align: center;
    }



    .navigation ul li a .icon .fa {
        font-size: 24px;
    }

    .navigation ul li a .title {
        position: relative;
        display: block;
        padding: 0 10px;
        height: 50px;
        line-height: 50px;
        text-align: start;
        white-space: nowrap;
        width: 200px;
    }

    .toggle {
        position: fixed;
        top: 50px;
        left: 0;
        width: 60px;
        height: 60px;
        background: var(--first-color);
        cursor: pointer;
    }

    .toggle.active {
        background: var(--first-color-light);
    }

    .toggle:before {
        content: '\f0c9';
        font-family: fontAwesome;
        position: absolute;
        width: 100%;
        height: 100%;
        line-height: 60px;
        text-align: center;
        font-size: 24px;
        color: var(--white-color);
    }

    .toggle.active:before {
        content: '\f00d';
    }

    .body .body_b {
        align-items: center;
    }

    .data {
        padding-top: 50px;
        padding-inline-start: 80px;
    }

    @media (max-width: 767px) {
        .navigation {
            left: -60px;
        }

        .navigation.active {
            left: 0px;
            width: 100%;
        }

        .header {
            width: 100%;
            height: var(--header-height);
            background-color: var(--first-color);
            padding-top: 20px;
            padding-left: 10px;
        }

        .ku {
            display: flex;
            justify-content: center;
            overflow: hidden;
            position: static;
            font-size: 1rem;
        }

        .data {
            padding-top: 50px;
            padding-inline-start: 0;
            font-size: .5rem;
        }

        .toggle {
            position: fixed;
            top: 50px;
            left: 0;
            width: 60px;
            height: 60px;
            background: none;
            cursor: pointer;
        }

        .toggle.active {
            background: none;
        }
    }
</style>

<!--Side bar-->
<div class="navigation">
    <ul>

        <li class="dropdown">
            <a href="/kulease2/12_ofc_law/01_home/01_admin_home">
                <span class="icon"><em class="fa fa-home" aria-hidden="true"></em></span>
                <span class="title">ข้อมูลสัญญาเช่า</span>
            </a>
        </li>

        <li class="dropdown">
            <a href="# ">
                <span class="icon "><em class="fa fa-pencil-square-o" aria-hidden="true "></em></span>
                <span class="title ">นิติกร ลงนามสัญญาเช่า</span>
                <span class="icon_d"><em class="fa fa-caret-down" aria-hidden="true"></em></span>
            </a>

            <div id="myDropdown" class="dropdown-content ">
                <a href="/kulease2/12_ofc_law/08_sign_law/01_lease/01_lease">สัญญาเช่าเพื่อร้านค้าหรือพาณิชย์</a>
                <a href="/kulease2/12_ofc_law/08_sign_law/02_lease/01_lease">สัญญาเช่าเพื่องานบริการ</a>
                <a href="/kulease2/12_ofc_law/08_sign_law/03_lease/01_lease">สัญญาเช่าเพื่องานวิจัย/การเรียนการสอน</a>
                <a href="/kulease2/12_ofc_law/08_sign_law/04_lease/01_lease">สัญญาเช่าเพื่อที่พักอาศัย</a>
                <a href="/kulease2/12_ofc_law/08_sign_law/05_lease/01_lease">สัญญาเช่าเพื่อโรงอาหาร</a>
            </div>
        </li>

        <li class="dropdown">
            <a href="# ">
                <span class="icon "><em class="fa fa-history" aria-hidden="true "></em></span>
                <span class="title ">ประวัติการลงนามสัญญาเช่า</span>
                <span class="icon_d"><em class="fa fa-caret-down" aria-hidden="true"></em></span>
            </a>

            <div id="myDropdown" class="dropdown-content ">
                <a href="/kulease2/12_ofc_law/09_sign_ofc_history/01_lease/01_lease">สัญญาเช่าเพื่อร้านค้าหรือพาณิชย์</a>
                <a href="/kulease2/12_ofc_law/09_sign_ofc_history/02_lease/01_lease">สัญญาเช่าเพื่องานบริการ</a>
                <a href="/kulease2/12_ofc_law/09_sign_ofc_history/03_lease/01_lease">สัญญาเช่าเพื่องานวิจัย/การเรียนการสอน</a>
                <a href="/kulease2/12_ofc_law/09_sign_ofc_history/04_lease/01_lease">สัญญาเช่าเพื่อที่พักอาศัย</a>
                <a href="/kulease2/12_ofc_law/09_sign_ofc_history/05_lease/01_lease">สัญญาเช่าเพื่อโรงอาหาร</a>
            </div>
        </li>

        <li class="dropdown">
            <a href="# ">
                <span class="icon "><em class="fa fa-money " aria-hidden="true "></em></span>
                <span class="title ">การจ่ายเงิน</span>
                <span class="icon_d"><em class="fa fa-caret-down" aria-hidden="true"></em></span>
            </a>

            <div id="myDropdown" class="dropdown-content ">
                <a href="/kulease2/12_ofc_law/05_st_money/01_lease/01_1_money">สัญญาเช่าเพื่อร้านค้าหรือพาณิชย์</a>
                <a href="/kulease2/12_ofc_law/05_st_money/02_lease/02_1_money">สัญญาเช่าเพื่องานบริการ</a>
                <a href="/kulease2/12_ofc_law/05_st_money/03_lease/03_1_money">สัญญาเช่าเพื่องานวิจัย/การเรียนการสอน</a>
                <a href="/kulease2/12_ofc_law/05_st_money/04_lease/04_1_money">สัญญาเช่าเพื่อที่พักอาศัย</a>
                <a href="/kulease2/12_ofc_law/05_st_money/05_lease/05_1_money">สัญญาเช่าเพื่อโรงอาหาร</a>
            </div>
        </li>

        <li class="dropdown">
            <a href="# ">
                <span class="icon "><em class="fa fa-map " aria-hidden="true "></em></span>
                <span class="title ">พื้นที่เช่า</span>
                <span class="icon_d"><em class="fa fa-caret-down" aria-hidden="true"></em></span>
            </a>

            <div id="myDropdown" class="dropdown-content ">
                <a href="/kulease2/12_ofc_law/06_st_area/01_lease/05_1_1_area">สัญญาเช่าเพื่อร้านค้าหรือพาณิชย์</a>
                <a href="/kulease2/12_ofc_law/06_st_area/02_lease/05_2_1_area">สัญญาเช่าเพื่องานบริการ</a>
                <a href="/kulease2/12_ofc_law/06_st_area/03_lease/05_3_1_area">สัญญาเช่าเพื่องานวิจัย/การเรียนการสอน</a>
                <a href="/kulease2/12_ofc_law/06_st_area/04_lease/05_4_1_area">สัญญาเช่าเพื่อที่พักอาศัย</a>
                <a href="/kulease2/12_ofc_law/06_st_area/05_lease/05_5_1_area">สัญญาเช่าเพื่อโรงอาหาร</a>
            </div>
        </li>

        <li class="dropdown">
            <a href="# ">
                <span class="icon "><em class="fa fa-bar-chart " aria-hidden="true "></em></span>
                <span class="title ">วิเคราะห์รายรับ</span>
                <span class="icon_d"><em class="fa fa-caret-down" aria-hidden="true"></em></span>
            </a>

            <div id="myDropdown" class="dropdown-content ">
                <a href="/kulease2/12_ofc_law/07_dashboard/01_lease/01_01_lease">สัญญาเช่าเพื่อร้านค้าหรือพาณิชย์</a>
                <a href="/kulease2/12_ofc_law/07_dashboard/02_lease/01_01_lease">สัญญาเช่าเพื่องานบริการ</a>
                <a href="/kulease2/12_ofc_law/07_dashboard/03_lease/01_01_lease">สัญญาเช่าเพื่องานวิจัย/การเรียนการสอน</a>
                <a href="/kulease2/12_ofc_law/07_dashboard/04_lease/01_01_lease">สัญญาเช่าเพื่อที่พักอาศัย</a>
                <a href="/kulease2/12_ofc_law/07_dashboard/05_lease/01_01_lease">สัญญาเช่าเพื่อโรงอาหาร</a>
                <a href="/kulease2/12_ofc_law/07_dashboard/06_all/01_01_lease">ทุกประเภทสัญญาเช่า</a>
            </div>
        </li>

        <li>
            <a href="/kulease2/08_login_logout_db/logout_db" onclick="return confirm('ยืนยัน : การออกจากระบบ')">
                <span class="icon "><em class="fa fa-sign-out " aria-hidden="true " style="color: #FFBD35;"></em></span>
                <span class="title " style="color: #FFBD35;">ออกจากระบบ</span>
            </a>
        </li>

    </ul>
</div>

<!-- สามขีด -->
<div class="toggle" onclick="toggleMenu() "></div>