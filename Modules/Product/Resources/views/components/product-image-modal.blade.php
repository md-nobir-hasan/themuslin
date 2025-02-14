<!-- Modal -->
<div class="modal fade" id="mediaUpdateModalId" tabindex="-1" aria-labelledby="mediaUpdateModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="" class="change-product-image-form">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="mediaUpdateModal">{{ __("Change product image") }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="product-id" name="product_id"/>

                    <x-media-upload name="image_id" :title="__('Select product image')"/>
                    <x-media-upload name="product_gallery" :multiple="true"
                                    :title="__('Select product gallery image')"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Close") }}</button>
                    <button type="button"
                            class="btn btn-primary change-product-image-button">{{ __("Update Product Image") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
