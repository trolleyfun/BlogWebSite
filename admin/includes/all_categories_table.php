<?php /* For function showAllCategories() */ ?>
<tr>
    <td><?=$cat_id;?></td>
    <td><?=$cat_title;?></td>
    <td><a href="admin_categories.php?edit_cat_id=<?=$cat_id;?>"><span class="fa fa-fw fa-edit"></span></a></td>
    <td><a href="admin_categories.php?delete_cat_id=<?=$cat_id;?>"><span class="fa fa-fw fa-trash-o"></span></a></td>
</tr>