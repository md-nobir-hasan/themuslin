<style>
    .alert-success {
        border-color: #f2f2f2;
        border-left: 5px solid #319a31;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
    }
    .alert-danger {
        border-color: #f2f2f2;
        border-left: 5px solid darkred;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
    }
</style>

@if($status == 'default')
    <span class="alert alert-success">{{__('Default Menu')}}</span>
@else
    <form action="{{route('admin.menu.default',$menuID)}}" method="post">
        @csrf
        <button type="submit" class="alert alert-danger set_default_menu">{{__('Set Default')}}</button>
    </form>
@endif
