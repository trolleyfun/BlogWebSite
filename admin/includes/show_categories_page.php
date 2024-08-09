<div class="col-xs-6">
    <?php 
    /* Add category to database */
    addCategories();
    /* Edit selected Category */
    editCategories();
    /* Delete categories from database */
    deleteCategories();
    ?>
</div>

<div class="col-xs-6">
    <?php 
    /* Display all categories from database */
    include "includes/show_all_categories.php";
    ?>
</div>