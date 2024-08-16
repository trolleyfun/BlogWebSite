<?php /* For function addPosts() */ ?>

<!-- Add Post Form -->
 <div class="col-md-6 col-xs-12">
    <h3>Добавить новую публикацию</h3>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="post_category_id">Регион<span style='color: #a94442;'> *</span></label>
            <select name="post_category_id" id="post_category_id" class="form-control" required>
                <option value="">Выберите регион...</option>
                <?php showAllCategories("includes/all_categories_list.php", ""); ?>
            </select>
            <span style='color: #a94442;'><?php displayErrorMessage($err_add_post['category_id_empty'], "Выберите регион"); ?></span>
            <span style='color: #a94442;'><?php displayErrorMessage($err_add_post['category_id_exists'], "Такого региона не существует. Выберите другой регион"); ?></span>
        </div>
        <div class="form-group">
            <label for="post_title">Название<span style='color: #a94442;'> *</span></label>
            <input type="text" name="post_title" id="post_title" class="form-control" required>
            <span style='color: #a94442;'><?php displayErrorMessage($err_add_post['title'], "Это поле не может быть пустым"); ?></span>
        </div>
        <div class="form-group">
            <label for="post_author">Автор<span style='color: #a94442;'> *</span></label>
            <input type="text" name="post_author" id="post_author" class="form-control" required>
            <span style='color: #a94442;'><?php displayErrorMessage($err_add_post['author'], "Это поле не может быть пустым"); ?></span>
        </div>
        <div class="form-group">
            <label for="post_image">Изображение</label>
            <input type="file" name="post_image" id="post_image" class="form-control">
            <span style='color: #a94442;'><?php displayErrorMessage($err_add_post['image'], "Загрузите изображение"); ?></span>
        </div>
        <div class="form-group">
            <label for="post_content">Текст<span style='color: #a94442;'> *</span></label>
            <textarea name="post_content" id="post_content" class="form-control summernote-post" rows="10" required></textarea>
            <span style='color: #a94442;'><?php displayErrorMessage($err_add_post['content'], "Это поле не может быть пустым"); ?></span>
        </div>
        <div class="form-group">
            <label for="post_tags">Тэги</label>
            <input type="text" name="post_tags" id="post_tags" class="form-control">
        </div>
        <div class="form-group">
            <input type="submit" name="add_post_btn" class="btn btn-primary" value="Добавить публикацию">
        </div>
    </form>
 </div>