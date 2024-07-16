<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>

<script>
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        window.addEventListener('livewire:navigated', () => {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
        $(".lang").on("click", function() {
            let lang = $(this).data("lang");
            // return alert(lang);
            $.ajax({
                url: "{{ route('lang') }}",
                type: "POST",
                data: {
                    lang: lang
                },
                success: function() {
                    location.reload();
                },
                error: function(error) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('lang') }}",
                        type: "POST",
                        data: {
                            lang: lang
                        },
                        success: function() {
                            location.reload();
                        },
                        error: function() {
                            alert("error again")
                        }
                    })
                }
            })
        })
    })
</script>
{{ $script }}
</body>

</html>
