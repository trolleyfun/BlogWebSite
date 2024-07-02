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
                                <?php 
                                /* Put All Posts from database */
                                showAllPosts();
                                ?>
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
