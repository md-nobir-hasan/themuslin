<style>
    .alert-success {
        border-color: #f2f2f2;
        border-left: 5px solid #319a31;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
        padding: 5px;
    }
    .alert-danger {
        border-color: #f2f2f2;
        border-left: 5px solid #c69500;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
        padding: 5px;
    }
</style>

@if($status === 0)
    <span class="alert alert-danger" >{{__('Inactive')}}</span>
@elseif($status === 1)
    <span class="alert alert-success" >{{__('Active')}}</span>
@elseif($status === 'complete')
    <span class="alert alert-success" >{{__('Complete')}}</span>
@elseif($status === 'close')
    <span class="alert alert-danger" >{{__('Close')}}</span>
@elseif($status === 'draft')
    <span class="alert alert-danger" >{{__('Draft')}}</span>
@elseif($status === 'in_progress')
    <span class="alert alert-info" >{{__('In Progress')}}</span>
@elseif($status === 'archive')
    <span class="alert alert-info" >{{__('Archive')}}</span>
@elseif($status === 'schedule')
    <span class="alert alert-warning" >{{__('Schedule')}}</span>
@elseif($status === 'publish')
    <span class="alert alert-success" >{{__('Publish')}}</span>
@elseif($status === 'confirm')
    <span class="alert alert-success" >{{__('Confirm')}}</span>
@elseif($status === 'yes')
    <span class="alert alert-success" >{{__('Yes')}}</span>
@elseif($status === 'no')
    <span class="alert alert-danger" >{{__('No')}}</span>
@elseif($status === 'cancel')
    <span class="alert alert-danger" >{{__('Cancel')}}</span>
@endif
