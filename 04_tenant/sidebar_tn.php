
<!-- side bar -->
<div class="navigation">
    <ul>
        <li>
            <a href="/kulease2/04_tenant/05_tenant_profile">
                <span class="icon"><em class="fa fa-user-circle-o" aria-hidden="true"></em></span>
                <span class="title"><?php echo $_SESSION['tn_name']; ?></span>
            </a>
        </li>

        <li>
            <a href="/kulease2/04_tenant/01_tenant_home">
                <span class="icon"><em class="fa fa-home" aria-hidden="true"></em></span>
                <span class="title">ข้อมูลสัญญาเช่า</span>
            </a>
        </li>

        <li>
            <a href="/kulease2/04_tenant/02_1_do_lease">
                <span class="icon "><em class="fa fa-paper-plane-o " aria-hidden="true "></em></span>
                <span class="title ">ทำ/ต่อ สัญญาเช่า</span>
            </a>
        </li>

        <li>
            <a href="/kulease2/04_tenant/06_history_sign">
                <span class="icon "><em class="fa fa-history " aria-hidden="true "></em></span>
                <span class="title ">ประวัติการลงนาม</span>
            </a>
        </li>

        <li>
            <a href="/kulease2/04_tenant/03_history_lease">
                <span class="icon "><em class="fa fa-book " aria-hidden="true "></em></span>
                <span class="title ">ประวัติการเช่า</span>
            </a>
        </li>

        <li>
            <a href="/kulease2/04_tenant/04_contact">
                <span class="icon "><em class="fa fa-compass " aria-hidden="true "></em></span>
                <span class="title ">ติดต่อเจ้าหน้าที่</span>
            </a>
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
<div class="toggle " onclick="toggleMenu() "></div>