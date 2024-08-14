<?php /* For function editCategories() */ ?>
<div class="col-md-6">
    <!-- Category Edit Form -->
    <h3>Редактирование региона</h3>
    <form action="" method="post">
        <div class="form-group">
            <label for="edit_cat_title">Новое название региона <?=$cat_title;?><span style='color: #a94442;'> *</span></label>
            <input type="text" name="edit_cat_title" id="edit_cat_title" autofocus="autofocus"  class="form-control" value="<?=$cat_title;?>" required>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_cat['title_empty'], "Это поле не может быть пустым"); ?></span>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_cat['title_exists'], "Такой регион уже существует. Выберите другое название"); ?></span>
        </div>
        <div class="form-group">
            <input type="submit" name="update_cat_btn" class="btn btn-primary" value="Сохранить изменения">
        </div>
    </form>
    <hr>
</div>
<!-- /.col-md-6 -->