<!-- List of comments -->
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Id комментария</th>
            <th>Название публикации</th>
            <th>Пользователь</th>
            <th>Дата комментария</th>
            <th>Комментарий</th>
            <th>E-mail</th>
            <th>Статус</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php 
        /* Put All Comments from database */
        showAllComments();
        ?>
    </tbody>
</table>