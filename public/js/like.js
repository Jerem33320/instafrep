$(function() {

    // flag
    let isPending = false;

    // On écoute le clic sur la liste entière, plutôt que sur chacun des liens :
    // 1. c'est plus performant, car il n'y a qu'un seul écouteur d'évenement
    // 2. lorsque des posts seront ajoutés dynamiquement dans la liste, les clics seront automatiquement
    //    pris en compte, sans ajouter de nouvel écouteur.
    $('#post-list').on('click', (evt) => {

        // On veut sélectionner le lien <a> ayant la class "like".
        // Mais on a peut être cliqué sur un de ses enfants,
        // donc on remonte les éléments parents jusqu'a tomber
        // (éventuellement !) sur le lien <a class="like">
        $link = $(evt.target).closest('.like');

        // Si le lien est undefined, c'est qu'on a cliqué ailleurs,
        // donc on sort du gestionnaire de clic
        if (!$link.length) return;

        // empêche le navigateur de suivre le lien
        // (et donc de recharger la page)
        evt.preventDefault();

        // Si une requete AJAX est déjà en cours, on ignore le clic
        if (isPending) return false;
        isPending = true;

        const url = $link.attr('href');

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
            .then((response) => {

                // Récupère la valeur actuelle du compteur
                const $counter = $link.next().find('strong');
                const currentCount = parseInt($counter.text());

                if (response.status === 201) { // si un like a été créé

                    $link
                        .attr('href', url.replace('like', 'unlike'))
                        .find('i')
                        .removeClass('far')
                        .addClass('fas')
                        .addClass('liked');

                    $counter.text(currentCount + 1);

                }
                else if (response.status === 204) { // si un like a été supprimé

                    $link
                        .attr('href', url.replace('unlike', 'like'))
                        .find('i')
                        .addClass('far')
                        .removeClass('fas')
                        .removeClass('liked');

                    $counter.text(currentCount - 1);
                }
                else {
                    return response.text().then((message) => {
                        throw new Error(message || response.statusText || 'Invalid response code : ' + response.status)
                    })
                }

                return response.text();
            })
            .catch((error) => {
                // if any error occurs
                console.log(error);

                Toast
                    .setMessage(error.message)
                    .error();
            })
            .finally(() => {
                isPending = false;
            });

    });

});
