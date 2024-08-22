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

/* Escape special characters in array elements for use in sql queries */
function escapeArray($array) {
    global $connection;

    $escapedArray = $array;
    foreach($escapedArray as $key=>$value) {
        $escapedArray[$key] = mysqli_real_escape_string($connection, $value);
    }

    return $escapedArray;
}

/* Check if $value is unsigneg integer or unsigned integer string */
function my_is_int($value) {
    if(is_int($value)) {
        return $value >= 0;
    } else
        return is_numeric($value) && ctype_digit($value);
}

/* Add category from input field of Add Form to database */
function addCategories() {
    global $connection;

    $err_add_cat = ['title_empty'=>false, 'title_exists'=>false];

    if (isset($_POST['add_cat_btn'])) {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../index.php");
        } else {
            $session_user_id = $_SESSION['user_id'];
            if (!userIdValidation($session_user_id)) {
                header("Location: ../includes/logout.php");
            } else {
                $cat_title = $_POST['cat_title'];
                $cat_title = mysqli_real_escape_string($connection, $cat_title);

                foreach($err_add_cat as $key=>$value) {
                    $err_add_cat[$key] = false;
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
        }
    }

    include "includes/add_categories.php";
}

/* Update category in database from input field of Edit Form */
function updateCategories($cat_id, $err_status) {
    global $connection;

    if (isset($_POST['update_cat_btn'])) {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../index.php");
        } else {
            $session_user_id = $_SESSION['user_id'];
            if (!userIdValidation($session_user_id)) {
                header("Location: ../includes/logout.php");
            } else {
                $cat['cat_id'] = $cat_id;
                $cat['title'] = $_POST['edit_cat_title'];
                $cat = escapeArray($cat);

                if (!categoryIdValidation($cat['cat_id'])) {
                    header("Location: admin_categories.php?source=info&operation=error");
                } else {    
                    foreach($err_status as $key=>$value) {
                        $err_status[$key] = false;
                    }
                    if (empty($cat['title'])) {
                        $err_status['title_empty'] = true;
                    } else {
                        $err_status['title_exists'] = ifCategoryTitleExists($cat['title'], $cat['cat_id']);
                    }
                    $err_result = false;
                    foreach($err_status as $err_item) {
                        $err_result = $err_result || $err_item;
                    }
                    
                    if (!$err_result) {
                        $query = "UPDATE categories SET cat_title = '{$cat['title']}' WHERE cat_id = {$cat['cat_id']};";
                        $updateCategory = mysqli_query($connection, $query);
                        validateQuery($updateCategory);
                
                        header("Location: admin_categories.php?source=info&operation=update");
                    }
                }
            }
        }
    }

    return $err_status;
}

/* Create Edit Form for selected category and put id and title of the category from the database */
function editCategories() {
    global $connection;

    if (isset($_GET['edit_cat_id'])) {
        $edit_cat_id = $_GET['edit_cat_id'];
        $edit_cat_id = mysqli_real_escape_string($connection, $edit_cat_id);

        if (!categoryIdValidation($edit_cat_id)) {
            header("Location: admin_categories.php?source=info&operation=error");
        } else {
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
    }
}

/* Delete category in database */
function deleteCategories($delete_cat_id) {
    global $connection;

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php");
    } else {
        $session_user_id = $_SESSION['user_id'];
        if (!userIdValidation($session_user_id)) {
            header("Location: ../includes/logout.php");
        } else {
            $delete_cat_id_escaped = mysqli_real_escape_string($connection, $delete_cat_id);

            if (!categoryIdValidation($delete_cat_id_escaped)) {
                header("Location: admin_categories.php?source=info&operation=error");
            } else {
                $query = "DELETE FROM categories WHERE cat_id = {$delete_cat_id_escaped};";

                $deleteCategory = mysqli_query($connection, $query);
                validateQuery($deleteCategory);

                header("Location: admin_categories.php?source=info&operation=delete");
            }
        }
    }
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

    $err_add_post = ['category_id_empty'=>false, 'category_id_exists'=>false, 'title'=>false, 'image'=>false, 'content'=>false];

    if (isset($_POST['add_post_btn'])) {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../index.php");
        } else {
            $session_user_id = $_SESSION['user_id'];
            if (!userIdValidation($session_user_id)) {
                header("Location: ../includes/logout.php");
            } else {        
                $post['author_id'] = $session_user_id;
                $post['category_id'] = $_POST['post_category_id'];
                $post['title'] = $_POST['post_title'];
                $post['date'] = date('y-m-d');

                $is_new_post_image = true;
                $default_post_image_name = "post_image_default.png";
                $post['image_name'] = $_FILES['post_image']['name'];
                $post['image_tmp'] = $_FILES['post_image']['tmp_name'];
                $post['image_err'] = $_FILES['post_image']['error'];
                if ($post['image_name'] == "" || $post['image_tmp'] == "" || $post['image_err'] == UPLOAD_ERR_NO_FILE) {
                    $post['image_name'] = $default_post_image_name;
                    $is_new_post_image = false;
                }

                $post['content'] = $_POST['post_content'];
                $post['tags'] = $_POST['post_tags'];

                $post = escapeArray($post);

                foreach($err_add_post as $key=>$value) {
                    $err_add_post[$key] = false;
                }
                if (empty($post['category_id'])) {
                    $err_add_post['category_id_empty'] = true;
                } else {
                    $err_add_post['category_id_exists'] = !categoryIdValidation($post['category_id']);
                }
                if (empty($post['title'])) {
                    $err_add_post['title'] = true;
                }
                if (empty($post['image_name'])) {
                    $err_add_post['image'] = true;
                }
                if (empty($post['content'])) {
                    $err_add_post['content'] = true;
                }
                $err_result = false;
                foreach($err_add_post as $err_item) {
                    $err_result = $err_result || $err_item;
                }

                if (!$err_result) {
                    if ($is_new_post_image) {
                        if(!move_uploaded_file($post['image_tmp'], "../img/{$post['image_name']}")) {
                            $post['image_name'] = $default_post_image_name;
                        }
                    }

                    $query = "INSERT INTO posts(post_category_id, post_title, post_author_id, post_date, post_image, post_content, post_tags) ";
                    $query .= "VALUES({$post['category_id']}, '{$post['title']}', {$post['author_id']}, '{$post['date']}', '{$post['image_name']}', '{$post['content']}', '{$post['tags']}');";

                    $addPost = mysqli_query($connection, $query);
                    validateQuery($addPost);

                    postsCountByCategory($post['category_id']);
                    postsCountByUser($post['author_id']);

                    header("Location: admin_posts.php?source=info&operation=add");
                }
            }
        } 
    }

    include "includes/add_posts.php";
}

