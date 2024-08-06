<?php /* For function addUsers() */ ?>

<!-- Add User Form -->
 <div class="col-xs-6">
    <h3>Добавление нового пользователя</h3>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="user_login">Логин</label>
            <input type="text" name="user_login" id="user_login" class="form-control">
            <span style='color: #a94442;'><?php displayErrorMessage($err_add_user['login'], "Введите логин"); ?></span>
        </div>
        <div class="form-group">
            <label for="user_password">Пароль</label>
            <input type="password" name="user_password" id="user_password" class="form-control" minlength="8">
            <span style='color: #a94442;'><?php displayErrorMessage($err_add_user['password'], "Введите пароль"); ?></span>
        </div>
        <div class="form-group">
            <label for="user_firstname">Имя</label>
            <input type="text" name="user_firstname" id="user_firstname" class="form-control">
            <span style='color: #a94442;'><?php displayErrorMessage($err_add_user['firstname'], "Введите имя"); ?></span>
        </div>
        <div class="form-group">
            <label for="user_lastname">Фамилия</label>
            <input type="text" name="user_lastname" id="user_lastname" class="form-control">
            <span style='color: #a94442;'><?php displayErrorMessage($err_add_user['lastname'], "Введите фамилию"); ?></span>
        </div>
        <div class="form-group">
            <label for="user_email">E-mail</label>
            <input type="email" name="user_email" id="user_email" class="form-control">
            <span style='color: #a94442;'><?php displayErrorMessage($err_add_user['email'], "Введите e-mail"); ?></span>
        </div>
        <div class="form-group">
            <label for="user_image">Изображение</label>
            <input type="file" name="user_image" id="user_image" class="form-control">
            <span style='color: #a94442;'><?php displayErrorMessage($err_add_user['image'], "Загрузите изображение"); ?></span>
        </div>
        <div class="form-group">
            <label for="user_privilege">Права доступа</label>
            <select name="user_privilege" id="user_privilege" class="form-control">
                <option value="">выберите тип учетной записи...</option>
                <option value="пользователь">пользователь</option>
                <option value="модератор">модератор</option>
                <option value="администратор">администратор</option>
            </select>
            <span style='color: #a94442;'><?php displayErrorMessage($err_add_user['privilege'], "Выберите тип учетной записи"); ?></span>
        </div>
        <div class="form-group">
            <input type="submit" name="add_user_btn" class="btn btn-primary" value="Добавить пользователя">
        </div>
    </form>
 </div>