<?php

namespace Modules\Order\Http\Controllers;

use App\AdminShopManage;
use Illuminate\Routing\Controller;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice;
use Modules\Order\Entities\Order;
use Modules\TaxModule\Services\CalculateTaxServices;

class InvoiceController extends Controller
{
    /**
     * @throws BindingResolutionException
     * @throws Exception
     */
    public function generateInvoice($orderId) {

        return $this->dcInvoiceMethod($orderId);
    }

    /**
     * @throws BindingResolutionException
     */
    public function downloadInvoice($orderId){
        return $this->invoiceMethod($orderId, "download");
    }

    /**
     * @throws BindingResolutionException
     * @throws Exception
     */
    private function invoiceMethod($orderId, $type = "stream"){
        $order = Order::with(["SubOrders","orderItems","orderItems.product","orderItems.variant","orderItems.variant.productColor","orderItems.variant.productSize","paymentMeta","address","address.country","address.state"])->where("id", $orderId)->first();
        $adminShop = AdminShopManage::with("logo","cover_photo")->find(1);

        $shopAddress = $adminShop->country?->name . ' , ' . $adminShop->state?->name . ' , ' . $adminShop->city . ' , ' . $adminShop->address;
        $buyer_address = $order->address?->country?->name . ' , ' . $order->address?->state?->name . ' , ' . $order->address?->city . ' , ' . $order->address?->address;

        $client = new Party([
            'name'          => $adminShop->store_name,
            'phone'         => $adminShop->number,
            'custom_fields' => [
                'email'        => $adminShop->email,
                'address' => $shopAddress,
            ],
        ]);

        $customer = new Buyer([
            'name'          => $order->address?->name ?? "",
            'phone'         => $order->address?->phone ?? "",
            'custom_fields' => [
                'email' => $order->address?->email ?? "",
                'address' => $buyer_address,
            ],
        ]);

        $items = [];

        $subTotal = $order->paymentMeta?->sub_total;
        $discountAmount = $order->paymentMeta?->coupon_amount;
        $finalSubTotal = $subTotal - $discountAmount;
        $taxPercentage = round(($order->paymentMeta?->tax_amount / $finalSubTotal) * 100,0);
        $discountPercentage = ($order->paymentMeta?->coupon_amount / $order->paymentMeta?->sub_total) * 100;

        $taxType = "";

        foreach($order->SubOrders as $subOrder){
            if($subOrder->tax_type == "inclusive_price"){
                $taxType = "Inclusive Tax";
            }
        }

        foreach($order->orderItems as $orderItem){
            $title = $orderItem->product->name;
            $unit = $orderItem->product?->uom?->unit?->name ?? '';

            if($orderItem->variant){
                $title .= PHP_EOL;
                if($orderItem->variant?->productSize){
                    $title .= " : " . $orderItem->variant?->productSize?->name;
                }

                if($orderItem->variant?->productColor){
                    $title .= " , " . $orderItem->variant?->productColor?->name;
                }

                if($orderItem->variant->attribute){
                    foreach($orderItem->variant->attribute as $attribute){
                        $title .= " , " . $attribute->attribute_name . ': ' . $attribute->attribute_value;
                    }
                }
            }

            $items[] = (new InvoiceItem())->title($title)
                ->pricePerUnit($orderItem->price)
                ->quantity($orderItem->quantity)
                ->units($unit);
        }

        $notes = str_replace("@break","<br>", get_static_option("admin_invoice_note"));


        $position = get_static_option('site_currency_symbol_position');
        if($position == 'right'){
            $currencyPosition = '{VALUE}{SYMBOL}';
        }else{
            $currencyPosition = '{SYMBOL}{VALUE}';
        }


        $invoice = Invoice::make('receipt')
            // ability to include translated invoice status
            // in case it was paid
            ->status($order->payment_status == "complete" ? "paid" : "unpaid")
            ->sequence($order->invoice_number)
            ->serialNumberFormat('{SEQUENCE}/{SERIES}')
            ->seller($client)
            ->buyer($customer)
            ->date($order->created_at)
            ->dateFormat('m/d/Y')
            ->currencySymbol(site_currency_symbol())
            ->currencyFormat($currencyPosition)
            ->currencyThousandsSeparator(',')
            ->currencyDecimalPoint('.')
            ->filename($client->name . ' ' . $customer->name)
            ->addItems($items);

        if($taxType == "Inclusive Tax"){
            $notes .= "<b>". __('All product price tax are inclusive') ."</b>";
        }else{
            $invoice->taxRate($taxPercentage);
        }

        $invoice->notes($notes)
            // ->logo(get_attachment_image_by_id(get_static_option('site_logo'))["img_url"])
            ->shipping($order->paymentMeta->shipping_cost)
            ->discountByPercent($discountPercentage);
        // You can additionally save generated invoice to configured disk

        $link = $invoice->url();
        // Then email a party with link

        // And return invoice itself to the browser or have a different view
        if($type == "stream"){
            return $invoice->stream();
        }

        return $invoice->download();
    }


    private function dcInvoiceMethod($orderId) 
    {
        $order = Order::with(["SubOrders","orderItems","orderItems.product","orderItems.variant","orderItems.variant.productColor","orderItems.variant.productSize","paymentMeta","address","address.country","address.state"])->where("id", $orderId)->first();


        // $adminShop = AdminShopManage::with("logo","cover_photo")->find(1);

        // $shopAddress = $adminShop->country?->name . ' , ' . $adminShop->state?->name . ' , ' . $adminShop->city . ' , ' . $adminShop->address;
        // $buyer_address = $order->address?->country?->name . ' , ' . $order->address?->state?->name . ' , ' . $order->address?->city . ' , ' . $order->address?->address;

    
        // $subTotal = $order->paymentMeta?->sub_total;
        // $discountAmount = $order->paymentMeta?->coupon_amount;
        // $finalSubTotal = $subTotal - $discountAmount;
        // $taxPercentage = round(($order->paymentMeta?->tax_amount / $finalSubTotal) * 100,0);
        // $discountPercentage = ($order->paymentMeta?->coupon_amount / $order->paymentMeta?->sub_total) * 100;

        return view('order::invoice', compact("order"));

    }




}