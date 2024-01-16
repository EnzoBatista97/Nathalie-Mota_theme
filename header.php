<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php bloginfo('name'); ?><?php wp_title('|'); ?></title>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <?php wp_head(); ?>

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
    </nav><!-- #site-navigation-header -->

