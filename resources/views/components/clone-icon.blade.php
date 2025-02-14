<form action="{{ $action }}" method="post" style="display: inline-block">
    @csrf
    <input type="hidden" name="item_id" value="{{ $id }}">
    <button type="submit" title="clone this to new draft" class="btn btn-xs btn-secondary btn-sm mb-2 me-1"><i
            class="las la-copy"></i></button>
</form>
