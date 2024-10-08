<div class="col-md-4 col-xs-12">
    <!-- Blog Search Well -->
    <div class="well">
        <h4>Поиск</h4>
        <form action="search.php" method="get">
            <div class="input-group">
                <input type="text" name="search_data" class="form-control">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit" name="search_btn">
                        <span class="glyphicon glyphicon-search"></span>
                </button>
                </span>
            </div>
        </form>
        <!-- /.input-group -->
    </div>

    <!-- Blog Categories Well -->
    <div class="well">
        <h4>Регионы</h4>
        <div class="row">
            <div class="col-lg-12">
                <ul class="list-unstyled">
                    <li><a href='index.php'>Все регионы</a></li>
                    <?php 
                    /* Put categories from database */
                    showAllCategories(100);
                    ?>
                </ul>
            </div>
        </div>
        <!-- /.row -->
    </div>

    <!-- Side Widget Well -->
    <?php include "widget.php"; ?>

</div>