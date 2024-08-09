<?php /* For function editUsers() */ ?>
<!-- Edit User Form -->
<div class="col-xs-6">
    <h3>Редактирование пользователя <b><?=$user_login;?></b></h3>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="edit_user_password">Пароль<span style='color: #a94442;'> *</span></label>
            <input type="password" name="edit_user_password" id="edit_user_password" class="form-control" minlength="8" value="<?=$user_password;?>" required>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_user['password_empty'], "Это поле не может быть пустым"); ?></span>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_user['password_correct'], "Пароль должен содержать не менее 8 символов"); ?></span>
        </div>
        <div class="form-group">
            <label for="edit_user_firstname">Имя<span style='color: #a94442;'> *</span></label>
            <input type="text" name="edit_user_firstname" id="edit_user_firstname" class="form-control" value="<?=$user_firstname;?>" required>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_user['firstname'], "Это поле не может быть пустым"); ?></span>
        </div>
        <div class="form-group">
            <label for="edit_user_lastname">Фамилия<span style='color: #a94442;'> *</span></label>
            <input type="text" name="edit_user_lastname" id="edit_user_lastname" class="form-control" value="<?=$user_lastname;?>" required>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_user['lastname'], "Это поле не может быть пустым"); ?></span>
        </div>
        <div class="form-group">
            <label for="edit_user_email">E-mail<span style='color: #a94442;'> *</span></label>
            <input type="email" name="edit_user_email" id="edit_user_email" class="form-control" value="<?=$user_email;?>" required>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_user['email_empty'], "Это поле не может быть пустым"); ?></span>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_user['email_correct'], "Некорректный e-mail"); ?></span>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_user['email_exists'], "Пользователь с таким e-mail уже существует. Выберите другой e-mail"); ?></span>
        </div>
        <div class="form-group">
            <label for="edit_user_image">Изображение</label>
            <img src="../img/<?=$user_image_name;?>" alt="Изображение пользователя" style="max-width: 70px;" class="img-responsive"><br>
            <input type="file" name="edit_user_image" id="edit_user_image" class="form-control">
            <input type="hidden" name="current_user_image" class="form-control" value="<?=$user_image_name;?>" readonly>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_user['image'], "Загрузите изображение"); ?></span>
        </div>
        <div class="form-group">
            <label for="edit_user_privilege">Права доступа<span style='color: #a94442;'> *</span></label>
            <select name="edit_user_privilege" id="edit_user_privilege" class="form-control" required>
                <option value="">Выберите тип учетной записи...</option>
                <option value="пользователь" <?php if ($user_privilege == "пользователь") {echo "selected";} ?>>пользователь</option>
                <option value="модератор" <?php if ($user_privilege == "модератор") {echo "selected";} ?>>модератор</option>
                <option value="администратор" <?php if ($user_privilege == "администратор") {echo "selected";} ?>>администратор</option>
            </select>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_user['privilege_empty'], "Выберите тип учетной записи"); ?></span>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_user['privilege_correct'], "Недопустимый тип учетной записи"); ?></span>
        </div>
        <div class="form-group">
            <input type="submit" name="update_user_btn" class="btn btn-primary" value="Сохранить изменения">
        </div>
    </form>
 </div>