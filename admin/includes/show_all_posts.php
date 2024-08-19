<?php 
selectPostOptions(); 
clickDeletePostIcon();
?>
<form action="" method="post">
    <!-- Post Options Buttons -->
    <div class="col-lg-4 col-md-6 form-group">
        <div class="input-group">
            <select name="post_option" class="form-control" id="select-option">
                <option value="">Выберите действие...</option>
                <option value="confirm">разрешить</option>
                <option value="block">отклонить</option>
                <option value="delete">удалить</option>
            </select>
            <span class="input-group-btn">
                <input type="submit" name="apply_post_option_btn" class="btn btn-success" value="Применить" onclick="return confirmDeleteOption('Вы уверены, что хотите удалить выбранные публикации?');">
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
                showAllPosts();
                ?>
            </tbody>
        </table>
    </div>
    <!-- /.col-xs-12 -->
    
    <!-- Pager -->
    <div class="col-xs-12">
        <ul class="pager">
            <li class="">
                <a href="#">&larr; Предыдущая</a>
            </li>
            <?php 
            
            ?>
            <li class="">
                <a href="#">Следующая &rarr;</a>
            </li>
        </ul>
    </div>
</form>