jQuery(document).ready(function($) {
    // Gestionnaire de clic sur le document
    $(document).on('click', function(event) {
        // Retirer la classe active du bouton du menu et du menu principal
        $('.burger-menu-btn').removeClass('active');
        $('.main-menu').removeClass('active');
    });

    // Gestionnaire de clic sur le bouton du menu burger
    $('.burger-menu-btn').click(function(event) {
        event.stopPropagation(); // Empêcher la propagation de l'événement pour éviter de fermer le menu immédiatement
        $(this).toggleClass('active');
        $('.main-menu').toggleClass('active');
    });
});
