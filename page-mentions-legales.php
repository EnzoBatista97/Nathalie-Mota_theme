<?php
/**
 * Template Name: Mentions légales
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php
        // Récupération du contenu de la page "Mentions légales"
        $mentions_legales_query = new WP_Query(array(
            'post_type' => 'page',
            'pagename' => 'mentions-legales'
        ));

        // Boucle WordPress pour afficher le contenu de la page
        if ($mentions_legales_query->have_posts()) :
            while ($mentions_legales_query->have_posts()) : $mentions_legales_query->the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                    </header><!-- .entry-header -->

                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div><!-- .entry-content -->
                </article><!-- #post-<?php the_ID(); ?> -->
            <?php endwhile;
            wp_reset_postdata();
        else : ?>
            <p><?php _e('Aucun contenu trouvé.', 'textdomain'); ?></p>
        <?php endif; ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
