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

/* Check if $value is unsigneg integer or unsigned integer string */
function my_is_int($value) {
    if(is_int($value)) {
        return $value >= 0;
    } else
        return is_numeric($value) && ctype_digit($value);
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

    if (!isset($_GET['post_id'])) {
        header("Location: index.php");
    } else {
        $selected_post_id = $_GET['post_id'];
        $selected_post_id = mysqli_real_escape_string($connection, $selected_post_id);

        if (!postIdValidation($selected_post_id)) {
            header("Location: index.php");
        } else {
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

                $err_add_comment = ['content'=>false]; 
                $err_add_comment = addComments($post_id, $err_add_comment);

                include "includes/post_form_full.php";
                if (isset($_SESSION['user_id'])) {
                    include "includes/post_edit_button.php";
                    include "includes/add_comment_form.php";
                }
                showCommentsOfPost($post_id);
            } 
        }
    } 
}

/* Display posts on selected category */
function showPostByCategory($posts_per_page) { 
    global $connection;

    if (!isset($_GET['cat_id'])) {
        header("Location: index.php");
    } else {
        $cat_id = $_GET['cat_id'];
        $cat_id = mysqli_real_escape_string($connection, $cat_id);
        $cat_title = "";

        if (!categoryIdValidation($cat_id)) {
            header("Location: index.php");
        } else {
            $query = "SELECT * FROM categories WHERE cat_id = {$cat_id};";
            $categoryById = mysqli_query($connection, $query);
            validateQuery($categoryById);

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
        }
    }
}

/* Display $items_amount categories from the beginning from database */
function showAllCategories($items_amount) {
    global $connection;

    $items_amount_escaped = mysqli_real_escape_string($connection, $items_amount);

    if (my_is_int($items_amount_escaped)) {

        $query = "SELECT * FROM categories ORDER BY cat_posts_count DESC, cat_title LIMIT {$items_amount_escaped};";

        $allCategories = mysqli_query($connection, $query);
        validateQuery($allCategories);

        while($row = mysqli_fetch_assoc($allCategories)) {
            $cat_id = $row['cat_id'];
            $cat_title = $row['cat_title'];
            
            include "includes/category_list.php";
        }
    }
}

/* Search posts on tags in database and display them on the page */
function searchPosts($posts_per_page) {
    global $connection;

    if (!isset($_GET['search_data'])) {
        header("Location: index.php");
    } else {
        $search_data = $_GET['search_data'];
        $search_data = mysqli_real_escape_string($connection, $search_data);

        if ($search_data == "") {
            header("Location: index.php");
        } else {
            $query = "SELECT * FROM posts AS p LEFT JOIN users AS u ON p.post_author_id = u.user_id WHERE post_tags LIKE '%{$search_data}%' AND post_status = 'опубликовано' ORDER BY post_date DESC, post_id DESC;";
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
        }
    }
}

/* Put comment from Comment Form in Post Section to database. $add_comment_post_id is ID of the post the comment is related to, $err_status is used for Comment Form fields validation */
function addComments($add_comment_post_id, $err_status) {
    global $connection;

    if (isset($_POST['add_comment_btn'])) {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php");
        } else {
            $comment['post_id'] = $add_comment_post_id;
            $comment['user_id'] = $_SESSION['user_id'];
            $comment['date'] = date('y-m-d');
            $comment['content'] = $_POST['comment_content'];
            $comment = escapeArray($comment);

            if (!userIdValidation($comment['user_id'])) {
                header("Location: includes/logout.php");
            } else {  
                if (!postIdValidation($comment['post_id'])) {
                    header("Location: index.php");
                } else {
                    foreach($err_status as $key=>$value) {
                        $err_status[$key] = false;
                    }
                    if (empty($comment['content'])) {
                        $err_status['content'] = true;
                    }
                    $err_result = false;
                    foreach($err_status as $err_item) {
                        $err_result = $err_result || $err_item;
                    }
                    
                    if (!$err_result) {
                        $query = "INSERT INTO comments(comment_post_id, comment_user_id, comment_date, comment_content) VALUES({$comment['post_id']}, {$comment['user_id']}, '{$comment['date']}', '{$comment['content']}');";

                        $addComment = mysqli_query($connection, $query);
                        validateQuery($addComment);

                        commentsCountByPost($comment['post_id']);
                        commentsCountByUser($comment['user_id']);

                        header("Location: post.php?post_id={$comment['post_id']}#add_comment_form");
                    }
                }
            }
        }
    }

    return $err_status;
}

/* Display all approved comments to post with $comment_post_id from database */
function showCommentsOfPost($comment_post_id) {
    global $connection;

    $comment_post_id_escaped = mysqli_real_escape_string($connection, $comment_post_id);

    if (!postIdValidation($comment_post_id_escaped)) {
        header("Location: index.php");
    } else {
        $query = "SELECT * FROM comments AS c LEFT JOIN users AS u ON c.comment_user_id = u.user_id WHERE comment_post_id = $comment_post_id_escaped AND comment_status = 'одобрен' ORDER BY comment_date DESC, comment_id DESC;";
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
        if ($user['image_name'] == "" || $user['image_tmp'] == "" || $user['image_error'] == UPLOAD_ERR_NO_FILE) {
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
                if (!move_uploaded_file($user['image_tmp'], "img/{$user['image_name']}")) {
                    $user['image_name'] = $default_user_image_name;
                }
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