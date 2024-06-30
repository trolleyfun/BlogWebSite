<?php include "../includes/db.php"; ?>
<?php include "includes/header.php"; ?>
    <div id="wrapper">

        <!-- Navigation -->
        <?php include "includes/navigation.php"; ?>

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
                            <form action="">
                                <div class="form-group">
                                    <label for="cat_title">Название региона:</label>
                                    <input type="text" name="cat_title" id="cat_title" class="form-control">
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
                                    <tr>
                                        <td>1</td>
                                        <td>Азия</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Западная Европа</td>
                                    </tr>
                                </tbody> 
                            </table>
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

<?php include "includes/footer.php"; ?>
