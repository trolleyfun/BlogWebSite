<?php /* For showPostById() function */ ?>
<!-- Comment -->
<div class="media">
    <a class="pull-left" href="post.php?post_id=<?=$comment_post_id;?>">
        <img class="media-object" src="img/<?=$comment_user_image;?>" alt="Фотография пользователя" style="width: 70px;">
    </a>
    <div class="media-body">
        <h4 class="media-heading">
            <?php if (!is_null($comment_user_id)) { ?>
            <?=$comment_user_login;?>
            <?php } else { ?>
            Неизвестный пользователь
            <?php } ?>
            <small><?=$comment_date;?></small>
        </h4>
        <?=$comment_content;?>
    </div>
</div>
<hr>
