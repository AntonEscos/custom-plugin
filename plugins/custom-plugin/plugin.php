<?php
/*
Plugin Name:  Custom Plugin
Description:  Discount product
Version: 1.0
Author: Anton Karpenko
*/

function enqueue_custom_styles() {
    wp_enqueue_style('custom-plugin-styles', plugins_url('styles.css', __FILE__));
}

add_action('admin_enqueue_scripts', 'enqueue_custom_styles');


function add_custom_menu() {
    add_menu_page('Custom Plugin', 'Custom Plugin', 'manage_options', 'custom-plugin', 'custom_plugin_page');
}
add_action('admin_menu', 'add_custom_menu');

function custom_plugin_page() {
    ?>
    <div class="wrap">
        <h2>Ваш плагін</h2>
        <form method="post" action="">

            <label for="product_category">Виберіть категорію продукту:</label>
            <?php
            
            $product_categories = get_terms(array(
                'taxonomy' => 'product_cat',
                'hide_empty' => false, 
            ));

            wp_dropdown_categories(array(
                'show_option_none' => 'Оберіть категорію',
                'id' => 'product_category',
                'name' => 'product_category',
                'taxonomy' => 'product_cat',
                'orderby' => 'name',
                'selected' => get_option('product_category'), 
            ));
            ?><br>

            <label for="numeric_field">Числове поле:</label>
            <input type="number" name="numeric_field" id="numeric_field" value="<?php echo esc_attr(get_option('numeric_field')); ?>" step="1" min="0"><br>

            <label for="product_category">Виберіть категорію продукту:</label>
            <?php

            wp_dropdown_categories(array(
                'show_option_none' => 'Оберіть категорію',
                'id' => 'product_category_free',
                'name' => 'product_category_free',
                'taxonomy' => 'product_cat',
                'orderby' => 'name',
                'selected' => get_option('product_category_free'), 
            ));
            ?><br>

            <?php wp_nonce_field('custom-plugin-nonce'); ?>
            <input type="submit" name="save_settings" class="button-primary" value="Зберегти">
        </form>
    </div>
    <?php
}

function save_custom_settings() {
    if (isset($_POST['save_settings']) && check_admin_referer('custom-plugin-nonce')) {

        $product_category = absint($_POST['product_category']);
        $numeric_field = intval($_POST['numeric_field']); 
        $product_category_free = intval($_POST['product_category_free']); 

        update_option('product_category', $product_category);
        update_option('numeric_field', $numeric_field);
        update_option('product_category_free', $product_category_free);
    }
}
add_action('admin_init', 'save_custom_settings');

function modify_cart_item_name($product_name, $cart_item, $cart_item_key) {
    return $product_name + 'aaaaa';
}
add_filter('woocommerce_cart_item_name', 'modify_cart_item_name', 10, 3);

