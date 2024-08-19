<?php 
clickDeleteCommentIcon();
clickConfirmCommentIcon();
selectCommentOptions();
?>
<form action="" method="post">
    <!-- Comment Options Buttons -->
    <div class="col-lg-4 col-md-6 form-group" id="select-option">
        <div class="input-group">
            <select name="comment_option" class="form-control">
                <option value="">Выберите действие...</option>
                <option value="confirm">разрешить</option>
                <option value="block">отклонить</option>
                <option value="delete">удалить</option>
            </select>
            <span class="input-group-btn">
                <input type="submit" name="apply_comment_option_btn" class="btn btn-success" value="Применить" onclick="return confirmDeleteOption('Вы уверены, что хотите удалить выбранные комментарии?');">
            </span>
        </div>
    </div>
    <!-- /.col-lg-4.col-md-6 -->

    <!-- List of comments -->
    <div class="col-xs-12 form-group">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th><input type="checkbox" id="allCheckBoxes"></th>
                    <th>Название публикации</th>
                    <th>Пользователь</th>
                    <th>Дата комментария</th>
                    <th>Комментарий</th>
                    <th>E-mail</th>
                    <th>Статус</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                /* Put All Comments from database */
                $pager_values = showAllComments(3);
                ?>
            </tbody>
        </table>
    </div>
    <!-- /.col-xs-12.form-group -->
</form>

<!-- Pager -->
<div class="col-xs-12">
    <ul class="pager">
        <li>
            <a href="<?=$pager_values['previous_page_link'];?>">&larr; Предыдущая</a>
        </li>
        <?php showPagesAdminComments($pager_values['pages_cnt'], $pager_values['page_num']); ?>
        <li>
            <a href="<?=$pager_values['next_page_link'];?>">Следующая &rarr;</a>
        </li>
    </ul>
</div>
<!-- /.col-xs-12 -->