<?php userLogin(); ?>
<!-- SignUp Well -->
<div class="well col-md-8">
    <h4>Регистрация</h4>
    <form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
            <label for="user_login">Логин<span style='color: #a94442;'> *</span></label>
            <input type="text" name="user_login" id="user_login" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="user_password">Пароль<span style='color: #a94442;'> *</span></label>
            <input type="password" name="user_password" id="user_password" class="form-control" minlength="8" required>
        </div>
        <div class="form-group">
            <label for="user_firstname">Имя<span style='color: #a94442;'> *</span></label>
            <input type="text" name="user_firstname" id="user_firstname" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="user_lastname">Фамилия<span style='color: #a94442;'> *</span></label>
            <input type="text" name="user_lastname" id="user_lastname" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="user_email">E-mail<span style='color: #a94442;'> *</span></label>
            <input type="email" name="user_email" id="user_email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="user_image">Изображение</label>
            <input type="file" name="user_image" id="user_image" class="form-control">
        </div>
        <div class="form-group">
            <input type="submit" name="add_user_btn" class="btn btn-primary" value="Зарегистрироваться">
        </div>
        <span>Уже есть учетная запись? <a href="login.php?source=login">Войти</a></span>
    </form>
</div>
<!-- /.well.col-md-8 -->