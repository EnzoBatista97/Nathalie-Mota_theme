document.addEventListener('DOMContentLoaded', function () {
    // Sélectionnez la modale et le bouton de fermeture
    var modal = document.getElementById('contact-modal');
    var closeModalButton = document.querySelector('.close-modal');

    // Sélectionnez le bouton qui ouvre la modale depuis le header (par exemple)
    var openModalButton = document.getElementById('menu-item-34');

    // Sélectionne le bouton de contact dans le contenu
    var ctaContactButton = document.querySelector('.cta-contact-button');

    // Fonction pour ouvrir la modale
    function openModal() {
        console.log('Ouverture de la modale');
        modal.style.display = 'block';
    }

    // Fonction pour fermer la modale
    function closeModal() {
        console.log('Fermeture de la modale');
        modal.style.display = 'none';
    }

    // Ajoutez un écouteur d'événement pour ouvrir la modale depuis le header
    openModalButton.addEventListener('click', openModal);

    // Ajoutez un écouteur d'événement pour ouvrir la modale depuis le contenu
    ctaContactButton.addEventListener('click', openModal);

    // Ajoutez un écouteur d'événement pour fermer la modale en cliquant sur le bouton de fermeture
    closeModalButton.addEventListener('click', closeModal);

    // Ajoutez un écouteur d'événement pour fermer la modale en cliquant en dehors de celle-ci
    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    // Ajoutez un écouteur d'événement pour fermer la modale en appuyant sur la touche Escape
    window.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
});
