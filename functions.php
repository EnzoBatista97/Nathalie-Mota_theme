<?php

// Ajout de jQuery
function theme_add_jquery() {
    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), '3.6.0', true);
}
add_action('wp_enqueue_scripts', 'theme_add_jquery');

// Configuration initiale du thème
function theme_setup() {
    add_theme_support('menus');
    add_theme_support('post-thumbnails');

    function custom_image_sizes() {
        add_image_size('custom-single-photo', 1200, 800, true);
    }
    add_action('after_setup_theme', 'custom_image_sizes');

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
    add_filter('jpeg_quality', function ($arg) {
        return 82;
    });
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'compress_uploaded_images');

// Fonction générique pour charger des photos
function load_photos($args) {
    $html = '';
    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            $html .= '<div class="photo-item">';
            $html .= '<img src="' . esc_url(get_the_post_thumbnail_url()) . '" alt="' . esc_attr(get_the_title()) . '" class="photo-image">';
            $html .= '</div>';
        endwhile;
        wp_reset_postdata();
    else :
        $html .= 'Aucune photo trouvée. Args: ' . print_r($args, true); // Ajout d'informations de débogage
    endif;

    wp_send_json_success($html);
}

// Fonction pour charger des photos par catégorie et format
function load_photos_by_category_and_format() {
    error_log('Entrée dans load_photos_by_category_and_format');
    
    // Assurez-vous que la requête est sécurisée
    check_ajax_referer('wp_rest', 'nonce');

    $selected_category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $selected_format = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '';
    $selected_date_filter = isset($_POST['dateFilter']) ? sanitize_text_field($_POST['dateFilter']) : ''; // Ajout de la gestion du filtre de date
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;

    // Vos arguments de requête WP_Query
    $args = array(
        'post_type'      => 'photo',
        'posts_per_page' => 8,
        'paged'          => $page,
    );

    // Ajout du filtre de date
    if (!empty($selected_date_filter)) {
        $order = ($selected_date_filter === 'recent') ? 'DESC' : 'ASC';
        $args['orderby'] = 'date';
        $args['order'] = $order;
    }

    // Si une catégorie est spécifiée, ajoutez la taxonomie à la requête
    if (!empty($selected_category)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'categorie',
            'field'    => 'slug',
            'terms'    => $selected_category,
        );
    }

    // Si un format est spécifié, ajoutez la taxonomie à la requête
    if (!empty($selected_format)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'format',
            'field'    => 'slug',
            'terms'    => $selected_format,
        );
    }

    // Affichez la catégorie, le format et le filtre de date sélectionnés dans les logs pour le débogage
    error_log('Catégorie sélectionnée : ' . $selected_category);
    error_log('Format sélectionné : ' . $selected_format);
    error_log('Filtre de date sélectionné : ' . $selected_date_filter);

    // Utilisation de la fonction générique pour charger les photos
    load_photos($args);
}

// Action pour charger plus de photos
function handle_load_photos() {
    if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'wp_rest')) {
        $action = isset($_POST['action']) ? $_POST['action'] : '';
        $args = array(
            'post_type'      => 'photo',
            'posts_per_page' => 8,
            'paged'          => $_POST['page'],
        );

        if ($action === 'load_more_photos') {
            load_photos($args);
        } elseif ($action === 'load_photos_by_category') {
            // ... (ajustez les arguments pour la catégorie)
            load_photos($args);
        } elseif ($action === 'load_photos_by_category_and_format') {
            // ... (ajustez les arguments pour la catégorie et le format)
            load_photos($args);
        } else {
            wp_send_json_error('Action AJAX non valide');
        }
    } else {
        wp_send_json_error('Nonce non valide');
    }

    exit();
}

// Action pour charger des photos par catégorie
function handle_category_filter() {
    error_log('Entrée dans handle_category_filter'); // Ajoutez cette ligne
    $args = array(
        'post_type'      => 'photo',
        'posts_per_page' => 8,
        'paged'          => $_POST['page'],
    );

    // Ajout de la catégorie sélectionnée à la requête
    if (isset($_POST['category']) && !empty($_POST['category'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'categorie',
                'field'    => 'slug',
                'terms'    => sanitize_text_field($_POST['category']),
            ),
        );
    }

    // Ajout du filtre de date
    if (isset($_POST['dateFilter']) && !empty($_POST['dateFilter'])) {
        $order = ($_POST['dateFilter'] === 'recent') ? 'DESC' : 'ASC';
        $args['orderby'] = 'date';
        $args['order'] = $order;
    }

    // Utilisation de la fonction générique pour charger les photos
    load_photos($args);
}

