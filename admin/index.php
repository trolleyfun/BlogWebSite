<?php include "includes/admin_header.php"; ?>
    <div id="wrapper">

        <!-- Navigation -->
        <?php include "includes/admin_navigation.php"; ?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Добро пожаловать
                            <small><?=$session_user['firstname']." ".$session_user['lastname'];?></small>
                        </h1>
                        <h3>Пользователей онлайн: <span class="users_online"></span></h3>
                    </div>
                </div>
                <!-- /.row -->

                <!-- Dashboard Section -->
                <!-- Widgets -->
                <div class="row">
                    <!-- Posts Statistics -->
                    <?php showPostsStatisticsWidget("primary", "admin_posts.php"); ?>
                    <!-- Comments Statistics -->
                    <?php showCommentsStatisticsWidget("green", "admin_comments.php"); ?>
                    <!-- Categories Statistics -->
                    <?php showCategoriesStatisticsWidget("yellow", "admin_categories.php"); ?>
                    <!-- Users Statistics -->
                    <?php showUsersStatisticsWidget("red", "admin_users.php"); ?>
                </div>
                <hr>
                <!-- /.row -->

                <!-- Charts -->
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Posts Number By Category Chart -->
                        <?php showPostsByCategoryChart("#337ab7", 6); ?>
                        <!-- Comments Number By Post Chart -->
                        <?php showCommentsByPostChart("#5cb85c", 5); ?>
                        <!-- Comments&Posts Number By User Chart -->
                        <?php showUsersActivityChart("#f0ad4e", "#d9534f", 6); ?>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<?php include "includes/admin_footer.php"; ?>
