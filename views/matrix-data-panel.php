<form method="post" action="<?php echo admin_url("admin.php?page=matrixdata"); ?>">
    <div id="product_attributes" class="panel matrixdata-wrapper">
        <div class="fu-metaboxes">
            <table width="100%">
                <tr>
                    <td width="50%" valign="top">
                        <h2>
                            <span>Product Data:</span>
                            <button type="button" class="button add_attribute" data-type="productattribute">
                                <?php _e('Add Product Attribute','frontuser'); ?>
                            </button>
                            <div class="clear"></div>
                        </h2>
                        <div class="clear"></div>
                        <div class="metabox-content product_attributes">
                            <table width="100%">
                                <thead>
                                    <tr>
                                        <td width="30%">Key</td>
                                        <td width="50%">Value</td>
                                        <td align="left"></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="hidden">
                                        <td width="30%"><input type="text" class="attribute_name" name="product_attribute_name[]" value="" /></td>
                                        <td width="50%">
                                            <?php $attributes = array('name', 'slug', 'date_created', 'date_modified', 'status', 'featured', 'catalog_visibility', 'description', 'short_description', 'sku', 'price', 'regular_price', 'sale_price', 'date_on_sale_from', 'date_on_sale_to', 'total_sales', 'tax_status', 'tax_class', 'manage_stock', 'stock_quantity', 'stock_status', 'backorders', 'sold_individually', 'weight', 'length', 'width', 'height', 'upsell_ids', 'cross_sell_ids', 'parent_id', 'reviews_allowed', 'purchase_note', 'attributes', 'default_attributes', 'menu_order', 'category_ids', 'tag_ids', 'virtual', 'gallery_image_ids', 'shipping_class_id', 'downloads', 'download_expiry', 'downloadable', 'download_limit', 'image_id', 'rating_counts', 'average_rating', 'review_count'); ?>
                                            <select class="attribute_name" name="product_attribute_values[]" >
                                            <?php foreach ($attributes as $val) { ?>
                                                <option value="<?php echo $val; ?>"><?php echo $val; ?></option>
                                            <?php } ?>
                                            </select>
                                        </td>
                                        <td align="left">
                                            <div class="actions">
                                                <button type="button" class="button remove_attribute"><span class="dashicons-trash"></span></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                        if(!empty( $product_attributes)) {
                                            foreach ($product_attributes as $key => $value) {
                                                ?>
                                    <tr>
                                        <td width="30%"><input type="text" class="attribute_name" name="product_attribute_name[]" value="<?php echo $key; ?>" /></td>
                                        <td width="50%">
	                                        <?php $attributes = array('name', 'slug', 'date_created', 'date_modified', 'status', 'featured', 'catalog_visibility', 'description', 'short_description', 'sku', 'price', 'regular_price', 'sale_price', 'date_on_sale_from', 'date_on_sale_to', 'total_sales', 'tax_status', 'tax_class', 'manage_stock', 'stock_quantity', 'stock_status', 'backorders', 'sold_individually', 'weight', 'length', 'width', 'height', 'upsell_ids', 'cross_sell_ids', 'parent_id', 'reviews_allowed', 'purchase_note', 'attributes', 'default_attributes', 'menu_order', 'category_ids', 'tag_ids', 'virtual', 'gallery_image_ids', 'shipping_class_id', 'downloads', 'download_expiry', 'downloadable', 'download_limit', 'image_id', 'rating_counts', 'average_rating', 'review_count'); ?>
                                            <select class="attribute_name" name="product_attribute_values[]" >
	                                            <?php foreach ($attributes as $val) { ?>
                                                    <option value="<?php echo $val; ?>" <?php if($val == $value) { echo 'selected="selected"'; } ?> ><?php echo $val; ?></option>
		                                        <?php } ?>
                                            </select>
                                        </td>
                                        <td align="left">
                                            <div class="actions">
                                                <button type="button" class="button remove_attribute"><span class="dashicons-trash"></span></button>
                                            </div>
                                        </td>
                                    </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr class="nodata">
                                                <td colspan="3"><p>No custom attribute added yet</p></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </td>
                    <td width="50%" valign="top">
                        <h2>
                            <span>User Data:</span>
                            <button type="button" class="button add_attribute" data-type="userattribute">
                                <?php _e('Add User Attribute','frontuser'); ?>
                            </button>
                            <div class="clear"></div>
                        </h2>
                        <div class="metabox-content user_attributes">
                            <table width="100%">
                                <thead>
                                    <tr>
                                        <td width="30%">Key</td>
                                        <td width="50%">Value</td>
                                        <td align="left"></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="hidden">
                                        <td width="30%"><input type="text" class="attribute_name" name="user_attribute_name[]" value="" /></td>
                                        <td width="50%">
	                                        <?php $attributes = array('ID', 'user_login', 'user_pass', 'user_nicename', 'user_email', 'user_url', 'user_registered', 'user_activation_key', 'user_status', 'display_name'); ?>
                                            <select class="attribute_values" name="user_attribute_values[]" >
		                                        <?php foreach ($attributes as $val) { ?>
                                                    <option value="<?php echo $val; ?>"><?php echo $val; ?></option>
		                                        <?php } ?>
                                            </select>
                                        </td>
                                        <td align="left">
                                            <div class="actions">
                                                <button type="button" class="button remove_attribute"><span class="dashicons-trash"></span></button>
                                            </div>
                                        </td>
                                    </tr>

                                    <?php
                                        if(!empty( $user_attributes)) {
                                            foreach ($user_attributes as $key => $value) {
                                                ?>
                                    <tr>
                                        <td width="30%"><input type="text" class="attribute_name" name="user_attribute_name[]" value="<?php echo $key; ?>" /></td>
                                        <td width="50%">
	                                        <?php $attributes = array('ID', 'user_login', 'user_pass', 'user_nicename', 'user_email', 'user_url', 'user_registered', 'user_activation_key', 'user_status', 'display_name'); ?>
                                            <select class="attribute_values" name="user_attribute_values[]" >
		                                        <?php foreach ($attributes as $val) { ?>
                                                    <option value="<?php echo $val; ?>" <?php if($val == $value) { echo 'selected="selected"'; } ?> ><?php echo $val; ?></option>
		                                        <?php } ?>
                                            </select>
                                        </td>
                                        <td align="left">
                                            <div class="actions">
                                                <button type="button" class="button remove_attribute"><span class="dashicons-trash"></span></button>
                                            </div>
                                        </td>
                                    </tr>
	                                            <?php
                                            }
                                        } else {
	                                        ?>
                                            <tr class="nodata">
                                                <td colspan="3"><p>No custom attribute added yet</p></td>
                                            </tr>
	                                        <?php
                                        }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="toolbar no-border">
            <?php submit_button('Save attributes'); ?>
        </div>
    </div>
</form>