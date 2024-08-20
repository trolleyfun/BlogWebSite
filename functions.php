<?php
/* Check if the query is successful. If not, intercept the program and display an error message */
function validateQuery($result) {
    global $connection;

    if (!$result) {
        die("Query to database failed. " . mysqli_error($connection));
    }
}

/* Display Message from argument $message if input data is invalid. $status = false: data is valid, $status = true: data is invalid */
function displayMessage($status, $message) {
    if ($status) {
        echo $message;
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

/* Display all posts from database. $post_per_page is number of posts on one page */
function showAllPosts($posts_per_page) { 
    global $connection;
    
    $query = "SELECT * FROM posts AS p LEFT JOIN users AS u ON p.post_author_id = u.user_id WHERE post_status = 'опубликовано' ORDER BY post_date DESC, post_id DESC;";
    $allPosts = mysqli_query($connection, $query);
    validateQuery($allPosts);

    $num_rows = mysqli_num_rows($allPosts);
    if ($num_rows == 0) {
        echo "<h2>Скоро здесь будут публикации</h2>";
        echo "<hr>";
    }

    $pages_cnt = ceil($num_rows / $posts_per_page);
    if ($pages_cnt == 0) {
        $pages_cnt = 1;
    }

    $page_num = 1;
    if (isset($_GET['page'])) {
        $page_num = $_GET['page'];
    }
    if ($page_num < 1 || $page_num > $pages_cnt) {
        $page_num = 1;
    }

    $previous_page_num = $page_num - 1;
    if ($previous_page_num < 1) {
        $previous_page_num = 1;
    }
    $previous_page_link = "index.php?page={$previous_page_num}";

    $next_page_num = $page_num + 1;
    if ($next_page_num > $pages_cnt) {
        $next_page_num = $pages_cnt;
    }
    $next_page_link = "index.php?page={$next_page_num}";

    $post_offset = $posts_per_page * ($page_num - 1);
    if ($post_offset < 0 || $post_offset >= $num_rows) {
        $post_offset = 0;
    }

    $page_name = "posts";

    for($i = 1; $row = mysqli_fetch_assoc($allPosts); $i++) {
        if ($i > $post_offset && $i <= $post_offset + $posts_per_page) {
            $post_id = $row['post_id'];
            $post_title = $row['post_title'];
            $post_author_id = $row['user_id'];
            $post_author_login = $row['user_login'];
            $post_date = $row['post_date'];
            $post_image = $row['post_image'];
            $post_content = substr($row['post_content'], 0, 500);

            include "includes/post_form_short.php";
        }
    }

    include "includes/pager_form.php";
}

/* Display selected post on the separate page */
function showPostById() { 
    global $connection;

    if (isset($_GET['post_id'])) {
        $selected_post_id = $_GET['post_id'];
    
        $query = "SELECT * FROM posts AS p LEFT JOIN users AS u ON p.post_author_id = u.user_id WHERE post_id = {$selected_post_id};";
        $postById = mysqli_query($connection, $query);
        validateQuery($postById);

        if ($row = mysqli_fetch_assoc($postById)) {
            $post_id = $row['post_id'];
            $post_title = $row['post_title'];
            $post_author_id = $row['user_id'];
            $post_author_login = $row['user_login'];
            $post_date = $row['post_date'];
            $post_image = $row['post_image'];
            $post_content = $row['post_content'];

            $err_add_comment = ['author'=>false, 'email'=>false, 'content'=>false, 'if_sent'=>false]; 
            $err_add_comment = addComments($post_id, $err_add_comment);

            include "includes/post_form_full.php";
            if (isset($_SESSION['login'])) {
                include "includes/post_edit_button.php";
                include "includes/add_comment_form.php";
            }
            showCommentsOfPost($post_id);
        }
    }
}

/* Display posts on selected category */
function showPostByCategory($posts_per_page) { 
    global $connection;

    if (isset($_GET['cat_id'])) {
        $cat_id = $_GET['cat_id'];
        $cat_title = "";

        $query = "SELECT * FROM categories WHERE cat_id = {$cat_id};";
        $categoryById = mysqli_query($connection, $query);
        validateQuery($categoryById);

        $number_categories = mysqli_num_rows($categoryById);
        if ($number_categories > 0) {
            if ($row = mysqli_fetch_assoc($categoryById)) {
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];
            }
        
            $query = "SELECT * FROM posts AS p LEFT JOIN users AS u ON p.post_author_id = u.user_id WHERE post_category_id = {$cat_id} AND post_status = 'опубликовано' ORDER BY post_date DESC, post_id DESC;";
            $postByCategory = mysqli_query($connection, $query);
            validateQuery($postByCategory);

            $number_rows = mysqli_num_rows($postByCategory);
            $search_str = "";
            switch(true) {
                case $number_rows == 0:
                    $search_str = "не найдено ни одной публикации";
                    break;
                case $number_rows % 100 >= 11 && $number_rows % 100 <= 19:
                    $search_str = "найдено {$number_rows} публикаций";
                    break;
                case $number_rows % 10 == 1:
                    $search_str = "найдена {$number_rows} публикация";
                    break;
                case $number_rows % 10 >= 2 && $number_rows % 10 <= 4:
                    $search_str = "найдено {$number_rows} публикации";
                    break;
                default:
                    $search_str = "найдено {$number_rows} публикаций";
                    break;            
            }
            
            include "includes/category_header.php";

            $pages_cnt = ceil($number_rows / $posts_per_page);
            if ($pages_cnt == 0) {
                $pages_cnt = 1;
            }

            $page_num = 1;
            if (isset($_GET['page'])) {
                $page_num = $_GET['page'];
            }
            if ($page_num < 1 || $page_num > $pages_cnt) {
                $page_num = 1;
            }

            $previous_page_num = $page_num - 1;
            if ($previous_page_num < 1) {
                $previous_page_num = 1;
            }
            $previous_page_link = "category.php?cat_id={$cat_id}&page={$previous_page_num}";

            $next_page_num = $page_num + 1;
            if ($next_page_num > $pages_cnt) {
                $next_page_num = $pages_cnt;
            }
            $next_page_link = "category.php?cat_id={$cat_id}&page={$next_page_num}";

            $post_offset = $posts_per_page * ($page_num - 1);
            if ($post_offset < 0 || $post_offset >= $number_rows) {
                $post_offset = 0;
            }

            $page_name = "category";

            for($i = 1; $row = mysqli_fetch_assoc($postByCategory); $i++) {
                if ($i > $post_offset && $i <= $post_offset + $posts_per_page) {
                    $post_id = $row['post_id'];
                    $post_title = $row['post_title'];
                    $post_author_id = $row['user_id'];
                    $post_author_login = $row['user_login'];
                    $post_date = $row['post_date'];
                    $post_image = $row['post_image'];
                    $post_content = substr($row['post_content'], 0, 500);

                    include "includes/post_form_short.php";
                }
            }

            include "includes/pager_form.php";
        } else {
            header("Location: index.php");
        }
    } else {
        header("Location: index.php");
    }
}

/* Display $items_amount categories from the beginning from database */
function showAllCategories($items_amount) {
    global $connection;

    $query = "SELECT * FROM categories ORDER BY cat_posts_count DESC, cat_title LIMIT $items_amount;";

    $allCategories = mysqli_query($connection, $query);
    validateQuery($allCategories);

    while($row = mysqli_fetch_assoc($allCategories)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];
        
        include "includes/category_list.php";
    }
}

