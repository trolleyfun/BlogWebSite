<?php /* For function editPosts() */ ?>

<!-- Edit Post Form -->
<div class="col-md-6 col-xs-12">
    <h3>Редактировать публикацию</h3>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="edit_post_category_id">Регион<span style='color: #a94442;'> *</span></label>
            <select name="edit_post_category_id" id="edit_post_category_id" class="form-control" required>
                <option value="">Выберите регион...</option>
                <?php showAllCategoriesInList($post_category_id); ?>
            </select>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['category_id_empty'], "Выберите регион"); ?></span>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['category_id_exists'], "Такого региона не существует. Выберите другой регион"); ?></span>
        </div>
        <div class="form-group">
            <label for="edit_post_title">Название<span style='color: #a94442;'> *</span></label>
            <input type="text" name="edit_post_title" id="edit_post_title" class="form-control" value="<?=$post_title;?>" required>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['title'], "Это поле не может быть пустым"); ?></span>
        </div>
        <div class="form-group">
            <label for="edit_post_date">Дата публикации<span style='color: #a94442;'> *</span></label>
            <input type="date" name="edit_post_date" id="edit_post_date" class="form-control" value="<?=$post_date;?>" required>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['date_empty'], "Это поле не может быть пустым"); ?></span>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['date_correct'], "Неверный формат даты"); ?></span>
        </div>
        <div class="form-group">
            <label for="edit_post_image">Изображение</label>
            <img class="img-responsive" src="../img/<?=$post_image;?>" alt="<?=$post_title;?>" style="max-width: 100px;"><br>
            <input type="file" name="edit_post_image" id="edit_post_image" class="form-control">
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['image'], "Загрузите изображение"); ?></span>
        </div>
        <div class="form-group">
            <label for="edit_post_content">Текст<span style='color: #a94442;'> *</span></label>
            <textarea name="edit_post_content" id="edit_post_content" class="form-control summernote-post" rows="10"><?=$post_content;?></textarea>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['content'], "Это поле не может быть пустым"); ?></span>
        </div>
        <div class="form-group">
            <label for="edit_post_tags">Тэги</label>
            <input type="text" name="edit_post_tags" id="edit_post_tags" class="form-control" value="<?=$post_tags;?>">
        </div>
        <div class="form-group">
            <label for="edit_post_status">Статус<span style='color: #a94442;'> *</span></label>
            <select name="edit_post_status" id="edit_post_status" class="form-control" required>
                <option value="">Выберите статус публикации...</option>
                <option value="ожидает проверки" <?php if ($post_status == "ожидает проверки") {echo "selected";} ?>>ожидает проверки</option>
                <option value="опубликовано" <?php if ($post_status == "опубликовано") {echo "selected";} ?>>опубликовано</option>
                <option value="заблокировано" <?php if ($post_status == "заблокировано") {echo "selected";} ?>>заблокировано</option>
            </select>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['status_empty'], "Выберите статус публикации"); ?></span>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['status_correct'], "Недопустимое значение статуса публикации"); ?></span>
        </div>
        <div class="form-group">
            <input type="submit" name="update_post_btn" class="btn btn-primary" value="Сохранить изменения">
        </div>
    </form>
 </div>