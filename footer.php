<nav id="site-navigation-footer" class="main-navigation">
    <?php
        // Affichage du menu de navigation
        wp_nav_menu(array(
            'theme_location' => 'menu-principal-footer',
            'menu_id' => 'primary-menu-footer',
        ));
    ?>
    <!-- Texte des droits d'auteur -->
    <p class="rights-reserved">TOUS DROITS RÉSERVÉS</p>
</nav>

<!-- Script pour la lightbox -->
<script src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/js/lightbox.js"></script>

<!-- Overlay de la lightbox -->
<div id="lightbox-overlay">
    <!-- Boutons de navigation de la lightbox -->
    <span id="lightbox-prev" class="lightbox-navigation">&lt;</span>
    <span id="lightbox-next" class="lightbox-navigation">&gt;</span>
    <span id="lightbox-close" class="lightbox-navigation">X</span>
    
    <!-- Contenu de la lightbox -->
    <div id="lightbox-container">
        <!-- Image de la lightbox -->
        <img id="lightbox-image" src="" alt="Photo">
        
        <!-- Informations supplémentaires de la lightbox -->
        <div id="lightbox-info">
            <!-- Référence de la photo -->
            <div id="lightbox-reference"></div>
            <!-- Catégorie de la photo -->
            <div id="lightbox-category"></div>
        </div>
    </div>
</div>

</body>
</html>
