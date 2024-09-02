<?php /* For function sendSupportMessage() */ ?>
<div class="col-md-6 col-xs-12">
    <h3>Возникли вопросы? Напишите нам!</h3>
    <form action="" method="post">
        <div class="form-group">
            <label for="support_subject">Тема обращения<span style='color: #a94442;'> *</span></label>
            <input type="text" name="support_subject" id="support_subject" class="form-control" required>
            <span style='color: #a94442;'><?php displayErrorMessage($err_support['subject'], "Это поле не может быть пустым"); ?></span>
        </div>
        <div class="form-group">
            <label for="support_email">E-mail для ответа<span style='color: #a94442;'> *</span></label>
            <input type="email" name="support_email" id="support_email" class="form-control" required>
            <span style='color: #a94442;'><?php displayErrorMessage($err_support['email_empty'], "Это поле не может быть пустым"); ?></span>
            <span style='color: #a94442;'><?php displayErrorMessage($err_support['email_correct'], "Некорректный e-mail"); ?></span>
        </div>
        <div class="form-group">
            <label for="support_content">Текст обращения<span style='color: #a94442;'> *</span></label>
            <textarea name="support_content" id="support_content" rows="7" class="form-control" required></textarea>
            <span style='color: #a94442;'><?php displayErrorMessage($err_support['content'], "Это поле не может быть пустым"); ?></span>
        </div>
        <div class="form-group">
            <input type="submit" name="send_support_btn" class="btn btn-primary" value="Отправить">
        </div>
    </form>
</div>
<!-- /.col-md-6.col-xs-12 -->