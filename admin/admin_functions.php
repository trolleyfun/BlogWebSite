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

    $err_add_cat = ['title_empty'=>false, 'title_exists'=>false];

    if (isset($_POST['add_cat_btn'])) {
        $cat_title = $_POST['cat_title'];
        foreach($err_add_cat as $err_item) {
            $err_item = false;
        }
        if (empty($cat_title)) {
            $err_add_cat['title_empty'] = true;
        } else {
            $err_add_cat['title_exists'] = ifCategoryTitleExists($cat_title, null);
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
        if (empty($cat_title)) {
            $err_status['title_empty'] = true;
        } else {
            $err_status['title_exists'] = ifCategoryTitleExists($cat_title, $cat_id);
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

            $err_edit_cat = ['title_empty'=>false, 'title_exists'=>false];

            $err_edit_cat = updateCategories($cat_id, $err_edit_cat);
            include "includes/edit_categories.php";
        }
    }
}

/* Read all categories from database and display as table */
function showAllCategoriesInTable($rows_per_page) {
    global $connection;

    $query = "SELECT * FROM categories ORDER BY cat_posts_count DESC, cat_title;";
    $adminCategories = mysqli_query($connection,$query);
    validateQuery($adminCategories);
    $num_rows = mysqli_num_rows($adminCategories);

    $pager['pages_cnt'] = ceil($num_rows / $rows_per_page);
    if ($pager['pages_cnt'] == 0) {
        $pager['pages_cnt'] = 1;
    }

    $pager['page_num'] = 1;
    if (isset($_GET['page'])) {
        $pager['page_num'] = $_GET['page'];
    }
    if ($pager['page_num'] < 1 || $pager['page_num'] > $pager['pages_cnt']) {
        $pager['page_num'] = 1;
    }

    $previous_page_num = $pager['page_num'] - 1;
    if ($previous_page_num < 1) {
        $previous_page_num = 1;
    }
    $pager['previous_page_link'] = "admin_categories.php?page={$previous_page_num}";

    $next_page_num = $pager['page_num'] + 1;
    if ($next_page_num > $pager['pages_cnt']) {
        $next_page_num = $pager['pages_cnt'];
    }
    $pager['next_page_link'] = "admin_categories.php?page={$next_page_num}";

    $post_offset = $rows_per_page * ($pager['page_num'] - 1);
    if ($post_offset < 0 || $post_offset >= $num_rows) {
        $post_offset = 0;
    }

    for ($i = 1; $row = mysqli_fetch_assoc($adminCategories); $i++) {
        if ($i > $post_offset && $i <= $post_offset + $rows_per_page) {
            $cat_id = $row['cat_id'];
            $cat_title = $row['cat_title'];
            $cat_posts_count = $row['cat_posts_count'];

            include "includes/all_categories_table.php";
        }
    }

    return $pager;
}

/* Read all categories from database and display as list. $post_category_id is selected item of the list */
function showAllCategoriesInList($post_category_id) {
    global $connection;

    $query = "SELECT * FROM categories ORDER BY cat_title;";
    $adminCategories = mysqli_query($connection,$query);
    validateQuery($adminCategories);
    
    while($row = mysqli_fetch_assoc($adminCategories)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];
        $cat_posts_count = $row['cat_posts_count'];

        include "includes/all_categories_list.php";
    }
}

/* Delete category if Delete Icon is clicked */
function clickDeleteCategoryIcon() {
    if (isset($_GET['delete_cat_id'])) {
        $delete_cat_id = $_GET['delete_cat_id'];
        
        deleteCategories($delete_cat_id);

        header("Location: admin_categories.php?source=info&operation=delete");
    }
}

/* Delete category in database */
function deleteCategories($delete_cat_id) {
    global $connection;

    $query = "DELETE FROM categories WHERE cat_id = {$delete_cat_id};";

    $deleteCategory = mysqli_query($connection, $query);
    validateQuery($deleteCategory);

    header("Location: admin_categories.php?source=info&operation=delete");
}

/* Read all posts from database and display them in Posts Section in admin */
function showAllPosts($rows_per_page) {
    global $connection;

    $query = "SELECT * FROM (posts AS p LEFT JOIN categories AS cat ON p.post_category_id = cat.cat_id) LEFT JOIN ";
    $query .= "users AS u ON p.post_author_id = u.user_id ORDER BY post_date DESC, post_id DESC;";
    $allPosts = mysqli_query($connection, $query);
    validateQuery($allPosts);
    $num_rows = mysqli_num_rows($allPosts);

    $pager['pages_cnt'] = ceil($num_rows / $rows_per_page);
    if ($pager['pages_cnt'] == 0) {
        $pager['pages_cnt'] = 1;
    }

    $pager['page_num'] = 1;
    if (isset($_GET['page'])) {
        $pager['page_num'] = $_GET['page'];
    }
    if ($pager['page_num'] < 1 || $pager['page_num'] > $pager['pages_cnt']) {
        $pager['page_num'] = 1;
    }

    $previous_page_num = $pager['page_num'] - 1;
    if ($previous_page_num < 1) {
        $previous_page_num = 1;
    }
    $pager['previous_page_link'] = "admin_posts.php?page={$previous_page_num}";

    $next_page_num = $pager['page_num'] + 1;
    if ($next_page_num > $pager['pages_cnt']) {
        $next_page_num = $pager['pages_cnt'];
    }
    $pager['next_page_link'] = "admin_posts.php?page={$next_page_num}";

    $post_offset = $rows_per_page * ($pager['page_num'] - 1);
    if ($post_offset < 0 || $post_offset >= $num_rows) {
        $post_offset = 0;
    }

    for ($i = 1; $row = mysqli_fetch_assoc($allPosts); $i++) {
        if ($i > $post_offset && $i <= $post_offset + $rows_per_page) {
            $post_id = $row['post_id'];
            $post_category_id = $row['cat_id'];
            $post_category_title = $row['cat_title'];
            $post_title = $row['post_title'];
            $post_author_id = $row['user_id'];
            $post_author_login = $row['user_login'];
            $post_date = $row['post_date'];
            $post_image = $row['post_image'];
            $post_tags = $row['post_tags'];
            $post_comments_count = $row['post_comments_count'];
            $post_status = $row['post_status'];

            include "includes/all_posts_table.php";
        }
    }

    return $pager;
}

