<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Exception;
use Illuminate\Http\Request;
use Carbon\Doctrine\CarbonType;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\String_;
use Illuminate\Support\Facades\Validator;

class TransactionDetailApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $transactionDetails = TransactionDetail::with('Food')->where('transaction_id', $request->transaction_id)->get();

            $foods = [];

            foreach ($transactionDetails as $transactionDetail) {
                $foods[] = $transactionDetail->Food;
            }

            return response()->json(['success' => true, 'data' => $transactionDetails, 'foods' => $foods]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // public function cart()
    // {
    //     $user = auth()->user();
    //     try {
    //         $carts = TransactionDetail::where('user_id', $user->id)->where('transaction_id', 'TH0001')->get();

    //         return response()->json(['success' => true, 'data' => $carts]);
    //     } catch (Exception $e) {
    //         return response()->json(['success' => false, 'message' => $e->getMessage()]);
    //     }
    // }

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
    // public function store(Request $request)
    // {
    //     $user = auth()->user();
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'food_quantity' => 'required|integer',
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json(['succcess' => false, 'message' => $validator->errors()]);
    //         }

    //         $transactionDetail = TransactionDetail::create([
    //             'transaction_id' => 'TH0001',
    //             'user_id' => $user->id,
    //             'food_id' => $request->food_id,
    //             'food_quantity' => $request->food_quantity,
    //         ]);

    //         return response()->json(['success' => true, 'data' => $transactionDetail]);
    //     } catch (Exception $e) {
    //         return response()->json(['success' => false, 'message' => $e->getMessage(), $e->getCode()]);
    //     }
    // }

    public function store(Request $request)
    {
        $user = auth()->user();
        try {
            TransactionDetail::where('transaction_id', 'TH0001')->where('user_id', $user->id)->update([
                'transaction_id' => $request->transaction_id,
            ]);

            $new = TransactionDetail::where('transaction_id', $request->transaction_id)->get();

            return response()->json(['success' => true, 'data' => $new]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), $e->getCode()]);
        }
    }

    public static function convert(string $transaction_id, string $food_id, int $food_quantity)
    {
        $user = auth()->user();
        try {
            $transactionDetail = TransactionDetail::create([
                'transaction_id' => $transaction_id,
                'food_id' => $food_id,
                'food_quantity' => $food_quantity,
            ]);
            return $transactionDetail;
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), $e->getCode()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TransactionDetail  $transactionDetail
     * @return \Illuminate\Http\Response
     */
    public function showCart(String $food_id)
    {
        $user = auth()->user();
        $cart = TransactionDetail::where('transaction_id', 'TH0001')->where('user_id', $user->id)->where('food_id', $food_id)->first();
        return response()->json(['success' => true, 'data' => $cart]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TransactionDetail  $transactionDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(TransactionDetail $transactionDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TransactionDetail  $transactionDetail
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, String $food_id)
    // {
    //     $user = auth()->user();
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'food_quantity' => 'required|integer',
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json(['succcess' => false, 'message' => $validator->errors()]);
    //         }

    //         TransactionDetail::where('user_id', $user->id)->where('food_id', $food_id)->update([
    //             'transaction_id' => $request->transaction_id == null ? 'TH0001' : $request->transaction_id,
    //             'food_quantity' => $request->food_quantity,
    //         ]);

    //         $new = TransactionDetail::where('user_id', $user->id)->where('food_id', $food_id)->first();

    //         return response()->json(['success' => true, 'data' => $new]);
    //     } catch (Exception $e) {
    //         return response()->json(['success' => false, 'message' => $e->getMessage(), $e->getCode()]);
    //     }
    // }

    // public function updateAll(Request $request)
    // {
    //     $user = auth()->user();
    //     try {
    //         TransactionDetail::where('transaction_id', 'TH0001')->where('user_id', $user->id)->update([
    //             'transaction_id' => $request->transaction_id,
    //         ]);

    //         $new = TransactionDetail::where('transaction_id', $request->transaction_id)->get();

    //         return response()->json(['success' => true, 'data' => $new]);
    //     } catch (Exception $e) {
    //         return response()->json(['success' => false, 'message' => $e->getMessage(), $e->getCode()]);
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TransactionDetail  $transactionDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(TransactionDetail $transactionDetail)
    {
        //
    }
}
