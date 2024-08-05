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
    
            header("Location: admin_categories.php");
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

/* Read all categories from database and display in $categoryFile Form */
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

        header("Location: admin_categories.php");
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

        include "includes/all_posts_table.php";
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
        $post_status = $_POST['post_status'];

        foreach($err_add_post as $err_item) {
            $err_item = false;
        }
        if ($post_category_id <= 0) {
            $err_add_post['category_id'] = true;
        }
        if ($post_title == "" || empty($post_title)) {
            $err_add_post['title'] = true;
        }
        if ($post_author == "" || empty($post_author)) {
            $err_add_post['author'] = true;
        }
        if ($post_image_name == "" || $post_image_err == UPLOAD_ERR_NO_FILE) {
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

            $query1 = "INSERT INTO posts(post_category_id, post_title, post_author, post_date, post_image, post_content, post_tags";
            if ($post_status == "" || empty($post_status)) {
                $query1 .= ") ";
            } else {
                $query1 .= ", post_status) ";
            }
            $query2 = "VALUES({$post_category_id}, '{$post_title}', '{$post_author}', '{$post_date}', '{$post_image_name}', '{$post_content}', '{$post_tags}'";
            if ($post_status == "" || empty($post_status)) {
                $query2 .= ");";
            } else {
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

        header("Location: admin_posts.php");
    }
}

/* Create Edit Post Form and put selected post's values from database into the form */
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

/* Put data from Edit Post Form to database */
function updatePosts() {
    global $connection;
    global $err_edit_post;

    if (isset($_POST['update_post_btn'])) {
        $update_post_id = $_POST['edit_post_id'];
        $update_post_category_id = $_POST['edit_post_category_id'];
        $update_post_title = $_POST['edit_post_title'];
        $update_post_author = $_POST['edit_post_author'];
        $update_post_date = $_POST['edit_post_date'];

        $is_new_post_image = true;
        $current_post_image_name = $_POST['current_post_image'];
        $update_post_image_name = $_FILES['edit_post_image']['name'];
        $update_post_image_temp = $_FILES['edit_post_image']['tmp_name'];
        $update_post_image_err = $_FILES['edit_post_image']['error'];
        if ($update_post_image_name == "" || $update_post_image_err == UPLOAD_ERR_NO_FILE) {
            $update_post_image_name = $current_post_image_name;
            $is_new_post_image = false;
        }

        $update_post_content = $_POST['edit_post_content'];
        $update_post_tags = $_POST['edit_post_tags'];
        $update_post_comments_count = $_POST['edit_post_comments_count'];
        $update_post_status = $_POST['edit_post_status'];

        foreach($err_edit_post as $err_item) {
            $err_item = false;
        }
        if ($update_post_id <= 0) {
            $err_edit_post['post_id'] = true;
        }
        if ($update_post_category_id <= 0) {
            $err_edit_post['category_id'] = true;
        }
        if ($update_post_title == "" || empty($update_post_title)) {
            $err_edit_post['title'] = true;
        }
        if ($update_post_author == "" || empty($update_post_author)) {
            $err_edit_post['author'] = true;
        }
        if ($update_post_date == "" || empty($update_post_date)) {
            $err_edit_post['date'] = true;
        }
        if ($update_post_image_name == "" || empty($update_post_image_name)) {
            $err_edit_post['image'] = true;
        }
        if ($update_post_content == "" || empty($update_post_content)) {
            $err_edit_post['content'] = true;
        }
        if ($update_post_comments_count < 0) {
            $err_edit_post['comments_count'] = true;
        }
        if ($update_post_status == "" || empty($update_post_status)) {
            $err_edit_post['status'] = true;
        }
        $err_result = false;
        foreach($err_edit_post as $err_item) {
            $err_result = $err_result || $err_item;
        }

        if (!$err_result) {
            if ($is_new_post_image) {
                move_uploaded_file($update_post_image_temp, "../img/{$update_post_image_name}");
            }

            $query = "UPDATE posts SET post_category_id = {$update_post_category_id}, ";
            $query .= "post_title = '{$update_post_title}', ";
            $query .= "post_author = '{$update_post_author}', ";
            $query .= "post_date = '{$update_post_date}', ";
            $query .= "post_image = '{$update_post_image_name}', ";
            $query .= "post_content = '{$update_post_content}', ";
            $query .= "post_tags = '{$update_post_tags}', ";
            $query .= "post_comments_count = '{$update_post_comments_count}', ";
            $query .= "post_status = '{$update_post_status}' ";
            $query .= "WHERE post_id = {$update_post_id};";

            $updatePost = mysqli_query($connection, $query);
            validateQuery($updatePost);

            header("Location: admin_posts.php");
        }
    }
}