/* Put Post from Add Post Form to database */
function addPosts() {
    global $connection;

    $err_add_post = ['category_id_empty'=>false, 'category_id_exists'=>false, 'title'=>false, 'author'=>false, 'image'=>false, 'content'=>false];

    if (isset($_POST['add_post_btn'])) {
        if (isset($_SESSION['user_id'])) {
            $post_author_id = $_SESSION['user_id'];
            $post_category_id = $_POST['post_category_id'];
            $post_title = $_POST['post_title'];
            $post_date = date('Y-m-d');

            $is_new_post_image = true;
            $default_post_image_name = "post_image_default.png";
            $post_image_name = $_FILES['post_image']['name'];
            $post_image_temp = $_FILES['post_image']['tmp_name'];
            $post_image_err = $_FILES['post_image']['error'];
            if ($post_image_name == "" || $post_image_err == UPLOAD_ERR_NO_FILE) {
                $post_image_name = $default_post_image_name;
                $is_new_post_image = false;
            }

            $post_content = $_POST['post_content'];
            $post_tags = $_POST['post_tags'];

            foreach($err_add_post as $err_item) {
                $err_item = false;
            }
            if (empty($post_category_id)) {
                $err_add_post['category_id_empty'] = true;
            } else {
                $err_add_post['category_id_exists'] = !postCategoryValidation($post_category_id);
            }
            if (empty($post_title)) {
                $err_add_post['title'] = true;
            }
            $err_add_post['author'] = !userValidation($post_author_id);
            if (empty($post_image_name)) {
                $err_add_post['image'] = true;
            }
            if (empty($post_content)) {
                $err_add_post['content'] = true;
            }
            $err_result = false;
            foreach($err_add_post as $err_item) {
                $err_result = $err_result || $err_item;
            }

            if (!$err_result) {
                if ($is_new_post_image) {
                    move_uploaded_file($post_image_temp, "../img/{$post_image_name}");
                }

                $query = "INSERT INTO posts(post_category_id, post_title, post_author_id, post_date, post_image, post_content, post_tags) ";
                $query .= "VALUES({$post_category_id}, '{$post_title}', {$post_author_id}, '{$post_date}', '{$post_image_name}', '{$post_content}', '{$post_tags}');";

                $addPost = mysqli_query($connection, $query);
                validateQuery($addPost);

                postsCountByCategory($post_category_id);
                postsCountByUser($post_author_id);

                header("Location: admin_posts.php?source=info&operation=add");
            }
        } else {
            header("Location: ../index.php");
        }
    }

    include "includes/add_posts.php";
}

/* Change Post Status with post_id = $post_id. If $confirm_option is "confirm" the change $post_status to "опубликовано", if $confirm_option is "block" then change $post_status to "заблокировано", for other values nothing occurs */
function confirmPosts($post_id, $confirm_option) {
    global $connection;
    
    $confirm_post_status = "";
    switch($confirm_option) {
        case "confirm":
            $confirm_post_status = "опубликовано";
            break;
        case "block":
            $confirm_post_status = "заблокировано";
            break;
    }

    if ($confirm_post_status != "") {
        $query = "SELECT * FROM posts WHERE post_id = {$post_id};";
        $postInfo = mysqli_query($connection, $query);
        validateQuery($postInfo);

        $confirm_post_category_id = 0;
        $confirm_post_author_id = 0;
        if ($row = mysqli_fetch_assoc($postInfo)) {
            $confirm_post_category_id = $row['post_category_id'];
            $confirm_post_author_id = $row['post_author_id'];
        }

        $query = "UPDATE posts SET post_status = '{$confirm_post_status}' WHERE post_id = {$post_id};";
        $confirmPost = mysqli_query($connection, $query);
        validateQuery($confirmPost);

        postsCountByCategory($confirm_post_category_id);
        postsCountByUser($confirm_post_author_id);
    }
}

/* Delete selected post from the database if delete icon is clicked */
function clickDeletePostIcon() {
    if (isset($_GET['delete_post_id'])) {
        $delete_post_id = $_GET['delete_post_id'];

        deletePosts($delete_post_id);
    }
}

