<?php
/* Check if the query is successful. If not, intercept the program and display an error post_operation_message */
function validateQuery($result) {
    global $connection;

    if (!$result) {
        die("Query to database failed. " . mysqli_error($connection));
    }
}

/* Display Message from argument $post_operation_message if input data is invalid. $status = false: data is valid, $status = true: data is invalid */
function displayErrorMessage($status, $post_operation_message) {
    if ($status) {
        echo $post_operation_message;
    }
}

/* Add category from input field of Add Form to database */
function addCategories() {
    global $connection;

    $err_add_cat = ['title'=>false];

    if (isset($_POST['add_cat_btn'])) {
        $cat_title = $_POST['cat_title'];
        foreach($err_add_cat as $err_item) {
            $err_item = false;
        }
        if ($cat_title == "" || empty($cat_title)) {
            $err_add_cat['title'] = true;
        } 
        $err_result = false;
        foreach($err_add_cat as $err_item) {
            $err_result = $err_result || $err_item;
        }
        if (!$err_result) {
            $query = "INSERT INTO categories(cat_title) VALUE('{$cat_title}');";
            $addCategory = mysqli_query($connection, $query);
            validateQuery($addCategory);

            header("Location: admin_categories.php?source=info&operation=add");
        }
    }

    include "includes/add_categories.php";
}

/* Update category in database from input field of Edit Form */
function updateCategories($cat_id, $err_status) {
    global $connection;

    if (isset($_POST['update_cat_btn'])) {
        $cat_title = $_POST['edit_cat_title'];
    
        foreach($err_status as $err_item) {
            $err_item = false;
        }
        if ($cat_title == "" || empty($cat_title)) {
            $err_status['title'] = true;
        }
        $err_result = false;
        foreach($err_status as $err_item) {
            $err_result = $err_result || $err_item;
        }
        
        if (!$err_result) {
            $query = "UPDATE categories SET cat_title = '{$cat_title}' WHERE cat_id = {$cat_id};";
            $updateCategory = mysqli_query($connection, $query);
            validateQuery($updateCategory);
    
            header("Location: admin_categories.php?source=info&operation=update");
        }
    }

    return $err_status;
}

/* Create Edit Form for selected category and put id and title of the category from the database */
function editCategories() {
    global $connection;

    if (isset($_GET['edit_cat_id'])) {
        $edit_cat_id = $_GET['edit_cat_id'];
        $query = "SELECT * FROM categories WHERE cat_id = {$edit_cat_id};";
        $editCategory = mysqli_query($connection, $query);
        validateQuery($editCategory);

        if ($row = mysqli_fetch_assoc($editCategory)) {
            $cat_id = $row['cat_id'];
            $cat_title = $row['cat_title'];

            $err_edit_cat = ['title'=>false];

            $err_edit_cat = updateCategories($cat_id, $err_edit_cat);
            include "includes/edit_categories.php";
        }
    }
}

/* Read all categories from database and display in $categoryFile Form */
function showAllCategories($categoriesForm, $arg) {
    global $connection;

    $query = "SELECT * FROM categories;";
    $adminCategories = mysqli_query($connection,$query);
    validateQuery($adminCategories);

    while($row = mysqli_fetch_assoc($adminCategories)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];

        include $categoriesForm;
    }
}

/* Delete category in database */
function deleteCategories() {
    global $connection;

    if (isset($_GET['delete_cat_id'])) {
        $delete_cat_id = $_GET['delete_cat_id'];
        $query = "DELETE FROM categories WHERE cat_id = {$delete_cat_id};";

        $deleteCategory = mysqli_query($connection, $query);
        validateQuery($deleteCategory);

        header("Location: admin_categories.php?source=info&operation=delete");
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

    $err_add_post = ['category_id'=>false, 'title'=>false, 'author'=>false, 'image'=>false, 'content'=>false, 'status'=>false];

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
        if ($post_status == "" || empty($post_status)) {
            $err_add_post['status'] = true;
        }
        $err_result = false;
        foreach($err_add_post as $err_item) {
            $err_result = $err_result || $err_item;
        }

        if (!$err_result) {
            move_uploaded_file($post_image_temp, "../img/{$post_image_name}");

            $query = "INSERT INTO posts(post_category_id, post_title, post_author, post_date, post_image, post_content, post_tags, post_status) ";
            $query .= "VALUES({$post_category_id}, '{$post_title}', '{$post_author}', '{$post_date}', '{$post_image_name}', '{$post_content}', '{$post_tags}', '{$post_status}');";

            $addPost = mysqli_query($connection, $query);
            validateQuery($addPost);

            header("Location: admin_posts.php?source=info&operation=add");
        }
    }

    include "includes/add_posts.php";
}

