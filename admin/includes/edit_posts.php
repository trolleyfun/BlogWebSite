<?php /* For function editPosts() */ ?>

<!-- Edit Post Form -->
<div class="col-xs-6">
    <h3>Редактирование публикации</h3>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="edit_post_category_id">Регион<span style='color: #a94442;'> *</span></label>
            <select name="edit_post_category_id" id="edit_post_category_id" class="form-control" required>
                <option value="">Выберите регион...</option>
                <?php showAllCategories("includes/all_categories_list.php", $post_category_id); ?>
            </select>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['category_id'], "Выберите регион"); ?></span>
        </div>
        <div class="form-group">
            <label for="edit_post_title">Название<span style='color: #a94442;'> *</span></label>
            <input type="text" name="edit_post_title" id="edit_post_title" class="form-control" value="<?=$post_title;?>" required>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['title'], "Это поле не может быть пустым"); ?></span>
        </div>
        <div class="form-group">
            <label for="edit_post_author">Автор<span style='color: #a94442;'> *</span></label>
            <input type="text" name="edit_post_author" id="edit_post_author" class="form-control" value="<?=$post_author;?>" required>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['author'], "Это поле не может быть пустым"); ?></span>
        </div>
        <div class="form-group">
            <label for="edit_post_date">Дата публикации<span style='color: #a94442;'> *</span></label>
            <input type="date" name="edit_post_date" id="edit_post_date" class="form-control" value="<?=$post_date;?>" required>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['date'], "Это поле не может быть пустым"); ?></span>
        </div>
        <div class="form-group">
            <label for="edit_post_image">Изображение</label>
            <img class="img-responsive" src="../img/<?=$post_image;?>" alt="<?=$post_title;?>" style="max-width: 100px;"><br>
            <input type="file" name="edit_post_image" id="edit_post_image" class="form-control">
            <input type="hidden" name="current_post_image" class="form-control" value="<?=$post_image;?>" readonly>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['image'], "Загрузите изображение"); ?></span>
        </div>
        <div class="form-group">
            <label for="edit_post_content">Текст<span style='color: #a94442;'> *</span></label>
            <textarea name="edit_post_content" id="edit_post_content" class="form-control" rows="10" required><?=$post_content;?></textarea>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['content'], "Это поле не может быть пустым"); ?></span>
        </div>
        <div class="form-group">
            <label for="edit_post_tags">Тэги</label>
            <input type="text" name="edit_post_tags" id="edit_post_tags" class="form-control" value="<?=$post_tags;?>">
        </div>
        <div class="form-group">
            <label for="edit_post_status">Статус<span style='color: #a94442;'> *</span></label>
            <select name="edit_post_status" id="edit_post_status" class="form-control" required>
                <option value="">Выберите стутус публикации...</option>
                <option value="черновик" <?php if ($post_status == "черновик") {echo "selected";} ?>>черновик</option>
                <option value="опубликовано" <?php if ($post_status == "опубликовано") {echo "selected";} ?>>опубликовано</option>
            </select>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['status'], "Выберите статус публикации"); ?></span>
        </div>
        <div class="form-group">
            <input type="submit" name="update_post_btn" class="btn btn-primary" value="Сохранить изменения">
        </div>
    </form>
 </div>