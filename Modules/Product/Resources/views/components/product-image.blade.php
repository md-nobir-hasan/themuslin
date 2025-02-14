<div class="image-product-wrapper">
     @if(isset($product))
          <x-media.media-upload type='vendor' :old_image="$product?->image" :title="__('Feature Image')" :name="'image_id'" :dimentions="'200x200'"/>
          <x-media.media-upload type='vendor' :gallery_image="$product?->gallery_images" :title="__('Additional Image Gallery')" :name="'product_gallery'" :dimentions="'200x200'" :multiple="true"/>
     @else
          <x-media.media-upload type='vendor' :title="__('Feature Image')" :name="'image_id'" :dimentions="'200x200'"/>
          <x-media.media-upload type='vendor' :title="__('Additional Image Gallery')" :name="'product_gallery'" :dimentions="'200x200'" :multiple="true"/>
     @endif
</div>