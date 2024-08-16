<?php /* For function addCategories() */ ?>

<!-- Category Add Form -->
<form action="" method="post">
    <div class="input-group">
        <input type="text" name="cat_title" class="form-control" placeholder="Введите название региона" required>
        <span class="input-group-btn">
            <button type="submit" name="add_cat_btn" class="btn btn-success">Добавить регион</button>
        </span>
    </div>
    <span style='color: #a94442;'><?php displayErrorMessage($err_add_cat['title_empty'], "Это поле не может быть пустым"); ?></span>
    <span style='color: #a94442;'><?php displayErrorMessage($err_add_cat['title_exists'], "Такой регион уже существует. Выберите другое название"); ?></span>
</form>
<hr>