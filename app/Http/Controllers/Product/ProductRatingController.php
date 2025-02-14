<?php

namespace Modules\CountryManage\Http\Controllers\Product;

use App\Helpers\FlashMsg;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\ProductRating;

class ProductRatingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:product-rating-list|product-rating-create|product-rating-edit|product-rating-delete', ['only', ['index']]);
        $this->middleware('permission:product-rating-create', ['only', ['store']]);
        $this->middleware('permission:product-rating-edit', ['only', ['update']]);
        $this->middleware('permission:product-rating-delete', ['only', ['destroy', 'bulk_action']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_ratings = ProductRating::with(['user', 'product'])->get();

        return view('backend.rating.all', compact('all_ratings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth('web')->user();
        if (! $user) {
            return back()->with(FlashMsg::explain('danger', __('Login to submit rating')));
        }

        $request->validate([
            'id' => 'required|exists:products',
            'rating' => 'required|string',
            'comment' => 'required|string',
        ]);

        $rating = abs($request->rating) == 0 ? 1 : abs($request->rating);

        if ($request->rating > 5) {
            $rating = 5;
        }

        // ensure rating not inserted before
        $user_rated_already = (bool) ProductRating::where('product_id', $request->id)->where('user_id', $user->id)->count();
        if ($user_rated_already) {
            return back()->with(FlashMsg::explain('danger', __('You have rated before')));
        }

        $rating = ProductRating::create([
            'product_id' => $request->id,
            'user_id' => $user->id,
            'status' => 0,
            'rating' => $rating,
            'review_msg' => $request->comment,
        ]);

        return $rating->id
            ? back()->with(FlashMsg::create_succeed('rating'))
            : back()->with(FlashMsg::create_failed('rating'));
    }

    public function approve(Request $request)
    {
        $request->validate(['id' => 'required|exists:product_ratings']);
        $product_rating = ProductRating::where('id', $request->id)->first();

        $updated = false;

        if (! is_null($product_rating)) {
            $updated = $product_rating->update(['status' => 1]);
        }

        return response()->json([
            'type' => $updated ? 'success' : 'error',
            'msg' => $updated ? __('Rating approved') : __('Something went wrong'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Modules\Product\Entities\ProductRating  $productRating
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductRating $rating)
    {
        if ($rating->delete()) {
            return back()->with(FlashMsg::delete_succeed('Rating'));
        }

        return back()->with(FlashMsg::delete_failed('Rating'));
    }

    public function bulk_action(Request $request)
    {
        ProductRating::whereIn('id', $request->ids)->delete();

        return 'ok';
    }
}
