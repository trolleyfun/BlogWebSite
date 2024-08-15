<?php /* For function showAllPosts() */ ?>
<tr>
    <td><input type="checkbox" class="checkBoxes" name="checkBoxArray[]" value="<?=$post_id;?>"></td>
    <td><a href="../post.php?post_id=<?=$post_id;?>"><?=$post_title;?></a></td>
    <td><a href="../category.php?cat_id=<?=$post_category_id;?>"><?=$post_category_title;?></a></td>
    <td><?=$post_author;?></td>
    <td><?=$post_date;?></td>
    <td><a href="../post.php?post_id=<?=$post_id;?>"><img src="../img/<?=$post_image;?>" alt="<?=$post_title;?>" style="max-width: 100px;"></a></td>
    <td><?=$post_tags;?></td>
    <td><?=$post_comments_count;?></td>
    <td><?=$post_status;?></td>
    <td><a href="admin_posts.php?source=edit_posts&edit_post_id=<?=$post_id;?>"><span class="fa fa-fw fa-edit"></span></a></td>
    <td><a href="admin_posts.php?delete_post_id=<?=$post_id;?>" onclick="return confirm('Вы уверены, что хотите удалить публикацию?');"><span class="fa fa-fw fa-trash-o"></span></a></td>
</tr>