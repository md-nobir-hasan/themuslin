<?php 

namespace App\Action;

use App\Shipping\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Product\Entities\SaleDetails;

class CheckoutAction {
    /**
     * @param $request
     * @param $type cheque | bank
     */
    public static function uploadCheckoutImage(Request $request, $type = 'cheque')
    {
        $folder_path = "assets/uploads/checkout/$type-images/";

        $upload_field = $type == 'cheque' ? 'cheque_payment_input' : 'bank_transfer_input';

        if ($request->hasFile($upload_field)) {
            $image = $request->$upload_field;

            $image_extension = $image->getClientOriginalExtension();
            $image_name_with_ext = $image->getClientOriginalName();

            $image_name = pathinfo($image_name_with_ext, PATHINFO_FILENAME);
            $image_name = strtolower(Str::slug($image_name));

            $image_db = $image_name . time() . '.' . $image_extension;

            $image->move($folder_path, $image_db);

            return $folder_path . $image_db;
        }
        return null;
    }

    public static function insertOrderDetails($cart_data, $cart_products, $order_id)
    {
        $order_details = [];

        foreach ($cart_data as $items) {
            foreach ($items as $item) {
                $attributes = [];
                $price = optional($cart_products->find($item['id']))->sale_price;

                if (!empty($item['attributes'])) {
                    $attributes = $item['attributes'];
                }

                if (!empty($item['id'])) {
                    $order_details[] = [
                        'item_id' => $item['id'],
                        'attributes' => json_encode($attributes),
                        'order_id' => $order_id,
                        'quantity' => $item['quantity'],
                        'price' => !empty($attributes) && isset($attributes['price']) ? $attributes['price'] : $price,
                    ];
                }
            }
        }

        SaleDetails::insert($order_details);
    }

    /**
     * If selected an existing shipping address return ShippingAddress ID
     * Else, create a ShippingAddress and return the ID
     *
     * @return int $id (\App\Shipping\ShippingAddress->id)
     * */
    public static function insertShippingAddress(Request $request): ?int
    {
        // if a saved shipping address selected
        if (!empty($request->shipping_address_id)) {
            $request->validate(['shipping_address_id' => 'required|exists:shipping_addresses,id']);
            return $request->shipping_address_id;
        }

        // if ship to another address is no selected
        if (empty($request->ship_to_another_address)) {
            return null;
        }

        $request->validate([
            'shipping_name' => 'required|string|max:191',
            'shipping_email' => 'required|string|max:191',
            'shipping_phone' => 'required|string|max:191',
            'shipping_country' => 'required|exists:countries,id',
            'shipping_state' => 'required|exists:states,id',
            'shipping_city' =>  'nullable|string|max:191',
            'shipping_zipcode' => 'nullable|string|max:191',
            'shipping_address' => 'nullable|string|max:191',
        ]);

        $shipping_address = ShippingAddress::create([
            'name' => $request->shipping_name,
            'email' => $request->shipping_email,
            'phone' => $request->shipping_phone,
            'user_id' => getUserByGuard('web')->id ?? null,
            'country_id' => $request->shipping_country,
            'state_id' => $request->shipping_state,
            'city' => $request->shipping_city,
            'zip_code' => $request->shipping_zipcode,
            'address' => $request->shipping_address,
        ]);

        if ($shipping_address) {
            return $shipping_address->id;
        }

        return null;
    }
}

