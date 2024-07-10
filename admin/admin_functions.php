<?php
/* Check if the query is successful. If not, intercept the program and display an error message */
function validateQuery($result) {
    global $connection;

    if (!$result) {
        die("Query to database failed. " . mysqli_error($connection));
    }
}

/* Display Message from argument $message if input data is invalid. $status = false: data is valid, $status = true: data is invalid */
function displayErrorMessage($status, $message) {
    if ($status) {
        echo $message;
    }
}

/* Add category from input field of Add Form to database */
function addCategories() {
    global $connection;
    global $err_add_cat_title;
    if (isset($_POST['add_cat_btn'])) {
        $cat_title = $_POST['cat_title'];
        $err_add_cat_title = false;
        if ($cat_title == "" || empty($cat_title)) {
            $err_add_cat_title = true;
        } else {
            $query = "INSERT INTO categories(cat_title) VALUE('{$cat_title}');";
            $addCategory = mysqli_query($connection, $query);
            validateQuery($addCategory);
        }
    }
}

/* Update category in database from input field of Edit Form */
function updateCategories() {
    global $connection;
    global $err_edit_cat_title;

    if (isset($_POST['update_cat_btn'])) {
        $update_id = $_POST['edit_id'];
        $update_title = $_POST['edit_title'];
    
        $err_edit_cat_title = false;
        if ($update_title == "" || empty($update_title)) {
            $err_edit_cat_title = true;
        } else {
            $query = "UPDATE categories SET cat_title = '{$update_title}' WHERE cat_id = {$update_id};";
            $updateCategory = mysqli_query($connection, $query);
            validateQuery($updateCategory);
    
            header("Location: categories.php");
        }
    }
}

/* Create Edit Form for selected category and put id and title of the category from the database */
function editCategories() {
    global $connection;
    global $err_edit_cat_title;

    if (isset($_GET['edit_id'])) {
        $edit_id = $_GET['edit_id'];
        $query = "SELECT * FROM categories WHERE cat_id = {$edit_id};";
        $editCategory = mysqli_query($connection, $query);
        validateQuery($editCategory);

        while($row = mysqli_fetch_assoc($editCategory)) {
            $edit_id_db = $row['cat_id'];
            $edit_title = $row['cat_title'];
            include "includes/edit_categories.php";
        }
    }
}

/* Read all categories from database and display in Categories Section of admin */
function showAllCategories($categoriesFile, $arg) {
    global $connection;

    $query = "SELECT * FROM categories;";
    $adminCategories = mysqli_query($connection,$query);
    validateQuery($adminCategories);

    while($row = mysqli_fetch_assoc($adminCategories)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];

        include $categoriesFile;
    }
}

/* Delete category in database */
function deleteCategories() {
    global $connection;

    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        $query = "DELETE FROM categories WHERE cat_id = {$delete_id};";

        $deleteCategory = mysqli_query($connection, $query);
        validateQuery($deleteCategory);

        header("Location: categories.php");
    }
}

/* Read all posts from database and display them in Posts Section in admin */
function showAllPosts() {
    global $connection;

    $query = "SELECT * FROM posts JOIN categories ON posts.post_category_id = categories.cat_id;";
    $allPosts = mysqli_query($connection, $query);
    validateQuery($allPosts);

    while($row = mysqli_fetch_assoc($allPosts)) {
        $post_id = $row['post_id'];
        $post_category_id = $row['post_category_id'];
        $post_category_title = $row['cat_title'];
        $post_title = $row['post_title'];
        $post_author = $row['post_author'];
        $post_date = $row['post_date'];
        $post_image = $row['post_image'];
        $post_tags = $row['post_tags'];
        $post_comments_count = $row['post_comments_count'];
        $post_status = $row['post_status'];

        echo "<tr>";
        echo "<td>{$post_id}</td>";
        echo "<td>{$post_category_title}</td>";
        echo "<td>{$post_title}</td>";
        echo "<td>{$post_author}</td>";
        echo "<td>{$post_date}</td>";
        echo "<td><img src='../img/{$post_image}' alt='{$post_title}' style='width: 100px;'></td>";
        echo "<td>{$post_tags}</td>";
        echo "<td>{$post_comments_count}</td>";
        echo "<td>{$post_status}</td>";
        echo "<td><a href='posts.php?source=edit_posts&edit_post_id={$post_id}'><span class='fa fa-fw fa-edit'></span></a></td>";
        echo "<td><a href='posts.php?delete_post_id={$post_id}'><span class='fa fa-fw fa-trash-o'></span></a></td>";
        echo "</tr>";
    }
}

