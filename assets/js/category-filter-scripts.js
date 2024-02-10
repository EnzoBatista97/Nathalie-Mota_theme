document.addEventListener('DOMContentLoaded', function initialize() {
    const categoryFilter = document.getElementById('category-filter');
    const formatFilter = document.getElementById('format-filter');
    const dateFilter = document.getElementById('date-filter');
    const loadMoreButton = document.getElementById('load-more-button');
    let selectedCategory = categoryFilter.value;
    let selectedFormat = formatFilter.value;
    let selectedDate = dateFilter.value;
    let page = 2;

    if (!categoryFilter || !formatFilter || !dateFilter) {
        console.error('Éléments #category-filter et/ou #format-filter et/ou #date-filter introuvables. Assurez-vous que votre HTML est correct.');
        return;
    }

    categoryFilter.addEventListener('change', handleFilterChange);
    formatFilter.addEventListener('change', handleFilterChange);
    dateFilter.addEventListener('change', handleFilterChange);
    loadMoreButton.addEventListener('click', handleLoadPhotos);

    function handleFilterChange() {
        selectedCategory = categoryFilter.value;
        selectedFormat = formatFilter.value;
        selectedDate = dateFilter.value;
        page = 2;
        loadPhotosByCategoryAndFormat();
    }

    function loadPhotosByCategoryAndFormat() {
        const data = {
            action: 'load_photos_by_category_and_format',
            nonce: wpApiSettings.nonce,
            category: selectedCategory || '',
            dateFilter: selectedDate,
            page: 1,
        };

        if (selectedFormat.trim() !== '') {
            data.format = selectedFormat;
        }

        fetch(frontendajax.ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data),
        })
        .then(response => response.json())
        .then(handleFilterSuccess)
        .catch(handleFilterError);
    }

    function handleFilterError(error) {
        console.error('Erreur Ajax pour le filtre :', error);
    }

    function handleFilterSuccess(response) {
        if (response.success) {
            if (response.data) {
                const newHtml = response.data;
                const photoListContainer = document.querySelector('.photo-list-section .photo-list-container');
                photoListContainer.innerHTML = newHtml.trim() !== '' ? newHtml : console.error('La réponse Ajax a renvoyé un contenu vide.');
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

    function handleLoadPhotos() {
        const data = {
            action: 'load_more_photos',
            nonce: wpApiSettings.nonce,
            page: page,
            category: selectedCategory,
            dateFilter: selectedDate,
        };

        if (selectedFormat.trim() !== '') {
            data.format = selectedFormat;
        }

        fetch(frontendajax.ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data),
        })
        .then(response => response.json())
        .then(handleLoadMoreSuccess)
        .catch(handleLoadMoreError);
    }

    function handleLoadMoreSuccess(response) {
        if (response.success && response.data) {
            const newHtml = response.data;
            const photoListContainer = document.querySelector('.photo-list-section .photo-list-container');
            photoListContainer.innerHTML += newHtml.trim() !== '' ? newHtml : console.error('La réponse Ajax a renvoyé un contenu vide.');
            page++;
        } else {
            console.error('Erreur lors du chargement des photos :', response.data);
            if (response.data && response.data.message) {
                console.error('Erreur côté serveur :', response.data.message);
            }
        }
    }

    function handleLoadMoreError(error) {
        console.error('Erreur lors du chargement supplémentaire de photos :', error);
    }
});
