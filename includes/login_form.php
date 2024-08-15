<?php /* For function userLogin() */ ?>
<!-- Login Section -->
<div class="well col-md-8 col-xs-12">
    <h4>Вход</h4>
    <form action="" method="post">
        <div class="form-group">
            <input type="text" name="user_login" placeholder="Введите логин" class="form-control" required>
            <span style='color: #a94442;'><?php displayMessage($err_user_login['login'], "Это поле не может быть пустым"); ?></span>
            <span style='color: #a94442;'><?php displayMessage($err_authorization, "Неверный логин или пароль"); ?></span>
        </div>
        <!-- /.form-group -->
        <div class="input-group">
            <input type="password" name="user_password" placeholder="Введите пароль" class="form-control" required>
            <span class="input-group-btn">
                <button type="submit" name="login_btn" class="btn btn-primary">Войти</button>
            </span>
        </div>
        <!-- /.input-group -->
        <span style='color: #a94442;'><?php displayMessage($err_user_login['password'], "Это поле не может быть пустым"); ?></span>
        <br>
        <div>
            <span>Нет учетной записи? <a href="login.php?source=signup">Зарегистрироваться</a></span>
        </div>
    </form>
</div>
<!-- /.well.col-md-8 -->