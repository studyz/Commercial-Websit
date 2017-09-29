<?php if( !is_product() ):?>
<div class="toolbar-products toolbar-top clear-both">
    <?php woocommerce_catalog_ordering();?>
    <div class="modes">
        <?php digitalworld_shop_view_more();?>
    </div>
</div>
<?php endif;?>