<?php get_header(); ?>

<?php
// Récupérer une image aléatoire depuis votre catalogue de photos
$args = array(
    'post_type'      => 'photo',
    'posts_per_page' => 1,
    'orderby'        => 'rand', // Obtenir une image aléatoire
);

$query = new WP_Query($args);

if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();
        $random_image_url = get_the_post_thumbnail_url();
    endwhile;
    wp_reset_postdata();
endif;
?>

<!-- Hero Section avec l'image aléatoire -->
<section class="hero-section" style="background-image: url('<?php echo esc_url($random_image_url); ?>');">
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
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <div class="photo-item">
                    <a href="<?php echo esc_url(get_permalink()); ?>">
                        <img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="photo-image">
                        <span class="icon-container">
                            <span class="photo-reference">
                                <?php $reference = get_post_meta(get_the_ID(), 'Référence', true);
                                    if ($reference) {
                                        echo esc_html($reference);
                                    } 
                                ?>
                            </span>
                            <span class="photo-category">
                                <?php
                                    $categories = get_the_terms(get_the_ID(), 'categorie');
                                    if ($categories) {
                                        echo esc_html($categories[0]->name);
                                    }
                                ?>
                            </span>
                            <span class="photo-info-icon"><i class="fas fa-eye"></i></span>
                            <span class="fullscreen-icon"><i class="fas fa-expand fullscreen-icon"></i></span>
                        </span>
                    </a>
                </div>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </div>

        <!-- Bouton "Charger plus" pour la pagination infinie -->
        <div class="load-more-container">
            <button id="load-more-button">Charger plus</button>
        </div>
    </section>



<?php else :
    echo 'Aucune photo trouvée.';
endif;

get_footer();
wp_footer();
?>
