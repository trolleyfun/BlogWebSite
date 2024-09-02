<!-- Profile Navigation Menu -->
<ul class="nav navbar-right navbar-nav">
    <li><a href="admin/">Раздел администратора</a></li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?=$session_user['login'];?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li>
                <a href="admin/admin_posts.php?source=add_posts"><span class="glyphicon glyphicon-plus"></span> Добавить публикацию</a>
            </li>
            <li>
                <a href="admin/admin_profile.php"><span class="glyphicon glyphicon-cog"></span> Профиль</a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="index.php?logout=true" onclick="return confirm('Вы уверены, что хотите завершить сеанс?');"><span class="glyphicon glyphicon-off"></span> Выйти</a>
            </li>
        </ul>
    </li>
</ul>