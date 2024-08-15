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
            showAllCategories("includes/all_categories_table.php", null);
            ?>
        </tbody> 
    </table>
</form>