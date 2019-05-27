// En Javascript

// Attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', () => {
    // Selection du bouton
    const btn = document.getElementById('roll');

    // Ecouter le clic sur le bouton
    btn.addEventListener('click', () => {
        // Sélectionner le paragraphe
        const p = document.querySelector('#random');

        // Demander au serveur un nombre aléatoire

        // ---- Envoi de la requête AJAX au serveur
        fetch('/rand', {
            headers: {
                // On ajoute ce header pour que Symfony reconnaisse
                // que c'est une requête AJAX.
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        // ---- Gestion de la réponse du serveur
        .then((response) => {
            return response.text();
        })
        .then((text) => {
            // Définir le texte dans le paragraphe
            p.textContent = text;
        })
        .catch(() => { console.log('ERREUR'); })



    })
});

// jQuery

// Attendre que le DOM soit chargé
// $(() => {
//     // Selection du bouton
//     const btn = $('#roll');
//     // Ecouter le clic sur le bouton
//     btn.on('click', () => {
//         // Sélectionner le paragraphe
//         const p = $('#random');
//         // Incrémenter le texte dans le paragraphe
//         const current = parseInt(p.text());
//         p.text(current + 1);
//     })
// });