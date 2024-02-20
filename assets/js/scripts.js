document.addEventListener('DOMContentLoaded', function () {
    console.log('Le DOM est chargé.');

    // Déclaration des éléments modaux et des boutons pour ouvrir le modal
    const modal = document.getElementById('contact-modal');
    const overlay = document.getElementById('overlay');
    const closeModalButton = document.querySelector('.close-modal');
    let openModalButtons = [];

    // Récupération des boutons pour ouvrir le modal
    const headerButton = document.getElementById('menu-item-34');
    const photoButton = document.querySelector('.cta-contact-button');
    const photoReferenceField = document.getElementById('your-reference');

    // Vérification de l'existence des boutons et ajout aux boutons ouvrant le modal
    if (headerButton) {
        openModalButtons.push(headerButton);
        console.log('Bouton de contact trouvé dans le header.');
    }

    if (photoButton) {
        openModalButtons.push(photoButton);
        console.log('Bouton de contact trouvé sur la page de photo.');
    }

    // Ajout des gestionnaires d'événements si des boutons pour ouvrir le modal sont présents
    if (openModalButtons.length > 0) {
        console.log('Ajout des gestionnaires d\'événements.');

        // Gestion de l'ouverture du modal pour chaque bouton
        openModalButtons.forEach(button => {
            button.addEventListener('click', openModal);
            button.addEventListener('click', function() {
                console.log('Le bouton de contact a été cliqué !');
            });
        });

        // Gestion de la fermeture du modal
        closeModalButton.addEventListener('click', closeModal);

        // Fonction pour ouvrir le modal
        function openModal(event) {
            console.log('Ouverture de la modale');
            event.preventDefault();

            modal.style.display = 'block';
            overlay.style.display = 'flex';

            // Récupération de la référence si disponible et mise à jour du champ
            const reference = event.target.getAttribute('data-reference');
            if (photoReferenceField) {
                photoReferenceField.value = reference;
                console.log('Valeur du champ de référence:', photoReferenceField.value);
            }
        }

        // Fonction pour fermer le modal
        function closeModal() {
            console.log('Fermeture de la modale');
            modal.style.display = 'none';
            overlay.style.display = 'none';
        }

        // Gestion de la fermeture du modal en cliquant en dehors du modal
        window.addEventListener('click', closeOnOutsideClick);

        // Gestion de la fermeture du modal avec la touche Escape
        window.addEventListener('keydown', closeOnEscapeKey);

        function closeOnOutsideClick(event) {
            if (event.target === overlay) {
                console.log('Clic à l\'extérieur de la modale, fermeture de la modale.');
                closeModal();
            }
        }

        function closeOnEscapeKey(event) {
            if (event.key === 'Escape') {
                console.log('Appui sur la touche Escape, fermeture de la modale.');
                closeModal();
            }
        }
    } else {
        console.log('Aucun bouton de contact trouvé.');
    }
});