/* Change Post Status with post_id = $post_id. If $confirm_option is "confirm" the change $post_status to "опубликовано", if $confirm_option is "block" then change $post_status to "заблокировано", for other values nothing occurs */
function confirmPosts($post_id, $confirm_option) {
    global $connection;

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php");
    } else {
        $session_user_id = $_SESSION['user_id'];
        if (!userIdValidation($session_user_id)) {
            header("Location: ../includes/logout.php");
        } else {
            $confirm_post['post_id'] = $post_id;    
            $confirm_post['status'] = "";
            switch($confirm_option) {
                case "confirm":
                    $confirm_post['status'] = "опубликовано";
                    break;
                case "block":
                    $confirm_post['status'] = "заблокировано";
                    break;
            }
            $confirm_post = escapeArray($confirm_post);

            if (!postIdValidation($confirm_post['post_id'])) {
                header("Location: admin_posts.php?source=info&operation=error");
            } else {
                if (postStatusValidation($confirm_post['status'])) {
                    $query = "SELECT * FROM posts WHERE post_id = {$confirm_post['post_id']};";
                    $postInfo = mysqli_query($connection, $query);
                    validateQuery($postInfo);

                    $confirm_post_category_id = 0;
                    $confirm_post_author_id = 0;
                    if ($row = mysqli_fetch_assoc($postInfo)) {
                        $confirm_post_category_id = $row['post_category_id'];
                        $confirm_post_author_id = $row['post_author_id'];
                    }

                    $query = "UPDATE posts SET post_status = '{$confirm_post['status']}' WHERE post_id = {$confirm_post['post_id']};";
                    $confirmPost = mysqli_query($connection, $query);
                    validateQuery($confirmPost);

                    postsCountByCategory($confirm_post_category_id);
                    postsCountByUser($confirm_post_author_id);
                }
            }
        }
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

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php");
    } else {
        $session_user_id = $_SESSION['user_id'];
        if (!userIdValidation($session_user_id)) {
            header("Location: ../includes/logout.php");
        } else {
            $delete_post_id_escaped = mysqli_real_escape_string($connection, $delete_post_id);

            if (!postIdValidation($delete_post_id_escaped)) {
                header("Location: admin_posts.php?source=info&operation=error");
            } else {
                $query = "SELECT * FROM posts WHERE post_id = {$delete_post_id_escaped};";
                $postInfo = mysqli_query($connection, $query);
                validateQuery($postInfo);

                $delete_post_category_id = 0;
                $delete_post_author_id = 0;
                if ($row = mysqli_fetch_assoc($postInfo)) {
                    $delete_post_category_id = $row['post_category_id'];
                    $delete_post_author_id = $row['post_author_id'];
                }

                $query = "DELETE FROM posts WHERE post_id={$delete_post_id_escaped};";
                $deletePost = mysqli_query($connection, $query);
                validateQuery($deletePost);

                postsCountByCategory($delete_post_category_id);
                postsCountByUser($delete_post_author_id);

                header("Location: admin_posts.php?source=info&operation=delete");
            }
        }
    }
}

/* Create Edit Post Form and put selected post's values from database into the form */
function editPosts() {
    global $connection;
    
    if (!isset($_GET['edit_post_id'])) {
        header("Location: admin_posts.php");
    } else {
        $edit_post_id = $_GET['edit_post_id'];
        $edit_post_id = mysqli_real_escape_string($connection, $edit_post_id);

        if (!postIdValidation($edit_post_id)) {
            header("Location: admin_posts.php?source=info&operation=error");
        } else {
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

                $err_edit_post = ['category_id_empty'=>false, 'category_id_exists'=>false, 'title'=>false, 'date_empty'=>false, 'date_correct'=>false, 'image'=>false, 'content'=>false, 'status_empty'=>false, 'status_correct'=>false];

                $err_edit_post = updatePosts($edit_post_id, $post_image, $err_edit_post);

                include "includes/edit_posts.php";
            }
        }
    }
}

