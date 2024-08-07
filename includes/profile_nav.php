<!-- Profile Navigation Menu -->
<ul class="nav navbar-right navbar-nav">
    <li><a href='admin'>Управление</a></li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?=$session_user['login'];?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li>
                <a href="admin/admin_profile.php"><i class="fa fa-fw fa-user"></i> Профиль</a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="includes/logout.php"><i class="fa fa-fw fa-power-off"></i> Выйти</a>
            </li>
        </ul>
    </li>
</ul>