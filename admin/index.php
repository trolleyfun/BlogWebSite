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
                    <?php showPostsStatisticsWidget("primary", "admin_posts.php"); ?>
                    <!-- Comments Statistics -->
                    <?php showCommentsStatisticsWidget("green", "admin_comments.php"); ?>
                    <!-- Categories Statistics -->
                    <?php showCategoriesStatisticsWidget("yellow", "admin_categories.php"); ?>
                    <!-- Users Statistics -->
                    <?php showUsersStatisticsWidget("red", "admin_users.php"); ?>
                </div>
                <!-- /.row -->

                <!-- Charts -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="dashboard_chart" style="width: auto; height: 300px;" data-color="#fff000" data-names="Европа Азия Россия" data-values="2 5 3" data-x-title="" data-y-title="количество публикаций" data-chart-title="Популярные регионы"></div>
                    </div>
                    <!-- /.col-lg-4.col-md-6 -->
                    <!-- <div class="col-lg-4 col-md-6">
                        <div class="columnchart_material" style="width: auto; height: 200px;" data-color="red"></div>
                    </div> -->
                    <!-- /.col-lg-4.col-md-6 -->
                    <!-- <div class="col-lg-4 col-md-6">
                        <div class="columnchart_material" style="width: auto; height: 200px;"></div>
                    </div> -->
                    <!-- /.col-lg-4.col-md-6 -->
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
