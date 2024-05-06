<script>
    document.addEventListener('livewire:initialized', () => {
        @this.on('resetForms', (event) => {
            try {
                $('form').each(function() {
                    this.reset();
                });
            } catch (error) {
                console.error('Error:' + error);
            }
        });
        @this.on('redirect', (event) => {
            const url = event[0].url;
            window.location.replace(url);
        });
        @this.on('success', (event) => {
            toastr.success(event.msg);
        });
        @this.on('error', (event) => {
            toastr.error(event.msg);
        });
        @this.on('show-modal', (event) => {
            let modal = event.modal;
            $(modal).modal('show');
        });
        @this.on('hide-modal', (event) => {
            let modal = event.modal;
            $(modal).modal('hide');
        });
        @this.on('livewire-upload-start', (event) => {
            $('input[type="submit"], button').prop('disabled', true);
        });
        @this.on('livewire-upload-finish', (event) => {
            $('input[type="submit"], button').prop('disabled', false);
        });
    });
</script>

</script>
{{-- image crop --}}
