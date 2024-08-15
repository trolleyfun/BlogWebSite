<?php /* For showPostById() function */ ?>
<!-- Blog Comments -->

<!-- Comments Form -->
<div class="well" id="add_comment_form">
    <h4>Оставьте комментарий</h4>
    <span style='color: #008000;'><?php displayMessage($err_add_comment['if_sent'], "Ваш комментарий успешно отправлен"); ?></span>
    <form role="form" action="" method="post">
        <div class="form-group">
            <label for="comment_author">Пользователь<span style='color: #a94442;'> *</span></label>
            <input type="text" name="comment_author" id="comment_author" class="form-control" required>
            <span style='color: #a94442;'><?php displayMessage($err_add_comment['author'], "Это поле не может быть пустым"); ?></span>
        </div>
        <div class="form-group">
            <label for="comment_email">E-mail<span style='color: #a94442;'> *</span></label>
            <input type="email" name="comment_email" id="comment_email" class="form-control" required>
            <span style='color: #a94442;'><?php displayMessage($err_add_comment['email'], "Это поле не может быть пустым"); ?></span>
        </div>
        <div class="form-group">
            <label for="comment_content">Комментарий<span style='color: #a94442;'> *</span></label>
            <textarea name="comment_content" id="comment_content" class="form-control summernote-comment" rows="3" required></textarea>
            <span style='color: #a94442;'><?php displayMessage($err_add_comment['content'], "Это поле не может быть пустым"); ?></span>
        </div>
        <button type="submit" name="add_comment_btn" class="btn btn-primary">Отправить</button>
    </form>
</div>
<hr>

<!-- Posted Comments -->
