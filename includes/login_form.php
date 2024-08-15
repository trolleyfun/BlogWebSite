<?php userLogin(); ?>
<!-- Login Section -->
<div class="well col-md-8 col-xs-12">
    <h4>Вход</h4>
    <form action="" method="post">
        <div class="form-group">
            <input type="text" name="user_login" placeholder="Введите логин" class="form-control">
        </div>
        <!-- /.form-group -->
        <div class="input-group form-group">
            <input type="password" name="user_password" placeholder="Введите пароль" class="form-control">
            <span class="input-group-btn">
                <button type="submit" name="login_btn" class="btn btn-primary">Войти</button>
            </span>
        </div>
        <span>Нет учетной записи? <a href="login.php?source=signup">Зарегистрироваться</a></span>
        <!-- /.input-group -->
    </form>
</div>
<!-- /.well.col-md-8 -->