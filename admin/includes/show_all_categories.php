<!-- Category Table -->
<table class="table table-bodered table-hover">
    <thead>
        <tr>
            <th>Id</th>
            <th>Название</th>
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