<?php

namespace Modules\CountryManage\Http\Controllers\Product;

use App\Action\CartAction;
use App\Action\RegistrationAction;
use App\Events\ProductOrdered;
use App\Helpers\CartHelper;
use App\Helpers\FlashMsg;
use App\Helpers\PaymentHelper;
use App\Shipping\ShippingMethod;
use App\Shipping\UserShippingAddress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductSellInfo;
use Xgenious\Paymentgateway\Facades\XgPaymentGateway;

class ProductSellPaymentController extends Controller
{
    const SUCCESS_ROUTE = 'frontend.products.payment.success';

    const CANCEL_ROUTE = 'frontend.products.payment.cancel';

    public function checkout(Request $request)
    {
        $request->validate([
            // user info
            'name' => 'required|string|max:191',
            'email' => 'required|email',
            'country' => 'required|exists:countries,id',
            'address' => 'nullable|string|max:191',
            'city' => 'required|string|max:191',
            'state' => 'required|exists:states,id',
            'zipcode' => 'required|string',
            'phone' => 'required|string',
            'shipping_address_id' => 'nullable|string|max:191',
            'selected_shipping_option' => 'nullable|string|max:191',
            'tax_amount' => 'nullable|string|max:191',
            'coupon' => 'nullable|string|max:191',
            'bank_transfer_input' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:11000',
            'cheque_payment_input' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:11000',
            // payment info
            'selected_payment_gateway' => 'nullable|string|max:191',
            'agree' => 'required',
            // if register
            'password' => 'sometimes|nullable|min:8|confirmed',
            'create_account' => 'nullable|string|max:191',
        ], ['agree.required' => __('You need to agree to our Terms & Conditions to complete the order')]);

        // if no items in cart
        if (CartHelper::isEmpty()) {
            return back()->with(FlashMsg::explain('danger', __('An error occurred')));
        }

        // if account create
        if ($request->create_account) {
            $registration_action = new RegistrationAction();
            $user = $registration_action->register($request);
            $user_id = optional($user)->id;
        }

        // shipping address
        $address = $request->sanitize_html('address');
        if ($request->shipping_address_id) {
            $user_shipping_address = UserShippingAddress::find($request->shipping_address_id);
            $address = $user_shipping_address && strlen($user_shipping_address->address)
                        ? $user_shipping_address->address
                        : $request->sanitize_html('address');
        }

        $user_id = auth('web')->check() ? auth('web')->id() : null;

        // calculate product and coupon prices
        $default_shipping_cost = CartAction::getDefaultShippingCost();

        $all_cart_items = CartHelper::getItems();

        $products = Product::whereIn('id', array_keys($all_cart_items))->get();

        $subtotal = CartAction::getCartTotalAmount($all_cart_items, $products);

        $coupon_amount = CartAction::calculateCoupon($request, $subtotal, $products, 'DISCOUNT');

        $selected_shipping_cost = $default_shipping_cost;

        $shipment_tax_applicable = false;

        // if user selected a shipping option
        if (isset($request->selected_shipping_option)) {
            $shipping_is_valid = CartAction::validateSelectedShipping($request->selected_shipping_option, $request->coupon);

            if (! $shipping_is_valid) {
                $shipping_method = ShippingMethod::with('availableOptions')->find($request->selected_shipping_option);

                if (is_null($shipping_method)) {
                    return back()->with(FlashMsg::explain('danger', __('Please select valid shipping option')))->withInput();
                }

                if (isset(optional($shipping_method)->availableOptions)) {
                    $minimum_order_amount = optional(optional($shipping_method)->availableOptions)->minimum_order_amount ?? 0;
                    $minimum_order_amount = float_amount_with_currency_symbol($minimum_order_amount);

                    $message = __('Minimum total order amount has to be')
                                .' '.$minimum_order_amount;

                    if (optional(optional($shipping_method)->availableOptions)->setting_preset === 'min_order_or_coupon') {
                        $message .= ' '.__('or a valid coupon has to be given.');
                    } elseif (optional(optional($shipping_method)->availableOptions)->setting_preset === 'min_order_and_coupon') {
                        $message .= ' '.__('and a valid coupon has to be given.');
                    }

                    return back()->with(FlashMsg::explain('danger', $message))->withInput();
                }

                return back()->with(FlashMsg::explain('danger', __('Please select valid shipping option')))->withInput();
            }

            $shipping_info = CartAction::getSelectedShippingCost($request->selected_shipping_option, $subtotal, $request->coupon);
            $selected_shipping_cost = $shipping_info['cost'];
            $shipment_tax_applicable = $shipping_info['is_taxable'];
        }

        $checkout_image_path = '';

        // check if shipping is taxable
        if ($shipment_tax_applicable) {
            // if shipment is taxable (is_taxable), calculate tax with shipping
            $subtotal_with_shipping = $subtotal + $selected_shipping_cost;
            $tax_amount = CartAction::getCheckoutTaxAmount($subtotal_with_shipping, $request->country, $request->state);
        } else {
            // else, only calculate subtotal
            $tax_amount = CartAction::getCheckoutTaxAmount($subtotal, $request->country, $request->state);
        }

        $total = $subtotal + $selected_shipping_cost + $tax_amount - $coupon_amount;

        $payment_meta = [
            'total' => (string) round($total, 2),
            'subtotal' => (string) round($subtotal, 2),
            'shipping_cost' => (string) round($selected_shipping_cost, 2),
            'tax_amount' => (string) round($tax_amount, 2),
            'coupon_amount' => (string) round($coupon_amount, 2),
        ];

        $product_sell_info = [
            // user
            'name' => $request->sanitize_html('name'),
            'email' => $request->sanitize_html('email'),
            'user_id' => $user_id,
            // billing address
            'country' => $request->sanitize_html('country'),
            'address' => $address,
            'city' => $request->sanitize_html('city'),
            'state' => $request->sanitize_html('state'),
            'zipcode' => $request->sanitize_html('zipcode'),
            'phone' => $request->sanitize_html('phone'),
            // shipping address
            'shipping_address_id' => $request->sanitize_html('shipping_address_id') ?? '',
            'selected_shipping_option' => $selected_shipping_cost,
            // product
            'coupon' => $request->coupon,
            'coupon_discounted' => $payment_meta['coupon_amount'],
            'total_amount' => $payment_meta['total'],
            'order_details' => json_encode($all_cart_items),
            'payment_meta' => json_encode($payment_meta),
            // payment
            'payment_gateway' => $request->sanitize_html('selected_payment_gateway'),
            'payment_track' => Str::random(10).Str::random(10),
            'transaction_id' => Str::random(10).Str::random(10),
            'payment_status' => 'pending',
            'status' => 'pending',

            'checkout_image_path' => $checkout_image_path,
        ];

        $product_sell_info = ProductSellInfo::create($product_sell_info);

        CartAction::storeItemSoldCount($all_cart_items, $products);

        try {
            return PaymentHelper::chargeCustomer($product_sell_info, $request);
        } catch (\Exception $e) {
            return back()->with(['msg' => $e->getMessage(), 'type' => 'danger']);
        }
    }