/* Put data from Edit Post Form to database */
function updatePosts($post_id, $current_image, $err_status) {
    global $connection;

    if (isset($_POST['update_post_btn'])) {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../index.php");
        } else {
            $session_user_id = $_SESSION['user_id'];
            if (!userIdValidation($session_user_id)) {
                header("Location: ../includes/logout.php");
            } else {
                $post['post_id'] = $post_id;
                $post['category_id'] = $_POST['edit_post_category_id'];
                $post['title'] = $_POST['edit_post_title'];
                $post['date'] = $_POST['edit_post_date'];

                $is_new_post_image = true;
                $default_post_image_name = "post_image_default.png";
                $post['image_name'] = $_FILES['edit_post_image']['name'];
                $post['image_tmp'] = $_FILES['edit_post_image']['tmp_name'];
                $post['image_err'] = $_FILES['edit_post_image']['error'];
                if ($post['image_name'] == "" || $post['image_tmp'] == "" || $post['image_err'] == UPLOAD_ERR_NO_FILE) {
                    $post['image_name'] = $current_image;
                    $is_new_post_image = false;
                }

                $post['content'] = $_POST['edit_post_content'];
                $post['tags'] = $_POST['edit_post_tags'];
                $post['status'] = $_POST['edit_post_status'];

                $post = escapeArray($post);

                if (!postIdValidation($post['post_id'])) {
                    header("Location: admin_posts.php?source=info&operation=error");
                } else {
                    foreach($err_status as $key=>$value) {
                        $err_status[$key] = false;
                    }
                    if (empty($post['category_id'])) {
                        $err_status['category_id_empty'] = true;
                    } else {
                        $err_status['category_id_exists'] = !categoryIdValidation($post['category_id']);
                    }
                    if (empty($post['title'])) {
                        $err_status['title'] = true;
                    }
                    if (empty($post['date'])) {
                        $err_status['date_empty'] = true;
                    } else {
                        $err_status['date_correct'] = !dateValidation($post['date']);
                    }
                    if (empty($post['image_name'])) {
                        $err_status['image'] = true;
                    }
                    if (empty($post['content'])) {
                        $err_status['content'] = true;
                    }
                    if (empty($post['status'])) {
                        $err_status['status_empty'] = true;
                    } else {
                        $err_status['status_correct'] = !postStatusValidation($post['status']);
                    }
                    $err_result = false;
                    foreach($err_status as $err_item) {
                        $err_result = $err_result || $err_item;
                    }

                    if (!$err_result) {
                        if ($is_new_post_image) {
                            if(!move_uploaded_file($post['image_tmp'], "../img/{$post['image_name']}")) {
                                $post['image_name'] = $default_post_image_name;
                            }
                        }

                        $query = "SELECT * FROM posts WHERE post_id = {$post['post_id']};";
                        $postInfo = mysqli_query($connection, $query);
                        validateQuery($postInfo);

                        $current_post_category_id = 0;
                        $post_author_id = 0;
                        if ($row = mysqli_fetch_assoc($postInfo)) {
                            $current_post_category_id = $row['post_category_id'];
                            $post_author_id = $row['post_author_id'];
                        }

                        $query = "UPDATE posts SET post_category_id = {$post['category_id']}, ";
                        $query .= "post_title = '{$post['title']}', ";
                        $query .= "post_date = '{$post['date']}', ";
                        $query .= "post_image = '{$post['image_name']}', ";
                        $query .= "post_content = '{$post['content']}', ";
                        $query .= "post_tags = '{$post['tags']}', ";
                        $query .= "post_status = '{$post['status']}' ";
                        $query .= "WHERE post_id = {$post['post_id']};";

                        $updatePost = mysqli_query($connection, $query);
                        validateQuery($updatePost);

                        postsCountByCategory($post['category_id']);
                        postsCountByUser($post_author_id);
                        if ($post['category_id'] !== $current_post_category_id) {
                            postsCountByCategory($current_post_category_id);
                        }

                        header("Location: admin_posts.php?source=info&operation=update");
                    }
                }
            }
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

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php");
    } else {
        $session_user_id = $_SESSION['user_id'];
        if (!userIdValidation($session_user_id)) {
            header("Location: ../includes/logout.php");
        } else {
            $delete_comment_id_escaped = mysqli_real_escape_string($connection, $delete_comment_id);

            if (!commentIdValidation($delete_comment_id_escaped)) {
                header("Location: admin_comments.php?source=info&operation=error");
            } else {
                $query = "SELECT * FROM comments WHERE comment_id = {$delete_comment_id_escaped};";
                $statusDelComment = mysqli_query($connection, $query);
                validateQuery($statusDelComment);
                $delete_comment_post_id = 0;
                $delete_comment_user_id = 0;
                if ($row = mysqli_fetch_assoc($statusDelComment)) {
                    $delete_comment_post_id = $row['comment_post_id'];
                    $delete_comment_user_id = $row['comment_user_id'];
                }

                $query = "DELETE FROM comments WHERE comment_id = $delete_comment_id_escaped;";
                $deleteComment = mysqli_query($connection, $query);
                validateQuery($deleteComment);

                commentsCountByPost($delete_comment_post_id);
                commentsCountByUser($delete_comment_user_id);

                header("Location: admin_comments.php?source=info&operation=delete");
            }
        }
    }
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
function confirmComments($comment_id, $confirm_option) {
    global $connection;

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php");
    } else {
        $session_user_id = $_SESSION['user_id'];
        if (!userIdValidation($session_user_id)) {
            header("Location: ../includes/logout.php");
        } else {
            $confirm_comment['comment_id'] = $comment_id;
            $confirm_comment['status'] = "";
            switch($confirm_option) {
                case "confirm":
                    $confirm_comment['status'] = "одобрен";
                    break;
                case "block":
                    $confirm_comment['status'] = "заблокирован";
                    break;
            }
            $confirm_comment = escapeArray($confirm_comment);

            if (!commentIdValidation($confirm_comment['comment_id'])) {
                header("Location: admin_comments.php?source=info&operation=error");
            } else {
                if (commentStatusValidation($confirm_comment['status'])) {
                    $query = "SELECT * FROM comments WHERE comment_id = {$confirm_comment['comment_id']};";
                    $statusConfirmComment = mysqli_query($connection, $query);
                    validateQuery($statusConfirmComment);
                    $confirm_comment_post_id = 0;
                    $confirm_comment_user_id = 0;
                    if ($row = mysqli_fetch_assoc($statusConfirmComment)) {
                        $confirm_comment_post_id = $row['comment_post_id'];
                        $confirm_comment_user_id = $row['comment_user_id'];
                    }

                    $query = "UPDATE comments SET comment_status = '{$confirm_comment['status']}' WHERE comment_id = {$confirm_comment['comment_id']};";
                    $confirmComment = mysqli_query($connection, $query);
                    validateQuery($confirmComment);

                    commentsCountByPost($confirm_comment_post_id);
                    commentsCountByUser($confirm_comment_user_id);

                    header("Location: admin_comments.php");
                }
            }
        }
    }
}

