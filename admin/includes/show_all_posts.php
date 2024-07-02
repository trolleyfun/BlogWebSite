<!-- List of posts -->
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Id публикации</th>
            <th>Id региона</th>
            <th>Название</th>
            <th>Автор</th>
            <th>Дата</th>
            <th>Изображение</th>
            <th>Тэги</th>
            <th>Кол-во комментариев</th>
            <th>Статус</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        /* Put All Posts from database */
        showAllPosts();
        ?>
    </tbody>
</table>