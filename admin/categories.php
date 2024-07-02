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
                            $is_add_empty = false;
                            addCategories();
                            ?>
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="cat_title">Название региона:</label>
                                    <input type="text" name="cat_title" id="cat_title" class="form-control">
                                    <?php 
                                    /* Write an error message if field is empty */
                                    if ($is_add_empty) {
                                        echo "<p style='color: #a94442;'>Введите название региона</p>";
                                    }
                                    ?>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="add_cat_btn" class="btn btn-primary" value="Добавить регион">
                                </div>
                            </form>

                            <?php 
                            /* Edit Category in database */
                            if (isset($_GET['edit_id']))
                            {
                                $edit_id = $_GET['edit_id'];
                                include "includes/edit_categories.php";
                            }
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
