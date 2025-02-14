<style>
    .variant_info_container {
        margin-top: 55px;
    }

    .additional_variant {
        margin-top: 35px;
    }

    .add_variant_info_btn {
        align-items: center;
        margin-top: 30px;
    }

    .remove_this_variant_info_btn {
        align-items: center;
        margin-top: 30px;
    }

    .inventory_item label {
        display: block;
    }


    .inventory_items_container .inventory_item {
        border: 1px solid var(--border-color);
        padding: 20px;
        -webkit-box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .05) !important;
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .05) !important;
    }

    .inventory_items_container .inventory_item:not(:first-child) {
        margin-top: 24px;
    }

    .inventory_item .selection {
        width: 100%;
    }

    .inventory_item .select2-container {
        display: block;
        width: 100%;
    }

    .inventory_item .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: 100%;
        height: 50px;
        line-height: 50px;
    }

    .inventory_item .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #444;
        line-height: 50px;
    }

    .inventory_item .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 50px;
        position: absolute;
        top: 1px;
        right: 1px;
        width: 20px;
        line-height: 50px;
    }

    .inventory_item .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #ddd transparent transparent transparent;
        top: 25px;
    }

    .inventory_item .form-control {
        padding: 0 20px 0 20px;
    }
</style>
