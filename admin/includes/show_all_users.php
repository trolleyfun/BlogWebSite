<?php
clickDeleteUserIcon();
selectUserOptions();
?>
<form action="" method="post">
    <!-- User Options Buttons -->
    <div class="col-lg-4 col-md-6 form-group">
        <div class="input-group">
            <select name="user_option" class="form-control">
                <option value="">Выберите действие...</option>
                <option value="moderator">сделать модератором</option>
                <option value="admin">сделать администратором</option>
                <option value="user">сделать пользователем</option>
                <option value="delete">удалить</option>
            </select>
            <span class="input-group-btn">
                <input type="submit" name="apply_user_option_btn" class="btn btn-success" value="Применить">
            </span>
        </div>
    </div>
    <!-- /.col-lg-4.col-md-6 -->
    <div class="col-lg-4 col-md-6 form-group">
        <a href="admin_users.php?source=add_users" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Добавить пользователя</a>
    </div>
    <!-- /.col-lg-4.col-md-6 -->

    <div class="col-xs-12 form-group">
        <!-- List of users -->
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th><input type="checkbox" id="allCheckBoxes"></th>
                    <th>Логин</th>
                    <th>Имя</th>
                    <th>Фамилия</th>
                    <th>E-mail</th>
                    <th>Изображение</th>
                    <th>Права доступа</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                /* Put All Users from database */
                showAllUsers();
                ?>
            </tbody>
        </table>
    </div>
    <!-- /.col-xs-12 -->
</form>