/* Change Count of approved Comments in response to post wiyh $post_id in posts table in database. The difference between new value and previous one is $diff. If $diff is positive, then the count increases, if $diff is negative, then the count decreases. */
function changeCommentsCount($post_id, $diff) {
    global $connection;

    $post_id_escaped = mysqli_real_escape_string($connection, $post_id);
    $diff_escaped = mysqli_real_escape_string($connection, $diff);

    if (postIdValidation($post_id_escaped) && is_int($diff_escaped)) {
        $query = "UPDATE posts SET post_comments_count = post_comments_count + {$diff_escaped} WHERE post_id = {$post_id_escaped};";
        $commentsCount = mysqli_query($connection, $query);
        validateQuery($commentsCount);
    }
}

/* Update the Count of approved Comments in database */
function commentsCountByPost($post_id) {
    global $connection;

    $post_id_escaped = mysqli_real_escape_string($connection, $post_id);

    if (postIdValidation($post_id_escaped)) {
        $query = "SELECT comment_post_id, COUNT(*) AS comments_count FROM comments ";
        $query .= "WHERE comment_status = 'одобрен' GROUP BY comment_post_id HAVING comment_post_id = {$post_id_escaped};";
        $commentsCount = mysqli_query($connection, $query);
        validateQuery($commentsCount);
        $post_comments_count = 0;
        if ($row = mysqli_fetch_assoc($commentsCount)) {
            $post_comments_count = $row['comments_count'];
        }

        if (my_is_int($post_comments_count)) {
            $query = "UPDATE posts SET post_comments_count = {$post_comments_count} WHERE post_id = {$post_id_escaped};";
            $updateCommentsCount = mysqli_query($connection, $query);
            validateQuery($updateCommentsCount);
        }
    }
}

/* Update number of posts to selected category in database */
function postsCountByCategory($cat_id) {
    global $connection;

    $cat_id_escaped = mysqli_real_escape_string($connection, $cat_id);

    if (categoryIdValidation($cat_id_escaped)) {
        $query = "SELECT post_category_id, COUNT(*) AS posts_count FROM posts WHERE post_status = 'опубликовано' ";
        $query .= "GROUP BY post_category_id HAVING post_category_id = {$cat_id_escaped};";
        $postsCount = mysqli_query($connection, $query);
        validateQuery($postsCount);
        $cat_posts_count = 0;
        if ($row = mysqli_fetch_assoc($postsCount)) {
            $cat_posts_count = $row['posts_count'];
        }

        if (my_is_int($cat_posts_count)) {
            $query = "UPDATE categories SET cat_posts_count = {$cat_posts_count} WHERE cat_id = {$cat_id_escaped};";
            $updatePostsCount = mysqli_query($connection, $query);
            validateQuery($updatePostsCount);
        }
    }
}

/* Update number of comments by selected user in database */
function commentsCountByUser($user_id) {
    global $connection;

    $user_id_escaped = mysqli_real_escape_string($connection, $user_id);

    if (userIdValidation($user_id_escaped)) {
        $query = "SELECT comment_user_id, COUNT(*) AS comments_count FROM comments WHERE comment_status = 'одобрен' ";
        $query .= "GROUP BY comment_user_id HAVING comment_user_id = {$user_id_escaped};";
        $commentsCount = mysqli_query($connection, $query);
        validateQuery($commentsCount);
        $user_comments_count = 0;
        if ($row = mysqli_fetch_assoc($commentsCount)) {
            $user_comments_count = $row['comments_count'];
        }

        if (my_is_int($user_comments_count)) {
            $query = "UPDATE users SET user_comments_cnt = {$user_comments_count} WHERE user_id = {$user_id_escaped};";
            $updateCommentsCount = mysqli_query($connection, $query);
            validateQuery($updateCommentsCount);
        }
    }
}

