<?php /* For function editProfile() */ ?>
<!-- Edit User Form -->
<div class="col-md-6 col-xs-12">
    <h3>Пользователь <b><?=$user_login;?></b></h3>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="profile_firstname">Имя<span style='color: #a94442;'> *</span></label>
            <input type="text" name="profile_firstname" id="profile_firstname" class="form-control" value="<?=$user_firstname;?>" required>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_profile['firstname'], "Это поле не может быть пустым"); ?></span>
        </div>
        <div class="form-group">
            <label for="profile_lastname">Фамилия<span style='color: #a94442;'> *</span></label>
            <input type="text" name="profile_lastname" id="profile_lastname" class="form-control" value="<?=$user_lastname;?>" required>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_profile['lastname'], "Это поле не может быть пустым"); ?></span>
        </div>
        <div class="form-group">
            <label for="profile_email">E-mail<span style='color: #a94442;'> *</span></label>
            <input type="email" name="profile_email" id="profile_email" class="form-control" value="<?=$user_email;?>" required>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_profile['email_empty'], "Это поле не может быть пустым"); ?></span>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_profile['email_correct'], "Некорректный e-mail"); ?></span>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_profile['email_exists'], "Пользователь с таким e-mail уже существует. Выберите другой e-mail"); ?></span>
        </div>
        <div class="form-group">
            <label for="profile_image">Изображение</label>
            <img src="../img/<?=$user_image_name;?>" alt="Изображение пользователя" style="max-width: 70px;" class="img-responsive"><br>
            <input type="file" name="profile_image" id="profile_image" class="form-control">
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_profile['image'], "Загрузите изображение"); ?></span>
        </div>
        <div class="form-group">
            <label for="profile_privilege">Права доступа<span style='color: #a94442;'> *</span></label>
            <select name="profile_privilege" id="profile_privilege" class="form-control" required>
                <option value="">Выберите тип учетной записи...</option>
                <option value="пользователь" <?php if ($user_privilege == "пользователь") {echo "selected";} ?>>пользователь</option>
                <option value="модератор" <?php if ($user_privilege == "модератор") {echo "selected";} ?>>модератор</option>
                <option value="администратор" <?php if ($user_privilege == "администратор") {echo "selected";} ?>>администратор</option>
            </select>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_profile['privilege_empty'], "Выберите тип учетной записи"); ?></span>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_profile['privilege_correct'], "Недопустимый тип учетной записи"); ?></span>
        </div>
        <div class="form-group">
            <input type="submit" name="update_profile_btn" class="btn btn-primary" value="Сохранить изменения">
        </div>
    </form>
 </div>