<!-- Edit Post Form -->
<div class="col-xs-6">
    <h3>Редактирование публикации</h3>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <input type="hidden" name="edit_post_id" class="form-control" value="<?=$edit_post_id;?>" readonly>
        </div>
        <div class="form-group">
            <label for="edit_post_category_id">Регион</label>
            <select name="edit_post_category_id" id="edit_post_category_id" class="form-control">
                <?php showAllCategories("includes/all_categories_list.php", $edit_post_category_id); ?>
            </select>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['category_id'], "Выберите регион"); ?></span>
        </div>
        <div class="form-group">
            <label for="edit_post_title">Название</label>
            <input type="text" name="edit_post_title" id="edit_post_title" class="form-control" value="<?=$edit_post_title;?>">
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['title'], "Введите название публикации"); ?></span>
        </div>
        <div class="form-group">
            <label for="edit_post_author">Автор</label>
            <input type="text" name="edit_post_author" id="edit_post_author" class="form-control" value="<?=$edit_post_author;?>">
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['author'], "Введите автора публикации"); ?></span>
        </div>
        <div class="form-group">
            <label for="edit_post_date">Дата публикации</label>
            <input type="date" name="edit_post_date" id="edit_post_date" class="form-control" value="<?=$edit_post_date;?>">
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['date'], "Введите дату"); ?></span>
        </div>
        <div class="form-group">
            <label for="edit_post_image">Изображение</label>
            <img class="img-responsive" src="../img/<?=$edit_post_image;?>" alt="<?=$edit_post_title;?>" style="width: 100px;"><br>
            <input type="file" name="edit_post_image" id="edit_post_image" class="form-control">
            <input type="hidden" name="current_post_image" class="form-control" value="<?=$edit_post_image;?>" readonly>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['image'], "Загрузите изображение"); ?></span>
        </div>
        <div class="form-group">
            <label for="edit_post_content">Текст</label>
            <textarea name="edit_post_content" id="edit_post_content" class="form-control" rows="10"><?=$edit_post_content;?></textarea>
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['content'], "Введите текст публикации"); ?></span>
        </div>
        <div class="form-group">
            <label for="edit_post_tags">Тэги</label>
            <input type="text" name="edit_post_tags" id="edit_post_tags" class="form-control" value="<?=$edit_post_tags;?>">
        </div>
        <div class="form-group">
            <input type="hidden" name="edit_post_comments_count" class="form-control" value="<?=$edit_post_comments_count;?>" readonly>
        </div>
        <div class="form-group">
            <label for="edit_post_status">Статус</label>
            <input type="text" name="edit_post_status" id="edit_post_status" class="form-control" value="<?=$edit_post_status;?>">
            <span style='color: #a94442;'><?php displayErrorMessage($err_edit_post['status'], "Выберите статус публикации"); ?></span>
        </div>
        <div class="form-group">
            <input type="submit" name="update_post_btn" class="btn btn-primary" value="Обновить публикацию">
        </div>
    </form>
 </div>