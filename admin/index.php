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
                    </div>
                </div>
                <!-- /.row -->

                <!-- Dashboard Section -->
                <!-- Widgets -->
                <div class="row">
                    <!-- Posts Statistics -->
                    <?php showPostsStatistics("primary", "admin_posts.php"); ?>
                    <!-- Comments Statistics -->
                    <?php showCommentsStatistics("green", "admin_comments.php"); ?>
                    <!-- Categories Statistics -->
                    <?php showCategoriesStatistics("yellow", "admin_categories.php"); ?>
                    <!-- Users Statistics -->
                    <?php showUsersStatistics("red", "admin_users.php"); ?>
                </div>
                <!-- /.row -->

                <!-- Charts -->
                <div class="row">
                    <div class="col-xs-12">
                        <div id="columnchart_material" style="width: auto; height: 500px;"></div>
                    </div>
                    <!-- /.col-xs-12 -->
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <?php include "includes/admin_charts_script.php"; ?>

<?php include "includes/admin_footer.php"; ?>
