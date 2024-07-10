<!-- List of posts -->
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Id публикации</th>
            <th>Регион</th>
            <th>Название</th>
            <th>Автор</th>
            <th>Дата</th>
            <th>Изображение</th>
            <th>Тэги</th>
            <th>Кол-во комментариев</th>
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