/* Put all comments from database and display them in Comments Section in admin */
function showAllComments() {
    global $connection;

    $query = "SELECT * FROM comments JOIN posts ON comments.comment_post_id = posts.post_id ORDER BY comment_id DESC;";
    $allComments = mysqli_query($connection, $query);
    validateQuery($allComments);

    while($row = mysqli_fetch_assoc($allComments)) {
        $comment_id = $row['comment_id'];
        $comment_post_id = $row['comment_post_id'];
        $comment_post_title = $row['post_title'];
        $comment_author = $row['comment_author'];
        $comment_date = $row['comment_date'];
        $comment_content = $row['comment_content'];
        $comment_email = $row['comment_email'];
        $comment_status = $row['comment_status'];

        include "includes/all_comments_table.php";
    }
}

/* Delete selected comment from the database */
function deleteComments() {
    global $connection;

    if (isset($_GET['del_comment_id'])) {
        $del_comment_id = $_GET['del_comment_id'];

        $query = "SELECT * FROM comments WHERE comment_id = {$del_comment_id};";
        $statusDelComment = mysqli_query($connection, $query);
        validateQuery($statusDelComment);
        $del_comment_post_id = 0;
        while($row = mysqli_fetch_assoc($statusDelComment)) {
            $del_comment_post_id = $row['comment_post_id'];
        }

        $query = "DELETE FROM comments WHERE comment_id = $del_comment_id;";
        $deleteComment = mysqli_query($connection, $query);
        validateQuery($deleteComment);

        commentsCountByPost($del_comment_post_id);

        header("Location: admin_comments.php");
    }
}

/* Change status of the comment: approved or unapproved */
function confirmComments() {
    global $connection;

    if (isset($_GET['confirm_comment'])) {
        if ($_GET['confirm_comment'] == "true") {
            $confirm_comment_status = "одобрен";
        } else {
            $confirm_comment_status = "заблокирован";
        }

        if (isset($_GET['comment_id'])) {
            $confirm_comment_id = $_GET['comment_id'];

            $query = "SELECT * FROM comments WHERE comment_id = {$confirm_comment_id};";
            $statusConfirmComment = mysqli_query($connection, $query);
            validateQuery($statusConfirmComment);
            $confirm_comment_post_id = 0;
            while($row = mysqli_fetch_assoc($statusConfirmComment)) {
                $confirm_comment_post_id = $row['comment_post_id'];
            }

            $query = "UPDATE comments SET comment_status = '{$confirm_comment_status}' WHERE comment_id = {$confirm_comment_id};";
            $confirmComment = mysqli_query($connection, $query);
            validateQuery($confirmComment);

            commentsCountByPost($confirm_comment_post_id);

            header("Location: admin_comments.php");
        }
    }
}

/* Change Count of approved Comments in response to post wiyh $post_id in posts table in database. The difference between new value and previous one is $diff. If $diff is positive, then the count increases, if $diff is negative, then the count decreases. */
function changeCommentsCount($post_id, $diff) {
    global $connection;

    $query = "UPDATE posts SET post_comments_count = post_comments_count + {$diff} WHERE post_id = {$post_id};";
    $commentsCount = mysqli_query($connection, $query);
    validateQuery($commentsCount);
}