/* Delete selected post from the database */
function deletePosts($delete_post_id) {
    global $connection;

    $query = "SELECT * FROM posts WHERE post_id = {$delete_post_id};";
    $postInfo = mysqli_query($connection, $query);
    validateQuery($postInfo);

    $delete_post_category_id = 0;
    $delete_post_author_id = 0;
    if ($row = mysqli_fetch_assoc($postInfo)) {
        $delete_post_category_id = $row['post_category_id'];
        $delete_post_author_id = $row['post_author_id'];
    }

    $query = "DELETE FROM posts WHERE post_id={$delete_post_id};";
    $deletePost = mysqli_query($connection, $query);
    validateQuery($deletePost);

    postsCountByCategory($delete_post_category_id);
    postsCountByUser($delete_post_author_id);

    header("Location: admin_posts.php?source=info&operation=delete");
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
            $post_author_id = $row['post_author_id'];
            $post_date = $row['post_date'];
            $post_image = $row['post_image'];
            $post_content = $row['post_content'];
            $post_tags = $row['post_tags'];
            $post_comments_count = $row['post_comments_count'];
            $post_status = $row['post_status'];

            $err_edit_post = ['category_id_empty'=>false, 'category_id_exists'=>false, 'title'=>false, 'date'=>false, 'image'=>false, 'content'=>false, 'status_empty'=>false, 'status_correct'=>false];

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
        if (empty($post_category_id)) {
            $err_status['category_id_empty'] = true;
        } else {
            $err_status['category_id_exists'] = !postCategoryValidation($post_category_id);
        }
        if (empty($post_title)) {
            $err_status['title'] = true;
        }
        if (empty($post_date)) {
            $err_status['date'] = true;
        }
        if (empty($post_image_name)) {
            $err_status['image'] = true;
        }
        if (empty($post_content)) {
            $err_status['content'] = true;
        }
        if (empty($post_status)) {
            $err_status['status_empty'] = true;
        } else {
            $err_status['status_correct'] = !postStatusValidation($post_status);
        }
        $err_result = false;
        foreach($err_status as $err_item) {
            $err_result = $err_result || $err_item;
        }

        if (!$err_result) {
            if ($is_new_post_image) {
                move_uploaded_file($post_image_temp, "../img/{$post_image_name}");
            }

            $query = "SELECT * FROM posts WHERE post_id = {$post_id};";
            $postInfo = mysqli_query($connection, $query);
            validateQuery($postInfo);

            $current_post_category_id = 0;
            $post_author_id = 0;
            if ($row = mysqli_fetch_assoc($postInfo)) {
                $current_post_category_id = $row['post_category_id'];
                $post_author_id = $row['post_author_id'];
            }

            $query = "UPDATE posts SET post_category_id = {$post_category_id}, ";
            $query .= "post_title = '{$post_title}', ";
            $query .= "post_date = '{$post_date}', ";
            $query .= "post_image = '{$post_image_name}', ";
            $query .= "post_content = '{$post_content}', ";
            $query .= "post_tags = '{$post_tags}', ";
            $query .= "post_status = '{$post_status}' ";
            $query .= "WHERE post_id = {$post_id};";

            $updatePost = mysqli_query($connection, $query);
            validateQuery($updatePost);

            postsCountByCategory($post_category_id);
            postsCountByUser($post_author_id);
            if ($post_category_id !== $current_post_category_id) {
                postsCountByCategory($current_post_category_id);
            }

            header("Location: admin_posts.php?source=info&operation=update");
        }
    }

    return $err_status;
}

/* Put all comments from database and display them in Comments Section in admin */
function showAllComments($rows_per_page) {
    global $connection;

    $query = "SELECT * FROM (comments AS c LEFT JOIN posts AS p ON c.comment_post_id = p.post_id) LEFT JOIN ";
    $query .= "users AS u ON c.comment_user_id = u.user_id ORDER BY comment_date DESC, comment_id DESC;";
    $allComments = mysqli_query($connection, $query);
    validateQuery($allComments);
    $num_rows = mysqli_num_rows($allComments);

    $pager['pages_cnt'] = ceil($num_rows / $rows_per_page);
    if ($pager['pages_cnt'] == 0) {
        $pager['pages_cnt'] = 1;
    }

    $pager['page_num'] = 1;
    if (isset($_GET['page'])) {
        $pager['page_num'] = $_GET['page'];
    }
    if ($pager['page_num'] < 1 || $pager['page_num'] > $pager['pages_cnt']) {
        $pager['page_num'] = 1;
    }

    $previous_page_num = $pager['page_num'] - 1;
    if ($previous_page_num < 1) {
        $previous_page_num = 1;
    }
    $pager['previous_page_link'] = "admin_comments.php?page={$previous_page_num}";

    $next_page_num = $pager['page_num'] + 1;
    if ($next_page_num > $pager['pages_cnt']) {
        $next_page_num = $pager['pages_cnt'];
    }
    $pager['next_page_link'] = "admin_comments.php?page={$next_page_num}";

    $post_offset = $rows_per_page * ($pager['page_num'] - 1);
    if ($post_offset < 0 || $post_offset >= $num_rows) {
        $post_offset = 0;
    }

    for ($i = 1; $row = mysqli_fetch_assoc($allComments); $i++) {
        if ($i > $post_offset && $i <= $post_offset + $rows_per_page) {
            $comment_id = $row['comment_id'];
            $comment_post_id = $row['post_id'];
            $comment_post_title = $row['post_title'];
            $comment_user_id = $row['user_id'];
            $comment_user_login = $row['user_login'];
            $comment_date = $row['comment_date'];
            $comment_content = $row['comment_content'];
            $comment_status = $row['comment_status'];

            include "includes/all_comments_table.php";
        }
    }

    return $pager;
}

/* Delete selected comment when Delete Comment icon is clicked */
function clickDeleteCommentIcon() {
    if (isset($_GET['delete_comment_id'])) {
        $comment_id = $_GET['delete_comment_id'];

        deleteComments($comment_id);
    }
}

