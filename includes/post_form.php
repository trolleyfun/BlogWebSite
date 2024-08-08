<?php /* Posts Form for showAllPosts and searchPosts functions */ ?>
<h2>
    <a href="post.php?post_id=<?=$post_id;?>"><?=$post_title;?></a>
</h2>
<p class="lead">
    автор: <a href="index.php"><?=$post_author;?></a>
</p>
<p><span class="glyphicon glyphicon-time"></span> Опубликовано <?=$post_date;?></p>
<hr>
<a href="post.php?post_id=<?=$post_id;?>">
    <img class="img-responsive" src="img/<?=$post_image;?>" alt="<?=$post_title;?>" style="max-width: 80%;">
</a>
<hr>
<p><?=$post_content;?></p>
<?php if ($is_btn) {include "includes/post_button.php";}?>
<hr>