document.addEventListener('DOMContentLoaded', function () {
    const categoryFilter = document.getElementById('category-filter');
    const formatFilter = document.getElementById('format-filter');
    const dateFilter = document.getElementById('date-filter');

    if (!categoryFilter || !formatFilter || !dateFilter) {
        console.error('Éléments #category-filter et/ou #format-filter et/ou #date-filter introuvables. Assurez-vous que votre HTML est correct.');
        return;
    }

    categoryFilter.addEventListener('change', loadPhotos);
    formatFilter.addEventListener('change', loadPhotos);
    dateFilter.addEventListener('change', loadPhotos);

    const loadMoreButton = document.getElementById('load-more-button');
    let page = 2;

    loadMoreButton.addEventListener('click', loadMorePhotos);

    function loadPhotos() {
        const selectedCategory = categoryFilter.value;
        const selectedFormat = formatFilter.value;
        const selectedDate = dateFilter.value;

        console.log('Catégorie sélectionnée (avant envoi de la requête) :', selectedCategory);
        console.log('Format sélectionné (avant envoi de la requête) :', selectedFormat);
        console.log('Filtre de date sélectionné (avant envoi de la requête) :', selectedDate);

        const data = {
            action: 'load_photos_by_category_and_format',
            nonce: wpApiSettings.nonce,
            category: selectedCategory,
            format: selectedFormat,
            dateFilter: selectedDate,
        };

        if (!selectedCategory) {
            delete data.category;
        }

        if (!selectedFormat) {
            delete data.format;
        }

        if (!selectedDate) {
            delete data.dateFilter;
        }

        fetch(frontendajax.ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data),
        })
        .then(response => response.json())
        .then(response => handleFilterSuccess(response, selectedCategory, selectedFormat))
        .catch(error => handleFilterError(error));
    }

    function handleFilterError(error) {
        console.error('Erreur Ajax pour le filtre :', error);
    }

    function handleFilterSuccess(response, selectedCategory, selectedFormat) {
        console.log('Réponse Ajax réussie pour le filtre :', response);
        console.log('Catégorie sélectionnée :', selectedCategory);
        console.log('Format sélectionné :', selectedFormat);

        if (response.success) {
            if (response.data) {
                const newHtml = response.data;

                if (newHtml.trim() !== '') {
                    const photoListContainer = document.querySelector('.photo-list-section .photo-list-container');
                    photoListContainer.innerHTML = newHtml;
                } else {
                    console.error('La réponse Ajax a renvoyé un contenu vide.');
                }
            } else {
                console.error('La réponse Ajax ne contient pas de données valides.');
            }
        } else {
            console.error('Erreur lors du chargement des photos :', response.data);

            if (response.data && response.data.message) {
                console.error('Erreur côté serveur :', response.data.message);
            }
        }
    }

    function loadMorePhotos() {
        console.log('Tentative de chargement de plus de photos. Page actuelle :', page);
        const selectedCategory = categoryFilter.value;
        const selectedFormat = formatFilter.value;
        const selectedDate = dateFilter.value;
    
        const data = {
            action: 'load_more_photos',
            nonce: wpApiSettings.nonce,
            page: page,
            category: selectedCategory,
            format: selectedFormat,
            dateFilter: selectedDate,
        };
    
        fetch(frontendajax.ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data),
        })
        .then(response => response.json())
        .then(response => handleLoadMoreSuccess(response))
        .catch(error => handleLoadMoreError(error));
    }
    

    function handleLoadMoreSuccess(response) {
        console.log('Réponse Ajax réussie pour charger plus de photos :', response);

        if (response.success) {
            if (response.data) {
                const newHtml = response.data;

                if (newHtml.trim() !== '') {
                    const photoListContainer = document.querySelector('.photo-list-section .photo-list-container');
                    photoListContainer.innerHTML += newHtml;
                    page++;
                } else {
                    console.error('La réponse Ajax a renvoyé un contenu vide.');
                }
            } else {
                console.error('La réponse Ajax ne contient pas de données valides.');
            }
        } else {
            console.error('Erreur lors du chargement des photos :', response.data);

            if (response.data && response.data.message) {
                console.error('Erreur côté serveur :', response.data.message);
            }
        }
    }
});