// Fonction pour charger des photos par catégorie
function load_photos_by_category() {
    error_log('Entrée dans load_photos_by_category');
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';

    // Ajoutez cette ligne pour loguer la catégorie
    error_log('Catégorie reçue dans la requête : ' . $category);

    // Assurez-vous que la requête est sécurisée
    check_ajax_referer('wp_rest', 'nonce');

    $selected_category = sanitize_text_field($_POST['category']);
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1; // Modification ici

    // Affichez la catégorie sélectionnée dans les logs pour le débogage
    error_log('Catégorie sélectionnée : ' . $selected_category);

    // Vos arguments de requête WP_Query
    $args = array(
        'post_type'      => 'photo',
        'posts_per_page' => 8,
        'paged'          => $page,
    );

    // Si une catégorie est spécifiée, ajoutez la taxonomie à la requête
    if (!empty($selected_category)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'categorie', // Assurez-vous que 'categorie' correspond à la taxonomie correcte
                'field'    => 'slug',
                'terms'    => $selected_category,
            ),
        );
    }

    // Utilisation de la fonction générique pour charger les photos
    load_photos($args);
}

// Assurez-vous d'ajouter la fonction à la fois pour les utilisateurs connectés et non connectés
add_action('wp_ajax_load_photos_by_category', 'load_photos_by_category');
add_action('wp_ajax_nopriv_load_photos_by_category', 'load_photos_by_category');

// Assurez-vous d'ajouter la fonction à la fois pour les utilisateurs connectés et non connectés
add_action('wp_ajax_load_photos_by_category_and_format', 'load_photos_by_category_and_format');
add_action('wp_ajax_nopriv_load_photos_by_category_and_format', 'load_photos_by_category_and_format');

// Ajout de la fonction pour les scripts du filtre de catégorie
function theme_scripts_and_styles_category_filter() {
    wp_enqueue_script('category-filter-scripts', get_template_directory_uri() . '/assets/js/category-filter-scripts.js', array('jquery'), null, true);

    wp_localize_script('category-filter-scripts', 'wpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));
    wp_localize_script('category-filter-scripts', 'frontendajax', array('ajaxurl' => admin_url('admin-ajax.php')));

    // Ajoutez d'autres styles spécifiques au filtre de catégorie si nécessaire
    // wp_enqueue_style('category-filter-styles', get_template_directory_uri() . '/assets/css/category-filter-styles.css', array(), '1.0', 'all');
}
add_action('wp_enqueue_scripts', 'theme_scripts_and_styles_category_filter');

// Enregistrement des scripts et styles nécessaires
function theme_scripts_and_styles() {
    wp_enqueue_script('custom-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array('jquery'), null, true);
    wp_enqueue_script('lightbox-script', get_template_directory_uri() . '/assets/js/lightbox.js', array('jquery'), '1.0', true); // Modifié ici

    wp_localize_script('custom-scripts', 'wpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));
    wp_localize_script('custom-scripts', 'frontendajax', array('ajaxurl' => admin_url('admin-ajax.php')));

    wp_enqueue_style('styles', get_template_directory_uri() . '/assets/sass/styles.css', array(), '1.0', 'all');
    wp_enqueue_style('fonts', get_template_directory_uri() . '/assets/css/fonts.css');


    // ... (ajoutez d'autres styles et scripts si nécessaire)
}
add_action('wp_enqueue_scripts', 'theme_scripts_and_styles');

// Vérification du jeton de sécurité (nonce) pour les requêtes AJAX
function verify_ajax_nonce() {
    if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'wp_rest')) {
        if (isset($_POST['action']) && $_POST['action'] === 'load_more_photos') {
            handle_load_photos();
        } elseif (isset($_POST['action']) && $_POST['action'] === 'load_photos_by_category') {
            handle_category_filter();
        } elseif (isset($_POST['action']) && $_POST['action'] === 'load_photos_by_category_and_format') {
            load_photos_by_category_and_format();
        } else {
            wp_send_json_error('Action AJAX non valide');
        }
    } else {
        wp_send_json_error('Nonce non valide');
    }
}

// Action pour charger plus de photos
add_action('wp_ajax_load_more_photos', 'verify_ajax_nonce');
add_action('wp_ajax_nopriv_load_more_photos', 'verify_ajax_nonce');