/* Delete selected comment from the database */
function deleteComments($delete_comment_id) {
    global $connection;

    $query = "SELECT * FROM comments WHERE comment_id = {$delete_comment_id};";
    $statusDelComment = mysqli_query($connection, $query);
    validateQuery($statusDelComment);
    $delete_comment_post_id = 0;
    $delete_comment_user_id = 0;
    if ($row = mysqli_fetch_assoc($statusDelComment)) {
        $delete_comment_post_id = $row['comment_post_id'];
        $delete_comment_user_id = $row['comment_user_id'];
    }

    $query = "DELETE FROM comments WHERE comment_id = $delete_comment_id;";
    $deleteComment = mysqli_query($connection, $query);
    validateQuery($deleteComment);

    commentsCountByPost($delete_comment_post_id);
    commentsCountByUser($delete_comment_user_id);

    header("Location: admin_comments.php?source=info&operation=delete");
}

/* Change status of the comment if Confirm Comment icon is clicked */
function clickConfirmCommentIcon() {
    if (isset($_GET['confirm_comment'])) {
        $confirm_comment_operation = $_GET['confirm_comment'];

        if (isset($_GET['comment_id'])) {
            $confirm_comment_id = $_GET['comment_id'];

            confirmComments($confirm_comment_id, $confirm_comment_operation);
        }
    }
}

/* Change status of the comment: approved or unapproved */
function confirmComments($confirm_comment_id, $confirm_option) {
    global $connection;

    $confirm_comment_status = "";
    switch($confirm_option) {
        case "confirm":
            $confirm_comment_status = "одобрен";
            break;
        case "block":
            $confirm_comment_status = "заблокирован";
            break;
    }

    if ($confirm_comment_status != "") {
        $query = "SELECT * FROM comments WHERE comment_id = {$confirm_comment_id};";
        $statusConfirmComment = mysqli_query($connection, $query);
        validateQuery($statusConfirmComment);
        $confirm_comment_post_id = 0;
        $confirm_comment_user_id = 0;
        if ($row = mysqli_fetch_assoc($statusConfirmComment)) {
            $confirm_comment_post_id = $row['comment_post_id'];
            $confirm_comment_user_id = $row['comment_user_id'];
        }

        $query = "UPDATE comments SET comment_status = '{$confirm_comment_status}' WHERE comment_id = {$confirm_comment_id};";
        $confirmComment = mysqli_query($connection, $query);
        validateQuery($confirmComment);

        commentsCountByPost($confirm_comment_post_id);
        commentsCountByUser($confirm_comment_user_id);

        header("Location: admin_comments.php");
    }
}

/* Change Count of approved Comments in response to post wiyh $post_id in posts table in database. The difference between new value and previous one is $diff. If $diff is positive, then the count increases, if $diff is negative, then the count decreases. */
function changeCommentsCount($post_id, $diff) {
    global $connection;

    if (!is_null($post_id)) {
        $query = "UPDATE posts SET post_comments_count = post_comments_count + {$diff} WHERE post_id = {$post_id};";
        $commentsCount = mysqli_query($connection, $query);
        validateQuery($commentsCount);
    }
}