/* Search posts on tags in database and display them on the page */
function searchPosts($posts_per_page) {
    global $connection;

    if (isset($_GET['search_data'])) {
        $search_data = $_GET['search_data'];

        if ($search_data != "") {
            $query = "SELECT * FROM posts AS p LEFT JOIN users AS u ON p.post_author_id = u.user_id WHERE post_tags LIKE '%$search_data%' AND post_status = 'опубликовано' ORDER BY post_date DESC, post_id DESC;";
            $search_result = mysqli_query($connection, $query);
            validateQuery($search_result);

            $number_rows = mysqli_num_rows($search_result);
            $search_str = "";
            switch(true) {
                case $number_rows == 0:
                    $search_str = "по Вашему запросу не найдено ни одной публикации";
                    break;
                case $number_rows % 100 >= 11 && $number_rows % 100 <= 19:
                    $search_str = "по Вашему запросу найдено {$number_rows} публикаций";
                    break;
                case $number_rows % 10 == 1:
                    $search_str = "по Вашему запросу найдена {$number_rows} публикация";
                    break;
                case $number_rows % 10 >= 2 && $number_rows % 10 <= 4:
                    $search_str = "по Вашему запросу найдено {$number_rows} публикации";
                    break;
                default:
                    $search_str = "по Вашему запросу найдено {$number_rows} публикаций";
                    break;            
            }
            
            include "includes/search_header.php";

            $pages_cnt = ceil($number_rows / $posts_per_page);
                if ($pages_cnt == 0) {
                    $pages_cnt = 1;
                }

                $page_num = 1;
                if (isset($_GET['page'])) {
                    $page_num = $_GET['page'];
                }
                if ($page_num < 1 || $page_num > $pages_cnt) {
                    $page_num = 1;
                }

                $previous_page_num = $page_num - 1;
                if ($previous_page_num < 1) {
                    $previous_page_num = 1;
                }
                $previous_page_link = "search.php?search_data={$search_data}&page={$previous_page_num}";

                $next_page_num = $page_num + 1;
                if ($next_page_num > $pages_cnt) {
                    $next_page_num = $pages_cnt;
                }
                $next_page_link = "search.php?search_data={$search_data}&page={$next_page_num}";

                $post_offset = $posts_per_page * ($page_num - 1);
                if ($post_offset < 0 || $post_offset >= $number_rows) {
                    $post_offset = 0;
                }

                $page_name = "search";

            for($i = 1; $row = mysqli_fetch_assoc($search_result); $i++) {
                if ($i > $post_offset && $i <= $post_offset + $posts_per_page) {
                    $post_id = $row['post_id'];
                    $post_title = $row['post_title'];
                    $post_author_id = $row['user_id'];
                    $post_author_login = $row['user_login'];
                    $post_date = $row['post_date'];
                    $post_image = $row['post_image'];
                    $post_content = substr($row['post_content'], 0, 500);

                    include "includes/post_form_short.php";
                }
            }

            include "includes/pager_form.php";
        } else {
            header("Location: index.php");
        }
    } else {
        header("Location: index.php");
    }
}

