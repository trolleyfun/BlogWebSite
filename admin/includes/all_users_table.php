<?php /* For function showAllUsers() */ ?>
<tr>
    <td><?=$user_id;?></td>
    <td><?=$user_login;?></td>
    <td><?=$user_firstname;?></td>
    <td><?=$user_lastname;?></td>
    <td><?=$user_email;?></td>
    <td><img src="../img/<?=$user_image;?>" alt="Изображение пользователя" style="max-width: 70px;"></td>
    <td><?=$user_privilege;?></td>
    <td><a href="admin_users.php?source=edit_users&edit_user_id=<?=$user_id;?>"><span class="fa fa-fw fa-edit"></span></a></td>
    <td><a href="admin_users.php?delete_user_id=<?=$user_id;?>"><span class="fa fa-fw fa-trash-o"></span></a></td>
</tr>