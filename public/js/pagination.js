$(function() {

    const $list = $('#post-list');
    const $loader = $(`
        <div class="card loader">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>    
    `);


    let nextPage = 2;
    let isLoading = false;
    let isDone = false;

    $('#pagination-next').on('click', (e) => {
        e.preventDefault();

        if (isLoading || isDone) return;
        isLoading = true;
        $list.append($loader);

        fetch(`/?p=${nextPage}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
            .then((response) => {
                return response.text();
            })
            .then((bodyText) => {
                $list.children().last().remove();

                if (bodyText) {
                    $list.append(bodyText);
                    nextPage++;
                } else {
                    isDone = true;
                }
            })
            // .catch( ... )
            .finally(() => {
                isLoading = false;
            })
        ;

    });


});