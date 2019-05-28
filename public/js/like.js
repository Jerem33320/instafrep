$(function() {

    $('#post-list').on('click', (evt) => {

        // On veut sélectionner le lien <a> ayant la class "like".
        // Mais on a peut être cliqué sur un de ses enfants,
        // donc on remonte les éléments parents jusqu'a tomber
        // (éventuellement !) sur le lien <a class="like">
        $link = $(evt.target).closest('.like');

        // Si le lien est undefined, c'est qu'on a cliqué ailleurs,
        // donc on sort du gestionnaire de clic
        if (!$link) return;

        // empêche le navigateur de suivre le lien
        // (et donc de recharger la page)
        evt.preventDefault();


        const url = $link.attr('href');

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
            .then((response) => {

                const $counter = $link.next().find('strong');
                const currentCount = parseInt($counter.text());

                // when call is done
                if (response.status === 201) {

                    $link
                        .attr('href', url.replace('like', 'unlike'))
                        .find('i')
                        .removeClass('far')
                        .addClass('fas')
                        .addClass('liked');

                    $counter.text(currentCount + 1);

                } else if (response.status === 204) {

                    $link
                        .attr('href', url.replace('unlike', 'like'))
                        .find('i')
                        .addClass('far')
                        .removeClass('fas')
                        .removeClass('liked');

                    $counter.text(currentCount - 1);
                } else {
                    // ????
                }
            })
            .catch((error) => {
                // if any error occurs
            })


    });

});