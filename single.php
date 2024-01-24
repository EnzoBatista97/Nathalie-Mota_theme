<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Nathalie-Mota_theme
 * @since Nathalie-Mota_theme 1.0
 */

get_header();

/* Start the Loop */
while (have_posts()) :
    the_post();

    // Inclure le template pour afficher le contenu de l'article unique
    get_template_part('templates_part/content-single');

    if (is_attachment()) {
        // Parent post navigation.
        the_post_navigation(
            array(
                /* translators: %s: Parent post link. */
                'prev_text' => sprintf(__('<span class="meta-nav">Published in</span><span class="post-title">%s</span>', 'nathalie-mota-theme-textdomain'), '%title'),
            )
        );
    }

    // If comments are open or there is at least one comment, load up the comment template.
    if (comments_open() || get_comments_number()) {
        comments_template();
    }

    // Previous/next post navigation.
    $nathalie_mota_theme_next = is_rtl() ? '<span class="meta-nav">&rarr;</span>' : '<span class="meta-nav">&larr;</span>';
    $nathalie_mota_theme_prev = is_rtl() ? '<span class="meta-nav">&larr;</span>' : '<span class="meta-nav">&rarr;</span>';

    $nathalie_mota_theme_next_label     = esc_html__('Next post', 'nathalie-mota-theme-textdomain');
    $nathalie_mota_theme_previous_label = esc_html__('Previous post', 'nathalie-mota-theme-textdomain');

    the_post_navigation(
        array(
            'next_text' => '<p class="meta-nav">' . $nathalie_mota_theme_next_label . $nathalie_mota_theme_next . '</p><p class="post-title">%title</p>',
            'prev_text' => '<p class="meta-nav">' . $nathalie_mota_theme_prev . $nathalie_mota_theme_previous_label . '</p><p class="post-title">%title</p>',
        )
    );
endwhile; // End of the loop.

get_footer();