/* Put comment from Comment Form in Post Section to database. $add_comment_post_id is ID of the post the comment is related to, $err_status is used for Comment Form fields validation */
function addComments($add_comment_post_id, $err_status) {
    global $connection;

    if (isset($_POST['add_comment_btn'])) {
        $add_comment_author = $_POST['comment_author'];
        $add_comment_date = date('Y-m-d');
        $add_comment_content = $_POST['comment_content'];
        $add_comment_email = $_POST['comment_email'];
        
        foreach($err_status as $err_item) {
            $err_item = false;
        }
        if (empty($add_comment_author)) {
            $err_status['author'] = true;
        }
        if (empty($add_comment_email)) {
            $err_status['email'] = true;
        }
        if (empty($add_comment_content)) {
            $err_status['content'] = true;
        }
        $err_result = false;
        foreach($err_status as $err_item) {
            $err_result = $err_result || $err_item;
        }

        if (!$err_result) {
            $query = "INSERT INTO comments(comment_post_id, comment_author, comment_date, comment_content, comment_email) VALUES({$add_comment_post_id}, '{$add_comment_author}', '{$add_comment_date}', '{$add_comment_content}', '{$add_comment_email}');";

            $addComment = mysqli_query($connection, $query);
            validateQuery($addComment);
            $err_status['if_sent'] = true;

            commentsCountByPost($add_comment_post_id);

            header("Location: post.php?post_id={$add_comment_post_id}#add_comment_form");
        }
    }

    return $err_status;
}

