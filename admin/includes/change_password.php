<!-- Change Password Form -->
<div class="col-md-6 col-xs-12">
    <h3>Изменить пароль</h3>
    <form action="" method="post">
        <div class="form-group">
            <label for="current_user_password">Старый пароль<span style='color: #a94442;'> *</span></label>
            <input type="password" name="current_user_password" id="current_user_password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="new_user_password">Новый пароль<span style='color: #a94442;'> *</span></label>
            <input type="password" name="new_user_password" id="new_user_password" class="form-control" minlength="8" required>
        </div>
        <div class="form-group">
            <input type="submit" name="change_password_btn" class="btn btn-primary" value="Изменить пароль">
        </div>
    </form>
 </div>