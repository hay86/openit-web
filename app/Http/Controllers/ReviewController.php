<?php
/**
 * Created by PhpStorm.
 * User: xukf
 * Date: 28/05/2017
 * Time: 9:07 AM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Review;
use App\Coupon;
use App\Order;
use Auth;
use Session;

class ReviewController extends Controller
{
    const ORDER_ID_START = 100000000000000;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $title = null;
        $review = null;

        if ($id >= self::ORDER_ID_START) {
            $order = Order::find($id);
            if (!empty($order)) {
                $title = $order->product->displayName;
                $review = Review::where('user_id', $user->id)->where('order_id', $id)->first();
            }
        }
        else {
            $product = Product::withTrashed()->find($id);
            if (!empty($product)) {
                $title = $product->displayName;
                $review = Review::where('user_id', $user->id)->where('product_id', $id)->first();
            }
        }

        return view('review.show', ['title' => $title, 'id' => $id, 'review' => $review]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'score'     => 'required|integer|min:-1|max:1',
            'review'    => 'nullable|string',
        ]);

        $user = Auth::user();
        $title = null;
        $order = null;
        $product = null;

        if ($id >= self::ORDER_ID_START) {
            $order = Order::find($id);
            if (!empty($order)) {
                $title = $order->product->displayName;
                $review = Review::where('user_id', $user->id)->where('order_id', $id)->first();
            }
        }
        else {
            $product = Product::withTrashed()->find($id);
            if (!empty($product)) {
                $title = $product->displayName;
                $review = Review::where('user_id', $user->id)->where('product_id', $id)->first();
            }
        }

        if (empty($review)) {
            $discount = $order ? [15,20] : [0,0,5,10];
            $discount = $discount[array_rand($discount)];

            if ($discount > 0) {
                $coupon_id = rand_token(8);

                for ($i=0; $i<3; $i++) {
                    if (empty(Coupon::find($coupon_id))) break;
                    $coupon_id = rand_token(8);
                }

                $coupon = new Coupon;
                $coupon->id = $coupon_id;
                $coupon->discount = $discount;
                $coupon->expired_at = date('Y-m-d', strtotime('+1 month'));
                $coupon->user_id = $user->id;
                $coupon->save();
            }

            $review = new Review;
            $review->score = $request->score;
            $review->prize = $discount;
            $review->review = $request->review;
            $review->product_id = $product ? $product->id : null;
            $review->order_id = $order ? $order->id : null;
            $review->user_id = $user->id;
            $review->save();
        }

        return view('review.show', ['title' => $title, 'id' => $id, 'review' => $review]);
    }
}