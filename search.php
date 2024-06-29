<?php include "includes/db.php"; ?>

<?php include "includes/header.php"; ?>

    <!-- Navigation -->
    <?php include "includes/navigation.php"; ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">
                <?php 
                if (isset($_POST['search_btn'])) {
                    $search_data = $_POST['search_data'];
                
                    $query = "SELECT * FROM posts WHERE post_tags LIKE '%$search_data%';";
                    if (!$search_result = mysqli_query($connection, $query)) {
                        die("Query to database failed" . mysqli_error($connection));
                    } else {
                        $number_rows = mysqli_num_rows($search_result);
                        if ($number_rows == 0) {
                ?>
                <!-- Put Search Results (no results) -->
                <h1 class="page-header">
                    Результаты поиска:
                    <small>по Вашему запросу не найдено ни одной публикации</small>
                </h1>
                <?php
                        } else {
                ?>
                <!-- Put Search Results from database (one or more results) -->
                <h1 class="page-header">
                    Результаты поиска:
                    <small>найдено <?=$number_rows;?> публикаций</small>
                </h1>

                <?php 
                            while($row = mysqli_fetch_assoc($search_result)) {
                                $post_title = $row['post_title'];
                                $post_author = $row['post_author'];
                                $post_date = $row['post_date'];
                                $post_image = $row['post_image'];
                                $post_content = $row['post_content'];
                ?>
                
                <h2>
                    <a href="#"><?=$post_title;?></a>
                </h2>
                <p class="lead">
                    автор: <a href="index.php"><?=$post_author;?></a>
                </p>
                <p><span class="glyphicon glyphicon-time"></span> Опубликовано <?=$post_date;?></p>
                <hr>
                <img class="img-responsive" src="img/<?=$post_image;?>" alt="<?=$post_title;?>">
                <hr>
                <p><?=$post_content;?></p>
                <a class="btn btn-primary" href="#">Читать дальше <span class="glyphicon glyphicon-chevron-right"></span></a>

                <hr>
                <?php
                            }
                        }
                    }
                }
                ?>

                <!-- Pager -->
                <ul class="pager">
                    <li class="previous">
                        <a href="#">&larr; Старые</a>
                    </li>
                    <li class="next">
                        <a href="#">Новые &rarr;</a>
                    </li>
                </ul>

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php"; ?>

        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <?php include "includes/footer.php"; ?>
