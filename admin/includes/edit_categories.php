<?php /* For function editCategories() */ ?>

<!-- Category Edit Form -->
<h3>Редактирование региона</h3>
<form action="" method="post">
    <div class="form-group">
        <label for="edit_cat_title">Название региона (Id=<?=$cat_id;?>):</label>
        <input type="text" name="edit_cat_title" id="edit_cat_title" autofocus="autofocus"  class="form-control" value="<?=$cat_title;?>" required>
        <span style='color: #a94442;'><?php displayErrorMessage($err_edit_cat['title'], "Это поле не может быть пустым"); ?></span>
    </div>
    <div class="form-group">
        <input type="submit" name="update_cat_btn" class="btn btn-primary" value="Сохранить изменения">
    </div>
</form>