/* Update the Count of approved Comments in database */
function commentsCountByPost($post_id) {
    global $connection;

    if (!is_null($post_id)) {
        $query = "SELECT comment_post_id, COUNT(*) AS comments_count FROM comments ";
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
}

/* Update number of posts to selected category in database */
function postsCountByCategory($cat_id) {
    global $connection;

    if (!is_null($cat_id)) {
        $query = "SELECT post_category_id, COUNT(*) AS posts_count FROM posts WHERE post_status = 'опубликовано' ";
        $query .= "GROUP BY post_category_id HAVING post_category_id = {$cat_id};";
        $postsCount = mysqli_query($connection, $query);
        validateQuery($postsCount);
        $cat_posts_count = 0;
        if ($row = mysqli_fetch_assoc($postsCount)) {
            $cat_posts_count = $row['posts_count'];
        }

        $query = "UPDATE categories SET cat_posts_count = {$cat_posts_count} WHERE cat_id = {$cat_id};";
        $updatePostsCount = mysqli_query($connection, $query);
        validateQuery($updatePostsCount);
    }
}

/* Update number of comments by selected user in database */
function commentsCountByUser($user_id) {
    global $connection;

    if (!is_null($user_id)) {
        $query = "SELECT comment_user_id, COUNT(*) AS comments_count FROM comments WHERE comment_status = 'одобрен' ";
        $query .= "GROUP BY comment_user_id HAVING comment_user_id = {$user_id};";
        $commentsCount = mysqli_query($connection, $query);
        validateQuery($commentsCount);
        $user_comments_count = 0;
        if ($row = mysqli_fetch_assoc($commentsCount)) {
            $user_comments_count = $row['comments_count'];
        }

        $query = "UPDATE users SET user_comments_cnt = {$user_comments_count} WHERE user_id = {$user_id};";
        $updateCommentsCount = mysqli_query($connection, $query);
        validateQuery($updateCommentsCount);
    }
}

/* Update number of posts by selected user in database */
function postsCountByUser($user_id) {
    global $connection;

    if (!is_null($user_id)) {
        $query = "SELECT post_author_id, COUNT(*) AS posts_count FROM posts WHERE post_status = 'опубликовано' ";
        $query .= "GROUP BY post_author_id HAVING post_author_id = {$user_id};";
        $postsCount = mysqli_query($connection, $query);
        validateQuery($postsCount);
        $user_posts_count = 0;
        if ($row = mysqli_fetch_assoc($postsCount)) {
            $user_posts_count = $row['posts_count'];
        }

        $query = "UPDATE users SET user_posts_cnt = {$user_posts_count} WHERE user_id = {$user_id};";
        $updatePostsCount = mysqli_query($connection, $query);
        validateQuery($updatePostsCount);
    }
}

/* Display all users from database */
function showAllUsers($rows_per_page) {
    global $connection;

    $query = "SELECT * FROM users ORDER BY user_id DESC;";
    $allUsers = mysqli_query($connection, $query);
    validateQuery($allUsers);
    $num_rows = mysqli_num_rows($allUsers);

    $pager['pages_cnt'] = ceil($num_rows / $rows_per_page);
    if ($pager['pages_cnt'] == 0) {
        $pager['pages_cnt'] = 1;
    }

    $pager['page_num'] = 1;
    if (isset($_GET['page'])) {
        $pager['page_num'] = $_GET['page'];
    }
    if ($pager['page_num'] < 1 || $pager['page_num'] > $pager['pages_cnt']) {
        $pager['page_num'] = 1;
    }

    $previous_page_num = $pager['page_num'] - 1;
    if ($previous_page_num < 1) {
        $previous_page_num = 1;
    }
    $pager['previous_page_link'] = "admin_users.php?page={$previous_page_num}";

    $next_page_num = $pager['page_num'] + 1;
    if ($next_page_num > $pager['pages_cnt']) {
        $next_page_num = $pager['pages_cnt'];
    }
    $pager['next_page_link'] = "admin_users.php?page={$next_page_num}";

    $post_offset = $rows_per_page * ($pager['page_num'] - 1);
    if ($post_offset < 0 || $post_offset >= $num_rows) {
        $post_offset = 0;
    }

    for ($i = 1; $row = mysqli_fetch_assoc($allUsers); $i++) {
        if ($i > $post_offset && $i <= $post_offset + $rows_per_page) {
            $user_id = $row['user_id'];
            $user_login = $row['user_login'];
            $user_password = $row['user_password'];
            $user_firstname = $row['user_firstname'];
            $user_lastname = $row['user_lastname'];
            $user_email = $row['user_email'];
            $user_image = $row['user_image'];
            $user_privilege = $row['user_privilege'];
            $user_posts_count = $row['user_posts_cnt'];
            $user_comments_count = $row['user_comments_cnt'];

            include "includes/all_users_table.php";
        }
    }

    return $pager;
}

/* Add information about new user to database */
function addUsers() {
    global $connection;

    $err_add_user = ['login_empty'=>false, 'login_exists'=>false, 'password_empty'=>false, 'password_correct'=>false, 'firstname'=>false, 'lastname'=>false, 'email_empty'=>false, 'email_exists'=>false, 'email_correct'=>false, 'image'=>false, 'privilege_empty'=>false, 'privilege_correct'=>false];

    if (isset($_POST['add_user_btn'])) {
        $user_login = $_POST['user_login'];
        $user_password = $_POST['user_password'];
        $user_firstname = $_POST['user_firstname'];
        $user_lastname = $_POST['user_lastname'];
        $user_email = $_POST['user_email'];

        $is_new_user_image = true;
        $default_user_image_name = "user_icon_default.png";
        $user_image_name = $_FILES['user_image']['name'];
        $user_image_tmp = $_FILES['user_image']['tmp_name'];
        $user_image_error = $_FILES['user_image']['error'];
        if ($user_image_name == "" || $user_image_error == UPLOAD_ERR_NO_FILE) {
            $user_image_name = $default_user_image_name;
            $is_new_user_image = false;
        }

        $user_privilege = $_POST['user_privilege'];

        foreach($err_add_user as $err_item) {
            $err_item = false;
        }
        if (empty($user_login)) {
            $err_add_user['login_empty'] = true;
        } else {
            $err_add_user['login_exists'] = ifLoginExists($user_login, null);
        }
        if (empty($user_password)) {
            $err_add_user['password_empty'] = true;
        } else {
            $err_add_user['password_correct'] = !passwordValidation($user_password);
        }
        if (empty($user_firstname)) {
            $err_add_user['firstname'] = true;
        }
        if (empty($user_lastname)) {
            $err_add_user['lastname'] = true;
        }
        if (empty($user_email)) {
            $err_add_user['email_empty'] = true;
        } else {
            $err_add_user['email_correct'] = !emailValidation($user_email);
            $err_add_user['email_exists'] = ifEmailExists($user_email, null);
        }
        if (empty($user_image_name)) {
            $err_add_user['image'] = true;
        }
        if (empty($user_privilege)) {
            $err_add_user['privilege_empty'] = true;
        } else {
            $err_add_user['privilege_correct'] = !userPrivilegeValidation($user_privilege);
        }
        $err_result = false;
        foreach($err_add_user as $err_item) {
            $err_result = $err_result || $err_item;
        }

        if (!$err_result) {
            if ($is_new_user_image) {
                move_uploaded_file($user_image_tmp, "../img/{$user_image_name}");
            }

            $user_password = password_hash($user_password, PASSWORD_BCRYPT);

            $query = "INSERT INTO users(user_login, user_password, user_firstname, user_lastname, user_email, user_image, user_privilege) VALUES('{$user_login}', '{$user_password}', '{$user_firstname}', '{$user_lastname}', '{$user_email}', '{$user_image_name}', '{$user_privilege}');";
            $addUser = mysqli_query($connection, $query);
            validateQuery($addUser);

            header("Location: admin_users.php?source=info&operation=add");
        }
    }

    include "includes/add_users.php";
}

/* Delete user if Delete Icon is clicked */
function clickDeleteUserIcon() {
    if (isset($_GET['delete_user_id'])) {
        $delete_user_id = $_GET['delete_user_id'];

        deleteUsers($delete_user_id);
    }
}

/* Delete user from database */
function deleteUsers($delete_user_id) {
    global $connection;

    $query = "DELETE FROM users WHERE user_id = {$delete_user_id};";
    $delUser = mysqli_query($connection, $query);
    validateQuery($delUser);

    header("Location: admin_users.php?source=info&operation=delete");
}

/* Change privileges of user with $user_id. $privilege might take the following values: user, moderator, admin */
function changeUserPrivilege($user_id, $privilege) {
    global $connection;

    switch ($privilege) {
        case "user":
            $new_user_privilege = "пользователь";
            break;
        case "moderator":
            $new_user_privilege = "модератор";
            break;
        case "admin":
            $new_user_privilege = "администратор";
            break;
        default:
            $new_user_privilege = "";
            break;
    }

    if ($new_user_privilege != "") {
        $query = "UPDATE users SET user_privilege = '{$new_user_privilege}' WHERE user_id = {$user_id};";
        $changeUserPrivilege = mysqli_query($connection, $query);
        validateQuery($changeUserPrivilege);
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

            $err_edit_user = ['firstname'=>false, 'lastname'=>false, 'email_empty'=>false, 'email_exists'=>false, 'email_correct'=>false, 'image'=>false, 'privilege_empty'=>false, 'privilege_correct'=>false];
            $err_reset_password = ['password_empty'=>false, 'password_correct'=>false];

            $err_edit_user = updateUsers($user_id, $err_edit_user);
            $err_reset_password = resetUserPassword($user_id, $err_reset_password);
            include "includes/edit_users.php";
            include "includes/reset_password.php";
        }
    }
}

