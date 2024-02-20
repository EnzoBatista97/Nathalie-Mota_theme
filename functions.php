<?php

// Enqueue jQuery
function theme_add_jquery() {
    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.7.1.min.js', array(), '3.7.1', true);
}
add_action('wp_enqueue_scripts', 'theme_add_jquery');

// Theme initial configuration
function theme_setup() {
    add_theme_support('menus');
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(600, 0, false); // Featured image size

    add_image_size('hero', 1450, 960, true);
    add_image_size('desktop-home', 600, 520, true);
    add_image_size('lightbox', 1300, 900, true);
    add_image_size('single', 700, 850, true);

    // Remove unused image sizes
    function remove_unused_image_sizes() {
        remove_image_size('thumbnail');
        remove_image_size('medium');
        remove_image_size('medium_large');
        remove_image_size('large');
    }
    add_action('init', 'remove_unused_image_sizes');

    // Optimize JPEG compression quality
    add_filter('jpeg_quality', function($arg){return 80;});

    register_nav_menu('menu-principal-header', __('Menu Header', 'NathalieMota'));
    register_nav_menu('menu-principal-footer', __('Menu Footer', 'NathalieMota'));
}
add_action('after_setup_theme', 'theme_setup');

// Enable custom logo support
add_theme_support('custom-logo', array(
    'height'      => 100,
    'width'       => 100,
    'flex-height' => true,
    'flex-width'  => true,
));


// Generic function to load photos
function load_photos($args) {
    $html = '';
    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            $html .= '<div class="photo-item">';
            $html .= '<a href="' . esc_url(get_permalink()) . '">';
            $html .= '<img src="' . esc_url(get_the_post_thumbnail_url()) . '" alt="' . esc_attr(get_the_title()) . '" class="photo-image">';
            $html .= '<span class="icon-container">';
            $html .= '<span class="photo-reference">';
            $reference = get_post_meta(get_the_ID(), 'Référence', true);
            if ($reference) {
                $html .= esc_html($reference);
            }
            $html .= '</span>';
            $html .= '<span class="photo-category">';
            $categories = get_the_terms(get_the_ID(), 'categorie');
            if ($categories) {
                $html .= esc_html($categories[0]->name);
            }
            $html .= '</span>';
            $html .= '<span class="photo-info-icon"><i class="fas fa-eye"></i></span>';
            $html .= '<span class="fullscreen-icon"><i class="fas fa-expand fullscreen-icon"></i></span>';
            $html .= '</span>';
            $html .= '</a>';
            $html .= '</div>';
        endwhile;
        wp_reset_postdata();
    else :
        $html .= 'No photos found.';
    endif;

    wp_send_json_success($html);
}

// Function to load photos by category and format
function load_photos_by_category_and_format() {
    check_ajax_referer('wp_rest', 'nonce');

    $selected_category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $selected_format = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '';
    $selected_date_filter = isset($_POST['dateFilter']) ? sanitize_text_field($_POST['dateFilter']) : '';
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;

    $args = array(
        'post_type'      => 'photo',
        'posts_per_page' => 8,
        'paged'          => $page,
    );

    if (!empty($selected_date_filter)) {
        $order = ($selected_date_filter === 'recent') ? 'DESC' : 'ASC';
        $args['orderby'] = 'date';
        $args['order'] = $order;
    }

    if (!empty($selected_category)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'categorie',
            'field'    => 'slug',
            'terms'    => $selected_category,
        );
    }

    if (!empty($selected_format)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'format',
            'field'    => 'slug',
            'terms'    => $selected_format,
        );
    }

    load_photos($args);
}

// Action to load more photos
function handle_load_photos() {
    if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'wp_rest')) {
        $action = isset($_POST['action']) ? $_POST['action'] : '';
        $args = array(
            'post_type'      => 'photo',
            'posts_per_page' => 8,
            'paged'          => $_POST['page'],
            'category_name'  => isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '',
            'format'         => isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '',
            'dateFilter'     => isset($_POST['dateFilter']) ? sanitize_text_field($_POST['dateFilter']) : '',
        );

        if (!empty($args['dateFilter'])) {
            if ($args['paged'] === 1) {
                load_photos($args);
            } else {
                $args['order'] = ($args['dateFilter'] === 'recent') ? 'DESC' : 'ASC';
                load_photos($args);
            }
        } else {
            load_photos($args);
        }
    } else {
        wp_send_json_error('Invalid nonce');
    }

    exit();
}

