<?php /* For function showAllCategories */ ?>
<option value="<?=$cat_id;?>" <?php if ($cat_id == $post_category_id) { echo "selected"; }?>><?=$cat_title;?></option>