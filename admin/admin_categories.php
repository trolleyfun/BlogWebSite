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
                            ?>
                        </div>

                        <!-- Category Table -->
                        <div class="col-xs-6">
                            <table class="table table-bodered table-hover">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Название</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    /* Put categories from database */
                                    showAllCategories("includes/all_categories_table.php", "");
                                    ?>
                                </tbody> 
                            </table>
                            <?php 
                            /* Delete categories from database */
                            deleteCategories();
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
