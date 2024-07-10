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
                                include "includes/add_posts.php";
                                break;
                            case "edit_posts":
                                /* Edit selected post */
                                $err_edit_post = ['post_id'=>false, 'category_id'=>false, 'title'=>false, 'author'=>false, 'date'=>false, 'image'=>false, 'content'=>false, 'comments_count'=>false, 'status'=>false];
                                updatePosts();
                                editPosts();
                                break;
                            default:
                                include "includes/show_all_posts.php";
                                break;
                        }
                        ?>

                        <?php 
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
