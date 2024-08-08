<?php /* For functions showPostsStatisticsWidget(), showCommentsStatisticsWidget(), showCategoriesStatisticsWidget(), showUsersStatisticsWidget() */ ?>
<div class="col-lg-3 col-md-6">
    <div class="panel panel-<?=$widget_color;?>">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-3">
                    <i class="fa <?=$widget_icon;?> fa-5x"></i>
                </div>
                <div class="col-xs-9 text-right">
                    <div class='huge'><?=$data_num;?></div>
                    <div><?=$data_str;?></div>
                </div>
            </div>
        </div>
        <a href="<?=$link_name;?>">
            <div class="panel-footer">
                <span class="pull-left">Подробнее</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
            </div>
        </a>
    </div>
</div>
<!-- /.col-lg-3.col-md-6 -->