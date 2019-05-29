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

    function loadPosts() {
        if (isLoading || isDone) return;
        isLoading = true;
        $list.append($loader);

        fetch(`/?p=${nextPage}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
            .then((response) => {

                if (parseInt(response.headers.get('X-Infrep-Is-Last-Page'))) {
                    isDone = true;
                }

                return response.text();
            })
            .then((bodyText) => {
                $list.children().last().remove();

                $list.append(bodyText);
                nextPage++;
            })
            // .catch( ... )
            .finally(() => {
                isLoading = false;
            })
        ;
    }

    $('#pagination-next').on('click', (e) => {
        e.preventDefault();
        loadPosts();
    });


});