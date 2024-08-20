<?php
clickDeleteUserIcon();
selectUserOptions();
?>
<form action="" method="post">
    <!-- User Options Buttons Top -->
    <div class="col-lg-4 col-md-6 form-group">
        <div class="input-group">
            <select name="user_option_top" class="form-control" id="select-option-top">
                <option value="">Выберите действие...</option>
                <option value="moderator">сделать модератором</option>
                <option value="admin">сделать администратором</option>
                <option value="user">сделать пользователем</option>
                <option value="delete">удалить</option>
            </select>
            <span class="input-group-btn">
                <input type="submit" name="apply_user_option_btn_top" class="btn btn-success" value="Применить" onclick="return confirmDeleteOptionTop('Вы уверены, что хотите удалить выбранных пользователей?');">
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
                    <th>Количество<br>публикаций</th>
                    <th>Количество<br>комментариев</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                /* Put All Users from database */
                $pager_values = showAllUsers(3);
                ?>
            </tbody>
        </table>
    </div>
    <!-- /.col-xs-12.form-group -->

    <!-- User Options Buttons Bottom -->
    <div class="col-lg-4 col-md-6 form-group">
        <div class="input-group">
            <select name="user_option_bottom" class="form-control" id="select-option-bottom">
                <option value="">Выберите действие...</option>
                <option value="moderator">сделать модератором</option>
                <option value="admin">сделать администратором</option>
                <option value="user">сделать пользователем</option>
                <option value="delete">удалить</option>
            </select>
            <span class="input-group-btn">
                <input type="submit" name="apply_user_option_btn_bottom" class="btn btn-success" value="Применить" onclick="return confirmDeleteOptionBottom('Вы уверены, что хотите удалить выбранных пользователей?');">
            </span>
        </div>
    </div>
    <!-- /.col-lg-4.col-md-6 -->
</form>

<!-- Pager -->
<div class="col-xs-12">
    <ul class="pager">
        <li>
            <a href="<?=$pager_values['previous_page_link'];?>">&larr; Предыдущая</a>
        </li>
        <?php showPagesAdminUsers($pager_values['pages_cnt'], $pager_values['page_num']); ?>
        <li>
            <a href="<?=$pager_values['next_page_link'];?>">Следующая &rarr;</a>
        </li>
    </ul>
</div>
<!-- /.col-xs-12 -->