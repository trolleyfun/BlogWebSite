<?php /* For function showAllCategories */ ?>
<tr>
    <td><?=$cat_id;?></td>
    <td><?=$cat_title;?></td>
    <td><a href="categories.php?edit_id=<?=$cat_id;?>"><span class="fa fa-fw fa-edit"></span></a></td>
    <td><a href="categories.php?delete_id=<?=$cat_id;?>"><span class="fa fa-fw fa-trash-o"></span></a></td>
</tr>