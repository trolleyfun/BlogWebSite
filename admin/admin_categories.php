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
                            Регионы
                            <small>Администратор</small>
                        </h1>

                        <div class="col-xs-6">
                            <?php 
                            /* Add category to database */
                            addCategories();
                            /* Edit selected Category */
                            editCategories();
                            /* Delete categories from database */
                            deleteCategories();
                            ?>
                        </div>

                        <div class="col-xs-6">
                            <?php 
                            /* Display all categories from database */
                            include "includes/show_all_categories.php";
                            ?>
                        </div>
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
