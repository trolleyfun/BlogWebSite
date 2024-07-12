<!-- Category Edit Form -->
<h3>Редактирование региона</h3>
<form action="" method="post">
    <div class="form-group">
        <input type="hidden" name="edit_id" id="edit_id" class="form-control" value="<?=$edit_id_db;?>" readonly>
        <label for="edit_title">Название региона (Id=<?=$edit_id;?>):</label>
        <input type="text" name="edit_title" id="edit_title" autofocus="autofocus"  class="form-control" value="<?=$edit_title;?>">
        <span style='color: #a94442;'><?php displayErrorMessage($err_edit_cat_title, "Введите название региона"); ?></span>
    </div>
    <div class="form-group">
        <input type="submit" name="update_cat_btn" class="btn btn-primary" value="Сохранить изменения">
    </div>
</form>