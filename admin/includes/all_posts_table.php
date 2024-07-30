<?php /* For function showAllPosts() */ ?>
<tr>
    <td><?=$post_id;?></td>
    <td><?=$post_category_title;?></td>
    <td><?=$post_title;?></td>
    <td><?=$post_author;?></td>
    <td><?=$post_date;?></td>
    <td><img src="../img/<?=$post_image;?>" alt="<?=$post_title;?>" style="width: 100px;"></td>
    <td><?=$post_tags;?></td>
    <td><?=$post_comments_count;?></td>
    <td><?=$post_status;?></td>
    <td><a href="admin_posts.php?source=edit_posts&edit_post_id=<?=$post_id;?>"><span class="fa fa-fw fa-edit"></span></a></td>
    <td><a href="admin_posts.php?delete_post_id=<?=$post_id;?>"><span class="fa fa-fw fa-trash-o"></span></a></td>
</tr>