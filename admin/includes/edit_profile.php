<?php /* For function editProfile() */ ?>
<!-- Edit User Form -->
<div class="col-xs-6">
    <h3>Пользователь <b><?=$user_login;?></b></h3>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="profile_password">Пароль</label>
            <input type="password" name="profile_password" id="profile_password" class="form-control" minlength="8" value="<?=$user_password;?>">
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_profile['password'], "Введите пароль"); ?></span>
        </div>
        <div class="form-group">
            <label for="profile_firstname">Имя</label>
            <input type="text" name="profile_firstname" id="profile_firstname" class="form-control" value="<?=$user_firstname;?>">
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_profile['firstname'], "Введите имя"); ?></span>
        </div>
        <div class="form-group">
            <label for="profile_lastname">Фамилия</label>
            <input type="text" name="profile_lastname" id="profile_lastname" class="form-control" value="<?=$user_lastname;?>">
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_profile['lastname'], "Введите фамилию"); ?></span>
        </div>
        <div class="form-group">
            <label for="profile_email">E-mail</label>
            <input type="email" name="profile_email" id="profile_email" class="form-control" value="<?=$user_email;?>">
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_profile['email'], "Введите e-mail"); ?></span>
        </div>
        <div class="form-group">
            <label for="profile_image">Изображение</label>
            <img src="../img/<?=$user_image_name;?>" alt="Изображение пользователя" style="max-width: 70px;" class="img-responsive"><br>
            <input type="file" name="profile_image" id="profile_image" class="form-control">
            <input type="hidden" name="current_profile_image" class="form-control" value="<?=$user_image_name;?>" readonly>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_profile['image'], "Загрузите изображение"); ?></span>
        </div>
        <div class="form-group">
            <label for="profile_privilege">Права доступа</label>
            <select name="profile_privilege" id="profile_privilege" class="form-control">
                <option value="пользователь" <?php if ($user_privilege == "пользователь") {echo "selected";} ?>>пользователь</option>
                <option value="модератор" <?php if ($user_privilege == "модератор") {echo "selected";} ?>>модератор</option>
                <option value="администратор" <?php if ($user_privilege == "администратор") {echo "selected";} ?>>администратор</option>
            </select>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_profile['privilege'], "Выберите тип учетной записи"); ?></span>
        </div>
        <div class="form-group">
            <input type="submit" name="update_profile_btn" class="btn btn-primary" value="Сохранить изменения">
        </div>
    </form>
 </div>