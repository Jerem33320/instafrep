// En Javascript

// Attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', () => {

    const alertBox = document.getElementById('alert');

    // Selection du bouton
    const btn = document.getElementById('roll');

    // Ecouter le clic sur le bouton
    btn.addEventListener('click', () => {

        // masquer l'alerte
        alertBox.style.display = 'none';

        // Sélectionner le paragraphe
        const p = document.querySelector('#random');

        btn.disabled = true;
        btn.classList.add('is-loading');

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
            if (response.status >= 200 && response.status < 300) {
                return response.text();
            }

            throw new Error(response.statusText);
        })
        .then((text) => {
            // Définir le texte dans le paragraphe
            p.textContent = text;

            // Débloquer le bouton
            btn.disabled = false;
            btn.classList.remove('is-loading');
        })
        .catch((err) => {
            // Débloquer le bouton
            btn.disabled = false;
            btn.classList.remove('is-loading');

            // Afficher l'alerte d'erreur
            alertBox.querySelector('.error').textContent = err.message
            alertBox.style.display = 'block';
        })



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