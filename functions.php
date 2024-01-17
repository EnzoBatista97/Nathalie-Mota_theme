<?php

// Ajouter la prise en charge des menus
register_nav_menus(array(
    'menu-principal-header' => __('Menu Header', 'NathalieMota'),
    'menu-principal-footer' => __('Menu Footer', 'NathalieMota'),
));

// Ajouter le support du logo du site
add_theme_support('custom-logo', array(
    'height'      => 100, // Ajustez la hauteur selon vos besoins
    'width'       => 100, // Ajustez la largeur selon vos besoins
    'flex-height' => true,
    'flex-width'  => true,
));

function theme_scripts_and_styles() {
    // Enregistrer le script
    wp_enqueue_script('scripts.js', get_template_directory_uri() . '/assets/js/scripts.js', array('jquery'), '1.0', true);

    // Enregistrer le style
    wp_enqueue_style('styles.css', get_template_directory_uri() . '/assets/sass/styles.css', array(), '1.0', 'all');

    // Enregistrer les fonts
    wp_enqueue_style('fonts', get_template_directory_uri() . '/assets/css/fonts.css');
}

// Ajouter l'action pour charger le script et le style dans la file d'attente WordPress
add_action('wp_enqueue_scripts', 'theme_scripts_and_styles');