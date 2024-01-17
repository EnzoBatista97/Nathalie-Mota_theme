<?php get_header(); ?>

<!-- Contenu principal -->
<div id="content">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php the_content(); ?>
    <?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>
<?php wp_footer(); ?>