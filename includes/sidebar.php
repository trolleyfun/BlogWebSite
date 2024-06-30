<div class="col-md-4">
                <!-- Blog Search Well -->
                <div class="well">
                    <h4>Поиск</h4>
                    <form action="search.php" method="post">
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
                                <!-- Put categories from database -->
                                <?php 
                                $query = "SELECT * FROM categories LIMIT 10;";

                                if (!$sidebarCategories = mysqli_query($connection, $query)) {
                                    die("Query to database failed." . mysqli_error($connection));
                                } else {
                                    while($row = mysqli_fetch_assoc($sidebarCategories)) {
                                        $cat_title = $row['cat_title'];
                                        echo "<li><a href='#'>{$cat_title}</a></li>";
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>

                <!-- Side Widget Well -->
                <?php include "widget.php"; ?>

            </div>