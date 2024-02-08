(function() {
    document.addEventListener('DOMContentLoaded', initLightbox);

    const lightboxCaptions = [];
    let currentPhotoIndex = 0;
    let photoItems;

    function initLightbox() {
        photoItems = document.querySelectorAll('.photo-item');
        const lightboxPrev = document.getElementById('lightbox-prev');
        const lightboxNext = document.getElementById('lightbox-next');
        const lightboxCloseButton = document.getElementById('lightbox-close');

        if (!lightboxPrev || !lightboxNext || !lightboxCloseButton) {
            console.error('Elements not found. Aborting initLightbox.');
            return;
        }

        lightboxCloseButton.addEventListener('click', closeLightbox);

        photoItems.forEach((photoItem, index) => {
            const caption = photoItem.querySelector('.photo-image').alt;
            lightboxCaptions.push(caption);

            photoItem.addEventListener('click', (event) => openLightbox(index));

            const fullscreenIcon = photoItem.querySelector('.fullscreen-icon');
            if (fullscreenIcon) {
                fullscreenIcon.addEventListener('click', (event) => {
                    event.preventDefault();
                    const index = Array.from(photoItems).indexOf(photoItem);
                    openLightbox(index);
                });
            }
        });

        lightboxPrev.addEventListener('click', showPrevPhoto);
        lightboxNext.addEventListener('click', showNextPhoto);

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