/* Update User Info in database */
function updateUsers($user_id, $err_status) {
    global $connection;

    if (isset($_POST['update_user_btn'])) {
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
        if (empty($user_firstname)) {
            $err_status['firstname'] = true;
        }
        if (empty($user_lastname)) {
            $err_status['lastname'] = true;
        }
        if (empty($user_email)) {
            $err_status['email_empty'] = true;
        } else {
            $err_status['email_correct'] = !emailValidation($user_email);
            $err_status['email_exists'] = ifEmailExists($user_email, $user_id);
        }
        if (empty($user_image_name)) {
            $err_status['image'] = true;
        }
        if (empty($user_privilege)) {
            $err_status['privilege_empty'] = true;
        } else {
            $err_status['privilege_correct'] = !userPrivilegeValidation($user_privilege);
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
        $user_id = $row['user_id'];
        $user_login = $row['user_login'];
        $user_password = $row['user_password'];
        $user_firstname = $row['user_firstname'];
        $user_lastname = $row['user_lastname'];
        $user_email = $row['user_email'];
        $user_image_name = $row['user_image'];
        $user_privilege = $row['user_privilege'];

        $err_edit_profile = ['firstname'=>false, 'lastname'=>false, 'email_empty'=>false, 'email_exists'=>false, 'email_correct'=>false, 'image'=>false, 'privilege_empty'=>false, 'privilege_correct'=>false];
        $err_change_password = ['current_password_empty'=>false, 'current_password_valid'=>false, 'new_password_empty'=>false, 'new_password_correct'=>false];

        $err_edit_profile = updateProfile($user_id, $err_edit_profile);
        $err_change_password = changeUserPassword($user_id, $user_password, $err_change_password);
        include "includes/edit_profile.php";
        include "includes/change_password.php";
    }
}

/* Put User Data with login $user_login from the form to database. Get as parameter and return an array $err_status which contains Error Status for all fields in the form */
function updateProfile($user_id, $err_status) {
    global $connection;

    if (isset($_POST['update_profile_btn'])) {
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
        if (empty($user_firstname)) {
            $err_status['firstname'] = true;
        }
        if (empty($user_lastname)) {
            $err_status['lastname'] = true;
        }
        if (empty($user_email)) {
            $err_status['email_empty'] = true;
        } else {
            $err_status['email_correct'] = !emailValidation($user_email);
            $err_status['email_exists'] = ifEmailExists($user_email, $user_id);
        }
        if (empty($user_image_name)) {
            $err_status['image'] = true;
        }
        if (empty($user_privilege)) {
            $err_status['privilege_empty'] = true;
        } else {
            $err_status['privilege_correct'] = !userPrivilegeValidation($user_privilege);
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
            $query .= "user_firstname = '{$user_firstname}', ";
            $query .= "user_lastname = '{$user_lastname}', ";
            $query .= "user_email = '{$user_email}', ";
            $query .= "user_image = '{$user_image_name}', ";
            $query .= "user_privilege = '{$user_privilege}' ";
            $query .= "WHERE user_id = {$user_id};";

            $updateUser = mysqli_query($connection, $query);
            validateQuery($updateUser);

            header("Location: admin_profile.php?source=info&operation=update");
        }

    }
    return $err_status;
}

/* Reset current user password and set new password */
function resetUserPassword($user_id, $err_status) {
    global $connection;

    if (isset($_POST['reset_password_btn'])) {
        $user_password = $_POST['reset_user_password'];

        foreach($err_status as $key=>$value) {
            $err_status[$key] = false;
        }
        if (empty($user_password)) {
            $err_status['password_empty'] = true;
        } else {
            $err_status['password_correct'] = !passwordValidation($user_password);
        }
        $err_result = false;
        foreach($err_status as $err_item) {
            $err_result = $err_result || $err_item;
        }

        if (!$err_result) {
            $user_password = password_hash($user_password, PASSWORD_BCRYPT);

            $query = "UPDATE users SET user_password = '{$user_password}' WHERE user_id = {$user_id};";
            $resetPassword = mysqli_query($connection, $query);
            validateQuery($resetPassword);

            header("Location: admin_users.php?source=info&operation=password");
        }
    }
    return $err_status;
}

/* If input password agrees with the current password from database ($db_user_password) then set new password */
function changeUserPassword($user_id, $db_user_password, $err_status) {
    global $connection;

    if (isset($_POST['change_password_btn'])) {
        $current_user_password = $_POST['current_user_password'];
        $new_user_password = $_POST['new_user_password'];

        foreach($err_status as $key=>$value) {
            $err_status[$key] = false;
        }
        if (empty($current_user_password)) {
            $err_status['current_password_empty'] = true;
        } else {
            $err_status['current_password_valid'] = !password_verify($current_user_password, $db_user_password);
        }
        if (empty($new_user_password)) {
            $err_status['new_password_empty'] = true;
        } else {
            $err_status['new_password_correct'] = !passwordValidation($new_user_password);
        }
        $err_result = false;
        foreach($err_status as $err_item) {
            $err_result = $err_result || $err_item;
        }

        if (!$err_result) {
            $new_user_password = password_hash($new_user_password, PASSWORD_BCRYPT);

            $query = "UPDATE users SET user_password = '{$new_user_password}' WHERE user_id = {$user_id};";
            $changePassword = mysqli_query($connection, $query);
            validateQuery($changePassword);

            header("Location: admin_profile.php?source=info&operation=password");
        }
    }
    return $err_status;
}

/* Check if login is already used by another user. $user_id is ID of user who wants to set login $login, put this parameter equal to null if that's a new user. Return true if login is used and return false if login isn't used */
function ifLoginExists($login, $user_id) {
    global $connection;

    if (is_null($user_id)) {
        $user_id_int = 0;
    } else {
        $user_id_int = $user_id;
    }
    $query = "SELECT * FROM users WHERE user_login = '{$login}' AND user_id != {$user_id_int};";
    $loginExists = mysqli_query($connection, $query);
    validateQuery($loginExists);
    $num_rows = mysqli_num_rows($loginExists);
    return $num_rows > 0;
}

/* Check if e-mail is already used by another user. $user_id is ID of user who wants to set e-mail $email, put this parameter equal to null if that's a new user. Return true if e-mail is used and return false if e-mail isn't used */
function ifEmailExists($email, $user_id) {
    global $connection;

    if (is_null($user_id)) {
        $user_id_int = 0;
    } else {
        $user_id_int = $user_id;
    }
    $query = "SELECT * FROM users WHERE user_email = '{$email}' AND user_id != {$user_id_int};";
    $emailExists = mysqli_query($connection, $query);
    validateQuery($emailExists);
    $num_rows = mysqli_num_rows($emailExists);
    return $num_rows > 0;
}
/* Check if category already exists. $cat_id is ID of category which is edited, put this parameter equal to null if that's a new category. Return true if category exists and return false if category doesn't exists */
function ifCategoryTitleExists($title, $cat_id) {
    global $connection;

    if (is_null($cat_id)) {
        $cat_id_int = 0;
    } else {
        $cat_id_int = $cat_id;
    }
    $query = "SELECT * FROM categories WHERE cat_title = '{$title}' AND cat_id != {$cat_id_int};";
    $categoryTitleExists = mysqli_query($connection, $query);
    validateQuery($categoryTitleExists);
    $num_rows = mysqli_num_rows($categoryTitleExists);
    return $num_rows > 0;
}

/* Check if e-mail is valid. Return true if e-mail is valid */
function emailValidation($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/* Check if password is reliable. The length should be 8 sybols or more */
function passwordValidation($password) {
    $min_length = 8;
    return strlen($password) >= $min_length;
}

/* Check if category of post exists in database. Return true if category exists */
function postCategoryValidation($post_category_id) {
    global $connection;

    if (!is_null($post_category_id)) {
    $query = "SELECT * FROM categories WHERE cat_id = {$post_category_id};";
    $postCategory = mysqli_query($connection, $query);
    validateQuery($postCategory);

    $num_rows = mysqli_num_rows($postCategory);

    return $num_rows > 0;
    } else {
        return false;
    }
}

/* Check if author of post exists in database. Return true if user exists */
function userValidation($user_id) {
    global $connection;

    if (!is_null($user_id)) {
    $query = "SELECT * FROM users WHERE user_id = {$user_id};";
    $userValidation = mysqli_query($connection, $query);
    validateQuery($userValidation);

    $num_rows = mysqli_num_rows($userValidation);

    return $num_rows > 0;
    } else {
        return false;
    }
}

/* Check if status of post is valid. Return true if status is valid */
function postStatusValidation($post_status) {
    $post_status_values = ['ожидает проверки', 'опубликовано', 'заблокировано'];

    return in_array($post_status, $post_status_values);
}

/* Check if status of comment is valid. Return true if status is valid */
function commentStatusValidation($comment_status) {
    $comment_status_values = ['одобрен', 'заблокирован'];

    return in_array($comment_status, $comment_status_values);
}

/* Check if privilege of user value is valid. Return true if privilege value is valid */
function userPrivilegeValidation($user_privilege) {
    $user_privilege_values = ['пользователь', 'модератор', 'администратор'];

    return in_array($user_privilege, $user_privilege_values);
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

    $query = "SELECT * FROM categories ORDER BY cat_posts_count DESC LIMIT {$categories_num};";

    $postsByCategoryChart = mysqli_query($connection, $query);
    validateQuery($postsByCategoryChart);

    $names_str = "";
    $values_str = "";
    while($row = mysqli_fetch_assoc($postsByCategoryChart)) {
        $cat_title = $row['cat_title'];
        $posts_cnt = $row['cat_posts_count'];
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
                $post_operation_message = "Ваша публикация успешно добавлена. Дождитесь проверки модератором";
                break;
            case "delete":
                $post_operation_message = "Выбранные публикации удалены";
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
                $comment_operation_message = "Ваш комментарий успешно отправлен. Дождитесь проверки модератором";
                break;
            case "delete":
                $comment_operation_message = "Выбранные комментарии удалены";
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
                $category_operation_message = "Выбранные регионы удалены";
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
                $user_operation_message = "Пользователь успешно создан";
                break;
            case "delete":
                $user_operation_message = "Выбранные пользователи удалены";
                break;
            case "update":
                $user_operation_message = "Изменения успешно сохранены";
                break;
            case "password":
                $user_operation_message = "Пароль успешно изменен";
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
            case "password":
                $profile_operation_message = "Пароль успешно изменен";
                break;
            default:
                $profile_operation_message = "Произошла непредвиденная ошибка. Попробуйте снова";
                break;
        }

        include "includes/profile_operation_info.php";
    }
}

/* Apply selected options on the Posts Page */
function selectPostOptions() {
    if (isset($_POST['apply_post_option_btn_top']) || isset($_POST['apply_post_option_btn_bottom'])) {
        if (isset($_POST['apply_post_option_btn_top'])) {
            $post_option = $_POST['post_option_top'];
        } else {
            $post_option = $_POST['post_option_bottom'];
        }

        if (isset($_POST['checkBoxArray'])) {
            $post_id_array = $_POST['checkBoxArray'];

            switch($post_option) {
                case "confirm":
                case "block":
                    foreach($post_id_array as $post_id_item) {
                        confirmPosts($post_id_item, $post_option);
                    }
                    break;
                case "delete":
                    foreach($post_id_array as $post_id_item) {
                        deletePosts($post_id_item);
                    }
                    break;
            }
        }
    }
}

/* Apply selected options on the Comments Page */
function selectCommentOptions() {
    if (isset($_POST['apply_comment_option_btn_top']) || isset($_POST['apply_comment_option_btn_bottom'])) {
        if (isset($_POST['apply_comment_option_btn_top'])) {
            $comment_option = $_POST['comment_option_top'];
        } else {
            $comment_option = $_POST['comment_option_bottom'];
        }

        if (isset($_POST['checkBoxArray'])) {
            $comment_id_array = $_POST['checkBoxArray'];

            switch($comment_option) {
                case "confirm":
                case "block":
                    foreach($comment_id_array as $comment_id_item) {
                        confirmComments($comment_id_item, $comment_option);
                    }
                    break;
                case "delete":
                    foreach($comment_id_array as $comment_id_item) {
                        deleteComments($comment_id_item);
                    }
                    break;
            }
        }
    }
}

/* Apply selected options on the Users Page */
function selectUserOptions() {
    if (isset($_POST['apply_user_option_btn_top']) || isset($_POST['apply_user_option_btn_bottom'])) {
        if (isset($_POST['apply_user_option_btn_top'])) {
            $user_option = $_POST['user_option_top'];
        } else {
            $user_option = $_POST['user_option_bottom'];
        }

        if (isset($_POST['checkBoxArray'])) {
            $user_id_array = $_POST['checkBoxArray'];

            switch($user_option) {
                case "moderator":
                case "admin":
                case "user":
                    foreach($user_id_array as $user_id_item) {
                        changeUserPrivilege($user_id_item, $user_option);
                    }
                    break;
                case "delete":
                    foreach($user_id_array as $user_id_item) {
                        deleteUsers($user_id_item);
                    }
                    break;
            }
        }
    }
}

/* Delete selected categories from database */
function deleteSelectedCategories() {
    if (isset($_POST['delete_categories_btn_top']) || isset($_POST['delete_categories_btn_bottom'])) {
        if (isset($_POST['checkBoxArray'])) {
            $category_id_array = $_POST['checkBoxArray'];

            foreach($category_id_array as $category_id_item) {
                deleteCategories($category_id_item);
            }
        }
    }
}

/* Display Pager on Posts Page in admin. $pages_count is number of pages, $current_page is number of current page */
function showPagesAdminPosts($pages_count, $current_page) {
    for($i = 1; $i <= $pages_count; $i++) {
        $page_link = "admin_posts.php?page={$i}";
        $page_num = $i;
        if ($page_num == $current_page) {
            $item_class = "active-page";
        } else {
            $item_class = "";
        }
        include "includes/admin_pager_item.php";
    }
}

/* Display Pager on Comments Page in admin. $pages_count is number of pages, $current_page is number of current page */
function showPagesAdminComments($pages_count, $current_page) {
    for($i = 1; $i <= $pages_count; $i++) {
        $page_link = "admin_comments.php?page={$i}";
        $page_num = $i;
        if ($page_num == $current_page) {
            $item_class = "active-page";
        } else {
            $item_class = "";
        }
        include "includes/admin_pager_item.php";
    }
}

/* Display Pager on Users Page in admin. $pages_count is number of pages, $current_page is number of current page */
function showPagesAdminUsers($pages_count, $current_page) {
    for($i = 1; $i <= $pages_count; $i++) {
        $page_link = "admin_users.php?page={$i}";
        $page_num = $i;
        if ($page_num == $current_page) {
            $item_class = "active-page";
        } else {
            $item_class = "";
        }
        include "includes/admin_pager_item.php";
    }
}

/* Display Pager on Categories Page in admin. $pages_count is number of pages, $current_page is number of current page */
function showPagesAdminCategories($pages_count, $current_page) {
    for($i = 1; $i <= $pages_count; $i++) {
        $page_link = "admin_categories.php?page={$i}";
        $page_num = $i;
        if ($page_num == $current_page) {
            $item_class = "active-page";
        } else {
            $item_class = "";
        }
        include "includes/admin_pager_item.php";
    }
}
?>