/* Update number of posts by selected user in database */
function postsCountByUser($user_id) {
    global $connection;

    $user_id_escaped = mysqli_real_escape_string($connection, $user_id);

    if (userIdValidation($user_id_escaped)) {
        $query = "SELECT post_author_id, COUNT(*) AS posts_count FROM posts WHERE post_status = 'опубликовано' ";
        $query .= "GROUP BY post_author_id HAVING post_author_id = {$user_id_escaped};";
        $postsCount = mysqli_query($connection, $query);
        validateQuery($postsCount);
        $user_posts_count = 0;
        if ($row = mysqli_fetch_assoc($postsCount)) {
            $user_posts_count = $row['posts_count'];
        }

        if (my_is_int($user_posts_count)) {
            $query = "UPDATE users SET user_posts_cnt = {$user_posts_count} WHERE user_id = {$user_id_escaped};";
            $updatePostsCount = mysqli_query($connection, $query);
            validateQuery($updatePostsCount);
        }
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
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../index.php");
        } else {
            $session_user_id = $_SESSION['user_id'];
            if (!userIdValidation($session_user_id)) {
                header("Location: ../includes/logout.php");
            } else {
                $user['login'] = $_POST['user_login'];
                $user['password'] = $_POST['user_password'];
                $user['firstname'] = $_POST['user_firstname'];
                $user['lastname'] = $_POST['user_lastname'];
                $user['email'] = $_POST['user_email'];

                $is_new_user_image = true;
                $default_user_image_name = "user_icon_default.png";
                $user['image_name'] = $_FILES['user_image']['name'];
                $user['image_tmp'] = $_FILES['user_image']['tmp_name'];
                $user['image_error'] = $_FILES['user_image']['error'];
                if ($user['image_name'] == "" || $user['image_tmp'] == "" || $user['image_error'] == UPLOAD_ERR_NO_FILE) {
                    $user['image_name'] = $default_user_image_name;
                    $is_new_user_image = false;
                }

                $user['privilege'] = $_POST['user_privilege'];

                $user = escapeArray($user);

                foreach($err_add_user as $key=>$value) {
                    $err_add_user[$key] = false;
                }
                if (empty($user['login'])) {
                    $err_add_user['login_empty'] = true;
                } else {
                    $err_add_user['login_exists'] = ifLoginExists($user['login'], null);
                }
                if (empty($user['password'])) {
                    $err_add_user['password_empty'] = true;
                } else {
                    $err_add_user['password_correct'] = !passwordValidation($user['password']);
                }
                if (empty($user['firstname'])) {
                    $err_add_user['firstname'] = true;
                }
                if (empty($user['lastname'])) {
                    $err_add_user['lastname'] = true;
                }
                if (empty($user['email'])) {
                    $err_add_user['email_empty'] = true;
                } else {
                    $err_add_user['email_correct'] = !emailValidation($user['email']);
                    $err_add_user['email_exists'] = ifEmailExists($user['email'], null);
                }
                if (empty($user['image_name'])) {
                    $err_add_user['image'] = true;
                }
                if (empty($user['privilege'])) {
                    $err_add_user['privilege_empty'] = true;
                } else {
                    $err_add_user['privilege_correct'] = !userPrivilegeValidation($user['privilege']);
                }
                $err_result = false;
                foreach($err_add_user as $err_item) {
                    $err_result = $err_result || $err_item;
                }

                if (!$err_result) {
                    if ($is_new_user_image) {
                        if (!move_uploaded_file($user['image_tmp'], "../img/{$user['image_name']}")) {
                            $user['image_name'] = $default_user_image_name;
                        }
                    }

                    $user['password'] = password_hash($user['password'], PASSWORD_BCRYPT);

                    $query = "INSERT INTO users(user_login, user_password, user_firstname, user_lastname, user_email, user_image, user_privilege) VALUES('{$user['login']}', '{$user['password']}', '{$user['firstname']}', '{$user['lastname']}', '{$user['email']}', '{$user['image_name']}', '{$user['privilege']}');";
                    $addUser = mysqli_query($connection, $query);
                    validateQuery($addUser);

                    header("Location: admin_users.php?source=info&operation=add");
                }
            }
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

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php");
    } else {
        $session_user_id = $_SESSION['user_id'];
        if (!userIdValidation($session_user_id)) {
            header("Location: ../includes/logout.php");
        } else {
            $delete_user_id_escaped = mysqli_real_escape_string($connection, $delete_user_id);
            if (!userIdValidation($delete_user_id_escaped)) {
                header("Location: admin_users.php?source=info&operation=error");
            } else {
                $query = "DELETE FROM users WHERE user_id = {$delete_user_id_escaped};";
                $delUser = mysqli_query($connection, $query);
                validateQuery($delUser);

                header("Location: admin_users.php?source=info&operation=delete");
            }
        }
    }
}

/* Change privileges of user with $user_id. $privilege might take the following values: user, moderator, admin */
function changeUserPrivilege($user_id, $privilege) {
    global $connection;

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php");
    } else {
        $session_user_id = $_SESSION['user_id'];
        if (!userIdValidation($session_user_id)) {
            header("Location: ../includes/logout.php");
        } else {
            $user['user_id'] = $user_id;
            $user['privilege'] = "";
            switch ($privilege) {
                case "user":
                    $user['privilege'] = "пользователь";
                    break;
                case "moderator":
                    $user['privilege'] = "модератор";
                    break;
                case "admin":
                    $user['privilege'] = "администратор";
                    break;
                default:
                    $user['privilege'] = "";
                    break;
            }
            $user = escapeArray($user);

            if (!userIdValidation($user['user_id'])) {
                header("Location: admin_users.php?source=info&operation=error");
            } else {
                if (userPrivilegeValidation($user['privilege'])) {
                    $query = "UPDATE users SET user_privilege = '{$user['privilege']}' WHERE user_id = {$user['user_id']};";
                    $changeUserPrivilege = mysqli_query($connection, $query);
                    validateQuery($changeUserPrivilege);
                }
            }
        }
    }
}

/* Display Edit User form and put values from database to the fields of the form */
function editUsers() {
    global $connection;

    if (!isset($_GET['edit_user_id'])) {
        header("Location: admin_users.php");
    } else {
        $edit_user_id = $_GET['edit_user_id'];
        $edit_user_id = mysqli_real_escape_string($connection, $edit_user_id);

        if (!userIdValidation($edit_user_id)) {
            header("Location: admin_users.php?source=info&operation=error");
        } else {
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

                $err_edit_user = updateUsers($user_id, $user_image_name, $err_edit_user);
                $err_reset_password = resetUserPassword($user_id, $err_reset_password);
                include "includes/edit_users.php";
                include "includes/reset_password.php";
            }
        }
    }
}

