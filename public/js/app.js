// Notification

function Toast() {

    const $toast = $('.alert');

    $toast.alert();


    function autoHide(delay = 2000) {

        setTimeout(() => {
            $toast
                .removeClass('show')
                .alert('hide');

        }, delay);
    }

    return {

        setMessage(message) {
            $toast.text(message);

            return this;
        },

        info() {
            $toast
                .removeClass('alert-warning')
                .removeClass('alert-danger')
                .removeClass('alert-success')
                .addClass('alert-info')
                .addClass('show');

            autoHide();
            return this;
        },

        success() {
            $toast
                .removeClass('alert-info')
                .removeClass('alert-warning')
                .removeClass('alert-danger')
                .addClass('alert-success')
                .addClass('show');

            autoHide();
            return this;
        },

        warning() {
            $toast
                .removeClass('alert-info')
                .removeClass('alert-danger')
                .removeClass('alert-success')
                .addClass('alert-warning')
                .addClass('show');

            autoHide();
            return this;
        },

        error() {
            $toast
                .removeClass('alert-info')
                .removeClass('alert-warning')
                .removeClass('alert-success')
                .addClass('alert-danger')
                .addClass('show');

            autoHide();
            return this;
        },

        hide() {
            $toast
                .removeClass('show')
                .alert('hide');
            return this;
        }
    }

}

Toast = Toast();