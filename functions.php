<?php
/* Check if the query is successful. If not, intercept the program and display an error message */
function validateQuery($result) {
    global $connection;

    if (!$result) {
        die("Query to database failed. " . mysqli_error($connection));
    }
}

/* Display all posts from database */
function showAllPosts() { 
    global $connection;
    
    $query = "SELECT * FROM posts;";
    $allPosts = mysqli_query($connection, $query);
    validateQuery($allPosts);

    while($row = mysqli_fetch_assoc($allPosts)) {
        $post_id = $row['post_id'];
        $post_title = $row['post_title'];
        $post_author = $row['post_author'];
        $post_date = $row['post_date'];
        $post_image = $row['post_image'];
        $post_content = $row['post_content'];

        include "includes/post_form.php";
    }
}

function showPostById() { 
    global $connection;

    if (isset($_GET['post_id'])) {
        $selected_post_id = $_GET['post_id'];
    
        $query = "SELECT * FROM posts WHERE post_id = {$selected_post_id};";
        $postById = mysqli_query($connection, $query);
        validateQuery($postById);

        while($row = mysqli_fetch_assoc($postById)) {
            $post_id = $row['post_id'];
            $post_title = $row['post_title'];
            $post_author = $row['post_author'];
            $post_date = $row['post_date'];
            $post_image = $row['post_image'];
            $post_content = $row['post_content'];

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
        $cat_title = $row['cat_title'];
        echo "<li><a href='#'>{$cat_title}</a></li>";
    }
}

/* Search posts on tags in database and display them on the page */
function searchPosts() {
    global $connection;

    if (isset($_POST['search_btn'])) {
        $search_data = $_POST['search_data'];
    
        $query = "SELECT * FROM posts WHERE post_tags LIKE '%$search_data%';";
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

        while($row = mysqli_fetch_assoc($search_result)) {
            $post_id = $row['post_id'];
            $post_title = $row['post_title'];
            $post_author = $row['post_author'];
            $post_date = $row['post_date'];
            $post_image = $row['post_image'];
            $post_content = $row['post_content'];

            include "includes/post_form.php";
        }
    }
}
?>