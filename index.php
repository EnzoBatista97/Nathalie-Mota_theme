<?php get_header(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <h1>PHOTOGRAPHE EVENT</h1>
</section>

<!-- Filtre de catégories -->
<section class="category-filter-section">
    <label for="category-filter">Filtrer par catégorie:</label>
    <select id="category-filter">
        <option value="">Toutes</option>
        <?php
        // Récupérer la liste des termes de catégorie
        $categories = get_terms(array(
            'taxonomy' => 'categorie',
            'hide_empty' => false, //Pour inclure les catégories même si aucun contenu n'est associé
        ));

        // Afficher les options du filtre de catégorie
        foreach ($categories as $category) {
            echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
        }
        ?>
    </select>
</section>

<!-- Filtre de formats -->
<section class="format-filter-section">
    <label for="format-filter">Filtrer par format :</label>
    <select id="format-filter">
        <option value="">Tous</option>
        <?php
        $formats = get_terms(array(
            'taxonomy' => 'format',
            'hide_empty' => false,
        ));

        foreach ($formats as $format) {
            echo '<option value="' . esc_attr($format->slug) . '">' . esc_html($format->name) . '</option>';
        }
        ?>
    </select>
</section>

<!-- Champ de tri par date -->
<section class="date-filter-section">
    <label for="date-filter">Trier par date :</label>
    <select id="date-filter">
        <option value="recent">Plus récentes</option>
        <option value="old">Plus anciennes</option>
    </select>
</section>


<?php
// Arguments pour la requête WP_Query
$args = array(
    'post_type'      => 'photo',
    'posts_per_page' => 8,
);

// Vérification si une catégorie est sélectionnée
if (isset($_GET['categorie']) && !empty($_GET['categorie'])) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'categorie',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_GET['categorie']),
        ),
    );
}

// Création de l'objet WP_Query
$query = new WP_Query($args);
 

// Vérification s'il y a des photos
if ($query->have_posts()) :
    ?>
    <!-- Liste des photos -->
    <section class="photo-list-section">
        <div class="photo-list-container">
            <?php
            $count = 0;
            while ($query->have_posts()) : $query->the_post();
                // Calcul de la classe pour les colonnes (1 ou 2)
                $column_class = ($count % 2 === 0) ? 'photo-item-single' : 'photo-item-double';
                ?>
                <!-- Structure pour une photo -->
                <div class="photo-item <?php echo esc_attr($column_class); ?>">
                    <img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="photo-image">
                </div>
                <?php
                $count++;
            endwhile;
            // Réinitialisation des données de la requête
            wp_reset_postdata();
            ?>
        </div>

        <!-- Bouton "Charger plus" pour la pagination infinie -->
        <div class="load-more-container">
            <button id="load-more-button">Charger plus</button>
        </div>
    </section>
    <?php
else :
    echo 'Aucune photo trouvée.';
endif;

get_footer();
wp_footer();
?>
