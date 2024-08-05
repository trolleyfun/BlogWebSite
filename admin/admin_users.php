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
                            Пользователи
                            <small>Администратор</small>
                        </h1>

                        <!-- Users Section -->
                        <?php 
                        if (isset($_GET['source'])) {
                            $source = $_GET['source'];
                        } else {
                            $source = "";
                        }

                        switch($source) {
                            case "add_users":
                                include "includes/add_users.php";
                                break;
                            case "edit_users":
                                /* Edit selected user */
                                // updatePosts();
                                editUsers();
                                break;
                            default:
                                include "includes/show_all_users.php";
                                break;
                        }
                        ?>

                        <?php 
                        /* Delete user from the database */
                        deleteUsers();
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