/* Delete selected post from the database */
function deletePosts() {
    global $connection;

    if (isset($_GET['delete_post_id'])) {
        $delete_post_id = $_GET['delete_post_id'];
        $query = "DELETE FROM posts WHERE post_id={$delete_post_id};";

        $deletePost = mysqli_query($connection, $query);
        validateQuery($deletePost);

        header("Location: admin_posts.php?source=info&operation=delete");
    }
}

/* Create Edit Post Form and put selected post's values from database into the form */
function editPosts() {
    global $connection;
    
    if (isset($_GET['edit_post_id'])) {
        $edit_post_id = $_GET['edit_post_id'];

        $query = "SELECT * FROM posts WHERE post_id = {$edit_post_id};";
        $editPost = mysqli_query($connection, $query);
        validateQuery($editPost);

        if ($row = mysqli_fetch_assoc($editPost)) {
            $post_id = $row['post_id'];
            $post_category_id = $row['post_category_id'];
            $post_title = $row['post_title'];
            $post_author = $row['post_author'];
            $post_date = $row['post_date'];
            $post_image = $row['post_image'];
            $post_content = $row['post_content'];
            $post_tags = $row['post_tags'];
            $post_comments_count = $row['post_comments_count'];
            $post_status = $row['post_status'];

            $err_edit_post = ['category_id'=>false, 'title'=>false, 'author'=>false, 'date'=>false, 'image'=>false, 'content'=>false, 'status'=>false];

            $err_edit_post = updatePosts($edit_post_id, $err_edit_post);

            include "includes/edit_posts.php";
        }
    }
}

