<?php include "includes/header.php"; ?>

    <!-- Navigation -->
    <?php include "includes/navigation.php"; ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-lg-8">
                <!-- Blog Posts -->
                <?php 
                /* Put Blog Posts from database */
                showPostById();
                ?>

                <!-- Posted Comments -->

                <!-- Comments Section -->
                <?php include "includes/comment_form.php"; ?>

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php"; ?>

        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <?php include "includes/footer.php"; ?>
