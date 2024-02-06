<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php bloginfo('name'); ?><?php wp_title('|'); ?></title>
    <?php wp_head(); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body <?php body_class(); ?>>

    <nav id="site-navigation-header" class="main-navigation">
        <div class="logo">
            <?php the_custom_logo(); ?>
        </div>

        <?php
        wp_nav_menu(array(
            'theme_location' => 'menu-principal-header',
            'menu_id' => 'primary-menu-header',
        ));
        ?>
    </nav>

    <?php get_template_part('template-parts/modal-contact');?>
