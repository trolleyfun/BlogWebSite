<?php 
selectPostOptions(); 
clickDeletePostIcon();
?>
<form action="" method="post">
    <!-- Post Options Buttons Top -->
    <div class="col-lg-4 col-md-6 form-group">
        <div class="input-group">
            <select name="post_option_top" class="form-control" id="select-option-top">
                <option value="">Выберите действие...</option>
                <option value="confirm">разрешить</option>
                <option value="block">отклонить</option>
                <option value="delete">удалить</option>
            </select>
            <span class="input-group-btn">
                <input type="submit" name="apply_post_option_btn_top" class="btn btn-success" value="Применить" onclick="return confirmDeleteOptionTop('Вы уверены, что хотите удалить выбранные публикации?');">
            </span>
        </div>
    </div>
    <!-- /.col-lg-4.col-md-6 -->
    <div class="col-lg-4 col-md-6 form-group">
        <a href="admin_posts.php?source=add_posts" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Добавить публикацию</a>
    </div>
    <!-- /.col-lg-4.col-md-6 -->

    <!-- List of posts -->
    <div class="col-xs-12 form-group">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th><input type="checkbox" id="allCheckBoxes"></th>
                    <th>Название</th>
                    <th>Регион</th>
                    <th>Автор</th>
                    <th></th>
                    <th>Дата публикации</th>
                    <th>Изображение</th>
                    <th>Тэги</th>
                    <th>Количество комментариев</th>
                    <th>Статус</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                /* Put All Posts from database */
                $pager_values = showAllPosts(3);
                ?>
            </tbody>
        </table>
    </div>
    <!-- /.col-xs-12 -->

    <!-- Post Options Buttons Bottom -->
    <div class="col-lg-4 col-md-6 form-group">
        <div class="input-group">
            <select name="post_option_bottom" class="form-control" id="select-option-bottom">
                <option value="">Выберите действие...</option>
                <option value="confirm">разрешить</option>
                <option value="block">отклонить</option>
                <option value="delete">удалить</option>
            </select>
            <span class="input-group-btn">
                <input type="submit" name="apply_post_option_btn_bottom" class="btn btn-success" value="Применить" onclick="return confirmDeleteOptionBottom('Вы уверены, что хотите удалить выбранные публикации?');">
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
        <?php showPagesAdminPosts($pager_values['pages_cnt'], $pager_values['page_num']); ?>
        <li>
            <a href="<?=$pager_values['next_page_link'];?>">Следующая &rarr;</a>
        </li>
    </ul>
</div>
<!-- /.col-xs-12 -->