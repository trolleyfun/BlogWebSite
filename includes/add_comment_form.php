<?php /* For showPostById() function */ ?>
<!-- Blog Comments -->

<!-- Comments Form -->
<div class="well" id="add_comment_form">
    <span style='color: #a94442;'><?php displayMessage($err_add_comment['author'], "Ошибка авторизации"); ?></span>    
    <h4>Оставьте комментарий</h4>
    <span style='color: #008000;'><?php displayMessage($err_add_comment['if_sent'], "Ваш комментарий успешно отправлен"); ?></span>
    <form role="form" action="" method="post">
        <div class="form-group">
            <textarea name="comment_content" class="form-control summernote-comment" rows="3" required></textarea>
            <span style='color: #a94442;'><?php displayMessage($err_add_comment['content'], "Это поле не может быть пустым"); ?></span>
        </div>
        <button type="submit" name="add_comment_btn" class="btn btn-primary">Отправить</button>
    </form>
</div>
<hr>

<!-- Posted Comments -->
