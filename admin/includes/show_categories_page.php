<?php 
/* Edit selected Category */
editCategories();
?>

<div class="col-md-6">
    <?php 
    /* Add category to database */
    addCategories();
    /* Delete categories from database */
    clickDeleteCategoryIcon();
    deleteSelectedCategories();
    /* Display all categories from database */
    include "includes/show_all_categories.php";
    ?>
</div>
<!-- /.col-md-6 -->