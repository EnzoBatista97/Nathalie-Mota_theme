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
        <div class="burger-menu">
            <div class="logo">
                <?php the_custom_logo(); ?>
            </div>


            <button class="burger-menu-btn" aria-label="Menu" aria-expanded="false">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icones/burger-menu.svg" alt="Menu Icon" class="burger-menu-icon">
            </button>

            <div class="main-menu" aria-label="Menu principal">
                <?php
                    wp_nav_menu(array(
                        'theme_location' => 'menu-principal-header',
                        'menu_id' => 'primary-menu-header',
                    ));
                ?>
            </div>
        </div>
    </nav>

    <?php get_template_part('template-parts/modal-contact');?>
