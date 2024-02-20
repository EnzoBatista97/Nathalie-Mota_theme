(function() {
    document.addEventListener('DOMContentLoaded', initLightbox);

    // Déclaration des variables et constantes
    const lightboxCaptions = [];
    let currentPhotoIndex = 0;
    let photoItems;

    // Fonction d'initialisation du lightbox
    function initLightbox() {
        photoItems = document.querySelectorAll('.photo-item');
        const lightboxPrev = document.getElementById('lightbox-prev');
        const lightboxNext = document.getElementById('lightbox-next');
        const lightboxCloseButton = document.getElementById('lightbox-close');

        // Vérification de l'existence des éléments nécessaires
        if (!lightboxPrev || !lightboxNext || !lightboxCloseButton) {
            console.error('Elements not found. Aborting initLightbox.');
            return;
        }

        // Ajout des écouteurs d'événements
        lightboxCloseButton.addEventListener('click', closeLightbox);
        photoItems.forEach((photoItem, index) => {
            const caption = photoItem.querySelector('.photo-image').alt;
            lightboxCaptions.push(caption);

            // Ouverture du lightbox lors du clic sur une photo
            photoItem.addEventListener('click', (event) => openLightbox(index));

            // Ouverture du lightbox lors du clic sur l'icône plein écran
            const fullscreenIcon = photoItem.querySelector('.fullscreen-icon');
            if (fullscreenIcon) {
                fullscreenIcon.addEventListener('click', (event) => {
                    event.preventDefault();
                    const index = Array.from(photoItems).indexOf(photoItem);
                    openLightbox(index);
                });
            }
        });

        // Navigation dans le lightbox avec les boutons précédent et suivant
        lightboxPrev.addEventListener('click', showPrevPhoto);
        lightboxNext.addEventListener('click', showNextPhoto);

        // Fermeture du lightbox avec la touche Escape
        document.addEventListener('keyup', function(event) {
            if (event.key === "Escape") {
                closeLightbox();
            }
        });
    }

    // Ouverture du lightbox pour une photo donnée
    function openLightbox(index) {
        console.log('Opening lightbox for index:', index);
        currentPhotoIndex = index;
        updateLightboxContent();
        document.getElementById('lightbox-overlay').style.display = 'block';
    }

    // Fermeture du lightbox
    function closeLightbox() {
        console.log('Closing lightbox');
        document.getElementById('lightbox-overlay').style.display = 'none';
    }

    // Mise à jour du contenu du lightbox
    function updateLightboxContent() {
        const lightboxImage = document.getElementById('lightbox-image');
        const lightboxReference = document.getElementById('lightbox-reference');
        const lightboxCategory = document.getElementById('lightbox-category');
    
        const photoItem = photoItems[currentPhotoIndex];
        const reference = photoItem.querySelector('.photo-reference').textContent;
        const category = photoItem.querySelector('.photo-category').textContent;
    
        lightboxImage.src = photoItem.querySelector('img').src;
        lightboxReference.textContent = reference;
        lightboxCategory.textContent = category;
    }

    // Affichage de la photo précédente dans le lightbox
    function showPrevPhoto() {
        if (currentPhotoIndex > 0) {
            currentPhotoIndex--;
            updateLightboxContent();
        }
    }

    // Affichage de la photo suivante dans le lightbox
    function showNextPhoto() {
        if (currentPhotoIndex < photoItems.length - 1) {
            currentPhotoIndex++;
            updateLightboxContent();
        }
    }
})();
