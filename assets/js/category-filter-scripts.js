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

    // Ajouter les écouteurs d'événements pour les filtres
    categoryFilter.addEventListener('change', handleFilterChange);
    formatFilter.addEventListener('change', handleFilterChange);
    dateFilter.addEventListener('change', handleFilterChange);
    loadMoreButton.addEventListener('click', handleLoadPhotos);

    // Fonction de changement de filtre
    function handleFilterChange() {
        selectedCategory = categoryFilter.value;
        selectedFormat = formatFilter.value;
        selectedDate = dateFilter.value;
        page = 2;
        console.log('Filtre changé :', selectedCategory, selectedFormat, selectedDate);
        loadPhotosByCategoryAndFormat();
    }

    // Fonction de chargement des photos en fonction des filtres
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

        console.log('Requête pour charger les photos :', data);

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

    // Fonction de gestion du succès du chargement des photos
    function handleFilterSuccess(response) {
        console.log('Réponse de chargement des photos :', response);
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

    // Fonction de gestion des erreurs de chargement des photos
    function handleFilterError(error) {
        console.error('Erreur Ajax pour le filtre :', error);
    }

    // Fonction de chargement supplémentaire de photos
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

        console.log('Requête pour charger plus de photos :', data);

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

    // Fonction de gestion du succès du chargement supplémentaire de photos
    function handleLoadMoreSuccess(response) {
        console.log('Réponse de chargement supplémentaire de photos :', response);
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

    // Fonction de gestion des erreurs du chargement supplémentaire de photos
    function handleLoadMoreError(error) {
        console.error('Erreur lors du chargement supplémentaire de photos :', error);
    }

    // Fonction pour attacher les écouteurs d'événements aux options des sélecteurs
    function attachEventListenersToOptions() {
        document.querySelectorAll('.filter-select option').forEach(option => {
            option.addEventListener('mouseover', handleOptionHover);
            option.addEventListener('mouseout', handleOptionHover);
            option.addEventListener('click', handleOptionActive);
        });
    }

    // Appeler la fonction pour attacher les écouteurs d'événements aux options
    attachEventListenersToOptions();

    // Fonction pour gérer le style au survol des options
    function handleOptionHover(event) {
        // Ajouter ou supprimer la classe 'hover' en fonction de l'état hover
        event.target.classList.toggle('hover', event.type === 'mouseover');
    }

    // Fonction pour gérer le style lors de la sélection d'une option
    function handleOptionActive(event) {
        // Ajouter la classe 'selected' à l'option sélectionnée lors de la pression
        document.querySelectorAll('.filter-select option').forEach(option => {
            option.classList.remove('selected');
        });
        event.target.classList.add('selected');
    }
});