/* Display all approved comments to post with $comment_post_id from database */
function showCommentsOfPost($comment_post_id) {
    global $connection;

    $query = "SELECT * FROM comments AS c LEFT JOIN users AS u ON c.comment_user_id = u.user_id WHERE comment_post_id = $comment_post_id AND comment_status = 'одобрен' ORDER BY comment_date DESC, comment_id DESC;";
    $commentsOfPost = mysqli_query($connection, $query);
    validateQuery($commentsOfPost);

    while($row = mysqli_fetch_assoc($commentsOfPost)) {
        $comment_user_id = $row['user_id'];
        $comment_user_login = $row['user_login'];
        $comment_user_image = $row['user_image'];
        $comment_date = $row['comment_date'];
        $comment_content = $row['comment_content'];

        if (is_null($comment_user_id)) {
            $comment_user_image = "user_icon_default.png";
        }
        include "includes/comment_form.php";
    }
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

/* User Authorization. Check if login and password is correct. If they are correct, start user session */
function userLogin() {
    global $connection;

    $err_user_login = ['login'=>false, 'password'=>false];
    $err_authorization = false;
    if (isset($_POST['login_btn'])) {
        $login_data['login'] = $_POST['user_login'];
        $login_data['password'] = $_POST['user_password'];
        $login_data = escapeArray($login_data);

        foreach($err_user_login as $key=>$value) {
            $err_user_login[$key] = false;
        }
        $err_authorization = false;
        if (empty($login_data['login'])) {
            $err_user_login['login'] = true;
        }
        if (empty($login_data['password'])) {
            $err_user_login['password'] = true;
        }
        $err_result = false;
        foreach($err_user_login as $err_item) {
            $err_result = $err_result || $err_item;
        }
    
        if (!$err_result) {
            $query = "SELECT * FROM users WHERE user_login = '{$login_data['login']}';";
            $userLogin = mysqli_query($connection, $query);
            validateQuery($userLogin);
            $err_authorization = true;
            if ($row = mysqli_fetch_assoc($userLogin)) {
                $user_id = $row['user_id'];
                $user_login = $row['user_login'];
                $user_password = $row['user_password'];
                $user_firstname = $row['user_firstname'];
                $user_lastname = $row['user_lastname'];
                $user_email = $row['user_email'];
                $user_image_name = $row['user_image'];
                $user_privilege = $row['user_privilege'];
                
                if ($login_data['login'] == $user_login && password_verify($login_data['password'], $user_password)) {
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['login'] = $user_login;
                    $_SESSION['firstname'] = $user_firstname;
                    $_SESSION['lastname'] = $user_lastname;
                    $err_authorization = false;

                    header("Location: index.php");
                }
            }
        }
    }

    include "includes/login_form.php";
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

    $query = "SELECT * FROM categories WHERE cat_id = {$post_category_id};";
    $postCategory = mysqli_query($connection, $query);
    validateQuery($postCategory);

    $num_rows = mysqli_num_rows($postCategory);

    return $num_rows > 0;
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

/* Users signup. Put data from the signup form to database */
function userSignup() {
    global $connection;

    $err_user_signup = ['login_empty'=>false, 'login_exists'=>false, 'password_empty'=>false, 'password_correct'=>false, 'firstname'=>false, 'lastname'=>false, 'email_empty'=>false, 'email_exists'=>false, 'email_correct'=>false, 'image'=>false];

    if (isset($_POST['signup_btn'])) {
        $user['login'] = $_POST['signup_user_login'];
        $user['password'] = $_POST['signup_user_password'];
        $user['firstname'] = $_POST['signup_user_firstname'];
        $user['lastname'] = $_POST['signup_user_lastname'];
        $user['email'] = $_POST['signup_user_email'];

        $is_new_user_image = true;
        $default_user_image_name = "user_icon_default.png";
        $user['image_name'] = $_FILES['signup_user_image']['name'];
        $user['image_tmp'] = $_FILES['signup_user_image']['tmp_name'];
        $user['image_error'] = $_FILES['signup_user_image']['error'];
        if ($user['image_name'] == "" || $user['image_error'] == UPLOAD_ERR_NO_FILE) {
            $user['image_name'] = $default_user_image_name;
            $is_new_user_image = false;
        }

        $user = escapeArray($user);

        foreach($err_user_signup as $key=>$value) {
            $err_user_signup[$key] = false;
        }
        if (empty($user['login'])) {
            $err_user_signup['login_empty'] = true;
        } else {
            $err_user_signup['login_exists'] = ifLoginExists($user['login'], null);
        }
        if (empty($user['password'])) {
            $err_user_signup['password_empty'] = true;
        } else {
            $err_user_signup['password_correct'] = !passwordValidation($user['password']);
        }
        if (empty($user['firstname'])) {
            $err_user_signup['firstname'] = true;
        }
        if (empty($user['lastname'])) {
            $err_user_signup['lastname'] = true;
        }
        if (empty($user['email'])) {
            $err_user_signup['email_empty'] = true;
        } else {
            $err_user_signup['email_correct'] = !emailValidation($user['email']);
            $err_user_signup['email_exists'] = ifEmailExists($user['email'], null);
        }
        if (empty($user['image_name'])) {
            $err_user_signup['image'] = true;
        }
        $err_result = false;
        foreach($err_user_signup as $err_item) {
            $err_result = $err_result || $err_item;
        }

        if (!$err_result) {
            if ($is_new_user_image) {
                move_uploaded_file($user['image_tmp'], "img/{$user['image_name']}");
            }

            $user['password'] = password_hash($user['password'], PASSWORD_BCRYPT);

            $query = "INSERT INTO users(user_login, user_password, user_firstname, user_lastname, user_email, user_image) VALUES('{$user['login']}', '{$user['password']}', '{$user['firstname']}', '{$user['lastname']}', '{$user['email']}', '{$user['image_name']}');";
            $userSignup = mysqli_query($connection, $query);
            validateQuery($userSignup);

            header("Location: login.php?source=info");
        }
    }

    include "includes/signup_form.php";
}

/* Update the Count of approved Comments in database */
function commentsCountByPost($post_id) {
    global $connection;

    if (!is_null($post_id)) {
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

/* Display Pager under the Posts on the Home Page of website. $pages_count is number of pages with posts, $current_page is number of current page */
function showPagesAllPosts($pages_count, $current_page) {
    for($i = 1; $i <= $pages_count; $i++) {
        $page_link = "index.php?page={$i}";
        $page_num = $i;
        if ($page_num == $current_page) {
            $item_class = "active-page";
        } else {
            $item_class = "";
        }
        include "includes/pager_item.php";
    }
}

/* Display Pager under the Posts on the Category Page of website with $cat_id. $pages_count is number of pages with posts, $current_page is number of current page */
function showPagesPostsOfCategory($pages_count, $current_page, $cat_id) {
    for($i = 1; $i <= $pages_count; $i++) {
        $page_link = "category.php?cat_id={$cat_id}&page={$i}";
        $page_num = $i;
        if ($page_num == $current_page) {
            $item_class = "active-page";
        } else {
            $item_class = "";
        }
        include "includes/pager_item.php";
    }
}

/* Display Pager under the Posts on the Search Page of website with search query $search. $pages_count is number of pages with posts, $current_page is number of current page */
function showPagesSearchPosts($pages_count, $current_page, $search) {
    for($i = 1; $i <= $pages_count; $i++) {
        $page_link = "search.php?search_data={$search}&page={$i}";
        $page_num = $i;
        if ($page_num == $current_page) {
            $item_class = "active-page";
        } else {
            $item_class = "";
        }
        include "includes/pager_item.php";
    }
}
?>