<?php get_header(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <h1>PHOTOGRAPHE EVENT</h1>
</section>

<section class="filter-section">
    <!-- Filtre de catégories -->
    <section class="category-filter-section">
        <label for="category-filter">CATÉGORIES</label>
        <select id="category-filter">
            <option value="">Toutes</option>
            <?php
            $categories = get_terms(array(
                'taxonomy' => 'categorie',
                'hide_empty' => false,
            ));

            foreach ($categories as $category) {
                echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
            }
            ?>
        </select>
    </section>

    <!-- Filtre de formats -->
    <section class="format-filter-section">
        <label for="format-filter">FORMATS</label>
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
        <label for="date-filter">TRIER PAR :</label>
        <select id="date-filter">
            <option value="recent">Plus récentes</option>
            <option value="old">Plus anciennes</option>
        </select>
    </section>
</section>

<?php
$args = array(
    'post_type'      => 'photo',
    'posts_per_page' => 8,
);

if (isset($_GET['categorie']) && !empty($_GET['categorie'])) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'categorie',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_GET['categorie']),
        ),
    );
}

$query = new WP_Query($args);

if ($query->have_posts()) :
?>
    <!-- Liste des photos -->
    <section class="photo-list-section">
        <div class="photo-list-container">
            <?php
            while ($query->have_posts()) : $query->the_post();
                echo '<div class="photo-item">';
                echo '<a href="' . esc_url(get_permalink()) . '">';
                echo '<img src="' . esc_url(get_the_post_thumbnail_url()) . '" alt="' . esc_attr(get_the_title()) . '" class="photo-image">';
                echo '<span class="photo-info-icon"><i class="fas fa-eye"></i></span>';
                echo '</a>';
                echo '<span class="fullscreen-icon"><i class="fas fa-expand"></i></span>';
                echo '</div>';
            endwhile;
            wp_reset_postdata();
            ?>
        </div>

        <!-- Bouton "Charger plus" pour la pagination infinie -->
        <div class="load-more-container">
            <button id="load-more-button">Charger plus</button>
        </div>
    </section>




<!-- Rétablissez le lien vers lightbox.js -->
<script src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/js/lightbox.js"></script>

    <!-- Lightbox -->
    <div id="lightbox-overlay"></div>
<div id="lightbox">
    <span id="lightbox-prev">&lt;</span>
    <img id="lightbox-image" src="" alt="">
    <span id="lightbox-next">&gt;</span>
    <p id="lightbox-info"></p>
    <span id="lightbox-close" onclick="closeLightbox()">×</span>
</div>

<?php
else :
    echo 'Aucune photo trouvée.';
endif;

get_footer();
wp_footer();
?>
