<?php selectPostOptions(); ?>
<form action="" method="post">
<div id="post-options" class="col-xs-4 form-group">
    <div class="input-group">
    <select name="post_option" class="form-control">
        <option value="">Выберите действие...</option>
        <option value="confirm">разрешить</option>
        <option value="block">отклонить</option>
        <option value="delete">удалить</option>
    </select>
    <span class="input-group-btn">
    <input type="submit" name="apply_post_option_btn" class="btn btn-success" value="Применить">
    </span>
    </div>
</div>
<div class="col-xs-4 form-group">
    <input type="submit" name="" class="btn btn-primary" value="Добавить">
</div>

<!-- List of posts -->
<div class="col-xs-12 form-group">
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th><input type="checkbox" name="" id="allCheckBoxes"></th>
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
</form>