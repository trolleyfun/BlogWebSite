<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">Управление сайтом</a>
    </div>
    <!-- Top Menu Items -->
    <ul class="nav navbar-right top-nav">
        <li><a href="../">Перейти на сайт</a></li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?=$session_user['login'];?> <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>
                    <a href="admin_posts.php?source=add_posts"><i class="fa fa-fw fa-plus"></i> Добавить публикацию</a>
                </li>
                <li>
                    <a href="admin_profile.php"><i class="fa fa-fw fa-cog"></i> Профиль</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="../includes/logout.php"><i class="fa fa-fw fa-power-off"></i> Выйти</a>
                </li>
            </ul>
        </li>
    </ul>
    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">
            <li class="active">
                <a href="index.php"><i class="fa fa-fw fa-dashboard"></i> Статистика</a>
            </li>
            <li>
                <a href="admin_posts.php"><i class="fa fa-fw fa-file-text"></i> Публикации</a>
            </li>
            <li>
                <a href="admin_comments.php"><i class="fa fa-fw fa-comments"></i> Комментарии</a>
            </li>
            <li>
                <a href="admin_categories.php"><i class="fa fa-fw fa-list"></i> Регионы</a>
            </li>
            <li>
                <a href="admin_users.php"><i class="fa fa-fw fa-user"></i> Пользователи</a>
            </li>
            <li>
                <a href="admin_profile.php"><i class="fa fa-fw fa-cog"></i> Профиль</a>
            </li>
        </ul>
    </div>
    <!-- /.navbar-collapse -->
</nav>