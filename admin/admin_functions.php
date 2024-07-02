<?php
/* Add category from input field of Add Form to database */
function addCategories() {
    global $connection;
    global $is_add_empty;
    if (isset($_POST['add_cat_btn'])) {
        $cat_title = $_POST['cat_title'];
        $is_add_empty = false;
        if ($cat_title == "" || empty($cat_title)) {
            $is_add_empty = true;
        } else {
            $query = "INSERT INTO categories(cat_title) VALUE('{$cat_title}');";
            if (!$addCategory = mysqli_query($connection, $query)) {
                die("Query to database failed." . mysqli_error($connection));
            }
        }
    }
}

/* Update category in database from input field of Edit Form */
function updateCategories() {
    global $connection;
    global $is_edit_empty;

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
}

/* Read all categories from database and display in Categories Section of admin */
function showAllCategories() {
    global $connection;

    $query = "SELECT * FROM categories;";
    if (!$adminCategories = mysqli_query($connection,$query)) {
        die("Query to database failed." . mysqli_error($connection));
    } else {
        while($row = mysqli_fetch_assoc($adminCategories)) {
            $cat_id = $row['cat_id'];
            $cat_title = $row['cat_title'];

            echo "<tr>";
            echo "<td>{$cat_id}</td>";
            echo "<td>{$cat_title}</td>";
            echo "<td><a href='categories.php?delete_id={$cat_id}'><span class='fa fa-fw fa-trash-o'></span></a></td>";
            echo "<td><a href='categories.php?edit_id={$cat_id}'><span class='fa fa-fw fa-edit'></span></a></td>";
            echo "</tr>";
        }
    }
}

/* Delete category in database */
function deleteCategories() {
    global $connection;

    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        $query = "DELETE FROM categories WHERE cat_id = {$delete_id};";

        if (!$deleteCategory = mysqli_query($connection, $query)) {
            die("Query to database failed." . mysqli_error($connection));
        }

        header("Location: categories.php");
    }
}

/* Read all posts from database and display them in Posts Section in admin */
function showAllPosts() {
    global $connection;

    $query = "SELECT * FROM posts;";
    if (!$allPosts = mysqli_query($connection, $query)) {
        die("Query to database failed." . mysqli_error($connection));
    } else {
        while($row = mysqli_fetch_assoc($allPosts)) {
            $post_id = $row['post_id'];
            $post_category_id = $row['post_category_id'];
            $post_title = $row['post_title'];
            $post_author = $row['post_author'];
            $post_date = $row['post_date'];
            $post_image = $row['post_image'];
            $post_tags = $row['post_tags'];
            $post_comments_count = $row['post_comments_count'];
            $post_status = $row['post_status'];

            echo "<tr>";
            echo "<td>{$post_id}</td>";
            echo "<td>{$post_category_id}</td>";
            echo "<td>{$post_title}</td>";
            echo "<td>{$post_author}</td>";
            echo "<td>{$post_date}</td>";
            echo "<td><img src='../img/{$post_image}' alt='{$post_title}' style='width: 100px;'></td>";
            echo "<td>{$post_tags}</td>";
            echo "<td>{$post_comments_count}</td>";
            echo "<td>{$post_status}</td>";
            echo "</tr>";
        }
    }
}
?>