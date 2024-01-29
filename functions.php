<?php

// Ajout jQuery
function theme_add_jquery() {
    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), '3.6.0', true);
}
add_action('wp_enqueue_scripts', 'theme_add_jquery');

// Ajout support des classes personnalisées pour les menus
function theme_setup() {

    // Ajout support des menus WordPress
    add_theme_support('menus');

    // Ajout support des images mises en avant (thumbnails)
    add_theme_support('post-thumbnails');

    // Ajout taille d'image personnalisée
    function custom_image_sizes() {
    add_image_size('custom-single-photo', 1200, 800, true);
    }

    add_action('after_setup_theme', 'custom_image_sizes');

    // Ajout support des classes personnalisées pour les éléments de menu
    register_nav_menu('menu-principal-header', __('Menu Header', 'NathalieMota'));
    register_nav_menu('menu-principal-footer', __('Menu Footer', 'NathalieMota'));
}

add_action('after_setup_theme', 'theme_setup');

// Ajout support des classes personnalisées pour les éléments de menu
function theme_custom_menu_classes($classes, $args) {
    if (property_exists($args, 'menu_class')) {
        $classes[] = $args->menu_class;
    }
    return $classes;
}

add_filter('nav_menu_css_class', 'theme_custom_menu_classes', 10, 3);

// Ajout support du logo du site
add_theme_support('custom-logo', array(
    'height'      => 100,
    'width'       => 100,
    'flex-height' => true,
    'flex-width'  => true,
));

// Compression des images lors de l'upload
function compress_uploaded_images($file) {
    // Définir la qualité de compression JPEG à 82%
    add_filter('jpeg_quality', function ($arg) {
        return 82;
    });
    return $file;
}

add_filter('wp_handle_upload_prefilter', 'compress_uploaded_images');

// Scripts et styles
function theme_scripts_and_styles() {
    // Script
    wp_enqueue_script('scripts', get_template_directory_uri() . '/assets/js/scripts.js', array('jquery'), '1.0', true);

    // Style
    wp_enqueue_style('styles', get_template_directory_uri() . '/assets/sass/styles.css', array(), '1.0', 'all');

    // Fonts
    wp_enqueue_style('fonts', get_template_directory_uri() . '/assets/css/fonts.css');
}

add_action('wp_enqueue_scripts', 'theme_scripts_and_styles');