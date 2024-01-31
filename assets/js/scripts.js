document.addEventListener('DOMContentLoaded', function () {
    // Récupération des éléments du DOM
    const modal = document.getElementById('contact-modal');
    const closeModalButton = document.querySelector('.close-modal');
    const openModalButton = document.getElementById('menu-item-34');
    const loadMoreButton = document.getElementById('load-more-button');
    let page = 2;

    // Ajout des gestionnaires d'événements
    openModalButton.addEventListener('click', () => openModal());
    closeModalButton.addEventListener('click', closeModal);
    loadMoreButton.addEventListener('click', loadMorePhotos);

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

    // Fonction pour charger plus de photos via Ajax
    function loadMorePhotos() {
        console.log('Tentative de chargement de plus de photos. Page actuelle :', page);
        const data = {
            action: 'load_more_photos',
            nonce: wpApiSettings.nonce,
            page: page,
        };
    
        jQuery.ajax({
            type: 'POST',
            url: frontendajax.ajaxurl,
            data: data,
            success: function (response) {
                console.log('Réponse Ajax réussie :', response);
                handleLoadMoreSuccess(response);
            },
            error: function (error) {
                console.error('Erreur lors du chargement des photos :', error);
                handleLoadMoreError(error);
            },
        });
    }
    
    function handleLoadMoreSuccess(response) {
        console.log('Réponse Ajax réussie :', response);
    
        const newElements = jQuery.parseHTML(response.data);
        const photoListContainer = jQuery('.photo-list-container');
        
        // Ajouter les nouveaux éléments à la fin de la liste existante
        photoListContainer.append(newElements);
    
        page++;
    }
    
    function handleLoadMoreError(error) {
        console.error('Erreur lors du chargement des photos :', error);
    }

    // Gestionnaires d'événements pour fermer la modale au clic à l'extérieur ou en appuyant sur Escape
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });

    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeModal();
        }
    });

    // Gestionnaire d'événements pour charger plus de photos
    loadMoreButton.addEventListener('click', () => {
        const data = {
            action: 'load_more_photos',
            page: page,
        };

        jQuery.ajax({
            type: 'POST',
            url: frontendajax.ajaxurl,
            data: data,
            success: (response) => {
                jQuery('.photo-list-container').append(response);
                page++;
            },
            error: (error) => {
                console.error('Erreur lors du chargement des photos', error);
            },
        });
    });

    // Fonction principale exécutée lorsque le document est prêt
    jQuery(document).ready(function ($) {
        // Fonction pour charger les catégories depuis l'API WordPress
        function loadCategories() {
            jQuery.ajax({
                url: `${wpApiSettings.root}wp/v2/categorie`,
                method: 'GET',
                beforeSend: (xhr) => xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce),
                success: (response) => {
                    const categorieSelect = document.getElementById('category-filter');
                    categorieSelect.innerHTML = '<option value="">Toutes</option>';
                    response.forEach((categorie) => {
                        categorieSelect.innerHTML += `<option value="${categorie.id}">${categorie.name}</option>`;
                    });
                },
                error: (error) => console.error('Erreur lors du chargement des catégories', error),
            });
        }

        // Fonction pour charger les formats depuis l'API WordPress
        function loadFormats() {
            jQuery.ajax({
                url: `${wpApiSettings.root}wp/v2/format`,
                method: 'GET',
                beforeSend: (xhr) => xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce),
                success: (response) => {
                    const formatSelect = document.getElementById('format-filter');
                    formatSelect.innerHTML = '<option value="">Tous</option>';
                    response.forEach((format) => {
                        formatSelect.innerHTML += `<option value="${format.id}">${format.name}</option>`;
                    });
                },
                error: (error) => console.error('Erreur lors du chargement des formats', error),
            });
        }

        // Fonction pour charger les photos en fonction des filtres sélectionnés
        function loadPhotos() {
            console.log('Chargement de photos en fonction des filtres sélectionnés.');
            const selectedCategorie = document.getElementById('categorie-filter');
            const selectedFormat = document.getElementById('format-filter');
            const sortOrder = document.getElementById('sort-order');

            if (selectedCategorie && selectedFormat && sortOrder) {
                const selectedCategorieValue = selectedCategorie.value;
                const selectedFormatValue = selectedFormat.value;
                const sortOrderValue = sortOrder.value;

                console.log('Filtres sélectionnés :', {
                    categorie: selectedCategorieValue,
                    format: selectedFormatValue,
                    order: sortOrderValue,
                });

                jQuery.ajax({
                    url: `${wpApiSettings.root}wp/v2/photo`,
                    method: 'GET',
                    data: {
                        categorie: selectedCategorieValue || '',
                        format: selectedFormatValue || '',
                        order: sortOrderValue || 'desc',
                    },
                    beforeSend: (xhr) => xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce),
                    success: (response) => {
                        console.log('Réponse Ajax réussie pour le chargement de photos :', response);
                        const photoListContainer = document.querySelector('.photo-list-container');
                        if (photoListContainer) {
                            photoListContainer.innerHTML = '';
                            response.forEach((photo) => {
                                const title = photo.title.rendered;
                                const link = photo.link;
                                const categories = photo.categorie;

                                const photoHTML = `
                                    <div class="photo-item">
                                        <img src="${photo.source_url}" alt="${title}" class="photo-image">
                                        <div class="photo-overlay">
                                            <p>Titre: ${title}</p>
                                            <p>Lien: <a href="${link}">${link}</a></p>
                                            <p>Catégorie: ${categories.join(', ')}</p>
                                        </div>
                                    </div>`;

                                photoListContainer.innerHTML += photoHTML;
                            });
                        }
                    },
                    error: (xhr, status, error) => console.error('Erreur lors du chargement des photos :', error),
                });
            }
        }

        // Chargement initial des catégories, formats et photos
        loadCategories();
        loadFormats();
        loadPhotos();

        // Gestionnaire d'événements pour mettre à jour les photos lors du changement de filtres
        $('#categorie-filter, #format-filter, #sort-order').change(() => {
            loadPhotos();
        });
    });
});
