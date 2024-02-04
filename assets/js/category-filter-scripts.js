document.addEventListener('DOMContentLoaded', function () {
    let categoryFilter = document.getElementById('category-filter');
    let formatFilter = document.getElementById('format-filter');
    let dateFilter = document.getElementById('date-filter'); // Ajoutez cette ligne

    if (!categoryFilter || !formatFilter || !dateFilter) { // Mettez à jour cette ligne
        console.error('Éléments #category-filter et/ou #format-filter et/ou #date-filter introuvables. Assurez-vous que votre HTML est correct.');
        return;
    }

    categoryFilter.addEventListener('change', function () {
        loadPhotos(); // Appel de la fonction commune pour charger les photos
    });

    formatFilter.addEventListener('change', function () {
        loadPhotos(); // Appel de la fonction commune pour charger les photos
    });

    dateFilter.addEventListener('change', function () { // Ajoutez cette ligne
        loadPhotos(); // Ajoutez cette ligne
    });

    function loadPhotos() {
        let selectedCategory = categoryFilter.value;
        let selectedFormat = formatFilter.value;
        let selectedDate = dateFilter.value;
    
        // Ajoutez des logs pour vérifier les valeurs sélectionnées
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
    
        // Ajoutez des vérifications pour exclure des paramètres vides ou non définis
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
    
        // Ajoutez des messages de débogage ici...
    
        if (response.success) {
            if (response.data) {
                const newHtml = response.data;
    
                if (newHtml.trim() !== '') { // Vérifiez si le HTML n'est pas vide
                    const photoListContainer = document.querySelector('.photo-list-section .photo-list-container');
    
                    // Ajouter les nouveaux éléments à la fin de la liste existante
                    photoListContainer.innerHTML = newHtml;  // Utilisation de '=' au lieu de '+=' pour remplacer le contenu
                } else {
                    console.error('La réponse Ajax a renvoyé un contenu vide.');
                }
            } else {
                console.error('La réponse Ajax ne contient pas de données valides.');
            }
        } else {
            console.error('Erreur lors du chargement des photos :', response.data);
    
            // Affichez le message d'erreur côté serveur dans la console
            if (response.data && response.data.message) {
                console.error('Erreur côté serveur :', response.data.message);
            }
        }
    }
    

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
    function handleLoadMoreSuccess(response) {
        console.log('Réponse Ajax réussie pour charger plus de photos :', response);

        // Ajoutez des messages de débogage ici...

        if (response.success) {
            if (response.data) {
                const newHtml = response.data;

                if (newHtml.trim() !== '') { // Vérifiez si le HTML n'est pas vide
                    const photoListContainer = document.querySelector('.photo-list-section .photo-list-container');

                    // Ajouter les nouveaux éléments à la fin de la liste existante
                    photoListContainer.innerHTML += newHtml;  // Utilisation de '+=' pour ajouter au lieu de remplacer
                    page++;
                } else {
                    console.error('La réponse Ajax a renvoyé un contenu vide.');
                }
            } else {
                console.error('La réponse Ajax ne contient pas de données valides.');
            }
        } else {
            console.error('Erreur lors du chargement des photos :', response.data);

            // Affichez le message d'erreur côté serveur dans la console
            if (response.data && response.data.message) {
                console.error('Erreur côté serveur :', response.data.message);
            }
        }
    }
});
