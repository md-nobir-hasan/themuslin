
@if(Session::has('success'))
  Swal.fire({
  icon: 'success',
  title: 'Great!',
  text: '{{ Session::get("success") }}'
})
@endif
@if(Session::has('success'))
<script>
    $.toast({
        heading: 'Success',
        text: '{{session("success")}}',
        position: 'top-right',
        loaderBg:'#ff5050',
        bgColor: '#0abf53',
        icon: 'success',
        hideAfter: 5000,
        stack: 6
    });
</script>
@endif
@if(Session::has('error'))
<script>
    $.toast({
        heading: 'Error',
        text: '{{session("error")}}',
        position: 'top-right',
        loaderBg:'#ff5050',
        bgColor: '#e6294b',
        icon: 'error',
        hideAfter: 6000,
        stack: 6
    });
</script>
@endif
