<script>
    $(function() {
        @if (Session::has('success'))
            Swal.fire({
            icon: 'success',
            toast:true,
            title: 'Great!',
            text: '{{ Session::get('success') }}'
            })
        @endif

        @if (Session::has('error'))
            Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ Session::get('error') }}'
            })
        @endif

        @if (Session::has('warning'))
            Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: '{{ Session::get('warning') }}'
            })
        @endif
    });
</script>