/* Update User Info in database */
function updateUsers($user_id, $current_image, $err_status) {
    global $connection;

    if (isset($_POST['update_user_btn'])) {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../index.php");
        } else {
            $session_user_id = $_SESSION['user_id'];
            if (!userIdValidation($session_user_id)) {
                header("Location: ../includes/logout.php");
            } else {
                $user['user_id'] = $user_id;
                $user['firstname'] = $_POST['edit_user_firstname'];
                $user['lastname'] = $_POST['edit_user_lastname'];
                $user['email'] = $_POST['edit_user_email'];
                $user['privilege'] = $_POST['edit_user_privilege'];
                
                $default_user_image_name = "user_icon_default.png";
                $user['image_name'] = $_FILES['edit_user_image']['name'];
                $user['image_tmp'] = $_FILES['edit_user_image']['tmp_name'];
                $user['image_error'] = $_FILES['edit_user_image']['error'];

                $is_new_image = true;
                if ($user['image_name'] == "" || $user['image_tmp'] == "" || $user['image_error'] == UPLOAD_ERR_NO_FILE) {
                    $user['image_name'] = $current_image;
                    $is_new_image = false;
                }

                $user = escapeArray($user);

                if (!userIdValidation($user['user_id'])) {
                    header("Location: admin_users.php?source=info&operation=error");
                } else {
                    foreach($err_status as $key=>$value) {
                        $err_status[$key] = false;
                    }
                    if (empty($user['firstname'])) {
                        $err_status['firstname'] = true;
                    }
                    if (empty($user['lastname'])) {
                        $err_status['lastname'] = true;
                    }
                    if (empty($user['email'])) {
                        $err_status['email_empty'] = true;
                    } else {
                        $err_status['email_correct'] = !emailValidation($user['email']);
                        $err_status['email_exists'] = ifEmailExists($user['email'], $user_id);
                    }
                    if (empty($user['image_name'])) {
                        $err_status['image'] = true;
                    }
                    if (empty($user['privilege'])) {
                        $err_status['privilege_empty'] = true;
                    } else {
                        $err_status['privilege_correct'] = !userPrivilegeValidation($user['privilege']);
                    }
                    $err_result = false;
                    foreach($err_status as $err_item) {
                        $err_result = $err_result || $err_item;
                    }

                    if (!$err_result) {
                        if ($is_new_image) {
                            if (!move_uploaded_file($user['image_tmp'], "../img/{$user['image_name']}")) {
                                $user['image_name'] = $default_user_image_name;
                            }
                        }

                        $query = "UPDATE users SET ";
                        $query .= "user_firstname = '{$user['firstname']}', ";
                        $query .= "user_lastname = '{$user['lastname']}', ";
                        $query .= "user_email = '{$user['email']}', ";
                        $query .= "user_image = '{$user['image_name']}', ";
                        $query .= "user_privilege = '{$user['privilege']}' ";
                        $query .= "WHERE user_id = {$user['user_id']};";

                        $updateUser = mysqli_query($connection, $query);
                        validateQuery($updateUser);

                        header("Location: admin_users.php?source=info&operation=update");
                    }
                }
            }
        }
    }
    return $err_status;
}

/* Get information about authorized user from database. Put user id as a parameter and return an array with login, firstname, lastname and privilege of user */
function getSessionInfo($user_id) {
    global $connection;

    $user_id_escaped = mysqli_real_escape_string($connection, $user_id);
    $session_user = ['login'=>"Логин", 'firstname'=>"Имя", 'lastname'=>"Фамилия", 'privilege'=>"пользователь"];

    if (userIdValidation($user_id_escaped)) {
        $query = "SELECT * FROM users WHERE user_id = {$user_id_escaped};";
        $sessionInfo = mysqli_query($connection, $query);
        validateQuery($sessionInfo);
        if ($row = mysqli_fetch_assoc($sessionInfo)) {
            $session_user['login'] = $row['user_login'];
            $session_user['firstname'] = $row['user_firstname'];
            $session_user['lastname'] = $row['user_lastname'];
            $session_user['privilege'] = $row['user_privilege'];
        }
    }

    return $session_user;
}

/* Display Edit Form for authorized user with id $user_id. Put data for this user from the database */
function editProfile($user_id) {
    global $connection;

    $user_id_escaped = mysqli_real_escape_string($connection, $user_id);

    if (!userIdValidation($user_id_escaped)) {
        header("Location: admin_profile.php?source=info&operation=error");
    } else {
        $query = "SELECT * FROM users WHERE user_id = {$user_id_escaped};";
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

            $err_edit_profile = updateProfile($user_id, $user_image_name, $err_edit_profile);
            $err_change_password = changeUserPassword($user_id, $user_password, $err_change_password);
            include "includes/edit_profile.php";
            include "includes/change_password.php";
        }
    }
}

/* Put User Data with login $user_login from the form to database. Get as parameter and return an array $err_status which contains Error Status for all fields in the form */
function updateProfile($user_id, $current_image, $err_status) {
    global $connection;

    if (isset($_POST['update_profile_btn'])) {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../index.php");
        } else {
            $session_user_id = $_SESSION['user_id'];
            if (!userIdValidation($session_user_id)) {
                header("Location: ../includes/logout.php");
            } else {
                $user['user_id'] = $user_id;
                $user['firstname'] = $_POST['profile_firstname'];
                $user['lastname'] = $_POST['profile_lastname'];
                $user['email'] = $_POST['profile_email'];
                $user['privilege'] = $_POST['profile_privilege'];
                
                $default_user_image_name = "user_icon_default.png";
                $user['image_name'] = $_FILES['profile_image']['name'];
                $user['image_tmp'] = $_FILES['profile_image']['tmp_name'];
                $user['image_error'] = $_FILES['profile_image']['error'];

                $is_new_image = true;
                if ($user['image_name'] == "" || $user['image_tmp'] == "" || $user['image_error'] == UPLOAD_ERR_NO_FILE) {
                    $user['image_name'] = $current_image;
                    $is_new_image = false;
                }

                $user = escapeArray($user);

                if (!userIdValidation($user['user_id'])) {
                    header("Location: admin_profile.php?source=info&operation=error");
                } else {
                    foreach($err_status as $key=>$value) {
                        $err_status[$key] = false;
                    }
                    if (empty($user['firstname'])) {
                        $err_status['firstname'] = true;
                    }
                    if (empty($user['lastname'])) {
                        $err_status['lastname'] = true;
                    }
                    if (empty($user['email'])) {
                        $err_status['email_empty'] = true;
                    } else {
                        $err_status['email_correct'] = !emailValidation($user['email']);
                        $err_status['email_exists'] = ifEmailExists($user['email'], $user_id);
                    }
                    if (empty($user['image_name'])) {
                        $err_status['image'] = true;
                    }
                    if (empty($user['privilege'])) {
                        $err_status['privilege_empty'] = true;
                    } else {
                        $err_status['privilege_correct'] = !userPrivilegeValidation($user['privilege']);
                    }
                    $err_result = false;
                    foreach($err_status as $err_item) {
                        $err_result = $err_result || $err_item;
                    }

                    if (!$err_result) {
                        if ($is_new_image) {
                            if (!move_uploaded_file($user['image_tmp'], "../img/{$user['image_name']}")) {
                                $user['image_name'] = $default_user_image_name;
                            }
                        }

                        $query = "UPDATE users SET ";
                        $query .= "user_firstname = '{$user['firstname']}', ";
                        $query .= "user_lastname = '{$user['lastname']}', ";
                        $query .= "user_email = '{$user['email']}', ";
                        $query .= "user_image = '{$user['image_name']}', ";
                        $query .= "user_privilege = '{$user['privilege']}' ";
                        $query .= "WHERE user_id = {$user['user_id']};";

                        $updateUser = mysqli_query($connection, $query);
                        validateQuery($updateUser);

                        header("Location: admin_profile.php?source=info&operation=update");
                    }
                }
            }
        }
    }
    return $err_status;
}

