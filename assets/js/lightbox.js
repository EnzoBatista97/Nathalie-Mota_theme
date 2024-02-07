(function() {
    document.addEventListener('DOMContentLoaded', function () {
        // Appeler la fonction pour initialiser la lightbox
        initLightbox();
    });

    const lightboxCaptions = []; // Déclarer le tableau pour les légendes des photos
    let currentPhotoIndex = 0; // Déclarer la variable pour l'index de la photo actuelle
    let photoItems; // Déclarer photoItems en dehors de la fonction initLightbox()

    function initLightbox() {
        photoItems = document.querySelectorAll('.photo-item');
        const lightboxPrev = document.getElementById('lightbox-prev');
        const lightboxNext = document.getElementById('lightbox-next');
        const lightboxCloseButton = document.getElementById('lightbox-close'); // Ajout du bouton de fermeture

        if (!lightboxPrev || !lightboxNext || !lightboxCloseButton) { // Modification ici pour inclure le bouton de fermeture
            console.error('Elements not found. Aborting initLightbox.');
            return;
        }

        // Ajout du gestionnaire d'événements au bouton de fermeture
        lightboxCloseButton.addEventListener('click', closeLightbox);

        photoItems.forEach((photoItem, index) => {
            // Récupérer la légende de chaque photo
            const caption = photoItem.querySelector('.photo-image').alt;
            lightboxCaptions.push(caption);

            // Ajouter un gestionnaire d'événement au clic sur l'image pour ouvrir la lightbox
            photoItem.addEventListener('click', (event) => openLightbox(index));

            // Ajouter un gestionnaire d'événement au clic sur l'icône plein écran
            const fullscreenIcon = photoItem.querySelector('.fullscreen-icon');
            if (fullscreenIcon) {
                fullscreenIcon.addEventListener('click', (event) => {
                    event.preventDefault(); // Empêcher le comportement par défaut du lien
                    // Trouver l'index de la photo correspondante
                    const index = Array.from(photoItems).indexOf(photoItem);
                    openLightbox(index);
                });
            }
        });

        lightboxPrev.addEventListener('click', showPrevPhoto);
        lightboxNext.addEventListener('click', showNextPhoto);

        // Ajout du gestionnaire d'événements pour la touche "Échap"
        document.addEventListener('keyup', function(event) {
            if (event.key === "Escape") {
                closeLightbox();
            }
        });
    }


    function openLightbox(index) {
        console.log('Opening lightbox for index:', index);
        currentPhotoIndex = index;
        updateLightboxContent();
        document.getElementById('lightbox-overlay').style.display = 'block';
    }

    function closeLightbox() {
        console.log('Closing lightbox');
        document.getElementById('lightbox-overlay').style.display = 'none';
    }

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
    
    
    
    

    function showPrevPhoto() {
        if (currentPhotoIndex > 0) {
            currentPhotoIndex--;
            updateLightboxContent();
        }
    }

    function showNextPhoto() {
        if (currentPhotoIndex < photoItems.length - 1) {
            currentPhotoIndex++;
            updateLightboxContent();
        }
    }
})();
