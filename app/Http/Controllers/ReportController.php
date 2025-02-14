<?php

namespace App\Http\Controllers;

use App\Exports\CustomerExport;
use App\Helpers\FlashMsg;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Product;
use Modules\Order\Entities\Order;
use App\User;
use Modules\Order\Entities\SubOrder;
use Modules\ShippingModule\Entities\ShippingAddress;

use Modules\Product\Entities\ProductAdditionalInformation;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\ProductCategory;
use Modules\Product\Entities\ProductInventory;
use Modules\Product\Entities\ProductSubCategory;
use Modules\Product\Entities\ProductTag;
use Modules\Product\Entities\Tag;
use Illuminate\Support\Facades\Validator;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockExport;
use App\Exports\SalesExport;
use Modules\Attributes\Entities\Category;
use Requests;


class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function stock()
    {

        $products = Product::with(['category', 'inventory', 'inventoryDetail'])->get();
        $categories = Category::all();


        return view('backend.report.stock', compact('products', 'categories'));
    }

    public function stockFilter(Request $request)
    {
        parse_str($request->input('data'), $parseData);

        $data = Validator::make($parseData, [
            'category' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        if ($data->fails()) {
            return response()->json(['errors' => $data->errors()], 422);
        }

        $validatedData = $data->validated();

        $products = Product::query();

        $products = $products->with(['category', 'inventory', 'inventoryDetail']);

        if (!empty($validatedData['category'])) {
            $products->whereHas('category', function ($query) use ($validatedData) {
                $query->where('categories.id', $validatedData['category']);
            });
        }

        if (!empty($validatedData['start_date'])) {
            $products->whereDate('created_at', '>=', $validatedData['start_date']);
        }

        if (!empty($validatedData['end_date'])) {
            $products->whereDate('created_at', '<=', $validatedData['end_date']);
        }

        $products = $products->get();

        return view('backend.report.stock_search', compact('products'))->render();
    }

    public function stockReport(Request $request)
    {
        // Decode the JSON products data
        $products = json_decode($request->input('products'));

        $reportData = [];

        if (empty($products)) {
            return response()->json(['error' => 'No products found.'], 400);
        }

        foreach ($products as $product) {

            if (!empty($product->inventoryDetail)) {

                foreach ($product->inventoryDetail as $variant) {
                    $reportData[] = [
                        'ID' => $product->id,
                        'Product Name' => $product->name ?? 'N/A',
                        'SKU' => $product->inventory->sku ?? 'N/A',
                        'Stock' => $variant->stock_count ?? 0,
                        'Sold' => $variant->sold_count ?? 0,
                        'Category' => $product->category->name ?? 'N/A',
                        'Sale Price' => $product->sale_price ?? 0,
                    ];
                }
            } else {
                $reportData[] = [
                    'ID' => $product->id,
                    'Product Name' => $product->name ?? 'N/A',
                    'SKU' => $product->inventory->sku ?? 'N/A',
                    'Stock' => $product->inventory->stock_count ?? 0,
                    'Sold' => $product->inventory->sold_count ?? 0,
                    'Category' => $product->category->name ?? 'N/A',
                    'Sale Price' => $product->sale_price ?? 0,
                ];
            }
        }
        // Generate the Excel file using Laravel Excel
        return Excel::download(new StockExport($reportData), 'stock_report.xlsx');
    }

    public function sales()
    {
        $orders = Order::with(['address', 'SubOrders', 'orderItems',])->get();

        return view('backend.report.sales', compact('orders'));
    }

    public function salesFilter(Request $request)
    {
        parse_str($request->input('data'), $parseData);

        // Validate incoming data
        $data = Validator::make($parseData, [
            'payment_status' => 'nullable|string',
            'order_status' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'product_name' => 'nullable|string',
            'discount' => 'nullable|string',
        ]);

        if ($data->fails()) {
            return response()->json(['errors' => $data->errors()], 422);
        }

        $validatedData = $data->validated();

        // Start building query
        $orders = Order::query();

        // Apply filtering conditions
        if (!empty($validatedData['payment_status'])) {
            $orders->where('payment_status', $validatedData['payment_status']);
        }

        if (!empty($validatedData['order_status'])) {
            $orders->where('order_status', $validatedData['order_status']);
        }

        if (!empty($validatedData['start_date'])) {
            $orders->whereDate('created_at', '>=', $validatedData['start_date']);
        }

        if (!empty($validatedData['end_date'])) {
            $orders->whereDate('created_at', '<=', $validatedData['end_date']);
        }

        if (!empty($validatedData['product_sku'])) {
            $orders->whereHas('orderItems.product', function ($query) use ($validatedData) {
                $query->where('sku', 'like', '%' . $validatedData['product_sku'] . '%');
            });
        }

        if (!empty($validatedData['discount'])) {
            if ($validatedData['discount'] == '1') {
                $orders->where('coupon_amount', '>', 0);
            } else if ($validatedData['discount'] == '2') {
                $orders->where('coupon_amount', '=', 0);
            }
        }


        // Include relationships
        $orders->with(['address', 'SubOrders', 'orderItems']);

        // Fetch the filtered data
        $orders = $orders->get();

        return view('backend.report.sales_search', compact('orders'))->render();
    }


    public function salesReport(Request $request)
    {
        // Decode the orders from JSON sent by the frontend
        $orders = json_decode($request->input('orders'));

        $reportData = [];

        // dd($orders);
        // exit();

        foreach ($orders as $order) {
            // Check if orderItems exists and is an array
            $reportData[] = [
                'Order ID' => $order->id ?? 'N/A',
                'Customer ID' => $order->user_id ?? 'N/A',
                'Invoice Number' => $order->invoice_number ?? 'N/A',
                'Customer Name' => $order->address->name ?? 'N/A',
                'Customer Email' => $order->address->email ?? 'N/A',
                'Product Name' => $order->orderItems[0]->product_name ?? 'N/A',
                'Product SKU' => $order->orderItems[0]->product_sku ?? 'N/A',
                'Quantity Sold' => $order->orderItems[0]->quantity ?? 0,
                'Product Sale Price' => $order->orderItems[0]->sale_price ?? 0,
                'Total Sale Amount' => isset($order->orderItems[0])
                    ? (floatval($order->orderItems[0]->sale_price ?? 0) * intval($order->orderItems[0]->quantity ?? 0))
                    : 0,
                'Order Date' => $order->created_at ?? 'N/A',
                'Payment Method' => $order->payment_gateway ?? 'N/A',
                'Shipping Address' => $order->address->full_address ?? 'N/A',
                'Order Status' => $order->order_status ?? 'N/A',
                'Payment Status' => $order->payment_status ?? 'N/A',
                'Discount Applied' => $order->coupon_amount ?? 0,
                'Coupon Applied' => $order->coupon ?? 'N/A',
                'Shipping Cost' => $order->shipping_cost ?? 0,
                'Total Order Value' => (floatval($order->total_amount ?? 0) + floatval($order->shipping_cost ?? 0)),
            ];
        }


        // Return the Excel report
        return Excel::download(new SalesExport($reportData), 'sales_report.xlsx');
    }


    public function customer()
    {
        $customers = User::with(['orders.subOrders', 'shippingAddress' => function ($query) {
            $query->where('default_shipping_status', 1);
        }])->get();

        return view('backend.report.customer', compact('customers'));
    }

    public function customerFilter(Request $request)
    {
        
        $customers = User::with(['orders.subOrders', 'shippingAddress' => function ($query) {
            $query->where('default_shipping_status', 1);
        }]);

        parse_str($request->input('data'), $parseData);

        $data = Validator::make($parseData, [
            'phone_number' => 'nullable|string',
            'email' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'order_count'=>'nullable|integer'
        ]);

        if ($data->fails()) {
            return response()->json(['errors' => $data->errors()], 422);
        }
        $validatedData = $data->validated();

        if (!empty($validatedData['start_date'])) {
            $customers->whereDate('created_at', '>=', $validatedData['start_date']);
        }

        if (!empty($validatedData['end_date'])) {
            $customers->whereDate('created_at', '<=', $validatedData['end_date']);
        }

        if (!empty($validatedData['phone_number'])) {
            $customers->where('phone', 'like', '%' . $validatedData['phone_number'] . '%');
        }

        if (!empty($validatedData['email'])) {
            $customers->where('email', 'like', '%' . $validatedData['email'] . '%');
        }

        if (!empty($validatedData['order_count'])) {
            $customers->whereHas('orders', function ($query) use ($validatedData) {
                $query->havingRaw('COUNT(*) >= ?', [$validatedData['order_count']]);
            });
        }        

        $customers = $customers->get();

        return view('backend.report.customer_search', compact('customers'))->render();
    }

    public function customerReport(Request $requests)
    {   

        $customers = json_decode($requests->input('customers'));

        $reportData = [];

        foreach ($customers as $customer) 
        {
            $reportData[] = [
                'Customer ID' => $customer->id,
                'First Name' => $customer->first_name ?? '-',
                'Last Name' => $customer->last_name ?? '-',
                'Full Name' => $customer->full_name ?? '-',
                'Email Address' => $customer->email ?? '-',
                'Phone Number' => $customer->phone ?? '-',
                'Registration Date' => $customer->created_at,
                'Total Orders' => $customer->order_count ?? 0,
                'Total Spent' => $customer->total_spent ?? 0,
                'Last Purchase Date' => $customer->last_order ?? 'N/A',
                'Shipping Address' =>$customer->shipping_address ?? 'N/A',
            ];
        }

        return Excel::download(new CustomerExport($reportData), 'customer_report.xlsx');
    }
}
