document.addEventListener('DOMContentLoaded', function initialize() {
    //Déclaration des constantes et variables
    const categoryDropdown = document.getElementById('category-dropdown');
    const formatDropdown = document.getElementById('format-dropdown');
    const dateDropdown = document.getElementById('date-dropdown');
    const loadMoreButton = document.getElementById('load-more-button');
    let selectedCategory = '';
    let selectedFormat = '';
    let selectedDate = '';
    let page = 1;

    // Fonction de changement de filtre
    function handleFilterChange(selectedValue, dropdownType) {
        switch(dropdownType) {
            case 'category':
                selectedCategory = selectedValue;
                break;
            case 'format':
                selectedFormat = selectedValue;
                break;
            case 'date':
                selectedDate = selectedValue;
                break;
        }
        page = 1;
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

// Fonction pour gérer les états spécifiques de l'élément sélectionné
function handleDropdownItemStates(dropdownList, dropdownType) {
    const dropdownToggle = dropdownList.parentElement.querySelector('.dropdown-toggle');

    dropdownList.querySelectorAll('li').forEach(item => {
        // Gestion de l'effet au survol
        item.addEventListener('mouseenter', function() {
            item.style.backgroundColor = '#FFD6D6'; // background lors du survol
        });
        item.addEventListener('mouseleave', function() {
            item.style.backgroundColor = ''; // Retour au background par défaut
        });

        // Gestion de l'effet lors du clic
        item.addEventListener('mousedown', function() {
            item.style.backgroundColor = '#FE5858'; // background lors du clic
        });
        item.addEventListener('mouseup', function() {
            item.style.backgroundColor = ''; // Retour au background par défaut après le clic
        });

        // Gestion de la sélection
        item.addEventListener('click', function() {
            const selectedValue = item.getAttribute('data-value');
            const selectedLabel = item.getAttribute('data-label'); // Récupérer la valeur de l'attribut data-label
            dropdownToggle.textContent = selectedLabel; // Afficher le label dans le rectangle
            handleFilterChange(selectedValue, dropdownType);
            dropdownList.querySelectorAll('li').forEach(item => {
                item.classList.remove('selected'); // Supprimer la classe 'selected' de tous les éléments
            });
            item.classList.add('selected'); // Ajouter la classe 'selected' à l'élément sélectionné
            dropdownList.classList.remove('show');
        });
    });
}




    // Fonction pour créer les dropdowns personnalisés
    function createCustomDropdown(dropdownElement, dropdownType) {
        const dropdownToggle = dropdownElement.querySelector('.dropdown-toggle');
        const dropdownList = dropdownElement.querySelector('.dropdown-list');

        dropdownToggle.addEventListener('click', function() {
            dropdownList.classList.toggle('show');
        });

        handleDropdownItemStates(dropdownList, dropdownType); // Appeler la fonction pour gérer les états spécifiques des éléments

        // Fermer la dropdown si on clique en dehors
        document.addEventListener('click', function(e) {
            if (!dropdownElement.contains(e.target)) {
                dropdownList.classList.remove('show');
            }
        });
    }

    createCustomDropdown(categoryDropdown, 'category');
    createCustomDropdown(formatDropdown, 'format');
    createCustomDropdown(dateDropdown, 'date');

    loadMoreButton.addEventListener('click', handleLoadPhotos);
});
