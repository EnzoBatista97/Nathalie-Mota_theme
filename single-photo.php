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
        <div class="photo-details-container">
            
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

        <!--Div de la photo-->
        <div class="photo-image-container">
            <?php

                // Affichage de l'image mise en avant
                $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'large');
                if ($image_url) {
                    echo '<img src="' . esc_url($image_url[0]) . '" alt="' . esc_attr(get_the_title()) . '" class="photo-image" />';
                }
            ?>
        </div>
    </div>

    <!--Div du CTA + défilemment des photos-->
    <div class="cta-and-navigation-container">

        <!--Affichage du CTA-->
        <div class="cta-container">    
            <p>Cette photo vous intéresse ?</p>
            <a href="#contactPopup" class="cta-contact" data-reference="<?php echo esc_attr($reference); ?>">Contact</a>
        </div>
        
        <!--Affichage du défilemment des photos-->
        <div class="photo-navigation-container">
            <?php
                the_post_navigation(
                    array(
                        'next_text' => '<p class="meta-nav">' . esc_html__('Next post', 'nathalie-mota-theme-textdomain') . '<span class="meta-nav-arrow">&rarr;</span></p><p class="post-title">%title</p>',
                        'prev_text' => '<p class="meta-nav"><span class="meta-nav-arrow">&larr;</span>' . esc_html__('Previous post', 'nathalie-mota-theme-textdomain') . '</p><p class="post-title">%title</p>',
                    )
                );
            ?>
        </div>
    </div>
</section>

<?php get_footer();?>