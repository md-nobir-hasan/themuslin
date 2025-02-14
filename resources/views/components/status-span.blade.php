@if($status === 'draft')
    <span class="alert alert-sm alert-warning" >{{__('Draft')}}</span>
@elseif($status === 'archive')
    <span class="alert alert-sm alert-warning" >{{__('Archive')}}</span>
@elseif($status === 'pending')
    <span class="alert alert-sm alert-warning" >{{__('Pending')}}</span>
@elseif($status === 'Active')
    <span class="alert alert-sm alert-success" >{{__('Active')}}</span>
@elseif($status === 'In-Active' || $status === 'Inactive')
    <span class="alert alert-sm alert-danger" >{{__('In Active')}}</span>
@elseif($status === 'complete' || $status === 'completed')
    <span class="alert alert-sm alert-success" >{{__('Complete')}}</span>
@elseif($status === 'close')
    <span class="alert alert-sm alert-danger" >{{__('Close')}}</span>
@elseif($status === 'in_progress' || $status === 'processing')
    <span class="alert alert-sm alert-info" >{{__('In Progress')}}</span>
@elseif($status === 'publish')
    <span class="alert alert-sm alert-success" >{{__('Publish')}}</span>
@elseif($status === 'approved')
    <span class="alert alert-sm alert-success" >{{__('Approved')}}</span>
@elseif($status === 'confirm')
    <span class="alert alert-sm alert-success" >{{__('Confirm')}}</span>
@elseif($status === 'yes')
    <span class="alert alert-sm alert-success" >{{__('Yes')}}</span>
@elseif($status === 'no')
    <span class="alert alert-sm alert-danger" >{{__('No')}}</span>
@elseif($status === 'cancel' || $status === 'cancelled')
    <span class="alert alert-sm alert-danger" >{{__('Cancel')}}</span>
@elseif($status === 'failed')
    <span class="alert alert-sm alert-danger" >{{__('Failed')}}</span>
@elseif($status === 'refunded')
    <span class="alert alert-sm alert-warning" >{{__('Refunded')}}</span>
@endif
