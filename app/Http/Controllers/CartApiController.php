<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Cart;
use App\Models\Food;
use Dotenv\Util\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        try {
            $carts = Cart::with("food")->where('user_id', $user->id)->get();

            $quantities = array();
            $foods = array();

            foreach ($carts as $cart) {
                $quantities[] = $cart->food_quantity;
                $temp = $cart->Food;
                $temp['food_id'] = $cart->food_id;
                $foods[] = $temp;
            }

            return response()->json(['success' => true, 'quantity' => $quantities, 'data' => $foods]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public static function getAllCart()
    {
        $user = auth()->user();
        try {
            $carts = Cart::where('user_id', $user->id)->get();
            return $carts;
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        try {
            $validator = Validator::make($request->all(), [
                'food_quantity' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['succcess' => false, 'message' => $validator->errors()]);
            }

            // kalo cartnya udah ada update 
            $foodExist = Cart::where('user_id', $user->id)->where('food_id', $request->food_id)->first();

            if ($foodExist) {
                $new = Cart::where('user_id', $user->id)->where('food_id', $request->food_id)->update([
                    'food_quantity' => $request->food_quantity,
                ]);

                return response()->json(['successs' => true, 'data' => $new]);
            }

            $cart = Cart::create([
                'user_id' => $user->id,
                'food_id' => $request->food_id,
                'food_quantity' => $request->food_quantity,
            ]);

            return response()->json(['success' => true, 'data' => $cart]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), $e->getCode()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(String $food_id)
    {
        $user = auth()->user();
        try {
            $cart = Cart::with('Food')->where('user_id', $user->id)->where('food_id', $food_id)->first();
            $food = $cart->Food;
            $food['quantity'] = $cart->food_quantity;

            return response()->json(['success' => true, 'data' => $food]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), $e->getCode()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $food_id)
    {
        $user = auth()->user();
        try {
            $validator = Validator::make($request->all(), [
                'food_quantity' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['succcess' => false, 'message' => $validator->errors()]);
            }

            // kalo quantitynya 0 delete 
            if ($request->food_quantity == 0) {
                Cart::where('user_id', $user->id)->where('food_id', $food_id)->delete();
            }

            Cart::where('user_id', $user->id)->where('food_id', $food_id)->update([
                'food_quantity' => $request->food_quantity,
            ]);

            $new = Cart::where('user_id', $user->id)->where('food_id', $food_id)->first();

            return response()->json(['success' => true, 'data' => $new]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), $e->getCode()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $food_id)
    {
        $user = auth()->user();
        try {
            Cart::where('user_id', $user->id)->where('food_id', $food_id)->delete();
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), $e->getCode()]);
        }
    }

    public static function removeAllCart()
    {
        $user = auth()->user();
        try {
            Cart::where('user_id', $user->id)->delete();
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), $e->getCode()]);
        }
    }
}