/* Reset current user password and set new password */
function resetUserPassword($user_id, $err_status) {
    global $connection;

    if (isset($_POST['reset_password_btn'])) {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../index.php");
        } else {
            $session_user_id = $_SESSION['user_id'];
            if (!userIdValidation($session_user_id)) {
                header("Location: ../includes/logout.php");
            } else {
                $user['user_id'] = $user_id;
                $user['password'] = $_POST['reset_user_password'];
                $user = escapeArray($user);

                if (!userIdValidation($user['user_id'])) {
                    header("Location: admin_profile.php?source=info&operation=error");
                } else {
                    foreach($err_status as $key=>$value) {
                        $err_status[$key] = false;
                    }
                    if (empty($user['password'])) {
                        $err_status['password_empty'] = true;
                    } else {
                        $err_status['password_correct'] = !passwordValidation($user['password']);
                    }
                    $err_result = false;
                    foreach($err_status as $err_item) {
                        $err_result = $err_result || $err_item;
                    }

                    if (!$err_result) {
                        $user['password'] = password_hash($user['password'], PASSWORD_BCRYPT);

                        $query = "UPDATE users SET user_password = '{$user['password']}' WHERE user_id = {$user['user_id']};";
                        $resetPassword = mysqli_query($connection, $query);
                        validateQuery($resetPassword);

                        header("Location: admin_users.php?source=info&operation=password");
                    }
                }
            }
        }
    }
    return $err_status;
}

/* If input password agrees with the current password from database ($db_user_password) then set new password */
function changeUserPassword($user_id, $db_user_password, $err_status) {
    global $connection;

    if (isset($_POST['change_password_btn'])) {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../index.php");
        } else {
            $session_user_id = $_SESSION['user_id'];
            if (!userIdValidation($session_user_id)) {
                header("Location: ../includes/logout.php");
            } else {
                $user['user_id'] = $user_id;
                $user['current_password'] = $_POST['current_user_password'];
                $user['new_password'] = $_POST['new_user_password'];
                $user = escapeArray($user);

                if (!userIdValidation($user['user_id'])) {
                    header("Location: admin_profile.php?source=info&operation=error");
                } else {
                    foreach($err_status as $key=>$value) {
                        $err_status[$key] = false;
                    }
                    if (empty($user['current_password'])) {
                        $err_status['current_password_empty'] = true;
                    } else {
                        $err_status['current_password_valid'] = !password_verify($user['current_password'], $db_user_password);
                    }
                    if (empty($user['new_password'])) {
                        $err_status['new_password_empty'] = true;
                    } else {
                        $err_status['new_password_correct'] = !passwordValidation($user['new_password']);
                    }
                    $err_result = false;
                    foreach($err_status as $err_item) {
                        $err_result = $err_result || $err_item;
                    }

                    if (!$err_result) {
                        $user['new_password'] = password_hash($user['new_password'], PASSWORD_BCRYPT);

                        $query = "UPDATE users SET user_password = '{$user['new_password']}' WHERE user_id = {$user['user_id']};";
                        $changePassword = mysqli_query($connection, $query);
                        validateQuery($changePassword);

                        header("Location: admin_profile.php?source=info&operation=password");
                    }
                }
            }
        }
    }
    return $err_status;
}

/* Check if login is already used by another user. $user_id is ID of user who wants to set login $login, put this parameter equal to null if that's a new user. Return true if login is used and return false if login isn't used */
function ifLoginExists($login, $user_id) {
    global $connection;

    $user['user_id'] = $user_id;
    $user['login'] = $login;
    if (is_null($user['user_id'])) {
        $user['user_id'] = 0;
    }
    $user = escapeArray($user);

    if (my_is_int($user['user_id'])) {
        $query = "SELECT * FROM users WHERE user_login = '{$user['login']}' AND user_id != {$user['user_id']};";
        $loginExists = mysqli_query($connection, $query);
        validateQuery($loginExists);
        $num_rows = mysqli_num_rows($loginExists);
        return $num_rows > 0;
    } else {
        return false;
    }
}

