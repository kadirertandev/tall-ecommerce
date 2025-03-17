<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>

{{ $slot }}

</body>

</html>
<script>
    Alpine.store('modal', {
        closeModal(modalName) {
            this.visibleModals.delete(modalName)

            this.$dispatch('modal-closed', {
                modalName
            })
        },

        visibleModals: new Set(),

        removeLastAddedModal() {
            let lastModal = Array.from(this.visibleModals).pop();

            this.visibleModals.delete(lastModal);

            this.$dispatch('modal-closed', {
                modalName: lastModal
            })
        }
    })
</script>
