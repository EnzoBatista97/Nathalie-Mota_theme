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
    <div class="filter-container category-filter-section">
        <label class="filter-label" for="category-filter">CATÉGORIES</label>
        <div class="custom-dropdown" id="category-dropdown">
    <!-- HTML -->
    <button class="dropdown-toggle"><i class="fas fa-chevron-down"></i> CATÉGORIES</button>

            <ul class="dropdown-list">
                <li data-value="" data-label="Toutes">Toutes</li>
                <?php
                $categories = get_terms(array(
                    'taxonomy' => 'categorie',
                    'hide_empty' => false,
                ));

                foreach ($categories as $category) {
                    $label = get_field('label', $category); // Supposons que le libellé est stocké dans un champ personnalisé nommé 'label'
                    $label = $label ? $label : $category->name; // Utiliser le nom de la taxonomie s'il n'y a pas de libellé personnalisé
                    echo '<li data-value="' . esc_attr($category->slug) . '" data-label="' . esc_attr($label) . '">' . esc_html($label) . '</li>';
                }
                ?>
            </ul>
        </div>
    </div>

    <!-- Filtre de formats -->
    <div class="filter-container format-filter-section">
        <label class="filter-label" for="format-filter">FORMATS</label>
        <div class="custom-dropdown" id="format-dropdown">
            <button class="dropdown-toggle"><i class="fas fa-chevron-down"></i> FORMATS</button>
            <ul class="dropdown-list">
                <li data-value="" data-label="Tous">Tous</li>
                <?php
                $formats = get_terms(array(
                    'taxonomy' => 'format',
                    'hide_empty' => false,
                ));

                foreach ($formats as $format) {
                    $label = get_field('label', $format); // Supposons que le libellé est stocké dans un champ personnalisé nommé 'label'
                    $label = $label ? $label : $format->name; // Utiliser le nom de la taxonomie s'il n'y a pas de libellé personnalisé
                    echo '<li data-value="' . esc_attr($format->slug) . '" data-label="' . esc_attr($label) . '">' . esc_html($label) . '</li>';
                }
                ?>
            </ul>
        </div>
    </div>

    <!-- Champ de tri par date -->
    <div class="filter-container date-filter-section">
        <label class="filter-label" for="date-filter">TRIER PAR </label>
        <div class="custom-dropdown" id="date-dropdown">
            <button class="dropdown-toggle"><i class="fas fa-chevron-down"></i> TRIER PAR</button>
            <ul class="dropdown-list">
                <li data-value="recent" data-label="Plus récentes">Plus récentes</li>
                <li data-value="old" data-label="Plus anciennes">Plus anciennes</li>
            </ul>
        </div>
    </div>
</section>

<?php
$args = array(
    'post_type'      => 'photo',
    'posts_per_page' => 8,
);

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
