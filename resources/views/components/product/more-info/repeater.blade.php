<div class="additional_info_repeater">
    <div class="form-row">
        <div class="col">
            <input type="text" name="info_title[]" class="form-control" placeholder="{{ __('Info Title') }}" @if(isset($infoTitle)) value="{{ $infoTitle }}" @endif>
        </div>
        <div class="col">
            <input type="text" name="info_text[]" class="form-control" placeholder="{{ __('Info Text') }}" @if(isset($infoText)) value="{{ $infoText }}" @endif>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-sm btn-success add_more_info_btn"> + </button>
            <button type="button" class="btn btn-sm btn-danger remove_this_info_btn" @if(isset($isFirst) && $isFirst) disabled @endif > - </button>
        </div>
    </div>
</div>
