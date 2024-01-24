<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Nathalie-Mota_theme
 * @since Nathalie-Mota_theme 1.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="entry-header">
        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
    </header><!-- .entry-header -->

    <!-- Ajouter la section pour la vignette (thumbnail) -->
    <div class="entry-thumbnail">
        <?php the_post_thumbnail(); ?>
    </div>

    <div class="entry-content">
        <?php
        the_content();

        // If single page and comments are open, load up the comment template.
        if (is_single() && comments_open()) {
            comments_template();
        }
        ?>
    </div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->