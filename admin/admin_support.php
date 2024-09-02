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
                            Поддержка
                        </h1>

                        <!-- Support Section -->
                        <div class="col-md-6 col-xs-12">
                            <h3>Возникли вопросы? Напишите нам!</h3>
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="support_subject">Тема обращения<span style='color: #a94442;'> *</span></label>
                                    <input type="text" name="support_subject" id="support_subject" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="support_email">E-mail для ответа<span style='color: #a94442;'> *</span></label>
                                    <input type="email" name="support_email" id="support_email" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="support_content">Текст обращения<span style='color: #a94442;'> *</span></label>
                                    <textarea name="support_content" id="support_content" rows="7" class="form-control" required></textarea>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="send_support_btn" class="btn btn-primary" value="Отправить">
                                </div>
                            </form>
                        </div>
                        <?php 
                        // if (isset($_GET['source'])) {
                        //     $source = $_GET['source'];
                        // } else {
                        //     $source = "";
                        // }

                        // switch($source) {
                        //     case "info":
                        //         showProfileOperationInfo();
                        //         break;
                        //     default:
                        //         editProfile($session_user_id);
                        //         break;
                        // }
                        ?>
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