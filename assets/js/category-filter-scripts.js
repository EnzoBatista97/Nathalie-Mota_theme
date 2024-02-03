document.addEventListener('DOMContentLoaded', function () {
    let categoryFilter = document.getElementById('category-filter');

    if (!categoryFilter) {
        console.error('Élément #category-filter introuvable. Assurez-vous que votre HTML est correct.');
        return;
    }

    categoryFilter.addEventListener('change', function () {
        let selectedCategory = categoryFilter.value;

        console.log('Catégorie sélectionnée (avant envoi de la requête) :', selectedCategory);

        if (!selectedCategory) {
            console.error('Aucune catégorie sélectionnée.');
            return;
        }

        const data = {
            action: 'load_photos_by_category',
            nonce: wpApiSettings.nonce,
            category: selectedCategory,
        };

        fetch(frontendajax.ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data),
        })
        .then(response => response.json())
        .then(response => handleCategoryFilterSuccess(response, selectedCategory))
        .catch(error => handleCategoryFilterError(error));

    });

    function handleCategoryFilterError(error) {
        console.error('Erreur Ajax pour le filtre de catégorie :', error);
    }

    function handleCategoryFilterSuccess(response, selectedCategory) {
        console.log('Réponse Ajax réussie pour le filtre de catégorie :', response);
        console.log('Catégorie sélectionnée :', selectedCategory);

        if (response.success) {
            if (response.data && response.data.html) {
                const newElements = document.createRange().createContextualFragment(response.data.html);
                const photoListContainer = document.querySelector('.photo-list-section .photo-list-container');

                console.log('Nouveaux éléments HTML :', newElements);

                photoListContainer.innerHTML = ''; // Supprime le contenu actuel
                photoListContainer.appendChild(newElements);
            } else {
                console.error('Erreur dans la structure de la réponse :', response);
            }
        } else {
            console.error('Erreur lors du chargement des photos par catégorie:', response.data);

            // Affichez le message d'erreur côté serveur dans la console
            if (response.data && response.data.message) {
                console.error('Erreur côté serveur :', response.data.message);
            }
        }
    }

    // ... Autres fonctionnalités

    const loadMoreButton = document.getElementById('load-more-button');
    let page = 2;

    // Gestionnaire d'événements pour charger plus de photos
    loadMoreButton.addEventListener('click', loadMorePhotos);

    // Fonction pour charger plus de photos via Ajax
    function loadMorePhotos() {
        console.log('Tentative de chargement de plus de photos. Page actuelle :', page);
        const data = {
            action: 'load_more_photos',
            nonce: wpApiSettings.nonce,
            page: page,
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

    // Fonction de succès pour charger plus de photos
    function handleCategoryFilterSuccess(response, selectedCategory) {
        console.log('Réponse Ajax réussie pour le filtre de catégorie :', response);
        console.log('Catégorie sélectionnée :', selectedCategory);
    
        if (response.success) {
            if (response.data) {
                const newHtml = response.data;
    
                if (newHtml.trim() !== '') { // Vérifiez si le HTML n'est pas vide
                    const photoListContainer = document.querySelector('.photo-list-section .photo-list-container');
    
                    // Ajouter les nouveaux éléments à la fin de la liste existante
                    photoListContainer.innerHTML = newHtml;
                } else {
                    console.error('La réponse Ajax a renvoyé un contenu vide.');
                }
            } else {
                console.error('La réponse Ajax ne contient pas de données valides.');
            }
        } else {
            console.error('Erreur lors du chargement des photos par catégorie:', response.data);
    
            // Affichez le message d'erreur côté serveur dans la console
            if (response.data && response.data.message) {
                console.error('Erreur côté serveur :', response.data.message);
            }
        }
    }
    

    // Fonction d'erreur lors du chargement de plus de photos
    function handleLoadMoreError(error) {
        console.error('Erreur lors du chargement des photos :', error);
    }
});
