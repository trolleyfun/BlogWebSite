<?php 
/* Update category in database */
$is_edit_empty = false;
if (isset($_POST['update_cat_btn'])) {
    $update_id = $_POST['edit_id'];
    $update_title = $_POST['edit_title'];

    $is_edit_empty = false;
    if ($update_title == "" || empty($update_title)) {
        $is_edit_empty = true;
    } else {
        $query = "UPDATE categories SET cat_title = '{$update_title}' WHERE cat_id = {$update_id};";
        if (!$updateCategory = mysqli_query($connection, $query)) {
            die("Query to database failed." . mysqli_error($connection));
        }

        header("Location: categories.php");
    }
}
?>
<?php
/* Put edit category from database to the form */
$query = "SELECT * FROM categories WHERE cat_id = {$edit_id};";
if (!$editCategory = mysqli_query($connection, $query)) {
    die("Query to database failed." . mysqli_error($connection));
} else {
    while($row = mysqli_fetch_assoc($editCategory)) {
        $edit_id_db = $row['cat_id'];
        $edit_title = $row['cat_title'];
?>

<!-- Category Edit Form -->
<form action="" method="post">
    <div class="form-group">
        <input type="hidden" name="edit_id" id="edit_id" class="form-control" value="<?=$edit_id_db;?>" readonly>
        <label for="edit_title">Исправьте название региона Id=<?=$edit_id;?>:</label>
        <input type="text" name="edit_title" id="edit_title" autofocus="autofocus"  class="form-control" value="<?=$edit_title;?>">
        <?php 
        /* Write an error message if field is empty */
        if ($is_edit_empty) {
            echo "<p style='color: #a94442;'>Введите название региона</p>";
        }
        ?>
    </div>
    <div class="form-group">
        <input type="submit" name="update_cat_btn" class="btn btn-primary" value="Обновить регион">
    </div>
</form>
<?php
    }
}
?>