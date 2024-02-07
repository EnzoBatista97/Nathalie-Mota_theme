<nav id="site-navigation-footer" class="main-navigation">
	<?php
		wp_nav_menu(array(
			'theme_location' => 'menu-principal-footer',
			'menu_id' => 'primary-menu-footer',
		));
	?>
	<p class="rights-reserved">TOUS DROITS RÉSERVÉS</p>
</nav>

<!-- Rétablissez le lien vers lightbox.js -->
<script src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/js/lightbox.js"></script>

<div id="lightbox-overlay">
    <span id="lightbox-prev" class="lightbox-navigation">&lt;</span>
    <span id="lightbox-next" class="lightbox-navigation">&gt;</span>
    <span id="lightbox-close" class="lightbox-navigation">X</span>
    <div id="lightbox-container">
        <img id="lightbox-image" src="" alt="Photo">
        <div id="lightbox-info">
            <div id="lightbox-reference"></div>
            <div id="lightbox-category"></div>
        </div>
    </div>
</div>




</body>
</html>

