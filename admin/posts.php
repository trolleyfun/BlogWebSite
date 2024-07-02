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

                        <!-- List of posts -->
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Id публикации</th>
                                    <th>Id региона</th>
                                    <th>Название</th>
                                    <th>Автор</th>
                                    <th>Дата</th>
                                    <th>Изображение</th>
                                    <th>Тэги</th>
                                    <th>Кол-во комментариев</th>
                                    <th>Статус</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>Летние каникулы в Японии</td>
                                    <td>Джон Смит</td>
                                    <td>10.06.2024</td>
                                    <td>example.jpg</td>
                                    <td>каникулы, япония, токио</td>
                                    <td>5</td>
                                    <td>Черновик</td>
                                </tr>
                            </tbody>
                        </table>
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
