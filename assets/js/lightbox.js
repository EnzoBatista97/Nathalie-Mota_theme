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

        if (!lightboxPrev || !lightboxNext) {
            console.error('Elements not found. Aborting initLightbox.');
            return;
        }

        photoItems.forEach((photoItem, index) => {
            // Récupérer la légende de chaque photo
            const caption = photoItem.querySelector('.photo-image').alt;
            lightboxCaptions.push(caption); // Modifier la référence à la variable ici

            photoItem.addEventListener('click', () => openLightbox(index));
        });

        lightboxPrev.addEventListener('click', showPrevPhoto);
        lightboxNext.addEventListener('click', showNextPhoto);
    }

    function openLightbox(index) {
        currentPhotoIndex = index;
        updateLightboxContent();
        document.getElementById('lightbox-overlay').style.display = 'block';
    }

    function closeLightbox() {
        document.getElementById('lightbox-overlay').style.display = 'none';
    }

    function updateLightboxContent() {
        const lightboxImage = document.getElementById('lightbox-image');
        const lightboxInfo = document.getElementById('lightbox-info');

        lightboxImage.src = photoItems[currentPhotoIndex].querySelector('img').src;
        lightboxInfo.textContent = lightboxCaptions[currentPhotoIndex]; // Modifier la référence à la variable ici
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
