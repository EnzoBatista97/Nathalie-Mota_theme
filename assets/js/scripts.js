document.addEventListener('DOMContentLoaded', function () {
    console.log('Le DOM est chargé.');

    const modal = document.getElementById('contact-modal');
    const overlay = document.getElementById('overlay');
    const closeModalButton = document.querySelector('.close-modal');
    let openModalButtons = [];

    const headerButton = document.getElementById('menu-item-34');
    const photoButton = document.querySelector('.cta-contact-button');
    const photoReferenceField = document.getElementById('your-reference');

    console.log('headerButton:', headerButton);
    console.log('photoButton:', photoButton);

    if (headerButton) {
        openModalButtons.push(headerButton);
        console.log('Bouton de contact trouvé dans le header.');
    }

    if (photoButton) {
        openModalButtons.push(photoButton);
        console.log('Bouton de contact trouvé sur la page de photo.');
    }

    if (openModalButtons.length > 0) {
        console.log('Ajout des gestionnaires d\'événements.');

        openModalButtons.forEach(button => {
            button.addEventListener('click', openModal);
            button.addEventListener('click', function() {
                console.log('Le bouton de contact a été cliqué !');
            });
        });

        closeModalButton.addEventListener('click', closeModal);

        function openModal(event) {
            console.log('Ouverture de la modale');
            event.preventDefault();

            modal.style.display = 'block';
            overlay.style.display = 'flex';

            const reference = event.target.getAttribute('data-reference');

            if (photoReferenceField) {
                photoReferenceField.value = reference;
                console.log('Valeur du champ de référence:', photoReferenceField.value);
            }
        }

        function closeModal() {
            console.log('Fermeture de la modale');
            modal.style.display = 'none';
            overlay.style.display = 'none';
        }

        window.addEventListener('click', closeOnOutsideClick);
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
