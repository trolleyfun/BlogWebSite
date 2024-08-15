<?php /* For function editUsers() */ ?>
<!-- Reset Password Form -->
<div class="col-md-6 col-xs-12">
    <h3>Изменить пароль</h3>
    <form action="" method="post">
        <div class="form-group">
            <label for="reset_user_password">Новый пароль<span style='color: #a94442;'> *</span></label>
            <input type="password" name="reset_user_password" id="reset_user_password" class="form-control" minlength="8" required>
            <span style='color: #a94442;'><?php displayErrorMessage($err_reset_password['password_empty'], "Это поле не может быть пустым"); ?></span>
            <span style='color: #a94442;'><?php displayErrorMessage($err_reset_password['password_correct'], "Пароль должен содержать не менее 8 символов"); ?></span>
        </div>
        <div class="form-group">
            <input type="submit" name="reset_password_btn" class="btn btn-primary" value="Изменить пароль">
        </div>
    </form>
 </div>