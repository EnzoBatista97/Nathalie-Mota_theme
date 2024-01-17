<?php

// Ajouter le support des classes personnalisées pour les menus
function theme_setup() {
    add_theme_support('menus');

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
    'height'      => 100, // Ajustez la hauteur selon vos besoins
    'width'       => 100, // Ajustez la largeur selon vos besoins
    'flex-height' => true,
    'flex-width'  => true,
));

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