<?php
// Ajout de jQuery pour les fonctionnalités dépendantes de JavaScript
function theme_add_jquery() {
    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), '3.6.0', true);
}
add_action('wp_enqueue_scripts', 'theme_add_jquery');

// Configuration initiale du thème
function theme_setup() {
    // Activation des menus WordPress
    add_theme_support('menus');

    // Activation des images mises en avant (thumbnails)
    add_theme_support('post-thumbnails');

    // Définition des tailles d'images personnalisées
    function custom_image_sizes() {
        add_image_size('custom-single-photo', 1200, 800, true);
    }
    add_action('after_setup_theme', 'custom_image_sizes');

    // Enregistrement des emplacements de menus personnalisés
    register_nav_menu('menu-principal-header', __('Menu Header', 'NathalieMota'));
    register_nav_menu('menu-principal-footer', __('Menu Footer', 'NathalieMota'));
}
add_action('after_setup_theme', 'theme_setup');

// Activation du support du logo personnalisé
add_theme_support('custom-logo', array(
    'height'      => 100,
    'width'       => 100,
    'flex-height' => true,
    'flex-width'  => true,
));

// Compression des images lors du téléchargement
function compress_uploaded_images($file) {
    // Réglage de la qualité de compression JPEG à 82%
    add_filter('jpeg_quality', function ($arg) {
        return 82;
    });
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'compress_uploaded_images');

// Enregistrement des scripts et styles nécessaires
function theme_scripts_and_styles() {
    // Inclusion du script personnalisé pour les fonctionnalités front-end
    wp_enqueue_script('custom-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array('jquery'), null, true);

    // Transmission des paramètres de l'API WordPress au script
    wp_localize_script('custom-scripts', 'wpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));
    wp_localize_script('custom-scripts', 'frontendajax', array('ajaxurl' => admin_url('admin-ajax.php')));

    // Inclusion des scripts et styles d'Ajax de WordPress
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-form');
    wp_enqueue_script('wp-api');
    wp_enqueue_script('custom-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array('jquery'), null, true);

    // Inclusion des styles
    wp_enqueue_style('styles', get_template_directory_uri() . '/assets/sass/styles.css', array(), '1.0', 'all');

    // Inclusion des polices
    wp_enqueue_style('fonts', get_template_directory_uri() . '/assets/css/fonts.css');
}
add_action('wp_enqueue_scripts', 'theme_scripts_and_styles');

// Vérification du jeton de sécurité (nonce) pour les requêtes AJAX
function verify_ajax_nonce() {
    // Vérification de la validité du jeton de sécurité
    if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'wp_rest')) {
        // Le jeton est valide, continuer le traitement
        load_more_photos(); // Appel de la fonction de chargement de plus de photos via AJAX
    } else {
        // Le jeton n'est pas valide, gérer la situation en conséquence
        wp_send_json_error('Nonce non valide');
    }
}
add_action('wp_ajax_load_more_photos', 'verify_ajax_nonce');
add_action('wp_ajax_nopriv_load_more_photos', 'verify_ajax_nonce');

// Configuration de l'emplacement du fichier journal des erreurs PHP
ini_set('error_log', 'C:/Users/Batis/Local Sites/nathalie-mota/logs/php/error.log');

// Fonction pour charger davantage de photos avec des journaux pour le débogage
function load_more_photos() {
    if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'wp_rest')) {
        // Modifiez cette partie pour renvoyer le HTML des nouvelles photos
        $html = '';
        $args = array(
            'post_type'      => 'photo',
            'posts_per_page' => 8,
            'paged'          => $_POST['page'],
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                // Structure pour une photo
                $html .= '<div class="photo-item">';
                $html .= '<img src="' . esc_url(get_the_post_thumbnail_url()) . '" alt="' . esc_attr(get_the_title()) . '" class="photo-image">';
                $html .= '</div>';
            endwhile;
            wp_reset_postdata();
        else :
            $html .= 'Aucune photo trouvée.';
        endif;

        wp_send_json_success($html);
    } else {
        error_log('Requête Ajax échouée en raison d\'un nonce non valide.');
        wp_send_json_error('Nonce non valide');
    }

    exit();
}

