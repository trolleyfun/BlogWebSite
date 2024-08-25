<?php /* Posts Form for showAllPosts(), showPostByCategory() and searchPosts() functions */ ?>
<div>
    <h2>
        <a href="post.php?post_id=<?=$post_id;?>"><?=$post_title;?></a>
    </h2>
    <p class="lead">
        автор: 
        <?php if (!is_null($post_author_id)) { ?>
        <?=$post_author_login;?>
        <?php } else { ?>
        Неизвестный пользователь
        <?php } ?>
    </p>
    <p><span class="glyphicon glyphicon-time"></span> Опубликовано <?=$post_date;?></p>
    <hr>
    <a href="post.php?post_id=<?=$post_id;?>">
        <img class="img-responsive" src="img/<?=$post_image;?>" alt="<?=$post_title;?>" style="width: 70%;">
    </a>
    <hr>
    <div class="post-content-short form-group"><?=$post_content;?></div>
    <a class='btn btn-primary' href='post.php?post_id=<?=$post_id;?>'>Читать дальше <span class='glyphicon glyphicon-chevron-right'></span></a>
</div>
<hr>