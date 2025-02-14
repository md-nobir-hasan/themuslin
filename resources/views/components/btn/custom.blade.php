$(document).on('click','#{{$id}}',function () {
    $(this).addClass("disabled")
    $(this).html('<i class="fas fa-spinner fa-spin mr-1"></i> {{$title}}');
});