jQuery(document).ready(function($) {
    $('.burger-menu-btn').click(function() {
        $(this).toggleClass('active');
        $('.main-menu').toggleClass('active');
    });
});
