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

                        <!-- Category Add Form -->
                        <div class="col-xs-6">
                            <?php 
                            /* Add category to database */
                            $err_add_cat_title = false;
                            addCategories();
                            ?>
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="cat_title">Название региона:</label>
                                    <input type="text" name="cat_title" id="cat_title" class="form-control">
                                    <span style='color: #a94442;'><?php displayErrorMessage($err_add_cat_title, "Введите название региона"); ?></span>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="add_cat_btn" class="btn btn-primary" value="Добавить регион">
                                </div>
                            </form>

                            <?php 
                            /* Update category in database */
                            $err_edit_cat_title = false;
                            updateCategories();
                            ?>

                            <?php 
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
                                    showAllCategories();
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
