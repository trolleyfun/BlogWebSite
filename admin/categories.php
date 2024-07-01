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
                            <small>Администратор</small>
                        </h1>

                        <!-- Category Add Form -->
                        <div class="col-xs-6">
                            <?php 
                            /* Send category to database */
                            $if_empty = false;
                            if (isset($_POST['add_cat_btn'])) {
                                $cat_title = $_POST['cat_title'];
                                $if_empty = false;
                                if ($cat_title == "" || empty($cat_title)) {
                                    $if_empty = true;
                                } else {
                                    $query = "INSERT INTO categories(cat_title) VALUE('{$cat_title}');";
                                    if (!$addCategory = mysqli_query($connection, $query)) {
                                        die("Query to database failed." . mysqli_error($connection));
                                    }
                                }
                            }
                            ?>
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="cat_title">Название региона:</label>
                                    <input type="text" name="cat_title" id="cat_title" class="form-control">
                                    <?php 
                                    /* Write an error message if field is empty */
                                    if ($if_empty) {
                                        echo "<p style='color: #a94442;'>Введите название региона</p>";
                                    }
                                    ?>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="add_cat_btn" class="btn btn-primary" value="Добавить регион">
                                </div>
                            </form>
                        </div>

                        <!-- Category Table -->
                        <div class="col-xs-6">
                            <table class="table table-bodered table-hover">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Название</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    /* Put categories from database */
                                    $query = "SELECT * FROM categories;";
                                    if (!$adminCategories = mysqli_query($connection,$query)) {
                                        die("Query to database failed." . mysqli_error($connection));
                                    } else {
                                        while($row = mysqli_fetch_assoc($adminCategories)) {
                                            $cat_id = $row['cat_id'];
                                            $cat_title = $row['cat_title'];

                                            echo "<tr>";
                                            echo "<td>{$cat_id}</td>";
                                            echo "<td>{$cat_title}</td>";
                                            echo "<td><a href='categories.php?delete_id={$cat_id}'><span class='fa fa-fw fa-trash-o'></span></a></td>";
                                            echo "</tr>";
                                        }
                                    }
                                    ?>
                                </tbody> 
                            </table>
                            <?php 
                            /* Delete categories from database */
                            if (isset($_GET['delete_id'])) {
                                $delete_id = $_GET['delete_id'];
                                $query = "DELETE FROM categories WHERE cat_id = {$delete_id};";

                                if (!$deleteCategory = mysqli_query($connection, $query)) {
                                    die("Query to database failed." . mysqli_error($connection));
                                }

                                header("Location: categories.php");
                            }
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
