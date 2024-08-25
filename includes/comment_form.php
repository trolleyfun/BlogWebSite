<?php /* For showPostById() function */ ?>
<!-- Comment -->
<div class="media">
    <img class="media-object pull-left" src="img/<?=$comment_user_image;?>" alt="Фотография пользователя" style="width: 80px;">
    <div class="media-body">
        <h4 class="media-heading">
            <?php if (!is_null($comment_user_id)) { ?>
            <?=$comment_user_login;?>
            <?php } else { ?>
            Неизвестный пользователь
            <?php } ?>
            <small><?=$comment_date;?></small>
        </h4>
        <div><?=$comment_content;?></div>
    </div>
</div>
<hr>
