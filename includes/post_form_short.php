<?php /* Posts Form for showAllPosts(), showPostByCategory() and searchPosts() functions */ ?>
<h2>
    <a href="post.php?post_id=<?=$post_id;?>"><?=$post_title;?></a>
</h2>
<p class="lead">
    автор: <?=$post_author;?>
</p>
<p><span class="glyphicon glyphicon-time"></span> Опубликовано <?=$post_date;?></p>
<hr>
<a href="post.php?post_id=<?=$post_id;?>">
    <img class="img-responsive" src="img/<?=$post_image;?>" alt="<?=$post_title;?>" style="width: 80%;">
</a>
<hr>
<p><?=$post_content;?></p>
<a class='btn btn-primary' href='post.php?post_id=<?=$post_id;?>'>Читать дальше <span class='glyphicon glyphicon-chevron-right'></span></a>
<hr>