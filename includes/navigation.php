<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Главная</a>
                <?php
                /* Display Profile Navigation Menu for authorized users */
                if (isset($_SESSION['user_id'])) {
                    include "includes/profile_nav_authorized.php";
                } else {
                    include "includes/profile_nav_not_authorized.php";
                }
                ?>
            </div>
        </div>
        <!-- /.container -->
    </nav>