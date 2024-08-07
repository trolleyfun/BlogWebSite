<?php /* For showPostById() function */ ?>
<!-- Comment -->
<div class="media">
    <a class="pull-left" href="post.php?post_id=<?=$comment_post_id;?>">
        <img class="media-object" src="img/user_icon.png" alt="Фотография пользователя">
    </a>
    <div class="media-body">
        <h4 class="media-heading"><?=$comment_author;?>
            <small><?=$comment_date;?></small>
        </h4>
        <?=$comment_content;?>
    </div>
</div>
<hr>
