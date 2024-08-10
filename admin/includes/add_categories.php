<?php /* For function addCategories() */ ?>

<!-- Category Add Form -->
<form action="" method="post">
    <div class="form-group">
        <label for="cat_title">Название региона<span style='color: #a94442;'> *</span></label>
        <input type="text" name="cat_title" id="cat_title" class="form-control" required>
        <span style='color: #a94442;'><?php displayErrorMessage($err_add_cat['title_empty'], "Это поле не может быть пустым"); ?></span>
        <span style='color: #a94442;'><?php displayErrorMessage($err_add_cat['title_exists'], "Такой регион уже существует. Выберите другое название"); ?></span>
    </div>
    <div class="form-group">
        <input type="submit" name="add_cat_btn" class="btn btn-primary" value="Добавить регион">
    </div>
</form>