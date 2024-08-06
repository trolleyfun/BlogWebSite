<?php include "db.php"; ?>
<?php session_start(); ?>

<?php 
if (isset($_POST['login_btn'])) {
    $received_login = $_POST['user_login'];
    $received_password = $_POST['user_password'];

    $received_login = mysqli_real_escape_string($connection, $received_login);
    $received_password = mysqli_real_escape_string($connection, $received_password);

    $query = "SELECT * FROM users WHERE user_login = '{$received_login}';";
    $userLogin = mysqli_query($connection, $query);
    if ($row = mysqli_fetch_assoc($userLogin)) {
        $user_id = $row['user_id'];
        $user_login = $row['user_login'];
        $user_password = $row['user_password'];
        $user_firstname = $row['user_firstname'];
        $user_lastname = $row['user_lastname'];
        $user_email = $row['user_email'];
        $user_image_name = $row['user_image'];
        $user_privilege = $row['user_privilege'];
        
        if ($user_login == $received_login && $user_password == $received_password) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['login'] = $user_login;
            $_SESSION['firstname'] = $user_firstname;
            $_SESSION['lastname'] = $user_lastname;
            $_SESSION['email'] = $user_email;
            $_SESSION['image'] = $user_image_name;
            $_SESSION['privilege'] = $user_privilege;

            header("Location: ../admin/");
        } else {
            header("Location: ../index.php");
        }
    } else {
        header("Location: ../index.php");
    }
}
?>