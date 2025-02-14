@php
    if (isset($permissions) && !auth('admin')->user()->can($permissions)){
        return;
    }
@endphp


<div class="bulk-delete-wrapper d-flex mt-3">
    <select name="bulk_option" id="bulk_option" >
        <option value="">{{{__('Bulk Action')}}}</option>
        <option value="delete">{{{__('Delete')}}}</option>
    </select>

    <button class="btn btn-primary " id="bulk_delete_btn">{{__('Apply')}}</button>
</div>