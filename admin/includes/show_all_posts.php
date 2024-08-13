<div id="post-options" class="col-xs-4">
    <select name="post_option" class="form-control">
        <option value="">Выберите опцию...</option>
        <option value="">Опубликовать</option>
        <option value="">Черновик</option>
        <option value="">Удалить</option>
    </select>
</div>
<div class="col-xs-4">
    <input type="submit" name="" class="btn btn-success" value="Применить">
    <input type="submit" name="" class="btn btn-primary" value="Добавить">
</div>

<!-- List of posts -->
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th><input type="checkbox" name="" class="allCheckBoxes"></th>
            <th>Id публикации</th>
            <th>Регион</th>
            <th>Название</th>
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