/* Update the Count of approved Comments in database */
function commentsCountByPost($post_id) {
    global $connection;

    $query = "SELECT comment_post_id, COUNT(comment_post_id) AS comments_count FROM comments ";
    $query .= "WHERE comment_status = 'одобрен' GROUP BY comment_post_id HAVING comment_post_id = {$post_id};";
    $commentsCount = mysqli_query($connection, $query);
    validateQuery($commentsCount);
    $post_comments_count = 0;
    while($row = mysqli_fetch_assoc($commentsCount)) {
        $post_comments_count = $row['comments_count'];
    }

    $query = "UPDATE posts SET post_comments_count = {$post_comments_count} WHERE post_id = {$post_id};";
    $updateCommentsCount = mysqli_query($connection, $query);
    validateQuery($updateCommentsCount);
}

/* Display all users from database */
function showAllUsers() {
    global $connection;

    $query = "SELECT * FROM users;";
    $allUsers = mysqli_query($connection, $query);
    validateQuery($allUsers);
    while($row = mysqli_fetch_assoc($allUsers)) {
        $user_id = $row['user_id'];
        $user_login = $row['user_login'];
        $user_password = $row['user_password'];
        $user_firstname = $row['user_firstname'];
        $user_lastname = $row['user_lastname'];
        $user_email = $row['user_email'];
        $user_image = $row['user_image'];
        $user_privilege = $row['user_privilege'];

        include "includes/all_users_table.php";
    }
}

/* Add information about new user to database */
function addUsers($err_status) {
    global $connection;

    if (isset($_POST['add_user_btn'])) {
        $user_login = $_POST['user_login'];
        $user_password = $_POST['user_password'];
        $user_firstname = $_POST['user_firstname'];
        $user_lastname = $_POST['user_lastname'];
        $user_email = $_POST['user_email'];

        $user_image_name = $_FILES['user_image']['name'];
        $user_image_tmp = $_FILES['user_image']['tmp_name'];
        $user_image_error = $_FILES['user_image']['error'];

        $user_privilege = $_POST['user_privilege'];

        foreach($err_status as $err_item) {
            $err_item = false;
        }
        if ($user_login == "" || empty($user_login)) {
            $err_status['login'] = true;
        }
        if ($user_password == "" || empty($user_password)) {
            $err_status['password'] = true;
        }
        if ($user_firstname == "" || empty($user_firstname)) {
            $err_status['firstname'] = true;
        }
        if ($user_lastname == "" || empty($user_lastname)) {
            $err_status['lastname'] = true;
        }
        if ($user_email == "" || empty($user_email)) {
            $err_status['email'] = true;
        }
        if ($user_image_name == "" || $user_image_error == UPLOAD_ERR_NO_FILE) {
            $err_status['image'] = true;
        }
        if ($user_privilege == "" || empty($user_privilege)) {
            $err_status['privilege'] = true;
        }
        $err_result = false;
        foreach($err_status as $err_item) {
            $err_result = $err_result || $err_item;
        }

        if (!$err_result) {
            move_uploaded_file($user_image_tmp, "../img/{$user_image_name}");

            $query = "INSERT INTO users(user_login, user_password, user_firstname, user_lastname, user_email, user_image, user_privilege) VALUES('{$user_login}', '{$user_password}', '{$user_firstname}', '{$user_lastname}', '{$user_email}', '{$user_image_name}', '{$user_privilege}');";
            $addUser = mysqli_query($connection, $query);
            validateQuery($addUser);
        }
    }

    return $err_status;
}

/* Delete user from database */
function deleteUsers() {
    global $connection;

    if (isset($_GET['del_user_id'])) {
        $user_id = $_GET['del_user_id'];

        $query = "DELETE FROM users WHERE user_id = {$user_id};";
        $delUser = mysqli_query($connection, $query);
        validateQuery($delUser);

        header("Location: admin_users.php");
    }
}
?>