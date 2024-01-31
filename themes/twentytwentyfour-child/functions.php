<?php 

if ( ! defined( '_S_VERSION' ) ) {
	define( '_S_VERSION', '1.0.1' );
}

function enqueue_task_scripts() {
    wp_enqueue_style( 'task-child-style', get_stylesheet_uri(), array(), _S_VERSION );
    wp_enqueue_script( 'allJs-js', get_stylesheet_directory_uri() . '/js/allJs.js', array('jquery'), _S_VERSION, true );

    wp_localize_script( 'allJs-js', 'testwoo_url_ajax', 
		array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			));
}

add_action( 'wp_enqueue_scripts', 'enqueue_task_scripts' );

function select_action() {

    $discount_category = get_option('product_category');
    $min_value_number = get_option('numeric_field');

    $args = array(
        'post_type' => 'product', 
        'posts_per_page' => -1,   
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat', 
                'field' => 'id',              
                'terms' => $discount_category,
            ),
        ),
    );

    $query = new WP_Query($args);

    $count = 0;
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $count++;
            $query->the_post();
            
            if ($count == 1) { 
                echo '<option value="value' . $count . '" data-custom-value="'. get_the_ID() .'" selected>'. get_the_title() . '</option>';
            } else {
                echo '<option value="value' . $count . '" data-custom-value="'. get_the_ID() .'">'. get_the_title() . '</option>';
            }
            
        }
        wp_reset_postdata();
    } else {
        echo 'Товари не знайдено';
    }

    wp_die();
}

add_action('wp_ajax_testwoo_action', 'select_action');
add_action('wp_ajax_nopriv_testwoo_action', 'select_action'); 


function add_product_action() {

    function add_product_to_cart_by_id($product_id) {
        
        if (wc_get_product($product_id)) {
            
            $cart_item_key = WC()->cart->add_to_cart($product_id);
    
            if ($cart_item_key) {
                return true;
                
            } else {
                return false; 
            }
        } else {
            return false; 
        }
    }

    $id_product = $_POST['id'];

    $result = add_product_to_cart_by_id($id_product);

    if ($result) {
        echo 'Товар успішно додано до корзини!';
    } else {
        echo 'Помилка при додаванні товару до корзини.';
    }

    wp_die();
}

add_action('wp_ajax_add_product', 'add_product_action');
add_action('wp_ajax_nopriv_add_product', 'add_product_action'); 