/* Put data from Edit Post Form to database */
function updatePosts($post_id, $err_status) {
    global $connection;

    if (isset($_POST['update_post_btn'])) {
        $post_category_id = $_POST['edit_post_category_id'];
        $post_title = $_POST['edit_post_title'];
        $post_author = $_POST['edit_post_author'];
        $post_date = $_POST['edit_post_date'];

        $is_new_post_image = true;
        $current_post_image_name = $_POST['current_post_image'];
        $post_image_name = $_FILES['edit_post_image']['name'];
        $post_image_temp = $_FILES['edit_post_image']['tmp_name'];
        $post_image_err = $_FILES['edit_post_image']['error'];
        if ($post_image_name == "" || $post_image_err == UPLOAD_ERR_NO_FILE) {
            $post_image_name = $current_post_image_name;
            $is_new_post_image = false;
        }

        $post_content = $_POST['edit_post_content'];
        $post_tags = $_POST['edit_post_tags'];
        $post_status = $_POST['edit_post_status'];

        foreach($err_status as $err_item) {
            $err_item = false;
        }
        if ($post_category_id <= 0) {
            $err_status['category_id'] = true;
        }
        if ($post_title == "" || empty($post_title)) {
            $err_status['title'] = true;
        }
        if ($post_author == "" || empty($post_author)) {
            $err_status['author'] = true;
        }
        if ($post_date == "" || empty($post_date)) {
            $err_status['date'] = true;
        }
        if ($post_image_name == "" || empty($post_image_name)) {
            $err_status['image'] = true;
        }
        if ($post_content == "" || empty($post_content)) {
            $err_status['content'] = true;
        }
        if ($post_status == "" || empty($post_status)) {
            $err_status['status'] = true;
        }
        $err_result = false;
        foreach($err_status as $err_item) {
            $err_result = $err_result || $err_item;
        }

        if (!$err_result) {
            if ($is_new_post_image) {
                move_uploaded_file($post_image_temp, "../img/{$post_image_name}");
            }

            $query = "UPDATE posts SET post_category_id = {$post_category_id}, ";
            $query .= "post_title = '{$post_title}', ";
            $query .= "post_author = '{$post_author}', ";
            $query .= "post_date = '{$post_date}', ";
            $query .= "post_image = '{$post_image_name}', ";
            $query .= "post_content = '{$post_content}', ";
            $query .= "post_tags = '{$post_tags}', ";
            $query .= "post_status = '{$post_status}' ";
            $query .= "WHERE post_id = {$post_id};";

            $updatePost = mysqli_query($connection, $query);
            validateQuery($updatePost);

            header("Location: admin_posts.php?source=info&operation=update");
        }
    }

    return $err_status;
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

    if (isset($_GET['delete_comment_id'])) {
        $delete_comment_id = $_GET['delete_comment_id'];

        $query = "SELECT * FROM comments WHERE comment_id = {$delete_comment_id};";
        $statusDelComment = mysqli_query($connection, $query);
        validateQuery($statusDelComment);
        $delete_comment_post_id = 0;
        if ($row = mysqli_fetch_assoc($statusDelComment)) {
            $delete_comment_post_id = $row['comment_post_id'];
        }

        $query = "DELETE FROM comments WHERE comment_id = $delete_comment_id;";
        $deleteComment = mysqli_query($connection, $query);
        validateQuery($deleteComment);

        commentsCountByPost($delete_comment_post_id);

        header("Location: admin_comments.php?source=info&operation=delete");
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
            if ($row = mysqli_fetch_assoc($statusConfirmComment)) {
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
    if ($row = mysqli_fetch_assoc($commentsCount)) {
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
function addUsers() {
    global $connection;

    $err_add_user = ['login_empty'=>false, 'login_exists'=>false, 'password'=>false, 'firstname'=>false, 'lastname'=>false, 'email'=>false, 'image'=>false, 'privilege'=>false];

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

        foreach($err_add_user as $err_item) {
            $err_item = false;
        }
        $err_add_user['login_exists'] = ifLoginExists($user_login);
        if ($user_login == "" || empty($user_login)) {
            $err_add_user['login_empty'] = true;
        }
        if ($user_password == "" || empty($user_password)) {
            $err_add_user['password'] = true;
        }
        if ($user_firstname == "" || empty($user_firstname)) {
            $err_add_user['firstname'] = true;
        }
        if ($user_lastname == "" || empty($user_lastname)) {
            $err_add_user['lastname'] = true;
        }
        if ($user_email == "" || empty($user_email)) {
            $err_status['email'] = true;
        }
        if ($user_image_name == "" || $user_image_error == UPLOAD_ERR_NO_FILE) {
            $err_add_user['image'] = true;
        }
        if ($user_privilege == "" || empty($user_privilege)) {
            $err_add_user['privilege'] = true;
        }
        $err_result = false;
        foreach($err_add_user as $err_item) {
            $err_result = $err_result || $err_item;
        }

        if (!$err_result) {
            move_uploaded_file($user_image_tmp, "../img/{$user_image_name}");

            $query = "INSERT INTO users(user_login, user_password, user_firstname, user_lastname, user_email, user_image, user_privilege) VALUES('{$user_login}', '{$user_password}', '{$user_firstname}', '{$user_lastname}', '{$user_email}', '{$user_image_name}', '{$user_privilege}');";
            $addUser = mysqli_query($connection, $query);
            validateQuery($addUser);

            header("Location: admin_users.php?source=info&operation=add");
        }
    }

    include "includes/add_users.php";
}

/* Delete user from database */
function deleteUsers() {
    global $connection;

    if (isset($_GET['delete_user_id'])) {
        $delete_user_id = $_GET['delete_user_id'];

        $query = "DELETE FROM users WHERE user_id = {$delete_user_id};";
        $delUser = mysqli_query($connection, $query);
        validateQuery($delUser);

        header("Location: admin_users.php?source=info&operation=delete");
    }
}

/* Display Edit User form and put values from database to the fields of the form */
function editUsers() {
    global $connection;

    if (isset($_GET['edit_user_id'])) {
        $edit_user_id = $_GET['edit_user_id'];

        $query = "SELECT * FROM users WHERE user_id = {$edit_user_id};";
        $editUser = mysqli_query($connection, $query);
        validateQuery($editUser);
        if ($row = mysqli_fetch_assoc($editUser)) {
            $user_id = $row['user_id'];
            $user_login = $row['user_login'];
            $user_password = $row['user_password'];
            $user_firstname = $row['user_firstname'];
            $user_lastname = $row['user_lastname'];
            $user_email = $row['user_email'];
            $user_image_name = $row['user_image'];
            $user_privilege = $row['user_privilege'];

            $err_edit_user = ['password'=>false, 'firstname'=>false, 'lastname'=>false, 'email'=>false, 'image'=>false, 'privilege'=>false];

            $err_edit_user = updateUsers($user_id, $err_edit_user);
            include "includes/edit_users.php";
        }
    }
}

/* Update User Info in database */
function updateUsers($user_id, $err_status) {
    global $connection;

    if (isset($_POST['update_user_btn'])) {
        $user_password = $_POST['edit_user_password'];
        $user_firstname = $_POST['edit_user_firstname'];
        $user_lastname = $_POST['edit_user_lastname'];
        $user_email = $_POST['edit_user_email'];
        $user_privilege = $_POST['edit_user_privilege'];
        
        $current_user_image = $_POST['current_user_image'];
        $user_image_name = $_FILES['edit_user_image']['name'];
        $user_image_tmp = $_FILES['edit_user_image']['tmp_name'];
        $user_image_error = $_FILES['edit_user_image']['error'];

        $is_new_image = true;
        if ($user_image_name == "" || $user_image_error == UPLOAD_ERR_NO_FILE) {
            $user_image_name = $current_user_image;
            $is_new_image = false;
        }

        foreach($err_status as $err_item) {
            $err_item = false;
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
        if ($user_image_name == "" || empty($user_image_name)) {
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
            if ($is_new_image) {
                move_uploaded_file($user_image_tmp, "../img/{$user_image_name}");
            }

            $query = "UPDATE users SET ";
            $query .= "user_password = '{$user_password}', ";
            $query .= "user_firstname = '{$user_firstname}', ";
            $query .= "user_lastname = '{$user_lastname}', ";
            $query .= "user_email = '{$user_email}', ";
            $query .= "user_image = '{$user_image_name}', ";
            $query .= "user_privilege = '{$user_privilege}' ";
            $query .= "WHERE user_id = {$user_id};";

            $updateUser = mysqli_query($connection, $query);
            validateQuery($updateUser);

            header("Location: admin_users.php?source=info&operation=update");
        }

    }
    return $err_status;
}

/* Get information about authorized user from database. Put login as a parameter and return an array with login, firstname, lastname and privilege of user */
function getSessionInfo($user_login) {
    global $connection;

    $query = "SELECT * FROM users WHERE user_login = '{$user_login}';";
    $sessionInfo = mysqli_query($connection, $query);
    validateQuery($sessionInfo);
    $session_user = ['login'=>$user_login, 'firstname'=>"", 'lastname'=>"", 'privilege'=>""];
    if ($row = mysqli_fetch_assoc($sessionInfo)) {
        $session_user['login'] = $row['user_login'];
        $session_user['firstname'] = $row['user_firstname'];
        $session_user['lastname'] = $row['user_lastname'];
        $session_user['privilege'] = $row['user_privilege'];
    }

    return $session_user;
}

/* Display Edit Form for authorized user with login $edit_user_login. Put data for this user from the database */
function editProfile($edit_user_login) {
    global $connection;

    $query = "SELECT * FROM users WHERE user_login = '{$edit_user_login}';";
    $editProfile = mysqli_query($connection, $query);
    validateQuery($editProfile);
    if ($row = mysqli_fetch_assoc($editProfile)) {
        $user_login = $row['user_login'];
        $user_password = $row['user_password'];
        $user_firstname = $row['user_firstname'];
        $user_lastname = $row['user_lastname'];
        $user_email = $row['user_email'];
        $user_image_name = $row['user_image'];
        $user_privilege = $row['user_privilege'];

        $err_edit_profile = ['password'=>false, 'firstname'=>false, 'lastname'=>false, 'email'=>false, 'image'=>false, 'privilege'=>false];

        $err_edit_profile = updateProfile($user_login, $err_edit_profile);
        include "includes/edit_profile.php";
    }
}

/* Put User Data with login $user_login from the form to database. Get as parameter and return an array $err_status which contains Error Status for all fields in the form */
function updateProfile($user_login, $err_status) {
    global $connection;

    if (isset($_POST['update_profile_btn'])) {
        $user_password = $_POST['profile_password'];
        $user_firstname = $_POST['profile_firstname'];
        $user_lastname = $_POST['profile_lastname'];
        $user_email = $_POST['profile_email'];
        $user_privilege = $_POST['profile_privilege'];
        
        $current_user_image = $_POST['current_profile_image'];
        $user_image_name = $_FILES['profile_image']['name'];
        $user_image_tmp = $_FILES['profile_image']['tmp_name'];
        $user_image_error = $_FILES['profile_image']['error'];

        $is_new_image = true;
        if ($user_image_name == "" || $user_image_error == UPLOAD_ERR_NO_FILE) {
            $user_image_name = $current_user_image;
            $is_new_image = false;
        }

        foreach($err_status as $err_item) {
            $err_item = false;
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
        if ($user_image_name == "" || empty($user_image_name)) {
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
            if ($is_new_image) {
                move_uploaded_file($user_image_tmp, "../img/{$user_image_name}");
            }

            $query = "UPDATE users SET ";
            $query .= "user_password = '{$user_password}', ";
            $query .= "user_firstname = '{$user_firstname}', ";
            $query .= "user_lastname = '{$user_lastname}', ";
            $query .= "user_email = '{$user_email}', ";
            $query .= "user_image = '{$user_image_name}', ";
            $query .= "user_privilege = '{$user_privilege}' ";
            $query .= "WHERE user_login = '{$user_login}';";

            $updateUser = mysqli_query($connection, $query);
            validateQuery($updateUser);

            header("Location: admin_profile.php?source=info&operation=update");
        }

    }
    return $err_status;
}

/* Check if login is already exists. Return true if login exists and return false if login doesn't exist */
function ifLoginExists($login) {
    global $connection;

    $query = "SELECT * FROM users WHERE user_login = '{$login}';";
    $loginExists = mysqli_query($connection, $query);
    validateQuery($loginExists);
    $num_rows = mysqli_num_rows($loginExists);
    if ($num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

/* Display number of published posts on the Statistics Page in admin. $widget_color is color of widget, $link_name is link to the Posts Page */
function showPostsStatisticsWidget($widget_color, $link_name) {
    global $connection;

    $query = "SELECT * FROM posts WHERE post_status = 'опубликовано';";
    $postsStatistics = mysqli_query($connection, $query);
    validateQuery($postsStatistics);

    $data_num = mysqli_num_rows($postsStatistics);
    $data_str = "";
    switch(true) {
        case $data_num % 100 >= 11 && $data_num % 100 <= 19:
            $data_str = "публикаций";
            break;
        case $data_num % 10 === 1:
            $data_str = "публикация";
            break;
        case $data_num % 10 >= 2 && $data_num % 10 <= 4:
            $data_str = "публикации";
            break;
        default:
            $data_str = "публикаций";
            break;
    }

    $widget_icon = "fa-file-text";

    include "includes/statistics_widget_form.php";
}

/* Display number of approved comments on the Statistics Page in admin. $widget_color is color of widget, $link_name is link to the Comments Page */
function showCommentsStatisticsWidget($widget_color, $link_name) {
    global $connection;

    $query = "SELECT * FROM comments WHERE comment_status = 'одобрен';";
    $commentsStatistics = mysqli_query($connection, $query);
    validateQuery($commentsStatistics);

    $data_num = mysqli_num_rows($commentsStatistics);
    $data_str = "";
    switch(true) {
        case $data_num % 100 >= 11 && $data_num % 100 <= 19:
            $data_str = "комментариев";
            break;
        case $data_num % 10 === 1:
            $data_str = "комментарий";
            break;
        case $data_num % 10 >= 2 && $data_num % 10 <= 4:
            $data_str = "комментария";
            break;
        default:
            $data_str = "комментариев";
            break;
    }

    $widget_icon = "fa-comments";

    include "includes/statistics_widget_form.php";
}

/* Display number of categories on the Statistics Page in admin. $widget_color is color of widget, $link_name is link to the Categories Page */
function showCategoriesStatisticsWidget($widget_color, $link_name) {
    global $connection;

    $query = "SELECT * FROM categories;";
    $categoriesStatistics = mysqli_query($connection, $query);
    validateQuery($categoriesStatistics);

    $data_num = mysqli_num_rows($categoriesStatistics);
    $data_str = "";
    switch(true) {
        case $data_num % 100 >= 11 && $data_num % 100 <= 19:
            $data_str = "регионов";
            break;
        case $data_num % 10 === 1:
            $data_str = "регион";
            break;
        case $data_num % 10 >= 2 && $data_num % 10 <= 4:
            $data_str = "региона";
            break;
        default:
            $data_str = "регионов";
            break;
    }

    $widget_icon = "fa-list";

    include "includes/statistics_widget_form.php";
}

/* Display number of users on the Statistics Page in admin. $widget_color is color of widget, $link_name is link to the Users Page */
function showUsersStatisticsWidget($widget_color, $link_name) {
    global $connection;

    $query = "SELECT * FROM users;";
    $usersStatistics = mysqli_query($connection, $query);
    validateQuery($usersStatistics);

    $data_num = mysqli_num_rows($usersStatistics);
    $data_str = "";
    switch(true) {
        case $data_num % 100 >= 11 && $data_num % 100 <= 19:
            $data_str = "пользователей";
            break;
        case $data_num % 10 === 1:
            $data_str = "пользователь";
            break;
        case $data_num % 10 >= 2 && $data_num % 10 <= 4:
            $data_str = "пользователя";
            break;
        default:
            $data_str = "пользователей";
            break;
    }

    $widget_icon = "fa-user";

    include "includes/statistics_widget_form.php";
}

/* Get the number of published posts for all categories from database and put this data to the chart. Categories are sorted by number of posts from top to down. $chart_color is color of the chart, $categories_num is number of categories that is displayed on the chart */
function showPostsByCategoryChart($chart_color, $categories_num) {
    global $connection;

    $query = "SELECT cat_title, CASE WHEN posts_cnt IS NULL THEN 0 ELSE posts_cnt END AS posts_cnt "; 
    $query .= "FROM categories AS cat LEFT JOIN ";
    $query .= "(SELECT post_category_id, COUNT(*) AS posts_cnt FROM posts WHERE post_status = 'опубликовано' GROUP BY post_category_id) AS p_cnt ";
    $query .= "ON cat.cat_id = p_cnt.post_category_id ORDER BY posts_cnt DESC LIMIT {$categories_num};";

    $postsByCategoryChart = mysqli_query($connection, $query);
    validateQuery($postsByCategoryChart);

    $names_str = "";
    $values_str = "";
    while($row = mysqli_fetch_assoc($postsByCategoryChart)) {
        $cat_title = $row['cat_title'];
        $posts_cnt = $row['posts_cnt'];
        /* '#' is divider between elements of array */
        if ($names_str != "") {
            $names_str .= "#";
        }
        if ($values_str != "") {
            $values_str .= "#";
        }
        $names_str .= $cat_title;
        $values_str .= $posts_cnt;
    }

    $axis_x_title = "";
    $axis_y_title = "количество\nпубликаций";
    $chart_title = "Популярные регионы";

    include "includes/statistics_chart_form.php";
}

/* Get the number of approved comments for all posts from database and put this data to the chart. Posts are sorted by number of comments from top to down. $chart_color is color of the chart, $posts_num is number of posts that is displayed on the chart */
function showCommentsByPostChart($chart_color, $posts_num) {
    global $connection;

    $query = "SELECT * FROM posts WHERE post_status = 'опубликовано' ORDER BY post_comments_count DESC LIMIT {$posts_num};";

    $commentsByPostChart = mysqli_query($connection, $query);
    validateQuery($commentsByPostChart);

    $names_str = "";
    $values_str = "";
    while($row = mysqli_fetch_assoc($commentsByPostChart)) {
        $cat_title = $row['post_title'];
        $posts_cnt = $row['post_comments_count'];
        if ($names_str != "") {
            $names_str .= "#";
        }
        if ($values_str != "") {
            $values_str .= "#";
        }
        $names_str .= $cat_title;
        $values_str .= $posts_cnt;
    }

    $axis_x_title = "";
    $axis_y_title = "количество\nкомментариев";
    $chart_title = "Самые читаемые публикации";

    include "includes/statistics_chart_form.php";
}

/* Show info message if post operation (add, delete or edit) was successful */
function showPostOperationInfo() {
    if (isset($_GET['operation'])) {
        $post_operation = $_GET['operation'];

        $post_operation_message = "";
        switch($post_operation) {
            case "add":
                $post_operation_message = "Ваша публикация успешно добавлена";
                break;
            case "delete":
                $post_operation_message = "Публикация удалена";
                break;
            case "update":
                $post_operation_message = "Изменения успешно сохранены";
                break;
            default:
                $post_operation_message = "Произошла непредвиденная ошибка. Попробуйте снова";
                break;
        }

        include "includes/post_operation_info.php";
    }
}

/* Show info message if comment operation (add, delete or edit) was successful */
function showCommentOperationInfo() {
    if (isset($_GET['operation'])) {
        $comment_operation = $_GET['operation'];

        $comment_operation_message = "";
        switch($comment_operation) {
            case "add":
                $comment_operation_message = "Ваш комментарий успешно добавлен";
                break;
            case "delete":
                $comment_operation_message = "Комментарий удален";
                break;
            case "update":
                $comment_operation_message = "Изменения успешно сохранены";
                break;
            default:
                $comment_operation_message = "Произошла непредвиденная ошибка. Попробуйте снова";
                break;
        }

        include "includes/comment_operation_info.php";
    }
}

/* Show info message if category operation (add, delete or edit) was successful */
function showCategoryOperationInfo() {
    if (isset($_GET['operation'])) {
        $category_operation = $_GET['operation'];

        $category_operation_message = "";
        switch($category_operation) {
            case "add":
                $category_operation_message = "Регион успешно добавлен";
                break;
            case "delete":
                $category_operation_message = "Регион удален";
                break;
            case "update":
                $category_operation_message = "Изменения успешно сохранены";
                break;
            default:
                $category_operation_message = "Произошла непредвиденная ошибка. Попробуйте снова";
                break;
        }

        include "includes/category_operation_info.php";
    }
}

/* Show info message if user operation (add, delete or edit) was successful */
function showUserOperationInfo() {
    if (isset($_GET['operation'])) {
        $user_operation = $_GET['operation'];

        $user_operation_message = "";
        switch($user_operation) {
            case "add":
                $user_operation_message = "Пользователь успешно добавлен";
                break;
            case "delete":
                $user_operation_message = "Пользователь удален";
                break;
            case "update":
                $user_operation_message = "Изменения успешно сохранены";
                break;
            default:
                $user_operation_message = "Произошла непредвиденная ошибка. Попробуйте снова";
                break;
        }

        include "includes/user_operation_info.php";
    }
}

/* Show info message if profile operation (add, delete or edit) was successful */
function showProfileOperationInfo() {
    if (isset($_GET['operation'])) {
        $profile_operation = $_GET['operation'];

        $profile_operation_message = "";
        switch($profile_operation) {
            case "update":
                $profile_operation_message = "Изменения успешно сохранены";
                break;
            default:
                $profile_operation_message = "Произошла непредвиденная ошибка. Попробуйте снова";
                break;
        }

        include "includes/profile_operation_info.php";
    }
}
?>