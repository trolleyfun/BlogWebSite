<?php /* For function addPosts() */ ?>

<!-- Add Post Form -->
 <div class="col-xs-6">
    <h3>Добавление новой публикации</h3>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="post_category_id">Регион</label>
            <select name="post_category_id" id="post_category_id" class="form-control">
                <option value="">Выберите регион...</option>
                <?php showAllCategories("includes/all_categories_list.php", ""); ?>
            </select>
            <span style='color: #a94442;'><?php displayErrorMessage($err_add_post['category_id'], "Выберите регион"); ?></span>
        </div>
        <div class="form-group">
            <label for="post_title">Название</label>
            <input type="text" name="post_title" id="post_title" class="form-control">
            <span style='color: #a94442;'><?php displayErrorMessage($err_add_post['title'], "Введите название публикации"); ?></span>
        </div>
        <div class="form-group">
            <label for="post_author">Автор</label>
            <input type="text" name="post_author" id="post_author" class="form-control">
            <span style='color: #a94442;'><?php displayErrorMessage($err_add_post['author'], "Введите автора публикации"); ?></span>
        </div>
        <div class="form-group">
            <label for="post_image">Изображение</label>
            <input type="file" name="post_image" id="post_image" class="form-control">
            <span style='color: #a94442;'><?php displayErrorMessage($err_add_post['image'], "Загрузите изображение"); ?></span>
        </div>
        <div class="form-group">
            <label for="post_content">Текст</label>
            <textarea name="post_content" id="post_content" class="form-control" rows="10"></textarea>
            <span style='color: #a94442;'><?php displayErrorMessage($err_add_post['content'], "Введите текст публикации"); ?></span>
        </div>
        <div class="form-group">
            <label for="post_tags">Тэги</label>
            <input type="text" name="post_tags" id="post_tags" class="form-control">
        </div>
        <div class="form-group">
            <label for="post_status">Статус</label>
            <select name="post_status" id="post_status" class="form-control">
                <option value="">Выберите стутус публикации...</option>
                <option value="черновик">черновик</option>
                <option value="опубликовано">опубликовано</option>
            </select>
            <span style='color: #a94442;'><?php displayErrorMessage($err_add_post['status'], "Выберите статус публикации"); ?></span>
        </div>
        <div class="form-group">
            <input type="submit" name="add_post_btn" class="btn btn-primary" value="Добавить публикацию">
        </div>
    </form>
 </div>