<?php /* For function userSignup() */ ?>
<!-- SignUp Section -->
<div class="well col-md-8 col-xs-12">
    <h4>Регистрация</h4>
    <form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
            <label for="signup_user_login">Логин<span style='color: #a94442;'> *</span></label>
            <input type="text" name="signup_user_login" id="signup_user_login" class="form-control" required>
            <span style='color: #a94442;'><?php displayMessage($err_user_signup['login_empty'], "Это поле не может быть пустым"); ?></span>
            <span style='color: #a94442;'><?php displayMessage($err_user_signup['login_exists'], "Пользователь с таким логином уже существует. Выберите другой логин"); ?></span>
        </div>
        <div class="form-group">
            <label for="signup_user_password">Пароль<span style='color: #a94442;'> *</span></label>
            <input type="password" name="signup_user_password" id="signup_user_password" class="form-control" minlength="8" required>
            <span style='color: #a94442;'><?php displayMessage($err_user_signup['password_empty'], "Это поле не может быть пустым"); ?></span>
            <span style='color: #a94442;'><?php displayMessage($err_user_signup['password_correct'], "Пароль должен содержать не менее 8 символов"); ?></span>
        </div>
        <div class="form-group">
            <label for="signup_user_firstname">Имя<span style='color: #a94442;'> *</span></label>
            <input type="text" name="signup_user_firstname" id="signup_user_firstname" class="form-control" required>
            <span style='color: #a94442;'><?php displayMessage($err_user_signup['firstname'], "Это поле не может быть пустым"); ?></span>
        </div>
        <div class="form-group">
            <label for="signup_user_lastname">Фамилия<span style='color: #a94442;'> *</span></label>
            <input type="text" name="signup_user_lastname" id="signup_user_lastname" class="form-control" required>
            <span style='color: #a94442;'><?php displayMessage($err_user_signup['lastname'], "Это поле не может быть пустым"); ?></span>
        </div>
        <div class="form-group">
            <label for="signup_user_email">E-mail<span style='color: #a94442;'> *</span></label>
            <input type="email" name="signup_user_email" id="signup_user_email" class="form-control" required>
            <span style='color: #a94442;'><?php displayMessage($err_user_signup['email_empty'], "Это поле не может быть пустым"); ?></span>
            <span style='color: #a94442;'><?php displayMessage($err_user_signup['email_correct'], "Некорректный e-mail"); ?></span>
            <span style='color: #a94442;'><?php displayMessage($err_user_signup['email_exists'], "Пользователь с таким e-mail уже существует. Выберите другой e-mail"); ?></span>
        </div>
        <div class="form-group">
            <label for="signup_user_image">Изображение</label>
            <input type="file" name="signup_user_image" id="signup_user_image" class="form-control">
            <span style='color: #a94442;'><?php displayMessage($err_user_signup['image'], "Загрузите изображение"); ?></span>
        </div>
        <div class="form-group">
            <input type="submit" name="signup_btn" class="btn btn-primary" value="Зарегистрироваться">
        </div>
        <span>Уже есть учетная запись? <a href="login.php?source=login">Войти</a></span>
    </form>
</div>
<!-- /.well.col-md-8 -->