/* Put Post from Add Post Form to database */
function addPosts() {
    global $connection;
    global $err_add_post;

    if (isset($_POST['add_post_btn'])) {
        $post_category_id = $_POST['post_category_id'];
        $post_title = $_POST['post_title'];
        $post_author = $_POST['post_author'];
        $post_date = date('Y-m-d');

        $post_image_name = $_FILES['post_image']['name'];
        $post_image_temp = $_FILES['post_image']['tmp_name'];
        $post_image_err = $_FILES['post_image']['error'];

        $post_content = $_POST['post_content'];
        $post_tags = $_POST['post_tags'];
        $post_comments_count = 1;
        $post_status = $_POST['post_status'];

        foreach($err_add_post as $err_item) {
            $err_item = false;
        }
        if ($post_category_id == "" || empty($post_category_id)) {
            $err_add_post['category_id'] = true;
        }
        if ($post_title == "" || empty($post_title)) {
            $err_add_post['title'] = true;
        }
        if ($post_author == "" || empty($post_author)) {
            $err_add_post['author'] = true;
        }
        if ($post_image_err == UPLOAD_ERR_NO_FILE) {
            $err_add_post['image'] = true;
        }
        if ($post_content == "" || empty($post_content)) {
            $err_add_post['content'] = true;
        }
        $err_result = false;
        foreach($err_add_post as $err_item) {
            $err_result = $err_result || $err_item;
        }

        if (!$err_result) {
            move_uploaded_file($post_image_temp, "../img/{$post_image_name}");

            $query1 = "INSERT INTO posts(post_category_id, post_title, post_author, post_date, post_image, post_content, post_tags, post_comments_count";
            $query2 = "VALUES({$post_category_id}, '{$post_title}', '{$post_author}', '{$post_date}', '{$post_image_name}', '{$post_content}', '{$post_tags}', {$post_comments_count}";
            if ($post_status == "" || empty($post_status)) {
                $query1 .= ") ";
                $query2 .= ");";
            } else {
                $query1 .= ", post_status) ";
                $query2 .= ", '{$post_status}');";
            }
            $query = $query1 . $query2;

            $addPost = mysqli_query($connection, $query);
            validateQuery($addPost);
        }
    }
}

/* Delete selected post from the database */
function deletePosts() {
    global $connection;

    if (isset($_GET['delete_post_id'])) {
        $delete_post_id = $_GET['delete_post_id'];
        $query = "DELETE FROM posts WHERE post_id={$delete_post_id};";

        $deletePost = mysqli_query($connection, $query);
        validateQuery($deletePost);

        header("Location: posts.php");
    }
}

function editPosts() {
    global $connection;
    global $err_edit_post;
    
    if (isset($_GET['edit_post_id'])) {
        $edit_post_id = $_GET['edit_post_id'];

        $query = "SELECT * FROM posts WHERE post_id = {$edit_post_id};";
        $editPost = mysqli_query($connection, $query);
        validateQuery($editPost);

        while($row = mysqli_fetch_assoc($editPost)) {
            $edit_post_id = $row['post_id'];
            $edit_post_category_id = $row['post_category_id'];
            $edit_post_title = $row['post_title'];
            $edit_post_author = $row['post_author'];
            $edit_post_date = $row['post_date'];
            $edit_post_image = $row['post_image'];
            $edit_post_content = $row['post_content'];
            $edit_post_tags = $row['post_tags'];
            $edit_post_comments_count = $row['post_comments_count'];
            $edit_post_status = $row['post_status'];

            include "includes/edit_posts.php";
        }
    }
}
?>