<div class="col-md-4">
    <!-- Login Well -->
    <div class="well">
        <h4>Вход</h4>
        <form action="includes/login.php" method="post">
            <div class="form-group">
                <input type="text" name="user_login" placeholder="Введите логин" class="form-control">
            </div>
            <!-- /.form-group -->
            <div class="input-group">
                <input type="password" name="user_password" placeholder="Введите пароль" class="form-control">
                <span class="input-group-btn">
                    <button type="submit" name="login_btn" class="btn btn-primary">Войти</button>
                </span>
            </div>
            <!-- /.input-group -->
        </form>
    </div>

    <!-- Blog Search Well -->
    <div class="well">
        <h4>Поиск</h4>
        <form action="search.php" method="post">
            <div class="input-group">
                <input type="text" name="search_data" class="form-control">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit" name="search_btn">
                        <span class="glyphicon glyphicon-search"></span>
                </button>
                </span>
            </div>
        </form>
        <!-- /.input-group -->
    </div>

    <!-- Blog Categories Well -->
    <div class="well">
        <h4>Регионы</h4>
        <div class="row">
            <div class="col-lg-12">
                <ul class="list-unstyled">
                    <?php 
                    /* Put categories from database */
                    showAllCategories(100);
                    ?>
                </ul>
            </div>
        </div>
        <!-- /.row -->
    </div>

    <!-- Side Widget Well -->
    <?php include "widget.php"; ?>

</div>