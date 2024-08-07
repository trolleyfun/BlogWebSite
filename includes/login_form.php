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