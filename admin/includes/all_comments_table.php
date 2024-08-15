<?php /* For function showAllComments() */ ?>
<tr>
    <td><input type="checkbox" name="checkBoxArray[]" class="checkBoxes" value="<?=$comment_id;?>"></td>
    <td><a href="../post.php?post_id=<?=$comment_post_id;?>"><?=$comment_post_title;?></a></td>
    <td><?=$comment_author;?></td>
    <td><?=$comment_date;?></td>
    <td><?=$comment_content;?></td>
    <td><?=$comment_email;?></td>
    <td><?=$comment_status;?></td>
    <td><a href="admin_comments.php?confirm_comment=confirm&comment_id=<?=$comment_id;?>">разрешить</a> <a href="admin_comments.php?confirm_comment=block&comment_id=<?=$comment_id;?>">отклонить</a></td>
    <td><a href="admin_comments.php?delete_comment_id=<?=$comment_id;?>" onclick="return confirm('Вы уверены, что хотите удалить комментарий?');"><span class="fa fa-fw fa-trash-o"></span></a></td>
</tr>