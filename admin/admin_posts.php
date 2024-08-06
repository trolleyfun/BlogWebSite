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
                            Публикации
                            <small>Администратор</small>
                        </h1>

                        <!-- Posts Section -->
                        <?php 
                        if (isset($_GET['source'])) {
                            $source = $_GET['source'];
                        } else {
                            $source = "";
                        }

                        switch($source) {
                            case "add_posts":
                                addPosts();
                                break;
                            case "edit_posts":
                                editPosts();
                                break;
                            default:
                                include "includes/show_all_posts.php";
                                break;
                        }
                        
                        /* Delete post from the database */
                        deletePosts();
                        ?>
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<?php include "includes/admin_footer.php"; ?>
