<!-- List of users -->
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Id пользователя</th>
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