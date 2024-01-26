<?php

// Ajouter le support des classes personnalisées pour les menus
function theme_setup() {
    // Ajouter le support des menus WordPress
    add_theme_support('menus');

    // Ajouter le support des images mises en avant (thumbnails)
    add_theme_support('post-thumbnails');

    // Ajouter la taille d'image personnalisée pour correspondre à la maquette
function custom_image_sizes() {
    add_image_size('custom-single-photo', 1200, 800, true); // Changer les dimensions selon vos besoins
}
add_action('after_setup_theme', 'custom_image_sizes');


    // Ajouter le support des classes personnalisées pour les éléments de menu
    register_nav_menu('menu-principal-header', __('Menu Header', 'NathalieMota'));
    register_nav_menu('menu-principal-footer', __('Menu Footer', 'NathalieMota'));
}
add_action('after_setup_theme', 'theme_setup');

// Ajouter le support des classes personnalisées pour les éléments de menu
function theme_custom_menu_classes($classes, $item, $args) {
    if (property_exists($args, 'menu_class')) {
        $classes[] = $args->menu_class;
    }

    return $classes;
}
add_filter('nav_menu_css_class', 'theme_custom_menu_classes', 10, 3);

// Ajouter le support du logo du site
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

// Ajouter le support des fichiers WebP lors de l'upload
function add_webp_upload_mimes($existing_mimes) {
    $existing_mimes['webp'] = 'image/webp';
    return $existing_mimes;
}
add_filter('mime_types', 'add_webp_upload_mimes');

// Enregistrez les scripts et styles
function theme_scripts_and_styles() {
    // Enregistrer le script
    wp_enqueue_script('scripts', get_template_directory_uri() . '/assets/js/scripts.js', array('jquery'), '1.0', true);

    // Enregistrer le style
    wp_enqueue_style('styles', get_template_directory_uri() . '/assets/sass/styles.css', array(), '1.0', 'all');

    // Enregistrer les fonts
    wp_enqueue_style('fonts', get_template_directory_uri() . '/assets/css/fonts.css');
}

// Ajouter l'action pour charger le script et le style dans la file d'attente WordPress
add_action('wp_enqueue_scripts', 'theme_scripts_and_styles');