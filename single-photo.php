<?php
/**
 * The template for displaying single photos
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Nathalie-Mota_theme
 * @since Nathalie-Mota_theme 1.0
*/
?>

<?php get_header();?>
 
<!--Section de la photo affichée-->
<section class="photo-details-section">

    <!--Div de la photo et ses détails-->
    <div class="photo-container">

        <!--Div des détails de la photos-->
        <div class="photo-details">

            <div class="photo-details_container">

                <!--Affichage du titre-->
                <h1 class="photo-title"><?php the_title(); ?></h1>

                <?php

                    // Affichage de la référence
                    $reference = get_post_meta(get_the_ID(), 'Référence', true);
                    if ($reference) {
                        echo '<p class="photo-reference">Référence : ' . esc_html($reference) . '</p>';
                    }

                    // Affichage de la catégorie
                    $categorie = get_the_terms(get_the_ID(), 'categorie');
                    if ($categorie && !is_wp_error($categorie)) {
                        $category_name = array();
                        foreach ($categorie as $category) {
                            $category_name[] = $category->name;
                        }
                        echo '<p class="photo-category">Catégorie : ' . esc_html(implode(', ', $category_name)) . '</p>';
                    }

                    // Affichage du format
                    $format = get_the_terms(get_the_ID(), 'format');
                    if ($format && !is_wp_error($format)) {
                        $format_name = array();
                        foreach ($format as $format) {
                            $format_name[] = $format->name;
                        }
                        echo '<p class="photo-format">Format : ' . esc_html(implode(', ', $format_name)) . '</p>';
                    }
                
                    // Affichage du type
                    $type = get_post_meta(get_the_ID(), 'Type', true);
                    if ($type) {
                        echo '<p class="photo-type">Type : ' . esc_html($type) . '</p>';
                    }
                
                    // Affichage de l'année de publication
                    $publication_year = get_the_date('Y');
                    echo '<p class="photo-year">Année : ' . esc_html($publication_year) . '</p>';
                ?>
            </div>
        </div>

        <!--Div de la photo-->
        <div class="photo-image-container">
            <div class="photo-item">
                <?php
                    // Affichage de l'image mise en avant
                    $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single');
                    if ($image_url) {
                        echo '<img src="' . esc_url($image_url[0]) . '" alt="' . esc_attr(get_the_title()) . '" class="photo-image" />';
                    }
                ?>
            </div>
        </div>
    </div>

    <!--Div du CTA + défilement des photos-->
    <div class="cta-and-navigation-container">
        <!--Affichage du CTA-->
        <div class="cta-container">    
            <p>Cette photo vous intéresse ?</p>
            <?php get_template_part('modal-contact');?>
            <button class="cta-contact-button" data-reference="<?php echo esc_attr($reference); ?>">Contact</button>
        </div>
        
        <!--Affichage de la miniature de la photo suivante-->
        <div class="next-photo-thumbnail">
            <?php
                $previous_post = get_previous_post();
                if ($previous_post) {
                    $previous_post_id = $previous_post->ID;
                    $previous_post_thumbnail = get_the_post_thumbnail($previous_post_id, 'thumbnail');
                    if ($previous_post_thumbnail) {
                        echo $previous_post_thumbnail;
                    }
                }
            ?>
        </div>


    </div>
<!--Ajout des flèches de navigation-->
<div class="photo-navigation">
    <?php if (get_next_post_link()): ?>
        <div class="nav-next">
            <?php next_post_link('%link', '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/icones/Arrow left.svg') . '" alt="' . __('Next Photo', 'textdomain') . '"><span class="screen-reader-text">' . __('Next Photo', 'textdomain') . '</span>'); ?>
        </div>
    <?php endif; ?>
    <?php if (get_previous_post_link()): ?>
        <div class="nav-previous">
            <?php previous_post_link('%link', '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/icones/Arrow right.svg') . '" alt="' . __('Previous Photo', 'textdomain') . '"><span class="screen-reader-text">' . __('Previous Photo', 'textdomain') . '</span>'); ?>
        </div>
    <?php endif; ?>
</div>
</section>

<!-- Section des photos apparentées -->
<section class="related-photos-section">
    <h3>VOUS AIMEREZ AUSSI</h3>
    <div class="related-photos-container">
        <?php
        // Récupérer la catégorie de la photo actuelle
        $current_category = get_the_terms(get_the_ID(), 'categorie');

        // Vérifier si la catégorie existe et récupérer les photos apparentées
        if ($current_category && !is_wp_error($current_category)) {
            // Configuration de la requête pour récupérer les photos apparentées
            $related_args = array(
                'post_type'      => 'photo',
                'posts_per_page' => 2,
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'categorie',
                        'field'    => 'term_id',
                        'terms'    => $current_category[0]->term_id, // Utilisez l'ID de la catégorie de la photo actuelle
                    ),
                ),
                'post__not_in'   => array(get_the_ID()), // Exclure la photo actuelle de la liste des photos apparentées
            );

            // Exécuter la requête pour récupérer les photos apparentées
            $related_query = new WP_Query($related_args);

            // Afficher les miniatures des photos apparentées
            if ($related_query->have_posts()) {
                while ($related_query->have_posts()) {
                    $related_query->the_post();
                    ?>
<div class="related-photo-thumbnail photo-item"> <!-- Ajoutez la classe photo-item ici -->
    <a href="<?php the_permalink(); ?>">
        <?php
        // Récupérer l'URL de l'image mise en avant de la photo apparentée
        $thumbnail_url = get_the_post_thumbnail_url(get_the_ID());
        if ($thumbnail_url) {
            echo '<img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr(get_the_title()) . '" class="photo-thumbnail photo-image" />'; // Ajoutez la classe photo-image
        } else {
            // Afficher une image de remplacement si aucune miniature n'est disponible
            echo '<img src="' . esc_url(get_template_directory_uri() . '/images/placeholder.jpg') . '" alt="Placeholder Image" class="photo-thumbnail photo-image" />'; // Ajoutez la classe photo-image
        }
        ?>
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


                    <?php
                }
                wp_reset_postdata();
            } else {
                echo 'Aucune photo apparentée trouvée.';
            }
        }
        ?>
    </div>
</section>

<?php get_footer();?>
<!-- Inclusion du script JavaScript -->
<script src="<?php echo get_template_directory_uri() . '/assets/js/burger-menu.js'; ?>"></script>
<script src="<?php echo get_template_directory_uri() . '/assets/js/scripts.js'; ?>"></script>
