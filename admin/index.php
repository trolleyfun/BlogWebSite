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
                <!-- Posts Statistics -->
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-file-text fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class='huge'>12</div>
                                        <div>публикаций</div>
                                    </div>
                                </div>
                            </div>
                            <a href="admin_posts.php">
                                <div class="panel-footer">
                                    <span class="pull-left">Подробнее</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <!-- /.col-lg-3.col-md-6 -->
                    <!-- Comments Statistics -->
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-green">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-comments fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class='huge'>23</div>
                                        <div>комментария</div>
                                    </div>
                                </div>
                            </div>
                            <a href="admin_comments.php">
                                <div class="panel-footer">
                                    <span class="pull-left">Подробнее</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <!-- /.col-lg-3.col-md-6 -->
                    <!-- Categories Statistics -->
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-yellow">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-list fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class='huge'>13</div>
                                        <div>регионов</div>
                                    </div>
                                </div>
                            </div>
                            <a href="admin_categories.php">
                                <div class="panel-footer">
                                    <span class="pull-left">Подробнее</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <!-- /.col-lg-3.col-md-6 -->
                    <!-- Users Statistics -->
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-red">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-user fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class='huge'>23</div>
                                        <div> пользователя</div>
                                    </div>
                                </div>
                            </div>
                            <a href="admin_users.php">
                                <div class="panel-footer">
                                    <span class="pull-left">Подробнее</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <!-- /.col-lg-3.col-md-6 -->
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<?php include "includes/admin_footer.php"; ?>
