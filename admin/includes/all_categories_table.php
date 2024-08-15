<?php /* For function showAllCategories() */ ?>
<tr>
    <td><input type="checkbox" class="checkBoxes" name="checkBoxArray[]" value="<?=$cat_id;?>"></td>
    <td><a href="../category.php?cat_id=<?=$cat_id;?>"><?=$cat_title;?></a></td>
    <td><?=$cat_posts_count;?></td>
    <td><a href="admin_categories.php?edit_cat_id=<?=$cat_id;?>"><span class="fa fa-fw fa-edit"></span></a></td>
    <td><a href="admin_categories.php?delete_cat_id=<?=$cat_id;?>" onclick="return confirm('Вы уверены, что хотите удалить регион?');"><span class="fa fa-fw fa-trash-o"></span></a></td>
</tr>