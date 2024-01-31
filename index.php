<?php get_header(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <h1>PHOTOGRAPHE EVENT</h1>
</section>

<!-- Filtres -->
<section class="filters-section">
    <div class="filter-container">
        <label for="category-filter" class="filter-label">Catégorie :</label>
        <select id="category-filter" class="filter-select">
            <option value="">Toutes</option>
            <!-- Les options seront chargées dynamiquement via Ajax -->
        </select>
    </div>

    <div class="filter-container">
        <label for="format-filter" class="filter-label">Format :</label>
        <select id="format-filter" class="filter-select">
            <option value="">Tous</option>
            <!-- Les options seront chargées dynamiquement via Ajax -->
        </select>
    </div>

    <div class="filter-container">
        <label for="sort-order" class="filter-label">Trier par date :</label>
        <select id="sort-order" class="filter-select">
            <option value="desc">Plus récentes</option>
            <option value="asc">Plus anciennes</option>
        </select>
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
            <?php
            $count = 0;
            while ($query->have_posts()) : $query->the_post();
                // Calcul de la classe pour les colonnes (1 ou 2)
                $column_class = ($count % 2 === 0) ? 'photo-item-single' : 'photo-item-double';
            ?>
                <!-- Structure pour une photo -->
                <div class="photo-item <?php echo $column_class; ?>">
                    <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>" class="photo-image">
                </div>
            <?php
                $count++;
            endwhile;
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
