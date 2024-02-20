<?php
/**
 * Template Name: Page À PROPOS
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php
        // Récupération du contenu de la page "À propos"
        $a_propos_query = new WP_Query(array(
            'post_type' => 'page',
            'pagename' => 'a-propos'
        ));

        // Boucle WordPress pour afficher le contenu de la page
        if ($a_propos_query->have_posts()) :
            while ($a_propos_query->have_posts()) : $a_propos_query->the_post(); ?>
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
