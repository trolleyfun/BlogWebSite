<?php /* For showPostById() function */ ?>
<!-- Blog Comments -->

<!-- Comments Form -->
<div class="well">
    <h4>Оставьте комментарий</h4>
    <form role="form" action="" method="post">
        <div class="form-group">
            <label for="comment_author">Пользователь:</label>
            <input type="text" name="comment_author" id="comment_author" class="form-control">
            <span style='color: #a94442;'><?php displayErrorMessage($err_add_comment['comment_author'], "Это поле не может быть пустым"); ?></span>
        </div>
        <div class="form-group">
            <label for="comment_email">E-mail:</label>
            <input type="email" name="comment_email" id="comment_email" class="form-control">
            <span style='color: #a94442;'><?php displayErrorMessage($err_add_comment['comment_email'], "Это поле не может быть пустым"); ?></span>
        </div>
        <div class="form-group">
            <label for="comment_content">Комментарий:</label>
            <textarea name="comment_content" id="comment_content" class="form-control" rows="3"></textarea>
            <span style='color: #a94442;'><?php displayErrorMessage($err_add_comment['comment_content'], "Это поле не может быть пустым"); ?></span>
        </div>
        <button type="submit" name="add_comment_btn" class="btn btn-primary">Отправить</button>
    </form>
</div>

<!-- Posted Comments -->
