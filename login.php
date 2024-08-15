<?php include "includes/header.php"; ?>

    <!-- Navigation -->
    <?php include "includes/navigation.php"; ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8 col-xs-12">

                <h1 class="page-header">
                    Добро пожаловать
                </h1>

                <?php 
                /* Login Section */
                if (isset($_SESSION['login'])) {
                    header("Location: index.php");
                } else {
                    if (isset($_GET['source'])) {
                    $source = $_GET['source'];
                    } else {
                        $source = "";
                    }
                    
                    switch ($source) {
                        case "login":
                            userLogin();
                            break;
                        case "signup":
                            include "includes/signup_form.php";
                            break;
                        case "info":
                            include "includes/signup_info.php";
                            break;
                        default:
                            header("Location: index.php");
                            break;
                    }
                }
                ?>
            </div>
            <!-- /.col-md-8 -->

            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php"; ?>

        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <?php include "includes/footer.php"; ?>
