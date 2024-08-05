<?php /* For function editUsers() */ ?>
<!-- Edit User Form -->
<div class="col-xs-6">
    <h3>Редактирование пользователя</h3>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="user_login">Логин</label>
            <input type="text" name="user_login" id="user_login" class="form-control" value="<?=$user_login;?>">
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_user['login'], "Введите логин"); ?></span>
        </div>
        <div class="form-group">
            <label for="user_password">Пароль</label>
            <input type="password" name="user_password" id="user_password" class="form-control" minlength="8" value="<?=$user_password;?>">
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_user['password'], "Введите пароль"); ?></span>
        </div>
        <div class="form-group">
            <label for="user_firstname">Имя</label>
            <input type="text" name="user_firstname" id="user_firstname" class="form-control" value="<?=$user_firstname;?>">
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_user['firstname'], "Введите имя"); ?></span>
        </div>
        <div class="form-group">
            <label for="user_lastname">Фамилия</label>
            <input type="text" name="user_lastname" id="user_lastname" class="form-control" value="<?=$user_lastname;?>">
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_user['lastname'], "Введите фамилию"); ?></span>
        </div>
        <div class="form-group">
            <label for="user_email">E-mail</label>
            <input type="email" name="user_email" id="user_email" class="form-control" value="<?=$user_email;?>">
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_user['email'], "Введите e-mail"); ?></span>
        </div>
        <div class="form-group">
            <label for="user_image">Изображение</label>
            <img src="../img/<?=$user_image_name;?>" alt="Изображение пользователя" style="width: 75px;" class="img-responsive"><br>
            <input type="file" name="user_image" id="user_image" class="form-control">
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_user['image'], "Загрузите изображение"); ?></span>
        </div>
        <div class="form-group">
            <label for="user_privilege">Права доступа</label>
            <select name="user_privilege" id="user_privilege" class="form-control">
                <option value="пользователь" <?php if ($user_privilege == "пользователь") {echo "selected";} ?>>Пользователь</option>
                <option value="модератор" <?php if ($user_privilege == "модератор") {echo "selected";} ?>>Модератор</option>
                <option value="администратор" <?php if ($user_privilege == "администратор") {echo "selected";} ?>>Администратор</option>
            </select>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_user['privilege'], "Выберите тип учетной записи"); ?></span>
        </div>
        <div class="form-group">
            <input type="submit" name="edit_user_btn" class="btn btn-primary" value="Сохранить изменения">
        </div>
    </form>
 </div>