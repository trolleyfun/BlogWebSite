<?php /* For function showAllPosts() */ ?>
<!-- Pager -->
<ul class="pager">
    <li class="previous">
        <a href="<?=$prevous_page_link;?>">&larr; Предыдущая</a>
    </li>
    <?php showPagesOfAllPosts($pages_cnt, $page_num); ?>
    <li class="previous">
        <a href="<?=$next_page_link;?>">Следующая &rarr;</a>
    </li>
</ul>