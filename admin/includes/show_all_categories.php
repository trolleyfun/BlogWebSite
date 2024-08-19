<form action="" method="post">
    <!-- Delete Categories Button -->
    <div class="form-group">
        <input type="submit" name="delete_categories_btn" class="btn btn-primary" value="Удалить выбранные регионы" onclick="return confirm('Вы уверены, что хотите удалить выбранные регионы?');">
    </div>

    <!-- Category Table -->
    <table class="table table-bodered table-hover">
        <thead>
            <tr>
                <th><input type="checkbox" id="allCheckBoxes"></th>
                <th>Название</th>
                <th>Количество<br>публикаций</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php 
            /* Put categories from database */
            $pager_values = showAllCategories("includes/all_categories_table.php", null, 3);
            ?>
        </tbody> 
    </table>
</form>

<!-- Pager -->
<ul class="pager">
    <li class="previous">
        <a href="<?=$pager_values['previous_page_link'];?>">&larr; Предыдущая</a>
    </li>
    <?php showPagesAdminCategories($pager_values['pages_cnt'], $pager_values['page_num']); ?>
    <li>
        <a href="<?=$pager_values['next_page_link'];?>">Следующая &rarr;</a>
    </li>
</ul>