// Action to handle category filter
function handle_category_filter() {
    $args = array(
        'post_type'      => 'photo',
        'posts_per_page' => 8,
        'paged'          => $_POST['page'],
    );

    if (isset($_POST['category']) && !empty($_POST['category'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'categorie',
                'field'    => 'slug',
                'terms'    => sanitize_text_field($_POST['category']),
            ),
        );
    }

    if (isset($_POST['dateFilter']) && !empty($_POST['dateFilter'])) {
        $order = ($_POST['dateFilter'] === 'recent') ? 'DESC' : 'ASC';
        $args['orderby'] = 'date';
        $args['order'] = $order;
    }

    load_photos($args);
}

// Function to load photos by category
function load_photos_by_category() {
    check_ajax_referer('wp_rest', 'nonce');

    $selected_category = sanitize_text_field($_POST['category']);
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;

    $args = array(
        'post_type'      => 'photo',
        'posts_per_page' => 8,
        'paged'          => $page,
    );

    if (!empty($selected_category)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'categorie',
                'field'    => 'slug',
                'terms'    => $selected_category,
            ),
        );
    }

    load_photos($args);
}

// Add AJAX actions
add_action('wp_ajax_load_photos_by_category_and_format', 'load_photos_by_category_and_format');
add_action('wp_ajax_nopriv_load_photos_by_category_and_format', 'load_photos_by_category_and_format');

add_action('wp_ajax_load_more_photos', 'handle_load_photos');
add_action('wp_ajax_nopriv_load_more_photos', 'handle_load_photos');

// Enqueue scripts for category filter
function theme_scripts_and_styles_category_filter() {
    wp_enqueue_script('category-filter-scripts', get_template_directory_uri() . '/assets/js/category-filter-scripts.js', array('jquery'), null, true);

    wp_localize_script('category-filter-scripts', 'wpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));
    wp_localize_script('category-filter-scripts', 'frontendajax', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'theme_scripts_and_styles_category_filter');

// Enqueue necessary scripts and styles
function theme_scripts_and_styles() {
    wp_enqueue_script('custom-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array('jquery'), null, true);
    wp_enqueue_script('lightbox-script', get_template_directory_uri() . '/assets/js/lightbox.js', array('jquery'), '1.0', true);
    wp_enqueue_script('burger-menu-script', get_template_directory_uri() . '/assets/js/burger-menu.js', array('jquery'), '1.0', true);

    wp_localize_script('custom-scripts', 'wpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));
    wp_localize_script('custom-scripts', 'frontendajax', array('ajaxurl' => admin_url('admin-ajax.php')));

    wp_enqueue_style('styles', get_template_directory_uri() . '/assets/sass/styles.css', array(), '1.0', 'all');
    wp_enqueue_style('fonts', get_template_directory_uri() . '/assets/css/fonts.css');
}
add_action('wp_enqueue_scripts', 'theme_scripts_and_styles');


// Verify security nonce for AJAX requests
function verify_ajax_nonce() {
    if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'wp_rest')) {
        if (isset($_POST['action']) && $_POST['action'] === 'load_more_photos') {
            handle_load_photos();
        } elseif (isset($_POST['action']) && $_POST['action'] === 'load_photos_by_category') {
            handle_category_filter();
        } elseif (isset($_POST['action']) && $_POST['action'] === 'load_photos_by_category_and_format') {
            load_photos_by_category_and_format();
        } else {
            wp_send_json_error('Invalid AJAX action');
        }
    } else {
        wp_send_json_error('Invalid nonce');
    }
}

// Action to load more photos
add_action('wp_ajax_load_more_photos', 'verify_ajax_nonce');
add_action('wp_ajax_nopriv_load_more_photos', 'verify_ajax_nonce');
