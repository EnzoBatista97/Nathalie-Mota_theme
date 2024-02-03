document.addEventListener('DOMContentLoaded', function () {
    // Récupération des éléments du DOM
    const modal = document.getElementById('contact-modal');
    const closeModalButton = document.querySelector('.close-modal');
    const openModalButton = document.getElementById('menu-item-34');
    //const loadMoreButton = document.getElementById('load-more-button');
    let page = 2;

    // Récupération de l'élément du filtre de catégorie
    //const categoryFilter = document.getElementById('category-filter');

    // Gestionnaire d'évènements pour le changement de catégorie
    //categoryFilter.addEventListener('change', function(){
        // Réinitialiser la page à 2 lorsqu'une nouvelle catégorie est sélectionnée
        //page = 2;

        // Récupérer la valeur de la catégorie sélectionnée
        //const selectedCategory = categoryFilter.value;

        // Effectuer une nouvelle requête Ajex en utilisant la catégories sélectionnée
       // const data = {
            //action: 'load_more_photos',
            //nonce: wpApiSettings.nonce,
            //page: page,
            //category: selectedCategory, //Ajout de la catégoie sélectionnée aux données de la requête
        //};

        //jQuery.ajax({
           // type: 'POST',
           // url: frontendajax.ajaxurl,
            //data: data,
            //success: handleLoadMoreSuccess,
            //error: handleLoadMoreError,
        //});
    //});

    // Ajout des gestionnaires d'événements
    openModalButton.addEventListener('click', openModal);
    closeModalButton.addEventListener('click', closeModal);
    //loadMoreButton.addEventListener('click', loadMorePhotos);

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
    /*function loadMorePhotos() {
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
            success: handleLoadMoreSuccess,
            error: handleLoadMoreError,
        });
    }

    // Fonction de succès pour charger plus de photos
    function handleLoadMoreSuccess(response) {
        console.log('Réponse Ajax réussie :', response);

        const newElements = jQuery.parseHTML(response.data);
        const photoListContainer = jQuery('.photo-list-container');

        // Vérifier le contenu de la réponse
        console.log('Contenu de la réponse :', response.data);

        // Vider la liste existante avant d'ajouter les nouvelles photos
        //photoListContainer.empty();

        // Ajouter les nouveaux éléments à la fin de la liste existante
        photoListContainer.append(newElements);

        page++;
    }

    // Fonction d'erreur lors du chargement de plus de photos
    function handleLoadMoreError(error) {
        console.error('Erreur lors du chargement des photos :', error);
    }*/

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
    /*loadMoreButton.addEventListener('click', () => {
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
    });*/
});