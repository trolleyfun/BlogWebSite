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

/* Display all posts from database */
function showAllPosts() { 
    global $connection;
    
    $query = "SELECT * FROM posts WHERE post_status = 'опубликовано';";
    $allPosts = mysqli_query($connection, $query);
    validateQuery($allPosts);

    $num_rows = mysqli_num_rows($allPosts);
    if ($num_rows == 0) {
        echo "<h2>Скоро здесь будут публикации</h2>";
        echo "<hr>";
    }

    $is_btn = true; //the button will be displayed under each post
    while($row = mysqli_fetch_assoc($allPosts)) {
        $post_id = $row['post_id'];
        $post_title = $row['post_title'];
        $post_author = $row['post_author'];
        $post_date = $row['post_date'];
        $post_image = $row['post_image'];
        $post_content = substr($row['post_content'], 0, 500);

        include "includes/post_form.php";
    }
}

/* Display selected post on the separate page */
function showPostById() { 
    global $connection;

    if (isset($_GET['post_id'])) {
        $selected_post_id = $_GET['post_id'];
    
        $query = "SELECT * FROM posts WHERE post_id = {$selected_post_id};";
        $postById = mysqli_query($connection, $query);
        validateQuery($postById);

        $is_btn = false; //the button won't be displayed under posts
        while($row = mysqli_fetch_assoc($postById)) {
            $post_id = $row['post_id'];
            $post_title = $row['post_title'];
            $post_author = $row['post_author'];
            $post_date = $row['post_date'];
            $post_image = $row['post_image'];
            $post_content = $row['post_content'];

            $err_add_comment = ['author'=>false, 'email'=>false, 'content'=>false, 'if_sent'=>false]; 
            $err_add_comment = addComments($post_id, $err_add_comment);

            include "includes/post_form.php";
            if (isset($_SESSION['login'])) {
                include "includes/add_comment_form.php";
            }
            showCommentsOfPost($post_id);
        }
    }
}

/* Display posts on selected category */
function showPostByCategory() { 
    global $connection;

    if (isset($_GET['cat_id'])) {
        $category_id = $_GET['cat_id'];
        $category_title = "";

        $query = "SELECT * FROM categories WHERE cat_id = {$category_id};";
        $categoryById = mysqli_query($connection, $query);
        validateQuery($categoryById);
        while($row = mysqli_fetch_assoc($categoryById)) {
            $category_id = $row['cat_id'];
            $category_title = $row['cat_title'];
        }
    
        $query = "SELECT * FROM posts WHERE post_category_id = {$category_id} AND post_status = 'опубликовано';";
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
        };
        
        include "includes/category_header.php";

        $is_btn = true; //the button will be displayed under each post
        while($row = mysqli_fetch_assoc($postByCategory)) {
            $post_id = $row['post_id'];
            $post_title = $row['post_title'];
            $post_author = $row['post_author'];
            $post_date = $row['post_date'];
            $post_image = $row['post_image'];
            $post_content = substr($row['post_content'], 0, 500);

            include "includes/post_form.php";
        }
    }
}

/* Display $items_amount categories from the beginning from database */
function showAllCategories($items_amount) {
    global $connection;

    $query = "SELECT * FROM categories LIMIT $items_amount;";

    $allCategories = mysqli_query($connection, $query);
    validateQuery($allCategories);

    while($row = mysqli_fetch_assoc($allCategories)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];
        echo "<li><a href='category.php?cat_id={$cat_id}'>{$cat_title}</a></li>";
    }
}

/* Search posts on tags in database and display them on the page */
function searchPosts() {
    global $connection;

    if (isset($_POST['search_btn'])) {
        $search_data = $_POST['search_data'];
    
        $query = "SELECT * FROM posts WHERE post_tags LIKE '%$search_data%' AND post_status = 'опубликовано';";
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
        };
        
        include "includes/search_header.php";

        $is_btn = true; //the button will be displayed under each post
        while($row = mysqli_fetch_assoc($search_result)) {
            $post_id = $row['post_id'];
            $post_title = $row['post_title'];
            $post_author = $row['post_author'];
            $post_date = $row['post_date'];
            $post_image = $row['post_image'];
            $post_content = substr($row['post_content'], 0, 500);

            include "includes/post_form.php";
        }
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
        if ($add_comment_author == "" || empty($add_comment_author)) {
            $err_status['author'] = true;
        }
        if ($add_comment_email == "" || empty($add_comment_email)) {
            $err_status['email'] = true;
        }
        if ($add_comment_content == "" || empty($add_comment_content)) {
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
        }
    }

    return $err_status;
}

function showCommentsOfPost($comment_post_id) {
    global $connection;

    $query = "SELECT * FROM comments WHERE comment_post_id = $comment_post_id AND comment_status = 'одобрен' ORDER BY comment_id DESC;";
    $commentsOfPost = mysqli_query($connection, $query);
    validateQuery($commentsOfPost);

    while($row = mysqli_fetch_assoc($commentsOfPost)) {
        $comment_author = $row['comment_author'];
        $comment_date = $row['comment_date'];
        $comment_content = $row['comment_content'];

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
?>