    public function checkoutContinuePending(Request $request)
    {
        $request->validate([
            'bank_transfer_input' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:11000',
            'cheque_payment_input' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:11000',
        ]);

        $product_sell_info = ProductSellInfo::findOrFail($request->id);

        return PaymentHelper::chargeCustomer($product_sell_info, $request);
    }

    public function cancelPayment(Request $request)
    {
        $product_sell_info = ProductSellInfo::findOrFail($request->id);
        $product_sell_info->update([
            'status' => 'canceled',
            'payment_status' => 'canceled',
        ]);

        return true;
    }

    public function reorder(Request $request)
    {
        $product_sell_info = ProductSellInfo::findOrFail($request->id);
        $new_sell = $product_sell_info->replicate();
        $new_sell->created_at = Carbon::now();
        $new_sell->updated_at = Carbon::now();
        $new_sell->status = 'pending';
        $new_sell->payment_status = 'pending';
        $new_sell->payment_track = Str::random(10).Str::random(10);
        $new_sell->transaction_id = Str::random(10).Str::random(10);
        $new_sell->save();

        return redirect()->to(route('user.product.order.details', $new_sell->id));
    }

    private function returnAppropriateRedirect($payment_data)
    {
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            event(new ProductOrdered([
                'order_id' => $payment_data['order_id'],
                'transaction_id' => $payment_data['transaction_id'],
            ]));

            return redirect()->route(self::SUCCESS_ROUTE, Str::random(6).$payment_data['order_id'].Str::random(6));
        }

        return redirect()->route(self::CANCEL_ROUTE, Str::random(6));
    }

    /** ===============================================================================
     *                      IPN/WEBHOOK FUNCTIONS
      =============================================================================== */
    public function paypal_ipn(Request $request)
    {
        $payment_data = XgPaymentGateway::paypal()->ipn_response();

        return $this->returnAppropriateRedirect($payment_data);
    }

    public function razorpay_ipn(Request $request)
    {
        $payment_data = XgPaymentGateway::razorpay()->ipn_response();

        return $this->returnAppropriateRedirect($payment_data);
    }

    public function paytm_ipn(Request $request)
    {
        $payment_data = XgPaymentGateway::paytm()->ipn_response();

        return $this->returnAppropriateRedirect($payment_data);
    }

    public function mollie_ipn()
    {
        $payment_data = XgPaymentGateway::mollie()->ipn_response();

        return $this->returnAppropriateRedirect($payment_data);
    }

    public function stripe_ipn(Request $request)
    {
        $payment_data = XgPaymentGateway::stripe()->ipn_response();

        return $this->returnAppropriateRedirect($payment_data);
    }

    public function flutterwave_ipn(Request $request)
    {
        $payment_data = XgPaymentGateway::flutterwave()->ipn_response();

        return $this->returnAppropriateRedirect($payment_data);
    }

    public function paystack_ipn(Request $request)
    {
        $payment_data = XgPaymentGateway::paystack()->ipn_response();

        return $this->returnAppropriateRedirect($payment_data);
    }

    public function midtrans_ipn()
    {
        $payment_data = XgPaymentGateway::midtrans()->ipn_response();

        return $this->returnAppropriateRedirect($payment_data);
    }

    public function payfast_ipn()
    {
        $payment_data = XgPaymentGateway::payfast()->ipn_response();

        return $this->returnAppropriateRedirect($payment_data);
    }

    public function cashfree_ipn(Request $request)
    {
        $payment_data = XgPaymentGateway::cashfree()->ipn_response();

        return $this->returnAppropriateRedirect($payment_data);

    }

    public function instamojo_ipn(Request $request)
    {
        $payment_data = XgPaymentGateway::instamojo()->ipn_response();

        return $this->returnAppropriateRedirect($payment_data);
    }

    public function marcadopago_ipn(Request $request)
    {
        $payment_data = XgPaymentGateway::marcadopago()->ipn_response();

        return $this->returnAppropriateRedirect($payment_data);
    }
}