/* Check if e-mail is already used by another user. $user_id is ID of user who wants to set e-mail $email, put this parameter equal to null if that's a new user. Return true if e-mail is used and return false if e-mail isn't used */
function ifEmailExists($email, $user_id) {
    global $connection;

    $user['user_id'] = $user_id;
    $user['email'] = $email;
    if (is_null($user['user_id'])) {
        $user['user_id'] = 0;
    }
    $user = escapeArray($user);

    if (my_is_int($user['user_id'])) {
        $query = "SELECT * FROM users WHERE user_email = '{$user['email']}' AND user_id != {$user['user_id']};";
        $emailExists = mysqli_query($connection, $query);
        validateQuery($emailExists);
        $num_rows = mysqli_num_rows($emailExists);
        return $num_rows > 0;
    } else {
        return false;
    }
}
/* Check if category already exists. $cat_id is ID of category which is edited, put this parameter equal to null if that's a new category. Return true if category exists and return false if category doesn't exists */
function ifCategoryTitleExists($title, $cat_id) {
    global $connection;

    $cat['cat_id'] = $cat_id;
    $cat['title'] = $title;
    if (is_null($cat['cat_id'])) {
        $cat['cat_id'] = 0;
    }
    $cat = escapeArray($cat);

    if (my_is_int($cat['cat_id'])) {
        $query = "SELECT * FROM categories WHERE cat_title = '{$cat['title']}' AND cat_id != {$cat['cat_id']};";
        $categoryTitleExists = mysqli_query($connection, $query);
        validateQuery($categoryTitleExists);
        $num_rows = mysqli_num_rows($categoryTitleExists);
        return $num_rows > 0;
    } else {
        return false;
    }
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

/* Check if category with $category_id exists in database. Return true if category exists */
function categoryIdValidation($category_id) {
    global $connection;

    $category_id_escaped = mysqli_real_escape_string($connection, $category_id);

    if (my_is_int($category_id_escaped)) {
        $query = "SELECT * FROM categories WHERE cat_id = {$category_id_escaped};";
        $categoryValidation = mysqli_query($connection, $query);
        validateQuery($categoryValidation);

        $num_rows = mysqli_num_rows($categoryValidation);

        return $num_rows > 0;
    } else {
        return false;
    }
}

/* Check if post with $post_id exists in database. Return true if post exists */
function postIdValidation($post_id) {
    global $connection;

    $post_id_escaped = mysqli_real_escape_string($connection, $post_id);

    if (my_is_int($post_id_escaped)) {
        $query = "SELECT * FROM posts WHERE post_id = {$post_id_escaped};";
        $postValidation = mysqli_query($connection, $query);
        validateQuery($postValidation);

        $num_rows = mysqli_num_rows($postValidation);

        return $num_rows > 0;
    } else {
        return false;
    }
}

/* Check if comment with $comment_id exists in database. Return true if comment exists */
function commentIdValidation($comment_id) {
    global $connection;

    $comment_id_escaped = mysqli_real_escape_string($connection, $comment_id);

    if (my_is_int($comment_id_escaped)) {
        $query = "SELECT * FROM comments WHERE comment_id = {$comment_id_escaped};";
        $commentValidation = mysqli_query($connection, $query);
        validateQuery($commentValidation);

        $num_rows = mysqli_num_rows($commentValidation);

        return $num_rows > 0;
    } else {
        return false;
    }
}

/* Check if user with $user_id exists in database. Return true if user exists */
function userIdValidation($user_id) {
    global $connection;

    $user_id_escaped = mysqli_real_escape_string($connection, $user_id);

    if (my_is_int($user_id_escaped)) {
        $query = "SELECT * FROM users WHERE user_id = {$user_id_escaped};";
        $userValidation = mysqli_query($connection, $query);
        validateQuery($userValidation);

        $num_rows = mysqli_num_rows($userValidation);

        return $num_rows > 0;
    } else {
        return false;
    }
}

/* Check if $date is correct date with format Y-m-d */
function dateValidation($date) {
    $dateArray = explode('-', $date);
    if (count($dateArray) == 3) {
        list($year, $month, $day) = $dateArray;
        if (my_is_int($year) && my_is_int($month) && my_is_int($day)) {
            return checkdate($month, $day, $year);
        } else {
            return false;
        }
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

/* Get the number of published posts for all categories from database and put this data to the chart. Categories are sorted by number of posts from top to down. $chart_color is color of the chart, $cat_num is number of categories that is displayed on the chart */
function showPostsByCategoryChart($chart_color, $cat_num) {
    global $connection;

    $cat_num_escaped = mysqli_real_escape_string($connection, $cat_num);

    if (my_is_int($cat_num_escaped)) {
        $query = "SELECT * FROM categories ORDER BY cat_posts_count DESC LIMIT {$cat_num_escaped};";

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
}

/* Get the number of approved comments for all posts from database and put this data to the chart. Posts are sorted by number of comments from top to down. $chart_color is color of the chart, $posts_num is number of posts that is displayed on the chart */
function showCommentsByPostChart($chart_color, $posts_num) {
    global $connection;

    $posts_num_escaped = mysqli_real_escape_string($connection, $posts_num);

    if (my_is_int($posts_num_escaped)) {
        $query = "SELECT * FROM posts WHERE post_status = 'опубликовано' ORDER BY post_comments_count DESC LIMIT {$posts_num_escaped};";

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
}

/* Show info message if post operation (add, delete or edit) was successful */
function showPostOperationInfo() {
    if (isset($_GET['operation'])) {
        $post_operation = $_GET['operation'];
    } else {
        $post_operation = "";
    }

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

/* Show info message if comment operation (add, delete or edit) was successful */
function showCommentOperationInfo() {
    if (isset($_GET['operation'])) {
        $comment_operation = $_GET['operation'];
    } else {
        $comment_operation = "";
    }

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

/* Show info message if category operation (add, delete or edit) was successful */
function showCategoryOperationInfo() {
    if (isset($_GET['operation'])) {
        $category_operation = $_GET['operation'];
    } else {
        $category_operation = "";
    }

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

/* Show info message if user operation (add, delete or edit) was successful */
function showUserOperationInfo() {
    if (isset($_GET['operation'])) {
        $user_operation = $_GET['operation'];
    } else {
        $user_operation = "";
    }

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

/* Show info message if profile operation (add, delete or edit) was successful */
function showProfileOperationInfo() {
    if (isset($_GET['operation'])) {
        $profile_operation = $_GET['operation'];
    } else {
        $profile_operation = "";
    }

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
