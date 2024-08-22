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
                            <small><?=$session_user['firstname']." ".$session_user['lastname'];?></small>
                        </h1>
                    </div>
                </div>
                <!-- /.row -->

                <!-- Dashboard Section -->
                <!-- Widgets -->
                <?php
                for ($i = 0; $i < 100; $i++) {
                    $err_add_user = ['login_empty'=>false, 'login_exists'=>false, 'password_empty'=>false, 'password_correct'=>false, 'firstname'=>false, 'lastname'=>false, 'email_empty'=>false, 'email_exists'=>false, 'email_correct'=>false, 'image'=>false, 'privilege_empty'=>false, 'privilege_correct'=>false];

    if (isset($_POST['add_user_btn'])) {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../index.php");
        } else {
            $session_user_id = $_SESSION['user_id'];
            if (!userIdValidation($session_user_id)) {
                header("Location: ../includes/logout.php");
            } else {
                $user['login'] = "login{$i}";
                $user['password'] = "12345678";
                $user['firstname'] = "Имя{$i}";
                $user['lastname'] = "Фамилия{$i}";
                $user['email'] = "mail{$i}@example.com";

                $is_new_user_image = true;
                $default_user_image_name = "user_icon_default.png";
                $user['image_name'] = "";
                $user['image_tmp'] = $_FILES['user_image']['tmp_name'];
                $user['image_error'] = $_FILES['user_image']['error'];
                if ($user['image_name'] == "" || $user['image_tmp'] == "" || $user['image_error'] == UPLOAD_ERR_NO_FILE) {
                    $user['image_name'] = $default_user_image_name;
                    $is_new_user_image = false;
                }

                $user['privilege'] = "пользователь";

                $user = escapeArray($user);

                foreach($err_add_user as $key=>$value) {
                    $err_add_user[$key] = false;
                }
                if (empty($user['login'])) {
                    $err_add_user['login_empty'] = true;
                } else {
                    $err_add_user['login_exists'] = ifLoginExists($user['login'], null);
                }
                if (empty($user['password'])) {
                    $err_add_user['password_empty'] = true;
                } else {
                    $err_add_user['password_correct'] = !passwordValidation($user['password']);
                }
                if (empty($user['firstname'])) {
                    $err_add_user['firstname'] = true;
                }
                if (empty($user['lastname'])) {
                    $err_add_user['lastname'] = true;
                }
                if (empty($user['email'])) {
                    $err_add_user['email_empty'] = true;
                } else {
                    $err_add_user['email_correct'] = !emailValidation($user['email']);
                    $err_add_user['email_exists'] = ifEmailExists($user['email'], null);
                }
                if (empty($user['image_name'])) {
                    $err_add_user['image'] = true;
                }
                if (empty($user['privilege'])) {
                    $err_add_user['privilege_empty'] = true;
                } else {
                    $err_add_user['privilege_correct'] = !userPrivilegeValidation($user['privilege']);
                }
                $err_result = false;
                foreach($err_add_user as $err_item) {
                    $err_result = $err_result || $err_item;
                }

                if (!$err_result) {
                    if ($is_new_user_image) {
                        if (!move_uploaded_file($user['image_tmp'], "../img/{$user['image_name']}")) {
                            $user['image_name'] = $default_user_image_name;
                        }
                    }

                    $user['password'] = password_hash($user['password'], PASSWORD_BCRYPT);

                    $query = "INSERT INTO users(user_login, user_password, user_firstname, user_lastname, user_email, user_image, user_privilege) VALUES('{$user['login']}', '{$user['password']}', '{$user['firstname']}', '{$user['lastname']}', '{$user['email']}', '{$user['image_name']}', '{$user['privilege']}');";
                    $addUser = mysqli_query($connection, $query);
                    validateQuery($addUser);
                }
            }
        }
    }
                }

                ?>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<?php include "includes/admin_footer.php"; ?>
