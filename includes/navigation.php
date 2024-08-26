<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">Главная</a>
        
            <!-- Collect the nav links, forms, and other content for toggling (PC view) -->
            <ul class="nav navbar-nav" id="bs-example-navbar-collapse-2">
                <?php 
                /* Put categories from database */
                showAllCategories(4);
                ?>
            </ul>
            <?php
            /* Display Profile Navigation Menu for authorized users */
            if (isset($_SESSION['user_id'])) {
                include "includes/profile_nav_authorized.php";
            } else {
                include "includes/profile_nav_not_authorized.php";
            }
            ?>
        </div>
        <!-- /.navbar-header -->

        <!-- Collect the nav links, forms, and other content for toggling (Mobile Devices view) -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php 
                /* Put categories from database */
                showAllCategories(4);
                ?>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>